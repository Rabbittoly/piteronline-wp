<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_dark_mode' ) ) {
	function foxiz_register_options_dark_mode() {

		return [
			'id'     => 'foxiz_config_section_dark_mode',
			'title'  => esc_html__( 'Dark Mode', 'foxiz' ),
			'desc'   => esc_html__( 'Customize dark mode for your website.', 'foxiz' ),
			'icon'   => 'el el-adjust',
			'fields' => [
				[
					'id'    => 'dark_mode_logo_notice',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'Consider providing dark logos, colors, and background settings if you use dark mode.', 'foxiz' ),
				],
				[
					'id'    => 'dark_mode_notice',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can set custom dark mode background in "Global Colors > Dark Mode Background".', 'foxiz' ),
				],
				[
					'id'     => 'section_start_dark_mode',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Global', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'dark_mode',
					'title'       => esc_html__( 'Dark Mode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select settings for the dark mode.', 'foxiz' ),
					'description' => esc_html__( 'In browser mode, switching modes not be allowed. However, you need to set up colors and data for both light and dark modes.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'0'       => esc_html__( 'Disable Dark Mode', 'foxiz' ),
						'1'       => esc_html__( 'Light/Dark Switchable', 'foxiz' ),
						'dark'    => esc_html__( 'Dark Mode Only', 'foxiz' ),
						'browser' => esc_html__( 'Based on Browser', 'foxiz' ),
					],
					'default'     => '0',
				],
				[
					'id'     => 'section_end_dark_mode',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_dark_mode_switchable',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Light/Dark Switchable', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'dark_mode_cookie',
					'title'       => esc_html__( 'Preventing Dark Mode Flickering', 'foxiz' ),
					'subtitle'    => esc_html__( 'Use cookies or JS function after Body tag to prevent background flickering during page load.', 'foxiz' ),
					'description' => esc_html__( 'The theme uses localStorage as the default for dark mode to reduce server usage.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'0' => esc_html__( 'Default (Footer Script)', 'foxiz' ),
						'2' => esc_html__( 'Move JS Function after Body Tag', 'foxiz' ),
						'1' => esc_html__( 'Use Cookies Method', 'foxiz' ),
					],
					'default'     => '2',
				],
				[
					'id'          => 'first_visit_mode',
					'title'       => esc_html__( 'Mode First Time Visit', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color scheme for your website when users visit your site at the first time.', 'foxiz' ),
					'description' => esc_html__( 'Based on browser will set the website\'s color scheme to either light or dark mode based on the user\'s browser settings on their first visit.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'default' => esc_html__( 'Light', 'foxiz' ),
						'dark'    => esc_html__( 'Dark', 'foxiz' ),
						'browser' => esc_html__( 'Based on Browser', 'foxiz' ),
					],
					'default'     => 'default',
				],
				[
					'id'     => 'section_end_dark_mode_switchable',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_dark_mode_image',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Style', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'dark_mode_image_opacity',
					'title'    => esc_html__( 'Image Opacity', 'foxiz' ),
					'subtitle' => esc_html__( 'Reduce the featured image opacity when enabled dark mode.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'          => 'dark_mode_size',
					'title'       => esc_html__( 'Switcher Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom size for the dark mode switcher (in pixels).', 'foxiz' ),
					'description' => esc_html__( 'The default value is 24px.', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => '24',
					'validate'    => 'numeric',
					'default'     => '',
				],
				[
					'id'     => 'section_end_dark_mode_image',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}