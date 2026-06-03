from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0021_operator_and_data'),
    ]

    operations = [
        migrations.AddField(
            model_name='operator',
            name='status',
            field=models.CharField(default='ACTIVE', max_length=20),
        ),
    ]
