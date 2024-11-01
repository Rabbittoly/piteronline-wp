<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

define( 'RB_THEME_ID', 'foxiz' );
defined( 'RB_API_URL' ) || define( 'RB_API_URL', 'https://api.themeruby.com' );
define( 'FOXIZ_LICENSE_ID', 'foxiz_license_id' );
define( 'FOXIZ_IMPORT_ID', 'foxiz_import_id' );
define( 'FOXIZ_ADMIN_NAMESPACE', 'foxiz-admin' );
define( 'FOXIZ_ACTIVATION_ID', '_Ruby_Activation' );

if ( ! function_exists( 'foxiz_get_option' ) ) {
	/**
	 * @param string $option_name
	 * @param false  $default
	 *
	 * @return false|mixed|void
	 */
	function foxiz_get_option( $option_name = '', $default = false ) {

		if ( ! isset( $GLOBALS[ FOXIZ_TOS_ID ] ) ) {
			$GLOBALS[ FOXIZ_TOS_ID ] = get_option( FOXIZ_TOS_ID, [] );
		}

		if ( ! $option_name ) {
			return $GLOBALS[ FOXIZ_TOS_ID ];
		}

		return isset( $GLOBALS[ FOXIZ_TOS_ID ][ $option_name ] ) ? $GLOBALS[ FOXIZ_TOS_ID ][ $option_name ] : $default;
	}
}

if ( ! function_exists( 'foxiz_is_theme_registered' ) ) {
	function foxiz_is_theme_registered() {

		return (bool) get_option( FOXIZ_ACTIVATION_ID, false );
	}
}

if ( ! function_exists( 'foxiz_convert_to_id' ) ) {
	function foxiz_convert_to_id( $name ) {

		$name = strtolower( strip_tags( $name ) );
		$name = str_replace( ' ', '-', $name );
		$name = preg_replace( '/[^A-Za-z0-9\-]/', '', $name );

		return substr( $name, 0, 20 );
	}
}

if ( ! function_exists( 'foxiz_is_plugin_active' ) ) {
	function foxiz_is_plugin_active( $plugin ) {

		return in_array( $plugin, (array) get_option( 'active_plugins', [] ), true ) || foxiz_is_plugin_active_for_network( $plugin );
	}
}

if ( ! function_exists( 'foxiz_get_active_plugins' ) ) {
	function foxiz_get_active_plugins() {

		$active_plugins = (array) get_option( 'active_plugins', [] );
		if ( is_multisite() ) {
			$network_plugins = array_keys( get_site_option( 'active_sitewide_plugins', [] ) );
			if ( $network_plugins ) {
				$active_plugins = array_merge( $active_plugins, $network_plugins );
			}
		}

		sort( $active_plugins );

		return array_unique( $active_plugins );
	}
}

if ( ! function_exists( 'foxiz_is_plugin_active_for_network' ) ) {
	function foxiz_is_plugin_active_for_network( $plugin ) {

		if ( ! is_multisite() ) {
			return false;
		}

		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $plugin ] ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_is_elementor_active' ) ) {
	function foxiz_is_elementor_active() {

		return class_exists( 'Elementor\\Plugin' ) || foxiz_is_plugin_active( 'elementor/elementor.php' );
	}
}

if ( ! function_exists( 'foxiz_is_doing_ajax' ) ) {
	function foxiz_is_doing_ajax() {

		return function_exists( 'wp_doing_ajax' ) && wp_doing_ajax();
	}
}

if ( ! function_exists( 'foxiz_convert_to_id' ) ) {
	/**
	 * @param $name
	 *
	 * @return string
	 */
	function foxiz_convert_to_id( $name ) {

		$name = strtolower( strip_tags( $name ) );
		$name = str_replace( ' ', '-', $name );
		$name = preg_replace( '/[^A-Za-z0-9\-]/', '', $name );
		$name = substr( $name, 0, 20 );

		return $name;
	}
}

if ( ! function_exists( 'foxiz_strip_tags' ) ) {
	/**
	 * @param        $content
	 * @param string $allowed_tags
	 *
	 * @return string
	 */
	function foxiz_strip_tags( $content, $allowed_tags = '<h1><h2><h3><h4><h5><h6><strong><b><em><i><a><code><p><div><ol><ul><li><br><button><figure><img><video><audio>' ) {

		return strip_tags( $content, $allowed_tags );
	}
}

if ( ! function_exists( 'foxiz_render_inline_html' ) ) {
	function foxiz_render_inline_html( $content ) {

		echo foxiz_strip_tags( $content );
	}
}

if ( ! function_exists( 'foxiz_protocol' ) ) {
	function foxiz_protocol() {

		return is_ssl() ? 'https' : 'http';
	}
}

if ( ! function_exists( 'foxiz_is_amp' ) ) {
	function foxiz_is_amp() {

		return function_exists( 'amp_is_request' ) && amp_is_request();
	}
}

if ( ! function_exists( 'rb_get_meta' ) ) {
	/**
	 * @param      $id
	 * @param null $post_id
	 *
	 * @return false|mixed
	 * get meta
	 */
	function rb_get_meta( $id, $post_id = null ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( empty( $post_id ) ) {
			return false;
		}

		$rb_meta = get_post_meta( $post_id, RB_META_ID, true );
		if ( ! empty( $rb_meta[ $id ] ) ) {

			if ( is_array( $rb_meta[ $id ] ) && isset( $rb_meta[ $id ]['placebo'] ) ) {
				unset( $rb_meta[ $id ]['placebo'] );
			}

			return $rb_meta[ $id ];
		}

		return false;
	}
}

if ( ! function_exists( 'rb_get_term_meta' ) ) {
	/**
	 * @param      $key
	 * @param null $term_id
	 *
	 * @return array|false
	 */
	function rb_get_term_meta( $key, $term_id = null ) {

		if ( empty( $term_id ) ) {
			$term_id = get_queried_object_id();
		}

		// get meta fields from option table
		$metas = get_metadata( 'term', $term_id, $key, true );

		if ( empty( $metas ) || ! is_array( $metas ) ) {
			return [];
		}

		return $metas;
	}
}

if ( ! function_exists( 'rb_get_nav_item_meta' ) ) {
	/**
	 * @param      $key
	 * @param      $nav_item_id
	 * @param null $menu_id
	 *
	 * @return array|false
	 */
	function rb_get_nav_item_meta( $key, $nav_item_id, $menu_id = null ) {

		$metas = get_metadata( 'post', $nav_item_id, $key, true );

		if ( empty( $metas ) ) {
			$metas = get_option( 'rb_menu_settings_' . $menu_id, [] );
			$metas = isset( $metas[ $nav_item_id ] ) ? $metas[ $nav_item_id ] : [];
		}

		if ( empty( $metas ) || ! is_array( $metas ) ) {
			return [];
		}

		return $metas;
	}
}

if ( ! function_exists( 'foxiz_query_order_selection' ) ) {
	function foxiz_query_order_selection( $settings = [] ) {

		$configs = [
			'date_post'               => esc_html__( 'Last Published', 'foxiz-core' ),
			'update'                  => esc_html__( 'Last Updated', 'foxiz-core' ),
			'comment_count'           => esc_html__( 'Popular Comment', 'foxiz-core' ),
			'popular'                 => esc_html__( 'Popular (by Post Views)', 'foxiz-core' ),
			'popular_1d'              => esc_html__( 'Popular Published Last 24 Hours', 'foxiz-core' ),
			'popular_2d'              => esc_html__( 'Popular Published Last 2 Days', 'foxiz-core' ),
			'popular_3d'              => esc_html__( 'Popular Published Last 3 Days', 'foxiz-core' ),
			'popular_w'               => esc_html__( 'Popular Published Last 7 Days', 'foxiz-core' ),
			'popular_m'               => esc_html__( 'Popular Published Last 30 Days', 'foxiz-core' ),
			'popular_3m'              => esc_html__( 'Popular Published Last 3 Months', 'foxiz-core' ),
			'popular_6m'              => esc_html__( 'Popular Published Last 6 Months', 'foxiz-core' ),
			'popular_y'               => esc_html__( 'Popular Published Last Year', 'foxiz-core' ),
			'top_review'              => esc_html__( 'Top Review (All Time)', 'foxiz-core' ),
			'top_review_3d'           => esc_html__( 'Top Review Published Last 3 Days', 'foxiz-core' ),
			'top_review_w'            => esc_html__( 'Top Review Published Last 7 Days', 'foxiz-core' ),
			'top_review_m'            => esc_html__( 'Top Review Published Last 30 Days', 'foxiz-core' ),
			'top_review_3m'           => esc_html__( 'Top Review Published Last 3 Months', 'foxiz-core' ),
			'top_review_6m'           => esc_html__( 'Top Review Published Last 6 Months', 'foxiz-core' ),
			'top_review_y'            => esc_html__( 'Top Review Published Last Year', 'foxiz-core' ),
			'last_review'             => esc_html__( 'Latest Review', 'foxiz-core' ),
			'post_type'               => esc_html__( 'Post Type', 'foxiz-core' ),
			'sponsored'               => esc_html__( 'Latest Sponsored', 'foxiz-core' ),
			'rand'                    => esc_html__( 'Random', 'foxiz-core' ),
			'rand_3d'                 => esc_html__( 'Random last 3 Days', 'foxiz-core' ),
			'rand_w'                  => esc_html__( 'Random last 7 Days', 'foxiz-core' ),
			'rand_m'                  => esc_html__( 'Random last 30 Days', 'foxiz-core' ),
			'rand_3m'                 => esc_html__( 'Random last 3 Months', 'foxiz-core' ),
			'rand_6m'                 => esc_html__( 'Random last 6 Months', 'foxiz-core' ),
			'rand_y'                  => esc_html__( 'Random last Last Year', 'foxiz-core' ),
			'author'                  => esc_html__( 'Author', 'foxiz-core' ),
			'new_live'                => esc_html__( 'Last Published Live', 'foxiz-core' ),
			'update_live'             => esc_html__( 'Last Updated Live', 'foxiz-core' ),
			'new_flive'               => esc_html__( 'Last Live (Archived Included)', 'foxiz-core' ),
			'update_flive'            => esc_html__( 'Last Updated Live (Archived Included)', 'foxiz-core' ),
			'alphabetical_order_decs' => esc_html__( 'Title DECS', 'foxiz-core' ),
			'alphabetical_order_asc'  => esc_html__( 'Title ACS', 'foxiz-core' ),
			'by_input'                => esc_html__( 'by input IDs Data (Post IDs filter)', 'foxiz-core' ),
		];

		if ( is_array( $settings ) && count( $settings ) ) {
			$configs = array_merge( $configs, $settings );
		}

		return $configs;
	}
}

if ( ! function_exists( 'foxiz_count_posts_category' ) ) {
	/**
	 * @param $item
	 *
	 * @return int
	 */
	function foxiz_count_posts_category( $item ) {

		$count     = $item->category_count;
		$tax_terms = get_terms( 'category', [
			'child_of' => $item->term_id,
		] );
		foreach ( $tax_terms as $tax_term ) {
			$count += $tax_term->count;
		}

		return $count;
	}
}

if ( ! function_exists( 'foxiz_calc_crop_sizes' ) ) {
	function foxiz_calc_crop_sizes() {

		$settings = foxiz_get_option();
		$crop     = true;
		if ( ! empty( $settings['crop_position'] ) && ( 'top' === $settings['crop_position'] ) ) {
			$crop = [ 'center', 'top' ];
		}

		$sizes = [
			'foxiz_crop_g1' => [ 330, 220, $crop ],
			'foxiz_crop_g2' => [ 420, 280, $crop ],
			'foxiz_crop_g3' => [ 615, 410, $crop ],
			'foxiz_crop_o1' => [ 860, 0, $crop ],
			'foxiz_crop_o2' => [ 1536, 0, $crop ],
		];

		foreach ( $sizes as $crop_id => $size ) {
			if ( empty( $settings[ $crop_id ] ) ) {
				unset( $sizes[ $crop_id ] );
			}
		}

		if ( ! empty( $settings['featured_crop_sizes'] ) && is_array( $settings['featured_crop_sizes'] ) ) {
			foreach ( $settings['featured_crop_sizes'] as $custom_size ) {
				if ( ! empty( $custom_size ) ) {
					$custom_size = preg_replace( '/\s+/', '', $custom_size );
					$hw          = explode( 'x', $custom_size );
					if ( isset( $hw[0] ) && isset( $hw[1] ) ) {
						$crop_id           = 'foxiz_crop_' . $custom_size;
						$sizes[ $crop_id ] = [ absint( $hw[0] ), absint( $hw[1] ), $crop ];
					}
				}
			}
		}

		return $sizes;
	}
}