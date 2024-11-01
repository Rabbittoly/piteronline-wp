<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

/** support wc */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
add_action( 'woocommerce_before_shop_loop', 'foxiz_wc_before_shop_loop', 5 );
add_action( 'woocommerce_no_products_found', 'foxiz_wc_before_shop_loop', 5 );
add_action( 'woocommerce_after_main_content', 'foxiz_wc_after_main_content', 10 );
add_action( 'woocommerce_after_main_content', 'woocommerce_get_sidebar', 5 );
add_action( 'woocommerce_after_shop_loop', 'foxiz_wc_after_shop_loop', 99 );
add_action( 'woocommerce_no_products_found', 'foxiz_wc_after_shop_loop', 99 );
add_action( 'woocommerce_grouped_product_list_before_quantity', 'foxiz_wc_group_thumbnail', 10 );
add_action( 'wp_footer', 'foxiz_add_cart_popup', 15 );

/** changes columns */
add_filter( 'loop_shop_columns', 'foxiz_wc_shop_columns' );

/** remove zipcode request */
add_filter( 'woocommerce_default_address_fields', 'foxiz_optional_postcode_checkout' );

/** posts per page */
add_filter( 'woocommerce_output_related_products_args', 'foxiz_wc_related_posts_per_page' );

/** sale percent */
add_filter( 'woocommerce_sale_flash', 'foxiz_wc_sale_percent', 10, 3 );

/** single related columns */
add_filter( 'woocommerce_cross_sells_columns', 'foxiz_wc_cross_sells_columns' );

/** review box */
add_filter( 'woocommerce_product_tabs', 'foxiz_wc_review_box' );

/** remove single breadcrumb */
add_action( 'woocommerce_before_main_content', 'foxiz_remove_single_breadcrumb', 1 );

/** change single rating position */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12 );

/** remove additional information heading */
add_filter( 'woocommerce_product_additional_information_heading', 'foxiz_additional_information_heading' );

/** change add cart button position */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 100 );

/** cross sell position */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 25 );

/** add category */
add_filter( 'woocommerce_shop_loop_item_title', 'foxiz_wc_product_category', 1 );

/** change rating position */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/** css */
add_filter( 'woocommerce_enqueue_styles', 'foxiz_wc_enqueue_styles' );

/** checkout layout */
add_action( 'woocommerce_checkout_before_customer_details', 'foxiz_checkout_customer_details_before' );
add_action( 'woocommerce_checkout_after_customer_details', 'foxiz_checkout_customer_details_after' );
add_action( 'woocommerce_checkout_after_order_review', 'foxiz_checkout_order_after', 20 );

/** mini cart */
add_filter( 'woocommerce_add_to_cart_fragments', 'foxiz_wc_add_to_cart_fragments', 10 );

/** single  */
add_action( 'woocommerce_single_product_summary', 'foxiz_wc_single_breadcrumb', 4 );
add_action( 'foxiz_wc_header_template', 'foxiz_wc_template', 10 );

/** re-setup link */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_before_shop_loop_item', 'foxiz_wc_absolute_product_link', 1 );

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'foxiz_wc_fix_lightbox', 10 );

add_filter( 'shortcode_atts_products', 'foxiz_wc_support_offset', 10, 4 );
add_filter( 'woocommerce_shortcode_products_query', 'foxiz_setup_offset_attr', 10, 3 );
add_filter( 'woocommerce_loop_add_to_cart_link', 'foxiz_wc_add_to_cart_wrapper' );

/** shop wrapper */
if ( ! function_exists( 'foxiz_wc_before_shop_loop' ) ) {
	function foxiz_wc_before_shop_loop() {

		if ( is_shop() ) {
			$sidebar_position = foxiz_get_option( 'wc_shop_sidebar_position' );
		} elseif ( is_product_category() ) {
			$sidebar_position = foxiz_get_option( 'wc_archive_sidebar_position' );
		}

		if ( ! empty( $sidebar_position ) && 'none' !== $sidebar_position ) {

			$class_name     = 'shop-page is-sidebar-' . $sidebar_position;
			$sticky_sidebar = foxiz_get_option( 'sticky_sidebar' );

			if ( ! empty( $sticky_sidebar ) ) {
				if ( '2' === (string) $sticky_sidebar ) {
					$class_name .= ' sticky-last-w';
				} else {
					$class_name .= ' sticky-sidebar';
				}
			}
			echo '<div class="' . esc_attr( $class_name ) . '">';
		} else {
			echo '<div class="shop-page without-sidebar">';
		}
		echo '<div class="rb-container edge-padding">';
		echo '<div class="grid-container"><div class="shop-page-content">';
	}
}

/** close site-main */
if ( ! function_exists( 'foxiz_wc_template' ) ) {
	function foxiz_wc_template() {

		if ( ! is_shop() ) {
			return false;
		}

		$template = foxiz_get_option( 'wc_shop_template' );

		if ( ! empty( $template ) ) {
			echo do_shortcode( $template );
		}
	}
}

/** close site-main */
if ( ! function_exists( 'foxiz_wc_after_shop_loop' ) ) {
	function foxiz_wc_after_shop_loop() {

		echo '</div>';
	}
}

/** close wrapper page-content */
if ( ! function_exists( 'foxiz_wc_after_main_content' ) ) {
	function foxiz_wc_after_main_content() {

		echo '</div></div></div>';
	}
}

/** shop posts per page */
if ( ! function_exists( 'foxiz_wc_related_posts_per_page' ) ) {
	function foxiz_wc_related_posts_per_page( $args ) {

		$total                  = foxiz_get_option( 'wc_related_posts_per_page' );
		$args['posts_per_page'] = $total;
		$args['columns']        = 4;

		return $args;
	}
}

/** remove zip code */
if ( ! function_exists( 'foxiz_optional_postcode_checkout' ) ) {
	function foxiz_optional_postcode_checkout( $fields ) {

		$fields['postcode']['required'] = false;

		return $fields;
	}
}

if ( ! function_exists( 'foxiz_checkout_customer_details_before' ) ) {
	function foxiz_checkout_customer_details_before() {

		?>
		<div class="checkout-col col-left">
		<?php
	}
}

if ( ! function_exists( 'foxiz_checkout_customer_details_after' ) ) {
	function foxiz_checkout_customer_details_after() { ?>
		</div><div class="checkout-col col-right">
		<?php
	}
}

if ( ! function_exists( 'foxiz_checkout_order_after' ) ) {
	function foxiz_checkout_order_after() { ?>
		</div>
		<?php
	}
}

/** remove description */
if ( ! function_exists( 'foxiz_additional_information_heading' ) ) {
	function foxiz_additional_information_heading( $heading ) {

		return false;
	}
}

/** product review box */
if ( ! function_exists( 'foxiz_wc_review_box' ) ) {
	function foxiz_wc_review_box( $tabs ) {

		$check = foxiz_get_option( 'wc_box_review' );
		if ( empty( $check ) ) {
			unset( $tabs['reviews'] );
		}

		return $tabs;
	}
}

/** cross sell */
if ( ! function_exists( 'foxiz_wc_cross_sells_columns' ) ) {
	function foxiz_wc_cross_sells_columns( $columns ) {

		return 4;
	}
}

/** listing columns */
if ( ! function_exists( 'foxiz_wc_shop_columns' ) ) {
	function foxiz_wc_shop_columns() {

		if ( is_shop() ) {
			$sidebar_position = foxiz_get_option( 'wc_shop_sidebar_position' );
		} elseif ( is_product_category() ) {
			$sidebar_position = foxiz_get_option( 'wc_archive_sidebar_position' );
		}

		if ( ! empty( $sidebar_position ) && 'none' === $sidebar_position ) {
			return 4;
		} else {
			return 3;
		}
	}
}

if ( ! function_exists( 'foxiz_wc_sale_percent' ) ) {
	function foxiz_wc_sale_percent( $html, $post, $product ) {

		if ( ! foxiz_get_option( 'wc_sale_percent' ) || empty( $product->get_regular_price() ) ) {
			return $html;
		}

		if ( $product->is_on_sale() ) {
			$attachment_ids = $product->get_gallery_image_ids();
			$class_name     = 'onsale percent ';
			if ( empty( $attachment_ids ) ) {
				$class_name = 'onsale percent without-gallery';
			}
			$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );

			return '<span class="' . esc_attr( $class_name ) . '"><span class="onsale-inner"><strong>' . '-' . esc_html( $percentage ) . '</strong><i aria-hidden="true">&#37;' . '</i></span></span>';
		}
	}
}

if ( ! function_exists( 'foxiz_wc_add_to_cart_fragments' ) ) {
	function foxiz_wc_add_to_cart_fragments( $fragments ) {

		$cart = WC()->cart;

		if ( ! $cart || ! $cart instanceof \WC_Cart ) {
			$count    = 0;
			$subtotal = 0;
		} else {
			$count    = $cart->get_cart_contents_count();
			$subtotal = $cart->get_cart_subtotal();
		}

		if ( foxiz_get_option( 'wc_mini_cart' ) || foxiz_get_option( 'wc_mobile_mini_cart' ) ) {
			$fragments['span.cart-counter']  = '<span class="cart-counter">' . $count . '</span>';
			$fragments['span.total-amount']  = '<span class="total-amount">' . $subtotal . '</span>';
			$fragments['div.mini-cart-wrap'] = '<div class="mini-cart-wrap woocommerce">' . $fragments['div.widget_shopping_cart_content'] . '</div>';
			unset( $fragments['div.widget_shopping_cart_content'] );
		}

		if ( foxiz_get_option( 'wc_add_cart_popup' ) ) {
			$fragments['span.add-cart-popup'] = '<span class="add-cart-popup"><span class="added-info">' . foxiz_html__( 'Product added to cart!', 'foxiz' ) . '</span>';
			$fragments['span.add-cart-popup'] .= '<a class="is-btn" href="' . wc_get_cart_url() . '">' . foxiz_html__( 'View Cart', 'foxiz' ) . '</a></span>';
		}

		return $fragments;
	}
}

if ( ! function_exists( 'foxiz_remove_single_breadcrumb' ) ) {
	function foxiz_remove_single_breadcrumb() {

		if ( is_product() ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		}
	}
}

if ( ! function_exists( 'foxiz_wc_product_category' ) ) {
	function foxiz_wc_product_category( $args = [] ) {

		$terms = get_the_terms( get_the_ID(), 'product_cat' );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			echo '<div class="product-top">';
			echo '<div class="product-entry-categories p-categories">';
			foreach ( $terms as $term ) {
				echo '<a href="' . foxiz_get_term_link( $term ) . '" class="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</a>';
			}
			echo '</div>';

			if ( function_exists( 'wc_get_template' ) ) {
				wc_get_template( 'loop/rating.php' );
			}
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'foxiz_wc_enqueue_styles' ) ) {
	function foxiz_wc_enqueue_styles( $styles ) {

		unset( $styles['woocommerce-general'] );

		return $styles;
	}
}

if ( ! function_exists( 'foxiz_wc_single_breadcrumb' ) ) {
	function foxiz_wc_single_breadcrumb() {

		if ( function_exists( 'woocommerce_breadcrumb' ) ) {
			woocommerce_breadcrumb();
		}
	}
}

if ( ! function_exists( 'foxiz_wc_absolute_product_link' ) ) {
	/**
	 * Insert the opening anchor tag for products in the loop.
	 */
	function foxiz_wc_absolute_product_link() {

		global $product;

		$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

		echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link product-absolute-link"></a>';
	}
}

if ( ! function_exists( 'foxiz_wc_fix_lightbox' ) ) {
	/**
	 * @param $html
	 *
	 * @return string|string[]|null
	 */
	function foxiz_wc_fix_lightbox( $html ) {

		if ( foxiz_is_elementor_active() ) {
			return preg_replace( '/<a(.*)href="([^"]*)"(.*)>/', '<a$1href="#"$3>', $html );
		}

		return $html;
	}
}

if ( ! function_exists( 'foxiz_wc_group_thumbnail' ) ) {
	/**
	 * @return false
	 */
	function foxiz_wc_group_thumbnail() {

		if ( ! foxiz_get_option( 'wc_group_thumbnail' ) ) {
			return false;
		}
		?>
		<td class="product-thumbnail grouped-thumb"><?php echo woocommerce_get_product_thumbnail(); ?></td>
		<?php
	}
}

if ( ! function_exists( 'foxiz_add_cart_popup' ) ) {
	function foxiz_add_cart_popup() {

		if ( foxiz_get_option( 'wc_add_cart_popup' ) && class_exists( 'WooCommerce' ) ) {
			echo '<div id="add-cart-popup"><span class="add-cart-popup"></span></div>';
		}
	}
}

if ( ! function_exists( 'foxiz_get_classes_products_loop' ) ) {
	function foxiz_get_classes_products_loop() {

		$classes = [
			'products-outer',
		];

		if ( foxiz_get_option( 'wc_box_style' ) ) {
			$classes[]   = 'is-boxed-' . foxiz_get_option( 'wc_box_style', '0' );
			$cart_layout = 'visible';
		} else {
			$cart_layout = '0';
		}

		$classes[] = 'cart-layout-' . $cart_layout;

		if ( foxiz_get_option( 'wc_add_cart_style' ) ) {
			$classes[] = 'cart-style-' . foxiz_get_option( 'wc_add_cart_style' );
		}

		if ( foxiz_get_option( 'wc_responsive_list' ) ) {
			$classes[] = 'is-m-list';
		}

		if ( foxiz_get_option( 'wc_centered' ) ) {
			$classes[] = 'p-center';
		}

		if ( $GLOBALS['wp_query']->get( 'wc_query' ) ) {
			$classes[] = 'yes-ploop';
		}

		return join( ' ', $classes );
	}
}

if ( ! function_exists( 'foxiz_wc_add_to_cart_wrapper' ) ) {
	function foxiz_wc_add_to_cart_wrapper( $html ) {

		return '<div class="product-btn">' . $html . '</div>';
	}
}

if ( ! function_exists( 'foxiz_wc_support_offset' ) ) {
	function foxiz_wc_support_offset( $out, $pairs, $atts, $shortcode ) {

		if ( ! empty( $atts['offset'] ) ) {
			$out['offset'] = $atts['offset'];
		}

		return $out;
	}
}

if ( ! function_exists( 'foxiz_setup_offset_attr' ) ) {
	function foxiz_setup_offset_attr( $query_args, $attributes, $type ) {

		if ( ! empty( $attributes['offset'] ) && ( empty( $attributes['page'] ) || $attributes['page'] < 2 ) ) {
			$query_args['offset'] = absint( $attributes['offset'] );
		}

		return $query_args;
	}
}