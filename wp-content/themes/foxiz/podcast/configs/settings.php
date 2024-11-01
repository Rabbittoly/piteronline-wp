<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_register_options_podcast' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast() {

		return [
			'id'    => 'foxiz_config_section_podcast',
			'title' => esc_html__( 'Ruby Podcast', 'foxiz' ),
			'icon'  => 'el el-mic',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_podcast_general' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast_general() {

		return [
			'id'         => 'foxiz_config_section_podcast_general',
			'title'      => esc_html__( 'General', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the podcast post type.', 'foxiz' ),
			'icon'       => 'el el-laptop',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'section_start_podcast_supported',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Podcast Post Type', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will not delete exist podcast data in the database.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'podcast_supported',
					'title'    => esc_html__( 'Support Podcast', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the podcast post type in your website.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'     => 'section_end_podcast_supported',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_slugs',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Permalinks', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'podcast_slug',
					'title'       => esc_html__( 'Podcast Slug', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom slug for the single podcast.', 'foxiz' ),
					'description' => esc_html__( 'Default is "podcast".', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => 'podcast',
					'default'     => '',
				],
				[
					'id'          => 'series_slug',
					'title'       => esc_html__( 'Show Slug', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom slug for the show.', 'foxiz' ),
					'description' => esc_html__( 'Default is "series".', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => 'series',
					'default'     => '',
				],
				[
					'id'     => 'section_end_podcast_slugs',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_podcast_display',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Displays', 'foxiz' ),
					'subtitle' => esc_html__( 'Allow the podcast episodes to appear on the blog and archives pages.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'podcast_blog_included',
					'title'    => esc_html__( 'Include Podcast in The Blog', 'foxiz' ),
					'subtitle' => esc_html__( 'The podcast episodes will appear on the blog page (home index).', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'       => 'podcast_author_included',
					'title'    => esc_html__( 'Include Podcast in The Author', 'foxiz' ),
					'subtitle' => esc_html__( 'The podcast episodes will appear on the author pages.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'       => 'podcast_tag_included',
					'title'    => esc_html__( 'Include Podcast in The Tag', 'foxiz' ),
					'subtitle' => esc_html__( 'The podcast episodes will appear on the tag pages.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'     => 'section_end_podcast_display',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_meta_icons',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'podcast_meta_play_label',
					'title'    => esc_html__( 'Play/Pause Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the "Play/Pause" text after the play meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'       => 'podcast_meta_author_label',
					'title'    => esc_html__( '"By" Host Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the "By" text before the host author meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'podcast_meta_duration_label',
					'title'    => esc_html__( 'Duration Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show the "Duration" text before the duration meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'     => 'section_end_podcast_meta_icons',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_inline_ad',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Inline Content Ads', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'podcast_inline_ad',
					'type'     => 'switch',
					'title'    => esc_html__( 'Inline Content Ads Supported', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable inline content ads for the single podcast.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_podcast_inline_ad',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_seo',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'SEO Optimized', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_markup',
					'type'     => 'switch',
					'title'    => esc_html__( 'Episode Markup', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the default schema markup for the episode.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_podcast_seo',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_podcast_design' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast_design() {

		return [
			'id'         => 'foxiz_config_section_podcast_styles',
			'title'      => esc_html__( 'Style', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the layout and style for the podcast post type.', 'foxiz' ),
			'icon'       => 'el el-heart',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'section_start_podcast_player',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Podcast Player', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings bellow will apply the self-hosted audio player.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'podcast_player_color',
					'title'       => esc_html__( 'Player Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the self hosted podcast layer.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_podcast_player_color',
					'title'       => esc_html__( 'Dark Mode - Player Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the self hosted podcast layer in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_podcast_player',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_podcast_icon',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Featured Image Icons', 'foxiz' ),
					'notice' => [
						esc_html__( 'The settings bellow will apply only to the search, tags and archive pages.', 'foxiz' ),
						esc_html__( 'The microphone icon will appear on the featured image.', 'foxiz' ),
					],
					'indent'   => true,
				],
				[
					'id'       => 'podcast_socials_overlay',
					'type'     => 'switch',
					'title'    => esc_html__( 'Social Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'The listen on socials will appear on the featured image.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'podcast_custom_icon',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Custom Podcast SVG', 'foxiz' ),
					'subtitle'    => esc_html__( 'Override the microphone icon with a SVG icon.', 'foxiz' ),
					'description' => esc_html__( 'Enable the option in "Theme Design > SVG Upload > SVG Supported" to upload SVG icons.', 'foxiz' ),
				],
				[
					'id'          => 'podcast_custom_icon_size',
					'title'       => esc_html__( 'SVG Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom font size (in px) for your SVG icon.', 'foxiz' ),
					'placeholder' => '28',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'          => 'podcast_custom_size',
					'title'       => esc_html__( 'Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom font size for the podcast icon.', 'foxiz' ),
					'placeholder' => '48',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'          => 'podcast_icon_bg',
					'title'       => esc_html__( 'Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the podcast icon.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'podcast_icon_color',
					'title'       => esc_html__( 'Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the mic icon.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_podcast_icon',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_readmore',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Read More', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'podcast_readmore_label',
					'title'       => esc_html__( 'Read More Label', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input the read more label for the podcast post type.', 'foxiz' ),
					'placeholder' => 'View Episode',
					'type'        => 'text',
					'default'     => 'View Episode',
				],
				[
					'id'     => 'section_end_podcast_readmore',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_podcast_single' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast_single() {

		return [
			'id'         => 'foxiz_config_section_podcast_single',
			'title'      => esc_html__( 'Episode', 'foxiz' ),
			'desc'       => esc_html__( 'Manage layout and styles for the episode (single podcast).', 'foxiz' ),
			'icon'       => 'el el-list-alt',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_single_podcast_layout',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The settings are treated as a global setting. Individual episode settings take priority over them.', 'foxiz' ),
				],
				[
					'id'    => 'info_single_podcast_settings',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The single episode will common use some global "Single Post" settings.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_single_podcast_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Layout', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Episode Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a layout for the episode.', 'foxiz' ),
					'options'  => [
						'1' => [
							'img'   => foxiz_get_asset_image( 'single-6.png' ),
							'title' => esc_html__( 'Layout 1', 'foxiz' ),
						],
						'2' => [
							'img'   => foxiz_get_asset_image( 'single-7.png' ),
							'title' => esc_html__( 'Layout 2', 'foxiz' ),
						],
					],
					'default'  => '1',
				],
				[
					'id'       => 'single_podcast_audio_autoplay',
					'type'     => 'switch',
					'title'    => esc_html__( 'Autoplay Audio', 'foxiz' ),
					'subtitle' => esc_html__( 'Autoplay the audio file.', 'foxiz' ),
					'desc'     => esc_html__( 'This setting may not work in some browsers due to the autoplay policies.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_single_podcast_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_podcast_category',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Show', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_entry_category',
					'type'     => 'select',
					'title'    => esc_html__( 'Entry Show', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable entry show info in the episode.', 'foxiz' ),
					'options'  => foxiz_config_extended_entry_category(),
					'default'  => 'bg-1,big',
				],
				[
					'id'     => 'section_end_single_podcast_category',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_single_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_avatar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Big Avatar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the author avatars before the entry meta bar.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'single_podcast_entry_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Entry Meta Tags', 'foxiz' ),
					'subtitle' => esc_html__( 'Organize how you want the entry meta info to appear in the episode.', 'foxiz' ),
					'options'  => [
						'avatar'   => esc_html__( 'avatar (Avatar)', 'foxiz' ),
						'author'   => esc_html__( 'author (Author)', 'foxiz' ),
						'date'     => esc_html__( 'date (Publish Date)', 'foxiz' ),
						'category' => esc_html__( 'category (Categories)', 'foxiz' ),
						'tag'      => esc_html__( 'tag (Tags)', 'foxiz' ),
						'view'     => esc_html__( 'view (Post Views)', 'foxiz' ),
						'comment'  => esc_html__( 'comment (Comments)', 'foxiz' ),
						'update'   => esc_html__( 'update  (Last Updated)', 'foxiz' ),
						'read'     => esc_html__( 'read (Reading Time)', 'foxiz' ),
						'like'     => esc_html__( 'like (Like/Dislike)', 'foxiz' ),
						'custom'   => esc_html__( 'custom (Custom)', 'foxiz' ),
						'duration' => esc_html__( 'duration (Duration)', 'foxiz' ),
						'index'    => esc_html__( 'index (Index)', 'foxiz' ),
					],
					'default'  => [ 'author', 'index' ],
				],
				[
					'id'          => 'single_podcast_entry_meta_keys',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'or Custom Key Input', 'foxiz' ),
					'subtitle'    => esc_html__( 'This setting is more flexible and allows you to show prefix, suffix, Taxonomy, and custom field value by keys, default keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom, duration, index].', 'foxiz' ),
					'description' => esc_html__( 'Prefix & Suffix: You can add a prefix or suffix to a meta using the following format: prefix {meta_key} suffix. For example: author, Categories: {category}, view. You can also allow inline HTML tags such as <i>, <span>, etc.', 'foxiz' ),
					'placeholder' => esc_html__( 'author, date', 'foxiz' ),
				],
				[
					'id'       => 'single_podcast_meta_author_label',
					'type'     => 'switch',
					'title'    => esc_html__( '"By" Host Label', 'foxiz' ),
					'subtitle' => esc_html__( 'Show "by" text before the host meta.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_podcast_author_job',
					'type'     => 'switch',
					'title'    => esc_html__( 'Host Job', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the author job info.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'single_podcast_meta_date_label',
					'type'        => 'switch',
					'title'       => esc_html__( '"Published & Updated" Date Label', 'foxiz' ),
					'subtitle'    => esc_html__( 'Show the label text before the date meta.', 'foxiz' ),
					'description' => esc_html__( 'The label only applies if you disable human time (ago format) to avoid SEO issues.', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'       => 'single_podcast_tablet_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on tablet devices.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => [],
				],
				[
					'id'       => 'single_podcast_mobile_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on mobile devices.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => [],
				],
				[
					'id'     => 'section_end_podcast_single_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_single_updated',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Last Updated Date', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_updated_meta',
					'type'     => 'switch',
					'title'    => esc_html__( 'Last Updated Date', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the last updated meta.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'single_podcast_update_format',
					'type'        => 'text',
					'title'       => esc_html__( 'Date Format', 'foxiz' ),
					'subtitle'    => esc_html__( 'Custom date format for the last updated meta.', 'foxiz' ),
					'placeholder' => 'Y/m/d \a\t g:i A',
					'default'     => '',
				],
				[
					'id'     => 'section_end_podcast_single_updated',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_single_read',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Reading Time', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_min_read',
					'type'     => 'switch',
					'title'    => esc_html__( 'Reading Time', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the reading time meta.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_podcast_single_read',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_podcast_sidebar',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Sidebar Area', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'single_podcast_sidebar_name',
					'type'        => 'select',
					'title'       => esc_html__( 'Assign a Sidebar', 'foxiz' ),
					'subtitle'    => esc_html__( 'Assign a sidebar if you select a episode layout which has a sidebar.', 'foxiz' ),
					'description' => esc_html__( 'You can set a sidebar for individual episodes in the post editor.', 'foxiz' ),
					'options'     => foxiz_config_sidebar_name( false ),
					'default'     => 'foxiz_sidebar_default',
				],
				[
					'id'       => 'single_podcast_sidebar_position',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Sidebar Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a position for the episode sidebar.', 'foxiz' ),
					'options'  => foxiz_config_sidebar_position(),
					'default'  => 'default',
				],
				[
					'id'       => 'single_podcast_sticky_sidebar',
					'type'     => 'select',
					'title'    => esc_html__( 'Sticky Sidebar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the sticky sidebar for the episode.', 'foxiz' ),
					'options'  => [
						'0'  => esc_html__( '- Default -', 'foxiz' ),
						'1'  => esc_html__( 'Sticky Sidebar', 'foxiz' ),
						'2'  => esc_html__( 'Sticky Last Widget', 'foxiz' ),
						'-1' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_single_podcast_sidebar',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_podcast_header',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Site Header', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_podcast_header_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Header Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a site header for the episode.', 'foxiz' ),
					'options'  => foxiz_config_header_style( true ),
					'default'  => '0',
				],
				[
					'id'          => 'single_podcast_header_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for displaying as the site header.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override on the "Header Layout" setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_single_podcast_header',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_single_podcast_breadcrumb',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'The setting below requests the Navxt plugin, Yoast SEO or Rank Math SEO breadcrumbs to run.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'single_podcast_breadcrumb',
					'title'    => esc_html__( 'Episode Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb bar in the episode.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'     => 'section_end_single_podcast_breadcrumb',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_podcast_show' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast_show() {

		$prefix = 'series_';

		return [
			'id'         => 'foxiz_config_section_show',
			'title'      => esc_html__( 'Show', 'foxiz' ),
			'desc'       => esc_html__( 'Manage layout and styles for the show (podcast category).', 'foxiz' ),
			'icon'       => 'el el-folder-open',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => $prefix . 'settings_notice',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit for individual show, navigate to "Admin Dashboard > Ruby Podcast > Shows > Edit".', 'foxiz' ),
				],
				[
					'id'    => $prefix . 'show_layout_notice',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'As default, The grid 3 columns layout will be applied to the shows. You can build a template to assign for the shows in the settings below.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_series_site_header',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Site Header', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => $prefix . 'header_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Header Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a website header layout for the shows.', 'foxiz' ),
					'options'  => foxiz_config_header_style( true, true ),
					'default'  => '0',
				],
				[
					'id'          => $prefix . 'nav_style',
					'type'        => 'select',
					'title'       => esc_html__( 'Navigation Bar Style', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select navigation bar style for the site header in the shows.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply only to pre-defined headers: 1, 2, 3 and 5.', 'foxiz' ),
					'options'     => [
						'0'        => esc_html__( 'Default', 'foxiz' ),
						'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
						'border'   => esc_html__( 'Bottom Border', 'foxiz' ),
						'd-border' => esc_html__( 'Dark Bottom Border', 'foxiz' ),
						'none'     => esc_html__( 'None', 'foxiz' ),
					],
					'default'     => '0',
				],
				[
					'id'          => $prefix . 'header_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Header Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode for displaying as the website header for the shows.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override on the "Header Layout" setting, Leave it blank if you would like to use the "Header Layout" setting.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_series_site_header',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_series_header',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Show Header', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => $prefix . 'category_header',
					'title'    => esc_html__( 'Show Header', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable or select a show header layout for the shows.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_category_header(),
					'default'  => '2',
				],
				[
					'id'       => $prefix . 'pattern',
					'title'    => esc_html__( 'Header Background Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a background style for the show header.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_archive_header_bg(),
					'default'  => 'dot',
				],
				[
					'id'       => $prefix . 'rss',
					'title'    => esc_html__( 'RSS Subscribe', 'foxiz' ),
					'subtitle' => esc_html__( 'Show a RSS subscribe button in the show header.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
				[
					'id'     => 'section_end_series_header',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_series_template_global',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Global Listing Template', 'foxiz' ),
					'subtitle' => esc_html__( 'Ensure "Use WP Global Query" under "Template Builder - Global Query > Query Mode" has been set in a block of your template to help the system understand that it is the main query.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => $prefix . 'template_global',
					'title'       => esc_html__( 'Use Ruby Template for the Post Listing', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a "Ruby Template" shortcode to display as the main blog posts, e.g: [Ruby_E_Template id="1"]', 'foxiz' ),
					'description' => esc_html__( 'This setting will be overridden by "Use Ruby Template for the Post Listing" in the individual show setting panel.', 'foxiz' ),
					'type'        => 'textarea',
					'rows'        => '2',
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				],
				[
					'id'     => 'section_end_series_template_global',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_series_posts_per_page',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Posts per Page', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => $prefix . 'posts_per_page',
					'title'       => esc_html__( 'Posts per Page', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select posts per page for the shows.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override on "Dashboard > Settings > Reading > Blog pages show at most" setting. It will also apply to the "Use Ruby Template for the Post Listing".', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'validate'    => 'numeric',
					'default'     => '',
				],
				[
					'id'       => $prefix . 'order_by',
					'title'    => esc_html__( 'Order Episodes', 'foxiz' ),
					'subtitle' => esc_html__( 'Order by episode index for the shows.', 'foxiz' ),
					'info'     => esc_html__( 'As default, the show will order episodes by publish date.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0'               => esc_html__( '- Default -', 'foxiz' ),
						'post_index'      => esc_html__( 'Episode Index ASC', 'foxiz' ),
						'post_index_desc' => esc_html__( 'Episode Index DECS', 'foxiz' ),
					],
					'std'      => '0',
				],
				[
					'id'     => 'section_end_series_posts_per_page',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_podcast_archive' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_podcast_archive() {

		$prefix = 'podcast_archive_';

		return [
			'id'         => 'foxiz_config_section_podcast_archive',
			'title'      => esc_html__( 'Archives', 'foxiz' ),
			'desc'       => esc_html__( 'Manage layout and styles for the podcast archives.', 'foxiz' ),
			'icon'       => 'el el-inbox-box',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => $prefix . 'layout_notice',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'As default, The grid 3 columns layout will be applied to the archives. You can build a template to assign for the archives in the settings below.', 'foxiz' ),
				],
				[
					'id'    => 'section_start_podcast_archive_header',
					'type'  => 'section',
					'class' => 'ruby-section-start',
					'title' => esc_html__( 'Archive Header', 'foxiz' ),

					'indent' => true,
				],
				[
					'id'       => $prefix . 'pattern',
					'title'    => esc_html__( 'Header Background Style', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a background style for the podcast archive header.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_archive_header_bg(),
					'default'  => 'dot',
				],
				[
					'id'     => 'section_end_podcast_archive_header',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_podcast_archive_template',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Global Listing Template', 'foxiz' ),
					'subtitle' => esc_html__( 'Ensure "Use WP Global Query" under "Template Builder - Global Query > Query Mode" has been set in a block of your template to help the system understand that it is the main query.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => $prefix . 'template_global',
					'title'       => esc_html__( 'Use Ruby Template for the Post Listing', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a "Ruby Template" shortcode to display as the main blog posts, e.g: [Ruby_E_Template id="1"]', 'foxiz' ),
					'description' => esc_html__( 'This setting will be overridden by "Use Ruby Template for the Post Listing" in the individual show setting panel.', 'foxiz' ),
					'type'        => 'textarea',
					'rows'        => '2',
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				],
				[
					'id'     => 'section_end_podcast_archive_template',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_podcast_archive_total',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Posts per Page', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => $prefix . 'posts_per_page',
					'title'       => esc_html__( 'Posts per Page', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select posts per page for the shows.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override on "Dashboard > Settings > Reading > Blog pages show at most" setting. It will also apply to the "Use Ruby Template for the Post Listing".', 'foxiz' ),
					'type'        => 'text',
					'class'       => 'small-text',
					'validate'    => 'numeric',
					'default'     => '',
				],
				[
					'id'     => 'section_end_podcast_archive_total',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}