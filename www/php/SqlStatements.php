<?php

class SqlStatements {

    /**
     * int :userId the user that is making the deck
     * int :tournamentId the tournament this deck is from, 0 if no tournament
     * int :formatId the format this deck is from
     * int :archetypeId the archetype of this deck
     */
    const INSERT_NEW_DECK = 'INSERT INTO dck_decks (dck_decks_userid,
                                                    dck_decks_tournamentid,
                                                    dck_decks_formatid,
                                                    dck_decks_archetypeid)
                                            VALUES (:userId,
                                                    :tournamentId,
                                                    :formatId,
                                                    :archetypeId)';

    const INSERT_CARD = 'INSERT INTO dck_cards (dck_cards_name) SELECT * FROM (SELECT :cardName) AS temp WHERE NOT EXISTS (SELECT dck_cards_name FROM dck_cards WHERE dck_cards_name = :cardName) LIMIT 1';

    const INSERT_CARD_INTO_DECK = 'SELECT @cardId := `dck_cards_id` FROM dck_cards WHERE dck_cards_name LIKE :cardName;
                                   INSERT INTO dck_deckcards (dck_deckcards_deckid, dck_deckcards_cardid, dck_deckcards_quantity, dck_deckcards_sideboard) VALUES (:deckId, @cardId, :cardQuantity, :isSideboard);';

    const LIST_DECKS_FORMAT_ARCHETYPE = 'SELECT dck_decks_id, dck_formats_name, dck_archetypes_name, dck_decks_date FROM dck_decks
                                             INNER JOIN dck_formats ON dck_decks.dck_decks_formatid = dck_formats.dck_formats_name
                                             INNER JOIN dck_archetypes ON dck_decks.dck_decks_archetypeid = dck_archetypes.dck_archetypes_name
                                             ORDER BY dck_decks_date DESC';
    //

}
