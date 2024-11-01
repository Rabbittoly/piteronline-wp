<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Login_Screen', false ) ) {
	class Ruby_Login_Screen {

		private static $instance;
		public static $style = '';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;
			self::$style    = foxiz_get_option( 'login_screen_style' );

			if ( foxiz_get_option( 'remove_admin_bar' ) ) {
				add_action( 'after_setup_theme', [ $this, 'admin_bar' ], 99 );
			}

			if ( foxiz_get_option( 'remove_lang_bar' ) ) {
				add_filter( 'login_display_language_dropdown', '__return_false' );
			}

			if ( self::$style ) {
				add_filter( 'login_body_class', [ $this, 'login_classes' ], 10, 1 );
				add_filter( 'login_headerurl', [ $this, 'logo_url' ] );
				add_action( 'login_enqueue_scripts', [ $this, 'enqueue' ], 20 );
				add_action( 'login_enqueue_scripts', [ $this, 'dynamic_style' ], 99 );
				add_action( 'login_header', [ $this, 'header_tag' ] );
				add_action( 'login_footer', [ $this, 'footer_tag' ] );
			}
		}

		function admin_bar() {

			if ( ! current_user_can( 'manage_options' ) && ! is_admin() ) {
				add_filter( 'show_admin_bar', '__return_false', 99 );
			}
		}

		/**
		 * @param $classes
		 *
		 * @return array|mixed
		 * login classes
		 */
		function login_classes( $classes ) {

			if ( is_array( $classes ) ) {
				$classes[] = 'rb-login-screen style-' . intval( self::$style );
			}

			return $classes;
		}

		function enqueue() {

			wp_enqueue_style( 'rb-admin-screen', FOXIZ_CORE_URL . 'assets/admin-screen.css', [], FOXIZ_CORE_VERSION, 'all' );
		}

		/**
		 * @return string|void
		 * logo URL
		 */
		public function logo_url() {

			$url = foxiz_get_option( 'logo_redirect' );

			if ( ! empty( $url ) ) {
				return esc_url( $url );
			} else {
				return home_url( '/' );
			}
		}

		public function header_tag() {

			echo '<div class="rb-login-outer">';
		}

		public function footer_tag() {

			echo '</div>';
		}

		/**
		 * dynamic style
		 */
		public function dynamic_style() {

			$output     = '';
			$logo       = foxiz_get_option( 'login_screen_logo' );
			$background = foxiz_get_option( 'login_screen_bg' );
			$color      = foxiz_get_option( 'login_color' );
			$position   = foxiz_get_option( 'login_form_position' );

			if ( ! empty( $logo['url'] ) ) {
				$output .= '.login.rb-login-screen h1 a {
						    height: 60px;
		                    width: 100%;
		                    max-width: 300px;
		                    background-size: contain; 
							background-image: url(' . esc_url( $logo['url'] ) . ');
							}';
			}

			if ( ! empty( $position ) ) {
				$output .= 'body.rb-login-screen:not(.interim-login) #login {';

				switch ( $position ) {
					case '1':
						$output .= 'margin-left: auto; margin-right: auto;';
						break;
					case '2':
						$output .= 'margin-left: auto; margin-right: 0;';
						break;
				}

				$output .= '}';
			}

			$output .= 'body.login:not(.interim-login) { ' . $this->create_background_css( $background ) . '}';
			if ( ! empty( $color ) ) {
				$output .= '.rb-login-screen.login input#wp-submit { background-color :' . $color . '}';
				$output .= '.rb-login-screen.login .button.wp-hide-pw .dashicons { color :' . $color . '}';
			}
			$output = preg_replace( '@({)\s+|(\;)\s+|/\*.+?\*\/|\R@is', '$1$2 ', $output );
			echo sprintf( "<style>\n%s\n</style>\n", $output );

			return false;
		}

		/**
		 * @param $settings
		 *
		 * @return string
		 */
		function create_background_css( $settings ) {

			if ( ! is_array( $settings ) ) {
				return '';
			}

			$output = '';
			if ( ! empty( $settings['background-color'] ) ) {
				$output .= 'background-color : ' . $settings['background-color'] . ';';
			}
			if ( ! empty( $settings['background-repeat'] ) ) {
				$output .= 'background-repeat : ' . $settings['background-repeat'] . ';';
			}
			if ( ! empty( $settings['background-size'] ) ) {
				$output .= 'background-size : ' . $settings['background-size'] . ';';
			}
			if ( ! empty( $settings['background-image'] ) ) {
				$output .= 'background-image : url(' . esc_url( $settings['background-image'] ) . ');';
			}
			if ( ! empty( $settings['background-attachment'] ) ) {
				$output .= 'background-attachment : ' . $settings['background-attachment'] . ';';
			}
			if ( ! empty( $settings['background-position'] ) ) {
				$output .= 'background-position : ' . $settings['background-position'] . ';';
			}

			return $output;
		}
	}
}

/** init */
Ruby_Login_Screen::get_instance();
