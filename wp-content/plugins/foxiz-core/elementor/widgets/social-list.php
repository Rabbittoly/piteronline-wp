<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_elementor_social_list;

/**
 * Class Social_List
 *
 * @package foxizElementor\Widgets
 */
class Social_List extends Widget_Base {

	public function get_name() {

		return 'foxiz-social-list';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Social List', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-social-icons';
	}

	public function get_keywords() {

		return [ 'template', 'builder', 'fan', 'follow', 'icon' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'layout_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block will get information from Theme Options > Social Profiles to show.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'       => esc_html__( 'Icon Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom font size for the social icons.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-social-list i' => 'font-size: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'icon_height',
			[
				'label'       => esc_html__( 'Icon Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom height value for the social icons.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .header-social-list i' => 'line-height: {{VALUE}}px;',
					'{{WRAPPER}} .header-social-list'   => 'line-height: 1;',
				],
			]
		);
		$this->add_responsive_control(
			'item_spacing', [
				'label'       => esc_html__( 'Item Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom spacing (in pixels) between social list items. Please note: The icon has left and right padding of 5 pixels for ease of targeting.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--icon-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'align', [
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-align-center-h',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-align-end-h',
					],
				],
				'selectors' => [ '{{WRAPPER}} .header-social-list' => 'text-align: {{VALUE}};', ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'color-section', [
				'label' => esc_html__( 'Colors', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'       => esc_html__( 'Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the social icons.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} .header-social-list' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_icon_color',
			[
				'label'       => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the social icons in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .header-social-list' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'stitle-section', [
				'label' => esc_html__( 'Headline Sticky', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'hide_on_stitle',
			[
				'label'       => esc_html__( 'Hide when Stick Single Headline', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'This option is useful (avoid duplicate icons) if you enable share on social icons when single headline is sticking.', 'foxiz-core' ),
				'default'     => false,
				'selectors'   => [ '.yes-tstick.sticky-on {{WRAPPER}}' => 'display: none;' ],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		foxiz_elementor_social_list();
	}
}