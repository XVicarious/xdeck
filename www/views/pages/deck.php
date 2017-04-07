<?php
require_once('php/CardCompare.php');
use \xdeck\Database;

$deck = Database::getDeck($_GET['id']);

usort($deck, 'CardCompare::compareClassic');
$deckString = '';
$sideString = '';
$deckCount = 0;
$sideCount = 0;
$cardTypes = ['Creature', 'Planeswalker', 'Instant', 'Sorcery', 'Artifact', 'Enchantment', 'Land'];
$typeCounts = [['Creature'=>0,'Planeswalker'=>0,'Instant'=>0,'Sorcery'=>0,'Artifact'=>0,'Enchantment'=>0,'Land'=>0],
               ['Creature'=>0,'Planeswalker'=>0,'Instant'=>0,'Sorcery'=>0,'Artifact'=>0,'Enchantment'=>0,'Land'=>0]];
foreach ($deck as $card) {
    if (intval($card['sideboard'])) {
        $editing = &$sideString;
        $sideCount += intval($card['numberOf']);
    } else {
        $editing = &$deckString;
        $deckCount += intval($card['numberOf']);
    }
    for ($i = 0; $i < count($cardTypes); $i++) {
        if (strpos($card['type'], $cardTypes[$i]) !== false) {
            $typeCounts[intval($card['sideboard'])][$cardTypes[$i]] += intval($card['numberOf']);
            break;
        }
    }
    if ($card['manaCost'] == null) {
        $card['manaCost'] = '';
    }
    $editing .= '<a href="/card/' . $card['cardid'] . '" class="collection-item"><span class="badge left left-badge">' .
                $card['numberOf'] . '</span><span>' . $card['cardName'] . '</span>' .
                '<span class="secondary-content mana-cost">' . $card['manaCost'] . '</span></span></a>';
}
$tipDeck = '';
$tipSide = '';
for ($i = 0; $i < count($cardTypes); $i++) {
    $cardType = $cardTypes[$i];
    $countDeck = $typeCounts[0][$cardType];
    $countSide = $typeCounts[1][$cardType];
    if ($countDeck !== 0) {
        $tipDeck .= ($cardType . ': ' . $countDeck . '<br/>');
    }
    if ($countSide !== 0) {
        $tipSide .= ($cardType . ': ' . $countSide . '<br/>');
    }
}
?>
<div class="row">
    <div class="col s12 m12 l8">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <a id="formatName" href="/format/<?php echo $deck[0]['formatId']; ?>">
                        <?php echo $deck[0]['formatName']; ?>
                    </a>
                    <a id="archetypeName" href="/archetype/<?php echo $deck[0]['archetypeId']; ?>">
                        <?php echo $deck[0]['archetypeName']; ?>
                    </a>
                </span>
                <div id="deck" class="collection with-header">
                    <div class="collection-header">Mainboard
                        <span id="deckcount" class="badge new tooltipped" data-badge-caption="cards" data-position="bottom">
                            <?php echo $deckCount; ?>
                        </span>
                    </div>
                    <?php echo $deckString; ?>
                </div>
                <div id="sideboard" class="collection with-header">
                    <div class="collection-header">Sideboard
                        <span id="sidecount" class="badge new tooltipped" data-badge-caption="cards" data-position="bottom">
                            <?php echo $sideCount; ?>
                        </span>
                    </div>
                    <?php echo $sideString; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m12 l4">
        <div class="collection with-header card-panel">
            <div class="collection-header">Recent <?php echo $deck[0]['formatName']; ?> <?php echo $deck[0]['archetypeName']; ?> Decks</div>
        </div>
    </div>
</div>
<script>
    var tooltipDeck = '<?php echo $tipDeck; ?>';
    var tooltipSide = '<?php echo $tipSide; ?>';
</script>
