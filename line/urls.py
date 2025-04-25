# line/urls.py
from django.urls import path
from line import views

app_name = 'line'  # Important to use app_name to avoid naming conflicts

urlpatterns = [
    # path('line_dashboard/', views.line_dashboard, name='line_dashboard'),

    path('ll_login/', views.ll_login, name='ll_login'),
    path('request_for_b_c/', views.request_for_b_c, name='request_for_b_c'),
    path('request_for_b_c/<str:leader>/', views.request_for_b_c, name='request_for_b_c'),

    path('request_for_sq/', views.request_for_sq, name='request_for_sq'),
    path('request_for_sq/<str:leader>/', views.request_for_sq, name='request_for_sq'),

    path('request_history/<str:type>/<str:line>/', views.request_history, name='request_history'),

]
