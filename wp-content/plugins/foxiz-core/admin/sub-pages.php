<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RB_ADMIN_SUB_PAGE', false ) ) {
	abstract class RB_ADMIN_SUB_PAGE {

		private $params = [];
		public $page_title;
		public $menu_title;
		public $position;
		public $menu_slug;
		public $capability;

		public function __construct() {

			$this->set_sub_page();
		}

		/** get sub page */
		abstract public function set_sub_page();

		/** get slug */
		abstract public function get_slug();

		/** get file name */
		abstract public function get_name();

		/** get params */
		public function get_params() {

			return $this->params;
		}

		public function set_params( $params = [] ) {

			return $this->params = $params;
		}

		public function validate() {

			return RB_ADMIN_CORE::get_instance()->get_purchase_code();
		}

		public function render() {

			RB_ADMIN_CORE::get_instance()->header_template();

			echo rb_admin_get_template_part( $this->get_slug(), $this->get_name(), $this->get_params() );
		}

	}
}