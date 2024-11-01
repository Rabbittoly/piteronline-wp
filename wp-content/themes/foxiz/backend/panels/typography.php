<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

/** typo config */
if ( ! function_exists( 'foxiz_register_options_typo' ) ) {
	function foxiz_register_options_typo() {

		return [
			'id'    => 'foxiz_config_section_typo',
			'title' => esc_html__( 'Typography', 'foxiz' ),
			'icon'  => 'el el-font',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_body' ) ) {
	function foxiz_register_options_typo_body() {

		return [
			'id'         => 'foxiz_config_section_typo_body',
			'title'      => esc_html__( 'Website Body', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for your site. The settings below apply to almost content elements on the website.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_body',
					'type'           => 'typography',
					'title'          => esc_html__( 'Body Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'The settings below apply to almost post and page content on your site.', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set; the minimum value will be 1.4 of the font size. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_body_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the body on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_body_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the body on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_excerpt' ) ) {
	function foxiz_register_options_typo_excerpt() {

		return [
			'id'         => 'foxiz_config_section_typo_excerpt',
			'title'      => esc_html__( 'Entry Excerpt', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font sizes and colors for the excerpt in the post listing.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'typo_excerpt_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The font family and other font values will base on the "Body Font" settings.', 'foxiz' ),
				],
				[
					'id'          => 'excerpt_color',
					'title'       => esc_html__( 'Excerpt Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for post excerpt text.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the default mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_excerpt_color',
					'title'       => esc_html__( 'Dark Mode - Excerpt Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for post excerpt text.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to dark mode and overlay layouts.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'             => 'font_excerpt',
					'type'           => 'typography',
					'title'          => esc_html__( 'Excerpt Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Custom font for excerpt and entry summary.', 'foxiz' ),
					'description'    => esc_html__( 'Leave blank to set as the defaults (body font).', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'font-size'      => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_excerpt_size',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the excerpt. Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_excerpt_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the excerpt on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_excerpt_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the excerpt on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h1' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h1() {

		return [
			'id'         => 'foxiz_config_section_typo_h1',
			'title'      => esc_html__( 'H1 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H1 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h1',
					'type'           => 'typography',
					'title'          => esc_html__( 'H1 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H1 tag and [ CSS classname: .h1]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h1_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h1_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h2' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h2() {

		return [
			'id'         => 'foxiz_config_section_typo_h2',
			'title'      => esc_html__( 'H2 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H2 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h2',
					'type'           => 'typography',
					'title'          => esc_html__( 'H2 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H2 tag and [ CSS classname: .h2]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h2_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h2_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h3' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h3() {

		return [
			'id'         => 'foxiz_config_section_typo_h3',
			'title'      => esc_html__( 'H3 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H3 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h3',
					'type'           => 'typography',
					'title'          => esc_html__( 'H3 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H3 tag and [ CSS classname: .h3]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h3_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h3_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h4' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h4() {

		return [
			'id'         => 'foxiz_config_section_typo_h4',
			'title'      => esc_html__( 'H4 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H4 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h4',
					'type'           => 'typography',
					'title'          => esc_html__( 'H4 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H4 tag and [ CSS classname: .h4]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h4_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h4_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h5' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h5() {

		return [
			'id'         => 'foxiz_config_section_typo_h5',
			'title'      => esc_html__( 'H5 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H5 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h5',
					'type'           => 'typography',
					'title'          => esc_html__( 'H5 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H5 tag and [ CSS classname: .h5]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h5_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h5_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_h6' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_h6() {

		return [
			'id'         => 'foxiz_config_section_typo_h6',
			'title'      => esc_html__( 'H6 Tag', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the H6 tag. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_h6',
					'type'           => 'typography',
					'title'          => esc_html__( 'H6 Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the H6 tag and [ CSS classname: .h6]', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_h6_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_h6_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for this heading tag on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_category' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_category() {

		return [
			'id'         => 'foxiz_config_section_typo_category',
			'title'      => esc_html__( 'Entry Category', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the entry category (category icon) in the post listing.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_ecat',
					'type'           => 'typography',
					'title'          => esc_html__( 'Entry Category Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for the entry category element.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_ecat_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the entry category element on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_ecat_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the entry category element on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_meta' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_meta() {

		return [
			'id'         => 'foxiz_config_section_typo_meta',
			'title'      => esc_html__( 'Entry Meta', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the post entry meta. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_font_emeta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta Font', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_emeta',
					'type'           => 'typography',
					'title'          => esc_html__( 'Entry Meta Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for entry meta info: date, view, comment... Those options will not apply to the author meta.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-size'      => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
				],
				[
					'id'          => 'dark_emeta_color',
					'title'       => esc_html__( 'Dark Mode - Entry Meta Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for entry meta in dark mode.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to dark mode and overlay layouts.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'             => 'font_eauthor',
					'type'           => 'typography',
					'title'          => esc_html__( 'Important Meta Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the author, category meta, review description and sponsored brand.', 'foxiz' ),
					'description'    => esc_html__( 'The font size will use the post entry meta font size setting.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-size'      => false,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'          => 'dark_eauthor_color',
					'title'       => esc_html__( 'Dark Mode - Important Meta Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the information entry meta in dark mode.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to dark mode and overlay layouts.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_font_emeta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_font_emeta_size',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Responsive Font Sizes', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'font_emeta_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for entry meta info on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_emeta_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for entry meta info on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_emeta_size',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_readmore' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_readmore() {

		return [
			'id'         => 'foxiz_config_section_typo_readmore',
			'title'      => esc_html__( 'Read More Button', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the read more button.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_readmore',
					'type'           => 'typography',
					'title'          => esc_html__( 'Read More Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the read more button', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-size'      => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
				],
				[
					'id'       => 'font_readmore_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the readmore button on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_readmore_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the readmore button on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_input' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_input() {

		return [
			'id'         => 'foxiz_config_section_typo_input',
			'title'      => esc_html__( 'Inputs & Button', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font for the input, textarea and button. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_font_input',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Input Font', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_input',
					'type'           => 'typography',
					'title'          => esc_html__( 'Input & Textarea Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'The settings below apply to the input and textarea form on your site.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_input_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for input and textarea on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_input_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for input and textarea on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_input',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_font_button',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Button Font', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_button',
					'type'           => 'typography',
					'title'          => esc_html__( 'Button Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'The settings below apply to all button on your site.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_button_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for buttons on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_button_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for buttons on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_button',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_single' ) ) {
	/**
	 * @return array
	 * single typography
	 */
	function foxiz_register_options_typo_single() {

		return [
			'id'         => 'foxiz_config_section_typo_single',
			'title'      => esc_html__( 'Single Post', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for Single Headline (Single Post Title) and Single Tagline (Single Sub Title).', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_font_single_headline',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Single Headline Fonts (Post Title)', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'    => 'typo_single_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'The single headline will use H1 font settings as the default. You can override the settings in this panel.', 'foxiz' ),
				],
				[
					'id'             => 'font_headline',
					'type'           => 'typography',
					'title'          => esc_html__( 'Full Width Headline', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the single headline for displaying in the full width layouts.', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_headline_size_content',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Content Font Size (Small Headline)', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single headline or displaying in 66.67% of the site width.', 'foxiz' ),
				],
				[
					'id'       => 'font_headline_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single headline on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_headline_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single headline on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_single_headline',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_font_single_tagline',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Single Tagline Fonts (Sub Title)', 'foxiz' ),
					'subtitle' => esc_html__( 'The single tagline will use the H2 tag font settings. You can override settings in this panel.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'             => 'font_tagline',
					'type'           => 'typography',
					'title'          => esc_html__( 'Full Width Tagline', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the single tagline for displaying in the full width layouts.', 'foxiz' ),
					'description'    => esc_html__( 'The line height value requires that the font size be set. Leave it blank for the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_tagline_size_content',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Content Font Size (Small Tagline)', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single tagline or displaying in 66.67% of the site width.', 'foxiz' ),
				],
				[
					'id'       => 'font_tagline_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single tagline on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_tagline_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the single tagline on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_tagline',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_font_single_quote',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Block Quote', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_quote',
					'type'           => 'typography',
					'title'          => esc_html__( 'Block Quote', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font values for the block quote.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'font-weight'    => true,
					'font-size'      => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'     => 'section_end_font_quote',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_font_post_pagi',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Next/Prev Post Pagination', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_epagi',
					'type'           => 'typography',
					'title'          => esc_html__( 'Next/Previous Post Pagination Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font settings for the post titles in the next/previous link navigation.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_epagi_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the next/previous link navigation on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_epagi_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the next/previous link navigation on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_font_post_pagi',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_breadcrumb' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_breadcrumb() {

		return [
			'id'         => 'foxiz_config_section_typo_breadcrumb',
			'title'      => esc_html__( 'Breadcrumb', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the breadcrumb bar. Selecting same font family settings for similar elements will optimize for your site speed.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_breadcrumb',
					'type'           => 'typography',
					'title'          => esc_html__( 'Breadcrumb Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'The settings below apply to the breadcrumb bar.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'          => 'breadcrumb_color',
					'title'       => esc_html__( 'Breadcrumb Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the breadcrumb bar.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to the default mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_breadcrumb_color',
					'title'       => esc_html__( 'Dark Mode - Breadcrumb Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the breadcrumb bar in dark mode.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'       => 'font_breadcrumb_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the breadcrumbs bar on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_breadcrumb_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the breadcrumbs bar on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_menus' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_menus() {

		return [
			'id'         => 'foxiz_config_section_typo_main_menu',
			'title'      => esc_html__( 'Header Menus', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for menus on the website header.', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_section_font_main_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Main Menu', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_main_menu',
					'type'           => 'typography',
					'title'          => esc_html__( 'Menu Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for top menu items for displaying in the main menu.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'             => 'font_main_sub_menu',
					'type'           => 'typography',
					'title'          => esc_html__( 'Sub-Level Menu Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for sub-level menu items for displaying in the main menu.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'     => 'section_end_section_font_main_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_section_font_mobile_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Mobile Menu', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_mobile_menu',
					'type'           => 'typography',
					'title'          => esc_html__( 'Mobile Menu Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for top menu items for displaying in the mobile menu.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'             => 'font_mobile_sub_menu',
					'type'           => 'typography',
					'title'          => esc_html__( 'Sub-Level Mobile Menu Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for sub-level menu items for displaying in the mobile menu.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'     => 'section_end_section_font_mobile_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_section_font_quick_access_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Mobile Quick Access', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_quick_access_menu',
					'type'           => 'typography',
					'title'          => esc_html__( 'Mobile Quick Access Menu Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for mobile quick access menu items for displaying on mobile devices.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'     => 'section_end_section_font_quick_access_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_heading' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_heading() {

		return [
			'id'         => 'foxiz_config_section_typo_block_heading',
			'title'      => esc_html__( 'Block Heading', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the block heading.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_heading',
					'type'           => 'typography',
					'title'          => esc_html__( 'Heading Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Heading tag will use default H tag font settings. Select font values if you would like to choose a custom font.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_heading_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the heading block on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_heading_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the heading block on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'             => 'font_sub_heading',
					'type'           => 'typography',
					'title'          => esc_html__( 'Tagline Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'Tagline tag will use default H6 tag font settings. Select font values if you would like to choose a custom font.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => true,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_widget_menu' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_widget_menu() {

		return [
			'id'         => 'foxiz_config_section_typo_widget_menu',
			'title'      => esc_html__( 'Archive & Menu Widgets', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the default widgets, apply to the archive, category widgets...', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_section_font_widgets',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Sidebar Archive & Menu Widgets', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'             => 'font_widget',
					'type'           => 'typography',
					'title'          => esc_html__( 'Default Widgets', 'foxiz' ),
					'subtitle'       => esc_html__( 'Select font for the default archives, categories and menu fonts.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'font-weight'    => true,
					'line-height'    => false,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_widget_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for buttons on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_widget_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for buttons on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
				[
					'id'     => 'section_end_section_font_widgets',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_toc' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_toc() {

		return [
			'id'         => 'foxiz_config_section_typo_toc',
			'title'      => esc_html__( 'Table of Contents', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'desc'       => esc_html__( 'Select font values for the table of contents.', 'foxiz' ),
			'fields'     => [
				[
					'id'             => 'font_toc',
					'type'           => 'typography',
					'title'          => esc_html__( 'Table of Contents Font', 'foxiz' ),
					'subtitle'       => esc_html__( 'The Table of Contents uses the default H5 tag font settings. Select custom font values below if you wish to override the default.', 'foxiz' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => false,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'font-weight'    => true,
					'font-size'      => true,
					'units'          => 'px',
					'default'        => [],
				],
				[
					'id'       => 'font_toc_size_tablet',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the Table of Contents on tablet devices (max screen width: 1024px). Leave this option blank to use the default value.', 'foxiz' ),
				],
				[
					'id'       => 'font_toc_size_mobile',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a font size (in pixels) for the Table of Contents on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
				],
			],
		];
	}
}
