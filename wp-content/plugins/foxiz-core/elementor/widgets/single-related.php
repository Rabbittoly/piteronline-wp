<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_single_footer;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Single_Related extends Widget_Base {

	public function get_name() {

		return 'foxiz-single-related';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Post Related', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-archive-posts';
	}

	public function get_keywords() {

		return [ 'single', 'template', 'builder', 'related', 'list' ];
	}

	public function get_categories() {

		return [ 'foxiz_single' ];
	}

	protected function register_controls() {

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

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="s-related-placeholder">' . esc_html__( 'Dynamic post related section', 'foxiz-core' ) . '</div>';
		} else {
			if ( function_exists( 'foxiz_single_footer' ) ) {
				foxiz_single_footer( true );
			}
		}
	}

}