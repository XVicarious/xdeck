<?php
$formats = ['vintage', 'legacy', 'modern', 'standard', 'commander'];
$card = Database::getCard($_GET['id'])[0];
$color = explode(',', $card['colors']);
$color = (count($color) > 1) ? 'amber' : strtolower($color[0]);
if ($color === 'white') {
    $color = 'yellow';
} elseif ($color === 'black') {
    $color = 'deep-purple';
} elseif ($color === '') {
    $color = 'grey';
}
if (strpos($card['type'], 'Land') !== false) {
    $color = 'brown';
}
//print_r($card);
?>
<div class="row">
    <div class="col s12 card-panel lighten-4 <?php echo $color; ?>">
        <div class="container">
            <div class="row">
                <div class="col s12 card-panel card-details">
                    <span class="name-wrapper flow-text"><?php echo $card['cardName']; ?></span>
                    <span class="mana-cost flow-text secondary-content"><?php echo $card['manaCost']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12 card-panel center grey lighten-5">
                    <span class="flow-text"><?php echo $card['type']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12 card-panel grey lighten-5">
                    <span class="flow-text card-text"><?php echo $card['text']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s6 l4">
        <div class="collection with-header card-panel grey lighten-5">
            <div class="collection-header">Legality</div>
            <?php
            foreach ($formats as $format) {
                $legal = ($card[$format] === null) ? 'Not Legal' : $card[$format];
                $format = ucfirst($format);
                $item = '<div class="collection-item">' .
                        '<span>' . $format . '</span>' .
                        '<span class="secondary-content">' . $legal . '</span>' .
                        '</div>';
                echo $item;
            }
            ?>
        </div>
    </div>
    <div class="col s6 l8">
        <div class="collection with-header card-panel grey lighten-5">
            <div class="collection-header">Recent Decks with <?php echo $card['cardName']; ?></div>
            <?php
            foreach (Database::getDecksWithCard($_GET['id']) as $deck) {
                echo '<a href="/deck/' . $deck['id'] . '" class="collection-item">';
                echo '<span>'. $deck['formatName'] . ' ' . $deck['archetypeName'] . '</span>';
                echo '<span class="secondary-content">' . $deck['ddate'] . '</span></a>';
            }
            ?>
        </div>
    </div>
</div>
