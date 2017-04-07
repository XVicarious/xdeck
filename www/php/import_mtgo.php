<?php
require_once('connection.php');
use \xdeck\Database;

$userId = (int) $_GET['user'];
$tournamentId = (int) $_GET['tournament'];
$formatId = (int) $_GET['format'];
$archetypeId = (int) $_GET['archetype'];
$deck = json_decode($_GET['deck'], true);

echo Database::insertDeck($userId, $tournamentId, $formatId, $archetypeId, $deck);
