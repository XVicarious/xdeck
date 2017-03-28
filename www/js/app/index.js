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
  /* var $topModern = $('#topModernCards');
  for (var i = 0; i < aTopModernCards.length; i++) {
    $topModern.append(
      '<a href="view_card.html?id=' + aTopModernCards[i]['id'] +
      '" class="collection-item">' + aTopModernCards[i]['cardName'] +
      '<span class="badge new" data-badge-caption="copies">' +
      aTopModernCards[i]['numberOf'] + '</span></a>'
    );
} */
  /* top cards for pauper */
  var $topPauper = $('#topPauperCards');
  for (i = 0; i < aTopPauperCards.length; i++) {
    $topPauper.append(
      '<a href="view_card.html?id=' + aTopPauperCards[i]['id'] +
      '" class="collection-item">' + aTopPauperCards[i]['cardName'] +
      '<span class="badge new" data-badge-caption="copies">' +
      aTopPauperCards[i]['numberOf'] + '</span></a>'
    );
  }
});
