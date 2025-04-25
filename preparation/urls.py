# preparation/urls.py
from django.urls import path
from preparation import views

app_name = 'preparation'

urlpatterns = [
    path('preparation_dashboard/', views.preparation_dashboard, name='preparation_dashboard'),

    path('po_stock/', views.po_stock, name='po_stock'),

    path('barcode_requests/', views.barcode_requests, name='barcode_requests'),
    path('barcode_request_<str:action>/<int:id>/', views.barcode_requests, name='barcode_request_action'),

    path('carelabel_requests/', views.carelabel_requests, name='carelabel_requests'),
    path('carelabel_request_<str:action>/<int:id>/', views.carelabel_requests, name='carelabel_request_action'),

    path('secondq_requests/', views.secondq_requests, name='secondq_requests'),
    path('secondq_request_<str:action>/<int:id>/', views.secondq_requests, name='secondq_request_action'),

    path('functions/', views.functions, name='functions'),
    path('add_to_stock/', views.add_to_stock, name='add_to_stock'),
    path('back_from_module/', views.back_from_module, name='back_from_module'),
    path('reduce_from_stock/', views.reduce_from_stock, name='reduce_from_stock'),
    path('throw_away/', views.throw_away, name='throw_away'),
    path('leftover/', views.leftover, name='leftover'),
    path('transfer_to_kikinda/', views.transfer_to_kikinda, name='transfer_to_kikinda'),
    path('transfer_to_senta/', views.transfer_to_senta, name='transfer_to_senta'),
    path('manual_request/', views.manual_request, name='manual_request'),

    path('leftover_table/',views.leftover_table, name='leftover_table'),
    path('leftover_table_all/',views.leftover_table_all, name='leftover_table_all'),
    path('import_file/',views.import_file ,name='import_file'),

    path('prep_locations/', views.prep_locations, name='prep_locations'),
    path('prep_locations/edit/<int:l_id>/', views.prep_locations, name='prep_locations_edit'),
    path("prep_locations/assign/", views.assign_location_to_po, name="assign_location_to_po"),

    path('log_tables_<str:action>/', views.log_tables, name='log_tables_action'),
    # path('log_table/', views.log_table, name='log_table_action'),


    path('pos/', views.pos_table, name='pos_table'),
    path('pos/edit/<int:pos_id>/', views.edit_pos, name='edit_pos'),
    path('import-pos/', views.import_pos_data, name='import_pos'),
    path('close-pos/', views.close_pos_data, name='close_pos'),

]