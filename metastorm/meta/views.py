from django.shortcuts import render
from django.http import HttpResponse
from django.core import serializers
import json
from .models import Format, Archetype

def format(request, format_id):
    fmt = Format.objects.get(pk=format_id)
    return render(request, 'format.html', {'format': fmt})

def archetype(request, format_id, archetype_id):
    return render(request, 'base.html')

def json_format(request):
    return HttpResponse(json.dumps(__get_json(Format)), content_type="application/json")

def json_archetype(request):
    return HttpResponse(json.dumps(__get_json(Archetype)), content_type="application/json")

def __get_json(model):
    JSONSerializer = serializers.get_serializer("json")
    json_serializer = JSONSerializer()
    json_serializer.serialize(model.objects.all())
    return json_serializer.getvalue()
