<?php
/**
 * Sidebar
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! is_shop() && ! is_product_category() ) {
	return;
}

if ( is_shop() ) {
	$foxiz_wc_sidebar_position = foxiz_get_option( 'wc_shop_sidebar_position' );
	$foxiz_wc_sidebar_name     = foxiz_get_option( 'wc_shop_sidebar_name' );
} else {
	$foxiz_wc_sidebar_position = foxiz_get_option( 'wc_archive_sidebar_position' );
	$foxiz_wc_sidebar_name     = foxiz_get_option( 'wc_archive_sidebar_name' );
}

if ( empty( $foxiz_wc_sidebar_position ) || 'none' === $foxiz_wc_sidebar_position ) {
	return;
}

if ( empty( $foxiz_wc_sidebar_name ) ) {
	$foxiz_wc_sidebar_name = 'foxiz_sidebar_default';
}

foxiz_single_sidebar( $foxiz_wc_sidebar_name );