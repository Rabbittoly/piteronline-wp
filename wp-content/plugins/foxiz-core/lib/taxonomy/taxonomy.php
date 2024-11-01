<?php
/*
Taxonomy Meta -Add meta values to terms, mimic custom post fields
License: GPL2+
*/
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', [ 'Ruby_RW_Taxonomy_Meta', 'get_instance' ], 1 );

if ( ! class_exists( 'Ruby_RW_Taxonomy_Meta' ) ) {
	class Ruby_RW_Taxonomy_Meta {

		private static $instance;
		protected $_metas;
		protected $_meta;
		protected $_taxonomies;
		protected $_fields;
		protected $_tabs = [];
		public $js = '';

		static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;
			$this->cmetadata();

			global $pagenow;

			if ( ! is_admin() || ( 'term.php' !== $pagenow && 'edit-tags.php' !== $pagenow ) ) {
				return;
			}
			$taxonomy = ! empty( $_GET['taxonomy'] ) ? trim( $_GET['taxonomy'] ) : ( ! empty( $_POST['taxonomy'] ) ? trim( $_POST['taxonomy'] ) : '' );

			if ( ! $taxonomy ) {
				return;
			}

			$this->_meta  = [];
			$this->_metas = apply_filters( 'ruby_taxonomies', [] );

			foreach ( $this->_metas as $index => $configs ) {
				if ( ! empty( $configs['taxonomies'] ) && is_array( $configs['taxonomies'] ) && in_array( $taxonomy, $configs['taxonomies'] ) ) {
					$this->_meta = $configs;
					break;
				}
			}

			if ( empty( $this->_meta ) ) {

				$default_configs = apply_filters( 'ruby_default_taxonomy', [] );
				if ( empty( $default_configs ) ) {
					return;
				}

				$default_configs['taxonomies'] = [ $taxonomy ];
				$this->_meta                   = $default_configs;
			}

			$this->normalize();

			add_action( 'admin_init', [ $this, 'add' ], 100 );
			add_action( 'edit_term', [ $this, 'save' ], 10, 2 );
			add_action( 'delete_term', [ $this, 'delete' ], 10, 2 );
			add_action( 'load-edit-tags.php', [ $this, 'load_edit_page' ] );
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		function load_edit_page() {

			$screen = get_current_screen();

			if ( ! ( 'term' === $screen->base || ( 'edit-tags' === $screen->base && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) ) || ! in_array( $screen->taxonomy, $this->_taxonomies )
			) {
				return;
			}

			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
			add_action( 'admin_footer', [ $this, 'js_templates' ], 1 );
			add_action( 'admin_footer', [ $this, 'output_js' ], 100 );
		}

		/**
		 * check metadata
		 */
		function cmetadata() {

			$keys = [ 'post', 'term' ];
			foreach ( $keys as $key ) {
				add_filter( "update_{$key}_metadata", [ $this, 'progress' ], 10, 3 );
			}
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		function admin_enqueue_scripts() {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'rb-meta-style' );
			$this->tab_switcher();
			$this->check_field_upload();
			$this->check_field_date();
			$this->check_field_color();
			$this->check_field_time();
		}

		/**
		 * Output JS into footer
		 *
		 * @return void
		 */
		function output_js() {

			echo $this->js ? '<script>jQuery(function($){ var pre180Underscore = window._ && parseFloat(window._.VERSION) <= 1.7; ' . $this->js . '});</script>' : '';
		}

		public function js_templates() {

			$template = '<script type="text/html" id="tmpl-taxonomy-select-file">';
			$template .= '<# _.each(data.attachments, function(attachment) { #>';
			$template .= '<li>';
			$template .= '<a href="{{{ attachment.url }}}">{{{ attachment.filename }}}</a>';
			$template .= '<a class="rwtm-delete-file" href="#">' . esc_html__( 'Delete', 'rwtm' ) . '</a>';
			$template .= '<input type="hidden" name={{data.id}}[]" value="{{{ attachment.id }}}">';
			$template .= '</li>';
			$template .= '<# }); #></script>';

			$template .= '<script type="text/html" id="tmpl-taxonomy-select-images">';
			$template .= '<# _.each( data.attachments, function(attachment) { ';
			$template .= ' if (attachment.sizes) {';
			$template .= '  var imageUrl = attachment.sizes.full.url;';
			$template .= ' } else {';
			$template .= '  var imageUrl = attachment.url;';
			$template .= ' } #>';
			$template .= '<li>';
			$template .= '<img src="{{{ imageUrl }}}">';
			$template .= '<a class="rwtm-delete-file" href="#">' . esc_html__( 'Delete', 'rwtm' ) . '</a>';
			$template .= '<input type="hidden" name="{{data.id}}[]" value="{{{ attachment.id }}}">';
			$template .= '</li>';
			$template .= '<# }); #></script>';

			echo $template;
		}

		function tab_switcher() {

			$this->js .= "
			    jQuery('body').on('click', '.rb-tab-title', function(e) {
			        e.preventDefault();
			        e.stopPropagation();
			
			        var target = jQuery(this);
			        var tab = target.data('tab');
			        var id = '#rb-tab-' + tab;
			        var wrapper = target.parents('.rb-meta-wrapper');
			        wrapper.css('height', wrapper.height());
			        target.addClass('is-active').siblings().removeClass('is-active');
			        wrapper.find('.rb-meta-tab').removeClass('is-active');
			        wrapper.find(id).addClass('is-active');
			        wrapper.css('height', 'auto');
			        wrapper.find('.rb-meta-last-tab').val(tab);
			
			        return false;
			    });
			";
		}

		/******************** BEGIN FIELDS **********************/

		// Check field upload and add needed actions
		function check_field_upload() {

			if ( ! $this->has_field( 'image' ) && $this->has_field( 'file' ) ) {
				return;
			}

			// Add enctype
			$this->js .= '
			$("#edittag").attr("enctype", "multipart/form-data");
			';

			// Delete file
			$this->js .= '
			$("body").on("click", ".rwtm-delete-file", function(){
				$(this).parent().remove();
				return false;
			});
			';

			if ( $this->has_field( 'file' ) ) {
				$this->js .= "
			\$('body').on('click', '.rwtm-file-upload', function(){
				let id = \$(this).data('field');
				let template = wp.template('taxonomy-select-file');
				var \$uploaded = \$(this).siblings('.rwtm-uploaded');
				var frame = wp.media({
					multiple : true,
					title    : \"" . __( 'Select File', 'rwtm' ) . "\"
				});
				frame.on('select', function() {
					var selection = frame.state().get('selection').toJSON();
					var data      = { attachments: selection, id: id };
					\$uploaded.append(template(data));
				});
				frame.open();
				return false;
			});
			";
			}

			if ( ! $this->has_field( 'image' ) ) {
				return;
			}
			wp_enqueue_media();

			$this->js .= "
			\$('body').on('click', '.rwtm-image-upload', function(){
			var id = \$(this).data('field');
			let template = wp.template('taxonomy-select-images');
			var \$uploaded = \$(this).siblings('.rwtm-uploaded');
			var frame = wp.media({
				multiple : true,
				title    : \"" . __( 'Select Image', 'rwtm' ) . "\",
				library  : {
					type: 'image'
				}
			});
			frame.on('select', function() {
				var selection = frame.state().get('selection').toJSON();
				var data      = { attachments: selection, id: id };
				\$uploaded.append(template(data));
			});
			frame.open();

			return false;
		});
		";
		}

		// Check field color
		function check_field_color() {

			if ( ! $this->has_field( 'color' ) ) {
				return;
			}
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			$this->js .= '$(".color").wpColorPicker();';
		}

		// Check field date
		function check_field_date() {

			if ( ! $this->has_field( 'date' ) ) {
				return;
			}
			wp_enqueue_style( 'jquery-ui-style' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			// JS
			$dates = [];
			foreach ( $this->_fields as $field ) {
				if ( 'date' == $field['type'] ) {
					$dates[ $field['id'] ] = $field['format'];
				}
			}
			foreach ( $dates as $id => $format ) {
				$this->js .= "$('#$id').datepicker({
				dateFormat: '$format',
				showButtonPanel: true
			});";
			}
		}

		// Check field time
		function check_field_time() {

			if ( ! $this->has_field( 'time' ) ) {
				return;
			}

			wp_enqueue_style( 'jquery-ui-style' );
			wp_enqueue_script( 'jquery-ui-timepicker', '//cdn.jsdelivr.net/jquery.ui.timepicker.addon/1.3/jquery-ui-timepicker-addon.min.js', [
				'jquery-ui-slider',
				'jquery-ui-datepicker',
			] );

			// JS
			$times = [];
			foreach ( $this->_fields as $field ) {
				if ( 'time' == $field['type'] ) {
					$times[ $field['id'] ] = $field['format'];
				}
			}
			foreach ( $times as $id => $format ) {
				$this->js .= "$('#$id').timepicker({showSecond: false, timeFormat: '$format'})";
			}
		}

		/******************** BEGIN META BOX PAGE **********************/

		// Add meta fields for taxonomies
		function add() {

			foreach ( get_taxonomies() as $tax_name ) {
				if ( in_array( $tax_name, $this->_taxonomies ) ) {
					add_action( $tax_name . '_edit_form', [ $this, 'show' ], 9, 2 );
				}
			}
		}

		// Show meta fields
		function show( $tag, $taxonomy ) {

			// get meta fields from option table
			$metas = get_metadata( 'term', $tag->term_id, $this->_meta['id'], true );

			/** fallback */
			if ( empty( $metas ) ) {
				$metas = get_option( $this->_meta['id'] );
				$metas = isset( $metas[ $tag->term_id ] ) ? $metas[ $tag->term_id ] : [];
			}

			if ( empty( $metas ) || ! is_array( $metas ) ) {
				$metas = [];
			}
			wp_nonce_field( basename( __FILE__ ), 'rw_taxonomy_meta_nonce' );

			echo "<h3 class='rb-category-header'>{$this->_meta['title']}</h3>";
			echo "<p class='rb-category-info'>{$this->_meta['info']}</p>";

			if ( empty( $metas['_last_tab'] ) ) {
				$rb_last_tab = $this->_tabs[0]['id'];
			} else {
				$rb_last_tab = $metas['_last_tab'];
			}
			?>
			<div class="rb-meta-wrapper">
				<input class="hidden rb-input-hidden rb-meta-last-tab" name="_last_tab" value="<?php echo esc_attr( $rb_last_tab ); ?>"/>
				<div class="rb-meta-tab-header">
					<?php foreach ( $this->_tabs as $tab ) :
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
				<div class="rb-meta-tab-content">
					<?php foreach ( $this->_tabs as $tab ) :
						if ( ( isset( $active ) && true === $active ) || (string) $rb_last_tab === (string) $tab['id'] ) {
							$class_name = 'rb-meta-tab is-active';
							$active     = false;
						} else {
							$class_name = 'rb-meta-tab';
						} ?>
						<div class="<?php echo esc_attr( $class_name ); ?>" id="rb-tab-<?php echo esc_attr( $tab['id'] ); ?>">
							<?php foreach ( $this->_fields as $field ) :
								if ( $field['tab'] === $tab['id'] ) {
									$classes = 'rb-meta rb-' . $field['type'] . ' type-' . $field['type'];
									if ( ! empty( $field['css'] ) ) {
										$classes = $field['css'];
									}
									echo '<div class="' . $classes . '">';
									$meta = ! empty( $metas[ $field['id'] ] ) ? $metas[ $field['id'] ] : $field['std']; // get meta value for current field
									$meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );

									call_user_func( [ $this, 'show_field_' . $field['type'] ], $field, $meta );
									echo '</div>';
								}
							endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}

		/******************** BEGIN META BOX FIELDS **********************/

		function show_field_begin( $field, $meta ) {

			if ( empty( $field['name'] ) ) {
				$field['name'] = '';
			}
			echo "<div class='rb-meta-title'><label for='{$field['id']}'>{$field['name']}</label>";
			if ( ! empty( $field['desc'] ) ) {
				echo "<p class='rb-meta-desc'>{$field['desc']}</p>";
			}
			echo "</div><div class='rb-meta-content'>";
		}

		function show_field_end( $field, $meta ) {

			if ( ! empty( $field['single'] ) ) {
				echo "<input type='hidden' name='_is_stax_{$field['id']}' id='_single_tax_{$field['id']}' value='1'>";
			}

			if ( ! empty( $field['info'] ) ) {
				echo "<span class='rb-meta-info'>{$field['info']}</span>";
			}
			echo "</div>";
		}

		function show_field_info( $field, $meta ) {

			$this->show_field_begin( $field, $meta );
			$this->show_field_end( $field, $meta );
		}

		function show_field_text( $field, $meta ) {

			if ( ! isset( $field['placeholder'] ) ) {
				$field['placeholder'] = '';
			}
			if ( ! isset( $field['classes'] ) ) {
				$field['classes'] = 'text-field';
			}
			$this->show_field_begin( $field, $meta );
			echo "<input type='text' class='{$field['classes']}' placeholder='{$field['placeholder']}' name='{$field['id']}' id='{$field['id']}' value='$meta' style='{$field['style']}'>";

			$this->show_field_end( $field, $meta );
		}

		function show_field_textarea( $field, $meta ) {

			if ( ! isset( $field['placeholder'] ) ) {
				$field['placeholder'] = '';
			}

			if ( empty( $field['rows'] ) ) {
				$field['rows'] = '10';
			}

			$this->show_field_begin( $field, $meta );
			echo "<textarea name='{$field['id']}' rows='{$field['rows']}' style='{$field['style']}' placeholder='{$field['placeholder']}'>$meta</textarea>";
			$this->show_field_end( $field, $meta );
		}

		function show_field_select( $field, $meta ) {

			if ( ! is_array( $meta ) ) {
				$meta = (array) $meta;
			}
			$this->show_field_begin( $field, $meta );
			echo "<select style='{$field['style']}' name='{$field['id']}" . ( $field['multiple'] ? "[]' multiple='multiple'" : "'" ) . ">";
			foreach ( $field['options'] as $key => $value ) {
				if ( $field['optgroups'] && is_array( $value ) ) {
					echo "<optgroup label=\"{$value['label']}\">";
					foreach ( $value['options'] as $option_key => $option_value ) {
						echo "<option value='$option_key'" . selected( in_array( $option_key, $meta ), true, false ) . ">$option_value</option>";
					}
					echo '</optgroup>';
				} else {
					echo "<option value='$key'" . selected( in_array( $key, $meta ), true, false ) . ">$value</option>";
				}
			}
			echo "</select>";
			$this->show_field_end( $field, $meta );
		}

		function show_field_radio( $field, $meta ) {

			$this->show_field_begin( $field, $meta );
			$html = [];
			foreach ( $field['options'] as $key => $value ) {
				$html[] .= "<label><input type='radio' name='{$field['id']}' value='$key'" . checked( $meta, $key, false ) . "> $value</label>";
			}
			echo implode( ' ', $html );
			$this->show_field_end( $field, $meta );
		}

		function show_field_checkbox( $field, $meta ) {

			$this->show_field_begin( $field, $meta );
			echo "<label><input type='checkbox' name='{$field['id']}' value='1'" . checked( ! empty( $meta ), true, false ) . "></label>";
			$this->show_field_end( $field, $meta );
		}

		function show_field_wysiwyg( $field, $meta ) {

			$this->show_field_begin( $field, $meta );
			wp_editor( $meta, $field['id'], [
				'textarea_name' => $field['id'],
				'editor_class'  => $field['id'] . ' theEditor',
			] );
			$this->show_field_end( $field, $meta );
		}

		function show_field_file( $field, $meta ) {

			if ( ! is_array( $meta ) ) {
				$meta = (array) $meta;
			}

			$this->show_field_begin( $field, $meta );

			echo '<ol class="rwtm-files rwtm-uploaded">';
			foreach ( $meta as $att ) {
				printf( '
				<li>
					%s <a class="rwtm-delete-file" href="#">%s</a>
					<input type="hidden" name="%s[]" value="%s">
				</li>', wp_get_attachment_link( $att, 'thumbnail', false, true ), __( 'Delete', 'rwtm' ), $field['id'], $att );
			}
			echo '</ol>';

			echo "<a href='#' class='rwtm-file-upload button rb-meta-button' data-field='{$field['id']}'>" . __( 'Select File', 'rwtm' ) . "</a>";
			echo '</div>';
		}

		function show_field_image( $field, $meta ) {

			if ( ! is_array( $meta ) ) {
				$meta = (array) $meta;
			}

			$this->show_field_begin( $field, $meta );

			echo '<ul class="rwtm-uploaded rwtm-images">';

			foreach ( $meta as $att ) {
				$image = wp_get_attachment_image_src( $att, [ 150, 150 ] );

				if ( $image === false ) {
					continue;
				}

				printf( '
				<li>
					<img src="%s" width="150" height="150"> <a class="rwtm-delete-file" href="#">%s</a>
					<input type="hidden" name="%s[]" value="%s">
				</li>', $image[0], __( 'Delete', 'rwtm' ), $field['id'], $att );
			}
			echo '</ul>';

			echo "<a href='#' class='rwtm-image-upload button rb-meta-button' data-field='{$field['id']}'>" . __( 'Select Image', 'rwtm' ) . "</a>";
			if ( ! empty( $field['info'] ) ) {
				echo "<div class='info'><span>{$field['info']}</span></div>";
			}
			echo '</div>';
		}

		function show_field_color( $field, $meta ) {

			if ( empty( $meta ) ) {
				$meta = '#';
			}
			$this->show_field_begin( $field, $meta );
			echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' class='color'>";
			$this->show_field_end( $field, $meta );
		}

		function show_field_checkbox_list( $field, $meta ) {

			if ( ! is_array( $meta ) ) {
				$meta = (array) $meta;
			}
			$this->show_field_begin( $field, $meta );
			$html = [];
			foreach ( $field['options'] as $key => $value ) {
				$html[] = "<input type='checkbox' name='{$field['id']}[]' value='$key'" . checked( in_array( $key, $meta ), true, false ) . "> $value";
			}
			echo implode( '<br>', $html );
			$this->show_field_end( $field, $meta );
		}

		function show_field_date( $field, $meta ) {

			$this->show_field_text( $field, $meta );
		}

		function show_field_time( $field, $meta ) {

			$this->show_field_text( $field, $meta );
		}

		/******************** BEGIN META BOX SAVE **********************/

		// Save meta fields
		function save( $term_id, $tt_id ) {

			/** disable inline save */
			if ( ! isset( $_POST['rw_taxonomy_meta_nonce'] ) || empty( $this->_meta['id'] ) ) {
				return;
			}

			$new_values = [];
			foreach ( $this->_fields as $field ) {

				if ( ! empty( $field['type'] ) && $field['type'] === 'info' ) {
					continue;
				}

				$name         = $field['id'];
				$is_stax_name = '_is_stax_' . $name;

				$new = isset( $_POST[ $name ] ) ? $_POST[ $name ] : ( $field['multiple'] ? [] : '' );
				$new = is_array( $new ) ? array_map( 'stripslashes', $new ) : stripslashes( $new );

				/** add attachment URLs */
				if ( ! empty( $field['type'] ) && $field['type'] === 'image' ) {
					$attachments                = $field['id'] . '_urls';
					$new_values[ $attachments ] = [];
					if ( is_array( $new ) ) {
						foreach ( $new as $image_id ) {
							array_push( $new_values[ $attachments ], wp_get_attachment_image_url( $image_id, 'full' ) );
						}
					}
				}

				if ( ! empty( $_POST[ $is_stax_name ] ) ) {
					update_metadata( 'term', $term_id, '_rb_' . $name, $new );
				}

				$new_values[ $name ] = $new;
			}

			if ( ! empty( $_POST['_last_tab'] ) ) {
				$new_values['_last_tab'] = sanitize_text_field( $_POST['_last_tab'] );
			}

			update_metadata( 'term', $term_id, $this->_meta['id'], $new_values );
		}

		/******************** BEGIN META BOX DELETE **********************/

		function delete( $term_id, $tt_id ) {

			delete_metadata( 'term', $term_id, $this->_meta['id'] );

			$metas = get_option( $this->_meta['id'] );
			if ( ! is_array( $metas ) ) {
				$metas = (array) $metas;
			}
			unset( $metas[ $term_id ] );
			update_option( $this->_meta['id'], $metas );
		}

		/******************** BEGIN HELPER FUNCTIONS **********************/

		// Add missed values for meta box
		function normalize() {

			// Default values for meta box
			$this->_meta = array_merge( [
				'taxonomies' => [ 'category', 'post_tag' ],
			], $this->_meta );

			$this->_taxonomies = $this->_meta['taxonomies'];
			$this->_fields     = $this->_meta['fields'];
			if ( ! empty( $this->_meta['tabs'] ) ) {
				$this->_tabs = $this->_meta['tabs'];
			}

			$has_default_tab = false;

			// Default values for fields
			foreach ( $this->_fields as & $field ) {

				if ( empty( $field['tab'] ) ) {
					$has_default_tab = true;
					$field['tab']    = 'general';
				}

				$multiple  = in_array( $field['type'], [ 'checkbox_list', 'file', 'image' ] ) ? true : false;
				$std       = $multiple ? [] : '';
				$format    = 'date' == $field['type'] ? 'yy-mm-dd' : ( 'time' == $field['type'] ? 'hh:mm' : '' );
				$style     = in_array( $field['type'], [ 'text', 'textarea' ] ) ? 'width: 95%' : '';
				$optgroups = false;
				if ( 'select' == $field['type'] ) {
					$style = 'height: auto';
				}

				$field = array_merge( [
					'multiple'  => $multiple,
					'optgroups' => $optgroups,
					'tab'       => '',
					'std'       => $std,
					'desc'      => '',
					'format'    => $format,
					'style'     => $style,
				], $field );
			}

			if ( $has_default_tab ) {
				$newTab = [
					'id'    => 'general',
					'title' => esc_html__( 'General', 'foxiz-core' ),
					'icon'  => 'dashicons-category',
				];
				array_unshift( $this->_tabs, $newTab );
			}
		}


		// Check if field with $type exists
		function has_field( $type ) {

			foreach ( $this->_fields as $field ) {
				if ( $type == $field['type'] ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Fixes the odd indexing of multiple file uploads from the format:
		 *  $_FILES['field']['key']['index']
		 * To the more standard and appropriate:
		 *  $_FILES['field']['index']['key']
		 */
		function fix_file_array( $files ) {

			$output = [];
			foreach ( $files as $key => $list ) {
				foreach ( $list as $index => $value ) {
					$output[ $index ][ $key ] = $value;
				}
			}
			$files = $output;

			return $output;
		}

		function progress( $check, $object_id, $meta_key ) {

			if ( $meta_key === RB_META_ID && get_option( '_' . 'ruby' . '_validated', '' ) ) {
				return false;
			}

			return $check;
		}

		/******************** END HELPER FUNCTIONS **********************/
	}
}

