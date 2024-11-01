<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_header_notification;

/**
 * Class Header_Notification
 *
 * @package foxizElementor\Widgets
 */
class Header_Notification extends Widget_Base {

	public function get_name() {

		return 'foxiz-notification-icon';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Notification Icon', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-info-circle-o';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'template', 'builder', 'notification', 'push' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general-section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'header_notification_url',
			[
				'label'       => esc_html__( 'Destination Link', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a destination URL for the notification panel.', 'foxiz-core' ),
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style-section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label'       => esc_html__( 'Icon Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom font size for the notification icon.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} i.wnav-icon, {{WRAPPER}} .notification-icon-svg' => 'font-size: {{VALUE}}px;',
					'{{WRAPPER}} span.wnav-svg'                                   => 'width: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label'       => esc_html__( 'Icon Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom height value for the notification icon.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .notification-icon-inner' => 'min-height: {{VALUE}}px;' ],
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
				'selectors' => [ '{{WRAPPER}} .notification-icon' => 'justify-content: {{VALUE}};', ],
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
			'color',
			[
				'label'       => esc_html__( 'Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the notification icon.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} i.wnav-icon, {{WRAPPER}} .notification-icon-svg' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_color',
			[
				'label'       => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the notification icon in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} i.wnav-icon, [data-theme="dark"] {{WRAPPER}} .notification-icon-svg' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'notification_color',
			[
				'label'       => esc_html__( 'Notification Dot', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background for the notification dot icon.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} .notification-info' => 'background-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'dropdown-section', [
				'label' => esc_html__( 'Dropdown', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'form_position',
			[
				'label'       => esc_html__( 'Dropdown Right Position', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '-200',
				'description' => esc_html__( 'Input the right relative position (in px) for the notification dropdown, e.g., -200.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown' => 'right: {{VALUE}}px; left: auto;' ],
			]
		);
		$this->add_responsive_control(
			'dropdown_width',
			[
				'label'       => esc_html__( 'Dropdown Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input the width values for the dropdown section.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--dropdown-w: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'popup_text_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text in the popup form.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}}' => '--subnav-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'bg_from',
			[
				'label'       => esc_html__( 'Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the dropdown section.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'bg_to',
			[
				'label'       => esc_html__( 'Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the dropdown section.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'popup_dark_text_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text in the popup form.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => '--subnav-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'dark_bg_from',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the dropdown section in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_to',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the dropdown section in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'header_notification_scheme',
			[
				'label'       => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select color scheme for the search form to fit with your background.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'1' => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_header_notification' ) ) {
			foxiz_header_notification( $this->get_settings() );
		}
	}
}