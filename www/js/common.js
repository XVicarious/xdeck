requirejs.config({
    "baseUrl": "js/lib",
    "paths": {
        "app": "../app",
        "jquery": "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min",
        "hammer": "https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min",
        "materialize": "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min",
        "moment": "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min"
    },
    shim: {
        "materialize": {
            deps: ["jquery", "hammer"]
        },
        "moment" : {
            deps: ["hammer"]
        }
    }
});
