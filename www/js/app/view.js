require(['jquery', 'cardcompare', 'materialize', 'convertcost'], function ($) {
  var parts = window.location.search.substr(1).split('&');
  var $_GET = {};
  for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split('=');
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
  }
  $(function () {
    $.get('php/get_deck.php', {id: $_GET['id']}, function (data) {
      data = JSON.parse(data);
      data.sort(CardCompare.compareClassic);
      $('#deckDate').text(data[0]['ddate']);
      $('#formatName').text(data[0]['formatName'])
                      .attr('href', 'format.html?id=' + data[0]['formatId']);
      $('#archetypeName').text(data[0]['archetypeName'])
                         .attr('href', 'archetype.html?id=' + data[0]['archetypeId']);
      var $deck = $('#deck');
      var $side = $('#sideboard');
      var $editing = $deck;
      var deckCount = 0; var sideCount = 0;
      var typeCounts = [{'Creature': 0, 'Planeswalker': 0, 'Instant': 0, 'Sorcery': 0, 'Artifact': 0, 'Enchantment': 0, 'Land': 0},
                        {'Creature': 0, 'Planeswalker': 0, 'Instant': 0, 'Sorcery': 0, 'Artifact': 0, 'Enchantment': 0, 'Land': 0}];
      for (var i = 0; i < data.length; i++) {
        var isSideboard = Boolean(parseInt(data[i]['sideboard']));
        $editing = $deck;
        if (isSideboard) {
          $editing = $side;
          sideCount += parseInt(data[i]['numberOf']);
        } else {
          deckCount += parseInt(data[i]['numberOf']);
        }
        for (var j = 0; j < CardCompare.cardTypes.length; j++) {
          if (data[i]['type'].includes(CardCompare.cardTypes[j])) {
            typeCounts[Number(isSideboard)][CardCompare.cardTypes[j]] += parseInt(data[i]['numberOf']);
            break;
          }
        }
        if (data[i]['manaCost'] == null) {
          data[i]['manaCost'] = '';
        }
        var newCost = ConvertCost.parse(data[i]['manaCost']);
        $editing.append(
            '<a class="collection-item">' +
            '<span class="badge left left-badge">' + data[i]['numberOf'] + '</span>' +
            '<span>' + data[i]['cardName'] + '</span>' +
            '<span class="secondary-content">' + newCost + '</span></span>' +
            '</a>'
        );
      }
      var tooltipDeck = '';
      var tooltipSide = '';
      for (i = 0; i < CardCompare.cardTypes.length; i++) {
        var cardType = CardCompare.cardTypes[i];
        var countDeck = typeCounts[0][cardType];
        var countSide = typeCounts[1][cardType];
        if (countDeck !== 0) {
          tooltipDeck += (cardType + ': ' + countDeck + '<br/>');
        }
        if (countSide !== 0) {
          tooltipSide += (cardType + ': ' + countSide + '<br/>');
        }
      }
      var $deckcount = $('#deckcount');
      var $sidecount = $('#sidecount');
      $deckcount.attr('data-tooltip', tooltipDeck);
      $sidecount.attr('data-tooltip', tooltipSide);
      $('.tooltipped').tooltip({delay: 50, html: true});
      $deckcount.text(deckCount);
      $sidecount.text(sideCount);
    });
  });
});
