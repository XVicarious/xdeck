<?php

class SqlStatements {

    const INSERT_NEW_DECK = 'INSERT INTO dck_decks (dck_decks_userid,
                                                    dck_decks_tournamentid,
                                                    dck_decks_formatid,
                                                    dck_decks_archetypeid)
                                            VALUES (:userId,
                                                    :tournamentId,
                                                    :formatId,
                                                    :archetypeId)';

    const INSERT_CARD = 'INSERT INTO dck_cards (dck_cards_name) SELECT * FROM (SELECT :cardName) AS temp WHERE NOT EXISTS (SELECT dck_cards_name FROM dck_cards WHERE dck_cards_name = :cardName) LIMIT 1';

    const INSERT_CARD_INTO_DECK = 'INSERT INTO dck_deckcards (dck_deckcards_deckid, dck_deckcards_cardid, dck_deckcards_quantity, dck_deckcards_sideboard) VALUES (:deckId, :cardId, :cardQuantity, :isSideboard);';

    const LIST_DECKS_FORMAT_ARCHETYPE = 'SELECT dck_decks_id AS id, dck_formats_name AS format, dck_archetypes_name as archetype, dck_decks_date AS ddate FROM dck_decks
                                             INNER JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_name
                                             INNER JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_name
                                             ORDER BY dck_decks_date DESC';

    const LIST_ALL_DECKS = 'SELECT dck_decks_id AS id, dck_formats_name AS format, dck_archetypes_name AS archetype, dck_decks_date AS ddate FROM dck_decks
                                INNER JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_name
                                INNER JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_name
                                ORDER BY dck_decks_date DESC';

    const AUTOCOMPLETE_CARDS = 'SELECT id, cardName, manaCost, cmc, type FROM cards WHERE cardName LIKE :query';

    const GET_DECK_BY_ID = 'SELECT dck_deckcards_deckid as deckid, dck_deckcards_cardid as cardid, dck_deckcards_quantity as numberOf, dck_deckcards_sideboard as sideboard, type, cmc, cardName, manaCost, dck_decks_date AS ddate, colors, dck_formats_name AS formatName, dck_archetypes_name AS archetypeName, dck_decks_formatid AS formatId, dck_decks_archetypeid AS archetypeId FROM dck_deckcards
        INNER JOIN cards ON dck_deckcards.dck_deckcards_cardid = cards.id
        LEFT JOIN dck_decks ON dck_deckcards.dck_deckcards_deckid = dck_decks.dck_decks_id
        LEFT JOIN dck_formats ON dck_decks_formatid = dck_formats.dck_formats_id
        LEFT JOIN dck_archetypes ON dck_decks_archetypeid = dck_archetypes.dck_archetypes_id
        WHERE dck_decks_id = :deckId';

    const GET_TOP_CARDS_FORMAT = 'SELECT dck_deckcards_cardid AS id, dck_deckcards_quantity AS numberOf, cardName FROM dck_deckcards
                                      INNER JOIN cards ON dck_deckcards.dck_deckcards_cardid = cards.id
                                      LEFT JOIN dck_decks ON dck_deckcards.dck_deckcards_deckid = dck_decks.dck_decks_id AND dck_decks.dck_decks_formatid = :formatId';

}
