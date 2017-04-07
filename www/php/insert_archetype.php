<?php
require_once('connection.php');
use \xdeck\Database;

$archetypeName = $_GET['name'];

echo Database::insertArchetype($archetypeName);
