const decklistTemplate = '<div class="deck">' +
                           '<div class="input-field">' +
                             '<input type="text" class="typeahead archetype">' +
                           '</div>' +
                         '</div>';
const eventTemplate = '<div class="card-panel">' +
                        '<div class="input-field">' +
                           '<input type="text" class="typeahead format">' +
                        '</div>' +
                      '</div>';
const ARTICLE_CLASS = '.article-item-extended';
const CARD_ROW = 'span.row';
const MAIN_DECK = 'div.sorted-by-overview-container ' + CARD_ROW;
const SIDE_CLASS = '.sorted-by-sideboard-container';
const SIDE_DECK = 'div' + SIDE_CLASS + ' ' + CARD_ROW;
console.log(SIDE_DECK);
require(['jquery', 'moment', 'bloodhound', 'typeahead'], function($, moment) {
  let dunFuckedUp = false;
  const WIZARDS = 'http://magic.wizards.com';
  let formats;
  let archetypes;
  try {
    formats = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: {
        url: 'php/get_formats.php',
      },
    });
    // formats.clearPrefetchCache();
    formats.initialize();
    archetypes = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: {
        url: 'php/get_archetypes.php',
      },
    });
    archetypes.initialize();
  } catch (excp) {
    console.error('It dun fucked up.');
    return;
  }
  $('#import-mtgo').click(function() {
    $.ajax({
      url: WIZARDS + '/en/content/deck-lists-magic-online-products-game-info',
      success: function(data) {
        const $decksPage = $(data);
        const latest = moment('2017-04-03');
        $decksPage.find(ARTICLE_CLASS).each(function() {
          const $eventPost = $(this);
          let $eventDate = $eventPost.find('.date');
          const eventDate = moment($eventDate.find('.month').text() + ' ' +
                                 $eventDate.find('.day').text() + ' ' +
                                 $eventDate.find('.year').text(),
                                 'MMMM D YYYY');
          if (eventDate.isAfter(latest) || eventDate.isSame(latest)) {
            $.ajax({
              url: WIZARDS + $eventPost.find('a').attr('href'),
              dataType: 'html',
              success: function(data) {
                const $event = $(data);
                const $eventSection = $(eventTemplate).appendTo('#decklists');
                $eventSection.append('<span class="event-name">' +
                                        $event.find('#main-content h1').text() +
                                     '</span>');
                $eventSection.append('<span class="event-date">' +
                                        eventDate.format('YYYY-MM-DD') +
                                      '</span>');
                try {
                  $eventSection.find('.typeahead.format').typeahead({
                    source: formats,
                    display: 'name',
                  }).blur(function() {
                    const $this = $(this);
                    formats.search($this.val(), function(results) {
                      if (results.length === 1) {
                        $this.attr('format', results[0].id);
                        return;
                      }
                      $this.attr('format', 0);
                    });
                  });
                } catch (expr) {
                  console.error('typeahead dun fucked up.');
                  return;
                }
                $event.find('.bean--wiz-content-deck-list').each(function() {
                  const $locallist = $(decklistTemplate)
                                       .appendTo($eventSection);
                  let username = $(this).find('.deck-meta h4')
                                        // fixme:
                                        // usernames can contain spaces :(
                                        // all decks are 5-0, so perhaps count
                                        // back to the beginning of that text
                                        // and then back one more
                                        .text();
                  username = username.trim().substring(0, username.length - 5)
                                             .trim();
                  $locallist.append('<span>' + username + '</span>');
                  try {
                    $locallist.find('.typeahead.archetype').typeahead({
                      source: archetypes,
                      display: 'name',
                    }).blur(function() {
                      const $this = $(this);
                      archetypes.search($this.val(), function(results) {
                        if (results.length === 1) {
                          $this.attr('archetype', results[0].id);
                          return;
                        }
                        $this.attr('archetype', 0);
                      });
                    });
                  } catch (expr) {
                    console.error('typeahead dun fucked up.');
                    return;
                  }
                  const $deckarea = $('<textarea></textarea>')
                                      .appendTo($locallist);
                  let $decklist = $(this).find('.deck-list-text');
                  $decklist.find(MAIN_DECK + ',' + SIDE_DECK).each(function() {
                    let cardname = $(this).find('.card-name').text();
                    let count = parseInt($(this).find('.card-count').text());
                    let isSideboard = $(this).parent()
                                             .hasClass(SIDE_CLASS.substring(1));
                    let line = (isSideboard ? 'SB:' : '') +
                               count + ' ' + cardname;
                    $deckarea.val($deckarea.val() + line + '\n');
                  });
                });
                $eventSection.append('<a class="btn-large s">Submit Decks</a>');
              },
            });
            if (dunFuckedUp) {
              return;
            }
          }
        });
        if (dunFuckedUp) {
          return;
        }
      },
      dataType: 'html',
    });
    if (dunFuckedUp) {
      return;
    }
  });
  $(document).on('click', '.s', function() {
    const $thisParent = $(this).parent();
    if ($thisParent.find('.format.tt-input').val() === '' || $thisParent.find('.typeahead.format.tt-input').attr('format') == 0) {
      console.error('A format is NEEDED! The given one is ' + $thisParent.find('.tt-input').val());
      return;
    }
    let formatId = parseInt($thisParent.find('.format.tt-input').attr('format'));
    const eventName = $thisParent.find('.event-name').text();
    const eventDate = $thisParent.find('.event-date').text();
    let eventId;
    $.ajax({
      url: 'php/insert_event.php',
      data: {'name': eventName, 'date': eventDate},
      async: false,
      success: function(data) {
        data = parseInt(data);
        if (Number.isInteger(data)) {
          eventId = data;
          return;
        }
        // If we don't get a number back eventId is 0
        eventId = 0;
      },
    });
    if (eventId === 0) {
      console.error('eventId is 0, something must have gone wrong!');
      return;
    }
    $thisParent.find('.deck').each(function() {
      if ($(this).find('.archetype.tt-input').val() === '') {
        console.error($(this), 'needs an archetype!');
        return;
      } else if ($(this).find('textarea').val() === '') {
        console.error($(this), 'needs cards in the deck!');
        return;
      }
      let archetypeId = parseInt($(this).find('.archetype.tt-input').attr('archetype'));
      console.log('186: ' + archetypeId);
      if (archetypeId === 0) {
        $.ajax({
          url: 'php/insert_archetype.php',
          data: {name: $(this).find('.archetype.tt-input').val()},
          async: false,
          success: function(data) {
            console.log('193 (data): ' + data);
            data = parseInt(data);
            if (Number.isInteger(data)) {
              archetypeId = data;
              return;
            }
            archetypeId = 0;
          },
        });
        if (archetypeId === 0) {
          console.error('archetypeId is still 0, it fucked up.');
          return;
        }
      }
      return;
      const username = $(this).find('span').text();
      let userId = 0;
      $.ajax({
        url: 'php/insert_user.php',
        data: {name: username},
        async: false,
        success: function(data) {
          data = parseInt(data);
          if (Number.isInteger(data)) {
            userId = data;
            return;
          }
          userId = 0;
        },
      });
      if (userId === 0) {
        console.error('userId is 0, something must have gone wrong!');
        return;
      }
      let deck = [];
      let lines = $(this).find('textarea').val().split('\n');
      for (let i = 0; i < lines.length; i++) {
        let numberOf;
        let name;
        let isSideboard = false;
        if (lines[i].substring(0, 3) === 'SB:') {
          isSideboard = true;
        }
        // if the card is a sideboard card, start from 3, otherwise 0
        numberOf = parseInt(lines[i].substring(isSideboard ? 3 : 0,
                                               lines[i].indexOf(' ')));
        name = lines[i].substring(lines[i].indexOf(numberOf) + 1);
        name = name.trim();
        deck.push([numberOf, name, isSideboard]);
      }
      let deckId;
      $.get('php/import_mtgo.php', {deck: deck,
                                    tournament: eventId,
                                    user: userId,
                                    // todo: get format
                                    format: formatId,
                                    // todo: get archetype
                                    archetype: archetypeId}, function(data) {
                                      data = parseInt(data);
                                      if (Number.isInteger(data)) {
                                        deckId = data;
                                        return;
                                      }
                                      deckId = 0;
                                    });
      if (deckId === 0) {
        console.error('deckId is 0, something must have went wrong!');
        return;
      }
      // Make a link to the deck that opens in a new tab
      $(this).html('<a href="/deck/' + deckId + '" target="_blank">' +
                      username + '\'s Deck' +
                    '</a>');
    });
  });
});
