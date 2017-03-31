<?php

/**
 * @return bool if the session is current
 */
function sessionCheck()
{
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

/**
 * @param string $username the username for the database
 * @param string $password the password for the database
 * @param string $database the database to connect to
 * @return PDO connecting to the database with the given credentials
 */
function createPDO($username, $password, $database)
{
    try {
        return new PDO('mysql:host=localhost;dbname='.$database, $username, $password);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
    }
}

/**
 * @return PDO a PDO with some default options
 */
function createPDO2()
{
    return createPDO("root", "root", "bmaurer_deckvc");
}

/**
 * @param string[] $array the array of cards
 * @param int      $cardId the id of the card to find
 * @return int     $i index of the card, -1 if it isn't found
 */
function card_exists($array, $cardId)
{
    for ($i = 0; $i < sizeof($array); $i++) {
        if ($array[$i]["id"] == $cardId) {
            return $i;
        }
    }
    return -1;
}
