from django.db import models
from django_mysql.models import SetCharField

# Create your models here.
class Card(models.Model):
    cardName = models.CharField(max_length=255, unique=True)
    layout = models.CharField(max_length=255)
    manaCost = models.CharField(max_length=255, null=True)
    cmc = models.IntegerField()
    colors = SetCharField(base_field=models.CharField(max_length=50), size=5, max_length=(5 * 51), null=True)
    type = models.CharField(max_length=255)
    text = models.TextField(null=True)
    power = models.CharField(max_length=255, null=True)
    toughness = models.CharField(max_length=255, null=True)
    loyalty = models.IntegerField(null=True)
    reserved = models.BooleanField()
    vintage = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    legacy = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    modern = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    standard = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    commander = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
