<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_render_covid_data;

/**
 * Class Covid_Data
 *
 * @package foxizElementor\Widgets
 */
class Covid_Data extends Widget_Base {

	public function get_name() {

		return 'foxiz-covid-data';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Covid Data Tracker', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-plus-circle-o';
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_title', [
				'label' => esc_html__( 'Covid Data Settings', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'country_code',
			[
				'label'       => esc_html__( 'Country Code', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [ 'active' => false ],
				'description' => esc_html__( 'Input a country code you would like to display, e.g: US. Leave blank if you would like to display global data.', 'foxiz-core' ),
				'default'     => '',
			]
		);

		$this->add_control(
			'country_name',
			[
				'label'       => esc_html__( 'Country Name', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Input the country name of the code you have just added.', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'fallback_section', [
				'label' => esc_html__( 'Fallback Values', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'fallback_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The block will receive the data from an API automatically. Input fallback values if the server cannot fetch data.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'get_data_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Check data here: https://covid19.who.int/', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'confirmed',
			[
				'label'   => esc_html__( 'Confirmed Cases', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$this->add_control(
			'deaths',
			[
				'label'   => esc_html__( 'Death Cases', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'heading_style', [
				'label' => esc_html__( 'Block Design', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Virus Icon', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Show a virus icon in this block.', 'foxiz-core' ),
				'default'     => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'       => esc_html__( 'Name HTML Tag', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a title HTML tag for the country name.', 'foxiz-core' ),
				'options'     => Options::heading_html_dropdown(),
				'default'     => '0',
			]
		);

		$this->add_responsive_control(
			'title_tag_size', [
				'label'       => esc_html__( 'Name Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom font size values (in pixels) for the country name for displaying in this block.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .country-name > *' => 'font-size: {{VALUE}}px;' ],
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
			'font_section', [
				'label' => esc_html__( 'Custom Font', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'custom_font_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::custom_font_info_description(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Label Text Font', 'foxiz-core' ),
				'name'     => 'label_font',
				'selector' => '{{WRAPPER}} .description-text',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Total Number Font', 'foxiz-core' ),
				'name'     => 'count_font',
				'selector' => '{{WRAPPER}} .data-item-value',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * render layout
	 */
	protected function render() {

		if ( function_exists( 'foxiz_render_covid_data' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();

			echo foxiz_render_covid_data( $settings );
		}
	}
}