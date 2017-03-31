<?php
require_once('php/connection.php');
if (isset($_GET['action'])) {
    if (isset($_GET['controller'])) {
        $controller = $_GET['controller'];
    } else { // We assume the controller is pages
        $controller = 'pages';
    }
    $action = $_GET['action'];
} else {
    $controller = 'pages';
    $action = 'home';
}
require_once('views/layout.php');
