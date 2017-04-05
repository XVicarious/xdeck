require(['jquery', 'materialize', 'convertcost'], function($) {
  $('.mana-cost').each(function() {
    $(this).html(ConvertCost.parse($(this).text()));
  });
  let $deckcount = $('#deckcount');
  let $sidecount = $('#sidecount');
  $deckcount.attr('data-tooltip', tooltipDeck);
  $sidecount.attr('data-tooltip', tooltipSide);
  $('.tooltipped').tooltip({delay: 50, html: true});
});
