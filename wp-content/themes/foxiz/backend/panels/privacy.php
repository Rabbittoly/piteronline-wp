<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_privacy' ) ) {
	function foxiz_register_options_privacy() {

		return [
			'id'     => 'foxiz_theme_ops_section_privacy',
			'title'  => esc_html__( 'Privacy Notice', 'foxiz' ),
			'desc'   => esc_html__( 'Customize the privacy notice popup bar.', 'foxiz' ),
			'icon'   => 'el el-exclamation-sign',
			'fields' => [
				[
					'id'       => 'privacy_bar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Privacy Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the privacy bar.', 'foxiz' ),
					'default'  => '0',
				],
				[
					'id'       => 'privacy_text',
					'type'     => 'textarea',
					'rows'     => 2,
					'title'    => esc_html__( 'Content', 'foxiz' ),
					'subtitle' => esc_html__( 'Input your privacy or cookie content for your site, allow raw HTML.', 'foxiz' ),
					'default'  => html_entity_decode( esc_html__( 'By using this site, you agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of Use</a>.', 'foxiz' ) ),
				],
				[
					'id'       => 'privacy_position',
					'type'     => 'select',
					'title'    => esc_html__( 'Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a position to display the privacy bar.', 'foxiz' ),
					'options'  => [
						'left'   => esc_html__( 'Fixed Left', 'foxiz' ),
						'0'      => esc_html__( 'Top Site', 'foxiz' ),
						'bottom' => esc_html__( 'Fixed Bottom', 'foxiz' ),
					],
					'default'  => 'left',
				],
				[
					'id'       => 'privacy_width',
					'type'     => 'select',
					'title'    => esc_html__( 'Max Width', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a max width for the top position.', 'foxiz' ),
					'options'  => [
						'wrap' => esc_html__( 'Wrapper', 'foxiz' ),
						'wide' => esc_html__( 'Full Wide', 'foxiz' ),
					],
					'default'  => 'wrap',
				],
				[
					'id'          => 'privacy_bg_color',
					'type'        => 'color',
					'title'       => esc_html__( 'Background Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for this box background.', 'foxiz' ),
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'privacy_text_color',
					'type'        => 'color',
					'title'       => esc_html__( 'Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the text.', 'foxiz' ),
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'dark_privacy_bg_color',
					'type'        => 'color',
					'title'       => esc_html__( 'Dark Mode - Background Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for this box background in dark mode.', 'foxiz' ),
					'transparent' => false,
					'default'     => '',
				],
				[
					'id'          => 'dark_privacy_text_color',
					'type'        => 'color',
					'title'       => esc_html__( 'Dark Mode - Text Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the text in dark mode.', 'foxiz' ),
					'transparent' => false,
					'default'     => '',
				],
			],
		];
	}
}
