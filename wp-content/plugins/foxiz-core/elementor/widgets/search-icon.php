<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_elementor_header_search;

/**
 * Class Header_Search_Icon
 *
 * @package foxizElementor\Widgets
 */
class Header_Search_Icon extends Widget_Base {

	public function get_name() {

		return 'foxiz-search-icon';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Search Posts', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-search';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'builder', 'search' ];
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
			'search_layout',
			[
				'label'       => esc_html__( 'Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Show a text label after the search icon.', 'foxiz-core' ),
				'options'     => [
					'0'    => esc_html__( 'Icon Layout (Popup Form)', 'foxiz-core' ),
					'form' => esc_html__( 'Form Layout', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'post_type',
			[
				'label'       => esc_html__( 'Include Post Types', 'foxiz-core' ),
				'description' => esc_html__( 'Specify all post types you want to display on the search result page using the post_types key.', 'foxiz-core' ),
				'placeholder' => 'post, podcast',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
			]
		);
		$this->add_control(
			'ajax_search',
			[
				'label'       => esc_html__( 'Live Results', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Enable live search results when typing.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( '- Disable -', 'foxiz-core' ),
					'1' => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'limit',
			[
				'label'       => esc_html__( 'Limit Posts', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Limit the number of posts displayed in live search results (maximum is 10).', 'foxiz-core' ),
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
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Input custom placeholder for the search form.', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'form_border',
			[
				'label'       => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a border radius value for the search form', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown, {{WRAPPER}} .search-form-inner' => 'border-radius: {{VALUE}}px;' ],
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
					'{{WRAPPER}} i.wnav-icon, {{WRAPPER}} .search-btn > .search-icon-svg'                               => 'font-size: {{VALUE}}px;',
					'{{WRAPPER}} .is-form-layout .search-icon-svg, {{WRAPPER}} .is-form-layout .search-form-inner .rbi' => 'font-size: {{VALUE}}px;',
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
					'{{WRAPPER}} .is-form-layout'                                                                               => '--input-fcolor: {{VALUE}}',
					'{{WRAPPER}} i.wnav-icon, {{WRAPPER}} .header-search-label, {{WRAPPER}} .icon-holder > .search-icon-svg,
					{{WRAPPER}} .is-form-layout .search-form-inner, {{WRAPPER}} .is-form-layout .rb-loader' => 'color: {{VALUE}};',

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
					'[data-theme="dark"] {{WRAPPER}} .is-form-layout'                                                                                                   => '--input-fcolor: {{VALUE}}',
					'[data-theme="dark"] {{WRAPPER}} i.wnav-icon, [data-theme="dark"] {{WRAPPER}} .header-search-label, [data-theme="dark"] {{WRAPPER}} .icon-holder > .search-icon-svg,
					[data-theme="dark"] {{WRAPPER}} .is-form-layout .search-form-inner, [data-theme="dark"] {{WRAPPER}} .is-form-layout .rb-loader' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'layout-form-section', [
				'label' => esc_html__( 'for Icon Layout', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'search_label',
			[
				'label'       => esc_html__( 'Search Label', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'placeholder' => esc_html__( 'Search', 'foxiz-core' ),
				'description' => esc_html__( 'Show a text label after the search icon.', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label'       => esc_html__( 'Icon Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select a custom height value for the search icon.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} i.wnav-icon'  => 'line-height: {{VALUE}}px;',
					'{{WRAPPER}} .icon-holder' => 'min-height: {{VALUE}}px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Label Font', 'foxiz-core' ),
				'name'     => 'search_label_font',
				'selector' => '{{WRAPPER}} .header-search-label',
			]
		);
		$this->add_responsive_control(
			'align', [
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-align-start-h',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-align-center-h',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-align-end-h',
					],
				],
				'selectors' => [ '{{WRAPPER}} .w-header-search > .icon-holder' => 'justify-content: {{VALUE}};', ],
			]
		);
		$this->add_control(
			'form_position',
			[
				'label'       => esc_html__( 'Popup Right Position', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a right relative position for the popup search form, e.g. -200, this setting apply to the layout icon. ', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown' => 'right: {{VALUE}}px; left: auto;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'layout-icon-section', [
				'label' => esc_html__( 'for Form Layout', 'foxiz-core' ),
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
			'popup_text_color',
			[
				'label'       => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text in the popup form.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}}' => '--subnav-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'bg_from',
			[
				'label'       => esc_html__( 'Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the popup search form.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .header-dropdown, {{WRAPPER}} .is-form-layout .live-search-response' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'bg_to',
			[
				'label'       => esc_html__( 'Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the popup search form.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .header-dropdown, {{WRAPPER}} .is-form-layout .live-search-response' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'popup_dark_text_color',
			[
				'label'       => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the text in the popup form.', 'foxiz-core' ),
				'default'     => '',
				'selectors'   => [
					'[data-theme="dark"] {{WRAPPER}}' => '--subnav-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'dark_bg_from',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (From)', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background color (color stop: 0%) for the popup search form in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .header-dropdown, [data-theme="dark"] {{WRAPPER}} .is-form-layout .live-search-response' => '--subnav-bg: {{VALUE}}; --subnav-bg-from: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_bg_to',
			[
				'label'       => esc_html__( 'Dark Mode - Background Gradient (To)', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color (color stop: 100%) for the popup search form in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}} .header-dropdown, [data-theme="dark"] {{WRAPPER}} .is-form-layout .live-search-response' => '--subnav-bg-to: {{VALUE}};' ],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'live-search-section', [
				'label' => esc_html__( 'Live Search Results', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
		$this->add_control(
			'featured_width', [
				'label'       => esc_html__( 'Image Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::featured_width_description(),
				'selectors'   => [
					'{{WRAPPER}} .feat-holder' => 'width: {{VALUE}}px; max-width: {{VALUE}}px;',
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

		$settings = $this->get_settings();

		if ( ! empty( $settings['header_search_custom_icon'] ) ) {
			$settings['header_search_custom_icon'] = [
				'url' => $settings['header_search_custom_icon'],
			];
		} else {
			$settings['header_search_custom_icon'] = foxiz_get_option( 'header_search_custom_icon' );
		}
		if ( ! empty( $settings['post_type'] ) ) {
			$settings['post_type'] = preg_replace( '/\s+/', '', strip_tags( $settings['post_type'] ) );
		}
		foxiz_elementor_header_search( $settings );
	}
}