<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function wp_json_encode;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Web_Stories extends Widget_Base {

	public function get_name() {

		return 'foxiz-stories';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Web Stories', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-slider-3d';
	}

	public function get_keywords() {

		return [ 'google', 'story', 'stories' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'Query Filter', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'web_stories_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block requires the "Web Stories" plugin to be installed and activated in order to function properly.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'blockType',
			[
				'label'       => esc_html__( 'Block Type', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select web stories type.', 'foxiz-core' ),
				'options'     => [
					'latest-stories'   => esc_html__( 'Latest Stories', 'foxiz-core' ),
					'selected-stories' => esc_html__( 'Selected Stories', 'foxiz-core' ),
				],
				'default'     => 'latest-stories',
			]
		);
		$this->add_control(
			'stories',
			[
				'label'       => esc_html__( 'Stories', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input story IDs, separated by commas, i.e., 1, 2, 3.', 'foxiz-core' ),
				'placeholder' => '1,2,3',
				'ai'          => [ 'active' => false ],
				'condition'   => [
					'blockType' => 'selected-stories',
				],
				'default'     => '',
			]
		);
		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'  => esc_html__( 'Date', 'foxiz-core' ),
					'title' => esc_html__( 'Title', 'foxiz-core' ),
				],
				'default' => 'date',
			]
		);
		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order By', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'asc'  => esc_html__( 'Ascending', 'foxiz-core' ),
					'desc' => esc_html__( 'Descending', 'foxiz-core' ),
				],
				'default' => 'asc',
			]
		);
		$this->add_control(
			'web_story_category',
			[
				'label'       => esc_html__( 'Categories IDs', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input story category IDs, separated by commas, i.e., 1, 2, 3.', 'foxiz-core' ),
				'placeholder' => '1,2,3',
				'ai'          => [ 'active' => false ],
				'default'     => '',
			]
		);
		$this->add_control(
			'web_story_tag',
			[
				'label'       => esc_html__( 'Tag IDs', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input story tag IDs, separated by commas, i.e., 1, 2, 3.', 'foxiz-core' ),
				'placeholder' => '1,2,3',
				'ai'          => [ 'active' => false ],
				'default'     => '',
			]
		);
		$this->add_control(
			'authors',
			[
				'label'       => esc_html__( 'Authors IDs', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input author IDs, separated by commas, i.e., 1, 2, 3.', 'foxiz-core' ),
				'placeholder' => '1,2,3',
				'ai'          => [ 'active' => false ],
				'default'     => '',
			]
		);
		$this->add_control(
			'numOfStories',
			[
				'label'   => esc_html__( 'Num of Stories', 'foxiz-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'layout', [
				'label' => esc_html__( 'Layout', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'viewType',
			[
				'label'       => esc_html__( 'Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a layout for the stories', 'foxiz-core' ),
				'options'     => [
					'grid'     => esc_html__( 'Grid', 'foxiz-core' ),
					'circles'  => esc_html__( 'Circle', 'foxiz-core' ),
					'carousel' => esc_html__( 'Carousel', 'foxiz-core' ),
					'list'     => esc_html__( 'List', 'foxiz-core' ),
				],
				'default'     => 'grid',
			]
		);
		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Display Title', 'foxiz-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'viewType' => 'circles',
				],
			]
		);
		$this->add_control(
			'circleSize',
			[
				'label'     => esc_html__( 'Circle Size', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => [
					'viewType' => 'circles',
				],
			]
		);
		$this->add_control(
			'show_author',
			[
				'label'     => esc_html__( 'Display Author', 'foxiz-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'viewType!' => 'circles',
				],
			]
		);
		$this->add_control(
			'show_date',
			[
				'label'     => esc_html__( 'Display Date', 'foxiz-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'viewType!' => 'circles',
				],
			]
		);
		$this->add_control(
			'show_excerpt',
			[
				'label'   => esc_html__( 'Display Excerpt', 'foxiz-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		$this->add_control(
			'imageAlignment',
			[
				'label'     => esc_html__( 'Image Align', 'foxiz-core' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'viewType' => 'list',
				],
				'options'   => [
					'left'  => esc_html__( 'Left', 'foxiz-core' ),
					'right' => esc_html__( 'Right', 'foxiz-core' ),
				],
				'default'   => 'left',
			]
		);
		$this->add_control(
			'sharp_corners',
			[
				'label'   => esc_html__( 'Use Sharp Corners', 'foxiz-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		$this->add_control(
			'show_archive_link',
			[
				'label'   => esc_html__( 'Display Archive Link', 'foxiz-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'archive_link_label',
			[
				'label'       => esc_html__( 'Archive Link Label', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'placeholder' => esc_html__( 'View All Stories', 'foxiz-core' ),
				'condition'   => [
					'show_archive_link' => 'yes',
				],
				'default'     => 'View All',
			]
		);
		$this->add_control(
			'numOfColumns',
			[
				'label'     => esc_html__( 'Num of Columns', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'condition' => [
					'viewType' => 'grid',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'typo_section', [
				'label' => esc_html__( 'Typography', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} *[class$="__title"]',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Excerpt Font', 'foxiz-core' ),
				'name'     => 'excerpt_font',
				'selector' => '{{WRAPPER}} *[class$="__excerpt"]',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Author, Date Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}} *[class$="__author"], {{WRAPPER}} *[class$="__date"]',
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
				'prefix_class' => '',
				'options'      => [
					'default-scheme' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'light-scheme'   => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'      => 'default-scheme',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( Plugin::$instance->editor->is_edit_mode() ) {

			echo '<div style="padding: 30px; color: #888; font-size: 1.5rem; font-weight: 700; text-align:center; border: 1px solid;">';
			if ( ! class_exists( '\Google\Web_Stories\Plugin' ) ) {
				esc_html_e( 'Web Stories Plugin Not Found, please install it first to use this block.', 'foxiz-core' );
			} else {
				esc_html_e( 'Web Stories Placeholder', 'foxiz-core' );
			}
			echo '</div>';

			return false;
		}

		if ( ! class_exists( '\Google\Web_Stories\Plugin' ) ) {
			return false;
		}

		$settings   = $this->get_settings();
		$data       = [];
		$fieldState = [];
		$taxQuery   = [];

		$data['blockType']    = ! empty( $settings['blockType'] ) ? $settings['blockType'] : "latest-stories";
		$data['viewType']     = ! empty( $settings['viewType'] ) ? $settings['viewType'] : "grid";
		$data['numOfStories'] = ! empty( $settings['numOfStories'] ) ? $settings['numOfStories'] : 4;

		if ( ! empty( $settings['authors'] ) ) {
			$data['authors'] = array_map( 'absint', explode( ',', $settings['authors'] ) );
		}

		if ( ! empty( $settings['stories'] ) && 'selected-stories' === $data['blockType'] ) {
			$data['stories'] = array_map( 'absint', explode( ',', $settings['stories'] ) );
		}

		if ( ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'] ) {
			$fieldState['show_title'] = true;
		} else {
			$fieldState['show_title'] = false;
		}

		if ( ! empty( $settings['show_author'] ) && 'yes' === $settings['show_author'] ) {
			$fieldState['show_author'] = true;
		} else {
			$fieldState['show_author'] = false;
		}

		if ( ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'] ) {
			$fieldState['show_date'] = true;
		} else {
			$fieldState['show_date'] = false;
		}
		if ( ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'] ) {
			$fieldState['show_excerpt'] = true;
		} else {
			$fieldState['show_excerpt'] = false;
		}

		if ( ! empty( $settings['show_sharp_corners'] ) && 'yes' === $settings['show_sharp_corners'] ) {
			$fieldState['show_sharp_corners'] = true;
		} else {
			$fieldState['show_sharp_corners'] = false;
		}

		if ( ! empty( $settings['imageAlignment'] ) && 'right' === $settings['imageAlignment'] && 'list' === $data['viewType'] ) {
			$fieldState['show_image_alignment'] = true;
			$data['imageAlignment']             = 'right';
		} else {
			$fieldState['show_image_alignment'] = false;
		}

		if ( ! empty( $settings['show_archive_link'] ) && 'yes' === $settings['show_archive_link'] && ! empty( $settings['archive_link_label'] ) ) {
			$data['archiveLinkLabel']        = $settings['archive_link_label'];
			$fieldState['show_archive_link'] = true;
		} else {
			$fieldState['show_archive_link'] = false;
		}

		if ( ! empty( $settings['circleSize'] ) && 'circles' === $data['viewType'] ) {
			$data['circleSize']             = absint( $settings['circleSize'] );
			$fieldState['show_circle_size'] = true;
		} else {
			$fieldState['show_circle_size'] = false;
		}

		if ( ! empty( $settings['numOfColumns'] ) && 'grid' === $data['viewType'] ) {
			$data['numOfColumns']                 = absint( $settings['numOfColumns'] );
			$fieldState['show_number_of_columns'] = true;
		} else {
			$fieldState['show_number_of_columns'] = false;
		}
		if ( ! empty( $settings['web_story_category'] ) ) {
			$taxQuery['web_story_category'] = array_map( 'absint', explode( ',', $settings['web_story_category'] ) );
		}

		if ( ! empty( $settings['web_story_tag'] ) ) {
			$taxQuery['web_story_tag'] = array_map( 'absint', explode( ',', $settings['web_story_tag'] ) );
		}

		$data['fieldState'] = $fieldState;

		if ( count( $taxQuery ) ) {
			$data['taxQuery'] = $taxQuery;
		}
		echo apply_filters( 'the_content', '<!-- wp:web-stories/embed ' . wp_json_encode( $data ) . ' /-->' );
	}
}