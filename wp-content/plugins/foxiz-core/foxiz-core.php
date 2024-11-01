<?php
/**
 * Plugin Name:    Foxiz Core
 * Plugin URI:     https://foxiz.themeruby.com/
 * Author URI:     https://themeforest.net/user/theme-ruby/
 * Description:    Features for Foxiz, this is required plugin (important) for this theme.
 * Version:        2.5.0
 * Requires at least: 6.0
 * Requires PHP:   7.0
 * Text Domain:    foxiz-core
 * Domain Path:    /languages/
 * Author:         Theme-Ruby
 *
 * @package        foxiz-core
 */
defined( 'ABSPATH' ) || exit;

define( 'FOXIZ_CORE_VERSION', '2.5.0' );
define( 'FOXIZ_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'FOXIZ_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'FOXIZ_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
defined( 'FOXIZ_TOS_ID' ) || define( 'FOXIZ_TOS_ID', 'foxiz_theme_options' );
defined( 'RB_META_ID' ) || define( 'RB_META_ID', 'rb_global_meta' );

/** LOAD FILES */
include_once FOXIZ_CORE_PATH . 'includes/file.php';

if ( ! class_exists( 'FOXIZ_CORE', false ) ) {
	class FOXIZ_CORE {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;
			register_activation_hook( __FILE__, [ $this, 'activation' ] );
			add_action( 'plugins_loaded', [ $this, 'translation' ], 100 );
			add_action( 'wp_enqueue_scripts', [ $this, 'core_enqueue' ], 1 );
			add_action( 'widgets_init', [ $this, 'register_widgets' ] );
		}

		public function translation() {

			$loaded = load_plugin_textdomain( 'foxiz-core', false, FOXIZ_CORE_PATH . 'languages/' );
			if ( ! $loaded ) {
				$locale = apply_filters( 'plugin_locale', get_locale(), 'foxiz-core' );
				$mofile = FOXIZ_CORE_PATH . 'languages/foxiz-core-' . $locale . '.mo';
				load_textdomain( 'foxiz-core', $mofile );
			}
		}

		public function core_enqueue() {

			if ( is_admin() || foxiz_is_amp() ) {
				return;
			}

			$deps = [ 'jquery' ];
			wp_register_style( 'foxiz-core', FOXIZ_CORE_URL . 'assets/core.js', $deps, FOXIZ_CORE_VERSION, true );

			$fonts = get_option( 'rb_adobe_fonts', [] );
			if ( ! empty( $fonts['project_id'] ) ) {
				wp_enqueue_style( 'adobe-fonts', esc_url_raw( 'https://use.typekit.net/' . esc_html( $fonts['project_id'] ) . '.css' ), [], false, 'all' );
			}

			wp_register_style( 'foxiz-admin-bar', FOXIZ_CORE_URL . 'assets/admin-bar.css', [], FOXIZ_CORE_VERSION );
			wp_register_script( 'foxiz-core', FOXIZ_CORE_URL . 'assets/core.js', $deps, FOXIZ_CORE_VERSION, true );

			$js_params     = [
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'darkModeID'   => $this->get_dark_mode_id(),
				'cookieDomain' => defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '',
				'cookiePath'   => defined( 'COOKIEPATH' ) ? COOKIEPATH : '/',
			];
			$multi_site_id = $this->get_multisite_subfolder();
			if ( $multi_site_id ) {
				$js_params['mSiteID'] = $multi_site_id;
			}
			wp_localize_script( 'foxiz-core', 'foxizCoreParams', $js_params );
			wp_enqueue_script( 'foxiz-core' );

			if ( is_admin_bar_showing() ) {
				wp_enqueue_style( 'foxiz-admin-bar' );
			}
		}

		public function get_dark_mode_id() {

			if ( is_multisite() ) {
				return 'D_' . trim( str_replace( '/', '_', preg_replace( '/https?:\/\/(www\.)?/', '', get_site_url() ) ) );
			}

			return 'RubyDarkMode';
		}

		public function get_multisite_subfolder() {

			if ( is_multisite() ) {
				$site_info = get_blog_details( get_current_blog_id() );
				$path      = $site_info->path;

				if ( ! empty( $path ) && '/' !== $path ) {
					return trim( str_replace( '/', '', $path ) );
				} else {
					return false;
				}
			}

			return false;
		}

		/**
		 * @return false
		 */
		public function register_widgets() {

			$widgets = [
				'Foxiz_W_Post',
				'Foxiz_W_Follower',
				'Foxiz_W_Weather',
				'Foxiz_Fw_Instagram',
				'Foxiz_W_Social_Icon',
				'Foxiz_W_Youtube_Subscribe',
				'Foxiz_W_Flickr',
				'Foxiz_W_Address',
				'Foxiz_W_Instagram',
				'Foxiz_Fw_Mc',
				'Foxiz_Ad_Image',
				'Foxiz_FW_Banner',
				'Foxiz_W_Facebook',
				'Foxiz_Ad_Script',
				'Foxiz_W_Ruby_Template',
			];

			foreach ( $widgets as $widget ) {
				if ( class_exists( $widget ) ) {
					register_widget( $widget );
				}
			}

			return false;
		}

		/**
		 * @param $network
		 */
		public function activation( $network ) {
			if ( is_multisite() && $network ) {
				global $wpdb;
				$blogs_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
				foreach ( $blogs_ids as $blog_id ) {
					switch_to_blog( (int) $blog_id );
					$this->create_db();
					restore_current_blog();
				}
			} else {
				$this->create_db();
			}
		}

		public function create_db() {
			new Foxiz_Personalize_Db();
		}

	}
}

/** LOAD */
FOXIZ_CORE::get_instance();