<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_render_pricing_plan;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Plan extends Widget_Base {

	public function get_name() {

		return 'foxiz-plan';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Plan Subscription', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-price-table';
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'paywall', 'membership', 'restricted' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Input a title for this banner.', 'foxiz-core' ),
				'default'     => 'Get <span>unlimited</span> access to everything',
			]
		);
		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'foxiz-core' ),
				'description' => esc_html__( 'Input a description for this plan.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => 'Plans starting at less than $9/month. <strong>Cancel anytime.</strong>',
			]
		);
		$this->add_control(
			'price',
			[
				'label'       => esc_html__( 'Price', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a price for this plan.', 'foxiz-core' ),
				'default'     => '9',
			]
		);
		$this->add_control(
			'unit',
			[
				'label'       => esc_html__( 'Price Unit', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a price unit for this plan.', 'foxiz-core' ),
				'default'     => '$',
			]
		);
		$this->add_control(
			'tenure',
			[
				'label'       => esc_html__( 'Price Tenure', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a price tenure for this plan.', 'foxiz-core' ),
				'default'     => '/month',
			]
		);
		$features = new Repeater();
		$features->add_control(
			'feature',
			[
				'label'       => esc_html__( 'Plan Feature', 'foxiz-core' ),
				'description' => esc_html__( 'Input a feature for this plan.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
				'default'     => '',
			]
		);
		$this->add_control(
			'features',
			[
				'label'       => esc_html__( 'Plan Features', 'foxiz-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $features->get_controls(),
				'title_field' => '{{{ feature }}}',

			]
		);
		$this->add_control(
			'shortcode',
			[
				'label'       => esc_html__( 'Membership Payment Button Shortcode', 'foxiz-core' ),
				'description' => esc_html__( 'Input a payment button shortcode. Use button text if you would like to custom label, e.g. [swpm_payment_button id=1 button_text="Buy Now"]', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'placeholder' => '[swpm_payment_button id=1]',
				'rows'        => 2,
				'default'     => '',

			]
		);
		$this->add_control(
			'register_button',
			[
				'label'       => esc_html__( 'or Free Button', 'foxiz-core' ),
				'description' => esc_html__( 'Input a free button label to navigate to the user to the register page. Leave blank the payment shortcode filed to use this setting.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'placeholder' => 'Join Now',
				'rows'        => 1,
				'default'     => '',

			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'box_style_section', [
				'label' => esc_html__( 'Box Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'box_style',
			[
				'label'       => esc_html__( 'Box Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a box style for this block.', 'foxiz-core' ),
				'options'     => [
					'shadow' => esc_html__( 'Shadow', 'foxiz-core' ),
					'border' => esc_html__( 'Border', 'foxiz-core' ),
					'bg'     => esc_html__( 'Background', 'foxiz-core' ),
				],
				'default'     => 'shadow',
			]
		);
		$this->add_control(
			'box_style_color',
			[
				'label'       => esc_html__( 'Box Style Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for your box style.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}}' => '--plan-box-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_box_style_color',
			[
				'label'       => esc_html__( 'Dark Mode - Box Style Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for this plan box in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--plan-box-color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'button_bg',
			[
				'label'       => esc_html__( 'Button Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the payment button.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} .plan-button-wrap' => '--plan-button-bg: {{VALUE}}; --plan-button-bg-opacity: {{VALUE}}ee;' ],
			]
		);
		$this->add_control(
			'dark_button_bg',
			[
				'label'       => esc_html__( 'Dark Mode - Button Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the payment button in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .plan-button-wrap' => '--plan-button-bg: {{VALUE}}; --plan-button-bg-opacity: {{VALUE}}ee;' ],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'       => esc_html__( 'Button Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the payment button.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '{{WRAPPER}} .plan-button-wrap' => '--plan-button-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_button_color',
			[
				'label'       => esc_html__( 'Dark Mode - Button Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the payment button in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .plan-button-wrap' => '--plan-button-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'size_section', [
				'label' => esc_html__( 'Font Size', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_size',
			[
				'label'       => esc_html__( 'Heading Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom font size (in px) for the plan heading.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .plan-heading' => 'font-size: {{VALUE}}px',
				],
			]
		);
		$this->add_responsive_control(
			'desc_size',
			[
				'label'       => esc_html__( 'Description Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom font size (in px) for the description.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .plan-description' => 'font-size: {{VALUE}}px',
				],
			]
		);
		$this->add_responsive_control(
			'feature_size',
			[
				'label'       => esc_html__( 'Feature List Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom font size (in px) for the description.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .plan-features' => 'font-size: {{VALUE}}px',
				],
			]
		);
		$this->add_responsive_control(
			'button_size',
			[
				'label'       => esc_html__( 'Button Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom font size (in px) for the payment button.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .plan-button-wrap' => '--plan-button-size: {{VALUE}}px',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'spacing_section', [
				'label' => esc_html__( 'Spacing', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'el_spacing',
			[
				'label'       => esc_html__( 'Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a custom spacing value(px) between element.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .plan-inner > *:not(:last-child)' => 'margin-bottom: {{VALUE}}px',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'color_section', [
				'label' => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'color_scheme',
			[
				'label'       => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::color_scheme_description(),
				'options'     => [
					'0' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'1' => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'font_section', [
				'label' => esc_html__( 'Custom Font', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'custom_font_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::custom_font_info_description(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Heading Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .plan-heading',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Description Font', 'foxiz-core' ),
				'name'     => 'description_font',
				'selector' => '{{WRAPPER}} .plan-description',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Plan Features Font', 'foxiz-core' ),
				'name'     => 'feature_font',
				'selector' => '{{WRAPPER}} .plan-features',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_render_pricing_plan' ) ) {
			echo foxiz_render_pricing_plan( $this->get_settings() );
		}
	}
}