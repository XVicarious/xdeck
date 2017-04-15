require(['jquery', 'convertcost'], function($) {
  $('.mana-cost').html(ConvertCost.parse($('.mana-cost').text()));
  $('.card-text').html(ConvertCost.parse($('.card-text').text()));
});
