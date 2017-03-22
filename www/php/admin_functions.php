<?php

function sessionCheck() {
    if (!isset($_SESSION)) {
        session_start();
    }
    $lastAction = $_SESSION['lastAction'];
    if ($lastAction + (30 * 60) < time()) {
        session_destroy();
        return false;
    }
    $_SESSION['lastAction'] = time();
    return true;
}

function createPDO($username, $password, $database) {
    try {
        return new PDO('mysql:host=localhost;dbname='.$database, $username, $password);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
        die('Unable to connect ' . $e->getMessage());
    }
}

function createPDO2() {
  return createPDO("root", "root", "xdeck");
}
