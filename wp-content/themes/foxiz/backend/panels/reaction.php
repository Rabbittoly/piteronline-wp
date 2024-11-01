<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_reaction' ) ) {
	function foxiz_register_options_reaction() {

		return [
			'title'  => esc_html__( 'Like & Reaction', 'foxiz' ),
			'id'     => 'foxiz_config_section_reaction',
			'desc'   => esc_html__( 'Customize user voting and post reactions.', 'foxiz' ),
			'icon'   => 'el el-smiley',
			'fields' => [
				[
					'id'    => 'voting_info',
					'type'  => 'info',
					'style' => 'info',
					'desc'  => esc_html__( 'You can enable the like/dislike meta in the Standard Block Design panels or Elementor block.', 'foxiz' ),
				],
				[
					'id'     => 'section_start_reaction_global',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'General', 'foxiz' ),
					'notice' => [
						esc_html__( 'These settings below will apply to the post like/dislike meta and post reactions.', 'foxiz' ),
						esc_html__( 'If a user likes a post after their previous like has expired and the browser cookies have been cleared, it will count as a new like.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'reaction_guest_expired',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Guest Expiration', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a value for the number of days after which to clear up database data for guest users.', 'foxiz' ),
					'default'  => 14,
				],
				[
					'id'       => 'reaction_logged_expired',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Logged Expiration', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a value for the number of days after which to clear up database data for logged users.', 'foxiz' ),
					'default'  => 14,
				],
				[
					'id'       => 'reaction_ip',
					'type'     => 'switch',
					'title'    => esc_html__( 'tracking by IP', 'foxiz' ),
					'subtitle' => esc_html__( 'By default, the theme will track voting using device cookies. If you want to limit likes per IP, you can enable tracking by IP address.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'          => 'js_count',
					'type'        => 'switch',
					'validate'    => 'numeric',
					'title'       => esc_html__( 'Optimize Counter for Caches', 'foxiz' ),
					'subtitle'    => esc_html__( 'By default, Total likes, reactions will not increase immediately after user interactions if you use caching. Enabling this feature will make the total count increase/decrease based on the user\'s cookies, accurately reflecting their actions.', 'foxiz' ),
					'description' => esc_html__( 'Please note: You don\'t need to enable this if you do not use a caching plugin.', 'foxiz' ),
					'default'     => false,
				],
				[
					'id'     => 'section_end_reaction_global',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false,
				],
				[
					'id'     => 'section_start_single_reaction',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Post Reactions', 'foxiz' ),
					'notice' => [
						esc_html__( 'The reaction will appear at the end of single post content.', 'foxiz' ),
					],
					'indent' => true,
				],
				[
					'id'       => 'single_post_reaction',
					'type'     => 'switch',
					'title'    => esc_html__( 'Single Post Reactions', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the reaction section.', 'foxiz' ),
					'default'  => false,
				],
				[
					'id'       => 'single_post_reaction_title',
					'type'     => 'text',
					'title'    => esc_html__( 'Reaction Heading', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a heading for the reaction section.', 'foxiz' ),
					'default'  => '',
				],
				[
					'id'       => 'reaction_items',
					'title'    => esc_html__( 'Reaction Items', 'foxiz' ),
					'subtitle' => esc_html__( 'Choose and sort order reaction items you would like to show.', 'foxiz' ),
					'type'     => 'sorter',
					'options'  => [
						'enabled'  => [
							'love'   => esc_html__( 'Love', 'foxiz' ),
							'sad'    => esc_html__( 'Sad', 'foxiz' ),
							'happy'  => esc_html__( 'Happy', 'foxiz' ),
							'sleepy' => esc_html__( 'Sleepy', 'foxiz' ),
							'angry'  => esc_html__( 'Angry', 'foxiz' ),
							'dead'   => esc_html__( 'Dead', 'foxiz' ),
							'wink'   => esc_html__( 'Wink', 'foxiz' ),
						],
						'disabled' => [
							'cry'       => esc_html__( 'Cry', 'foxiz' ),
							'embarrass' => esc_html__( 'Embarrass', 'foxiz' ),
							'joy'       => esc_html__( 'Joy', 'foxiz' ),
							'shy'       => esc_html__( 'Shy', 'foxiz' ),
							'surprise'  => esc_html__( 'Surprise', 'foxiz' ),
						],
					],
					[
						'id'     => 'section_end_post_reaction',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
				],
			],
		];
	}
}



