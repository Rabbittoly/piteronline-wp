<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'rbSubPageSystemInfo' ) ) {
	class rbSubPageSystemInfo extends RB_ADMIN_SUB_PAGE {

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

			$this->page_title = esc_html__( 'System Info', 'foxiz-core' );
			$this->menu_title = esc_html__( 'System Info', 'foxiz-core' );
			$this->menu_slug  = 'ruby-system-info';
			$this->capability = 'manage_options';
			$this->position   = PHP_INT_MAX;

			$this->set_params( [
				'system_info' => RB_ADMIN_CORE::get_instance()->get_system_info(),
				'wp_info'     => RB_ADMIN_CORE::get_instance()->get_wp_info(),
			] );
		}

		public function get_slug() {

			return 'admin/system-info/template';
		}

		public function get_name() {

			return false;
		}
	}
}
