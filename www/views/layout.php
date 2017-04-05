<?php
$hostname = gethostname();
if (strcmp($hostname, 'gator3074.hostgator.com') === 0) {
    $base = 'https://xvss.net/devel/xdeck/';
} elseif (strcmp($hostname, 'XVSS-HERMES') === 0) {
    $base = '\/~xvicarious\/';
} else {
    $base = '/';
}
$homeActive = '';
$format1Active = '';
$format2Active = '';
$format3Active = '';
$format4Active = '';
if (!isset($_GET['action'])) {
    $homeActive = 'active';
} elseif ($_GET['action'] === 'format') {
    if ($_GET['id'] === '1') {
        $format1Active = 'active';
    } elseif ($_GET['id'] === '2') {
        $format2Active = 'active';
    } elseif ($_GET['id'] === '3') {
        $format3Active = 'active';
    } elseif ($_GET['id'] === '4') {
        $format4Active = 'active';
    }
}
?>
<!DOCTYPE html>
<head>
    <title>MTGMetaVisions</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/materialize.min.css">
    <link href="css/mana.min.css" rel="stylesheet">
    <link href="css/extra.css" rel="stylesheet">
    <meta name="theme-color" content="#3f51b5">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <base href="<?php echo $base; ?>" />
</head>
<body class="blue lighten-4">
    <header>
        <nav class="blue nav-extended">
            <div class="nav-wrapper">
                <form id="search-input">
                    <div class="input-field">
                        <input id="search" class="typeahead" type="search" name="search">
                        <label class="label-icon" for="search">
                            <a id="search-icon"><i class="material-icons">search</i></a>
                        </label>
                        <i class="material-icons clear-search">close</i>
                    </div>
                </form>
                <a class="brand-logo center">MTGMetaVisions</a>
            </div>
            <div class="nav-content">
                <ul class="tabs tabs-transparent">
                    <li class="tab"><a href="/" class="<?php echo $homeActive; ?>" target="_self">Home</a></li>
                    <li class="tab"><a href="/format/1/" class="<?php echo $format1Active; ?>" target="_self">Modern</a></li>
                    <li class="tab"><a href="/format/2/" class="<?php echo $format2Active; ?>" target="_self">Pauper</a></li>
                    <li class="tab"><a href="/format/3/" class="<?php echo $format3Active; ?>" target="_self">Standard</a></li>
                    <li class="tab"><a href="/format/4/" class="<?php echo $format4Active; ?>" target="_self">Legacy</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div>
            <?php require_once('routes.php'); ?>
        </div>
    </main>
    <footer class="page-footer blue">
        <div class="container">
            <div class="row">
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                All original content on this page is &copy; 2016 MTGMetaVisions and may not be used or reproduced without consent. Wizards of the Coast, Magic: The Gathering, and their logos are trademarks of Wizards of the Coast LLC &copy; 1995 - 2016 Wizards. All rights reserved. xdeck is not affiliated with Wizards of the Coast LLC.
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.3/require.min.js"></script>
    <script>
    const action = <?php echo (isset($_GET['action']) ? '\''.$_GET['action'].'\'' : '\'home\''); ?>;
    requirejs(['js/common'], function(common) {
      requirejs(['js/app/search']);
      requirejs(['js/app/' + action]);
    });
    </script>
</body>
