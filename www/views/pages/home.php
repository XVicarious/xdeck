<div class="row">
    <div id="recentDecks" class="col s12 collection with-header card-panel">
        <div class="collection-header"><h4>Recent Decks</h4></div>
    </div>
</div>
<div class="row">
    <div class="col s4">
        <div id="topPauperCards" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Pauper Cards</h5></div>
        </div>
    </div>
    <div class="col s4">
        <div id="topModernCards" class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Modern Cards</h5></div>
        </div>
    </div>
    <div class="col s4">
        <div class="collection with-header card-panel">
            <div class="collection-header"><h5>Top Legacy Cards</h5></div>
        </div>
    </div>
</div>
<script>
    requirejs(['./js/common'], function (common) {
      var topPauperCards = '<?php echo Database::getTopCards(2) ?>';
      var topModernCards = '<?php echo Database::getTopCards(1) ?>';
      var topLegacyCards = '<?php echo Database::getTopCards(3) ?>';
      requirejs(['app/index']);
    });
</script>
