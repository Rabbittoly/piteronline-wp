<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_breadcrumb' ) ) {
	function foxiz_register_options_breadcrumb() {

		return [
			'title'  => esc_html__( 'Breadcrumb Bar', 'foxiz' ),
			'id'     => 'foxiz_config_section_breadcrumb',
			'desc'   => esc_html__( 'The theme supports Navxt plugin Yoast SEO and Rank Math SEO breadcrumbs.', 'foxiz' ),
			'icon'   => 'el el-random',
			'fields' => [
				[
					'id'    => 'info_breadcrumb',
					'type'  => 'info',
					'style' => 'warning',
					'desc'  => esc_html__( 'The settings below request the Navxt plugin, Yoast SEO or Rank Math SEO breadcrumbs to run.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_breadcrumb_global',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Global', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'breadcrumb',
					'type'     => 'switch',
					'title'    => esc_html__( 'Breadcrumb Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb bar for your site.', 'foxiz' ),
					'description' => esc_html__( 'This setting will turn off the breadcrumb bar for your whole site.', 'foxiz' ),
					'default'  => true,
				],
				[
					'id'       => 'breadcrumb_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Wrapping Text', 'foxiz' ),
					'subtitle' => esc_html__( 'Allow long words to be able to break and wrap onto the next line.', 'foxiz' ),
					'options'  => [
						'0'    => esc_html__( 'No Wrap', 'foxiz' ),
						'wrap' => esc_html__( 'Line Wrap', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'     => 'section_end_breadcrumb_global',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_page_breadcrumb',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Page Breadcrumbs', 'foxiz' ),
					'indent' => true,
				],
				[
					'id'       => 'single_post_breadcrumb',
					'title'    => esc_html__( 'Single Post Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb bar in the single post.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'single_page_breadcrumb',
					'title'    => esc_html__( 'Single Page Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb bar in the single page.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'category_breadcrumb',
					'title'    => esc_html__( 'Category Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb in the category pages.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'author_breadcrumb',
					'title'    => esc_html__( 'Author Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb in the author pages.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'       => 'archive_breadcrumb',
					'title'    => esc_html__( 'Archive Breadcrumb', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the breadcrumb in the archive pages.', 'foxiz' ),
					'type'     => 'select',
					'options'  => [
						'1' => esc_html__( 'Use Global Setting', 'foxiz' ),
						'0' => esc_html__( 'Disable', 'foxiz' ),
					],
					'default'  => '1',
				],
				[
					'id'     => 'section_end_page_breadcrumb',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false,
				],
			],
		];
	}
}