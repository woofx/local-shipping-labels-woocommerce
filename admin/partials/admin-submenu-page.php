<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/Admin/partials
 */
?>

<?php defined( 'ABSPATH' ) or exit; ?>
<script type="text/javascript">
	jQuery( function( $ ) {
		$("#footer-thankyou").html("If you like <strong>Local Shipping Labels for Woocommerce</strong> please leave us a <a href='https://wordpress.org/support/view/plugin-reviews/local-shipping-labels-for-woocommerce?rate=5#postform'>★★★★★</a> rating.<br>A huge thank you in advance!");
	});
</script>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'Local Shipping Labels for WooCommerce', $this->plugin_name ); ?></h2>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ($settings_tabs as $tab_slug => $tab_title ) {
		$tab_link = esc_url("?page={$this->plugin_name}&tab={$tab_slug}");
		printf('<a href="%1$s" class="nav-tab nav-tab-%2$s %3$s">%4$s</a>', $tab_link, $tab_slug, (($active_tab == $tab_slug) ? 'nav-tab-active' : ''), $tab_title);
	}
	?>
	</h2>

	<?php

	switch($active_tab){
		case 'general':
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'partials/admin-tab-general.php');
			break;
		case 'support':
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'partials/admin-tab-support.php');
			break;
		case 'debug':
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'partials/admin-tab-debug.php');
			break;
	}

	?>

	
</div>