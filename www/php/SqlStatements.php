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

    const LIST_DECKS_FORMAT_ARCHETYPE = 'SELECT dck_decks_id, dck_formats_name, dck_archetypes_name, dck_decks_date FROM dck_decks
                                             INNER JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_name
                                             INNER JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_name
                                             ORDER BY dck_decks_date DESC';

    const AUTOCOMPLETE_CARDS = 'SELECT id, cardName, manaCost FROM cards WHERE cardName LIKE :query';

    const GET_DECK_BY_ID = 'SELECT dck_deckcards_deckid as deckid, dck_deckcards_cardid as cardid, dck_deckcards_quantity as numberOf, dck_deckcards_sideboard as sideboard, type, cmc, cardName, manaCost, dck_decks_date AS ddate FROM dck_deckcards
        INNER JOIN cards ON dck_deckcards.dck_deckcards_cardid = cards.id
        LEFT JOIN dck_decks ON dck_deckcards.dck_deckcards_deckid = dck_decks.dck_decks_id
        WHERE dck_decks_id = :deckId';

}
