<?php
/**
 * EDD_Steem_Checkout_Handler
 *
 * @package EDD Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Steem_Checkout_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('wp_enqueue_scripts', array($instance, 'enqueue_scripts'));
	}

	public static function enqueue_scripts() {

		// Plugin
		wp_enqueue_script('edd-steem', EDD_STEEM_DIR_URL . '/assets/js/plugin.js', array('jquery'), EDD_STEEM_VERSION);

		// Localize plugin script data
		wp_localize_script('edd-steem', 'edd_steem', array(
			'cart' => array(
				'base_currency' => edd_steem_get_base_fiat_currency(),
				'amounts' => EDD_Steem::get('amounts'),
			),
		));
	}
}

EDD_Steem_Checkout_Handler::init();