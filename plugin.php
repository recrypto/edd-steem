<?php
/**
 * Plugin Name: EDD Steem
 * Plugin URI: https://github.com/recrypto/edd-steem
 * Description: Accept Steem payments directly to your Easy Digital Downloads shop (Currencies: STEEM, SBD).
 * Version: 1.0.1
 * Author: ReCrypto
 * Author URI: https://steemit.com/@recrypto
 * Requires at least: 4.1
 * Tested up to: 4.7.5
 *
 * Text Domain: edd-steem
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('EDD_STEEM_VERSION', '1.0.1');
define('EDD_STEEM_DIR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('EDD_STEEM_DIR_URL', trailingslashit(plugin_dir_url(__FILE__)));

register_activation_hook(__FILE__, 'edd_steem_activate');
register_deactivation_hook(__FILE__, 'edd_steem_deactivate');

/** 
 * Plugin activation
 *
 * @since 1.0.0
 */
function edd_steem_activate() {
	do_action('edd_steem_activated');

	$settings = get_option('edd_settings', array());

	if ( ! isset($settings['steem_accepted_currencies'])) {
		$settings['steem_accepted_currencies'] = array(
			'STEEM' => 'STEEM',
			'SBD' => 'SBD',
		);
	}

	update_option('edd_settings', $settings);

	// Make sure to have fresh currency rates
	update_option('edd_steem_rates', array());
}

/**
 * Plugin deactivation
 *
 * @since 1.0.0
 */
function edd_steem_deactivate() {
	do_action('edd_steem_deactivated');

	// Make sure to have fresh currency rates
	update_option('edd_steem_rates', array());
}

/**
 * Plugin init
 * 
 * @since 1.0.0
 */
function edd_steem_init() {

	/**
	 * Fires before including the files
	 *
	 * @since 1.0.0
	 */
	do_action('edd_steem_pre_init');

	require_once(EDD_STEEM_DIR_PATH . 'libraries/wordpress.php');
	require_once(EDD_STEEM_DIR_PATH . 'libraries/easy-digital-downloads.php');

	require_once(EDD_STEEM_DIR_PATH . 'includes/edd-steem-functions.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/class-edd-steem-transaction-transfer.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/class-edd-steem.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/class-edd-gateway-steem.php');

	require_once(EDD_STEEM_DIR_PATH . 'includes/edd-steem-handler.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/edd-steem-cart-handler.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/edd-steem-checkout-handler.php');
	require_once(EDD_STEEM_DIR_PATH . 'includes/edd-steem-payment-handler.php');

	/**
	 * Fires after including the files
	 *
	 * @since 1.0.0
	 */
	do_action('edd_steem_init');
}
add_action('plugins_loaded', 'edd_steem_init');



/**
 * Register "EDD Steem" as payment gateway in Easy Digital Downloads
 *
 * @since 1.0.0
 *
 * @param array $gateways
 * @return array $gateways
 */
function edd_steem_register_gateway($gateways) {
	$gateways['steem'] = array(
		'admin_label' => 'Steem',
		'checkout_label' => 'Steem'
	);

	return $gateways;
}
add_filter('edd_payment_gateways', 'edd_steem_register_gateway');