<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_adsense;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Ad_Script extends Widget_Base {

	public function get_name() {

		return 'foxiz-ad-script';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Ad Script', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-code';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'advert', 'script', 'adsense', 'promotion' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a description for this adverting box.', 'foxiz-core' ),
				'default'     => esc_html__( '- Advertisement -', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'code',
			[
				'label'       => esc_html__( 'Ad/Adsense Code', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input your custom ad or Adsense code. Use Adsense units code to ensure it display exactly where you put. The widget will not work if you are using auto ads.', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->add_control(
			'size',
			[
				'label'       => esc_html__( 'Ad Size', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a custom size for this ad if you use adsense ad units.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Do not Override', 'foxiz-core' ),
					'1' => esc_html__( 'Custom Size Below', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);

		$this->add_control(
			'desktop_size',
			[
				'label'       => esc_html__( 'Size on Desktop', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a size on desktop devices.', 'foxiz-core' ),
				'options'     => Options::ad_size_dropdown(),
				'default'     => '1',
			]
		);
		$this->add_control(
			'tablet_size',
			[
				'label'       => esc_html__( 'Size on Tablet', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a size on tablet devices.', 'foxiz-core' ),
				'options'     => Options::ad_size_dropdown(),
				'default'     => '1',
			]
		);
		$this->add_control(
			'mobile_size',
			[
				'label'       => esc_html__( 'Size on Mobile', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a size on mobile devices.', 'foxiz-core' ),
				'options'     => Options::ad_size_dropdown(),
				'default'     => '1',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'color_section', [
				'label' => esc_html__( 'Color', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Description Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--meta-fcolor: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_color',
			[
				'label'     => esc_html__( 'Dark Mode - Description Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--meta-fcolor: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_get_adsense' ) ) {
			$settings               = $this->get_settings();
			$settings['uuid']       = 'uid_' . $this->get_id();
			$settings['no_spacing'] = true;
			echo foxiz_get_adsense( $settings );
		}
	}
}