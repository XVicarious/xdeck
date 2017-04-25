requirejs.config({
  'baseUrl': 'js/lib',
  'paths': {
    'app': 'js/app',
    'moment': 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min',
    'bootstrap': 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min',
    'velocity': 'https://cdnjs.cloudflare.com/ajax/libs/velocity/1.5.0/velocity.min',
    'handlebars': 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min',
    'jquery': 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min',
    'typeahead': 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.jquery.min',
    'bloodhound': 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/bloodhound',
    'hammerjs': 'https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min',
    // 'convertcost': 'ConvertCost',
    // 'cardcompare': 'CardCompare',
  },
  'shim': {
    materialize: {
      deps: ['jquery', 'hammerjs', 'velocity'],
    },
    moment: {
      deps: ['hammerjs'],
    },
    typeahead: {
      deps: ['jquery', 'hammerjs'],
      init: function($) {
        return require.s.contexts._.registry['typeahead.js'].factory($);
      },
    },
    bloodhound: {
      deps: ['jquery'],
      exports: 'Bloodhound',
    },
    bootstrap: {
      deps: ['hammerjs'],
    },
    velocity: {
      exports: 'Vel',
    },
  },
});
