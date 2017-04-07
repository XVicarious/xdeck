<?php
require_once('connection.php');
use \xdeck\Database;

$formats = Database::getFormats();
echo json_encode($formats);
