(function($) {
	"use strict";

	function edd_get_base_currency() {
		return (edd_steem !== 'undefined' && 'cart' in edd_steem && 'base_currency' in edd_steem.cart) ? edd_steem.cart.base_currency : 'USD';
	}

	function edd_get_amount($currency) {
		return (edd_steem !== 'undefined' && 'cart' in edd_steem && 'amounts' in edd_steem.cart) ? edd_steem.cart.amounts[$currency + '_' + edd_get_base_currency()] : -1;
	}
	
	$(document).on('change', 'select[name="edd_steem-amount_currency"]', function(event) {
		var $currency = this.value;
		var $amount = edd_get_amount($currency);

		if ($amount > -1) {
			$('#edd_steem-amount').html($amount);
		}
	});
})(jQuery);