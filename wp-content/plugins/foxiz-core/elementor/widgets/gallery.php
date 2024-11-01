<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_simple_gallery;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Simple_Gallery extends Widget_Base {

	public function get_name() {

		return 'foxiz-simple-gallery';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Simple Gallery', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-gallery-grid';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'images', 'showcase', 'list', 'photo', 'gallery' ];
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
		$gallery_item = new Repeater();
		$gallery_item->add_control(
			'image',
			[
				'label' => esc_html__( 'Item Image', 'foxiz-core' ),
				'type'  => Controls_Manager::MEDIA,
				'ai'    => [ 'active' => false ],
			]
		);
		$gallery_item->add_control(
			'title',
			[
				'label'   => esc_html__( 'Item Title', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'ai'      => [ 'active' => false ],
				'rows'    => 1,
				'default' => '',
			]
		);
		$gallery_item->add_control(
			'description',
			[
				'label'   => esc_html__( 'Item Description', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'ai'      => [ 'active' => false ],
				'rows'    => 2,
				'default' => '',
			]
		);
		$gallery_item->add_control(
			'meta',
			[
				'label'   => esc_html__( 'Meta', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$gallery_item->add_control(
			'link',
			[
				'label'   => esc_html__( 'Item URL', 'foxiz-core' ),
				'type'    => Controls_Manager::URL,
				'default' => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => false,
				],
			]
		);
		$this->add_control(
			'gallery_data',
			[
				'label'       => esc_html__( 'Add Gallery Item', 'foxiz-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $gallery_item->get_controls(),
				'default'     => [
					[
						'title'       => esc_html__( 'Item #1', 'foxiz-core' ),
						'description' => '',
						'image'       => '',
						'link'        => '',
						'meta'        => '',
					],
				],
				'title_field' => '{{{ title }}} - {{{ description }}}',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'image_section', [
				'label' => esc_html__( 'Image', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'image_style', [
				'label'       => esc_html__( 'Image Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a style for the gallery image', 'foxiz-core' ),
				'options'     => [
					'shadow'   => esc_html__( 'Shadow', 'foxiz-core' ),
					'border'   => esc_html__( 'Dark Border', 'foxiz-core' ),
					'g-border' => esc_html__( 'Gray Border', 'foxiz-core' ),
					'none'     => esc_html__( 'None', 'foxiz-core' ),
				],
				'default'     => 'shadow',
			]
		);
		$this->add_responsive_control(
			'image_border_width', [
				'label'       => esc_html__( 'Image Border Width', 'foxiz-core' ),
				'description' => esc_html__( 'Input a custom border width value for the gallery image.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}} .simple-gallery-image img' => 'border-width: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'image_border_radius', [
				'label'       => esc_html__( 'Image Border Radius', 'foxiz-core' ),
				'description' => esc_html__( 'Input a custom border radius value for the gallery image.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}} .simple-gallery-image img' => 'border-radius: {{VALUE}}px;' ],
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

		$this->start_controls_section(
			'title_section', [
				'label' => esc_html__( 'Title', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the title.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .simple-gallery-title' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_title_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the title in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#fff',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .simple-gallery-title' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'heading_font',
				'selector' => '{{WRAPPER}} .simple-gallery-title',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'description_section', [
				'label' => esc_html__( 'Description', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the description', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .simple-gallery-desc' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_desc_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for the description in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#eee',
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .simple-gallery-desc' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Description Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .simple-gallery-desc',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'meta_section', [
				'label' => esc_html__( 'Meta', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Meta Font', 'foxiz-core' ),
				'name'     => 'meta_font',
				'selector' => '{{WRAPPER}} .simple-gallery-meta',
			]
		);
		$this->add_responsive_control(
			'meta_border_radius', [
				'label'       => esc_html__( 'Border Radius', 'foxiz-core' ),
				'description' => esc_html__( 'Input a custom border radius value for the meta.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}} .simple-gallery-meta' => 'border-radius: {{VALUE}}px;' ],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'label'    => esc_html__( 'Meta Background', 'foxiz-core' ),
				'name'     => 'meta_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .simple-gallery-meta',
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
			'block_columns', [
				'label' => esc_html__( 'Columns', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns on Desktop', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_description(),
				'options'     => Options::columns_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'columns_tablet',
			[
				'label'       => esc_html__( 'Columns on Tablet', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_tablet_description(),
				'options'     => Options::columns_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'columns_mobile',
			[
				'label'       => esc_html__( 'Columns on Mobile', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_mobile_description(),
				'options'     => Options::columns_dropdown( [ 0, 1, 2, 3, 4 ] ),
				'default'     => '0',
			]
		);
		$this->add_control(
			'column_gap',
			[
				'label'       => esc_html__( 'Column Gap', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::column_gap_description(),
				'options'     => Options::column_gap_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'column_gap_custom', [
				'label'       => esc_html__( '1/2 Custom Gap Value', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::column_gap_custom_description(),
				'selectors'   => [
					'{{WRAPPER}} .is-gap-custom'                  => 'margin-left: -{{VALUE}}px; margin-right: -{{VALUE}}px; --column-gap: {{VALUE}}px;',
					'{{WRAPPER}} .is-gap-custom .block-inner > *' => 'padding-left: {{VALUE}}px; padding-right: {{VALUE}}px;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'border_section', [
				'label' => esc_html__( 'Grid Borders', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'border_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::column_border_info(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'column_border',
			[
				'label'       => esc_html__( 'Column Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::column_border_description(),
				'options'     => Options::column_border_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'bottom_border',
			[
				'label'       => esc_html__( 'Bottom Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::bottom_border_description(),
				'options'     => Options::column_border_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'last_bottom_border',
			[
				'label'       => esc_html__( 'Last Bottom Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::last_bottom_border_description(),
				'options'     => Options::switch_dropdown( false ),
				'default'     => '-1',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'spacing_section', [
				'label' => esc_html__( 'Spacing', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_responsive_control(
			'el_spacing', [
				'label'       => esc_html__( 'Custom Element Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::el_spacing_description(),
				'selectors'   => [ '{{WRAPPER}} .block-wrap' => '--el-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'image_spacing', [
				'label'       => esc_html__( 'Image Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom a bottom spacing values (in pixels) for the images.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .block-wrap' => '--image-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'bottom_margin', [
				'label'       => esc_html__( 'Custom Bottom Margin', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::el_margin_description(),
				'selectors'   => [ '{{WRAPPER}} .block-wrap' => '--bottom-spacing: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'center_section', [
				'label' => esc_html__( 'Centering', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'center_mode',
			[
				'label'       => esc_html__( 'Centering Content', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::center_mode_description(),
				'options'     => Options::switch_dropdown( false ),
				'default'     => '1',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_get_simple_gallery' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();
			echo foxiz_get_simple_gallery( $settings );
		}
	}
}