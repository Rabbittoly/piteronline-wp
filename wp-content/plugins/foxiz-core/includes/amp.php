<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', [ 'Foxiz_AMP', 'get_instance' ], 3 );

if ( ! class_exists( 'Foxiz_AMP', false ) ) {
	class Foxiz_AMP {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			if ( ! defined( 'AMP__VERSION' ) ) {
				return;
			}

			add_filter( 'amp_supportable_post_types', [ $this, 'remove_post_types' ] );
			add_action( 'after_setup_theme', [ $this, 'add_theme_support' ], 5 );
			add_action( 'wp_print_styles', [ $this, 'remove_defaults' ] );
			add_action( 'init', [ $this, 'amp_configs' ], 997 );

			if ( ! foxiz_get_option( 'amp_debug' ) ) {
				add_action( 'admin_menu', [ $this, 'remove_validation' ], 9999 );
				add_action( 'wp_loaded', [ $this, 'remove_notices' ], 9999 );
			}
		}

		public function add_theme_support() {

			add_theme_support( 'amp', [
				'paired' => true,
			] );
		}

		public function remove_defaults() {

			if ( foxiz_is_amp() ) {
				remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
				remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
			}
		}

		/**
		 * @param $post_types
		 *
		 * @return array
		 */
		public function remove_post_types( $post_types ) {

			if ( empty( $post_types ) || ! is_array( $post_types ) ) {
				return $post_types;
			}

			foreach ( $post_types as $index => $post_type ) {
				if ( 'rb-etemplate' === $post_type || 'product' === $post_type ) {
					unset( $post_types[ $index ] );
				}
			}

			return $post_types;
		}

		public function amp_configs() {

			if ( ! class_exists( 'AMP_Options_Manager' ) ) {
				return;
			}

			if ( true === AMP_Options_Manager::get_option( 'all_templates_supported' ) ) {
				AMP_Options_Manager::update_option( 'all_templates_supported', false );
			}

			if ( 'transitional' !== AMP_Options_Manager::get_option( 'theme_support' ) ) {
				AMP_Options_Manager::update_option( 'theme_support', 'transitional' );
			} else {
				add_action( 'admin_print_styles', [ $this, 'remove_mode_selection' ] );
				add_action( 'admin_footer', [ $this, 'print_mode' ] );
			}
		}

		public function print_mode() {

			$current_screen = get_current_screen();
			if ( $current_screen->id === 'toplevel_page_amp-options' && defined( 'AMP__VERSION' ) && AMP__VERSION >= 2 ) : ?>
				<script>
                    (function ($) {
                        var templateModeTimeOut = setInterval(function () {
                            var templateModes = $('#template-modes');
                            if (templateModes.length > 0) {
                                templateModes.html('<div class="selectable selectable--left" style="box-shadow: -10px 0 0 #6cc296;"><div class="settings-welcome__illustration"><h3><?php esc_html_e( 'Foxiz supported and activated AMP in the Transitional mode.', 'foxiz-core' ); ?></h3></div></div>');
                                clearInterval(templateModeTimeOut);
                            }
                        }, 100);
                        setTimeout(function () {
                            clearInterval(templateModeTimeOut);
                        }, 5000);
                    })(jQuery);
				</script>
				<script>
                    (function ($) {
                        $('.amp-website-mode').find('td').html('<p class="notice notice-success"><?php esc_html_e( 'Foxiz supported and activated AMP in the Transitional mode.', 'foxiz-core' ); ?></p>');
                        $('#amp-options-supported_post_types-rb-etemplate , #amp-options-supported_post_types-product').next().addBack().remove();
                        $('#all_templates_supported_fieldset').remove();
                    })(jQuery);
				</script>
			<?php
			endif;
		}

		public function remove_mode_selection() { ?>
			<style type='text/css'> .amp-website-mode fieldset {
                    display: none;
                }</style>
			<?php
		}

		public function remove_validation() {

			remove_submenu_page( 'amp-options', 'edit.php?post_type=amp_validated_url' );
			remove_submenu_page( 'amp-options', esc_attr( 'edit-tags.php?taxonomy=amp_validation_error&post_type=amp_validated_url' ) );
			remove_filter( 'dashboard_glance_items', [
				'AMP_Validated_URL_Post_Type',
				'filter_dashboard_glance_items',
			] );
		}

		public function remove_notices() {

			remove_action( 'admin_bar_menu', 'AMP_Validation_Manager::add_admin_bar_menu_items', 101 );
			remove_action( 'edit_form_top', 'AMP_Validation_Manager::print_edit_form_validation_status' );
			remove_action( 'edit_form_top', 'AMP_Validated_URL_Post_Type::print_url_as_title' );
			remove_action( 'all_admin_notices', 'AMP_Validation_Manager::print_plugin_notice' );
			remove_action( 'enqueue_block_editor_assets', 'AMP_Validation_Manager::enqueue_block_validation' );
			remove_action( 'all_admin_notices', 'AMP_Validation_Manager::print_plugin_notice' );
		}
	}
}
