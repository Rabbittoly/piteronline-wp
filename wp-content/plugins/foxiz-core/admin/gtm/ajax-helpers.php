<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Ajax_Google_Tags' ) ) {
	class Ruby_Ajax_Google_Tags {

		private static $instance;
		private static $nonce = 'foxiz-admin';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'wp_ajax_rb_gtm_delete', [ $this, 'remove' ] );
			add_action( 'wp_ajax_rb_gtm_add', [ $this, 'add' ] );
		}

		/** remove tags */
		function remove() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			delete_option( 'simple_gtm_id' );
			delete_option( 'simple_gtag_id' );

			wp_send_json_success( esc_html__( 'Tags successfully removed.', 'foxiz-core' ));

			wp_die();
		}

		/** add tags */
		function add() {

			$nonce   = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';
			$gtm_id  = ( isset( $_POST['gtmID'] ) ) ? sanitize_text_field( $_POST['gtmID'] ) : false;
			$gtag_id = ( isset( $_POST['gtagID'] ) ) ? sanitize_text_field( $_POST['gtagID'] ) : false;

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			if ( empty( $gtm_id ) && empty( $gtag_id ) ) {
				wp_send_json_error( esc_html__( 'Tags not found!', 'foxiz-core' ), 403 );

				wp_die();
			}

			if ( $gtm_id ) {
				update_option( 'simple_gtm_id', $gtm_id );
			}
			if ( $gtag_id ) {
				update_option( 'simple_gtag_id', $gtag_id );
			}
			wp_send_json_success( esc_html__( 'Google Tag has been successfully added.', 'foxiz-core' ) );

			wp_die();
		}

	}
}

/** init */
Ruby_Ajax_Google_Tags::get_instance();