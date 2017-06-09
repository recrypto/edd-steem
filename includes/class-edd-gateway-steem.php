<?php
/**
 * EDD_Gateway_Steem
 *
 * @package EDD Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class EDD_Gateway_Steem {

	public $gateway_id = 'steem';

	private function __construct() {

		$this->register();

		if ( ! edd_is_gateway_active($this->gateway_id)) {
			return;
		}

		$this->register_filters();
		$this->register_actions();
	}

	public static function get_instance() {
		static $instance = null;

		if ($instance === null) {
			$instance = new self();
		}

		return $instance;
	}

	private function register() {
		add_filter('edd_payment_gateways', array($this, 'register_gateway'), 10);
	}

	public function register_gateway($gateways) {
		$gateways[$this->gateway_id] = array(
			'admin_label' => 'Steem',
			'checkout_label' => 'Steem'
		);

		return $gateways;
	}


	#
	private function register_filters() {
		add_filter('edd_accepted_payment_icons', array($this, 'register_payment_icon' ), 10, 1);

		if (is_admin()) {
			add_filter('edd_settings_sections_gateways', array($this, 'register_gateway_section'), 1, 1);
			add_filter('edd_settings_gateways', array($this, 'register_gateway_settings'), 1, 1);
		}
	}

	/**
	 * Register the payment icon
	 *
	 * @since  1.0.0
	 * @param  array $payment_icons Array of payment icons
	 * @return array                The array of icons with Amazon Added
	 */
	public function register_payment_icon($payment_icons) {
		$payment_icons['steem'] = 'Steem';

		return $payment_icons;
	}

	/**
	 * Register the payment gateways setting section
	 *
	 * @since  1.0.0
	 * @param  array $gateway_sections Array of sections for the gateways tab
	 * @return array                   Added Steem into sub-sections
	 */
	public function register_gateway_section($gateway_sections) {
		$gateway_sections['steem'] = __('Steem', 'edd-steem');

		return $gateway_sections;
	}


	/**
	 * Register the gateway settings
	 *
	 * @since  1.0.0
	 * @param  $gateway_settings array
	 * @return array
	 */
	public function register_gateway_settings( $gateway_settings ) {

		$gateway_settings['steem'] = array(
			'steem' => array(
				'id'   => 'steem',
				'name' => '<strong>' . __( 'Steem Settings', 'edd-steem' ) . '</strong>',
				'type' => 'header',
			),
			'steem_payee' => array(
				'id'   => 'steem_payee',
				'name' => __( 'Payee', 'edd-steem' ),
				'desc' => __( 'This is your Steem username where your customers will pay you.', 'edd-steem' ),
				'type' => 'text',
				'size' => 'regular',
			),
			'steem_accepted_currencies' => array(
				'id'   => 'steem_accepted_currencies',
				'name' => __( 'Accepted Currencies', 'edd-steem' ),
				'desc' => __( 'Select the Steem currencies you will accept.', 'edd-steem' ),
				'type'    => 'multicheck',
				'options' => edd_steem_get_currencies(),
			),
		);

		return $gateway_settings;
	}


	#

	private function register_actions() {
		add_action('edd_steem_cc_form', array($this, 'form'));
		add_action('edd_gateway_steem', array($this, 'process_purchase'));
	}


	/**
	 * Display the wallet and address forms
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function form() {

		if ( ! edd_get_option('steem_payee')) {
			echo '<fieldset class="edd-payment-form">';

			if (is_super_admin()) {
				_e('Please set your Steem username at the Easy Digital Downloads Settings to get paid via Steem.', 'edd-steem');
			}
			else {
				_e('Sorry, Steem payments is not available right now.', 'edd-steem');
			}

			echo '</fieldset>';

			return;
		}
		elseif ( ! edd_steem_get_accepted_currencies()) {
			echo '<fieldset class="edd-payment-form">';

			if (is_super_admin()) {
				_e('Please set one or more accepted currencies at the Easy Digital Downloads Settings to get paid via Steem.', 'edd-steem');
			}
			else {
				_e('Sorry, Steem payments is not available right now.', 'edd-steem');
			}

			echo '</fieldset>';

			return;
		}

		$amount_currencies_html = '';

		if ($currencies = edd_steem_get_currencies()) {
			foreach ($currencies as $currency_symbol => $currency) {
				if (edd_steem_is_accepted_currency($currency_symbol)) {
					$amount_currencies_html .= sprintf('<option value="%s">%s</option>', $currency_symbol, $currency);
				}
			}
		}
		
		$default_fields = array(
			'amount' => '<p class="form-row form-row-wide">
				<label for="' . $this->field_id('amount') . '">' . esc_html__( 'Amount', 'edd-steem' ) . '</label>
				<span id="' . $this->field_id('amount') . '">' . EDD_Steem::get_amount() . '</span>
			</p>',
			'amount_currency' => '<p class="form-row form-row-wide">
				<label for="' . $this->field_id('amount-currency') . '">' . esc_html__( 'Currency', 'edd-steem' ) . '</label>
				<select id="' . $this->field_id('amount-currency') . '"' . $this->field_name('amount_currency') . '>' . $amount_currencies_html . '</select>
			</p>',
		);

		$fields = wp_parse_args($fields, apply_filters('edd_steem_form_fields', $default_fields, $this->gateway_id));

		remove_action( 'edd_purchase_form_after_cc_form', 'edd_checkout_tax_fields', 999 );

		ob_start(); ?>

		<fieldset id="<?php echo esc_attr($this->gateway_id); ?>-form" class='edd-steem-form edd-payment-form'>
			<?php do_action('edd_steem_form_start', $this); ?>

			<?php foreach ($fields as $field) : ?>
					<?php echo $field; ?>
			<?php endforeach; ?>

			<?php do_action('edd_steem_form_end', $this); ?>

			<div class="clear"></div>
		</fieldset>

		<?php
		$form = ob_get_clean();
		echo $form;

	}

	/**
	 * Process the purchase and create the charge in Amazon
	 *
	 * @since  1.0.0
	 * @param  $purchase_data array Cart details
	 * @return void
	 */
	public function process_purchase($purchase_data) {

		if ( ! edd_get_option('steem_payee') || ! edd_steem_get_accepted_currencies()) {
			edd_set_error('steem_setup_error', __('Sorry, something went wrong.', 'edd-steem'));
		}

		$amount_currency = isset($_POST[$this->field_id('amount_currency')]) ? $_POST[$this->field_id('amount_currency')] : 'STEEM';
		$from_currency_symbol = edd_steem_get_base_fiat_currency();

		if (edd_steem_is_accepted_currency($amount_currency)) {
			EDD_Steem::set_amount_currency($amount_currency);

			if ($amounts = EDD_Steem::get('amounts')) {
				if (isset($amounts[EDD_Steem::get_amount_currency() . '_' . $from_currency_symbol])) {
					EDD_Steem::set_amount($amounts[EDD_Steem::get_amount_currency() . '_' . $from_currency_symbol]);
				}
			}
		}

		if (empty(EDD_Steem::get_memo())) {
			EDD_Steem::set_memo();
		}

		EDD_Steem::set_payee(edd_get_option('steem_payee'));

		if ($errors = edd_get_errors()) {
			edd_send_back_to_checkout('?payment-mode=steem');
		}


		// Setup payment data to be recorded
		$payment_data = array(
			'price'         => $purchase_data['price'],
			'date'          => $purchase_data['date'],
			'user_email'    => $purchase_data['user_email'],
			'purchase_key'  => $purchase_data['purchase_key'],
			'currency'      => edd_get_currency(),
			'downloads'     => $purchase_data['downloads'],
			'user_info'     => $purchase_data['user_info'],
			'cart_details'  => $purchase_data['cart_details'],
			'gateway'       => $this->gateway_id,
			'status'        => 'pending',
		);

		$payment_id = edd_insert_payment( $payment_data );

		if ($payment_id > 0) {

			edd_update_payment_meta($payment_id, '_edd_steem_payee', EDD_Steem::get_payee());
			edd_update_payment_meta($payment_id, '_edd_steem_amount', EDD_Steem::get_amount());
			edd_update_payment_meta($payment_id, '_edd_steem_amount_currency', EDD_Steem::get_amount_currency());
			edd_update_payment_meta($payment_id, '_edd_steem_memo', EDD_Steem::get_memo());

			edd_update_payment_meta($payment_id, '_edd_steem_status', 'pending');

			EDD_Steem::reset();

			// Empty the shopping cart
			edd_empty_cart();

			// Send them to the confirmation page
			edd_send_to_success_page();
		}
	}



	# Helpers

	/**
	 * Output field name HTML
	 *
	 * Gateways which support tokenization do not require names - we don't want the data to post to the server.
	 *
	 * @since 1.0.0
	 * @param string $name
	 * @return string
	 */
	public function field_name($name) {
		return ' name="' . $this->field_id($name) . '" ';
	}

	/**
	 * Construct field identifier
	 *
	 * @since 1.0.0
	 * @param string $key
	 * @return string
	 */
	public function field_id($key) {
		return esc_attr(sprintf('edd_%s-%s', $this->gateway_id, $key));
	}
}

function edd_gateway_steem() {
	return EDD_Gateway_Steem::get_instance();
}

edd_gateway_steem();