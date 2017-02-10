define(["jquery", "jqueryui"], function($) {
    var SCRYFALL_URL = "https://api.scryfall.com"
    var deck = []; // initialize the deck
    $(function() {
        $.post("php/new_deck.php", {deck:JSON.stringify([[4, "Lightning Bolt", false],[4, "Rift Bolt", false],[4, "Atarka's Command", false]]), userId: 0, tournament: 0}, function(data){console.log(data)});
        $("#cardnameSearch").keydown(function(event) {
            if (event.which == 9) { // tab
                event.preventDefault();
                $.get(SCRYFALL_URL+"/cards/autocomplete", {q: $("#cardnameSearch").val()}, function(data) {
                    $("#cardnameSearch").autocomplete({
                        source: data["data"]
                    }, "json");
                });
                var press = $.Event("keydown");
                press.ctrlKey = false;
                press.which = 40; // down
                $(this).trigger(press); // todo: this doesn't work the first time
            } else if (event.which == 13) { // enter
                //$.get(SCRYFALL_URL+"/cards/named", {exact: $("#cardnameSearch").val()}, function(data) {
                    deck.push([$("#cardnameQuantity").val(), $("#cardnameSearch")]); // push the data to the array as an object
                    // todo: call update to deck table
                //}, "json");
            }
        });
    });
});
