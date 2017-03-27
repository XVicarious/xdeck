require(['jquery', 'moment'], function ($, moment) {
  var parts = window.location.search.substr(1).split('&');
  var $_GET = {};
  for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split('=');
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
  }
  var id = $_GET['id'];
  $.get('php/list_decks.php', {id: id}, function (data) {
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
  $.get('php/get_top_cards_2.php', {format: id, type: 'Creature'}, function (data) {
    data = JSON.parse(data);
    writeCollection($('#topCreatures'), data);
  });
  $.get('php/get_top_cards_2.php', {format: id, type: 'Instant'}, function (data) {
    // todo: properly include Planeswalker, Instant, Sorcery, Artifact, Enchantment
    data = JSON.parse(data);
    writeCollection($('#topSpells'), data);
  });
  $.get('php/get_top_cards_2.php', {format: id, type: 'Land'}, function (data) {
    data = JSON.parse(data);
    writeCollection($('#topLands'), data);
  });
  function writeCollection ($element, data) {
    for (var i = 0; i < data.length; i++) {
      $element.append(
        '<a href="view_card.html?id=' + data[i]['id'] +
        '" class="collection-item">' + data[i]['cardName'] +
        '<span class="badge new" data-badge-caption="copies">' +
        data[i]['numberOf'] + '</span></a>'
      );
    }
  }
});
