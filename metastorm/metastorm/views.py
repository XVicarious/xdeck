from django.shortcuts import render

#from .models import Card

def index(request):
    return HttpResponse("Hola, bienvenido a metastorm")
