<?php
require "admin_functions.php";
include "SqlStatements.php";
$dbh = createPDO2();
try {
    $stmt = $dbh->prepare(SqlStatements::GET_DECK_BY_ID);
    $stmt->bindParam(':deckId', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    
}
