<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', [ 'Foxiz_Register_Podcast', 'get_instance' ], 4 );

if ( ! class_exists( 'Foxiz_Register_Podcast' ) ) {
	class Foxiz_Register_Podcast {

		protected static $instance = null;
		static $settings = [];
		private $slug, $series_plug;

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;
			$this->get_settings();

			if ( ! empty( self::$settings['podcast_supported'] ) ) {
				add_action( 'init', [ $this, 'register' ], 2 );
				if ( foxiz_is_plugin_active( 'elementor/elementor.php' ) ) {
					add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ], 2 );
				}
			}
		}

		public function get_settings() {

			self::$settings = foxiz_get_option();

			$this->slug        = 'podcast';
			$this->series_plug = 'series';

			if ( ! empty( self::$settings['podcast_slug'] ) ) {
				$this->slug = self::$settings['podcast_slug'];
			}
			if ( ! empty( self::$settings['series_slug'] ) ) {
				$this->series_plug = self::$settings['series_slug'];
			}
		}

		public function register() {

			register_post_type( 'podcast', [
				'labels'              => [
					'name'                  => esc_html__( 'Episodes', 'foxiz-core' ),
					'all_items'             => esc_html__( 'All Episodes', 'foxiz-core' ),
					'menu_name'             => esc_html__( 'Ruby Podcast', 'foxiz-core' ),
					'singular_name'         => esc_html__( 'Episode', 'foxiz-core' ),
					'add_new'               => esc_html__( 'Add New Episode', 'foxiz-core' ),
					'add_item'              => esc_html__( 'New Episode', 'foxiz-core' ),
					'add_new_item'          => esc_html__( 'Add New Episode', 'foxiz-core' ),
					'new_item'              => esc_html__( 'Add New Episode', 'foxiz-core' ),
					'edit_item'             => esc_html__( 'Edit Episode', 'foxiz-core' ),
					'not_found'             => esc_html__( 'No episode item found.', 'foxiz-core' ),
					'not_found_in_trash'    => esc_html__( 'No episode item found in Trash.', 'foxiz-core' ),
					'view_item'             => esc_html__( 'View Episode', 'foxiz-core' ),
					'view_items'            => esc_html__( 'View Episodes', 'foxiz-core' ),
					'search_items'          => esc_html__( 'Search Episodes', 'foxiz-core' ),
					'filter_items_list'     => esc_html__( 'Filter Episode list', 'foxiz-core' ),
					'items_list_navigation' => esc_html__( 'Episode list navigation', 'foxiz-core' ),
					'items_list'            => esc_html__( 'Episode list', 'foxiz-core' ),
					'parent_item_colon'     => '',
				],
				'public'              => true,
				'hierarchical'        => false,
				'publicly_queryable'  => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'has_archive'         => true,
				'can_export'          => true,
				'capability_type'     => 'post',
				'exclude_from_search' => false,
				'show_in_rest'        => true,
				'menu_position'       => 6,
				'show_ui'             => true,
				'menu_icon'           => 'dashicons-microphone',
				'rewrite'             => [
					'slug'       => $this->slug,
					'feeds'      => true,
					'with_front' => false,
				],
				'supports'            => [
					'title',
					'editor',
					'excerpt',
					'thumbnail',
					'author',
					'comments',
					'revisions',
				],
				'taxonomies'          => [ 'series', 'post_tag' ],
			] );

			register_taxonomy( 'series', [ 'podcast' ], [
				'hierarchical'      => true,
				'labels'            => [
					'name'          => esc_html__( 'Shows', 'foxiz-core' ),
					'menu_name'     => esc_html__( 'Shows', 'foxiz-core' ),
					'singular_name' => esc_html__( 'Show', 'foxiz-core' ),
					'search_items'  => esc_html__( 'Search Show', 'foxiz-core' ),
					'all_items'     => esc_html__( 'Shows', 'foxiz-core' ),
					'parent_item'   => esc_html__( 'Parent Show', 'foxiz-core' ),
					'edit_item'     => esc_html__( 'Edit Show', 'foxiz-core' ),
					'update_item'   => esc_html__( 'Update Show', 'foxiz-core' ),
					'view_item'     => esc_html__( 'View Show', 'foxiz-core' ),
					'add_new_item'  => esc_html__( 'Add New Show', 'foxiz-core' ),
					'new_item_name' => esc_html__( 'New Show Name', 'foxiz-core' ),
				],
				'show_ui'           => true,
				'query_var'         => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => [ 'slug' => $this->series_plug ],
			] );

			flush_rewrite_rules();
		}

		private function load_files() {

			require_once FOXIZ_CORE_PATH . 'podcast/podcast-grid-flex.php';
			require_once FOXIZ_CORE_PATH . 'podcast/podcast-list-flex.php';
			require_once FOXIZ_CORE_PATH . 'podcast/podcast-overlay-flex.php';
		}

		function register_widgets() {

			$this->load_files();

			$widgets = [
				'Podcast_Grid_Flex_1',
				'Podcast_List_Flex_1',
				'Podcast_Overlay_Flex_1',
			];

			foreach ( $widgets as $widget ) {
				$widget_name = 'foxizElementor\Widgets\\' . $widget;
				if ( class_exists( $widget_name ) ) {
					\Elementor\Plugin::instance()->widgets_manager->register( new $widget_name() );
				}
			}
		}
	}
}