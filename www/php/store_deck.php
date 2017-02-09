<?php
require "admin_functions.php";
include "SqlStatements.php";
if (sessionCheck()) {
    $deckJSON = $_POST['deck'];
    $deckUser = $_POST['userId'];
    $deckTournament = $_POST['tournament']; // todo: add tournament database
    $deckDate = date('Y-m-d H:i:s'); // when this deck was made
    $dbh = createPDO();
    try {
        $deckObject = JSON.parse($deckJSON);
        $stmt = null; // prepare the SqlStatements
        $stmt->bindParam(':userId', $deckUser, PDO::PARAM_INT);
        $stmt->bindParam(':tournamentId', 0, PDO::PARAM_INT); // replace tournament id with 0 for now
    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
    }
    $dbh = null;
}