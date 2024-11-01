<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Tagline extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-tagline';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Post Tagline', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-text';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'title', 'subtitle', 'tagline' ];
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
			'title_color',
			[
				'label'     => esc_html__( 'Tagline Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--tagline-fcolor: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_dark_color',
			[
				'label'     => esc_html__( 'Dark Mode - Tagline Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--tagline-fcolor: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_align',
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
				'label'    => esc_html__( 'Tagline Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .s-tagline',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<h3 class="s-tagline">' . esc_html__( 'Dynamic post tagline will replaced width the real tagline after your assigned this template', 'foxiz-core' ) . '</h3>';
		} else {
			if ( function_exists( 'foxiz_single_tagline' ) ) {
				foxiz_single_tagline();
			}
		}
	}

}