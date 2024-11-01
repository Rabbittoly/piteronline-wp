<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_single_next_prev;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Pagination extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-navigation';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Post Pagination', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-post-navigation';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'nav' ];
	}

	public function get_categories() {

		return [ 'foxiz_single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .h4',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}} .nav-label',
			]
		);
		$this->add_responsive_control(
			'featured_size', [
				'label'       => esc_html__( 'Featured Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '50',
				'selectors'   => [ '{{WRAPPER}} .e-pagi img' => 'height: {{VALUE}}px; width: {{VALUE}}px;' ],
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
				'label'        => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => Options::color_scheme_description(),
				'options'      => [
					'default-scheme' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'light-scheme'   => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'prefix_class' => '',
				'default'      => 'default-scheme',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_single_next_prev' ) ) {
			foxiz_single_next_prev( true );
		}
	}

}