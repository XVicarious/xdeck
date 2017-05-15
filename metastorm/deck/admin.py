from dal import autocomplete
from django import forms
from django.contrib import admin
from django.shortcuts import render_to_response

from lxml import html
import requests
import time

from .models import Deck, DeckCard
from brewer.models import Brewer
from card.models import Card
from event.models import Event
from meta.models import Archetype, Format

def ImportView(request, *args, **kwargs):
    if (request.GET.get('importo') == 'mtgo'):
        _load_mtgo_stuff()
    return render_to_response('import.html')


admin.site.register_view('import', view=ImportView)

def _load_mtgo_stuff():
    wizards = "http://magic.wizards.com"
    mtgo_lists = "/en/content/deck-lists-magic-online-products-game-info"

    cached_cards = []

    page = requests.get(wizards + mtgo_lists)
    tree = html.fromstring(page.content)

    events = tree.xpath("//div[contains(@class,'article-item')]")
    for event in events:
        # Get the junk we need for each event (link and date)
        url = wizards + event[0].attrib['href']
        dates = event.xpath("//span[@class='date']")[0]
        month = dates[0].text.strip()
        event_date = str(dates[2].text).strip() + "-" + str(time.strptime(month.strip(), "%B").tm_mon) + "-" + str(dates[1].text).strip()
        # Then load up the event's page and get the deck sections and the event name
        event_page = requests.get(url)
        event_tree = html.fromstring(event_page.content)
        decks = event_tree.xpath("//div[@class='deck-group']")
        event_name = event_tree.xpath("//div[@id='main-content']/h1/text()")[0]
        # I'm sure there is a better way...
        possible_formats = event_name.split(' ')
        format_id = 0
        for p_format in possible_formats:
            try:
                format_id = Format.objects.get(name=p_format)
            except Exception as e:
                continue
        # We ran into a big issue, skip this one
        if (format_id == 0):
            continue
        # Check if the event exists already, if not create it and get the id
        try:
            event_obj = Event.objects.get(name__exact=event_name, format__exact=format_id, date__exact=event_date)
            if event_obj:
                continue
        except Exception:
            event_obj = Event(name=event_name, format=format_id, date=event_date)
            event_obj.save()
        for deck in decks:
            # Start with getting the person who piloted the deck
            # Try to get the pilot, if it doesn't exist add a new person
            deck_list = []
            brewer = deck.xpath("span[@class='deck-meta']/h4/text()")[0]
            brewer = brewer[:len(brewer)-6]
            print(event_name + ' ' + brewer)
            try:
                brewer_obj = Brewer.objects.get(name=brewer)
            except Exception:
                brewer_obj = Brewer(name=brewer)
                brewer_obj.save()
            # Get the deck contents and create a new deck
            deck_element = deck.xpath("div/div[@class='deck-list-text']/div[contains(@class, 'sorted-by-overview-container')]")[0]
            sideboard_element = deck.xpath("div/div[@class='deck-list-text']/div[contains(@class, 'sorted-by-sideboard-container')]")[0]
            deck_subs = deck_element.xpath("div[contains(@class, 'element')]/span[@class='row']")
            # Wizzars doesn't do archetypes in these so we will set them to 0 for now
            new_deck = Deck(brewer=brewer_obj, format=format_id, archetype=Archetype.objects.get(pk=1), event=event_obj)
            new_deck.save()
            for card in deck_subs:
                numberOf = card.xpath("span[@class='card-count']/text()")[0]
                cardName = card.xpath("span[@class='card-name']/a/text()")[0]
                if '//' in cardName:
                    cardName = cardName.split('//')[0].strip()
                try:
                    card_obj = Card.objects.get(cardName=cardName)
                except Exception as err:
                    print("OH NOOOOOOOOOOOOOOOOO " + cardName)
                    print(err)
                    new_deck.delete()
                    return
                deck_list.append(DeckCard(deck=new_deck, card=card_obj, numberOf=numberOf, isSideboard=False))
            side_subs = sideboard_element.xpath("span[contains(@class, 'row')]")
            for side in side_subs:
                numberOf = side.xpath("span[@class='card-count']/text()")[0]
                cardName = side.xpath("span[@class='card-name']/a/text()")[0]
                if '//' in cardName:
                    cardName = cardName.split('//')[0].strip()
                try:
                    card_obj = Card.objects.get(cardName=cardName)
                except Exception:
                    print("OH NOOOOOOOOOOOOOOOOO " + cardName)
                    print(err)
                    new_deck.delete()
                    return
                deck_list.append(DeckCard(deck=new_deck, card=card_obj, numberOf=numberOf, isSideboard=True))
            for deckcard in deck_list:
                deckcard.save()

class CardAutocomplete(autocomplete.Select2QuerySetView):
    def get_queryset(self):
        qs = Card.cardName.all()
        if self.q:
            qs = qs.filter(cardName__contains=self.q)
        return qs

class DeckCardForm(forms.ModelForm):
    class Meta:
        model = DeckCard
        fields = ('__all__')
        widgets = {
            'card': autocomplete.ModelSelect2(url='cardname-complete')
        }

class DeckCardInline(admin.TabularInline):
    model = DeckCard
    #def formfield_for_foreignkey(self, db_field, request=None, **kwargs):
    #    """Enable ordering drop-down alphabetically."""
    #    if db_field.name == 'card':
    #        kwargs['queryset'] = Card.objects.order_by("cardName")
    #    return super(DeckCardInline, self).formfield_for_foreignkey(db_field, request, **kwargs)

class DeckAdmin(admin.ModelAdmin):
    list_display = ['get_deck_name', 'get_brewer', ]
    #inlines = [DeckCardInline]
    def get_format(self, obj):
        return obj.format.name
    def get_archetype(self, obj):
        return obj.archetype.name
    def get_deck_name(self, obj):
        deck_name = "%s %s" % (obj.format.name, obj.archetype.name)
        return deck_name
    def get_brewer(self, obj):
        return obj.brewer.name
    get_deck_name.short_description = 'Deck Name'
    get_brewer.short_description = 'Brewer'

admin.site.register(Deck, DeckAdmin)
