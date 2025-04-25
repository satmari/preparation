# line/views.py
import traceback
from datetime import timedelta

from django.core.exceptions import ObjectDoesNotExist
from django.db import connection, connections
from django.shortcuts import render
from django.utils import timezone

from core.models import *


def line_dashboard(request):
    return render(request, 'line/line_dashboard.html')
    # is_line = request.user.groups.filter(name='lines').exists()
    # return render(request, 'line/line_dashboard.html', {'is_line': is_line})

def ll_login(request):
    error_msg = ''

    if request.method == 'POST':
        # return HttpResponse("POST request received successfully!")

        password = request.POST.get('password')

        if not password or not (4 <= len(password) <= 5):
            error_msg += 'PIN must be between 4 and 5 characters.<br>'
            # return render(request, 'line/error.html', {'msg': msg})

        with connections['inteos_db'].cursor() as cursor:
            cursor.execute("""
                    SELECT Name FROM [BdkCLZG].[dbo].[WEA_PersData]
                    WHERE Func = 23 AND FlgAct = 1 AND PinCode = %s
                """, [password])
            rows = cursor.fetchall()

        if not rows:
            error_msg += 'LineLeader with this PIN does not exist <br>'
            return render(request, 'line/ll_login.html', {'error_msg': error_msg})
        else:
            leader = rows[0][0]  # get the first Name
            request.session['leader'] = leader
            return render(request, 'line/ll_login.html', {'leader': leader})
    else:
        return render(request, 'line/ll_login.html')

def request_for_b_c(request, leader=None):
    # print(leader)

    error_msg = ""
    success_msg = ""

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                        SELECT  p.po_new as po
                        FROM [pos] as p
                        WHERE p.closed_po = 'Open'
                        ORDER BY p.created_at desc
                """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]

    if leader is not None:
        return render(request, 'line/request_for_b_c.html', {
            'leader': leader,
            'pos': pos,
        })

    elif request.method == 'POST':

        leader = request.POST.get('leader')
        po_num = request.POST.get('po')
        # qty = int(request.POST.get('qty'))
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

            try:
                BarcodeRequests.objects.create(
                    po_id=po.id,
                    user_id=request.user.id,
                    ponum=po_num,
                    size=po.size,
                    # qty=int(qty),
                    module=request.user.username,
                    leader=leader,
                    type="modul",
                    status='pending',
                    comment=comment,
                )
                success_msg += "Zahtev za barkod etikete uspešno snimljen <br>"
            except Exception as e:
                traceback.print_exc()
                error_msg += "Problem saving to BarcodeKiStocks table"

        # Handle carelabel
        if carelabel != '0':

            try:
                CarelabelRequests.objects.create(
                    po_id=po.id,
                    user_id=request.user.id,
                    ponum=po_num,
                    size=po.size,
                    # qty=int(qty),
                    module=request.user.username,
                    leader=leader,
                    type="modul",
                    status='pending',
                    comment=comment,
                )
                success_msg += "Zahtev za stranične etikete uspešno snimljen"
            except Exception as e:
                error_msg = "Problem saving to CarelabelKiStocks table"

        if carelabel == '0' and barcode == '0':
            error_msg = "Nije označen ni barcode ni carelabel"

        # If there are no errors, pass success_msg to template
        if not error_msg:
            return render(request, 'line/request_for_b_c.html', {
                'pos': pos,
                'leader': leader,
                'success_msg': success_msg,
            })

        # If errors exist, pass them to the template
        return render(request, 'line/request_for_b_c.html', {
            'pos': pos,
            'leader': leader,
            'error_msg': error_msg,
        })

    else:
        return render(request, 'line/request_for_b_c.html', {
            'leader':leader,
            'pos':pos,
        })

def request_for_sq(request, leader=None):
    # print(leader)

    error_msg = ""
    success_msg = ""

    with connections['default'].cursor() as cursor:
        cursor.execute("""
                        SELECT  p.po_new as po
                        FROM [pos] as p
                        WHERE p.closed_po = 'Open'
                        ORDER BY p.created_at desc
                """)
        pos_rows = cursor.fetchall()
    # pos = [row[0] for row in pos_rows]
    pos = [{'po': row[0]} for row in pos_rows]

    if leader is not None:
        return render(request, 'line/request_for_sq.html', {
            'pos': pos,
            'leader': leader

        })

    elif request.method == 'POST':

        leader = request.POST.get('leader')
        po_num = request.POST.get('po')
        # qty = int(request.POST.get('qty'))
        comment = request.POST.get('comment', '')

        # Verify if PO exists and is not closed
        try:
            po = Pos.objects.get(po=po_num)
        except ObjectDoesNotExist:
            error_msg += "Komesa doesn't exist in the PO table <br/>"
        else:
            if po.closed_po == 'Closed':
                error_msg += "PO is Closed <br/>"

        try:
            SecondQRequests.objects.create(
                po_id=po.id,
                user_id=request.user.id,
                ponum=po_num,
                size=po.size,
                style=po.style,
                color=po.color,
                # qty=int(qty),
                module=request.user.username,
                leader=leader,
                type="modul",
                status='pending',
                comment=comment
            )
            success_msg = "Zahtev za drugu klasu uspešno snimljen <br>"
        except Exception as e:
            # traceback.print_exc()
            error_msg += "Problem saving to SecondQRequests table"


        # If there are no errors, pass success_msg to template
        if not error_msg:
            return render(request, 'line/request_for_sq.html', {
                'pos': pos,
                'leader': leader,
                'success_msg': success_msg,
                'error_msg': error_msg,
            })

        # If errors exist, pass them to the template
        return render(request, 'line/request_for_sq.html', {
            'pos': pos,
            'leader': leader,
            'error_msg': error_msg,
        })

    else:
        return render(request, 'line/request_for_sq.html', {
            'leader':leader,
            'pos':pos,
        })

def request_history(request, type, line):
    one_year_ago = timezone.now() - timedelta(days=365)

    if type == 'b':
        # history = BarcodeRequests.objects.filter(module=line,created_at__gte=one_year_ago)
        with connection.cursor() as cursor:
            cursor.execute("""
                SELECT r.ponum,r.size,r.qty, r.module,r.leader, r.status, 
                r.type, r.comment, r.created_at, r.updated_at, 
                pos.style, pos.color, pos.color_desc, pos.brand, pos.flash, pos.skeda
                FROM barcode_requests r
                JOIN pos ON r.ponum = pos.po
                WHERE r.module = %s AND r.created_at >= DATEADD(year, -1, GETDATE())
                ORDER BY r.created_at desc
            """, [line])
            columns = [col[0] for col in cursor.description]
            history = [dict(zip(columns, row)) for row in cursor.fetchall()]



        title = "Barcode Request History"

    else:
        # history = CarelabelRequests.objects.filter(module=line,created_at__gte=one_year_ago)
        with connection.cursor() as cursor:
            cursor.execute("""
                SELECT r.ponum,r.size,r.qty, r.module,r.leader, r.status, 
                r.type, r.comment, r.created_at, r.updated_at, 
                pos.style, pos.color, pos.color_desc, pos.brand, pos.flash, pos.skeda
                FROM carelabel_requests r
                JOIN pos ON r.ponum = pos.po
                WHERE r.module = %s AND r.created_at >= DATEADD(year, -1, GETDATE())
                ORDER BY r.created_at desc
            """, [line])
            columns = [col[0] for col in cursor.description]
            history = [dict(zip(columns, row)) for row in cursor.fetchall()]

        title = "Carelabel Request History"

    context = {
        'history': history,
        'type': type,
        'line': line,
        'title': title,
    }

    return render(request, 'line/request_history.html', context)