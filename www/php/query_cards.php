<?php
require_once('connection.php');
echo json_encode(Database::queryForCards($_GET['query']));
