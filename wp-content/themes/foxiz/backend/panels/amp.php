<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_amp_plugin_status_info' ) ) {
	function foxiz_amp_plugin_status_info( $id = 'amp-plugin-info' ) {

		if ( ! function_exists( 'amp_init' ) ) {
			return [
				'id'    => $id,
				'type'  => 'info',
				'style' => 'warning',
				'desc'  => html_entity_decode( esc_html__( 'The AMP (Accelerated Mobile Pages) Plugin is missing! Please install and activate <a target="_blank" href="https://wordpress.org/plugins/amp">Automattic AMP</a> plugin to activate the settings.', 'foxiz' ) ),
			];
		}

		return null;
	}
}

if ( ! function_exists( 'foxiz_register_options_amp' ) ) {
	function foxiz_register_options_amp() {

		return [
			'id'    => 'foxiz_config_section_amp',
			'title' => esc_html__( 'AMP', 'foxiz' ),
			'desc'  => esc_html__( 'Customize your website in AMP mode.', 'foxiz' ),
			'icon'  => 'el el-ok',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_amp_general' ) ) {
	function foxiz_register_options_amp_general() {

		return [
			'id'         => 'foxiz_config_section_amp_general',
			'title'      => esc_html__( 'General', 'foxiz' ),
			'desc'       => esc_html__( 'Customize your website in AMP mode.', 'foxiz' ),
			'icon'       => 'el el-cog',
			'subsection' => true,
			'fields'     => [
				foxiz_amp_plugin_status_info( 'general-amp-plugin-info' ),
				[
					'id'    => 'amp-footer-info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Each AMP page has a 75,000 byte CSS limit. Foxiz will support a compact footer to ensure your site will meet with this requirement.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_amp_general',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Debug Mode', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_debug',
					'type'     => 'switch',
					'title'    => esc_html__( 'AMP Debug', 'foxiz' ),
					'subtitle' => esc_html__( 'Debug mode will provide more information about the each AMP page such as CSS usages, plugin scripts etc...', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'remove_amp_switcher',
					'type'     => 'switch',
					'title'    => esc_html__( 'Remove Footer Switcher', 'foxiz' ),
					'subtitle' => esc_html__( 'Remove the "Exit Mobile Version" link at the footer.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_amp_general',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'AMP Footer', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_footer_logo',
					'type'     => 'media',
					'url'      => true,
					'preview'  => true,
					'title'    => esc_html__( 'AMP Footer Logo', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload AMP footer logo.', 'foxiz' ),
				],
				[
					'id'       => 'amp_footer_logo_height',
					'type'     => 'text',
					'title'    => esc_html__( 'Logo Height', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a height for value for the footer logo, Default is 50px.', 'foxiz' ),
				],
				[
					'id'       => 'amp_footer_social',
					'type'     => 'switch',
					'title'    => esc_html__( 'Social Icons', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the social list in this section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'amp_footer_bottom_center',
					'type'     => 'switch',
					'title'    => esc_html__( 'Centered Mode', 'foxiz' ),
					'subtitle' => esc_html__( 'Centering this section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'     => 'section_end_amp_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_footer_copyright',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Copyright Section', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'amp_copyright',
					'type'        => 'switch',
					'title'       => esc_html__( 'Copyright Section', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the footer copyright section.', 'foxiz' ),
					'description' => esc_html__( 'To setup the copyright area, Please navigate to Footer > Copyright Section.', 'foxiz' ),
					'default'     => true,
				],
				[
					'id'     => 'section_end_amp_footer_copyright',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_footer_back_top',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Back to Top', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_back_top',
					'type'     => 'switch',
					'title'    => esc_html__( 'Back to Top', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the back to top button.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'     => 'section_end_amp_back_top',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_amp_single' ) ) {
	function foxiz_register_options_amp_single() {

		return [
			'id'         => 'foxiz_config_section_amp_single',
			'title'      => esc_html__( 'Single Post', 'foxiz' ),
			'desc'       => esc_html__( 'Select settings for the single post on your site in AMP mode.', 'foxiz' ),
			'icon'       => 'el el-cog',
			'subsection' => true,
			'fields'     => [
				foxiz_amp_plugin_status_info( 'single-amp-plugin-info' ),
				[
					'id'    => 'amp-single-info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Each AMP page has a 75,000 byte CSS limit, turn off unnecessary section will increase the AMP performance.', 'foxiz' ),
				],
				[
					'id'       => 'amp_disable_left_share',
					'type'     => 'switch',
					'title'    => esc_html__( 'Fixed Left Share Bar', 'foxiz' ),
					'off'      => esc_html__( 'Default from Single Settings', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the Author Card in AMP mode.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'amp_disable_author',
					'type'     => 'switch',
					'title'    => esc_html__( 'Author Card', 'foxiz' ),
					'off'      => esc_html__( 'Default from Single Settings', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the Author Card in AMP mode.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'amp_disable_single_pagination',
					'type'     => 'switch',
					'title'    => esc_html__( 'Next/Prev Articles', 'foxiz' ),
					'off'      => esc_html__( 'Default from Single Settings', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the Next/Prev Articles section in AMP mode.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'amp_disable_comment',
					'type'     => 'switch',
					'title'    => esc_html__( 'Comment Box', 'foxiz' ),
					'off'      => esc_html__( 'Default', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable comment form in AMP mode.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'amp_disable_related',
					'type'     => 'switch',
					'title'    => esc_html__( 'Related & Popular Section', 'foxiz' ),
					'off'      => esc_html__( 'Default from Single Settings', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the single footer section, including related and popular blocks in AMP mode.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'amp_disable_single_sidebar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sidebar Section', 'foxiz' ),
					'off'      => esc_html__( 'Default from Single Settings', 'foxiz' ),
					'on'       => esc_html__( 'Disable on AMP', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the single post sidebar section in AMP mode.', 'foxiz' ),
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_amp_auto_ads' ) ) {
	function foxiz_register_options_amp_auto_ads() {

		return [
			'id'         => 'foxiz_config_section_amp_auto_ads',
			'title'      => esc_html__( 'AMP Auto Ads', 'foxiz' ),
			'desc'       => esc_html__( 'Select setting for auto ads in AMP mode.', 'foxiz' ),
			'icon'       => 'el el-cog',
			'subsection' => true,
			'fields'     => [
				foxiz_amp_plugin_status_info( 'auto-ads-amp-plugin-info' ),
				[
					'id'    => 'amp-auto-ads-info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Auto ads for AMP automatically place AdSense Auto ads on your AMP pages. Google will automatically show ads on your AMP pages at optimal times when they are likely to perform well and provide a good experience.', 'foxiz' ),
				],
				[
					'id'          => 'amp_ad_auto_code',
					'title'       => esc_html__( 'AMP Auto Ads Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your amp auto ads code.', 'foxiz' ),
					'type'        => 'textarea',
					'rows'        => 2,
					'placeholder' => esc_html( '<amp-auto-ads type="adsense" data-ad-client....amp-auto-ads>' ),
					'default'     => '',
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_amp_ads' ) ) {
	function foxiz_register_options_amp_ads() {

		return [
			'id'         => 'foxiz_config_section_amp_ads',
			'title'      => esc_html__( 'AMP Ads', 'foxiz' ),
			'desc'       => esc_html__( 'Select setting for ads in AMP mode.', 'foxiz' ),
			'icon'       => 'el el-cog',
			'subsection' => true,
			'fields'     => [
				foxiz_amp_plugin_status_info( 'ads-amp-plugin-info' ),
				[
					'id'    => 'amp-ads-info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'Leave blank AMP auto ads if you would like to use the ad units.', 'foxiz' ),
				],

				[
					'id'     => 'section_start_amp_header_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Header Ad', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_header_ad_type',
					'type'     => 'select',
					'title'    => esc_html__( 'Header - Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select your ad type to display at the header.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Adsense --', 'foxiz' ),
						'2' => esc_html__( 'AMP Custom Script Ad', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_header_adsense_client',
					'type'     => 'text',
					'required' => [ 'amp_header_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Header - Data Ad Client', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-client number ID (without ca-pub-).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_header_adsense_slot',
					'type'     => 'text',
					'required' => [ 'amp_header_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Header - Data Ad Slot', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-slot number ID.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_header_adsense_size',
					'type'     => 'select',
					'required' => [ 'amp_header_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Header - Adsense Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size for this ad.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Responsive --', 'foxiz' ),
						'2' => esc_html__( 'Fixed Height (90px)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_header_ad_code',
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'amp_header_ad_type', '=', '2' ],
					'title'    => esc_html__( 'Header - AMP Custom Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your AMP custom ad script.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'     => 'section_end_amp_header_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_footer_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Footer Ad', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_footer_ad_type',
					'type'     => 'select',
					'title'    => esc_html__( 'Footer - Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select your ad type to display at the footer.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Adsense --', 'foxiz' ),
						'2' => esc_html__( 'AMP Custom Script Ad', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_footer_adsense_client',
					'type'     => 'text',
					'required' => [ 'amp_footer_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Footer - Data Ad Client', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-client number ID (without ca-pub-).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_footer_adsense_slot',
					'type'     => 'text',
					'required' => [ 'amp_footer_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Footer - Data Ad Slot', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-slot number ID.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_footer_adsense_size',
					'type'     => 'select',
					'required' => [ 'amp_footer_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Footer - Adsense Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size for this ad.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Responsive --', 'foxiz' ),
						'2' => esc_html__( 'Fixed Height (90px)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_footer_ad_code',
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'amp_footer_ad_type', '=', '2' ],
					'title'    => esc_html__( 'Footer - AMP Custom Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your AMP custom ad script.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'     => 'section_end_amp_footer_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_top_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Top Single Content Ad', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_top_single_ad_type',
					'type'     => 'select',
					'title'    => esc_html__( 'Top - Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select your ad type to display at the top single content.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Adsense --', 'foxiz' ),
						'2' => esc_html__( 'AMP Custom Script Ad', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_top_single_adsense_client',
					'type'     => 'text',
					'required' => [ 'amp_top_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Top - Data Ad Client', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-client number ID (without ca-pub-).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_top_single_adsense_slot',
					'type'     => 'text',
					'required' => [ 'amp_top_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Top - Data Ad Slot', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-slot number ID.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_top_single_adsense_size',
					'type'     => 'select',
					'required' => [ 'amp_top_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Top - Adsense Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size for this ad.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Responsive --', 'foxiz' ),
						'2' => esc_html__( 'Fixed Height (90px)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_top_single_ad_code',
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'amp_top_single_ad_type', '=', '2' ],
					'title'    => esc_html__( 'Top - AMP Custom Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your AMP custom ad script.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'     => 'section_end_amp_top_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				/** bottom single */
				[
					'id'     => 'section_start_amp_bottom_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Bottom Single Content Ad', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_bottom_single_ad_type',
					'type'     => 'select',
					'title'    => esc_html__( 'Bottom - Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select your ad type to display at the bottom single content.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Adsense --', 'foxiz' ),
						'2' => esc_html__( 'AMP Custom Script Ad', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_bottom_single_adsense_client',
					'type'     => 'text',
					'required' => [ 'amp_bottom_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Bottom - Data Ad Client', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-client number ID (without ca-pub-).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_bottom_single_adsense_slot',
					'type'     => 'text',
					'required' => [ 'amp_bottom_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Bottom - Data Ad Slot', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-slot number ID.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_bottom_single_adsense_size',
					'type'     => 'select',
					'required' => [ 'amp_bottom_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Bottom - Adsense Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size for this ad.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Responsive --', 'foxiz' ),
						'2' => esc_html__( 'Fixed Height (90px)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_bottom_single_ad_code',
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'amp_bottom_single_ad_type', '=', '2' ],
					'title'    => esc_html__( 'Bottom - AMP Custom Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your AMP custom ad script.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'     => 'section_end_amp_bottom_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_amp_inline_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Inline Single Content Ad', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'amp_inline_single_ad_type',
					'type'     => 'select',
					'title'    => esc_html__( 'Inline - Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select your ad type to display at the bottom single content.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Adsense --', 'foxiz' ),
						'2' => esc_html__( 'AMP Custom Script Ad', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_inline_single_adsense_client',
					'type'     => 'text',
					'required' => [ 'amp_inline_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Inline - Data Ad Client', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-client number ID (without ca-pub-).', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_inline_single_adsense_slot',
					'type'     => 'text',
					'required' => [ 'amp_inline_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Inline - Data Ad Slot', 'foxiz' ),
					'subtitle' => esc_html__( 'Input the data-ad-slot number ID.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'amp_inline_single_adsense_size',
					'type'     => 'select',
					'required' => [ 'amp_inline_single_ad_type', '=', '1' ],
					'title'    => esc_html__( 'Inline - Adsense Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size for this ad.', 'foxiz' ),
					'options'  => [
						'1' => esc_html__( '-- Responsive --', 'foxiz' ),
						'2' => esc_html__( 'Fixed Height (90px)', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'amp_inline_single_ad_code',
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'amp_inline_single_ad_type', '=', '2' ],
					'title'    => esc_html__( 'Inline - AMP Custom Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your AMP custom ad script.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'          => 'amp_ad_single_positions',
					'type'        => 'text',
					'title'       => esc_html__( 'Display Positions', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a position (after x paragraphs) to display your ads.', 'foxiz' ),
					'description' => esc_html__( 'Allow multiple positions, separated by commas. e.g. 4,9', 'foxiz' ),
					'default'     => '4',
				],
				[
					'id'     => 'section_end_amp_inline_single_advert',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

