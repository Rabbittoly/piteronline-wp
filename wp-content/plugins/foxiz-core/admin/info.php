<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Admin_Information' ) ) {
	class Foxiz_Admin_Information {

		private static $instance;
		private $taxonomy;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function get_current_taxonomy() {

			if ( wp_doing_ajax() ) {
				if ( isset( $_POST['taxonomy'] ) && is_string( $_POST['taxonomy'] ) ) {
					return sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) );
				}
			} elseif ( isset( $_GET['taxonomy'] ) && is_string( $_GET['taxonomy'] ) ) {
				return sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) );
			}

			return null;
		}

		public function __construct() {

			self::$instance = $this;

			$this->taxonomy = $this->get_current_taxonomy();
			add_action( 'admin_notices', [ $this, 'demo_image_notices' ], 20 );
			add_action( 'in_plugin_update_message-' . RB_THEME_ID . '-core/' . RB_THEME_ID . '-core.php', [
				$this,
				'version_update_warning',
			] );
			if ( ! empty( $this->taxonomy ) ) {
				add_filter( 'manage_edit-' . $this->taxonomy . '_columns', [ $this, 'add_columns' ] );
				add_filter( 'manage_edit-' . $this->taxonomy . '_sortable_columns', [ $this, 'sortable_columns' ] );
				add_filter( 'manage_' . $this->taxonomy . '_custom_column', [ $this, 'column_content' ], 10, 3 );
			}
		}

		function add_columns( $columns ) {

			$new_columns = [];
			foreach ( $columns as $key => $value ) {
				$new_columns[ $key ] = $value;
				if ( $key === 'slug' ) {
					$new_columns['term_id'] = 'Term ID';
				}
			}

			return $new_columns;
		}

		public function sortable_columns( $sortable_columns ) {

			$sortable_columns['term_id'] = 'term_id';

			return $sortable_columns;
		}

		function column_content( $content, $column_name, $term_id ) {

			if ( $column_name === 'term_id' ) {
				return $term_id;
			}

			return $content;
		}

		public function demo_image_notices() {

			$current_screen = get_current_screen();
			if ( ! $current_screen || $current_screen->id !== 'foxiz_page_ruby-options' ) {
				return;
			}

			$demo     = 'themeruby.com';
			$settings = get_option( FOXIZ_TOS_ID );
			$buffer   = '';
			foreach ( $settings as $key => $item ) {
				if ( ! empty( $item['url'] ) && strpos( $item['url'], $demo ) !== false ) {
					$buffer .= '<li><strong>' . $this->info( $key ) . ': </strong><span class="url-info">' . $item['url'] . '</span></li>';
				}
			}

			if ( empty( $buffer ) ) {
				return;
			}

			echo '<div class="notice notice-warning rb-setting-warning is-dismissible">';
			echo '<h3 class="rb-setting-warning-title"><i class="rbi-dash rbi-dash-info"></i>' . esc_html__( 'IMPORTANT: Please update image settings after importing a demo!', 'foxiz-core' ) . '</h3>';
			echo '<p class="rb-setting-warning-desc">' . esc_html__( 'After importing the demo, some images including SVG icons like search bars and logos may still link to the demo server. These images can negatively affect your website\'s SEO ranking and loading speed. To enhance performance, we strongly recommend replacing them with images hosted on your own server. Please ensure the following settings are updated:', 'foxiz-core' ) . '</p><ul>' . $buffer . '</ul>';
			echo '</div>';
		}

		function info( $key ) {

			$data = [
				'logo'                       => esc_html__( 'Logo > Default Logos > Main Logo', 'foxiz-core' ),
				'dark_logo'                  => esc_html__( 'Logo > Default Logos > Dark Mode - Main Logo', 'foxiz-core' ),
				'mobile_logo'                => esc_html__( 'Logo > Mobile Logos > Mobile Logo', 'foxiz-core' ),
				'dark_mobile_logo'           => esc_html__( 'Logo > Mobile Logos > Dark Mode - Mobile Logo', 'foxiz-core' ),
				'transparent_logo'           => esc_html__( 'Logo > Transparent Logos > Transparent Logo', 'foxiz-core' ),
				'logo_organization'          => esc_html__( 'Logo > Organization Logo > Organization Logo', 'foxiz-core' ),
				'icon_touch_apple'           => esc_html__( 'Logo > Bookmarklet > iOS Bookmarklet Icon', 'foxiz-core' ),
				'icon_touch_metro'           => esc_html__( 'Logo > Bookmarklet > Metro UI Bookmarklet Icon', 'foxiz-core' ),
				'ad_top_image'               => esc_html__( 'Ads & Slide Up > Top Site > Ad Image', 'foxiz-core' ),
				'ad_top_dark_image'          => esc_html__( 'Ads & Slide Up > Top Site > Dark Mode - Ad Image', 'foxiz-core' ),
				'ad_single_image'            => esc_html__( 'Ads & Slide Up > Inline Single Content > Ad Image', 'foxiz-core' ),
				'ad_single_dark_image'       => esc_html__( 'Ads & Slide Up > Inline Single Content > Dark Mode - Ad Image', 'foxiz-core' ),
				'amp_footer_logo'            => esc_html__( 'AMP > General > AMP Footer Logo', 'foxiz-core' ),
				'page_404_featured'          => esc_html__( '404 Page > Header Image', 'foxiz-core' ),
				'page_404_dark_featured'     => esc_html__( '404 Page > Dark Mode - Header Image', 'foxiz-core' ),
				'saved_image'                => esc_html__( 'Personalize > Reading List Header > Description Image', 'foxiz-core' ),
				'saved_image_dark'           => esc_html__( 'Personalize > Reading List Header > Dark Mode - Description Image', 'foxiz-core' ),
				'interest_image'             => esc_html__( 'Personalize > User Interests > Categories > Description Image', 'foxiz-core' ),
				'interest_image_dark'        => esc_html__( 'Personalize > User Interests > Categories > Dark Mode - Description Image', 'foxiz-core' ),
				'interest_author_image'      => esc_html__( 'Personalize > User Interests > Authors > Description Image', 'foxiz-core' ),
				'interest_author_image_dark' => esc_html__( 'Personalize > User Interests > Authors > Dark Mode - Description Image', 'foxiz-core' ),
				'footer_logo'                => esc_html__( 'Footer > Footer Logo', 'foxiz-core' ),
				'dark_footer_logo'           => esc_html__( 'Footer > Dark Mode - Footer Logo', 'foxiz-core' ),
				'header_search_custom_icon'  => esc_html__( 'Header > Header Search > Custom Search SVG', 'foxiz-core' ),
				'notification_custom_icon'   => esc_html__( 'Header > Header Search > Custom Notification SVG', 'foxiz-core' ),
				'login_custom_icon'          => esc_html__( 'Header > Popup Sign In > Custom Login SVG', 'foxiz-core' ),
				'cart_custom_icon'           => esc_html__( 'Header > Mini Cart > Custom Cart SVG Icon', 'foxiz-core' ),
				'header_login_logo'          => esc_html__( 'Login > Popup Sign In > Form Logo', 'foxiz-core' ),
				'header_login_dark_logo'     => esc_html__( 'Login > Popup Sign In > Dark Mode - Form Logo', 'foxiz-core' ),
				'login_screen_logo'          => esc_html__( 'Login > Login Screen Layout > Login Logo', 'foxiz-core' ),
				'newsletter_cover'           => esc_html__( 'Popup Newsletter > Cover Image', 'foxiz-core' ),
				'facebook_default_img'       => esc_html__( 'SEO Optimized > Fallback Share Image', 'foxiz-core' ),
				'single_post_review_image'   => esc_html__( 'Single Post > Review & Rating > Default Review Image', 'foxiz-core' ),
				'podcast_custom_icon'        => esc_html__( 'Podcast > General > Custom Podcast SVG', 'foxiz-core' ),
			];

			if ( ! empty( $data[ $key ] ) ) {
				return $data[ $key ];
			}

			return esc_html__( 'External link', 'foxiz' );
		}

		public function version_update_warning() {

			$output = '<hr class="rb-update-separator">';
			$output .= '<div class="rb-update-warning">';
			$output .= '<div class="rb-update-warning-title"><i class="dashicons-before dashicons-info"></i>' . esc_html__( 'The Foxiz Core needs to be updated!', 'foxiz' ) . '</div>';
			$output .= '<div>' . sprintf( 'Please update the theme before updating Foxiz Core. We strongly advise  %1$sbacking up your site%2$s to ensure the safety of your data, and make sure you first update in a staging environment.', '<a href="https://help.themeruby.com/foxiz/backup-restore-website-data/">', '</a>' ) . '</div>';
			$output .= '</div>';

			echo $output;
		}
	}
}
