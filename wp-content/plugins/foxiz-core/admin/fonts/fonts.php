<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'rbSubPageAdobeFonts' ) ) {
	class rbSubPageAdobeFonts extends RB_ADMIN_SUB_PAGE {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			parent::__construct();
		}

		public function set_sub_page() {

			$this->page_title = esc_html__( 'Adobe Fonts', 'foxiz-core' );
			$this->menu_title = esc_html__( 'Adobe Fonts', 'foxiz-core' );
			$this->menu_slug  = 'ruby-adobe-fonts';
			$this->capability = 'manage_options';
			$this->position   = 10;

			$data = $this->get_data();

			$data['button'] = esc_html__( 'Save Changes', 'foxiz-core' );
			$data['delete'] = esc_html__( 'Delete Project', 'foxiz-core' );

			$this->set_params( $data );
		}

		public function get_data() {

			$data = get_option( 'rb_adobe_fonts', [] );
			$data = wp_parse_args( $data, [
				'project_id' => '',
				'fonts'      => '',
			] );

			return $data;
		}

		public function get_slug() {

			return ! $this->validate() ? 'admin/templates/template' : 'admin/fonts/template';
		}

		public function get_name() {

			return ! $this->validate() ? 'redirect' : false;
		}

		/**
		 * @param array $fonts
		 *
		 * @return array
		 */
		public function font_selection( $fonts = [] ) {

			$options = [
				'0' => esc_html__( '- Default -', 'foxiz-core' ),
			];

			if ( is_array( $fonts ) ) {

				foreach ( $fonts as $name => $font ) {
					if ( ! isset( $font['family'] ) ) {
						continue;
					}

					if ( isset( $font['variations'] ) && is_array( $font['variations'] ) ) {
						foreach ( $font['variations'] as $variation ) {
							if ( substr( $variation, - 1 ) === 'i' ) {
								$label = $font['family'] . esc_html__( ' - Italic', 'foxiz-core' ) . ' ' . substr( $variation, 0, - 1 );
							} else {
								$label = $font['family'] . esc_html__( ' - Normal', 'foxiz-core' ) . ' ' . $variation;
							}
							$options[ $name . '::' . $variation ] = $label;
						}
					}
				}
			}

			return $options;
		}
	}
}