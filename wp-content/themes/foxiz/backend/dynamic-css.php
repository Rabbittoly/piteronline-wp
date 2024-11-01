<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Css' ) ) {
	class Foxiz_Css {

		private static $instance;
		public static $debug = false;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'update_option_' . FOXIZ_TOS_ID, [ $this, 'update_hook' ], 10, 2 );
			add_action( 'created_term ', [ $this, 'write_css' ], 999 );
			add_action( 'edited_term', [ $this, 'write_css' ], 999 );
			add_action( 'wp_update_nav_menu', [ $this, 'write_css' ], 999 );
			add_action( 'enqueue_block_editor_assets', [ $this, 'dynamic_editor' ], 999 );
		}

		/**
		 * @param $css
		 *
		 * @return string|string[]|null
		 */
		function minify_css( $css ) {

			return preg_replace( '@({)\s+|(\;)\s+|/\*.+?\*\/|\R@is', '$1$2 ', $css );
		}

		/**
		 * @param array $settings
		 *
		 * @return string|string[]|null
		 */
		function write_css( $settings = [] ) {

			if ( empty( $settings ) || ! is_array( $settings ) ) {
				$settings = foxiz_get_option();
			}

			$output = $this->output( $settings );
			update_option( 'foxiz_style_cache', addslashes( $output ) );

			return $output;
		}

		/**
		 * @param $old_value
		 * @param $settings
		 */
		public function update_hook( $old_value, $settings ) {

			$this->write_css( $settings );
		}

		/**
		 * @param $handle
		 */
		public function load( $handle = 'foxiz-style' ) {

			if ( self::$debug && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$settings = foxiz_get_option();
				$output   = $this->output( $settings );
			} else {
				$output = get_option( 'foxiz_style_cache' );
				if ( empty( $output ) ) {
					$output = $this->write_css();
				} else {
					$output = stripslashes( $output );
				}
			}
			wp_add_inline_style( $handle, $output );
		}

		function dynamic_editor() {

			$this->load( 'foxiz-editor-style' );
		}

		/**
		 * @param $settings
		 * @param $config_id
		 *
		 * @return string
		 */
		public function css_background( $settings, $config_id ) {

			if ( ! isset( $settings[ $config_id ] ) || ! is_array( $settings[ $config_id ] ) ) {
				return '';
			}

			$background = $settings[ $config_id ];
			$output     = '';
			if ( ! empty( $background['background-color'] ) ) {
				$output .= 'background-color : ' . $background['background-color'] . ';';
			}
			if ( ! empty( $background['background-repeat'] ) ) {
				$output .= 'background-repeat : ' . $background['background-repeat'] . ';';
			}
			if ( ! empty( $background['background-size'] ) ) {
				$output .= 'background-size : ' . $background['background-size'] . ';';
			}
			if ( ! empty( $background['background-image'] ) ) {
				$output .= 'background-image : url(' . esc_url( $background['background-image'] ) . ');';
			}
			if ( ! empty( $background['background-attachment'] ) ) {
				$output .= 'background-attachment : ' . $background['background-attachment'] . ';';
			}
			if ( ! empty( $background['background-position'] ) ) {
				$output .= 'background-position : ' . $background['background-position'] . ';';
			}

			return $output;
		}

		/**
		 * @param array  $settings
		 * @param string $prefix
		 * @param string $config_id
		 *
		 * @return false|string
		 */
		public function font_var( $settings = [], $prefix = '', $config_id = '' ) {

			if ( empty( $settings[ $config_id ] ) || ! is_array( $settings[ $config_id ] ) ) {
				return false;
			}

			if ( ! empty( $settings[ $config_id ]['font-backup'] ) && ! empty( $settings[ $config_id ]['font-family'] ) ) {
				$settings[ $config_id ]['font-family'] = $settings[ $config_id ]['font-family'] . ', ' . $settings[ $config_id ]['font-backup'];
			}

			if ( foxiz_get_option( 'disable_google_font' ) ) {
				$settings[ $config_id ]['font-family'] = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif';
			}

			$output     = '';
			$font_array = shortcode_atts( [
				'font-family'    => '',
				'font-weight'    => '',
				'text-transform' => '',
				'color'          => '',
				'font-style'     => '',
				'font-size'      => '',
				'letter-spacing' => '',
				'line-height'    => '',
			], $settings[ $config_id ] );

			if ( ! empty( $font_array['line-height'] ) && ! empty( $font_array['font-size'] ) ) {

				$line_height_ratio = floatval( $font_array['line-height'] ) / floatval( $font_array['font-size'] );

				// the line-height must be greater than 1
				if ( $line_height_ratio <= 1 ) {
					unset( $font_array['line-height'] );
				} else {
					$font_array['line-height'] = number_format( $line_height_ratio, 3 );
				}
			} else {
				unset( $font_array['line-height'] );
			}

			if ( ! empty( $font_array['letter-spacing'] ) && ! empty( $font_array['font-size'] ) ) {
				$font_array['letter-spacing'] = number_format( floatval( $font_array['letter-spacing'] ) / floatval( $font_array['font-size'] ), 5 ) . 'em';
			}

			foreach ( $font_array as $name => $value ) {
				if ( ! empty( $value ) ) {
					$output .= '--' . $prefix . '-' . $this->compact_font_name( $name ) . ':' . $value . ';';
				}
			}

			return $output;
		}

		/**
		 * @param $name
		 *
		 * @return string
		 */
		public function compact_font_name( $name ) {

			$name = trim( $name );
			switch ( $name ) {
				case 'font-family' :
					return 'family';
				case 'font-weight' :
					return 'fweight';
				case 'text-transform' :
					return 'transform';
				case 'color' :
					return 'fcolor';
				case 'font-style' :
					return 'fstyle';
				case 'font-size' :
					return 'fsize';
				case 'letter-spacing' :
					return 'fspace';
				case 'line-height' :
					return 'fheight';
				default :
					return $name;
			}
		}

		/**
		 * @param array  $settings
		 * @param string $name
		 * @param string $config_id
		 *
		 * @return false|string
		 */
		function font_size_var( $settings = [], $name = '', $config_id = '' ) {

			if ( empty( $name ) || empty( $settings[ $config_id ] ) ) {
				return false;
			}

			if ( is_array( $settings[ $config_id ] ) ) {
				if ( ! empty( $settings[ $config_id ]['font-size'] ) ) {
					return '--' . $name . '-fsize : ' . floatval( $settings[ $config_id ]['font-size'] ) . 'px;';
				}

				return false;
			} else {
				return '--' . $name . '-fsize : ' . floatval( $settings[ $config_id ] ) . 'px;';
			}
		}

		/**
		 * @param array  $settings
		 * @param string $name
		 * @param string $config_id
		 *
		 * @return string
		 */
		function get_gradient_background_var( $settings = [], $name = '', $config_id = '' ) {

			if ( empty( $name ) || empty( $settings[ $config_id ] ) ) {
				return false;
			}

			$output = '';
			if ( ! empty( $settings[ $config_id ]['from'] ) && ! empty( $settings[ $config_id ]['to'] ) ) {
				$output .= '--' . $name . '-bg: ' . $settings[ $config_id ]['from'] . ';';
				$output .= '--' . $name . '-bg-from: ' . $settings[ $config_id ]['from'] . ';';
				$output .= '--' . $name . '-bg-to: ' . $settings[ $config_id ]['to'] . ';';
			} elseif ( ! empty( $settings[ $config_id ]['from'] ) ) {
				$output .= '--' . $name . '-bg: ' . $settings[ $config_id ]['from'] . ';';
				$output .= '--' . $name . '-bg-from: ' . $settings[ $config_id ]['from'] . ';';
				$output .= '--' . $name . '-bg-to: ' . $settings[ $config_id ]['from'] . ';';
			} elseif ( ! empty( $settings[ $config_id ]['to'] ) ) {
				$output .= '--' . $name . '-bg: ' . $settings[ $config_id ]['to'] . ';';
				$output .= '--' . $name . '-bg-from: ' . $settings[ $config_id ]['to'] . ';';
				$output .= '--' . $name . '-bg-to: ' . $settings[ $config_id ]['to'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_typography_var( $settings = [] ) {

			$output = '';
			$buffer = '';

			$buffer .= $this->font_var( $settings, 'body', 'font_body' );
			$buffer .= $this->font_var( $settings, 'h1', 'font_h1' );
			$buffer .= $this->font_var( $settings, 'h2', 'font_h2' );
			$buffer .= $this->font_var( $settings, 'h3', 'font_h3' );
			$buffer .= $this->font_var( $settings, 'h4', 'font_h4' );
			$buffer .= $this->font_var( $settings, 'h5', 'font_h5' );
			$buffer .= $this->font_var( $settings, 'h6', 'font_h6' );
			$buffer .= $this->font_var( $settings, 'cat', 'font_ecat' );
			$buffer .= $this->font_var( $settings, 'meta', 'font_emeta' );
			$buffer .= $this->font_var( $settings, 'meta-b', 'font_eauthor' );
			$buffer .= $this->font_var( $settings, 'input', 'font_input' );
			$buffer .= $this->font_var( $settings, 'btn', 'font_button' );
			$buffer .= $this->font_var( $settings, 'menu', 'font_main_menu' );
			$buffer .= $this->font_var( $settings, 'submenu', 'font_main_sub_menu' );
			$buffer .= $this->font_var( $settings, 'dwidgets', 'font_widget' );
			$buffer .= $this->font_var( $settings, 'headline', 'font_headline' );
			$buffer .= $this->font_var( $settings, 'tagline', 'font_tagline' );
			$buffer .= $this->font_var( $settings, 'heading', 'font_heading' );
			$buffer .= $this->font_var( $settings, 'subheading', 'font_sub_heading' );
			$buffer .= $this->font_var( $settings, 'quote', 'font_quote' );
			$buffer .= $this->font_var( $settings, 'epagi', 'font_epagi' );
			$buffer .= $this->font_var( $settings, 'excerpt', 'font_excerpt' );
			$buffer .= $this->font_var( $settings, 'bcrumb', 'font_breadcrumb' );
			$buffer .= $this->font_var( $settings, 'toc', 'font_toc' );
			$buffer .= $this->font_size_var( $settings, 'readmore', 'font_readmore' );
			$buffer .= $this->font_size_var( $settings, 'excerpt', 'font_excerpt_size' );
			$buffer .= $this->font_size_var( $settings, 'headline-s', 'font_headline_size_content' );
			$buffer .= $this->font_size_var( $settings, 'tagline-s', 'font_tagline_size_content' );
			$buffer .= $this->font_size_var( $settings, 'bookmark', 'bookmark_icon_size' );

			if ( ! empty( $buffer ) ) {
				$output .= ':root {' . $buffer . '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_tablet_var( $settings = [] ) {

			$output = '';
			$buffer = '';

			$buffer .= $this->font_size_var( $settings, 'body', 'font_body_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h1', 'font_h1_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h2', 'font_h2_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h3', 'font_h3_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h4', 'font_h4_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h5', 'font_h5_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'h6', 'font_h6_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'cat', 'font_ecat_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'meta', 'font_emeta_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'readmore', 'font_readmore_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'input', 'font_input_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'btn', 'font_button_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'bcrumb', 'font_breadcrumb_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'heading', 'font_heading_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'excerpt', 'font_excerpt_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'dwidgets', 'font_widget_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'headline', 'font_headline_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'headline-s', 'font_headline_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'tagline', 'font_tagline_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'tagline-s', 'font_tagline_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'product', 'font_product_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'sproduct', 'font_sproduct_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'price', 'font_price_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'bookmark', 'bookmark_icon_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'toc', 'font_toc_size_tablet' );
			$buffer .= $this->font_size_var( $settings, 'epagi', 'font_epagi_size_tablet' );

			if ( ! empty( $buffer ) ) {
				$output .= '@media (max-width: 1024px) {';
				$output .= 'body {' . $buffer . '}';
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_mobile_var( $settings = [] ) {

			$output = '';
			$buffer = '';

			$buffer .= $this->font_size_var( $settings, 'body', 'font_body_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h1', 'font_h1_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h2', 'font_h2_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h3', 'font_h3_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h4', 'font_h4_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h5', 'font_h5_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'h6', 'font_h6_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'cat', 'font_ecat_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'meta', 'font_emeta_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'readmore', 'font_readmore_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'input', 'font_input_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'btn', 'font_button_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'bcrumb', 'font_breadcrumb_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'heading', 'font_heading_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'excerpt', 'font_excerpt_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'dwidgets', 'font_widget_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'headline', 'font_headline_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'headline-s', 'font_headline_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'tagline', 'font_tagline_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'tagline-s', 'font_tagline_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'product', 'font_product_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'sproduct', 'font_sproduct_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'price', 'font_price_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'bookmark', 'bookmark_icon_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'toc', 'font_toc_size_mobile' );
			$buffer .= $this->font_size_var( $settings, 'epagi', 'font_epagi_size_mobile' );

			if ( ! empty( $settings['dark_mode_size'] ) ) {
				$buffer .= '--dm-size : ' . absint( $settings['dark_mode_size'] * .94 ) . 'px;';
			}

			if ( ! empty( $buffer ) ) {
				$output .= '@media (max-width: 767px) {';
				$output .= 'body {' . $buffer . '}';
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_colors_var_data( $settings = [] ) {

			$output = '';

			if ( ! empty( $settings['body_background'] ) ) {
				$output .= '--solid-white :' . $settings['body_background'] . ';';
			}
			if ( ! empty( $settings['global_color'] ) ) {
				$output .= '--g-color :' . $settings['global_color'] . ';';
				$output .= '--g-color-90 :' . $settings['global_color'] . 'e6;';
			}
			if ( ! empty( $settings['accent_color'] ) ) {
				$output .= '--dark-accent :' . $settings['accent_color'] . ';';
				$output .= '--dark-accent-90 :' . $settings['accent_color'] . 'e6;';
				$output .= '--dark-accent-0 :' . $settings['accent_color'] . '00;';
			}
			if ( ! empty( $settings['review_color'] ) ) {
				$output .= '--review-color :' . $settings['review_color'] . ';';
			}
			if ( ! empty( $settings['sponsor_color'] ) ) {
				$output .= '--sponsor-color :' . $settings['sponsor_color'] . ';';
			}
			if ( ! empty( $settings['live_blog_color'] ) ) {
				$output .= '--live-color :' . $settings['live_blog_color'] . ';';
			}
			if ( ! empty( $settings['bookmark_color'] ) ) {
				$output .= '--bookmark-color :' . $settings['bookmark_color'] . ';';
				$output .= '--bookmark-color-90 :' . $settings['bookmark_color'] . 'e6;';
			}
			if ( ! empty( $settings['counter_color']['rgba'] ) ) {
				$output .= '--counter-color :' . $settings['counter_color']['rgba'] . ';';
				$output .= '--counter-opacity : 1;';
			}
			if ( ! empty( $settings['hyperlink_color'] ) ) {
				$output .= '--hyperlink-color :' . $settings['hyperlink_color'] . ';';
			}
			if ( ! empty( $settings['icon_video_color'] ) ) {
				$output .= '--video-color :' . $settings['icon_video_color'] . ';';
			}
			if ( ! empty( $settings['icon_gallery_color'] ) ) {
				$output .= '--gallery-color :' . $settings['icon_gallery_color'] . ';';
			}
			if ( ! empty( $settings['icon_audio_color'] ) ) {
				$output .= '--audio-color :' . $settings['icon_audio_color'] . ';';
			}
			if ( ! empty( $settings['excerpt_color'] ) ) {
				$output .= '--excerpt-color :' . $settings['excerpt_color'] . ';';
			}
			if ( ! empty( $settings['breadcrumb_color'] ) ) {
				$output .= '--bcrumb-color :' . $settings['breadcrumb_color'] . ';';
			}
			if ( ! empty( $settings['reading_indicator_color']['from'] ) ) {
				$output .= '--indicator-bg-from :' . $settings['reading_indicator_color']['from'] . ';';
			}
			if ( ! empty( $settings['reading_indicator_color']['to'] ) ) {
				$output .= '--indicator-bg-to :' . $settings['reading_indicator_color']['to'] . ';';
			}
			if ( ! empty( $settings['reading_indicator_height'] ) ) {
				$output .= '--indicator-height :' . absint( $settings['reading_indicator_height'] ) . 'px;';
			}
			if ( ! empty( $settings['alert_bg'] ) ) {
				$output .= '--alert-bg :' . $settings['alert_bg'] . ';';
			}
			if ( ! empty( $settings['alert_color'] ) ) {
				$output .= '--alert-color :' . $settings['alert_color'] . ';';
			}
			if ( ! empty( $settings['podcast_icon_bg'] ) ) {
				$output .= '--podcast-icon-bg :' . $settings['podcast_icon_bg'] . ';';
			}
			if ( ! empty( $settings['podcast_icon_color'] ) ) {
				$output .= '--podcast-icon-color :' . $settings['podcast_icon_color'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_colors_var_data_dark( $settings = [] ) {

			$output = '';
			if ( ! empty( $settings['dark_global_color'] ) ) {
				$output .= '--g-color :' . $settings['dark_global_color'] . ';';
				$output .= '--g-color-90 :' . $settings['dark_global_color'] . 'e6;';
			}
			if ( ! empty( $settings['dark_bookmark_color'] ) ) {
				$output .= '--bookmark-color :' . $settings['dark_bookmark_color'] . ';';
				$output .= '--bookmark-color-90 :' . $settings['dark_bookmark_color'] . 'e6;';
			}
			if ( ! empty( $settings['dark_background'] ) ) {
				$output .= '--solid-white :' . $settings['dark_background'] . ';';
			}
			if ( ! empty( $settings['dark_accent_color'] ) ) {
				$output .= '--dark-accent :' . $settings['dark_accent_color'] . ';';
				$output .= '--dark-accent-90 :' . $settings['dark_accent_color'] . 'e6;';
				$output .= '--dark-accent-0 :' . $settings['dark_accent_color'] . '00;';
			}
			if ( ! empty( $settings['dark_alert_bg'] ) ) {
				$output .= '--alert-bg :' . $settings['dark_alert_bg'] . ';';
			}
			if ( ! empty( $settings['dark_alert_color'] ) ) {
				$output .= '--alert-color :' . $settings['dark_alert_color'] . ';';
			}
			if ( ! empty( $settings['dark_excerpt_color'] ) ) {
				$output .= '--excerpt-color :' . $settings['dark_excerpt_color'] . ';';
			}
			if ( ! empty( $settings['dark_breadcrumb_color'] ) ) {
				$output .= '--bcrumb-color :' . $settings['dark_breadcrumb_color'] . ';';
			}
			if ( ! empty( $settings['dark_emeta_color'] ) ) {
				$output .= '--meta-fcolor :' . $settings['dark_emeta_color'] . ';';
			}
			if ( ! empty( $settings['dark_eauthor_color'] ) ) {
				$output .= '--meta-b-fcolor :' . $settings['dark_eauthor_color'] . ';';
			}
			if ( ! empty( $settings['dark_counter_color']['rgba'] ) ) {
				$output .= '--counter-color :' . $settings['dark_counter_color']['rgba'] . ';';
			}
			if ( ! empty( $settings['dark_review_color'] ) ) {
				$output .= '--review-color :' . $settings['dark_review_color'] . ';';
			}
			if ( ! empty( $settings['dark_sponsor_color'] ) ) {
				$output .= '--sponsor-color :' . $settings['dark_sponsor_color'] . ';';
			}
			if ( ! empty( $settings['dark_live_blog_color'] ) ) {
				$output .= '--live-color :' . $settings['dark_live_blog_color'] . ';';
			}
			if ( ! empty( $settings['wc_dark_add_cart_text'] ) ) {
				$output .= '--wcac-color :' . $settings['wc_dark_add_cart_text'] . ';';
			}
			if ( ! empty( $settings['wc_dark_add_cart_color'] ) ) {
				$output .= '--wcac-bg :' . $settings['wc_dark_add_cart_color'] . ';';
				$output .= '--wcac-bg-90 :' . $settings['wc_dark_add_cart_color'] . 'e6;';
			}
			if ( ! empty( $settings['wc_dark_add_cart_bcolor'] ) ) {
				$output .= '--wcac-bcolor :' . $settings['wc_dark_add_cart_bcolor'] . ';';
			}
			if ( ! empty( $settings['wc_dark_add_cart_hover_text'] ) ) {
				$output .= '--wcac-h-color :' . $settings['wc_dark_add_cart_hover_text'] . ';';
			}
			if ( ! empty( $settings['wc_dark_add_cart_hover_color'] ) ) {
				$output .= '--wcac-h-bg :' . $settings['wc_dark_add_cart_hover_color'] . ';';
				$output .= '--wcac-h-bg-90 :' . $settings['wc_dark_add_cart_hover_color'] . 'e6;';
			}
			if ( ! empty( $settings['wc_dark_add_cart_hover_bcolor'] ) ) {
				$output .= '--wcac-h-bcolor :' . $settings['wc_dark_add_cart_hover_bcolor'] . ';';
			}
			if ( ! empty( $settings['dark_toc_bg'] ) ) {
				$output .= '--toc-bg :' . $settings['dark_toc_bg'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_colors_var( $settings = [] ) {

			$output = '';

			if ( $this->get_colors_var_data( $settings ) ) {
				$output .= ':root {';
				$output .= $this->get_colors_var_data( $settings );
				$output .= '}';
			}

			if ( $this->get_colors_var_data_dark( $settings ) ) {
				$output .= '[data-theme="dark"], .light-scheme {';
				$output .= $this->get_colors_var_data_dark( $settings );
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_1_var_data( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'nav', 'hd1_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'hd1_sub_background' );

			if ( ! empty( $settings['hd1_color'] ) ) {
				$output .= '--nav-color :' . $settings['hd1_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['hd1_color'] . '1a;';
			}
			if ( ! empty( $settings['hd1_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['hd1_sub_color'] . ';';
				$output .= '--subnav-color-10 :' . $settings['hd1_sub_color'] . '1a;';
			}
			if ( ! empty( $settings['hd1_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['hd1_color_hover'] . ';';
			}
			if ( ! empty( $settings['hd1_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['hd1_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['hd1_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['hd1_sub_color_hover'] . ';';
			}
			if ( ! empty( $settings['transparent_hd1_color'] ) ) {
				$output .= '--transparent-nav-color :' . $settings['transparent_hd1_color'] . ';';
			}
			if ( ! empty( $settings['transparent_hd1_color_hover'] ) ) {
				$output .= '--transparent-nav-color-hover :' . $settings['transparent_hd1_color_hover'] . ';';
			}
			if ( ! empty( $settings['transparent_hd1_color_hover_accent'] ) ) {
				$output .= '--transparent-nav-color-h-accent :' . $settings['transparent_hd1_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['hd1_height'] ) ) {
				$output .= '--nav-height :' . floatval( $settings['hd1_height'] ) . 'px;';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_1_var_data_dark( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'nav', 'dark_hd1_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'dark_hd1_sub_background' );

			if ( ! empty( $settings['dark_hd1_color'] ) ) {
				$output .= '--nav-color :' . $settings['dark_hd1_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['dark_hd1_color'] . '1a;';
			}
			if ( ! empty( $settings['dark_hd1_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['dark_hd1_sub_color'] . ';';
				$output .= '--subnav-color-10 :' . $settings['dark_hd1_sub_color'] . '1a;';
			}
			if ( ! empty( $settings['dark_hd1_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['dark_hd1_color_hover'] . ';';
			}
			if ( ! empty( $settings['dark_hd1_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['dark_hd1_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['dark_hd1_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['dark_hd1_sub_color_hover'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_4_var_data( $settings = [] ) {

			$output = '';

			$output .= $this->get_gradient_background_var( $settings, 'nav', 'hd4_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'hd4_sub_background' );

			if ( ! empty( $settings['hd4_color'] ) ) {
				$output .= '--nav-color :' . $settings['hd4_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['hd4_color'] . '1a;';
			}
			if ( ! empty( $settings['hd4_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['hd4_sub_color'] . ';';
				$output .= '--subnav-color-10 :' . $settings['hd4_sub_color'] . '1a;';
			}
			if ( ! empty( $settings['hd4_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['hd4_color_hover'] . ';';
			}
			if ( ! empty( $settings['hd4_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['hd4_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['hd4_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['hd4_sub_color_hover'] . ';';
			}
			if ( ! empty( $settings['hd4_height'] ) ) {
				$output .= '--nav-height :' . floatval( $settings['hd4_height'] ) . 'px;';
			}
			if ( ! empty( $settings['hd4_logo_height'] ) ) {
				$output .= '--hd4-logo-height :' . floatval( $settings['hd4_logo_height'] ) . 'px;';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_4_var_data_dark( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'nav', 'dark_hd4_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'dark_hd4_sub_background' );

			if ( ! empty( $settings['dark_hd4_color'] ) ) {
				$output .= '--nav-color :' . $settings['dark_hd4_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['dark_hd4_color'] . '1a;';
			}
			if ( ! empty( $settings['dark_hd4_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['dark_hd4_sub_color'] . ';';
				$output .= '--subnav-color-10 :' . $settings['dark_hd4_sub_color'] . '1a;';
			}
			if ( ! empty( $settings['dark_hd4_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['dark_hd4_color_hover'] . ';';
			}
			if ( ! empty( $settings['dark_hd4_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['dark_hd4_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['dark_hd4_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['dark_hd4_sub_color_hover'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_5_var_data( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'nav', 'hd5_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'hd5_sub_background' );

			if ( ! empty( $settings['hd5_color'] ) ) {
				$output .= '--nav-color :' . $settings['hd5_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['hd5_color'] . '1a;';
			}
			if ( ! empty( $settings['hd5_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['hd5_sub_color'] . ';';
				$output .= '--subnav-color-10 :' . $settings['hd5_sub_color'] . '1a;';
			}
			if ( ! empty( $settings['hd5_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['hd5_color_hover'] . ';';
			}
			if ( ! empty( $settings['hd5_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['hd5_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['hd5_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['hd5_sub_color_hover'] . ';';
			}
			if ( ! empty( $settings['hd5_height'] ) ) {
				$output .= '--nav-height :' . floatval( $settings['hd5_height'] ) . 'px;';
			}
			if ( ! empty( $settings['hd5_logo_height'] ) ) {
				$output .= '--hd5-logo-height :' . floatval( $settings['hd5_logo_height'] ) . 'px;';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_5_var_data_dark( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'nav', 'dark_hd5_background' );
			$output .= $this->get_gradient_background_var( $settings, 'subnav', 'dark_hd5_sub_background' );

			if ( ! empty( $settings['dark_hd5_color'] ) ) {
				$output .= '--nav-color :' . $settings['dark_hd5_color'] . ';';
				$output .= '--nav-color-10 :' . $settings['dark_hd5_color'] . '1a;';
			}
			if ( ! empty( $settings['dark_hd5_sub_color'] ) ) {
				$output .= '--subnav-color :' . $settings['dark_hd5_sub_color'] . ';';
			}
			if ( ! empty( $settings['dark_hd5_color_hover'] ) ) {
				$output .= '--nav-color-h :' . $settings['dark_hd5_color_hover'] . ';';
			}
			if ( ! empty( $settings['dark_hd5_color_hover_accent'] ) ) {
				$output .= '--nav-color-h-accent :' . $settings['dark_hd5_color_hover_accent'] . ';';
			}
			if ( ! empty( $settings['dark_hd5_sub_color_hover'] ) ) {
				$output .= '--subnav-color-h :' . $settings['dark_hd5_sub_color_hover'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_1_var( $settings = [] ) {

			$output = '';
			if ( $this->get_header_1_var_data( $settings ) ) {
				$output .= '.is-hd-1, .is-hd-2, .is-hd-3 {';
				$output .= $this->get_header_1_var_data( $settings );
				$output .= '}';
			}

			if ( $this->get_header_1_var_data_dark( $settings ) ) {
				$output .= '[data-theme="dark"].is-hd-1, [data-theme="dark"].is-hd-2,[data-theme="dark"].is-hd-3 {';
				$output .= $this->get_header_1_var_data_dark( $settings );
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_4_var( $settings = [] ) {

			$output = '';
			if ( $this->get_header_4_var_data( $settings ) ) {
				$output .= '.is-hd-4 {';
				$output .= $this->get_header_4_var_data( $settings );
				$output .= '}';
			}

			if ( $this->get_header_4_var_data_dark( $settings ) ) {
				$output .= '[data-theme="dark"].is-hd-4 {';
				$output .= $this->get_header_4_var_data_dark( $settings );
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_header_5_var( $settings = [] ) {

			$output = '';
			if ( $this->get_header_5_var_data( $settings ) ) {
				$output .= '.is-hd-5, body.is-hd-5:not(.sticky-on) {';
				$output .= $this->get_header_5_var_data( $settings );
				$output .= '}';
			}

			if ( $this->get_header_5_var_data_dark( $settings ) ) {
				$output .= '[data-theme="dark"].is-hd-5, [data-theme="dark"].is-hd-5:not(.sticky-on) {';
				$output .= $this->get_header_5_var_data_dark( $settings );
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_mobile_header_var_data( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'mbnav', 'mobile_background' );
			$output .= $this->get_gradient_background_var( $settings, 'submbnav', 'mobile_sub_background' );

			if ( ! empty( $settings['mobile_color'] ) ) {
				$output .= '--mbnav-color :' . $settings['mobile_color'] . ';';
			}
			if ( ! empty( $settings['mobile_sub_color'] ) ) {
				$output .= '--submbnav-color :' . $settings['mobile_sub_color'] . ';';
				$output .= '--submbnav-color-10 :' . $settings['mobile_sub_color'] . '1a;';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_mobile_header_var_data_dark( $settings = [] ) {

			$output = '';
			$output .= $this->get_gradient_background_var( $settings, 'mbnav', 'dark_mobile_background' );
			$output .= $this->get_gradient_background_var( $settings, 'submbnav', 'dark_mobile_sub_background' );

			if ( ! empty( $settings['dark_mobile_color'] ) ) {
				$output .= '--mbnav-color :' . $settings['dark_mobile_color'] . ';';
			}
			if ( ! empty( $settings['dark_mobile_sub_color'] ) ) {
				$output .= '--submbnav-color :' . $settings['dark_mobile_sub_color'] . ';';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_mobile_header_var( $settings = [] ) {

			$output = '';
			if ( $this->get_mobile_header_var_data( $settings ) ) {
				$output .= ':root {';
				$output .= $this->get_mobile_header_var_data( $settings );
				$output .= '}';
			}

			if ( $this->get_mobile_header_var_data_dark( $settings ) ) {
				$output .= '[data-theme="dark"] {';
				$output .= $this->get_mobile_header_var_data_dark( $settings );
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_global_var_data( $settings = [] ) {

			$output = '';

			if ( ! empty( $settings['single_post_content_spacing'] ) ) {
				$output .= '--cp-spacing :' . floatval( $settings['single_post_content_spacing'] ) . 'rem;';
			}
			if ( ! empty( $settings['privacy_bg_color'] ) ) {
				$output .= '--privacy-bg-color :' . $settings['privacy_bg_color'] . ';';
			}
			if ( ! empty( $settings['privacy_text_color'] ) ) {
				$output .= '--privacy-color :' . $settings['privacy_text_color'] . ';';
			}
			if ( ! empty( $settings['ad_top_bg'] ) ) {
				$output .= '--top-site-bg :' . $settings['ad_top_bg'] . ';';
			}
			if ( ! empty( $settings['custom_border'] ) ) {
				$settings['custom_border'] = absint( $settings['custom_border'] );
				$custom_small_border       = $settings['custom_border'] - 3;
				$custom_big_border         = $settings['custom_border'] + 5;

				if ( $custom_small_border < 2 ) {
					$custom_small_border = 2;
				}
				$output .= '--round-3 :' . $custom_small_border . 'px;';
				$output .= '--round-5 :' . $settings['custom_border'] . 'px;';
				$output .= '--round-7 :' . $custom_big_border . 'px;';
			} elseif ( ! empty( $settings['design_border'] ) ) {
				$output .= '--round-3 :0px;';
				$output .= '--round-5 :0px;';
				$output .= '--round-7 :0px;';
			}

			if ( ! empty( $settings['input_style'] ) ) {
				$output .= '--input-bg : transparent;';

				if ( 'gray' === $settings['input_style'] ) {
					$output .= '--input-border : 1px solid var(--flex-gray-15);';
				} else {
					$output .= '--input-border : 1px solid currentColor;';
				}
			}

			if ( ! empty( $settings['hyperlink_line'] ) ) {
				if ( empty( $settings['hyperlink_line_color'] ) ) {
					$settings['hyperlink_line_color'] = 'var(--g-color)';
				}
				if ( empty( $settings['dark_hyperlink_line_color'] ) ) {
					$settings['dark_hyperlink_line_color'] = 'var(--g-color)';
				}
			} else {
				unset( $settings['hyperlink_line_color'], $settings['dark_hyperlink_line_color'] );
			}
			if ( ! empty( $settings['hyperlink_color'] ) ) {
				$output .= '--hyperlink-color :' . $settings['hyperlink_color'] . ';';
			}
			if ( ! empty( $settings['hyperlink_line_color'] ) ) {
				$output .= '--hyperlink-line-color :' . $settings['hyperlink_line_color'] . ';';
			}
			if ( ! empty( $settings['hyperlink_style'] ) ) {
				switch ( $settings['hyperlink_style'] ) {
					case 'bold_italic' :
						$output .= '--hyperlink-weight :700;';
						$output .= '--hyperlink-style :italic;';
						break;
					case 'italic' :
						$output .= '--hyperlink-weight :400;';
						$output .= '--hyperlink-style :italic;';
						break;
					case 'bold' :
						$output .= '--hyperlink-weight :700;';
						break;
					case 'normal' :
						$output .= '--hyperlink-weight :400;';
						break;
				}
			}

			if ( ! empty( $settings['mobile_height'] ) ) {
				$output .= '--mbnav-height :' . $settings['mobile_height'] . 'px;';
			}
			if ( ! empty( $settings['mlogo_height'] ) ) {
				$output .= '--mlogo-height :' . $settings['mlogo_height'] . 'px;';
			}

			if ( ! empty( $settings['footer_logo_height'] ) ) {
				$output .= '--flogo-height :' . $settings['footer_logo_height'] . 'px;';
			}
			if ( ! empty( $settings['single_post_entry_category_size'] ) ) {
				$output .= '--single-category-fsize :' . $settings['single_post_entry_category_size'] . 'px;';
			}
			/** heading */
			if ( ! empty( $settings['heading_color'] ) ) {
				$output .= '--heading-color :' . $settings['heading_color'] . ';';
			}
			if ( ! empty( $settings['heading_sub_color'] ) ) {
				$output .= '--heading-sub-color :' . $settings['heading_sub_color'] . ';';
			}
			/** wc */
			$output .= $this->font_var( $settings, 'price', 'font_price' );
			$output .= $this->font_var( $settings, 'product', 'font_product' );

			if ( ! empty( $settings['font_sproduct_size'] ) ) {
				$output .= '--sproduct-fsize :' . absint( $settings['font_sproduct_size'] ) . 'px;';
			}
			if ( ! empty( $settings['wc_price_color'] ) ) {
				$output .= '--wc-price-color :' . $settings['wc_price_color'] . ';';
			}
			if ( ! empty( $settings['wc_sale_text'] ) ) {
				$output .= '--wc-sale-color :' . $settings['wc_sale_text'] . ';';
			}
			if ( ! empty( $settings['wc_sale_color'] ) ) {
				$output .= '--wc-sale-bg :' . $settings['wc_sale_color'] . ';';
			}
			if ( ! empty( $settings['wc_star_color'] ) ) {
				$output .= '--wc-star-color :' . $settings['wc_star_color'] . ';';
			}
			if ( isset( $settings['wc_add_cart_border'] ) && '' !== $settings['wc_add_cart_border'] ) {
				$output .= '--wcac-border :' . absint( $settings['wc_add_cart_border'] ) . 'px;';
			}
			if ( ! empty( $settings['wc_add_cart_text'] ) ) {
				$output .= '--wcac-color :' . $settings['wc_add_cart_text'] . ';';
			}
			if ( ! empty( $settings['wc_add_cart_color'] ) ) {
				$output .= '--wcac-bg :' . $settings['wc_add_cart_color'] . ';';
				$output .= '--wcac-bg-90 :' . $settings['wc_add_cart_color'] . 'e6;';
			}
			if ( ! empty( $settings['wc_add_cart_bcolor'] ) ) {
				$output .= '--wcac-bcolor :' . $settings['wc_add_cart_bcolor'] . ';';
			}
			if ( ! empty( $settings['wc_add_cart_hover_text'] ) ) {
				$output .= '--wcac-h-color :' . $settings['wc_add_cart_hover_text'] . ';';
			}
			if ( ! empty( $settings['wc_add_cart_hover_color'] ) ) {
				$output .= '--wcac-h-bg :' . $settings['wc_add_cart_hover_color'] . ';';
				$output .= '--wcac-h-bg-90 :' . $settings['wc_add_cart_hover_color'] . 'e6;';
			}
			if ( ! empty( $settings['wc_add_cart_hover_bcolor'] ) ) {
				$output .= '--wcac-h-bcolor :' . $settings['wc_add_cart_hover_bcolor'] . ';';
			}
			if ( ! empty( $settings['wc_product_gap_desktop'] ) ) {
				$output .= '--product-gut :' . absint( $settings['wc_product_gap_desktop'] ) . 'px;';
			}
			if ( ! empty( $settings['wc_product_gap_tablet'] ) ) {
				$output .= '--product-tgut :' . absint( $settings['wc_product_gap_tablet'] ) . 'px;';
			}
			if ( ! empty( $settings['wc_gallery_nav_columns'] ) ) {
				$output .= '--product-gallery-col :' . absint( $settings['wc_gallery_nav_columns'] ) . ';';
			}
			if ( ! empty( $settings['wc_gallery_nav_ratio'] ) ) {
				$output .= '--product-gallery-ratio :' . floatval( $settings['wc_gallery_nav_ratio'] / 100 ) . ';';
			}
			if ( ! empty( $settings['more_width'] ) ) {
				$output .= '--more-width :' . absint( $settings['more_width'] ) . 'px;';
			}
			if ( ! empty( $settings['slide_up_bg']['rgba'] ) ) {
				$output .= '--slideup-bg :' . $settings['slide_up_bg']['rgba'] . ';';
			}
			if ( ! empty( $settings['slide_up_icon_color'] ) ) {
				$output .= '--slideup-icon-color :' . $settings['slide_up_icon_color'] . ';';
			}
			if ( ! empty( $settings['slide_up_na_icon_color'] ) ) {
				$output .= '--slideup-icon-na-color :' . $settings['slide_up_na_icon_color'] . ';';
			}
			if ( ! empty( $settings['slide_up_na_icon_bg'] ) ) {
				$output .= '--slideup-icon-na-bg :' . $settings['slide_up_na_icon_bg'] . ';';
				$output .= '--slideup-icon-na-bg-90 :' . $settings['slide_up_na_icon_bg'] . 'e6;';
			}
			if ( ! empty( $settings['podcast_player_color'] ) ) {
				$output .= '--player-color :' . $settings['podcast_player_color'] . ';';
			}
			if ( ! empty( $settings['podcast_custom_size'] ) ) {
				$output .= '--podcast-icon-size : ' . $settings['podcast_custom_size'] . 'px;';
			}
			if ( ! empty( $settings['podcast_custom_icon_size'] ) ) {
				$output .= '--podcast-svg-size : ' . $settings['podcast_custom_icon_size'] . 'px;';
			}
			if ( ! empty( $settings['container_width'] ) ) {
				$output .= '--rb-width : ' . absint( $settings['container_width'] ) . 'px;';
				$output .= '--rb-small-width : ' . absint( $settings['container_width'] * .67 ) . 'px;';
			}
			if ( ! empty( $settings['container_width_single'] ) ) {
				$output .= '--rb-s-width : ' . absint( $settings['container_width_single'] ) . 'px;';
			}
			if ( ! empty( $settings['single_post_line_length'] ) ) {
				$content_sb_width = ! empty( $settings['single_content_width'] ) ? $settings['single_content_width'] : 760;
				$output           .= '--s-content-width : ' . absint( $content_sb_width ) . 'px;';
			}
			if ( ! empty( $settings['single_post_width_wo_sb'] ) ) {
				$content_fw_width = ! empty( $settings['single_content_fw_width'] ) ? $settings['single_content_fw_width'] : 860;
				$output           .= '--max-width-wo-sb : ' . absint( $content_fw_width ) . 'px;';
			}
			if ( ! empty( $settings['quick_access_menu_height'] ) ) {
				$output .= '--qview-height : ' . absint( $settings['quick_access_menu_height'] ) . 'px;';
			}
			if ( ! empty( $settings['alignwide_width'] ) ) {
				$output .= '--alignwide-w : ' . absint( $settings['alignwide_width'] ) . 'px;';
			}
			if ( ! empty( $settings['single_post_sidebar_padding'] ) ) {
				$output .= '--s-sidebar-padding :' . $settings['single_post_sidebar_padding'] . '%;';
			}
			if ( ! empty( $settings['single_10_ratio'] ) ) {
				$output .= '--s10-feat-ratio :' . $settings['single_10_ratio'] . '%;';
			}
			if ( ! empty( $settings['single_11_ratio'] ) ) {
				$output .= '--s11-feat-ratio :' . $settings['single_11_ratio'] . '%;';
			}
			if ( ! empty( $settings['title_hover_text_color'] ) ) {
				$output .= '--title-hcolor :' . $settings['title_hover_text_color'] . ';';
			}
			if ( ! empty( $settings['title_hover_effect_color'] ) ) {
				$output .= '--title-e-hcolor :' . $settings['title_hover_effect_color'] . ';';
			}
			if ( ! empty( $settings['toc_bg'] ) ) {
				$output .= '--toc-bg :' . $settings['toc_bg'] . ';';
			}
			if ( ! empty( $settings['default_ratio'] ) ) {
				$output .= '--feat-ratio : ' . absint( $settings['default_ratio'] ) . ';';
			}
			if ( ! empty( $settings['dark_mode_size'] ) ) {
				$output .= '--dm-size : ' . absint( $settings['dark_mode_size'] ) . 'px;';
			}
			if ( ! empty( $settings['login_popup_width'] ) ) {
				$output .= '--login-popup-w : ' . absint( $settings['login_popup_width'] ) . 'px;';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return false|string
		 */
		function get_global_var( $settings = [] ) {

			$buffer = $this->get_global_var_data( $settings );
			if ( ! empty( $buffer ) ) {
				return ':root {' . $buffer . '}';
			}

			return false;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_categories_var( $settings = [] ) {

			$output        = '';
			$tablet_buffer = '';
			$mobile_buffer = '';

			/** from theme options */
			if ( ! empty( $settings['category_highlight_color'] ) || ! empty( $settings['category_color'] ) ) {
				$output .= '.p-category {';
				if ( ! empty( $settings['category_highlight_color'] ) && strlen( $settings['category_highlight_color'] ) > 3 ) {
					$output .= '--cat-highlight : ' . $settings['category_highlight_color'] . ';';
					$output .= '--cat-highlight-90 : ' . $settings['category_highlight_color'] . 'e6;';
				}
				if ( ! empty( $settings['category_color'] ) && strlen( $settings['category_color'] ) > 3 ) {
					$output .= '--cat-fcolor : ' . $settings['category_color'] . ';';
				}
				$output .= '}';
			}

			if ( ! empty( $settings['category_dark_highlight_color'] ) || ! empty( $settings['category_dark_color'] ) ) {
				$output .= '[data-theme="dark"] .p-category, .light-scheme .p-category, [data-theme="dark"] .meta-category, .light-scheme .meta-category {';
				if ( ! empty( $settings['category_dark_highlight_color'] ) && strlen( $settings['category_dark_highlight_color'] ) > 3 ) {
					$output .= '--cat-highlight : ' . $settings['category_dark_highlight_color'] . ';';
					$output .= '--cat-highlight-90 : ' . $settings['category_dark_highlight_color'] . 'e6;';
				}
				if ( ! empty( $settings['category_dark_color'] ) && strlen( $settings['category_dark_color'] ) > 3 ) {
					$output .= '--cat-fcolor : ' . $settings['category_dark_color'] . ';';
				}
				$output .= '}';
			}

			if ( ! empty( $settings['category_blog_heading_size'] ) ) {
				$output .= 'body.category .blog-content {';
				$output .= '--heading-size-desktop : ' . floatval( $settings['category_blog_heading_size'] ) . 'px;';
				$output .= '}';
			}

			/** individuals */
			$terms    = get_terms( [ 'hide_empty' => true ] );
			$term_ids = wp_list_pluck( $terms, 'term_id' );

			foreach ( $term_ids as $id ) {
				$values = rb_get_term_meta( 'foxiz_category_meta', $id );
				if ( empty( $values ) || ! is_array( $values ) ) {
					continue;
				}

				$highlight         = '';
				$dark_highlight    = '';
				$color             = '';
				$dark_color        = '';
				$title_size        = '';
				$title_size_mobile = '';
				$title_size_tablet = '';
				$ratio             = '';

				if ( ! empty( $values['highlight_color'] ) && strlen( $values['highlight_color'] ) > 3 ) {
					$highlight = $values['highlight_color'];
				}
				if ( ! empty( $values['dark_highlight_color'] ) && strlen( $values['dark_highlight_color'] ) > 3 ) {
					$dark_highlight = $values['dark_highlight_color'];
				}
				if ( ! empty( $values['color'] ) && strlen( $values['color'] ) > 3 ) {
					$color = $values['color'];
				}
				if ( ! empty( $values['dark_color'] ) && strlen( $values['dark_color'] ) > 3 ) {
					$dark_color = $values['dark_color'];
				}
				if ( ! empty( $values['title_size'] ) ) {
					$title_size = $values['title_size'];
				} elseif ( ! empty( $settings['category_title_size'] ) ) {
					$title_size = $settings['category_title_size'];
				}

				if ( ! empty( $values['title_size_mobile'] ) ) {
					$title_size_mobile = $values['title_size_mobile'];
				} elseif ( ! empty( $settings['category_title_size_mobile'] ) ) {
					$title_size_mobile = $settings['category_title_size_mobile'];
				}

				if ( ! empty( $values['title_size_tablet'] ) ) {
					$title_size_tablet = $values['title_size_tablet'];
				} elseif ( ! empty( $settings['category_title_size_table'] ) ) {
					$title_size_tablet = $settings['category_title_size_table'];
				}

				if ( ! empty( $values['display_ratio'] ) ) {
					$ratio = $values['display_ratio'];
				} elseif ( ! empty( $settings['category_display_ratio'] ) ) {
					$ratio = $settings['category_display_ratio'];
				}

				/** category color */
				if ( ! empty( $highlight ) || ( ! empty( $color ) && strlen( $color ) > 3 ) ) {
					$output .= '.p-category.category-id-' . $id . ', .meta-category .category-' . $id . ' {';
					if ( ! empty( $highlight ) ) {
						$output .= '--cat-highlight : ' . $highlight . ';';
						$output .= '--cat-highlight-90 : ' . $highlight . 'e6;';
					}
					if ( ! empty( $color ) && strlen( $color ) > 3 ) {
						$output .= '--cat-fcolor : ' . $color . ';';
					}
					$output .= '}';
				}

				if ( ! empty( $dark_highlight ) || ( ! empty( $dark_color ) && strlen( $dark_color ) > 3 ) ) {
					$output .= '[data-theme="dark"] .meta-category .category-' . $id . ',';
					$output .= '.light-scheme .meta-category .category-' . $id . ',';
					$output .= '[data-theme="dark"] .p-category.category-id-' . $id . ',';
					$output .= '.light-scheme .p-category.category-id-' . $id . '{';
					if ( ! empty( $dark_highlight ) && strlen( $dark_highlight ) > 3 ) {
						$output .= '--cat-highlight : ' . $dark_highlight . ';';
						$output .= '--cat-highlight-90 : ' . $dark_highlight . 'e6;';
					}
					if ( ! empty( $dark_color ) && strlen( $dark_color ) > 3 ) {
						$output .= '--cat-fcolor : ' . $dark_color . ';';
					}
					$output .= '}';
				}

				/** add heading color */
				if ( ! empty( $highlight ) && strlen( $highlight ) > 3 ) {
					$output .= '.category-' . $id . ' .blog-content {';
					$output .= '--heading-sub-color : ' . $highlight . ';';
					$output .= '}';
				}

				if ( ! empty( $highlight ) && strlen( $highlight ) > 3 ) {
					$output .= '.is-cbox-' . $id . ' {';
					$output .= '--cbox-bg : ' . $highlight . 'e6;';
					$output .= '}';
				}

				if ( ! empty( $dark_highlight ) && strlen( $dark_highlight ) > 3 ) {
					$output .= '[data-theme="dark"] .is-cbox-' . $id . ' {';
					$output .= '--cbox-bg : ' . $dark_highlight . 'e6;';
					$output .= '}';
				}

				/** featured */
				if ( ! empty( $ratio ) ) {
					$output .= 'body.category-' . $id . ' .blog-content .p-featured {';
					$output .= 'padding-bottom : ' . floatval( $ratio ) . '%;';
					$output .= '}';
				}

				if ( ! empty( $values['blog_heading_size'] ) ) {
					$output .= 'body.category-' . $id . ' .blog-content {';
					$output .= '--heading-size-desktop : ' . floatval( $values['blog_heading_size'] ) . 'px;';
					$output .= '}';
				}

				/** category title font size */
				if ( ! empty( $title_size ) ) {
					$output .= 'body.category-' . $id . ' .blog-content {';
					$output .= '--title-size: ' . floatval( $title_size ) . 'px;';
					$output .= '}';
				}

				if ( ! empty( $title_size_tablet ) ) {
					$tablet_buffer .= 'body.category-' . $id . ' .blog-content {';
					$tablet_buffer .= '--title-size: ' . floatval( $title_size_tablet ) . 'px;}';
				}

				if ( ! empty( $title_size_mobile ) ) {
					$mobile_buffer .= 'body.category-' . $id . ' .blog-content {';
					$mobile_buffer .= '--title-size: ' . floatval( $title_size_mobile ) . 'px;}';
				}
			}

			if ( ! empty( $mobile_buffer ) ) {
				$output .= '@media (max-width: 767px){' . $mobile_buffer . '}';
			}

			if ( ! empty( $tablet_buffer ) ) {
				$output .= '@media (max-width: 1024px){' . $tablet_buffer . '}';
			}

			return $output;
		}

		/**
		 * @param $settings
		 *
		 * @return string
		 */
		function get_archive_var( $settings ) {

			$output        = '';
			$tablet_buffer = '';
			$mobile_buffer = '';

			if ( ! empty( $settings['archive_title_size'] ) ) {
				$output .= '.archive .blog-content {';
				$output .= '--title-size: ' . floatval( $settings['archive_title_size'] ) . 'px;';
				$output .= '}';
			}

			if ( ! empty( $settings['archive_title_size_tablet'] ) ) {
				$tablet_buffer .= '.archive .blog-content {';
				$tablet_buffer .= '--title-size: ' . floatval( $settings['archive_title_size_tablet'] ) . 'px;';
				$tablet_buffer .= '}';
			}
			if ( ! empty( $settings['archive_title_size_mobile'] ) ) {
				$mobile_buffer .= '.archive .blog-content {';
				$mobile_buffer .= '--title-size: ' . floatval( $settings['archive_title_size_mobile'] ) . 'px;';
				$mobile_buffer .= '}';
			}

			/** search page */
			if ( ! empty( $settings['search_title_size'] ) ) {
				$output .= 'body.search .blog-content {';
				$output .= '--title-size: ' . floatval( $settings['search_title_size'] ) . 'px;';
				$output .= '}';
			}
			if ( ! empty( $settings['search_title_size_tablet'] ) ) {
				$tablet_buffer .= 'body.search .blog-content {';
				$tablet_buffer .= '--title-size: ' . floatval( $settings['search_title_size_tablet'] ) . 'px;';
				$tablet_buffer .= '}';
			}

			if ( ! empty( $settings['search_title_size_mobile'] ) ) {
				$mobile_buffer .= 'body.search .blog-content {';
				$mobile_buffer .= '--title-size: ' . floatval( $settings['search_title_size_mobile'] ) . 'px;';
				$mobile_buffer .= '}';
			}

			/** blog */
			if ( ! empty( $settings['blog_title_size'] ) ) {
				$output .= 'body.blog .blog-content {';
				$output .= '--title-size: ' . floatval( $settings['blog_title_size'] ) . 'px;';
				$output .= '}';
			}
			if ( ! empty( $settings['blog_title_size_tablet'] ) ) {
				$tablet_buffer .= 'body.blog .blog-content {';
				$tablet_buffer .= '--title-size: ' . floatval( $settings['blog_title_size_tablet'] ) . 'px;';
				$tablet_buffer .= '}';
			}
			if ( ! empty( $settings['blog_title_size_mobile'] ) ) {
				$mobile_buffer .= 'body.blog .blog-content {';
				$mobile_buffer .= '--title-size: ' . floatval( $settings['blog_title_size_mobile'] ) . 'px;';
				$mobile_buffer .= '}';
			}

			/** saved section */
			if ( ! empty( $settings['saved_title_size'] ) ) {
				$output .= '.saved-section .blog-content {';
				$output .= '--title-size: ' . floatval( $settings['saved_title_size'] ) . 'px;';
				$output .= '}';
			}

			if ( ! empty( $settings['saved_title_size_tablet'] ) ) {
				$tablet_buffer .= '.saved-section .blog-content {';
				$tablet_buffer .= '--title-size: ' . floatval( $settings['saved_title_size_tablet'] ) . 'px;';
				$tablet_buffer .= '}';
			}
			if ( ! empty( $settings['saved_title_size_mobile'] ) ) {
				$mobile_buffer .= '.saved-section .blog-content {';
				$mobile_buffer .= '--title-size: ' . floatval( $settings['saved_title_size_mobile'] ) . 'px;';
				$mobile_buffer .= '}';
			}

			if ( ! empty( $settings['archive_display_ratio'] ) ) {
				$output .= 'body.archive:not(.author):not(.category) .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['archive_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $settings['search_display_ratio'] ) ) {
				$output .= 'body.search .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['search_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $settings['author_display_ratio'] ) ) {
				$output .= 'body.author .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['author_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $settings['blog_display_ratio'] ) ) {
				$output .= 'body.blog .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['blog_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $settings['saved_display_ratio'] ) ) {
				$output .= '.saved-section .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['saved_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $settings['recommended_display_ratio'] ) ) {
				$output .= '.rec-section .blog-content .p-featured {';
				$output .= 'padding-bottom : ' . floatval( $settings['recommended_display_ratio'] ) . '%;';
				$output .= '}';
			}

			if ( ! empty( $tablet_buffer ) ) {
				$output .= '@media (max-width: 1024px){' . $tablet_buffer . '}';
			}
			if ( ! empty( $mobile_buffer ) ) {
				$output .= '@media (max-width: 767px){' . $mobile_buffer . '}';
			}

			return $output;
		}

		/**
		 * @param $settings
		 *
		 * @return string
		 */
		function get_heading_var( $settings ) {

			$output = '';
			if ( ! empty( $settings['single_post_related_blog_heading_size'] ) ) {
				$output .= '.single-related {';
				$output .= '--heading-size-desktop :' . floatval( $settings['single_post_related_blog_heading_size'] ) . 'px;';
				$output .= '}';
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_global_var_dark_data( $settings = [] ) {

			$output = '';
			/** privacy box */
			if ( ! empty( $settings['dark_privacy_bg_color'] ) ) {
				$output .= '--privacy-bg-color :' . $settings['dark_privacy_bg_color'] . ';';
			}
			if ( ! empty( $settings['dark_privacy_text_color'] ) ) {
				$output .= '--privacy-color :' . $settings['dark_privacy_text_color'] . ';';
			}
			/** heading color */
			if ( ! empty( $settings['dark_heading_color'] ) ) {
				$output .= '--heading-color :' . $settings['dark_heading_color'] . ';';
			}
			if ( ! empty( $settings['dark_heading_sub_color'] ) ) {
				$output .= '--heading-sub-color :' . $settings['dark_heading_sub_color'] . ';';
			}
			if ( ! empty( $settings['ad_top_dark_bg'] ) ) {
				$output .= '--top-site-bg :' . $settings['ad_top_dark_bg'] . ';';
			}
			if ( ! empty( $settings['dark_hyperlink_color'] ) ) {
				$output .= '--hyperlink-color :' . $settings['dark_hyperlink_color'] . ';';
			}
			if ( ! empty( $settings['dark_hyperlink_line_color'] ) ) {
				$output .= '--hyperlink-line-color :' . $settings['dark_hyperlink_line_color'] . ';';
			}
			if ( ! empty( $settings['wc_dark_price_color'] ) ) {
				$output .= '--wc-price-color :' . $settings['wc_dark_price_color'] . ';';
			}
			if ( ! empty( $settings['wc_dark_star_color'] ) ) {
				$output .= '--wc-star-color :' . $settings['wc_dark_star_color'] . ';';
			}
			if ( ! empty( $settings['dark_slide_up_bg']['rgba'] ) ) {
				$output .= '--slideup-bg :' . $settings['dark_slide_up_bg']['rgba'] . ';';
			}
			if ( ! empty( $settings['dark_slide_up_icon_color'] ) ) {
				$output .= '--slideup-icon-color :' . $settings['dark_slide_up_icon_color'] . ';';
			}
			if ( ! empty( $settings['dark_podcast_player_color'] ) ) {
				$output .= '--player-color :' . $settings['dark_podcast_player_color'] . ';';
			}
			if ( ! empty( $settings['dark_title_hover_text_color'] ) ) {
				$output .= '--title-hcolor :' . $settings['dark_title_hover_text_color'] . ';';
			}
			if ( ! empty( $settings['dark_title_hover_effect_color'] ) ) {
				$output .= '--title-e-hcolor :' . $settings['dark_title_hover_effect_color'] . ';';
			}

			return $output;
		}

		/**
		 * @param $settings
		 *
		 * @return string
		 */
		function get_global_var_dark( $settings = [] ) {

			$buffer = $this->get_global_var_dark_data( $settings );
			if ( ! empty( $buffer ) ) {
				return '[data-theme="dark"], .light-scheme {' . $buffer . ' }';
			}

			return false;
		}

		/**
		 * @param       $settings
		 * @param       $config_id
		 * @param false $with_font_size
		 *
		 * @return false|string
		 */
		function font_css( $settings, $config_id, $with_font_size = false ) {

			if ( empty( $settings[ $config_id ] ) || ! is_array( $settings[ $config_id ] ) ) {
				return false;
			}

			$setting = $settings[ $config_id ];

			if ( isset( $setting['google'] ) ) {
				unset ( $setting['google'] );
			}

			if ( isset( $setting['subsets'] ) ) {
				unset ( $setting['subsets'] );
			}
			if ( isset( $setting['font-options'] ) ) {
				unset ( $setting['font-options'] );
			}

			if ( ! empty( $setting['font-size'] ) ) {
				if ( ! empty( $setting['line-height'] ) ) {
					$setting['line-height'] = number_format( absint( $setting['line-height'] ) / absint( $setting['font-size'] ), 2 );
				}

				if ( ! $with_font_size ) {
					unset( $setting['font-size'] );
				}
			}

			$output = '';

			if ( ! empty( $setting['font-backup'] ) && ! empty( $setting['font-family'] ) ) {
				$setting['font-family'] = $setting['font-family'] . ', ' . $setting['font-backup'];
				unset ( $setting['font-backup'] );
			}

			foreach ( $setting as $key => $val ) {
				if ( '' !== trim( $val ) ) {
					$output .= $key . ':' . $val . ';';
				}
			}

			return $output;
		}

		/**
		 * @param array $settings
		 *
		 * @return string
		 */
		function get_dynamic_style( $settings = [] ) {

			$output         = '';
			$mobile_buffer  = '';
			$tablet_buffer  = '';
			$desktop_buffer = '';

			/** optimize css size for AMP */
			$font_readmore_css          = $this->font_css( $settings, 'font_readmore' );
			$font_mobile_menu_css       = $this->font_css( $settings, 'font_mobile_menu', true );
			$font_sub_mobile_menu_css   = $this->font_css( $settings, 'font_mobile_sub_menu', true );
			$font_quick_access_menu_css = $this->font_css( $settings, 'font_quick_access_menu', true );

			if ( ! empty( $font_readmore_css ) ) {
				$output .= '.p-readmore { ' . $font_readmore_css . '}';
			}
			if ( ! empty( $font_mobile_menu_css ) ) {
				$output .= '.mobile-menu > li > a  { ' . $font_mobile_menu_css . '}';
			}
			if ( ! empty( $font_sub_mobile_menu_css ) ) {
				$output .= '.mobile-menu .sub-menu a, .logged-mobile-menu a { ' . $font_sub_mobile_menu_css . '}';
			}
			if ( ! empty( $font_quick_access_menu_css ) ) {
				$output .= '.mobile-qview a { ' . $font_quick_access_menu_css . '}';
			}

			$search_header_bg      = $this->css_background( $settings, 'search_header_background' );
			$dark_search_header_bg = $this->css_background( $settings, 'dark_search_header_background' );
			if ( ! empty( $search_header_bg ) ) {
				$output .= '.search-header:before { ' . $search_header_bg . '}';
			}
			if ( ! empty( $dark_search_header_bg ) ) {
				$output .= '[data-theme="dark"] .search-header:before { ' . $dark_search_header_bg . '}';
			}

			$footer_bg      = $this->css_background( $settings, 'footer_background' );
			$dark_footer_bg = $this->css_background( $settings, 'dark_footer_background' );
			if ( ! empty( $footer_bg ) ) {
				$output .= '.footer-has-bg { ' . $footer_bg . '}';
			}
			if ( ! empty( $dark_footer_bg ) ) {
				$output .= '[data-theme="dark"] .footer-has-bg { ' . $dark_footer_bg . '}';
			}
			if ( ! empty( $settings['ad_top_width'] ) ) {
				$output .= '.top-site-ad .ad-image { max-width: ' . intval( $settings['ad_top_width'] ) . 'px; }';
			}
			if ( ! empty( $settings['ad_single_width'] ) ) {
				$output .= '.ad_single_index .ad-image { max-width: ' . intval( $settings['ad_single_width'] ) . 'px; }';
			}
			if ( ! empty( $settings['ad_single_2_width'] ) ) {
				$output .= '.ad_single_2_index .ad-image { max-width: ' . intval( $settings['ad_single_2_width'] ) . 'px; }';
			}
			if ( ! empty( $settings['ad_single_3_width'] ) ) {
				$output .= '.ad_single_3_index .ad-image { max-width: ' . intval( $settings['ad_single_3_width'] ) . 'px; }';
			}
			if ( ! empty( $settings['ad_top_dark_bg'] ) ) {
				$output .= '.yes-hd-transparent .top-site-ad {--top-site-bg :' . $settings['ad_top_dark_bg'] . ';}';
			}
			if ( ! empty( $settings['grid_box_1_box_color'] ) ) {
				$output .= '.block-grid-box-1 {--default-box :' . $settings['grid_box_1_box_color'] . ';}';
			}
			if ( ! empty( $settings['grid_box_1_dark_box_color'] ) ) {
				$output .= '.block-grid-box-1 {--dark-box-color :' . $settings['grid_box_1_dark_box_color'] . ';}';
			}
			if ( ! empty( $settings['grid_box_2_box_color'] ) ) {
				$output .= '.block-grid-box-2 {--default-box :' . $settings['grid_box_2_box_color'] . ';}';
			}
			if ( ! empty( $settings['grid_box_2_dark_box_color'] ) ) {
				$output .= '.block-grid-box-2 {--default-dark-box :' . $settings['grid_box_2_dark_box_color'] . ';}';
			}
			if ( ! empty( $settings['list_box_1_box_color'] ) ) {
				$output .= '.block-list-box-1 {--default-box :' . $settings['list_box_1_box_color'] . ';}';
			}
			if ( ! empty( $settings['list_box_1_dark_box_color'] ) ) {
				$output .= '.block-list-box-1 {--default-dark-box :' . $settings['list_box_1_dark_box_color'] . ';}';
			}
			if ( ! empty( $settings['list_box_2_box_color'] ) ) {
				$output .= '.block-list-box-1 {--default-box :' . $settings['list_box_2_box_color'] . ';}';
			}
			if ( ! empty( $settings['list_box_2_dark_box_color'] ) ) {
				$output .= '.block-list-box-2 {--default-dark-box :' . $settings['list_box_2_dark_box_color'] . ';}';
			}
			if ( ! empty( $settings['exclusive_label'] ) ) {
				$output .= '.entry-title.is-p-protected a:before {';
				$output .= 'content: "' . esc_html( $settings['exclusive_label'] ) . '";  display: inline-block;';
				$output .= '}';
			}
			if ( ! empty( $settings['footer_copyright_size'] ) ) {
				$output .= '.copyright-menu a { font-size:' . $settings['footer_copyright_size'] . 'px; }';
			}
			if ( ! empty( $settings['footer_copyright_text_size'] ) ) {
				$output .= '.copyright-inner .copyright { font-size:' . $settings['footer_copyright_text_size'] . 'px; }';
			}
			if ( ! empty( $settings['footer_columns_size'] ) ) {
				$output .= '.footer-col .widget_nav_menu a > span { font-size:' . $settings['footer_columns_size'] . 'px; }';
			}
			if ( ! empty( $settings['light_switcher_color'] ) ) {
				$output .= '.svg-mode-light { color:' . $settings['light_switcher_color'] . '; }';
			}
			if ( ! empty( $settings['light_switcher_bg'] ) ) {
				$output .= '.mode-icon-default { background:' . $settings['light_switcher_bg'] . '; }';
			}
			if ( ! empty( $settings['light_switcher_slide'] ) ) {
				$output .= 'body:not([data-theme="dark"]) .dark-mode-slide { background:' . $settings['light_switcher_slide'] . '; }';
			}
			if ( ! empty( $settings['dark_switcher_color'] ) ) {
				$output .= '.svg-mode-dark { color:' . $settings['dark_switcher_color'] . '; }';
			}
			if ( ! empty( $settings['dark_switcher_bg'] ) ) {
				$output .= '.mode-icon-dark { background-color:' . $settings['dark_switcher_bg'] . '; }';
			}
			if ( ! empty( $settings['dark_switcher_slide'] ) ) {
				$output .= '[data-theme="dark"] .dark-mode-slide { background:' . $settings['dark_switcher_slide'] . '; }';
			}
			if ( ! empty( $settings['amp_footer_logo_height'] ) ) {
				$output .= '.amp-footer .footer-logo { height :' . $settings['amp_footer_logo_height'] . 'px; }';
			}
			if ( ! empty( $settings['remove_amp_switcher'] ) ) {
				$output .= '#amp-mobile-version-switcher { display: none; }';
			}
			if ( ! empty( $settings['single_6_ratio'] ) ) {
				$output .= '.single .featured-vertical { padding-bottom: ' . absint( $settings['single_6_ratio'] ) . '%; }';
			}

			if ( ! empty( $settings['header_search_custom_icon']['url'] ) ) {
				if ( foxiz_is_svg( $settings['header_search_custom_icon']['url'] ) ) {
					$output .= '.search-icon-svg { -webkit-mask-image: url(' . esc_url( $settings['header_search_custom_icon']['url'] ) . ');';
					$output .= 'mask-image: url(' . esc_url( $settings['header_search_custom_icon']['url'] ) . ');';
					$output .= ' }';
				} else {
					/** image fallback */
					$output .= '.search-icon-svg { background-image: url(' . esc_url( $settings['header_search_custom_icon']['url'] ) . ');';
					$output .= ' background-size: cover; background-color: transparent;';
					$output .= ' }';
				}
			}

			if ( ! empty( $settings['cart_custom_icon']['url'] ) ) {
				if ( foxiz_is_svg( $settings['cart_custom_icon']['url'] ) ) {
					$output .= '.cart-icon-svg { -webkit-mask-image: url(' . esc_url( $settings['cart_custom_icon']['url'] ) . ');';
					$output .= 'mask-image: url(' . esc_url( $settings['cart_custom_icon']['url'] ) . ');';
					$output .= ' }';
				} else {
					$output .= '.cart-icon-svg { background-image: url(' . esc_url( $settings['cart_custom_icon']['url'] ) . ');';
					$output .= ' background-size: cover; background-color: transparent;';
					$output .= ' }';
				}
			}

			if ( ! empty( $settings['notification_custom_icon']['url'] ) ) {
				if ( foxiz_is_svg( $settings['notification_custom_icon']['url'] ) ) {
					$output .= '.notification-icon-svg { -webkit-mask-image: url(' . esc_url( $settings['notification_custom_icon']['url'] ) . ');';
					$output .= 'mask-image: url(' . esc_url( $settings['notification_custom_icon']['url'] ) . ');';
					$output .= ' }';
				} else {
					$output .= '.notification-icon-svg { background-image: url(' . esc_url( $settings['notification_custom_icon']['url'] ) . ');';
					$output .= ' background-size: cover; background-color: transparent;';
					$output .= ' }';
				}
			}

			if ( ! empty( $settings['login_custom_icon']['url'] ) ) {
				if ( foxiz_is_svg( $settings['login_custom_icon']['url'] ) ) {
					$output .= '.login-icon-svg { -webkit-mask-image: url(' . esc_url( $settings['login_custom_icon']['url'] ) . ');';
					$output .= 'mask-image: url(' . esc_url( $settings['login_custom_icon']['url'] ) . ');';
					$output .= ' }';
				} else {
					$output .= '.login-icon-svg { background-image: url(' . esc_url( $settings['login_custom_icon']['url'] ) . ');';
					$output .= ' background-size: cover; background-color: transparent;';
					$output .= ' }';
				}
			}

			if ( ! empty( $settings['mobile_sub_col'] ) ) {
				$output .= '.mobile-menu .sub-menu > * {flex: 0 0 100%}';
			}

			/** menus */
			$menus = wp_get_nav_menus();
			if ( ! empty( $menus ) ) {
				foreach ( $menus as $menu ) {
					$menu_id   = $menu->term_id;
					$nav_items = wp_get_nav_menu_items( $menu_id );
					$nav_items = wp_list_pluck( $nav_items, 'ID' );

					if ( is_array( $nav_items ) ) {
						foreach ( $nav_items as $item_id ) {
							$item_data = rb_get_nav_item_meta( 'foxiz_menu_meta', $item_id, $menu_id );

							if ( ! empty( $item_data['sub_label_color'] ) ) {
								$output .= '.menu-item-' . esc_attr( $item_id ) . ' span.menu-sub-title { color: ' . esc_attr( $item_data['sub_label_color'] ) . ' !important;}';
							}
							if ( ! empty( $item_data['sub_label_bg'] ) ) {
								$output .= '.menu-item-' . esc_attr( $item_id ) . ' span.menu-sub-title { background-color: ' . esc_attr( $item_data['sub_label_bg'] ) . ';}';
							}
							if ( ! empty( $item_data['sub_label_dark_color'] ) ) {
								$output .= '[data-theme="dark"] .menu-item-' . esc_attr( $item_id ) . ' span.menu-sub-title { color: ' . esc_attr( $item_data['sub_label_dark_color'] ) . ' !important;}';
							}
							if ( ! empty( $item_data['sub_label_dark_bg'] ) ) {
								$output .= '[data-theme="dark"] .menu-item-' . esc_attr( $item_id ) . ' span.menu-sub-title { background-color: ' . esc_attr( $item_data['sub_label_dark_bg'] ) . ';}';
							}
						}
					}
				}
			}

			if ( ! empty( $settings['single_post_sidebar_width'] ) ) {
				$sidebar_width = floatval( $settings['single_post_sidebar_width'] );
				if ( $sidebar_width > 0 && $sidebar_width < 100 ) {
					$desktop_buffer .= '.grid-container > .sidebar-wrap { flex: 0 0 ' . $sidebar_width . '%;  width: ' . $sidebar_width . '%; } ';
					$desktop_buffer .= '.grid-container > .s-ct { flex: 0 0 ' . ( 99.9 - $sidebar_width ) . '%;  width: ' . ( 99.9 - $sidebar_width ) . '%; } ';
				}
			}

			if ( ! empty( $settings['wc_shop_sidebar_width'] ) ) {
				$sidebar_width = floatval( $settings['wc_shop_sidebar_width'] );
				if ( $sidebar_width > 0 && $sidebar_width < 100 ) {
					$desktop_buffer .= '.shop-page .grid-container > *:nth-child(2) { flex: 0 0 ' . $sidebar_width . '%;  width: ' . $sidebar_width . '%; } ';
					$desktop_buffer .= '.shop-page:not(.without-sidebar) .grid-container > *:nth-child(1) { flex: 0 0 ' . ( 99.9 - $sidebar_width ) . '%;  width: ' . ( 99.9 - $sidebar_width ) . '%; } ';
				}
			}

			if ( ! empty( $settings['ajax_next_button_offset'] ) ) {
				$continue_offset = absint( $settings['ajax_next_button_offset'] );
				for ( $i = 1; $i <= $continue_offset; $i ++ ) {
					$output .= '#single-post-infinite > .single-post-outer:nth-child(' . ( $i + 1 ) . ') .continue-reading { display: none}';
					$output .= '#single-post-infinite > .single-post-outer:nth-child(' . ( $i + 1 ) . ') .s-ct { height: auto; max-height: none }';
				}
			}

			/** podcast post type */
			if ( ! empty( $settings['podcast_custom_icon']['url'] ) ) {
				if ( foxiz_is_svg( $settings['podcast_custom_icon']['url'] ) ) {
					$output .= '.podcast-icon-svg { -webkit-mask-image: url(' . esc_url( $settings['podcast_custom_icon']['url'] ) . ');';
					$output .= 'mask-image: url(' . esc_url( $settings['podcast_custom_icon']['url'] ) . ');';
					$output .= ' }';
				} else {
					$output .= '.podcast-icon-svg { background-image: url(' . esc_url( $settings['podcast_custom_icon']['url'] ) . ');';
					$output .= ' background-size: cover; background-color: transparent;';
					$output .= ' }';
				}
			}

			if ( ! empty( $settings['wc_box_color'] ) || ! empty( $settings['wc_dark_box_color'] ) ) {
				$output .= '.yes-ploop { ';
				if ( ! empty( $settings['wc_box_color'] ) ) {
					$output .= '--box-color: ' . $settings['wc_box_color'] . ';';
				}
				if ( ! empty( $settings['wc_dark_box_color'] ) ) {
					$output .= '--dark-box-color: ' . $settings['wc_dark_box_color'] . ';';;
				}
				$output .= ' }';
			}

			if ( ! empty( $settings['wc_global_color'] ) ) {
				$output .= '.woocommerce { ';
				$output .= '--g-color :' . $settings['wc_global_color'] . ';';
				$output .= '--g-color-90 :' . $settings['wc_global_color'] . 'e6;';
				$output .= ' }';
			}

			if ( ! empty( $settings['input_style'] ) ) {
				if ( empty( $settings['input_focus_color'] ) ) {
					$settings['input_focus_color'] = 'currentColor';
				}
				$output .= 'input[type="text"]:focus, input[type="tel"]:focus , input[type="password"]:focus,';
				$output .= 'input[type="email"]:focus, input[type="url"]:focus, input[type="search"]:focus, input[type="number"]:focus,';
				$output .= 'textarea:focus { border-color: ' . $settings['input_focus_color'] . '; }';
			}

			if ( ! empty( $settings['live_blog_meta'] ) ) {
				if ( empty( $settings['live_label'] ) ) {
					$settings['live_label'] = 'Live:';
				}
				if ( 'label' === $settings['live_blog_meta'] || 'all' === $settings['live_blog_meta'] ) {
					$output .= '.live-tag:after { content: "' . esc_attr( $settings['live_label'] . ' ' ) . '"}';
				}
			}

			if ( ! empty( $settings['page_content_width'] ) ) {
				$output .= '.single-page { --rb-small-width : ' . absint( $settings['page_content_width'] ) . 'px; }';
			}

			if ( ! empty( $settings['header_search_custom_icon_size'] ) ) {
				$size          = floatval( $settings['header_search_custom_icon_size'] );
				$output        .= '.search-icon-svg, .rbi-search.wnav-icon { font-size: ' . $size . 'px;}';
				$mobile_buffer .= '.search-icon-svg, .rbi-search.wnav-icon { font-size: ' . ( 0.85 * $size ) . 'px;}';
			}
			if ( ! empty( $settings['cart_custom_icon_size'] ) ) {
				$size          = floatval( $settings['cart_custom_icon_size'] );
				$output        .= '.cart-icon-svg, .wnav-icon.rbi-cart { font-size: ' . $size . 'px;}';
				$mobile_buffer .= '.cart-icon-svg, .wnav-icon.rbi-cart { font-size: ' . ( 0.85 * $size ) . 'px;}';
			}
			if ( ! empty( $settings['login_custom_icon_size'] ) ) {
				$size          = floatval( $settings['login_custom_icon_size'] );
				$output        .= '.login-icon-svg, .rbi-user.wnav-icon { font-size: ' . $size . 'px;}';
				$mobile_buffer .= '.login-icon-svg, .rbi-user.wnav-icon { font-size: ' . ( 0.85 * $size ) . 'px;}';
			}
			if ( ! empty( $settings['notification_custom_icon_size'] ) ) {
				$size          = floatval( $settings['notification_custom_icon_size'] );
				$output        .= '.notification-icon-svg, .rbi-notification.wnav-icon { font-size: ' . $size . 'px;}';
				$mobile_buffer .= '.notification-icon-svg, .rbi-notification.wnav-icon { font-size: ' . ( 0.85 * $size ) . 'px;}';
			}
			if ( ! empty( $settings['social_custom_icon_size'] ) ) {
				$size          = floatval( $settings['social_custom_icon_size'] );
				$output        .= '.header-social-list { font-size: ' . $size . 'px;}';
				$mobile_buffer .= '.header-social-list { font-size: ' . ( 0.85 * $size ) . 'px;}';
			}

			if ( ! empty( $settings['wc_product_margin_desktop'] ) ) {
				$output .= '.products:not(.block-inner) .loop-product { margin-bottom: ' . absint( $settings['wc_product_margin_desktop'] ) . 'px;}';
			}
			if ( ! empty( $settings['wc_product_margin_tablet'] ) ) {
				$tablet_buffer .= '.products:not(.block-inner) .loop-product { margin-bottom: ' . absint( $settings['wc_product_margin_tablet'] ) . 'px;}';
			}
			if ( ! empty( $settings['wc_product_margin_mobile'] ) ) {
				$mobile_buffer .= '.products:not(.block-inner) .loop-product { margin-bottom: ' . absint( $settings['wc_product_margin_mobile'] ) . 'px;}';
			}
			if ( ! empty( $desktop_buffer ) ) {
				$output .= '@media (min-width: 1025px) { ' . $desktop_buffer . '}';
			}
			if ( ! empty( $tablet_buffer ) ) {
				$output .= '@media (max-width: 1024px) { ' . $tablet_buffer . ' }';
			}
			if ( ! empty( $mobile_buffer ) ) {
				$output .= '@media (max-width: 767px) { ' . $mobile_buffer . ' }';
			}

			return $output;
		}

		/**
		 * @param $settings
		 *
		 * @return string|string[]|null
		 */
		function output( $settings ) {

			$output = $this->get_typography_var( $settings );
			$output .= $this->get_tablet_var( $settings );
			$output .= $this->get_mobile_var( $settings );
			$output .= $this->get_colors_var( $settings );
			$output .= $this->get_header_1_var( $settings );
			$output .= $this->get_header_4_var( $settings );
			$output .= $this->get_header_5_var( $settings );
			$output .= $this->get_mobile_header_var( $settings );
			$output .= $this->get_archive_var( $settings );
			$output .= $this->get_categories_var( $settings );
			$output .= $this->get_heading_var( $settings );
			$output .= $this->get_global_var( $settings );
			$output .= $this->get_global_var_dark( $settings );
			$output .= $this->get_dynamic_style( $settings );

			return $this->minify_css( $output );
		}

	}
}

/** load */
Foxiz_Css::get_instance();

