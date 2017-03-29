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
print_r($card);
?>
<style>
.large-text {
    font-size: 48px;
}
.ms, span.card-name {
    margin-top: 1%;
    margin-bottom:1%;
    margin-left:0.1em;
}
.card-details {
    margin-top: 20px;
}
</style>
<div class="row">
    <div class="col s12 card-panel lighten-4 <?php echo $color; ?>">
        <div class="row card-details">
            <div class="col s12 l6">
                <span class="name-wrapper flow-text"><?php echo $card['cardName']; ?></span>
            </div>
            <div class="col s12 l6">
                <span class="right mana-cost flow-text"><?php echo $card['manaCost']; ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 card-panel lighten-4 <?php echo $color; ?>">
        <div class="row">
            <div class="container">
                <div class="col s12 card-panel center grey lighten-5">
                    <span class="flow-text"><?php echo $card['type']; ?></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <span class="flow-text card-text"><?php echo $card['text']; ?></span>
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
</div>
<script>
requirejs(['/js/common.js'], function (common) {
  require(['jquery', 'convertcost'], function ($) {
    $('.mana-cost').html(ConvertCost.parse($('.mana-cost').text()));
    $('.card-text').html(ConvertCost.parse($('.card-text').text()));
  });
});
</script>
