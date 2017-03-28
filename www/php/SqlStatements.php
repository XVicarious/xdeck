<?php

/**
 * Encapsulates any and all SQL statements needed for xdeck
 */
class SqlStatements
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
    const INSERT_CARD_INTO_DECK = 'INSERT INTO dck_deckcards
        (dck_deckcards_deckid, dck_deckcards_cardid,
         dck_deckcards_quantity, dck_deckcards_sideboard)
         VALUES (:deckId, :cardId, :cardQuantity, :isSideboard);';

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
     * todo: add a limit to this, at least as an option
     * @var string
     */
    const LIST_ALL_DECKS = 'SELECT dck_decks_id AS id, dck_formats_name AS format,
        dck_archetypes_name AS archetype, dck_decks_date AS ddate FROM dck_decks
        LEFT JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_id
        LEFT JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_id
        ORDER BY dck_decks_date DESC';

    /**
     * Autocomplete for the deckbuilder
     * @param string :query what to search up
     * @var string
     */
    const AUTOCOMPLETE_CARDS = 'SELECT id, cardName, manaCost, cmc, type FROM cards WHERE cardName LIKE :query';

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
}
