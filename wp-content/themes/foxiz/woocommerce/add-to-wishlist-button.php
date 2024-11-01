<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product; ?>
<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) ) ?>" rel="nofollow" data-product-id="<?php echo strip_tags( $product_id ) ?>" data-product-type="<?php echo strip_tags( $product_type ); ?>" class="<?php echo strip_tags( $link_classes ); ?>">
	<?php if ( isset( $icon ) ) {
		foxiz_render_inline_html( $icon );
	}
	if ( isset( $label ) ) {
		foxiz_render_inline_html( $label );
	}
	?>
</a>
<span class="ajax-loading" style="visibility:hidden"></span>

