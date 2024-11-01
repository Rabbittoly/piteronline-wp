<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_mobile_header_mini_cart;

/**
 * Class Header_Mobile_Cart
 *
 * @package foxizElementor\Widgets
 */
class Header_Mobile_Cart extends Widget_Base {

	public function get_name() {

		return 'foxiz-header-mcart';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Mobile Mini Cart Icon', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-menu-toggle';
	}

	public function get_keywords() {

		return [
			'foxiz',
			'ruby',
			'header',
			'template',
			'builder',
			'shop',
			'popup',
			'product',
			'woocommerce',
			'mobile',
		];
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
			'mobile_cart_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'PLEASE NOTE: This block is intended exclusively for the mobile header template.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_mobile_header_mini_cart' ) ) {
			foxiz_mobile_header_mini_cart();
		}
	}
}