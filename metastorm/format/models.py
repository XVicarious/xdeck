from django.db import models

# Create your models here.
class Format(models.Model):
    formatName = models.CharField(max_length=255, unique=True)
