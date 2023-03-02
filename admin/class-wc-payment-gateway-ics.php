<?php

/**
 * ICS Connectx Payment Gateway.
 *
 * Provides a ICS Connectx Payment Payment Gateway.
 *
 * @class       WC_Gateway_ICS
 * @extends     WC_Payment_Gateway
 * @version     2.1.0
 * @package     WooCommerce/Classes/Payment
 */
class WC_Gateway_ICS extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		// Setup general properties.
		$this->setup_properties();

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Get settings.
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->enabled        = $this->get_option( 'enabled' );
		$this->enabled3d        = $this->get_option( 'enabled3d' );
		$this->test_mode        = $this->get_option( 'test_mode' );

		$this->icsid            = $this->get_option( 'icsid' );
		$this->userid          = $this->get_option( 'userid' );
		$this->storied          = $this->get_option( 'storied' );
		$this->auth_token          = $this->get_option( 'auth_token' );
		
		// Test
		$this->userid_test          = $this->get_option( 'userid_test' );
		$this->storied_test          = $this->get_option( 'storied_test' );
		$this->auth_token_test          = $this->get_option( 'auth_token_test' );
		$this->icsid_test            = $this->get_option( 'icsid_test' );

		// CARD LABELLING
		$this->c_number          = $this->get_option( 'c_number' );
		$this->c_cvv           = $this->get_option( 'c_cvv' );
		$this->c_expiry           = $this->get_option( 'c_expiry' );


		$this->instructions       = $this->get_option( 'instructions' );
		$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
		$this->enable_for_virtual = $this->get_option( 'enable_for_virtual', 'yes' ) === 'yes';

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );
		add_filter( 'gettext', array( $this, 'c_expiry_label' ), 100, 3 );
		add_filter( 'gettext', array( $this, 'c_cvv_label' ), 100, 3 );
		add_filter( 'gettext', array( $this, 'c_number_label' ), 100, 3 );

		// Customer Emails.
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );

		// //Payment Script
		// add_action( 'wp_footer', array( $this, 'payment_script'),9999);
	
		// Woocomerce web  hook
        add_action( 'woocommerce_api_icssuccesswebhook', array( $this, 'ics_payment_success_proccessing_api' ) );
        add_action( 'woocommerce_api_icsfailurewebhook', array( $this, 'ics_payment_failure_proccessing_api' ) );

		// Payment checkout description
		add_filter( 'woocommerce_gateway_description', [$this, 'popup_modal'], 20, 2 );


		
		 
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		$this->id                 = 'ics';
		// $this->icon               = apply_filters( 'woocommerce_ics_icon', plugins_url('img/ics.png', __FILE__ ) );
		$this->method_title       = __( 'ICS Connectx Payment', 'ics-connectx-gateway' );
		// $this->icsid            = __( 'Add ICS Key', 'ics-connectx-gateway' );
		// $this->userid          = __( 'Add User ID', 'ics-connectx-gateway' );
		// $this->storied          = __( 'Add Storied', 'ics-connectx-gateway' );
		// $this->auth_token          = __( 'Add AUTH Token', 'ics-connectx-gateway' );
		// $this->icsid_test            = __( 'Add Test ICS Key', 'ics-connectx-gateway' );
		// $this->userid_test          = __( 'Add Test User ID ', 'ics-connectx-gateway' );
		// $this->storied_test          = __( 'Add Test Storied ', 'ics-connectx-gateway' );
		// $this->auth_token_test          = __( 'Add Test AUTH Token ', 'ics-connectx-gateway' );
		$this->method_description = __( 'Have your customers pay with ICS Connectx Payment.', 'ics-connectx-gateway' );
		$this->has_fields         = true;
		// support default form with credit card
        $this->supports = array( 'default_credit_card_form' );
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$home_url=home_url();
		$this->form_fields = array(
			'enabled'            => array(
				'title'       => __( 'Enable/Disable', 'ics-connectx-gateway' ),
				'label'       => __( "Enable ICS Connectx Payment", 'ics-connectx-gateway' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'enabled3d'            => array(
				'title'       => __( '3D Merchant', 'ics-connectx-gateway' ),
				'label'       => __( "Enable 3D Merchant", 'ics-connectx-gateway' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'success_url'              => array(
				'title'       => __( 'Success URL', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Payment success URL', 'ics-connectx-gateway' ),
				'default'     => __( $home_url.'/wc-api/icssuccesswebhook', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'failure_url'              => array(
				'title'       => __( 'Failure URL', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Payment failure URL', 'ics-connectx-gateway' ),
				'default'     => __( $home_url.'/wc-api/icsfailurewebhook', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'title'              => array(
				'title'       => __( 'Title', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'ICS Connectx Payment method description that the customer will see on your checkout.', 'ics-connectx-gateway' ),
				'default'     => __( 'ICS Connectx Payment', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			
			'description'        => array(
				'title'       => __( 'Description', 'ics-connectx-gateway' ),
				'type'        => 'textarea',
				'description' => __( 'ICS Connectx Payment method description that the customer will see on your website.', 'ics-connectx-gateway' ),
				'default'     => __( 'ICS Connectx Payment Option', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'instructions'       => array(
				'title'       => __( 'Instructions', 'ics-connectx-gateway' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', 'ics-connectx-gateway' ),
				'default'     => __( 'You made a payment via ICS Connectx Payment', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'title'              => array(
				'title'       => __( 'Title', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'ICS Connectx Payment method description that the customer will see on your checkout.', 'ics-connectx-gateway' ),
				'default'     => __( 'ICS Connectx Payment', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'c_number'              => array(
				'title'       => __( 'Card Number Label', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Enter the Card Number Label that will show on checkout', 'ics-connectx-gateway' ),
				'default'     => __( 'Card number', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'c_cvv'              => array(
				'title'       => __( 'Card CVV Label', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Enter the Card CVV Label that will show on checkout', 'ics-connectx-gateway' ),
				'default'     => __( 'Card code', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'c_expiry'        => array(
				'title'       => __( 'Card Expiry Label', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Enter the Card Expiry Label that will show on checkout', 'ics-connectx-gateway' ),
				'default'     => __( 'Expiry (MM/YY)', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'test_mode'            => array(
				'title'       => __( 'Test Mode/Live Mode', 'ics-connectx-gateway' ),
				'label'       => __( "Enable Test Mode for ICS Connectx Payment", 'ics-connectx-gateway' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'yes',
			),
			'icsid'             => array(
				'title'       => __( 'Live ICS ID', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Live ICS ID', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'userid'             => array(
				'title'       => __( 'Live User ID', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Live User ID', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'storied'             => array(
				'title'       => __( 'Live Storied', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Live Storied', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'auth_token'             => array(
				'title'       => __( 'Live Auth token', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Live Auth token', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),

			'icsid_test'             => array(
				'title'       => __( 'Test ICS ID', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Test ICS ID', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'userid_test'             => array(
				'title'       => __( 'Test User ID', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Test User ID', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'storied_test'             => array(
				'title'       => __( 'Test Storied', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Test Storied', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			'auth_token_test'             => array(
				'title'       => __( ' Test Auth token', 'ics-connectx-gateway' ),
				'type'        => 'text',
				'description' => __( 'Add your Test Auth token', 'ics-connectx-gateway' ),
				'desc_tip'    => true,
			),
			 
			
			'enable_for_methods' => array(
				'title'             => __( 'Enable for shipping methods', 'ics-connectx-gateway' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select',
				'css'               => 'width: 400px;',
				'default'           => '',
				'description'       => __( 'If ICS is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'ics-connectx-gateway' ),
				'options'           => $this->load_shipping_method_options(),
				'desc_tip'          => true,
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select shipping methods', 'ics-connectx-gateway' ),
				),
			),
			'enable_for_virtual' => array(
				'title'   => __( 'Accept for virtual orders', 'ics-connectx-gateway' ),
				'label'   => __( 'Accept ICS if the order is virtual', 'ics-connectx-gateway' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),
		);
	}

	/**
	 * Check If The Gateway Is Available For Use.
	 *
	 * @return bool
	 */
	public function is_available() {
		$order          = null;
		$needs_shipping = false;

		// Test if shipping is needed first.
		if ( WC()->cart && WC()->cart->needs_shipping() ) {
			$needs_shipping = true;
		} elseif ( is_page( wc_get_page_id( 'checkout' ) ) && 0 < get_query_var( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );

			// Test if order needs shipping.
			if ( 0 < count( $order->get_items() ) ) {
				foreach ( $order->get_items() as $item ) {
					$_product = $item->get_product();
					if ( $_product && $_product->needs_shipping() ) {
						$needs_shipping = true;
						break;
					}
				}
			}
		}

		$needs_shipping = apply_filters( 'woocommerce_cart_needs_shipping', $needs_shipping );

		// Virtual order, with virtual disabled.
		if ( ! $this->enable_for_virtual && ! $needs_shipping ) {
			return false;
		}

		// Only apply if all packages are being shipped via chosen method, or order is virtual.
		if ( ! empty( $this->enable_for_methods ) && $needs_shipping ) {
			$order_shipping_items            = is_object( $order ) ? $order->get_shipping_methods() : false;
			$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

			if ( $order_shipping_items ) {
				$canonical_rate_ids = $this->get_canonical_order_shipping_item_rate_ids( $order_shipping_items );
			} else {
				$canonical_rate_ids = $this->get_canonical_package_rate_ids( $chosen_shipping_methods_session );
			}

			if ( ! count( $this->get_matching_rates( $canonical_rate_ids ) ) ) {
				return false;
			}
		}

		return parent::is_available();
	}

	/**
	 * Checks to see whether or not the admin settings are being accessed by the current request.
	 *
	 * @return bool
	 */
	private function is_accessing_settings() {
		if ( is_admin() ) {
			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['page'] ) || 'wc-settings' !== $_REQUEST['page'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['tab'] ) || 'checkout' !== $_REQUEST['tab'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['section'] ) || 'ics' !== $_REQUEST['section'] ) {
				return false;
			}
			// phpcs:enable WordPress.Security.NonceVerification

			return true;
		}

		return false;
	}

	/**
	 * Loads all of the shipping method options for the enable_for_methods field.
	 *
	 * @return array
	 */
	private function load_shipping_method_options() {
		// Since this is expensive, we only want to do it if we're actually on the settings page.
		if ( ! $this->is_accessing_settings() ) {
			return array();
		}

		$data_store = WC_Data_Store::load( 'shipping-zone' );
		$raw_zones  = $data_store->get_zones();

		foreach ( $raw_zones as $raw_zone ) {
			$zones[] = new WC_Shipping_Zone( $raw_zone );
		}

		$zones[] = new WC_Shipping_Zone( 0 );

		$options = array();
		foreach ( WC()->shipping()->load_shipping_methods() as $method ) {

			$options[ $method->get_method_title() ] = array();

			// Translators: %1$s shipping method name.
			$options[ $method->get_method_title() ][ $method->id ] = sprintf( __( 'Any &quot;%1$s&quot; method', 'ics-connectx-gateway' ), $method->get_method_title() );

			foreach ( $zones as $zone ) {

				$shipping_method_instances = $zone->get_shipping_methods();

				foreach ( $shipping_method_instances as $shipping_method_instance_id => $shipping_method_instance ) {

					if ( $shipping_method_instance->id !== $method->id ) {
						continue;
					}

					$option_id = $shipping_method_instance->get_rate_id();

					// Translators: %1$s shipping method title, %2$s shipping method id.
					$option_instance_title = sprintf( __( '%1$s (#%2$s)', 'ics-connectx-gateway' ), $shipping_method_instance->get_title(), $shipping_method_instance_id );

					// Translators: %1$s zone name, %2$s shipping method instance name.
					$option_title = sprintf( __( '%1$s &ndash; %2$s', 'ics-connectx-gateway' ), $zone->get_id() ? $zone->get_zone_name() : __( 'Other locations', 'ics-connectx-gateway' ), $option_instance_title );

					$options[ $method->get_method_title() ][ $option_id ] = $option_title;
				}
			}
		}

		return $options;
	}

	/**
	 * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
	 *
	 * @since  3.4.0
	 *
	 * @param  array $order_shipping_items  Array of WC_Order_Item_Shipping objects.
	 * @return array $canonical_rate_ids    Rate IDs in a canonical format.
	 */
	private function get_canonical_order_shipping_item_rate_ids( $order_shipping_items ) {

		$canonical_rate_ids = array();

		foreach ( $order_shipping_items as $order_shipping_item ) {
			$canonical_rate_ids[] = $order_shipping_item->get_method_id() . ':' . $order_shipping_item->get_instance_id();
		}

		return $canonical_rate_ids;
	}

	/**
	 * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
	 *
	 * @since  3.4.0
	 *
	 * @param  array $chosen_package_rate_ids Rate IDs as generated by shipping methods. Can be anything if a shipping method doesn't honor WC conventions.
	 * @return array $canonical_rate_ids  Rate IDs in a canonical format.
	 */
	private function get_canonical_package_rate_ids( $chosen_package_rate_ids ) {

		$shipping_packages  = WC()->shipping()->get_packages();
		$canonical_rate_ids = array();

		if ( ! empty( $chosen_package_rate_ids ) && is_array( $chosen_package_rate_ids ) ) {
			foreach ( $chosen_package_rate_ids as $package_key => $chosen_package_rate_id ) {
				if ( ! empty( $shipping_packages[ $package_key ]['rates'][ $chosen_package_rate_id ] ) ) {
					$chosen_rate          = $shipping_packages[ $package_key ]['rates'][ $chosen_package_rate_id ];
					$canonical_rate_ids[] = $chosen_rate->get_method_id() . ':' . $chosen_rate->get_instance_id();
				}
			}
		}

		return $canonical_rate_ids;
	}

	/**
	 * Indicates whether a rate exists in an array of canonically-formatted rate IDs that activates this gateway.
	 *
	 * @since  3.4.0
	 *
	 * @param array $rate_ids Rate ids to check.
	 * @return boolean
	 */
	private function get_matching_rates( $rate_ids ) {
		// First, match entries in 'method_id:instance_id' format. Then, match entries in 'method_id' format by stripping off the instance ID from the candidates.
		return array_unique( array_merge( array_intersect( $this->enable_for_methods, $rate_ids ), array_intersect( $this->enable_for_methods, array_unique( array_map( 'wc_get_string_before_colon', $rate_ids ) ) ) ) );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		// $order->update_status( apply_filters( 'woocommerce_ics_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order ), __( 'Payments pending.', 'ics-connectx-gateway' ) );
  		// 	// Return thankyou redirect.

		// 	// and this is our custom JS in your plugin directory that works with token.js
		// 	wp_register_script( 'ics_popup_pay_js', plugin_dir_url( __FILE__ ) . 'js/popup.js', array( 'jquery' ) );
				
		// 	//in most payment processors you have to use PUBLIC KEY to obtain a token
		// 	wp_localize_script( 'ics_popup_pay_js', 'ics_popup_pay', array(
		// 		'order_id' =>$order
		// 	));

		// 	wp_enqueue_script( 'ics_popup_pay_js' );


		// return array(
		// 	'result'   => 'success',
		// 	'redirect' => $this->get_return_url( $order ),
		// ); 

		if($this->enabled3d=='no') {//Normal Transaction
			if ( $this->ics_payment_complete( $order )){
 			//$this->ics_payment_complete( $order );

                $order->payment_complete();
			
                // Return thankyou redirect.
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				); 
			}
		}

		if ( ($this->enabled3d=='yes')) {//Normal Transaction
			//$this->ics_payment_complete( $order );

			$order->update_status( apply_filters( 'woocommerce_ics_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order ), __( 'Payments pending.', 'ics-connectx-gateway' ) );

		   
			   // Return thankyou redirect.
			   return array(
				   'result'   => 'success',
				   'redirect' => $this->get_return_url( $order ),
			   ); 
	   }
	}
	
    	  // Validate fields
      public function validate_fields() {
        return true;
      }

	public function ics_payment_complete($order) {

		 
 
		$total = intval( $order->get_total() );
		//var_dump($total);

		// POST Data
		
		// Credit Card Information

		$card_number = str_replace( array(' ', '-' ), '', $_POST['ics-card-number'] );
		$cvv =  ( isset( $_POST['ics-card-cvv'] ) ) ? $_POST['ics-card-cvv'] : ''; // 
		$expiry_date = str_replace( array( '/', ' '), '', $_POST['ics-card-expiry'] ); // 
		
		
		
		// Billing Data
		$amount=$total;
		$firstname=$order->get_billing_first_name();
		$lastname=$order->get_billing_last_name();
		$country=$order->get_billing_country();
		$order_currency   = method_exists( $order, 'get_currency' ) ? $order->get_currency() : $order->get_order_currency();

		$currency=$order_currency;//$order->get_billing_currency();//check
		$address=$order->get_billing_address_1();
		$zip=$order->get_billing_postcode();


		// Checking if test mode else

		if($this->test_mode=="yes"){//===Test mode active
			$icsid=$this->icsid_test;
			$userid=$this->userid_test;
			$storied=$this->storied_test;
		}else{
			$icsid=$this->icsid;
			$userid=$this->userid;
			$storied=$this->storied;
		}

		

		// API CONNECTIONS
		$url = 'https://secure.icsaccess.com/api';
		// $header='Content-Type: application/json';
		$data=array(
			'icsid' => $icsid,
			'userid' => $userid,
			'storeid' => $storied,
			'amount' =>$amount,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'transtype' => 'NA',
			'country' => $country,
			'currency' => 'USD',$currency,
			'address' => $address,
			'zip' => $zip,
			'account' => $card_number,
			'expdate' => $expiry_date,
			'cvvcode' => $cvv
		  );

		//var_dump($data);

		$response = wp_remote_post( $url, 
			array( 
				'timeout' => 90 ,
				'method' => 'POST',
				'body' => wp_json_encode($data),
				'headers'     => array('Content-Type'=>'application/json')
				) 
		);

	
 

        if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			return "Something went wrong: $error_message";
		}
		if (  $response['body'] ==='Authentication failed') {
			wc_add_notice('<b>Authentication failed</b> -- Confirm your Merchant Key are correct in the ICS payment setting', 'error');

		 }

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$order->update_status( apply_filters( 'woocommerce_ics_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order ), __( 'Payments pending.', 'ics-connectx-gateway' ) );
		}

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		
			$error_prompt='<b>'.$response_body['resulttext'].'</b> -- '.$response_body['resultstatus'];
			 wc_add_notice($error_prompt, 'error');
			  
			wp_remote_retrieve_response_code( $response );

			


			if ( 'Approved' === $response_body['resulttext'] ) {
				//$order->payment_complete();
				//add_option('woocommerce_pay_page_id', $page_id);

				// Remove cart.
				//WC()->cart->empty_cart();

				// Return thankyou redirect.
				// return array(
				// 	'result'   => 'success',
				// 	'redirect' => $this->get_return_url( $order ),
				// );
				 return true;
			}
		}
  
	}



	public function ics_payment_success_proccessing_api(){
		// Receive Tracking ID and use it to query Additional detail (Order ID ...)
		// 

    if(isset($_GET['order_number']) && ! empty($_GET['order_number'])){
		 // $_GET['order_number'];
		$order_id= $_GET['order_number'];

	  //Retrieve order by Order ID
	  if ($order=wc_get_order($order_id)) {
		$order->update_status( apply_filters( 'woocommerce_ics_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'wc-completed', $order ), __( 'Payments pending.', 'ics-connectx-gateway' ) );

		// Return thankyou redirect.
		echo  "Your payment was successful. You will be redirected in 5 seconds..." ;
		header("refresh:5;url=/");

	  }else{
		echo  "It seems your order ID is not correct." ;

	  }

	   

	  
	}else{
	echo  json_encode(array('status'=>'Error','message'=>"Order ID is required"));

	}





     exit;
	}

	public function ics_payment_failure_proccessing_api(){
	echo json_encode(array('status'=>'Error','message'=>"Your Payment is not succesful"));

	 
		exit;
	}



	
	/**
	 * Output for the order received page.
	 */
	public function thankyou_page($order_id) {
		 
	
	 

		if ( $this->instructions ) {

			// Check if 3D is enabled. If true create submission form else eco default

			if($this->enabled3d){
				// echo "SUBMISSION FORM";
	 			$order=wc_get_order($order_id);//Retrieve order by Order ID
				$total = intval( $order->get_total() );
				$amount=$total;
				$firstname=$order->get_billing_first_name();
				$lastname=$order->get_billing_last_name();
				$country=$order->get_billing_country();
				$currency   = 'USD';//method_exists( $order, 'get_currency' ) ? $order->get_currency() : $order->get_order_currency();
		

				if($this->test_mode=="yes"){//===Test mode active
					$icsid=$this->icsid_test;
					$userid=$this->userid_test;
					$storied=$this->storied_test;
					$auth_token=$this->auth_token_test;
				}else{
					$icsid=$this->icsid;
					$userid=$this->userid;
					$storied=$this->storied;
					$auth_token=$this->auth_token;

				}
				// FORM DATA
				$order_number=$order_id;
				$digest=$icsid.''.$userid.''.$storied.''.$auth_token.''.$order_number;
				$digest=hash('sha256', $digest);
			

				echo //https://secure.icsaccess.com/api or https://secure.icsaccess.com/payment_form
				"<form action='https://secure.icsaccess.com/payment_form' method='POST' enctype='application/json' id='initial_payment_form'>

				<input type='hidden' name='auth_token' value='$auth_token' />
				<input type='hidden' name='transtype' value='FR' />
				<input type='hidden' name='order_number' value='$order_number' />
				<input type='hidden' name='digest' value='$digest' />
				<input type='hidden' name='currency' value='$currency' />
				<input type='hidden' name='amount' value='$amount' />
				<input type='hidden' name='firstname' value='$firstname' />
				<input type='hidden' name='lastname' value='$lastname' />

				<input type='submit' name='' value='CONTINUE TO MAKE PAYMENT'> 
				</form><br>
				";
			}else{
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
			}
		}





	}

	/**
	 * Change payment complete order status to completed for ICS orders.
	 *
	 * @since  3.1.0
	 * @param  string         $status Current order status.
	 * @param  int            $order_id Order ID.
	 * @param  WC_Order|false $order Order object.
	 * @return string
	 */
	public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'ics' === $order->get_payment_method() ) {
			$status = 'completed';
		}
		return $status;
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin  Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( $this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method() ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
		}
	}


	function c_expiry_label( $translated_text, $text, $domain ) {
		$ex=$this->c_expiry;
		$translated_text = str_replace('Expiry (MM/YY)', $ex, $translated_text);
		return $translated_text; 
	}
	function c_cvv_label( $translated_text, $text, $domain ) {
		$ex=$this->c_cvv;
		$translated_text = str_replace('Card code', $ex, $translated_text);
		return $translated_text; 
	}
	function c_number_label( $translated_text, $text, $domain ) {
		$ex=$this->c_number;
		$translated_text = str_replace('Card number', $ex, $translated_text);
		return $translated_text; 
	}




	function popup_modal( $description, $payment_id ) {

		if ( 'ics' !== $payment_id ) {
			return $description;
		}
		
		ob_start();

		
		
		if($this->enabled3d=='yes'){
			echo $paybtn='<br><button type="button" id="threedpayment" >CONTINUE WITH 3D PAYMENT</button> </form>';//3d Payment

		}else{
			echo $paybtn='<br><button type="button" id="modal-toggle" class="modal-toggle" data-target="openModal1" >PROCEED TO PAYMENT</button>';//Normal transaction
		
		}

		$cn= __( 'Card number', 'ics-connectx-gateway');
		$cx= __( 'Expiry (MM/YY)', 'ics-connectx-gateway');
		$cc= __( 'Card code', 'ics-connectx-gateway');

		echo $modal='  
		<form action="#" method="POST" id="popup-ics">
		<div id="openModal1" class="modal-wrapper">
			<div class="modal">
			<a href="#close" title="Close" class="close">X</a>
			<div class="modal-header">
				<h4>Pay with Card - ICS ConnectX</h4>
			</div>
			<div class="modal-content">
		 
			 

			<fieldset id="wc-ics-cc-form" class="wc-credit-card-form wc-payment-form">
						<p class="form-row form-row-wide woocommerce-validated">
				<label for="ics-card-number">'.$cn.'<span class="required">*</span></label><input id="ics-card-number" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="•••• •••• •••• ••••" name="ics-card-number">
				</p><p class="form-row form-row-first woocommerce-validated">
				<label for="ics-card-expiry">'.$cx.'<span class="required">*</span></label><input id="ics-card-expiry" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="MM / YY" name="ics-card-expiry">
				</p><p class="form-row form-row-last woocommerce-validated">
				<label for="ics-card-cvc">'.$cc.'<span class="required">*</span></label><input id="ics-card-cvc" class="input-text wc-credit-card-form-card-cvc" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="CVC" name="ics-card-cvc" style="width:100px">
				</p> <div class="clear"></div>
				<input id="ics-submit-btn" class=""  value="MAKE PAYMENT" type="submit" name="ics-submit-btn" style="width:100px">

			</fieldset>

		
			</div>
			</div>
		 </div>
		 </form>';

		 echo '<script>
					jQuery(document).ready(function($){
						//Hide Deafault Payment forms frameset
						jQuery("fieldset#wc-ics-cc-form").eq(1).hide(); 
						jQuery("#popup-ics").on("submit",function(e){
								  e.preventDefault();
								  var formData = {
									  number: jQuery("#ics-card-number").val(),
									  expiry: jQuery("#ics-card-expiry").val(),
									  cvv: jQuery("#ics-card-cvc").val(),
								  };
								  //instantiate Checkform
								  var checkout_form = $( "form.woocommerce-checkout" );
								  jQuery("input#ics-card-number").eq(1).val(jQuery("input#ics-card-number").eq(0).val());
								  jQuery("input#ics-card-expiry").eq(1).val(jQuery("input#ics-card-expiry").eq(0).val());
								  jQuery("input#ics-card-cvc").eq(1).val(jQuery("input#ics-card-cvc").eq(0).val());
								  //Hide Popup
								  jQuery("#openModal1").css({"opacity":0,"z-index":-1});
								  // submit the form now
								  checkout_form.submit();
								  //console.log("Form Data",formData);
								});
								//Close and open modal
								var cb = document.querySelectorAll(".close");
								for (var i = 0; i < cb.length; i++) {
									cb[i].addEventListener("click", function() {
										var dia = this.parentNode.parentNode; /* You need to update this part if you change level of close button */
										dia.style.opacity = 0;
										dia.style.zIndex = -1;
									});
								 }
							  jQuery("#modal-toggle").click(function() {
								  //console.log("Pop");
								  jQuery("#openModal1").css({"opacity":1,"z-index":9999});
							  
							  });
							  //Hide place order button
							  jQuery(".input-radio").change(function(){
								if(jQuery(this).attr("id")==="payment_method_ics") {
								jQuery("button#place_order").hide();
								}else{
								jQuery("button#place_order").show();
								}
							  });
							 jQuery("#threedpayment").click(function(e){
								var checkout_form = jQuery("form.woocommerce-checkout");
								  // submit the form now
								  checkout_form.submit();
							  });
					});
							  </script>';
	 

		$description .= ob_get_clean();

		return $description;
	}


	


}