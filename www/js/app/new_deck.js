var deck = []; // initialize the deck

require(["materialize", "typeahead", "bloodhound"], function(materialize, typeahead) {
    var SCRYFALL_URL = "https://api.scryfall.com";
    var DATE_FORMAT = "YY/MM/DD";
    var TIME_FORMAT = "h:mm:ss";
    $(function() {
        var cardDatabase = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('cardName'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "php/list_cards.php?query=%QUERY",
                wildcard: "%QUERY"
            },
            limit: 15
        });
        $.post("php/list_decks.php", function(data) {
            data = JSON.parse(data);
            var $table = $("#decksList");
            for(var i = 0; i < data.length; i++) {
                var div = "<a class=\"collection-item\">";
                //div += moment(data[i]["dck_decks_date"]).format(DATE_FORMAT);
                div += "</a>"
                $table.append(div);
            }
        });
        $("input.typeahead").typeahead({minLength: 3, highlight: true}, {
            source: cardDatabase,
            name: "cardname",
            display: "cardName",
            limit: 15
        }).on("typeahead:selected", function(element, item) {
            addToDeck(item, $("#isSideboard").prop("checked"));
            writeTable();
        }).on("typeahead:autocompleted", function(element, item) {
            addToDeck(item, $("#isSideboard").prop("checked"));
            writeTable();
        });
        $("#saveDeck").click(function() {
            $.post("php/new_deck.php", {deck:JSON.stringify(deck)}, function(data) {
                console.log("Deck saved.");
                console.log(data);
            });
        });
        function addToDeck(item, isSideboard) {
            for (var i = 0; i < deck.length; i++) {
                if (deck[i][0] == item.id && deck[i][3] == isSideboard) {
                    break;
                }
            }
            if (i < deck.length) {
                deck[i][1] += parseInt($("#cardnameQuantity").val());
            } else {
                deck.push([item.id, parseInt($("#cardnameQuantity").val()), item.cardName, isSideboard]);
            }
        }
        function writeTable() {
            var $tbody = $("#tableBody");
            $tbody.empty();
            for (var i = 0; i < deck.length; i++) {
                var $tr = $("<tr/>").appendTo($tbody);
                $tr.append("<td>" + deck[i][1] + "</td>");
                $tr.append("<td>" + deck[i][2] + "</td>");
                $tr.append("<td>" + deck[i][3] + "</td>");
            }
        }
    });
});
