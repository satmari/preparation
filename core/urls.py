#core\urls.py
from django.urls import path
from django.contrib.auth import views as auth_views
from core import views

app_name = 'core'

urlpatterns = [
    path('', views.main_page, name='main_page'),
    path('admin_dashboard', views.admin_dashboard, name='admin_dashboard'),

    path('login', views.login_view, name='login'),
    path('logout', views.logout_view, name='logout'),
    # path('login', auth_views.LoginView.as_view(template_name='core/login.html'), name='login'),
    # path('logout', auth_views.LogoutView.as_view(next_page='main_page'), name='logout'),

    path('import', views.import_users, name='import_users'),
]
