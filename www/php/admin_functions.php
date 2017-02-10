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

function createPDO() {
    $sql_username = 'root';
    $sql_password = 'root';
    try {
        return new PDO('mysql:host=localhost;dbname=xdeck', $sql_username, $sql_password);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
        die('Unable to connect ' . $e->getMessage());
    }
}
