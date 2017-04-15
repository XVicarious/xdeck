from django.db import models
from deck.models import DeckCard

# Create your models here.
class Archetype(models.Model):
    name = models.CharField(max_length=255, unique=True)

    def __str__(self):
        return self.name

class Format(models.Model):
    name = models.CharField(max_length=255, unique=True)

    @property
    def top_cards(self, limit=10):
        try:
            cards = DeckCard.objects.filter(deck__format__id=self.id, isSideboard=False)
        except DeckCard.DoesNotExist:
            pass
        card_totals = []
        for card in cards:
            found = False
            for c in card_totals:
                if c['name'] == card.card.cardName:
                    c['numberOf'] += card.numberOf
                    found = True
                    break;
            if (not found):
                card_totals.append({'name':card.card.cardName, 'numberOf':card.numberOf})
        return card_totals

    def __str__(self):
        return self.name
