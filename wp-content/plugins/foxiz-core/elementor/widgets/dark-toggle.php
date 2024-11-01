<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_dark_mode_switcher;

/**
 * Class Dark_Mode_Toggle
 *
 * @package foxizElementor\Widgets
 */
class Dark_Mode_Toggle extends Widget_Base {

	public function get_name() {

		return 'foxiz-dark-mode-toggle';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Dark Mode Toggle', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-adjust';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'dark', 'template', 'builder', 'light', 'switcher' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label'       => esc_html__( 'Switcher Size Scale', 'foxiz-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Change dark mode switcher size.', 'foxiz-core' ),
				'size_units'  => [ '%' ],
				'range'       => [
					'%' => [
						'min' => 50,
						'max' => 150,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'   => [
					'{{WRAPPER}}' => '--dm-size: calc(24px * {{SIZE}}/100);',
				],
			]
		);
		$this->add_responsive_control(
			'align', [
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-align-start-h',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-align-center-h',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-align-end-h',
					],
				],
				'selectors' => [ '{{WRAPPER}} .dark-mode-toggle' => 'justify-content: {{VALUE}};', ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'light-mode-section', [
				'label' => esc_html__( 'Switcher - Light Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'light_color',
			[
				'label'       => esc_html__( 'Icon - Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the sun icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .dark-mode-slide .svg-mode-light' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'light_background',
			[
				'label'       => esc_html__( 'Icon - Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the sun icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .dark-mode-slide .mode-icon-default' => 'background-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'light_divider',
			[
				'label'       => esc_html__( 'Slide Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the slider in light mode.', 'foxiz-core' ),
				'selectors'   => [ 'body:not([data-theme="dark"]) {{WRAPPER}} .dark-mode-slide' => 'background-color: {{VALUE}};' ],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'dark-mode-section', [
				'label' => esc_html__( 'Switcher - Dark Mode', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'dark_text_color',
			[
				'label'       => esc_html__( 'Icon - Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the moon icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .dark-mode-slide .svg-mode-dark' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_color',
			[
				'label'       => esc_html__( 'Icon - Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the moon icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .dark-mode-slide .mode-icon-dark' => 'background: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_divider',
			[
				'label'       => esc_html__( 'Slide Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the slider in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .dark-mode-slide' => 'background-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_dark_mode_switcher' ) ) {
			foxiz_dark_mode_switcher();
		}
	}
}