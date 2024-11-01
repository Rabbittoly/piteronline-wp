<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_ad_image;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Ad_Image extends Widget_Base {

	public function get_name() {

		return 'foxiz-ad-image';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Ad Image', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-image-rollover';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'advert', 'image', 'promotion' ];
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
			'image',
			[
				'label'       => esc_html__( 'Ad Image', 'foxiz-core' ),
				'description' => esc_html__( 'Upload your ad image.', 'foxiz-core' ),
				'type'        => Controls_Manager::MEDIA,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'dark_image',
			[
				'label'       => esc_html__( 'Dark Mode - Ad Image', 'foxiz-core' ),
				'description' => esc_html__( 'Upload your ad image in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::MEDIA,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'destination',
			[
				'label'       => esc_html__( 'Ad Destination', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
				'description' => esc_html__( 'Input your ad destination URL.', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'image_width_section', [
				'label' => esc_html__( 'Image', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'image_width',
			[
				'label'       => esc_html__( 'Image Max Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a max width value (in px) for your ad image, leave blank set full size.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .ad-image' => 'max-width: {{VALUE}}px',
				],
			]
		);
		$this->add_control(
			'feat_lazyload',
			[
				'label'       => esc_html__( 'Lazy Load', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::feat_lazyload_description(),
				'options'     => Options::feat_lazyload_dropdown(),
				'default'     => '0',
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

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_get_ad_image' ) ) {
			$settings               = $this->get_settings();
			$settings['uuid']       = 'uid_' . $this->get_id();
			$settings['no_spacing'] = true;

			if ( ! empty( $settings['image']['id'] ) ) {
				$medata = wp_get_attachment_metadata( $settings['image']['id'] );
				if ( ! empty( $medata['width'] ) && ! empty( $medata['height'] ) ) {
					$settings['image']['width']  = $medata['width'];
					$settings['image']['height'] = $medata['height'];
				}
			}
			echo foxiz_get_ad_image( $settings );
		}
	}
}