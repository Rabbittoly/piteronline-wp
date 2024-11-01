<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_adblock' ) ) {
	function foxiz_register_options_adblock() {

		return [
			'id'     => 'foxiz_config_section_adblock',
			'title'  => esc_html__( 'AdBlock Detector', 'foxiz' ),
			'desc'   => esc_html__( 'Detecting most of the AdBlock extensions and show a popup to disable the extension.', 'foxiz' ),
			'icon'   => 'el el-minus-sign',
			'fields' => [
				[
					'id'       => 'adblock_detector',
					'title'    => esc_html__( 'AdBlock Detector', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the adblock detector.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				],
				[
					'id'       => 'adblock_title',
					'title'    => esc_html__( 'Title', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a title for the adblock popup.', 'foxiz' ),
					'type'     => 'text',
					'default'  => esc_html__( 'AdBlock Detected', 'foxiz' ),
				],
				[
					'id'       => 'adblock_description',
					'title'    => esc_html__( 'Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a description for the adblock popup.', 'foxiz' ),
					'default'  => esc_html__( 'Our site is an advertising supported site. Please whitelist to support our site.', 'foxiz' ),
					'type'     => 'textarea',
					'rows'     => 2,
				],
			],
		];
	}
}
