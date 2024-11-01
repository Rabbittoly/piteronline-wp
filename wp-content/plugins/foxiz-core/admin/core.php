<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

class RB_ADMIN_CORE {

	static $panel_slug = 'admin/templates/template';
	static $header = 'header';
	static $panel_title = '';
	static $icon = 'dashicons-awards';
	static $recommended_plugins = [];
	protected static $instance = null;
	private static $theme_id = null;
	private static $core_plugin_id = null;
	private static $key = null;
	private static $license = null;
	private static $sub_pages;
	private static $dashboard_slug = 'foxiz-admin';
	private static $nonce = 'foxiz-admin';
	public $panel_name = 'dashboard';
	public $panel_template = 'admin_template';
	private $activation = FOXIZ_ACTIVATION_ID;
	private $params = [];
	private $purchase_info = FOXIZ_LICENSE_ID;
	private $import_info = FOXIZ_IMPORT_ID;
	private $apiSever = RB_API_URL . '/wp-json/market/validate';

	public function __construct() {

		self::$instance = $this;
		add_action( 'plugins_loaded', [ $this, 'init' ], 0 );
	}

	static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function init() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		self::$panel_title = esc_html__( 'Foxiz', 'foxiz-core' );
		$this->get_configs();
		$this->subpage_files();
		$this->get_params();

		add_filter( 'http_request_args', [ $this, 'is_premium' ], 99999, 2 );

		add_action( 'admin_menu', [ $this, 'register_dashboard' ], 0 );
		add_action( 'admin_menu', [ $this, 'register_subpages' ], 50 );
		add_action( 'admin_menu', [ $this, 'register_system_info' ], PHP_INT_MAX );

		add_action( 'rb_scheduled_c', [ $this, 'validate' ] );
		add_action( 'wp_ajax_rb_register_theme', [ $this, 'register_theme' ] );
		add_action( 'wp_ajax_rb_deregister_theme', [ $this, 'deregister_theme' ] );
		add_action( 'wp_ajax_rb_recommended_plugin', [ $this, 'recommended_plugin' ] );
		add_action( 'redux/' . FOXIZ_TOS_ID . '/panel/before', [ $this, 'header_template' ] );
		add_action( 'admin_init', [ 'Foxiz_Admin_Information', 'get_instance' ], 25 );
		add_action( 'admin_init', [ 'Ruby_Importer', 'get_instance' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ], 15 );

		if ( ! $this->is_local() ) {
			add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'update_core_plugin' ], 5, 1 );
			add_filter( 'pre_set_transient_update_plugins', [ $this, 'update_core_plugin' ], 5, 1 );
			add_filter( 'pre_set_site_transient_update_themes', [ $this, 'update_theme' ], 1, 1 );
			add_filter( 'pre_set_transient_update_themes', [ $this, 'update_theme' ], 1, 1 );
			add_action( 'redux/page/' . FOXIZ_TOS_ID . '/load', [ $this, 'scheduled' ] );
		}
	}

	public function get_purchase_data() {

		return get_option( $this->purchase_info );
	}

	public function set_sub_pages() {

		$args = [];

		if ( $this->get_purchase_code() ) {
			$args[] = [
				'class' => 'Import',
				'path'  => 'admin/import/import.php',
			];
			$args[] = [
				'class' => 'Translation',
				'path'  => 'admin/translation/translation.php',
			];
			$args[] = [
				'class' => 'AdobeFonts',
				'path'  => 'admin/fonts/fonts.php',
			];
			$args[] = [
				'class' => 'GTM',
				'path'  => 'admin/gtm/gtm.php',
			];
		}

		return $args;
	}

	/** get purchase code */
	public function get_purchase_code() {

		return isset( self::$license['purchase_code'] ) ? self::$license['purchase_code'] : false;
	}

	public function get_expiration() {

		return isset( self::$license['expiration'] ) ? self::$license['expiration'] : false;
	}

	/** load files */
	public function subpage_files() {

		$pages = self::$sub_pages;

		array_push( $pages, [
			'class' => 'SystemInfo',
			'path'  => 'admin/system-info/system-info.php',
		] );

		foreach ( $pages as $sub_page ) {
			if ( ! empty( $sub_page['path'] ) ) {
				require_once FOXIZ_CORE_PATH . $sub_page['path'];
			}
		}
	}

	public function get_params() {

		$this->params = wp_parse_args( self::$license, [
			'title'               => esc_html__( 'Foxiz', 'foxiz-core' ),
			'purchase_code'       => '',
			'is_activated'        => '',
			'system_info'         => $this->get_system_info(),
			'menu'                => $this->get_dashboard_menu(),
			'expiration'          => '',
			'step'                => get_option( '_foxiz_setup_current_step', 1 ),
			'recommended_plugins' => self::$recommended_plugins,
			'can_install_plugins' => current_user_can( 'install_plugins' ),
		] );

		if ( ! $this->params['is_activated'] ) {
			$this->panel_name = 'register';
		}

		if ( $this->get_key_val( 'title' ) ) {
			unset( $this->params['is_activated'] );
		}
	}

	public function __clone() {

		_doing_it_wrong( __FUNCTION__, esc_html__( 'Not allowed!', 'foxiz-core' ), FOXIZ_CORE_VERSION );
	}

	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, esc_html__( 'Not allowed!', 'foxiz-core' ), FOXIZ_CORE_VERSION );
	}

	public function scheduled() {

		wp_schedule_single_event( time(), 'rb_scheduled_c' );
		if ( ! wp_next_scheduled( 'rb_scheduled_c' ) ) {
			wp_schedule_single_event( time() + 3 * DAY_IN_SECONDS, 'rb_scheduled_c' );
		}
	}

	public function set_option( $id, $value ) {

		update_option( self::$theme_id . '_' . $id, $value );
	}

	/** register dashboard */
	public function register_dashboard() {

		if ( ! defined( 'FOXIZ_THEME_VERSION' ) ) {
			return;
		}

		$panel_hook_suffix = add_menu_page( self::$panel_title, self::$panel_title, 'manage_options', self::$dashboard_slug,
			[ $this, $this->panel_template ], self::$icon, 3 );

		add_action( 'load-' . $panel_hook_suffix, [ $this, 'load_assets' ] );
	}

	/** register subpage */
	public function register_subpages() {

		if ( ! defined( 'FOXIZ_THEME_VERSION' ) || empty( self::$sub_pages ) ) {
			return;
		}

		global $submenu;
		foreach ( self::$sub_pages as $sub_page ) {

			if ( empty( $sub_page['class'] ) || empty( $sub_page['path'] ) ) {
				continue;
			}

			$class_name = 'rbSubPage' . $sub_page['class'];
			$sub_page   = new $class_name();
			if ( ! empty( $sub_page->menu_slug ) ) {
				$page_hook_suffix = add_submenu_page( self::$dashboard_slug, $sub_page->page_title, $sub_page->menu_title, $sub_page->capability, $sub_page->menu_slug,
					[ $sub_page, 'render' ], $sub_page->position );
				add_action( 'load-' . $page_hook_suffix, [ $this, 'load_assets' ] );
			}
		}

		if ( isset( $submenu[ self::$dashboard_slug ][0][0] ) ) {
			$submenu[ self::$dashboard_slug ][0][0] = $this->get_dashboard_label();
		}
	}

	/** SystemInfo */
	public function register_system_info() {

		$sub_page = [
			'class' => 'SystemInfo',
			'path'  => 'admin/system-info/system-info.php',
		];

		$class_name = 'rbSubPage' . $sub_page['class'];
		$sub_page   = new $class_name();

		if ( ! empty( $sub_page->menu_slug ) ) {
			$page_hook_suffix = add_submenu_page( self::$dashboard_slug, $sub_page->page_title, $sub_page->menu_title, $sub_page->capability, $sub_page->menu_slug,
				[ $sub_page, 'render' ] );
			add_action( 'load-' . $page_hook_suffix, [ $this, 'load_assets' ] );
		}
	}

	public function get_dashboard_label() {

		if ( $this->get_purchase_code() ) {
			return esc_html__( 'Home', 'foxiz-core' );
		} else {
			return esc_html__( 'Registration', 'foxiz-core' );
		}
	}

	/** get import */
	public function get_imports() {

		$data = get_option( $this->import_info, [] );

		if ( is_array( $data ) && isset( $data['listing'] ) ) {

			foreach ( $data['listing'] as $index => $values ) {
				$data['listing'][ $index ]['content']       = $this->get_request( $index, 'content' );
				$data['listing'][ $index ]['pages']         = $this->get_request( $index, 'pages' );
				$data['listing'][ $index ]['theme_options'] = $this->get_request( $index, 'theme-options' );
				$data['listing'][ $index ]['widgets']       = $this->get_request( $index, 'widgets' );
				$data['listing'][ $index ]['taxonomies']    = $this->get_request( $index, 'taxonomies' );
				$data['listing'][ $index ]['post_types']    = $this->get_request( $index, 'post-types' );
			}

			return $data['listing'];
		}

		return false;
	}

	public function get_request( $index, $key ) {

		return "import/?demo=$index&data=$key&code=" . $this->get_purchase_code();
	}

	public function load_assets() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ], 25 );
	}

	/** js and css */
	public function register_assets() {

		$css_path = ! is_rtl() ? 'dashboard' : 'dashboard-rtl';
		wp_enqueue_style( 'rb-admin-style', plugins_url( FOXIZ_REL_PATH . '/admin/assets/' . $css_path . '.css' ), [], FOXIZ_CORE_VERSION, 'all' );
		wp_register_script( 'rb-searcher', plugins_url( FOXIZ_REL_PATH . '/admin/assets/searcher.js' ), [ 'jquery' ], FOXIZ_CORE_VERSION, true );
		wp_register_script( 'rb-admin-script', plugins_url( FOXIZ_REL_PATH . '/admin/assets/panel.js' ), [
			'jquery',
			'wp-util',
			'rb-searcher',
		], FOXIZ_CORE_VERSION, true );
		wp_localize_script( 'rb-admin-script', 'foxizAdminCore', $this->localize_params() );
	}

	public function localize_params() {

		return apply_filters( 'rb_admin_localize_data', [
			'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
			'_rbNonce'               => wp_create_nonce( self::$nonce ),
			'updating'               => esc_html__( 'Updating...', 'foxiz-core' ),
			'reload'                 => esc_html__( 'Reload...', 'foxiz-core' ),
			'error'                  => esc_html__( 'Error!', 'foxiz-core' ),
			'confirmDeleteImporter'  => esc_html__( 'Are you sure you want to delete the imported content? This action cannot be undone.', 'foxiz-core' ),
			'confirmUpdateDemos'     => esc_html__( 'Do you want to update new import data?', 'foxiz-core' ),
			'confirmDeleteAdobeFont' => esc_html__( 'Are you sure to delete this font project?', 'foxiz-core' ),
			'confirmDeactivate'      => esc_html__( 'Are you sure you want to deactivate the current license? This action could lead to site errors or disruptions.', 'foxiz-core' ),
			'confirmDeleteGA'        => esc_html__( 'Are you sure to delete the Google Tag?', 'foxiz-core' ),
		] );
	}

	public function enqueue_assets() {

		wp_enqueue_script( 'rb-admin-script' );
	}

	public function admin_template() {

		$this->header_template();
		echo rb_admin_get_template_part( self::$panel_slug, $this->panel_name, $this->params );
	}

	public function header_template() {

		echo rb_admin_get_template_part( self::$panel_slug, self::$header, $this->params );
	}

	/** register theme */
	public function register_theme() {

		$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

		if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
			wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

			wp_die();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );

			wp_die();
		}

		if ( empty( $_POST['purchase_code'] ) || empty( $_POST['email'] ) ) {
			wp_send_json_error( esc_html__( 'It seems the data is empty. Please take a moment to review the input form.', 'foxiz-core' ), 400 );
			wp_die();
		}

		if ( ! is_email( $_POST['email'] ) ) {
			wp_send_json_error( esc_html__( 'It seems the email format is incorrect. Please take a moment to review the input form.', 'foxiz-core' ), 400 );
			wp_die();
		}

		$url = add_query_arg( [
			'purchase_code' => sanitize_text_field( $_POST['purchase_code'] ),
			'email'         => esc_html( $_POST['email'] ),
			'theme'         => self::$theme_id,
			'action'        => 'register',
		], $this->apiSever );

		$response = $this->validation_api( $url );

		if ( empty( $response['code'] ) || 200 !== $response['code'] ) {
			wp_send_json_error( esc_html( $response['message'] ), 400 );

			wp_die();
		} else {
			if ( ! empty( $response['data']['purchase_info'] ) ) {
				update_option( $this->purchase_info, array_map( 'sanitize_text_field', $response['data']['purchase_info'] ) );
			}
			if ( ! empty( $response['data']['import'] ) ) {
				update_option( $this->import_info, $this->sanitize_data( $response['data']['import'] ) );
			}

			$this->activated();
			$this->unset_keys();

			wp_send_json_success( esc_html( $response['message'] ), 200 );

			wp_die();
		}
	}

	public function validation_api( $url ) {

		$params = [
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
			'timeout'    => 60,
		];

		$response = wp_remote_get( $url, $params );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json_error( esc_html__( 'There seems to be an issue with remote requests. Could you please check if the hosting allows opening URLs?', 'foxiz-core' ), 400 );
			wp_die();
		}

		$response = wp_remote_retrieve_body( $response );

		return json_decode( $response, true );
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function sanitize_data( $data ) {

		if ( ! is_array( $data ) ) {
			return [];
		}

		foreach ( $data as $key => $item ) {
			if ( ! is_array( $item ) ) {
				$data[ $key ] = sanitize_text_field( $item );
			} else {
				foreach ( $item as $key_item => $item_value ) {
					if ( ! is_array( $item_value ) ) {
						$data[ $key ][ $key_item ] = sanitize_text_field( $item_value );
					} else {
						foreach ( $item_value as $key_item_value => $values ) {
							if ( ! is_array( $values ) ) {
								$data[ $key ][ $key_item ][ $key_item_value ] = sanitize_text_field( $values );
							} else {
								foreach ( $values as $values_key => $values_sub ) {
									if ( ! is_array( $values_sub ) ) {
										$data[ $key ][ $key_item ][ $key_item_value ][ $values_key ] = sanitize_text_field( $values_sub );
									} else {
										foreach ( $values_sub as $values_sub_key => $v ) {
											$data[ $key ][ $key_item ][ $key_item_value ][ $values_key ][ $values_sub_key ] = sanitize_text_field( $v );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $data;
	}

	/** deregister_theme */
	public function deregister_theme() {

		$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

		if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
			wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

			wp_die();
		}

		if ( ! $this->get_purchase_code() || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'foxiz-core' ), 403 );
			wp_die();
		}

		$url = add_query_arg( [
			'purchase_code' => $this->get_purchase_code(),
			'action'        => 'deregister',
		], $this->apiSever );

		$response = $this->validation_api( $url );
		$this->unset_code( [ $this->purchase_info, $this->import_info ] );
		$this->unset_keys();

		if ( empty( $response['code'] ) || 200 !== $response['code'] ) {
			wp_send_json_error( esc_html( $response['message'] ), 400 );
		} else {
			wp_send_json_success( esc_html( $response['message'] ), 200 );
		}

		$this->deactivated();
		wp_die();
	}

	/**
	 * @param $data
	 *
	 * @return false
	 */
	public function unset_code( $data ) {

		array_map( 'delete_option', $data );

		return false;
	}

	/**
	 * @param false $override
	 *
	 * @return string
	 */
	public function update_importer( $override = false ) {

		if ( ! $override ) {
			$timeout = get_transient( 'ruby_update_timeout' );
			if ( ! empty( $timeout ) ) {
				return false;
			}
		}

		$code = $this->get_purchase_code();

		if ( empty( $code ) ) {
			return 'Purchase code not found!';
		}

		$url = add_query_arg( [
			'purchase_code' => $code,
			'theme'         => 'foxiz',
			'action'        => 'demos',
		], $this->apiSever );

		$response = $this->validation_api( $url );

		if ( empty( $response['code'] ) || 200 !== $response['code'] ) {
			if ( ! empty( $response['code'] ) && 666 === $response['code'] ) {
				$this->unset_code( [ $this->purchase_info, $this->import_info ] );
			}

			return esc_html__( 'Unable to fetch data from ThemeRuby server at the moment. Please try again later or feel free to reach out to our support team for assistance!', 'foxiz-core' );
		}

		$data            = get_option( $this->import_info, [] );
		$data['listing'] = $response['listing'];
		update_option( $this->import_info, $this->sanitize_data( $data ) );

		return 'done';
	}

	/**
	 * @param $request
	 * @param $url
	 *
	 * @return mixed
	 */
	public function is_premium( $request, $url ) {

		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {

			$data = json_decode( $request['body']['themes'] );
			unset( $data->themes->{self::$theme_id} );
			$request['body']['themes'] = wp_json_encode( $data );
		} elseif ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/1.1/' ) ) {

			$data = json_decode( $request['body']['plugins'] );
			unset( $data->plugins->{self::$core_plugin_id} );

			$request['body']['plugins'] = wp_json_encode( $data );
		} elseif ( false !== strpos( $url, RB_API_URL . '/download' ) ) {
			$request['headers'] = $this->request_headers();
		}

		return $request;
	}

	public function get_token() {

		return isset( self::$license['token'] ) ? self::$license['token'] : false;
	}

	public function validate() {

		if ( get_site_transient( self::$key ) ) {
			return;
		}

		$args     = [ 'type' => 'validate' ];
		$domain   = $this->get_api_url();
		$path     = $this->api_path_for( $args['type'] );
		$response = $this->request( $domain . $path, $args );
		if ( ! is_wp_error( $response ) && ! empty( $response ) ) {
			update_option( '_ruby_validated', isset( $response['licensed'] ) && $response['licensed'] ? '' : 'e' );
			update_option( self::$key, $response );
			$delay = 20;
		} else {
			$delay = 2;
		}

		set_site_transient( self::$key, true, $delay * DAY_IN_SECONDS );
	}

	public function get_api_url() {

		return RB_API_URL . '/wp-json';
	}

	public function api_path_for( $path ) {

		$paths = [
			'version'  => '/market/version',
			'validate' => '/market/cvalid',
		];

		return $paths[ $path ];
	}

	public function request( $url, $args = [] ) {

		$defaults = [
			'sslverify' => true,
			'headers'   => [
				'User-Agent'    => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
				'Authorization' => 'Bearer ' . $this->get_purchase_code(),
			],
			'timeout'   => 20,
			'type'      => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$response = wp_remote_get( esc_url_raw( $url ), $args );

		$response_code    = wp_remote_retrieve_response_code( $response );
		$response_message = wp_remote_retrieve_response_message( $response );

		if ( ! empty( $response->errors ) && isset( $response->errors['http_request_failed'] ) ) {
			return new WP_Error( 'http_error', esc_html( current( $response->errors['http_request_failed'] ) ) );
		}

		if ( 200 !== $response_code && ! empty( $response_message ) ) {
			return new WP_Error( $response_code, $response_message );
		} elseif ( 200 !== $response_code ) {
			return new WP_Error( $response_code, __( 'An unknown API error occurred.', 'foxiz-core' ) );
		} else {
			$return = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( null === $return ) {
				return new WP_Error( 'api_error', __( 'An unknown API error occurred.', 'foxiz-core' ) );
			}

			return $return;
		}
	}

	/**
	 * @param $transient
	 *
	 * @return mixed
	 */
	public function update_theme( $transient ) {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$ver = get_site_transient( '_rb_ThemeNewVersion' );
		if ( empty( $ver ) ) {
			$args   = [ 'type' => 'version' ];
			$domain = $this->get_api_url();
			$path   = $this->api_path_for( $args['type'] );

			$url      = $domain . $path . '?item_id=' . self::$theme_id . '&quick=true';
			$response = $this->request( $url, $args );

			if ( ! is_wp_error( $response ) && ! empty( $response ) ) {
				$this->set_token( $response );
				$ver = $response;
			} else {
				$ver = 'api_error';
			}

			set_site_transient( '_rb_ThemeNewVersion', $ver, HOUR_IN_SECONDS );
		}

		if ( ! empty( $ver['new_version'] ) && version_compare( $ver['new_version'], $transient->checked[ self::$theme_id ], '>' ) ) {
			$transient->response[ self::$theme_id ] = $ver;
		}

		return $transient;
	}

	/**
	 * @param $transient
	 *
	 * @return mixed
	 */
	public function update_core_plugin( $transient ) {

		$ver = get_site_transient( '_rb_CoreNewVersion' );
		if ( empty( $ver ) ) {
			$args   = [ 'type' => 'version' ];
			$domain = $this->get_api_url();
			$path   = $this->api_path_for( $args['type'] );

			$url      = $domain . $path . '?item_id=' . self::$theme_id . '-core';
			$response = $this->request( $url, $args );

			if ( ! is_wp_error( $response ) && ! empty( $response ) ) {
				$response['slug'] = null;
				$ver              = (object) $response;
			} else {
				$ver = 'api_error';
			}

			set_site_transient( '_rb_CoreNewVersion', $ver, HOUR_IN_SECONDS );
		}

		if ( ! empty( $ver->new_version ) && version_compare( $ver->new_version, FOXIZ_CORE_VERSION, '>' ) ) {
			$transient->response[ self::$core_plugin_id ] = $ver;
		} else {
			unset( $transient->response[ self::$core_plugin_id ] );
		}

		return $transient;
	}

	public function get_wp_info() {

		return [
			'wp_version'    => [
				'title' => esc_html__( 'WordPress Version', 'foxiz-core' ),
				'value' => isset( $GLOBALS['wp_version'] ) ? $GLOBALS['wp_version'] : '',
			],
			'debug_mode'    => [
				'title'   => esc_html__( 'Debug Mode', 'foxiz-core' ),
				'value'   => ( WP_DEBUG ) ? 'Enabled' : 'Disabled',
				'passed'  => ( WP_DEBUG ) ? false : true,
				'warning' => esc_html__( 'Enabling WordPress debug mode might display details about your site\'s PHP code to visitors.', 'foxiz-core' ),
			],
			'debug_log'     => [
				'title' => esc_html__( 'Debug Log', 'foxiz-core' ),
				'value' => ( WP_DEBUG_LOG ) ? 'Enabled' : 'Disabled',
			],
			'theme_name'    => [
				'title' => esc_html__( 'Theme Name', 'foxiz-core' ),
				'value' => wp_get_theme()->Name,
			],
			'theme_version' => [
				'title' => esc_html__( 'Theme Version', 'foxiz-core' ),
				'value' => wp_get_theme()->Version,
			],
			'theme_author'  => [
				'title' => esc_html__( 'Theme Author', 'foxiz-core' ),
				'value' => '<a target="_blank" href="//1.envato.market/6bEx7Q">Theme-Ruby</a>',
			],
		];
	}

	public function get_system_info() {

		return [
			'php_version'     => [
				'title'   => esc_html__( 'PHP Version', 'foxiz-core' ),
				'value'   => phpversion(),
				'min'     => '5.6',
				'passed'  => version_compare( phpversion(), '7.0.0' ) >= 0,
				'warning' => esc_html__( 'WordPress recommended PHP version 7.0 or greater to get better performance for your site.', 'foxiz-core' ),
			],
			'memory_limit'    => [
				'title'   => esc_html__( 'Memory Limit', 'foxiz-core' ),
				'value'   => size_format( wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) ) ),
				'min'     => '64M',
				'passed'  => wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) >= 67108864,
				'warning' => esc_html__( 'The memory_limit value is set low. The theme recommended this value to be at least 64MB for the theme in order to work.', 'foxiz-core' ),
			],
			'max_input_vars'  => [
				'title'   => esc_html__( 'Max Input Vars', 'foxiz-core' ),
				'value'   => ini_get( 'max_input_vars' ),
				'min'     => '3000',
				'passed'  => (int) ini_get( 'max_input_vars' ) >= 2000,
				'warning' => esc_html__( 'The max_input_vars value is set low. The theme recommended this value to be at least 3000.', 'foxiz-core' ),
			],
			'post_max_size'   => [
				'title'   => esc_html__( 'Post Max Size', 'foxiz-core' ),
				'value'   => ini_get( 'post_max_size' ),
				'min'     => '32',
				'passed'  => (int) ini_get( 'post_max_size' ) >= 32,
				'warning' => esc_html__( 'The post_max_size value is set low. We recommended this value to be at least 32M.', 'foxiz-core' ),
			],
			'max_upload_size' => [
				'title'   => esc_html__( 'Max Upload Size', 'foxiz-core' ),
				'value'   => size_format( wp_max_upload_size() ),
				'min'     => '32',
				'passed'  => (int) wp_max_upload_size() >= 33554432,
				'warning' => esc_html__( 'The post_max_size value is set low. We recommended this value to be at least 32M.', 'foxiz-core' ),
			],
			'zip_archive'     => [
				'title'   => esc_html__( 'ZipArchive Support', 'foxiz-core' ),
				'value'   => class_exists( '\ZipArchive' ) ? 'Yes' : 'No',
				'passed'  => class_exists( '\ZipArchive' ),
				'warning' => esc_html__( 'ZipArchive can be used to autonomously update the theme.', 'foxiz-core' ),
			],
		];
	}

	public function get_dashboard_menu() {

		if ( ! $this->get_purchase_code() ) {
			return [
				'registration' => [
					'title' => esc_html__( 'Registration', 'foxiz-core' ),
					'icon'  => 'rbi-dash-license',
					'url'   => $this->get_admin_menu_url( self::$dashboard_slug ),
				],
			];
		}

		$menus              = [];
		$is_imported        = get_option( '_rb_flag_imported', false );
		$import_menu        = [
			'title' => esc_html__( 'Demo Importer', 'foxiz-core' ),
			'icon'  => 'rbi-dash-layer',
			'url'   => $this->get_admin_menu_url( 'rb-demo-importer' ),
		];
		$menus['dashboard'] = [
			'title' => esc_html__( 'Dashboard', 'foxiz-core' ),
			'icon'  => 'rbi-dash-dashboard',
			'url'   => $this->get_admin_menu_url( self::$dashboard_slug ),
		];
		if ( ! $is_imported ) {
			$menus['import'] = $import_menu;
		}
		$menus['options'] = [
			'title' => esc_html__( 'Theme Options', 'foxiz-core' ),
			'icon'  => 'rbi-dash-option',
			'url'   => $this->get_admin_menu_url( 'ruby-options' ),
		];
		$menus['more']    = [
			'title'     => esc_html__( 'Extra Features', 'foxiz-core' ),
			'icon'      => 'rbi-dash-more',
			'url'       => '#',
			'sub_items' => [],
		];
		if ( $is_imported ) {
			$menus['more']['sub_items']['import'] = $import_menu;
		}
		$menus['more']['sub_items']['translation'] = [
			'title' => esc_html__( 'Quick Translation', 'foxiz-core' ),
			'icon'  => 'rbi-dash-translate',
			'url'   => $this->get_admin_menu_url( 'ruby-translation' ),
		];
		$menus['more']['sub_items']['adobe']       = [
			'title' => esc_html__( 'Adobe Fonts', 'foxiz-core' ),
			'icon'  => 'rbi-dash-adobe',
			'url'   => $this->get_admin_menu_url( 'ruby-adobe-fonts' ),
		];
		$menus['more']['sub_items']['gtm']         = [
			'title' => esc_html__( 'Analytics 4', 'foxiz-core' ),
			'icon'  => 'rbi-dash-gtm',
			'url'   => $this->get_admin_menu_url( 'ruby-gmt-integration' ),
		];
		$menus['more']['sub_items']['openai']      = [
			'title' => esc_html__( 'OpenAI Assistant', 'foxiz-core' ),
			'icon'  => 'rbi-dash-openai',
			'url'   => $this->get_admin_menu_url( 'ruby-openai' ),
		];

		if ( foxiz_is_plugin_active( 'bbpress/bbpress.php' ) ) {
			$menus['more']['sub_items']['bbpress'] = [
				'title' => esc_html__( 'bbPress Forums', 'foxiz-core' ),
				'icon'  => 'rbi-dash-chat',
				'url'   => $this->get_admin_menu_url( 'ruby-bbp-supported' ),
			];
		}

		$menus['system'] = [
			'title' => esc_html__( 'System Info', 'foxiz-core' ),
			'icon'  => 'rbi-dash-info',
			'url'   => $this->get_admin_menu_url( 'ruby-system-info' ),
		];

		return $menus;
	}

	/** plugins status */
	public function plugin_status( $plugin ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin = $this->get_plugin_file_path( $plugin );
		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			return 'not_found';
		}

		if ( in_array( $plugin, (array) get_option( 'active_plugins', [] ), true ) || is_plugin_active_for_network( $plugin ) ) {
			return 'active';
		}

		return 'inactive';
	}

	/**
	 * get plugin file
	 *
	 * @param $slug
	 *
	 * @return string
	 */
	public function get_plugin_file_path( $slug ) {

		if ( strpos( $slug, '/' ) ) {
			return $slug;
		}

		if ( isset( self::$recommended_plugins[ $slug ] ) ) {
			$plugin_information = self::$recommended_plugins[ $slug ];
			if ( ! empty( $plugin_information['file'] ) ) {
				return $slug . '/' . $plugin_information['file'] . '.php';
			}
		}

		return $slug . '/' . $slug . '.php';
	}

	/** recommended_plugin progress */
	public function recommended_plugin() {

		$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_key( $_POST['_nonce'] ) : '';

		if ( empty( $nonce ) || false === wp_verify_nonce( $nonce, self::$nonce ) ) {
			wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz-core' ), 400 );

			wp_die();
		}

		$plugin = ( isset( $_POST['plugin'] ) ) ? trim( sanitize_text_field( $_POST['plugin'] ) ) : '';

		if ( empty( $plugin ) || empty( self::$recommended_plugins[ $plugin ] ) ) {
			wp_send_json_error( esc_html__( 'Invalid plugin.', 'foxiz-core' ), 400 );

			wp_die();
		}

		$plugin = $this->get_plugin_info( $plugin );
		$status = $this->plugin_status( $plugin['file'] );

		switch ( $status ) {
			case 'not_found':
				$result = $this->install_plugin( $plugin );
				if ( $result ) {
					wp_send_json_success( [
						'status'      => 'is-inactive',
						'statusLabel' => esc_html__( 'Inactive', 'foxiz-core' ),
						'btnLabel'    => esc_html__( 'Activate', 'foxiz-core' ),
					], 200 );
				} else {
					wp_send_json_error( esc_html__( 'Plugin installation failed.', 'foxiz-core' ), 400 );
				}
				break;
			case  'inactive' :
				$result = $this->activate_plugin( $plugin );
				if ( $result ) {
					wp_send_json_success( [
						'status'      => 'is-active',
						'statusLabel' => esc_html__( 'Activated', 'foxiz-core' ),
						'btnLabel'    => esc_html__( 'Deactivate', 'foxiz-core' ),
					], 200 );
				} else {
					wp_send_json_error( esc_html__( 'Plugin Activation failed.', 'foxiz-core' ), 400 );
				}
				break;
			case  'active' :
				$result = $this->deactivate_plugin( $plugin['file'] );
				if ( $result ) {
					wp_send_json_success( [
						'status'      => 'is-inactive',
						'statusLabel' => esc_html__( 'Inactive', 'foxiz-core' ),
						'btnLabel'    => esc_html__( 'Activate', 'foxiz-core' ),
					], 200 );
				} else {
					wp_send_json_error( esc_html__( 'Plugin Deactivation failed.', 'foxiz-core' ), 400 );
				}
				break;
		}

		wp_send_json_error( esc_html__( 'Invalid action!', 'foxiz-core' ), 400 );
	}

	/**
	 * @param $plugin
	 *
	 * @return false|mixed|string
	 */
	public function importer_plugin( $plugin ) {

		if ( empty( $plugin ) || empty( self::$recommended_plugins[ $plugin ] ) ) {
			return false;
		}

		$plugin = $this->get_plugin_info( $plugin );
		$title  = ! empty( $plugin['title'] ) ? esc_html( $plugin['title'] ) : esc_html( ucwords( $plugin['slug'] ) );
		$status = $this->plugin_status( $plugin['file'] );

		switch ( $status ) {
			case 'not_found':
				Ruby_Importer::save_import_progress( sprintf( esc_html__( 'Installing Plugin: %s', 'foxiz-core' ), $title ), 8 );
				$result = $this->install_plugin( $plugin );
				if ( $result ) {
					Ruby_Importer::save_import_progress( sprintf( esc_html__( 'Activating Plugin: %s', 'foxiz-core' ), $title ), 2 );
					$result = $this->activate_plugin( $plugin );
				}
				break;
			case  'inactive' :
				Ruby_Importer::save_import_progress( sprintf( esc_html__( 'Activating Plugin: %s', 'foxiz-core' ), $title ), 2 );
				$result = $this->activate_plugin( $plugin );
				break;
		}

		if ( empty( $result ) ) {
			return false;
		}

		return $title;
	}

	/**
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function install_plugin( $plugin ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		if ( ! function_exists( 'plugins_api' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if ( ! empty( $plugin['package'] ) ) {
			$package = esc_url( $plugin['package'] );
		} else {
			$plugin = ! empty( $plugin['slug'] ) ? $plugin['slug'] : (string) $plugin;
			$api    = plugins_api( 'plugin_information', [
					'slug'   => sanitize_text_field( wp_unslash( $plugin ) ),
					'fields' => [ 'sections' => false ],
				]
			);

			if ( ! is_wp_error( $api ) ) {
				$package = $api->download_link;
			}
		}

		if ( empty( $package ) ) {
			return false;
		}

		$skin   = new WP_Ajax_Upgrader_Skin();
		$u      = new Plugin_Upgrader( $skin );
		$result = $u->install( $package, [ 'overwrite_package' => true ] );

		if ( is_wp_error( $result ) || is_wp_error( $skin->result ) || is_null( $result ) ) {
			return false;
		}

		return true;
	}

	/**
	 * activate plugin
	 *
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function activate_plugin( $plugin ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		$plugin = ! empty( $plugin['file'] ) ? $plugin['file'] : (string) $plugin;

		if ( ! function_exists( 'activate_plugin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$activate = activate_plugin( $this->get_plugin_file_path( $plugin ), '', false, true );

		if ( is_wp_error( $activate ) ) {
			return false;
		}

		return true;
	}

	/**
	 * deactivate_plugin
	 *
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function deactivate_plugin( $plugin ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin = $this->get_plugin_file_path( $plugin );
		deactivate_plugins( $plugin, true );

		return true;
	}

	/**
	 * @param $plugin
	 *
	 * @return mixed
	 */
	public function get_plugin_info( $plugin ) {

		if ( ! empty( self::$recommended_plugins[ $plugin ] ) ) {
			return self::$recommended_plugins[ $plugin ];
		}

		return [
			'slug' => $plugin,
			'file' => $plugin . '/' . $plugin . '.php',
		];
	}

	private function get_recommended_plugins() {

		return [
			'elementor'                          => [
				'title'       => esc_html__( 'Elementor', 'foxiz-core' ),
				'description' => esc_html__( 'Leading website builder platform for professionals and business owners.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'elementor',
				'file'        => 'elementor/elementor.php',
				'status'      => $this->plugin_status( 'elementor/elementor.php' ),
				'icon'        => 'https://ps.w.org/elementor/assets/icon-128x128.gif',
			],
			'ruby-submission'                    => [
				'title'       => esc_html__( 'Ruby Post Submission', 'foxiz-core' ),
				'description' => esc_html__( 'A lightweight and user-friendly plugin designed to let users submit content from the frontend.', 'foxiz-core' ),
				'package'     => RB_API_URL . '/repository/ruby-submission.zip',
				'slug'        => 'ruby-submission',
				'file'        => 'ruby-submission/ruby-submission.php',
				'status'      => $this->plugin_status( 'ruby-submission/ruby-submission.php' ),
				'icon'        => RB_API_URL . '/images/ruby-submission.gif?v=1.0',
			],
			'leadin'                             => [
				'title'       => esc_html__( 'HubSpot', 'foxiz-core' ),
				'description' => esc_html__( 'All the tools and integrations you need for marketing, sales, and customer service.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'leadin',
				'file'        => 'leadin/leadin.php',
				'status'      => $this->plugin_status( 'leadin/leadin.php' ),
				'icon'        => 'https://ps.w.org/leadin/assets/icon-128x128.png',
			],
			'post-views-counter'                 => [
				'title'       => esc_html__( 'Post Views Counter', 'foxiz-core' ),
				'description' => esc_html__( 'Display how many times a post, page or custom post type.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'post-views-counter',
				'file'        => 'post-views-counter/post-views-counter.php',
				'status'      => $this->plugin_status( 'post-views-counter/post-views-counter.php' ),
				'icon'        => 'https://ps.w.org/post-views-counter/assets/icon-128x128.png',
			],
			'breadcrumb-navxt'                   => [
				'title'       => esc_html__( 'Breadcrumb NavXT', 'foxiz-core' ),
				'description' => esc_html__( 'Generates locational breadcrumb trails for your WordPress powered blog or website.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'breadcrumb-navxt',
				'file'        => 'breadcrumb-navxt/breadcrumb-navxt.php',
				'status'      => $this->plugin_status( 'breadcrumb-navxt/breadcrumb-navxt.php' ),
				'icon'        => 'https://ps.w.org/breadcrumb-navxt/assets/icon.svg',
			],
			'contact-form-7'                     => [
				'title'       => esc_html__( 'Contact Form 7', 'foxiz-core' ),
				'description' => esc_html__( 'Just another contact form plugin. Simple but flexible.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'contact-form-7',
				'file'        => 'contact-form-7/wp-contact-form-7.php',
				'status'      => $this->plugin_status( 'contact-form-7/wp-contact-form-7.php' ),
				'icon'        => 'https://ps.w.org/contact-form-7/assets/icon.svg',
			],
			'mailchimp-for-wp'                   => [
				'title'       => esc_html__( 'Mailchimp for WordPress', 'foxiz-core' ),
				'description' => esc_html__( 'Allows you to add a multitude of newsletter sign-up methods to your site.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'mailchimp-for-wp',
				'file'        => 'mailchimp-for-wp/mailchimp-for-wp.php',
				'status'      => $this->plugin_status( 'mailchimp-for-wp/mailchimp-for-wp.php' ),
				'icon'        => 'https://ps.w.org/mailchimp-for-wp/assets/icon-256x256.png',
			],
			'woocommerce'                        => [
				'title'       => esc_html__( 'WooCommerce', 'foxiz-core' ),
				'description' => esc_html__( 'All the tools and integrations you need for marketing, sales, and customer service.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'woocommerce',
				'file'        => 'woocommerce/woocommerce.php',
				'status'      => $this->plugin_status( 'woocommerce/woocommerce.php' ),
				'icon'        => 'https://ps.w.org/woocommerce/assets/icon-128x128.gif',
			],
			'publishpress-authors'               => [
				'title'       => esc_html__( 'Multiple Authors', 'foxiz-core' ),
				'description' => esc_html__( 'Create, manage and display authors for all your WordPress content.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'publishpress-authors',
				'file'        => 'publishpress-authors/publishpress-authors.php',
				'status'      => $this->plugin_status( 'publishpress-authors/publishpress-authors.php' ),
				'icon'        => 'https://ps.w.org/publishpress-authors/assets/icon-256x256.png',
			],
			'custom-post-type-ui'                => [
				'title'       => esc_html__( 'Custom Post Type UI', 'foxiz-core' ),
				'description' => esc_html__( 'Admin UI for creating custom content types like post types and taxonomies.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'custom-post-type-ui',
				'file'        => 'custom-post-type-ui/custom-post-type-ui.php',
				'status'      => $this->plugin_status( 'custom-post-type-ui/custom-post-type-ui.php' ),
				'icon'        => 'https://ps.w.org/custom-post-type-ui/assets/icon-256x256.png',
			],
			'bbpress'                            => [
				'title'       => esc_html__( 'bbPress', 'foxiz-core' ),
				'description' => esc_html__( 'Forum software from the creators of WordPress. Quickly setup a place for asyncronous discussion, subscriptions, and more!', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'bbpress',
				'file'        => 'bbpress/bbpress.php',
				'status'      => $this->plugin_status( 'bbpress/bbpress.php' ),
				'icon'        => 'https://ps.w.org/bbpress/assets/icon.svg',
			],
			'wp-recipe-maker'                    => [
				'title'       => esc_html__( 'WP Recipe Maker', 'foxiz-core' ),
				'description' => esc_html__( 'Easy recipe plugin that everyone can use with automatic JSON-LD metadata for your recipes.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'wp-recipe-maker',
				'file'        => 'wp-recipe-maker/wp-recipe-maker.php',
				'status'      => $this->plugin_status( 'wp-recipe-maker/wp-recipe-maker.php' ),
				'icon'        => 'https://ps.w.org/wp-recipe-maker/assets/icon-128x128.png',
			],
			'web-stories'                        => [
				'title'       => esc_html__( 'Web Stories', 'foxiz-core' ),
				'description' => esc_html__( 'Visual storytelling format for the web, enabling you to easily create visual narratives.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'web-stories',
				'file'        => 'web-stories/web-stories.php',
				'status'      => $this->plugin_status( 'web-stories/web-stories.php' ),
				'icon'        => 'https://ps.w.org/web-stories/assets/icon.svg',
			],
			'cryptocurrency-price-ticker-widget' => [
				'title'       => esc_html__( 'Cryptocurrency Widgets', 'foxiz-core' ),
				'description' => esc_html__( 'Easily display a crypto ticker widget, coins price lists, tables, multi-currency tabs & price labels anywhere.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'cryptocurrency-price-ticker-widget',
				'file'        => 'cryptocurrency-price-ticker-widget/cryptocurrency-price-ticker-widget.php',
				'status'      => $this->plugin_status( 'cryptocurrency-price-ticker-widget/cryptocurrency-price-ticker-widget.php' ),
				'icon'        => 'https://ps.w.org/cryptocurrency-price-ticker-widget/assets/icon-256x256.png',
			],
			'wp-super-cache'                     => [
				'title'       => esc_html__( 'WP Super Cache', 'foxiz-core' ),
				'description' => esc_html__( 'A very fast caching engine for WordPress that produces static html files.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'wp-super-cache',
				'file'        => 'wp-super-cache/wp-cache.php',
				'status'      => $this->plugin_status( 'wp-super-cache/wp-cache.php' ),
				'icon'        => 'https://ps.w.org/wp-super-cache/assets/icon-256x256.png',
			],
			'autoptimize'                        => [
				'title'       => esc_html__( 'Autoptimize', 'foxiz-core' ),
				'description' => esc_html__( 'Autoptimize speeds up your website by optimizing JS, CSS, images.', 'foxiz-core' ),
				'package'     => false,
				'slug'        => 'autoptimize',
				'file'        => 'autoptimize/autoptimize.php',
				'status'      => $this->plugin_status( 'autoptimize/autoptimize.php' ),
				'icon'        => 'https://ps.w.org/autoptimize/assets/icon-256X256.png',
			],
		];
	}

	private function activated() {

		update_option( $this->activation, 'yes' );
	}

	private function deactivated() {

		delete_option( $this->activation );
	}

	private function get_admin_menu_url( $menu_slug ) {

		return admin_url( 'admin.php?page=' . $menu_slug );
	}

	private function get_configs() {

		self::$theme_id            = defined( 'RB_THEME_ID' ) ? RB_THEME_ID : wp_get_theme()->get( 'Name' );
		self::$core_plugin_id      = RB_THEME_ID . '-core/' . RB_THEME_ID . '-core.php';
		self::$key                 = '_lic' . FOXIZ_LICENSE_ID;
		self::$license             = $this->get_purchase_data();
		self::$sub_pages           = $this->set_sub_pages();
		self::$recommended_plugins = $this->get_recommended_plugins();
	}

	private function get_key_val( $name ) {

		$value = get_option( self::$key );

		if ( isset( $value[ $name ] ) ) {
			return $value[ $name ];
		}

		return false;
	}

	private function is_local() {

		$site_url = strtolower( home_url() );

		return ( strpos( $site_url, 'localhost' ) !== false || strpos( $site_url, '127.0.0.1' ) !== false || strpos( $site_url, '::1' ) !== false );
	}

	private function unset_keys() {

		delete_site_transient( self::$key );
		delete_option( self::$key );
	}

	private function request_headers() {

		return [
			'User-Agent'    => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
			'Authorization' => 'Bearer ' . $this->get_token(),
		];
	}

	private function set_token( $response ) {

		if ( isset( $response['token'] ) ) {
			$info          = get_option( $this->purchase_info, [] );
			$info['token'] = $response['token'];
			update_option( $this->purchase_info, $info );
		}
	}

}

/** LOAD */
RB_ADMIN_CORE::get_instance();
