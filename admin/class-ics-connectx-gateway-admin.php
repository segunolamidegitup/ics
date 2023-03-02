<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://softwebb.com
 * @since      1.0.0
 *
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/admin
 * @author     segun olamide <segunolamide78@gmail.com>
 */
class Ics_Connectx_Gateway_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ics_Connectx_Gateway_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ics_Connectx_Gateway_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ics-connectx-gateway-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ics_Connectx_Gateway_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ics_Connectx_Gateway_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ics-connectx-gateway-admin.js', array( 'jquery' ), $this->version, false );

	}



	public function ics_payment_init() {
		if( class_exists( 'WC_Payment_Gateway' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-wc-payment-gateway-ics.php';
			// require_once plugin_dir_path( __FILE__ ) . '/includes/payleo-order-statuses.php';
			// require_once plugin_dir_path( __FILE__ ) . '/includes/ics-checkout-description-fields.php';Migrated
		}
	}
	
	public function add_to_woo_ics_payment_gateway( $gateways ) {
		$gateways[] = 'WC_Gateway_ICS';
		return $gateways;
	}


		

	
	function techiepress_ics_description_fields_validation() {
//   echo $_POST['payment_method'];
		if( (isset($_POST['payment_method']) && $_POST['payment_method']=='ics') && (! isset( $_POST['card_number'])) && (! isset( $_POST['expiry_date'] ) ) && ! isset($_POST['cvv'] ) ) {
			wc_add_notice( 'Please enter your card details', 'error' );
		}
	}

	function techiepress_checkout_update_order_meta( $order_id ) {
		if( isset( $_POST['card_number'] ) || ! empty( $_POST['card_number'] ) ) {
		update_post_meta( $order_id, 'card_number', $_POST['card_number'] );
		}
	}

	function techiepress_order_data_after_billing_address( $order ) {
		echo '<p><strong>' . __( 'Card Number:', 'ics-connectx-gateway' ) . '</strong><br>' . get_post_meta( $order->get_id(), 'card_number', true ) . '</p>';
	}

	function techiepress_order_item_meta_end( $item_id, $item, $order ) {
		echo '<p><strong>' . __( 'Card Number:', 'ics-connectx-gateway' ) . '</strong><br>' . get_post_meta( $order->get_id(), 'card_number', true ) . '</p>';
	}









}
