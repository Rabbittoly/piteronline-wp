<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_header_search_form;

class Search_Category extends Widget_Base {

	public function get_name() {

		return 'foxiz-search-category';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Search Taxonomies', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-search';
	}

	public function get_keywords() {

		return [
			'foxiz',
			'ruby',
			'bookmark',
			'follow',
			'recommended',
			'search',
			'form',
			'category',
			'taxonomy',
			'tag',
		];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general-section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'taxonomies',
			[
				'label'       => esc_html__( 'Search by Taxonomy Keys', 'foxiz-core' ),
				'description' => esc_html__( 'The search only returns categories by default. Input the taxonomy slugs/names/keys separated by commas (e.g., category, post_tag, genre) to include additional taxonomies. Input "all" to show all taxonomies.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => 'category, post_tag, genre',
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'default'     => '',
			]
		);
		$this->add_control(
			'limit',
			[
				'label'       => esc_html__( 'Limit Taxonomies', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Limit the number of taxonomies displayed in the search results to a maximum of 6.', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'follow',
			[
				'label'       => esc_html__( 'Follow Button', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => Options::switch_dropdown( false ),
				'description' => esc_html__( 'Enable or disable the follow button.', 'foxiz-core' ),
				'default'     => '-1',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'search-icon-section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'header_search_placeholder',
			[
				'label'       => esc_html__( 'Search Form Placeholder', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input custom placeholder for the search form.', 'foxiz-core' ),
				'default'     => 'Search by Category..',
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
			]
		);
		$this->add_control(
			'form_border',
			[
				'label'       => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a border radius value for the search form', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--round-5: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'header_search_custom_icon',
			[
				'label'       => esc_html__( 'Search Icon', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Override the default search icon with an SVG icon by inputting the file URL of your SVG icon.', 'foxiz-core' ),
				'placeholder' => esc_html__( 'https://yourdomain.com/wp-content/uploads/....filename.svg', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .search-icon-svg' => 'mask-image: url({{VALUE}}); -webkit-mask-image: url({{VALUE}}); background-image: none;',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label'       => esc_html__( 'Icons Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom font size for the search icon.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--icon-size: {{VALUE}}px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Input Font', 'foxiz-core' ),
				'name'     => 'search_input_font',
				'selector' => '{{WRAPPER}} input[type="text"]',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'       => esc_html__( 'Text & Icons Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the icon and text for this search form.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}}' => '--input-fcolor: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'dark_icon_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text & Icons Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the icon and text for this search form in dark mode.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => '--input-fcolor: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'layout-icon-section', [
				'label' => esc_html__( 'Input Form', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'header_search_style',
			[
				'label'   => esc_html__( 'Form Style', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'0'    => esc_html__( '- Default -', 'foxiz-core' ),
					'line' => esc_html__( 'Underline', 'foxiz-core' ),
					'bold' => esc_html__( 'Bold Underline', 'foxiz-core' ),
					'gray' => esc_html__( 'Gray Background', 'foxiz-core' ),
					'none' => esc_html__( 'None', 'foxiz-core' ),
				],
				'default' => '0',
			]
		);
		$this->add_control(
			'form_color',
			[
				'label'       => esc_html__( 'Form Style Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color based on your form style.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}}' => '--search-form-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_form_color',
			[
				'label'       => esc_html__( 'Dark Mode - Form Style Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select a color based on your form style in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--search-form-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'dropdown-section', [
				'label' => esc_html__( 'Popup Section', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'popup_padding',
			[
				'label'       => esc_html__( 'Inner Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Input a custom inner padding value for the popup section.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .live-search-inner' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->add_control(
			'bg_from',
			[
				'label'       => esc_html__( 'Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the popup search form.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '{{WRAPPER}} .live-search-response' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'bg_to',
			[
				'label'       => esc_html__( 'Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the popup search form.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .live-search-response' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_from',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the popup search form in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .live-search-response' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_to',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the popup search form in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .live-search-response' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'live-search-section', [
				'label' => esc_html__( 'Live Search Results', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'desc_source',
			[
				'label'       => esc_html__( 'Description', 'foxiz-core' ),
				'description' => esc_html__( 'Select the source for the description. Use with caution as including total posts with children may impact hosting speed during live Ajax search.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'desc' => esc_html__( 'Taxonomy Description', 'foxiz-core' ),
					'0'    => esc_html__( 'Total Posts', 'foxiz-core' ),
					'2'    => esc_html__( 'Total Posts Include Children', 'foxiz-core' ),
					'none' => esc_html__( 'None', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .cbox-title',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Description Font', 'foxiz-core' ),
				'name'     => 'desc_font',
				'selector' => '{{WRAPPER}} .cbox-count',
			]
		);
		$this->add_responsive_control(
			'featured_width', [
				'label'       => esc_html__( 'Featured Image Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::featured_width_description(),
				'selectors'   => [
					'{{WRAPPER}} .cbox-featured-holder' => 'width: {{VALUE}}px; max-width: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'header_search_scheme',
			[
				'label'       => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select color scheme for the search form to fit with your background.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'1' => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( ! function_exists( 'foxiz_header_search_form' ) ) {
			return false;
		}

		$settings                = $this->get_settings();
		$settings['search_type'] = 'category';
		$settings['ajax_search'] = '1';

		if ( ! empty( $settings['header_search_custom_icon'] ) ) {
			$settings['header_search_custom_icon'] = [
				'url' => $settings['header_search_custom_icon'],
			];
		} else {
			$settings['header_search_custom_icon'] = foxiz_get_option( 'header_search_custom_icon' );
		}

		foxiz_header_search_form( $settings );
	}
}