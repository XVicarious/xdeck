const ConvertCost = {
  colorCodes: ['W', 'U', 'B', 'R', 'G', 'C', 'X', 'Y',
               'Z', 'P', 'T', 'S', '∞', 'h', 'r', 'w'],
  // todo: this needs support for infinity symbols, and tap symbols
  parse: function(string) {
    const idChars = ['/', '{', '}', '(', ')']; // characters we don't want to add to our build
    if (string == null || !string.trim()) { // if the string is null or empty
      return ''; // we are already done, return an empty string
    }
    let newFangledString = ''; // our new mana cost representation
    let build = ''; // for our longer identifiers we need to build up to it
    const iStart = '<i class="ms ms-cost ms-shadow ms-'; // the start for the <i> element with the proper classes for displaying the symbols
    const iEnd = '"/>'; // the end of the <i> element
    let hEnd = ''; // used in cases of half mana
    let lastEndTag = -1;
    let status = 0;
    const iSplit = ' ms-split'; // for our split symbols
    string = string.replace(/(\r\n|\n|\r)/gm, '<br/>'); // convert various newlines to html newlines
    for (let i = 0; i < string.length; i++) { // go over each and every character of the string
      let currentChar = string.charAt(i);
      if (idChars.indexOf(currentChar) !== -1 || ConvertCost.colorCodes.indexOf(currentChar) !== -1 || (currentChar >= '0' && currentChar <= '9')) {
        let nextChar = string.charAt(i + 1);
        if (currentChar === '{' || currentChar === '(' || currentChar === ')') { // if we are starting a new mana symbol
          if (currentChar === '{') {
            status = 1;
          }
          if (i >= 0 && lastEndTag <= i - 1) { // if our current position is after the last time we ended a tag
            if (currentChar === ')') {
              i++;
            }
            newFangledString += string.substring(lastEndTag + 1, i); // add everything from the last tag to just before now to our string
            if (currentChar === '(') {
              newFangledString += '<span class="reminder">';
            } else if (currentChar === ')') {
              newFangledString += '</span>';
            }
            lastEndTag = i - 1;
          }
        } else if (currentChar === '(') {
          console.log(string.substring(lastEndTag + 1, i));
        }
        if (nextChar != null && nextChar === '}' && status === 1) { // this is a short easy one
          lastEndTag = i + 1;
          status = 0;
          if (ConvertCost.colorCodes.indexOf(currentChar) > -1 || (currentChar >= '0' && currentChar <= '9')) { // if the next character is a color, or C, or X, Y, Z, or if its a digit
            if (build.trim()) { // if build has characters in it
              build += currentChar; // add the current character to the build
              if (build === 'hw' || build === 'hr') { // if we have those half manas
                newFangledString += '<span class="ms-half">'; // half mana are special cases...
                build = build.charAt(1); // we need the color of the mana
                hEnd = '</span>';
              }
              newFangledString += (iStart + build.toLowerCase() + ((string.charAt(i - 1) === '/' && currentChar !== 'P') ? iSplit : '') + iEnd); // compile the mana symbols, and if the last character was a / and not P, it was split cost so add iSplit, otherwise don't add anything
              newFangledString += hEnd; // will tack on the end span tag if we had a half mana
              hEnd = ''; // reset hEnd
              build = ''; // we are done with this symbol, reset build for the next one
            } else { // build is empty, meaning it is a short, easy symbol
              currentChar = currentChar.toLowerCase();
              if (currentChar === 't') { // this is going to be our tap symbol
                currentChar += 'ap'; // it needs to be the full word, so says mana.css
              } else if (currentChar === ConvertCost.colorCodes[12]) {
                currentChar = 'infinity';
              }
              newFangledString += (iStart + currentChar.toLowerCase() + iEnd); // fix up this symbol, it was a short one
            }
          }
        } else { // it is a long one, take a seat
          if (idChars.indexOf(currentChar) === -1 && status === 1 && (ConvertCost.colorCodes.indexOf(currentChar) > -1 || (currentChar >= '0' && currentChar <= '9'))) { // if current character isn't one of our markers (/,{,})
            build += currentChar; // add this character to the build
          }
        }
      }
    }
    newFangledString += string.substring(lastEndTag + 1); // we are done, so add the rest of the string to our final product
    return newFangledString; // the final mana cost, each symbol is an <i> tag with the proper classes from ManaCSS
  },
};
