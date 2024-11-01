<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_elementor_main_menu;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Navigation extends Widget_Base {

	public function get_name() {

		return 'foxiz-navigation';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Menu Navigation', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-navigation-horizontal';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'template', 'builder', 'menu', 'main', 'mega', 'horizontal' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
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
			'main_menu', [
				'label'        => esc_html__( 'Assign Menu', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a menu for your site.', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'multiple'     => false,
				'options'      => $menus,
				'default'      => ! empty( array_keys( $menus )[0] ) ? array_keys( $menus )[0] : '',
				'save_default' => true,
			]
		);
		$this->add_control(
			'is_main_menu',
			[
				'label'       => esc_html__( 'Set as Main Menu', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Set this is the main site menu, This option help the site to understand where to add the single sticky headline.', 'foxiz-core' ),
				'default'     => 'yes',
			]
		);
		$this->add_control(
			'menu_more',
			[
				'label'       => esc_html__( 'More Menu Button', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Enable or disable the more button at the end of the navigation.', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'root_level_section', [
				'label' => esc_html__( 'Top-Level Menu Items', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Main Menu Font', 'foxiz-core' ),
				'name'     => 'menu_font',
				'selector' => '{{WRAPPER}} .main-menu > li > a',
			]
		);
		$this->add_control(
			'menu_height', [
				'label'       => esc_html__( 'Menu Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom height value (in pixels) for this menu. Default is 60.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--nav-height: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'menu_sticky_height', [
				'label'       => esc_html__( 'Sticky Menu Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom height value (in pixels) for this menu when sticking if it is enabled.', 'foxiz-core' ),
				'selectors'   => [ '.sticky-on {{WRAPPER}}' => '--nav-height: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'menu_item_spacing', [
				'label'       => esc_html__( 'Item Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom spacing between menu item. Default is 12.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--menu-item-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'menu_edge_spacing',
			[
				'label'        => esc_html__( 'No Edge Spacing', 'foxiz-core' ),
				'description'  => esc_html__( 'Enable or disable the left spacing of the first menu item.', 'foxiz-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'no-edge',
				'prefix_class' => '',
				'default'      => 'no',
			]
		);
		$this->add_control(
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
				'selectors' => [ '{{WRAPPER}} .main-menu-wrap' => 'justify-content: {{VALUE}};', ],
			]
		);
		$this->start_controls_tabs( 'top_item_tabs' );
		$this->start_controls_tab( 'top_item_normal_tab',
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
				'selectors'   => [ '{{WRAPPER}}' => '--nav-color: {{VALUE}}; --nav-color-10: {{VALUE}}1a;' ],
			]
		);
		$this->add_control(
			'menu_dark_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for displaying in the navigation bar of this header in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--nav-color: {{VALUE}}; --nav-color-10: {{VALUE}}1a;' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'top_item_hover_tab',
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
				'selectors'   => [ '{{WRAPPER}}' => '--nav-color-h: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'menu_hover_color_accent',
			[
				'label'       => esc_html__( 'Accent Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a accent color when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--nav-color-h-accent: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'menu_dark_hover_color',
			[
				'label'       => esc_html__( 'Dark Mode -Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--nav-color-h: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'menu_dark_hover_color_accent',
			[
				'label'       => esc_html__( 'Dark Mode - Accent Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a accent color when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--nav-color-h-accent: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'sub_menu_section', [
				'label' => esc_html__( 'Submenu Items', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Submenu Font', 'foxiz-core' ),
				'name'     => 'submenu_font',
				'selector' => '{{WRAPPER}} .main-menu .sub-menu > .menu-item a, {{WRAPPER}} .more-col .menu a, {{WRAPPER}} .collapse-footer-menu a',
			]
		);
		$this->add_control(
			'submenu_border', [
				'label'       => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom border radius for submenu.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--round-7: {{VALUE}}px;' ],
			]
		);
		$this->start_controls_tabs( 'sub_item_tabs' );
		$this->start_controls_tab( 'sub_item_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'submenu_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the sub menu dropdown section.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-color: {{VALUE}}; --subnav-color-10: {{VALUE}}1a;' ],
			]
		);
		$this->add_control(
			'submenu_bg_from',
			[
				'label'       => esc_html__( 'Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the sub menu dropdown section.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'submenu_bg_to',
			[
				'label'       => esc_html__( 'Background Gradient (To)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the sub menu dropdown section.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_submenu_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the sub menu dropdown section in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-color: {{VALUE}}; --subnav-color-10: {{VALUE}}1a;' ],
			]
		);
		$this->add_control(
			'dark_submenu_bg_from',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the sub menu dropdown section in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_submenu_bg_to',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (To)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the sub menu dropdown section in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'sub_item_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'submenu_hover_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for hover effects. Consider choosing a contrasting color, as this setting also applies to other menu items and header dropdowns.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-color-h: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'dark_submenu_hover_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-color-h: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'menu_divider_section', [
				'label' => esc_html__( 'Divider', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'divider_style', [
				'label'        => esc_html__( 'Divider Style', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a divider style to show between menu items.', 'foxiz-core' ),
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
		$this->start_controls_section(
			'mega-menu-section', [
				'label' => esc_html__( 'Mega Menu - Color Scheme', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'color_scheme_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This is treated as a global setting. Each menu item in "Appearance > Menus" take priority over this setting.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'color_scheme',
			[
				'label'       => esc_html__( 'Color Scheme', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'In case you would like to switch layout and text to light when set a dark background for sub menu in light mode.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'1' => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'     => '0',
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
		foxiz_elementor_main_menu( $settings );
	}
}