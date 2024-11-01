<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Importer' ) ) {
	class Ruby_Importer {

		private static $instance;
		private static $nonce = 'foxiz-admin';
		static $progress_percent_key = 'rb_import_progress_percent';
		static $progress_label_key = 'rb_import_progress_label';
		static $imported_key = 'rb_imported_content';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'wp_ajax_rb_importer_update', [ $this, 'update_importer' ] );
			add_action( 'wp_ajax_rb_cleanup_content', [ $this, 'cleanup_content' ] );
			add_action( 'wp_ajax_rb_importer_plugin', [ $this, 'init_plugins' ] );
			add_action( 'wp_ajax_rb_importer', [ $this, 'init' ] );
			add_action( 'wp_ajax_rb_import_progress', [ $this, 'get_import_progress' ] );
			add_action( 'rb_importer_before_content', [ $this, 'register_podcast' ], 20 );
			add_action( 'rb_importer_before_widgets', [ $this, 'register_demo_widgets' ] );
			add_action( 'rb_importer_content_settings', [ $this, 'after_import_content' ] );
		}

		/** load file */
		public function load_files() {

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			require_once plugin_dir_path( __FILE__ ) . 'lib/radium-importer.php';
			require_once plugin_dir_path( __FILE__ ) . 'init.php';
		}

		/** get demos */
		function get_demos() {

			return RB_ADMIN_CORE::get_instance()->get_imports();
		}

		/**
		 * @return string
		 */
		function register_tos_id() {

			if ( ! defined( 'FOXIZ_TOS_ID' ) ) {
				return 'RUBY_OPTIONS';
			}

			return FOXIZ_TOS_ID;
		}

		public function update_importer() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			wp_send_json( RB_ADMIN_CORE::get_instance()->update_importer( true ), 200 );

			wp_die();
		}

		/** clean up content */
		public function cleanup_content() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			$content = isset( $_POST['cleanup'] ) ? sanitize_key( $_POST['cleanup'] ) : '';

			if ( empty( $content ) ) {
				wp_send_json_error( esc_html__( 'Configuration is incorrect.', 'foxiz-core' ), 400 );

				wp_die();
			}

			$imported = get_option( self::$imported_key, [] );
			add_action( 'before_delete_post', [ $this, 'before_delete_post' ], 0 );

			if ( 'all' === $content ) {
				if ( empty( $imported['posts'] ) || is_array( $imported['posts'] ) ) {
					foreach ( $imported['posts'] as $post_id ) {
						wp_delete_post( (int) $post_id, true );
					}
				}
			} else {
				if ( empty( $imported['posts'] ) || is_array( $imported['posts'] ) ) {
					foreach ( $imported['posts'] as $post_id ) {
						$post_type = get_post_type( $post_id );
						if ( 'post' === $post_type || 'attachment' === $post_type ) {
							wp_delete_post( (int) $post_id, true );
						}
					}
				}
			}
			if ( empty( $imported['categories'] ) || is_array( $imported['categories'] ) ) {
				foreach ( $imported['categories'] as $term_id ) {
					wp_delete_term( (int) $term_id, 'category' );
				}
			}
			if ( empty( $imported['tags'] ) || is_array( $imported['tags'] ) ) {
				foreach ( $imported['tags'] as $term_id ) {
					wp_delete_term( (int) $term_id, 'post_tag' );
				}
			}

			wp_send_json_success();

			wp_die();
		}

		/** importer */
		public function init_plugins() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';
			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			$settings = ( isset( $_POST['settings'] ) ) ? $_POST['settings'] : [];

			if ( empty( $settings ) ) {
				wp_send_json_error( esc_html__( 'Configuration is incorrect.', 'foxiz-core' ), 400 );

				wp_die();
			}

			$plugins = ( isset( $settings['plugins'] ) && is_array( $settings['plugins'] ) ) ? array_map( 'sanitize_text_field', $settings['plugins'] ) : [];

			$this->load_files();
			$this->clear_import_progress();
			$this->install_plugins( $plugins );
			wp_send_json_success( '' );

			wp_die();
		}

		public function init() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

				wp_die();
			}

			$settings = ( isset( $_POST['settings'] ) ) ? $_POST['settings'] : [];

			if ( empty( $settings ) ) {
				wp_send_json_error( esc_html__( 'Configuration is incorrect.', 'foxiz-core' ), 400 );

				wp_die();
			}

			$this->load_files();
			$demos = $this->get_demos();

			$directory      = isset( $settings['directory'] ) ? sanitize_key( $settings['directory'] ) : '';
			$import_all     = isset( $settings['import_all'] ) ? filter_var( $settings['import_all'], FILTER_VALIDATE_BOOLEAN ) : false;
			$import_content = isset( $settings['import_content'] ) ? filter_var( $settings['import_content'], FILTER_VALIDATE_BOOLEAN ) : false;
			$import_pages   = isset( $settings['import_pages'] ) ? filter_var( $settings['import_pages'], FILTER_VALIDATE_BOOLEAN ) : false;
			$import_opts    = isset( $settings['import_opts'] ) ? filter_var( $settings['import_opts'], FILTER_VALIDATE_BOOLEAN ) : false;
			$import_widgets = isset( $settings['import_widgets'] ) ? filter_var( $settings['import_widgets'], FILTER_VALIDATE_BOOLEAN ) : false;
			$clean_up       = isset( $settings['clean_up'] ) ? filter_var( $settings['clean_up'], FILTER_VALIDATE_BOOLEAN ) : false;
			$plugins        = ( isset( $settings['plugins'] ) && is_array( $settings['plugins'] ) ) ? array_map( 'sanitize_text_field', $settings['plugins'] ) : [];

			if ( ! isset( $demos[ $directory ] ) ) {
				wp_send_json_error( esc_html__( 'Demo name not found.', 'foxiz-core' ), 400 );

				wp_die();
			}

			$demo = $demos[ $directory ];

			$params = [
				'directory'         => $directory,
				'theme_option_name' => $this->register_tos_id(),
				'import_all'        => $import_all,
				'import_content'    => $import_content,
				'import_pages'      => $import_pages,
				'import_opts'       => $import_opts,
				'import_widgets'    => $import_widgets,
			];

			$params['theme_options'] = isset( $demo['theme_options'] ) ? $demo['theme_options'] : null;
			$params['taxonomies']    = isset( $demo['taxonomies'] ) ? $demo['taxonomies'] : null;
			$params['post_types']    = isset( $demo['post_types'] ) ? $demo['post_types'] : null;
			$params['content']       = isset( $demo['content'] ) ? $demo['content'] : null;
			$params['pages']         = isset( $demo['pages'] ) ? $demo['pages'] : null;
			$params['widgets']       = isset( $demo['widgets'] ) ? $demo['widgets'] : null;

			/** clear progress info */
			if ( empty( $plugins ) ) {
				$this->clear_import_progress();
			}

			if ( $clean_up ) {
				add_action( 'before_delete_post', [ $this, 'before_delete_post' ], 0 );
				$this->delete_imported_content();
			}

			$this->before_import();
			new RB_INIT_IMPORTER( $params );
			wp_send_json( 'Have fun!', 200 );
			wp_die();
		}

		function before_delete_post() {

			remove_all_actions( 'before_delete_post' );
		}

		/**
		 * @param array $plugins
		 */
		public function install_plugins( $plugins = [] ) {

			foreach ( $plugins as $plugin ) {
				RB_ADMIN_CORE::get_instance()->importer_plugin( $plugin );
			}
		}

		/** import progress */
		public function before_import() {

			add_filter( 'wp_import_posts', [ $this, 'count_total_posts' ], 10, 1 );
			add_filter( 'wp_import_post_data_raw', [ $this, 'update_progress_importing_post' ], 10, 1 );
		}

		public function count_total_posts( $posts ) {

			$remain_percent = max( 1, 100 - (float) get_option( self::$progress_percent_key ) );
			set_transient( 'rb_import_total_posts', max( count( $posts ), 1 ), 3600 );
			set_transient( 'rb_import_remain_percent', $remain_percent, 3600 );

			return $posts;
		}

		public function update_progress_importing_post( $post ) {

			$total  = get_transient( 'rb_import_total_posts' );
			$remain = get_transient( 'rb_import_remain_percent' );

			if ( $total > 1 ) {
				self::save_import_progress( '', floatval( $remain * 2 / $total ) );
			}

			return $post;
		}

		/**
		 * @param        $id
		 * @param string $type
		 */
		static function mark_as_imported( $id, $type = 'post' ) {

			$imported = get_option( self::$imported_key, [ 'posts' => [], 'categories' => [], 'tags' => [] ] );

			if ( 'post' === $type ) {
				$imported['posts'][] = $id;
			} elseif ( 'category' === $type ) {
				$imported['categories'][] = $id;
			} elseif ( 'tag' === $type ) {
				$imported['tags'][] = $id;
			}

			update_option( self::$imported_key, $imported );
		}

		function delete_imported_content() {

			self::save_import_progress( esc_html__( 'Purge dummy content...', 'foxiz-core' ), 5 );

			$imported = get_option( self::$imported_key, [] );

			if ( empty( $imported['posts'] ) || is_array( $imported['posts'] ) ) {
				foreach ( $imported['posts'] as $post_id ) {
					wp_delete_post( (int) $post_id, true );
				}
			}
			if ( empty( $imported['categories'] ) || is_array( $imported['categories'] ) ) {
				foreach ( $imported['categories'] as $term_id ) {
					wp_delete_term( (int) $term_id, 'category' );
				}
			}

			if ( empty( $imported['tags'] ) || is_array( $imported['tags'] ) ) {
				foreach ( $imported['tags'] as $term_id ) {
					wp_delete_term( (int) $term_id, 'post_tag' );
				}
			}
		}

		/**
		 * @param string $directory
		 */
		function after_import_content( $directory = '' ) {

			$demos = $this->get_demos();
			self::save_import_progress( esc_html__( 'Finishing...', 'foxiz-core' ), 0 );

			if ( ! empty( $demos[ $directory ]['homepage'] ) ) {
				$pages = get_posts( [
						'post_type'   => 'page',
						'title'       => $demos[ $directory ]['homepage'],
						'post_status' => 'all',
						'numberposts' => 1,
						'order'       => 'ASC',
					]
				);
				if ( ! empty( $pages[0]->ID ) ) {
					update_option( 'page_on_front', $pages[0]->ID );
					update_option( 'show_on_front', 'page' );
				}

				$blog = get_posts( [
						'post_type'   => 'page',
						'title'       => 'Blog',
						'post_status' => 'all',
						'numberposts' => 1,
						'order'       => 'ASC',
					]
				);

				if ( ! empty( $blog[0]->ID ) ) {
					update_option( 'page_for_posts', $blog[0]->ID );
				}
			} else {
				update_option( 'page_on_front', 0 );
				update_option( 'show_on_front', 'posts' );
			}

			/** setup WC */
			if ( class_exists( 'WC_Install' ) ) {
				WC_Install::create_pages();
			}

			/** setup menu */
			$main_menu   = get_term_by( 'name', 'main', 'nav_menu' );
			$mobile_menu = get_term_by( 'name', 'mobile', 'nav_menu' );
			$quick_menu  = get_term_by( 'name', 'mobile-quick-access', 'nav_menu' );

			$menu_locations = [];
			if ( isset( $main_menu->term_id ) ) {
				$menu_locations['foxiz_main'] = $main_menu->term_id;
			}
			if ( isset( $mobile_menu->term_id ) ) {
				$menu_locations['foxiz_mobile'] = $mobile_menu->term_id;
			}
			if ( isset( $quick_menu->term_id ) ) {
				$menu_locations['foxiz_mobile_quick'] = $quick_menu->term_id;
			}
			set_theme_mod( 'nav_menu_locations', $menu_locations );
		}

		function register_demo_widgets() {

			/** empty sidebars */
			$sidebars_widgets['foxiz_sidebar_default']          = [];
			$sidebars_widgets['foxiz_sidebar_more']             = [];
			$sidebars_widgets['foxiz_sidebar_fw_footer']        = [];
			$sidebars_widgets['foxiz_sidebar_footer_1']         = [];
			$sidebars_widgets['foxiz_sidebar_footer_2']         = [];
			$sidebars_widgets['foxiz_sidebar_footer_3']         = [];
			$sidebars_widgets['foxiz_sidebar_footer_4']         = [];
			$sidebars_widgets['foxiz_sidebar_footer_5']         = [];
			$sidebars_widgets['foxiz_entry_top']                = [];
			$sidebars_widgets['foxiz_entry_bottom']             = [];
			$sidebars_widgets['foxiz_sidebar_multi_sb1']        = [];
			$sidebars_widgets['foxiz_sidebar_multi_sb2']        = [];
			$sidebars_widgets['foxiz_sidebar_multi_next-posts'] = [];
			$sidebars_widgets['foxiz_sidebar_multi_single']     = [];
			$sidebars_widgets['foxiz_sidebar_multi_blog']       = [];
			$sidebars_widgets['foxiz_sidebar_multi_contact']    = [];

			/** add sidebars */
			$theme_options                   = get_option( FOXIZ_TOS_ID, [] );
			$theme_options['multi_sidebars'] = [ 'sb1', 'sb2', 'next-posts', 'single', 'blog', 'contact' ];
			update_option( 'sidebars_widgets', $sidebars_widgets );
			update_option( FOXIZ_TOS_ID, $theme_options );

			/** register sidebar to import */
			register_sidebar( [
				'name'          => 'More Menu Section',
				'id'            => 'foxiz_sidebar_more',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			register_sidebar( [
				'name'          => 'sb1',
				'id'            => 'foxiz_sidebar_multi_sb1',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			register_sidebar( [
				'name'          => 'sb2',
				'id'            => 'foxiz_sidebar_multi_sb2',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );
			register_sidebar( [
				'name'          => 'next-posts',
				'id'            => 'foxiz_sidebar_multi_next-posts',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			register_sidebar( [
				'name'          => 'single',
				'id'            => 'foxiz_sidebar_multi_single',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			register_sidebar( [
				'name'          => 'contact',
				'id'            => 'foxiz_sidebar_multi_contact',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			register_sidebar( [
				'name'          => 'blog',
				'id'            => 'foxiz_sidebar_multi_blog',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );

			return false;
		}

		/** register for import */
		function register_podcast() {

			if ( class_exists( 'Foxiz_Register_Podcast' ) ) {
				Foxiz_Register_Podcast::get_instance()->register();
			}
		}

		public function clear_import_progress() {

			delete_option( self::$progress_label_key );
			delete_option( self::$progress_percent_key );
			update_option( '_rb_flag_imported', true );
		}

		/**
		 * @param string $label
		 * @param int    $percent
		 */
		static function save_import_progress( $label = '', $percent = 0 ) {

			if ( ! empty( $label ) ) {
				update_option( self::$progress_label_key, $label );
			}

			$current_percent = (float) $percent + (float) get_option( self::$progress_percent_key, 0 );
			update_option( self::$progress_percent_key, min( 99, $current_percent ) );
		}

		public function get_import_progress() {

			$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

			if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

				wp_die();
			}

			wp_send_json_success(
				[
					'percent' => (int) get_option( self::$progress_percent_key, 0 ),
					'label'   => get_option( self::$progress_label_key, esc_html__( 'Installing...', 'foxiz-core' ) ),
				], 200
			);

			wp_die();
		}
	}
}