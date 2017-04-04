require(['typeahead', 'handlebars', 'bloodhound', 'materialize', 'convertcost'], function (typeahead, Handlebars, Bloodhound) {
  var parts = window.location.search.substr(1).split('&');
  var $_GET = {};
  for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split('=');
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
  }
  $('ul.tabs').tabs();
  try {
    var cardDatabase = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('cardName'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: 'php/query_cards.php?query=%QUERY',
        wildcard: '%QUERY',
        filter: function (data) {
          for (var i = 0; i < data.length; i++) {
            data[i]['manaCost'] = ConvertCost.parse(data[i]['manaCost']);
          }
          return data;
        }
      },
      limit: 7
    });
    $('.typeahead').typeahead({minLength: 2, highlight: true}, {
      source: cardDatabase,
      name: 'search',
      display: 'cardName',
      limit: 7,
      templates: {
        suggestion: Handlebars.compile('<div><span class="flow-text">{{cardName}}</span><span class="flow-text secondary-content search-mana-cost">{{{manaCost}}}</span></div>')
      }
    }).bind('typeahead:select', function (ev, suggestion) {
      window.location = '/card/' + parseInt(suggestion.id);
    });
    $('#search-icon').click(function () {
      $('.twitter-typeahead').show(400, function () {
        $(this).css('display', 'block');
        $('#search-icon').children('i').css('color', '#444');
        $('.clear-search').show(0, function () {
          $(this).css('display', 'block');
          $(this).css('color', '#444');
        });
        $('#search').focus();
      });
    });
    $('#search').blur(function () {
      $('#search-icon').children('i').css('color', 'rgba(255,255,255,0.7)');
      $('.clear-search').hide();
      $('.twitter-typeahead').hide();
    });
    $('.twitter-typeahead').hide(0);
  } catch (error) {
    console.error('Error loading typeahead and/or Bloodhound, this happens time to time because things.');
    $('.typeahead').hide(0);
    Materialize.toast('Error loading typeahead and/or Bloodhound, search is not available. A fix is being worked on.', 4000);
  }
  $('.dropdown-button').dropdown();
  // $('.button-collapse').sideNav();
});
