<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_tax_based_accordion;

/**
 * Class Tax_Accordion
 *
 * @package foxizElementor\Widgets
 */
class Tax_Accordion extends Widget_Base {

	public function get_name() {

		return 'foxiz-tax-accordion';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Tax-based Accordion Posts', 'foxiz-core' );
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'category', 'tax', 'tag', 'accordion', 'posts' ];
	}

	public function get_icon() {

		return 'eicon-folder-o';
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general_section', [
				'label' => esc_html__( 'Add Taxonomies', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$categories = new Repeater();
		$categories->add_control(
			'category',
			[
				'label'   => esc_html__( 'Select a Category', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Options::cat_slug_dropdown( 'post', esc_html__( '- Select a category -', 'foxiz-core' ) ),
				'default' => 0,
			]
		);
		$categories->add_control(
			'tax_id',
			[
				'label'       => esc_html__( 'or Term ID', 'foxiz-core' ),
				'description' => esc_html__( 'Input the tag or taxonomy Term ID; ensure that the featured image for this taxonomy is set for display in Posts > Edit "your taxonomy".', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'default'     => '',
			]
		);
		$categories->add_control(
			'post_type',
			[
				'label'       => esc_html__( 'Custom Post Type', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => Options::post_type_dropdown(),
				'description' => Options::post_type_description(),
				'default'     => '0',
			]
		);
		$categories->add_control(
			'tax_title',
			[
				'label'       => esc_html__( 'Custom Taxonomy Title', 'foxiz-core' ),
				'description' => esc_html__( 'Input custom title for this taxonomy, inline raw HTML is allowed for icon.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'default'     => '',
			]
		);
		$this->add_control(
			'categories',
			[
				'label'       => esc_html__( 'Add Taxonomies', 'foxiz-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $categories->get_controls(),
				'default'     => [
					[
						'category' => '',
						'tax_id'   => '',
					],
				],
				'title_field' => '{{{ tax_id ? "Term ID: " + tax_id : "Category: " + category }}}',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'query_filters', [
				'label' => esc_html__( 'Query Post Settings', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'query_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'These settings below will apply to the post listing of each taxonomy or category that has been added.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Order By', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::order_description(),
				'options'     => Options::order_dropdown(),
				'default'     => 'alphabetical_order_asc',
			]
		);
		$this->add_control(
			'posts_per_page',
			[
				'label'       => esc_html__( 'Number of Posts', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::posts_per_page_description(),
				'default'     => '10',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'cache_section', [
				'label' => esc_html__( 'Cache', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'cache_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Cached for 2 hours to minimize query calls. This means that if you add new posts, this block will not apply immediately, especially beneficial for numerous taxonomies.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'cache_interval', [
				'label'       => esc_html__( 'Cache Interval', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Enter time in seconds to clear cache for this block.', 'foxiz-core' ),
				'default'     => 7200,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'tax_design_section', [
				'label' => esc_html__( 'Taxonomy Title', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'tax_title_tag',
			[
				'label'   => esc_html__( 'Taxonomy Title HTML Tag', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Options::heading_html_dropdown(),
				'default' => '0',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Taxonomy Title Font', 'foxiz-core' ),
				'name'     => 'tax_font',
				'selector' => '{{WRAPPER}} .tax-title',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'design_section', [
				'label' => esc_html__( 'Post Title', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_icon_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Ensure the setting "Theme Options > Theme Design > Font Awesome" is enabled if you use a FontAwesome class name.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'title_icon_foxiz_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Theme icons: https://icons.themeruby.com/foxiz/.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'title_icon',
			[
				'label'       => esc_html__( 'Title Icon', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a icon classname to show the icon before the post title.', 'foxiz' ),
				'placeholder' => esc_html__( 'rbi rbi-next', 'foxiz' ),
				'options'     => Options::heading_html_dropdown(),
				'default'     => 'rbi rbi-next',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'       => esc_html__( 'Title HTML Tag', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::heading_html_description(),
				'options'     => Options::heading_html_dropdown(),
				'default'     => 'div',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .entry-title',
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
			'border_section', [
				'label' => esc_html__( 'Grid Borders', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'border_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'These settings will apply to the post listing inside each taxonomy tab.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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
		$this->end_controls_section();
		$this->start_controls_section(
			'spacing_section', [
				'label' => esc_html__( 'Spacing', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_responsive_control(
			'tax_title_padding', [
				'label'       => esc_html__( 'Tax Title Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Enter custom padding margin values (in pixels) between tax titles.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--tax-title-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'bottom_margin', [
				'label'       => esc_html__( 'Post Title Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Enter custom padding margin values (in pixels) between post titles.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--ap-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'left_padding', [
				'label'       => esc_html__( 'Post Title Left Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Enter custom left padding values (in pixels) for post titles.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--ap-left-spacing: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_get_tax_based_accordion' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				delete_transient( 'foxiz_tax_based_' . $settings['uuid'] );
				$settings['yes_edit_mode'] = true;
			}

			echo foxiz_get_tax_based_accordion( $settings );
		}
	}
}