<?php
$deck = Database::getDeck($_GET['id']);
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
    $editing .= '<a class="collection-item"><span class="badge left left-badge">' .
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
    <div class="col s12 card-panel">
        <div class="row">
            <div class="col s12">
                <h4 class="center">
                    <a id="formatName" href="./?action=format&id=<?php echo $deck[0]['formatId']; ?>">
                        <?php echo $deck[0]['formatName']; ?>
                    </a>
                    <a id="archetypeName" href="?action=archetype&id=<?php echo $deck[0]['archetypeId']; ?>">
                        <?php echo $deck[0]['archetypeName']; ?>
                    </a>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col s12"><a id="deckDate" class="center"></a></div><!-- todo: Fix centering of date -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 card-panel">
        <div>
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
<script>
    var tooltipDeck = '<?php echo $tipDeck; ?>';
    var tooltipSide = '<?php echo $tipSide; ?>';
    requirejs(['./js/common'], function (common) {
      requirejs(['app/deck']);
    });
</script>