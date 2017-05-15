from django.shortcuts import render
from django.http import HttpResponse, Http404

from .models import Card, CardToken, CardToToken
from deck.models import Deck, DeckCard

TAG_CHARS = ['/', '{', '}', '(', ')']
COLOR_CODES = ['W', 'U', 'B', 'R', 'G', 'P', 'C', 'X', 'Y', 'Z', 'T', 'S', '∞', 'h', 'r', 'w']

# Create your views here.
def index(request):
    return HttpResponse("Hola, bienvenido a metastorm")

def detail(request, card_id):
    """
    The card's detailed view. Shows the card's name, mana cost, and rules text.
    It also shows recent decks with said card, and the formats it is legal in.
    """
    try:
        card = Card.objects.get(pk=card_id)
        # todo: recent decks returns DeckCards, we want the decks
        recent_decks = DeckCard.objects.filter(card=card_id)
    except Card.DoesNotExist:
        raise Http404('Card does not exist')
    except DeckCard.DoesNotExist:
        pass
    return render(request, 'card.html', {'card': card, 'recent_decks': recent_decks})

def token(request, token_id):
    try:
        tkn = CardToken.objects.get(pk=token_id)
        relatedCards = CardToToken.objects.filter(token_id=tkn)
    except CardToken.DoesNotExist:
        pass
    return render(request, 'token.html', {'token': tkn, 'related_cards': relatedCards});
