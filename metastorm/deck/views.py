from django.shortcuts import render
from django.http import Http404

from .models import Deck,DeckCard

# Create your views here.
def detail(request, deck_id):
    try:
        deck = Deck.objects.get(pk=deck_id)
        deckCards = DeckCard.objects.filter(deck=deck_id, isSideboard=False)
        sideCards = DeckCard.objects.filter(deck=deck_id, isSideboard=True)
    except Deck.DoesNotExist or DeckCard.DoesNotExist:
        raise Http404('Deck does not exist')
    return render(request, 'deck.html', {'deck': deck, 'cards': deckCards, 'side': sideCards})
