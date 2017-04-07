<?php
require_once('connection.php');
use \xdeck\Database;

echo json_encode(Database::getArchetypes());
