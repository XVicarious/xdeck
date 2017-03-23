var deck = []; // initialize the deck

require(["materialize", "typeahead", "bloodhound", "cardcompare"], function(materialize, typeahead) {
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
        /*$.post("php/list_decks.php", function(data) {
            data = JSON.parse(data);
            var $table = $("#decksList");
            for(var i = 0; i < data.length; i++) {
                var div = "<a class=\"collection-item\">";
                //div += moment(data[i]["dck_decks_date"]).format(DATE_FORMAT);
                div += "</a>"
                $table.append(div);
            }
        });*/
        console.log(CardCompare.compareCMC({"cmc": 3}, {"cmc": 2}));
        $("input.typeahead").typeahead({minLength: 3, highlight: true}, {
            source: cardDatabase,
            name: "cardname",
            display: "cardName",
            limit: 15
        }).on("typeahead:selected", function(element, item) {
            addToDeck(item, $("#isSideboard").prop("checked"));
            writeTable();
            deck.sort(CardCompare.compareCMC);
        }).on("typeahead:autocompleted", function(element, item) {
            addToDeck(item, $("#isSideboard").prop("checked"));
            writeTable();
            deck.sort(CardCompare.compareCMC);
        });
        $("#saveDeck").click(function() {
            var deckCards = cardsInDeck(); var sideCards = cardsInSideboard();
            if (deckCards >= 60 && sideCards <= 15) {
                $.post("php/new_deck.php", {deck:JSON.stringify(deck)}, function(data) {
                    console.log("Deck saved.");
                });
            } else if (deckCards < 60) {
                console.log("Not enough cards in deck. Allowed: 60 or more. You have: " + deckCards);
            } else if (sideCards > 15) {
                console.log("Too many cards in sideboard. Allowed: 15 or less. You have: " + sideCards);
            }
        });
        $(document).on("click", ".deck-card", function() {
            deck.splice($(this).attr("deck-card-id"), 1);
            writeTable();
        });
        $(document).on("click", "span.badge.quantity", function() {
            $(this).attr("contentEditable", true);
        }).on("blur", "span.badge.quantity", function() {
            $(this).attr("contentEditable", false);
            if ($(this).text() == "" || $(this).text() == "0") {
                $(this).text("1");
            }
            var deckCardId = $(this).parent("div").attr("deck-card-id");
            deck[deckCardId][1] = parseInt($(this).text());
            writeTable();
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
                deck.push([item.id, parseInt($("#cardnameQuantity").val()), item.cardName, isSideboard, item.manaCost]);
            }
        }
        function writeTable() {
            var $tbody = $("#deckView");
            var $sideboard = $("#sideView");
            $tbody.empty(); $sideboard.empty();
            for (var i = 0; i < deck.length; i++) {
                $editing = $tbody;
                if (deck[i][3]) {
                    $editing = $sideboard;
                }
                var $tr = $("<div deck-card-id=\"" + i + "\"/>").appendTo($editing);
                $tr.append("<span class=\"badge left quantity\">" + deck[i][1] + "</span>");
                $tr.append("<span>" + deck[i][2] + "</span>");
                $tr.append("<span class=\"secondary-content\">" +
                           "<span id=\"manaCost\">" + deck[i][4] + "</span>" +
                           "<a href=\"#\" class=\"deck-card\" deck-card-id=\"" + i + "\"><i class=\"material-icons\">delete</i></a>"+
                           "</span>");
            }
        }
        function cardsInDeck() {
            var cards = 0;
            for (var i = 0; i < deck.length; i++) {
                if (!deck[i][3]) {
                    cards += deck[i][1];
                }
            }
            return cards;
        }
        function cardsInSideboard() {
            var cards = 0;
            for (var i = 0; i < deck.length; i++) {
                if (deck[i][3]) {
                    cards += deck[i][1];
                }
            }
            return cards;
        }
    });
});
