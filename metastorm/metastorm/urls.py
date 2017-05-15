from django.conf.urls import include, url
from django.contrib import admin
from adminplus.sites import AdminSitePlus
from meta import views as metaView
from card import views as cardView
from .views import index

admin.site = AdminSitePlus()
admin.autodiscover()

urlpatterns = [
    url(r'^/?$', index),
    url(r'^card/', include('card.urls')),
    url(r'^token/(?P<token_id>[0-9]+)/?$', cardView.token, name='token'),
    url(r'^deck/', include('deck.urls')),
    url(r'^format/(?P<format_id>[0-9]+)/?$', metaView.format, name='format'),
    url(r'^archetype/(?P<format_id>[0-9]+)/(?P<archetype_id>[0-9]+)/?$', metaView.archetype, name='archetype'),
    # url(r'^meta/format/json/?$', views.json_format),
    # url(r'^meta/archetype/json/?$', views.json_archetype),
    url(r'^admin/', admin.site.urls),
]
