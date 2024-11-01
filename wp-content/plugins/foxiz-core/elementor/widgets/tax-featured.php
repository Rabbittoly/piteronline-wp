<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use foxizElementorControl\Options;

/**
 * Class Taxonomy_Featured
 *
 * @package foxizElementor\Widgets
 */
class Taxonomy_Featured extends Widget_Base {

	public function get_name() {

		return 'foxiz-tax-featured';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Taxonomy Featured Image', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-post-title';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'category', 'tag', 'featured', 'taxonomy', 'image' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'template_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block is only used to build category, tag, and taxonomy templates. It displays the featured image based on the current page.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'crop_size',
			[
				'label'       => esc_html__( 'Featured Image Size', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::crop_size(),
				'options'     => Options::crop_size_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'image_ratio', [
				'label'       => esc_html__( 'Image Ratio', 'foxiz-core' ),
				'description' => esc_html__( 'The image size will be based on your dimensions. Input a custom ratio if you want to specify the exact height.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}}' => '--image-ratio: {{VALUE}}%;' ],
			]
		);
		$this->add_control(
			'feat_align',
			[
				'label'       => esc_html__( 'Featured Align', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Align the featured image for a single post. This setting will apply when you set a custom ratio.', 'foxiz-core' ),
				'options'     => [
					''       => esc_html__( '- Default -', 'foxiz-core' ),
					'top'    => esc_html__( 'Top', 'foxiz-core' ),
					'bottom' => esc_html__( 'Bottom', 'foxiz-core' ),
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}}' => '--feat-position: center {{VALUE}};',
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
		$this->end_controls_section();
	}

	protected function render() {

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="s-feat-placeholder"></div>';
		} else {
			foxiz_elementor_tax_featured( $this->get_settings() );
		}
	}

}