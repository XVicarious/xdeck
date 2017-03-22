<?php
require "admin_functions.php";
require "mPDO.php";
include "SqlStatements.php";
$dbh = createPDO2();
try {
    $stmt = $dbh->prepare(SqlStatements::LIST_DECKS_FORMAT_ARCHETYPE);
    $formatId = 0;
    $archetypeId = 0;
    $stmt->bindParam(':formatId', $formatId, PDO::PARAM_INT);
    $stmt->bindParam(':archetypeId', $archetypeId, PDO::PARAM_INT);
    $stmt->execute();
    $decks = json_encode($stmt->fetchAll());
    echo $decks;
} catch (PDOException $e) {
    error_log($e->getMessage());
}
