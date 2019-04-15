<?php

/**
 * Debug Information Tab
 *
 * Admin Submenu > Status: Contents
 *
 * @link       woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Local_Shipping_Labels_For_Woocommerce
 * @subpackage Local_Shipping_Labels_For_Woocommerce/admin/partials
 */
?>

<table class="form-table">
    <tr>
        <td colspan="2"><h3>System Requirement</h3></td>
    </tr>
    <tr>
        <td><strong>PHP version</strong></td>
        <td>
        5.4 or above
        <?php if(version_compare( PHP_VERSION, '5.4', '>=' )): ?>
            <span class="dashicons dashicons-yes"></span>
        <?php else: ?>
            <span class="dashicons dashicons-no"></span>
        <?php endif ?>
        <p class="description">Your Version: <?php echo PHP_VERSION ?></p>
        </td>
    </tr>
    <tr>
        <td><strong>Wordpress Version</strong></td>
        <td>
        4.8 or above
        <?php if(floatval(get_bloginfo( 'version' ))>=4.8): ?>
            <span class="dashicons dashicons-yes"></span>
        <?php else: ?>
            <span class="dashicons dashicons-no"></span>
        <?php endif ?>
        <p class="description">Your Version: <?php echo get_bloginfo( 'version' ) ?></p>
        </td>
    </tr>
    <tr>
        <td><strong>WooCommerce Version</strong></td>
        <td>
        3.0 or above
        <?php if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '>=' ) ) : ?>
            <span class="dashicons dashicons-yes"></span>
        <?php else: ?>
            <span class="dashicons dashicons-no"></span>
        <?php endif ?>
        <p class="description">Your Version: <?php echo (defined('WC_VERSION')) ? WC_VERSION : 'n/a' ?></p>
        </td>
    </tr>
</table>