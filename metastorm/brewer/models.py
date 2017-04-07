from django.db import models

# Create your models here.
class Brewer(models.Model):
    brewerName = models.CharField(max_length=255, unique=True)
