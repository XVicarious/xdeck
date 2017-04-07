<?php
require_once('connection.php');
use \xdeck\Database;

$eventName = $_GET['name'];
$eventDate = $_GET['date'];

echo Database::insertEvent($eventName, $eventDate);
