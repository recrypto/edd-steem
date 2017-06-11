<?php
/**
 * EDD_Steem_Payment_Handler
 *
 * @package EDD Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Steem_Payment_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('edd_payment_receipt_after_table', array($instance, 'payment_details'));
	}

	public static function payment_details($payment) {

		if (edd_get_payment_gateway($payment->ID) != 'steem') {
			return;
		}

		$payment_id = $payment->ID;

		?>

		<section class="edd-steem-payment-receipt-payment-details">

			<h2 class="edd-steem-payment-receipt-payment-details__title"><?php _e( 'Steem Payment details', 'edd-steem' ); ?></h2>

			<table id="edd_purchase_receipt_steem_payment_details" class="edd-table">
				<tbody>
					<tr>
						<th><?php _e('Payee', 'edd-steem'); ?></th>
						<td><?php echo edd_payment_get_steem_payee($payment_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Memo', 'edd-steem'); ?></th>
						<td><?php echo edd_payment_get_steem_memo($payment_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Amount', 'edd-steem'); ?></th>
						<td><?php echo edd_payment_get_steem_amount($payment_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Currency', 'edd-steem'); ?></th>
						<td><?php echo edd_payment_get_steem_amount_currency($payment_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Status', 'edd-steem'); ?></th>
						<td><?php echo edd_payment_get_steem_status($payment_id); ?></td>
					</tr>
				</tbody>
			</table>

			<p>
				<?php printf(
					__("Please don't forge to include the %s when making a transfer for this transaction to Steem.", 'edd-steem'),
					sprintf('<strong>"%s"</strong>', __('MEMO', 'edd-steem'))
				); ?>
			</p>

			<?php do_action( 'edd_steem_purchase_receipt_payment_details_after_table', $payment); ?>

		</section>

		<?php if ($transfer = get_post_meta($payment_id, '_edd_steem_transaction_transfer', true)) : ?>
		<section class="edd-steem-payment-receipt-transaction-details">

			<h2 class="edd-steem-payment-receipt-transaction-details__title"><?php _e( 'Steem Transfer details', 'edd-steem' ); ?></h2>

			<table id="edd_purchase_receipt_steem_transaction_details" class="edd-table">
				<tbody>
					<tr>
						<th><?php _e('Steem Transaction ID', 'edd-steem'); ?></th>
						<td><?php echo $transfer['tx_id']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Steem Transfer ID', 'edd-steem'); ?></th>
						<td><?php echo $transfer['ID']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Payor', 'edd-steem'); ?></th>
						<td><?php echo $transfer['from']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Paid on', 'edd-steem'); ?></th>
						<td><?php printf('%s on %s', date('F j, Y', $transfer['timestamp']), date('g:i A', $transfer['timestamp'])); ?></td>
					</tr>
				</tbody>
			</table>

			<?php do_action( 'edd_steem_purchase_receipt_transaction_details_after_table', $payment ); ?>

		</section>
		<?php endif; ?>

		<?php
	}
}

EDD_Steem_Payment_Handler::init();
