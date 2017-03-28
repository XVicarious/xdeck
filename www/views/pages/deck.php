<div class="row">
    <div class="col s12 card-panel">
        <div class="row"><div class="col s12"><h4 class="center"><a id="formatName" href="#">[deck_format]</a>&nbsp;<a id="archetypeName" href="#">[deck_archetype]</a></h4></div></div>
        <div class="row"><div class="col s12"><a id="deckDate" class="center"></a></div></div><!-- todo: Fix centering of date -->
    </div>
</div>
<div class="row">
    <div class="col s12 card-panel">
        <div>
            <div id="deck" class="collection with-header">
                <div class="collection-header">Mainboard<span id="deckcount" class="badge new tooltipped" data-badge-caption="cards" data-position="bottom"></span></div>
            </div>
            <div id="sideboard" class="collection with-header">
                <div class="collection-header">Sideboard<span id="sidecount" class="badge new tooltipped" data-badge-caption="cards" data-position="bottom"></span></div>
            </div>
        </div>
    </div>
</div>
<script>
    var data = '<?php echo Database::getDeck($_GET['id']); ?>';
    requirejs(['./js/common'], function (common) {
      requirejs(['app/deck']);
    });
</script>
