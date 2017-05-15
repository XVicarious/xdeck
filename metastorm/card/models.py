from django.db import models
from django_mysql.models import SetCharField
import re, logging

# Create your models here.
class Card(models.Model):

    COST_PATTERN = r"(({(h[wubrg]|\u221E|\u00BD|(([0-9]+|[WUBRGCXYZST])(\/[WUBRGP])?))})|\(.*\))"

    cardName = models.CharField(max_length=255, unique=True)
    layout = models.CharField(max_length=255)
    manaCost = models.CharField(max_length=255, null=True)
    cmc = models.IntegerField()
    colors = SetCharField(base_field=models.CharField(max_length=50), size=5, max_length=(5 * 51), null=True)
    type = models.CharField(max_length=255)
    text = models.TextField(null=True)
    power = models.CharField(max_length=255, null=True)
    toughness = models.CharField(max_length=255, null=True)
    loyalty = models.CharField(max_length=255, null=True)
    reserved = models.BooleanField()
    vintage = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    legacy = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    modern = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    standard = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)
    commander = SetCharField(base_field=models.CharField(max_length=50), size=1, max_length=50, null=True)

    def __str__(self):
        return self.cardName

    @property
    def html_cost(self):
        return self.transform_tags(self.manaCost)

    @property
    def html_text(self):
        return self.transform_tags(self.text)

    def transform_tags(self, string):
        if (string == None or string == ''):
            return ''
        complete = ''
        empty = ''
        regex = re.compile(self.COST_PATTERN)
        last_index = 0
        string = "<br />".join(string.split('\n'))
        for tag in regex.finditer(string):
            if (tag.group()[0] == '{'):
                raw = tag.group()[1:-1].lower()
                bool_split = raw.find('/') != -1 and raw.find('p') == -1
                split = ' ms-split' if bool_split else ''
                bool_half = raw[0] == 'h'
                if bool_half: # Scott says this is bad, fairly certain it isn't
                    raw = raw[1:]
                raw = empty.join(raw.split('/'))
                if raw == 't':
                    raw += 'ap'
                elif raw == "\u221E":
                    raw = 'infinity'
                elif raw == "\u00BD":
                    raw = 'half'
                # The previous 6 lines might be able to be cleaned up
                html_tag = "<i class=\"ms ms-cost ms-shadow ms-%s%s\"></i>" % (raw, split)
                if bool_half:
                    html_tag = '<span class="ms-half">' + html_tag + '</span>'
            else:
                html_tag = '<span class="reminder">%s</span>' % tag.group()
            complete += string[last_index:tag.start()] + html_tag
            last_index = tag.end()
        complete += string[last_index:]
        return complete

class CardToken(models.Model):

    COST_PATTERN = r"(({(h[wubrg]|\u221E|\u00BD|(([0-9]+|[WUBRGCXYZST])(\/[WUBRGP])?))})|\(.*\))"

    name = models.CharField(max_length=255)
    colors = SetCharField(base_field=models.CharField(max_length=50), max_length=(5 * 51), null=True)
    type = models.CharField(max_length=255)
    text = models.TextField(null=True)
    power = models.CharField(max_length=255, null=True)
    toughness = models.CharField(max_length=255, null=True)

    @property
    def html_text(self):
        return self.transform_tags(self.text)

    def transform_tags(self, string):
        if (string == None or string == ''):
            return ''
        complete = ''
        empty = ''
        regex = re.compile(self.COST_PATTERN)
        last_index = 0
        string = "<br />".join(string.split('\n'))
        for tag in regex.finditer(string):
            if (tag.group()[0] == '{'):
                raw = tag.group()[1:-1].lower()
                bool_split = raw.find('/') != -1 and raw.find('p') == -1
                split = ' ms-split' if bool_split else ''
                bool_half = raw[0] == 'h'
                if bool_half: # Scott says this is bad, fairly certain it isn't
                    raw = raw[1:]
                raw = empty.join(raw.split('/'))
                if raw == 't':
                    raw += 'ap'
                elif raw == "\u221E":
                    raw = 'infinity'
                elif raw == "\u00BD":
                    raw = 'half'
                # The previous 6 lines might be able to be cleaned up
                html_tag = "<i class=\"ms ms-cost ms-shadow ms-%s%s\"></i>" % (raw, split)
                if bool_half:
                    html_tag = '<span class="ms-half">' + html_tag + '</span>'
            else:
                html_tag = '<span class="reminder">%s</span>' % tag.group()
            complete += string[last_index:tag.start()] + html_tag
            last_index = tag.end()
        complete += string[last_index:]
        return complete

 # Model to define cards that create a specific token
class CardToToken(models.Model):
    token = models.ForeignKey(CardToken)
    card = models.ForeignKey(Card)
