<?php

/**
 * General Settings Tab
 *
 * Admin Submenu > General: Contents
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/admin/partials
 */
?>

<form method="post" action="options.php">
	<?php
	
	//add_settings_section callback is displayed here. For every new section we need to call settings_fields.
	settings_fields("label_content_section");

	// all the add_settings_field callbacks is displayed here
	do_settings_sections($this->plugin_name);
	
	// Add the submit button to serialize the options
	submit_button(); 
	?>
</form>