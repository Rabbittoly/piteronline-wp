<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function rb_social_follower;

/**
 * Class Social_Follower
 *
 * @package foxizElementor\Widgets
 */
class Social_Follower extends Widget_Base {

	public function get_name() {

		return 'foxiz-social-follower';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Social Follower', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-social-icons';
	}

	public function get_keywords() {

		return [ 'template', 'builder', 'fan', 'follow', 'counter' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_fb', [
				'label' => esc_html__( 'Facebook', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'facebook_page',
			[
				'label' => esc_html__( 'FanPage Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'facebook_count',
			[
				'label' => esc_html__( 'Facebook Likes Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_twitter', [
				'label' => esc_html__( 'Twitter', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'twitter_user',
			[
				'label' => esc_html__( 'Twitter Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'twitter_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_pinterest', [
				'label' => esc_html__( 'Pinterest', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'pinterest_user',
			[
				'label' => esc_html__( 'Pinterest Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'pinterest_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_instagram', [
				'label' => esc_html__( 'Instagram', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'instagram_user',
			[
				'label' => esc_html__( 'Instagram Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->add_control(
			'instagram_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_youtube', [
				'label' => esc_html__( 'Youtube', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'youtube_link',
			[
				'label'       => esc_html__( 'Youtube Channel or User URL', 'foxiz-core' ),
				'placeholder' => 'https://www.youtube.com/channel/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);

		$this->add_control(
			'youtube_count',
			[
				'label' => esc_html__( 'Youtube Subscribers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_tiktok', [
				'label' => esc_html__( 'Tiktok', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'tiktok_link',
			[
				'label'       => esc_html__( 'Tiktok URL', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => 'https://tiktok.com/@...',
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'tiktok_count',
			[
				'label' => esc_html__( 'Tiktok Members Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_telegram', [
				'label' => esc_html__( 'Telegram', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'telegram_link',
			[
				'label'       => esc_html__( 'Channel or Invite URL', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => 'https://t.me/...',
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);

		$this->add_control(
			'telegram_count',
			[
				'label' => esc_html__( 'Telegram Members Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_whatsapp', [
				'label' => esc_html__( 'WhatsApp', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'whatsapp_link',
			[
				'label'       => esc_html__( 'Channel or Invite URL', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => 'https://chat.whatsapp.com/invite...',
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'whatsapp_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_gnews', [
				'label' => esc_html__( 'Google News', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'gnews_link',
			[
				'label'       => esc_html__( 'Google News URL', 'foxiz-core' ),
				'placeholder' => 'https://news.google.com/publications/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'gnews_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_linkedin', [
				'label' => esc_html__( 'LinkedIn', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'linkedin_link',
			[
				'label'       => esc_html__( 'LinkedIn URL', 'foxiz-core' ),
				'placeholder' => 'https://www.linkedin.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'linkedin_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_medium', [
				'label' => esc_html__( 'Medium', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'medium_link',
			[
				'label'       => esc_html__( 'Medium URL', 'foxiz-core' ),
				'placeholder' => 'https://www.medium.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'medium_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_flipboard', [
				'label' => esc_html__( 'Flipboard', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'flipboard_link',
			[
				'label'       => esc_html__( 'Flipboard URL', 'foxiz-core' ),
				'placeholder' => 'https://www.flipboard.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'flipboard_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();


		$this->start_controls_section(
			'section_twitch', [
				'label' => esc_html__( 'Twitch', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'twitch_link',
			[
				'label'       => esc_html__( 'Twitch URL', 'foxiz-core' ),
				'placeholder' => 'https://www.twitch.tv/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'twitch_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_steam', [
				'label' => esc_html__( 'Steam', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'steam_link',
			[
				'label'       => esc_html__( 'Steam URL', 'foxiz-core' ),
				'placeholder' => 'https://www.steamcommunity.com/groups/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'steam_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_tumblr', [
				'label' => esc_html__( 'Tumblr', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'tumblr_link',
			[
				'label' => esc_html__( 'Tumblr URL', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->add_control(
			'tumblr_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_discord', [
				'label' => esc_html__( 'Discord', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'discord_link',
			[
				'label' => esc_html__( 'Discord Server URL', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->add_control(
			'discord_count',
			[
				'label' => esc_html__( 'Members Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_paypal', [
				'label' => esc_html__( 'PayPal', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'paypal_link',
			[
				'label'       => esc_html__( 'PayPal URL', 'foxiz-core' ),
				'placeholder' => 'https://paypal.me/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'paypal_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_patreon', [
				'label' => esc_html__( 'Patreon', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'patreon_link',
			[
				'label'       => esc_html__( 'Patreon URL', 'foxiz-core' ),
				'placeholder' => 'https://www.patreon.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'patreon_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_soundcloud', [
				'label' => esc_html__( 'Soundcloud', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'soundcloud_user',
			[
				'label' => esc_html__( 'User Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'soundcloud_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_vimeo', [
				'label' => esc_html__( 'Vimeo', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'vimeo_user',
			[
				'label' => esc_html__( 'Vimeo User Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'vimeo_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_dribbble', [
				'label' => esc_html__( 'Dribbble', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'dribbble_user',
			[
				'label' => esc_html__( 'Dribbble User Name', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);

		$this->add_control(
			'dribbble_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_snapchat', [
				'label' => esc_html__( 'Snapchat', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'snapchat_link',
			[
				'label'       => esc_html__( 'Snapchat URL', 'foxiz-core' ),
				'placeholder' => 'https://www.snapchat.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'snapchat_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_quora', [
				'label' => esc_html__( 'Quora', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'quora_link',
			[
				'label'       => esc_html__( 'Quora Social URL', 'foxiz-core' ),
				'placeholder' => 'https://quora.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'quora_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_spotify', [
				'label' => esc_html__( 'Spotify', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'spotify_link',
			[
				'label'       => esc_html__( 'Spotify URL', 'foxiz-core' ),
				'placeholder' => 'https://open.spotify.com/artist/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'spotify_count',
			[
				'label' => esc_html__( 'Listeners Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_truth', [
				'label' => esc_html__( 'Truth Social', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'truth_link',
			[
				'label'       => esc_html__( 'Truth Social URL', 'foxiz-core' ),
				'placeholder' => 'https://truthsocial.com/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'truth_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_threads', [
				'label' => esc_html__( 'Threads', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'threads_link',
			[
				'label'       => esc_html__( 'Threads URL', 'foxiz-core' ),
				'placeholder' => 'https://www.threads.net/...',
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 1,
			]
		);
		$this->add_control(
			'threads_count',
			[
				'label' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_rss', [
				'label' => esc_html__( 'RSS', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'rss_link',
			[
				'label' => esc_html__( 'RSS Feed URL', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->add_control(
			'rss_count',
			[
				'label' => esc_html__( 'Readers Value', 'foxiz-core' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => [ 'active' => false ],
				'rows'  => 1,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'style_section', [
				'label' => esc_html__( 'Widget Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'style',
			[
				'label'       => esc_html__( 'Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a style for your social followers.', 'foxiz-core' ),
				'options'     => [
					'1'  => esc_html__( 'Style 1', 'foxiz-core' ),
					'2'  => esc_html__( 'Style 2', 'foxiz-core' ),
					'3'  => esc_html__( 'Style 3', 'foxiz-core' ),
					'4'  => esc_html__( 'Style 4', 'foxiz-core' ),
					'5'  => esc_html__( 'Style 5', 'foxiz-core' ),
					'6'  => esc_html__( 'Style 6', 'foxiz-core' ),
					'7'  => esc_html__( 'Style 7', 'foxiz-core' ),
					'8'  => esc_html__( 'Style 8', 'foxiz-core' ),
					'9'  => esc_html__( 'Style 9', 'foxiz-core' ),
					'10' => esc_html__( 'Style 10', 'foxiz-core' ),
					'11' => esc_html__( 'Style 11', 'foxiz-core' ),
					'12' => esc_html__( 'Style 12', 'foxiz-core' ),
					'13' => esc_html__( 'Style 13', 'foxiz-core' ),
					'14' => esc_html__( 'Style 14', 'foxiz-core' ),
					'15' => esc_html__( 'Style 15', 'foxiz-core' ),
				],
				'default'     => '1',
			]
		);
		$this->add_control(
			'color_style', [
				'label'        => esc_html__( 'Color Style', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => esc_html__( 'Show icons in monochromatic black color or in colorful variations.', 'foxiz-core' ),
				'options'      => [
					'colorful' => esc_html__( 'Colorful', 'foxiz-core' ),
					'mono'     => esc_html__( 'Monochromatic', 'foxiz-core' ),
				],
				'default'      => 'colorful',
				'prefix_class' => 'yes-',
			]
		);
		$this->add_control(
			'mono_dark_accent',
			[
				'label'       => esc_html__( 'Color', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a color for the Monochromatic mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => [
					'color_style' => 'mono',
				],
				'selectors'   => [ '{{WRAPPER}}' => '--dark-accent: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_mono_dark_accent',
			[
				'label'       => esc_html__( 'Dark Mode - Color', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a color for the Monochromatic mode in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'condition'   => [
					'color_style' => 'mono',
				],
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--dark-accent: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_mono_color',
			[
				'label'       => esc_html__( 'Dark Mode - Icon Color', 'foxiz-core' ),
				'description' => esc_html__( 'Select an icon color for Monochromatic mode in dark mode. This setting should only be used for styles with backgrounds.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => [
					'color_style' => 'mono',
				],
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}' => '--awhite: {{VALUE}};' ],
			]
		);
		$this->add_responsive_control(
			'widget_font_size', [
				'label'       => esc_html__( 'Custom Font Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input custom font size for this widget.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--s-icon-size : {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'font_section', [
				'label' => esc_html__( 'Typography', 'foxiz-core' ),
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
				'label'    => esc_html__( 'Total Fans Text Font', 'foxiz-core' ),
				'name'     => 'fan_font',
				'selector' => '{{WRAPPER}} .follower-el .fntotal, {{WRAPPER}} .follower-el .fnlabel',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Description Font', 'foxiz-core' ),
				'name'     => 'description_font',
				'selector' => '{{WRAPPER}} .follower-el .text-count',
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
			'block_columns', [
				'label' => esc_html__( 'Columns', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'custom_columns',
			[
				'label'        => esc_html__( 'Custom Columns', 'foxiz-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Input number of columns for the socials.', 'foxiz-core' ),
				'default'     => '',
				'condition'   => [
					'custom_columns' => 'yes',
				],
				'selectors'   => [ '{{WRAPPER}}' => '--s-columns : {{VALUE}}' ],
			]
		);
		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => esc_html__( 'Column Gap', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'condition' => [
					'custom_columns' => 'yes',
				],
				'selectors' => [ '{{WRAPPER}} ' => '--column-gap: {{VALUE}}px' ],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		$settings         = $this->get_settings();
		$settings['uuid'] = 'uid_' . $this->get_id();
		$style            = $settings['style'];
		echo rb_social_follower( $settings, $style );
	}
}