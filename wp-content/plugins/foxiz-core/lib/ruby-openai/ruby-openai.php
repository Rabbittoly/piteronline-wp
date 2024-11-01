<?php
/**
 * Plugin Name:    Ruby OpenAI Assistant
 * Plugin URI:     https://themeforest.net/user/theme-ruby/
 * Description:    A powerful tool designed to streamline content creation for bloggers, writers, and website owners.
 * Version:        2.0
 * Domain Path:    /languages/
 * Author:         Theme-Ruby
 * Author URI:     https://themeforest.net/user/theme-ruby/
 *
 * @package        ruby-openai
 */
defined( 'ABSPATH' ) || exit;
define( 'RB_OPENAI_VER', '2.0' );
define( 'RB_OPENAI_URL', plugin_dir_url( __FILE__ ) );
define( 'RB_OPENAI_PATH', plugin_dir_path( __FILE__ ) );

include_once RB_OPENAI_PATH . 'helpers.php';
include_once RB_OPENAI_PATH . 'edit-template.php';

if ( ! class_exists( 'RB_OPENAI_ASSISTANT', false ) ) {
	class RB_OPENAI_ASSISTANT {

		protected static $instance = null;
		public $capability = 'manage_options';
		private static $parent_slug = 'foxiz-admin';
		public $menu_id;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;
			add_action( 'admin_menu', [ $this, 'add_admin_menu' ], 1000 );
			add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ], 20 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_editor_assets' ], 999 );
			add_action( 'wp_ajax_rb_openai_save', [ $this, 'save' ] );
			add_action( 'wp_ajax_rb_openai_create_content', [ $this, 'create_content' ] );
			add_filter( 'rb_single_metaboxes', [ $this, 'meta_configs' ] );
		}

		function register_assets() {

			$script_version = filemtime( RB_OPENAI_PATH . 'assets/js/panel.js' );
			$editor_version = filemtime( RB_OPENAI_PATH . 'assets/js/editor.js' );

			wp_register_script( 'rb-openai-panel', RB_OPENAI_URL . 'assets/js/panel.js', [ 'jquery' ], $script_version, true );
			wp_register_script( 'rb-openai-editor', RB_OPENAI_URL . 'assets/js/editor.js', [ 'jquery' ], $editor_version, true );
		}

		public function load_assets() {

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_panel_assets' ], 80 );
		}

		public function enqueue_panel_assets() {

			wp_enqueue_style( 'rb-admin-style' );
			wp_enqueue_script( 'rb-openai-panel' );
		}

		public function enqueue_editor_assets( $hook ) {

			if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
				wp_enqueue_script( 'rb-openai-editor' );
			}
		}

		public function add_admin_menu() {

			if ( ! class_exists( 'RB_ADMIN_CORE' ) || ! RB_ADMIN_CORE::get_instance()->get_purchase_code() ) {
				return;
			}
			$this->menu_id = add_submenu_page(
				self::$parent_slug,
				esc_html__( 'OpenAI Assistant', 'rb-openai' ),
				esc_html__( 'OpenAI Assistant', 'rb-openai' ),
				$this->capability,
				'ruby-openai',
				[ $this, 'settings_interface' ]
			);

			add_action( 'load-' . $this->menu_id, [ $this, 'load_assets' ] );
		}

		function save() {

			// Check the nonce for security
			if ( ! check_ajax_referer( 'rb-openai', '_wpnonce_ruby' ) ) {
				wp_send_json_error( esc_html__( 'Invalid nonce verification', 'rb-openai' ), 400 );

				wp_die();
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'rb-openai' ), 403 );

				wp_die();
			}

			// Process the form data
			$rb_openai     = isset( $_POST['rb_openai'] ) ? sanitize_text_field( $_POST['rb_openai'] ) : '';
			$api_key       = isset( $_POST['rb_openai_api_key'] ) ? sanitize_text_field( $_POST['rb_openai_api_key'] ) : '';
			$max_token     = isset( $_POST['rb_openai_max_tokens'] ) ? absint( sanitize_text_field( $_POST['rb_openai_max_tokens'] ) ) : '';
			$temperature   = isset( $_POST['rb_openai_temperature'] ) ? floatval( sanitize_text_field( $_POST['rb_openai_temperature'] ) ) : '';
			$writing_style = isset( $_POST['rb_openai_writing_style'] ) ? sanitize_text_field( $_POST['rb_openai_writing_style'] ) : '';
			$language      = isset( $_POST['rb_openai_language'] ) ? sanitize_text_field( $_POST['rb_openai_language'] ) : '';

			update_option( 'rb_openai', $rb_openai );
			update_option( 'rb_openai_api_key', trim( $api_key ) );
			update_option( 'rb_openai_max_tokens', $max_token );
			update_option( 'rb_openai_temperature', $temperature );
			update_option( 'rb_openai_writing_style', $writing_style );
			update_option( 'rb_openai_language', $language );

			wp_send_json_success( 'Save successfully!' );
		}

		public function settings_interface() {

			if ( class_exists( 'RB_ADMIN_CORE' ) ) {
				RB_ADMIN_CORE::get_instance()->header_template();
			}

			$rb_openai               = get_option( 'rb_openai' ) ? get_option( 'rb_openai' ) : false;
			$rb_openai_api_key       = get_option( 'rb_openai_api_key' ) ? get_option( 'rb_openai_api_key' ) : '';
			$rb_openai_max_tokens    = get_option( 'rb_openai_max_tokens' ) ? get_option( 'rb_openai_max_tokens' ) : 2000;
			$rb_openai_temperature   = get_option( 'rb_openai_temperature' ) ? get_option( 'rb_openai_temperature' ) : 0.8;
			$rb_openai_writing_style = get_option( 'rb_openai_writing_style' ) ? get_option( 'rb_openai_writing_style' ) : 'creative';
			$rb_openai_language      = get_option( 'rb_openai_language' ) ? get_option( 'rb_openai_language' ) : 'english';

			?>
			<div class="rb-dashboard-wrap rb-dashboard-openai">
				<div class="rb-dashboard-section rb-dashboard-fw">
					<div class="rb-intro-content">
						<div class="rb-intro-content-inner">
							<h2 class="rb-dashboard-title">
								<?php esc_html_e( 'Ruby OpenAI Assistant', 'rb-openai' ); ?>
							</h2>
							<p class="rb-dashboard-tagline"><?php esc_html_e( 'Powered by the cutting-edge OpenAI technology (GTP 3.5 Turbo), Ruby OpenAI Assistant tool is designed to make content creation an effortless and inspiring experience.', 'rb-openai' ); ?></p>
						</div>
						<div class="rb-intro-big-icon">
							<?php if ( get_option( 'rb_openai_api_key' ) ) : ?>
								<span class="rb-loading"><i class="rbi-dash rbi-dash-openai"></i></span>
							<?php else : ?>
								<i class="rbi-dash rbi-dash-openai"></i>
							<?php endif; ?>
						</div>
					</div>
					<div class="rb-dashboard-steps col-50">
						<?php $step_class = empty( $rb_openai_api_key ) ? 'rb-dashboard-step' : 'rb-dashboard-step is-checked'; ?>
						<a class="<?php echo esc_attr( $step_class ); ?>" href="https://platform.openai.com/api-keys" target="_blank" rel="nofollow">
							<h3><?php esc_html_e( 'Create Your OpenAI API Key', 'rb-openai' ); ?></h3>
							<p class="rb-dashboard-desc"><?php esc_html_e( 'Generate your own API key through your OpenAI account to access the API features.', 'rb-openai' ); ?></p>
							<div class="rb-step-icon"><i class="rbi-dash rbi-dash-key"></i></div>
						</a>
						<a class="rb-dashboard-step" href="<?php echo admin_url( 'post-new.php#rb_meta_foxiz_post_options' ); ?>" target="_blank">
							<h3><?php esc_html_e( 'Generate Ideas for Your Posts', 'rb-openai' ); ?></h3>
							<p class="rb-dashboard-desc"><?php esc_html_e( 'By providing a text prompt, the API will generate a text completion that aligns with the pattern you specified.', 'rb-openai' ); ?></p>
							<div class="rb-step-icon"><i class="rbi-dash rbi-dash-write"></i></div>
						</a>
					</div>
					<form id="rb-openai" name="rb-openai" method="post" action="">
						<?php wp_nonce_field( 'rb-openai', '_wpnonce_ruby' ); ?>
						<div class="rb-form-wrap is-boxed">
							<div class="rb-panel-inline">
								<span class="rb-panel-label"><?php esc_html_e( 'OpenAI Assistant', 'rb-openai' ); ?></span>
								<label for="ai-assist" class="rb-switch">
									<input id="ai-assist" type="checkbox" class="rb-switch-input" name="rb_openai" <?php if ( $rb_openai ) {
										echo 'checked';
									} ?>>
									<span class="rb-switch-slider">
								</label>
							</div>
							<div class="rb-panel-inline">
								<label class="rb-panel-inline-label" for="api-key"><?php esc_html_e( 'OpenAI API Key', 'rb-openai' ); ?>
									<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php printf( __( 'Generate your API key by following this <a href=%1$s target="_blank" >link</a>', 'rb-openai' ), '//platform.openai.com/account/api-keys' ); ?></span></span>
								</label>
								<input id="api-key" type="text" name="rb_openai_api_key" placeholder="sk-xxxx..........................." value="<?php echo esc_attr( $rb_openai_api_key ); ?>">
							</div>
							<div class="rb-panel-inline">
								<label class="rb-panel-inline-label" for="max-tokens"><?php esc_html_e( 'Max Token', 'rb-openai' ); ?>
									<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php esc_html_e( 'Maximum number of tokens in the generated output. It controls the length of the generated text. ', 'rb-openai' ); ?></span></span>
								</label>
								<input id="max-tokens" type="text" name="rb_openai_max_tokens" placeholder="200" value="<?php echo esc_attr( $rb_openai_max_tokens ); ?>">
							</div>
							<div class="rb-panel-inline">
								<label class="rb-panel-inline-label" for="temperature"><?php esc_html_e( 'Temperature', 'rb-openai' ); ?>
									<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php esc_html_e( 'Controls the randomness of the output. Higher values (e.g., 1.2) make the output more random, while lower values (e.g., 0.8) make it more focused and deterministic.', 'rb-openai' ); ?></span></span>
								</label>
								<input id="temperature" type="text" name="rb_openai_temperature" placeholder="0.8" value="<?php echo esc_attr( $rb_openai_temperature ); ?>">
							</div>
							<div class="rb-panel-inline">
								<label class="rb-panel-inline-label" for="writing-style"><?php esc_html_e( 'Writing Style', 'rb-openai' ); ?>
									<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php esc_html_e( 'Choose a writing style that aligns with the type of content you intend to publish and resonates with your target audience.', 'rb-openai' ); ?></span></span>
								</label>
								<?php $writing_styles = rb_openai_writing_style_selection(); ?>
								<select id="writing-style" name="rb_openai_writing_style">
									<?php foreach ( $writing_styles as $key => $label ): ?>
										<?php $selected = ( $key === $rb_openai_writing_style ) ? 'selected="selected"' : ''; ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="rb-panel-inline no-border">
								<label class="rb-panel-inline-label" for="writing-style"><?php esc_html_e( 'Writing Language', 'rb-openai' ); ?>
									<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php esc_html_e( 'Choose a writing language to create content.', 'rb-openai' ); ?></span></span>
								</label>
								<?php $languages = rb_openai_languages_selection(); ?>
								<select id="writing-style" name="rb_openai_language">
									<?php foreach ( $languages as $key => $label ): ?>
										<?php $selected = ( $key === $rb_openai_language ) ? 'selected="selected"' : ''; ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="rb-form-sticky">
							<div id="rb-form-response" class="rb-response-info"></div>
							<button type="submit" name="action" class="rb-panel-button is-big-button" id="rb-submit-api" value="update">
								<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span>
								<?php echo esc_html__( 'Save Changes', 'rb-openai' ); ?></button>
						</div>
					</form>
					<div></div>
				</div>
			</div>
			<?php
		}

		public function meta_configs( $configs ) {

			$configs['tabs'][] = [
				'id'     => 'section-openai',
				'title'  => esc_html__( 'OpenAI Assistant', 'foxiz' ),
				'icon'   => 'dashicons-image-filter',
				'fields' => [
					[
						'id'       => 'ai_assistant',
						'name'     => esc_html__( 'OpenAI Assistant', 'foxiz' ),
						'type'     => 'html_template',
						'callback' => 'rb_single_openai_template',
					],
				],
			];

			return $configs;
		}

		public function create_content() {

			$prompt       = ! empty( $_POST['prompt'] ) ? sanitize_text_field( $_POST['prompt'] ) : false;
			$content_type = ! empty( $_POST['content_type'] ) ? sanitize_text_field( $_POST['content_type'] ) : 'content';

			if ( ! $prompt ) {
				wp_send_json_error( esc_html__( 'Invalid prompt', 'rb-openai' ), 403 );
				wp_die();
			}

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error( esc_html__( 'Sorry, you are not allowed to perform this action.', 'rb-openai' ), 403 );

				wp_die();
			}

			$settings = [ 'prompt' => trim( $prompt ) ];

			if ( ! empty( $content_type ) && 'content' === $content_type ) {
				$max_tokens = get_option( 'rb_openai_max_tokens' );
				if ( intval( $max_tokens ) < 2000 ) {
					$settings['max_tokens'] = 2000;
				} else {
					$settings['max_tokens'] = $max_tokens;
				}
			}

			$response = rb_openai_content_generator( $settings );
			wp_send_json( $response );

			wp_die();
		}
	}
}

/** init */
RB_OPENAI_ASSISTANT::get_instance();
