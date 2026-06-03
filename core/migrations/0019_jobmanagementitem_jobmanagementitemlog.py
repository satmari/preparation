from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0018_add_material_to_print_request_labels'),
    ]

    operations = [
        migrations.CreateModel(
            name='JobManagementItem',
            fields=[
                ('id', models.AutoField(primary_key=True, serialize=False)),
                ('pro', models.CharField(max_length=255)),
                ('pro_new', models.CharField(blank=True, max_length=50, null=True)),
                ('pro_id', models.IntegerField(blank=True, null=True)),
                ('location', models.CharField(blank=True, max_length=255, null=True)),
                ('skeda', models.CharField(blank=True, max_length=255, null=True)),
                ('print_type', models.CharField(max_length=20)),
                ('pro_print_type', models.CharField(max_length=300, unique=True)),
                ('qty', models.IntegerField(blank=True, null=True)),
                ('status', models.CharField(default='NEW', max_length=50)),
                ('operator', models.CharField(blank=True, max_length=255, null=True)),
                ('created_at', models.DateTimeField(auto_now_add=True)),
                ('updated_at', models.DateTimeField(auto_now=True)),
            ],
            options={
                'db_table': 'job_management_items',
            },
        ),
        migrations.AlterUniqueTogether(
            name='jobmanagementitem',
            unique_together={('pro', 'print_type')},
        ),
        migrations.CreateModel(
            name='JobManagementItemLog',
            fields=[
                ('id', models.AutoField(primary_key=True, serialize=False)),
                ('pro', models.CharField(max_length=255)),
                ('pro_new', models.CharField(blank=True, max_length=50, null=True)),
                ('pro_id', models.IntegerField(blank=True, null=True)),
                ('location', models.CharField(blank=True, max_length=255, null=True)),
                ('skeda', models.CharField(blank=True, max_length=255, null=True)),
                ('print_type', models.CharField(max_length=20)),
                ('pro_print_type', models.CharField(max_length=300)),
                ('qty', models.IntegerField(blank=True, null=True)),
                ('status', models.CharField(blank=True, max_length=50, null=True)),
                ('operator', models.CharField(blank=True, max_length=255, null=True)),
                ('created_at', models.DateTimeField(auto_now_add=True)),
                ('updated_at', models.DateTimeField(auto_now=True)),
            ],
            options={
                'db_table': 'job_management_items_log',
            },
        ),
    ]
