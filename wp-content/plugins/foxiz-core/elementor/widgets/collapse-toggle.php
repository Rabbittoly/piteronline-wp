<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_mobile_toggle_btn;

/**
 * Class Header_Collapse_Toggle
 *
 * @package foxizElementor\Widgets
 */
class Header_Collapse_Toggle extends Widget_Base {

	public function get_name() {

		return 'foxiz-collapse-toggle';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Mobile Collapse Toggle', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-menu-toggle';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'mobile', 'toggle', 'collapse', 'button' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'search-icon-section', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'toggle_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'PLEASE NOTE: This block is intended exclusively for the mobile header template.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_mobile_toggle_btn' ) ) {
			foxiz_mobile_toggle_btn();
		}
	}
}