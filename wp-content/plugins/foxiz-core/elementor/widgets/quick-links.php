<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_quick_links;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Quick_links extends Widget_Base {

	public function get_name() {

		return 'foxiz-quick-links';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Quick Links', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-editor-link';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'menu', 'links' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$quick_links = new Repeater();
		$this->start_controls_section(
			'content-label-section', [
				'label' => esc_html__( 'Label', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'header',
			[
				'label'       => esc_html__( 'Label', 'foxiz-core' ),
				'description' => esc_html__( 'HTML tags allowed in case you want to add an icon (i tag).', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'default'     => esc_html__( 'Quick Links', 'foxiz-core' ),
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'source-section', [
				'label' => esc_html__( 'Data Source', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'source', [
				'label'       => esc_html__( 'Source', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a data source to display quick link data.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'0'    => esc_html__( 'form Manually Add', 'foxiz-core' ),
					'tax'  => esc_html__( 'from Top Taxonomies', 'foxiz-core' ),
					'both' => esc_html__( 'from Manually Add & Taxonomies', 'foxiz-core' ),
				],
				'default'     => '0',

			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'tax-section', [
				'label' => esc_html__( 'for Top Taxonomies', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'source_tax', [
				'label'       => esc_html__( 'Taxonomy Keys', 'foxiz-core' ),
				'description' => esc_html__( 'Input the taxonomy slugs/names/keys you want to collect, separated by commas if you want to display multiple taxonomies (e.g., category, post_tag, genre).', 'foxiz-core' ),
				'placeholder' => 'category, post_tag',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'default'     => 'category, post_tag',
			]
		);
		$this->add_control(
			'total', [
				'label'       => esc_html__( 'Total', 'foxiz-core' ),
				'description' => esc_html__( 'Max taxonomy items to show.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content-section', [
				'label' => esc_html__( 'for Manually Add', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$quick_links->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'foxiz-core' ),
				'description' => esc_html__( 'HTML tags allowed in case you want to add an icon (i tag).', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'placeholder' => esc_html__( 'Trending', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$quick_links->add_control(
			'url',
			[
				'label' => esc_html__( 'URL', 'foxiz-core' ),
				'type'  => Controls_Manager::URL,
			]
		);
		$this->add_control(
			'quick_links',
			[
				'label'       => esc_html__( 'Add Quick Link', 'foxiz-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $quick_links->get_controls(),
				'default'     => [
					[
						'url'   => '',
						'title' => esc_html__( 'Quick Link #1', 'foxiz-core' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'label-section', [
				'label' => esc_html__( 'Block Label', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Label Font', 'foxiz-core' ),
				'name'     => 'heading_font',
				'selector' => '{{WRAPPER}} .qlink-label',
			]
		);
		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Label Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--label-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_label_color',
			[
				'label'     => esc_html__( 'Dark Mode - Label Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}' => '--label-color: {{VALUE}};' ],
			]
		);
		$this->add_responsive_control(
			'label_spacing', [
				'label'     => esc_html__( 'Right Spacing', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [ '{{WRAPPER}}' => '--label-spacing: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'item-section', [
				'label' => esc_html__( 'Quick Link Items', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Item Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .qlink a',
			]
		);
		$this->add_responsive_control(
			'item_spacing', [
				'label'       => esc_html__( 'Column Spacing', 'foxiz-core' ),
				'description' => esc_html__( 'Input gap spacing between quick link item.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}}' => '--qlink-gap: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'row_spacing', [
				'label'       => esc_html__( 'Row Spacing', 'foxiz-core' ),
				'description' => esc_html__( 'Input row gap for the block in case you wrap items.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}}' => '--r-qlink-gap: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Item Style', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( 'Text Only', 'foxiz-core' ),
					'2' => esc_html__( 'Button', 'foxiz-core' ),
					'3' => esc_html__( 'Underline', 'foxiz-core' ),
					'4' => esc_html__( 'Border', 'foxiz-core' ),
				],
				'default' => '1',
			]
		);
		$this->add_control(
			'item_color',
			[
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--qlink-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_item_color',
			[
				'label'     => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--qlink-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hover_effect',
			[
				'label'     => esc_html__( 'Hover Effect', 'foxiz-core' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'layout' => [ '1', '3' ],
				],
				'options'   => [
					'underline' => esc_html__( 'Underline Line', 'foxiz-core' ),
					'dotted'    => esc_html__( 'Underline Dotted', 'foxiz-core' ),
					'double'    => esc_html__( 'Underline Double', 'foxiz-core' ),
					'color'     => esc_html__( 'Text Color', 'foxiz-core' ),
				],
				'default'   => 'underline',
			]
		);
		$this->add_control(
			'item_bg',
			[
				'label'     => esc_html__( 'Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'layout' => '2',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--qlink-bg: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dark_item_bg',
			[
				'label'     => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'layout' => '2',
				],
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--qlink-bg: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'inner_padding',
			[
				'label'     => esc_html__( 'Item Padding', 'foxiz-core' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => [
					'layout' => [ '2', '4' ],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--qlink-padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'item_border', [
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => [
					'layout' => [ '2', '4' ],
				],
				'selectors' => [ '{{WRAPPER}}' => '--round-3: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'overflow-section', [
				'label' => esc_html__( 'Overflow', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'overflow',
			[
				'label'   => esc_html__( 'Overflow', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'0' => esc_html__( 'Desktop Line Wrap - Mobile Horizontal Scroll', 'foxiz-core' ),
					'2' => esc_html__( 'Line Wrap', 'foxiz-core' ),
					'3' => esc_html__( 'Horizontal Scroll', 'foxiz-core' ),
				],
				'default' => '0',
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'condition' => [
					'overflow' => [ '0', '2' ],
				],
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .qlinks-inner' => 'justify-content: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'divider-section', [
				'label' => esc_html__( 'Divider', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'divider', [
				'label'        => esc_html__( 'Divider Style', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a divider style between quick link items.', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'0'      => esc_html__( 'None', 'foxiz-core' ),
					'slash'  => esc_html__( 'Slash (/)', 'foxiz-core' ),
					'pipe'   => esc_html__( 'Pipe (|)', 'foxiz-core' ),
					'hyphen' => esc_html__( 'Hyphen (-)', 'foxiz-core' ),
					'dot'    => esc_html__( 'Dot (.)', 'foxiz-core' ),
				],
				'prefix_class' => 'is-divider-',
				'default'      => '0',

			]
		);
		$this->add_control(
			'divider_color',
			[
				'label'       => esc_html__( 'Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for divider.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--divider-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_divider_color',
			[
				'label'       => esc_html__( 'Dark Mode - Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color for divider in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--divider-color: {{VALUE}};' ],
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
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_get_quick_links' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();
			echo foxiz_get_quick_links( $settings );
		}
	}
}