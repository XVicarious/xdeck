<?php
require_once('connection.php');
use \xdeck\Database;

echo json_encode(Database::queryForCards($_GET['query']));
