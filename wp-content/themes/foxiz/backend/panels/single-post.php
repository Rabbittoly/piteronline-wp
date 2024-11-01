<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_single_post' ) ) {
	function foxiz_register_options_single_post() {

		return [
			'title' => esc_html__( 'Single Post', 'foxiz' ),
			'id'    => 'foxiz_config_section_sp',
			'icon'  => 'el el-file',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_layout' ) ) {
	function foxiz_register_options_single_post_layout() {

		return [
			'title'      => esc_html__( 'Post Layout', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_layout',
			'desc'       => esc_html__( 'Customize the layouts and styles for the single post.', 'foxiz' ),
			'icon'       => 'el el-laptop',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_single_post_layout',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The settings are treated as a global setting. Individual post settings take priority over them.', 'foxiz' ),
				],
				[
					'id'    => 'info_single_post_settings',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Post > Edit Post > Post Settings" to change for the individual posts.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_sp_default',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Standard Post', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Predefined Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a default layout for the single post.', 'foxiz' ),
					'options'  => foxiz_config_single_standard_layouts( false ),
					'default'  => 'standard_1',
				],
				[
					'id'          => 'single_post_standard_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'or Use Ruby Template for the Single Post', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for the single post.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_sp_default',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_sp_video',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Video Post Format', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_video_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Video Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a global layout for the video post format.', 'foxiz' ),
					'options'  => foxiz_config_single_video_layouts( false ),
					'default'  => 'video_1',
				],
				[
					'id'          => 'single_post_video_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Video Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for the single video post format.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'       => 'single_post_video_autoplay',
					'type'     => 'switch',
					'title'    => esc_html__( 'Autoplay', 'foxiz' ),
					'subtitle' => esc_html__( 'Autoplay the featured video when the visitors scrolling to it.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_video_float',
					'type'     => 'switch',
					'title'    => esc_html__( 'Floating', 'foxiz' ),
					'subtitle' => esc_html__( 'Floating video on the screen on scroll.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_sp_video',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_sp_audio',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Audio Post Format', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_audio_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Audio Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a global layout for the audio post format.', 'foxiz' ),
					'options'  => foxiz_config_single_audio_layouts( false ),
					'default'  => 'audio_1',
				],
				[
					'id'          => 'single_post_audio_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Audio Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for the single audio post format.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'       => 'single_post_audio_autoplay',
					'type'     => 'switch',
					'title'    => esc_html__( 'Autoplay Audio', 'foxiz' ),
					'subtitle' => esc_html__( 'Autoplay the audio file.', 'foxiz' ),
					'desc'     => esc_html__( 'This setting may not work in some browsers due to the autoplay policies.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_sp_audio',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_sp_gallery',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Gallery Post Format', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_gallery_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Gallery Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a global layout for the gallery post format.', 'foxiz' ),
					'options'  => foxiz_config_single_gallery_layouts( false ),
					'default'  => 'gallery_1',
				],
				[
					'id'          => 'single_post_gallery_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Gallery - Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for the single gallery post format.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'       => 'single_post_gallery_lightbox',
					'type'     => 'switch',
					'title'    => esc_html__( 'Gallery Lightbox', 'foxiz' ),
					'subtitle' => esc_html__( 'Show a popup when clicking on gallery images.', 'foxiz' ),
					'default'  => 1,
				],
				[
					'id'     => 'section_end_sp_gallery',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_centered',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Centering Header', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply to the whole predefined layouts, Except for layouts are created by Ruby Template.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_post_centered',
					'type'     => 'switch',
					'title'    => esc_html__( 'Center Header', 'foxiz' ),
					'subtitle' => esc_html__( 'Center the single post title, subtitle and entry category.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'single_post_meta_centered',
					'type'        => 'switch',
					'title'       => esc_html__( 'Center Entry Meta', 'foxiz' ),
					'subtitle'    => esc_html__( 'Center the single entry meta bar.', 'foxiz' ),
					'description' => esc_html__( 'It is recommended that you consider turning off the big avatar and last updated date settings in "Single Post > Entry Meta" panel if you have enabled this setting.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'     => 'section_end_single_centered',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_feat_ratio',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Featured Ratio', 'foxiz' ),
					'subtitle' => esc_html__( 'By default, the theme will display the original ratio of the featured image the single post. You can customize the percent here to make it look more appealing.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_6_ratio',
					'title'       => esc_html__( 'layout 6 - Featured Ratio', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom featured image ratio for the single layout 6.', 'foxiz' ),
					'description' => esc_html__( 'Input a number, the value will be based on a percent of the width of the featured image.', 'foxiz' ),
					'placeholder' => '150',
					'validate'    => 'numeric',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'          => 'single_10_ratio',
					'title'       => esc_html__( 'Layout 10 - Featured Ratio', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom featured image ratio for the single layout 10.', 'foxiz' ),
					'description' => esc_html__( 'Input a number, the value will be based on a percent of the width of the featured image.', 'foxiz' ),
					'placeholder' => '45',
					'validate'    => 'numeric',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '45',
				],
				[
					'id'          => 'single_11_ratio',
					'title'       => esc_html__( 'Layout 11 - Featured Ratio', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom featured image ratio for the single layout 11.', 'foxiz' ),
					'description' => esc_html__( 'Input a number, the value will be based on a percent of the width of the featured image.', 'foxiz' ),
					'placeholder' => '45',
					'validate'    => 'numeric',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '45',
				],
				[
					'id'     => 'section_end_single_feat_ratio',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],

			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_sections' ) ) {
	function foxiz_register_options_single_post_sections() {

		return [
			'title'      => esc_html__( 'Content Area', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_section',
			'desc'       => esc_html__( 'Manage layout and styles for the post content.', 'foxiz' ),
			'icon'       => 'el el-th-list',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_content_area_post_type',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Some settings below will also apply to custom post types such as podcasts and others.', 'foxiz' ),
				],
				[
					'id'       => 'section_start_content_width_sidebar',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Has Sidebar - Optimized Line Length', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply to a single post layout with a sidebar.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_post_line_length',
					'type'     => 'select',
					'title'    => esc_html__( 'Limit Content Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Enhance readability by optimizing the line length through the limitation of the content width in the post.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( '- None -', 'foxiz' ),
						'1' => esc_html__( 'Optimize Line Length', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'          => 'single_content_width',
					'title'       => esc_html__( 'Default Max Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter the maximum width (in px) for the content.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply if you choose "Optimize Line Length". Leave blank to use the default (760).', 'foxiz' ),
					'placeholder' => '760',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '760',
				],
				[
					'id'     => 'section_end_content_width_sidebar',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_content_width_wo_sidebar',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'No Sidebar - Optimized Line Length', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply to the single post full-width layout (without a sidebar).', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_post_width_wo_sb',
					'type'     => 'select',
					'title'    => esc_html__( 'Limit Content Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Enhance readability by optimizing the line length through the limitation of the content width in the post.', 'foxiz' ),
					'options'  => [
						'0'     => esc_html__( '- None -', 'foxiz' ),
						'small' => esc_html__( 'Optimize Line Length', 'foxiz' ),
					],
					'default'  => 'small',
				],
				[
					'id'          => 'single_content_fw_width',
					'title'       => esc_html__( 'Default Max Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter the maximum width (in px) for the content.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply if you choose "Optimize Line Length". Leave blank to use the default (840).', 'foxiz' ),
					'placeholder' => '840',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '840',
				],
				[
					'id'     => 'section_end_content_width_wo_sidebar',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_hyperlink',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Hyperlink', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'hyperlink_style',
					'title'    => esc_html__( 'Hyperlink Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font style for the post hyperlinks.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'           => esc_html__( 'Bold', 'foxiz' ),
						'bold_italic' => esc_html__( 'Bold Italic', 'foxiz' ),
						'italic'      => esc_html__( 'Normal Italic', 'foxiz' ),
						'normal'      => esc_html__( 'Normal', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'hyperlink_line',
					'title'    => esc_html__( 'Hyperlink Underline', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable hyperlink underline.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Enable', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'          => 'hyperlink_color',
					'title'       => esc_html__( 'Hyperlink Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color value for post hyperlinks.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_hyperlink_color',
					'title'       => esc_html__( 'Dark Mode - Hyperlink Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color value for post hyperlinks in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'hyperlink_line_color',
					'title'       => esc_html__( 'Underline Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the hyperlink underline. Leave blank to set as the global color.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_hyperlink_line_color',
					'title'       => esc_html__( 'Dark Mode - Underline Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the hyperlink underline in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_single_hyperlink',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_alignwide',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Align Wide Image & Gallery', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply to wide image & gallery in the content without sidebar.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'alignwide_width',
					'title'       => esc_html__( 'Wide Align Max Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a max width value (in px) for wide image and gallery.', 'foxiz' ),
					'description' => esc_html__( 'Default is 1600px', 'foxiz' ),
					'placeholder' => '1600',
					'validate'    => 'numeric',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_alignwide',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_section_qv',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Quick View Info', 'foxiz' ),
					'subtitle' => esc_html__( 'A banner displayed at the top of content for sponsored and review posts.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_post_quick_view',
					'type'     => 'switch',
					'title'    => esc_html__( 'Quick View Info', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable quick view info bar (review & sponsor meta) at the top of the content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_single_section_qv',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_content_spacing',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Paragraph Spacing', 'foxiz' ),
					'subtitle' => esc_html__( 'These settings will affect the spacing of paragraph tags within the post content.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_content_spacing',
					'type'        => 'text',
					'title'       => esc_html__( 'Paragraph Spacing (rem)', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter a spacing value in rem (relative to the body font size) for the spacing between paragraphs in the post content.', 'foxiz' ),
					'description' => esc_html__( 'This value specifies the spacing between paragraphs relative to the body font size, measured in rem. The default value is 1.5', 'foxiz' ),
					'placeholder' => '1.5',
				],
				[
					'id'     => 'section_end_single_content_spacing',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],

				[
					'id'     => 'section_start_single_section_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Footer Area', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_tags',
					'type'     => 'switch',
					'title'    => esc_html__( 'Post Tags Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the post tags bar at the bottom of the post content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_sources',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sources Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the sources bar at the bottom of the post content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_via',
					'type'     => 'switch',
					'title'    => esc_html__( 'Via Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the via bar at the bottom of the post content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'efoot_layout',
					'title'    => esc_html__( 'Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a layout for this section.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'    => esc_html__( '- Default -', 'foxiz' ),
						'bg'   => esc_html__( 'Gray Background', 'foxiz' ),
						'gray' => esc_html__( 'Gray Border', 'foxiz' ),
						'dark' => esc_html__( 'Dark Border', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_single_section_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_newsletter',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Bottom Newsletter', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_newsletter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Bottom Newsletter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the newsletter form at bottom entry content.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_newsletter_title',
					'type'     => 'text',
					'title'    => esc_html__( 'Newsletter Heading', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your a heading for your newsletter section.', 'foxiz' ),
					'default'  => esc_html__( 'Sign Up For Daily Newsletter', 'foxiz' ),
				],
				[
					'id'       => 'single_post_newsletter_description',
					'type'     => 'textarea',
					'rows'     => 2,
					'title'    => esc_html__( 'Newsletter Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your a description for your newsletter.', 'foxiz' ),
					'default'  => esc_html__( 'Be keep up! Get the latest breaking news delivered straight to your inbox.', 'foxiz' ),
				],
				[
					'id'          => 'single_post_newsletter_code',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Newsletter Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your a newsletter (subscribe) shortcode or script to show at bottom entry content.', 'foxiz' ),
					'description' => esc_html__( 'You can use the shortcode of MC4WP or your plugin.', 'foxiz' ),
					'placeholder' => '[mc4wp_form]',
					'default'     => '[mc4wp_form]',
				],
				[
					'id'       => 'single_post_newsletter_policy',
					'type'     => 'textarea',
					'rows'     => 2,
					'title'    => esc_html__( 'Policy Text', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your policy text for the newsletter form, row HTML allowed.', 'foxiz' ),
					'default'  => 'By signing up, you agree to our <a href="#">Terms of Use</a> and acknowledge the data practices in our <a href="#">Privacy Policy</a>. You may unsubscribe at any time.',
				],
				[
					'id'     => 'section_end_single_newsletter',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_box',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Author Card & Next/Prev Pagination', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_author_card',
					'type'     => 'switch',
					'title'    => esc_html__( 'Author Card', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the author information in the bottom of the content.', 'foxiz' ),
					'desc'     => esc_html__( 'The author box requests author information (Users > Profile) for displaying.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_next_prev',
					'type'     => 'switch',
					'title'    => esc_html__( 'Next/Prev Post Pagination', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the next/previous link navigation the single post.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_next_prev_mobile',
					'type'     => 'switch',
					'title'    => esc_html__( 'Next/Prev - Mobile Hide', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide the next/previous link navigation on mobile devices.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_single_box',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_classic_editor',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Classic Editor', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below are supported for the classic editor (old).', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_iframe_responsive',
					'title'       => esc_html__( 'Iframe Embed Responsive', 'foxiz' ),
					'subtitle'    => esc_html__( 'This feature helps you embed iframes in the classic editor to be responsive and adjust to the screen width.', 'foxiz' ),
					'description' => esc_html__( 'PLEASE NOTE: Do not enable if you are using the default Gutenberg editor, as this is unnecessary.', 'foxiz' ),
					'type'        => 'switch',
					'transparent' => true,
				],
				[
					'id'     => 'section_end_classic_editor',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_post_social_left',
					'title'  => esc_html__( 'Fixed Left Area', 'foxiz' ),
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'indent' => true,
				],
				[
					'id'       => 'share_left',
					'type'     => 'switch',
					'title'    => esc_html__( 'Fixed Left Share Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on socials at the fixed left section.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_left_mobile',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this section on mobile devices.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_left_color',
					'type'     => 'switch',
					'title'    => esc_html__( 'Colorful Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable color for social icons.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_left_facebook',
					'type'     => 'switch',
					'title'    => esc_html__( 'Facebook', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Facebook.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_left_twitter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Twitter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Twitter.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_left_flipboard',
					'type'     => 'switch',
					'title'    => esc_html__( 'Flipboard', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Flipboard.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_pinterest',
					'type'     => 'switch',
					'title'    => esc_html__( 'Pinterest', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Pinterest.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_whatsapp',
					'type'     => 'switch',
					'title'    => esc_html__( 'WhatsApp', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on WhatsApp.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_linkedin',
					'type'     => 'switch',
					'title'    => esc_html__( 'LinkedIn', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on LinkedIn.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_tumblr',
					'type'     => 'switch',
					'title'    => esc_html__( 'Tumblr', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Tumblr.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_reddit',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reddit', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Reddit.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_vk',
					'type'     => 'switch',
					'title'    => esc_html__( 'Vkontakte', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Vkontakte.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_telegram',
					'type'     => 'switch',
					'title'    => esc_html__( 'Telegram', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Telegram.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_threads',
					'type'     => 'switch',
					'title'    => esc_html__( 'Threads', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Threads.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_email',
					'type'     => 'switch',
					'title'    => esc_html__( 'Email', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Email.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_left_copy',
					'type'     => 'switch',
					'title'    => esc_html__( 'Copy Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the copy post link icon.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_left_print',
					'type'     => 'switch',
					'title'    => esc_html__( 'Print', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the print button.', 'foxiz' ),
					'required' => [ 'share_left', '=', '1' ],
					'default'  => true,
				],
				[
					'id'          => 'share_left_native',
					'type'        => 'switch',
					'title'       => esc_html__( 'Native Share', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the native browser share button.', 'foxiz' ),
					'description' => esc_html__( 'The button will only appear on supported browsers.', 'foxiz' ),
					'required'    => [ 'share_left', '=', '1' ],
					'default'     => true,
				],
				[
					'id'     => 'section_end_single_post_social_left',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_sidebar' ) ) {
	function foxiz_register_options_single_post_sidebar() {

		return [
			'title'      => esc_html__( 'Sidebar Area', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_sidebar',
			'desc'       => esc_html__( 'Manage sidebars for the single post.', 'foxiz' ),
			'icon'       => 'el el-align-right',
			'subsection' => true,
			'fields'     => [
				[
					'id'          => 'single_post_sidebar_name',
					'type'        => 'select',
					'title'       => esc_html__( 'Assign a Sidebar', 'foxiz' ),
					'subtitle'    => esc_html__( 'Assign a sidebar if you select a single layout which has a sidebar.', 'foxiz' ),
					'description' => esc_html__( 'You can set a sidebar for individual posts in the post editor.', 'foxiz' ),
					'options'     => foxiz_config_sidebar_name( false ),
					'default'     => 'foxiz_sidebar_default',
				],
				[
					'id'       => 'single_post_sidebar_position',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Sidebar Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a position for the single sidebar.', 'foxiz' ),
					'options'  => foxiz_config_sidebar_position(),
					'default'  => 'default',
				],
				[
					'id'       => 'single_post_sticky_sidebar',
					'type'     => 'select',
					'title'    => esc_html__( 'Sticky Sidebar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the sticky sidebar for the single post.', 'foxiz' ),
					'options'  => [
						'0'  => esc_html__( '- Default -', 'foxiz' ),
						'1'  => esc_html__( 'Sticky Sidebar', 'foxiz' ),
						'2'  => esc_html__( 'Sticky Last Widget', 'foxiz' ),
						'-1' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'single_post_sidebar_width',
					'type'        => 'text',
					'title'       => esc_html__( 'Sidebar Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom % width (1 to 100) for the single sidebar.', 'foxiz' ),
					'desc'        => esc_html__( 'This setting will also apply to the custom post type sidebar.', 'foxiz' ),
					'placeholder' => '33.3',
					'default'     => '',
				],
				[
					'id'       => 'single_post_sidebar_border',
					'type'     => 'select',
					'title'    => esc_html__( 'Left Border', 'foxiz' ),
					'subtitle' => esc_html__( 'Display a divider border in the single post sidebar.', 'foxiz' ),
					'desc'     => esc_html__( 'This setting is used for predefined single post layouts and also applies to the sidebar of custom post types.', 'foxiz' ),
					'options'  => [
						'0'         => esc_html__( '- None -', 'foxiz' ),
						'gray'      => esc_html__( 'Gray Solid', 'foxiz' ),
						'dark'      => esc_html__( 'Dark Solid', 'foxiz' ),
						'gray-dot'  => esc_html__( 'Gray Dotted', 'foxiz' ),
						'dark-dot'  => esc_html__( 'Dark Dotted', 'foxiz' ),
						'gray-dash' => esc_html__( 'Gray Dashed', 'foxiz' ),
						'dark-dash' => esc_html__( 'Dark Dashed', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'single_post_sidebar_padding',
					'type'        => 'text',
					'title'       => esc_html__( 'Left Extra Spacing', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom extra % spacing between single sidebar and content.', 'foxiz' ),
					'description' => esc_html__( 'Recommend a small value. Big percent values (more than 10) may cause sidebar layout issues.', 'foxiz' ),
					'placeholder' => '5',
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_category' ) ) {
	function foxiz_register_options_single_post_category() {

		return [
			'title'      => esc_html__( 'Entry Category', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_category',
			'desc'       => esc_html__( 'Select settings for the entry category.', 'foxiz' ),
			'icon'       => 'el el-folder-open',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'single_post_entry_category',
					'type'     => 'select',
					'title'    => esc_html__( 'Entry Category', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable entry category info in the single post.', 'foxiz' ),
					'options'  => foxiz_config_extended_entry_category(),
					'default'  => 'bg-1,big',
				],
				[
					'id'          => 'single_post_primary_category',
					'type'        => 'switch',
					'title'       => esc_html__( 'Primary Category', 'foxiz' ),
					'subtitle'    => esc_html__( 'By default, Primary category setting will apply only to the post listing.', 'foxiz' ),
					'description' => esc_html__( 'Enable this option if you would like to only show the primary category in the single post.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'          => 'single_max_categories',
					'title'       => esc_html__( 'Maximum Category Icons', 'foxiz' ),
					'subtitle'    => esc_html__( 'Specify the maximum number of categories or taxonomies to display at the top of a single post.', 'foxiz' ),
					'description' => esc_html__( 'This setting also applies if you replace category with tag information. Leave it blank to show all categories.', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'          => 'single_post_entry_category_size',
					'title'       => esc_html__( 'Entry Category Font Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom font size value (in px) for the single entry category.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply only on desktop devices. Navigate to "Typography > Entry Category" to set for tablet mobile devices.', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_tagline' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_tagline() {

		return [
			'title'      => esc_html__( 'Single Tagline', 'foxiz' ),
			'id'         => 'foxiz_config_section_single_tagline',
			'desc'       => esc_html__( 'Select a HTML tag for the single tagline to optimize your SEO settings.', 'foxiz' ),
			'icon'       => 'el el-pencil',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_tagline_typo',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit the tagline typography, navigate to "Typography > Headline & Tagline > Single Tagline".', 'foxiz' ),
				],
				[
					'id'     => 'section_start_tagline',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Tagline', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'tagline_tag',
					'type'     => 'select',
					'title'    => esc_html__( 'HTML Tag', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a HTML tag for the single tagline.', 'foxiz' ),
					'options'  => [
						'h2'   => esc_html__( 'H2', 'foxiz' ),
						'h3'   => esc_html__( 'H3', 'foxiz' ),
						'h4'   => esc_html__( 'H4', 'foxiz' ),
						'h5'   => esc_html__( 'H5', 'foxiz' ),
						'h6'   => esc_html__( 'H6', 'foxiz' ),
						'p'    => esc_html__( 'p', 'foxiz' ),
						'span' => esc_html__( 'span', 'foxiz' ),
						'div'  => esc_html__( 'div', 'foxiz' ),
					],
					'default'  => 'h2',
				],
				[
					'id'       => 'tagline_source',
					'type'     => 'select',
					'title'    => esc_html__( 'Tagline Source', 'foxiz' ),
					'subtitle' => esc_html__( 'Choose a content source for the tagline', 'foxiz' ),
					'options'  => [
						'0'       => esc_html__( 'Use Default Tagline', 'foxiz' ),
						'excerpt' => esc_html__( 'Use Excerpt', 'foxiz' ),
						'dual'    => esc_html__( 'Use Excerpt if empty Tagline', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_tagline',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_highlight',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Highlights', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'highlight_heading',
					'type'     => 'text',
					'title'    => esc_html__( 'Highlight Heading', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a heading for the highlights section if it is existing.', 'foxiz' ),
					'default'  => esc_html__( 'Highlights', 'foxiz' ),
				],
				[
					'id'     => 'section_end_highlight',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],

			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_meta' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_meta() {

		return [
			'title'      => esc_html__( 'Entry Meta', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_style',
			'desc'       => esc_html__( 'Customize the entry meta for the single post.', 'foxiz' ),
			'icon'       => 'el el-adjust-alt',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'section_start_single_meta_layout',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'You can center-align this section by navigating to "Single Post > Layout > Center Entry Meta".', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_meta_layout',
					'title'       => esc_html__( 'Right Section Layout', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a layout for the right section of the entry meta.', 'foxiz' ),
					'description' => esc_html__( 'This section includes "READ TIME" meta and "SHARE ON SOCIALS".', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'0'       => esc_html__( 'Standard', 'foxiz' ),
						'wrap'    => esc_html__( 'Wrap - Highlight', 'foxiz' ),
						'minimal' => esc_html__( 'Wrap - Minimalist', 'foxiz' ),
					],
					'default'     => '0',
				],
				[
					'id'       => 'single_post_meta_divider',
					'title'    => esc_html__( 'Divider Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a divider style between entry metas.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'         => esc_html__( '- Default -', 'foxiz' ),
						'default'   => esc_html__( 'Vertical Line', 'foxiz' ),
						'line'      => esc_html__( 'Solid Line', 'foxiz' ),
						'gray-line' => esc_html__( 'Gray Solid Line', 'foxiz' ),
						'dot'       => esc_html__( 'Dot', 'foxiz' ),
						'gray-dot'  => esc_html__( 'Gray Dot', 'foxiz' ),
						'none'      => esc_html__( 'White Spacing', 'foxiz' ),
						'wrap'      => esc_html__( 'Line Wrap', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'single_post_meta_border',
					'type'     => 'switch',
					'title'    => esc_html__( 'Top Border', 'foxiz' ),
					'subtitle' => esc_html__( 'Display a top gray border at the top of this section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_single_meta_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta', 'foxiz' ),
					'notice' => [
						esc_html__( 'Taxonomy & Custom Field Meta: You can input "Taxonomy name/slug" or custom field ID (meta boxes) if you want to display custom taxonomy or custom field value.', 'foxiz' ),
						esc_html__( 'Prefix & Suffix: You can add your prefix or suffix to a meta via the format: prefix {meta_key} suffix. Example: author, Categories: {category}, view', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'          => 'single_post_entry_meta',
					'type'        => 'select',
					'multi'       => true,
					'title'       => esc_html__( 'Entry Meta Tags', 'foxiz' ),
					'subtitle'    => esc_html__( 'Organize how you want the entry meta info to appear in the single post.', 'foxiz' ),
					'description' => esc_html__( 'Select "Disable" only if you wish to hide the entry meta.', 'foxiz' ),
					'options'     => foxiz_config_entry_meta_tags(),
					'default'     => [ 'author' ],
				],
				[
					'id'          => 'single_post_entry_meta_keys',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'or Custom Key Input', 'foxiz' ),
					'subtitle'    => esc_html__( 'This setting is more flexible and allows you to show prefix, suffix, Taxonomy, and custom field value by keys, default keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom, duration, index].', 'foxiz' ),
					'description' => esc_html__( 'Prefix & Suffix: You can add a prefix or suffix to a meta using the following format: prefix {meta_key} suffix. For example: author, Categories: {category}, view. You can also allow inline HTML tags such as <i>, <span>, etc.', 'foxiz' ),
					'placeholder' => esc_html__( 'author, date', 'foxiz' ),
				],
				[
					'id'          => 'single_post_avatar',
					'type'        => 'switch',
					'title'       => esc_html__( 'Big Avatar', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the author avatars before the entry meta bar.', 'foxiz' ),
					'description' => esc_html__( 'Tips: consider using the avatar meta and disable it if you want to center the bar to avoid layout issues..', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'       => 'single_post_meta_author_label',
					'title'    => esc_html__( '"By" Author Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show "by" text before the author meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'single_post_author_job',
					'type'     => 'switch',
					'title'    => esc_html__( 'Author Job', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the author job info.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_meta_author_style',
					'title'    => esc_html__( 'Author Meta Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a style for the author meta.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'     => esc_html__( 'Underline', 'foxiz' ),
						'bold'  => esc_html__( 'Bold Underline', 'foxiz' ),
						'dot'   => esc_html__( 'Dotted', 'foxiz' ),
						'dot-l' => esc_html__( 'Light Dotted', 'foxiz' ),
						'wavy'  => esc_html__( 'Wavy', 'foxiz' ),
						'color' => esc_html__( 'Color', 'foxiz' ),
						'text'  => esc_html__( 'Text Only', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'single_post_meta_bookmark_style',
					'title'    => esc_html__( 'Bookmark Meta Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a style for the bookmark icon.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'      => esc_html__( 'Default (without Border)', 'foxiz' ),
						'border' => esc_html__( 'Gray Bolder', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'single_post_meta_date_label',
					'title'       => esc_html__( '"Published & Updated" Date Label', 'foxiz' ),
					'subtitle'    => esc_html__( 'Show the label text before the date meta.', 'foxiz' ),
					'description' => esc_html__( 'The label only applies if you disable human time (ago format) to avoid SEO issues.', 'foxiz' ),
					'type'        => 'switch',
					'default'     => true,
				],
				[
					'id'       => 'single_post_tablet_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on tablet devices.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => [],
				],
				[
					'id'       => 'single_post_mobile_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on mobile devices.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => [],
				],
				[
					'id'     => 'section_end_single_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_updated_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Last Updated Date', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_updated_meta',
					'type'     => 'switch',
					'title'    => esc_html__( 'Last Updated Date', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the last updated meta.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_updated_label',
					'type'     => 'switch',
					'title'    => esc_html__( 'Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Toggle the display of the "Last Updated" label before the date.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'single_post_update_format',
					'type'        => 'text',
					'title'       => esc_html__( 'Date Format', 'foxiz' ),
					'subtitle'    => esc_html__( 'Custom date format for the last updated meta.', 'foxiz' ),
					'placeholder' => 'Y/m/d \a\t g:i A',
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_updated_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_min_read',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Right Section -  Reading Time', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'single_post_min_read',
					'type'        => 'switch',
					'title'       => esc_html__( 'Reading Time', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the reading time meta.', 'foxiz' ),
					'description' => esc_html__( 'Tips: consider using the default update meta and disable it if you want to center the bar to avoid layout issues..', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'     => 'section_end_single_min_read',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_post_social_top',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Right Section - Share on Socials', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'share_top',
					'type'     => 'switch',
					'title'    => esc_html__( 'Share Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the share on socials in the entry meta.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_top_color',
					'type'     => 'switch',
					'title'    => esc_html__( 'Colorful Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable color for social icons.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_top_facebook',
					'type'     => 'switch',
					'title'    => esc_html__( 'Facebook', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Facebook.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_top_twitter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Twitter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Twitter.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_top_flipboard',
					'type'     => 'switch',
					'title'    => esc_html__( 'Flipboard', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Flipboard.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_pinterest',
					'type'     => 'switch',
					'title'    => esc_html__( 'Pinterest', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Pinterest.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_whatsapp',
					'type'     => 'switch',
					'title'    => esc_html__( 'WhatsApp', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on WhatsApp.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_linkedin',
					'type'     => 'switch',
					'title'    => esc_html__( 'LinkedIn', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on LinkedIn.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_tumblr',
					'type'     => 'switch',
					'title'    => esc_html__( 'Tumblr', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Tumblr.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_reddit',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reddit', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Reddit.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_vk',
					'type'     => 'switch',
					'title'    => esc_html__( 'Vkontakte', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Vkontakte.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_telegram',
					'type'     => 'switch',
					'title'    => esc_html__( 'Telegram', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Telegram.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_threads',
					'type'     => 'switch',
					'title'    => esc_html__( 'Threads', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Threads.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_email',
					'type'     => 'switch',
					'title'    => esc_html__( 'Email', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Email.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_top_copy',
					'type'     => 'switch',
					'title'    => esc_html__( 'Copy Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the copy post link icon.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_top_print',
					'type'     => 'switch',
					'title'    => esc_html__( 'Print', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the print post button.', 'foxiz' ),
					'required' => [ 'share_top', '=', '1' ],
					'default'  => true,
				],
				[
					'id'          => 'share_top_native',
					'type'        => 'switch',
					'title'       => esc_html__( 'Native Share', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the native browser share button.', 'foxiz' ),
					'description' => esc_html__( 'The button will only appear on supported browsers.', 'foxiz' ),
					'required'    => [ 'share_top', '=', '1' ],
					'default'     => true,
				],
				[
					'id'     => 'section_end_single_post_social_top',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_shares' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_shares() {

		return [
			'title'      => esc_html__( 'Share on Socials', 'foxiz' ),
			'id'         => 'foxiz_config_section_single_shares',
			'desc'       => esc_html__( 'Choose the social media platforms for displaying the share button.', 'foxiz' ),
			'icon'       => 'el el-share',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_top_share',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Theme Options > Single Post > Entry Meta" to configure the top share bar.', 'foxiz' ),
				],
				[
					'id'    => 'left_share_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Theme Options > Single Posts > Content Area > Fixed Left Area" to configure the left share bar.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_single_post_social_bottom',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'At Bottom of Post Content', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'share_bottom',
					'type'     => 'switch',
					'title'    => esc_html__( 'Bottom Content Share Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the bottom share bar.', 'foxiz' ),
					'desc'     => esc_html__( 'This section is displayed at below of the post content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_bottom_color',
					'type'     => 'switch',
					'title'    => esc_html__( 'Colorful Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable color for social icons.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_bottom_facebook',
					'type'     => 'switch',
					'title'    => esc_html__( 'Facebook', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Facebook.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_bottom_twitter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Twitter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Twitter.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_bottom_flipboard',
					'type'     => 'switch',
					'title'    => esc_html__( 'Flipboard', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Flipboard.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_pinterest',
					'type'     => 'switch',
					'title'    => esc_html__( 'Pinterest', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Pinterest.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_whatsapp',
					'type'     => 'switch',
					'title'    => esc_html__( 'WhatsApp', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on WhatsApp.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_linkedin',
					'type'     => 'switch',
					'title'    => esc_html__( 'LinkedIn', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on LinkedIn.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_tumblr',
					'type'     => 'switch',
					'title'    => esc_html__( 'Tumblr', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Tumblr.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_reddit',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reddit', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Reddit.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_vk',
					'type'     => 'switch',
					'title'    => esc_html__( 'Vkontakte', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Vkontakte.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_telegram',
					'type'     => 'switch',
					'title'    => esc_html__( 'Telegram', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Telegram.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_threads',
					'type'     => 'switch',
					'title'    => esc_html__( 'Threads', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Threads.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_email',
					'type'     => 'switch',
					'title'    => esc_html__( 'Email', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Email.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => false,
				],
				[
					'id'       => 'share_bottom_copy',
					'type'     => 'switch',
					'title'    => esc_html__( 'Copy Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the copy post link button.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => true,
				],
				[
					'id'       => 'share_bottom_print',
					'type'     => 'switch',
					'title'    => esc_html__( 'Print', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the print button.', 'foxiz' ),
					'required' => [ 'share_bottom', '=', '1' ],
					'default'  => true,
				],
				[
					'id'          => 'share_bottom_native',
					'type'        => 'switch',
					'title'       => esc_html__( 'Native Share', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the native browser share button.', 'foxiz' ),
					'description' => esc_html__( 'The button will only appear on supported browsers.', 'foxiz' ),
					'required'    => [ 'share_bottom', '=', '1' ],
					'default'     => true,
				],
				[
					'id'     => 'section_end_single_post_social_bottom',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_sponsored' ) ) {
	function foxiz_register_options_single_post_sponsored() {

		return [
			'title'      => esc_html__( 'Sponsored Post', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_sponsored',
			'desc'       => esc_html__( 'Customize the sponsored posts.', 'foxiz' ),
			'icon'       => 'el el-bell',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'sponsor_meta_text',
					'type'     => 'text',
					'title'    => esc_html__( 'Sponsored Meta Text', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a meta text for the sponsored posts.', 'foxiz' ),
					'default'  => foxiz_html__( 'Sponsored by', 'foxiz' ),
				],
				[
					'id'       => 'single_post_sponsor_redirect',
					'type'     => 'switch',
					'title'    => esc_html__( 'Directly Redirect', 'foxiz' ),
					'subtitle' => esc_html__( 'Directly redirect to the sponsored URL when visitors click on the post title in the blog listing.', 'foxiz' ),
					'default'  => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_disclosure' ) ) {
	function foxiz_register_options_single_post_disclosure() {

		return [
			'title'      => esc_html__( 'Affiliate Disclosure', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_disclosure',
			'desc'       => esc_html__( 'Customize the affiliate disclosure for your single post.', 'foxiz' ),
			'icon'       => 'el el-exclamation-sign',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_disclosure_disable',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Post > Edit Post > Post Settings" to configure the section for individual posts.', 'foxiz' ),
				],
				[
					'id'    => 'info_disclosure_alternative',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can also use the Top and Bottom widget section in "Appearance > Widgets" for the disclosure info, or build a single template if you want more control over the position.', 'foxiz' ),
				],
				[
					'id'          => 'single_post_disclosure_condition',
					'title'       => esc_html__( 'Global Show Condition', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set the default display condition of the Disclosure section for all posts on your website.', 'foxiz' ),
					'description' => esc_html__( 'You can choose whether to show this section by default for all posts or disable it. Individual posts can override this setting via the post panel.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'0' => esc_html__( 'Disable by Default', 'foxiz' ),
						'1' => esc_html__( 'Show for All Posts', 'foxiz' ),
					],
					'default'     => '0',
				],
				[
					'id'          => 'disclosure_content',
					'title'       => esc_html__( 'Disclosure Content', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your disclosure content, allowing raw HTML.', 'foxiz' ),
					'description' => esc_html__( 'This section will appear on the top of the featured image or post content, depending on the layout you choose.', 'foxiz' ),
					'type'        => 'textarea',
					'default'     => '<strong>Disclosure:</strong> This website may contain affiliate links, which means I may earn a commission if you click on the link and make a purchase. I only recommend products or services that I personally use and believe will add value to my readers. Your support is appreciated!',
					'rows'        => 3,
				],
				[
					'id'       => 'disclosure_layout',
					'title'    => esc_html__( 'Box Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Choose a style for the Disclosure section.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'text' => esc_html__( 'Text Only', 'foxiz' ),
						'bg'   => esc_html__( 'Gray Background', 'foxiz' ),
					],
					'default'  => 'text',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_review' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_review() {

		return [
			'title'      => esc_html__( 'Review & Rating', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_review',
			'desc'       => esc_html__( 'Customize the review post.', 'foxiz' ),
			'icon'       => 'el el-star',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'single_post_review_type',
					'title'    => esc_html__( 'Default Review Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a default review type for your site.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'star'  => esc_html__( 'The Star (1 > 5)', 'foxiz' ),
						'score' => esc_html__( 'The Score (1 > 10)', 'foxiz' ),
					],
					'default'  => 'star',
				],
				[
					'id'       => 'single_post_user_can_review',
					'title'    => esc_html__( 'User Rating in Comments', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable visitors can rate and review your product on its review post.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( '- Disable -', 'foxiz' ),
						'1' => esc_html__( 'Enable for Post Review Only', 'foxiz' ),
						'2' => esc_html__( 'Enable for All Posts', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'single_post_review_image',
					'title'    => esc_html__( 'Default Review Image', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload a default image for the review box.', 'foxiz' ),
					'desc'     => esc_html__( 'Individual review image setting will take priority over this setting.', 'foxiz' ),
					'type'     => 'media',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_comment' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_comment() {

		return [
			'title'      => esc_html__( 'Comments', 'foxiz' ),
			'id'         => 'foxiz_config_section_single_comment',
			'desc'       => esc_html__( 'Customize the single comment box.', 'foxiz' ),
			'icon'       => 'el el-comment',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'single_post_comment_button',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show/Hide Comment Button', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the button show/hide comment box in the single post.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_comment',
					'type'     => 'switch',
					'title'    => esc_html__( 'Disable All Comments', 'foxiz' ),
					'subtitle' => esc_html__( 'This is a global setting to completely disable the comment box.', 'foxiz' ),
					'default'  => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_footer' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_footer() {

		return [
			'title'      => esc_html__( 'Related & Popular', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_footer',
			'desc'       => esc_html__( 'Customize the related and popular sections in the single post.', 'foxiz' ),
			'icon'       => 'el el-flag',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_related_post_type',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The settings below will also apply to custom post type as podcast and others.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_single_post_related',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Related Section', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_related',
					'type'     => 'select',
					'title'    => esc_html__( 'Related Section', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the related section at the footer the single post.', 'foxiz' ),
					'options'  => [
						'0'           => esc_html__( '- Disable -', 'foxiz' ),
						'standard'    => esc_html__( 'Standard Layout', 'foxiz' ),
						'rb_template' => esc_html__( 'Use Ruby Template', 'foxiz' ),
					],
					'default'  => 'standard',
				],
				[
					'id'       => 'single_post_related_where',
					'type'     => 'select',
					'title'    => esc_html__( 'Posts from Where', 'foxiz' ),
					'subtitle' => esc_html__( 'What posts should be displayed in the related section.', 'foxiz' ),
					'options'  => [
						'all'      => esc_html__( 'Same Tags & Categories', 'foxiz' ),
						'tag'      => esc_html__( 'Same Tags', 'foxiz' ),
						'category' => esc_html__( 'Same Categories', 'foxiz' ),
					],
					'default'  => 'all',
				],
				[
					'id'          => 'single_post_related_order',
					'type'        => 'select',
					'title'       => esc_html__( 'Order By', 'foxiz' ),
					'subtitle'    => esc_html__( 'Choose the order for the related posts.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the related shortcode if there are no specific order parameters for the shortcode.', 'foxiz' ),
					'options'     => [
						'rand'      => esc_html__( 'Random', 'foxiz' ),
						'relevance' => esc_html__( 'Relevance', 'foxiz' ),
						'date'      => esc_html__( 'Last Published', 'foxiz' ),
						'modified'  => esc_html__( 'Last Updated', 'foxiz' ),
					],
					'default'     => 'rand',
				],
				[
					'id'       => 'single_post_related_total',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Number of Posts', 'foxiz' ),
					'subtitle' => esc_html__( 'Select number of posts to show at once.', 'foxiz' ),
					'default'  => 4,
				],
				[
					'id'     => 'section_end_single_post_related',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_post_related_template',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Related Template', 'foxiz' ),
					'notice' => [
						esc_html__( 'Select "Use Ruby Template" under the "Related Section" setting if you use Ruby Template shortcode.', 'foxiz' ),
						esc_html__( 'Ensure "Use WP Global Query" under "Template Builder - Global Query > Query Mode" has been set in a block of your template to help the system understand that it is related section.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'          => 'single_post_related_shortcode',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Add a ruby template shortcode you would like to display for this section.', 'foxiz' ),
					'placeholder' => '[Ruby_E_Template id="1"]',
				],
				[
					'id'     => 'section_end_single_post_related_template',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_post_related_blog',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Standard Related Layout', 'foxiz' ),
					'notice' => [
						esc_html__( 'The settings below will not be available if you use Ruby Template to build this section.', 'foxiz' ),
						esc_html__( 'Select "Standard Layout" under the "Related Section" setting if you use settings below.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'single_post_related_blog_heading',
					'type'     => 'text',
					'title'    => esc_html__( 'Related Heading', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a heading for this section. Leave -1 to disable this heading.', 'foxiz' ),
					'default'  => 'You Might Also Like',
				],
				[
					'id'       => 'single_post_related_blog_heading_layout',
					'title'    => esc_html__( 'Heading Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a heading layout for the heading.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_heading_layout( true ),
					'default'  => '0',
				],
				[
					'id'       => 'single_post_related_heading_tag',
					'title'    => esc_html__( 'Heading HTML Tag', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a HTML tag for this heading.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_heading_tag(),
					'default'  => '0',
				],
				[
					'id'       => 'single_post_related_blog_heading_size',
					'title'    => esc_html__( 'Heading Font Size (Desktop)', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a custom font size value for this heading (in pixels) on the desktop. Leave this option blank to set the default.', 'foxiz' ),
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'default'  => '',
				],
				[
					'id'       => 'single_post_related_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Blog Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select blog listing layout for the related section.', 'foxiz' ),
					'options'  => [
						'grid_1'       => [
							'img'   => foxiz_get_asset_image( 'grid-1.jpg' ),
							'title' => esc_html__( 'Grid 1', 'foxiz' ),
						],
						'grid_2'       => [
							'img'   => foxiz_get_asset_image( 'grid-1.jpg' ),
							'title' => esc_html__( 'Grid 2', 'foxiz' ),
						],
						'grid_box_1'   => [
							'img'   => foxiz_get_asset_image( 'grid-box-1.jpg' ),
							'title' => esc_html__( 'Boxed Grid 1', 'foxiz' ),
						],
						'grid_box_2'   => [
							'img'   => foxiz_get_asset_image( 'grid-box-2.jpg' ),
							'title' => esc_html__( 'Boxed Grid 2', 'foxiz' ),
						],
						'grid_small_1' => [
							'img'   => foxiz_get_asset_image( 'grid-small-1.jpg' ),
							'title' => esc_html__( 'Small Grid', 'foxiz' ),
						],
					],
					'default'  => 'grid_small_1',
				],

				[
					'id'       => 'single_post_related_pagination',
					'title'    => esc_html__( 'Pagination Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a pagination type for the related section.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'               => esc_html__( '- Disable -', 'foxiz' ),
						'next_prev'       => esc_html__( 'Next Prev', 'foxiz' ),
						'load_more'       => esc_html__( 'Show More', 'foxiz' ),
						'infinite_scroll' => esc_html__( 'Infinite Scroll', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_single_post_related_blog',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_inline_related',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Inner Content Related', 'foxiz' ),
					'notice' => [
						esc_html__( 'Automatically add a related section into post content after x paragraph(s). To use the Shortcode, read the documentation "Related Box Shortcode" for further information.', 'foxiz' ),
						esc_html__( 'If you manually added related content via Gutenberg block or inline shortcode for a post, this box will be disabled in that post to ensure you have full control.', 'foxiz' ),
						esc_html__( 'The related box will not appear on post content with a number of paragraphs less than this setting.', 'foxiz' ),
						esc_html__( 'NOTE: Avoid placing a Ruby Template in this section with "WP Global Query" enabled, as it may prevent the page from loading properly.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'          => 'single_post_inline_related',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Related Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Create your related box using the [ruby_related] shortcode.', 'foxiz' ),
					'desc'        => esc_html__( 'For further shortcode information, please read: https://help.themeruby.com/foxiz/related-box-shortcode/', 'foxiz' ),
					'placeholder' => '[ruby_related heading="More Read" total="5" layout="1" where="all" order="rand"]',
				],
				[
					'id'          => 'single_post_inline_related_pos',
					'type'        => 'text',
					'title'       => esc_html__( 'Display Positions', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input positions (after x paragraphs) to display the related box.', 'foxiz' ),
					'description' => esc_html__( 'Allow multiple positions, separate positions by commas, e.g., 5, 8.', 'foxiz' ),
					'placeholder' => '5, 8',
					'default'     => '5',
				],
				[
					'id'     => 'section_end_single_inline_related',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_post_popular',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Popular Section', 'foxiz' ),
					'subtitle' => esc_html__( 'Display a template at the bottom of single post page. Choose popular in your block query settings to show popular posts.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_popular_shortcode',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Add a ruby template shortcode you would like to display for this section.', 'foxiz' ),
					'description' => esc_html__( 'Leave blank if you would like to disable it.', 'foxiz' ),
					'placeholder' => '[Ruby_E_Template id="1"]',
				],
				[
					'id'     => 'section_end_single_post_popular',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_ajax' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_ajax() {

		return [
			'title'      => esc_html__( 'Auto Load Next Posts', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_ajax',
			'desc'       => esc_html__( 'Customize the ajax load next posts feature.', 'foxiz' ),
			'icon'       => 'el el-refresh',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'single_post_ajax_next_post',
					'type'     => 'switch',
					'title'    => esc_html__( 'Auto Load Next Posts', 'foxiz' ),
					'subtitle' => esc_html__( 'Turn on or off the AJAX loading of the next post when visitors reach the end of the post content.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'single_post_ajax_limit',
					'type'        => 'text',
					'title'       => esc_html__( 'Limit Total Posts', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set a maximum limit for the total number of posts loaded using the "Auto Load Next Posts" feature while scrolling.', 'foxiz' ),
					'placeholder' => '10',
					'default'     => 20,
				],
				[
					'id'       => 'ajax_next_button',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show Continue Reading', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide a part of content of next posts and show the button to increase page views.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'ajax_next_button_offset',
					'type'        => 'text',
					'title'       => esc_html__( 'Continue Reading Offset', 'foxiz' ),
					'subtitle'    => esc_html__( 'Define the number of posts that should appear in full content before showing the "Continue Reading" button.', 'foxiz' ),
					'description' => esc_html__( 'Leave blank to set show button in all next posts.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'ajax_next_cat',
					'type'     => 'switch',
					'title'    => esc_html__( 'Same Categories', 'foxiz' ),
					'subtitle' => esc_html__( 'Only load posts which has same categories with the current post.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'ajax_next_sidebar_name',
					'type'     => 'select',
					'title'    => esc_html__( 'Assign a Sidebar', 'foxiz' ),
					'subtitle' => esc_html__( 'Assign a special sidebar for all next load posts, Recommended use simple or advert content.', 'foxiz' ),
					'options'  => foxiz_config_sidebar_name(),
					'default'  => 'foxiz_sidebar_default',
				],
				[
					'id'       => 'ajax_next_hide_sidebar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Hide Sidebar on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide the post sidebar on mobile devices when load next posts.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'ajax_next_comment_button',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show/Hide Comment Button', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the button show/hide comment box when load next posts.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'ajax_next_crawler',
					'type'     => 'switch',
					'title'    => esc_html__( 'Load Next Posts for Crawlers', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the next post if the user is a crawler or bot.', 'foxiz' ),
					'default'  => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_highlight' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_highlight() {

		return [
			'title'      => esc_html__( 'Highlight Shares', 'foxiz' ),
			'id'         => 'foxiz_config_section_highlight_share',
			'desc'       => esc_html__( 'Show the popup shares bar when the user highlight text in the post content.', 'foxiz' ),
			'icon'       => 'el el-share-alt',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_highlight_post_type',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The settings below will also apply to custom post type as podcast and others.', 'foxiz' ),
				],
				[
					'id'       => 'single_post_highlight_shares',
					'type'     => 'switch',
					'title'    => esc_html__( 'Highlight Shares', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the highlight shares feature.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_highlight_share_facebook',
					'type'     => 'switch',
					'title'    => esc_html__( 'Share on Facebook', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Facebook.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_highlight_share_twitter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Share on Twitter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Twitter.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_post_highlight_share_reddit',
					'type'     => 'switch',
					'title'    => esc_html__( 'Share on Reddit', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Reddit.', 'foxiz' ),
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_header' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_header() {

		return [
			'title'      => esc_html__( 'Site Header', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_header',
			'desc'       => esc_html__( 'Customize the site header for the single post.', 'foxiz' ),
			'icon'       => 'el el-th',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'single_post_header_settings_notice',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit for individual posts, navigate to "Posts > Edit > Post Settings > Site Header".', 'foxiz' ),
				],
				[
					'id'       => 'section_start_single_font_resizer',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Font Resizer', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply only to the predefined header. Use the "Foxiz - Header Font Resizer" block if you use a template.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_font_resizer',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reading Font Resizer', 'foxiz' ),
					'subtitle' => esc_html__( 'Show an icon that allows the visitors can change the font size of single content.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_single_font_resizer',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_header',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Site Header', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below are treated as global settings. Individual post settings take priority over them.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_header_style',
					'type'        => 'select',
					'title'       => esc_html__( 'Header Layout', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a site header for the single post.', 'foxiz' ),
					'options'     => foxiz_config_header_style( true, true ),
					'description' => esc_html__( 'The transparent headers are only suited for layouts: Standard 2, Video 2 and Audio 2', 'foxiz' ),
					'default'     => '0',
				],
				[
					'id'          => 'single_post_header_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for displaying as the site header.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override on the "Header Layout" setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_header',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_header_video',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Site Header for Video', 'foxiz' ),
					'notice' => [
						esc_html__( 'This setting will apply to the video post format.', 'foxiz' ),
						esc_html__( 'It is treated as a global setting for the video format. Individual post settings take priority over them.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'          => 'single_post_header_template_video',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set a custom site header template for the video post.', 'foxiz' ),
					'description' => esc_html__( 'Leave the input blank to disable it.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_header_video',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_header_audio',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Site Header for Audio', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply to the audio post format.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_header_template_audio',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set a custom site header template for the audio post.', 'foxiz' ),
					'description' => esc_html__( 'Leave the input blank to disable it.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_header_audio',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_header_gallery',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Site Header for Gallery', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply to the gallery post format.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'single_post_header_template_gallery',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set a custom site header template for the gallery post.', 'foxiz' ),
					'description' => esc_html__( 'Leave the input blank to disable it.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_header_gallery',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_sticky' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_post_sticky() {

		return [
			'title'      => esc_html__( 'Sticky Headline', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_sticky',
			'desc'       => esc_html__( 'Sticky the single heading and share on social icons when scrolling down.', 'foxiz' ),
			'icon'       => 'el el-arrow-down',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_sticky_headline_post_type',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The settings below will also apply to custom post type as podcast and others.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_single_sticky',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'General', 'foxiz' ),
					'notice' => [
						esc_html__( 'Set the option "Set as Main Menu" in the "Main Navigation" Elementor block to "Yes" if you are using a template for your header if you enable this option.', 'foxiz' ),
						esc_html__( 'Ensure that there is enough spacing for the headline if you are using a header template.', 'foxiz' ),
						esc_html__( 'Ensure that the Sticky Header is enabled in "Edit Section > Foxiz - for Header Template > Sticky Header" if you are using a header template.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'single_post_sticky_title',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sticky Headline', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable sticky the single post headline (post title).', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_single_sticky',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_sticky_share',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Share on Socials', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'share_sticky',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show Share Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on social icons in this bar.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_sticky_label',
					'type'     => 'switch',
					'title'    => esc_html__( 'Show Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the left label.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_sticky_color',
					'type'     => 'switch',
					'title'    => esc_html__( 'Colorful Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable color for social icons.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_single_sticky_share',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_sticky_socials',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'social Icons', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'share_sticky_facebook',
					'type'     => 'switch',
					'title'    => esc_html__( 'Facebook', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Facebook.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_sticky_twitter',
					'type'     => 'switch',
					'title'    => esc_html__( 'Twitter', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Twitter.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_sticky_flipboard',
					'type'     => 'switch',
					'title'    => esc_html__( 'Flipboard', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Flipboard.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_pinterest',
					'type'     => 'switch',
					'title'    => esc_html__( 'Pinterest', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Pinterest.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_whatsapp',
					'type'     => 'switch',
					'title'    => esc_html__( 'WhatsApp', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on WhatsApp.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_linkedin',
					'type'     => 'switch',
					'title'    => esc_html__( 'LinkedIn', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on LinkedIn.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_tumblr',
					'type'     => 'switch',
					'title'    => esc_html__( 'Tumblr', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Tumblr.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_reddit',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reddit', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Reddit.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_vk',
					'type'     => 'switch',
					'title'    => esc_html__( 'Vkontakte', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Vkontakte.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_telegram',
					'type'     => 'switch',
					'title'    => esc_html__( 'Telegram', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Telegram.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_threads',
					'type'     => 'switch',
					'title'    => esc_html__( 'Threads', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Threads.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_email',
					'type'     => 'switch',
					'title'    => esc_html__( 'Email', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable share on Email.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'share_sticky_copy',
					'type'     => 'switch',
					'title'    => esc_html__( 'Copy Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the copy post link icon.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'share_sticky_print',
					'type'     => 'switch',
					'title'    => esc_html__( 'Print', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the print button.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'share_sticky_native',
					'type'        => 'switch',
					'title'       => esc_html__( 'Native Share', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the native browser share button.', 'foxiz' ),
					'description' => esc_html__( 'The button will only appear on supported browsers.', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'     => 'section_end_single_sticky_social',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_reading_indicator' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_single_reading_indicator() {

		return [
			'title'      => esc_html__( 'Reading Indicator', 'foxiz' ),
			'id'         => 'foxiz_config_section_sp_indicator.',
			'desc'       => esc_html__( 'Display the reading indicator bar at the top site.', 'foxiz' ),
			'icon'       => 'el el-road',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_sticky_indicator_post_type',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The settings below will also apply to custom post type as podcast and others.', 'foxiz' ),
				],
				[
					'id'       => 'single_post_reading_indicator',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reading Indicator', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the reading indicator bar.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'reading_indicator_height',
					'type'     => 'text',
					'title'    => esc_html__( 'Bar Height', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a custom height value (in pixels) for this bar. Leave blank to set it as the default.', 'foxiz' ),
					'class'    => 'small-text',
					'default'  => '',
				],
				[
					'id'          => 'reading_indicator_color',
					'type'        => 'color_gradient',
					'title'       => esc_html__( 'Bar Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for this bar.', 'foxiz' ),
					'validate'    => 'color',
					'transparent' => false,
					'default'     => [
						'from' => '',
						'to'   => '',
					],
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_featured' ) ) {
	function foxiz_register_options_single_post_featured() {

		return [
			'id'         => 'foxiz_config_section_single_featured',
			'title'      => esc_html__( 'Featured Image', 'foxiz' ),
			'icon'       => 'el el-picture',
			'desc'       => esc_html__( 'Customize the featured image size.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_single_lazyload',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Performance > Images" to set up lazy loading for the single featured image and content images.', 'foxiz' ),
				],
				[
					'id'       => 'single_crop_size',
					'title'    => esc_html__( 'Featured Image Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a crop size for the feature image to display in the single post.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_crop_size(),
					'default'  => '0',
				],
				[
					'id'       => 'single_post_featured_lightbox',
					'type'     => 'switch',
					'title'    => esc_html__( 'Featured Lightbox', 'foxiz' ),
					'subtitle' => esc_html__( 'Show a popup when clicking on the featured image.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'auto_video_featured',
					'title'    => esc_html__( 'Auto Featured Image from Video Platforms', 'foxiz' ),
					'subtitle' => esc_html__( 'Automatically fetch images from YouTube, Vimeo, and Dailymotion to set them as the featured image for the video post format if the featured image is not set.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'          => 'single_post_caption_fallback',
					'type'        => 'select',
					'title'       => esc_html__( 'Featured Caption Fallback', 'foxiz' ),
					'subtitle'    => esc_html__( 'Choose a fallback caption type for the featured image.', 'foxiz' ),
					'description' => esc_html__( 'Select "No Fallback" if you do not want to use the image caption when the caption text setting is empty.', 'foxiz' ),
					'options'     => [
						'0'    => esc_html__( '- Fallback -', 'foxiz' ),
						'none' => esc_html__( 'No Fallback', 'foxiz' ),
					],
					'default'     => '0',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_single_post_types' ) ) {
	function foxiz_register_options_single_post_types() {

		$fields = [
			[
				'id'       => 'section_start_post_type',
				'type'     => 'section',
				'class'    => 'ruby-section-start',
				'title'    => esc_html__( 'Global Layout', 'foxiz' ),
				'subtitle' => esc_html__( 'If you create post types via the "Custom Post Type UI" or "ACF" plugins, Panel settings for individual post types will appear under this section.', 'foxiz' ),
				'indent'   => true,
			],
			[
				'id'          => 'post_type_layout',
				'type'        => 'image_select',
				'title'       => esc_html__( 'Predefined Layout', 'foxiz' ),
				'subtitle'    => esc_html__( 'Select a default layout for all custom post types, excluding default post and podcast post types.', 'foxiz' ),
				'description' => esc_html__( 'These settings will apply to all post types.', 'foxiz' ),
				'options'     => foxiz_config_single_standard_layouts( false ),
				'default'     => 'standard_8',
			],
			[
				'id'          => 'post_type_template',
				'type'        => 'textarea',
				'rows'        => 2,
				'title'       => esc_html__( 'or Use Ruby Template for the Single Post', 'foxiz' ),
				'subtitle'    => esc_html__( 'Use a Ruby template to build a single layout instead of a predefined template.', 'foxiz' ),
				'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
				'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
				'default'     => '',
			],
			[
				'id'     => 'section_end_post_type',
				'type'   => 'section',
				'class'  => 'ruby-section-end',
				'indent' => false,
			],
		];

		$custom_post_types = apply_filters( 'ruby_post_types_config', [] );
		if ( ! empty( $custom_post_types ) && is_array( $custom_post_types ) ) {
			$count             = count( $custom_post_types );
			$index             = 0;
			$section_end_class = 'ruby-section-end';

			foreach ( $custom_post_types as $key => $post_type ) {
				$index ++;
				$label = ! empty( $post_type['label'] ) ? $post_type['label'] : $key;
				if ( $count === $index ) {
					$section_end_class = 'ruby-section-end no-border';
				}
				$fields[] = [
					'id'     => 'section_start_post_type_' . $key,
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Post Type: ', 'foxiz' ) . $label,
					'indent' => true,
				];
				$fields[] = [
					'id'       => 'post_type_layout_' . $key,
					'type'     => 'image_select',
					'title'    => esc_html__( 'Predefined Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a default single layout for the post type: ', 'foxiz' ) . $label,
					'options'  => foxiz_config_single_standard_layouts(),
					'default'  => 'default',
				];
				$fields[] = [
					'id'          => 'post_type_template_' . $key,
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'or Use Ruby Template for the Single Post', 'foxiz' ),
					'subtitle'    => esc_html__( 'Use a Ruby template to build a custom layout instead of a predefined template.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the Predefined layout above. Leave blank to use the Predefined Layout setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'default'     => '',
				];
				$fields[] = [
					'id'          => 'post_type_tax_' . $key,
					'type'        => 'text',
					'title'       => esc_html__( 'Set Main Taxonomy', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input the taxonomy slug/name/key you created via code or a 3rd party plugin. It is the string after "...wp-admin/edit-tags.php?taxonomy=" when you are on the edit page of the taxonomy.', 'foxiz' ),
					'description' => esc_html__( 'Ensure the assigned taxonomy belongs to the post type.', 'foxiz' ),
					'placeholder' => 'category',
					'default'     => '',
				];
				$fields[] = [
					'id'          => 'single_post_entry_meta_keys_' . $key,
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Single Entry Meta Keys', 'foxiz' ),
					'subtitle'    => esc_html__( 'This setting is more flexible and allows you to show custom tax and custom fields for this post type alongside default values such as author and date...', 'foxiz' ),
					'placeholder' => esc_html__( 'author, date', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the single post page and override the "Single Post > Entry Meta" settings.', 'foxiz' ),
					'default'     => '',
				];
				$fields[] = [
					'id'          => $key . '_archive_header',
					'title'       => esc_html__( 'Archive Header', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a header layout for the archive of this post type.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the main archive page of this post type if you enable the "Has archive" option using a third-party plugin.', 'foxiz' ),
					'type'        => 'select',
					'options'     => foxiz_config_archive_header( true ),
					'default'     => '0',
				];
				$fields[] = [
					'id'          => $key . '_archive_template_global',
					'title'       => esc_html__( 'Archive Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a "Ruby Template" shortcode to display as the main blog posts for the root archive of this post type. For example: [Ruby_E_Template id="1"]', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the main archive page of this post type if you enable the "Has archive" option using a third-party plugin.', 'foxiz' ),
					'type'        => 'textarea',
					'rows'        => 2,
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				];
				$fields[] = [
					'id'       => $key . '_archive_posts_per_page',
					'title'    => esc_html__( 'Archive Posts per Page', 'foxiz' ),
					'subtitle' => esc_html__( 'Input posts per page for this archive post type.', 'foxiz' ),
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'default'  => '',
				];
				$fields[] = [
					'id'     => 'section_end_post_type_' . $key,
					'type'   => 'section',
					'class'  => $section_end_class,
					'indent' => false,
				];
			}
		}

		return [
			'title'  => esc_html__( 'Post Types', 'foxiz' ),
			'id'     => 'foxiz_config_section_post_types_layout',
			'desc'   => esc_html__( 'The settings below enable you to create templates for single posts of any post type created with third-party plugins such as Custom Post Type UI and Advanced Custom Fields (ACF). The theme will utilize default settings from the Single Post panels for post content and other sections.', 'foxiz' ),
			'icon'   => 'el el-file',
			'fields' => $fields,
		];
	}
}