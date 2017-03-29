<!DOCTYPE html>
<head>
    <title>xdeck</title>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <link href="/css/mana.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>
    <script>
      requirejs(['/js/common.js'], function (common) {
        require(['jquery', 'materialize'], function ($) {
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
                <a href="#" class="brand-logo center">Catchy Name Here</a>
                <ul class="right hide-on-med-and-down">
                  <li><a href="/">Home</a></li>
                  <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Formats<i class="material-icons right">arrow_drop_down</i></a></li>
                </ul>
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
