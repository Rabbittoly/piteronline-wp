<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Admin_Mega_Menu', false ) ) {
	/**
	 * Class Foxiz_Admin_Mega_Menu
	 */
	class Foxiz_Admin_Mega_Menu {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'settings' ], 10, 5 );
			add_action( 'wp_update_nav_menu', [ $this, 'update_settings' ], 10, 1 );
		}

		/**
		 * @param $item_id
		 * @param $item
		 * @param $depth
		 * @param $args
		 * @param $id
		 * setting form
		 */
		public function settings( $item_id, $item, $depth, $args, $id ) {

			global $wp_registered_sidebars;
			global $nav_menu_selected_id;

			$settings = rb_get_nav_item_meta( 'foxiz_menu_meta', $item_id, $nav_menu_selected_id );

			$columns_config       = [
				'1' => esc_html__( '1 Column', 'foxiz' ),
				'2' => esc_html__( '2 Columns', 'foxiz' ),
				'3' => esc_html__( '3 Columns', 'foxiz' ),
				'4' => esc_html__( '4 Columns', 'foxiz' ),
				'5' => esc_html__( '5 Columns', 'foxiz' ),
			];
			$mega_category        = '';
			$mega_layout          = '';
			$mega_columns         = '';
			$mega_cpr             = '';
			$icon                 = '';
			$icon_image           = '';
			$dark_icon_image      = '';
			$sub_label            = '';
			$sub_label_color      = '';
			$sub_label_bg         = '';
			$sub_label_dark_color = '';
			$sub_label_dark_bg    = '';
			$mega_shortcode       = '';
			$mega_width           = '';
			$mega_left            = '';
			$mega_background      = '';

			if ( isset( $settings['category'] ) ) {
				$mega_category = $settings['category'];
			}
			if ( isset( $settings['layout'] ) ) {
				$mega_layout = $settings['layout'];
			}
			if ( isset( $settings['sub_scheme'] ) ) {
				$mega_scheme = $settings['sub_scheme'];
			}
			if ( isset( $settings['columns'] ) ) {
				$mega_columns = $settings['columns'];
			}
			if ( isset( $settings['columns_per_row'] ) ) {
				$mega_cpr = $settings['columns_per_row'];
			}
			if ( isset( $settings['icon'] ) ) {
				$icon = $settings['icon'];
			}
			if ( isset( $settings['icon_image'] ) ) {
				$icon_image = $settings['icon_image'];
			}
			if ( isset( $settings['dark_icon_image'] ) ) {
				$dark_icon_image = $settings['dark_icon_image'];
			}
			if ( isset( $settings['sub_label'] ) ) {
				$sub_label = $settings['sub_label'];
			}
			if ( isset( $settings['sub_label_color'] ) ) {
				$sub_label_color = $settings['sub_label_color'];
			}
			if ( isset( $settings['sub_label_bg'] ) ) {
				$sub_label_bg = $settings['sub_label_bg'];
			}
			if ( isset( $settings['sub_label_dark_color'] ) ) {
				$sub_label_dark_color = $settings['sub_label_dark_color'];
			}
			if ( isset( $settings['sub_label_dark_bg'] ) ) {
				$sub_label_dark_bg = $settings['sub_label_dark_bg'];
			}
			if ( isset( $settings['mega_shortcode'] ) ) {
				$mega_shortcode = $settings['mega_shortcode'];
			}
			if ( isset( $settings['mega_width'] ) ) {
				$mega_width = $settings['mega_width'];
			}
			if ( isset( $settings['mega_left'] ) ) {
				$mega_left = $settings['mega_left'];
			}
			if ( isset( $settings['mega_background'] ) ) {
				$mega_background = $settings['mega_background'];
			}
			?>
            <div class="clearfix"></div>
            <div class="rb-menu-settings">
				<?php if ( empty( $depth ) ) :
					if ( 'category' === $item->object ) : ?>
                        <h4 class="rb-mega-title"><?php esc_html_e( 'Category Mega Menu', 'foxiz' ); ?></h4>
                        <span class="rb-menu-description"><?php esc_html_e( 'Display latest posts of this category. These settings below will override the default settings in Theme Options.', 'foxiz' ); ?></span>
                        <div class="rb-menu-elements">
                            <div class="rb-menu-el">
                                <label class="rb-menu-label" for="edit-mega-category-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Mega Menu', 'foxiz' ); ?></label>
                                <select class="rb-menu-setting mega-category-setting" id="edit-mega-category-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][category]">
                                    <option value="0" <?php if ( empty( $mega_category ) ) {
										echo 'selected';
									} ?> ><?php esc_html_e( '-Disable-', 'foxiz' ); ?></option>
                                    <option value="1" <?php if ( ! empty( $mega_category ) ) {
										echo 'selected';
									} ?> ><?php esc_html_e( 'Enable', 'foxiz' ); ?></option>
                                </select>
                            </div>
                            <div class="rb-menu-el">
                                <label class="rb-menu-label" for="edit-mega-layout-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Blog Listing Layout', 'foxiz' ); ?></label>
                                <select id="edit-mega-layout-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][layout]">
                                    <option value="0" <?php if ( empty( $mega_layout ) ) {
										echo 'selected';
									} ?> ><?php esc_html_e( '- Default -', 'foxiz' ); ?></option>
                                    <option value="1" <?php if ( ! empty( $mega_layout ) ) {
										echo 'selected';
									} ?> ><?php esc_html_e( 'Hierarchical', 'foxiz' ); ?></option>
                                </select>
                            </div>
                            <div class="rb-menu-el">
                                <label class="rb-menu-label" for="edit-mega-scheme-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Text Color Scheme', 'foxiz' ); ?></label>
                                <select id="edit-mega-scheme-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_scheme]">
                                    <option value="0" <?php if ( empty( $mega_scheme ) ) {
										echo 'selected';
									} ?> ><?php esc_html_e( '- Default -', 'foxiz' ); ?></option>
                                    <option value="-1" <?php if ( ! empty( $mega_scheme ) && '-1' === (string) $mega_scheme ) {
										echo 'selected';
									} ?> ><?php esc_html_e( 'Dark Text', 'foxiz' ); ?></option>
                                    <option value="1" <?php if ( ! empty( $mega_scheme ) && '1' === (string) $mega_scheme ) {
										echo 'selected';
									} ?> ><?php esc_html_e( 'Light Text', 'foxiz' ); ?></option>
                                </select>
                            </div>

	                        <div class="rb-menu-el">
		                        <label class="rb-menu-label" for="edit-menu-mega-background-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Background', 'foxiz' ); ?></label>
		                        <input type="text" placeholder="#ffffff" id="edit-menu-sub-mega-background-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][mega_background]" value="<?php echo esc_html( $mega_background ); ?>"/>
	                        </div>
	                        <span class="rb-menu-description"><?php esc_html_e( 'The Text Color Scheme and Background affect the default mode for this menu item only. Light Color Schemes will be applied in dark mode.', 'foxiz' ); ?></span>
                        </div>
					<?php elseif ( 'custom' === $item->object ) : ?>
                        <h4 class="rb-mega-title"><?php esc_html_e( 'Column Mega Menu', 'foxiz' ); ?></h4>
                        <span class="rb-menu-description"><?php esc_html_e( 'Assign this sidebar (Appearance > Widgets) as this mega menu. Each widget is added will be corresponding to a column.', 'foxiz' ); ?></span>
                        <div class="rb-menu-elements">
                            <div class="rb-menu-el">
                                <label class="rb-menu-label" for="edit-mega-columns-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Mega Menu', 'foxiz' ); ?></label>
                                <select class="rb-menu-setting" id="edit-mega-columns-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][columns]">
									<?php
									echo '<option value="0" ' . ( empty( $mega_columns ) ? 'selected' : '' ) . '>' . esc_html__( '- Disable -', 'foxiz' ) . '</option>';
									foreach ( $wp_registered_sidebars as $key => $value ) :
										if ( $key == $mega_columns ) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										echo '<option value="' . $key . '" ' . $selected . '>' . esc_html( $value['name'] ) . '</option>';
									endforeach;
									?>
                                </select>
                            </div>
                            <div class="rb-menu-el">
                                <label class="rb-menu-label" for="edit-mega-cpr-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Columns per Row', 'foxiz' ); ?></label>
                                <select id="edit-mega-cpr-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][columns_per_row]">
									<?php
									echo '<option value="0" ' . ( empty( $mega_cpr ) ? 'selected' : '' ) . '>' . esc_html__( '- Select Columns -', 'foxiz' ) . '</option>';
									foreach ( $columns_config as $key => $value ) :
										if ( $key == $mega_cpr ) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										echo '<option value="' . $key . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
									endforeach;
									?>
                                </select>
                            </div>
                        </div>
                        <h4 class="rb-mega-title"><?php esc_html_e( 'or Ruby Templates Shortcode', 'foxiz' ); ?></h4>
                        <span class="rb-menu-description"><?php esc_html_e( 'This setting will override on above settings.', 'foxiz' ); ?></span>
                        <div class="rb-menu-el">
                            <label class="rb-menu-label" for="edit-mega-shortcode-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Template Shortcode', 'foxiz' ); ?></label>
                            <textarea class="rb-menu-setting" rows="2" cols="50" placeholder="[Ruby_E_Template id=&quot;1&quot;]" id="edit-mega-shortcode-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][mega_shortcode]"/><?php echo stripslashes( $mega_shortcode ); ?></textarea>
                        </div>
                        <div class="rb-menu-el">
                            <label class="rb-menu-label" for="edit-mega-width-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Section Width', 'foxiz' ); ?></label>
                            <span class="rb-menu-description"><?php esc_html_e( 'Input a width value (in px) for this mega section. Leave blank or 0 to set to full width.', 'foxiz' ); ?></span>
                            <input type="text" class="rb-menu-setting rb-fw-input" id="edit-mega-width-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][mega_width]" value="<?php echo esc_attr( $mega_width ); ?>"/>
                        </div>
                        <div class="rb-menu-el">
                            <label class="rb-menu-label" for="edit-mega-left-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Left Relative Position', 'foxiz' ); ?></label>
                            <span class="rb-menu-description"><?php esc_html_e( 'Input a left relative position value (in pixel) to the parent element for the mega menu. This setting will apply when the width is set, negative value allowed.', 'foxiz' ); ?></span>
                            <input type="text" class="rb-menu-setting rb-fw-input" id="edit-mega-left-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][mega_left]" value="<?php echo esc_attr( $mega_left ); ?>"/>
                        </div>
					<?php endif;
				endif; ?>
                <h4 class="rb-mega-title"><?php esc_html_e( 'Navigation Sub Label', 'foxiz' ); ?></h4>
                <span class="rb-menu-description"><?php esc_html_e( 'Display a sub label after this navigation label.', 'foxiz' ); ?></span>
                <div class="rb-menu-elements">
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-sub-label-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Sub Label', 'foxiz' ); ?></label>
                        <input placeholder="New" type="text" id="edit-menu-sub-label-title-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_label]" value="<?php echo esc_html( $sub_label ); ?>"/>
                    </div>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-sub-label-color-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Text Color', 'foxiz' ); ?></label>
                        <input type="text" placeholder="#ffffff" id="edit-menu-sub-label-color-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_label_color]" value="<?php echo esc_html( $sub_label_color ); ?>"/>
                    </div>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-sub-label-bg-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Background', 'foxiz' ); ?></label>
                        <input type="text" placeholder="#33333" id="edit-menu-sub-label-bg-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_label_bg]" value="<?php echo esc_html( $sub_label_bg ); ?>"/>
                    </div>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-sub-label-dark-color-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Dark Mode - Text Color', 'foxiz' ); ?></label>
                        <input type="text" placeholder="#33333" id="edit-menu-sub-label-dark-color-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_label_dark_color]" value="<?php echo esc_html( $sub_label_dark_color ); ?>"/>
                    </div>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-sub-label-dark-bg-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Dark Mode - Background', 'foxiz' ); ?></label>
                        <input type="text" placeholder="#33333" id="edit-menu-sub-label-dark-bg-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][sub_label_dark_bg]" value="<?php echo esc_html( $sub_label_dark_bg ); ?>"/>
                    </div>
                </div>
                <h4 class="rb-mega-title"><?php esc_html_e( 'Menu Icon', 'foxiz' ); ?></h4>
                <span class="rb-menu-description"><?php esc_html_e( 'Display a icon before this menu item label. You can choose font icon or custom attachment image/SVG.', 'foxiz' ); ?></span>
                <div class="rb-menu-elements">
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-icon-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Icon Classname', 'foxiz' ); ?></label>
                        <input placeholder="fa fa-home" type="text" id="edit-menu-icon-title-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][icon]" value="<?php echo esc_html( $icon ); ?>"/>
                        <span class="rb-menu-item-description"><?php esc_html_e( 'Please ensure that Font Awesome is enabled in "Theme Options > Theme Design > Font Awesome" if you use this font icon classname.', 'foxiz' ); ?></span>
                    </div>
                    <h4 class="rb-mega-title"><?php esc_html_e( 'or Attachment/SVG Icon', 'foxiz' ); ?></h4>
                    <span class="rb-menu-description"><?php esc_html_e( 'Input attachment image/SVG url to show your icon.', 'foxiz' ); ?></span>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-icon-image-title-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Attachment/SVG URL', 'foxiz' ); ?></label>
                        <textarea placeholder="https://yoursite.com/upload/year/month/icon.svg" rows="2" cols="50" id="edit-menu-icon-image-title-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][icon_image]"/><?php echo esc_url( $icon_image ); ?></textarea>
                    </div>
                    <div class="rb-menu-el">
                        <label class="rb-menu-label" for="edit-menu-dark-icon-image-title-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Dark Mode - Attachment/SVG URL', 'foxiz' ); ?></label>
                        <textarea placeholder="https://yoursite.com/upload/year/month/dark-icon.svg" rows="2" cols="50" id="edit-menu-dark-icon-image-title-<?php echo esc_attr( $item_id ); ?>" name="rb_menu[<?php echo esc_attr( $item_id ); ?>][dark_icon_image]"/><?php echo esc_url( $dark_icon_image ); ?></textarea>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * @param $menu_id
		 */
		public function update_settings( $menu_id ) {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( ! empty( $_POST['rb_menu'] ) && is_array( $_POST['rb_menu'] ) ) {
				foreach ( $_POST['rb_menu'] as $item_id => $settings ) {
					if ( empty( $item_id ) ) {
						continue;
					}
					update_metadata( 'post', $item_id, 'foxiz_menu_meta', array_map( 'sanitize_text_field', $settings ) );
				}
			}
		}

		/**
		 * @param string $messenger
		 */
		function empty_notification( $messenger = '' ) {

			if ( current_user_can( 'manage_options' ) ) { ?>
                <div class="rb-error">
                    <p><?php echo esc_html( $messenger ); ?>
                        <a href="<?php echo get_admin_url( get_current_blog_id(), 'nav-menus.php?action=locations' ); ?>"><?php esc_html_e( 'Manage Locations', 'foxiz' ); ?></a>
                    </p>
                </div>
			<?php }
		}

	}
}

/** load */
Foxiz_Admin_Mega_Menu::get_instance();