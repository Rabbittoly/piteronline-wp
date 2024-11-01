<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_ads' ) ) {
	function foxiz_register_options_ads() {

		return [
			'id'    => 'foxiz_config_section_ads',
			'title' => esc_html__( 'Ads & Slide Up', 'foxiz' ),
			'desc'  => esc_html__( 'Customize the adverts for your website.', 'foxiz' ),
			'icon'  => 'el el-usd',
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_ad_auto' ) ) {
	function foxiz_register_options_ad_auto() {

		return [
			'id'         => 'foxiz_config_section_ad_auto',
			'title'      => esc_html__( 'Adsense - Auto Ads', 'foxiz' ),
			'desc'       => esc_html__( 'Auto ads will scan your site and automatically place ads where they are likely to perform well and potentially generate more revenue.', 'foxiz' ),
			'icon'       => 'el el-usd',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_adsense_auto',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'When utilizing AdSense Auto Ads code, it is essential to deactivate any other AdSense units code.', 'foxiz' ),
				],
				[
					'id'    => 'info_adsense_auto_duplicate',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'Avoid adding multiple Auto Ads codes on your website, as it can result in undesirable outcomes and impact ad performance.', 'foxiz' ),
				],
				[
					'id'          => 'ad_auto_code',
					'title'       => esc_html__( 'Auto Adsense Ads Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your auto ads code.', 'foxiz' ),
					'type'        => 'textarea',
					'placeholder' => esc_html( '<script async src="... crossorigin="anonymous"></script>' ),
					'description' => esc_html__( 'Leave this option blank to use unit ads code.', 'foxiz' ),
					'rows'        => 3,
					'default'     => '',
				],
				[
					'id'          => 'ad_auto_allowed',
					'title'       => esc_html__( 'Allow Other Ads Placement', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable simultaneous display of other ads when using auto ads, this also applies to AMP mode.', 'foxiz' ),
					'description' => esc_html__( 'PLEASE NOTE: enabling both features simultaneously without proper control may lead to potential issues.', 'foxiz' ),
					'type'        => 'switch',
					'default'     => false,
				],
				[
					'id'       => 'disable_ad_auto_wc',
					'title'    => esc_html__( 'Disable on Woocommerce Pages', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable auto Adsense on Woocommerce such as shop, product, cart, checkout....', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_ad_top' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_ad_top() {

		return [
			'id'         => 'foxiz_config_section_ad_top',
			'title'      => esc_html__( 'Top Site', 'foxiz' ),
			'desc'       => esc_html__( 'Select ad settings for displaying at the top of your site.', 'foxiz' ),
			'icon'       => 'el el-usd',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_ad_top_site',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'This section supports ads in the top header website. For other ad spots, please read the theme documentation for further info.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_ad_top_type',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Ad Type', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'ad_top_type',
					'title'    => esc_html__( 'Ad Type', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a ad type for displaying in the top of the website.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Script', 'foxiz' ),
						'0' => esc_html__( 'Custom Image', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'     => 'section_end_ad_top_type',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_ad_top_script',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Ad Script', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply if you choose the "Script" ad type.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'          => 'ad_top_code',
					'type'        => 'textarea',
					'rows'        => 3,
					'title'       => esc_html__( 'Script - Ad/Adsense Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your ad script code or ad shortcode.', 'foxiz' ),
					'description' => esc_html__( 'Use Adsense unit code to ensure it displays exactly where you place it.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'ad_top_size',
					'title'    => esc_html__( 'Script - Ad Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a custom size for this ad if you use adsense ad units.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( 'Do not Override', 'foxiz' ),
						'1' => esc_html__( 'Custom Size Below', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'ad_top_desktop_size',
					'title'    => esc_html__( 'Script - Size on Desktop', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on desktop devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '1',
				],
				[
					'id'       => 'ad_top_tablet_size',
					'title'    => esc_html__( 'Script - Size on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on tablet devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '2',
				],
				[
					'id'       => 'ad_top_mobile_size',
					'title'    => esc_html__( 'Script - Size on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on mobile devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '3',
				],
				[
					'id'     => 'section_end_ad_top_script',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'       => 'section_start_ad_top_image',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Ad Image', 'foxiz' ),
					'subtitle' => esc_html__( 'The settings below will apply if you choose the "Custom Image" ad type.', 'foxiz' ),
					'indent'   => true,
				],
				[
					'id'       => 'ad_top_image',
					'title'    => esc_html__( 'Ad Image', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your ad image.', 'foxiz' ),
					'type'     => 'media',
					'default'  => '',
				],
				[
					'id'       => 'ad_top_dark_image',
					'title'    => esc_html__( 'Dark Mode - Ad Image', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your ad image in dark mode.', 'foxiz' ),
					'type'     => 'media',
					'default'  => '',
				],
				[
					'id'       => 'ad_top_destination',
					'title'    => esc_html__( 'Ad Destination', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your ad destination URL.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '#',
				],
				[
					'id'       => 'ad_top_width',
					'title'    => esc_html__( 'Max Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a max width value (in px) for your ad image, leave blank set full size.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '',
				],
				[
					'id'       => 'ad_top_animation',
					'title'    => esc_html__( 'Animation Effect', 'foxiz' ),
					'subtitle' => esc_html__( 'Turn on the animation can affect to the pagespeed CLS score.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'     => 'section_end_ad_top_image',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_ad_top_style',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Style', 'foxiz' ),
					'indent' => true,
				],

				[
					'id'          => 'ad_top_bg',
					'title'       => esc_html__( 'Section Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background for this ad.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'ad_top_dark_bg',
					'title'       => esc_html__( 'Dark Mode - Section Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background for this ad section in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'       => 'ad_top_spacing',
					'title'    => esc_html__( 'Spacing', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a top and bottom spacing for this ad.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( '15px', 'foxiz' ),
						'1' => esc_html__( 'No spacing', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_ad_top_style',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_ad_single' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_ad_single() {

		return [
			'id'         => 'foxiz_config_section_ad_single',
			'title'      => esc_html__( 'Inline Single Content', 'foxiz' ),
			'desc'       => esc_html__( 'Select ad settings for displaying inside the single post content.', 'foxiz' ),
			'icon'       => 'el el-usd',
			'subsection' => true,
			'fields'     => [
				[
					'id'    => 'info_single_ad',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'This section supports ads inside single post content, after x paragraphs. For other ad spots, please read the theme documentation for further info.', 'foxiz' ),
				],
				[
					'id'    => 'info_podcast_inline_ad',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'Navigate to Ruby "Podcast > General > Inline Content Ads Supported" to enable ads for the single podcast.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_ad_single_1',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Ad 1', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'ad_single_type',
					'title'       => esc_html__( 'Ad 1 - Type', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a ad type for displaying inside the single post content.', 'foxiz' ),
					'description' => esc_html__( 'Setup below settings corresponding to your ad type.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'Script', 'foxiz' ),
						'0' => esc_html__( 'Image', 'foxiz' ),
					],
					'default'     => '1',
				],
				[
					'id'       => 'ad_single_description',
					'title'    => esc_html__( 'Ad Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a description for the adverting box.', 'foxiz' ),
					'type'     => 'text',
					'default'  => esc_html__( '- Advertisement -', 'foxiz' ),
				],
				[
					'id'       => 'ad_single_align',
					'title'    => esc_html__( 'Ad Align', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a align style for the adverts.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'full'  => esc_html__( 'Full Width', 'foxiz' ),
						'left'  => esc_html__( 'Float Left', 'foxiz' ),
						'right' => esc_html__( 'Float Right', 'foxiz' ),
					],
					'default'  => 'full',
				],
				[
					'id'          => 'ad_single_positions',
					'title'       => esc_html__( 'Display Positions', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a position (after x paragraphs) to display your ads.', 'foxiz' ),
					'description' => esc_html__( 'Allow multiple positions, separated by commas. e.g. 4,9', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => esc_html__( '4,9', 'foxiz' ),
					'default'     => 4,
				],
				[
					'id'          => 'ad_single_code',
					'type'        => 'textarea',
					'rows'        => 3,
					'title'       => esc_html__( 'Script - Ad/Adsense Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your custom ad script code or Adsense. Use Adsense units code to ensure it display exactly where you put.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Script" ad type.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'ad_single_size',
					'title'    => esc_html__( 'Script - Ad Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a custom size for this ad if you use adsense ad units.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( 'Do not Override', 'foxiz' ),
						'1' => esc_html__( 'Custom Size Below', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'ad_single_desktop_size',
					'title'    => esc_html__( 'Script - Size on Desktop', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on desktop devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '1',
				],
				[
					'id'       => 'ad_single_tablet_size',
					'title'    => esc_html__( 'Script - Size on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on tablet devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '2',
				],
				[
					'id'       => 'ad_single_mobile_size',
					'title'    => esc_html__( 'Script - Size on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on mobile devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '3',
				],
				[
					'id'          => 'ad_single_image',
					'title'       => esc_html__( 'Image - Ad', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload your ad image.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Custom Image" ad type.', 'foxiz' ),
					'type'        => 'media',
					'default'     => '',
				],
				[
					'id'       => 'ad_single_dark_image',
					'title'    => esc_html__( 'Image - Dark Mode Ad', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your ad image in dark mode.', 'foxiz' ),
					'type'     => 'media',
					'default'  => '',
				],
				[
					'id'       => 'ad_single_destination',
					'title'    => esc_html__( 'Image - Ad Destination', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your ad destination URL.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '#',
				],
				[
					'id'       => 'ad_single_width',
					'title'    => esc_html__( 'Image - Max Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a max width value (in px) for your ad image, leave blank set full size.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '',
				],
				[
					'id'     => 'section_end_ad_single_1',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],

				/** ad 2 */
				[
					'id'     => 'section_start_ad_single_2',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Ad 2', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'ad_single_2_type',
					'title'       => esc_html__( 'Ad 2 - Type', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a ad type for displaying inside the single post content.', 'foxiz' ),
					'description' => esc_html__( 'Setup below settings corresponding to your ad type.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'Script', 'foxiz' ),
						'0' => esc_html__( 'Image', 'foxiz' ),
					],
					'default'     => '1',
				],
				[
					'id'       => 'ad_single_2_description',
					'title'    => esc_html__( 'Ad Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a description for the adverting box.', 'foxiz' ),
					'type'     => 'text',
					'default'  => esc_html__( '- Advertisement -', 'foxiz' ),
				],
				[
					'id'       => 'ad_single_2_align',
					'title'    => esc_html__( 'Ad Align', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a align style for the adverts.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'full'  => esc_html__( 'Full Width', 'foxiz' ),
						'left'  => esc_html__( 'Float Left', 'foxiz' ),
						'right' => esc_html__( 'Float Right', 'foxiz' ),
					],
					'default'  => 'full',
				],
				[
					'id'          => 'ad_single_2_positions',
					'title'       => esc_html__( 'Display Positions', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a position (after x paragraphs) to display your ads.', 'foxiz' ),
					'description' => esc_html__( 'Allow multiple positions, separated by commas. e.g. 4,9', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => esc_html__( '4,9', 'foxiz' ),
					'default'     => 9,
				],
				[
					'id'          => 'ad_single_2_code',
					'type'        => 'textarea',
					'rows'        => 3,
					'title'       => esc_html__( 'Script - Ad/Adsense Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your custom ad script code or Adsense. Use Adsense units code to ensure it display exactly where you put.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Script" ad type.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'ad_single_2_size',
					'title'    => esc_html__( 'Script - Ad Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a custom size for this ad if you use adsense ad units.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( 'Do not Override', 'foxiz' ),
						'1' => esc_html__( 'Custom Size Below', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'ad_single_2_desktop_size',
					'title'    => esc_html__( 'Script - Size on Desktop', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on desktop devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '1',
				],
				[
					'id'       => 'ad_single_2_tablet_size',
					'title'    => esc_html__( 'Script - Size on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on tablet devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '2',
				],
				[
					'id'       => 'ad_single_2_mobile_size',
					'title'    => esc_html__( 'Script - Size on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on mobile devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '3',
				],
				[
					'id'          => 'ad_single_2_image',
					'title'       => esc_html__( 'Image - Ad', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload your ad image.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Custom Image" ad type.', 'foxiz' ),
					'type'        => 'media',
					'default'     => '',
				],
				[
					'id'       => 'ad_single_2_dark_image',
					'title'    => esc_html__( 'Image - Dark Mode Ad', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your ad image in dark mode.', 'foxiz' ),
					'type'     => 'media',
					'default'  => '',
				],
				[
					'id'       => 'ad_single_2_destination',
					'title'    => esc_html__( 'Image - Ad Destination', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your ad destination URL.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '#',
				],
				[
					'id'       => 'ad_single_2_width',
					'title'    => esc_html__( 'Image - Max Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a max width value (in px) for your ad image, leave blank set full size.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '',
				],
				[
					'id'     => 'section_end_ad_single_2',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],

				/** ad 3 */
				[
					'id'     => 'section_start_ad_single_3',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Ad 3', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'          => 'ad_single_3_type',
					'title'       => esc_html__( 'Ad 3 - Type', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a ad type for displaying inside the single post content.', 'foxiz' ),
					'description' => esc_html__( 'Setup below settings corresponding to your ad type.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'Script', 'foxiz' ),
						'0' => esc_html__( 'Image', 'foxiz' ),
					],
					'default'     => '1',
				],
				[
					'id'       => 'ad_single_3_description',
					'title'    => esc_html__( 'Ad Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a description for the adverting box.', 'foxiz' ),
					'type'     => 'text',
					'default'  => esc_html__( '- Advertisement -', 'foxiz' ),
				],
				[
					'id'       => 'ad_single_3_align',
					'title'    => esc_html__( 'Ad Align', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a align style for the adverts.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'full'  => esc_html__( 'Full Width', 'foxiz' ),
						'left'  => esc_html__( 'Float Left', 'foxiz' ),
						'right' => esc_html__( 'Float Right', 'foxiz' ),
					],
					'default'  => 'full',
				],
				[
					'id'          => 'ad_single_3_positions',
					'title'       => esc_html__( 'Display Positions', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a position (after x paragraphs) to display your ads.', 'foxiz' ),
					'description' => esc_html__( 'Allow multiple positions, separated by commas. e.g. 4,9', 'foxiz' ),
					'type'        => 'text',
					'placeholder' => esc_html__( '4,9', 'foxiz' ),
					'default'     => 12,
				],
				[
					'id'          => 'ad_single_3_code',
					'type'        => 'textarea',
					'rows'        => 3,
					'title'       => esc_html__( 'Script - Ad/Adsense Code', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input your custom ad script code or Adsense. Use Adsense units code to ensure it display exactly where you put.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Script" ad type.', 'foxiz' ),
					'default'     => '',
				],
				[
					'id'       => 'ad_single_3_size',
					'title'    => esc_html__( 'Script - Ad Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a custom size for this ad if you use adsense ad units.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'0' => esc_html__( 'Do not Override', 'foxiz' ),
						'1' => esc_html__( 'Custom Size Below', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'ad_single_3_desktop_size',
					'title'    => esc_html__( 'Script - Size on Desktop', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on desktop devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '1',
				],
				[
					'id'       => 'ad_single_3_tablet_size',
					'title'    => esc_html__( 'Script - Size on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on tablet devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '2',
				],
				[
					'id'       => 'ad_single_3_mobile_size',
					'title'    => esc_html__( 'Script - Size on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a size on mobile devices.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_ad_size_dropdown(),
					'default'  => '3',
				],
				[
					'id'          => 'ad_single_3_image',
					'title'       => esc_html__( 'Image - Ad', 'foxiz' ),
					'subtitle'    => esc_html__( 'Upload your ad image.', 'foxiz' ),
					'description' => esc_html__( 'The settings below will apply if you choose the "Custom Image" ad type.', 'foxiz' ),
					'type'        => 'media',
					'default'     => '',
				],
				[
					'id'       => 'ad_single_3_dark_image',
					'title'    => esc_html__( 'Image - Dark Mode Ad', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your ad image in dark mode.', 'foxiz' ),
					'type'     => 'media',
					'default'  => '',
				],
				[
					'id'       => 'ad_single_3_destination',
					'title'    => esc_html__( 'Image - Ad Destination', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your ad destination URL.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '#',
				],
				[
					'id'       => 'ad_single_3_width',
					'title'    => esc_html__( 'Image - Max Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a max width value (in px) for your ad image, leave blank set full size.', 'foxiz' ),
					'type'     => 'text',
					'default'  => '',
				],
				[
					'id'     => 'section_end_ad_single_3',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_footer_slide_up' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_footer_slide_up() {

		return [
			'id'         => 'foxiz_config_section_footer_slide_up',
			'title'      => esc_html__( 'Footer Slide Up', 'foxiz' ),
			'desc'       => esc_html__( 'Show ads or any shortcode in the slide up footer section.', 'foxiz' ),
			'icon'       => 'el el-chevron-up',
			'subsection' => true,
			'fields'     => [
				[
					'id'       => 'footer_slide_up',
					'type'     => 'switch',
					'title'    => esc_html__( 'Footer Slide Up Section', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the footer slide up section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'slide_up_shortcode',
					'type'        => 'textarea',
					'rows'        => 2,
					'title'       => esc_html__( 'Slide Up Template Shortcode', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a template shortcode or any other shortcode you would like to show in this section.', 'foxiz' ),
					'placeholder' => esc_html__( '[Ruby_E_Template id="1"]', 'foxiz' ),
				],
				[
					'id'       => 'slide_up_expired',
					'type'     => 'select',
					'title'    => esc_html__( 'Side Up Expired', 'foxiz' ),
					'subtitle' => esc_html__( 'The period to redisplay the popup when visitors closed it.', 'foxiz' ),
					'options'  => [
						'1'  => esc_html__( '1 Day', 'foxiz' ),
						'2'  => esc_html__( '2 Days', 'foxiz' ),
						'3'  => esc_html__( '3 Days', 'foxiz' ),
						'7'  => esc_html__( '1 Week', 'foxiz' ),
						'14' => esc_html__( '2 Weeks', 'foxiz' ),
						'21' => esc_html__( '3 Weeks', 'foxiz' ),
						'30' => esc_html__( '1 Month', 'foxiz' ),
						'-1' => esc_html__( 'Always Display', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'slide_up_delay',
					'type'     => 'text',
					'title'    => esc_html__( 'Delay Time', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a delay time (ms) value to show the slide up after the site loaded.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'slide_up_bg',
					'title'    => esc_html__( 'Slide Up Background', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a background for this section.', 'foxiz' ),
					'type'     => 'color_rgba',
				],
				[
					'id'       => 'dark_slide_up_bg',
					'title'    => esc_html__( 'Dark Mode - Slide Up Background', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a background for this section in dark mode.', 'foxiz' ),
					'type'     => 'color_rgba',
				],
				[
					'id'          => 'slide_up_icon_color',
					'title'       => esc_html__( 'Button Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the slide up toggle button when activated.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'dark_slide_up_icon_color',
					'title'       => esc_html__( 'Dark Mode - Button Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the slide up toggle button when activated in dark mode.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'slide_up_na_icon_color',
					'title'       => esc_html__( 'Not Activate - Button Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the slide up toggle button when not activated.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
				[
					'id'          => 'slide_up_na_icon_bg',
					'title'       => esc_html__( 'Not Activate - Button Background', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a background color for the slide up toggle button when not activated.', 'foxiz' ),
					'type'        => 'color',
					'transparent' => false,
					'validate'    => 'color',
				],
			],
		];
	}
}