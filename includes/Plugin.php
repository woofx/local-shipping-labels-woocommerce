<?php
namespace Woofx\LocalShippingLabelsForWooCommerce;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/includes
 */

use Woofx\LocalShippingLabelsForWooCommerce\Loader;
use Woofx\LocalShippingLabelsForWooCommerce\i18n;
use Woofx\LocalShippingLabelsForWooCommerce\Admin;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/includes
 * @author     WooFX <rafaat.ahmed@kaizenflow.xyz>
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woofx\LocalShippingLabelsForWooCommerce\Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Reference to the activated plugin instance.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woofx\LocalShippingLabelsForWooCommerce\Plugin    $_instance    Reference to the activated plugin instance.
	 */
	protected static $_instance = null;

	/**
	 * Main Plugin Instance
	 *
	 * Ensures only one instance of plugin is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOCAL_SHIPPING_LABELS_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = LOCAL_SHIPPING_LABELS_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name  = 'local-shipping-labels-for-woocommerce';
		
		$this->loader = new Loader();		

		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Local_Shipping_Labels_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Main( $this->get_plugin_name(), $this->get_version() );

		// register plugin options
		$this->loader->add_action("admin_init", $plugin_admin,"register_settings");

		// register submenu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_submenu_page',99 );

		// register bulk action and handle (ie. create shipping label) for woocommerce orders
		$this->loader->add_filter( 'bulk_actions-edit-shop_order', $plugin_admin,'register_bulk_action',99 );
		$this->loader->add_filter( 'handle_bulk_actions-edit-shop_order', $plugin_admin,'bulk_action_handler', 10, 3 );
		$this->loader->add_action( 'admin_notices', $plugin_admin,'bulk_action_admin_notice' );

		// regsiter ajax response for generated pdf
		$this->loader->add_action( 'wp_ajax_'.$this->plugin_name.'-generate_pdf', $plugin_admin, 'generate_shipping_label_pdf_ajax' );

		// content filters during pdf generation
		$this->loader->add_filter( $this->plugin_name.'-label_content_html', $plugin_admin, 'label_content_html' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woofx\LocalShippingLabelsForWooCommerce\Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * The name of the option key prefixed with plugin name, used to uniquely 
	 * identify it within the context of WordPress.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_option_name($key) {
		return $this->plugin_name . '-' . $key;
	}

}
