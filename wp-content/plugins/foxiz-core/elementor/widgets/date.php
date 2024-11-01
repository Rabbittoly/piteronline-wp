<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use function foxiz_current_date;

/**
 * Class Current_Date
 *
 * @package foxizElementor\Widgets
 */
class Current_Date extends Widget_Base {

	public function get_name() {

		return 'foxiz-current-date';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Current Date', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-calendar';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'mobile', 'date', 'today', 'current' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content-section', [
				'label' => esc_html__( 'Date Format', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'date_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Consider setting up an automatic cache clearance at midnight (00:00) to ensure accurate display of the current date.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'format',
			[
				'label'       => esc_html__( 'Date Format', 'foxiz-core' ),
				'description' => esc_html__( 'Input the date format.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 1,
				'ai'          => [ 'active' => false ],
				'default'     => 'l, M j, Y',
				'placeholder' => 'l, M j, Y',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_align',
			[
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'default'   => '',
				'selectors' => [ '{{WRAPPER}}' => 'text-align: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Date Font', 'foxiz-core' ),
				'name'     => 'date_font',
				'selector' => '{{WRAPPER}} .current-date',
			]
		);
		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--meta-fcolor: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_color',
			[
				'label'     => esc_html__( 'Dark Mode - Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--meta-fcolor: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_current_date' ) ) {
			foxiz_current_date( $this->get_settings() );
		}
	}
}