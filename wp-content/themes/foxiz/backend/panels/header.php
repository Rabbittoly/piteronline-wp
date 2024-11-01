<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_header' ) ) {
	function foxiz_register_options_header() {

		return [
			'id'    => 'foxiz_config_section_header',
			'title' => esc_html__( 'Header', 'foxiz' ),
			'icon'  => 'el el-th',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_general' ) ) {
	function foxiz_register_options_header_general() {

		return [
			'id'         => 'foxiz_config_section_header_general',
			'title'      => esc_html__( 'General', 'foxiz' ),
			'icon'       => 'el el-cog',
			'subsection' => true,
			'desc'       => esc_html__( 'Customize the layout and style for your site header.', 'foxiz' ),
			'fields'     => [
				[
					'id'     => 'section_start_header_style',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'General', 'foxiz' ),
					'notice' => [
						esc_html__( 'The settings below will apply the layout to the entire website.', 'foxiz' ),
						esc_html__( 'If you want to use the Foxiz Header and Footer in Elementor Pro, you need to delete both the HEADER and FOOTER templates in the "Theme Builder" panel if you have them.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'          => 'header_style',
					'type'        => 'select',
					'title'       => esc_html__( 'Header Layout', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a header layout for your site.', 'foxiz' ),
					'options'     => foxiz_config_header_style( false, false, true ),
					'description' => esc_html__( 'This is treated as a global setting. Other position settings take priority over this setting.', 'foxiz' ),
					'default'     => '1',
				],
				[
					'id'          => 'header_template',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode if you use a header template.', 'foxiz' ),
					'description' => esc_html__( 'NOTE: Select "Use Ruby Template" in the "Header Layout" setting if you are using the Ruby Template shortcode.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
					'rows'        => 2,
					'default'     => '',
				],
				[
					'id'     => 'section_end_header_style',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_header_sticky',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Sticky Menu', 'foxiz' ),
					'notice' => [
						esc_html__( 'The settings below will apply only to predefined headers.', 'foxiz' ),
						esc_html__( 'Navigate to "Edit Section > Foxiz - for Header Template > Sticky Header" to enable the sticky feature if you use a header template.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'sticky',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sticky Menu', 'foxiz' ),
					'subtitle' => esc_html__( 'Stick your menu bar when scrolling up and down.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'smart_sticky',
					'type'     => 'switch',
					'title'    => esc_html__( 'Smart Sticky', 'foxiz' ),
					'subtitle' => esc_html__( 'Only stick the menu bar when scrolling up.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_header_sticky',
					'type'   => 'section',
					'class'  => 'no-border ruby-section-end',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_more' ) ) {
	function foxiz_register_options_header_more() {

		return [
			'id'         => 'foxiz_config_section_header_more',
			'title'      => esc_html__( 'More Menu Item', 'foxiz' ),
			'icon'       => 'el el-braille',
			'subsection' => true,
			'desc'       => esc_html__( 'Customize the more dropdown section.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'info_more_icon',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can enable this menu item in the Header settings pane > More Menu Button.', 'foxiz' ),
				],
				[
					'id'    => 'info_more_section',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Navigate to "Dashboard > Appearance > Widgets > More Menu Section" to add content for the dropdown section.', 'foxiz' ),
				],
				[
					'id'          => 'more_column',
					'type'        => 'select',
					'title'       => esc_html__( 'Columns per Row', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select columns per row for the dropdown section.', 'foxiz' ),
					'description' => esc_html__( 'Each widget is added in "Appearance >Widgets > More Menu Section" will be corresponding to a column.', 'foxiz' ),
					'options'     => [
						'2' => esc_html__( '2 Columns', 'foxiz' ),
						'3' => esc_html__( '3 Columns', 'foxiz' ),
						'4' => esc_html__( '4 Columns', 'foxiz' ),
						'5' => esc_html__( '5 Columns', 'foxiz' ),
					],
					'default'     => '3',
				],
				[
					'id'          => 'more_width',
					'type'        => 'text',
					'class'       => 'small-text',
					'title'       => esc_html__( 'Dropdown Section Width', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a value (in px) for the width of the dropdown section.', 'foxiz' ),
					'placeholder' => '450',
					'default'     => '',
				],
				[
					'id'       => 'more_footer_menu',
					'type'     => 'select',
					'options'  => foxiz_config_menu_slug(),
					'title'    => esc_html__( 'Footer Menu', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a footer menu to display at the bottom of this section.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'          => 'more_footer_copyright',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Footer Copyright', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter the copyright text you want to display in the footer section. Raw HTML is allowed.', 'foxiz' ),
					'description' => esc_html__( 'Use {year} in your text to automatically display the current year.', 'foxiz' ),
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_search' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_header_search() {

		return [
			'id'         => 'foxiz_config_section_header_search',
			'title'      => esc_html__( 'Header Search', 'foxiz' ),
			'icon'       => 'el el-search',
			'subsection' => true,
			'desc'       => esc_html__( 'Select settings for search form to display in the website header.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'info_search_placeholder',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit the placeholder text of the search form, navigate to "Theme Design > Search Placeholder".', 'foxiz' ),
				],
				[
					'id'    => 'info_search_mobile',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit the search form on mobile devices, navigate to "Header > Mobile Header".', 'foxiz' ),
				],
				[
					'id'          => 'header_search_heading',
					'type'        => 'text',
					'title'       => esc_html__( 'Search Heading', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a heading for displaying above the search form.', 'foxiz' ),
					'description' => esc_html__( 'The heading will show in the "More" section and "Mobile Header Collapse" section.', 'foxiz' ),
					'default'     => esc_html__( 'Search', 'foxiz' ),
				],
				[
					'id'       => 'header_search_icon',
					'type'     => 'switch',
					'title'    => esc_html__( 'Header Search Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the search icon in the header.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'header_search_mode',
					'type'        => 'select',
					'title'       => esc_html__( 'Toggle Mode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select mode for the search button when clicking on.', 'foxiz' ),
					'description' => esc_html__( 'Ensure the option "More Menu - Search Form" is enabled when you select the "More Menu Triggered" option.', 'foxiz' ),
					'options'     => [
						'search' => esc_html__( 'Standard Search Form', 'foxiz' ),
						'more'   => esc_html__( 'More Menu Triggered', 'foxiz' ),
					],
					'default'     => 'search',
				],
				[
					'id'          => 'ajax_search',
					'type'        => 'switch',
					'title'       => esc_html__( 'Live Search Result', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable live search result when typing.', 'foxiz' ),
					'description' => esc_html__( 'This setting will apply to predefined headers. To config the live search for header templates, check the "Header Search Icon" block.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'       => 'ajax_search_limit',
					'title'    => esc_html__( 'Live Search Limit Posts', 'foxiz' ),
					'subtitle' => esc_html__( 'Limit the number of posts displayed in live search results (maximum is 10).', 'foxiz' ),
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'default'  => '',
				],
				[
					'id'       => 'more_search',
					'type'     => 'switch',
					'title'    => esc_html__( 'More Menu - Search Form', 'foxiz' ),
					'subtitle' => esc_html__( 'Show search form at the top of the more section.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'header_search_custom_icon_size',
					'title'       => esc_html__( 'Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom size (in px) for the search icon.', 'foxiz' ),
					'placeholder' => '20',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_notification' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_header_notification() {

		return [
			'id'         => 'foxiz_config_section_header_notification',
			'title'      => esc_html__( 'Notification', 'foxiz' ),
			'icon'       => 'el el-bell',
			'subsection' => true,
			'desc'       => esc_html__( 'Shows posts within 24 hours that allows users to receive real-time updates about new content that has been posted on your website.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'header_notification_info',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The settings below will apply to the predefined headers. Please use "Header Notification Icon" for the header templates.', 'foxiz' ),
				],
				[
					'id'       => 'header_notification',
					'type'     => 'switch',
					'title'    => esc_html__( 'Notification Section', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the notification section on the header.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'notification_duration',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Max Duration', 'foxiz' ),
					'subtitle' => esc_html__( 'Enter the maximum duration value (hours ago) to retrieve new post data.', 'foxiz' ),
					'default'  => 72,
				],
				[
					'id'       => 'notification_reload',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Reload Interval', 'foxiz' ),
					'subtitle' => esc_html__( 'Enter the maximum duration value (in hours) to reload the data in the visitor\'s browser', 'foxiz' ),
					'default'  => 12,
				],
				[
					'id'       => 'header_notification_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom Destination', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a destination URL for the "Show More" text.', 'foxiz' ),
					'default'  => '#',
				],
				[
					'id'          => 'notification_custom_icon',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Custom Notification SVG', 'foxiz' ),
					'subtitle'    => esc_html__( 'Override the bell icon with a SVG icon.', 'foxiz' ),
					'description' => esc_html__( 'Enable the option in "Theme Design > SVG Upload > SVG Supported" to upload SVG icons.', 'foxiz' ),
				],
				[
					'id'          => 'notification_custom_icon_size',
					'title'       => esc_html__( 'Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom size (in px) for the notification icon.', 'foxiz' ),
					'placeholder' => '20',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_mobile' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_header_mobile() {

		return [
			'id'         => 'foxiz_config_section_header_mobile',
			'title'      => esc_html__( 'Mobile Header', 'foxiz' ),
			'icon'       => 'el el-iphone-home',
			'subsection' => true,
			'desc'       => esc_html__( 'Customize the layout and style for your site header on mobile devices.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'info_mobile_navbar_typo',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To edit the typography, navigate to "Typography > Menu > Mobile Menu Settings". Navigate to "Social Profiles" for the social list.', 'foxiz' ),
				],
				[
					'id'    => 'info_mobile_header_color',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Color settings will apply to the tablet and mobile header. Leave blank to set as the desktop navigation colors.', 'foxiz' ),
				],
				[
					'id'    => 'info_mobile_header_dark_color',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'Ensure "Dark Mode Colors" settings are set if you enable dark mode.', 'foxiz' ),
				],
				[
					'id'       => 'section_start_mh_layout',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'The center logo layout is best suited for small logos.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'mh_layout',
					'type'     => 'select',
					'title'    => esc_html__( 'Header Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a layout for the mobile header.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( 'Left Logo', 'foxiz' ),
						'1' => esc_html__( 'Center Logo', 'foxiz' ),
						'2' => esc_html__( 'Left Logo 2', 'foxiz' ),
						'3' => esc_html__( 'Top Logo', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'mh_template',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'or Use Ruby Template for the Mobile Header', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode to display as a mobile header.', 'foxiz' ),
					'description' => esc_html__( 'This setting will override the above setting. Leave it blank to use the predefined mobile header. In AMP mode, it will fallback to predefined header layout due to limitations of AMP.', 'foxiz' ),
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				],
				[
					'id'       => 'mobile_search',
					'type'     => 'switch',
					'title'    => esc_html__( 'Search Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable search icon in the mobile header.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'mobile_amp_search',
					'type'     => 'switch',
					'title'    => esc_html__( 'AMP Header - Search Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable search icon the AMP header.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'          => 'mobile_height',
					'type'        => 'text',
					'class'       => 'small-text',
					'title'       => esc_html__( 'Mobile Navigation Height', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom value for the mobile navigation height.', 'foxiz' ),
					'placeholder' => '42',
				],
				[
					'id'          => 'mlogo_height',
					'type'        => 'text',
					'class'       => 'small-text',
					'title'       => esc_html__( 'Mobile Logo Height', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom value for the mobile logo height. This value should be equal to or less than the Mobile Navigation Height.', 'foxiz' ),
					'placeholder' => '42',
				],
				[
					'id'          => 'quick_access_menu_height',
					'type'        => 'text',
					'class'       => 'small-text',
					'validate'    => 'numeric',
					'title'       => esc_html__( 'Quick View Menu Height', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a height value for the quick view menu bar.', 'foxiz' ),
					'placeholder' => '42',
				],
				[
					'id'          => 'mh_top_divider',
					'type'        => 'select',
					'title'       => esc_html__( 'Top Logo - Divider Style', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a divider style for the top logo layout.', 'foxiz' ),
					'description' => esc_html__( 'This setting applies exclusively to the top logo layout.', 'foxiz' ),
					'options'     => [
						'0'      => esc_html__( '- None -', 'foxiz' ),
						'gray'   => esc_html__( 'Gray Border', 'foxiz' ),
						'dark'   => esc_html__( 'Dark Border', 'foxiz' ),
						'shadow' => esc_html__( 'Shadow', 'foxiz' ),
					],
					'default'     => 'gray',
				],
				[
					'id'     => 'section_end_mh_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_mobile_collapse',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Collapse Section', 'foxiz' ),
					'subtitle' => esc_html__( 'To edit the login button, navigate to "Header > Sign in Buttons".', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'mobile_sub_col',
					'type'     => 'select',
					'title'    => esc_html__( 'Sub-menu Columns', 'foxiz' ),
					'subtitle' => esc_html__( 'Select the number of columns per row for the submenus.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( '2 Columns', 'foxiz' ),
						'1' => esc_html__( '1 Column', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'          => 'mobile_search_form',
					'type'        => 'switch',
					'title'       => esc_html__( 'Search Form', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the search form.', 'foxiz' ),
					'description' => esc_html__( 'Live search will be disabled in this form.', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'          => 'collapse_template',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Collapse Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a Ruby Template shortcode to display in the mobile collapsed section.', 'foxiz' ),
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				],
				[
					'id'       => 'mobile_social',
					'type'     => 'switch',
					'title'    => esc_html__( 'social Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the social icons.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'mobile_footer_menu',
					'type'     => 'select',
					'options'  => foxiz_config_menu_slug(),
					'title'    => esc_html__( 'Footer Menu', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a footer menu to display at the bottom of this section.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'          => 'mobile_copyright',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Footer Copyright', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enter the copyright text you want to display in the footer section. Raw HTML is allowed.', 'foxiz' ),
					'description' => esc_html__( 'Use {year} in your text to automatically display the current year.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'     => 'section_end_mobile_collapse',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_mh_colors',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Colors', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'mobile_background',
					'type'        => 'color_gradient',
					'title'       => esc_html__( 'Header Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the mobile navigation bar and quick view mobile menu.', 'foxiz' ),
					'description' => esc_html__( 'use the option "To" to set a gradient background.', 'foxiz' ),
					'validate'    => 'color',
					'transparent' => false,
					'default'     => [
						'from' => '',
						'to'   => '',
					],
				],
				[
					'id'          => 'mobile_color',
					'title'       => esc_html__( 'Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a text color for toggle button, search, quick view menu for displaying on the mobile header.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'mobile_sub_background',
					'type'        => 'color_gradient',
					'title'       => esc_html__( 'Collapse Section Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the collapsed section.', 'foxiz' ),
					'validate'    => 'color',
					'transparent' => false,
					'default'     => [
						'from' => '',
						'to'   => '',
					],
				],
				[
					'id'          => 'mobile_sub_color',
					'title'       => esc_html__( 'Collapse Section - Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a text color for menu item, sub menu item and other elements for displaying in the collapsed section.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_mh_colors',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_mh_dark_colors',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Dark Mode Colors', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'dark_mobile_background',
					'type'        => 'color_gradient',
					'title'       => esc_html__( 'Header Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the mobile navigation bar and quick view mobile menu in dark mode.', 'foxiz' ),
					'validate'    => 'color',
					'transparent' => false,
					'default'     => [
						'from' => '',
						'to'   => '',
					],
				],
				[
					'id'          => 'dark_mobile_color',
					'title'       => esc_html__( 'Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a text color for toggle button, search, quick view menu for displaying on the mobile header in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_mobile_sub_background',
					'type'        => 'color_gradient',
					'title'       => esc_html__( 'Collapse Section Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the collapsed section in dark mode.', 'foxiz' ),
					'validate'    => 'color',
					'transparent' => false,
					'default'     => [
						'from' => '',
						'to'   => '',
					],
				],
				[
					'id'          => 'dark_mobile_sub_color',
					'title'       => esc_html__( 'Collapse Section - Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a text color for menu item, sub menu item and other elements for displaying in the collapsed section in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'     => 'section_end_mh_dark_colors',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_login' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_header_login() {

		return [
			'id'         => 'foxiz_config_section_header_login',
			'title'      => esc_html__( 'Sign In Buttons', 'foxiz' ),
			'icon'       => 'el el-circle-arrow-right',
			'desc'       => esc_html__( 'Customize the logo buttons in the header.', 'foxiz' ),
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'login_popup_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To config the popup login form. Please navigate to "Theme Options > Login > Popup Sign In".', 'foxiz' ),
				],
				[
					'id'     => 'section_start_login_button',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Desktop', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'header_login_icon',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sign In Button', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the sign in button on the header.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'header_login_layout',
					'type'     => 'select',
					'title'    => esc_html__( 'Button Layout', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a layout for the sign in trigger button.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( 'Icon', 'foxiz' ),
						'1' => esc_html__( 'Text Button', 'foxiz' ),
						'2' => esc_html__( 'Text with Icon Button', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'logged_gravatar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Logged Gravatar', 'foxiz' ),
					'subtitle' => esc_html__( 'Display the user Gravatar in the welcome label.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_login_button',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_mobile_login_button',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Mobile', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'mobile_login',
					'type'     => 'switch',
					'title'    => esc_html__( 'Mobile Header - Sign In', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the sign in button in the mobile collapsible section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'mobile_login_label',
					'type'        => 'text',
					'title'       => esc_html__( 'Sign In Label', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom sign in label.', 'foxiz' ),
					'placeholder' => esc_html__( 'Have an existing account?', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'     => 'section_end_mobile_login_button',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_logged_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Logged User Menu', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'header_login_menu',
					'type'        => 'select',
					'title'       => esc_html__( 'User Dashboard Menu', 'foxiz' ),
					'subtitle'    => esc_html__( 'Assign a menu for displaying when hovering on the login icon if user logged.', 'foxiz' ),
					'options'     => foxiz_config_menu_slug(),
					'placeholder' => esc_html__( '- Assign a Menu -', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'header_login_menu_mobile',
					'type'     => 'switch',
					'title'    => esc_html__( 'User Dashboard Menu for Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the "User Dashboard Menu" in the collapsed section when the user is logged in.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_logged_menu',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_login_icon',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Icon', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'login_custom_icon',
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'title'       => esc_html__( 'Custom Login SVG', 'foxiz' ),
					'subtitle'    => esc_html__( 'Override the user icon with a SVG icon.', 'foxiz' ),
					'description' => esc_html__( 'Enable the option in "Theme Design > SVG Upload > SVG Supported" if you cannot upload .SVG files.', 'foxiz' ),
				],
				[
					'id'          => 'login_custom_icon_size',
					'title'       => esc_html__( 'Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom size (in px) for the login icon.', 'foxiz' ),
					'placeholder' => '20',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],
				[
					'id'     => 'section_end_login_icon',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],

			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_alert' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_header_alert() {

		return [
			'id'         => 'foxiz_config_section_header_alert',
			'title'      => esc_html__( 'Alert Bar', 'foxiz' ),
			'icon'       => 'el el-bell',
			'subsection' => true,
			'desc'       => esc_html__( 'Show an alert or event information at the bottom of the navigation.', 'foxiz' ),
			'fields'     => [
				[
					'id'       => 'alert_bar_info',
					'type'     => 'info',
					'style'    => 'warning',
					'subtitle' => esc_html__( 'The alert bar will not be available when using a header template.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'info_alert_builder',
					'type'     => 'info',
					'style'    => 'info',
					'subtitle' => esc_html__( 'Template Builder: You can add text and button features to create an alert bar for the Elementor header templates.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'alert_bar',
					'type'        => 'switch',
					'title'       => esc_html__( 'Alert Bar', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the alert bar below the header.', 'foxiz' ),
					'description' => esc_html__( 'This is treated as a global setting. Other individual post and page settings take priority over this setting.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'       => 'alert_home',
					'type'     => 'switch',
					'title'    => esc_html__( 'Homepage Only', 'foxiz' ),
					'subtitle' => esc_html__( 'Only show the bar in the homepage.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'alert_content',
					'type'     => 'textarea',
					'title'    => esc_html__( 'Alert Content', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your alert content to show.', 'foxiz' ),
					'rows'     => 2,
					'default'  => '',
				],
				[
					'id'       => 'alert_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Alert URL', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your alert URL.', 'foxiz' ),
					'default'  => '#',
				],
				[
					'id'          => 'alert_bg',
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
					'title'       => esc_html__( 'Background Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select background color for this section.', 'foxiz' ),
				],
				[
					'id'          => 'alert_color',
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
					'title'       => esc_html__( 'Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select text color for this section.', 'foxiz' ),
				],
				[
					'id'          => 'dark_alert_bg',
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
					'title'       => esc_html__( 'Dark Mode - Background Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select background color for this section in dark mode.', 'foxiz' ),
				],
				[
					'id'          => 'dark_alert_color',
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
					'title'       => esc_html__( 'Dark Mode - Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select text color for this section in dark mode.', 'foxiz' ),
				],
				[
					'id'       => 'alert_sticky_hide',
					'title'    => esc_html__( 'Hide when Sticky', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide this bar when the navigation is sticking.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_socials' ) ) {
	function foxiz_register_options_header_socials() {

		return [
			'id'         => 'foxiz_config_section_header_socials',
			'title'      => esc_html__( 'Social Icons', 'foxiz' ),
			'icon'       => 'el el-facebook',
			'subsection' => true,
			'desc'       => esc_html__( 'Customize the social icons in the header.', 'foxiz' ),
			'fields'     => [
				[
					'id'    => 'header_social_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'To configure the social icons appearance, please navigate to Header Layout (select the style you used for your website) > Social Icons.', 'foxiz' ),
				],
				[
					'id'    => 'header_social_profile_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Please navigate to Theme Options > Social Profiles to enter your site’s social profiles.', 'foxiz' ),
				],
				[
					'id'          => 'social_custom_icon_size',
					'title'       => esc_html__( 'Icon Size', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a custom size (in px) for the social icon.', 'foxiz' ),
					'placeholder' => '16',
					'type'        => 'text',
					'class'       => 'small-text',
					'default'     => '',
				],

			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_header_cart' ) ) {
	function foxiz_register_options_header_cart() {

		return [
			'id'         => 'foxiz_config_section_mini_cart',
			'title'      => esc_html__( 'Mini Cart', 'foxiz' ),
			'icon'       => 'el el-shopping-cart',
			'subsection' => true,
			'desc'       => esc_html__( 'Show a cart icon at the website header.', 'foxiz' ),
			'fields'     => ! class_exists( 'WooCommerce' ) ? foxiz_wc_plugin_status_info( 'header_mini_cart_warning' ) :
				[
					[
						'id'       => 'wc_mini_cart',
						'type'     => 'switch',
						'title'    => esc_html__( 'Header Mini Cart', 'foxiz' ),
						'subtitle' => esc_html__( 'Show mini cart icon in the header.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'       => 'wc_mobile_mini_cart',
						'type'     => 'switch',
						'title'    => esc_html__( 'Mobile Header - Mini Cart', 'foxiz' ),
						'subtitle' => esc_html__( 'Show mini cart icon in the mobile header.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'       => 'cart_counter',
						'type'     => 'switch',
						'title'    => esc_html__( 'Cart Counter', 'foxiz' ),
						'subtitle' => esc_html__( 'Show number of products in the cart.', 'foxiz' ),
						'default'  => true,
					],
					[
						'id'       => 'total_amount',
						'type'     => 'switch',
						'title'    => esc_html__( 'Total Amount', 'foxiz' ),
						'subtitle' => esc_html__( 'Show total amount after the cart icon.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'       => 'cart_custom_icon',
						'type'     => 'media',
						'title'    => esc_html__( 'Custom Cart SVG', 'foxiz' ),
						'subtitle' => esc_html__( 'Override default cart icon with a SVG icon.', 'foxiz' ),
					],
					[
						'id'          => 'cart_custom_icon_size',
						'title'       => esc_html__( 'Icon Size', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input a custom size (in px) for the cart icon.', 'foxiz' ),
						'placeholder' => '20',
						'type'        => 'text',
						'class'       => 'small-text',
						'default'     => '',
					],
				],
		];
	}
}
