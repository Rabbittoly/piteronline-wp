<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Rb_Translation_Init', false ) ) {
	class Rb_Translation_Init {

		private static $instance;
		private static $nonce = 'foxiz-admin';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;

			add_action( 'wp_ajax_rb_fetch_translation', [ $this, 'reload_translation' ] );
			add_action( 'wp_ajax_rb_update_translation', [ $this, 'update_translation' ] );
		}

		function reload_translation() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			delete_option( 'rb_translation_data' );
			wp_send_json_success( esc_html__( 'Completed', 'foxiz-core' ));
		}

		public function update_translation() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';
			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			$data = $_POST;
			unset( $data['_nonce'], $data['action'] );

			$data = array_map( 'sanitize_text_field', array_map( 'stripslashes', $data ) );
			update_option( 'rb_translated_data', $data );

			wp_send_json_success();

			wp_die();
		}

	}
}

/** load */
Rb_Translation_Init::get_instance();
