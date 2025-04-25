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
    # return render(request, 'senta/kikinda_dashboard.html', {'is_kikinda': is_kikinda})
    return render(request, 'kikinda/kikinda_dashboard.html')

def po_stock(request):
    # return HttpResponse("po_stock view is working!")
    with connections['default'].cursor() as cursor:
        cursor.execute("""
        SELECT  pos.id,
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
		(SELECT p.location FROM prep_locations as p WHERE p.id = pos.loc_id_ki) as location,

		(SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
		(SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id AND barcode_requests.status != 'error') request_b,
		(SELECT SUM([barcode_ki_stocks].qty)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].status = 'to_receive') as to_receive_b,
		(SELECT SUM([barcode_ki_stocks].qty)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].status != 'to_receive') as stockl_b,
		(SELECT SUM([barcode_ki_stocks].qty)*(-1)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].type = 'in_line') as in_prod_b,
        
        (SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
        (SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id AND carelabel_requests.status != 'error') request_c,
		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status = 'to_receive') as to_receive_c,
		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status != 'to_receive') as stockl_c,
		(SELECT SUM([carelabel_ki_stocks].qty)*(-1)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].type = 'in_line') as in_prod_c
		
		FROM pos
		LEFT JOIN [172.27.161.200].[posummary].dbo.pro as posum ON posum.po_new = pos.po_new

		WHERE pos.closed_po = 'Open' AND posum.location_all = 'Kikinda'
		GROUP BY	pos.id,
					pos.po,
					pos.po_new,
					posum.location_all,
					pos.size,
					pos.style,
					pos.color,
					pos.color_desc,
					--pos.season,
					pos.flash,
					pos.brand,
					pos.skeda,
					pos.total_order_qty,
					pos.no_lines_by_skeda,
					pos.loc_id_ki

		ORDER BY pos.po asc,
			     pos.size desc""")
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
    # return HttpResponse("functions view is working!")

    errors = []
    success_msg = ""

    # find list of lines to receive
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
            WHERE status = 'to_receive'""")
        lines = cursor.fetchall()
        columns = [col[0] for col in cursor.description]
        data = [dict(zip(columns, row)) for row in lines]

    if id is not None:
        # Handle the case with an ID

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

        # Authenticated user info
        user = request.user
        module = user.username.lower()  # or user.get_full_name().lower() if needed

        if module == "kikinda":
            location_plant = "Kikinda"
        elif module == "senta":
            location_plant = "Senta"
        else:
            location_plant = "Subotica"

        # Fetch prep_locations for the plant
        with connections['default'].cursor() as cursor:
            cursor.execute("""
                    SELECT * FROM prep_locations WHERE location_plant = %s
                """, [location_plant])
            location_rows = cursor.fetchall()
            columns = [col[0] for col in cursor.description]
            locations = [dict(zip(columns, row)) for row in location_rows]

        # Build select options
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
        # return HttpResponse("POST request received successfully!")

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

            success_msg = "Uspesno zaprimljena kolicina"  # Append the success message

        except Exception as e:
            errors.append("Problem to save in table")

        if not errors:
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
                    WHERE status = 'to_receive'""")
                lines = cursor.fetchall()
                columns = [col[0] for col in cursor.description]
                data = [dict(zip(columns, row)) for row in lines]

            return render(request, 'kikinda/receive_from_su_b.html', {
                'data': data,
                'success_msg': success_msg
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/receive_from_su_b.html', {
            'data': data,
            'errors': errors
        })

    else:

        return render(request, 'kikinda/receive_from_su_b.html', {'data':data})

def receive_from_su_c(request, id=None):
    # return HttpResponse("functions view is working!")

    errors = []
    success_msg = ""

    # find list of lines to receive
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
            WHERE status = 'to_receive'""")
        lines = cursor.fetchall()
        columns = [col[0] for col in cursor.description]
        data = [dict(zip(columns, row)) for row in lines]

    if id is not None:
        # Handle the case with an ID

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

        # Authenticated user info
        user = request.user
        module = user.username.lower()  # or user.get_full_name().lower() if needed

        if module == "kikinda":
            location_plant = "Kikinda"
        elif module == "senta":
            location_plant = "Senta"
        else:
            location_plant = "Subotica"

        # Fetch prep_locations for the plant
        with connections['default'].cursor() as cursor:
            cursor.execute("""
                    SELECT * FROM prep_locations WHERE location_plant = %s
                """, [location_plant])
            location_rows = cursor.fetchall()
            columns = [col[0] for col in cursor.description]
            locations = [dict(zip(columns, row)) for row in location_rows]

        # Build select options
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
        # return HttpResponse("POST request received successfully!")

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

            success_msg = "Uspesno zaprimljena kolicina"  # Append the success message

        except Exception as e:
            errors.append("Problem to save in table")

        if not errors:
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
                    WHERE status = 'to_receive'""")
                lines = cursor.fetchall()
                columns = [col[0] for col in cursor.description]
                data = [dict(zip(columns, row)) for row in lines]

            return render(request, 'kikinda/receive_from_su_c.html', {
                'data': data,
                'success_msg': success_msg
            })

        # If errors exist, pass them to the template
        return render(request, 'kikinda/receive_from_su_c.html', {
            'data': data,
            'errors': errors
        })

    else:

        return render(request, 'kikinda/receive_from_su_c.html', {'data':data})

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