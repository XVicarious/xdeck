<<<<<<< HEAD
require(['jquery', 'moment'], function($, moment) {
  $.get('php/list_decks.php', {id: 0}, function(data) {
    data = JSON.parse(data);
    $('#formatName').text(data[0]['format']);
    const $recentDecks = $('#recentDecks');
    for (let i = 0; i < data.length; i++) {
      $recentDecks.append(
        '<a href="view.html?id=' + data[i]['id'] +
        '" class="collection-item"><div>' +
        data[i]['format'] + ' ' + data[i]['archetype'] +
        '<span class="secondary-content">' +
        moment(data[i]['ddate']).format('MMMM Do YYYY') + '</span>'
      );
    }
  });
=======
require(['jquery', 'moment'], function ($, moment) {
>>>>>>> 95825237baf054af740768b89b89e79c2b90954f
});
