<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use function foxiz_header_user;

class Header_Login_Icon extends Widget_Base {

	public function get_name() {

		return 'foxiz-login-icon';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Login Link Trigger Button', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-exit';
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
				'raw'             => esc_html__( 'NOTE: This block requires additional configuration for popup login form and the creation of a login page. Please navigate to "Theme Options > Login".', 'foxiz-core' ),
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
				'raw'             => esc_html__( 'The block will show a welcome message for the logged-in user.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'header_login_layout',
			[
				'label'       => esc_html__( 'Sign In Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Choose how the sign-in button should appear. Options include:', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Icon Only', 'foxiz' ),
					'1' => esc_html__( 'Text Button', 'foxiz' ),
					'2' => esc_html__( 'Text with Icon', 'foxiz' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'label_text',
			[
				'label'       => esc_html__( 'Login Label', 'foxiz-core' ),
				'description' => esc_html__( 'Enter the text label for the button when "Text Button" or "Text with Icon" is selected. Leave blank for the default label.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'default'     => '',
				'rows'        => 1,
			]
		);
		$this->add_control(
			'login_icon',
			[
				'label'       => esc_html__( 'Custom Login Icon (SVG Attachment)', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Override default login icon with a SVG icon, Input the file URL of your svg icon.', 'foxiz-core' ),
				'placeholder' => esc_html__( 'https://yourdomain.com/wp-content/uploads/....filename.svg', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .login-icon-svg' => 'mask-image: url({{VALUE}}); -webkit-mask-image: url({{VALUE}}); background-image: none;',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'Layout - Icon Only', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon-style-info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The settings below will be applied to the "Icon Only" sign-in layout.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'       => esc_html__( 'Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the login icon.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} .login-toggle' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_icon_color',
			[
				'label'       => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the login icon in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'text-button-section', [
				'label' => esc_html__( 'Layouts - Button', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'button-style-info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The settings below will apply to the "Text Button" and "Text with Icon" sign-in layouts.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->start_controls_tabs( 'btn_style_tabs' );
		$this->start_controls_tab( 'btn_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text on the login button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'button_bg',
			[
				'label'       => esc_html__( 'Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_text_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text login button.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_button_bg',
			[
				'label'       => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the login button in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'btn_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'text_hover_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text on the login button when hovering.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle:hover span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'button_hover_bg',
			[
				'label'       => esc_html__( 'Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the button when hovering.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle:hover' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_hover_text_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text login button when hovering.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle:hover span' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_button_hover_bg',
			[
				'label'       => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the login button in dark mode when hovering.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle:hover' => '--g-color: {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'button_border',
			[
				'label'       => esc_html__( 'Button Border', 'foxiz-core' ),
				'description' => esc_html__( 'Enable or disable the border for the login button.', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'selectors'   => [ '{{WRAPPER}} .login-toggle.is-btn' => 'border: 1px solid var(--usr-btn-border, currentColor)' ],
			]
		);

		$this->start_controls_tabs( 'btn_border_tabs' );
		$this->start_controls_tab( 'btn_border_normal_tab',
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
			]
		);
		$this->add_control(
			'border_color_dark',
			[
				'label'       => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--usr-btn-border: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'btn_border_hover_tab',
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
				'selectors'   => [ '{{WRAPPER}} .login-toggle:hover' => '--usr-btn-border: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'border_hover_color_dark',
			[
				'label'       => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the button border in dark mode when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .login-toggle:hover' => '--usr-btn-border: {{VALUE}};' ],
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
				'selector'    => '{{WRAPPER}} .login-toggle.header-element span',
			]
		);
		$this->add_control(
			'size',
			[
				'label'       => esc_html__( 'Icon Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify the size of the login icon in pixels. This will adjust both the width and height of the icon as well as the font size of the text label and line height of the link.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .login-toggle.header-element svg'                                          => 'width: {{VALUE}}px; height: {{VALUE}}px;',
					'{{WRAPPER}} .login-toggle.header-element .login-icon-svg, {{WRAPPER}} .login-toggle i' => 'font-size: {{VALUE}}px;',
					'{{WRAPPER}} a.is-logged'                                                               => 'line-height: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label'       => esc_html__( 'Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom height value for the login icon/button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle' => 'line-height: {{VALUE}}px; height: {{VALUE}}px;', ],
			]
		);
		$this->add_control(
			'padding',
			[
				'label'       => esc_html__( 'Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom left right padding for the login icon/button.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .login-toggle' => '--login-btn-padding: {{VALUE}}px;', ],
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
		$this->start_controls_section(
			'dropdown-section', [
				'label' => esc_html__( 'When Logged', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'logged_menu_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'NOTE: To configure the user dropdown menu, please go to "Theme Options > Header > Sign In Buttons > User Dashboard Menu".', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'logged_gravatar',
			[
				'label'       => esc_html__( 'Gravatar Icon', 'foxiz-core' ),
				'description' => esc_html__( 'Display the user Gravatar in the welcome label.', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => true,
			]
		);
		$this->add_control(
			'gravatar_size',
			[
				'label'       => esc_html__( 'Gravatar Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify the size of the Gravatar icon in pixels.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--user-isize: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'logged_size',
			[
				'label'       => esc_html__( 'Welcome Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom font size for welcome text.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .logged-welcome' => 'font-size: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'username_width',
			[
				'label'       => esc_html__( 'Max Username Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '120',
				'description' => esc_html__( 'Limit the username width (in px) to keep the block within a small width in the header.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--uname-width: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'logged_color',
			[
				'label'       => esc_html__( 'Welcome Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the welcome text. The white color will be applied for dark mode.', 'foxiz-core' ),
				'selectors'   => [
					'body:not([data-theme="dark"]) {{WRAPPER}} .logged-welcome' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'form_position',
			[
				'label'       => esc_html__( 'Dropdown Right Position', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '-200',
				'description' => esc_html__( 'input a right relative position for the logged dropdown, e.g. -200', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown' => 'right: {{VALUE}}px; left: auto;' ],
			]
		);

		$this->start_controls_tabs( 'dropdown_style_tabs' );
		$this->start_controls_tab( 'dropdown_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);

		$this->add_control(
			'dropdown_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the logged dropdown.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .user-dropdown a' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'bg_from',
			[
				'label'       => esc_html__( 'Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the dropdown section.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .header-dropdown' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'bg_to',
			[
				'label'       => esc_html__( 'Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the dropdown section.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_dropdown_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the logged dropdown in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .user-dropdown a' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_from',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the dropdown section in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .header-dropdown' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_to',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the dropdown section in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .header-dropdown' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'dropdown_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'hover_dropdown_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the logged dropdown when hovering.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .user-dropdown a:hover' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_hover_dropdown_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a text color for the logged dropdown when hovering in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .user-dropdown a:hover' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();
		if ( function_exists( 'foxiz_header_user' ) ) {
			foxiz_header_user( $settings );
		}
	}
}