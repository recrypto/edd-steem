<?php
/**
 * EDD Steem Helpers
 *
 * @package EDD Steem
 * @category Helper
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Retrieve Steem currencies
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_get_currencies() {
	return apply_filters('edd_steem_currencies', array(
		'STEEM' => 'Steem',
		'SBD' => 'Steem Backed Dollar',
	));
}

/**
 * Retrieve payment method settings
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_get_settings() {
	return get_option('edd_settings', array());
}

/**
 * Retrieve single payment method settings
 *
 * @since 1.0.0
 * @return mixed
 */
function edd_steem_get_setting($key) {
	$settings = edd_steem_get_settings();

	return isset($settings["steem_{$key}"]) ? $settings["steem_{$key}"] : null;
}

/**
 * Retrieve Steem accepted currencies
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_get_accepted_currencies() {
	$accepted_currencies = edd_steem_get_setting('accepted_currencies');

	if ($accepted_currencies) {
		foreach ($accepted_currencies as $accepted_currency_symbol => $accepted_currency) {
			$accepted_currencies[$accepted_currency_symbol] = $accepted_currency_symbol;
		}
	}

	return apply_filters('edd_steem_accepted_currencies', $accepted_currencies ? $accepted_currencies : array());
}

/**
 * Check if the Steem payment method settings has accepted currencies
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_has_accepted_currencies() {
	return ( ! empty(edd_steem_get_accepted_currencies()));
}

/**
 * Check currency is accepted on Steem payment method
 *
 * @since 1.0.0
 * @param string $currency_symbol
 * @return boolean
 */
function edd_steem_is_accepted_currency($currency_symbol) {
	$currencies = edd_steem_get_accepted_currencies();
	return in_array($currency_symbol, $currencies);
}


# Fiat

/**
 * Retrieve shop's base fiat currency symbol
 *
 * @since 1.0.0
 * @return string $fiat_currency
 */
function edd_steem_get_base_fiat_currency() {
	$fiat_currency = edd_steem_get_currency_symbol();

	if ( ! in_array($fiat_currency, edd_steem_get_accepted_fiat_currencies())) {
		// $fiat_currency = apply_filters('edd_steem_base_default_fiat_currency', 'USD');
	}

	return apply_filters('edd_steem_base_fiat_currency', $fiat_currency);
}

/**
 * Retrieve list of accept fiat currencies
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_get_accepted_fiat_currencies() {
	return apply_filters('edd_steem_accepted_fiat_currencies', array(
		'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'GBP', 'HKD', 'HRK', 'HUF', 'IDR', 'ILS', 'INR', 'JPY', 'KRW', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'ZAR', 'EUR',
	));
}

/**
 * Check fiat currency is accepted on Easy Digital Downloads shop
 *
 * @since 1.0.0
 * @param string $currency_symbol
 * @return boolean
 */
function edd_steem_is_accepted_fiat_currency($currency_symbol) {
	$currencies = edd_steem_get_accepted_fiat_currencies();
	return in_array($currency_symbol, $currencies);
}


# Rates

/**
 * Retrieve Steem rates
 *
 * @since 1.0.0
 * @return array
 */
function edd_steem_get_rates() {
	return get_option('edd_steem_rates', array());
}

/**
 * Retrieve rate
 *
 * @since 1.0.0
 * @param string $from_currency_symbol
 * @param string $to_currency_symbol
 * @return float
 */
function edd_steem_get_rate($from_currency_symbol, $to_currency_symbol) {
	$rates = edd_steem_get_rates();

	$from_currency_symbol = strtoupper($from_currency_symbol);
	$to_currency_symbol = strtoupper($to_currency_symbol);

	$pair_currency_symbol = "{$to_currency_symbol}_{$from_currency_symbol}";

	return apply_filters(
		'edd_steem_rate', 
		(isset($rates[$pair_currency_symbol]) ? $rates[$pair_currency_symbol] : null), 
		$from_currency_symbol, 
		$to_currency_symbol
	);
}

/**
 * Convert the amount in USD to crypto amount
 *
 * @since 1.0.0
 * @param float $amount
 * @param string $from_currency_symbol
 * @param string $to_currency_symbol
 * @return float
 */
function edd_steem_rate_convert($amount, $from_currency_symbol, $to_currency_symbol) {
	$rate = edd_steem_get_rate($from_currency_symbol, $to_currency_symbol);

	return apply_filters(
		'edd_steem_rate_convert', 
		($rate > 0 ? round($amount / $rate, 3, PHP_ROUND_HALF_UP) : 0), 
		$amount, 
		$from_currency_symbol, 
		$to_currency_symbol
	);
}


# Order functions

/**
 * Retrieve payment's Steem payee username
 *
 * @since 1.0.0
 * @param int $payment_id
 * @return string
 */
function edd_payment_get_steem_payee($payment_id) {
	return apply_filters('edd_payment_steem_payee', get_post_meta($payment_id, '_edd_steem_payee', true), $payment_id);
}

/**
 * Retrieve payment's Steem memo
 *
 * @since 1.0.0
 * @param int $payment_id
 * @return string
 */
function edd_payment_get_steem_memo($payment_id) {
	return apply_filters('edd_payment_steem_memo', get_post_meta($payment_id, '_edd_steem_memo', true), $payment_id);
}

/**
 * Retrieve payment's Steem amount
 *
 * @since 1.0.0
 * @param int $payment_id
 * @return string
 */
function edd_payment_get_steem_amount($payment_id) {
	return apply_filters('edd_payment_steem_amount', get_post_meta($payment_id, '_edd_steem_amount', true), $payment_id);
}

/**
 * Retrieve payment's Steem amount currency
 *
 * @since 1.0.0
 * @param int $payment_id
 * @return string
 */
function edd_payment_get_steem_amount_currency($payment_id) {
	return apply_filters('edd_payment_steem_amount_currency', get_post_meta($payment_id, '_edd_steem_amount_currency', true), $payment_id);
}

/**
 * Retrieve payment's Steem status
 *
 * @since 1.0.0
 * @param int $payment_id
 * @return string
 */
function edd_payment_get_steem_status($payment_id) {
	return apply_filters('edd_payment_steem_status', get_post_meta($payment_id, '_edd_steem_status', true), $payment_id);
}