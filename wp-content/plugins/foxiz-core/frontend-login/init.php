<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Frontend_Login', false ) ) {
	class Ruby_Frontend_Login {

		private static $instance;
		public $style = '';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_filter( 'logout_redirect', [ $this, 'logout_redirect' ], 10, 3 );
			add_filter( 'login_url', [ $this, 'login_url' ], 10, 3 );
			add_filter( 'authenticate', [ $this, 'maybe_redirect_at_auth' ], 999, 3 );
			add_filter( 'register_url', [ $this, 'register_url' ], 10 );
			add_filter( 'register', [ $this, 'register_link' ], 10 );
			add_filter( 'lostpassword_url', [ $this, 'lostpassword_url' ], 10, 2 );
			add_action( 'lost_password', [ $this, 'lostpassword_error_redirect' ], 999, 2 );
			add_action( 'template_redirect', [ $this, 'logged_redirect' ], 20 );
		}

		function register_link( $link ) {

			$login_register = foxiz_get_option( 'login_register' );

			if ( ! empty( $login_register ) ) {
				return '<a href="' . esc_url( $login_register ) . '">' . foxiz_html__( 'Register', 'foxiz-core' ) . '</a>';
			} else {
				return $link;
			}
		}

		function register_url( $url ) {

			$login_register = foxiz_get_option( 'login_register' );

			if ( ! empty( $login_register ) ) {
				return esc_url( $login_register );
			} else {
				return $url;
			}
		}

		function lostpassword_url( $link, $redirect ) {

			$login_forget = foxiz_get_option( 'login_page' );

			if ( empty( $login_forget ) ) {
				return $link;
			}
			$args = [ 'action' => 'lostpassword' ];

			if ( ! empty( $redirect ) ) {
				$args['redirect_to'] = urlencode( $redirect );
			}

			return add_query_arg( $args, esc_url( $login_forget ) );
		}

		function logout_redirect( $redirect_to ) {

			if ( foxiz_get_option( 'logout_redirect' ) ) {
				return esc_url( foxiz_get_option( 'logout_redirect' ) );
			} else {
				return $redirect_to;
			}
		}

		function maybe_redirect_at_auth( $user, $username, $password ) {

			$redirect_url = foxiz_get_option( 'login_page' );

			if ( ! empty( $redirect_url ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				if ( is_wp_error( $user ) ) {
					$msg          = $user->get_error_message();
					$msg          = preg_replace( '/<a.*?<\/a>/', '', $msg );
					$redirect_url = add_query_arg( 'auth_error_msg', rawurlencode( strip_tags( $msg ) ), $redirect_url );
					wp_safe_redirect( esc_url( $redirect_url ) );
					exit;
				}
			}

			return $user;
		}

		/**
		 * @param $errors
		 */
		function lostpassword_error_redirect( $errors ) {

			$url = foxiz_get_option( 'login_page' );

			if ( empty( $url ) ) {
				return;
			}

			$url = esc_url( $url );
			if ( is_wp_error( $errors ) && $errors->get_error_codes() ) {
				$msg = $errors->get_error_message();
				$msg = preg_replace( '/<a.*?<\/a>/', '', $msg );
			} else {
				$msg = esc_html__( 'Error: invalid', 'foxiz-core' );
			}

			$args = [
				'action'          => 'lostpassword',
				'passw_error_msg' => rawurlencode( strip_tags( $msg ) ),
			];

			wp_safe_redirect( add_query_arg( $args, $url ) );
			exit;
		}

		function logged_redirect() {

			if ( ! foxiz_get_option( 'login_page_disable_logged' ) ) {
				return;
			}

			$logged_url = foxiz_get_option( 'login_redirect' );
			if ( empty( $logged_url ) || ! is_user_logged_in() ) {
				return;
			}

			$current_url   = rtrim( foxiz_get_current_permalink(), '/' );
			$login_page    = rtrim( foxiz_get_option( 'login_page' ), '/' );
			$register_page = rtrim( foxiz_get_option( 'login_register' ), '/' );

			if ( $current_url === $login_page || $current_url === $register_page ) {
				wp_safe_redirect( esc_url( $logged_url ) );
				exit();
			}
		}

		function login_url( $default_url, $redirect, $force_reauth ) {

			$url = foxiz_get_option( 'login_page' );

			if ( empty( $url ) || is_admin() ) {
				return $default_url;
			}

			$url = esc_url( $url );

			if ( ! empty( $redirect ) ) {
				$url = add_query_arg( 'redirect_to', urlencode( $redirect ), $url );
			}

			if ( $force_reauth ) {
				$url = add_query_arg( 'reauth', '1', $url );
			}

			return $url;
		}
	}
}

/** init */
Ruby_Frontend_Login::get_instance();
