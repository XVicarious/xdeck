from django.db import models

# Create your models here.
class Deck(models.Model):
    brewerId = models.ForeignKey('brewer.Brewer')
    formatId = models.ForeignKey('format.Format')
    archetypeId = models.ForeignKey('archetype.Archetype')
    eventId = models.ForeignKey('event.Event')

class DeckCard(models.Model):
    deckId = models.ForeignKey(Deck)
    cardId = models.ForeignKey('card.Card')
    numberOf = models.IntegerField()
    isSideboard = models.BooleanField()
