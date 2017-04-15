from django.conf.urls import url
from . import views
urlpatterns = [
    url(r'^(?P<format_id>[0-9]+)/$', views.format, name='format')
    url(r'^(?P<format_id>[0-9]+)/(?P<archetype_id>[0-9]+)/$', views.archetype, name='archetype')
]
