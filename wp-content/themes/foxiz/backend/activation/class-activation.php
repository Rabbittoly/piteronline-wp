<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Theme_Activation' ) ) {
	class Foxiz_Theme_Activation {

		private static $instance;
		private static $version;
		private static $nonce = 'foxiz-activation';
		private static $core_plugin = [];

		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * register core plugin
		 */
		private function register_activation() {

			self::$core_plugin = [
				'slug'     => 'foxiz-core',
				'plugin'   => 'foxiz-core/foxiz-core.php',
				'package'  => FOXIZ_THEME_DIR . 'plugins/foxiz-core.zip',
				'redirect' => admin_url( 'admin.php?page=foxiz-admin' ),
			];
		}

		public function __construct() {

			self::$version = defined( 'FOXIZ_THEME_VERSION' ) ? FOXIZ_THEME_VERSION : '1.0';

			$this->register_activation();
			add_action( 'wp_ajax_ruby-install-core', [ $this, 'install_core' ] );
			add_action( 'wp_ajax_ruby-upgrade-core', [ $this, 'upgrade_core' ] );
			add_action( 'wp_ajax_ruby-activation-dismiss', [ $this, 'dismiss_notice' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'admin_notices', [ $this, 'core_plugin_notice' ], 5 );
			add_action( 'switch_theme', [ $this, 'reset_notices' ] );
			add_action( 'after_switch_theme', [ $this, 'reset_notices' ] );
		}

		public function enqueue_scripts() {

			wp_register_style( 'foxiz-activation', self::get_url() . 'activation.css', [], self::$version );
			wp_register_script( 'foxiz-activation', self::get_url() . 'activation.js', [ 'jquery' ], self::$version, true );
			wp_localize_script( 'foxiz-activation', 'foxizActivation', [ '_nonce' => wp_create_nonce( self::$nonce ) ] );
		}

		public function core_plugin_notice() {

			if ( ! current_user_can( 'install_plugins' ) || get_transient( '_' . self::$nonce ) ) {
				return;
			}

			$core_version = defined( 'FOXIZ_CORE_VERSION' ) ? FOXIZ_CORE_VERSION : false;
			if ( ! empty( $core_version ) && version_compare( $core_version, self::$version, '<' ) ) {

				global $current_screen;
				if ( in_array( $current_screen->id, [
					'toplevel_page_foxiz-admin',
					'plugins',
					'update',
					'theme-install',
				] )
				) {
					return;
				}
				$this->upgrade_notice();
			} else {
				$this->activation_notice();
			}
		}

		/**
		 * get current folder path
		 *
		 * @return string
		 */
		static function get_url() {

			$path      = wp_normalize_path( dirname( __FILE__ ) );
			$theme_dir = wp_normalize_path( get_template_directory() );

			if ( false !== strpos( $path, $theme_dir ) ) {
				$relative_path = str_replace( $theme_dir, '', $path );

				return trailingslashit( get_template_directory_uri() . $relative_path );
			}

			return plugin_dir_url( __FILE__ );
		}

		/**
		 * check core plugin status
		 *
		 * @return false|string
		 */
		public function plugin_status() {

			if ( ! file_exists( WP_PLUGIN_DIR . '/' . self::$core_plugin['plugin'] ) ) {
				return 'not_found';
			}

			if ( in_array( self::$core_plugin['plugin'], (array) get_option( 'active_plugins', [] ), true ) ) {
				return 'active';
			}

			if ( is_multisite() ) {
				$plugins = get_site_option( 'active_sitewide_plugins' );
				if ( isset( $plugins[ self::$core_plugin['plugin'] ] ) ) {
					return 'active';
				}
			}

			return 'inactive';
		}

		/**
		 * activate_plugin core
		 *
		 * @return bool
		 */
		private function activate_plugin() {

			if ( ! current_user_can( 'install_plugins' ) ) {
				return false;
			}

			if ( ! function_exists( 'activate_plugin' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$activate = activate_plugin( self::$core_plugin['plugin'], '', false, false );

			if ( is_wp_error( $activate ) ) {
				return false;
			}

			return true;
		}

		/**
		 * install_plugin core
		 *
		 * @return bool
		 */
		private function install_plugin() {

			if ( ! current_user_can( 'install_plugins' ) ) {
				return false;
			}

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}
			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$package = self::$core_plugin['package'];
			$skin    = new WP_Ajax_Upgrader_Skin();
			$u       = new Plugin_Upgrader( $skin );
			$result  = $u->install( $package, [ 'overwrite_package' => true ] );

			if ( is_wp_error( $result ) || is_wp_error( $skin->result ) || is_null( $result ) ) {
				return false;
			}

			$this->activate_plugin();

			return true;
		}

		public function install_core() {

			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz' ) );
				wp_die();
			}

			$status = $this->plugin_status();

			if ( 'active' === $status ) {
				wp_send_json_success();
			} elseif ( 'inactive' === $status ) {
				$result = $this->activate_plugin();

				if ( $result ) {
					wp_send_json_success();
					wp_die();
				}

				wp_send_json_error( esc_html__( 'Activation failed. Please reload and try again.', 'foxiz' ) );
				wp_die();
			}

			$result = $this->install_plugin();

			if ( $result ) {
				wp_send_json_success();
				wp_die();
			}

			wp_send_json_error( esc_html__( 'Install failed. Please reload and try again.', 'foxiz' ) );
			wp_die();
		}

		public function upgrade_core() {

			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce validation failed. Please try again.', 'foxiz' ) );
				wp_die();
			}

			$result = $this->install_plugin();
			if ( $result ) {
				wp_send_json_success();
				wp_die();
			}

			wp_send_json_error( esc_html__( 'upgrade failed. Please reload and try again.', 'foxiz' ) );
			wp_die();
		}

		/**
		 * @param string $action
		 *
		 * @return string
		 */
		private function get_button( $action = '' ) {

			if ( 'upgrade' === $action ) {
				$button_label = esc_html__( 'Upgrade Now', 'foxiz' );
				$action       = esc_html__( 'Upgrading...', 'foxiz' );
				$status       = '';
				$btn_id       = 'ruby-upgrade-core';
			} else {
				$status       = $this->plugin_status();
				$button_label = esc_html__( 'Let&rsquo;s Get Started', 'foxiz' );
				$actionLabels = [
					'active'    => esc_html__( 'Redirecting', 'foxiz' ),
					'inactive'  => esc_html__( 'Activating', 'foxiz' ),
					'not_found' => esc_html__( 'Installing &amp; Activating', 'foxiz' ),
				];
				$btn_id       = 'ruby-install-core';
				$action       = isset( $actionLabels[ $status ] ) ? $actionLabels[ $status ] : $actionLabels['not_found'];
			}

			$output = sprintf(
				'<button id="%s" class="ruby-install-core ruby-button button button-primary" data-status="%s" data-action="%s" data-redirect="%s">%s</button>',
				esc_attr( $btn_id ),
				esc_attr( $status ),
				esc_attr( $action ),
				! empty( self::$core_plugin['redirect'] ) ? esc_url( self::$core_plugin['redirect'] ) : '',
				esc_html( $button_label )
			);
			$output .= '<div class="rb-error is-hidden"></div>';

			return $output;
		}

		/**
		 * activation notice
		 */
		public function activation_notice() {

			if ( $this->plugin_status() === 'active' ) {
				return;
			}

			wp_enqueue_style( 'foxiz-activation' );
			wp_enqueue_script( 'foxiz-activation' );

			?>
			<div id="ruby-activation-notice" class="ruby-notice notice is-dismissible">
				<div class="ruby-notice-inner">
					<div class="ruby-welcome">
						<div class="ruby-welcome-content">
							<p class="ruby-welcome-tagline"><?php echo esc_html__( 'Thank you for choosing the Foxiz theme!', 'foxiz' ); ?></p>
							<h2 class="ruby-welcome-title"><?php echo esc_html__( 'Launch Your Perfect Site in No Time', 'foxiz' ); ?> &#128640;</h2>
							<p class="ruby-welcome-description"><?php echo esc_html__( 'Discover a WordPress theme that streamlines article and blog post writing. We provide top-notch support and welcoming assistance!', 'foxiz' ); ?></p>
							<?php echo html_entity_decode( $this->get_button() ); ?>
							<p class="ruby-welcome-sub-description"><?php echo esc_html__( 'By clicking the button, you will install and activate the Core plugin of the theme to unlock all theme features.', 'foxiz' ); ?></p>
						</div>
						<div class="ruby-welcome-featured">
							<img src="<?php echo esc_url( FOXIZ_THEME_URI . 'backend/assets/intro.jpg' ); ?>" alt="<?php echo esc_attr__( 'Foxiz Demos', 'foxiz' ); ?>">
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function upgrade_notice() {

			wp_enqueue_style( 'foxiz-activation' );
			wp_enqueue_script( 'foxiz-activation' );
			?>
			<div id="ruby-activation-notice" class="ruby-notice notice is-dismissible">
				<div class="ruby-notice-inner">
					<div class="ruby-welcome">
						<div class="ruby-welcome-content">
							<p class="ruby-welcome-tagline"><?php echo esc_html__( 'Thank you for choosing the Foxiz theme!', 'foxiz' ); ?></p>
							<h2 class="ruby-welcome-title"><?php echo esc_html__( 'New Foxiz Core Update Available', 'foxiz' ); ?></h2>
							<p class="ruby-welcome-description"><?php echo esc_html__( 'To ensure optimal performance and compatibility, please update Foxiz Core to the latest version.', 'foxiz' ); ?></p>
							<?php echo html_entity_decode( $this->get_button( 'upgrade' ) ); ?>
							<p class="ruby-welcome-sub-description"><?php echo esc_html__( 'Before proceeding with any updates, please back up your site to safeguard your data.', 'foxiz' ); ?></p>
						</div>
						<div class="ruby-welcome-featured">
							<img src="<?php echo esc_url( FOXIZ_THEME_URI . 'backend/assets/intro.jpg' ); ?>" alt="<?php echo esc_attr__( 'Foxiz Demos', 'foxiz' ); ?>">
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function dismiss_notice() {

			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( false === wp_verify_nonce( $nonce, self::$nonce ) ) {
				wp_send_json_error( esc_html__( 'Nonce not validated.', 'foxiz' ) );
			}

			set_transient( '_' . self::$nonce, 1, 21600 );
			wp_send_json_success();
		}

		public function reset_notices() {

			delete_transient( '_' . self::$nonce );
		}
	}
}

/** load */
Foxiz_Theme_Activation::get_instance();
