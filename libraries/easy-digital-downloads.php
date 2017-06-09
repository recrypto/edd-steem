<?php
/**
 * Easy Digital Downloads Helpers
 *
 * @package EDD Steem
 * @category Library
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** Easy Digital Downloads helper functions *****************************************************************/


if ( ! function_exists('edd_steem_get_currency_symbol')) :

/**
 * Retrieve shop's base currency symbol
 *
 * @since 1.0.0
 * @return string
 */
function edd_steem_get_currency_symbol() {
	return apply_filters('edd_currency_symbol', edd_get_currency());
}

endif;