<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Rb_Init_Fonts', false ) ) {
	class Rb_Init_Fonts {

		private static $instance;

		public $settings;
		public $supported_headings;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;
			add_filter( 'ruby_fonts', [ $this, 'theme_options_mapping' ] );
		}

		/**
		 * @param $setting
		 *
		 * @return string
		 */
		public function parse_setting( $setting ) {

			$params    = explode( '::', $setting );
			$font_data = get_option( 'rb_adobe_fonts', [] );
			$output    = '';

			if ( isset( $params[0] ) ) {
				if ( ! empty( $font_data['fonts'][ $params[0] ]['backup'] ) ) {
					$output .= 'font-family:' . $font_data['fonts'][ $params[0] ]['backup'] . ';';
				} else {
					$output .= 'font-family:' . $params[0] . ';';
				}
			}

			if ( ! empty( $params[1] ) ) {
				$output .= 'font-weight:' . intval( $params[1] ) . ';';
			}

			if ( substr( $params[1], - 1 ) === 'i' ) {
				$output .= 'font-style: italic;';
			}

			return $output;
		}

		/** css output */
		public function css_output() {

			$settings   = get_option( 'rb_adobe_font_settings', [] );
			$css_output = '';
			if ( count( $settings ) ) {
				foreach ( $settings as $tag => $setting ) {
					if ( ! empty( $setting ) ) {
						$css_output .= $tag . '{' . $this->parse_setting( $setting ) . '}';
					}
				}
			}

			if ( ! wp_style_is( 'foxiz-style' ) ) {
				wp_add_inline_style( 'adobe-fonts', $css_output );
			} else {
				wp_add_inline_style( 'foxiz-style', $css_output );
			}
		}

		public function theme_options_mapping( $fonts ) {

			$adobe_fonts = get_option( 'rb_adobe_fonts', [] );

			if ( ! empty( $adobe_fonts['fonts'] ) ) {
				foreach ( $adobe_fonts['fonts'] as $name => $data ) {

					$new = [
						'subsets' => [
							[
								'id'   => 'adobe',
								'name' => esc_html__( 'Based on Adobe', 'foxiz' ),
							],
						],
					];

					if ( empty( $data['variations'] ) || ! is_array( $data['variations'] ) ) {
						$data['variations'] = [ '400', '700' ];
					}

					foreach ( $data['variations'] as $variant ) {
						if ( stripos( $variant, 'i' ) ) {
							$variant = trim( $variant ) . 'talic';
						}
						$new['variants'][] = [
							'id'   => $variant,
							'name' => $this->get_variant_name( $variant ),
						];
					}

					if ( empty( $fonts[ $name ] ) ) {
						$fonts[ $name ] = $new;
					}
				}
			}

			return $fonts;
		}

		public function get_variant_name( $variant ) {

			switch ( $variant ) {
				case '100':
					return esc_html__( 'Light 100', 'foxiz' );
				case '300':
					return esc_html__( 'Book 300', 'foxiz' );
				case '400':
					return esc_html__( 'Normal 400', 'foxiz' );
				case '500':
					return esc_html__( 'Medium 500', 'foxiz' );
				case '600':
					return esc_html__( 'Semi-Bold 600', 'foxiz' );
				case '700':
					return esc_html__( 'Bold 700', 'foxiz' );
				case '800':
					return esc_html__( 'Extra-Bold 800', 'foxiz' );
				case '900':
					return esc_html__( 'Extra-Bold 900', 'foxiz' );
				case '100italic':
					return esc_html__( 'Light 100 Italic', 'foxiz' );
				case '300italic':
					return esc_html__( 'Book 300 Italic', 'foxiz' );
				case '400italic':
					return esc_html__( 'Normal 400 Italic', 'foxiz' );
				case '500italic':
					return esc_html__( 'Medium 500 Italic', 'foxiz' );
				case '600italic':
					return esc_html__( 'Semi-Bold 600 Italic', 'foxiz' );
				case '700italic':
					return esc_html__( 'Bold 700 Italic', 'foxiz' );
				case '800italic':
					return esc_html__( 'Extra-Bold 800 Italic', 'foxiz' );
				case '900italic':
					return esc_html__( 'Extra-Bold 900 Italic', 'foxiz' );
				default:
					return $variant;
			}
		}
	}
}

/** load */
Rb_Init_Fonts::get_instance();
