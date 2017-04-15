from django.conf.urls import include, url
from django.contrib import admin
from meta import views

urlpatterns = [
    url(r'^card/', include('card.urls')),
    url(r'^deck/', include('deck.urls')),
    url(r'^format/(?P<format_id>[0-9]+)/?$', views.format, name='format'),
    url(r'^archetype/(?P<format_id>[0-9]+)/(?P<archetype_id>[0-9]+)/?$', views.archetype, name='archetype'),
    url(r'^admin/', admin.site.urls),
]
