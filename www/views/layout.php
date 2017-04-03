<!DOCTYPE html>
<head>
    <title>xdeck</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/materialize.min.css">
    <link href="css/mana.min.css" rel="stylesheet">
    <link href="css/extra.css" rel="stylesheet">
</head>
<body class="indigo lighten-4">
    <header>
      <ul id="dropdown1" class="dropdown-content">
        <li><a href="format/1/">Modern</a></li>
        <li><a href="format/2/">Pauper</a></li>
        <li><a href="format/3/">Legacy</a></li>
        <li><a href="format/4/">Vintage</a></li>
        <li><a href="format/5/">Standard</a></li>
      </ul>
        <nav class="indigo">
            <div class="nav-wrapper">
                <form id="search-input">
                    <div class="input-field">
                        <input id="search" class="typeahead" type="search" name="search">
                        <label class="label-icon" for="search">
                            <a id="search-icon" href="#!"><i class="material-icons">search</i></a>
                        </label>
                        <i class="material-icons clear-search">close</i>
                    </div>
                </form>
                <div class="pull-up">
                    <a class="brand-logo center">Catchy Name Here</a>
                    <ul class="right hide-on-med-and-down navbar">
                      <li class="clickable"><a href="/">Home</a></li>
                      <li class="clickable">
                          <a class="dropdown-button" href="#!" data-activates="dropdown1">Formats<i class="material-icons right">arrow_drop_down</i></a>
                      </li>
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
    <footer class="page-footer indigo">
        <div class="container">
            <div class="row">
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                All original content on this page is &copy; 2016 Brian Maurer and may not be used or reproduced without consent. Wizards of the Coast, Magic: The Gathering, and their logos are trademarks of Wizards of the Coast LLC &copy; 1995 - 2016 Wizards. All rights reserved. xdeck is not affiliated with Wizards of the Coast LLC.
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>
    <script>
    var action = <?php echo (isset($_GET['action']) ? '\''.$_GET['action'].'\'' : '\'home\''); ?>;
    requirejs(['js/common'], function (common) {
      requirejs(['js/app/search']);
      requirejs(['js/app/' + action]);
    });
    </script>
</body>
