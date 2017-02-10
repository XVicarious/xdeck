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

    const INSERT_CARD_INTO_DECK = 'SELECT @cardId := `dck_cards_id` FROM dck_cards WHERE dck_cards_name LIKE :cardName;
                                   INSERT INTO dck_deckcards (dck_deckcards_deckid, dck_deckcards_cardid, dck_deckcards_quantity, dck_deckcards_sideboard) VALUES (:deckId, @cardId, :cardQuantity, :isSideboard);';

}
