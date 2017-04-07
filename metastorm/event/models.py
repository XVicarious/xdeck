from django.db import models

# Create your models here.
class Event(models.Model):
    eventName = models.CharField(max_length=255)
    eventDate = models.DateField()
