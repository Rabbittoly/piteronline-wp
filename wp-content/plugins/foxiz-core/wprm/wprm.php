<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', [ 'Foxiz_WPRM', 'get_instance' ], 1 );

if ( ! class_exists( 'Foxiz_WPRM', false ) ) {
	class Foxiz_WPRM {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {


			if ( ! class_exists( 'WP_Recipe_Maker' ) ) {
				return;
			}

			$pinterest_lib = foxiz_get_option( 'wprm_pinterest' );

			if ( empty( $pinterest_lib ) || '-1' === (string) $pinterest_lib ) {
				add_filter( 'wprm_load_pinit', '__return_false', 9999 );
			}

			if ( foxiz_get_option( 'wprm_supported' ) ) {
				add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ], 9999 );
				remove_action( 'wp_head', [ 'WPRM_Template_Manager', 'head_css' ] );
				remove_action( 'wp_footer', [ 'WPRM_Template_Manager', 'templates_css' ], 99 );
				add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_style' ], 92 );
			}
		}

		function enqueue_style() {

			$path = is_rtl() ? 'assets/wprm-rtl.css' : 'assets/wprm.css';
			wp_enqueue_style( 'foxiz-wprm', FOXIZ_CORE_URL . $path, [], FOXIZ_CORE_VERSION, 'all' );
		}
	}
}