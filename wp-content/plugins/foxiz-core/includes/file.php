<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

/** CORE FUNCTIONS */
require_once FOXIZ_CORE_PATH . 'includes/core-functions.php';

/** LIBRARY */
if ( is_admin() && ! foxiz_is_plugin_active( 'redux-framework/redux-framework.php' ) ) {
	include_once FOXIZ_CORE_PATH . 'lib/redux-framework/framework.php';
}

/** ADMIN ONLY */
if ( is_admin() ) {
	require_once FOXIZ_CORE_PATH . 'admin/setting-helpers.php';
	require_once FOXIZ_CORE_PATH . 'admin/updater.php';
	require_once FOXIZ_CORE_PATH . 'lib/rb-meta/rb-meta.php';
	require_once FOXIZ_CORE_PATH . 'lib/taxonomy/taxonomy.php';

	require_once FOXIZ_CORE_PATH . 'admin/sub-pages.php';

	/** adobe fonts */
	require_once FOXIZ_CORE_PATH . 'admin/fonts/init.php';
	require_once FOXIZ_CORE_PATH . 'admin/fonts/ajax-helpers.php';

	require_once FOXIZ_CORE_PATH . 'admin/translation/init.php';
	require_once FOXIZ_CORE_PATH . 'admin/info.php';
	require_once FOXIZ_CORE_PATH . 'admin/core.php';

	/** importer */
	require_once FOXIZ_CORE_PATH . 'admin/import/ajax-helpers.php';

	/** importer */
	require_once FOXIZ_CORE_PATH . 'admin/gtm/ajax-helpers.php';
}

if ( ! class_exists( 'Foxiz_Post_Elements' ) ) {
	require_once FOXIZ_CORE_PATH . 'lib/foxiz-elements/foxiz-elements.php';
}

if ( is_admin() && ! class_exists( 'RB_OPENAI_ASSISTANT' ) ) {
	require_once FOXIZ_CORE_PATH . 'lib/ruby-openai/ruby-openai.php';
}

if ( foxiz_is_plugin_active( 'bbpress/bbpress.php' ) ) {
	require_once FOXIZ_CORE_PATH . 'lib/bbp/ruby-bbp-supported.php';
}

/** FUNCTIONS */
require_once FOXIZ_CORE_PATH . 'includes/function-helpers.php';

/** TEMPLATES */
require_once FOXIZ_CORE_PATH . 'includes/template-helpers.php';
require_once FOXIZ_CORE_PATH . 'includes/template-ads.php';
require_once FOXIZ_CORE_PATH . 'includes/template-share.php';
require_once FOXIZ_CORE_PATH . 'includes/template-socials.php';
require_once FOXIZ_CORE_PATH . 'includes/template-svg.php';
require_once FOXIZ_CORE_PATH . 'includes/template-widgets.php';
require_once FOXIZ_CORE_PATH . 'includes/gtm.php';

/** CLASSES */
require_once FOXIZ_CORE_PATH . 'includes/amp.php';
require_once FOXIZ_CORE_PATH . 'includes/optimized.php';
require_once FOXIZ_CORE_PATH . 'includes/shortcodes.php';
require_once FOXIZ_CORE_PATH . 'includes/table-contents.php';
require_once FOXIZ_CORE_PATH . 'includes/video-thumb.php';

/** PERSONALIZE */
require_once FOXIZ_CORE_PATH . 'personalize/database.php';
require_once FOXIZ_CORE_PATH . 'personalize/helpers.php';

/** REACTION */
require_once FOXIZ_CORE_PATH . 'reaction/reaction.php';

/** FRONTEND LOGIN */
require_once FOXIZ_CORE_PATH . 'frontend-login/templates.php';
require_once FOXIZ_CORE_PATH . 'frontend-login/login-screen.php';
require_once FOXIZ_CORE_PATH . 'frontend-login/init.php';

/** HOOKS */
require_once FOXIZ_CORE_PATH . 'includes/hooks.php';

/** RUBY TEMPLATE & ELEMENTOR */
if ( foxiz_is_plugin_active( 'elementor/elementor.php' ) ) {
	require_once FOXIZ_CORE_PATH . 'elementor/template-helpers.php';
	require_once FOXIZ_CORE_PATH . 'elementor/control.php';
	require_once FOXIZ_CORE_PATH . 'elementor/ruby-templates/init.php';
	require_once FOXIZ_CORE_PATH . 'elementor/dark-supported.php';
	require_once FOXIZ_CORE_PATH . 'elementor/base.php';
}

/** MEMBERSHIP SUPPORTED */
require_once FOXIZ_CORE_PATH . 'membership/membership.php';
require_once FOXIZ_CORE_PATH . 'membership/settings.php';

/** RECIPE MARKER SUPPORTED */
require_once FOXIZ_CORE_PATH . 'wprm/wprm.php';
require_once FOXIZ_CORE_PATH . 'wprm/settings.php';

/** PODCAST */
require_once FOXIZ_CORE_PATH . 'podcast/init.php';

/** WIDGETS */
require_once FOXIZ_CORE_PATH . 'widgets/banner.php';
require_once FOXIZ_CORE_PATH . 'widgets/fw-instagram.php';
require_once FOXIZ_CORE_PATH . 'widgets/fw-mc.php';
require_once FOXIZ_CORE_PATH . 'widgets/ruby-template.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-ad-image.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-ad-script.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-address.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-facebook.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-flickr.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-follower.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-instagram.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-post.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-social-icon.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-weather.php';
require_once FOXIZ_CORE_PATH . 'widgets/sb-youtube.php';