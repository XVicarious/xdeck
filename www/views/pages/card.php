<?php
$formats = ['vintage', 'legacy', 'modern', 'standard', 'commander'];
$card = \xdeck\Database::getCard($_GET['id']);
$definedColor = ['W'=>'yellow', 'U'=>'blue', 'B'=>'purple', 'R'=>'red', 'G'=>'green', 'A'=>'amber'];
$color = $card->colors[0];
if (count($card->colors) > 1) {
    $color = 'A';
}
?>
<div class="row">
    <div class="col s12 m12 l8">
        <div class="card-panel <?php echo $definedColor[$color]; ?> lighten-4">
            <div class="container">
                <div class="row">
                    <div class="col s12 card-panel card-details">
                        <span class="name-wrapper flow-text"><?php echo $card->cardName; ?></span>
                        <span class="mana-cost flow-text secondary-content"><?php echo $card->manaCost; ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 card-panel center grey lighten-5">
                        <span class="flow-text"><?php echo $card->typeLine(); ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 card-panel grey lighten-5">
                        <span class="flow-text card-text"><?php echo $card->text; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m12 l4">
        <div class="row">
            <div class="col s12">
                <div class="collection with-header card-panel grey lighten-5">
                    <div class="collection-header">Recent Decks with <?php echo $card->cardName; ?></div>
                    <?php
                    foreach (\xdeck\Database::getDecksWithCard($_GET['id']) as $deck) {
                        echo '<a href="/deck/' . $deck['id'] . '" class="collection-item">';
                        echo '<span>'. $deck['formatName'] . ' ' . $deck['archetypeName'] . '</span>';
                        echo '<span class="secondary-content">' . $deck['ddate'] . '</span></a>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="collection with-header card-panel grey lighten-5">
                    <div class="collection-header">Legality</div>
                    <?php
                    foreach ($card->legality as $format) {
                        $legal = ($format[1] === 0) ? 'Banned' : ($format[1] === 1 ? 'Legal' : 'Not Legal');
                        $item = '<div class="collection-item">' .
                                '<span>' . $format[0] . '</span>' .
                                '<span class="secondary-content">' . $legal . '</span>' .
                                '</div>';
                        echo $item;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
