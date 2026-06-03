from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0022_operator_status'),
    ]

    operations = [
        migrations.AddField(
            model_name='jobmanagementitemlog',
            name='created_new_at',
            field=models.DateTimeField(blank=True, null=True),
        ),
        migrations.AddField(
            model_name='jobmanagementitemlog',
            name='assigned_at',
            field=models.DateTimeField(blank=True, null=True),
        ),
    ]
