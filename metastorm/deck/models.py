from django.db import models

# Create your models here.
class Deck(models.Model):
    brewer = models.ForeignKey('brewer.Brewer')
    format = models.ForeignKey('meta.Format')
    archetype = models.ForeignKey('meta.Archetype')
    event = models.ForeignKey('event.Event')

    @property
    def main_count(self):
        return self.__count(self.id, False)

    @property
    def side_count(self):
        return self.__count(self.id, True)

    # todo: don't think this needs self, figure out static?
    def __count(self, deckId, sideboard):
        count = 0
        for card in DeckCard.objects.filter(deck=deckId, isSideboard=sideboard):
            count += card.numberOf
        return count


class DeckCard(models.Model):
    deck = models.ForeignKey(Deck)
    card = models.ForeignKey('card.Card')
    numberOf = models.IntegerField()
    isSideboard = models.BooleanField()
