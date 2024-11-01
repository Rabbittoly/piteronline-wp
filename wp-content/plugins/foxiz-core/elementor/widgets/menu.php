<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_elementor_sidebar_menu;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Sidebar_Menu extends Widget_Base {

	public function get_name() {

		return 'foxiz-sidebar-menu';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Sidebar Menu', 'foxiz-core' );
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'template', 'builder', 'navigation', 'sidebar', 'vertical' ];
	}

	public function get_icon() {

		return 'eicon-nav-menu';
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general_section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$menus = $this->get_menus();
		$this->add_control(
			'menu', [
				'label'        => esc_html__( 'Assign Menu', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a menu for this block.', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'multiple'     => false,
				'options'      => $menus,
				'default'      => ! empty( array_keys( $menus )[0] ) ? array_keys( $menus )[0] : '',
				'save_default' => true,
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'foxiz-core' ),
				'description' => esc_html__( 'Input a heading, Support the i tag (raw HTML) for displaying icon. e.g: <i class="rbi rbi-trending"></i> Your Heading', 'foxiz-core' ),
				'placeholder' => esc_html__( 'About US', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'heading_section', [
				'label' => esc_html__( 'Heading', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'       => esc_html__( 'Heading HTML Tag', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::heading_html_description(),
				'options'     => Options::heading_html_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Heading Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .menu-heading',
			]
		);
		$this->add_responsive_control(
			'title_spacing', [
				'label'       => esc_html__( 'Bottom Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Set the spacing between the menu heading and the content.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--h-menu-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'       => esc_html__( 'Heading Color', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a color for the menu heading.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--h-menu-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_title_color',
			[
				'label'       => esc_html__( 'Dark Mode - Heading Color', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a color for the menu heading in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--h-menu-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'Layout', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'menu_layout', [
				'label'       => esc_html__( 'Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a menu layout. Horizontal layout will not support menu depths.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Vertical', 'foxiz-core' ),
					'1' => esc_html__( 'Horizontal', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'menu_item_spacing', [
				'label'       => esc_html__( 'Item Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom spacing between menu item.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--sidebar-menu-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
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
				'selectors' => [ '{{WRAPPER}}' => '--menu-align : {{VALUE}};', ],
			]
		);

		$this->start_controls_tabs( 'main_item_tabs' );
		$this->start_controls_tab( 'main_item_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'menu_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for displaying in the navigation bar of this header.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .sidebar-menu a > span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'menu_dark_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for displaying in the navigation bar of this header in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .sidebar-menu a > span' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'main_item_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'menu_hover_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .sidebar-menu a:hover > span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'menu_dark_hover_color',
			[
				'label'       => esc_html__( 'Dark Mode -  Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .sidebar-menu a:hover > span' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'font_section', [
				'label' => esc_html__( 'Font & Font Size', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Menu Font', 'foxiz-core' ),
				'name'     => 'menu_font',
				'selector' => '{{WRAPPER}} .sidebar-menu a',
			]
		);
		$this->add_responsive_control(
			'sub_font_size', [
				'label'       => esc_html__( 'Sub Menu Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom font size value for the sub menu item.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .sidebar-menu ul.sub-menu a' => 'font-size: {{VALUE}}px !important;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'vertical_divider_section', [
				'label' => esc_html__( 'for Vertical', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'bottom_border',
			[
				'label'        => esc_html__( 'Bottom Border', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => Options::bottom_border_description(),
				'options'      => Options::column_border_dropdown(),
				'prefix_class' => 'mborder-',
				'default'      => '0',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'hoz_divider_section', [
				'label' => esc_html__( 'for Horizontal', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'hoz_divider_style',
			[
				'label'        => esc_html__( 'Horizontal Divider Style', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a divider style to display between menu items in the horizontal layout.', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => Options::menu_divider_dropdown(),
				'default'      => '0',
				'prefix_class' => 'is-divider-',
			]
		);
		$this->add_control(
			'divider_color',
			[
				'label'       => esc_html__( 'Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for divider.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--divider-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_divider_color',
			[
				'label'       => esc_html__( 'Dark Mode - Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for divider in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--divider-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
	}

	protected function get_menus() {

		$menus   = wp_get_nav_menus();
		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * render layout
	 */
	protected function render() {

		$settings = $this->get_settings();
		foxiz_elementor_sidebar_menu( $settings );
	}
}