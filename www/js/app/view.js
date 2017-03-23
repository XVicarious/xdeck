require(["jquery"], function($) {
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
            data.sort(function(a, b) {
                var type1 = a["type"]; var type2 = b["type"];
                var cmc1 = a["cmc"]; var cmc2 = b["cmc"];
                var name1 = a["cardName"]; var name2 = b["cardName"];
                var color1 = a["manaCost"]; var color2 = b["manaCost"];
                for (var i = 0; i < cardTypes.length; i++) {
                    var type1includes = type1.includes(cardTypes[i]);
                    var type2includes = type2.includes(cardTypes[i]);
                    if (type1includes && type2includes) {
                        var cmcCompare = compareCMC(cmc1, cmc2);
                        if (cmcCompare == 0) {
                            var colorCompare = 0; //compareColors(color1, color2);
                            if (colorCompare == 0) {
                                return compareName(name1, name2);
                            }
                            return colorCompare;
                        }
                        return cmcCompare;
                    } else if (type1includes && !type2includes) {
                        return -1;
                    } else if (!type1includes && type2includes) {
                        return 1;
                    }
                }
                return 0;
            });
            function getColors(colorString) {
                var validColors = "";
                if (colorString === null || !colorString.trim()) {
                    return "";
                }
                console.log(colors);
                for (var s = 0; s < colorString.length; s++) {
                    if (colors.indexOf(colorString.charAt(s)) > -1) {
                        validColors += colorString.charAt(s);
                    }
                }
                return validColors;
            }
            function compareColors(color1, color2) {
                var color1s = getColors(color1); var color2s = getColors(color2);
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
            function compareCMC(cmc1, cmc2) {
                if (cmc1 === cmc2) {
                    return 0;
                } else if (cmc1 > cmc2) {
                    return 1;
                }
                return -1;
            }
            function compareName(name1, name2) {
                if (name1 < name2) {
                    return -1;
                } else if (name1 > name2) {
                    return 1;
                }
                return 0;
            }
            console.log(data);
            $("#deckDate").text(data[0]["dck_decks_date"]);
            for (var i = 0; i < data.length; i++) {
                var cardType = data[i]["type"];
                var isSideboard = data[i]["dck_deckcards_sideboard"];
                for (var j = 0; j < cardTypes.length; j++) {
                    /*if (isSideboard == 1) {
                        $("#sideboard").append(
                            "<a class=\"collection-item\""> +
                            "<span>" + data[i]["dck_deckcards_quantity"] + "</span>" +
                            " <span>" + data[i]["cardName"] + "</span>" +
                            "<span class=\"secondary-content\">" + data[i]["manaCost"] + "</span>" +
                            "</a>"
                        );
                        data.splice(i, 1);
                        i--;
                        break;
                    } else */if (cardType.includes(cardTypes[j])) {
                        $("#deck").append(
                            "<a class=\"collection-item\">" +
                            "<span>" + data[i]["dck_deckcards_quantity"] + "</span>" +
                            " <span>" + data[i]["cardName"] + "</span>" +
                            "<span class=\"secondary-content\">" + data[i]["manaCost"] + "</span>" +
                            "</a>"
                        );
                        data.splice(i, 1);
                        i--;
                        break;
                    }
                }
            }
        });
    });
});
