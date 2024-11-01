<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use function foxiz_elementor_custom_field_meta;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Custom_Meta extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-custom-meta';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Custom Field Meta', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-meta-data';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'meta' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section', [
				'label' => esc_html__( 'Custom Field Meta', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'meta_id',
			[
				'label'       => esc_html__( 'Custom Field ID/name', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
				'description' => esc_html__( 'Input your own custom field ID that created by any party plugin to display.', 'foxiz-core' ),
				'placeholder' => esc_html__( 'filed_id', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->add_control(
			'meta_label',
			[
				'label'       => esc_html__( 'Meta Tagline', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
				'description' => esc_html__( 'Input a tagline for this meta', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->add_control(
			'meta_icon',
			[
				'label'            => esc_html__( 'Meta Icon', 'foxiz-core' ),
				'type'             => Controls_Manager::ICONS,
				'description'      => esc_html__( 'Select an icon for this meta', 'foxiz-core' ),
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);
		$this->add_control(
			'icon_position',
			[
				'label'   => esc_html__( 'Icon Position', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'begin' => esc_html__( 'Begin', 'foxiz' ),
					'end'   => esc_html__( 'End', 'foxiz' ),
				],
				'default' => 'begin',
			]
		);
		$this->add_control(
			'label_position',
			[
				'label'   => esc_html__( 'Tagline Position', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'begin' => esc_html__( 'Begin', 'foxiz' ),
					'end'   => esc_html__( 'End', 'foxiz' ),
				],
				'default' => 'end',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'meta_style_section', [
				'label' => esc_html__( 'for Meta', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}}',
			]
		);
		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Meta Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--meta-fcolor: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_meta_color',
			[
				'label'     => esc_html__( 'Dark Mode - Meta Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--meta-fcolor: {{VALUE}};' ],
			]
		);
		$this->add_responsive_control(
			'meta_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--meta-spacing: {{VALUE}}px;' ],
				'default'   => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'meta_label_section', [
				'label' => esc_html__( 'for Tagline', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Tagline Font', 'foxiz-core' ),
				'name'     => 'meta_label_font',
				'selector' => '{{WRAPPER}} .meta-tagline',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'icon_style_section', [
				'label' => esc_html__( 'for Icon', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Icon Font Size', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--meta-icon-size: {{VALUE}}px;' ],
				'default'   => '',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--meta-icon-color: {{VALUE}};' ],
				'default'   => '',
			]
		);
		$this->add_control(
			'dark_icon_color',
			[
				'label'     => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--meta-icon-color: {{VALUE}};' ],
				'default'   => '',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		$settings = $this->get_settings();
		foxiz_elementor_custom_field_meta( $settings );
	}
}