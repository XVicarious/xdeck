from django.shortcuts import render
from django.http import HttpResponse, Http404

from .models import Card

# Create your views here.
def index(request):
    return HttpResponse("Hola, bienvenido a metastorm")

def detail(request, card_id):
    try:
        card = Card.objects.get(pk=card_id)
    except Card.DoesNotExist:
        raise Http404('Card does not exist')
    return render(request, 'detail.html', {'card': card})
