<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', [ 'Foxiz_Register_Metaboxes', 'get_instance' ], 1 );

if ( ! class_exists( 'Foxiz_Register_Metaboxes', false ) ) {
	class Foxiz_Register_Metaboxes {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_filter( 'rb_meta_boxes', [ $this, 'register' ] );
		}

		function register( $metaboxes = [] ) {

			$metaboxes[] = $this->single_post_metaboxes();
			$metaboxes[] = $this->page_metaboxes();

			/** custom post type support */
			$metaboxes[] = $this->single_post_type_metaboxes();

			return $metaboxes;
		}

		public function page_metaboxes() {

			return [
				'id'         => 'foxiz_page_options',
				'title'      => esc_html__( 'Single Page Settings', 'foxiz' ),
				'desc'       => esc_html__( 'The settings below will apply to the single page and Elementor pages. To config for the blog index page. Navigate to Theme Options > Blog Index', 'foxiz' ),
				'context'    => 'normal',
				'post_types' => [ 'page' ],
				'tabs'       => [
					[
						'id'     => 'section-page-general',
						'title'  => esc_html__( 'General', 'foxiz' ),
						'desc'   => esc_html__( 'Navigate to "Theme Options > Blog Index" to set layout and styles if you assign this page as the blog (index) page.', 'foxiz' ),
						'icon'   => 'dashicons-align-full-width',
						'fields' => [
							[
								'id'      => 'page_header_style',
								'name'    => esc_html__( 'Page Header Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a top header layout for this page. This option is used for the single page (not Elementor page).', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_page_header_dropdown(),
								'default' => 'default',
							],
							[
								'id'      => 'page_breadcrumb',
								'name'    => esc_html__( 'Page Breadcrumb', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the breadcrumb on this page header.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'0'  => esc_html__( 'Use Global Setting', 'foxiz' ),
									'1'  => esc_html__( 'Enable', 'foxiz' ),
									'-1' => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => '0',
							],
							[
								'id'      => 'breadcrumb_pos',
								'name'    => esc_html__( 'Breadcrumb Position', 'foxiz' ),
								'desc'    => esc_html__( 'Choose the position for this page breadcrumb if enabled.', 'foxiz' ),
								'info'    => esc_html__( 'The right position is best for short titles.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'0'     => esc_html__( 'Use Global Setting', 'foxiz' ),
									'left'  => esc_html__( 'Left', 'foxiz' ),
									'right' => esc_html__( 'Right', 'foxiz' ),
								],
								'default' => '0',
							],
							[
								'id'      => 'page_header_width',
								'name'    => esc_html__( 'Limit Page Header Width', 'foxiz' ),
								'desc'    => esc_html__( 'Limit the max-width for the page header content, which includes the page title and featured image.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'small'   => esc_html__( 'Optimize Line Length', 'foxiz' ),
									'-1'      => esc_html__( 'Full Width', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'width_wo_sb',
								'name'    => esc_html__( 'Limit Page Content Width', 'foxiz' ),
								'desc'    => esc_html__( 'This setting will only apply if you choose to disable the sidebar on this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'small'   => esc_html__( 'Optimize Line Length', 'foxiz' ),
									'-1'      => esc_html__( 'Full Width', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-sidebar',
						'title'  => esc_html__( 'Sidebar Area', 'foxiz' ),
						'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Single Pages > Sidebar Area".', 'foxiz' ),
						'icon'   => 'dashicons-align-pull-right',
						'fields' => [
							[
								'id'      => 'sidebar_position',
								'name'    => esc_html__( 'Sidebar Position', 'foxiz' ),
								'desc'    => esc_html__( 'Select a position for this page sidebar.', 'foxiz' ),
								'class'   => 'sidebar-select',
								'type'    => 'image_select',
								'options' => foxiz_config_sidebar_position( true, true ),
								'default' => 'default',
							],
							[
								'id'      => 'sidebar_name',
								'name'    => esc_html__( 'Assign a Sidebar', 'foxiz' ),
								'desc'    => esc_html__( 'Assign a custom sidebar for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_sidebar_name(),
								'default' => 'default',
							],

						],
					],
					[
						'id'     => 'section-toc',
						'title'  => esc_html__( 'Table of Content', 'foxiz' ),
						'icon'   => 'dashicons-editor-ol',
						'fields' => [
							[
								'id'      => 'table_contents_page',
								'name'    => esc_html__( 'Table of Contents', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the table content for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_layout',
								'name'    => esc_html__( 'layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for the table of contents of this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Full Width (2 Columns)', 'foxiz' ),
									'2'       => esc_html__( 'Half Width', 'foxiz' ),
									'3'       => esc_html__( 'Full Width (1 Column)', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_position',
								'type'    => 'text',
								'name'    => esc_html__( 'Display Position', 'foxiz' ),
								'desc'    => esc_html__( 'Input a position (after x paragraphs) to display the table of contents box.', 'foxiz' ),
								'info'    => esc_html__( 'Leave it blank as the default, Set "-1" to display at the top.', 'foxiz' ),
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-header',
						'title'  => esc_html__( 'Site Header', 'foxiz' ),
						'icon'   => 'dashicons-heading',
						'desc'   => esc_html__( 'The transparent headers are only suited for pages have a slider or wide background image at the top.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'header_style',
								'name'    => esc_html__( 'Predefined Header Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a site header layout for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_header_style( true, true, false, true ),
								'default' => '0',
							],
							[
								'id'          => 'header_template',
								'name'        => esc_html__( 'or Use Ruby Template for Site Header', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website header for this page.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will override all "Header Layout" settings. Leave it blank to disable.', 'foxiz' ),
								'type'        => 'textarea',
								'placeholder' => '[Ruby_E_Template id="1"]',
								'default'     => '',
							],
							[
								'id'          => 'mh_template',
								'name'        => esc_html__( 'or Use Ruby Template for Mobile Header', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode to display as a mobile header for this page.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will override all "Mobile Layout" settings. Leave it blank to disable.', 'foxiz' ),
								'type'        => 'textarea',
								'placeholder' => '[Ruby_E_Template id="1"]',
								'default'     => '',
							],
							[
								'id'      => 'nav_style',
								'type'    => 'select',
								'name'    => esc_html__( 'Navigation Bar Style', 'foxiz' ),
								'desc'    => esc_html__( 'Select navigation bar style for the site header of this page.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined headers: 1, 2, 3 and 5.', 'foxiz' ),
								'options' => [
									'default'  => esc_html__( '- Default -', 'foxiz' ),
									'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
									'border'   => esc_html__( 'Bottom Border', 'foxiz' ),
									'd-border' => esc_html__( 'Dark Bottom Border', 'foxiz' ),
									'none'     => esc_html__( 'None', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-footer',
						'title'  => esc_html__( 'Site Footer', 'foxiz' ),
						'icon'   => 'dashicons-align-full-width',
						'fields' => [
							[
								'id'          => 'footer_template',
								'name'        => esc_html__( 'Footer Template Shortcode', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website footer for this page.', 'foxiz' ),
								'info'        => esc_html__( 'Leave it blank as the default.', 'foxiz' ),
								'placeholder' => '[Ruby_E_Template id="1"]',
								'type'        => 'textarea',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-seo',
						'title'  => esc_html__( 'SEO Optimized', 'foxiz' ),
						'icon'   => 'dashicons-chart-area',
						'fields' => [
							[
								'id'      => 'meta_description',
								'name'    => esc_html__( 'Meta Description', 'foxiz' ),
								'desc'    => esc_html__( 'Input the meta description (SEO) for this page.', 'foxiz' ),
								'info'    => esc_html__( 'Leave this field blank or disable setting "Theme Options > SEO Optimized > Meta Description" if you are using a 3rd party SEO plugin.', 'foxiz' ),
								'type'    => 'textarea',
								'rows'    => 3,
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-ads',
						'title'  => esc_html__( 'Ads', 'foxiz' ),
						'icon'   => 'dashicons-money-alt',
						'fields' => [
							[
								'id'      => 'disable_top_ad',
								'name'    => esc_html__( 'Top Site Ad', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the top ad site for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'disable_header_ad',
								'name'    => esc_html__( 'Header Advertising Widget Section', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable all widgets in the header advertising widget section (Appearance > Widgets) for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'alert_bar',
								'name'    => esc_html__( 'Header Alert Bar', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the alert bar below the header.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined header layouts.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'auto_ads',
								'name'    => esc_html__( 'Auto Ads', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable auto Ads for this page.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
				],
			];
		}

		public function single_post_metaboxes() {

			$configs = [
				'id'         => 'foxiz_post_options',
				'title'      => esc_html__( 'Single Post Settings', 'foxiz' ),
				'context'    => 'normal',
				'post_types' => [ 'post' ],
				'tabs'       => [
					[
						'id'     => 'section-tagline',
						'title'  => esc_html__( 'Tagline & Highlights', 'foxiz' ),
						'icon'   => 'dashicons-edit-large',
						'fields' => [
							[
								'id'      => 'tagline',
								'name'    => esc_html__( 'Tagline', 'foxiz' ),
								'desc'    => esc_html__( 'Input a tagline for this post.', 'foxiz' ),
								'info'    => esc_html__( 'It will display under the single post title.', 'foxiz' ),
								'type'    => 'textarea',
								'rows'    => 3,
								'single'  => true,
								'default' => '',
							],
							[
								'id'      => 'tagline_tag',
								'name'    => esc_html__( 'Tagline HTML Tag', 'foxiz' ),
								'desc'    => esc_html__( 'Select a HTML tag for this tagline.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'0'    => esc_html__( '- Default -', 'foxiz' ),
									'h2'   => esc_html__( 'H2', 'foxiz' ),
									'h3'   => esc_html__( 'H3', 'foxiz' ),
									'h4'   => esc_html__( 'H4', 'foxiz' ),
									'h5'   => esc_html__( 'H5', 'foxiz' ),
									'h6'   => esc_html__( 'H6', 'foxiz' ),
									'p'    => esc_html__( 'p', 'foxiz' ),
									'span' => esc_html__( 'span', 'foxiz' ),
									'div'  => esc_html__( 'div', 'foxiz' ),
								],
								'default' => '0',
							],
							[
								'id'     => 'highlights',
								'name'   => esc_html__( 'Post Highlights', 'foxiz' ),
								'desc'   => esc_html__( 'Show a highlights section at the top for the post content.', 'foxiz' ),
								'info'   => esc_html__( 'Navigate to "Theme Options > Single Post > Tagline & highlights" to edit the heading.', 'foxiz' ),
								'type'   => 'group',
								'button' => esc_html__( '+Add Highlight', 'foxiz' ),
								'fields' => [
									[
										'id'      => 'point',
										'name'    => esc_html__( 'Highlight Point', 'foxiz' ),
										'default' => '',
									],
								],
							],
						],
					],
					[
						'id'     => 'section-featured',
						'title'  => esc_html__( 'Featured Image', 'foxiz' ),
						'icon'   => 'dashicons-format-image',
						'fields' => [
							[
								'id'      => 'featured_crop_size',
								'name'    => esc_html__( 'Featured Crop Size', 'foxiz' ),
								'desc'    => esc_html__( 'Select a custom crop size for this post featured image.', 'foxiz' ),
								'info'    => esc_html__( 'You can create new sizes in "Theme Options > Theme Design > Featured Image"', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_crop_size(),
								'default' => 'default',
							],
							[
								'id'      => 'featured_caption',
								'name'    => esc_html__( 'Caption Text', 'foxiz' ),
								'desc'    => esc_html__( 'Input caption text for the featured image.', 'foxiz' ),
								'type'    => 'textarea',
								'default' => '',
							],
							[
								'id'      => 'featured_attribution',
								'name'    => esc_html__( 'Attribution', 'foxiz' ),
								'desc'    => esc_html__( 'Input an attribution for the featured image.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-category',
						'title'  => esc_html__( 'Primary Category & Tag', 'foxiz' ),
						'desc'   => esc_html__( 'Only the selected category or tag will be displayed on the entry category icon in the post listing.', 'foxiz' ),
						'icon'   => 'dashicons-admin-network',
						'fields' => [
							[
								'name'        => esc_html__( 'Primary Category', 'foxiz' ),
								'id'          => 'primary_category',
								'type'        => 'category_select',
								'taxonomy'    => 'category',
								'placeholder' => esc_html__( 'Select a primary category for this post.', 'foxiz' ),
								'desc'        => esc_html__( 'It is useful if this post has multiple categories.', 'foxiz' ),
								'info'        => esc_html__( 'Ensure the selected item belongs the categories of this post.', 'foxiz' ),
								'default'     => '',
							],

							[
								'name'        => esc_html__( 'Primary Tag', 'foxiz' ),
								'id'          => 'primary_tag',
								'type'        => 'tag_select',
								'placeholder' => esc_html__( 'Select a primary tag for this post.', 'foxiz' ),
								'desc'        => esc_html__( 'It is useful if this post has multiple tags, and you want to display tags instead of the category in category icon of the post listings', 'foxiz' ),
								'info'        => esc_html__( 'Input keyword to search; ensure the selected item belongs to the tags of this post.', 'foxiz' ),
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-custom-meta',
						'title'  => esc_html__( 'Custom Meta', 'foxiz' ),
						'desc'   => esc_html__( 'Navigate to "Theme Options > Theme Design > Custom Meta" for additional settings.', 'foxiz' ),
						'icon'   => 'dashicons-plus-alt',
						'fields' => [
							[
								'id'      => 'meta_custom',
								'name'    => esc_html__( 'Custom Meta Value', 'foxiz' ),
								'desc'    => esc_html__( 'Input a value for the custom meta that you created.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-standard',
						'title'  => esc_html__( 'Standard Format', 'foxiz' ),
						'icon'   => 'dashicons-menu-alt',
						'desc'   => esc_html__( 'The setting below will apply to the standard post format.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'layout',
								'name'    => esc_html__( 'Standard Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
								'type'    => 'image_select',
								'class'   => 'big',
								'options' => foxiz_config_single_standard_layouts(),
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-video',
						'title'  => esc_html__( 'Video Format', 'foxiz' ),
						'icon'   => 'dashicons-format-video',
						'desc'   => esc_html__( 'Choose the "Video" option within the "Post Format" setting located in the right-hand pane for the feature to function correctly.', 'foxiz' ),
						'fields' => [
							[
								'id'          => 'video_url',
								'name'        => esc_html__( 'Video URL', 'foxiz' ),
								'desc'        => esc_html__( 'Input your video link, support: Youtube, Vimeo, DailyMotion.', 'foxiz' ),
								'info'        => esc_html__( 'Do not forget to select "Video" from the "Post Format" dropdown.', 'foxiz' ),
								'placeholder' => 'https://youtu.be/...',
								'type'        => 'text',
								'default'     => '',
							],
							[
								'id'          => 'video_embed',
								'name'        => esc_html__( 'Iframe Embed Code', 'foxiz' ),
								'desc'        => esc_html__( 'Input iframe embed code if WordPress cannot support your video URL.', 'foxiz' ),
								'info'        => esc_html__( 'Leave the "Video URL" setting blank to make it work.', 'foxiz' ),
								'placeholder' => '<iframe.....',
								'type'        => 'textarea',
							],
							[
								'id'   => 'video_hosted',
								'name' => esc_html__( 'Self-Hosted Video', 'foxiz' ),
								'desc' => esc_html__( 'Upload your video file, support: mp4, m4v, webm, ogv, wmv, flv files.', 'foxiz' ),
								'info' => esc_html__( 'This setting will take priority over other video source settings.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'   => 'video_preview',
								'name' => esc_html__( 'Preview Video', 'foxiz' ),
								'desc' => esc_html__( 'Upload a preview video for displaying in the blog listing when hovering on featured image.', 'foxiz' ),
								'info' => esc_html__( 'Tip: Keep this preview video shorts and lightweight as possible.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'      => 'video_layout',
								'name'    => esc_html__( 'Video Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for this video post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
								'type'    => 'image_select',
								'class'   => 'big',
								'options' => foxiz_config_single_video_layouts(),
								'default' => 'default',
							],
							[
								'id'      => 'video_autoplay',
								'name'    => esc_html__( 'Autoplay Video', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable autoplay video for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'          => 'duration',
								'name'        => esc_html__( 'Duration', 'foxiz' ),
								'desc'        => esc_html__( 'Duration of this video file. This setting will apply to SEO markup data.', 'foxiz' ),
								'info'        => esc_html__( 'Ensure the date format is HH:MM:SS', 'foxiz' ),
								'placeholder' => '01:10:30',
								'type'        => 'text',
							],
						],
					],
					[
						'id'     => 'section-audio',
						'title'  => esc_html__( 'Audio Format', 'foxiz' ),
						'icon'   => 'dashicons-format-audio',
						'desc'   => esc_html__( 'Choose the "Audio" option within the "Post Format" setting located in the right-hand pane for the feature to function correctly.', 'foxiz' ),
						'fields' => [
							[
								'id'          => 'audio_url',
								'name'        => esc_html__( 'Audio URL', 'foxiz' ),
								'desc'        => esc_html__( 'Input your audio URL, support: SoundCloud, MixCloud.', 'foxiz' ),
								'info'        => esc_html__( 'Do not forget to select "Audio" from the "Post Format" dropdown.', 'foxiz' ),
								'placeholder' => 'https://soundcloud...',
								'type'        => 'text',
							],
							[
								'id'          => 'audio_embed',
								'name'        => esc_html__( 'or Embed Code', 'foxiz' ),
								'desc'        => esc_html__( 'Input iframe embed code if WordPress cannot support your audio URL.', 'foxiz' ),
								'info'        => esc_html__( 'Leave the "Audio URL" setting blank to make it work.', 'foxiz' ),
								'placeholder' => '<iframe.....',
								'type'        => 'textarea',
							],
							[
								'id'   => 'audio_hosted',
								'name' => esc_html__( 'Self-Hosted Audio', 'foxiz' ),
								'desc' => esc_html__( 'Upload your audio file, support: mp3, ogg, wma, m4a, wav files.', 'foxiz' ),
								'info' => esc_html__( 'This setting will take priority over other audio source settings.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'      => 'audio_layout',
								'name'    => esc_html__( 'Audio Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a audio layout for this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
								'type'    => 'image_select',
								'class'   => 'big',
								'options' => foxiz_config_single_audio_layouts(),
								'default' => 'default',
							],
							[
								'id'      => 'audio_autoplay',
								'name'    => esc_html__( 'Autoplay Audio', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable autoplay audio for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-gallery',
						'title'  => esc_html__( 'Gallery Format', 'foxiz' ),
						'icon'   => 'dashicons-format-gallery',
						'desc'   => esc_html__( 'Choose the "Gallery" option within the "Post Format" setting located in the right-hand pane for the feature to function correctly.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'gallery_data',
								'name'    => esc_html__( 'Upload Gallery', 'foxiz' ),
								'desc'    => esc_html__( 'Upload your images for this gallery.', 'foxiz' ),
								'info'    => esc_html__( 'Do not forget to select "Gallery" from the "Post Format" dropdown.', 'foxiz' ),
								'type'    => 'images',
								'default' => '',
							],
							[
								'id'      => 'gallery_layout',
								'name'    => esc_html__( 'Gallery Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for this gallery post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
								'type'    => 'image_select',
								'class'   => 'big',
								'options' => foxiz_config_single_gallery_layouts(),
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-template',
						'title'  => esc_html__( 'Template Builder', 'foxiz' ),
						'icon'   => 'dashicons-layout',
						'desc'   => esc_html__( 'Use Ruby template to build the layout for this post. The setting will take priority over all.', 'foxiz' ),
						'fields' => [
							[
								'id'          => 'single_template',
								'name'        => esc_html__( 'Template Shortcode', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for this post.', 'foxiz' ),
								'info'        => esc_html__( 'Leave blank to if you want to use the predefined layouts.', 'foxiz' ),
								'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
								'type'        => 'text',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-sidebar',
						'title'  => esc_html__( 'Sidebar Area', 'foxiz' ),
						'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Single Post > Sidebar Area".', 'foxiz' ),
						'icon'   => 'dashicons-align-pull-right',
						'fields' => [
							[
								'id'      => 'sidebar_position',
								'name'    => esc_html__( 'Sidebar Position', 'foxiz' ),
								'desc'    => esc_html__( 'Select a position for this post sidebar.', 'foxiz' ),
								'class'   => 'sidebar-select',
								'type'    => 'image_select',
								'options' => foxiz_config_sidebar_position(),
								'default' => 'default',
							],
							[
								'id'      => 'sidebar_name',
								'name'    => esc_html__( 'Assign a Sidebar', 'foxiz' ),
								'desc'    => esc_html__( 'Assign a custom sidebar for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_sidebar_name(),
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-review',
						'title'  => esc_html__( 'Review', 'foxiz' ),
						'icon'   => 'dashicons-star-filled',
						'desc'   => esc_html__( 'As default, The box will appear at the bottom. Use [ruby_review_box] shortcode if you want to put the box anywhere in the post content.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'review',
								'name'    => esc_html__( 'Post Review', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the review box.', 'foxiz' ),
								'type'    => 'select',
								'class'   => 'ruby-review-checkbox',
								'options' => [
									'-1' => esc_html__( '- Disable -', 'foxiz' ),
									'1'  => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => '-1',
							],
							[
								'id'      => 'user_can_review',
								'name'    => esc_html__( 'User Rating in Comments', 'foxiz' ),
								'desc'    => esc_html__( 'Visitors can leave a rating and review for this post.', 'foxiz' ),
								'info'    => esc_html__( 'The comment box will be replaced with the review box.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
									'1'       => esc_html__( 'Enable with Review', 'foxiz' ),
									'2'       => esc_html__( 'Force Enable for This Post', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'review_type',
								'name'    => esc_html__( 'Review Type', 'foxiz' ),
								'desc'    => esc_html__( 'Select a type of review for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'star'    => esc_html__( 'Stars (1 > 5)', 'foxiz' ),
									'score'   => esc_html__( 'Score (1 > 10)', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'review_title',
								'name'    => esc_html__( 'Review Heading', 'foxiz' ),
								'desc'    => esc_html__( 'Input a heading for this review box.', 'foxiz' ),
								'type'    => 'text',
								'default' => esc_html__( 'Review Overview', 'foxiz' ),
							],
							[
								'id'   => 'review_image',
								'name' => esc_html__( 'Review Image', 'foxiz' ),
								'desc' => esc_html__( 'Upload a background image for the review box.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'     => 'review_criteria',
								'name'   => esc_html__( 'Criteria Data', 'foxiz' ),
								'desc'   => esc_html__( 'Create your review criteria based on the type you choose.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Criteria',
								'fields' => [
									[
										'name'    => esc_html__( 'Item Label', 'foxiz' ),
										'id'      => 'label',
										'default' => esc_html__( 'Criteria', 'foxiz' ),
									],
									[
										'name'    => esc_html__( 'Score (1 > 10) or Rating (1 > 5)', 'foxiz' ),
										'id'      => 'rating',
										'default' => '',
									],
								],
							],
							[
								'id'     => 'review_pros',
								'name'   => esc_html__( 'Advantages', 'foxiz' ),
								'desc'   => esc_html__( 'Input advantages for this review.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Pros',
								'fields' => [
									[
										'name'    => esc_html__( 'Item', 'foxiz' ),
										'id'      => 'pros_item',
										'default' => esc_html__( 'advantage item', 'foxiz' ),
									],
								],
							],
							[
								'id'     => 'review_cons',
								'name'   => esc_html__( 'Disadvantages', 'foxiz' ),
								'desc'   => esc_html__( 'Input disadvantages for this review.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Cons',
								'fields' => [
									[
										'name'    => esc_html__( 'Item', 'foxiz' ),
										'id'      => 'cons_item',
										'default' => esc_html__( 'disadvantage item', 'foxiz' ),
									],
								],
							],
							[
								'id'   => 'review_meta',
								'name' => esc_html__( 'Meta Description', 'foxiz' ),
								'desc' => esc_html__( 'Input a short description to display before the review score, e.g. Good, Bad...', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'   => 'review_summary',
								'name' => esc_html__( 'Final Summary', 'foxiz' ),
								'desc' => esc_html__( 'Input final summary for this review.', 'foxiz' ),
								'type' => 'textarea',
							],
							[
								'id'   => 'review_button',
								'name' => esc_html__( 'Offer Label', 'foxiz' ),
								'desc' => esc_html__( 'Input a offer label (Call to action) for this product review.', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'   => 'review_destination',
								'name' => esc_html__( 'Offer Destination URL', 'foxiz' ),
								'desc' => esc_html__( 'Input the destination URL of the offer.', 'foxiz' ),
								'info' => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'          => 'review_price',
								'name'        => esc_html__( 'Price Offer', 'foxiz' ),
								'desc'        => esc_html__( 'Input the price of the offer.', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => '99',
								'type'        => 'text',
							],
							[
								'id'          => 'review_currency',
								'name'        => esc_html__( 'Currency', 'foxiz' ),
								'desc'        => esc_html__( 'Input a currency for the offer price.', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => 'USD',
								'type'        => 'text',
							],
							[
								'id'          => 'review_price_valid',
								'name'        => esc_html__( 'Price Valid Until', 'foxiz' ),
								'desc'        => esc_html__( 'Input the valid until  date for this offer, Ensure you input right format: yyyy-mm-dd', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => esc_html__( 'yyyy-mm-dd', 'foxiz' ),
								'type'        => 'text',
							],
						],
					],
					[
						'id'     => 'section-live',
						'title'  => esc_html__( 'Live Blogging', 'foxiz' ),
						'icon'   => 'dashicons-format-status',
						'desc'   => esc_html__( 'Use the "Live Blog" Gutenberg block to blog a live event, political elections, sporting events, conferences..', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'live_blog',
								'name'    => esc_html__( 'Live Blog', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable live blog for this post.', 'foxiz' ),
								'info'    => esc_html__( 'In archive mode, visitors still see the entries, but posting tools are removed.', 'foxiz' ),
								'single'  => true,
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- None -', 'foxiz' ),
									'yes'     => esc_html__( 'Enable', 'foxiz' ),
									'archive' => esc_html__( 'Archive', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'live_location',
								'name'    => esc_html__( 'Live Location', 'foxiz' ),
								'desc'    => esc_html__( 'Input a live location for this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will be used for schema structure data markup.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
							[
								'id'          => 'live_label',
								'name'        => esc_html__( 'Live Label', 'foxiz' ),
								'desc'        => esc_html__( 'Input a custom Live Label to display at the the top.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will override the default setting in Theme Options > Live Blogging > Live Updates Label.', 'foxiz' ),
								'placeholder' => esc_html__( 'Live Updates', 'foxiz' ),
								'type'        => 'text',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-sponsor',
						'title'  => esc_html__( 'Sponsored Post', 'foxiz' ),
						'icon'   => 'dashicons-bell',
						'fields' => [
							[
								'id'      => 'sponsor_post',
								'name'    => esc_html__( 'Sponsored Post', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable sponsored content for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'-1' => esc_html__( '- Disable -', 'foxiz' ),
									'1'  => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => '-1',
							],
							[
								'id'      => 'sponsor_url',
								'name'    => esc_html__( 'Sponsor URL', 'foxiz' ),
								'desc'    => esc_html__( 'Input the sponsor website URL.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
							[
								'id'      => 'sponsor_name',
								'name'    => esc_html__( 'Sponsor Name', 'foxiz' ),
								'desc'    => esc_html__( 'Input the sponsor brand name for this post', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
							[
								'id'   => 'sponsor_logo',
								'name' => esc_html__( 'Sponsor Logo', 'foxiz' ),
								'desc' => esc_html__( 'Upload the sponsor logo for this post.', 'foxiz' ),
								'info' => esc_html__( 'The recommended height value is 52px.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'   => 'sponsor_logo_light',
								'name' => esc_html__( 'Sponsor Light Logo', 'foxiz' ),
								'desc' => esc_html__( 'Upload the sponsor light logo for this post.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'      => 'sponsor_redirect',
								'name'    => esc_html__( 'Directly Redirect', 'foxiz' ),
								'desc'    => esc_html__( 'Directly redirect to the sponsor website when clicking on the post listing title.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-shares',
						'title'  => esc_html__( 'Fake Post Views', 'foxiz' ),
						'desc'   => esc_html__( 'The view meta requests the "Post Views Counter" plugin to run.', 'foxiz' ),
						'icon'   => 'dashicons-visibility',
						'fields' => [
							[
								'id'      => 'start_view',
								'name'    => esc_html__( 'Fake View Value', 'foxiz' ),
								'desc'    => esc_html__( 'Input a starting view value for this post.', 'foxiz' ),
								'info'    => esc_html__( 'Leave this setting blank to display the real count.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-widget',
						'title'  => esc_html__( 'Widgets & Ads', 'foxiz' ),
						'desc'   => esc_html__( 'Manage ad sections and top/bottom content widgets for this post.', 'foxiz' ),
						'icon'   => 'dashicons-editor-insertmore',
						'fields' => [
							[
								'id'      => 'disable_top_ad',
								'name'    => esc_html__( 'Top Site Advert', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the top ad site for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'disable_header_ad',
								'name'    => esc_html__( 'Header Advertising Widget Section', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable all widgets in the header advertising widget section (Appearance > Widgets) for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'alert_bar',
								'name'    => esc_html__( 'Header Alert Bar', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the alert bar below the header.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined header layouts.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_top',
								'name'    => esc_html__( 'Top Content -  Widgets Area', 'foxiz' ),
								'desc'    => esc_html__( 'Show widgets at the top of the post content.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Top Area" to add your widgets.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'1'  => esc_html__( 'Enable', 'foxiz' ),
									'-1' => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => '1',
							],
							[
								'id'      => 'entry_bottom',
								'name'    => esc_html__( 'Bottom Content - Widgets Area', 'foxiz' ),
								'desc'    => esc_html__( 'Show widgets at the bottom of the post content.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Bottom Area" to add your widgets.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'1'  => esc_html__( 'Enable', 'foxiz' ),
									'-1' => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => '1',
							],
							[
								'id'      => 'entry_ad_1',
								'name'    => esc_html__( 'Inline Ad 1', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 1 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_ad_2',
								'name'    => esc_html__( 'Inline Ad 2', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 2 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_ad_3',
								'name'    => esc_html__( 'Inline Ad 3', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 3 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'auto_ads',
								'name'    => esc_html__( 'Auto Ads', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable auto Ads for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-toc',
						'title'  => esc_html__( 'Table of Content', 'foxiz' ),
						'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Table of Content".', 'foxiz' ),
						'icon'   => 'dashicons-editor-ol',
						'fields' => [
							[
								'id'      => 'table_contents_post',
								'name'    => esc_html__( 'Table of Contents', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the table content for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_layout',
								'name'    => esc_html__( 'Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for the table of contents of this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Full Width (2 Columns)', 'foxiz' ),
									'2'       => esc_html__( 'Half Width', 'foxiz' ),
									'3'       => esc_html__( 'Full Width (1 Column)', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_position',
								'type'    => 'text',
								'name'    => esc_html__( 'Display Position', 'foxiz' ),
								'desc'    => esc_html__( 'Input a position (after x paragraphs) to display the table of contents box.', 'foxiz' ),
								'info'    => esc_html__( 'Leave it blank as the default, Set "-1" to display at the top.', 'foxiz' ),
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-pages-selected',
						'title'  => esc_html__( 'Break Page Selection', 'foxiz' ),
						'desc'   => esc_html__( 'Display the selected page section when you use page breaks for a lengthy content post. Ensure all headings in the post content are included to guarantee the proper functioning of the feature.', 'foxiz' ),
						'icon'   => 'dashicons-admin-page',
						'fields' => [
							[
								'id'     => 'page_selected',
								'name'   => esc_html__( 'Headings Table', 'foxiz' ),
								'desc'   => esc_html__( 'Please input the heading for each page corresponding to the page break tags.', 'foxiz' ),
								'info'   => esc_html__( 'For more information, refer to the following link: https://help.themeruby.com/foxiz/break-page-selection/', 'foxiz' ),
								'type'   => 'group',
								'button' => esc_html__( '+Add Heading', 'foxiz' ),
								'fields' => [
									[
										'id'      => 'title',
										'name'    => esc_html__( 'Input Heading', 'foxiz' ),
										'default' => '',
									],
								],
							],
						],
					],
					[
						'id'     => 'section-via',
						'title'  => esc_html__( 'Sources/Via', 'foxiz' ),
						'icon'   => 'dashicons-paperclip',
						'fields' => [
							[
								'id'     => 'source_data',
								'name'   => esc_html__( 'Post Sources', 'foxiz' ),
								'desc'   => esc_html__( 'Add sources for this post.', 'foxiz' ),
								'info'   => esc_html__( 'It will display below the post tags.', 'foxiz' ),
								'type'   => 'group',
								'class'  => 'small-item',
								'button' => esc_html__( '+Add Post Source', 'foxiz' ),
								'fields' => [
									[
										'name'    => esc_html__( 'Source Name', 'foxiz' ),
										'id'      => 'name',
										'default' => '',
									],
									[
										'name'    => esc_html__( 'Source URL', 'foxiz' ),
										'id'      => 'url',
										'default' => '',
									],

								],
							],
							[
								'id'     => 'via_data',
								'name'   => esc_html__( 'Post Via', 'foxiz' ),
								'desc'   => esc_html__( 'Add via or credit for this post.', 'foxiz' ),
								'info'   => esc_html__( 'It will display below the post tags.', 'foxiz' ),
								'type'   => 'group',
								'class'  => 'small-item',
								'button' => esc_html__( '+Add Post Via', 'foxiz' ),
								'fields' => [
									[
										'name'    => esc_html__( 'Via Name', 'foxiz' ),
										'id'      => 'name',
										'default' => '',
									],
									[
										'name'    => esc_html__( 'Via URL', 'foxiz' ),
										'id'      => 'url',
										'default' => '',
									],

								],
							],
						],
					],
					[
						'id'     => 'section-ajax',
						'title'  => 'Auto Load Next Posts',
						'icon'   => 'dashicons-update',
						'fields' => [
							[
								'id'      => 'ajax_next_post',
								'name'    => esc_html__( 'Auto Load Next Posts', 'foxiz' ),
								'desc'    => esc_html__( 'Load next posts when scrolling down to the single bottom.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Theme Options > Single Post > Auto Load Next Posts" for more settings.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'ajax_limit',
								'name'    => esc_html__( 'Limit Total Posts', 'foxiz' ),
								'desc'    => esc_html__( 'Set a maximum limit for the total number of posts loaded using the "Auto Load Next Posts" feature while scrolling.', 'foxiz' ),
								'info'    => esc_html__( 'Leave it blank as the default.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],

						],
					],
					[
						'id'     => 'section-reaction',
						'title'  => 'Reaction',
						'icon'   => 'dashicons-heart',
						'fields' => [
							[
								'id'      => 'reaction',
								'name'    => esc_html__( 'User Reaction', 'foxiz' ),
								'desc'    => esc_html__( 'Show the reaction section at the bottom of the post content.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Theme Options > User Reaction" for more settings.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-inline-related',
						'title'  => 'Inline Related',
						'icon'   => 'dashicons-clipboard',
						'desc'   => esc_html__( 'If you manually added related content via Gutenberg block or inline shortcode, this box will be disabled to ensure you have full control.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'inline_related',
								'name'    => esc_html__( 'Inline Related Posts', 'foxiz' ),
								'desc'    => esc_html__( 'Show the related posts block in post content for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'          => 'inline_related_pos',
								'name'        => esc_html__( 'Display Positions', 'foxiz' ),
								'desc'        => esc_html__( 'Input positions (after x paragraphs) to display the related box for this post.', 'foxiz' ),
								'info'        => esc_html__( 'Allow multiple positions, separate positions by commas, e.g., 5, 8.', 'foxiz' ),
								'placeholder' => '5,8',
								'type'        => 'text',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-disclosure',
						'title'  => 'Affiliate Disclosure',
						'icon'   => 'dashicons-info-outline',
						'fields' => [
							[
								'id'      => 'disclosure_condition',
								'name'    => esc_html__( 'Disclosure Section', 'foxiz' ),
								'desc'    => esc_html__( 'Choose the display of the Disclosure section for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Show', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-seo',
						'title'  => 'SEO Optimized',
						'desc'   => esc_html__( 'Disable default schema markup if you use a party 3rd SEO plugin.', 'foxiz' ),
						'icon'   => 'dashicons-chart-area',
						'fields' => [
							[
								'id'       => 'article_markup',
								'name'     => esc_html__( 'Article Schema Markup', 'foxiz' ),
								'subtitle' => esc_html__( 'Select structured data (schema markup) or disable the default for this post.', 'foxiz' ),
								'type'     => 'select',
								'options'  => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Article', 'foxiz' ),
									'2'       => esc_html__( 'News Article', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default'  => 'default',
							],
							[
								'id'      => 'review_markup',
								'name'    => esc_html__( 'Review Schema Markup', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable review markup for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'meta_description',
								'name'    => esc_html__( 'Meta Description', 'foxiz' ),
								'desc'    => esc_html__( 'Enter the meta description (SEO) for this post. The theme will fallback to Tagline > Excerpt > part of the post content if left empty.', 'foxiz' ),
								'info'    => esc_html__( 'If you are using a third-party SEO plugin, fully disable the meta description for Foxiz by turning off the setting in "Theme Options > SEO Optimized > Meta Description".', 'foxiz' ),
								'type'    => 'textarea',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-header',
						'title'  => 'Site Header',
						'icon'   => 'dashicons-heading',
						'desc'   => esc_html__( 'The transparent headers are only suited for layouts: Standard 2, Standard 5, Video 2 and Audio 2.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'header_style',
								'name'    => esc_html__( 'Predefined Header Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a site header layout for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_header_style( true, true ),
								'default' => 'default',
							],
							[
								'id'          => 'header_template',
								'name'        => esc_html__( 'or Use Ruby Template for Site Header', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website header for this post.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will override all "Header Layout" settings. Leave it blank to disable.', 'foxiz' ),
								'placeholder' => '[Ruby_E_Template id="1"]',
								'type'        => 'textarea',
								'default'     => '',
							],
							[
								'id'      => 'nav_style',
								'type'    => 'select',
								'name'    => esc_html__( 'Navigation Bar Style', 'foxiz' ),
								'desc'    => esc_html__( 'Select navigation bar style for the site header of this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined headers: 1, 2, 3 and 5.', 'foxiz' ),
								'options' => [
									'default'  => esc_html__( '- Default -', 'foxiz' ),
									'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
									'border'   => esc_html__( 'Bottom Border', 'foxiz' ),
									'd-border' => esc_html__( 'Dark Bottom Border', 'foxiz' ),
									'none'     => esc_html__( 'None', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-footer',
						'title'  => esc_html__( 'Site Footer', 'foxiz' ),
						'icon'   => 'dashicons-align-full-width',
						'fields' => [
							[
								'id'          => 'footer_template',
								'name'        => esc_html__( 'Use Ruby Template for Site Footer', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website footer for this post.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will take priority over all other settings.', 'foxiz' ),
								'type'        => 'textarea',
								'placeholder' => '[Ruby_E_Template id="1"]',
								'default'     => '',
							],
						],
					],
				],
			];

			return apply_filters( 'rb_single_metaboxes', $configs );
		}

		public function single_post_type_metaboxes() {

			$post_types = foxiz_get_post_types_list();

			unset( $post_types['forum'] );
			unset( $post_types['topic'] );
			unset( $post_types['reply'] );
			$supported_post_types = array_keys( $post_types );

			if ( empty( $supported_post_types ) ) {
				return [];
			}

			$configs = [
				'id'         => 'foxiz_post_options',
				'title'      => esc_html__( 'Single Post Type Settings', 'foxiz' ),
				'context'    => 'normal',
				'post_types' => $supported_post_types,
				'tabs'       => [
					[
						'id'     => 'section-tagline',
						'title'  => esc_html__( 'Tagline & Highlights', 'foxiz' ),
						'icon'   => 'dashicons-edit-large',
						'fields' => [
							[
								'id'      => 'tagline',
								'name'    => esc_html__( 'Tagline', 'foxiz' ),
								'desc'    => esc_html__( 'Input a tagline for this post.', 'foxiz' ),
								'info'    => esc_html__( 'It will display under the single post title.', 'foxiz' ),
								'type'    => 'textarea',
								'single'  => true,
								'default' => '',
							],
							[
								'id'      => 'tagline_tag',
								'name'    => esc_html__( 'Tagline HTML Tag', 'foxiz' ),
								'desc'    => esc_html__( 'Select a HTML tag for this tagline.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'0'    => esc_html__( '- Default -', 'foxiz' ),
									'h2'   => esc_html__( 'H2', 'foxiz' ),
									'h3'   => esc_html__( 'H3', 'foxiz' ),
									'h4'   => esc_html__( 'H4', 'foxiz' ),
									'h5'   => esc_html__( 'H5', 'foxiz' ),
									'h6'   => esc_html__( 'H6', 'foxiz' ),
									'p'    => esc_html__( 'p', 'foxiz' ),
									'span' => esc_html__( 'span', 'foxiz' ),
									'div'  => esc_html__( 'div', 'foxiz' ),
								],
								'default' => '0',
							],
							[
								'id'     => 'highlights',
								'name'   => esc_html__( 'Post Highlights', 'foxiz' ),
								'desc'   => esc_html__( 'Show a highlights section at the top for the post content.', 'foxiz' ),
								'info'   => esc_html__( 'Navigate to "Theme Options > Single Post > Tagline & highlights" to edit the heading.', 'foxiz' ),
								'type'   => 'group',
								'button' => esc_html__( '+Add Highlight', 'foxiz' ),
								'fields' => [
									[
										'id'      => 'point',
										'name'    => esc_html__( 'Highlight Point', 'foxiz' ),
										'default' => '',
									],
								],
							],
						],
					],
					[
						'id'     => 'section-featured',
						'title'  => esc_html__( 'Featured Image', 'foxiz' ),
						'icon'   => 'dashicons-format-image',
						'fields' => [
							[
								'id'      => 'featured_crop_size',
								'name'    => esc_html__( 'Featured Crop Size', 'foxiz' ),
								'desc'    => esc_html__( 'Select a custom crop size for this post featured image.', 'foxiz' ),
								'info'    => esc_html__( 'You can create new sizes in "Theme Options > Theme Design > Featured Image"', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_crop_size(),
								'default' => 'default',
							],
							[
								'id'      => 'featured_caption',
								'name'    => esc_html__( 'Caption Text', 'foxiz' ),
								'desc'    => esc_html__( 'Input caption text for the featured image.', 'foxiz' ),
								'type'    => 'textarea',
								'default' => '',
							],
							[
								'id'      => 'featured_attribution',
								'name'    => esc_html__( 'Attribution', 'foxiz' ),
								'desc'    => esc_html__( 'Input an attribution for the featured image.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-custom-meta',
						'title'  => esc_html__( 'Custom Meta', 'foxiz' ),
						'desc'   => esc_html__( 'Navigate to "Theme Options > Theme Design > Custom Meta" for additional settings.', 'foxiz' ),
						'icon'   => 'dashicons-plus-alt',
						'fields' => [
							[
								'id'      => 'meta_custom',
								'name'    => esc_html__( 'Custom Meta Value', 'foxiz' ),
								'desc'    => esc_html__( 'Input a value for the custom meta that you created.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-standard',
						'title'  => esc_html__( 'Layout', 'foxiz' ),
						'icon'   => 'dashicons-menu-alt',
						'desc'   => esc_html__( 'The setting below will apply to this post type.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'layout',
								'name'    => esc_html__( 'Standard Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
								'type'    => 'image_select',
								'class'   => 'big',
								'options' => foxiz_config_single_standard_layouts(),
								'default' => 'default',
							],
							[
								'id'          => 'single_template',
								'name'        => esc_html__( 'Template Shortcode', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for this post.', 'foxiz' ),
								'info'        => esc_html__( 'Leave blank to if you want to use the predefined layouts.', 'foxiz' ),
								'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
								'type'        => 'text',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-sidebar',
						'title'  => esc_html__( 'Sidebar Area', 'foxiz' ),
						'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Single Post > Sidebar Area".', 'foxiz' ),
						'icon'   => 'dashicons-align-pull-right',
						'fields' => [
							[
								'id'      => 'sidebar_position',
								'name'    => esc_html__( 'Sidebar Position', 'foxiz' ),
								'desc'    => esc_html__( 'Select a position for this post sidebar.', 'foxiz' ),
								'class'   => 'sidebar-select',
								'type'    => 'image_select',
								'options' => foxiz_config_sidebar_position(),
								'default' => 'default',
							],
							[
								'id'      => 'sidebar_name',
								'name'    => esc_html__( 'Assign a Sidebar', 'foxiz' ),
								'desc'    => esc_html__( 'Assign a custom sidebar for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_sidebar_name(),
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-review',
						'title'  => esc_html__( 'Review', 'foxiz' ),
						'icon'   => 'dashicons-star-filled',
						'desc'   => esc_html__( 'As default, The box will appear at the bottom. Use [ruby_review_box] shortcode if you want to put the box anywhere in the post content.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'review',
								'name'    => esc_html__( 'Post Review', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the review box.', 'foxiz' ),
								'type'    => 'select',
								'class'   => 'ruby-review-checkbox',
								'options' => [
									'-1' => esc_html__( '- Disable -', 'foxiz' ),
									'1'  => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => '-1',
							],
							[
								'id'      => 'user_can_review',
								'name'    => esc_html__( 'User Rating in Comments', 'foxiz' ),
								'desc'    => esc_html__( 'Visitors can leave a rating and review for this post.', 'foxiz' ),
								'info'    => esc_html__( 'The comment box will be replaced with the review box.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
									'1'       => esc_html__( 'Enable with Review', 'foxiz' ),
									'2'       => esc_html__( 'Force Enable for This Post', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'review_type',
								'name'    => esc_html__( 'Review Type', 'foxiz' ),
								'desc'    => esc_html__( 'Select a type of review for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'star'    => esc_html__( 'Stars (1 > 5)', 'foxiz' ),
									'score'   => esc_html__( 'Score (1 > 10)', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'review_title',
								'name'    => esc_html__( 'Review Heading', 'foxiz' ),
								'desc'    => esc_html__( 'Input a heading for this review box.', 'foxiz' ),
								'type'    => 'text',
								'default' => esc_html__( 'Review Overview', 'foxiz' ),
							],
							[
								'id'   => 'review_image',
								'name' => esc_html__( 'Review Image', 'foxiz' ),
								'desc' => esc_html__( 'Upload a background image for the review box.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'     => 'review_criteria',
								'name'   => esc_html__( 'Criteria Data', 'foxiz' ),
								'desc'   => esc_html__( 'Create your review criteria based on the type you choose.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Criteria',
								'fields' => [
									[
										'name'    => esc_html__( 'Item Label', 'foxiz' ),
										'id'      => 'label',
										'default' => esc_html__( 'Criteria', 'foxiz' ),
									],
									[
										'name'    => esc_html__( 'Score (1 > 10) or Rating (1 > 5)', 'foxiz' ),
										'id'      => 'rating',
										'default' => '',
									],
								],
							],
							[
								'id'     => 'review_pros',
								'name'   => esc_html__( 'Advantages', 'foxiz' ),
								'desc'   => esc_html__( 'Input advantages for this review.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Pros',
								'fields' => [
									[
										'name'    => esc_html__( 'Item', 'foxiz' ),
										'id'      => 'pros_item',
										'default' => esc_html__( 'advantage item', 'foxiz' ),
									],
								],
							],
							[
								'id'     => 'review_cons',
								'name'   => esc_html__( 'Disadvantages', 'foxiz' ),
								'desc'   => esc_html__( 'Input disadvantages for this review.', 'foxiz' ),
								'type'   => 'group',
								'button' => '+ Add Cons',
								'fields' => [
									[
										'name'    => esc_html__( 'Item', 'foxiz' ),
										'id'      => 'cons_item',
										'default' => esc_html__( 'disadvantage item', 'foxiz' ),
									],
								],
							],
							[
								'id'   => 'review_meta',
								'name' => esc_html__( 'Meta Description', 'foxiz' ),
								'desc' => esc_html__( 'Input a short description to display before the review score, e.g. Good, Bad...', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'   => 'review_summary',
								'name' => esc_html__( 'Final Summary', 'foxiz' ),
								'desc' => esc_html__( 'Input final summary for this review.', 'foxiz' ),
								'type' => 'textarea',
							],
							[
								'id'   => 'review_button',
								'name' => esc_html__( 'Offer Label', 'foxiz' ),
								'desc' => esc_html__( 'Input a offer label (Call to action) for this product review.', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'   => 'review_destination',
								'name' => esc_html__( 'Offer Destination URL', 'foxiz' ),
								'desc' => esc_html__( 'Input the destination URL of the offer.', 'foxiz' ),
								'info' => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'type' => 'text',
							],
							[
								'id'          => 'review_price',
								'name'        => esc_html__( 'Price Offer', 'foxiz' ),
								'desc'        => esc_html__( 'Input the price of the offer.', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => '99',
								'type'        => 'text',
							],
							[
								'id'          => 'review_currency',
								'name'        => esc_html__( 'Currency', 'foxiz' ),
								'desc'        => esc_html__( 'Input a currency for the offer price.', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => 'USD',
								'type'        => 'text',
							],
							[
								'id'          => 'review_price_valid',
								'name'        => esc_html__( 'Price Valid Until', 'foxiz' ),
								'desc'        => esc_html__( 'Input the valid until  date for this offer, Ensure you input right format: yyyy-mm-dd', 'foxiz' ),
								'info'        => esc_html__( 'Review schema will use this information.', 'foxiz' ),
								'placeholder' => esc_html__( 'yyyy-mm-dd', 'foxiz' ),
								'type'        => 'text',
							],
						],
					],
					[
						'id'     => 'section-sponsor',
						'title'  => esc_html__( 'Sponsored Post', 'foxiz' ),
						'icon'   => 'dashicons-bell',
						'fields' => [
							[
								'id'      => 'sponsor_post',
								'name'    => esc_html__( 'Sponsored Post', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable sponsored content for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'-1' => esc_html__( '- Disable -', 'foxiz' ),
									'1'  => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => '-1',
							],
							[
								'id'      => 'sponsor_url',
								'name'    => esc_html__( 'Sponsor URL', 'foxiz' ),
								'desc'    => esc_html__( 'Input the sponsor website URL.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
							[
								'id'      => 'sponsor_name',
								'name'    => esc_html__( 'Sponsor Name', 'foxiz' ),
								'desc'    => esc_html__( 'Input the sponsor brand name for this post', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
							[
								'id'   => 'sponsor_logo',
								'name' => esc_html__( 'Sponsor Logo', 'foxiz' ),
								'desc' => esc_html__( 'Upload the sponsor logo for this post.', 'foxiz' ),
								'info' => esc_html__( 'The recommended height value is 52px.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'   => 'sponsor_logo_light',
								'name' => esc_html__( 'Sponsor Light Logo', 'foxiz' ),
								'desc' => esc_html__( 'Upload the sponsor light logo for this post.', 'foxiz' ),
								'type' => 'file',
							],
							[
								'id'      => 'sponsor_redirect',
								'name'    => esc_html__( 'Directly Redirect', 'foxiz' ),
								'desc'    => esc_html__( 'Directly redirect to the sponsor website when clicking on the post listing title.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-shares',
						'title'  => esc_html__( 'Fake Post Views', 'foxiz' ),
						'desc'   => esc_html__( 'The view meta requests the "Post Views Counter" plugin to run.', 'foxiz' ),
						'icon'   => 'dashicons-visibility',
						'fields' => [
							[
								'id'      => 'start_view',
								'name'    => esc_html__( 'Fake View Value', 'foxiz' ),
								'desc'    => esc_html__( 'Input a starting view value for this post.', 'foxiz' ),
								'info'    => esc_html__( 'Leave this setting blank to display the real count.', 'foxiz' ),
								'type'    => 'text',
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-widget',
						'title'  => esc_html__( 'Widgets & Ads', 'foxiz' ),
						'desc'   => esc_html__( 'Manage ad sections and top/bottom content widgets for this post.', 'foxiz' ),
						'icon'   => 'dashicons-editor-insertmore',
						'fields' => [
							[
								'id'      => 'disable_top_ad',
								'name'    => esc_html__( 'Top Site Advert', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the top ad site for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'disable_header_ad',
								'name'    => esc_html__( 'Header Advertising Widget Section', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable all widgets in the header advertising widget section (Appearance > Widgets) for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'alert_bar',
								'name'    => esc_html__( 'Header Alert Bar', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the alert bar below the header.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined header layouts.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_top',
								'name'    => esc_html__( 'Top Content - Widgets Area', 'foxiz' ),
								'desc'    => esc_html__( 'Show widgets at the top of the post content.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Top Area" to add your widgets.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'1'  => esc_html__( 'Enable', 'foxiz' ),
									'-1' => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => '1',
							],
							[
								'id'      => 'entry_bottom',
								'name'    => esc_html__( 'Bottom Content - Widgets Area', 'foxiz' ),
								'desc'    => esc_html__( 'Show widgets at the bottom of the post content.', 'foxiz' ),
								'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Bottom Area" to add your widgets.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'1'  => esc_html__( 'Enable', 'foxiz' ),
									'-1' => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => '1',
							],
							[
								'id'      => 'entry_ad_1',
								'name'    => esc_html__( 'Inline Ad 1', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 1 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_ad_2',
								'name'    => esc_html__( 'Inline Ad 2', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 2 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'entry_ad_3',
								'name'    => esc_html__( 'Inline Ad 3', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable inline content ad 3 for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'auto_ads',
								'name'    => esc_html__( 'Auto Ads', 'foxiz' ),
								'desc'    => esc_html__( 'Choose to disable auto Ads for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-toc',
						'title'  => esc_html__( 'Table of Content', 'foxiz' ),
						'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Table of Content".', 'foxiz' ),
						'icon'   => 'dashicons-editor-ol',
						'fields' => [
							[
								'id'      => 'table_contents_post',
								'name'    => esc_html__( 'Table of Contents', 'foxiz' ),
								'desc'    => esc_html__( 'Enable or disable the table content for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Enable', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_layout',
								'name'    => esc_html__( 'Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a layout for the table of contents of this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Full Width (2 Columns)', 'foxiz' ),
									'2'       => esc_html__( 'Half Width', 'foxiz' ),
									'3'       => esc_html__( 'Full Width (1 Column)', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'      => 'table_contents_position',
								'type'    => 'text',
								'name'    => esc_html__( 'Display Position', 'foxiz' ),
								'desc'    => esc_html__( 'Input a position (after x paragraphs) to display the table of contents box.', 'foxiz' ),
								'info'    => esc_html__( 'Leave it blank as the default, Set "-1" to display at the top.', 'foxiz' ),
								'default' => '',
							],
						],
					],
					[
						'id'     => 'section-pages-selected',
						'title'  => esc_html__( 'Break Page Selection', 'foxiz' ),
						'desc'   => esc_html__( 'Display the selected page section when you use page breaks for a lengthy content post. Ensure all headings in the post content are included to guarantee the proper functioning of the feature.', 'foxiz' ),
						'icon'   => 'dashicons-admin-page',
						'fields' => [
							[
								'id'     => 'page_selected',
								'name'   => esc_html__( 'Headings Table', 'foxiz' ),
								'desc'   => esc_html__( 'Please input the heading for each page corresponding to the page break tags.', 'foxiz' ),
								'info'   => esc_html__( 'For more information, refer to the following link: https://help.themeruby.com/foxiz/break-page-selection/', 'foxiz' ),
								'type'   => 'group',
								'button' => esc_html__( '+Add Heading', 'foxiz' ),
								'fields' => [
									[
										'id'      => 'title',
										'name'    => esc_html__( 'Input Heading', 'foxiz' ),
										'default' => '',
									],
								],
							],
						],
					],
					[
						'id'     => 'section-via',
						'title'  => esc_html__( 'Sources/Via', 'foxiz' ),
						'icon'   => 'dashicons-paperclip',
						'fields' => [
							[
								'id'     => 'source_data',
								'name'   => esc_html__( 'Post Sources', 'foxiz' ),
								'desc'   => esc_html__( 'Add sources for this post.', 'foxiz' ),
								'info'   => esc_html__( 'It will display below the post tags.', 'foxiz' ),
								'type'   => 'group',
								'class'  => 'small-item',
								'button' => esc_html__( '+Add Post Source', 'foxiz' ),
								'fields' => [
									[
										'name'    => esc_html__( 'Source Name', 'foxiz' ),
										'id'      => 'name',
										'default' => '',
									],
									[
										'name'    => esc_html__( 'Source URL', 'foxiz' ),
										'id'      => 'url',
										'default' => '',
									],

								],
							],
							[
								'id'     => 'via_data',
								'name'   => esc_html__( 'Post Via', 'foxiz' ),
								'desc'   => esc_html__( 'Add via or credit for this post.', 'foxiz' ),
								'info'   => esc_html__( 'It will display below the post tags.', 'foxiz' ),
								'type'   => 'group',
								'class'  => 'small-item',
								'button' => esc_html__( '+Add Post Via', 'foxiz' ),
								'fields' => [
									[
										'name'    => esc_html__( 'Via Name', 'foxiz' ),
										'id'      => 'name',
										'default' => '',
									],
									[
										'name'    => esc_html__( 'Via URL', 'foxiz' ),
										'id'      => 'url',
										'default' => '',
									],

								],
							],
						],
					],
					[
						'id'     => 'section-inline-related',
						'title'  => 'Inline Related',
						'icon'   => 'dashicons-clipboard',
						'fields' => [
							[
								'id'      => 'inline_related',
								'name'    => esc_html__( 'Inline Related Posts', 'foxiz' ),
								'desc'    => esc_html__( 'Show the related posts block in post content. Custom post types will query related posts by the first taxonomy or by tag slugs if available.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'          => 'inline_related_pos',
								'name'        => esc_html__( 'Display Positions', 'foxiz' ),
								'desc'        => esc_html__( 'Input positions (after x paragraphs) to display the related box for this post.', 'foxiz' ),
								'info'        => esc_html__( 'Allow multiple positions, separate positions by commas, e.g., 5, 8.', 'foxiz' ),
								'placeholder' => '5,8',
								'type'        => 'text',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-disclosure',
						'title'  => 'Affiliate Disclosure',
						'icon'   => 'dashicons-info-outline',
						'fields' => [
							[
								'id'      => 'disclosure_condition',
								'name'    => esc_html__( 'Disclosure Section', 'foxiz' ),
								'desc'    => esc_html__( 'Choose the display of the Disclosure section for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => [
									'default' => esc_html__( '- Default -', 'foxiz' ),
									'1'       => esc_html__( 'Show', 'foxiz' ),
									'-1'      => esc_html__( 'Disable', 'foxiz' ),
								],
								'default' => 'default',
							],
						],
					],
					[
						'id'     => 'section-header',
						'title'  => 'Site Header',
						'icon'   => 'dashicons-heading',
						'desc'   => esc_html__( 'The transparent headers are only suited for layouts: Standard 2, Standard 5, Video 2 and Audio 2.', 'foxiz' ),
						'fields' => [
							[
								'id'      => 'header_style',
								'name'    => esc_html__( 'Header Layout', 'foxiz' ),
								'desc'    => esc_html__( 'Select a site header layout for this post.', 'foxiz' ),
								'type'    => 'select',
								'options' => foxiz_config_header_style( true, true ),
								'default' => 'default',
							],
							[
								'id'      => 'nav_style',
								'type'    => 'select',
								'name'    => esc_html__( 'Navigation Bar Style', 'foxiz' ),
								'desc'    => esc_html__( 'Select navigation bar style for the site header of this post.', 'foxiz' ),
								'info'    => esc_html__( 'This setting will apply only to pre-defined headers: 1, 2, 3 and 5.', 'foxiz' ),
								'options' => [
									'default'  => esc_html__( '- Default -', 'foxiz' ),
									'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
									'border'   => esc_html__( 'Bottom Border', 'foxiz' ),
									'd-border' => esc_html__( 'Dark Bottom Border', 'foxiz' ),
									'none'     => esc_html__( 'None', 'foxiz' ),
								],
								'default' => 'default',
							],
							[
								'id'          => 'header_template',
								'name'        => esc_html__( 'Template Shortcode', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website header for this page.', 'foxiz' ),
								'info'        => esc_html__( 'This setting will override on the "Header style" setting.', 'foxiz' ),
								'placeholder' => '[Ruby_E_Template id="1"]',
								'type'        => 'textarea',
								'default'     => '',
							],
						],
					],
					[
						'id'     => 'section-footer',
						'title'  => esc_html__( 'Site Footer', 'foxiz' ),
						'icon'   => 'dashicons-align-full-width',
						'fields' => [
							[
								'id'          => 'footer_template',
								'name'        => esc_html__( 'Template Shortcode', 'foxiz' ),
								'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website footer for this post.', 'foxiz' ),
								'info'        => esc_html__( 'Leave it blank as the default.', 'foxiz' ),
								'type'        => 'textarea',
								'placeholder' => '[Ruby_E_Template id="1"]',
								'default'     => '',
							],
						],
					],
				],
			];

			return apply_filters( 'rb_single_metaboxes', $configs );
		}
	}
}