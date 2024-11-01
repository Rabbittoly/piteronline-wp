<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_protocol' ) ) {
	function foxiz_protocol() {

		return is_ssl() ? 'https' : 'http';
	}
}

if ( ! function_exists( 'rb_get_meta' ) ) {
	function rb_get_meta( $id, $post_id = null ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( empty( $post_id ) ) {
			return false;
		}

		$rb_meta = get_post_meta( $post_id, 'rb_global_meta', true );
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
	function rb_get_term_meta( $key, $term_id = null ) {

		if ( empty( $term_id ) ) {
			$term_id = get_queried_object_id();
		}

		$metas = get_metadata( 'term', $term_id, $key, true );
		if ( empty( $metas ) || ! is_array( $metas ) ) {
			return [];
		}

		return $metas;
	}
}

if ( ! function_exists( 'rb_get_nav_item_meta' ) ) {
	function rb_get_nav_item_meta( $key, $nav_item_id, $menu_id = null ) {

		$metas = get_metadata( 'post', $nav_item_id, $key, true );

		/** fallback */
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

if ( ! function_exists( 'foxiz_html__' ) ) {
	function foxiz_html__( $text, $domain = 'foxiz-core' ) {

		$id   = foxiz_convert_to_id( $text );
		$data = get_option( 'rb_translated_data', [] );
		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		} else {
			$translated = esc_html( translate( $text, $domain ) );
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_attr__' ) ) {
	function foxiz_attr__( $text, $domain = 'foxiz-core' ) {

		$id   = foxiz_convert_to_id( $text );
		$data = get_option( 'rb_translated_data', [] );

		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		} else {
			$translated = esc_attr( translate( $text, $domain ) );
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_html_e' ) ) {
	function foxiz_html_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_html__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_attr_e' ) ) {
	function foxiz_attr_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_attr__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_is_amp' ) ) {
	function foxiz_is_amp() {

		return function_exists( 'amp_is_request' ) && amp_is_request();
	}
}

if ( ! function_exists( 'foxiz_pretty_number' ) ) {
	function foxiz_pretty_number( $number ) {

		$number = intval( $number );
		if ( $number > 999999 ) {
			$number = str_replace( '.00', '', number_format( ( $number / 1000000 ), 2 ) ) . foxiz_attr__( 'M' );
		} elseif ( $number > 999 ) {
			$number = str_replace( '.0', '', number_format( ( $number / 1000 ), 1 ) ) . foxiz_attr__( 'k' );
		}

		return $number;
	}
}

if ( ! function_exists( 'foxiz_is_svg' ) ) {
	function foxiz_is_svg( $attachment = '' ) {

		return substr( $attachment, - 4 ) === '.svg';
	}
}

if ( ! function_exists( 'foxiz_render_svg' ) ) {
	function foxiz_render_svg( $svg_name = '', $color = '', $ui = '' ) {

		echo foxiz_get_svg( $svg_name, $color, $ui );
	}
}

if ( ! function_exists( 'foxiz_get_svg' ) ) {
	function foxiz_get_svg( $svg_name = '', $color = '', $ui = '' ) {

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_breadcrumb' ) ) {
	function foxiz_get_breadcrumb( $classes = '', $check_setting = true ) {

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_image_size' ) ) {
	function foxiz_get_image_size( $filename ) {

		if ( is_string( $filename ) ) {
			return @getimagesize( $filename );
		}

		return [];
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
					$custom_size = preg_replace( '/\s+/', '', $custom_size );;
					$hw = explode( 'x', $custom_size );
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

if ( ! function_exists( 'foxiz_get_adsense' ) ) {
	function foxiz_get_adsense() {

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_ad_image' ) ) {
	function foxiz_get_ad_image() {

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_theme_mode' ) ) {
	function foxiz_get_theme_mode() {

		return 'default';
	}
}

if ( ! function_exists( 'foxiz_get_active_plugins' ) ) {
	/**
	 * @return array
	 */
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

if ( ! function_exists( 'foxiz_is_elementor_active' ) ) {
	function foxiz_is_elementor_active() {

		return class_exists( 'Elementor\\Plugin' );
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
