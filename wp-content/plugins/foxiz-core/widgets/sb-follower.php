<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Follower' ) ) {

	class Foxiz_W_Follower extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-follower';

		function __construct() {

			$this->params = [
				'title'            => 'Stay Connected',
				'style'            => '',
				'facebook_page'    => '',
				'facebook_count'   => '',
				'twitter_user'     => '',
				'twitter_count'    => '',
				'pinterest_user'   => '',
				'pinterest_count'  => '',
				'instagram_user'   => '',
				'instagram_count'  => '',
				'youtube_link'     => '',
				'youtube_count'    => '',
				'tiktok_link'      => '',
				'tiktok_count'     => '',
				'telegram_link'    => '',
				'telegram_count'   => '',
				'whatsapp_link'    => '',
				'whatsapp_count'   => '',
				'gnews_link'       => '',
				'gnews_count'      => '',
				'linkedin_link'    => '',
				'linkedin_count'   => '',
				'medium_link'      => '',
				'medium_count'     => '',
				'flipboard_link'   => '',
				'flipboard_count'  => '',
				'twitch_link'      => '',
				'twitch_count'     => '',
				'steam_link'       => '',
				'steam_count'      => '',
				'tumblr_link'      => '',
				'tumblr_count'     => '',
				'discord_link'     => '',
				'discord_count'    => '',
				'paypal_link'      => '',
				'paypal_count'     => '',
				'patreon_link'     => '',
				'patreon_count'    => '',
				'soundcloud_user'  => '',
				'soundcloud_count' => '',
				'vimeo_user'       => '',
				'vimeo_count'      => '',
				'dribbble_user'    => '',
				'dribbble_count'   => '',
				'snapchat_link'    => '',
				'snapchat_count'   => '',
				'quora_link'       => '',
				'quora_count'      => '',

				'spotify_link'  => '',
				'spotify_count' => '',

				'truth_link'     => '',
				'truth_count'    => '',
				'threads_link'   => '',
				'threads_count'  => '',
				'rss_link'       => '',
				'rss_count'      => '',
				'font_size'      => '',
				'columns'        => '',
				'tablet_columns' => '',
				'mobile_columns' => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Social Counter', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display your media socials with total of followers in the sidebar.', 'foxiz-core' ),
			] );
		}

		function update( $new_instance, $old_instance ) {

			if ( current_user_can( 'unfiltered_html' ) ) {
				return wp_parse_args( (array) $new_instance, $this->params );
			} else {
				$instance = [];
				foreach ( $new_instance as $id => $value ) {
					$instance[ $id ] = sanitize_text_field( $value );
				}

				return wp_parse_args( $instance, $this->params );
			}
		}

		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'title' => esc_html__( 'Title', 'foxiz-core' ),
				'value' => $instance['title'],
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'style' ),
				'name'    => $this->get_field_name( 'style' ),
				'title'   => esc_html__( 'Style', 'foxiz-core' ),
				'options' => [
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
				'value'   => $instance['style'],
			] );

			echo '<div class="rb-form-2cols">';

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_facebook' ),
				'name'  => $this->get_field_name( 'head_facebook' ),
				'title' => esc_html__( 'Facebook', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'facebook_page' ),
				'name'  => $this->get_field_name( 'facebook_page' ),
				'title' => esc_html__( 'FanPage Name', 'foxiz-core' ),
				'value' => $instance['facebook_page'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'facebook_count' ),
				'name'  => $this->get_field_name( 'facebook_count' ),
				'title' => esc_html__( 'Likes Value', 'foxiz-core' ),
				'value' => $instance['facebook_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_twitter' ),
				'name'  => $this->get_field_name( 'head_twitter' ),
				'title' => esc_html__( 'Twitter', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'twitter_user' ),
				'name'  => $this->get_field_name( 'twitter_user' ),
				'title' => esc_html__( 'Twitter Name', 'foxiz-core' ),
				'value' => $instance['twitter_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'twitter_count' ),
				'name'  => $this->get_field_name( 'twitter_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['twitter_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_pinterest' ),
				'name'  => $this->get_field_name( 'head_pinterest' ),
				'title' => esc_html__( 'Pinterest', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'pinterest_user' ),
				'name'  => $this->get_field_name( 'pinterest_user' ),
				'title' => esc_html__( 'Pinterest Name', 'foxiz-core' ),
				'value' => $instance['pinterest_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'pinterest_count' ),
				'name'  => $this->get_field_name( 'pinterest_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['pinterest_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_instagram' ),
				'name'  => $this->get_field_name( 'head_instagram' ),
				'title' => esc_html__( 'Instagram', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'instagram_user' ),
				'name'  => $this->get_field_name( 'instagram_user' ),
				'title' => esc_html__( 'Instagram Name', 'foxiz-core' ),
				'value' => $instance['instagram_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'instagram_count' ),
				'name'  => $this->get_field_name( 'instagram_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['instagram_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_youtube' ),
				'name'  => $this->get_field_name( 'head_youtube' ),
				'title' => esc_html__( 'Youtube', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'youtube_link' ),
				'name'        => $this->get_field_name( 'youtube_link' ),
				'title'       => esc_html__( 'Youtube Channel or User URL', 'foxiz-core' ),
				'placeholder' => 'https://www.youtube.com/channel/...',
				'value'       => $instance['youtube_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'youtube_count' ),
				'name'  => $this->get_field_name( 'youtube_count' ),
				'title' => esc_html__( 'Subscribers Value', 'foxiz-core' ),
				'value' => $instance['youtube_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_tiktok' ),
				'name'  => $this->get_field_name( 'head_tiktok' ),
				'title' => esc_html__( 'Tiktok', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'tiktok_link' ),
				'name'        => $this->get_field_name( 'tiktok_link' ),
				'title'       => esc_html__( 'Tiktok Page URL', 'foxiz-core' ),
				'placeholder' => 'https://tiktok.com/@...',
				'value'       => $instance['tiktok_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'tiktok_count' ),
				'name'  => $this->get_field_name( 'tiktok_count' ),
				'title' => esc_html__( 'Members Value', 'foxiz-core' ),
				'value' => $instance['tiktok_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_telegram' ),
				'name'  => $this->get_field_name( 'head_telegram' ),
				'title' => esc_html__( 'Telegram', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'telegram_link' ),
				'name'        => $this->get_field_name( 'telegram_link' ),
				'title'       => esc_html__( 'Channel or Invite URL', 'foxiz-core' ),
				'placeholder' => 'https://t.me/...',
				'value'       => $instance['telegram_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'telegram_count' ),
				'name'  => $this->get_field_name( 'telegram_count' ),
				'title' => esc_html__( 'Members Value', 'foxiz-core' ),
				'value' => $instance['telegram_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_whatsapp' ),
				'name'  => $this->get_field_name( 'head_whatsapp' ),
				'title' => esc_html__( 'WhatsApp', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'whatsapp_link' ),
				'name'        => $this->get_field_name( 'whatsapp_link' ),
				'title'       => esc_html__( 'Channel or Invite URL', 'foxiz-core' ),
				'placeholder' => 'https://chat.whatsapp.com/invite/...',
				'value'       => $instance['whatsapp_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'whatsapp_count' ),
				'name'  => $this->get_field_name( 'whatsapp_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['whatsapp_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_gnews' ),
				'name'  => $this->get_field_name( 'head_gnews' ),
				'title' => esc_html__( 'Youtube', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'gnews_link' ),
				'name'        => $this->get_field_name( 'gnews_link' ),
				'title'       => esc_html__( 'Google News URL', 'foxiz-core' ),
				'placeholder' => 'https://news.google.com/publications/...',
				'value'       => $instance['gnews_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'gnews_count' ),
				'name'  => $this->get_field_name( 'gnews_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['gnews_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_linkedin' ),
				'name'  => $this->get_field_name( 'head_linkedin' ),
				'title' => esc_html__( 'LinkedIn', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'linkedin_link' ),
				'name'        => $this->get_field_name( 'linkedin_link' ),
				'title'       => esc_html__( 'LinkedIn URL', 'foxiz-core' ),
				'placeholder' => 'https://www.linkedin.com/...',
				'value'       => $instance['linkedin_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'linkedin_count' ),
				'name'  => $this->get_field_name( 'linkedin_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['linkedin_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_medium' ),
				'name'  => $this->get_field_name( 'head_medium' ),
				'title' => esc_html__( 'Medium', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'medium_link' ),
				'name'        => $this->get_field_name( 'medium_link' ),
				'title'       => esc_html__( 'Medium URL', 'foxiz-core' ),
				'placeholder' => 'https://www.medium.com/...',
				'value'       => $instance['medium_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'medium_count' ),
				'name'  => $this->get_field_name( 'medium_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['medium_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_flipboard' ),
				'name'  => $this->get_field_name( 'head_flipboard' ),
				'title' => esc_html__( 'Flipboard', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'flipboard_link' ),
				'name'        => $this->get_field_name( 'flipboard_link' ),
				'title'       => esc_html__( 'Flipboard URL', 'foxiz-core' ),
				'placeholder' => 'https://www.flipboard.com/...',
				'value'       => $instance['flipboard_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'flipboard_count' ),
				'name'  => $this->get_field_name( 'flipboard_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['flipboard_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_twitch' ),
				'name'  => $this->get_field_name( 'head_twitch' ),
				'title' => esc_html__( 'Twitch', 'foxiz-core' ),
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'twitch_link' ),
				'name'        => $this->get_field_name( 'twitch_link' ),
				'title'       => esc_html__( 'Twitch URL', 'foxiz-core' ),
				'placeholder' => 'https://www.twitch.tv/...',
				'value'       => $instance['twitch_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'twitch_count' ),
				'name'  => $this->get_field_name( 'twitch_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['twitch_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_steam' ),
				'name'  => $this->get_field_name( 'head_steam' ),
				'title' => esc_html__( 'Steam', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'steam_link' ),
				'name'        => $this->get_field_name( 'steam_link' ),
				'title'       => esc_html__( 'Steam URL', 'foxiz-core' ),
				'placeholder' => 'https://www.steamcommunity.com/groups/...',
				'value'       => $instance['steam_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'steam_count' ),
				'name'  => $this->get_field_name( 'steam_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['steam_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_tumblr' ),
				'name'  => $this->get_field_name( 'head_tumblr' ),
				'title' => esc_html__( 'Tumblr', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'tumblr_link' ),
				'name'        => $this->get_field_name( 'tumblr_link' ),
				'title'       => esc_html__( 'Tumblr URL', 'foxiz-core' ),
				'placeholder' => '',
				'value'       => $instance['tumblr_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'tumblr_count' ),
				'name'  => $this->get_field_name( 'tumblr_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['tumblr_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_discord' ),
				'name'  => $this->get_field_name( 'head_discord' ),
				'title' => esc_html__( 'Discord', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'discord_link' ),
				'name'        => $this->get_field_name( 'discord_link' ),
				'title'       => esc_html__( 'Tumblr URL', 'foxiz-core' ),
				'placeholder' => 'https://discord.com/servers/...',
				'value'       => $instance['discord_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'discord_count' ),
				'name'  => $this->get_field_name( 'discord_count' ),
				'title' => esc_html__( 'Members Value', 'foxiz-core' ),
				'value' => $instance['discord_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_paypal' ),
				'name'  => $this->get_field_name( 'head_paypal' ),
				'title' => esc_html__( 'PayPal', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'paypal_link' ),
				'name'        => $this->get_field_name( 'paypal_link' ),
				'title'       => esc_html__( 'PayPal URL', 'foxiz-core' ),
				'placeholder' => 'https://paypal.me/...',
				'value'       => $instance['paypal_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'paypal_count' ),
				'name'  => $this->get_field_name( 'paypal_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['paypal_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_patreon' ),
				'name'  => $this->get_field_name( 'head_patreon' ),
				'title' => esc_html__( 'Patreon', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'patreon_link' ),
				'name'        => $this->get_field_name( 'patreon_link' ),
				'title'       => esc_html__( 'Patreon URL', 'foxiz-core' ),
				'placeholder' => 'https://patreon.me/...',
				'value'       => $instance['patreon_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'patreon_count' ),
				'name'  => $this->get_field_name( 'patreon_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['patreon_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_soundcloud' ),
				'name'  => $this->get_field_name( 'head_soundcloud' ),
				'title' => esc_html__( 'SoundCloud', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'soundcloud_user' ),
				'name'  => $this->get_field_name( 'soundcloud_user' ),
				'title' => esc_html__( 'Soundcloud User Name', 'foxiz-core' ),
				'value' => $instance['soundcloud_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'soundcloud_count' ),
				'name'  => $this->get_field_name( 'soundcloud_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['soundcloud_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_vimeo' ),
				'name'  => $this->get_field_name( 'head_vimeo' ),
				'title' => esc_html__( 'Vimeo', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'vimeo_user' ),
				'name'  => $this->get_field_name( 'vimeo_user' ),
				'title' => esc_html__( 'Vimeo User Name', 'foxiz-core' ),
				'value' => $instance['vimeo_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'vimeo_count' ),
				'name'  => $this->get_field_name( 'vimeo_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['vimeo_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_dribbble' ),
				'name'  => $this->get_field_name( 'head_dribbble' ),
				'title' => esc_html__( 'Dribbble', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'dribbble_user' ),
				'name'  => $this->get_field_name( 'dribbble_user' ),
				'title' => esc_html__( 'Dribbble User Name', 'foxiz-core' ),
				'value' => $instance['dribbble_user'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'dribbble_count' ),
				'name'  => $this->get_field_name( 'dribbble_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['dribbble_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_snapchat' ),
				'name'  => $this->get_field_name( 'head_snapchat' ),
				'title' => esc_html__( 'Snapchat', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'snapchat_link' ),
				'name'        => $this->get_field_name( 'snapchat_link' ),
				'title'       => esc_html__( 'Snapchat URL', 'foxiz-core' ),
				'placeholder' => 'https://snapchat.com/...',
				'value'       => $instance['snapchat_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'snapchat_count' ),
				'name'  => $this->get_field_name( 'snapchat_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['snapchat_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_quora' ),
				'name'  => $this->get_field_name( 'head_quora' ),
				'title' => esc_html__( 'Quora', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'quora_link' ),
				'name'        => $this->get_field_name( 'quora_link' ),
				'title'       => esc_html__( 'Quora Social URL', 'foxiz-core' ),
				'placeholder' => 'https://quora.com/...',
				'value'       => $instance['quora_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'quora_count' ),
				'name'  => $this->get_field_name( 'quora_count' ),
				'title' => esc_html__( 'Memembers Value', 'foxiz-core' ),
				'value' => $instance['quora_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_spotify' ),
				'name'  => $this->get_field_name( 'head_spotify' ),
				'title' => esc_html__( 'Spotify', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'spotify_link' ),
				'name'        => $this->get_field_name( 'spotify_link' ),
				'title'       => esc_html__( 'Spotify URL', 'foxiz-core' ),
				'placeholder' => 'https://open.spotify.com/artist/...',
				'value'       => $instance['spotify_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'spotify_count' ),
				'name'  => $this->get_field_name( 'spotify_count' ),
				'title' => esc_html__( 'Listeners Value', 'foxiz-core' ),
				'value' => $instance['spotify_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_truth' ),
				'name'  => $this->get_field_name( 'head_truth' ),
				'title' => esc_html__( 'Truth Social', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'truth_link' ),
				'name'        => $this->get_field_name( 'truth_link' ),
				'title'       => esc_html__( 'Truth Social URL', 'foxiz-core' ),
				'placeholder' => 'https://truthsocial.com/...',
				'value'       => $instance['truth_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'truth_count' ),
				'name'  => $this->get_field_name( 'truth_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['truth_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_threads' ),
				'name'  => $this->get_field_name( 'head_threads' ),
				'title' => esc_html__( 'Treads', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'threads_link' ),
				'name'        => $this->get_field_name( 'threads_link' ),
				'title'       => esc_html__( 'Threads URL', 'foxiz-core' ),
				'placeholder' => 'https://www.threads.net/...',
				'value'       => $instance['threads_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'threads_count' ),
				'name'  => $this->get_field_name( 'threads_count' ),
				'title' => esc_html__( 'Followers Value', 'foxiz-core' ),
				'value' => $instance['threads_count'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_rss' ),
				'name'  => $this->get_field_name( 'head_rss' ),
				'title' => esc_html__( 'Treads', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'rss_link' ),
				'name'  => $this->get_field_name( 'rss_link' ),
				'title' => esc_html__( 'RSS Feed URL', 'foxiz-core' ),
				'value' => $instance['rss_link'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'rss_count' ),
				'name'  => $this->get_field_name( 'rss_count' ),
				'title' => esc_html__( 'Readers Value', 'foxiz-core' ),
				'value' => $instance['rss_count'],
			] );

			echo '</div>';

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'style_settings' ),
				'name'  => $this->get_field_name( 'style_settings' ),
				'title' => esc_html__( 'Style', 'foxiz-core' ),
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'font_size' ),
				'name'        => $this->get_field_name( 'font_size' ),
				'title'       => esc_html__( 'Icon Size', 'foxiz-core' ),
				'description' => esc_html__( 'Input icon size value in px', 'foxiz-core' ),
				'value'       => $instance['font_size'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'columns' ),
				'name'        => $this->get_field_name( 'columns' ),
				'title'       => esc_html__( 'Columns', 'foxiz-core' ),
				'description' => esc_html__( 'Input the number of columns for this widget on desktop.', 'foxiz-core' ),
				'value'       => $instance['columns'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'tablet_columns' ),
				'name'        => $this->get_field_name( 'tablet_columns' ),
				'title'       => esc_html__( 'Tablet Columns', 'foxiz-core' ),
				'description' => esc_html__( 'Input the number of columns for this widget on tablet.', 'foxiz-core' ),
				'value'       => $instance['tablet_columns'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'mobile_columns' ),
				'name'        => $this->get_field_name( 'mobile_columns' ),
				'title'       => esc_html__( 'Mobile Columns', 'foxiz-core' ),
				'description' => esc_html__( 'Input the number of columns for this widget on mobile.', 'foxiz-core' ),
				'value'       => $instance['mobile_columns'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];

			if ( ! empty( $instance['columns'] ) || ! empty( $instance['tablet_columns'] ) || ! empty( $instance['mobile_columns'] ) || ! empty( $instance['font_size'] ) ) {

				echo '<style> [id="' . $args['widget_id'] . '"] {';
				if ( ! empty( $instance['font_size'] ) ) {
					echo '--s-icon-size : ' . absint( $instance['font_size'] ) . 'px;';
				}
				if ( ! empty( $instance['columns'] ) ) {
					echo '--s-columns: ' . absint( $instance['columns'] ) . ';';
				}
				if ( ! empty( $instance['tablet_columns'] ) ) {
					echo '--s-tcolumns: ' . absint( $instance['tablet_columns'] ) . ';';
				}
				if ( ! empty( $instance['mobile_columns'] ) ) {
					echo '--s-mcolumns: ' . absint( $instance['mobile_columns'] ) . ';';
				}
				echo '} </style>';
			}

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
			}
			echo rb_social_follower( $instance, $instance['style'] );
			echo $args['after_widget'];
		}
	}
}