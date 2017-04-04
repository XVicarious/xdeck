<?php

namespace xdeck\containers;

require_once('helpers.php');

use \xdeck\Helpers;

class Card
{

    public $cardId;
    public $layout;
    public $cardName;
    public $manaCost;
    public $cmc;
    public $colors;
    public $typeLine;
    public $text;
    public $power;
    public $toughness;
    public $loyalty;
    public $reserved;
    public $legality;

    public function __construct($cardDb)
    {
        $this->cardId = intval($cardDb['id']);
        $this->layout = $cardDb['layout'];
        $this->cardName = $cardDb['cardName'];
        $this->manaCost = $cardDb['manaCost'];
        $this->cmc = intval($cardDb['cmc']);
        $this->colors = [];
        $this->setColors($cardDb['colors']);
        $this->typeLine = ['super'=>[],'type'=>[],'subtype'=>[]];
        $tmpTypes = explode(' ', $cardDb['type']);
        foreach ($tmpTypes as $type) {
            if (in_array($type, Helpers::SUPER_TYPES)) {
                array_push($this->typeLine['super'], $type);
            } elseif (in_array($type, Helpers::CARD_TYPES)) {
                array_push($this->typeLine['type'], $type);
            } elseif ($type !== '—') {
                array_push($this->typeLine['subtype'], $type);
            }
        }
        $this->text = $cardDb['text'];
        $this->power = $cardDb['power'];
        $this->toughness = $cardDb['toughness'];
        $this->loyalty = $cardDb['loyalty'];
        $this->reserved = filter_var($cardDb['reserved'], FILTER_VALIDATE_BOOLEAN);
        $this->legality = [];
        foreach (Helpers::GAME_FORMATS as $format) {
            $legality = $cardDb[$format];
            if ($legality === 'Banned') {
                array_push($this->legality, [ucfirst($format), 0]);
                continue;
            } elseif ($legality === 'Legal') {
                array_push($this->legality, [ucfirst($format), 1]);
                continue;
            }
            array_push($this->legality, [ucfirst($format), 2]);
        }
    }

    /**
     * @param string $colorString the color string provided by the database
     * @return null
     */
    private function setColors($colorString)
    {
        $tmpColors = explode(',', $colorString);
        foreach ($tmpColors as $color) {
            // If the color is blue, we want it to be U, because black is B.
            if ($color === 'Blue') {
                array_push($this->colors, 'U');
                continue;
            }
            array_push($this->colors, substr($color, 0, 1));
        }
    }

    /**
     * @return string the card's typeline as defined by its super, card, and sub types
     */
    public function typeLine()
    {
        $line = '';
        foreach ($this->typeLine as $key => $type) {
            if ($key === 'subtype' && !empty($type)) {
                $line .= ' —';
            }
            foreach ($type as $sub) {
                $line .= (($key !== 'super' || $line !== '') ? ' ' : '') . $sub;
            }
        }
        return $line;
    }
}
