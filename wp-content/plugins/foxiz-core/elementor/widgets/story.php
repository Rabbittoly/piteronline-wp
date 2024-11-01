<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Google\Web_Stories\Model\Story;
use function wp_json_encode;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Web_Story extends Widget_Base {

	public function get_name() {

		return 'foxiz-story';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Web Single Story', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-slides';
	}

	public function get_keywords() {

		return [ 'google', 'story' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		if ( ! class_exists( '\Google\Web_Stories\Plugin' ) ) {
			return;
		}

		$stories_dropdown = [
			'0' => esc_html__( '- Select a story -', 'foxiz-core' ),
		];

		$query_data = get_posts( [
			'numberposts' => - 1,
			'post_type'   => 'web-story',
			'post_status' => 'publish',
		] );

		if ( $query_data ) {
			foreach ( $query_data as $post ) {
				$stories_dropdown[ $post->ID ] = $post->post_title;
			}
		}

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'Query Filter', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'web_stories_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This block requires the "Web Stories" plugin to be installed and activated in order to function properly.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);

		$this->add_control(
			'story',
			[
				'label'   => esc_html__( 'Select Story', 'foxiz-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $stories_dropdown,
				'default' => '0',
			]
		);
		$this->add_control(
			'previewOnly',
			[
				'label'   => esc_html__( 'Poster Preview', 'foxiz-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		$this->add_control(
			'width',
			[
				'label'   => esc_html__( 'Story Width', 'foxiz-core' ),
				'type'    => Controls_Manager::NUMBER,
				'ai'      => [ 'active' => false ],
				'default' => 360,
			]
		);
		$this->add_control(
			'height',
			[
				'label'   => esc_html__( 'Story Height', 'foxiz-core' ),
				'type'    => Controls_Manager::NUMBER,
				'ai'      => [ 'active' => false ],
				'default' => 600,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'typo_section', [
				'label' => esc_html__( 'Typography', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} *[class$="__title"]',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( ! class_exists( '\Google\Web_Stories\Plugin' ) ) {
			return false;
		}

		$settings = $this->get_settings();

		if ( empty( $settings['story'] ) ) {
			return false;
		}

		$post = get_post( $settings['story'] );
		if ( empty( $post ) ) {
			return false;
		}

		$story = new Story();
		$story->load_from_post( $post );

		$data = [
			'blockType' => 'url',
			'url'       => $story->get_url(),
			'title'     => $story->get_title(),
			'poster'    => $story->get_poster_portrait(),
		];

		if ( ! empty( $settings['previewOnly'] ) && 'yes' === $settings['previewOnly'] ) {
			$data['previewOnly'] = true;
		}

		$data['width']  = ! empty( $settings['width'] ) ? absint( $settings['width'] ) : 360;
		$data['height'] = ! empty( $settings['height'] ) ? absint( $settings['height'] ) : 600;

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div style="height: ' . $data['height'] . 'px; width: ' . $data['width'] . 'px; max-width: 100%; position: relative;">';
			if ( ! empty( $data['poster'] ) ) {
				echo '<img style="height: 100%; width: 100%; object-fit:cover" src="' . $data['poster'] . '"/>';
			} else {
				echo '<div style="width: 100%; height: 100%; background: #eee"></div>';
			}
			echo '<h3 class="web-stories__title" style="color: #fff; position: absolute; bottom: 20px; left: 20px;">' . $data['title'] . '</h3>';
			echo '</div>';
		} else {

			echo apply_filters( 'the_content', '<!-- wp:web-stories/embed ' . wp_json_encode( $data ) . ' /-->' );
		}
	}
}