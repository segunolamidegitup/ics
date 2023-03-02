<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://softwebb.com
 * @since      1.0.0
 *
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ics_Connectx_Gateway
 * @subpackage Ics_Connectx_Gateway/includes
 * @author     segun olamide <segunolamide78@gmail.com>
 */
class Ics_Connectx_Gateway_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ics-connectx-gateway',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
