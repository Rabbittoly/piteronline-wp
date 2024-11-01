<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

add_action( 'elementor/element/common/_section_background/after_section_end', 'foxiz_widget_background_dark_mode', 10, 2 );
add_action( 'elementor/element/common/_section_border/after_section_end', 'foxiz_widget_border_dark_mode', 11, 2 );
add_action( 'elementor/element/section/section_background/after_section_end', 'foxiz_section_background_dark_mode', 10, 2 );
add_action( 'elementor/element/section/section_background_overlay/after_section_end', 'foxiz_section_overlay_dark_mode', 10, 2 );
add_action( 'elementor/element/section/section_structure/after_section_end', 'foxiz_section_header_sticky', 11, 2 );
add_action( 'elementor/element/section/section_border/after_section_end', 'foxiz_section_border_dark_mode', 12, 2 );
add_action( 'elementor/element/container/section_background/after_section_end', 'foxiz_container_background_dark_mode', 10, 2 );
add_action( 'elementor/element/container/section_background_overlay/after_section_end', 'foxiz_container_overlay_dark_mode', 10, 2 );
add_action( 'elementor/element/container/section_border/after_section_end', 'foxiz_container_border_dark_mode', 12, 2 );
add_action( 'elementor/element/container/section_layout_container/after_section_end', 'foxiz_container_header_sticky', 11, 2 );
add_action( 'elementor/element/column/section_border/after_section_end', 'foxiz_column_border_dark_mode', 10, 2 );
add_action( 'elementor/element/column/section_style/after_section_end', 'foxiz_column_background_dark_mode', 10, 2 );
add_action( 'elementor/element/column/section_background_overlay/after_section_end', 'foxiz_column_overlay_dark_mode', 10, 2 );
add_action( 'elementor/element/tabs/section_tabs_style/after_section_end', 'foxiz_tabs_dark_mode', 10, 2 );
add_action( 'elementor/element/heading/section_title_style/after_section_end', 'foxiz_block_heading_dark_mode', 10, 2 );
add_action( 'elementor/element/text-editor/section_style/after_section_end', 'foxiz_block_text_dark_mode', 10, 2 );
add_action( 'elementor/element/button/section_style/after_section_end', 'foxiz_block_button_dark_mode', 10, 2 );
add_action( 'elementor/element/divider/section_divider_style/after_section_end', 'foxiz_block_divider_dark_mode', 10, 2 );
add_action( 'elementor/element/image-box/section_style_content/after_section_end', 'foxiz_block_image_dark_mode', 10, 2 );
add_action( 'elementor/element/icon/section_style_icon/after_section_end', 'foxiz_block_icon_dark_mode', 10, 2 );
add_action( 'elementor/element/icon-box/section_style_content/after_section_end', 'foxiz_block_icon_box_dark_mode', 10, 2 );
add_action( 'elementor/element/icon-list/section_text_style/after_section_end', 'foxiz_block_icon_list_dark_mode', 10, 2 );
add_action( 'elementor/element/star-rating/section_stars_style/after_section_end', 'foxiz_block_star_rating_dark_mode', 10, 2 );
add_action( 'elementor/element/testimonial/section_style_testimonial_job/after_section_end', 'foxiz_block_testimonial_dark_mode', 10, 2 );
add_action( 'elementor/element/counter/section_title/after_section_end', 'foxiz_block_counter_dark_mode', 10, 2 );
add_action( 'elementor/element/social-icons/section_social_hover/after_section_end', 'foxiz_block_social_icons_dark_mode', 10, 2 );

if ( ! function_exists( 'foxiz_container_background_dark_mode' ) ) {
	function foxiz_container_background_dark_mode( $section, $args ) {

		/* header options */
		$section->start_controls_section(
			'foxiz_container_bg_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Background', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'dark_mode_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '[data-theme="dark"] {{WRAPPER}}',
			]
		);
		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_container_overlay_dark_mode' ) ) {
	function foxiz_container_overlay_dark_mode( $section, $args ) {

		$selector = '[data-theme="dark"] {{WRAPPER}}::before, [data-theme="dark"] {{WRAPPER}} > .elementor-background-video-container::before, [data-theme="dark"] {{WRAPPER}} > .e-con-inner > .elementor-background-video-container::before, [data-theme="dark"] {{WRAPPER}} > .elementor-background-slideshow::before, [data-theme="dark"] {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow::before';

		/* header options */
		$section->start_controls_section(
			'foxiz_container_overlay_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - BG Overlay', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'dark_mode_background_overlay',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector,
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_container_border_dark_mode' ) ) {
	function foxiz_container_border_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_container_border_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Border', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_border_color', [
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->add_control(
			'dark_border_hover_color', [
				'label'       => esc_html__( 'Hover - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_section_background_dark_mode' ) ) {
	function foxiz_section_background_dark_mode( $section, $args ) {

		/* header options */
		$section->start_controls_section(
			'foxiz_section_bg_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Background', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'dark_mode_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '[data-theme="dark"] {{WRAPPER}}.elementor-section',
			]
		);
		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_section_overlay_dark_mode' ) ) {
	function foxiz_section_overlay_dark_mode( $section, $args ) {

		/* header options */
		$section->start_controls_section(
			'foxiz_section_overlay_bg_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - BG Overlay', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'dark_mode_background_overlay',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '[data-theme="dark"] {{WRAPPER}} > .elementor-background-overlay',
			]
		);
		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_section_header_sticky' ) ) {
	function foxiz_section_header_sticky( $section, $args ) {

		if ( foxiz_is_ruby_template() ) {
			$section->start_controls_section(
				'foxiz_section_header', [
					'label'         => esc_html__( 'Foxiz - for Header Template', 'foxiz-core' ),
					'tab'           => Controls_Manager::TAB_LAYOUT,
					'hide_in_inner' => true,
				]
			);
			$section->add_control(
				'sticky_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'The settings below are used for the header template.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_control(
				'header_sticky',
				[
					'label'              => esc_html__( 'Sticky Header', 'foxiz-core' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Enable or disable the sticky for this section.', 'foxiz-core' ),
					'return_value'       => 'section-sticky',
					'prefix_class'       => 'e-',
					'render_type'        => 'none',
					'frontend_available' => true,
					'default'            => '',
				]
			);
			$section->add_control(
				'header_smart_sticky',
				[
					'label'        => esc_html__( 'Smart Sticky', 'foxiz-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => esc_html__( 'Only stick the main menu when scrolling up.', 'foxiz-core' ),
					'condition'    => [ 'header_sticky' => 'section-sticky' ],
					'return_value' => 'is-smart-sticky',
					'prefix_class' => '',
				]
			);
			$section->add_control(
				'sticky_bg_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Header Sticky Background.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_group_control(
				Group_Control_Background::get_type(), [
					'name'     => 'header_sticky_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '.sticky-on {{WRAPPER}}.elementor-section',
				]
			);
			$section->add_control(
				'dark_sticky_bg_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Dark Mode - Header Sticky Background.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_group_control(
				Group_Control_Background::get_type(), [
					'name'     => 'dark_header_sticky_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '.sticky-on[data-theme="dark"] {{WRAPPER}}.elementor-section',
				]
			);
			$section->end_controls_section();
		}
	}
}

if ( ! function_exists( 'foxiz_container_header_sticky' ) ) {
	function foxiz_container_header_sticky( $section, $args ) {

		if ( foxiz_is_ruby_template() ) {
			$section->start_controls_section(
				'foxiz_section_header', [
					'label'         => esc_html__( 'Foxiz - for Header Template', 'foxiz-core' ),
					'tab'           => Controls_Manager::TAB_LAYOUT,
					'hide_in_inner' => true,
				]
			);
			$section->add_control(
				'sticky_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'The settings below are used for the header template.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_control(
				'header_sticky',
				[
					'label'              => esc_html__( 'Sticky Header', 'foxiz-core' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Enable or disable the sticky for this section.', 'foxiz-core' ),
					'return_value'       => 'section-sticky',
					'prefix_class'       => 'e-',
					'render_type'        => 'none',
					'frontend_available' => true,
					'default'            => '',
				]
			);
			$section->add_control(
				'header_smart_sticky',
				[
					'label'        => esc_html__( 'Smart Sticky', 'foxiz-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => esc_html__( 'Only stick the main menu when scrolling up.', 'foxiz-core' ),
					'condition'    => [ 'header_sticky' => 'section-sticky' ],
					'return_value' => 'is-smart-sticky',
					'prefix_class' => '',
				]
			);
			$section->add_control(
				'sticky_bg_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Header Sticky Background.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_group_control(
				Group_Control_Background::get_type(), [
					'name'     => 'header_sticky_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '.sticky-on {{WRAPPER}}',
				]
			);
			$section->add_control(
				'dark_sticky_bg_info',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Dark Mode - Header Sticky Background.', 'foxiz' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
			$section->add_group_control(
				Group_Control_Background::get_type(), [
					'name'     => 'dark_header_sticky_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '.sticky-on[data-theme="dark"] {{WRAPPER}}',
				]
			);
			$section->end_controls_section();
		}
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_heading_dark_mode' ) ) {
	function foxiz_block_heading_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_heading_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_title_color', [
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_text_dark_mode' ) ) {
	function foxiz_block_text_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_text_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_title_color', [
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => 'color: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_button_dark_mode' ) ) {
	function foxiz_block_button_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_icon_section', [
				'label' => esc_html__( 'Foxiz Icon Size', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_responsive_control(
			'btn_icon_size', [
				'label'       => esc_html__( 'Icon Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify the icon size. This setting applies only to the icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{VALUE}}px;' ],
			]
		);
		$section->end_controls_section();
		$section->start_controls_section(
			'foxiz_button_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_button_text_color', [
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text button in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_button_bg_color', [
				'label'       => esc_html__( 'Background Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color for the button in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button' => 'background: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_button_border_color', [
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a border color for the button in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}}',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_button_text_color_hover', [
				'label'       => esc_html__( 'Hover - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text button when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button:hover, [data-theme="dark"] {{WRAPPER}} .elementor-button:focus'         => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-button:hover svg, [data-theme="dark"] {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_button_bg_color_hover', [
				'label'       => esc_html__( 'Hover - Background Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color for the button when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button:hover, [data-theme="dark"] {{WRAPPER}} .elementor-button:focus' => 'background: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_button_border_color_hover', [
				'label'       => esc_html__( 'Hover - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a border color for the button when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-button:hover, [data-theme="dark"] {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}}',
				],
				'default'     => '',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_divider_dark_mode' ) ) {
	function foxiz_block_divider_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_divider_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_divider_color', [
				'label'       => esc_html__( 'Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the divider in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--divider-color: {{VALUE}}', ],
				'default'     => '',
			]
		);

		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_image_dark_mode' ) ) {
	function foxiz_block_image_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_divider_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_title_color', [
				'label'       => esc_html__( 'Title Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the title in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .elementor-image-box-title' => 'color: {{VALUE}};', ],
				'default'     => '#ffffff',
			]
		);

		$section->add_control(
			'dark_desc_color', [
				'label'       => esc_html__( 'Description Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the description in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .elementor-image-box-description' => 'color: {{VALUE}};', ],
				'default'     => '#eeeeee',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_icon_dark_mode' ) ) {
	function foxiz_block_icon_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_divider_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_icon_color', [
				'label'       => esc_html__( 'Primary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a primary color for the icon in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon'                                                                            => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon, [data-theme="dark"] {{WRAPPER}}.elementor-view-default .elementor-icon'     => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon, [data-theme="dark"] {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->add_control(
			'dark_icon_secondary_color', [
				'label'       => esc_html__( 'Secondary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a secondary color for the icon in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon'      => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon'     => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'default'     => '',
				'condition'   => [ 'view!' => 'default', ],
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_icon_box_dark_mode' ) ) {
	function foxiz_block_icon_box_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_divider_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_icon_color', [
				'label'       => esc_html__( 'Icon Primary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a primary color for the icon in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon'                                                                        => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon, [data-theme="dark"] {{WRAPPER}}.elementor-view-default .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_icon_s_color', [
				'label'       => esc_html__( 'Icon Secondary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a secondary color for the icon in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon'  => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'default'     => '',
			]
		);

		$section->add_control(
			'dark_hover_icon_color', [
				'label'       => esc_html__( 'Hover - Icon Primary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a primary color for the icon when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon:hover'                                                                              => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon:hover, [data-theme="dark"] {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_hover_icon_s_color', [
				'label'       => esc_html__( 'Hover - Icon Secondary Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a secondary color for the icon when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-framed .elementor-icon:hover'  => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_title_color', [
				'label'       => esc_html__( 'Title Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the title in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-box-title' => 'color: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->add_control(
			'dark_description_color', [
				'label'       => esc_html__( 'Description Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the title in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-box-description' => 'color: {{VALUE}};',
				],
				'default'     => '#eeeeee',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_icon_list_dark_mode' ) ) {
	function foxiz_block_icon_list_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_divider_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_icon_color', [
				'label'       => esc_html__( 'Icon Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for icons in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-icon i'   => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->add_control(
			'dark_hover_icon_color', [
				'label'       => esc_html__( 'Hover - Icon Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for icons when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon i'   => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_text_color', [
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for text in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
				],
				'default'     => '#ffffff',
			]
		);
		$section->add_control(
			'dark_hover_text_color', [
				'label'       => esc_html__( 'Hover - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for text when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
				],
				'default'     => '',
			]
		);
		$section->add_control(
			'dark_divider_color', [
				'label'       => esc_html__( 'Divider Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the divider in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => [ 'divider' => 'yes' ],
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
				],
				'default'     => '#444444',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_section_border_dark_mode' ) ) {
	function foxiz_section_border_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_border_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Border', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_border_color', [
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->add_control(
			'dark_border_hover_color', [
				'label'       => esc_html__( 'Hover - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_widget_background_dark_mode' ) ) {
	function foxiz_widget_background_dark_mode( $section, $args ) {

		/* header options */
		$section->start_controls_section(
			'foxiz_widget_bg_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Background', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);
		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'dark_mode_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '[data-theme="dark"] {{WRAPPER}} > .elementor-widget-container',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_widget_border_dark_mode' ) ) {
	function foxiz_widget_border_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_border_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Border', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);
		$section->add_control(
			'dark_border_color', [
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} > .elementor-widget-container' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->add_control(
			'dark_border_hover_color', [
				'label'       => esc_html__( 'Hover - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}:hover > .elementor-widget-container' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_column_background_dark_mode' ) ) {
	function foxiz_column_background_dark_mode( $section, $args ) {

		$is_dome_optimization_active = Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		$main_selector_element       = $is_dome_optimization_active ? 'widget' : 'column';

		$section->start_controls_section(
			'foxiz_column_bg_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Background', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$section->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'column_dark_mode_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '[data-theme="dark"] {{WRAPPER}}:not(.elementor-motion-effects-element-type-background) > .elementor-' . $main_selector_element . '-wrap, [data-theme="dark"] {{WRAPPER}} > .elementor-' . $main_selector_element . '-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer',
			]
		);

		$section->end_controls_section();
	}
}

if ( ! function_exists( 'foxiz_column_overlay_dark_mode' ) ) {
	function foxiz_column_overlay_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_column_overlay_dark_mode', [
				'label'     => esc_html__( 'Foxiz Dark Mode - BG Overlay', 'foxiz-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$section->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'column_dark_background_overlay',
				'selector' => '[data-theme="dark"] {{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
			]
		);

		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_column_border_dark_mode' ) ) {
	function foxiz_column_border_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_column_border_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode - Border', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_border_color', [
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}} > .elementor-element-populated' => 'border-color: {{VALUE}}',
				],
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_tabs_dark_mode' ) ) {
	function foxiz_tabs_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_tabs_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'foxiz_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-tab-mobile-title, [data-theme="dark"] {{WRAPPER}} .elementor-tab-desktop-title.elementor-active, [data-theme="dark"] {{WRAPPER}} .elementor-tab-title:before, [data-theme="dark"] {{WRAPPER}} .elementor-tab-title:after, [data-theme="dark"] {{WRAPPER}} .elementor-tab-content, [data-theme="dark"] {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);
		$section->add_control(
			'dark_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-tab-desktop-title.elementor-active' => 'background-color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-tabs-content-wrapper'               => 'background-color: {{VALUE}};',
				],
			]
		);

		$section->add_control(
			'dark_tab_color',
			[
				'label'     => esc_html__( 'Title Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-tab-title, 
					[data-theme="dark"] {{WRAPPER}} .elementor-tab-title a' => 'color: {{VALUE}}',
				],
			]
		);
		$section->add_control(
			'dark_tab_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-tab-title.elementor-active,
					 [data-theme="dark"] {{WRAPPER}} .elementor-tab-title.elementor-active a' => 'color: {{VALUE}}',
				],
			]
		);
		$section->add_control(
			'dark_content_color',
			[
				'label'     => esc_html__( 'Content Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
				],
				'default'   => '#fff',
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_star_rating_dark_mode' ) ) {
	function foxiz_block_star_rating_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_star_rating_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_stars_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$section->add_control(
			'dark_stars_unmarked_color',
			[
				'label'     => esc_html__( 'Unmarked Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
				],
			]
		);
		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_testimonial_dark_mode' ) ) {
	function foxiz_block_testimonial_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_testimonial_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$section->add_control(
			'dark_content_content_color',
			[
				'label'     => esc_html__( 'Content - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$section->add_control(
			'dark_name_text_color',
			[
				'label'     => esc_html__( 'Name - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$section->add_control(
			'dark_job_text_color',
			[
				'label'     => esc_html__( 'Job - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_counter_dark_mode' ) ) {
	function foxiz_block_counter_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_counter_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_number_color',
			[
				'label'     => esc_html__( 'Number Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			]
		);
		$section->add_control(
			'dark_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$section->end_controls_section();
	}
}

/**
 * @param $section
 * @param $args
 */
if ( ! function_exists( 'foxiz_block_social_icons_dark_mode' ) ) {
	function foxiz_block_social_icons_dark_mode( $section, $args ) {

		$section->start_controls_section(
			'foxiz_social_icons_dark_mode', [
				'label' => esc_html__( 'Foxiz Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$section->add_control(
			'dark_icon_primary_color',
			[
				'label'     => esc_html__( 'Primary Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		$section->add_control(
			'dark_icon_secondary_color',
			[
				'label'     => esc_html__( 'Secondary Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon i'   => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$section->add_control(
			'dark_hover_icon_primary_color',
			[
				'label'     => esc_html__( 'Hover - Primary Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$section->add_control(
			'dark_hover_icon_secondary_color',
			[
				'label'     => esc_html__( 'Hover - Secondary Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon:hover i'   => 'color: {{VALUE}};',
					'[data-theme="dark"] {{WRAPPER}} .elementor-social-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$section->end_controls_section();
	}
}