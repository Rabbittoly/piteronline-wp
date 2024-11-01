<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	include_once RUBY_SUBMISSION_PATH . 'admin/version.php';
	include_once RUBY_SUBMISSION_PATH . 'admin/admin-menu.php';
	include_once RUBY_SUBMISSION_PATH . 'admin/translation-string.php';
	require_once RUBY_SUBMISSION_PATH . 'admin/ajax-handler.php';
}