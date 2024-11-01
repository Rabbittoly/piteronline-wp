<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_podcast_single_metaboxes' ) ) {
	function foxiz_podcast_single_metaboxes() {

		$configs = [
			'id'         => 'foxiz_post_options',
			'title'      => esc_html__( 'Episode Settings', 'foxiz' ),
			'context'    => 'normal',
			'post_types' => [ 'podcast' ],
			'tabs'       => [
				[
					'id'     => 'section-file',
					'title'  => esc_html__( 'Episode Data', 'foxiz' ),
					'icon'   => 'dashicons-media-audio',
					'desc'   => esc_html__( 'Upload audio episode files as MP3 or M4A, or paste the file URL.', 'foxiz' ),
					'fields' => [
						[
							'id'   => 'audio_hosted',
							'name' => esc_html__( 'Self-Hosted File', 'foxiz' ),
							'desc' => esc_html__( 'Upload your audio file, support: mp3, ogg, wma, m4a, wav files.', 'foxiz' ),
							'type' => 'file',
						],
						[
							'id'   => 'audio_url',
							'name' => esc_html__( 'Audio URL', 'foxiz' ),
							'desc' => esc_html__( 'Input your audio URL, support: SoundCloud, MixCloud.', 'foxiz' ),
							'type' => 'text',
						],
						[
							'id'   => 'audio_embed',
							'name' => esc_html__( 'Iframe Embed Code', 'foxiz' ),
							'desc' => esc_html__( 'Input iframe embed code if WordPress cannot support your audio URL.', 'foxiz' ),
							'type' => 'textarea',
						],
						[
							'id'      => 'audio_autoplay',
							'name'    => esc_html__( 'Autoplay Audio', 'foxiz' ),
							'desc'    => esc_html__( 'Enable or disable autoplay audio for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'1'       => esc_html__( 'Enable', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
					],
				],
				[
					'id'     => 'section-tagline',
					'title'  => esc_html__( 'Tagline & Highlights', 'foxiz' ),
					'icon'   => 'dashicons-edit-large',
					'fields' => [
						[
							'id'      => 'tagline',
							'name'    => esc_html__( 'Tagline', 'foxiz' ),
							'desc'    => esc_html__( 'Input a tagline for this episode.', 'foxiz' ),
							'info'    => esc_html__( 'It will display under the episode title.', 'foxiz' ),
							'single'  => true,
							'rows'    => 3,
							'type'    => 'textarea',
							'default' => '',
						],
						[
							'id'      => 'tagline_tag',
							'name'    => esc_html__( 'Tagline HTML Tag', 'foxiz' ),
							'desc'    => esc_html__( 'Select a HTML tag for this tagline.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'0'    => esc_html__( '- Default -', 'foxiz' ),
								'h2'   => esc_html__( 'H2', 'foxiz' ),
								'h3'   => esc_html__( 'H3', 'foxiz' ),
								'h4'   => esc_html__( 'H4', 'foxiz' ),
								'h5'   => esc_html__( 'H5', 'foxiz' ),
								'h6'   => esc_html__( 'H6', 'foxiz' ),
								'p'    => esc_html__( 'p', 'foxiz' ),
								'span' => esc_html__( 'span', 'foxiz' ),
							],
							'default' => '0',
						],
						[
							'id'     => 'highlights',
							'name'   => esc_html__( 'Podcast Highlights', 'foxiz' ),
							'desc'   => esc_html__( 'Show a highlights section at the top for the post content.', 'foxiz' ),
							'info'   => esc_html__( 'Navigate to "Theme Options > Single Post > Tagline & highlights" to edit the heading.', 'foxiz' ),
							'type'   => 'group',
							'button' => esc_html__( '+Add Highlight', 'foxiz' ),
							'fields' => [
								[
									'id'      => 'point',
									'name'    => esc_html__( 'Highlight Point', 'foxiz' ),
									'default' => '',
								],
							],
						],
					],
				],
				[
					'id'     => 'section-meta',
					'title'  => esc_html__( 'Episode Meta', 'foxiz' ),
					'icon'   => 'dashicons-database',
					'fields' => [
						[
							'id'          => 'post_index',
							'name'        => esc_html__( 'Episode Index', 'foxiz' ),
							'desc'        => esc_html__( 'The index of this episode file.', 'foxiz' ),
							'placeholder' => 'Episode 1',
							'type'        => 'text',
						],
						[
							'id'          => 'duration',
							'name'        => esc_html__( 'Duration', 'foxiz' ),
							'desc'        => esc_html__( 'Duration of this episode file. This setting will also apply to SEO markup data.', 'foxiz' ),
							'info'        => esc_html__( 'Ensure the date format is HH:MM:SS', 'foxiz' ),
							'placeholder' => '01:10:30',
							'type'        => 'text',
						],
						[
							'id'      => 'featured_caption',
							'name'    => esc_html__( 'Caption Text', 'foxiz' ),
							'desc'    => esc_html__( 'Input caption text for the featured image.', 'foxiz' ),
							'type'    => 'textarea',
							'default' => '',
						],
						[
							'id'      => 'featured_attribution',
							'name'    => esc_html__( 'Attribution', 'foxiz' ),
							'desc'    => esc_html__( 'Input an attribution for the featured image.', 'foxiz' ),
							'type'    => 'text',
							'default' => '',
						],
					],
				],
				[
					'id'     => 'section-socials',
					'title'  => esc_html__( 'Social Podcast Links', 'foxiz' ),
					'icon'   => 'dashicons-admin-links',
					'fields' => [
						[
							'id'          => 'listen_on_apple',
							'name'        => esc_html__( 'Listen on Apple Podcast', 'foxiz' ),
							'desc'        => esc_html__( 'Add an Apple podcast link for this episode.', 'foxiz' ),
							'placeholder' => 'https://podcasts.apple.com...',
							'type'        => 'textarea',
						],
						[
							'id'          => 'listen_on_spotify',
							'name'        => esc_html__( 'Listen on Spotify', 'foxiz' ),
							'desc'        => esc_html__( 'Add a Spotify link for this episode.', 'foxiz' ),
							'placeholder' => 'https://open.spotify.com/...',
							'type'        => 'textarea',
						],
						[
							'id'          => 'listen_on_soundcloud',
							'name'        => esc_html__( 'Listen on Soundcloud', 'foxiz' ),
							'desc'        => esc_html__( 'Add a Soundcloud link for this episode.', 'foxiz' ),
							'placeholder' => 'https://soundcloud.com/...',
							'type'        => 'textarea',
						],
						[
							'id'          => 'listen_on_google',
							'name'        => esc_html__( 'Listen on Google Podcast', 'foxiz' ),
							'desc'        => esc_html__( 'Add a google podcast link for this episode.', 'foxiz' ),
							'placeholder' => 'https://podcasts.google.com/...',
							'type'        => 'textarea',
						],
						[
							'id'     => 'listen_on',
							'name'   => esc_html__( 'More Social Podcasts', 'foxiz' ),
							'desc'   => esc_html__( 'Add this episode links on other social media podcasts.', 'foxiz' ),
							'type'   => 'group',
							'button' => '+ Add Link',
							'fields' => [
								[
									'name'        => esc_html__( 'Social Name', 'foxiz' ),
									'id'          => 'label',
									'placeholder' => 'Spotify',
									'default'     => '',
								],
								[
									'name'        => esc_html__( 'Icon Classname', 'foxiz' ),
									'placeholder' => 'rbi-spotify',
									'id'          => 'icon',
									'default'     => '',
								],
								[
									'name'        => esc_html__( 'Episode URL', 'foxiz' ),
									'placeholder' => 'https://open.spotify.com/episode...',
									'id'          => 'url',
									'default'     => '',
								],
							],
						],
					],
				],
				[
					'id'     => 'section-layout',
					'title'  => esc_html__( 'Layout', 'foxiz' ),
					'desc'   => esc_html__( 'The setting below will take priority over other settings in "Theme Options > Ruby Podcast > Episode".', 'foxiz' ),
					'icon'   => 'dashicons-menu',
					'fields' => [
						[
							'id'      => 'layout',
							'name'    => esc_html__( 'Episode Layout', 'foxiz' ),
							'desc'    => esc_html__( 'Select a layout for this episode.', 'foxiz' ),
							'info'    => esc_html__( 'This setting will override on the Theme Option setting.', 'foxiz' ),
							'type'    => 'image_select',
							'class'   => 'big',
							'options' => [
								'default' => [
									'img'   => foxiz_get_asset_image( 'default.png' ),
									'title' => esc_html__( '- Default -', 'foxiz' ),
								],
								'1'       => [
									'img'   => foxiz_get_asset_image( 'single-6.png' ),
									'title' => esc_html__( 'Layout 1', 'foxiz' ),
								],
								'2'       => [
									'img'   => foxiz_get_asset_image( 'single-7.png' ),
									'title' => esc_html__( 'Layout 2', 'foxiz' ),
								],
							],
							'default' => 'default',
						],
					],
				],
				[
					'id'     => 'section-sidebar',
					'title'  => esc_html__( 'Sidebar Area', 'foxiz' ),
					'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Ruby Podcast > Episode".', 'foxiz' ),
					'icon'   => 'dashicons-align-pull-right',
					'fields' => [
						[
							'id'      => 'sidebar_position',
							'name'    => esc_html__( 'Sidebar Position', 'foxiz' ),
							'desc'    => esc_html__( 'Select a position for this episode sidebar.', 'foxiz' ),
							'class'   => 'sidebar-select',
							'type'    => 'image_select',
							'options' => foxiz_config_sidebar_position( true, true ),
							'default' => 'default',
						],
						[
							'id'      => 'sidebar_name',
							'name'    => esc_html__( 'Assign a Sidebar', 'foxiz' ),
							'desc'    => esc_html__( 'Assign a custom sidebar for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => foxiz_config_sidebar_name(),
							'default' => 'default',
						],
					],
				],
				[
					'id'     => 'section-widget',
					'title'  => esc_html__( 'Widgets & Ads', 'foxiz' ),
					'desc'   => esc_html__( 'Manage ad sections and top/bottom content widgets for this episode.', 'foxiz' ),
					'icon'   => 'dashicons-editor-insertmore',
					'fields' => [
						[
							'id'      => 'disable_top_ad',
							'name'    => esc_html__( 'Top Site Advert', 'foxiz' ),
							'desc'    => esc_html__( 'Enable or disable the top ad site for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'disable_header_ad',
							'name'    => esc_html__( 'Header Advertising Widget Section', 'foxiz' ),
							'desc'    => esc_html__( 'Enable or disable all widgets in the header advertising widget section (Appearance > Widgets) for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'alert_bar',
							'name'    => esc_html__( 'Header Alert Bar', 'foxiz' ),
							'desc'    => esc_html__( 'Enable or disable the alert bar below the header.', 'foxiz' ),
							'info'    => esc_html__( 'This setting will apply only to pre-defined header layouts.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'1'       => esc_html__( 'Enable', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'entry_top',
							'name'    => esc_html__( 'Top Content -  Widgets Area', 'foxiz' ),
							'desc'    => esc_html__( 'Show widgets at the top of the post content.', 'foxiz' ),
							'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Top Area" to add your widgets.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'1'  => esc_html__( 'Enable', 'foxiz' ),
								'-1' => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => '1',
						],
						[
							'id'      => 'entry_bottom',
							'name'    => esc_html__( 'Bottom Content - Widgets Area', 'foxiz' ),
							'desc'    => esc_html__( 'Show widgets at the bottom of the post content.', 'foxiz' ),
							'info'    => esc_html__( 'Navigate to "Appearance > Widgets > Single Content - Bottom Area" to add your widgets.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'1'  => esc_html__( 'Enable', 'foxiz' ),
								'-1' => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => '1',
						],
						[
							'id'      => 'entry_ad_1',
							'name'    => esc_html__( 'Inline Ad 1', 'foxiz' ),
							'desc'    => esc_html__( 'Choose to disable inline content ad 1 for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'entry_ad_2',
							'name'    => esc_html__( 'Inline Ad 2', 'foxiz' ),
							'desc'    => esc_html__( 'Choose to disable inline content ad 2 for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'entry_ad_3',
							'name'    => esc_html__( 'Inline Ad 3', 'foxiz' ),
							'desc'    => esc_html__( 'Choose to disable inline content ad 3 for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'auto_ads',
							'name'    => esc_html__( 'Auto Ads', 'foxiz' ),
							'desc'    => esc_html__( 'Choose to disable auto Ads for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
					],
				],
				[
					'id'     => 'section-toc',
					'title'  => esc_html__( 'Table of Content', 'foxiz' ),
					'desc'   => esc_html__( 'The settings below will take priority over other settings in "Theme Options > Table of Content".', 'foxiz' ),
					'icon'   => 'dashicons-editor-ol',
					'fields' => [
						[
							'id'      => 'table_contents_post',
							'name'    => esc_html__( 'Table of Contents', 'foxiz' ),
							'desc'    => esc_html__( 'Enable or disable the table content for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'1'       => esc_html__( 'Enable', 'foxiz' ),
								'-1'      => esc_html__( 'Disable', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'table_contents_layout',
							'name'    => esc_html__( 'Layout', 'foxiz' ),
							'desc'    => esc_html__( 'Select a layout for the table of contents of this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'default' => esc_html__( '- Default -', 'foxiz' ),
								'1'       => esc_html__( 'Full Width (2 Columns)', 'foxiz' ),
								'2'       => esc_html__( 'Half Width', 'foxiz' ),
								'3'       => esc_html__( 'Full Width (1 Column)', 'foxiz' ),
							],
							'default' => 'default',
						],
						[
							'id'      => 'table_contents_position',
							'type'    => 'text',
							'name'    => esc_html__( 'Display Position', 'foxiz' ),
							'desc'    => esc_html__( 'Input a position (after x paragraphs) to display the table of contents box.', 'foxiz' ),
							'info'    => esc_html__( 'Leave it blank as the default, Set "-1" to display at the top.', 'foxiz' ),
							'default' => '',
						],
					],
				],
				[
					'id'     => 'section-header',
					'title'  => 'Site Header',
					'icon'   => 'dashicons-heading',
					'fields' => [
						[
							'id'      => 'header_style',
							'name'    => esc_html__( 'Header Layout', 'foxiz' ),
							'desc'    => esc_html__( 'Select a site header for this episode.', 'foxiz' ),
							'type'    => 'select',
							'options' => foxiz_config_header_style( true ),
							'default' => 'default',
						],
						[
							'id'          => 'header_template',
							'name'        => esc_html__( 'or Use Ruby Template for Site Header', 'foxiz' ),
							'desc'        => esc_html__( 'Input a Ruby Template shortcode for displaying as the website header for this episode.', 'foxiz' ),
							'info'        => esc_html__( 'This setting will override all "Header Layout" settings. Leave it blank to disable.', 'foxiz' ),
							'placeholder' => '[Ruby_E_Template id="1"]',
							'type'        => 'textarea',
							'default'     => '',
						],
						[
							'id'      => 'nav_style',
							'type'    => 'select',
							'name'    => esc_html__( 'Navigation Bar Style', 'foxiz' ),
							'desc'    => esc_html__( 'Select navigation bar style for the site header of this episode.', 'foxiz' ),
							'info'    => esc_html__( 'This setting will apply only to pre-defined headers: 1, 2, 3 and 5.', 'foxiz' ),
							'options' => [
								'default'  => esc_html__( '- Default -', 'foxiz' ),
								'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
								'border'   => esc_html__( 'Bottom Border', 'foxiz' ),
								'd-border' => esc_html__( 'Dark Bottom Border', 'foxiz' ),
								'none'     => esc_html__( 'None', 'foxiz' ),
							],
							'default' => 'default',
						],
					],
				],
				[
					'id'     => 'section-rss',
					'title'  => 'Podcast RSS',
					'icon'   => 'dashicons-rss',
					'desc'   => esc_html__( 'To enable Podcast RSS, make sure to use self-hosted audio files as they are the only supported format.', 'foxiz' ),
					'fields' => [
						[
							'id'      => 'episode_type',
							'name'    => esc_html__( 'Episode Type', 'foxiz' ),
							'desc'    => esc_html__( 'Select a type for this episode.', 'foxiz' ),
							'info'    => esc_html__( 'This info will display in podcast RSS.', 'foxiz' ),
							'type'    => 'select',
							'options' => [
								'full'    => esc_html__( '- Full -', 'foxiz' ),
								'trailer' => esc_html__( 'Trailer', 'foxiz' ),
							],
							'default' => 'full',
						],
					],
				],
			],
		];

		return apply_filters( 'rb_single_metaboxes', $configs );
	}
}