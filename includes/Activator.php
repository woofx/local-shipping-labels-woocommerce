<?php
namespace Woofx\LocalShippingLabelsForWooCommerce;
/**
 * Fired during plugin activation
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/includes
 * @author     WooFX <rafaat.ahmed@kaizenflow.xyz>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, '5.4', '<' ) )
		{
			wp_die( sprintf( 'Local Shipping Labels For Woocommerce requires PHP 5.4 or higher. You’re still on %s.', PHP_VERSION ) );
		}

		// Check for required WP version
		if ( version_compare( get_bloginfo('version'), '4.8', '<' ) )
		{
			wp_die( sprintf( 'Local Shipping Labels For Woocommerce requires Wordpress 4.8 or higher. You’re still on %s.', get_bloginfo('version') ) );
		}

	}

}
