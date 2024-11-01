<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_elementor_cta;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class CTA extends Widget_Base {

	public function get_name() {

		return 'foxiz-cta';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Call to Action', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-image-rollover';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'call', 'action', 'button' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'description' => esc_html__( 'Input a heading for this block', 'foxiz-core' ),
				'default'     => 'Add Your Heading Text Here',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Heading HTML Tag', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Options::heading_html_dropdown( false ),
				'default' => 'h2',
			]
		);
		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'description' => esc_html__( 'Input a description for the call to action, allowing limited HTML tags (a, strong, p).', 'foxiz-core' ),
				'default'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
			]
		);
		$this->add_control(
			'description_tag',
			[
				'label'   => esc_html__( 'Description HTML Tag', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Options::heading_html_dropdown( false ),
				'default' => 'p',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_image',
			[
				'label' => esc_html__( 'Image', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'foxiz-core' ),
				'type'    => Controls_Manager::MEDIA,
				'ai'      => [ 'active' => false ],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'dark_image',
			[
				'label'   => esc_html__( 'Dark Mode - Choose Image', 'foxiz-core' ),
				'type'    => Controls_Manager::MEDIA,
				'ai'      => [ 'active' => false ],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'image_size',
			[
				'label'       => esc_html__( 'Image Size', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::crop_size(),
				'options'     => Options::crop_size_dropdown(),
				'default'     => '0',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_btn',
			[
				'label' => esc_html__( 'Buttons', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'btn_1_description',
			[
				'label'     => esc_html__( 'Button 1', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_label_1',
			[
				'label' => esc_html__( 'Label', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXT,
				'ai'    => [ 'active' => false ],
			]
		);
		$this->add_control(
			'btn_link_1',
			[
				'label' => esc_html__( 'Link', 'foxiz-core' ),
				'type'  => Controls_Manager::URL,
			]
		);
		$this->add_control(
			'btn_2_description',
			[
				'label'     => esc_html__( 'Button 2', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_label_2',
			[
				'label' => esc_html__( 'Label', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXT,
				'ai'    => [ 'active' => false ],
			]
		);
		$this->add_control(
			'btn_link_2',
			[
				'label' => esc_html__( 'Link', 'foxiz-core' ),
				'type'  => Controls_Manager::URL,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'block_link_section',
			[
				'label' => esc_html__( 'Block Link', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'img_link',
			[
				'label'       => esc_html__( 'Block Link', 'foxiz-core' ),
				'description' => esc_html__( 'Specify the URL to navigate to when hovering over the entire block or the featured image.', 'foxiz-core' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'img_link_apply',
			[
				'label'       => esc_html__( 'Apply When', 'foxiz-core' ),
				'description' => esc_html__( 'Select when the link should apply', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					''    => esc_html__( 'Hover on Featured Image', 'foxiz-core' ),
					'all' => esc_html__( 'Hover over the Entire Block', 'foxiz-core' ),
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
			]
		);
		$this->add_control(
			'vertical_align',
			[
				'label'     => esc_html__( 'Vertical Align', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'foxiz-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => esc_html__( 'Middle', 'foxiz-core' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cta-content' => 'justify-content: {{VALUE}};',
				],

			]
		);
		$this->add_responsive_control(
			'max_width',
			[
				'label'     => esc_html__( 'Max Width', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-content-max: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'title_description',
			[
				'label'     => esc_html__( 'Heading', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .cta-title',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--cta-title-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_title_color',
			[
				'label'     => esc_html__( 'Dark Mode Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--cta-title-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label'     => esc_html__( 'Spacing', 'foxiz-core' ) . ' (px)',
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-title-space: {{VALUE}}px;',
				],
				'default'   => 10,
			]
		);
		$this->add_control(
			'desc_description',
			[
				'label'     => esc_html__( 'Description', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_font',
				'selector' => '{{WRAPPER}} .cta-description',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--cta-desc-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_desc_color',
			[
				'label'     => esc_html__( 'Dark Mode Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--cta-desc-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'position',
			[
				'label'   => esc_html__( 'Image Position', 'foxiz-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'top'    => [
						'title' => esc_html__( 'Top', 'foxiz-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => esc_html__( 'bottom', 'foxiz-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'toggle'  => false,
			]
		);
		$this->add_responsive_control(
			'image_width',
			[
				'label'          => esc_html__( 'Width', 'foxiz-core' ) . ' (%)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%' ],
				'range'          => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}}' => '--cta-img-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'image_space',
			[
				'label'     => esc_html__( 'Spacing (px)', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-img-space: {{VALUE}}px;',
				],
				'default'   => 10,
			]
		);
		$this->add_control(
			'img_mobile_hide',
			[
				'label'     => esc_html__( 'Hide on Mobile', 'foxiz-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-m-img-display: none',
				],
			]
		);
		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => esc_html__( 'Opacity', 'foxiz-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label'     => esc_html__( 'Opacity', 'foxiz-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover img',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label'      => esc_html__( 'Transition Duration', 'foxiz-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default'    => [
					'unit' => 'ms',
				],
				'selectors'  => [
					'{{WRAPPER}} img' => 'transition-duration: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'img_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'foxiz-core' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} img',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'btn_space',
			[
				'label'     => esc_html__( 'Spacing (px)', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-btn-space: {{VALUE}}px;',
				],
				'default'   => 10,
			]
		);
		$this->add_responsive_control(
			'btn_gap',
			[
				'label'     => esc_html__( 'Gap', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--cta-btn-gap: {{VALUE}}px;',
				],
				'default'   => 5,
			]
		);
		$this->add_responsive_control(
			'btn_border',
			[
				'label'      => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_btn_1',
			[
				'label' => esc_html__( 'for Button 1', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_1_font',
				'selector' => '{{WRAPPER}} .cta-btn-1',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_1_border',
				'selector' => '{{WRAPPER}} .cta-btn-1',
			]
		);
		$this->add_responsive_control(
			'btn_1_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .cta-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'btn_1_effects' );
		$this->start_controls_tab( 'btn_1_normal',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_1_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_1_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--btn-1-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_1_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .cta-btn-1',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_1_normal_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .cta-btn-1',
			]
		);
		$this->add_control(
			'btn_1_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_1_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--btn-1-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_1_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .cta-btn-1' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_1_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .cta-btn-1',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'btn_1_hover',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_1_hover_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_1_hover_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--btn-1-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_1_border_hover_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cta-btn-1:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_1_hover_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .cta-btn-1:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_1_hover_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .cta-btn-1:hover',
			]
		);
		$this->add_control(
			'btn_1_hover_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_1_hover_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--btn-1-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_1_hover_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .cta-btn-1:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_1_hover_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .cta-btn-1:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_control(
			'btn_1_transition',
			[
				'label'      => esc_html__( 'Transition Duration', 'foxiz-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default'    => [
					'unit' => 'ms',
				],
				'selectors'  => [
					'{{WRAPPER}} .cta-btn-1' => 'transition-duration: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'btn_1_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'foxiz-core' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_btn_2',
			[
				'label' => esc_html__( 'for Button 2', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_2_font',
				'selector' => '{{WRAPPER}} .cta-btn-2',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_2_border',
				'selector' => '{{WRAPPER}} .cta-btn-2',
			]
		);
		$this->add_responsive_control(
			'btn_2_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .cta-btn-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'btn_2_effects' );
		$this->start_controls_tab( 'btn_2_normal',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_2_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_2_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--btn-2-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_2_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .cta-btn-2',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_2_normal_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .cta-btn-2',
			]
		);
		$this->add_control(
			'btn_2_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_2_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--btn-2-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_2_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .cta-btn-2' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_2_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .cta-btn-2',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'btn_2_hover',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_2_hover_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_2_hover_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--btn-2-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_2_border_hover_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cta-btn-2:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_2_hover_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .cta-btn-2:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_2_hover_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .cta-btn-2:hover',
			]
		);
		$this->add_control(
			'btn_2_hover_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_2_hover_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--btn-2-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_2_hover_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .cta-btn-2:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_2_hover_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .cta-btn-2:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_control(
			'btn_2_transition',
			[
				'label'      => esc_html__( 'Transition Duration', 'foxiz-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default'    => [
					'unit' => 'ms',
				],
				'selectors'  => [
					'{{WRAPPER}} .cta-btn-2' => 'transition-duration: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'btn_2_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'foxiz-core' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();
		foxiz_elementor_cta( $settings );
	}

}