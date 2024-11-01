<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_BBP_Settings' ) ) {
	class Ruby_BBP_Settings {

		protected static $instance = null;
		public $page_slug, $page_title, $menu_title;

		public $general_ID, $sidebars_ID, $topic_ID;
		private static $parent_slug = 'foxiz-admin';
		public $capability = 'manage_options';
		public $menu_id;

		static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		function __construct() {

			if ( ! class_exists( 'RB_ADMIN_CORE' ) || ! RB_ADMIN_CORE::get_instance()->get_purchase_code() ) {
				return;
			}

			$this->page_title  = esc_html__( 'Ruby bbPress', 'ruby-bbp' );
			$this->menu_title  = esc_html__( 'Ruby bbPress', 'ruby-bbp' );
			$this->page_slug   = 'ruby-bbp-supported';
			$this->general_ID  = 'ruby_bbp_general';
			$this->sidebars_ID = 'ruby_bbp_sidebars';
			$this->topic_ID    = 'ruby_bbp_topic';

			add_action( 'admin_menu', [ $this, 'add_admin_menu' ], 3000 );
			add_action( 'admin_init', [ $this, 'register_settings' ] );
		}

		public function add_admin_menu() {

			$this->menu_id = add_submenu_page(
				self::$parent_slug,
				esc_html__( 'bbPress Forums', 'ruby-bbp' ),
				esc_html__( 'bbPress Forums', 'ruby-bbp' ),
				$this->capability,
				$this->page_slug,
				[ $this, 'settings_interface' ]
			);
		}

		public function settings_interface() {

			/** load header */
			RB_ADMIN_CORE::get_instance()->header_template();
			?>
			<div class="rb-dashboard-wrap">
				<div class="rb-dashboard-section rb-dashboard-fw">
					<div class="rb-intro-content">
						<div class="rb-intro-content-inner">
							<h2 class="rb-dashboard-title">
								<?php esc_html_e( 'Enhanced bbPress Features', 'ruby-bbp' ); ?>
							</h2>
							<p class="rb-dashboard-tagline"><?php esc_html_e( 'Provides additional styles, layouts, and advanced features for the bbPress forum plugin.', 'ruby-bbp' ); ?></p>
						</div>
						<div class="rb-intro-big-icon"><i class="rbi-dash rbi-dash-bbpress"></i></div>
					</div>
					<div class="wrap ruby-bbp-settings">
						<?php
						$active_tab = 'general';
						if ( isset( $_GET['tab'] ) ) {
							$active_tab = $_GET['tab'];
						}
						?>
						<h2 class="nav-tab-wrapper">
							<a href="?page=<?php echo $_GET['page']; ?>&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General', 'ruby-bbp' ); ?></a>
							<a href="?page=<?php echo $_GET['page']; ?>&tab=sidebars" class="nav-tab <?php echo $active_tab === 'sidebars' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Sidebars', 'ruby-bbp' ); ?></a>
							<a href="?page=<?php echo $_GET['page']; ?>&tab=topic" class="nav-tab <?php echo $active_tab === 'topic' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Topic', 'ruby-bbp' ); ?></a>
						</h2>
						<form class="is-boxed" action="options.php" method="post">
							<?php switch ( $active_tab ) {
								case 'sidebars' :
									settings_fields( $this->sidebars_ID );
									do_settings_sections( $this->sidebars_ID );
									submit_button();
									break;
								case 'topic' :
									settings_fields( $this->topic_ID );
									do_settings_sections( $this->topic_ID );
									submit_button();
									break;
								default :
									settings_fields( $this->general_ID );
									do_settings_sections( $this->general_ID );
									submit_button();
							} ?>
						</form>
					</div>
				</div>
			</div>
			<?php
		}

		public function register_settings() {

			add_settings_section( $this->general_ID, esc_html__( 'General Settings', 'ruby-bbp' ), [
				$this,
				'general_section_info',
			], $this->general_ID );
			register_setting( $this->general_ID, 'ruby_bbp_private' );
			register_setting( $this->general_ID, 'ruby_bbp_lead_topic' );
			register_setting( $this->general_ID, 'ruby_bbp_search_placeholder' );
			register_setting( $this->general_ID, 'ruby_bbp_search_topic_placeholder' );
			register_setting( $this->general_ID, 'ruby_bbp_search_reply_placeholder' );
			register_setting( $this->general_ID, 'ruby_bbp_avatar' );

			add_settings_section( $this->sidebars_ID, esc_html__( 'Sidebar Settings', 'ruby-bbp' ), [
				$this,
				'sidebar_section_info',
			], $this->sidebars_ID );
			register_setting( $this->sidebars_ID, 'ruby_bbp_sidebar' );
			register_setting( $this->sidebars_ID, 'ruby_bbp_forum_sidebar' );
			register_setting( $this->sidebars_ID, 'ruby_bbp_topic_sidebar' );
			register_setting( $this->sidebars_ID, 'ruby_bbp_shortcode_sidebar' );

			add_settings_section( $this->topic_ID, esc_html__( 'Topic', 'ruby-bbp' ), [
				$this,
				'topic_section_info',
			], $this->topic_ID );

			register_setting( $this->topic_ID, 'ruby_bbp_topic_title' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_description' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_button' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_image' );

			register_setting( $this->topic_ID, 'ruby_bbp_topic_forums' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_custom_field_1' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_custom_field_2' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_status_1' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_status_2' );
			register_setting( $this->topic_ID, 'ruby_bbp_topic_status_3' );

			/** setting field */
			/** general */
			add_settings_field( 'ruby-bbp-private', esc_html__( 'Private Content', 'ruby-bbp' ), [
				$this,
				'on_off_dropdown',
			], $this->general_ID, $this->general_ID, [
				'name'     => 'ruby_bbp_private',
				'selected' => get_option( 'ruby_bbp_private' ),
				'desc'     => esc_html__( 'Enable private content for topic creator and the site administrator only.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-topic-lead', esc_html__( 'Show Lead Topics', 'ruby-bbp' ), [
				$this,
				'on_off_dropdown',
			], $this->general_ID, $this->general_ID, [
				'name'     => 'ruby_bbp_lead_topic',
				'selected' => get_option( 'ruby_bbp_lead_topic' ),
				'desc'     => esc_html__( 'Alternate layout style where topics appear as lead posts.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-search', esc_html__( 'General Search Placeholder', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->general_ID, $this->general_ID, [
				'name'        => 'ruby_bbp_search_placeholder',
				'value'       => get_option( 'ruby_bbp_search_placeholder', esc_html__( 'Search All Forums', 'ruby-bbp' ) ),
				'placeholder' => esc_html__( 'Search All Forums', 'ruby-bbp' ),
				'desc'        => esc_html__( 'Input a placeholder for the forum search form.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-search', esc_html__( 'Topic Search Placeholder', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->general_ID, $this->general_ID, [
				'name'        => 'ruby_bbp_search_topic_placeholder',
				'value'       => get_option( 'ruby_bbp_search_topic_placeholder', esc_html__( 'Search for Topics', 'ruby-bbp' ) ),
				'placeholder' => esc_html__( 'Search for Topics', 'ruby-bbp' ),
				'desc'        => esc_html__( 'Input a placeholder for the topic search form.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-reply-search', esc_html__( 'Reply Search Placeholder', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->general_ID, $this->general_ID, [
				'name'        => 'ruby_bbp_search_reply_placeholder',
				'value'       => get_option( 'ruby_bbp_search_reply_placeholder', esc_html__( 'Search for Replies', 'ruby-bbp' ) ),
				'placeholder' => esc_html__( 'Search for Replies', 'ruby-bbp' ),
				'desc'        => esc_html__( 'Input a placeholder for the reply search form.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-avatar', esc_html__( 'Ruby Avatar', 'ruby-bbp' ), [
				$this,
				'on_off_dropdown',
			], $this->general_ID, $this->general_ID, [
				'name'     => 'ruby_bbp_avatar',
				'selected' => get_option( 'ruby_bbp_avatar', 1 ),
				'desc'     => esc_html__( 'Show avatar based on the first letter of the username if Gravatar is not found.', 'ruby-bbp' ),
			] );

			/** sidebars */
			add_settings_field( 'ruby-bbp-sidebar', esc_html__( 'Global Sidebar', 'ruby-bbp' ), [
				$this,
				'sidebar_dropdown',
			], $this->sidebars_ID, $this->sidebars_ID, [
				'name'     => 'ruby_bbp_sidebar',
				'selected' => get_option( 'ruby_bbp_sidebar', 0 ),
				'desc'     => esc_html__( 'Assign a sidebar all bbpress pages.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-forum-sidebar', esc_html__( 'Forum Sidebar', 'ruby-bbp' ), [
				$this,
				'sidebar_dropdown',
			], $this->sidebars_ID, $this->sidebars_ID, [
				'name'        => 'ruby_bbp_forum_sidebar',
				'has_default' => true,
				'selected'    => get_option( 'ruby_bbp_forum_sidebar', '_default' ),
				'desc'        => esc_html__( 'Assign a sidebar for the forums page.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-topic-sidebar', esc_html__( 'Topic Sidebar', 'ruby-bbp' ), [
				$this,
				'sidebar_dropdown',
			], $this->sidebars_ID, $this->sidebars_ID, [
				'name'        => 'ruby_bbp_topic_sidebar',
				'has_default' => true,
				'selected'    => get_option( 'ruby_bbp_topic_sidebar', '_default' ),
				'desc'        => esc_html__( 'Assign a sidebar for the topic.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-shortcode-sidebar', esc_html__( 'Page Shortcode Sidebar', 'ruby-bbp' ), [
				$this,
				'sidebar_dropdown',
			], $this->sidebars_ID, $this->sidebars_ID, [
				'name'        => 'ruby_bbp_shortcode_sidebar',
				'has_default' => true,
				'selected'    => get_option( 'ruby_bbp_shortcode_sidebar', 0 ),
				'desc'        => esc_html__( 'Assign a sidebar for the bbPress pages created by shortcodes.', 'ruby-bbp' ),
			] );

			/** topic */
			add_settings_field( 'ruby-bbp-topic-title', esc_html__( 'New Topic - Toggle Heading', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_title',
				'placeholder' => esc_html__( 'Start new topic?', 'ruby-bbp' ),
				'value'       => get_option( 'ruby_bbp_topic_title', '' ),
				'desc'        => esc_html__( 'Add a heading for the create new topic toggle box.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-topic-description', esc_html__( 'New Topic - Toggle Description', 'ruby-bbp' ), [
				$this,
				'input_textarea',
			], $this->topic_ID, $this->topic_ID, [
				'name'  => 'ruby_bbp_topic_description',
				'value' => get_option( 'ruby_bbp_topic_description', '' ),
				'desc'  => esc_html__( 'Create your own custom text input field for the new topic.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-button', esc_html__( 'New Topic - Toggle Button', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_button',
				'placeholder' => esc_html__( 'Add New', 'ruby-bbp' ),
				'value'       => get_option( 'ruby_bbp_topic_button', 'Add a New Topic' ),
				'desc'        => esc_html__( 'Add a button label for the create new topic toggle box.', 'ruby-bbp' ),
			] );
			add_settings_field( 'ruby-bbp-topic-image', esc_html__( 'New Topic - Box Image', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_image',
				'placeholder' => 'https://yoursite.com/wp-content/uploads/...jpg',
				'value'       => get_option( 'ruby_bbp_topic_image', '' ),
				'desc'        => esc_html__( 'Add a featured image (attachment link) for the new topic toggle box.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-custom-field-1', esc_html__( 'Custom Filed 1', 'ruby-bbp' ), [
				$this,
				'topic_custom_field',
			], $this->topic_ID, $this->topic_ID, [
				'name' => 'ruby_bbp_topic_custom_field_1',
				'data' => get_option( 'ruby_bbp_topic_custom_field_1', [] ),
				'desc' => esc_html__( 'Create a custom text input field when creating a new topic.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-custom-field-2', esc_html__( 'Custom Filed 2', 'ruby-bbp' ), [
				$this,
				'topic_custom_field',
			], $this->topic_ID, $this->topic_ID, [
				'name' => 'ruby_bbp_topic_custom_field_2',
				'data' => get_option( 'ruby_bbp_topic_custom_field_2', [] ),
				'desc' => esc_html__( 'Create a custom text input field when creating a new topic.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-status-1', esc_html__( 'Frontend Status - 1st Label', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_status_1',
				'placeholder' => esc_html__( 'Resolved', 'ruby-bbp' ),
				'value'       => get_option( 'ruby_bbp_topic_status_1', '' ),
				'desc'        => esc_html__( 'Add a custom label for the 1st front-end status meta.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-status-2', esc_html__( 'Frontend Status - 2nd Label', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_status_2',
				'placeholder' => esc_html__( 'Not Resolved', 'ruby-bbp' ),
				'value'       => get_option( 'ruby_bbp_topic_status_2', '' ),
				'desc'        => esc_html__( 'Add a custom label for the 2nd front-end status meta.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-status-3', esc_html__( 'Frontend Status - 3rd Label', 'ruby-bbp' ), [
				$this,
				'input_text',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_status_3',
				'placeholder' => esc_html__( 'Has a Solution', 'ruby-bbp' ),
				'value'       => get_option( 'ruby_bbp_topic_status_3', '' ),
				'desc'        => esc_html__( 'Add a custom label for the 3rd front-end status meta.', 'ruby-bbp' ),
			] );

			add_settings_field( 'ruby-bbp-topic-forums', esc_html__( 'New Topic Form in Forums Page', 'ruby-bbp' ), [
				$this,
				'on_off_dropdown',
			], $this->topic_ID, $this->topic_ID, [
				'name'        => 'ruby_bbp_topic_forums',
				'has_default' => true,
				'selected'    => get_option( 'ruby_bbp_topic_forums', 1 ),
				'desc'        => esc_html__( 'Enable or disable the create new topic button in the forums listing.', 'ruby-bbp' ),
			] );
		}

		function general_section_info() {

			echo '<p>' . esc_html__( 'Forum features that can be enabled and disabled.', 'ruby-bbp' ) . '</p>';
		}

		function sidebar_section_info() {

			echo '<p>' . esc_html__( 'Customize the forum sidebars.', 'ruby-bbp' ) . '</p>';
		}

		function topic_section_info() {

			echo '<p>' . esc_html__( 'Customize the create new topic.', 'ruby-bbp' ) . '</p>';
		}

		function on_off_dropdown( $args ) { ?>
			<select name="<?php echo esc_attr( $args['name'] ); ?>">
				<option value="1" <?php if ( ! empty( $args['selected'] ) ) {
					echo 'selected';
				} ?> ><?php esc_html_e( 'Enable', 'ruby-bbp' ); ?></option>
				<option value="0" <?php if ( empty( $args['selected'] ) ) {
					echo 'selected';
				} ?> ><?php esc_html_e( '- Disable -', 'ruby-bbp' ); ?></option>
			</select>
			<p><?php if ( ! empty( $args['desc'] ) ) {
					echo esc_html( $args['desc'] );
				} ?></p>
			<?php
		}

		function sidebar_dropdown( $args ) { ?>
			<select name="<?php echo esc_attr( $args['name'] ); ?>">
				<?php if ( ! empty( $args['has_default'] ) ) : ?>
					<option value="_default" <?php if ( empty( $args['selected'] ) && '_default' == $args['selected'] ) {
						echo 'selected';
					} ?>><?php esc_html_e( '- Default -', 'ruby-bbp' ); ?></option>
				<?php endif; ?>
				<option value="0" <?php if ( empty( $args['selected'] ) ) {
					echo 'selected';
				} ?>><?php esc_html_e( 'Disable', 'ruby-bbp' ); ?></option>
				<?php global $wp_registered_sidebars;
				foreach ( $wp_registered_sidebars as $sidebar_id => $data ) {

					if ( $args['selected'] === $sidebar_id ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
					<option value="<?php echo esc_attr( $sidebar_id ); ?>" <?php echo $selected ?> ><?php echo esc_html( $data['name'] ); ?></option>
				<?php } ?>
			</select>
			<p><?php if ( ! empty( $args['desc'] ) ) {
					echo esc_html( $args['desc'] );
				} ?></p>
			<?php
		}

		function input_text( $args ) { ?>
			<input class="regular-text" type="text" name="<?php echo esc_attr( $args['name'] ); ?>" placeholder="<?php if ( ! empty( $args['placeholder'] ) ) {
				echo esc_attr( $args['placeholder'] );
			} ?>" value="<?php echo $args['value']; ?>">
			<p><?php if ( ! empty( $args['desc'] ) ) {
					echo esc_html( $args['desc'] );
				} ?></p>
			<?php
		}

		function input_textarea( $args ) { ?>
			<textarea rows="7" cols="50" name="<?php echo esc_attr( $args['name'] ); ?>" placeholder="<?php if ( ! empty( $args['placeholder'] ) ) {
				echo esc_attr( $args['placeholder'] );
			} ?>"><?php echo $args['value']; ?></textarea>
			<p><?php if ( ! empty( $args['desc'] ) ) {
					echo esc_html( $args['desc'] );
				} ?></p>
			<?php
		}

		function topic_custom_field( $args ) {

			$data = wp_parse_args(
				$args['data'], [
					'title'   => '',
					'private' => 0,
				]
			)
			?>
			<p>
				<label for="<?php echo esc_attr( $args['name'] . '-title' ); ?>"><?php echo esc_html__( 'Title:', 'ruby-bbp' ); ?>
					<input type="text" name="<?php echo esc_attr( $args['name'] . '[title]' ); ?>" value="<?php echo $data['title']; ?>"></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $args['name'] . '-private' ); ?>"><?php esc_html_e( 'is private field?', 'ruby-bbp' ); ?>
					<select name="<?php echo esc_attr( $args['name'] . '[private]' ); ?>">
						<option value="1" <?php if ( ! empty( $data['private'] ) ) {
							echo 'selected';
						} ?> ><?php esc_html_e( 'Enable', 'ruby-bbp' ); ?></option>
						<option value="0" <?php if ( empty( $data['private'] ) ) {
							echo 'selected';
						} ?> ><?php esc_html_e( '- Disable -', 'ruby-bbp' ); ?></option>
					</select></label></p>
			<p><?php if ( ! empty( $args['desc'] ) ) {
					echo esc_html( $args['desc'] );
				} ?></p>
			<?php
		}
	}
}
