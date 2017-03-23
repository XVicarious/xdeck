require(["jquery", "cardcompare"], function($) {
    var cardTypes = ["Creature", "Planeswalker", "Instant", "Sorcery",
                     "Artifact", "Enchantment", "Land"];
    var colors = "WUBRG";
    var nonColors = "LAC";
    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    $(function() {
        $.get("php/get_deck.php", {id: $_GET['id']}, function(data) {
            data = JSON.parse(data);
            data.sort(CardCompare.compareClassic);
            function compareColors(color1, color2) {
                var color1s = CardCompare.getColors(color1); var color2s = CardCompare.getColors(color2);
                var priority1; var priority2;
                if (color1s.length + color2s.length == 0) {
                    color1s = color1; color2s = color2;
                    for (var u = 0; u < Math.min(color1s.length, color2s.length); u++) {
                        priority1 = nonColors.indexOf(color1s.charAt(u));
                        priority2 = nonColors.indexOf(color2s.charAt(u));
                        if (priority1 != priority2) {
                            return priority1 < priority2 ? -1 : 1;
                        }
                    }
                    return 0;
                }
                if (color1s.length < color2s.length) {
                    return -1;
                } else if (color1s.length > color2s.length) {
                    return 1;
                } else {
                    for (var u = 0; u < Math.min(color1s.length, color2s.length); u++) {
                        priority1 = colors.indexOf(color1s.charAt(u));
                        priority2 = colors.indexOf(color2s.charAt(u));
                        if (priority1 != priority2) {
                            return priority1 < priority2 ? -1 : 1;
                        }
                    }
                    return 0;
                }
            }
            $("#deckDate").text(data[0]["ddate"]);
            var $deck = $("#deck");
            var $side = $("#sideboard");
            var $editing = $deck;
            for (var i = 0; i < data.length; i++) {
                var isSideboard = Boolean(parseInt(data[i]["sideboard"]));
                $editing = $deck;
                if (isSideboard) {
                    $editing = $side;
                }
                $editing.append(
                    "<a class=\"collection-item\">" +
                    "<span class=\"badge left\">" + data[i]["numberOf"] + "</span>" +
                    "<span>" + data[i]["cardName"] + "</span>" +
                    "<span class=\"secondary-content\">" + data[i]["manaCost"] + "</span></span>" +
                    "</a>"
                );
            }
        });
    });
});
