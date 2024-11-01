<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Shortcodes', false ) ) {
	class Foxiz_Shortcodes {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_shortcode( 'ruby_static_newsletter', [ $this, 'render_static_newsletter' ] );
			add_shortcode( 'ruby_related', [ $this, 'render_related' ] );
			add_shortcode( 'ruby_review_box', [ $this, 'render_review_box' ] );
		}

		public function render_static_newsletter( $attrs ) {

			$settings = shortcode_atts( [
				'classes'         => '',
				'title'           => foxiz_get_option( 'single_post_newsletter_title' ),
				'description'     => foxiz_get_option( 'single_post_newsletter_description' ),
				'code'            => foxiz_get_option( 'single_post_newsletter_code' ),
				'policy'          => foxiz_get_option( 'single_post_newsletter_policy' ),
				'heading_tag'     => 'h2',
				'description_tag' => 'h6',
			], $attrs );

			$output     = '';
			$class_name = 'newsletter-box';
			if ( ! empty( $settings['classes'] ) ) {
				$class_name .= ' ' . $settings['classes'];
			}
			$output .= '<div class="' . esc_attr( $class_name ) . '">';
			$output .= '<div class="newsletter-box-header">';
			$output .= '<span class="newsletter-icon"><i class="rbi rbi-plane"></i></span>';
			$output .= '<div class="inner">';
			if ( ! empty( $settings['title'] ) ) {
				$output .= '<' . esc_attr( $settings['heading_tag'] ) . ' class="newsletter-box-title">' . esc_html( $settings['title'] ) . '</' . esc_attr( $settings['heading_tag'] ) . '>';
			}
			if ( ! empty( $settings['title'] ) ) {
				$output .= '<' . esc_attr( $settings['description_tag'] ) . ' class="newsletter-box-description">' . esc_html( $settings['description'] ) . '</' . esc_attr( $settings['description_tag'] ) . '>';
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="newsletter-box-content">';
			if ( ! empty( $settings['code'] ) ) {
				$output .= do_shortcode( $settings['code'] );
			}
			$output .= '</div>';
			if ( ! empty( $settings['policy'] ) ) {
				$output .= '<div class="newsletter-box-policy">' . $settings['policy'] . '</div>';
			}
			$output .= '</div>';

			return $output;
		}

		/**
		 * @param $attrs
		 *
		 * @return false|string
		 */
		public function render_related( $attrs ) {

			$settings = shortcode_atts( [
				'heading'        => foxiz_html__( 'You Might Also Like', 'foxiz-core' ),
				'heading_tag'    => '',
				'heading_layout' => '',
				'total'          => 2,
				'layout'         => 1,
				'ids'            => '',
				'where'          => '',
				'order'          => '',
				'post_id'        => get_the_ID(),
			], $attrs );

			if ( empty( $settings['heading_layout'] ) ) {
				$settings['heading_layout'] = foxiz_get_option( 'heading_layout' );
			}

			$func_name = 'foxiz_get_layout_related_' . absint( $settings['layout'] );
			if ( function_exists( $func_name ) ) {
				ob_start();
				call_user_func( $func_name, $settings );

				return ob_get_clean();
			}

			return false;
		}

		public function render_review_box( $attrs ) {

			if ( function_exists( 'foxiz_single_review' ) ) {
				ob_start();
				foxiz_single_review( null, true );

				return ob_get_clean();
			}

			return false;
		}
	}
}

/** init */
Foxiz_Shortcodes::get_instance();



