<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use function foxiz_get_breadcrumb;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Breadcrumb extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-breadcrumb';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Breadcrumb', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-theme-builder';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'breadcrumb' ];
	}

	public function get_categories() {

		return [ 'foxiz_single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'breadcrumb_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block requests Breadcrumb NavXT or Yoast Breadcrumb or Rankmath SEO Breadcrumb in oder to work.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'breadcrumb_color',
			[
				'label'     => esc_html__( 'Breadcrumb Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--bcrumb-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_breadcrumb_color',
			[
				'label'     => esc_html__( 'Dark Mode - Breadcrumb Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--bcrumb-color {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => '',
				'selectors' => [ '{{WRAPPER}}' => 'text-align: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Breadcrumb Font', 'foxiz-core' ),
				'name'     => 'breadcrumb_font',
				'selector' => '{{WRAPPER}} .breadcrumb-inner',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		echo foxiz_get_breadcrumb();
	}

}