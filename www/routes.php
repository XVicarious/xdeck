<?php
function call($controller, $action)
{
    require_once('controllers/' . $controller . '_controller.php');
    switch ($controller) {
        case 'pages':
            $controller = new PagesController();
            break;
    }
    $controller->{$action}();
}

$controllers = array('pages' => ['home', 'error', 'deck', 'format', 'archetype', 'card']);

if (array_key_exists($controller, $controllers)) {
    echo $action;
    if (in_array($action, $controllers[$controller])) {
        call($controller, $action);
    } else {
        call('pages', 'error');
    }
} else {
    if (in_array($action, $controllers['pages'])) {
        call('pages', $action);
    } else {
        call('pages', 'error');
    }
}
