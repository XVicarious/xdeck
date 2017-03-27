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
  $.get('php/get_top_cards.php', {format: 1}, function (data) {
    data = JSON.parse(data);
    var $topModern = $('#topModernCards');
    for (var i = 0; i < data.length; i++) {
      $topModern.append(
        '<a href="view_card.html?id=' + data[i]['id'] +
        '" class="collection-item">' + data[i]['cardName'] +
        '<span class="badge new" data-badge-caption="copies">' +
        data[i]['numberOf'] + '</span></a>'
      );
    }
  });
  /* top cards for pauper */
  $.get('php/get_top_cards.php', {format: 2}, function (data) {
    data = JSON.parse(data);
    var $topPauper = $('#topPauperCards');
    for (var i = 0; i < data.length; i++) {
      $topPauper.append(
        '<a href="view_card.html?id=' + data[i]['id'] +
        '" class="collection-item">' + data[i]['cardName'] +
        '<span class="badge new" data-badge-caption="copies">' +
        data[i]['numberOf'] + '</span></a>'
      );
    }
  });
});
