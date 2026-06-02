# job_management/urls.py
from django.urls import path
from job_management import views

app_name = 'job_management'

urlpatterns = [
    path('pos-overview/', views.pos_overview, name='pos_overview'),
]
