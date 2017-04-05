<?php

namespace xdeck;

class Helpers
{

    const SUPER_TYPES = ['Basic', 'Legendary', 'Snow', 'World'];

    const CARD_TYPES = ['Artifact', 'Creature', 'Tribal', 'Enchantment', 'Land', 'Planeswalker', 'Instant', 'Sorcery'];

    const GAME_FORMATS = ['vintage', 'legacy', 'modern', 'standard', 'commander'];

    const COLOR_CODES = ['W', 'U', 'B', 'R', 'G', 'X', 'Y', 'Z', 'P', 'T', 'S', 'h', 'r', 'w', '∞'];

    const FORMAT_CHARS = ['/', '{', '}', '(', ')'];

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

    public static function parseManaCardText($toParse)
    {
        if ($toParse == null || !trim($toParse)) {
            return '';
        }
        $toParse = preg_replace('/(\r\n|\n|\r)/m', '<br/>', $toParse);
        $stringArray = str_split($toParse, 1);
        $parsedString = '';
        $build = '';
        $iStart = '<i class="ms ms-cost ms-shadow ms-';
        $iEnd = '"/>';
        $hEnd = ''; // used in case of half mana
        $lastEndTag = -1;
        $status = 0;
        $iSplit = ' ms-split'; // for split mana costs
        $stringArrayLength = count($stringArray);
        for ($i = 0; $i < $stringArrayLength; $i++) {
            $currentChar = $stringArray[$i];
            if (in_array($currentChar, self::FORMAT_CHARS) || in_array($currentChar, self::COLOR_CODES) || ctype_digit($currentChar)) {
                $nextChar = $stringArray[$i + 1];
                if ($currentChar === self::FORMAT_CHARS[1] || $currentChar === self::FORMAT_CHARS[3] || $currentChar === self::FORMAT_CHARS[4]) {
                    if ($currentChar === self::FORMAT_CHARS[1]) {
                        $status = 1;
                    }
                    if ($i >= 0 && $lastEndTag <= $i - 1) {
                        if ($currentChar === self::FORMAT_CHARS[4]) {
                            $i++;
                        }
                        $parsedString .= substr($toParse, $lastEndTag + 1, $i);
                        if ($currentChar === self::FORMAT_CHARS[3]) {
                            $parsedString .= '<span class="reminder">';
                        } elseif ($currentChar === self::FORMAT_CHARS[4]) {
                            $parsedString .= '</span>';
                        }
                        $lastEndTag = $i - 1;
                    }
                }
                if ($nextChar !== null && $nextChar === self::FORMAT_CHARS[2] && $status = 1) {
                    $lastEndTag = $i + 1;
                    $status = 0;
                    if (in_array($currentChar, self::COLOR_CODES) || ctype_digit($currentChar)) {
                        if (trim($build)) {
                            $build += $currentChar;
                            if ($build === 'hw' || $build === 'hr') {
                                $parsedString += '<span class="ms-half">';
                                $build = substr($build, $i, 1);
                                $hEnd = '</span>';
                            }
                            $parsedString .= ($iStart . strtolower($build) . (($stringArray[$i - 1] === self::FORMAT_CHARS[0] && $currentChar !== self::COLOR_CODES[8]) ? $iSplit : '') . $iEnd);
                            $parsedString .= $hEnd;
                            $hEnd = '';
                            $build = '';
                            continue;
                        }
                        $currentChar = strtolower($currentChar);
                        if ($currentChar === self::COLOR_CODES[9]) {
                            $currentChar .= 'ap';
                        } elseif ($currentChar === self::COLOR_CODES[14]) {
                            $currentChar = 'infinity';
                        }
                        $parsedString .= ($iStart . $currentChar . $iEnd);
                    }
                } elseif ($status === 1 && (in_array($currentChar, self::COLOR_CODES) || ctype_digit($currentChar)) && !in_array($currentChar, self::FORMAT_CHARS)) {
                    $build .= $currentChar;
                }
            }
        }
        $parsedString .= substr($toParse, $lastEndTag + 1);
        return $parsedString;
    }
}
