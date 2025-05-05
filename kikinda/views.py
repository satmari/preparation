# kikinda/views.py
#from logging.config import stopListening
import traceback

#from django.contrib.auth.decorators import login_required
from django.shortcuts import render, redirect, get_object_or_404
#from django.template.defaulttags import comment
from django.core.exceptions import ObjectDoesNotExist

from core.models import *
#from django.contrib.auth.models import Group
from django.db import connection, connections, transaction
#from django.contrib import messages
#from django.http import JsonResponse

#from datetime import datetime
#from django.http import HttpResponse
import logging
logger = logging.getLogger(__name__)


def kikinda_dashboard(request):
    # is_kikinda = request.user.groups.filter(name='kikinda').exists()
    # return render(request, 'kikinda/kikinda_dashboard.html', {'is_kikinda': is_kikinda})
    return render(request, 'kikinda/kikinda_dashboard.html')

def po_stock(request):
    # return HttpResponse("po_stock view is working!")
    with connections['default'].cursor() as cursor:
        cursor.execute("""
        SELECT 
    pos.id,
    pos.po,
    pos.po_new,
    posum.location_all,
    pos.size,
    pos.style,
    pos.color,
    pos.color_desc,
    pos.flash,
    pos.brand,
    pos.skeda,
    pos.total_order_qty,
    pos.no_lines_by_skeda,
    p.location,

    ISNULL(bs.stock_b, 0) AS stock_b,
    ISNULL(br.request_b, 0) AS request_b,
    ISNULL(bks.to_receive_b, 0) AS to_receive_b,
    ISNULL(bks.stockl_b, 0) AS stockl_b,
    ISNULL(bks.in_prod_b, 0) AS in_prod_b,

    ISNULL(cs.stock_c, 0) AS stock_c,
    ISNULL(cr.request_c, 0) AS request_c,
    ISNULL(cks.to_receive_c, 0) AS to_receive_c,
    ISNULL(cks.stockl_c, 0) AS stockl_c,
    ISNULL(cks.in_prod_c, 0) AS in_prod_c

FROM pos
LEFT JOIN prep_locations AS p ON p.id = pos.loc_id_ki
LEFT JOIN [172.27.161.200].[posummary].dbo.pro AS posum ON posum.po_new = pos.po_new

-- Barcode Stocks aggregation
LEFT JOIN (
    SELECT 
        po_id,
        SUM(qty) AS stock_b
    FROM barcode_stocks
    GROUP BY po_id
) AS bs ON bs.po_id = pos.id

LEFT JOIN (
    SELECT 
        po_id,
        SUM(qty) AS request_b
    FROM barcode_requests
    WHERE status != 'error'
    GROUP BY po_id
) AS br ON br.po_id = pos.id

LEFT JOIN (
    SELECT 
        po_id,
        SUM(CASE WHEN status = 'to_receive' THEN qty ELSE 0 END) AS to_receive_b,
        SUM(CASE WHEN status != 'to_receive' THEN qty ELSE 0 END) AS stockl_b,
        SUM(CASE WHEN type = 'in_line' THEN qty * -1 ELSE 0 END) AS in_prod_b
    FROM barcode_ki_stocks
    GROUP BY po_id
) AS bks ON bks.po_id = pos.id

-- Carelabel Stocks aggregation
LEFT JOIN (
    SELECT 
        po_id,
        SUM(qty) AS stock_c
    FROM carelabel_stocks
    GROUP BY po_id
) AS cs ON cs.po_id = pos.id

LEFT JOIN (
    SELECT 
        po_id,
        SUM(qty) AS request_c
    FROM carelabel_requests
    WHERE status != 'error'
    GROUP BY po_id
) AS cr ON cr.po_id = pos.id

LEFT JOIN (
    SELECT 
        po_id,
        SUM(CASE WHEN status = 'to_receive' THEN qty ELSE 0 END) AS to_receive_c,
        SUM(CASE WHEN status != 'to_receive' THEN qty ELSE 0 END) AS stockl_c,
        SUM(CASE WHEN type = 'in_line' THEN qty * -1 ELSE 0 END) AS in_prod_c
    FROM carelabel_ki_stocks
    GROUP BY po_id
) AS cks ON cks.po_id = pos.id

WHERE 
    pos.closed_po = 'Open' AND
    posum.location_all = 'Kikinda'

ORDER BY 
    pos.po ASC,
    pos.size DESC;
""")
        lines = cursor.fetchall()
        columns = [col[0] for col in cursor.description]
        data = [dict(zip(columns, row)) for row in lines]

    for row in data:
        total_order_qty = row.get('total_order_qty') or 0

        stock_b = row.get('stock_b') or 0
        request_b = row.get('request_b') or 0
        row['to_receive_b'] = row.get('to_receive_b') or 0
        row['stockl_b'] = row.get('stockl_b') or 0
        row['in_prod_b'] = row.get('in_prod_b')  or 0

        row['b_s_to_print'] = total_order_qty - stock_b
        row['b_s_on_stock'] = stock_b - request_b

        stock_c = row.get('stock_c') or 0
        request_c = row.get('request_c') or 0
        row['to_receive_c'] = row.get('to_receive_c') or 0
        row['stockl_c'] = row.get('stockl_c') or 0
        row['in_prod_c'] = row.get('in_prod_c') or 0

        row['c_s_to_print'] = total_order_qty - stock_c
        row['c_s_on_stock'] = stock_c - request_c



    return render(request, 'kikinda/po_stock.html', {'data': data})

def functions(request):
    # return HttpResponse("functions view is working!")

    return render(request, 'kikinda/functions.html')

def receive_from_su_b(request, id=None):
    errors = []
    success_msg = ""

    def fetch_data():
        with connections['default'].cursor() as cursor:
            cursor.execute(
                """SELECT *,
                    (SELECT po_new FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
                    (SELECT style FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as style,
                    (SELECT color FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as color,
                    (SELECT l.location FROM pos as p
                     JOIN prep_locations as l ON l.id = p.loc_id_ki
                     WHERE p.id = barcode_ki_stocks.po_id) as location
                FROM barcode_ki_stocks
                WHERE status = 'to_receive'"""
            )
            lines = cursor.fetchall()
            columns = [col[0] for col in cursor.description]
            return [dict(zip(columns, row)) for row in lines]

    if id is not None:
        action = request.GET.get('action', 'receive')

        if action == 'delete':
            try:
                barcode = get_object_or_404(BarcodeKIStocks, id=id)
                po_id = barcode.po_id
                qty = barcode.qty

                # Delete the barcode record
                barcode.delete()

                # Also delete the matching BarcodeRequest
                BarcodeRequests.objects.filter(
                    po_id=po_id,
                    module='kikinda',
                    type='transfer_ki',
                    qty=qty
                ).delete()

                success_msg = "Uspešno obrisano."
            except Exception as e:
                errors.append("Neuspešno brisanje.")

            data = fetch_data()
            return render(request, 'kikinda/receive_from_su_b.html', {
                'data': data,
                'success_msg': success_msg,
                'errors': errors
            })

        else:
            # Receive action
            with connections['default'].cursor() as cursor:
                cursor.execute("""
                    SELECT *,
                        (SELECT po_new FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
                        (SELECT style FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as style,
                        (SELECT color FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as color,
                        (SELECT loc_id_ki FROM pos WHERE pos.id = barcode_ki_stocks.po_id) as location_id,
                        (SELECT l.location FROM pos as p
                         JOIN prep_locations as l ON l.id = p.loc_id_ki
                         WHERE p.id = barcode_ki_stocks.po_id) as location
                    FROM barcode_ki_stocks
                    WHERE status = 'to_receive' AND id = %s
                """, [id])
                row = cursor.fetchone()
                columns = [col[0] for col in cursor.description]
                data = dict(zip(columns, row))

            # Extract required fields
            id = data["id"]
            qty = data["qty"]
            location = data["location"]
            location_id = data["location_id"]

            user = request.user
            module = user.username.lower()

            if module == "kikinda":
                location_plant = "Kikinda"
            elif module == "senta":
                location_plant = "Senta"
            else:
                location_plant = "Subotica"

            with connections['default'].cursor() as cursor:
                cursor.execute("""SELECT * FROM prep_locations WHERE location_plant = %s""", [location_plant])
                location_rows = cursor.fetchall()
                columns = [col[0] for col in cursor.description]
                locations = [dict(zip(columns, row)) for row in location_rows]

            locationsArray = {'': ''}
            for item in locations:
                locationsArray[item['id']] = item['location']

            return render(request, 'kikinda/receive_from_su_b.html', {
                'id': id,
                'qty': qty,
                'location': location,
                'location_id': str(location_id),
                'locationsArray': locationsArray,
            })

    elif request.method == 'POST':

        id = request.POST.get('id')
        qty = request.POST.get('qty')
        location_id = request.POST.get('location_id')

        try:
            qty = int(qty)
            location_id = int(location_id)
        except ValueError:
            errors.append("Invalid input format.")

        try:
            barcode = get_object_or_404(BarcodeKIStocks, id=id)
            barcode.qty = qty
            barcode.status = 'stock'
            barcode.module = None
            barcode.type = ''
            barcode.save()

            po = get_object_or_404(Pos, id=barcode.po_id)
            po.loc_id_ki = location_id
            po.save()

            success_msg = "Uspešno zaprimljena količina."

        except Exception as e:
            errors.append("Problem pri čuvanju u tabeli.")

        data = fetch_data()
        return render(request, 'kikinda/receive_from_su_b.html', {
            'data': data,
            'success_msg': success_msg,
            'errors': errors
        })

    else:
        data = fetch_data()
        return render(request, 'kikinda/receive_from_su_b.html', {'data': data})

def receive_from_su_c(request, id=None):
    errors = []
    success_msg = ""

    def fetch_data():
        with connections['default'].cursor() as cursor:
            cursor.execute(
                """SELECT *,
                    (SELECT po_new FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
                    (SELECT style FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as style,
                    (SELECT color FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as color,
                    (SELECT l.location FROM pos as p
                     JOIN prep_locations as l ON l.id = p.loc_id_ki
                     WHERE p.id = carelabel_ki_stocks.po_id) as location
                FROM carelabel_ki_stocks
                WHERE status = 'to_receive'"""
            )
            lines = cursor.fetchall()
            columns = [col[0] for col in cursor.description]
            return [dict(zip(columns, row)) for row in lines]

    if id is not None:
        action = request.GET.get('action', 'receive')

        if action == 'delete':
            try:
                carelabel = get_object_or_404(CarelabelKIStocks, id=id)
                po_id = carelabel.po_id
                qty = carelabel.qty

                # Delete the barcode record
                carelabel.delete()

                # Also delete the matching BarcodeRequest
                CarelabelRequests.objects.filter(
                    po_id=po_id,
                    module='kikinda',
                    type='transfer_ki',
                    qty=qty
                ).delete()

                success_msg = "Uspešno obrisano."
            except Exception as e:
                errors.append("Neuspešno brisanje.")

            data = fetch_data()
            return render(request, 'kikinda/receive_from_su_c.html', {
                'data': data,
                'success_msg': success_msg,
                'errors': errors
            })

        else:
            # Receive action
            with connections['default'].cursor() as cursor:
                cursor.execute("""
                    SELECT *,
                        (SELECT po_new FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
                        (SELECT style FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as style,
                        (SELECT color FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as color,
                        (SELECT loc_id_ki FROM pos WHERE pos.id = carelabel_ki_stocks.po_id) as location_id,
                        (SELECT l.location FROM pos as p
                         JOIN prep_locations as l ON l.id = p.loc_id_ki
                         WHERE p.id = carelabel_ki_stocks.po_id) as location
                    FROM carelabel_ki_stocks
                    WHERE status = 'to_receive' AND id = %s
                """, [id])
                row = cursor.fetchone()
                columns = [col[0] for col in cursor.description]
                data = dict(zip(columns, row))

            # Extract required fields
            id = data["id"]
            qty = data["qty"]
            location = data["location"]
            location_id = data["location_id"]

            user = request.user
            module = user.username.lower()

            if module == "kikinda":
                location_plant = "Kikinda"
            elif module == "senta":
                location_plant = "Senta"
            else:
                location_plant = "Subotica"

            with connections['default'].cursor() as cursor:
                cursor.execute("""SELECT * FROM prep_locations WHERE location_plant = %s""", [location_plant])
                location_rows = cursor.fetchall()
                columns = [col[0] for col in cursor.description]
                locations = [dict(zip(columns, row)) for row in location_rows]

            locationsArray = {'': ''}
            for item in locations:
                locationsArray[item['id']] = item['location']

            return render(request, 'kikinda/receive_from_su_c.html', {
                'id': id,
                'qty': qty,
                'location': location,
                'location_id': str(location_id),
                'locationsArray': locationsArray,
            })

    elif request.method == 'POST':

        id = request.POST.get('id')
        qty = request.POST.get('qty')
        location_id = request.POST.get('location_id')

        try:
            qty = int(qty)
            location_id = int(location_id)
        except ValueError:
            errors.append("Invalid input format.")

        try:
            carelabel = get_object_or_404(CarelabelKIStocks, id=id)
            carelabel.qty = qty
            carelabel.status = 'stock'
            carelabel.module = None
            carelabel.type = ''
            carelabel.save()

            po = get_object_or_404(Pos, id=carelabel.po_id)
            po.loc_id_ki = location_id
            po.save()

            success_msg = "Uspešno zaprimljena količina."

        except Exception as e:
            errors.append("Problem pri čuvanju u tabeli.")

        data = fetch_data()
        return render(request, 'kikinda/receive_from_su_c.html', {
            'data': data,
            'success_msg': success_msg,
            'errors': errors
        })

    else:
        data = fetch_data()
        return render(request, 'kikinda/receive_from_su_c.html', {'data': data})

def give_to_the_line(request):
    # return HttpResponse("functions view is working!")

    error_msg = ""
    error_msg_b = ""
    error_msg_c = ""
    success_msg = ""

    with connections['bbstock_db'].cursor() as cursor:
        cursor.execute("""
            SELECT [location] as line FROM [bbStock].[dbo].[locations]
            WHERE location_dest='KIKINDA' and location_type= 'MODULE/LINE' and SUBSTRING(location, 5,1) = 'A'
            ORDER BY [location] asc
            """)
        line_rows = cursor.fetchall()
    lines = [{'line':row[0]} for row in line_rows]

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                    SELECT  p.po_new as po
                    FROM [pos] as p
                    JOIN [172.27.161.200].[posummary].[dbo].[pro] as ps ON ps.po_new = p.po
                    WHERE p.closed_po = 'Open' AND ps.location_all = 'Kikinda'
                    ORDER BY p.created_at desc
            """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]


    if request.method == 'POST':
        # print(request.POST)
        po_num = request.POST.get('po')
        qty = int(request.POST.get('qty'))
        modul = request.POST.get('modul')
        barcode = request.POST.get('barcode', '0')
        carelabel = request.POST.get('carelabel', '0')
        comment = request.POST.get('comment', '')

        # Verify if PO exists and is not closed
        try:
            po = Pos.objects.get(po=po_num)
        except ObjectDoesNotExist:
            error_msg += "Komesa doesn't exist in the PO table <br/>"
        else:
            if po.closed_po == 'Closed':
                error_msg += "PO is Closed <br/>"

        # Handle barcode
        if barcode != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                    SELECT SUM(qty) as barcode_ki_stock
                    FROM barcode_ki_stocks
                    WHERE ponum = %s AND status != 'to_receive'
                """, [po_num])
                row = cursor.fetchone()
                barcode_ki_stock = row[0] or 0

            if barcode_ki_stock - qty < 0:
                error_msg_b += "Nema dovoljno barkodova na stanju <br/>"

            if error_msg_b == "":  # Proceed if no errors so far
                try:
                    BarcodeKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        module=modul,
                        type="in_line",
                        status='stock',
                        comment=comment,
                    )
                    success_msg += "BarcodeKiStocks uspesno snimljen. <br>"
                except Exception as e:
                    traceback.print_exc()
                    error_msg += "Problem saving to BarcodeKiStocks table"

        # Handle carelabel
        if carelabel != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                    SELECT SUM(qty) as carelabel_ki_stocks
                    FROM carelabel_ki_stocks
                    WHERE ponum = %s AND status != 'to_receive'
                """, [po_num])
                row = cursor.fetchone()
                carelabel_ki_stocks = row[0] or 0

            if carelabel_ki_stocks - qty < 0:
                error_msg_c += "Nema dovoljno carelabela na stanju <br/>"

            if error_msg_c == "":  # Proceed if no errors so far
                try:
                    CarelabelKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        module=modul,
                        type="in_line",
                        status='stock',
                        comment=comment,
                    )
                    success_msg += "CarelabelKiStocks uspesno snimljen."
                except Exception as e:
                    error_msg = "Problem saving to CarelabelKiStocks table"

        if carelabel == '0' and barcode == '0':
            error_msg = "Nije oznacen ni barcode ni carelabel"

        # If there are no errors, pass success_msg to template
        if not error_msg:
            return render(request, 'kikinda/give_to_the_line.html', {
                'pos': pos,
                'lines': lines,
                'success_msg': success_msg,
                'error_msg_b': error_msg_b,
                'error_msg_c': error_msg_c,
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/give_to_the_line.html', {
            'pos': pos,
            'lines': lines,
            'error_msg': error_msg,
            'error_msg_b': error_msg_b,
            'error_msg_c': error_msg_c,
        })

    # print(pos)
    return render(request, 'kikinda/give_to_the_line.html', {
        'pos': pos,
        'lines': lines
    })

def return_to_main(request):
    # return HttpResponse("functions view is working!")

    error_msg = ""
    error_msg_b = ""
    error_msg_c = ""
    success_msg = ""

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                    SELECT  p.po_new as po
                    FROM [pos] as p
                    JOIN [172.27.161.200].[posummary].[dbo].[pro] as ps ON ps.po_new = p.po
                    WHERE p.closed_po = 'Open' AND ps.location_all = 'Kikinda'
                    ORDER BY p.created_at desc
            """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]

    if request.method == 'POST':
        # print(request.POST)
        po_num = request.POST.get('po')
        qty = int(request.POST.get('qty'))
        barcode = request.POST.get('barcode', '0')
        carelabel = request.POST.get('carelabel', '0')
        comment = request.POST.get('comment', '')

        # Verify if PO exists and is not closed
        try:
            po = Pos.objects.get(po=po_num)
        except ObjectDoesNotExist:
            error_msg += "Komesa doesn't exist in the PO table <br/>"
        else:
            if po.closed_po == 'Closed':
                error_msg += "PO is Closed <br/>"

        # Handle barcode
        if barcode != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                            SELECT SUM(qty) as barcode_ki_stock
                            FROM barcode_ki_stocks
                            WHERE ponum = %s AND status != 'to_receive'
                        """, [po_num])
                row = cursor.fetchone()
                barcode_ki_stock = row[0] or 0

            if barcode_ki_stock - qty < 0:
                error_msg_b += "Nema dovoljno barkodova na stanju <br/>"

            if error_msg_b == "":  # Proceed if no errors so far
                try:
                    BarcodeKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        # module=modul,
                        type="returned",
                        status='stock',
                        comment=comment,
                    )

                    # BarcodeStocks.objects.create(
                    #     po_id=po.id,
                    #     user_id=request.user.id,
                    #     ponum=po_num,
                    #     size=po.size,
                    #     qty=int(qty),
                    #     # module=modul,
                    #     type="returned",
                    #     # status='stock',
                    #     comment=comment,
                    # )

                    BarcodeRequests.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),
                        status='done',
                        module='kikinda',
                        type="return_ki",
                        comment=comment,
                    )

                    success_msg += "BarcodeKiStocks uspesno snimljen. <br>"
                except Exception as e:
                    traceback.print_exc()
                    error_msg += "Problem saving to BarcodeKiStocks table"

        # Handle carelabel
        if carelabel != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                            SELECT SUM(qty) as carelabel_ki_stocks
                            FROM carelabel_ki_stocks
                            WHERE ponum = %s AND status != 'to_receive'
                        """, [po_num])
                row = cursor.fetchone()
                carelabel_ki_stocks = row[0] or 0

            if carelabel_ki_stocks - qty < 0:
                error_msg_c += "Nema dovoljno carelabela na stanju <br/>"

            if error_msg_c == "":  # Proceed if no errors so far
                try:
                    CarelabelKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        # module=modul,
                        type="returned",
                        status='stock',
                        comment=comment,
                    )

                    # CarelabelStocks.objects.create(
                    #     po_id=po.id,
                    #     user_id=request.user.id,
                    #     ponum=po_num,
                    #     size=po.size,
                    #     qty=int(qty),
                    #     # module=modul,
                    #     type="returned",
                    #     # status='stock',
                    #     comment=comment,
                    # )

                    CarelabelRequests.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),
                        status='done',
                        module='kikinda',
                        type="return_ki",
                        comment=comment,
                    )
                    success_msg += "CarelabelKiStocks uspesno snimljen."
                except Exception as e:
                    error_msg = "Problem saving to CarelabelKiStocks table"

        if carelabel == '0' and barcode == '0':
            error_msg = "Nije oznacen ni barcode ni carelabel"

        # If there are no errors, pass success_msg to template
        if not error_msg:
            return render(request, 'kikinda/return_to_main.html', {
                'pos': pos,
                'success_msg': success_msg,
                'error_msg_b': error_msg_b,
                'error_msg_c': error_msg_c,
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/return_to_main.html', {
            'pos': pos,
            'error_msg': error_msg,
            'error_msg_b': error_msg_b,
            'error_msg_c': error_msg_c,
        })

    # print(pos)
    return render(request, 'kikinda/return_to_main.html', {
        'pos': pos,

    })

def reduce_from_stock(request):
    # return HttpResponse("functions view is working!")

    error_msg = ""
    error_msg_b = ""
    error_msg_c = ""
    success_msg = ""

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                    SELECT  p.po_new as po
                    FROM [pos] as p
                    JOIN [172.27.161.200].[posummary].[dbo].[pro] as ps ON ps.po_new = p.po
                    WHERE p.closed_po = 'Open' AND ps.location_all = 'Kikinda'
                    ORDER BY p.created_at desc
            """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]

    if request.method == 'POST':
        # print(request.POST)
        po_num = request.POST.get('po')
        qty = int(request.POST.get('qty'))
        barcode = request.POST.get('barcode', '0')
        carelabel = request.POST.get('carelabel', '0')
        comment = request.POST.get('comment', '')

        # Verify if PO exists and is not closed
        try:
            po = Pos.objects.get(po=po_num)
        except ObjectDoesNotExist:
            error_msg += "Komesa doesn't exist in the PO table <br/>"
        else:
            if po.closed_po == 'Closed':
                error_msg += "PO is Closed <br/>"

        # Handle barcode
        if barcode != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                            SELECT SUM(qty) as barcode_ki_stock
                            FROM barcode_ki_stocks
                            WHERE ponum = %s AND status != 'to_receive'
                        """, [po_num])
                row = cursor.fetchone()
                barcode_ki_stock = row[0] or 0

            if barcode_ki_stock - qty < 0:
                error_msg_b += "Nema dovoljno barkodova na stanju <br/>"

            if error_msg_b == "":  # Proceed if no errors so far
                try:
                    BarcodeKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        # module=modul,
                        type="reduce",
                        status='stock',
                        comment=comment,
                    )

                    success_msg += "BarcodeKiStocks uspesno snimljen. <br>"
                except Exception as e:
                    traceback.print_exc()
                    error_msg += "Problem saving to BarcodeKiStocks table"

        # Handle carelabel
        if carelabel != '0':

            with connection.cursor() as cursor:
                cursor.execute("""
                            SELECT SUM(qty) as carelabel_ki_stocks
                            FROM carelabel_ki_stocks
                            WHERE ponum = %s AND status != 'to_receive'
                        """, [po_num])
                row = cursor.fetchone()
                carelabel_ki_stocks = row[0] or 0

            if carelabel_ki_stocks - qty < 0:
                error_msg_c += "Nema dovoljno carelabela na stanju <br/>"

            if error_msg_c == "":  # Proceed if no errors so far
                try:
                    CarelabelKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) * (-1),  # negative qty
                        qty_to_receive=0,
                        # module=modul,
                        type="reduce",
                        status='stock',
                        comment=comment,
                    )

                    success_msg += "CarelabelKiStocks uspesno snimljen."
                except Exception as e:
                    error_msg = "Problem saving to CarelabelKiStocks table"

        if carelabel == '0' and barcode == '0':
            error_msg = "Nije oznacen ni barcode ni carelabel"

        # If there are no errors, pass success_msg to template
        if not error_msg:
            return render(request, 'kikinda/reduce_from_stock.html', {
                'pos': pos,
                'success_msg': success_msg,
                'error_msg_b': error_msg_b,
                'error_msg_c': error_msg_c,
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/reduce_from_stock.html', {
            'pos': pos,
            'error_msg': error_msg,
            'error_msg_b': error_msg_b,
            'error_msg_c': error_msg_c,
        })

    # print(pos)
    return render(request, 'kikinda/reduce_from_stock.html', {
        'pos': pos,

    })

def back_from_module(request):
    # return HttpResponse("back_from_module view is working!")

    # find list of modules/lines
    with connections['bbstock_db'].cursor() as cursor:
        cursor.execute("""
            SELECT [location] as line FROM [bbStock].[dbo].[locations]
             WHERE location_dest = 'KIKINDA'
        """)
        lines = cursor.fetchall()
    lines = [row[0] for row in lines]
    # print(lines)

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                    SELECT  p.po_new as po
                    FROM [pos] as p
                    JOIN [172.27.161.200].[posummary].[dbo].[pro] as ps ON ps.po_new = p.po
                    WHERE p.closed_po = 'Open' AND ps.location_all = 'Kikinda'
                    ORDER BY p.created_at desc
            """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]

    errors = []
    success_msg = ""

    if request.method == 'POST':
        # print(request.POST)
        po_num = request.POST.get('po')
        qty = request.POST.get('qty')
        barcode = request.POST.get('barcode', '0')
        carelabel = request.POST.get('carelabel', '0')
        modul = request.POST.get('modul')
        comment = request.POST.get('comment', '')

        # Validate required fields
        if not po_num or len(po_num) < 6 or len(po_num) > 7:
            errors.append("PO number must be 6-7 characters long")

        if not qty:
            errors.append("Qty/Kolicina je obavezna")

        if not modul:
            errors.append("Modul/line polje je obavezno")

        # Verify if PO exists and is not closed
        try:
            po = Pos.objects.get(po=po_num)
        except ObjectDoesNotExist:
            errors.append("Komesa doesn't exist in the PO table")
        else:
            if po.closed_po == 'Closed':
                errors.append("PO is Closed")

        # Handle barcode
        if barcode != '0':

            if not errors:  # Proceed if no errors so far
                try:
                    BarcodeKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty) ,  # negative qty
                        module=modul,
                        type="in_line",
                        status="stock",
                        comment=comment,

                    )
                    success_msg += "BarcodeKIStock uspesno snimljen. <br>"  # Append the success message
                except Exception as e:
                    errors.append("Problem saving to BarcodeKIStock table")

        # Handle carelabel
        if carelabel != '0':

            if not errors:  # Proceed if no errors so far
                try:
                    CarelabelKIStocks.objects.create(
                        po_id=po.id,
                        user_id=request.user.id,
                        ponum=po_num,
                        size=po.size,
                        qty=int(qty),  # negative qty
                        module=modul,
                        type="in_line",
                        status="stock",
                        comment=comment,

                    )
                    success_msg += "CarelabelKIStocks uspesno snimljen."  # Append the success message
                except Exception as e:
                    errors.append("Problem saving to CarelabelKIStocks table")

        if carelabel == '0' and barcode == '0':
            errors.append("Nije oznacen ni barcode ni carelabel")

        # If there are no errors, pass success_msg to template
        if not errors:
            return render(request, 'kikinda/back_from_module.html', {
                'pos': pos,
                'lines': lines,
                'success_msg': success_msg
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/back_from_module.html', {
            'pos': pos,
            'lines': lines,
            'errors': errors
        })


    # If GET request, just render the form with open POS
    return render(request, 'kikinda/back_from_module.html', {
        'pos': pos,
        'lines': lines
    })