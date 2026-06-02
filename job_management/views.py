# job_management/views.py
from django.shortcuts import render
from django.contrib.auth.decorators import login_required
from django.db import connection, connections


@login_required
def pos_overview(request):

    # Main query: pos + posummary + barcode/carelabel stocks
    with connection.cursor() as cursor:
        cursor.execute("""
            SELECT
                posum.pro                       AS posumm_pro,
                posum.qty                       AS posumm_qty,
                pos.po,
                pos.po_new,
                pos.total_order_qty,
                ISNULL(bs.stock_b, 0)           AS qty_b_printed,
                ISNULL(cs.stock_c, 0)           AS qty_c_printed
            FROM pos

            LEFT JOIN [172.27.161.200].[posummary].[dbo].[pro] AS posum
                ON SUBSTRING(posum.pro, 3, 7) = pos.po
                AND posum.status_int = 'OPEN'

            LEFT JOIN (
                SELECT po_id, SUM(qty) AS stock_b
                FROM barcode_stocks
                GROUP BY po_id
            ) AS bs ON bs.po_id = pos.id

            LEFT JOIN (
                SELECT po_id, SUM(qty) AS stock_c
                FROM carelabel_stocks
                GROUP BY po_id
            ) AS cs ON cs.po_id = pos.id

            WHERE pos.closed_po = 'Open'
            ORDER BY pos.po ASC
        """)
        rows = cursor.fetchall()
        columns = [col[0] for col in cursor.description]
        pos_list = [dict(zip(columns, row)) for row in rows]

    # Inteos query: box qty per POnum
    with connections['inteos_db'].cursor() as cursor:
        cursor.execute("""
            WITH Delivered AS (
                SELECT
                    RIGHT('000000' + CAST(po AS VARCHAR(50)), 6) AS po6,
                    SUM(qty) AS delivered_qty
                FROM [172.27.161.200].[bbStock].[dbo].[bbStock]
                WHERE status = 'DELIVERED'
                GROUP BY RIGHT('000000' + CAST(po AS VARCHAR(50)), 6)
            ),
            MainData AS (
                SELECT
                    PO.POnum,
                    SUM(BB.BoxQuant) AS SumSlusBoxSquant,
                    CASE
                        WHEN PO.POClosed = 0 THEN 'Open'
                        WHEN PO.POClosed IS NULL THEN 'Open'
                        WHEN PO.POClosed = 1 THEN 'Closed'
                    END AS status_int,
                    DL.delivered_qty AS delivered
                FROM CNF_PO AS PO
                INNER JOIN CNF_BlueBox AS BB
                    ON PO.INTKEY = BB.IntKeyPO
                LEFT JOIN Delivered DL
                    ON RIGHT(PO.POnum, 6) = DL.po6
                WHERE
                    PO.POnum LIKE '000%'
                    AND BB.Bagno != 'LOST'
                    AND ISNULL(PO.POClosed, 0) != 1
                GROUP BY PO.POnum, PO.POClosed, DL.delivered_qty

                UNION ALL

                SELECT
                    PO.POnum,
                    SUM(BB.BoxQuant) AS SumSlusBoxSquant,
                    CASE
                        WHEN PO.POClosed = 0 THEN 'Open'
                        WHEN PO.POClosed IS NULL THEN 'Open'
                        WHEN PO.POClosed = 1 THEN 'Closed'
                    END AS status_int,
                    DL.delivered_qty AS delivered
                FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_PO] AS PO
                INNER JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_BlueBox] AS BB
                    ON PO.INTKEY = BB.IntKeyPO
                LEFT JOIN Delivered DL
                    ON RIGHT(PO.POnum, 6) = DL.po6
                WHERE
                    PO.POnum LIKE '000%'
                    AND BB.Bagno != 'LOST'
                    AND ISNULL(PO.POClosed, 0) != 1
                GROUP BY PO.POnum, PO.POClosed, DL.delivered_qty
            )
            SELECT POnum, SumSlusBoxSquant FROM MainData
        """)
        inteos_rows = cursor.fetchall()

    # RIGHT(POnum, 7) = po_new
    inteos_dict = {str(ponum or '')[-7:]: (box_qty or 0) for ponum, box_qty in inteos_rows}

    # Truck/cutting query — runs on posummary_db (172.27.161.200)
    with connections['posummary_db'].cursor() as cursor:
        cursor.execute("""
            SELECT
                posum.pro,
                ISNULL((
                    SELECT SUM(mp.pro_pcs_actual)
                    FROM [cutting].[dbo].[mattress_pros] AS mp
                    JOIN [cutting].[dbo].[pro_skedas] AS ps ON ps.pro_id = mp.pro_id
                    JOIN [cutting].[dbo].[mattresses] AS m ON m.id = mp.mattress_id
                    JOIN [cutting].[dbo].[mattress_phases] AS p ON p.mattress_id = m.id
                    WHERE p.active = 1
                      AND p.status IN ('TO_LOAD','TO_SPREAD','TO_CUT','ON_CUT')
                      AND p.location IN ('SP1','SP2','SP3','SP4','CUT','MS1','TUB')
                      AND m.skeda_item_type NOT IN ('MW','MB')
                      AND RIGHT(m.skeda, 1) != 'B'
                      AND ps.pro = posum.pro
                ), 0) AS qty_to_cut,
                ISNULL((
                    SELECT SUM(qty)
                    FROM [bbStock].[dbo].[bbStock]
                    WHERE SUBSTRING(bbname, 4, 9) = posum.pro
                      AND location = 'JUST_CUT'
                ), 0) AS qty_bb_su
            FROM [posummary].[dbo].[pro] AS posum
            WHERE posum.status_int = 'Open'
              AND posum.location_all IN ('Kikinda', 'Senta')
            ORDER BY posum.pro
        """)
        truck_rows = cursor.fetchall()

    # bbStock delivered/wip/finishing — po_new = SUBSTRING(bbname, 6, 7)
    with connections['bbstock_db'].cursor() as cursor:
        cursor.execute("""
            SELECT DISTINCT SUBSTRING([bbname], 6, 7)
            FROM [bbStock]
            WHERE status IN ('DELIVERED', 'WIP', 'FINISHING')
        """)
        bb_set = {row[0] for row in cursor.fetchall() if row[0]}

    # Aggregate by RIGHT(pro, 7) = po_new, skip rows where total_to_send = 0
    truck_dict = {}
    for pro, qty_to_cut, qty_bb_su in truck_rows:
        total = (qty_to_cut or 0) + (qty_bb_su or 0)
        if total == 0:
            continue
        key = str(pro or '')[-7:]
        if key in truck_dict:
            truck_dict[key]['will_be_cut'] += (qty_to_cut or 0)
            truck_dict[key]['total_to_send'] += total
        else:
            truck_dict[key] = {
                'will_be_cut': (qty_to_cut or 0),
                'total_to_send': total,
            }

    min_req = 20

    for pos in pos_list:
        po_new = str(pos.get('po_new') or '')
        pos['box_qty'] = inteos_dict.get(po_new, '-')
        posumm_val = pos.get('posumm_qty') or 0
        box_val = pos['box_qty'] if isinstance(pos['box_qty'], (int, float)) else 0
        pos['total_target_qty'] = max(posumm_val, box_val)
        pos['c_delta'] = box_val - (pos.get('qty_c_printed') or 0)
        pos['b_delta'] = box_val - (pos.get('qty_b_printed') or 0)
        match = truck_dict.get(po_new)
        if_pro_is_in_pkl_sugg = 'Yes' if match else 'No'
        if_po_has_bb_in_del_wip_fin = 'Yes' if po_new in bb_set else 'No'
        pos['if_pro_is_in_pkl_sugg'] = if_pro_is_in_pkl_sugg
        pos['if_po_has_bb_in_del_wip_fin'] = if_po_has_bb_in_del_wip_fin
        pos['will_be_cut'] = int(match['will_be_cut']) if match else '-'
        pos['total_to_send'] = int(match['total_to_send']) if match else '-'
        cut_val = int(match['will_be_cut']) if match else 0
        pos['c_priority'] = max(0, cut_val + pos['c_delta'])
        pos['b_priority'] = max(0, cut_val + pos['b_delta'])
        target = pos['total_target_qty']
        pos['c_priority4'] = max(0, target - (pos.get('qty_c_printed') or 0))
        pos['b_priority4'] = max(0, target - (pos.get('qty_b_printed') or 0))

        def calc_priority(p123, p4):
            if p123 > min_req:
                if if_pro_is_in_pkl_sugg == 'Yes':
                    return 'Priority 1'
                elif if_po_has_bb_in_del_wip_fin == 'Yes':
                    return 'Priority 2'
                else:
                    return 'Priority 3'
            else:
                if p4 > min_req:
                    return 'Priority 4'
                else:
                    return 'Nothing'

        pos['c_prio_label'] = calc_priority(pos['c_priority'], pos['c_priority4'])
        pos['b_prio_label'] = calc_priority(pos['b_priority'], pos['b_priority4'])

    return render(request, 'job_management/pos_overview.html', {'pos_list': pos_list})
