<?php
require "admin_functions.php";
include "SqlStatements.php";
$dbh = createPDO2();
try {
    $formatId = $_GET['format'];
    $type = '%' . $_GET['type'] . '%';
    $stmt = $dbh->prepare(SqlStatements::GET_TOP_CARDS_FORMAT . ' AND cards.type LIKE :query');
    $stmt->bindParam(':formatId', $formatId, PDO::PARAM_INT);
    $stmt->bindParam(':query', $type, PDO::PARAM_STR);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $newCards = [];
    foreach ($cards as $card) {
        $index = card_exists($newCards, intval($card["id"]));
        if ($index > -1) {
            $newCards[$index]["numberOf"] += intval($card["numberOf"]);
        } else {
            array_push($newCards, ["id"=>intval($card["id"]),
                                   "numberOf"=>intval($card["numberOf"]),
                                   "cardName"=>$card["cardName"]]);
        }
    }
    $newerCards = [];
    $quantityCards = [];
    foreach ($newCards as $card) {
        array_push($quantityCards, $card["numberOf"]);
    }
    arsort($quantityCards);
    foreach ($quantityCards as $key => $value) {
        array_push($newerCards, $newCards[$key]);
        if (sizeof($newerCards) == 10) {
            break;
        }
    }
    echo json_encode($newerCards);
} catch (PDOException $e) { /* eat it :( */}

/**
 * $array the array of cards
 * $cardId the id of the card to find
 * returns the index of the card, -1 if it isn't found
 */
function card_exists($array, $cardId) {
    for ($i = 0; $i < sizeof($array); $i++) {
        if ($array[$i]["id"] == $cardId) {
            return $i;
        }
    }
    return -1;
}
