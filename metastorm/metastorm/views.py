from django.shortcuts import render

#from .models import Card

def index(request):
    return HttpResponse("Hola, bienvenido a metastorm")

#def card(cardId):
    # todo: write up a Card module?
    #card = 0;
    #context = {'card', card}
