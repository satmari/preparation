"""
Django settings for project project.

Generated by 'django-admin startproject' using Django 5.1.7.

For more information on this file, see
https://docs.djangoproject.com/en/5.1/topics/settings/

For the full list of settings and their values, see
https://docs.djangoproject.com/en/5.1/ref/settings/
"""

from pathlib import Path
import os
from decouple import config

# Build paths inside the project like this: BASE_DIR / 'subdir'.
BASE_DIR = Path(__file__).resolve().parent.parent


# Quick-start development settings - unsuitable for production
# See https://docs.djangoproject.com/en/5.1/howto/deployment/checklist/

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = 'django-insecure-bmxc-h$+cflwf&+g#5r9onr-t-b(+eztq(mz2^dyp++m^f0ni1'

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = True

ALLOWED_HOSTS = ['172.27.161.193', 'localhost', '127.0.0.1']
FORCE_SCRIPT_NAME = '/preparation'
USE_X_FORWARDED_HOST = True
SECURE_PROXY_SSL_HEADER = ('HTTP_X_FORWARDED_PROTO', 'https')

# Application definition

INSTALLED_APPS = [
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',

    'debug_toolbar',

    'core',
    'preparation',
    'line',
    'kikinda',
    'senta'
]

MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',

    # 'django.middleware.cache.UpdateCacheMiddleware',
    # 'django.middleware.cache.FetchFromCacheMiddleware',

    'django.middleware.common.CommonMiddleware',
    'debug_toolbar.middleware.DebugToolbarMiddleware',

]

ROOT_URLCONF = 'project.urls'

TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [BASE_DIR / 'templates'],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.debug',
                'django.template.context_processors.request',
                'django.contrib.auth.context_processors.auth',
                'django.contrib.messages.context_processors.messages',

                "core.context_processors.user_roles",
            ],
        },
    },
]

WSGI_APPLICATION = 'project.wsgi.application'


# Database
# https://docs.djangoproject.com/en/5.1/ref/settings/#databases

# DATABASES = {
#     'default': {
#         'ENGINE': 'django.db.backends.sqlite3',
#         'NAME': BASE_DIR / 'db.sqlite3',
#     }
# }

DATABASES = {
     'default': {
        'ENGINE': 'mssql',
        'NAME': config('DB_NAME'),
        'USER': config('DB_USER'),
        'PASSWORD': config('DB_PASSWORD'),
        'HOST': config('DB_HOST'),
        'PORT': '',
        'OPTIONS': {
            'driver': 'ODBC Driver 17 for SQL Server',
            'extra_params': 'TrustServerCertificate=yes',
        }
     },
    'posummary_db': {
        'ENGINE': 'mssql',
        'NAME': config('DB_NAME1'),
        'USER': config('DB_USER1'),
        'PASSWORD': config('DB_PASSWORD1'),
        'HOST': config('DB_HOST1'),
        'PORT': '',
        'OPTIONS': {
            'driver': 'ODBC Driver 17 for SQL Server',
            'extra_params': 'TrustServerCertificate=yes',
        },
    },
    'trebovanje_db': {
        'ENGINE': 'mssql',
        'NAME': config('DB_NAME2'),
        'USER': config('DB_USER2'),
        'PASSWORD': config('DB_PASSWORD2'),
        'HOST': config('DB_HOST2'),
        'PORT': '',
        'OPTIONS': {
            'driver': 'ODBC Driver 17 for SQL Server',
            'extra_params': 'TrustServerCertificate=yes',
        },
    },
    'bbstock_db': {
        'ENGINE': 'mssql',
        'NAME': config('DB_NAME3'),
        'USER': config('DB_USER3'),
        'PASSWORD': config('DB_PASSWORD3'),
        'HOST': config('DB_HOST3'),
        'PORT': '',
        'OPTIONS': {
            'driver': 'ODBC Driver 17 for SQL Server',
            'extra_params': 'TrustServerCertificate=yes',
        },
    },
    'inteos_db': {
        'ENGINE': 'mssql',
        'NAME': config('DB_NAME4'),
        'USER': config('DB_USER4'),
        'PASSWORD': config('DB_PASSWORD4'),
        'HOST': config('DB_HOST4'),
        'PORT': '',
        'OPTIONS': {
            'driver': 'ODBC Driver 17 for SQL Server',
            'extra_params': 'TrustServerCertificate=yes',
        },
    }

}

AUTH_USER_MODEL = 'core.CustomUser'

AUTHENTICATION_BACKENDS = (
    'django.contrib.auth.backends.ModelBackend',  # Default backend
)

# Set the user session expiration time
SESSION_COOKIE_AGE = 365 * 24 * 60 * 60  # 365 days
SESSION_EXPIRE_AT_BROWSER_CLOSE = False  # Don't expire session when browser is closed

# Password validation
# https://docs.djangoproject.com/en/5.1/ref/settings/#auth-password-validators

# AUTH_PASSWORD_VALIDATORS = [
#     {
#         'NAME': 'django.contrib.auth.password_validation.UserAttributeSimilarityValidator',
#     },
#     {
#         'NAME': 'django.contrib.auth.password_validation.MinimumLengthValidator',
#     },
#     {
#         'NAME': 'django.contrib.auth.password_validation.CommonPasswordValidator',
#     },
#     {
#         'NAME': 'django.contrib.auth.password_validation.NumericPasswordValidator',
#     },
# ]
AUTH_PASSWORD_VALIDATORS = []

# settings.py
LOGIN_URL = '/login/'  # Or the URL you prefer
LOGIN_REDIRECT_URL = '/'


# Internationalization
# https://docs.djangoproject.com/en/5.1/topics/i18n/

LANGUAGE_CODE = 'en-us'
# TIME_ZONE = 'UTC'
TIME_ZONE = 'Europe/Belgrade'
TIME_FORMAT = 'H:i'
USE_TZ = False
# USE_I18N = True


# Static files (CSS, JavaScript, Images)
# https://docs.djangoproject.com/en/5.1/howto/static-files/

STATIC_URL = '/static/preparation/'

STATIC_ROOT = os.path.join(BASE_DIR, 'staticfiles')

# Path to the project directory
BASE_DIR = Path(__file__).resolve().parent.parent

STATICFILES_DIRS = [
    BASE_DIR / "core" / "static",        # Static files for core
    BASE_DIR / "preparation" / "static",
    BASE_DIR / "kikinda" / "static",
    BASE_DIR / "senta" / "static",

]

# Default primary key field type
DEFAULT_AUTO_FIELD = 'django.db.models.BigAutoField'


# INTERNAL_IPS = [
#     '127.0.0.1',
# ]

SESSION_COOKIE_NAME = 'prep_sessionid'
SESSION_COOKIE_PATH = '/preparation/'
