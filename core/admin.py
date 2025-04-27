from django.contrib import admin
from django.urls import path
from django.contrib.auth.admin import UserAdmin
from core.models import *
from core.views import *
import openpyxl
from django.http import HttpResponse




class CustomUserAdmin(UserAdmin):
    model = CustomUser
    list_display = ['username', 'group_names', 'is_staff', 'is_active']
    list_filter = ['is_staff', 'is_active']
    search_fields = ['username', 'first_name', 'last_name']
    ordering = ['username']
    exclude = ('email',)

    # Include the 'groups' field to display groups in the admin form
    fieldsets = (
        (None, {'fields': ('username', 'password', 'groups')}),  # Include 'groups' here
        ('Personal info', {'fields': ('first_name', 'last_name')}),
        ('Permissions', {'fields': ('is_active', 'is_staff', 'is_superuser')}),
        ('Important dates', {'fields': ('last_login', 'date_joined')}),
    )

    add_fieldsets = (
        (None, {
            'classes': ('wide',),
            'fields': ('username', 'password1', 'password2', 'is_staff', 'is_active', 'groups'),
        }),
    )

    # Add the custom template to include the import button
    change_list_template = "core/customuser_change_list.html"

    # Display user groups in the list
    def group_names(self, obj):
        return ", ".join([group.name for group in obj.groups.all()]) if obj.groups.exists() else "No Group"

    group_names.short_description = "Groups"  # Sets column title in Django Admin

    # Adding the import_users URL
    def get_urls(self):
        urls = super().get_urls()
        custom_urls = [
            path('import/', import_users, name='import_users'),  # Custom URL for importing users
        ]
        return custom_urls + urls


# Register the CustomUserAdmin with the custom URL for import users
admin.site.register(CustomUser, CustomUserAdmin)

# Custom action to export data to Excel
def export_pos_to_excel(modeladmin, request, queryset):
    # Create an in-memory workbook and worksheet
    wb = openpyxl.Workbook()
    ws = wb.active
    ws.title = "POS Data"

    # Define the headers
    headers = [
        "PO Key", "Order Code", "PO", "Size", "Style", "Color", "Color Description",
        "Season", "Total Order Qty", "Flash", "Closed PO", "Brand", "Status", "Type",
        "Comment", "Delivery Date", "Hangtag", "Created At", "Updated At", "SAP Material",
        "Skeda", "No. Lines by Skeda", "PO New", "Loc ID SU", "Loc ID KI", "Loc ID SE"
    ]
    ws.append(headers)

    # Add data rows
    for obj in queryset:
        ws.append([
            obj.po_key, obj.order_code, obj.po, obj.size, obj.style, obj.color, obj.color_desc,
            obj.season, obj.total_order_qty, obj.flash, obj.closed_po, obj.brand, obj.status, obj.type,
            obj.comment, obj.delivery_date, obj.hangtag,
            obj.created_at.strftime('%Y-%m-%d %H:%M:%S') if obj.created_at else "",
            obj.updated_at.strftime('%Y-%m-%d %H:%M:%S') if obj.updated_at else "",
            obj.sap_material, obj.skeda, obj.no_lines_by_skeda, obj.po_new,
            obj.loc_id_su, obj.loc_id_ki, obj.loc_id_se
        ])

    # Prepare the response
    response = HttpResponse(content_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
    response["Content-Disposition"] = 'attachment; filename="pos_export.xlsx"'

    wb.save(response)
    return response


export_pos_to_excel.short_description = "Export selected POS records to Excel"

@admin.register(Pos)
class PosAdmin(admin.ModelAdmin):
    list_display = ('po', 'size', 'style', 'color', 'color_desc', 'season', 'total_order_qty', 'flash', 'skeda','brand','closed_po','created_at')
    search_fields = ('po', 'size', 'style', 'color', 'skeda')
    list_filter = ('po', 'style' ,'closed_po')
    actions = [export_pos_to_excel]  # Add export action


# Register models manually
@admin.register(BarcodeStocks)
class BarcodeStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(CarelabelStocks)
class CarelabelStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(BarcodeRequests)
class BarcodeRequestsAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(CarelabelRequests)
class CarelabelRequestsAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)


@admin.register(BarcodeKIStocks)
class BarcodeKIStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(CarelabelKIStocks)
class CarelabelKIStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(BarcodeSEStocks)
class BarcodeSEStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(CarelabelSEStocks)
class CarelabelSEStocksAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

@admin.register(ThrowAway)
class ThrowAwayAdmin(admin.ModelAdmin):
    list_display = ('id', 'material', 'type', 'qty', 'created_at')

@admin.register(Leftovers)
class LeftoversAdmin(admin.ModelAdmin):
    list_display = ('id', 'material', 'sku', 'price', 'qty', 'status', 'location')

@admin.register(PrepLocations)
class PrepLocationsAdmin(admin.ModelAdmin):
    list_display = ('id', 'location', 'location_desc', 'location_plant')

@admin.register(SecondQRequests)
class SecondQRequestsAdmin(admin.ModelAdmin):
    list_display = ('id', 'po_id', 'user_id', 'ponum', 'size', 'qty', 'module', 'status')
    search_fields = ('ponum',)

