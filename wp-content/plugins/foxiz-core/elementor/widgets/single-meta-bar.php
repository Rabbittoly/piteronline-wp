<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_single_header_meta;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Meta_Bar extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-meta-bar';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Post Meta Bar', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-meta-data';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'meta' ];
	}

	public function get_categories() {

		return [ 'foxiz_single' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'meta_section', [
				'label' => esc_html__( 'Entry Meta Tags', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'entry_meta_flex_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::meta_flex_description(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'entry_meta_prefix_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::meta_prefix_description(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'entry_meta',
			[
				'label'       => esc_html__( 'Entry Meta Tags', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => Options::entry_meta_tags_description(),
				'placeholder' => Options::entry_meta_tags_placeholder(),
				'default'     => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Layout', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'meta_bar_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'To access the default settings, please go to "Theme Options > Single Post > Entry Meta".', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'meta_font_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Recommendation: Use the same font family for both the "META" and "META BOLD" settings.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-success',
			]
		);
		$this->add_control(
			'meta_layout',
			[
				'label'       => esc_html__( 'Right Section Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a layout for the right section of the entry meta.', 'foxiz-core' ),
				'options'     => [
					'0'        => esc_html__( '- Default -', 'foxiz-core' ),
					'standard' => esc_html__( 'Standard', 'foxiz-core' ),
					'wrap'     => esc_html__( 'Wrap - Highlight', 'foxiz-core' ),
					'minimal'  => esc_html__( 'Wrap - Minimalist', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'meta_centered',
			[
				'label'       => esc_html__( 'Center Entry Meta', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Center the meta bar. It is recommended that you consider turning off the big avatar and last updated meta when centering.', 'foxiz-core' ),
				'options'     => [
					'0'  => esc_html__( '- Default -', 'foxiz-core' ),
					'-1' => esc_html__( 'Left', 'foxiz-core' ),
					'1'  => esc_html__( 'Center', 'foxiz-core' ),
				],

				'default' => '0',
			]
		);
		$this->add_control(
			'meta_divider',
			[
				'label'       => esc_html__( 'Divider Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::meta_divider_description(),
				'options'     => Options::meta_divider_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'meta_border',
			[
				'label'       => esc_html__( 'Top Border', 'foxiz-core' ),
				'description' => esc_html__( 'Show a gray border at the top of the meta bar', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'0'  => esc_html__( '- Default -', 'foxiz-core' ),
					'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}} .is-meta, {{WRAPPER}} .meta-text',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Bold Meta Font', 'foxiz-core' ),
				'name'     => 'bold_meta_font',
				'selector' => '{{WRAPPER}} .meta-author, {{WRAPPER}} .meta-bold',
			]
		);
		$this->add_control(
			'meta_author_style',
			[
				'label'       => esc_html__( 'Author Meta Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a style for the author meta', 'foxiz-core' ),
				'options'     => [
					'0'         => esc_html__( '- Default -', 'foxiz-core' ),
					'underline' => esc_html__( 'Underline', 'foxiz-core' ),
					'bold'      => esc_html__( 'Bold Underline', 'foxiz-core' ),
					'dot'       => esc_html__( 'Dotted', 'foxiz-core' ),
					'wavy'      => esc_html__( 'Wavy', 'foxiz-core' ),
					'color'     => esc_html__( 'Color', 'foxiz-core' ),
					'text'      => esc_html__( 'Text Only', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'meta_bookmark_style',
			[
				'label'       => esc_html__( 'Bookmark Meta Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a style for the bookmark icon.', 'foxiz-core' ),
				'options'     => [
					'0'      => esc_html__( '- Default -', 'foxiz-core' ),
					'text'   => esc_html__( 'Text Only', 'foxiz-core' ),
					'border' => esc_html__( 'Gray Bolder', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'avatar-section', [
				'label' => esc_html__( 'Big Avatar', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'avatar',
			[
				'label'       => esc_html__( 'Big Avatar', 'foxiz-core' ),
				'description' => esc_html__( 'Tips: consider using the avatar meta and disable it if you want to center the bar to avoid layout issues.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'0'  => esc_html__( '- Default -', 'foxiz-core' ),
					'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'big_avatar_size', [
				'label'       => esc_html__( 'Image Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '60',
				'selectors'   => [ '{{WRAPPER}}' => '--b-avatar-size:  {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'big_avatar_border', [
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--avatar-radius: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'right_section', [
				'label' => esc_html__( 'Right Section', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'min_read',
			[
				'label'   => esc_html__( 'Reading Time', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'0'  => esc_html__( '- Default -', 'foxiz-core' ),
					'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default' => '0',
			]
		);
		$this->add_responsive_control(
			'share_size', [
				'label'       => esc_html__( 'Share Icons Size', 'foxiz-core' ),
				'description' => esc_html__( 'To configure social sharing options, navigate to "Theme Options > Single Post > Entry Meta > Right Section - Share on Social".', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '16',
				'selectors'   => [ '{{WRAPPER}} .t-shared-sec .share-action' => 'font-size: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'updated-section', [
				'label' => esc_html__( 'Last Updated Date', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'updated_meta',
			[
				'label'   => esc_html__( 'Updated Meta', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'0'  => esc_html__( '- Default -', 'foxiz-core' ),
					'-1' => esc_html__( 'Disable', 'foxiz-core' ),
					'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default' => '0',
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

		if ( function_exists( 'foxiz_single_header_meta' ) ) {
			foxiz_single_header_meta( 'single_post', $this->get_settings() );
		}
	}
}