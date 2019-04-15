<?php
namespace Woofx\LocalShippingLabelsForWooCommerce\Admin;
/**
 * The admin settings view functionality of the plugin.
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/Settings
 */

use Woofx\LocalShippingLabelsForWooCommerce\Plugin;

/**
 * The admin settings view functionality of the plugin.
 *
 * Defines hooks for managing plugin options via Settings API.
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/Settings
 * @author     WooFX <rafaat.ahmed@kaizenflow.xyz>
 */
class Settings {

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
	 * Register callback for 'Label Content' section description
	 *
	 * @since    1.0.0
	 */
	public function label_content_section_description(){echo "Manage content to display on delivery labels.";}
	
	/**
	 * Register the form element callback for 'display_fields' option.
	 *
	 * @since    1.0.0
	 */
	public function display_fields_form_element()
    {
		// prefixed option name
		$option_name = Plugin::instance()->get_option_name('display_fields');

		// get option
		$options = get_option( $option_name,[]);
		// bugfix - cached result, returning string
		if(empty($options)) $options = [];

		// prepare output

		// option - order items
		$html = '<input type="checkbox" id="items" name="'.$option_name.'[items]" value="1"' . checked( 1, @$options['items'], false ) . '/>';
		$html .= '<label for="items">Order Items</label>';
		$html .= '<br>';

		// option - billing address line 2
		$html .= '<input type="checkbox" id="billing_2" name="'.$option_name.'[billing_2]" value="1"' . checked( 1, @$options['billing_2'], false ) . '/>';
		$html .= '<label for="billing_2">Billing Address Line 2</label>';
		$html .= '<br>';

		// option - country		
		$html .= '<input type="checkbox" id="country" name="'.$option_name.'[country]" value="1"' . checked( 1, @$options['country'], false ) . '/>';
		$html .= '<label for="country">Country</label>';
		
		echo $html;
        
	}
	
	/**
	 * Register the form element callback for 'mark_as_paid' option.
	 *
	 * @since    1.0.0
	 */
	public function mark_as_paid_form_element(){
		
		// prefixed option name
		$option_name = Plugin::instance()->get_option_name('mark_as_paid');

		// get option
		$options = get_option( $option_name,[]);
		// bugfix - cached result, returning string
		if(empty($options)) $options = [];

		//bugfix check woocommerce
		if(!class_exists('WooCommerce')){
			echo "WooCommerce not installed/active.";
			return;
		} 

		// list active payment methods
		$available_payment_methods = WC()->payment_gateways->get_available_payment_gateways();

		// prepare output
		$html = "";

		if( empty($available_payment_methods) ){
			$html = "No active payment gateways.";
		}
		else{

			// loop gateways
			foreach( $available_payment_methods as $method ) {
				$html .= sprintf('<input type="checkbox" id="%s" name="%s[%s]" value="1" %s />',
					$method->method_title,
					$option_name,
					$method->method_title,
					checked( 1, @$options[$method->method_title], false )
				);

				$html .= '<label for="'.$method->method_title.'">'.$method->method_title.'</label><br>';
			}

			// description
			$html .= '<p class="description">Orders made with selected payment methods will mention <code>(Paid)</code> after amount. <code>ie. Amount: USD 100 (Paid)</code>';

		}

		echo $html;
        
    }
    
}
