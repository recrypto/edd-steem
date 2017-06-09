<?php
/**
 * EDD_Steem_Transaction_Transfer
 *
 * @package EDD Steem
 * @category Class
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Steem_Transaction_Transfer {

	/**
	 * Retrieve "Steem Transaction Transfer" via Steemful API
	 *
	 * @since 1.0.0
	 * @param WP_Post $payment
	 * @return $transfer
	 */
	public static function get($payment) {
		$transfer = null;

		if (is_int($payment)) {
			$payment = get_post($payment);
		}

		if (empty($payment) || is_wp_error($payment) || ! isset($payment->post_type) || $payment->post_type != 'edd_payment') {
			return $transfer;
		}

		$payment_id = $payment->ID;

		if (edd_get_payment_gateway($payment_id) != 'steem') {
			return $transfer;
		}

		$data = array(
			'to' => edd_payment_get_steem_payee($payment_id),
			'memo' => edd_payment_get_steem_memo($payment_id),
			'amount' => edd_payment_get_steem_amount($payment_id),
			'amount_currency' => edd_payment_get_steem_amount_currency($payment_id),
		);

		if (empty($data['to']) || empty($data['memo']) || empty($data['amount'] || empty($data['amount_currency']))) {
			return $transfer;
		}

		$response = wp_remote_get(
			add_query_arg(
				array(
					'to' => $data['to'],
					'memo' => $data['memo'],
					'amount' => $data['amount'],
					'amount_symbol' => $data['amount_currency'],
					'limit' => 1,
				),
				'http://steemful.com/api/v1/transactions/transfers'
			)
		);

		if (is_array($response)) {
			$response_body = json_decode(wp_remote_retrieve_body($response), true);

			if (isset($response_body['data'][0]) && $response_body['data'][0]) {
				$transfer = $response_body['data'][0];
			}
		}

		return $transfer;
	}
}