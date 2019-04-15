<?php
namespace Woofx\LocalShippingLabelsForWooCommerce\Admin;
/**
 * The pdf generation functionality of the plugin.
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/PDF_Maker
 */

// reference the Dompdf namespace
use Dompdf\Dompdf;

/**
 * The pdf generation functionality of the plugin.
 *
 * Define pdf generation related functions.
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/PDF_Maker
 * @author     WooFX <rafaat.ahmed@kaizenflow.xyz>
 */
class PDF_Maker {

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
	 * Generates PDF, and output buffer to browser with appropiate headers.
	 *
	 * @since   1.0.0
	 * @param   array    $orders       List of order ids
	 */
	function generate($orders){

		// set header
		header('Content-Type: application/pdf');
		
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		
		// load html template
		$dompdf->loadHtml($this->render_template($orders));
		
		// setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// render the HTML as PDF
		$dompdf->render();

		// output the generated PDF to Browser
		echo $dompdf->output();

	}

	/**
	 * Processes template file, returns html output.
	 *
	 * @access	private
	 * @since		1.0.0
	 * @param   array    $orders       List of order ids
	 */
	private function render_template( $orders ) {
		
		// template file path
		$template = plugin_dir_path( __DIR__ ) .'admin/templates/shipping-label.php';

		// process template file
		
		// output buffer
		ob_start();
		if (file_exists($template)) {
			include($template);
		}

		return ob_get_clean();

	}

	



}
