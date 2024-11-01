<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

defined( 'RB_META_ID' ) || define( 'RB_META_ID', 'rb_global_meta' );

if ( ! class_exists( 'RB_META' ) ) {
	class  RB_META {

		private static $instance;

		const RB_META_VERSION = '2.4';

		static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;

			add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ], 20 );
			add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ], PHP_INT_MAX );
			add_action( 'save_post', [ $this, 'save' ], 0 );
			add_action( 'edit_form_top', [ $this, 'create_nonce' ] );
			add_action( 'block_editor_meta_box_hidden_fields', [ $this, 'create_nonce' ] );
			add_action( 'wp_ajax_rb_meta_gallery', [ $this, 'gallery_update' ] );
		}

		/**
		 * create nonce
		 */
		function create_nonce() {

			wp_nonce_field( basename( __FILE__ ), 'rb_meta_nonce' );
		}

		/**
		 * register_meta_boxes
		 */
		public function register_meta_boxes() {

			global $post;
			global $pagenow;
			global $rb_meta_boxes;

			$rb_meta_boxes = [];
			$rb_meta_boxes = apply_filters( 'rb_meta_boxes', $rb_meta_boxes );

			if ( is_array( $rb_meta_boxes ) ) {
				foreach ( $rb_meta_boxes as $section ) {

					if ( empty( $section ) ) {
						continue;
					}

					if ( ! empty( $pagenow ) && 'post.php' === $pagenow && is_array( $section['post_types'] ) && in_array( 'page', $section['post_types'] ) ) {
						$current_template = get_post_meta( $post->ID, '_wp_page_template', true );
						if ( ! empty( $section['except_template'] ) && $section['except_template'] === $current_template ) {
							continue;
						}
						if ( ! empty( $section['include_template'] ) && $section['include_template'] !== $current_template ) {
							continue;
						}
					}

					$section = wp_parse_args( $section, [
						'id'         => '',
						'title'      => 'Ruby Meta Box',
						'context'    => 'normal',
						'post_types' => [ 'post' ],
						'priority'   => 'high',
					] );

					add_meta_box( 'rb_meta_' . $section['id'], $section['title'], [
						$this,
						'settings_form',
					], $section['post_types'], $section['context'], $section['priority'], $section );
				}
			}
		}

		public function register_scripts( $hook ) {

			$current_screen = get_current_screen();
			if ( ! empty( $current_screen->post_type ) && strpos( $current_screen->post_type, 'acf-' ) === 0 ) {
				return;
			}

			$style_deps = [];

			if ( ! wp_style_is( 'select2', 'registered' ) && ! wp_script_is( 'select2', 'registered' ) ) {
				$style_deps[] = 'select2';
				wp_register_style( 'select2', FOXIZ_CORE_URL . 'lib/redux-framework/assets/css/select2.css', [], self::RB_META_VERSION, 'all' );
			}

			wp_register_style( 'rb-meta-style', plugin_dir_url( __FILE__ ) . '/assets/meta.css', $style_deps, self::RB_META_VERSION );

			if ( $hook === 'post.php' || $hook === 'post-new.php' ) {

				$script_deps = [ 'jquery', 'jquery-ui-datepicker', 'tags-suggest' ];

				if ( ! wp_script_is( 'select2', 'registered' ) ) {
					$script_deps[] = 'select2';
					wp_register_script( 'select2', FOXIZ_CORE_URL . 'lib/redux-framework/assets/js/select2.min.js', [ 'jquery' ], self::RB_META_VERSION, true );
				}

				wp_register_script( 'rb-meta-script', plugin_dir_url( __FILE__ ) . '/assets/meta.js', $script_deps, self::RB_META_VERSION, true );
				wp_localize_script( 'rb-meta-script', 'rbMetaParams', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
				wp_enqueue_media();
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'tags-suggest' );

				wp_enqueue_style( 'rb-meta-style' );
				wp_enqueue_script( 'rb-meta-script' );
			}
		}

		function save( $post_id ) {

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $this->regs() ) {
				return false;
			}

			if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
				return false;
			}

			if ( empty( $_POST['rb_meta_nonce'] ) || ! wp_verify_nonce( $_POST['rb_meta_nonce'], basename( __FILE__ ) ) ) {
				return false;
			}

			if ( isset( $_POST['rb_meta'] ) ) {

				$stored_meta = get_post_meta( $post_id, RB_META_ID, true );
				if ( ! is_array( $stored_meta ) ) {
					$stored_meta = [];
				}

				$rb_meta_data = $_POST['rb_meta'];

				if ( is_array( $rb_meta_data ) ) {
					foreach ( $rb_meta_data as $meta_id => $meta_val ) {
						$meta_id = sanitize_text_field( $meta_id );

						/** sanitize_text_field */
						if ( ! current_user_can( 'unfiltered_html' ) ) {
							if ( is_array( $meta_val ) ) {
								foreach ( $meta_val as $key => $val ) {
									if ( is_array( $val ) ) {
										$meta_val[ $key ] = array_map( 'sanitize_text_field', $val );
									} else {
										$meta_val[ $key ] = sanitize_text_field( $val );
									}
								}
							} else {
								$meta_val = sanitize_text_field( $meta_val );
							}
						}

						if ( ! empty( $meta_val['type'] ) ) {
							if ( $meta_val['type'] === 'datetime' ) {
								if ( ! empty( $meta_val['date'] ) ) {
									if ( empty( $meta_val['time'] ) || ! preg_match( "/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $meta_val['time'] ) ) {
										$meta_val['time'] = '';
									}
									$stored_meta[ $meta_id ] = strtotime( $meta_val['date'] . ' ' . $meta_val['time'] );
									if ( ! empty( $meta_val['key'] ) ) {
										update_post_meta( $post_id, $meta_val['key'], $stored_meta[ $meta_id ] );
									}
								} else {
									$stored_meta[ $meta_id ] = '';
									if ( isset( $meta_val['kvd'] ) ) {
										update_post_meta( $post_id, $meta_val['key'], $meta_val['kvd'] );
									} else {
										update_post_meta( $post_id, $meta_val['key'], '' );
									}
								}
							}
						} else {
							$stored_meta[ $meta_id ] = $meta_val;
						}
					}
				}

				update_post_meta( $post_id, RB_META_ID, $stored_meta );

				if ( ! empty( $rb_meta_data['_single_metas'] ) && is_array( $rb_meta_data['_single_metas'] ) ) {
					foreach ( $rb_meta_data['_single_metas'] as $meta_key ) {
						$meta_key = esc_attr( $meta_key );
						if ( isset( $stored_meta[ $meta_key ] ) ) {
							update_post_meta( $post_id, 'ruby_' . $meta_key, $stored_meta[ $meta_key ] );
						}
					}
				}

				if ( ! empty( $stored_meta['post_index'] ) ) {
					$index = preg_replace( '/\D/', '', $stored_meta['post_index'] );
					update_post_meta( $post_id, 'ruby_index', $index );
				}
			}
		}

		/**
		 * @param $post
		 * @param $callback_args
		 * settings form
		 */
		function settings_form( $post, $callback_args ) {

			$stored_meta = get_post_meta( $post->ID, RB_META_ID, true );

			if ( empty( $callback_args['args'] ) ) {
				return;
			}

			$section             = $callback_args['args'];
			$wrapper_classname   = [];
			$wrapper_classname[] = 'rb-meta-wrapper';

			if ( empty( $section['tabs'] ) || ! is_array( $section['tabs'] ) ) {

				if ( ! isset( $section['fields'] ) ) {
					$section['fields'] = [];
				}

				$section['tabs']     = [
					[
						'id'     => 'rb-meta-none-tab',
						'title'  => '',
						'fields' => $section['fields'],
					],
				];
				$wrapper_classname[] = 'rb-meta-none-tab';
			}

			if ( ! empty( $section['context'] ) ) {
				$wrapper_classname[] = 'context-' . esc_attr( $section['context'] );
			}
			$wrapper_classname = implode( ' ', $wrapper_classname );

			$rb_last_tab = '';
			$data_attrs  = 'data-section_id = rb_meta_' . $section['id'];

			if ( ! empty( $section['except_template'] ) ) {
				$data_attrs .= ' data-except_template=' . esc_attr( $section['except_template'] ) . '';
			}

			if ( ! empty( $section['include_template'] ) ) {
				$data_attrs .= ' data-include_template=' . esc_attr( $section['include_template'] ) . '';
			}
			if ( ! empty( $stored_meta['last_tab'] ) && ! empty( $stored_meta['last_tab'][ $section['id'] ] ) ) {
				$rb_last_tab = $stored_meta['last_tab'][ $section['id'] ];
			} ?>
			<div class="<?php echo esc_attr( $wrapper_classname ); ?>" <?php echo esc_html( $data_attrs ); ?>>
				<input class="hidden rb-input-hidden rb-meta-last-tab" name="rb_meta[last_tab][<?php echo esc_attr( $section['id'] ); ?>]" value="<?php echo esc_attr( $rb_last_tab ); ?>"/>
				<div class="rb-meta-tab-header">
					<?php
					if ( empty( $rb_last_tab ) ) {
						$active = true;
					}
					foreach ( $section['tabs'] as $tab ) :
						$tab = wp_parse_args( $tab, [ 'id' => '', 'title' => '' ] );
						if ( ( isset( $active ) && true === $active ) || (string) $rb_last_tab === (string) $tab['id'] ) {
							$class_name = 'rb-tab-title is-active';
							$active     = false;
						} else {
							$class_name = 'rb-tab-title';
						} ?>
						<a href="#" class="<?php echo esc_attr( $class_name ); ?>" data-tab="<?php echo esc_attr( $tab['id'] ); ?>">
							<?php $title_classes = 'tab-title';
							if ( ! empty( $tab['icon'] ) ) {
								$title_classes .= ' dashicons-before ' . esc_attr( $tab['icon'] );
							} ?>
							<h3 class="<?php echo esc_attr( $title_classes ); ?>"><?php echo esc_html( $tab['title'] ); ?></h3>
						</a>
					<?php endforeach; ?>
				</div>
				<?php
				if ( empty( $rb_last_tab ) ) {
					$active = true;
				} ?>
				<div class="rb-meta-tab-content">
					<?php foreach ( $section['tabs'] as $tab ) :
						if ( ! empty( $tab['fields'] ) ) :
							$tab = wp_parse_args( $tab, [
								'id'    => '',
								'title' => '',
								'desc'  => '',
							] );

							if ( ( isset( $active ) && true === $active ) || (string) $rb_last_tab === (string) $tab['id'] ) {
								$class_name = 'rb-meta-tab is-active';
								$active     = false;
							} else {
								$class_name = 'rb-meta-tab';
							} ?>
							<div class="<?php echo esc_attr( $class_name ); ?>" id="rb-tab-<?php echo esc_attr( $tab['id'] ); ?>">
								<?php if ( ! empty( $tab['desc'] ) ) : ?>
									<p class="tab-description"><?php echo $tab['desc']; ?></p>
								<?php endif; ?>
								<?php foreach ( $tab['fields'] as $field ) :
									$params = wp_parse_args( $field, [
										'id'      => '',
										'name'    => '',
										'desc'    => '',
										'info'    => '',
										'single'  => false,
										'type'    => '',
										'class'   => '',
										'default' => '',
									] );

									if ( ! empty( $stored_meta[ $params['id'] ] ) ) {
										$params['value'] = $stored_meta[ $params['id'] ];
									}

									$func_mame = 'input_' . $params['type'];
									if ( method_exists( $this, $func_mame ) ) {
										$this->$func_mame( $params );
									}

									if ( $params['single'] ) {
										$this->add_single_meta( $params );
									}
								endforeach; ?>
							</div>
						<?php endif;
					endforeach; ?>
				</div>
			</div>
			<?php
		}

		function gallery_update() {

			if ( ! empty( $_POST['attachments'] ) ) {

				$str = '';
				foreach ( $_POST['attachments'] as $id ) {
					$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
					if ( ! empty( $thumbnail[0] ) ) {
						$str .= '<span class="thumbnail"><img  src="' . $thumbnail[0] . '" width="75" height="75"/></span>';
					}
				}
				wp_send_json( $str );
				die();
			}
		}

		/**
		 * @param $params
		 */
		function input_text( $params ) {

			$defaults = [
				'id'          => '',
				'name'        => '',
				'desc'        => '',
				'info'        => '',
				'default'     => '',
				'class'       => '',
				'placeholder' => '',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( ! isset( $params['value'] ) ) {
				if ( ! empty( $params['default'] ) ) {
					$params['value'] = $params['default'];
				} else {
					$params['value'] = '';
				}
			} ?>
			<div class="rb-meta rb-input <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<label for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></label>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class="rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<input type="text" class="rb-meta-text" placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" id="<?php echo esc_attr( $params['id'] ); ?>" value="<?php echo esc_attr( $params['value'] ); ?>"/>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/** input select */
		function input_select( $params ) {

			$defaults = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'info'    => '',
				'default' => '',
				'options' => [],
				'class'   => '',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			} ?>
			<div class="rb-meta rb-select <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<label for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></label>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class="rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<select class="rb-meta-select" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" id="<?php echo esc_attr( $params['id'] ); ?>"/>
					<?php foreach ( $params['options'] as $val => $name ) :
						if ( (string) $params['value'] === (string) $val ) : ?>
							<option selected value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $name ); ?></option>
						<?php else : ?>
							<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $name ); ?></option>
						<?php endif; ?><?php endforeach; ?>
					</select>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_image_select( $params ) {

			$defaults = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'info'    => '',
				'default' => '',
				'options' => [],
				'class'   => '',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			} ?>
			<div class="rb-meta rb-image-select <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<span class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></span>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class=" rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="rb-boxes">
						<?php foreach ( $params['options'] as $val => $image ) :
							if ( (string) $params['value'] === (string) $val ) : ?>
								<span class="rb-checkbox is-active">
                            <?php if ( is_array( $image ) ):
	                            $image = wp_parse_args( $image, [
		                            'img'   => '#',
		                            'title' => '',
	                            ] ); ?>
	                            <img src="<?php echo esc_url( $image['img'] ); ?>" alt=""/>
	                            <span class="select-title"><?php echo esc_html( $image['title'] ); ?></span>
                            <?php else : ?>
	                            <img src="<?php echo esc_url( $image ); ?>" alt=""/>
                            <?php endif; ?>
                                <input checked="checked" type="radio" class="rb-meta-image" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" value="<?php echo esc_attr( $val ); ?>">
                            </span>
							<?php else : ?>
								<span class="rb-checkbox">
					<?php if ( is_array( $image ) ):
						$image = wp_parse_args( $image, [
							'img'   => '#',
							'title' => '',
						] ); ?>
						<img src="<?php echo esc_url( $image['img'] ); ?>" alt=""/>
						<span class="select-title"><?php echo esc_html( $image['title'] ); ?></span>
					<?php else : ?>
						<img src="<?php echo esc_url( $image ); ?>" alt=""/>
					<?php endif; ?>
						<input type="radio" class="rb-meta-image" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" value="<?php echo esc_attr( $val ); ?>">
				</span>
							<?php endif;
						endforeach; ?>
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_images( $params ) {

			$defaults = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'info'    => '',
				'default' => '',
				'class'   => 'rb-images',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $defaults['default'];
			} ?>
			<div class="rb-meta rb-gallery <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<span class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></span>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class=" rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="rb-gallery-content">
						<div class="meta-preview">
							<?php if ( ! empty( $params['value'] ) ) :
								$data_ids = explode( ',', $params['value'] );
								foreach ( $data_ids as $attachment_id ) :
									$img = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
									if ( isset( $img[0] ) ) {
										echo '<span class="thumbnail"><img src="' . esc_url( $img[0] ) . '" /></span>';
									}
								endforeach;
							endif; ?>
						</div>
						<input class="rb-edit-gallery button rb-meta-button" type="button" value="+ Add/Edit Gallery"/>
						<input class="rb-clear-gallery button rb-meta-button" type="button" value="Clear"/>
						<input type="hidden" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" class="rb-value-gallery" value="<?php echo esc_attr( $params['value'] ); ?>">
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 * input textarea
		 */
		function input_textarea( $params ) {

			$defaults = [
				'id'          => '',
				'name'        => '',
				'desc'        => '',
				'default'     => '',
				'class'       => '',
				'rows'        => 2,
				'placeholder' => '',
			];
			$params   = wp_parse_args( $params, $defaults );

			if ( ! isset( $params['value'] ) ) {
				if ( ! empty( $params['default'] ) ) {
					$params['value'] = $params['default'];
				} else {
					$params['value'] = '';
				}
			} ?>
			<div class="rb-meta rb-textarea <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<span class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></span>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class="rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<textarea rows="<?php echo esc_attr( $params['rows'] ); ?>" cols="50" placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>" class="rb-meta-textarea" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" id="<?php echo esc_attr( $params['id'] ); ?>"><?php echo esc_html( $params['value'] ); ?></textarea>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_category_select( $params ) {

			$defaults = [
				'id'       => '',
				'name'     => '',
				'desc'     => '',
				'default'  => '',
				'taxonomy' => 'category',
				'class'    => '',
				'empty'    => 'None',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			}

			$categories_data = [];

			$categories = get_categories( [
				'hide_empty' => 0,
				'type'       => 'post',
			] );

			$array_walker = new Rb_Category_Select_Walker;
			$array_walker->walk( $categories, 4 );
			$buffer = $array_walker->cat_array;
			foreach ( $buffer as $name => $id ) {
				$categories_data[ $name ] = $id;
			}
			$params['options'] = $categories_data;
			?>
			<div class="rb-meta rb-select rb-category-select <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<label for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></label>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class="rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="rb-tax-select-parent">
						<select class="rb-meta-select rb-tax-select" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" id="<?php echo esc_attr( $params['id'] ); ?>"/>
						<option value="0" <?php if ( empty( $params['value'] ) ) {
							echo 'selected';
						} ?>>-- <?php echo esc_html( $params['empty'] ); ?> --
						</option>
						<?php foreach ( $params['options'] as $name => $id ) :
							if ( (string) $params['value'] === (string) $id ) : ?>
								<option selected value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
							<?php else : ?>
								<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
							<?php endif; ?><?php endforeach; ?>
						</select>
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		function input_tag_select( $params ) {

			$defaults = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'default' => '',
				'class'   => '',
				'empty'   => 'None',
			];

			$params = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			} elseif ( is_numeric( $params['value'] ) ) {
				$tag = get_term( (int) $params['value'], 'post_tag' );
				if ( ! empty( $tag ) && ! is_wp_error( $tag ) ) {
					$params['value'] = $tag->name;
				}
			} ?>
			<div class="rb-meta rb-select rb-tag-select rb-category-select <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<label for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></label>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class="rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<input data-wp-taxonomy="post_tag" type="text" class="rb-meta-text rb-tags-suggest" value="<?php echo $params['value']; ?>" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" id="<?php echo esc_attr( $params['id'] ); ?>"/>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_file( $params ) {

			$defaults = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'info'    => '',
				'default' => '',
				'class'   => 'rb-file',
			];
			$params   = wp_parse_args( $params, $defaults );
			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			} ?>
			<div class="rb-meta rb-file <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<span for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></span>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class=" rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="rb-file-content">
						<div class="meta-preview">
							<?php if ( ! empty( $params['value'] ) ) :
								$file_name = get_the_title( $params['value'] );
								$url       = wp_get_attachment_url( $params['value'] );
								echo '<a class="thumbnail file" href="' . $url . '">';
								if ( ! wp_attachment_is_image( $params['value'] ) ) {
									$src = wp_mime_type_icon( $params['value'] );
									echo '<img class="icon" src="' . esc_html( $src ) . '"/>';
								} else {
									echo '<img class="image" src="' . esc_html( $url ) . '"/>';
								}
								echo '<span class=" file-name">' . esc_attr( $file_name ) . '</span></a>';
							endif; ?>
						</div>
						<input class="rb-edit-file button rb-meta-button" type="button" value="+Add Media"/>
						<input class="rb-clear-file button rb-meta-button" type="button" value="Clear"/>
						<input type="hidden" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>]" class="rb-value-file" value="<?php echo esc_attr( $params['value'] ); ?>">
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_datetime( $params ) {

			$defaults = [
				'id'          => '',
				'name'        => '',
				'desc'        => '',
				'default'     => '',
				'key'         => '',
				'kvd'         => '',
				'class'       => 'rb-date',
				'placeholder' => 'mm/dd/yyyy',
			];
			$params   = wp_parse_args( $params, $defaults );

			if ( empty( $params['value'] ) ) {
				$params['value'] = $params['default'];
			} ?>
			<div class="rb-meta rb-date <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<label for="<?php echo esc_attr( $params['id'] ); ?>" class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></label>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class=" rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="rb-date-content">
						<input type="hidden" class="rb-meta-type" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][type]" value="datetime">
						<?php if ( ! empty( $params['key'] ) ) : ?>
							<input type="hidden" class="rb-meta-key" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][key]" value="<?php echo esc_attr( $params['key'] ); ?>">
						<?php endif;
						if ( ! empty( $params['kvd'] ) ) : ?>
							<input type="hidden" class="rb-meta-kvd" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][kvd]" value="<?php echo esc_attr( $params['kvd'] ); ?>">
						<?php endif; ?>
						<input type="text" autocomplete="off" class="rb-meta-date" placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][date]" id="<?php echo esc_attr( $params['id'] ) . '_date'; ?>" value="<?php if ( ! empty( $params['value'] ) ) {
							echo date( 'm\/d\/Y', (float) ( $params['value'] ) );
						} ?>"/>
						<input type="text" class="rb-meta-time" autocomplete="off" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][time]" id="<?php echo esc_attr( $params['id'] ) . '_time'; ?>" value="<?php echo date( 'H:i', (float) ( $params['value'] ) ); ?>"/>
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $params
		 */
		function input_group( $params ) {

			$last_index = 0;
			$defaults   = [
				'id'      => '',
				'name'    => '',
				'desc'    => '',
				'info'    => '',
				'default' => '',
				'fields'  => [],
				'button'  => '',
				'class'   => '',
				'value'   => [],
			];

			$params = wp_parse_args( $params, $defaults );
			if ( ! isset( $params['value']['placebo'] ) ) {
				$params['value']['placebo'] = [];
			}
			?>
			<div class="rb-meta rb-group <?php echo esc_attr( $params['class'] ); ?>">
				<div class="rb-meta-title">
					<span class="rb-meta-label"><?php echo esc_html( $params['name'] ); ?></span>
					<?php if ( ! empty( $params['desc'] ) ) : ?>
						<p class=" rb-meta-desc"><?php echo esc_html( $params['desc'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="rb-meta-content">
					<div class="group-holder">
						<div class="rb-group-content">
							<?php if ( is_array( $params['value'] ) && count( $params['value'] ) ) :
								foreach ( $params['value'] as $index => $item ) :

									$class_name = 'group-item';
									if ( 'placebo' === ( string ) $index ) {
										$class_name .= ' group-placebo is-hidden';
									}
									?>
									<div class="<?php echo esc_attr( $class_name ); ?>">
										<?php foreach ( $params['fields'] as $field ) :
											$field = wp_parse_args( $field, [
												'placeholder' => '',
												'id'          => '',
												'name'        => '',
												'default'     => '',
											] );
											if ( ! isset( $item[ $field['id'] ] ) ) {
												$item[ $field['id'] ] = $field['default'];
											}
											if ( (string) $index === 'placebo' ) {
												$item[ $field['id'] ] = 0;
											}
											?>
											<div class="item">
												<span class="group-item-title"><?php echo esc_html( $field['name'] ); ?></span>
												<input type="text" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" name="rb_meta[<?php echo esc_attr( $params['id'] ); ?>][<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $field['id'] ); ?>]" value="<?php echo esc_html( $item[ $field['id'] ] ); ?>"/>
											</div>
										<?php endforeach; ?>
										<?php if ( (string) $index !== 'placebo' ) :
											$last_index = absint( $index ) + 1; ?>
											<a href="#" class="rb-group-delete"><?php echo esc_html__( 'Delete', 'foxiz-core' ); ?></a>
										<?php endif; ?>
									</div>
								<?php
								endforeach;
							endif; ?>
						</div>
						<div class="group-item default-group-item is-hidden" data-index="<?php echo esc_attr( $last_index ); ?>">
							<?php foreach ( $params['fields'] as $field ) :
								$field = wp_parse_args( $field, [
									'placeholder' => '',
									'id'          => '',
									'name'        => '',
									'default'     => '',
								] ); ?>
								<div class="item">
									<span class="group-item-title"><?php echo esc_html( $field['name'] ); ?></span>
									<input type="text" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" data-group-id="<?php echo esc_attr( $params['id'] ); ?>" data-id="<?php echo esc_attr( $field['id'] ); ?>" data-value="<?php echo esc_attr( $field['default'] ); ?>"/>
								</div>
							<?php endforeach; ?>
							<a href="#" class="rb-group-delete"><?php echo esc_html__( 'Delete', 'foxiz-core' ); ?></a>
						</div>
						<a href="#" class="rb-group-trigger"><?php echo esc_html( $params['button'] ); ?></a>
					</div>
					<?php if ( ! empty( $params['info'] ) ) : ?>
						<span class="rb-meta-info"><?php echo esc_html( $params['info'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		function input_html_template( $param ) {

			if ( ! empty( $param['callback'] ) && function_exists( $param['callback'] ) ) {
				call_user_func( $param['callback'] );
			}
		}

		function add_single_meta( $params ) { ?>
			<input type="hidden" name="rb_meta[_single_metas][]" value="<?php echo $params['id'] ?>"/>
		<?php }

		private function regs() {

			$option = get_option( '_lic' . FOXIZ_LICENSE_ID );

			return ! empty( $option['title'] );
		}

	}
}

/** init */
RB_META::get_instance();