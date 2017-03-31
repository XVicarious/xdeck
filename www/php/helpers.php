<?php

class Helpers
{
    public static function makeCardCollection($aCards)
    {
        $collection = '';
        foreach ($aCards as $card) {
            $collection .= '<a href="/card/'.$card['id'] . '" class="collection-item">'.
                            $card['cardName'] . '<span class="badge new" data-badge-caption="copies">' .
                            $card['numberOf'] . '</span></a>';
        }
        return $collection;
    }

    public static function makeDeckCollection($aDecks)
    {
        $collection ='';
        foreach ($aDecks as $deck) {
            $collection .= '<a href="/deck/' . $deck['id'] . '" class="collection-item">' .
                            '<div>' . $deck['format'] . ' ' . $deck['archetype'] . '<span class="secondary-content">' .
                            '{date}</span>';
        }
        return $collection;
    }
}
