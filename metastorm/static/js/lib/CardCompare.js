/**
* @class Compare two card objects with cmc, cardName, type, sideboard, colors
*/
var CardCompare = {
  cardTypes: ['Creature', 'Planeswalker', 'Instant', 'Sorcery', 'Artifact', 'Enchantment', 'Land'],
  colors: 'WUBRG',
  nonColors: 'LAC',
  /**
   * @function
   * @name compareCMC
   * @argument card1 the first card to compare
   * @argument card2 the second card to compare
   * @return a negative value if card2's cmc is greater, positive if vice-versa, or 0 if they're the same
   */
  compareCMC: function (card1, card2) {
    return card1.cmc - card2.cmc;
  },
  compareName: function (card1, card2) {
    if (card1.cardName > card2.cardName) {
      return 1;
    } else if (card1.cardName < card2.cardName) {
      return -1;
    }
    return 0;
  },
  compareSuperType: function (card1, card2) {
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
  compareSideboard: function (card1, card2) {
    if (card1.sideboard && !card2.sideboard) {
      return 1;
    } else if (!card1.sideboard && card2.sideboard) {
      return -1;
    }
    return 0;
  },
  compareColors: function (card1, card2) {
    var color1 = CardCompare.getColors(card1.colors);
    var color2 = CardCompare.getColors(card2.colors);
    if (color1.length + color2.length === 0) {
      color1 = card1.colors; color2 = card2.colors;
      return CardCompare.priorityCompare(CardCompare.nonColors, color1, color2);
    }
    if (color1.length < color2.length) {
      return -1;
    } else if (color1.length > color2.length) {
      return 1;
    } else {
      return CardCompare.priorityCompare(CardCompare.color, color1, color2);
    }
  },
  priorityCompare: function (array, string1, string2) {
    var priority1, priority2;
    if (string1 === string2) {
      return 0;
    }
    for (var i = 0; i < Math.min(string1.length, string2.length); i++) {
      priority1 = array.indexOf(string1.charAt(i));
      priority2 = array.indexOf(string2.charAt(i));
      if (priority1 !== priority2) {
        return priority1 < priority2 ? -1 : 1;
      }
    }
    return 0;
  },
  getColors: function (colorString) {
    var validColors = '';
    var newColorString = '';
    if (colorString == null || !colorString.trim()) {
      return validColors;
    }
    colorString = colorString.split(',');
    for (var i = 0; i < colorString.length; i++) {
      if (colorString[i] === 'Blue') {
        newColorString += 'U';
      } else {
        newColorString += colorString[i].charAt(1);
      }
    }
    colorString = newColorString;
    for (i = 0; i < colorString.length; i++) {
      if (CardCompare.colors.indexOf(colorString.charAt(i)) > -1) {
        validColors += colorString.charAt(i);
      }
    }
    return validColors;
  },
  compareClassic: function (card1, card2) {
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
