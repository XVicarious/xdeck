from django.db import models
from django_mysql.models import SetCharField

# Create your models here.
class CardToken(models.Model):
    name = models.CharField(max_length=255)
    colors = SetCharField(base_field=models.CharField(max_length=50), max_length=(5 * 51), null=True)
    type = models.CharField(max_length=255)
    text = models.TextField(null=True)
    power = models.CharField(max_length=255, null=True)
    toughness = models.CharField(max_length=255, null=True)

 # Model to define cards that create a specific token
class CardToToken(models.Model):
    token = models.ForeignKey(CardToken)
    card = models.ForeignKey('card.Card')
