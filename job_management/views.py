# job_management/views.py
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.http import JsonResponse
from django.db import connection, connections


def _build_pos_list():
    with connection.cursor() as cursor:
        cursor.execute("""
            SELECT
                posum.pro                       AS posumm_pro,
                posum.qty                       AS posumm_qty,
                posum.id                        AS posumm_pro_id,
                posum.location_all              AS posumm_location,
                posum.skeda                     AS posumm_skeda,
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

    inteos_dict = {str(ponum or '')[-7:]: (box_qty or 0) for ponum, box_qty in inteos_rows}

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

    with connections['bbstock_db'].cursor() as cursor:
        cursor.execute("""
            SELECT DISTINCT SUBSTRING([bbname], 6, 7)
            FROM [bbStock]
            WHERE status IN ('DELIVERED', 'WIP', 'FINISHING')
        """)
        bb_set = {row[0] for row in cursor.fetchall() if row[0]}

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

    return pos_list


@login_required
def pos_overview(request):
    pos_list = _build_pos_list()
    return render(request, 'job_management/pos_overview.html', {'pos_list': pos_list})


@login_required
def job_items(request):
    from core.models import JobManagementItem, Operator
    PRIORITY_ORDER = {'Priority 1': 1, 'Priority 2': 2, 'Priority 3': 3, 'Priority 4': 4}
    items = sorted(
        JobManagementItem.objects.all(),
        key=lambda x: PRIORITY_ORDER.get(x.priority, 99)
    )
    operators = Operator.objects.filter(status='ACTIVE').order_by('operator_name')
    return render(request, 'job_management/job_items.html', {
        'items': items,
        'operators': operators,
        'prio1_count': sum(1 for i in items if i.priority == 'Priority 1'),
        'prio2_count': sum(1 for i in items if i.priority == 'Priority 2'),
        'prio3_count': sum(1 for i in items if i.priority == 'Priority 3'),
        'prio4_count': sum(1 for i in items if i.priority == 'Priority 4'),
    })


@login_required
def assign_operator(request):
    if request.method != 'POST':
        return JsonResponse({'error': 'POST required'}, status=405)
    from core.models import JobManagementItem
    import json
    data = json.loads(request.body)
    item_id = data.get('item_id')
    operator_name = data.get('operator_name')
    try:
        item = JobManagementItem.objects.get(id=item_id)
        item.operator = operator_name
        item.status = 'ASSIGNED'
        item.save()
        return JsonResponse({'ok': True, 'operator': operator_name, 'status': item.status})
    except JobManagementItem.DoesNotExist:
        return JsonResponse({'error': 'Not found'}, status=404)


@login_required
def operators_list(request):
    from core.models import Operator
    operators = Operator.objects.all().order_by('operator_name')
    return render(request, 'job_management/operators.html', {'operators': operators})


@login_required
def send_to_stock(request, item_id):
    from core.models import JobManagementItem, JobManagementItemLog, Pos, BarcodeStocks, CarelabelStocks

    try:
        item = JobManagementItem.objects.get(id=item_id, status='ASSIGNED')
    except JobManagementItem.DoesNotExist:
        messages.error(request, 'Item not found or not in ASSIGNED status.')
        return redirect('job_management:job_items')

    # Find the local Pos record via po_new
    pos_obj = None
    try:
        pos_obj = Pos.objects.get(po_new=item.pro_new)
    except (Pos.DoesNotExist, Pos.MultipleObjectsReturned):
        pass

    if request.method == 'POST':
        qty = int(request.POST.get('qty') or 0)
        qty_waste = int(request.POST.get('qty_waste') or 0)
        machine = request.POST.get('machine') or request.POST.get('machine_c') or ''
        comment = request.POST.get('comment', '')

        if item.print_type == 'BARCODE' and pos_obj:
            BarcodeStocks.objects.create(
                po_id=pos_obj.id, user_id=request.user.id,
                ponum=pos_obj.po, size=pos_obj.size,
                qty=qty, qty_waste=qty_waste,
                type='new', comment=comment, machine=machine,
            )
        elif item.print_type == 'CARELABEL' and pos_obj:
            CarelabelStocks.objects.create(
                po_id=pos_obj.id, user_id=request.user.id,
                ponum=pos_obj.po, size=pos_obj.size,
                qty=qty, type='new', comment=comment, machine=machine,
            )

        JobManagementItemLog.objects.create(
            pro=item.pro, pro_new=item.pro_new, pro_id=item.pro_id,
            location=item.location, skeda=item.skeda,
            print_type=item.print_type, pro_print_type=item.pro_print_type,
            qty=qty, priority=item.priority,
            status='SENT_TO_STOCK', operator=item.operator,
            created_new_at=item.created_at,
            assigned_at=item.updated_at,
        )
        item.delete()

        messages.success(request, f'Job {item.pro} ({item.print_type}) moved to log.')
        return redirect('job_management:job_items')

    return render(request, 'job_management/send_to_stock.html', {
        'item': item,
        'pos_obj': pos_obj,
        'barcode_machines': ['AUTOTEX', 'SGF', 'NOVEXX', 'NOVEXX 90deg', 'ZEBRA 600'],
    })


@login_required
def operator_add(request):
    from core.models import Operator
    if request.method == 'POST':
        name = request.POST.get('operator_name', '').strip()
        if name:
            Operator.objects.create(operator_name=name, status='ACTIVE')
    return redirect('job_management:operators_list')


@login_required
def operator_edit(request, pk):
    from core.models import Operator
    if request.method == 'POST':
        op = Operator.objects.get(pk=pk)
        op.status = request.POST.get('status', op.status)
        op.save()
    return redirect('job_management:operators_list')


@login_required
def apply_jobs(request):
    if request.method != 'POST':
        return redirect('job_management:pos_overview')

    from core.models import JobManagementItem, JobManagementItemLog

    # Step 1: delete all NEW items — they will be re-evaluated fresh
    deleted_count, _ = JobManagementItem.objects.filter(status='NEW').delete()

    # Step 2: collect pro_print_type keys that still exist (non-NEW statuses)
    existing_keys = set(JobManagementItem.objects.values_list('pro_print_type', flat=True))

    pos_list = _build_pos_list()

    saved = 0
    for pos in pos_list:
        pro = pos.get('posumm_pro')
        if not pro:
            continue

        pro_new = pos.get('po_new') or ''
        pro_id = pos.get('posumm_pro_id')
        location = pos.get('posumm_location')
        skeda = pos.get('posumm_skeda')

        for print_type in ['CARELABEL', 'BARCODE']:
            pro_print_type_key = f"{pro}_{print_type}"

            # Skip if already exists with a non-NEW status
            if pro_print_type_key in existing_keys:
                continue

            if print_type == 'BARCODE':
                prio_label = pos['b_prio_label']
                qty_123 = pos['b_priority']
                qty_4 = pos['b_priority4']
            else:
                prio_label = pos['c_prio_label']
                qty_123 = pos['c_priority']
                qty_4 = pos['c_priority4']

            # Skip rows with no priority
            if prio_label == 'Nothing':
                continue

            if prio_label in ('Priority 1', 'Priority 2', 'Priority 3'):
                qty = qty_123
            else:  # Priority 4
                qty = qty_4

            JobManagementItem.objects.create(
                pro=pro,
                pro_new=pro_new,
                pro_id=pro_id,
                location=location,
                skeda=skeda,
                print_type=print_type,
                pro_print_type=pro_print_type_key,
                qty=qty,
                priority=prio_label,
                status='NEW',
            )
            saved += 1

    messages.success(request, f"Jobs applied: {deleted_count} removed, {saved} new records added.")
    return redirect('job_management:job_items')


@login_required
def print_job_item(request, item_id):
    from core.models import JobManagementItem, PrintRequestLabels
    from core.utils import get_composition_str
    from django.utils import timezone

    item = JobManagementItem.objects.get(pk=item_id)
    po = item.pro[-7:] if item.pro and len(item.pro) >= 7 else item.pro

    # Get po_id, style, color, size from pos table using last 7 chars of pro
    pos_data = {}
    with connection.cursor() as cursor:
        cursor.execute("SELECT TOP 1 id, style, color, size FROM pos WHERE po = %s", [po])
        row = cursor.fetchone()
        if row:
            pos_data = {'po_id': row[0], 'style': row[1], 'color': row[2], 'size': row[3]}

    # Get material from trebovanje_db
    material_str = None
    try:
        with connections['trebovanje_db'].cursor() as cursor:
            cursor.execute("""
                SELECT wc, material
                FROM trebovanje.dbo.sap_coois_all
                WHERE po LIKE %s
                  AND wc NOT IN ('WCPS','WC03I','WC03I_K','WC03I_Z','WC03O','WC03O_K','WC03O_Z')
                  AND material NOT LIKE 'K%%'
                  AND material NOT LIKE 'CUT%%'
                ORDER BY wc ASC
            """, ['%' + po])
            mat_rows = cursor.fetchall()
        if mat_rows:
            material_str = '   '.join(f"{r[0]}-{r[1]}" for r in mat_rows)
    except Exception:
        pass

    composition_str = get_composition_str(po)

    PrintRequestLabels.objects.create(
        po_id=pos_data.get('po_id') or 0,
        po=po,
        type='Barcode' if item.print_type == 'BARCODE' else 'Carelabel',
        style=pos_data.get('style'),
        color=pos_data.get('color'),
        size=pos_data.get('size'),
        comment=item.qty,
        created=timezone.now().strftime('%Y-%m-%d %H:%M'),
        printer="Preparacija Zebra",
        qty=item.qty,
        material=material_str,
        composition=composition_str,
    )

    messages.success(request, f"Label printed: {item.pro} — {item.print_type}.")
    return redirect('job_management:job_items')


@login_required
def job_logs(request):
    from core.models import JobManagementItemLog
    logs = JobManagementItemLog.objects.all().order_by('-created_at')
    return render(request, 'job_management/job_logs.html', {'logs': logs})
