require(["materialize", "moment"], function(materialize, moment) {
    var SCRYFALL_URL = "https://api.scryfall.com";
    var DATE_FORMAT = "YY/MM/DD";
    var TIME_FORMAT = "h:mm:ss";
    var deck = []; // initialize the deck
    $(function() {
        $.post("php/list_decks.php", function(data) {
            data = JSON.parse(data);
            var $table = $("#decksList");
            for(var i = 0; i < data.length; i++) {
                var div = "<a class=\"collection-item\">";
                div += moment(data[i]["dck_decks_date"]).format(DATE_FORMAT);
                div += "</a>"
                $table.append(div);
            }
        });
        //$.post("php/new_deck.php", {deck:JSON.stringify([[4, "Lightning Bolt", false],[4, "Rift Bolt", false],[4, "Atarka's Command", false]]), userId: 0, tournament: 0}, function(data){console.log(data)});
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
                for (var i = 0; i < deck.length; i++) {
                    if (deck[i][1] == $("#cardnameSearch").val() && deck[i][2] == $("#isSideboard").prop("checked")) {
                        break; // the card was found, break out of the loop so we can take care of it
                    }
                }
                /* Right now we are going to assume our user knows what he's
                 * doing and not check if it is a valid card. */
                // todo: check if it is a valid card
                if (i < deck.length) {
                    deck[i][0] += parseInt($("#cardnameQuantity").val()); // we already have the card, just add to the total number of cards
                } else {
                    deck.push([parseInt($("#cardnameQuantity").val()), $("#cardnameSearch").val(), $("#isSideboard").prop("checked")]); // push the data to the array as an object
                }
                //$.get(SCRYFALL_URL+"/cards/named", {exact: $("#cardnameSearch").val()}, function(data) {
                //}, "json");
                writeTable(); // update the card table
            }
        });
        $("#saveDeck").click(function() {
            $.post("php/new_deck.php", {deck:JSON.stringify(deck)}, function(data) {
                materialize.toast("Deck saved.");
                console.log("Deck saved.")
            });
        });
        function writeTable() {
            var $tbody = $("#tableBody");
            $tbody.empty();
            for (var i = 0; i < deck.length; i++) {
                var $tr = $("<tr/>").appendTo($tbody);
                $tr.append("<td>" + deck[i][0] + "</td>");
                $tr.append("<td>" + deck[i][1] + "</td>");
                $tr.append("<td>" + deck[i][2] + "</td>");
            }
        }
    });
});
