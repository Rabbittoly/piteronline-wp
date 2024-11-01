<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Admin_Hooks' ) ) {
	class Foxiz_Admin_Hooks {

		protected static $instance = null;

		public function __construct() {

			self::$instance = $this;

			add_action( 'after_switch_theme', [ $this, 'set_defaults' ], 9 );
			add_action( 'switch_theme', [ $this, 'set_defaults' ], 9 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor' ], 90 );

			/** add settings to theme options panel */
			add_filter( 'ruby_post_types_config', [ $this, 'ctp_supported' ], 0 );
			add_filter( 'ruby_taxonomies_config', [ $this, 'ctax_supported' ], 0 );

			/** author template supported */
			add_action( 'personal_options', [ $this, 'register_author_settings' ], 10, 1 );
			add_action( 'personal_options_update', [ $this, 'update_author_settings' ], 10, 1 );
			add_action( 'edit_user_profile_update', [ $this, 'update_author_settings' ], 10, 1 );

			add_action( 'save_post', [ $this, 'update_metaboxes' ], 10, 1 );
			add_action( 'save_post', [ $this, 'content_word_count' ], 100, 1 );
		}

		public function set_defaults() {

			/** disable default elementor schemes */
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );

			$current = get_option( FOXIZ_TOS_ID );
			if ( is_array( $current ) || ! empty( $current ) ) {
				return false;
			}

			ob_start();
			include foxiz_get_file_path( 'backend/assets/defaults.json' );
			$response = ob_get_clean();
			$data     = json_decode( $response, true );
			if ( is_array( $data ) ) {
				set_transient( '_ruby_old_settings', $current, 30 * 86400 );
				update_option( FOXIZ_TOS_ID, $data );
			}

			return false;
		}

		function enqueue( $hook ) {

			wp_enqueue_style( 'foxiz-admin-style', foxiz_get_file_uri( 'backend/assets/admin.css' ), [], FOXIZ_THEME_VERSION, 'all' );

			if ( $hook === 'post.php' || $hook === 'post-new.php' || 'widgets.php' === $hook || 'nav-menus.php' === $hook || 'term.php' === $hook ) {
				wp_register_script( 'foxiz-admin', foxiz_get_file_uri( 'backend/assets/admin.js' ), [ 'jquery' ], FOXIZ_THEME_VERSION, true );
				wp_enqueue_script( 'foxiz-admin' );
			}
		}

		function enqueue_editor() {

			$deps      = [];
			$uri       = is_rtl() ? 'backend/assets/editor-rtl.css' : 'backend/assets/editor.css';
			$gfont_url = Foxiz_Font::get_instance()->get_font_url();

			if ( ! empty( $gfont_url ) ) {
				wp_register_style( 'foxiz-gfonts-editor', esc_url_raw( $gfont_url ), $deps, FOXIZ_THEME_VERSION, 'all' );
				$deps[] = 'foxiz-gfonts-editor';
			}
			wp_register_style( 'foxiz-editor-style', foxiz_get_file_uri( $uri ), $deps, FOXIZ_THEME_VERSION, 'all' );
			wp_enqueue_style( 'foxiz-editor-style' );
		}

		/**
		 * @return mixed|void
		 */
		function ctp_supported() {

			$post_types = apply_filters( 'cptui_get_post_type_data', get_option( 'cptui_post_types', [] ), get_current_blog_id() );

			if ( function_exists( 'acf_maybe_unserialize' ) ) {
				$acf_query = new WP_Query( [
					'posts_per_page'         => - 1,
					'post_type'              => 'acf-post-type',
					'orderby'                => 'menu_order title',
					'order'                  => 'ASC',
					'suppress_filters'       => false,
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'post_status'            => [ 'publish', 'acf-disabled' ],
				] );

				if ( $acf_query->have_posts() ) {
					while ( $acf_query->have_posts() ) {
						$acf_query->the_post();
						global $post;
						$data = (array) acf_maybe_unserialize( $post->post_content );
						if ( empty( $data['post_type'] ) ) {
							continue;
						}
						$key                = $data['post_type'];
						$label              = ! empty( $data['labels']['singular_name'] ) ? $data['labels']['singular_name'] : $data['post_type'];
						$post_types[ $key ] = [ 'label' => $label ];
					}

					wp_reset_postdata();
				}
			}

			return $post_types;
		}

		/**
		 * @return mixed|void
		 */
		function ctax_supported() {

			$taxonomies = apply_filters( 'cptui_get_taxonomy_data', get_option( 'cptui_taxonomies', [] ), get_current_blog_id() );

			if ( function_exists( 'acf_maybe_unserialize' ) ) {
				$acf_query = new WP_Query( [
					'posts_per_page'         => - 1,
					'post_type'              => 'acf-taxonomy',
					'orderby'                => 'menu_order title',
					'order'                  => 'ASC',
					'suppress_filters'       => false,
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'post_status'            => [ 'publish', 'acf-disabled' ],
				] );

				if ( $acf_query->have_posts() ) {
					while ( $acf_query->have_posts() ) {
						$acf_query->the_post();
						global $post;
						$data = (array) acf_maybe_unserialize( $post->post_content );

						if ( empty( $data['taxonomy'] ) ) {
							continue;
						}
						$key                = $data['taxonomy'];
						$label              = ! empty( $data['labels']['singular_name'] ) ? $data['labels']['singular_name'] : $data['taxonomy'];
						$taxonomies[ $key ] = [ 'label' => $label ];
					}

					wp_reset_postdata();
				}
			}

			return $taxonomies;
		}

		/**
		 * @param $profile_user
		 */
		function register_author_settings( $profile_user ) {

			$user_id = $profile_user->ID;

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$author_bio = get_user_meta( $user_id, 'author_bio', true );
			$tick       = get_user_meta( $user_id, 'author_tick', true );

			?>
			<table class="form-table author-template-settings" role="presentation">
				<tr class="user-template-wrap">
					<th><label for="description"><?php esc_html_e( 'Author Page Template Builder', 'foxiz' ); ?></label>
					</th>
					<td>
						<textarea placeholder="[Ruby_E_Template id=&quot;1&quot;]" name="template_global" id="description" rows="2" cols="30"><?php echo get_user_meta( $user_id, 'template_global', true ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Use the Ruby Template to customize the author profile and display details like biography, occupation, and skills.', 'foxiz' ); ?></p>
					</td>
				</tr>
				<tr class="user-bio-box">
					<th><label for="role"><?php esc_html_e( 'Author Bio', 'foxiz' ); ?></label></th>
					<td>
						<select name="author_bio" id="author_bio">
							<option value="0" <?php if ( $author_bio == '0' ) {
								echo 'selected';
							} ?>><?php esc_html_e( '- Default from Theme Options -', 'foxiz' ); ?>
							</option>
							<option value="1" <?php if ( $author_bio == '1' ) {
								echo 'selected';
							} ?>><?php esc_html_e( 'Enable', 'foxiz' ); ?>
							</option>
							<option value="-1" <?php if ( $author_bio == '-1' ) {
								echo 'selected';
							} ?>>
								<?php esc_html_e( 'Disable', 'foxiz' ); ?>
							</option>
						</select>
						<p class="description"><?php esc_html_e( 'Display author bio box in the header of the author page.', 'foxiz' ); ?></p>
					</td>
				</tr>
				<tr class="user-verified">
					<th><label for="role"><?php esc_html_e( 'Verified Tick for Author Box', 'foxiz' ); ?></label></th>
					<td>
						<select name="author_tick" id="author_tick">
							<option value="1" <?php if ( $tick == '1' ) {
								echo 'selected';
							} ?>><?php esc_html_e( 'Enable', 'foxiz' ); ?>
							</option>
							<option value="0" <?php if ( $tick == '0' ) {
								echo 'selected';
							} ?>><?php esc_html_e( 'Disable', 'foxiz' ); ?>
							</option>
						</select>
						<p class="description"><?php esc_html_e( 'Display a Verified tick icon after the author meta on the author box.', 'foxiz' ); ?></p>
					</td>
				</tr>
			</table>
			<?php
			/** create nonce */
			wp_nonce_field( 'rb_user_profile_update', 'rb_nonce' );
		}

		/**
		 * @param $user_id
		 */
		function update_author_settings( $user_id ) {

			if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'rb_user_profile_update', 'rb_nonce' ) ) {
				return;
			}
			if ( ! empty( $_POST['template_global'] ) ) {
				update_user_meta( $user_id, 'template_global', sanitize_text_field( trim( $_POST['template_global'] ) ) );
			} else {
				delete_user_meta( $user_id, 'template_global' );
			}
			if ( isset( $_POST['author_bio'] ) ) {
				update_user_meta( $user_id, 'author_bio', sanitize_text_field( $_POST['author_bio'] ) );
			}
			if ( isset( $_POST['author_tick'] ) ) {
				update_user_meta( $user_id, 'author_tick', sanitize_text_field( $_POST['author_tick'] ) );
			}
		}

		/**
		 * update posts
		 *
		 * @param $post_id
		 *
		 */
		function update_metaboxes( $post_id ) {

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
				return;
			}

			if ( foxiz_is_sponsored_post( $post_id ) ) {
				update_post_meta( $post_id, 'foxiz_sponsored', 1 );
			} else {
				delete_post_meta( $post_id, 'foxiz_sponsored' );
			}

			$review = foxiz_get_review_settings( $post_id );

			if ( ! empty( $review['average'] ) ) {
				if ( empty( $review['type'] ) || 'score' === $review['type'] ) {
					update_post_meta( $post_id, 'foxiz_review_average', floatval( $review['average'] ) );
				} else {
					update_post_meta( $post_id, 'foxiz_review_average', floatval( $review['average'] ) * 2 );
				}
			} else {
				delete_post_meta( $post_id, 'foxiz_review_average' );
			}

			delete_post_meta( $post_id, 'rb_content_images' );
		}

		/**
		 * @param string $post_id
		 */
		function content_word_count( $post_id = '' ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
				return;
			}

			delete_post_meta( $post_id, 'foxiz_content_total_word' );
			foxiz_update_word_count( $post_id );
		}

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

/** load */
Foxiz_Admin_Hooks::get_instance();