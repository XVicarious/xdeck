<!DOCTYPE html>
<head>
    <title>xdeck</title>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <link href="/css/mana.min.css" rel="stylesheet">
    <link href="/css/extra.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>
    <script>
      requirejs(['/js/common.js'], function (common) {
        require(['jquery', 'handlebars', 'materialize', 'bloodhound', 'typeahead', 'convertcost'], function ($, Handlebars) {
          var cardDatabase = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('cardName'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
              url: '/php/list_cards.php?query=%QUERY',
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
          $('.typeahead').typeahead({minLength: 3, highlight: true}, {
            source: cardDatabase,
            name: 'search',
            display: 'cardName',
            limit: 7,
            templates: {
                suggestion: Handlebars.compile(
                    '<div><span class="flow-text">{{cardName}}</span><span class="flow-text secondary-content search-mana-cost">{{{manaCost}}}</span></div>'
                )
            }
        }).bind('typeahead:select', function (ev, suggestion) {
            window.location = '/card/' + parseInt(suggestion.id);
        });
        $('#search').focus(function () {
            $(this).parent().siblings('.label-icon').css('color', '#444');
        });
          $('.dropdown-button').dropdown();
        });
      });
    </script>
</head>
<body class="indigo lighten-4">
    <header>
      <ul id="dropdown1" class="dropdown-content">
        <li><a href="/format/1/">Modern</a></li>
        <li><a href="/format/2/">Pauper</a></li>
        <li><a href="/format/3/">Legacy</a></li>
        <li><a href="/format/4/">Vintage</a></li>
        <li><a href="/format/5/">Standard</a></li>
      </ul>
        <nav class="indigo">
            <div class="nav-wrapper">
                <form>
                    <div class="input-field">
                        <input id="search" class="typeahead" type="search" name="search" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
                <div class="pull-up">
                    <a class="brand-logo center">Catchy Name Here</a>
                    <ul class="right hide-on-med-and-down">
                      <li><a href="/">Home</a></li>
                      <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Formats<i class="material-icons right">arrow_drop_down</i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <?php require_once('routes.php'); ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
