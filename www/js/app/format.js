require(['jquery', 'moment'], function ($, moment) {
  $.get('php/list_decks.php', {id: 0}, function (data) {
    data = JSON.parse(data);
    $('#formatName').text(data[0]['format']);
    var $recentDecks = $('#recentDecks');
    for (var i = 0; i < data.length; i++) {
      $recentDecks.append(
        '<a href="view.html?id=' + data[i]['id'] + '" class="collection-item"><div>' +
        data[i]['format'] + ' ' + data[i]['archetype'] + '<span class="secondary-content">' +
        moment(data[i]['ddate']).format('MMMM Do YYYY') + '</span>'
      );
    }
  });
});
