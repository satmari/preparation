from django.contrib.auth.models import AbstractUser
from django.contrib.auth.models import BaseUserManager
from django.db import models

class CustomUserManager(BaseUserManager):
    def create_user(self, username, password=None, **extra_fields):
        """Create and return a regular user with an username and password."""
        if not username:
            raise ValueError('The Username field must be set')
        user = self.model(username=username, **extra_fields)
        user.set_password(password)
        user.save(using=self._db)
        return user

    def create_superuser(self, username, password=None, **extra_fields):
        """Create and return a superuser with an username and password."""
        extra_fields.setdefault('is_staff', True)
        extra_fields.setdefault('is_superuser', True)

        return self.create_user(username, password, **extra_fields)

class CustomUser(AbstractUser):
    email = None  # Remove the email field

    USERNAME_FIELD = 'username'  # Use only 'username' for authentication
    REQUIRED_FIELDS = []  # No required fields except username

    objects = CustomUserManager()  # Set custom manager

    def __str__(self):
        return self.username

class Pos(models.Model):
    id = models.AutoField(primary_key=True)
    po_key = models.CharField(max_length=255, unique=True)
    order_code = models.CharField(max_length=255, null=True, blank=True)
    po = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    style = models.CharField(max_length=255, null=True, blank=True)
    color = models.CharField(max_length=255, null=True, blank=True)
    color_desc = models.CharField(max_length=255, null=True, blank=True)
    season = models.CharField(max_length=255, null=True, blank=True)
    total_order_qty = models.IntegerField(null=True, blank=True)
    flash = models.CharField(max_length=255, null=True, blank=True)
    closed_po = models.CharField(max_length=255, null=True, blank=True)
    brand = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    delivery_date = models.CharField(max_length=255, null=True, blank=True)
    hangtag = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    sap_material = models.CharField(max_length=255, null=True, blank=True)
    skeda = models.CharField(max_length=255, null=True, blank=True)
    no_lines_by_skeda = models.CharField(max_length=255, null=True, blank=True)
    po_new = models.CharField(max_length=255, null=True, blank=True)
    loc_id_su = models.CharField(max_length=255, null=True, blank=True)
    loc_id_ki = models.CharField(max_length=255, null=True, blank=True)
    loc_id_se = models.CharField(max_length=255, null=True, blank=True)

    class Meta:
        db_table = 'pos'


class BarcodeStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'barcode_stocks'


class CarelabelStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)


    class Meta:
        db_table = 'carelabel_stocks'


class BarcodeRequests(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    leader = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'barcode_requests'


class CarelabelRequests(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    leader = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'carelabel_requests'


class ThrowAway(models.Model):
    id = models.AutoField(primary_key=True)
    material = models.CharField(max_length=255)
    type = models.CharField(max_length=255)
    qty = models.IntegerField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'throw_aways'


class Leftovers(models.Model):
    id = models.AutoField(primary_key=True)
    material = models.CharField(max_length=255)
    sku = models.CharField(max_length=255)
    price = models.FloatField()
    qty = models.IntegerField()
    status = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    location = models.CharField(max_length=255, null=True, blank=True)
    place = models.CharField(max_length=255, null=True, blank=True)

    class Meta:
        db_table = 'leftovers'


class Leftovers2(models.Model):
    id = models.AutoField(primary_key=True)
    material = models.CharField(max_length=255)
    sku = models.CharField(max_length=255)
    type = models.CharField(max_length=255)
    price = models.FloatField(null=True)
    ponum = models.CharField(max_length=255, null=True)
    qty = models.IntegerField()
    status = models.CharField(max_length=255)
    location = models.CharField(max_length=255, null=True, blank=True)
    place = models.CharField(max_length=255, null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'leftovers2'


class BarcodeKIStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    qty_to_receive = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'barcode_ki_stocks'


class CarelabelKIStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    qty_to_receive = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'carelabel_ki_stocks'


class BarcodeSEStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    qty_to_receive = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'barcode_se_stocks'

class CarelabelSEStocks(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    qty_to_receive = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    machine = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'carelabel_se_stocks'

class PrepLocations(models.Model):
    id = models.AutoField(primary_key=True)
    location = models.CharField(max_length=255)
    location_desc = models.CharField(max_length=255, null=True, blank=True)
    location_plant = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'prep_locations'

class PrintRequestLabels(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    po = models.CharField(max_length=255)
    type = models.CharField(max_length=255)
    style = models.CharField(max_length=255, null=True, blank=True)
    color = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    leader = models.CharField(max_length=255, null=True, blank=True)
    comment = models.CharField(max_length=255, null=True, blank=True)
    created = models.CharField(max_length=255, null=True, blank=True)
    printer = models.CharField(max_length=255, null=True, blank=True)
    qty = models.CharField(max_length=255, null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'print_request_labels'

class SecondQRequests(models.Model):
    id = models.AutoField(primary_key=True)
    po_id = models.IntegerField()
    user_id = models.IntegerField()
    ponum = models.CharField(max_length=255, null=True, blank=True)
    size = models.CharField(max_length=255, null=True, blank=True)
    qty = models.IntegerField(null=True, blank=True)
    module = models.CharField(max_length=255, null=True, blank=True)
    leader = models.CharField(max_length=255, null=True, blank=True)
    status = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=255, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    style = models.CharField(max_length=255, null=True, blank=True)
    color = models.CharField(max_length=255, null=True, blank=True)
    materiale = models.CharField(max_length=255, null=True, blank=True)
    tg2 = models.CharField(max_length=255, null=True, blank=True)
    desc = models.CharField(max_length=255, null=True, blank=True)
    ccc = models.CharField(max_length=255, null=True, blank=True)
    cd = models.CharField(max_length=255, null=True, blank=True)
    barcode = models.CharField(max_length=255, null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'secondq_requests'