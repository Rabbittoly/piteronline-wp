<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Ajax_Adobe_Fonts' ) ) {
	class Ruby_Ajax_Adobe_Fonts {

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

			add_action( 'wp_ajax_rb_adobef_delete', [ $this, 'remove_font' ] );
			add_action( 'wp_ajax_rb_adobef_update', [ $this, 'update_font' ] );
		}

		public function font_api( $project_id ) {

			$data     = [];
			$api_url  = 'https://typekit.com/api/v1/json/kits/' . $project_id . '/published';
			$response = wp_remote_get( $api_url, [ 'timeout' => 60 ] );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return esc_html__( 'Project ID is invalid. Please check again!', 'foxiz-core' );
			}

			$response      = wp_remote_retrieve_body( $response );
			$response      = json_decode( $response, true );
			$font_families = $response['kit']['families'];

			if ( is_array( $font_families ) && count( $font_families ) ) {

				foreach ( $font_families as $font_family ) {
					$family_name          = $font_family['slug'];
					$data[ $family_name ] = [
						'family'     => $font_family['name'],
						'backup'     => str_replace( '"', '', $font_family['css_stack'] ),
						'variations' => [],
					];

					if ( isset( $font_family['css_names'][0] ) ) {
						$data[ $family_name ]['css_names'] = $font_family['css_names'][0];
					}
					foreach ( $font_family['variations'] as $variation ) {

						$variations = str_split( $variation );
						if ( $variations[0] === 'n' ) {
							$font_variation = $variations[1] . '00';
						} else {
							$font_variation = $variations[1] . '00' . $variations[0];
						}

						array_push( $data[ $family_name ]['variations'], $font_variation );
					}
				}
			}

			if ( ! count( $data ) ) {
				return esc_html__( 'Project is empty. Please add some fonts to your project.', 'foxiz-core' );
			}

			return $data;
		}

		function remove_font() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			delete_option( 'rb_adobe_fonts' );

			wp_send_json_success( esc_html__( 'Adobe Fonts have been successfully removed.', 'foxiz-core' ) );

			wp_die();
		}

		/** update fonts */
		function update_font() {

			$nonce      = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';
			$project_id = ( isset( $_POST['projectID'] ) ) ? sanitize_text_field( $_POST['projectID'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( empty( $project_id ) ) {
				wp_send_json_error( esc_html__( 'Project ID not found.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			$data = $this->font_api( $project_id );

			if ( ! is_array( $data ) ) {
				wp_send_json_error( $data );
			}

			update_option( 'rb_adobe_fonts', [
				'project_id' => $project_id,
				'fonts'      => $data,
			] );

			wp_send_json_success( esc_html__( 'Adobe Fonts have been successfully loaded.', 'foxiz-core' ) );

			wp_die();
		}

	}
}

/** init */
Ruby_Ajax_Adobe_Fonts::get_instance();