from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0019_jobmanagementitem_jobmanagementitemlog'),
    ]

    operations = [
        migrations.AddField(
            model_name='jobmanagementitem',
            name='priority',
            field=models.CharField(blank=True, max_length=50, null=True),
        ),
        migrations.AddField(
            model_name='jobmanagementitemlog',
            name='priority',
            field=models.CharField(blank=True, max_length=50, null=True),
        ),
    ]
