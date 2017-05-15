from django.shortcuts import render
from deck.models import Deck

#from .models import Card

def index(request):
    try:
        recent_decks = Deck.objects.all().order_by('-id')[:10]
    except Exception:
        pass
    return render(request, 'index.html', {'recent_decks': recent_decks})
