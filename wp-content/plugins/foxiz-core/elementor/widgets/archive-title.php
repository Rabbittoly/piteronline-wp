<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_elementor_archive_title;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Archive_Title extends Widget_Base {

	public function get_name() {

		return 'foxiz-archive-title';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Archive Title', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-post-title';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'category', 'tag', 'heading', 'taxonomy' ];
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
				'raw'             => esc_html__( 'This block is only used to build category, tag, search, taxonomy and archive templates. It displays the title based on the current page.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'dynamic_title',
			[
				'label'       => esc_html__( 'Custom Title', 'foxiz-core' ),
				'description' => esc_html__( 'Input a custom title for the archive, use {archive} to display dynamic archive title.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'default'     => '',
				'placeholder' => '{archive} Latest News',
			]
		);
		$this->add_control(
			'follow',
			[
				'label'       => esc_html__( 'Follow Button', 'foxiz-core' ),
				'description' => esc_html__( 'Show the follow button below the title on category, tag, or author archive pages.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => Options::switch_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Heading HTML Tag', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Options::heading_html_dropdown(),
				'default' => 'h1',
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
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--archive-hcolor: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_dark_color',
			[
				'label'     => esc_html__( 'Dark Mode - Title Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--archive-hcolor: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_align',
			[
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => '',
				'selectors' => [ '{{WRAPPER}}' => 'text-align: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .archive-title',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'label'    => esc_html__( 'Text Shadow', 'foxiz-core' ),
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .archive-title',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<h1 class="archive-title e-archive-title">' . esc_html__( 'Dynamic archive title', 'foxiz-core' ) . '</h1>';
		} else {
			$settings = $this->get_settings();
			if ( function_exists( 'foxiz_elementor_archive_title' ) ) {
				echo foxiz_elementor_archive_title( $settings );
			}
		}
	}

}