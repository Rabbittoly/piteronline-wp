<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'ruby_submission_migrate_db' ) ) {
	function ruby_submission_migrate_db( $network ) {

		if ( is_multisite() && $network ) {
			global $wpdb;
			$blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blogs as $blog_id ) {
				switch_to_blog( $blog_id );
				ruby_submission_create_database();
				restore_current_blog();
			}
		} else {
			ruby_submission_create_database();
		}
	}
}

if ( ! function_exists( 'ruby_submission_create_database' ) ) {
	function ruby_submission_create_database() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( '
				CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'rb_submission (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(50) NOT NULL,
					`data` text NOT NULL,
					PRIMARY KEY (`id`)
				) ' . $charset_collate . ';'
		);
	}
}


