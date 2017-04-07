from django.db import models

# Create your models here.
class Archetype(models.Model):
    archetypeName = models.CharField(max_length=255, unique=True)
