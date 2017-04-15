let deck = []; // initialize the deck
require(['moment', 'typeahead', 'bloodhound',
         'cardcompare', 'convertcost', 'materialize'], function() {
  // var DATE_FORMAT = 'YY/MM/DD';
  $(function() {
    const cardDatabase = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('cardName'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: 'php/list_cards.php?query=%QUERY',
        wildcard: '%QUERY',
      },
      limit: 15,
    });
    /* $.post('php/list_decks.php', function (data) {
      data = JSON.parse(data);
      var $table = $('#decksList');
      for (var i = 0; i < data.length; i++) {
        $table.append('<a class="collection-item">' +
                       moment(data[i]['dck_decks_date']).format(DATE_FORMAT) +
                       '</a>');
      }
    }); */
    $('input.typeahead').typeahead({minLength: 3, highlight: true}, {
      source: cardDatabase,
      name: 'cardname',
      display: 'cardName',
      limit: 15,
    }).on('typeahead:selected', function(element, item) {
      addToDeck(item, $('#isSideboard').prop('checked'));
      deck.sort(CardCompare.compareClassic);
      writeTable();
    }).on('typeahead:autocompleted', function(element, item) {
      addToDeck(item, $('#isSideboard').prop('checked'));
      deck.sort(CardCompare.compareClassic);
      writeTable();
    });
    $('#saveDeck').click(function() {
      const deckCards = cardsInDeck(); const sideCards = cardsInDeck(true);
      if (deckCards >= 60 && sideCards <= 15) {
        $.post('php/new_deck.php',
          {deck: JSON.stringify(deck)}, function(data) {
            Materialize.toast('Deck saved.', 4000);
          });
      } else if (deckCards < 60) {
        Materialize.toast('Not enough cards in deck. ' +
          'Allowed: 60 or more. You have: ' + deckCards, 4000);
      } else if (sideCards > 15) {
        Materialize.toast('Too many cards in sideboard. ' +
          'Allowed: 15 or less. You have: ' + sideCards, 4000);
      }
    });
    $(document).on('click', '.deck-card', function() {
      deck.splice($(this).attr('deck-card-id'), 1);
      writeTable();
    });
    $(document).on('click', 'span.badge.quantity', function() {
      $(this).attr('contentEditable', true);
    }).on('blur', 'span.badge.quantity', function() {
      $(this).attr('contentEditable', false);
      if ($(this).text() === '' || $(this).text() === '0') {
        $(this).text('1');
      }
      let deckCardId = $(this).parent('div').attr('deck-card-id');
      deck[deckCardId]['numberOf'] = parseInt($(this).text());
      writeTable();
    });
    function addToDeck(item, isSideboard) {
      for (let i = 0; i < deck.length; i++) {
        if (deck[i]['id'] === item.id && deck[i]['sideboard'] === isSideboard) {
          break;
        }
      }
      if (i < deck.length) {
        deck[i]['numberOf'] += parseInt($('#cardnameQuantity').val());
      } else {
        deck.push({'id': item.id,
                   'numberOf': parseInt($('#cardnameQuantity').val()),
                   'cardName': item.cardName, 'sideboard': isSideboard,
                   'manaCost': item.manaCost,
                   'type': item.type,
                   'cmc': item.cmc});
      }
    }
    function writeTable() {
      const $tbody = $('#deckView');
      const $sideboard = $('#sideView');
      let $editing;
      $tbody.empty(); $sideboard.empty();
      $tbody.append(
        '<div class="collection-header">Mainboard</div>'
      );
      $sideboard.append(
        '<div class="collection-header">Sideboard</div>'
      );
      for (let i = 0; i < deck.length; i++) {
        if (deck[i]['sideboard']) {
          $editing = $sideboard;
        } else {
          $editing = $tbody;
        }
        $editing.append(
                       '<div class="collection-item" ' +
                                   'deck-card-id="' + i + '">' +
                         '<span class="badge left quantity">' +
                           deck[i]['numberOf'] +
                         '</span>' +
                         '<span>' + deck[i]['cardName'] + '</span>' +
                         '<span class="secondary-content">' +
                           '<span>' +
                             ConvertCost.parse(deck[i]['manaCost']) +
                           '</span>' +
                         '</span>' +
                       '</div>'
                 );
      }
    }
    function cardsInDeck(sideboard) {
      sideboard = (typeof sideboard !== 'undefined') ? sideboard : false;
      let cards = 0;
      for (let i = 0; i < deck.length; i++) {
        if (deck[i]['sideboard'] === sideboard) {
          cards += deck[i]['numberOf'];
        }
      }
      return cards;
    }
  });
});
