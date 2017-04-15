from django.contrib import admin

from .models import Card

@admin.register(Card)
class CardAdmin(admin.ModelAdmin):
    list_display = ['cardName', 'html_cost', 'type']
    def html_cost(self, obj):
        return obj.html_cost
    html_cost.allow_tags = True
    class Media:
        css = { 'all': ('css/mana.min.css',) }
