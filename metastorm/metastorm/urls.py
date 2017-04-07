from django.conf.urls import include, url
from django.contrib import admin

from . import views

urlpatterns = [
    url(r'^card/', include('card.urls')),
    #url(r'^format/([0-9]+)/?$', views.format),
    #url(r'^archetype/([0-9]+)/([0-9]+)/?$', views.archetype),
    #url(r'^deck/([0-9]+)/?$', views.deck)
    url(r'^admin/', admin.site.urls),
]
