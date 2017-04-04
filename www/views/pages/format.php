<?php
require_once('php/helpers.php');
use \xdeck\Database;
use \xdeck\Helpers;
?>
<div class="row">
    <div class="col s12">
        <div id="recentDecks" class="collection with-header card-panel">
            <div class="collection-header"><h4>Recent <?php echo Database::getFormatName($_GET['id']); ?> Decks</h4></div>
            <!-- todo: figure out padding issue here -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col s4">
        <div id="topCreatures" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Creatures</h5></div>
            <?php echo Helpers::makeCardCollection(Database::getTopCards(intval($_GET['id']), ['%Creature%'])); ?>
        </div>
    </div>
    <div class="col s4">
        <div id="topSpells" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Spells</h5></div>
            <!-- fixme: Enchantment Creatures appear, they shouldn't -->
            <?php echo Helpers::makeCardCollection(Database::getTopCards(intval($_GET['id']), ['Instant%', 'Sorcery%', '%Enchantment%', '%Artifact%']))?>
        </div>
    </div>
    <div class="col s4">
        <div id="topLands" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Lands</h5></div>
            <?php echo Helpers::makeCardCollection(Database::getTopCards(intval($_GET['id']), ['%Land%'])); ?>
        </div>
    </div>
</div>
