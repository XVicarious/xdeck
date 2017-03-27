var ConvertCost = {
  colorCodes: ['W', 'U', 'B', 'R', 'G', 'C', 'X', 'Y', 'Z', 'P'],
  // todo: this needs support for infinity symbols, and tap symbols
  parse: function (string) {
    var idChars = ['/', '{', '}']; // characters we don't want to add to our build
    if (string == null || !string.trim()) { // if the string is null or empty
      return ''; // we are already done, return an empty string
    }
    var newFangledString = ''; // our new mana cost representation
    var build = ''; // for our longer identifiers we need to build up to it
    var iStart = '<i class="ms ms-cost ms-shadow ms-'; // the start for the <i> element with the proper classes for displaying the symbols
    var iEnd = '"/>'; // the end of the <i> element
    var iSplit = ' ms-split'; // for our split symbols
    for (var i = 0; i < string.length; i++) { // go over each and every character of the string
      var currentChar = string.charAt(i);
      var nextChar = string.charAt(i + 1);
      if (nextChar != null && nextChar === '}') { // this is a short easy one
        if (ConvertCost.colorCodes.indexOf(currentChar) > -1 || (currentChar >= '0' && currentChar <= '9')) { // if the next character is a color, or C, or X, Y, Z, or if its a digit
          if (build.trim()) { // if build has characters in it
            build += currentChar; // add the current character to the build
            newFangledString += (iStart + build.toLowerCase() + ((string.charAt(i - 1) === '/' && currentChar !== 'P') ? iSplit : '') + iEnd); // compile the mana symbols, and if the last character was a / and not P, it was split cost so add iSplit, otherwise don't add anything
            build = ''; // we are done with this symbol, reset build for the next one
          } else { // build is empty, meaning it is a short, easy symbol
            newFangledString += (iStart + currentChar.toLowerCase() + iEnd); // fix up this symbol, it was a short one
          }
        }
      } else { // it is a long one, take a seat
        if (idChars.indexOf(currentChar) === -1) { // if current character isn't one of our markers (/,{,})
          build += currentChar; // add this character to the build
        }
      }
    }
    return newFangledString; // the final mana cost, each symbol is an <i> tag with the proper classes from ManaCSS
  }
};
