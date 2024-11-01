<?php
/*
 * Plugin Name:       Frontend Post Submission
 * Plugin URI:        https://themeruby.com
 * Description:       A lightweight and user-friendly WordPress plugin designed to let users submit content from the frontend with ease.
 * Version:           1.0.0
 * Author:            Theme-Ruby
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author URI:        https://themeruby.com
 * Text Domain:       ruby-submission
 * Domain Path:       /languages/
 */

defined( 'ABSPATH' ) || exit;

define( 'RUBY_SUBMISSION_VERSION', '1.0.0' );
define( 'RUBY_SUBMISSION_PATH', plugin_dir_path( __FILE__ ) );
define( 'RUBY_SUBMISSION_URL', plugin_dir_url( __FILE__ ) );

require_once RUBY_SUBMISSION_PATH . 'admin/load.php';
include_once RUBY_SUBMISSION_PATH . 'includes/db-migration.php';
include_once RUBY_SUBMISSION_PATH . 'includes/helper.php';
include_once RUBY_SUBMISSION_PATH . 'includes/translation-string.php';
require_once RUBY_SUBMISSION_PATH . 'includes/form-shortcode.php';
require_once RUBY_SUBMISSION_PATH . 'includes/client-ajax-handler.php';

if ( ! function_exists( 'ruby_submission_load_textdomain' ) ) {
	function ruby_submission_load_textdomain() {

		load_plugin_textdomain( 'ruby-submission', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

add_action( 'init', 'ruby_submission_load_textdomain' );
register_activation_hook( __FILE__, 'ruby_submission_migrate_db' );
