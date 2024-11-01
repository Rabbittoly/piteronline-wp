<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_design' ) ) {
	function foxiz_register_options_design() {

		return [
			'id'    => 'foxiz_config_section_design',
			'title' => esc_html__( 'Theme Design', 'foxiz' ),
			'icon'  => 'el el-idea',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_category' ) ) {
	function foxiz_register_options_design_category() {

		return [
			'id'         => 'foxiz_config_section_design_category',
			'title'      => esc_html__( 'Entry Category', 'foxiz' ),
			'desc'       => esc_html__( 'The category label display in the post listing.', 'foxiz' ),
			'icon'       => 'el el-folder-open',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'category_color_notice',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit color for individual categories, navigate to "Admin Dashboard > Posts > Categories > Edit".', 'foxiz' ),
				],
				[
					'id'       => 'section_start_entry_category_limit',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Limit Number of Category Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below apply to all post listings on your website. You can configure settings for individual posts under "Single Post > Entry Category".', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'max_categories',
					'title'       => esc_html__( 'Max Category Icons if choose Category', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter a value to limit the number of entry categories or taxonomies shown on all post listing layouts.', 'foxiz' ),
					'description' => esc_html__( 'This is useful when you have multiple categories per post and have not set a primary category in the post settings. Leave it blank to display all categories.', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'          => 'max_post_tags',
					'title'       => esc_html__( 'Max Category Icons if Choose Post Tags', 'foxiz' ),
					'subtitle'    => esc_html__( 'In case you replace the category with post tags for the category icon, set the value to limit the number of tags.', 'foxiz' ),
					'description' => esc_html__( 'This is useful when you have multiple tags per post and have not set a primary tag in the post settings. Leave it blank to display all tags.', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '1',
				],
				[
					'id'     => 'section_end_entry_category_limit',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_entry_category_color',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Category Colors', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'category_highlight_color',
					'title'       => esc_html__( 'Highlight Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a highlight color for the entry category to display in the post listing.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'category_color',
					'title'       => esc_html__( 'Accent Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select an accent (text) color for the entry category to display in the post listing. Leave blank to set it as the default.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'category_dark_highlight_color',
					'title'       => esc_html__( 'Dark Mode - Highlight Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a highlight color for the entry category to display in the post listing in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'category_dark_color',
					'title'       => esc_html__( 'Dark Mode - Accent Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select an accent (text) color for the entry category to display in the post listing in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'     => 'section_end_entry_category_color',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_meta' ) ) {
	/**
	 * @return array
	 * post entry meta
	 */
	function foxiz_register_options_design_meta() {

		return [
			'id'         => 'foxiz_config_section_meta_style',
			'title'      => esc_html__( 'Entry Meta', 'foxiz' ),
			'desc'       => esc_html__( 'These are small elements that display in the post listing e.g: author name, date, total views, total comments...', 'foxiz' ),
			'icon'       => 'el el-adjust-alt',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_view_meta',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The view meta requests the "Post Views Counter" plugin to run.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_meta_divider',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Meta Divider', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'meta_divider',
					'title'    => esc_html__( 'Divider Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a divider style between entry metas.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'default'   => esc_html__( 'Vertical Line', 'foxiz' ),
						'line'      => esc_html__( 'Solid Line', 'foxiz' ),
						'gray-line' => esc_html__( 'Gray Solid Line', 'foxiz' ),
						'dot'       => esc_html__( 'Dot', 'foxiz' ),
						'gray-dot'  => esc_html__( 'Gray Dot', 'foxiz' ),
						'none'      => esc_html__( 'White Spacing', 'foxiz' ),
						'wrap'      => esc_html__( 'Line Wrap', 'foxiz' ),
					],
					'default'  => 'default',
				],
				[
					'id'     => 'section_end_meta_divider',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_entry_meta_limit',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Limit Number of Taxonomy Links', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'max_entry_meta',
					'title'       => esc_html__( 'Max Tags/Taxonomy Links', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a value to limit the number of taxonomy links displayed in the entry meta bar.', 'foxiz' ),
					'description' => esc_html__( 'This feature is useful when you have multiple tags, taxonomies per post. Enhancing the clarity of your blog layout.', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'     => 'section_end_entry_meta_limit',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_meta_icons',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta Icons', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'meta_author_label',
					'title'    => esc_html__( '"By" Author Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the "By" text before the post author meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'meta_date_icon',
					'title'    => esc_html__( 'Published Date Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the icon before the post date meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'meta_updated_icon',
					'title'    => esc_html__( 'Updated Date Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the clock icon before the post updated meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'meta_comment_icon',
					'title'    => esc_html__( 'Comment Meta Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the icon before the post comment meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'meta_view_icon',
					'title'    => esc_html__( 'Post View Meta Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the icon before the post view meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'          => 'meta_view_pretty_number',
					'title'       => esc_html__( 'Post Views in Readable Format', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable to display post views using "M" for million and "k" for thousand formats for better readability.', 'foxiz' ),
					'description' => esc_html__( 'If you are using the "Format Number" option for post views, it is recommended to disable this setting.', 'foxiz' ),
					'type'        => 'switch',
					'default'     => true,
				],
				[
					'id'       => 'meta_read_icon',
					'title'    => esc_html__( 'Reading Time Meta Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the icon before the reading time meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'meta_category_icon',
					'title'    => esc_html__( 'Category Meta Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the icon before the post category meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'     => 'section_end_meta_icons',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_reading_speed',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Reading Speed', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'read_speed',
					'title'    => esc_html__( 'Words per Minute', 'foxiz' ),
					'subtitle' => esc_html__( 'Input number of words per minute to calculate the reading time. Default is 130', 'foxiz' ),
					'type'     => 'text',
					'default'  => 130,
				],
				[
					'id'     => 'section_end_reading_speed',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_human_time',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Human Time', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'human_time',
					'title'    => esc_html__( 'Display Human Time (Ago)', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the human time format ("ago") for the data post entry meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => '0',
				],
				[
					'id'     => 'section_end_human_time',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_edit_link',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Edit Post Link', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'edit_post_link',
					'title'    => esc_html__( 'Edit Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Display the edit post link for the logged users on the featured image.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'     => 'section_end_edit_link',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_meta_custom' ) ) {
	/**
	 * @return array
	 * post entry meta
	 */
	function foxiz_register_options_design_meta_custom() {

		return [
			'id'         => 'foxiz_config_section_meta_custom',
			'title'      => esc_html__( 'Custom Meta', 'foxiz' ),
			'desc'       => esc_html__( 'Create a new meta to display in the blog post listing.', 'foxiz' ),
			'icon'       => 'el el-asterisk',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'meta_custom_text',
					'title'    => esc_html__( 'Meta Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the label for this meta.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '',
				],
				[
					'id'          => 'meta_custom_icon',
					'title'       => esc_html__( 'Meta Icon ClassName', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your custom CSS icon classname to display at the beginning of the meta.', 'foxiz' ),
					'description' => esc_html__( 'If you use font Awesome. ensure that the setting in "Theme Design > Font Awesome" is enabled.', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => 'rbi-time',
					'default'     => '',
				],
				[
					'id'          => 'meta_custom_important',
					'type'        => 'switch',
					'title'       => esc_html__( 'Is Bold Meta', 'foxiz' ),
					'subtitle'    => esc_html__( 'Highlight this meta with bold font settings.', 'foxiz' ),
					'description' => esc_html__( 'The "Typography > Entry Meta > Important Meta Font" settings will apply to this meta.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'       => 'meta_custom_pos',
					'type'     => 'select',
					'title'    => esc_html__( 'Label Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a position for the custom meta label.', 'foxiz' ),
					'options'  => [
						'begin' => esc_html__( 'Prefix', 'foxiz' ),
						'end'   => esc_html__( 'Suffix', 'foxiz' ),
					],
					'default'  => 'end',
				],
				[
					'id'       => 'meta_custom_fallback',
					'type'     => 'select',
					'title'    => esc_html__( 'Fallback Meta', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a fallback meta if this meta value does not exist', 'foxiz' ),
					'options'  => [
						'author'   => esc_html__( 'author (Author)', 'foxiz' ),
						'date'     => esc_html__( 'date (Publish Date)', 'foxiz' ),
						'category' => esc_html__( 'category (Categories)', 'foxiz' ),
						'tag'      => esc_html__( 'tag (Tags)', 'foxiz' ),
						'view'     => esc_html__( 'view (Post Views)', 'foxiz' ),
						'comment'  => esc_html__( 'comment (Comments)', 'foxiz' ),
						'update'   => esc_html__( 'update  (Last Updated)', 'foxiz' ),
						'read'     => esc_html__( 'read (Reading Time)', 'foxiz' ),
						'0'        => esc_html__( 'None', 'foxiz' ),
					],
					'default'  => 'update',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_featured' ) ) {
	/**
	 * @return array
	 * featured image
	 */
	function foxiz_register_options_design_featured() {

		return [
			'id'         => 'foxiz_config_section_featured_image',
			'title'      => esc_html__( 'Featured Image', 'foxiz' ),
			'icon'       => 'el el-picture',
			'desc'       => esc_html__( 'Manage post featured images in your site.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'     => 'section_start_lazy_load',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'General', 'foxiz' ),
					'notice' => [
						esc_html__( 'Run regenerate thumbnail if you add/remove crop sizes. Please read documentation for further information.', 'foxiz' ),
						esc_html__( 'Assigning correct size for layouts is important for the look and give the best performance of your site..', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'crop_position',
					'type'     => 'select',
					'title'    => esc_html__( 'Crop Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select position to crop the featured image.', 'foxiz' ),
					'desc'     => esc_html__( 'Recommended select the top position if you have people images.', 'foxiz' ),
					'options'  => [
						'top'    => esc_html__( 'From The Top', 'foxiz' ),
						'center' => esc_html__( 'From The Center', 'foxiz' ),
					],
					'default'  => 'top',
				],
				[
					'id'         => 'featured_crop_sizes',
					'type'       => 'multi_text',
					'class'      => 'medium-text',
					'show_empty' => false,
					'title'      => esc_html__( 'Define Custom Crop Sizes', 'foxiz' ),
					'label'      => esc_html__( 'Add a Crop Size', 'foxiz' ),
					'subtitle'   => esc_html__( 'This option will help you optimize the site speed or increase image quality on your site.', 'foxiz' ),
					'desc'       => esc_html__( 'Input a custom crop size: width x height. e.g. 300x200', 'foxiz' ),
					'add_text'   => esc_html__( 'Create a New Crop Size', 'foxiz' ),
					'default'    => [],
				],
				[
					'id'          => 'default_ratio',
					'type'        => 'text',
					'title'       => esc_html__( 'Default Featured Ratio', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter a custom ratio as a percentage in the format (height/width * 100) to fit your images. Default is 60', 'foxiz' ),
					'desc'        => esc_html__( 'This setting will apply to the whole website, and it can be overridden by the custom ratio values in the block settings.', 'foxiz' ),
					'placeholder' => '60',
				],
				[
					'id'       => 'edit_link',
					'type'     => 'switch',
					'title'    => esc_html__( 'Edit Post Link', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the edit post link in the featured image for logged users.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_lazy_load',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_feat_size',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Theme Crop Sizes', 'foxiz' ),
					'notice' => [
						esc_html__( 'WordPress will crop uploaded images to ensure your site use the best image size for the blog layouts.', 'foxiz' ),
						esc_html__( 'Below is the list of image sizes. Enable or disable any size you would like.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'foxiz_crop_g1',
					'type'     => 'switch',
					'title'    => esc_html__( 'G1- 330x220', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this image crop size.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'foxiz_crop_g2',
					'type'     => 'switch',
					'title'    => esc_html__( 'G2 - 420x280', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this image crop size.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'foxiz_crop_g3',
					'type'     => 'switch',
					'title'    => esc_html__( 'G3 - 615x410', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this image crop size.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'foxiz_crop_o1',
					'type'     => 'switch',
					'title'    => esc_html__( 'Original Ratio - 860x0', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this image crop size.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'foxiz_crop_o2',
					'type'     => 'switch',
					'title'    => esc_html__( 'Original Ratio - 1536x0', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable this image crop size.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_feat_size',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_slider' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_design_slider() {

		return [
			'id'         => 'foxiz_config_section_slider',
			'title'      => esc_html__( 'Slider Animation', 'foxiz' ),
			'desc'       => esc_html__( 'Select settings for post sliders on your site.', 'foxiz' ),
			'icon'       => 'el el-resize-horizontal',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'slider_play',
					'type'     => 'switch',
					'title'    => esc_html__( 'Auto Play Next Slides', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable autoplay for the sliders.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'slider_speed',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Auto Play Speed', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a custom time value to next a slide in milliseconds (default is 5000).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'slider_effect',
					'type'     => 'select',
					'title'    => esc_html__( 'Slide Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a effect for the sliders. This setting will be not available for the carousel mode.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( 'Slide', 'foxiz' ),
						'1' => esc_html__( 'Fade', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'slider_fmode',
					'type'     => 'switch',
					'title'    => esc_html__( 'Carousel Free Scroll', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable free mode when scrolling on the carousels.', 'foxiz' ),
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_format' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_design_format() {

		return [
			'id'         => 'foxiz_config_section_post_format',
			'title'      => esc_html__( 'Post Format Icons', 'foxiz' ),
			'desc'       => esc_html__( 'Select settings for your post entry meta.', 'foxiz' ),
			'icon'       => 'el el-record',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'post_icon_video',
					'title'    => esc_html__( 'Video Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable icon for the video post format.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'       => 'post_icon_gallery',
					'title'    => esc_html__( 'Gallery Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable icon for the gallery post format.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => '0',
				],
				[
					'id'       => 'post_icon_audio',
					'title'    => esc_html__( 'Audio Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable icon for the audio post format.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => '0',
				],
				[
					'id'          => 'icon_video_color',
					'title'       => esc_html__( 'Video Icon Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color value for the video icon. This setting will not apply to bottom right layout', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],

				[
					'id'          => 'icon_gallery_color',
					'title'       => esc_html__( 'Gallery Icon Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color value for the gallery icon. This setting will not apply to bottom right layout', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'icon_audio_color',
					'title'       => esc_html__( 'Audio Icon Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color value for the audio icon. This setting will not apply to bottom right layout.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_readmore' ) ) {
	/**
	 * @return array
	 * read more settings
	 */
	function foxiz_register_options_design_readmore() {

		return [
			'id'         => 'foxiz_config_section_readmore',
			'title'      => esc_html__( 'Read More Button', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the read more button in your site.', 'foxiz' ),
			'icon'       => 'el el-arrow-right',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_read_more',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can enable/disable the button via "Standard Block Design" panes or Elementor block settings.', 'foxiz' ),
				],
				[
					'id'    => 'info_read_more_typo',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Typography > Read More Button" to edit fonts for the button.', 'foxiz' ),
				],
				[
					'id'          => 'readmore_label',
					'type'        => 'text',
					'title'       => esc_html__( 'Read More Label', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input the read more label to display on your site.', 'foxiz' ),
					'placeholder' => esc_html__( 'Read More', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'readmore_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Button Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a style for the read more button.', 'foxiz' ),
					'options'  => [
						'0'      => esc_html__( '- Default -', 'foxiz' ),
						'simple' => esc_html__( 'Text Only', 'foxiz' ),
						'bg'     => esc_html__( 'Background', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'readmore_icon',
					'title'    => esc_html__( 'Read More Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Show a icon after the read more label.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_hover' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_design_hover() {

		return [
			'id'         => 'foxiz_config_section_design_hover',
			'title'      => esc_html__( 'Hover Effects', 'foxiz' ),
			'icon'       => 'el el-hand-up',
			'desc'       => esc_html__( 'Select hover effect settings for your site.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'section_start_title_hover_effect',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Title Hover Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'The color settings will be applied based on the chosen effect. Please note that some settings may not be applicable in certain effect styles.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'hover_effect',
					'title'    => esc_html__( 'Title Hover Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply to the post title.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Default (Color & Underline)', 'foxiz' ),
						'2' => esc_html__( 'Style 2 (Global Color)', 'foxiz' ),
						'3' => esc_html__( 'Style 3 (Dark Border)', 'foxiz' ),
						'4' => esc_html__( 'Style 4 (Big Border)', 'foxiz' ),
						'5' => esc_html__( 'Style 5 (Text Background)', 'foxiz' ),
						'6' => esc_html__( 'Style 6 (Underline)', 'foxiz' ),
						'7' => esc_html__( 'Style 7 (Solid Color Underline)', 'foxiz' ),
						'8' => esc_html__( 'Style 8 (Opacity)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'          => 'title_hover_text_color',
					'title'       => esc_html__( 'Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the title when hovering.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'title_hover_effect_color',
					'title'       => esc_html__( 'Effect Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the effect when hovering.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'dark_title_hover_text_color',
					'title'       => esc_html__( 'Dark Mode - Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the title when hovering in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'dark_title_hover_effect_color',
					'title'       => esc_html__( 'Dark Mode - Effect Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the effect when hovering in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'     => 'section_end_title_hover_effect',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_element_hover_effect',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Elements Hover Effect', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'menu_hover_effect',
					'type'     => 'select',
					'title'    => esc_html__( 'Menu Hover Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply to top level menu items.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( 'Default (Color Border)', 'foxiz' ),
						'2' => esc_html__( 'Style 2 (Opacity)', 'foxiz' ),
						'3' => esc_html__( 'Style 3 (Background)', 'foxiz' ),
						'4' => esc_html__( 'Style 4 (Underline)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'btn_hover_effect',
					'type'     => 'select',
					'title'    => esc_html__( 'Button Hover Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'This setting will apply to the button.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( 'Default (Blurry Shadow)', 'foxiz' ),
						'2' => esc_html__( 'Style 2 (Solid Shadow)', 'foxiz' ),
						'3' => esc_html__( 'Style 3 (Background)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'     => 'section_end_element_hover_effect',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_svg' ) ) {
	/**
	 * @return array
	 * font icons settings
	 */
	function foxiz_register_options_design_svg() {

		return [
			'id'         => 'foxiz_config_section_svg_supported',
			'title'      => esc_html__( 'SVG Upload', 'foxiz' ),
			'icon'       => 'el el-upload',
			'desc'       => esc_html__( 'Please ensure that you are using trusted svg sources to avoid XML vulnerabilities.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'svg_supported',
					'title'    => esc_html__( 'SVG Supported', 'foxiz' ),
					'subtitle' => esc_html__( 'Support upload file type SVG for your site.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_gif' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_design_gif() {

		return [
			'id'         => 'foxiz_config_section_gif_supported',
			'title'      => esc_html__( 'Featured GIF', 'foxiz' ),
			'icon'       => 'el el-photo',
			'desc'       => esc_html__( 'Prevent WordPress convert gif to a static image when uploading.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'gif_supported',
					'type'     => 'switch',
					'title'    => esc_html__( 'GIF Supported', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable GIF supported for your site.', 'foxiz' ),
					'default'  => '1',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_border' ) ) {
	/**
	 * @return array
	 * font icons settings
	 */
	function foxiz_register_options_design_border() {

		return [
			'id'         => 'foxiz_config_section_design_border',
			'title'      => esc_html__( 'Round Corner', 'foxiz' ),
			'icon'       => 'el el-record',
			'desc'       => esc_html__( 'The small border style in featured images and other element whole the website.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'design_border',
					'title'    => esc_html__( 'Round Corner', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable round corner style for whole the website.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'    => esc_html__( '- Default -', 'foxiz' ),
						'none' => esc_html__( 'No Border', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'custom_border',
					'class'       => 'small-text',
					'type'        => 'text',
					'title'       => esc_html__( 'Custom Round Corner', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom round corner (in px) for your site.', 'foxiz' ),
					'description' => esc_html__( 'The recommended value is 1 to 20. Leave blank to set it as the default.', 'foxiz' ),
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_icons' ) ) {
	/**
	 * @return array
	 * font icons settings
	 */
	function foxiz_register_options_design_icons() {

		return [
			'id'         => 'foxiz_config_section_font_icons',
			'title'      => esc_html__( 'Font Awesome', 'foxiz' ),
			'icon'       => 'el el-fontsize',
			'desc'       => esc_html__( 'Load FontAwesome library. The setting will help you can add FontAwesome icons anywhere in the site.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'fontawesome_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'This feature may affect to your site speed.', 'foxiz' ),
				],
				[
					'id'    => 'add_fontawesome_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can use the i tag to add a icon, e.g. <i class="fa-solid fa-house"></i>', 'foxiz' ),
				],
				[
					'id'    => 'fontawesome_url_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => html_entity_decode( esc_html__( 'To find icons, you can refer to <a href="//fontawesome.com/search?o=r&m=free" target="_blank" rel="nofollow">the Official website</a>', 'foxiz' ) ),
				],
				[
					'id'       => 'font_awesome',
					'title'    => esc_html__( 'Font Awesome', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable Font Awesome supported.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => '0',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_placeholder' ) ) {
	function foxiz_register_options_design_placeholder() {

		return [
			'id'         => 'foxiz_config_section_search_placeholder',
			'title'      => esc_html__( 'Search', 'foxiz' ),
			'icon'       => 'el el-search',
			'desc'       => esc_html__( 'These settings below will apply to all search forms.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'          => 'search_placeholder',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Placeholder Text', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a placeholder text for the search forms.', 'foxiz' ),
					'description' => esc_html__( 'Leave blank for the default.', 'foxiz' ),
					'placeholder' => esc_html__( 'Search Headlines, News...', 'foxiz' ),
				],
				[
					'id'          => 'header_search_custom_icon',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Custom Search SVG', 'foxiz' ),
					'subtitle'    => esc_html__( 'Override default search icon with a SVG icon.', 'foxiz' ),
					'description' => esc_html__( 'Enable the option in "Theme Design > SVG Upload > SVG Supported" if you cannot upload .SVG files.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_loader' ) ) {
	function foxiz_register_options_design_loader() {

		return [
			'id'         => 'foxiz_config_section_loader',
			'title'      => esc_html__( 'Loader Style', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the ajax loader icon.', 'foxiz' ),
			'icon'       => 'el el-repeat',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'loader_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Loader Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a style for the ajax loader icon.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '- Default -', 'foxiz' ),
						'2' => esc_html__( 'Thin Border', 'foxiz' ),
						'3' => esc_html__( 'Rectangle', 'foxiz' ),
						'4' => esc_html__( 'Dots', 'foxiz' ),
					],
					'default'  => '1',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_back_top' ) ) {
	function foxiz_register_options_design_back_top() {

		return [
			'id'         => 'foxiz_config_section_back_top',
			'title'      => esc_html__( 'Back to Top', 'foxiz' ),
			'icon'       => 'el el-arrow-up',
			'desc'       => esc_html__( 'Customize the back to top button.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'back_top',
					'type'     => 'switch',
					'title'    => esc_html__( 'Back to Top', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the back to top button at the bottom right.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'mobile_back_top',
					'type'        => 'switch',
					'title'       => esc_html__( 'Mobile Back to Top', 'foxiz' ),
					'subtitle'    => esc_html__( 'Show the back to top button on mobile devices.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply only if the back top setting is enabled.', 'foxiz' ),
					'default'     => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_tooltips' ) ) {
	function foxiz_register_options_design_tooltips() {

		return [
			'id'         => 'foxiz_config_section_tooltips',
			'title'      => esc_html__( 'Tooltips', 'foxiz' ),
			'icon'       => 'el el-question-sign',
			'desc'       => esc_html__( 'Manage tooltips for the share on socials.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'site_tooltips',
					'type'     => 'switch',
					'title'    => esc_html__( 'Tooltips', 'foxiz' ),
					'subtitle' => esc_html__( 'Show tooltips when you mouse over elements.', 'foxiz' ),
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_container' ) ) {
	function foxiz_register_options_design_container() {

		return [
			'id'         => 'foxiz_config_section_container',
			'title'      => esc_html__( 'Container Width', 'foxiz' ),
			'icon'       => 'el el-resize-horizontal',
			'desc'       => esc_html__( 'Customize the container width, default value is 1280px include left and right edge (20px*2).', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'container_width_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The recommended value is between 1040 and 1540, incorrect values may lead to website layout issues.', 'foxiz' ),
				],
				[
					'id'    => 'container_width_e_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'This setup globally affects the website, impacting predefined templates, except for Elementor pages and Ruby templates. Each of them has its own container settings for customizing width in specific sections or containers.', 'foxiz' ),
				],
				[
					'id'          => 'container_width',
					'title'       => esc_html__( 'Container Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a container width value (in px) for your website. Note that 40px edge padding is included.', 'foxiz' ),
					'description' => esc_html__( 'Use with caution; leave blank to use the default value.', 'foxiz' ),
					'placeholder' => '1280',
					'type'        => 'text',
					'class'       => 'small-text',
				],
				[
					'id'          => 'container_width_single',
					'title'       => esc_html__( 'Container Width for Single Post', 'foxiz' ),
					'subtitle'    => esc_html__( 'It is useful if you want narrower content width in single posts while keeping a wider layout on other pages such as the homepage.', 'foxiz' ),
					'description' => esc_html__( 'This setting applies only to predefined single posts, inside the header and footer.', 'foxiz' ),
					'placeholder' => '1280',
					'type'        => 'text',
					'class'       => 'small-text',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_input' ) ) {
	function foxiz_register_options_design_input() {

		return [
			'id'         => 'foxiz_config_section_input',
			'title'      => esc_html__( 'Input Style', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the appearance of input fields.', 'foxiz' ),
			'icon'       => 'el el-check-empty',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'input_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Input Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Choose the style for the input form.', 'foxiz' ),
					'options'  => [
						'0'    => esc_html__( 'Gray Background', 'foxiz' ),
						'gray' => esc_html__( 'Gray Border', 'foxiz' ),
						'bold' => esc_html__( 'Dark Border', 'foxiz' ),
					],
					'default'  => '0',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_design_live_blog' ) ) {
	function foxiz_register_options_design_live_blog() {

		return [
			'title'      => esc_html__( 'Live Blogging', 'foxiz' ),
			'id'         => 'foxiz_config_section_live_blog',
			'desc'       => esc_html__( 'Customize the live blogging posts.', 'foxiz' ),
			'icon'       => 'el el-comment-alt',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_live_blog_Color',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To customize the live blog colors, navigate to "Global Colors > Live Blogging" panel.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_live_blog_listing',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Post Listing', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'live_label',
					'type'     => 'text',
					'title'    => esc_html__( 'Live Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Define the label for the live blogging to be displayed before the post title in the listing.', 'foxiz' ),
					'default'  => foxiz_html__( 'Live: ', 'foxiz' ),
				],
				[
					'id'       => 'live_blog_meta',
					'title'    => esc_html__( 'Meta Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a style to showcase live blogging meta before the post title in the listing.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'dot'   => esc_html__( 'Dot', 'foxiz' ),
						'label' => esc_html__( 'Text Only', 'foxiz' ),
						'all'   => esc_html__( 'Dot & Text', 'foxiz' ),
					],
					'default'  => 'dot',
				],
				[
					'id'     => 'section_end_live_blog_listing',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_live_blog_single',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Single Post', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_live_label',
					'type'     => 'text',
					'title'    => esc_html__( 'Live Updates Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Define the label for Live Updates to appear at the top of the single post page (replaceable with a category icon).', 'foxiz' ),
					'default'  => foxiz_html__( 'Live Updates', 'foxiz' ),
				],
				[
					'id'          => 'live_blog_interval',
					'type'        => 'text',
					'title'       => esc_html__( 'Refresh Interval (s)', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input the refresh interval in seconds for live blogging posts, minimum value is 30 seconds.', 'foxiz' ),
					'description' => esc_html__( 'This setting allows visitors to avoid page reloading. A lower value will use more server resources.', 'foxiz' ),
					'default'     => '600',
				],
				[
					'id'     => 'section_end_live_blog_single',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}
