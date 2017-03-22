<?php
require "admin_functions.php";
require "mPDO.php";
include "SqlStatements.php";
//if (sessionCheck()) {
    $deckJSON = $_POST['deck'];
    $deckUser = 1; //$_POST['userId'];
    $deckTournament = 0; //$_POST['tournament']; // todo: add tournament database
    $deckDate = date('Y-m-d H:i:s'); // when this deck was made
    $dbh = createPDO();
    try {
        // [cardQuantity, cardName, sideboard]
        $deckObject = json_decode($deckJSON);
        $zero = 0;
        // First, create the deck
        $stmt = $dbh->prepare(SqlStatements::INSERT_NEW_DECK);
        $stmt->bindParam(':userId', $deckUser, PDO::PARAM_INT);
        // todo: support for tournaments, formats, and archetypes
        $stmt->bindParam(':tournamentId', $zero, PDO::PARAM_INT);
        $stmt->bindParam(':formatId', $zero, PDO::PARAM_INT);
        $stmt->bindParam(':archetypeId', $zero, PDO::PARAM_INT);
        $stmt->execute();
        $last_id = $dbh->lastInsertId();
        foreach ($deckObject as $card) {
            // Make sure the card is in the database, seems silly, but this is
            // the best way I know how
            $stmt = $dbh->prepare(SqlStatements::INSERT_CARD);
            $stmt->bindParam(':cardName', $card[1], PDO::PARAM_STR);
            $stmt->execute();
            // Now put the card into the deck
            $stmt = $dbh->prepare(SqlStatements::INSERT_CARD_INTO_DECK);
            $stmt->bindParam(':cardName', $card[1], PDO::PARAM_STR);
            $stmt->bindParam(':deckId', $last_id, PDO::PARAM_INT);
            $stmt->bindParam(':cardQuantity', $card[0], PDO::PARAM_INT);
            $stmt->bindParam(':isSideboard', $card[2], PDO::PARAM_BOOL);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
    } catch (Exception $e) {
        error_log($e->getMessage(), 0);
    }
    $dbh = null;
//}
