<?php
require "admin_functions.php";
require "mPDO.php";
include "SqlStatements.php";
$dbh = createPDO2();
try {
    $stmt = $dbh->prepare(SqlStatements::LIST_ALL_DECKS);
    $stmt->execute();
    $decks = json_encode($stmt->fetchAll());
    echo $decks;
} catch (PDOException $e) {
    error_log($e->getMessage());
}
