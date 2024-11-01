<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use function foxiz_render_register_link;
use function foxiz_render_register_link_edit_mode;

class Header_Register_Link extends Widget_Base {

	public function get_name() {

		return 'foxiz-register-link';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Register Link Button', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-h-align-right';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'template', 'builder', 'user', 'popup', 'login', 'register' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'layout_section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'login_settings_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'NOTE: This block requests configuration for register URL and the creation of a register page. Please navigate to "Theme Options > Login".', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'frontend_login_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'To build the frontend login and register pages, you can use the "Foxiz - Login Form" and "Foxiz - Register Form" blocks.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'login_logged_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Tips: Navigate to "Settings > General > Membership" to enable registration for your website. The block will disable the logged-in user.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'label_text',
			[
				'label'       => esc_html__( 'Register Label', 'foxiz-core' ),
				'description' => esc_html__( 'To configure the destination of this block, navigate to "Theme Options > Login > Custom Register Page URL".', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'default'     => 'Sign Up',
				'rows'        => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'text-button-section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'button_style_tabs' );
		$this->start_controls_tab( 'normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'text_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text register button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'button_bg',
			[
				'label'       => esc_html__( 'Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_text_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text register button.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .reg-link span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_button_bg',
			[
				'label'       => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color for the register button in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .reg-link' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'text_hover_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text register button when hovering.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link:hover span' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'button_hover_bg',
			[
				'label'       => esc_html__( 'Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the button when hovering.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link:hover' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);

		$this->add_control(
			'dark_text_hover_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the text register button when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .reg-link:hover span' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'dark_button_hover_bg',
			[
				'label'       => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color for the register button in dark mode when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .reg-link:hover' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'button-border-section', [
				'label' => esc_html__( 'Border', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'button_border',
			[
				'label'       => esc_html__( 'Button Border', 'foxiz-core' ),
				'description' => esc_html__( 'Enable or disable the border for the register link button.', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'selectors'   => [ '{{WRAPPER}} .reg-link.is-btn' => 'border: 1px solid var(--usr-btn-border, currentColor)' ],
			]
		);
		$this->start_controls_tabs( 'border_style_tabs' );
		$this->start_controls_tab( 'border_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'border_color',
			[
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--usr-btn-border: {{VALUE}};' ],
				'default'     => '',
			]
		);
		$this->add_control(
			'border_color_dark',
			[
				'label'       => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--usr-btn-border: {{VALUE}};' ],
				'default'     => '',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'border_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'border_hover_color',
			[
				'label'       => esc_html__( 'Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .reg-link:hover' => '--usr-btn-border: {{VALUE}};' ],
				'default'     => '',
			]
		);

		$this->add_control(
			'border_hover_color_dark',
			[
				'label'       => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border in dark mode when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .reg-link:hover' => '--usr-btn-border: {{VALUE}};' ],
				'default'     => '',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'dimension-section', [
				'label' => esc_html__( 'Font & Dimensions', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'       => esc_html__( 'Text Label Font', 'foxiz-core' ),
				'description' => esc_html__( 'Choose the font style and size for the button text label.', 'foxiz-core' ),
				'name'        => 'button_font',
				'selector'    => '{{WRAPPER}} .reg-link span',
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label'       => esc_html__( 'Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom height value for the login icon/button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link' => 'line-height: {{VALUE}}px; height: {{VALUE}}px;', ],
			]
		);
		$this->add_control(
			'padding',
			[
				'label'       => esc_html__( 'Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom left right padding for the login icon/button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .reg-link' => '--login-btn-padding: {{VALUE}}px;', ],
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--round-3: {{VALUE}}px;',
				],
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
				'selectors' => [ '{{WRAPPER}} .widget-h-login' => 'text-align: {{VALUE}};', ],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			foxiz_render_register_link_edit_mode( $settings );
		} else {
			foxiz_render_register_link( $settings );
		}
	}
}