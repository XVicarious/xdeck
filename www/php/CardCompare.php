<?php

class CardCompare
{
    const CARD_TYPES = ['Creature', 'Planeswalker', 'Instant', 'Sorcery', 'Artifact', 'Enchantment', 'Land'];
    const COLORS = 'WUBRG';
    const NON_COLORS = 'LAC';
    public static function compareCMC($card1, $card2)
    {
        return intval($card1['cmc']) - intval($card2['cmc']);
    }
    public static function compareName($card1, $card2)
    {
        return strcmp($card1['cardName'], $card2['cardName']);
    }
    public static function compareSuperType($card1, $card2)
    {
        foreach (self::CARD_TYPES as $type) {
            $type1include = strpos($card1['type'], $type);
            $type2include = strpos($card2['type'], $type);
            if ($type1include !== false && $type2include !== false) {
                return 0;
            } elseif ($type1include !== false) {
                return -1;
            } elseif ($type2include !== false) {
                return 1;
            }
        }
        return 0;
    }
    public static function compareSideboard($card1, $card2)
    {
        if ((bool)$card1['sideboard'] && !(bool)$card2['sideboard']) {
            return 1;
        } elseif (!(bool)$card1['sideboard'] && (bool)$card2['sideboard']) {
            return -1;
        }
        return 0;
    }
    public static function compareColors($card1, $card2)
    {
        $color1 = self::getColors($card1['colors']);
        $color2 = self::getColors($card2['colors']);
        if (strlen($color1) + strlen($color2) === 0) {
            $color1 = $card1['colors'];
            $color2 = $card2['colors'];
            return self::priorityCompare($color1, $color2, false);
        }
        if (strlen($color1) < strlen($color2)) {
            return -1;
        } elseif (strlen($color1) > strlen($color2)) {
            return 1;
        }
        return self::priorityCompare($color1, $color2);
    }
    public static function compareClassic($card1, $card2)
    {
        $sideboardCompare = self::compareSideboard($card1, $card2);
        if ($sideboardCompare === 0) {
            $superTypeCompare = self::compareSuperType($card1, $card2);
            if ($superTypeCompare === 0) {
                $cmcCompare = self::compareCMC($card1, $card2);
                if ($cmcCompare === 0) {
                    $colorCompare = self::compareColors($card1, $card2);
                    if ($colorCompare === 0) {
                        return self::compareName($card1, $card2);
                    }
                    return $colorCompare;
                }
                return $cmcCompare;
            }
            return $superTypeCompare;
        }
        return $sideboardCompare;
    }
    private static function priorityCompare($string1, $string2, $colorful = true)
    {
        $priority1 = 0;
        $priority2 = 0;
        if (strcmp($string1, $string2) === 0) {
            return 0;
        }
        for ($i = 0; $i < min(strlen($string1), strlen($string2)); $i++) {
            $priority1 = ($colorful) ? strpos(self::COLORS, $string1{$i}) : strpos(self::NON_COLORS, $string1{$i});
            $priority2 = ($colorful) ? strpos(self::COLORS, $string2{$i}) : strpos(self::NON_COLORS, $string2{$i});
            if ($priority1 !== $priority2) {
                return $priority1 < $priority2 ? -1 : 1;
            }
        }
        return 0;
    }
    private static function getColors($colorString)
    {
        $validColors = '';
        $newColorString = '';
        if ($colorString === null || trim($colorString) == false) {
            return $validColors;
        }
        $colorString = explode(',', $colorString);
        foreach ($colorString as $color) {
            if ($color === 'Blue') {
                $newColorString .= 'U';
            }
            $newColorString .= $color{1};
        }
        $colorString = $newColorString;
        for ($i = 0; $i < strlen($colorString); $i++) {
            if (strpos(self::COLORS, $colorString{$i}) !== false) {
                $validColors .= $colorString{$i};
            }
        }
        return $validColors;
    }
}
