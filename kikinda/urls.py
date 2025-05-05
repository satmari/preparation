# kikinda/urls.py
from django.urls import path
from kikinda import views

app_name = 'kikinda'

urlpatterns = [
    path('kikinda_dashboard/', views.kikinda_dashboard, name='kikinda_dashboard'),

    path('po_stock/', views.po_stock, name='po_stock'),
    path('functions/', views.functions, name='functions'),

    path('receive_from_su_b/', views.receive_from_su_b, name='receive_from_su_b'),
    path('receive_from_su_b/<int:id>/', views.receive_from_su_b, name='receive_from_su_b'),
    path('receive_from_su_c/', views.receive_from_su_c, name='receive_from_su_c'),
    path('receive_from_su_c/<int:id>/', views.receive_from_su_c, name='receive_from_su_c'),

    path('give_to_the_line/', views.give_to_the_line, name='give_to_the_line'),
    path('return_to_main/', views.return_to_main, name='return_to_main'),
    path('reduce_from_stock/', views.reduce_from_stock, name='reduce_from_stock'),

]