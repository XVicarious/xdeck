from dal import autocomplete
from django import forms
from django.contrib import admin

from .models import Deck, DeckCard
from brewer.models import Brewer
from card.models import Card

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
    def formfield_for_foreignkey(self, db_field, request=None, **kwargs):
        """enable ordering drop-down alphabetically"""
        if db_field.name == 'card':
            kwargs['queryset'] = Card.objects.order_by("cardName")
        return super(DeckCardInline, self).formfield_for_foreignkey(db_field, request, **kwargs)

@admin.register(Deck)
class DeckAdmin(admin.ModelAdmin):
    list_display = ['get_deck_name', 'get_brewer',]
    inlines = [DeckCardInline]
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
