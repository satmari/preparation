from django.db import migrations, models


OPERATORS = [
    'Abraham Zofia',
    'Stojkovic Mirjana',
    'Brear Miroslav',
    'Sedlak Gordana',
    'Tikvicki Sandra',
    'Aniko Lerinc',
]


def insert_operators(apps, schema_editor):
    Operator = apps.get_model('core', 'Operator')
    for name in OPERATORS:
        Operator.objects.get_or_create(operator_name=name)


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0020_jobmanagementitem_priority_jobmanagementitemlog_priority'),
    ]

    operations = [
        migrations.CreateModel(
            name='Operator',
            fields=[
                ('id', models.AutoField(primary_key=True, serialize=False)),
                ('operator_name', models.CharField(max_length=255)),
            ],
            options={
                'db_table': 'operators',
            },
        ),
        migrations.RunPython(insert_operators, migrations.RunPython.noop),
    ]
