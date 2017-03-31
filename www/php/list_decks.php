<?php
require "admin_functions.php";
include "SqlStatements.php";
$dbh = createPDO2();
try {
    $stmt = $dbh->prepare(SqlStatements::LIST_DECKS_FORMAT);
    $formatId = $_GET['id'];
    $stmt->bindParam(':formatId', $formatId, PDO::PARAM_INT);
    $stmt->execute();
    $decks = json_encode($stmt->fetchAll());
    echo $decks;
} catch (PDOException $e) {
    error_log($e->getMessage());
}
