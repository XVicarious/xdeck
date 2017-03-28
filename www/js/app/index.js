require(['jquery', 'moment'], function ($, moment) {
  $.post('php/list_all_decks.php', function (data) {
    data = JSON.parse(data);
    var $recentDecks = $('#recentDecks');
    for (var i = 0; i < data.length; i++) {
      $recentDecks.append(
        '<a href="view.html?id=' + data[i]['id'] + '" class="collection-item"><div>' +
        data[i]['format'] + ' ' + data[i]['archetype'] +
        '<span class="secondary-content">' + moment(data[i]['ddate']).format('MMMM Do YYYY') + '</span>' +
        '</div></a>'
      );
    }
  });
  /* top cards for modern */
  topModernCards = JSON.parse(topModernCards);
  var $topModern = $('#topModernCards');
  for (var i = 0; i < topModernCards.length; i++) {
    $topModern.append(
      '<a href="view_card.html?id=' + topModernCards[i]['id'] +
      '" class="collection-item">' + topModernCards[i]['cardName'] +
      '<span class="badge new" data-badge-caption="copies">' +
      topModernCards[i]['numberOf'] + '</span></a>'
    );
  }
  /* top cards for pauper */
  topPauperCards = JSON.parse(topPauperCards);
  var $topPauper = $('#topPauperCards');
  for (var i = 0; i < topPauperCards.length; i++) {
    $topPauper.append(
      '<a href="view_card.html?id=' + topPauperCards[i]['id'] +
      '" class="collection-item">' + topPauperCards[i]['cardName'] +
      '<span class="badge new" data-badge-caption="copies">' +
      topPauperCards[i]['numberOf'] + '</span></a>'
    );
  }
});
