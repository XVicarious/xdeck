from django.conf.urls import url
from . import views
from .admin import CardAutocomplete
urlpatterns = [
    url(r'^(?P<deck_id>[0-9]+)/$', views.detail, name='detail'),
    url(
        r'^cardname-complete/$',
        CardAutocomplete.as_view(),
        name='cardname-complete',
    ),
]
