<?php
namespace Woofx\LocalShippingLabelsForWooCommerce\Admin;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/Main
 */

use Woofx\LocalShippingLabelsForWooCommerce\Plugin;
use Woofx\LocalShippingLabelsForWooCommerce\Admin\Settings;
use Woofx\LocalShippingLabelsForWooCommerce\Admin\PDF_Maker;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/Main
 * @author     WooFX <rafaat.ahmed@kaizenflow.xyz>
 */
class Main {

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
	 * Register plugin settings with Settings API.
	 *
	 * @since    1.0.0
	 */
	function register_settings(){

		$plugin_settings = new Settings($this->plugin_name,$this->version);
		$main = Plugin::instance();
		
		$_section = "label_content_section";
		$_page = $this->plugin_name;

		// section - label content
        add_settings_section($_section, "Label Content Options", array($plugin_settings, "label_content_section_description"), $_page );

        // setting - display additional fields
		add_settings_field( $main->get_option_name('display_fields'), "Additional Display Fields", array($plugin_settings, "display_fields_form_element"), $_page, $_section );
		
		// setting - display paid beside amount
        add_settings_field(  $main->get_option_name("mark_as_paid"), "Mark as Paid", array($plugin_settings, "mark_as_paid_form_element"), $_page, $_section );
        
        // register
        register_setting($_section, $main->get_option_name('display_fields') );
        register_setting($_section, $main->get_option_name('mark_as_paid') );
	}

	/**
	 * Register submenu page.
	 *
	 * @since    1.0.0
	 */
	public function register_submenu_page() {
		$_page = $this->plugin_name;
		add_options_page( 'Local Shipping Labels for WooCommerce', 'Local Shipping Labels', 'manage_options', $_page, array($this,'submenu_page_display_callback'), 99 ); 
	}

	/**
	 * Submenu page display callback.
	 *
	 * @since    1.0.0
	 */
	public function submenu_page_display_callback() {
		
		$settings_tabs = array (
			'general'	=> __('General', $this->plugin_name ),
			'support'	=> __('Support', $this->plugin_name ),
			'debug'		=> __('Status', $this->plugin_name ),
		);

		
		$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'general';
		$active_section = isset( $_GET[ 'section' ] ) ? sanitize_text_field( $_GET[ 'section' ] ) : '';

		include('partials/admin-submenu-page.php');

	}

	/**
	 * Register bulk action in edit-shop_order.
	 *
	 * @since    1.0.0
	 */
	public function register_bulk_action($bulk_actions) {
		$bulk_actions['create_local_shipping_labels'] = __( 'Create Shipping Labels', 'create_local_shipping_labels');
		return $bulk_actions;
	}

	/**
	 * Register handle for bulk action 'create_local_shipping_labels' in edit-shop_order.
	 *
	 * @since    1.0.0
	 */
	function bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
		
		if ( $doaction !== 'create_local_shipping_labels' ) {
		  return $redirect_to;
		}

		// prepare pdf link
		$_nonce_key = $this->plugin_name;
		$complete_url = wp_nonce_url( admin_url( "admin-ajax.php?action={$this->plugin_name}-generate_pdf&order_ids=".implode(',',$post_ids) ), $_nonce_key );
		$redirect_to = add_query_arg( 'generated_shipping_labels', urlencode($complete_url), $redirect_to );
		
		return $redirect_to;
	}

	/**
	 * Register adming notice to display output link.
	 *
	 * @since    1.0.0
	 */
	function bulk_action_admin_notice() {

		if ( ! empty( $_REQUEST['generated_shipping_labels'] ) ) {
			$link = $_REQUEST['generated_shipping_labels'];
			printf(
				'<div id="message" class="updated"><p>Shipping label generated. <a href="%s" target="_blank">Click here</a> to preview.</p></div>',
				$link
			);
		}
	}
	
	/**
	 * Ajax hook to prepare and display shipping labels pdf.
	 *
	 * @since    1.0.0
	 */
	public function generate_shipping_label_pdf_ajax() {
		
		// Check the nonce
		$_nonce_key = $this->plugin_name;
		if( empty( $_GET['action'] ) || !check_admin_referer( $_nonce_key ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', $this->plugin_name ) );
		}

		// Check if all parameters are set
		if ( empty( $_GET['order_ids'] ) ) {
			wp_die( __( "You haven't selected any orders", $this->plugin_name ) );
		}

		// Process order ids
		$order_ids = (array) array_map( 'absint', explode( ',', urldecode($_GET['order_ids'])  ) );
		
		// Access Control
		
		// set default is allowed
		$allowed = true;

		// check if user is logged in
		if ( ! is_user_logged_in() ) {
			$allowed = false;
		}

		// Check the user privileges
		if( !( current_user_can( 'manage_woocommerce_orders' ) || current_user_can( 'edit_shop_orders' ) ) && !isset( $_GET['my-account'] ) ) {
			$allowed = false;
		}

		if ( !$allowed ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.',  $this->plugin_name  ) );
		}

		// Prepare pdf generation
		$pdf_maker = new PDF_Maker($this->plugin_name,$this->version);
		
		// Generate pdf
		$pdf_maker->generate($order_ids);

		exit;
	}	

	/**
	 * Filter hook to process label item html used in generating pdfs.
	 *
	 * @since    1.0.0
	 * @param    int	$order_id		Order ID for shipping label.
	 */
	public function label_content_html($order_id){
		
		// load WC order object
		$order = wc_get_order( $order_id );
		if(!$order) return false;

		// load options
		$main = Plugin::instance();

		// option - display fields
		$_options_display_fields = get_option( $main->get_option_name('display_fields') ,[]);
		// bugfix - cached result, returning string
		if(empty($_options_display_fields)) $_options_display_fields = [];

		// option - mark as paid
		$_options_mark_as_paid = get_option( $main->get_option_name('mark_as_paid') ,[]);
		// bugfix - cached result, returning string
		if(empty($_options_mark_as_paid)) $_options_mark_as_paid = [];
		
		// prepare address
		$address_html = $order->get_billing_address_1()."<br>";
		if(!empty($order->get_billing_address_2() && isset($_options_display_fields['billing_2']))){
			$address_html .= $order->get_billing_address_2()."<br>";
		}
		$address_html .= $order->get_billing_city()."<br>";
		if(isset($_options_display_fields['country'])){
			$address_html .= $order->get_billing_country()."<br>";
		}

		// prepare packing list (ie. order products)
		$packing_list_html = false;
		if(isset($_options_display_fields['items'])){
			$packing_list_html = "<table width='100%'>";
			
			// The loop to order items ( WC_Order_Item_Product objects )
			foreach( $order->get_items() as $item_id => $item ){
				$packing_list_html .= "<tr>";
				$packing_list_html .= "<td width='80%'>{$item->get_name()}</td>";
				$packing_list_html .= "<td style='text-align:right'>x{$item->get_quantity()}</td>";
				$packing_list_html .= "</tr>";
			
			}

			$packing_list_html .= "</table>";
		}
		

		// prepare notes
		$notes_html = false;
		if(!empty($order->get_customer_note())){
			$notes_html = "<br>Notes: {$order->get_customer_note()}"; 
		}

		// prepare amount
		$amount_html = "Amount: {$order->get_currency()} {$order->get_total()}";
		if(in_array($order->get_payment_method_title(),$_options_mark_as_paid)){
			$amount .= " (Paid)";
		}
		$amount_html .= "<br>";
		
		// prepare html output
		$content = "<div class='packing-slip-content'>";
		
		$content .= "Order# {$order->get_id()}<br>";
		
		$content .= "<strong>{$order->get_billing_first_name()} {$order->get_billing_last_name()}</strong><br>";
		$content .= $order->get_billing_phone()."<br>";
		$content .= $address_html;
		$content .= "<br>";
		
		if($packing_list_html) $content .= $packing_list_html . '<br>';
		
		$content .= $amount_html;
		
		if($notes_html) $content .= $notes_html;
		
		$content .= "</div>";

		return $content;
	}



}
