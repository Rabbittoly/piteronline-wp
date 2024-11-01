<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'rbSubPageGTM' ) ) {
	class rbSubPageGTM extends RB_ADMIN_SUB_PAGE {

		private static $instance;

		public function __construct() {

			self::$instance = $this;

			parent::__construct();
		}

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function set_sub_page() {

			$this->page_title = esc_html__( 'Google Tag Manager & Analytics 4', 'foxiz-core' );
			$this->menu_title = esc_html__( 'GTM & GA4', 'foxiz-core' );
			$this->menu_slug  = 'ruby-gmt-integration';
			$this->capability = 'manage_options';
			$this->position   = 500000;

			$this->set_params( [
				'gtm_id' => get_option( 'simple_gtm_id' ),
				'gtag_id'     => get_option( 'simple_gtag_id' ),
			] );
		}
		public function get_slug() {

			return ! $this->validate() ? 'admin/templates/template' : 'admin/gtm/template';
		}

		public function get_name() {

			return ! $this->validate() ? 'redirect' : false;
		}

	}
}
