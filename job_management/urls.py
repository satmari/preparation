# job_management/urls.py
from django.urls import path
from job_management import views

app_name = 'job_management'

urlpatterns = [
    path('pos-overview/', views.pos_overview, name='pos_overview'),
    path('apply-jobs/', views.apply_jobs, name='apply_jobs'),
    path('job-items/', views.job_items, name='job_items'),
    path('assign-operator/', views.assign_operator, name='assign_operator'),
    path('operators/', views.operators_list, name='operators_list'),
    path('operators/add/', views.operator_add, name='operator_add'),
    path('operators/<int:pk>/edit/', views.operator_edit, name='operator_edit'),
    path('send-to-stock/<int:item_id>/', views.send_to_stock, name='send_to_stock'),
]
