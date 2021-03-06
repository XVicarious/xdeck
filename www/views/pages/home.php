<?php
use \xdeck\Database;

?>
<div class="row">
    <div class="col s12">
        <div id="recentDecks" class="collection with-header card-panel">
            <div class="collection-header"><h4>Recent Decks</h4></div>
            <?php
            foreach (Database::getLatestDecks() as $deck) {
                echo '<a class="collection-item" href="/deck/'. $deck['id'] .'/">';
                echo $deck['format'] . ' ' . $deck['archetype'];
                echo '<span class="secondary-content">' . $deck['ddate'] . '</span></a>';
            }
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s4">
        <div id="topPauperCards" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Pauper Cards</h5></div>
            <?php
            foreach (Database::getTopCards(2) as $card) {
                $cardString = '<a href="/card/' . $card['id'] . '/" class="collection-item">' .
                               $card['cardName'] . '<span class="badge new" data-badge-caption="copies">' .
                               $card['numberOf'] . '</span></a>';
                echo $cardString;
            }
            ?>
        </div>
    </div>
    <div class="col s4">
        <div id="topModernCards" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Modern Cards</h5></div>
            <?php
            foreach (Database::getTopCards(1) as $card) {
                $cardString = '<a href="/card/' . $card['id'] . '/" class="collection-item">' .
                               $card['cardName'] . '<span class="badge new" data-badge-caption="copies">' .
                               $card['numberOf'] . '</span></a>';
                echo $cardString;
            }
            ?>
        </div>
    </div>
    <div class="col s4">
        <div class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Legacy Cards</h5></div>
            <?php
            foreach (Database::getTopCards(3) as $card) {
                $cardString = '<a href="/card/' . $card['id'] . '/" class="collection-item">' .
                               $card['cardName'] . '<span class="badge new" data-badge-caption="copies">' .
                               $card['numberOf'] . '</span></a>';
                echo $cardString;
            }
            ?>
        </div>
    </div>
</div>
