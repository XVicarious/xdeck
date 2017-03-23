var CardCompare = {
    cardTypes: ["Creature", "Planeswalker", "Instant", "Sorcery", "Artifact", "Enchantment", "Land"],
    colors: "WUBRG",
    nonColors: "LAC",
    compareCMC: function(card1, card2) {
        if (card1.cmc > card2.cmc) {
            return 1;
        } else if (card1.cmc < card2.cmc) {
            return -1;
        }
        return 0;
    },
    compareName: function(card1, card2) {
        if (card1.cardName > card2.cardName) {
            return 1;
        } else if (card1.cardName < card2.cardName) {
            return -1;
        }
        return 0;
    },
    compareSuperType: function(card1, card2) {
        for (var i = 0; i < CardCompare.cardTypes.length; i++) {
            var type1include = card1.type.includes(CardCompare.cardTypes[i]);
            var type2include = card2.type.includes(CardCompare.cardTypes[i]);
            if (type1include && type2include) {
                return 0;
            } else if (type1include && !type2include) {
                return -1;
            } else if (!type1include && type2include) {
                return 1;
            }
        }
        return 0;
    },
    compareSideboard: function(card1, card2) {
        if (card1.sideboard && !card2.sideboard) {
            return 1;
        } else if (!card1.sideboard && card2.sideboard) {
            return -1;
        }
        return 0;
    },
    compareColors: function(card1, card2) {
        return 0; // todo: implement this properly
    },
    getColors: function(colorString) {
        var validColors = "";
        if (colorString == null || !colorString.trim()) {
            return validColors;
        }
        for (var i = 0; i < colorString.length; i++) {
            if (CardCompare.colors.indexOf(colorString.charAt(i)) > -1) {
                validColors += colorString.charAt(i);
            }
        }
        return validColors;
    },
    compareClassic: function(card1, card2) {
        var sideboardCompare = CardCompare.compareSideboard(card1, card2);
        if (sideboardCompare === 0) {
            var supertypeCompare = CardCompare.compareSuperType(card1, card2);
            if (supertypeCompare === 0) {
                var cmcCompare = CardCompare.compareCMC(card1, card2);
                if (cmcCompare === 0) {
                    var colorCompare = CardCompare.compareColors(card1, card2);
                    if (colorCompare === 0) {
                        return CardCompare.compareName(card1, card2);
                    }
                    return colorCompare;
                }
                return cmcCompare;
            }
            return supertypeCompare;
        }
        return sideboardCompare;
    }
};
