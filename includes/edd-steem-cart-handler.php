<?php
/**
 * EDD_Steem_Cart_Handler
 *
 * @package EDD Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Steem_Cart_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('template_redirect', array($instance, 'calculate_totals'), 1000);
	}

	public static function calculate_totals() {
		$cart = EDD()->cart;
		$cart_total = $cart->get_total();
		
		if ( ! edd_is_checkout()) {
			return;
		}

		$amounts = array();
		$from_currency_symbol = edd_steem_get_base_fiat_currency();

		if ($currencies = edd_steem_get_currencies()) {
			foreach ($currencies as $to_currency_symbol => $currency) {
				$amount = edd_steem_rate_convert($cart_total, $from_currency_symbol, $to_currency_symbol);

				if ($amount <= 0) {
					continue;
				}

				if (EDD_Steem::get('amount_currency') == $to_currency_symbol) {
					EDD_Steem::set('amount', $amount);
				}

				$amounts["{$to_currency_symbol}_{$from_currency_symbol}"] = $amount;
			}

			foreach ($currencies as $to_currency_symbol => $currency) {
				if ( ! isset($amounts["{$to_currency_symbol}_{$from_currency_symbol}"])) {
					$amounts["{$to_currency_symbol}_{$from_currency_symbol}"] = $cart->total;
					EDD_Steem::set('amount', $cart->total);
				}
			}

			EDD_Steem::set('amounts', $amounts);
		}
	}
}

EDD_Steem_Cart_Handler::init();