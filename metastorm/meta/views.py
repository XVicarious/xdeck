from django.shortcuts import render
from django.http import HttpResponse
from .models import Format

def format(request, format_id):
    fmt = Format.objects.get(pk=format_id)
    return render(request, 'format.html', {'format': fmt})

def archetype(request, format_id, archetype_id):
    return render(request, 'base.html')
