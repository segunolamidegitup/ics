<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ics.com
 * @since             1.0.0
 * @package           Ics_Connectx_Gateway
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce ICS Connectx Gateway  
 * Plugin URI:        https://https://portal.icsaccess.com/docs/transaction_api_integration/
 * Description:       ICS Connectx  makes it easy for businesses in America & Europe to accept secure payments from multiple local and global payment channels. With ICS for WooCommerce, you can accept payments via: Credit/Debit Cards — Visa, Mastercard
 * Version:           1.0.0
 * Author:            ConnectX
 * Author URI:        https://ics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ics-connectx-gateway
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ICS_CONNECTX_GATEWAY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ics-connectx-gateway-activator.php
 */
function activate_ics_connectx_gateway() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ics-connectx-gateway-activator.php';
	Ics_Connectx_Gateway_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ics-connectx-gateway-deactivator.php
 */
function deactivate_ics_connectx_gateway() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ics-connectx-gateway-deactivator.php';
	Ics_Connectx_Gateway_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ics_connectx_gateway' );
register_deactivation_hook( __FILE__, 'deactivate_ics_connectx_gateway' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ics-connectx-gateway.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ics_connectx_gateway() {

	$plugin = new Ics_Connectx_Gateway();
	$plugin->run();

}
run_ics_connectx_gateway();
