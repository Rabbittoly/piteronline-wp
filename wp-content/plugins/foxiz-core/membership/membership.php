<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', [ 'Foxiz_Membership', 'get_instance' ], 0 );

if ( ! class_exists( 'Foxiz_Membership', false ) ) {
	class Foxiz_Membership {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			if ( ! class_exists( 'SimpleWpMembership' ) ) {
				return false;
			}

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ], 9998 );
			add_filter( 'swpm_not_logged_in_more_tag_msg', [ $this, 'restrict_box' ] );
			add_filter( 'swpm_not_logged_in_post_msg', [ $this, 'restrict_box' ] );
			add_filter( 'swpm_restricted_post_msg', [ $this, 'restrict_level_box' ] );
			add_filter( 'swpm_restricted_more_tag_msg', [ $this, 'restrict_level_box' ] );
			add_filter( 'swpm_account_expired_msg', [ $this, 'restrict_renewal_box' ] );
			add_filter( 'swpm_account_expired_more_tag_msg', [ $this, 'restrict_renewal_box' ] );
			add_filter( 'foxiz_entry_title_classes', [ $this, 'add_classes' ], 10, 2 );
		}

		function enqueue_style() {

			$path = 'swpm.css';
			if ( is_rtl() ) {
				$path = 'swpm-rtl.css';
			}

			wp_enqueue_style( 'foxiz-swpm', FOXIZ_CORE_URL . 'assets/' . $path, [], FOXIZ_CORE_VERSION, 'all' );
		}

		/**
		 * @param $error_msg
		 *
		 * @return string
		 */
		public function restrict_box( $error_msg ) {

			$login_url  = SwpmSettings::get_instance()->get_value( 'login-page-url' );
			$joinus_url = SwpmSettings::get_instance()->get_value( 'join-us-page-url' );

			if ( empty( $login_url ) || empty( $joinus_url ) ) {
				return '<p class="rb-error">The login page or the join us page URL is missing in the settings configuration.</p>';
			}

			$restrict_title = foxiz_get_option( 'restrict_title' );
			$restrict_desc  = foxiz_get_option( 'restrict_desc' );
			$join_us_label  = foxiz_get_option( 'join_us_label' );
			$login_label    = foxiz_get_option( 'login_label' );
			$login_desc     = foxiz_get_option( 'login_desc' );

			$output = '';
			$output .= '<div class="restrict-box"><div class="restrict-box-inner">';

			$output .= '<h2 class="restrict-title">' . foxiz_strip_tags( $restrict_title ) . '</h2>';
			$output .= '<p class="restrict-desc">' . foxiz_strip_tags( $restrict_desc ) . '</p>';
			$output .= '<div class="restrict-button-wrap"><a href="' . $joinus_url . '" class="restrict-button is-btn">' . $join_us_label . '</a></div>';
			$output .= '<div class="restrict-login">';
			$output .= '<span class="restrict-login-description">' . foxiz_strip_tags( $login_desc ) . '</span>';
			$output .= '<a class="restrict-login-link" href="' . $login_url . '">' . $login_label . '</a>';
			$output .= '</div>';

			$output .= '</div></div>';

			return $output;
		}

		/**
		 * @param $error_msg
		 *
		 * @return string
		 */
		public function restrict_level_box( $error_msg ) {

			$joinus_url = SwpmSettings::get_instance()->get_value( 'join-us-page-url' );

			if ( empty( $joinus_url ) ) {
				return '<p class="rb-error">The login page or the join us page URL is missing in the settings configuration.</p>';
			}

			$restrict_title = foxiz_get_option( 'restrict_level_title' );
			$restrict_desc  = foxiz_get_option( 'restrict_level_desc' );
			$join_us_label  = foxiz_get_option( 'join_us_label' );

			$output = '';
			$output .= '<div class="restrict-box"><div class="restrict-box-inner">';

			$output .= '<h2 class="restrict-title">' . foxiz_strip_tags( $restrict_title ) . '</h2>';
			$output .= '<p class="restrict-desc">' . foxiz_strip_tags( $restrict_desc ) . '</p>';
			$output .= '<div class="restrict-button-wrap"><a href="' . $joinus_url . '" class="restrict-button is-btn">' . $join_us_label . '</a></div>';

			$output .= '</div></div>';

			return $output;
		}

		/**
		 * @param $error_msg
		 *
		 * @return string
		 */
		public function restrict_renewal_box( $error_msg ) {

			$renewal = SwpmSettings::get_instance()->get_value( 'renewal-page-url' );
			if ( empty( $renewal ) ) {
				$renewal = SwpmSettings::get_instance()->get_value( 'join-us-page-url' );
			}
			if ( empty( $renewal ) ) {
				return '<p class="rb-error">The renewal page or the URL is missing in the settings configuration.</p>';
			}

			$restrict_title = foxiz_get_option( 'restrict_renewal_title' );
			$restrict_desc  = foxiz_get_option( 'restrict_renewal_desc' );
			$renewal_label  = foxiz_get_option( 'renewal_label' );

			$output = '';
			$output .= '<div class="restrict-box"><div class="restrict-box-inner">';

			$output .= '<h2 class="restrict-title">' . foxiz_strip_tags( $restrict_title ) . '</h2>';
			$output .= '<p class="restrict-desc">' . foxiz_strip_tags( $restrict_desc ) . '</p>';
			$output .= '<div class="restrict-button-wrap"><a href="' . $renewal . '" class="restrict-button is-btn">' . $renewal_label . '</a></div>';

			$output .= '</div></div>';

			return $output;
		}

		/**
		 * @param $classes
		 * @param $post_id
		 *
		 * @return string
		 */
		public function add_classes( $classes, $post_id ) {

			$protected = SwpmProtection::get_instance();
			if ( ! $protected->is_protected( $post_id ) ) {
				return $classes;
			}

			return $classes . ' is-p-protected';
		}
	}
}
