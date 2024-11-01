<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_playlist;

/**
 * Class Youtube_Playlist
 *
 * @package foxizElementor\Widgets
 */
class Playlist extends Widget_Base {

	public function get_name() {

		return 'foxiz-playlist';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Youtube Videos', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-video-playlist';
	}

	public function get_keywords() {

		return [ 'youtube', 'playlist', 'clips' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'Videos Settings', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'playlist_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: Due to play/stop API control button so this block only supports Youtube videos.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$playlist = new Repeater();
		$playlist->add_control(
			'url',
			[
				'label'       => esc_html__( 'Youtube Video URL', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'placeholder' => esc_html__( 'Input video url...', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$playlist->add_control(
			'title',
			[
				'label'       => esc_html__( 'Video Title', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'placeholder' => esc_html__( 'Input video title...', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$playlist->add_control(
			'meta',
			[
				'label'   => esc_html__( 'Meta/Channel Name', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => '',
			]
		);
		$playlist->add_control(
			'image',
			[
				'label'   => esc_html__( 'Custom Thumbnail (Optional)', 'foxiz-core' ),
				'type'    => Controls_Manager::MEDIA,
				'ai'      => [ 'active' => false ],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'videos',
			[
				'label'       => esc_html__( 'Add Videos', 'foxiz-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $playlist->get_controls(),
				'default'     => [
					[
						'url'   => '',
						'title' => esc_html__( 'Video Title #1', 'foxiz-core' ),
						'image' => '',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'playlist_height', [
				'label'       => esc_html__( 'Playlist Max Height', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input a max height for the playlist in tablet and mobile.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--playlist-height: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'title_tag_size', [
				'label'       => esc_html__( 'Playlist Title Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::title_size_description(),
				'selectors'   => [ '{{WRAPPER}} .plist-item-title' => 'font-size: {{VALUE}}px;' ],
			]
		);

		$this->add_responsive_control(
			'play_title_size', [
				'label'       => esc_html__( 'Playing Title Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom font size values (in pixels) for the playing title for displaying in this block.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}} .play-title' => 'font-size: {{VALUE}}px;' ],
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
				'label'    => esc_html__( 'Playlist Title', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} span.plist-item-title',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Playing Title', 'foxiz-core' ),
				'name'     => 'playing_title_font',
				'selector' => '{{WRAPPER}} .play-title',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( function_exists( 'foxiz_get_playlist' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();
			echo foxiz_get_playlist( $settings );
		}
	}
}