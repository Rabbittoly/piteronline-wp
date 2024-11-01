<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Font' ) ) {
	class Foxiz_Font {

		private static $instance;

		public $gfonts;
		public $settings;
		private $std_fonts = [
			"'system-ui', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif",
			"Arial, Helvetica, sans-serif",
			"'Arial Black', Gadget, sans-serif",
			"'Bookman Old Style', serif",
			"'Comic Sans MS', cursive",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"'Lucida Console', Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
			"Tahoma,Geneva, sans-serif",
			"'Times New Roman', Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif",
		];

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			$this->gfonts   = $this->get_gfonts();
			$this->settings = foxiz_get_option();
		}

		public function get_gfonts() {
			if ( defined( 'FOXIZ_CORE_PATH' ) ) {
				$gfont_file = FOXIZ_CORE_PATH . 'lib/redux-framework/inc/fields/typography/googlefonts.php';
				if ( file_exists( $gfont_file ) ) {
					return include $gfont_file;
				}
			}

			return [];
		}

		/**
		 * @param $font_family
		 *
		 * @return false|string
		 */
		public function get_all_styles( $font_family ) {

			$styles  = [];
			$exclude = [ '100', '200', '100i', '200i' ];
			if ( empty( $font_family ) || ! isset( $this->gfonts[ $font_family ] ) ) {
				return false;
			}

			$gfont = $this->gfonts[ $font_family ];
			if ( is_array( $gfont['variants'] ) ) {
				foreach ( $gfont['variants'] as $variant ) {
					if ( ! isset( $variant['id'] ) || ! in_array( $variant['id'], $exclude ) ) {
						array_push( $styles, $variant['id'] );
					}
				}
			}

			return implode( ',', $styles );
		}

		/** get google font URLs */
		function get_font_url() {

			if ( ! empty( $this->settings['disable_google_fonts'] ) ) {
				return false;
			}

			$pre_fonts = [];
			$fonts     = [];
			$subsets   = [];
			$link      = '';

			foreach ( $this->settings as $id => $field ) {

				if ( ! empty( $field['subsets'] ) && $field['subsets'] === 'adobe' ) {
					continue;
				}

				if ( ! empty( $field['font-family'] ) ) {

					if ( in_array( $field['font-family'], $this->std_fonts ) ) {
						continue;
					}

					if ( ! isset( $field['font-style'] ) ) {
						$field['font-style'] = '';
					}
					if ( 'font_body' === $id ) {
						$field['font-weight'] = '';
						$field['font-style']  = $this->get_all_styles( $field['font-family'] );
					}

					if ( ! empty( $field['font-weight'] ) ) {
						$field['font-style'] = $field['font-weight'] . $field['font-style'];
					}

					array_push( $pre_fonts, $field );
				}
			}

			if ( empty( $this->settings['disable_default_fonts'] ) ) {
				$pre_fonts = array_merge( $pre_fonts, [
					[
						'font-family' => 'Oxygen',
						'font-style'  => '400,700',
					],
					[
						'font-family' => 'Encode Sans Condensed',
						'font-style'  => '400,500,600,700,800',
					],
				] );
			}

			foreach ( $pre_fonts as $field ) {

				$field['font-family'] = str_replace( ' ', '+', $field['font-family'] );
				$styles               = explode( ',', $field['font-style'] );

				if ( ! isset( $fonts[ $field['font-family'] ] ) ) {
					$fonts[ $field['font-family'] ]               = $field;
					$fonts[ $field['font-family'] ]['font-style'] = [];
				}

				$fonts[ $field['font-family'] ]['font-style'] = array_merge( $fonts[ $field['font-family'] ]['font-style'], $styles );
			}

			foreach ( $fonts as $family => $font ) {
				if ( ! empty( $link ) ) {
					$link .= "%7C";
				}
				$link .= $family;

				if ( ! empty( $font['font-style'] ) && is_array( $font['font-style'] ) ) {
					$link               .= ':';
					$font['font-style'] = array_unique( $font['font-style'] );
					$link               .= implode( ',', $font['font-style'] );
				}

				if ( ! empty( $font['subset'] ) ) {
					foreach ( $font['subset'] as $subset ) {
						if ( ! in_array( $subset, $subsets ) ) {
							array_push( $subsets, $subset );
						}
					}
				}
			}

			if ( ! empty( $subsets ) ) {
				$link .= "&subset=" . implode( ',', $subsets );
			}
			$link .= "&display=swap";

			return 'https://fonts.googleapis.com/css?family=' . str_replace( '|', '%7C', $link );
		}
	}
}
