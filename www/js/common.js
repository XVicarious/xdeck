requirejs.config({
  'baseUrl': './js/lib',
  'paths': {
    'app': './js/app',
    'jquery': 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min',
    'hammer': 'https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min',
    'materialize': 'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min',
    'moment': 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min',
    'typeahead': 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.jquery.min',
    'bootstrap': 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min',
    'bloodhound': 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/bloodhound.min',
    'velocity': 'https://cdnjs.cloudflare.com/ajax/libs/velocity/1.5.0/velocity.min',
    'handlebars': 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min',
    'cardcompare': 'CardCompare',
    'convertcost': 'ConvertCost'
  },
  shim: {
    'materialize': {
      deps: ['jquery', 'hammer', 'velocity']
    },
    'moment': {
      deps: ['hammer']
    },
    'typeahead': {
      deps: ['jquery', 'hammer'],
      init: function ($) {
        return require.s.contexts._.registry['typeahead.js'].factory($);
      }
    },
    'bootstrap': {
      deps: ['hammer']
    }
  }
});
