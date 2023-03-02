<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://softwebb.com
 * @since      1.0.0
 *
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/public
 * @author     segun olamide <segunolamide78@gmail.com>
 */
class Ics_Connectx_Gateway_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ics-connectx-gateway-public.css', array(), $this->version, 'all' );

		wp_enqueue_style('popupcss', plugin_dir_url( __FILE__ ) . 'css/popup.css', array(), $this->version, 'all' );


		// wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ics-connectx-gateway-public.js', array( 'jquery' ), $this->version, true );
		// in most payment processors you have to use PUBLIC KEY to obtain a token
		 
	
		//in most payment processors you have to use PUBLIC KEY to obtain a token
		// wp_localize_script( 'ics_popup_pay_js', 'ics_popup_pay');
	
		// wp_enqueue_script( 'ics_popup_pay_js' );

		// //Boostrap
		// wp_enqueue_script( 'boostrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js',array('jquery'), $this->version, true );
	
		wp_enqueue_script( 'popupjs', plugin_dir_url( __FILE__ ) . 'js/popup.js',array('jquery'), $this->version, true );

		
	}


	


}
