from django.conf.urls import include, url
from django.contrib import admin
from adminplus.sites import AdminSitePlus
from meta import views

admin.site = AdminSitePlus()
admin.autodiscover()

urlpatterns = [
    url(r'^card/', include('card.urls')),
    url(r'^deck/', include('deck.urls')),
    url(r'^format/(?P<format_id>[0-9]+)/?$', views.format, name='format'),
    url(r'^archetype/(?P<format_id>[0-9]+)/(?P<archetype_id>[0-9]+)/?$', views.archetype, name='archetype'),
    #url(r'^meta/format/json/?$', views.json_format),
    #url(r'^meta/archetype/json/?$', views.json_archetype),
    url(r'^admin/', admin.site.urls),
]
