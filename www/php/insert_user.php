<?php
require_once('connection.php');
use \xdeck\Database;

$username = $_GET['name'];

echo Database::insertUser($username);
