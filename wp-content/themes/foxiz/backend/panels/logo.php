<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_logo' ) ) {
	function foxiz_register_options_logo() {

		return [
			'id'    => 'foxiz_config_section_site_logo',
			'title' => esc_html__( 'Logo', 'foxiz' ),
			'icon'  => 'el el-barcode',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_logo_global' ) ) {
	function foxiz_register_options_logo_global() {

		return [
			'id'         => 'foxiz_config_section_global_logo',
			'title'      => esc_html__( 'Default Logos', 'foxiz' ),
			'desc'       => esc_html__( 'Upload logos for you website.', 'foxiz' ),
			'icon'       => 'el el-laptop',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_add_favicon',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Go to "Settings > General Settings > Site Icon" to easily add your site icon (favicon).', 'foxiz' ),
				],
				[
					'id'    => 'info_add_logo_dark',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Ensure that dark mode logos are configured when enabling dark mode for your site.', 'foxiz' ),
				],
				[
					'id'    => 'template_logo_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The logo settings may not apply to the Header Template. Edit the header with Elementor to configure the logo block if your website uses a header template.', 'foxiz' ),
				],
				[
					'id'          => 'logo',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Main Logo', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select or upload the main logo for your site.', 'foxiz' ),
					'description' => esc_html__( 'For optimal display, use a retina-ready logo with a recommended height of 120px, which is twice the height of its wrapper.', 'foxiz' ),
				],
				[
					'id'          => 'dark_logo',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Dark Mode - Main Logo', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select or upload the logo for your site’s dark mode.', 'foxiz' ),
					'description' => esc_html__( 'This logo should match the main logo but with colors adjusted to contrast well with a dark mode header background.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_logo_mobile' ) ) {
	function foxiz_register_options_logo_mobile() {

		return [
			'id'         => 'foxiz_config_section_mobile_logo',
			'title'      => esc_html__( 'Mobile Logos', 'foxiz' ),
			'desc'       => esc_html__( 'Customize the mobile logos.', 'foxiz' ),
			'icon'       => 'el el-iphone-home',
			'subsection' => true,
			'fields'     => [
				[
					'id'          => 'mobile_logo',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Mobile Logo', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload a retina logo for displaying on mobile devices.', 'foxiz' ),
					'description' => esc_html__( 'For optimal display, use a retina-ready logo with a recommended height of 84px, which is twice the height of its wrapper.', 'foxiz' ),
				],
				[
					'id'       => 'dark_mobile_logo',
					'type'     => 'media',
					'url'      => true,
					'preview'  => true,
					'title'    => esc_html__( 'Dark Mode - Mobile Logo', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload a retina logo for displaying on mobile devices in dark mode.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_logo_transparent' ) ) {
	function foxiz_register_options_logo_transparent() {

		return [
			'id'         => 'foxiz_config_section_transparent_logo',
			'title'      => esc_html__( 'Transparent Logos', 'foxiz' ),
			'desc'       => esc_html__( 'Upload and manage logos for transparent headers.', 'foxiz' ),
			'icon'       => 'el el-photo',
			'subsection' => true,
			'fields'     => [
				[
					'id'          => 'transparent_logo',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Transparent Logo', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload a light logo for transparent headers, if necessary, for pages using a transparent header.', 'foxiz' ),
					'description' => esc_html__( 'For optimal display, use a retina-ready logo with a recommended height of 120px, which is twice the height of its wrapper.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_logo_organization' ) ) {
	function foxiz_register_options_logo_organization() {

		return [
			'id'         => 'foxiz_config_section_organization_logo',
			'title'      => esc_html__( 'Organization Logo', 'foxiz' ),
			'desc'       => esc_html__( 'This logo is for schema markup. If your main logo uses light colors, a dark logo is recommended for this setting.', 'foxiz' ),
			'icon'       => 'el el-photo',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'logo_seo_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'IMPORTANT: The "Main Logo" or  Organization Logo (1st Priority) setting is crucial for schema data markup. Please ensure this setting is properly configured.', 'foxiz' ),
				],
				[
					'id'          => 'logo_organization',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Organization Logo', 'foxiz' ),
					'subtitle'    => esc_html__( 'This logo will appear on social media when sharing the front page and in search results.', 'foxiz' ),
					'description' => esc_html__( 'Leave this field blank to use the main logo as the organization logo.', 'foxiz' ),
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_logo_favicon' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_logo_favicon() {

		return [
			'id'         => 'foxiz_config_section_logo_favicon',
			'title'      => esc_html__( 'Bookmarklet', 'foxiz' ),
			'desc'       => esc_html__( 'Select or upload bookmarklet icons for iOS and Android devices.', 'foxiz' ),
			'icon'       => 'el el-bookmark',
			'subsection' => true,
			'fields'     => [
				[
					'id'          => 'icon_touch_apple',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'iOS Bookmarklet Icon', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload an icon for the Apple touch.', 'foxiz' ),
					'description' => esc_html__( 'The recommended image size is 72 x 72px', 'foxiz' ),
				],
				[
					'id'          => 'icon_touch_metro',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Metro UI Bookmarklet Icon', 'foxiz' ),
					'description' => esc_html__( 'The recommended image size is 144 x 144px', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload icon for the Metro interface.', 'foxiz' ),
				],
			],
		];
	}
}
