<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use function foxiz_render_frontend_login;
use function foxiz_render_user_login;

class Login_Form extends Widget_Base {

	public function get_name() {

		return 'foxiz-login-form';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Login Form', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-person';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'template', 'builder', 'user', 'login', 'register', 'create' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'label_section', [
				'label' => esc_html__( 'Login Form', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'lform_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block allows you to create a frontend login page with your own custom design.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'login_failed_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'DEFINE FRONTEND LOGIN: Foxiz uses the default wp-login.php to manage user logins, ensuring maximum security. To prevent redirecting to wp-login.php, navigate to "Theme Options > Login > Custom Login Page URL" and set the login page to the page URL where you place this block.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'login_header',
			[
				'label'       => esc_html__( 'Login Header Content', 'foxiz-core' ),
				'description' => esc_html__( 'Display custom information at the top of the login form. Allows raw HTML code.', 'foxiz-core' ),
				'placeholder' => '<h1>Login</h1>',
				'default'     => '<h1>Login</h1>',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'label_username',
			[
				'label'   => esc_html__( 'Username Label', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 1,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$this->add_control(
			'label_password',
			[
				'label'   => esc_html__( 'Password Label', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 1,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$this->add_control(
			'label_remember',
			[
				'label'   => esc_html__( 'Remember Label', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 1,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);

		$this->add_control(
			'label_log_in',
			[
				'label'   => esc_html__( 'Login Button Label', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 1,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'lostpassword', [
				'label' => esc_html__( 'Lost Password Form', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'lostpassword_header',
			[
				'label'       => esc_html__( 'Lost Password Header Content', 'foxiz-core' ),
				'description' => esc_html__( 'Display custom information when a visitor clicks on the lost password link. Allows raw HTML code.', 'foxiz-core' ),
				'default'     => '<h1><i class="rbi rbi-unlock"></i>Get Your Password</h1><p>Please enter your username or email address. You will receive an email message with instructions on how to reset your password.</p>',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'confirmemail_header',
			[
				'label'       => esc_html__( 'Confirm Email Header Content', 'foxiz-core' ),
				'description' => esc_html__( 'Display custom information when a visitor successfully resets their password. Allows raw HTML code.', 'foxiz-core' ),
				'default'     => '<h1><i class="rbi rbi-unlock"></i>Get Your Password</h1><p>A password reset email has been sent. It may take a few minutes to arrive. Please wait 10 minutes before requesting another reset.</p>',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'logged_section', [
				'label' => esc_html__( 'Logged Status Box', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'logged_status',
			[
				'label'       => esc_html__( 'Logged Status ', 'foxiz-core' ),
				'description' => esc_html__( 'Select a layout if the user is logged in.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'0' => esc_html__( 'None', 'foxiz-core' ),
					'1' => esc_html__( 'Status with Avatar', 'foxiz-core' ),
					'2' => esc_html__( 'Minimal', 'foxiz-core' ),
				],
				'default'     => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'label_style_section', [
				'label' => esc_html__( 'Label', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'label_style',
			[
				'label'        => esc_html__( 'Label Style', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => esc_html__( 'Select a style for the login label.', 'foxiz-core' ),
				'options'      => [
					'none' => esc_html__( 'None', 'foxiz-core' ),
					'pipe' => esc_html__( 'Pipe (|)', 'foxiz-core' ),
					'dot'  => esc_html__( 'Dot (.)', 'foxiz-core' ),
				],
				'default'      => 'none',
				'prefix_class' => 'is-label-',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Label Font', 'foxiz-core' ),
				'name'     => 'label_font',
				'selector' => '{{WRAPPER}} .rb-login-label, {{WRAPPER}} .logged-status-simple',
			]
		);
		$this->add_responsive_control(
			'label_spacing', [
				'label'       => esc_html__( 'Label Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom bottom margin for the label.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--llabel-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--llabel-color : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'label_icon',
			[
				'label'     => esc_html__( 'Icon Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--licon-color : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_label_color',
			[
				'label'     => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--llabel-color : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_label_icon',
			[
				'label'     => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--licon-color : {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'input_style_section', [
				'label' => esc_html__( 'Input Fields', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'input_spacing', [
				'label'       => esc_html__( 'Input Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom spacing between input fields.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--linput-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Input Font', 'foxiz-core' ),
				'name'     => 'input_font',
				'selector' => '{{WRAPPER}} .user-login-form input',
			]
		);
		$this->add_control(
			'input_style',
			[
				'label'        => esc_html__( 'Input Style', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'bg'     => esc_html__( 'Gray Background', 'foxiz-core' ),
					'border' => esc_html__( 'Gray Border', 'foxiz-core' ),
				],
				'default'      => 'bg',
				'prefix_class' => 'is-input-',
			]
		);
		$this->add_responsive_control(
			'input_border', [
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--round-7: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'button_section', [
				'label' => esc_html__( 'Login Button', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Button Font', 'foxiz-core' ),
				'name'     => 'button_font',
				'selector' => '{{WRAPPER}} .user-login-form input[type="submit"]',
			]
		);
		$this->add_control(
			'button_width', [
				'label'     => esc_html__( 'Button Width', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--lbutton-width: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'button_border', [
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--round-3: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'button_padding',
			[
				'label'       => esc_html__( 'Inner Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Input a custom padding for the login button', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--lbutton-padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--awhite : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'button_bg',
			[
				'label'     => esc_html__( 'Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--g-color : {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_button_color',
			[
				'label'     => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--awhite : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_button_bg',
			[
				'label'     => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"]  {{WRAPPER}}' => '--g-color : {{VALUE}}; --g-color-90: {{VALUE}}e6;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'form_meta_section', [
				'label' => esc_html__( 'Footer Links & Lost your password?', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}} .lostpassw-link, {{WRAPPER}} .login-form-footer',
			]
		);
		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Meta Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .lostpassw-link, {{WRAPPER}} .login-form-footer' => 'color : {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_meta_color',
			[
				'label'     => esc_html__( 'Dark Mode - Meta Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}} .lostpassw-link, [data-theme="dark"] {{WRAPPER}} .login-form-footer' => 'color : {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'padding_section', [
				'label' => esc_html__( 'Inner Padding', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'padding_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The settings below allow you to set different inner spacing for the login form and logged status. Use the Advanced tab for additional style settings: background, shadow, and border...', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_responsive_control(
			'form_padding',
			[
				'label'       => esc_html__( 'Login Form', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Input a custom inner padding for the login form.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--lform-padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'logged_padding',
			[
				'label'       => esc_html__( 'Logged Box Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Enter custom padding for the logged status box.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--lstatus-padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		$settings         = $this->get_settings();
		$settings['uuid'] = 'uid_' . $this->get_id();
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			foxiz_render_frontend_login( $settings );
		} else {
			foxiz_render_user_login( $settings );
		}
	}
}