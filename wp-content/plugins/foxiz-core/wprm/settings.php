<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_wprm_supported' ) ) {
	function foxiz_register_options_wprm_supported() {

		if ( ! class_exists( 'WP_Recipe_Maker' ) ) {
			return [
				'id'     => 'foxiz_config_section_wprm_supported',
				'title'  => esc_html__( 'WP Recipe Maker', 'foxiz-core' ),
				'desc'   => esc_html__( 'Enable styling support for the WP Recipe Maker plugin, compatible with dark mode.', 'foxiz-core' ),
				'icon'   => 'el el-tasks',
				'fields' => [
					[
						'id'    => 'wprm_install_warning',
						'type'  => 'info',
						'style' => 'warning',
						'desc'  => html_entity_decode( esc_html__( 'The WP Recipe Maker Plugin is missing! Please install and activate the <a href="https://wordpress.org/plugins/wp-recipe-maker/">WP Recipe Maker</a> plugin to enable the settings.', 'foxiz-core' ) ),
					],
				],
			];
		}

		return [
			'id'     => 'foxiz_config_section_wprm_supported',
			'title'  => esc_html__( 'WP Recipe Maker', 'foxiz-core' ),
			'desc'   => esc_html__( 'Enable styling support for the WP Recipe Maker plugin, compatible with dark mode.', 'foxiz-core' ),
			'icon'   => 'el el-tasks',
			'fields' => [
				[
					'id'       => 'wprm_supported',
					'type'     => 'switch',
					'title'    => esc_html__( 'Load Styles', 'foxiz-core' ),
					'subtitle' => esc_html__( 'Customize WP Recipe Maker styling to match the theme aesthetics.', 'foxiz-core' ),
					'default'  => 1,
				],
				[
					'id'          => 'wprm_pinterest',
					'type'        => 'select',
					'title'       => esc_html__( 'Pinterest Script', 'foxiz-core' ),
					'subtitle'    => esc_html__( 'Force to disable the Pinterest library script of this plugin.', 'foxiz-core' ),
					'description' => esc_html__( 'Disabling the script of the plugin will fix the layout of the "Share on Pinterest" button.', 'foxiz-core' ),
					'options'     => [
						'1'  => esc_html__( 'Enable', 'foxiz-core' ),
						'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					],
					'default'     => '-1',
				],
				[
					'id'       => 'wprm_toc',
					'type'     => 'select',
					'title'    => esc_html__( 'Table of Contents Included', 'foxiz-core' ),
					'subtitle' => esc_html__( 'Include the heading of the WP Recipe Maker to table of contents.', 'foxiz-core' ),
					'options'  => [
						'1'  => esc_html__( 'Enable', 'foxiz-core' ),
						'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					],
					'default'  => '1',
				],
			],
		];
	}
}
