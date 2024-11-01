<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_author_info;
use function foxiz_single_author_box;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Author extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-author';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Author Box', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-email-field';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'search', 'author', 'user' ];
	}

	public function get_categories() {

		return [ 'foxiz_single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section', [
				'label' => esc_html__( 'Author', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'author_id',
			[
				'label'       => esc_html__( 'Select a Author', 'foxiz-core' ),
				'description' => esc_html__( 'The dynamic author setting is only available for single posts and will collect and display the author\'s information based on the queried single post.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => Options::author_dropdown( true, false ),
				'default'     => 'dynamic_author',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'biographical_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The author box request Biographical Info to display, Navigate to Users > Edit user to add the information.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Biographical Font', 'foxiz-core' ),
				'name'     => 'description_font',
				'selector' => '{{WRAPPER}} .ubio',
			]
		);
		$this->add_responsive_control(
			'avatar_size', [
				'label'       => esc_html__( 'Avatar Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '50',
				'selectors'   => [ '{{WRAPPER}} .author-avatar' => 'width: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'nice_name', [
				'label'        => esc_html__( 'Name Underline', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'name-underline' => esc_html__( '- Default -', 'foxiz-core' ),
					'name-text'      => esc_html__( 'Text Only', 'foxiz-core' ),
				],
				'prefix_class' => '',
				'default'      => 'name-underline',
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

	/**
	 * render layout
	 */
	protected function render() {

		if ( ! function_exists( 'foxiz_get_author_info' ) ) {
			return;
		}

		$settings = $this->get_settings();

		if ( empty( $settings['author_id'] ) || 'dynamic_author' == $settings['author_id'] ) {
			foxiz_single_author_box( true );
		} else {
			echo foxiz_get_author_info( $settings['author_id'] );
		}
	}
}