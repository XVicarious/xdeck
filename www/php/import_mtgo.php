<?php
require_once('connection.php');
use \xdeck\Database;

$userId = (int) $_GET['user'];
$tournamentId = (int) $_GET['tournament'];
$formatId = (int) $_GET['format'];
$archetypeId = (int) $_GET['archetype'];
$deck = $_GET['deck'];
echo Database::insertDeck($userId, $tournamentId, $formatId, $archetypeId, $deck);
