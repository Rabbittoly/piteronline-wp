<?php
/**
 * Plugin Name:    Ruby bbPress Supported
 * Plugin URI:     https://themeforest.net/user/theme-ruby/
 * Description:    bbPress custom styles and functions.
 * Version:        1.1
 * Text Domain:    ruby-bbp-supported
 * Domain Path:    /languages/
 * Author:         Theme-Ruby
 * Author URI:     https://themeforest.net/user/theme-ruby/
 *
 * @package        pixwell-deal
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RB_BBP_SUPPORTED', '1.1' );
define( 'RB_BBP_URL', plugin_dir_url( __FILE__ ) );
define( 'RB_BBP_PATH', plugin_dir_path( __FILE__ ) );

include_once RB_BBP_PATH . 'includes/settings.php';

if ( ! class_exists( 'Ruby_BBP_Supported' ) ) {
	class Ruby_BBP_Supported {

		protected static $instance = null;

		static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;

			if ( ! class_exists( 'bbpress' ) ) {
				return;
			}

			include_once RB_BBP_PATH . 'includes/helper.php';
			include_once RB_BBP_PATH . 'includes/actions.php';
			include_once RB_BBP_PATH . 'templates/parts.php';

			$this->translation();

			if ( is_admin() && current_user_can( 'manage_options' ) ) {
				Ruby_BBP_Settings::get_instance();
			}

			add_post_type_support( 'forum', [ 'thumbnail' ] );
			add_filter( 'bbp_default_styles', [ $this, 'remove_bbp_style' ], 10, 1 );
			add_action( 'bbp_enqueue_scripts', [ $this, 'enqueue' ], 99 );
			add_filter( 'template_include', [ $this, 'bbpress_redirect' ], 99 );
			add_filter( 'bbp_template_stack', [ $this, 'location' ], 9 );
			add_filter( 'bbp_kses_allowed_tags', [ $this, 'remove_tags' ], 10, 1 );
			add_filter( 'bbp_show_lead_topic', [ $this, 'show_lead_topic' ], 10, 1 );
		}

		function show_lead_topic( $show_lead ) {

			if ( get_option( 'ruby_bbp_lead_topic' ) ) {
				$show_lead[] = true;
			}

			return $show_lead;
		}

		function remove_tags( $tags ) {

			if ( ! current_user_can( 'moderate' ) ) {
				/** fix break layout if without ol */
				if ( isset( $tags['li'] ) ) {
					unset( $tags['li'] );
				}
				if ( isset( $tags['ul'] ) ) {
					unset( $tags['ul'] );
				}
				if ( isset( $tags['ol'] ) ) {
					unset( $tags['ol'] );
				}
			}

			return $tags;
		}

		function enqueue() {

			if ( ! is_bbpress() ) {
				return false;
			}

			$file_name = 'style';
			if ( is_rtl() ) {
				$file_name = 'rtl';
			}

			wp_register_style( 'ruby-bbp-icon', RB_BBP_URL . 'assets/icons.css', [], RB_BBP_SUPPORTED );
			wp_register_style( 'ruby-bbp', RB_BBP_URL . 'assets/' . $file_name . '.css', [ 'ruby-bbp-icon' ], RB_BBP_SUPPORTED );
			wp_register_script( 'ruby-bbp', RB_BBP_URL . 'assets/scripts.js', [ 'jquery' ], RB_BBP_SUPPORTED, true );

			/** load */
			wp_enqueue_style( 'ruby-bbp' );
			wp_enqueue_script( 'ruby-bbp' );
		}

		function remove_bbp_style( $defaults ) {

			return [];
		}

		function location() {

			return RB_BBP_PATH . 'templates';
		}

		public function bbpress_redirect( $template ) {

			if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {

				/** allow set elementor template */
				if ( class_exists( 'Elementor\Plugin' ) && is_page() ) {
					$template_slug = get_page_template_slug();
					if ( substr( $template_slug, 0, 9 ) === 'elementor' ) {
						return $template;
					}
				}

				$template = RB_BBP_PATH . 'templates/single-bbp.php';
			}

			return $template;
		}

		function translation() {

			$loaded = load_plugin_textdomain( 'ruby-bbp', false, RB_BBP_PATH . 'languages/' );
			if ( ! $loaded ) {
				$locale = apply_filters( 'plugin_locale', get_locale(), 'ruby-bbp' );
				$mofile = RB_BBP_PATH . 'languages/ruby-bbp-supported' . $locale . '.mo';
				load_textdomain( 'ruby-bbp', $mofile );
			}
		}
	}
}

/** LOAD */
add_action( 'plugins_loaded', [ 'Ruby_BBP_Supported', 'get_instance' ], 10 );
