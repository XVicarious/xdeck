# -*- coding: utf-8 -*-
# Generated by Django 1.11 on 2017-04-17 15:16
from __future__ import unicode_literals

from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    initial = True

    dependencies = [
        ('card', '0001_initial'),
        ('brewer', '0001_initial'),
        ('meta', '__first__'),
        ('event', '__first__'),
    ]

    operations = [
        migrations.CreateModel(
            name='Deck',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('archetype', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='meta.Archetype')),
                ('brewer', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='brewer.Brewer')),
                ('event', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='event.Event')),
                ('format', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='meta.Format')),
            ],
        ),
        migrations.CreateModel(
            name='DeckCard',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('numberOf', models.IntegerField()),
                ('isSideboard', models.BooleanField()),
                ('card', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='card.Card')),
                ('deck', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='deck.Deck')),
            ],
        ),
    ]
