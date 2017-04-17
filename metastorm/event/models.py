from django.db import models

# Create your models here.
class Event(models.Model):
    name = models.CharField(max_length=255)
    date = models.DateField()
    format = models.ForeignKey('meta.Format')