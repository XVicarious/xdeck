<?php

namespace xdeck;

require_once('card.php');

use xdeck\containers;
use \PDO;

class Database
{

    /**
     * Inserts a new deck into the database
     * @param int :userId id of the user inserting the deck
     * @param int :tournamentId id of the tournament this deck was played in
     *                          if there was no tournament, 0
     * @param :formatId id of the format of this deck
     * @param :archetypeId id of the archetype of this deck
     * @var string
     */
    const INSERT_NEW_DECK = 'INSERT INTO dck_decks (dck_decks_userid,
        dck_decks_tournamentid, dck_decks_formatid, dck_decks_archetypeid)
        VALUES (:userId, :tournamentId, :formatId, :archetypeId)';

    /**
     * Inserts a card into the deck
     * @param int :deckId id of the deck this card belongs to
     * @param int :cardId id of the card being inserted
     * @param int :cardQuantity the number of the card being inserted
     * @param bool :isSideboard if the card is in the sideboard
     * @var string
     */
    /**const INSERT_CARD_INTO_DECK = 'INSERT INTO dck_deckcards
        (dck_deckcards_deckid, dck_deckcards_cardid,
         dck_deckcards_quantity, dck_deckcards_sideboard)
         VALUES (:deckId, :cardId, :cardQuantity, :isSideboard);';*/

    /**
     * Lists all decks of archetype in format
     * @param int :formatId id of the format for the decks
     * @param int :archetypeId id of the archetype for the decks
     * @var string
     */
    const LIST_DECKS_FORMAT_ARCHETYPE = 'SELECT dck_decks_id AS id, dck_formats_name AS format,
        dck_archetypes_name as archetype, dck_decks_date AS ddate FROM dck_decks
        INNER JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_name
        INNER JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_name
        ORDER BY dck_decks_date DESC';

    /**
     * Lists all the decks in the format
     * @param int :formatId id of the format for the decks
     * @var string
     */
    const LIST_DECKS_FORMAT = 'SELECT dck_decks_id AS id, dck_formats_name AS format,
        dck_archetypes_name as archetype, dck_decks_date AS ddate FROM dck_decks
        LEFT JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_id
        LEFT JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_id
        WHERE dck_decks_formatid = :formatId ORDER BY dck_decks_date DESC';

    /**
     * List every deck in the database
     * @param int :qLimit the number of decks you want
     * @var string
     */
    const LIST_ALL_DECKS = 'SELECT dck_decks_id AS id, dck_formats_name AS format,
        dck_archetypes_name AS archetype, dck_decks_date AS ddate FROM dck_decks
        LEFT JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_id
        LEFT JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_id
        ORDER BY dck_decks_date DESC
        LIMIT :qLimit';

    /**
     * Autocomplete for the deckbuilder
     * @param string :query what to search up
     * @param int :qLimit how many cards to limit the search to
     * @var string
     */
    const AUTOCOMPLETE_CARDS = 'SELECT id, cardName, manaCost, cmc, type FROM cards
        WHERE
            cardName LIKE :query
            OR cards.text LIKE :query
        LIMIT :qLimit';

    const ADVANCED_SEARCH = 'SELECT id, cardName, manaCost FROM cards';

    /**
     * Get a deck with the given id
     * @param int :deckId id for the deck to grab
     * @var string
     */
    const GET_DECK_BY_ID = 'SELECT dck_deckcards_deckid as deckid, dck_deckcards_cardid as cardid,
        dck_deckcards_quantity as numberOf, dck_deckcards_sideboard as sideboard, type, cmc,
        cardName, manaCost, dck_decks_date AS ddate, colors, dck_formats_name AS formatName,
        dck_archetypes_name AS archetypeName, dck_decks_formatid AS formatId, dck_decks_archetypeid AS archetypeId
        FROM dck_deckcards INNER JOIN cards ON dck_deckcards.dck_deckcards_cardid = cards.id
        LEFT JOIN dck_decks ON dck_deckcards.dck_deckcards_deckid = dck_decks.dck_decks_id
        LEFT JOIN dck_formats ON dck_decks_formatid = dck_formats.dck_formats_id
        LEFT JOIN dck_archetypes ON dck_decks_archetypeid = dck_archetypes.dck_archetypes_id
        WHERE dck_decks_id = :deckId';

    /**
     * Get the cards for the given format
     * @param int :formatId id for the format of the cards
     * @var string
     */
    const GET_TOP_CARDS_FORMAT = 'SELECT dck_deckcards_cardid AS id, dck_deckcards_quantity AS numberOf,
        cardName FROM dck_deckcards INNER JOIN cards ON dck_deckcards.dck_deckcards_cardid = cards.id
        LEFT JOIN dck_decks ON dck_deckcards.dck_deckcards_deckid = dck_decks.dck_decks_id
            AND dck_decks.dck_decks_formatid = :formatId
        WHERE dck_decks_formatid = :formatId';

    const GET_CARD_BY_ID = 'SELECT * FROM cards WHERE id = :cardId';

    const GET_DECK_WITH_CARD = 'SELECT dck_decks_id AS id, dck_decks_date AS ddate,
                                       dck_formats_name AS formatName, dck_archetypes_name AS archetypeName
                                FROM dck_decks
                                LEFT JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats_id
                                LEFT JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes_id
                                LEFT JOIN dck_deckcards ON dck_decks.dck_decks_id = dck_deckcards_deckid
                                    WHERE dck_deckcards_cardid = :cardId
                                ORDER BY dck_decks_date DESC';

    const GET_FORMAT_NAME = 'SELECT dck_formats_name AS name FROM dck_formats WHERE dck_formats_id = :itemId';

    const GET_ARCHETYPE_NAME = 'SELECT dck_archetypes_name AS name FROM dck_archetypes WHERE dck_archetypes_id = :itemId';

    const INSERT_EVENT = 'INSERT IGNORE INTO dck_tournaments (dck_tournaments_name, dck_tournaments_date) VALUES (:tName, :tDate);
                          SELECT dck_tournaments_id FROM dck_tournaments WHERE (dck_tournaments_name = :tName AND dck_tournaments_date = :tDate);';

    const INSERT_USER = 'INSERT IGNORE INTO dck_users (dck_users_name) VALUES (:uName);
                         SELECT dck_users_id FROM dck_users WHERE (dck_users_name = :uName);';

    const INSERT_ARCHETYPE = 'INSERT IGNORE INTO dck_archetypes (dck_archetypes_name) VALUES (:aName);
                              SELECT dck_archetypes_id FROM dck_archetypes WHERE (dck_archetypes_name = :aName)';

    const GET_FORMATS = 'SELECT dck_formats_id AS id, dck_formats_name AS name FROM dck_formats';

    const GET_ARCHETYPES = 'SELECT dck_archetypes_id AS id, dck_archetypes_name AS name FROM dck_archetypes';

    const INSERT_CARD_INTO_DECK = 'INSERT INTO dck_deckcards (dck_deckcards_cardid, dck_deckcards_deckid, dck_deckcards_quantity, dck_deckcards_sideboard)
                                   SELECT cards.id, :deckId, :numberOf, :sideboard FROM cards WHERE card.cardName = :cardName';

    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$instance = new PDO('mysql:host=localhost;dbname=bmaurer_deckvc', 'root', 'root', $pdo_options);
        }
        return self::$instance;
    }

    public static function insertDeck($userId, $tournamentId, $formatId, $archetypeId, $deck)
    {
        try {
            $stmt = self::getInstance()->prepare(self::INSERT_NEW_DECK);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':tournamentId', $tournamentId, PDO::PARAM_INT);
            $stmt->bindParam(':formatId', $formatId, PDO::PARAM_INT);
            $stmt->bindParam(':archetypeId', $archetypeId, PDO::PARAM_INT);
            $stmt->execute();
            $deck_id = self::getInstance()->lastInsertId();
            foreach ($deck as $card) {
                $stmt = self::getInstance()->prepare(self::INSERT_CARD_INTO_DECK);
                $stmt->bindParam(':deckId', $deck_id, PDO::PARAM_INT);
                $stmt->bindParam(':numberOf', $card[0], PDO::PARAM_INT);
                $stmt->bindParam(':sideboard', $card[2], PDO::PARAM_BOOL);
                $stmt->bindParam(':cardName', $card[1], PDO::PARAM_STR);
                $stmt->execute();
            }
            return $deck_id;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return 0;
    }

    /**
     * @param string $username user to insert
     * @return int id of the inserted user, 0 if there was a problem
     */
    public static function insertUser($username)
    {
        try {
            $stmt = self::getInstance()->prepare(self::INSERT_USER);
            $stmt->bindParam(':uName', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['dck_users_id'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return 0;
    }

    public static function getArchetypes()
    {
        try {
            $stmt = self::getInstance()->prepare(self::GET_ARCHETYPES);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return [];
    }

    public static function getFormats()
    {
        try {
            $stmt = self::getInstance()->prepare(self::GET_FORMATS);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return [];
    }

    /**
     * @param string $archetypeName name of the archetype to insert
     * @return int id of the inserted archetype, 0 if there was a problem
     */
    public static function insertArchetype($archetypeName)
    {
        try {
            $stmt = self::getInstance()->prepare(self::INSERT_ARCHETYPE);
            $stmt->bindParam(':aName', $archetypeName, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['dck_archetypes_id'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return 0;
    }

    /**
     * @param string $eventName name of the event
     * @param string $eventDate date of the tournament, probably in YYYY-MM-DD format
     * @return int id of the inserted event, 0 if there was a problem
     */
    public static function insertEvent($eventName, $eventDate)
    {
        try {
            $stmt = self::getInstance()->prepare(self::INSERT_EVENT);
            $stmt->bindParam(':tName', $eventName, PDO::PARAM_STR);
            $stmt->bindParam(':tDate', $eventDate, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['dck_tournaments_id'];
        } catch (Exception $e) {
            error_log($e->getMessage(), 0);
        }
        return 0;
    }

    public static function getFormatName($formatId)
    {
        return self::getNameById($formatId, self::GET_FORMAT_NAME);
    }

    public static function getArchetypeName($archetypeId)
    {
        return self::getNameById($archetypeId, self::GET_ARCHETYPE_NAME);
    }

    private static function getNameById($itemId, $query)
    {
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    public static function getDeck($deckId)
    {
        try {
            $stmt = self::getInstance()->prepare(self::GET_DECK_BY_ID);
            $stmt->bindParam(':deckId', $deckId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $pdoe) {
            error_log($pdoe->getMessage(), 0);
        }
    }

    /**
     * @param int $formatId id of the format to fetch cards for
     * @param string[] $types what types of cards you want
     * @param int $limit how many of the top cards you want
     * @return [] array of size $limit of cards
     */
    public static function getTopCards($formatId, $types = [], $limit = 10)
    {
        try {
            $query = self::GET_TOP_CARDS_FORMAT;
            if (!empty($types)) {
                $query .= ' AND (';
                $totalTypes = count($types);
                for ($i = 0; $i < $totalTypes; $i++) {
                    $query .= "(type LIKE '$types[$i]')";
                    if ($i + 1 !== $totalTypes) {
                        $query .= ' OR ';
                    }
                }
                $query .= ')';
            }
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':formatId', $formatId, PDO::PARAM_INT);
            $stmt->execute();
            $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $newCards = [];
            $newerCards = [];
            $quantityCards = [];
            foreach ($cards as $card) {
                $index = self::cardExists($newCards, intval($card['id']));
                if ($index > -1) {
                    $newCards[$index]['numberOf'] += intval($card['numberOf']);
                    $quantityCards[$index] += intval($card['numberOf']);
                    continue;
                }
                array_push($newCards, ['id'=>intval($card['id']),
                                       'numberOf'=>intval($card['numberOf']),
                                       'cardName'=>$card['cardName']]);
                array_push($quantityCards, intval($card['numberOf']));
            }
            arsort($quantityCards);
            foreach (array_keys($quantityCards) as $key) {
                array_push($newerCards, $newCards[$key]);
                if (sizeof($newerCards) == $limit) {
                    break;
                }
            }
            return $newerCards;
        } catch (PDOException $pdoe) {
            error_log($pdoe->getMessage(), 0);
        }
    }

    /**
     * @param int $cardId the card to get information for
     * @return string[] the card and it's various attributes
     * @todo make a card object
     */
    public static function getCard($cardId)
    {
        try {
            $stmt = self::getInstance()->prepare(self::GET_CARD_BY_ID);
            $stmt->bindParam(':cardId', $cardId, PDO::PARAM_INT);
            $stmt->execute();
            return new containers\Card($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    /**
     * @param int $limit limit the number of decks to return
     * @return string[][] the latests $limit decks posted
     * @todo make a deck object
     */
    public static function getLatestDecks($limit = 10)
    {
        try {
            $stmt = self::getInstance()->prepare(self::LIST_ALL_DECKS);
            $stmt->bindParam(':qLimit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    /**
     * @param int $cardId id of the card to look for in decks
     * @return string[][] an array of decks with the various attributes
     * @todo make a deck object
     */
    public static function getDecksWithCard($cardId)
    {
        try {
            $stmt = self::getInstance()->prepare(self::GET_DECK_WITH_CARD);
            $stmt->bindParam(':cardId', $cardId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    /**
     * @param string $query the card name being searched for, surrounded with %
     * @param int $limit the number of results to return
     * @param string[][] an array of cards in an array
     */
    public static function queryForCards($query, $limit = 20)
    {
        try {
            $stmt = self::getInstance()->prepare(self::AUTOCOMPLETE_CARDS);
            $query = '%' . $query . '%';
            $stmt->bindParam(':query', $query, PDO::PARAM_STR);
            $stmt->bindParam(':qLimit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    public static function queryForCardsAdvanced($query, $limit = 20)
    {
        $query = explode(' ', $query);
        $builtQuery = '';
        foreach ($query as $qItem) {
            if (strpos($qItem, 'manaCost:')) {
                $builtQuery .= 'manaCost LIKE %' . substr(9) . '%';
            }
        }
        $finalQuery = self::ADVANCED_SEARCH . ' WHERE ' . $builtQuery . ' LIMIT :qLimit';
        try {
            $stmt = self::getInstance()->prepare($finalQuery);
            $stmt->bindParam(':qLimit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
        }
    }

    /**
     * @param string[] $array the array of cards
     * @param int      $cardId the id of the card to find
     * @return int     index of the card, -1 if it isn't found
     */
    private static function cardExists($array, $cardId)
    {
        for ($i = 0; $i < sizeof($array); $i++) {
            if ($array[$i]["id"] == $cardId) {
                return $i;
            }
        }
        return -1;
    }
}
