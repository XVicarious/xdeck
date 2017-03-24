<?php
require "admin_functions.php";
require "mPDO.php";
include "SqlStatements.php";
//if (sessionCheck()) {
    $deckJSON = $_POST['deck'];
    //$deckUser = $_POST['userId'];
    //$deckTournament = $_POST['tournament']; // todo: add tournament database
    $deckDate = date('Y-m-d H:i:s'); // when this deck was made
    $dbh = createPDO2();
    try {
        // [cardId, cardQuantity, cardName, sideboard]
        $deckObject = json_decode($deckJSON, true);
        $zero = 0;
        // First, create the deck
        $stmt = $dbh->prepare(SqlStatements::INSERT_NEW_DECK);
        $stmt->bindParam(':userId', $zero, PDO::PARAM_INT);
        // todo: support for tournaments, formats, and archetypes
        $stmt->bindParam(':tournamentId', $zero, PDO::PARAM_INT);
        $stmt->bindParam(':formatId', $zero, PDO::PARAM_INT);
        $stmt->bindParam(':archetypeId', $zero, PDO::PARAM_INT);
        $stmt->execute();
        $last_id = $dbh->lastInsertId();
        foreach ($deckObject as $card) {
            // Now put the card into the deck
            $stmt = $dbh->prepare(SqlStatements::INSERT_CARD_INTO_DECK);
            $stmt->bindParam(':cardId', $card["id"], PDO::PARAM_INT);
            $stmt->bindParam(':deckId', $last_id, PDO::PARAM_INT);
            $stmt->bindParam(':cardQuantity', $card["numberOf"], PDO::PARAM_INT);
            $stmt->bindParam(':isSideboard', $card["sideboard"], PDO::PARAM_BOOL);
            $stmt->execute();
            echo $stmt->errorCode();
            print_r($stmt->errorInfo());
        }
    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
    }
    $dbh = null;
//}
