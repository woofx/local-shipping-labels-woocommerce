<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              woofx.kaizenflow.xyz
 * @since             1.0.0
 * @package           Local_Shipping_Labels_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Local Shipping Labels for WooCommerce
 * Plugin URI:        https://github.com/woofx/local-shipping-labels-woocommerce
 * Description:       Print shipping labels for local deliveries. Print multiple labels in a single page.
 * Version:           1.0.0
 * Author:            WooFX
 * Author URI:        woofx.kaizenflow.xyz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       local-shipping-labels-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'LOCAL_SHIPPING_LABELS_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use Woofx\LocalShippingLabelsForWooCommerce\Activator;
use Woofx\LocalShippingLabelsForWooCommerce\Plugin;


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-local-shipping-labels-for-woocommerce-activator.php
 */
function activate_local_shipping_labels_for_woocommerce() {
	#require_once plugin_dir_path( __FILE__ ) . 'includes/class-local-shipping-labels-for-woocommerce-activator.php';
	Activator::activate();
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
#require plugin_dir_path( __FILE__ ) . 'includes/class-local-shipping-labels-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_local_shipping_labels_for_woocommerce() {

	$plugin = Plugin::instance();
	$plugin->run();

}
run_local_shipping_labels_for_woocommerce();
