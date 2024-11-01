<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_config_podcast_entry_meta' ) ) {
	/**
	 * @return array
	 */
	function foxiz_config_podcast_entry_meta() {

		return array(
			'avatar'   => esc_html__( 'avatar (Avatar)', 'foxiz' ),
			'author'   => esc_html__( 'author (Host)', 'foxiz' ),
			'date'     => esc_html__( 'date (Publish Date)', 'foxiz' ),
			'category' => esc_html__( 'category (Categories)', 'foxiz' ),
			'tag'      => esc_html__( 'tag (Tags)', 'foxiz' ),
			'view'     => esc_html__( 'view (Post Views)', 'foxiz' ),
			'comment'  => esc_html__( 'comment (Comments)', 'foxiz' ),
			'update'   => esc_html__( 'update  (Last Updated)', 'foxiz' ),
			'read'     => esc_html__( 'read (Reading Time)', 'foxiz' ),
			'custom'   => esc_html__( 'custom (Custom)', 'foxiz' ),
			'duration' => esc_html__( 'duration (Duration)', 'foxiz' ),
			'index'    => esc_html__( 'index (Episode Index)', 'foxiz' ),
		);
	}
}

if ( ! function_exists( 'foxiz_get_listen_on_settings' ) ) {
	function foxiz_get_listen_on_settings( $post_id = '' ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$data = rb_get_meta( 'listen_on', $post_id );
		if ( empty( $data ) || ! is_array( $data ) ) {
			$data = array();
		}

		$apple      = rb_get_meta( 'listen_on_apple', $post_id );
		$spotify    = rb_get_meta( 'listen_on_spotify', $post_id );
		$soundcloud = rb_get_meta( 'listen_on_soundcloud', $post_id );
		$google     = rb_get_meta( 'listen_on_google', $post_id );

		if ( ! empty( $google ) ) {
			array_unshift( $data, array(
				'label' => 'Google Podcast',
				'icon'  => 'rbi-googlepodcast',
				'url'   => $google,
			) );
		}

		if ( ! empty( $soundcloud ) ) {
			array_unshift( $data, array(
				'label' => 'SoundCloud',
				'icon'  => 'rbi-soundcloud',
				'url'   => $soundcloud,
			) );
		}

		if ( ! empty( $spotify ) ) {
			array_unshift( $data, array(
				'label' => 'Spotify',
				'icon'  => 'rbi-spotify',
				'url'   => $spotify,
			) );
		}

		if ( ! empty( $apple ) ) {
			array_unshift( $data, array(
				'label' => 'Apple',
				'icon'  => 'rbi-applepodcast',
				'url'   => $apple,
			) );
		}

		return $data;
	}
}

if ( ! function_exists( 'foxiz_get_series_settings' ) ) {
	function foxiz_get_series_settings( $tax_id = '' ) {
		$prefix = 'series_';

		if ( empty( $tax_id ) ) {
			$tax_id = get_queried_object_id();
		}
		$settings = rb_get_term_meta( 'foxiz_category_meta', $tax_id );

		$settings['category']      = $tax_id;
		$settings['category_name'] = get_cat_name( $tax_id );
		$settings['uuid']          = 'uid_c' . $tax_id;

		if ( empty( $settings['category_header'] ) ) {
			$settings['category_header'] = foxiz_get_option( $prefix . 'category_header' );
		}
		if ( empty( $settings['breadcrumb'] ) ) {
			$settings['breadcrumb'] = foxiz_get_option( $prefix . 'breadcrumb' );
		}

		if ( '-1' === (string) $settings['breadcrumb'] ) {
			$settings['breadcrumb'] = false;
		}

		if ( empty( $settings['featured_image'] ) || ! is_array( $settings['featured_image'] ) || ! count( $settings['featured_image'] ) ) {
			$settings['featured_image'] = foxiz_get_option( $prefix . 'featured_image' );
			if ( ! empty( $settings['featured_image'] ) ) {
				$settings['featured_image'] = explode( ',', $settings['featured_image'] );
			}
		}
		if ( empty( $settings['pattern'] ) ) {
			$settings['pattern'] = foxiz_get_option( $prefix . 'pattern' );
		}
		if ( empty( $settings['template_global'] ) ) {
			$settings['template_global'] = foxiz_get_option( $prefix . 'template_global' );
		}

		/** define block index */
		$settings['block_structure'] = 'thumbnail,title,meta,excerpt';
		$settings['columns']         = '3';
		$settings['columns_tablet']  = '2';
		$settings['columns_mobile']  = '1';
		$settings['crop_size']       = 'foxiz_crop_g2';
		$settings['title_index']     = '1';
		$settings['entry_meta']      = 'play,author';
		$settings['excerpt_length']  = '20';
		$settings['excerpt_source']  = 'tagline';

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_get_podcast_archive_settings' ) ) {
	function foxiz_get_podcast_archive_settings() {

		$prefix                      = 'podcast_archive_';
		$settings['uuid']            = 'uid_' . $prefix . get_queried_object_id();
		$settings['pattern']         = foxiz_get_option( $prefix . 'pattern' );
		$settings['template_global'] = foxiz_get_option( $prefix . 'template_global' );
		$settings['posts_per_page']  = foxiz_get_option( $prefix . 'posts_per_page' );
		if ( empty( $settings['posts_per_page'] ) ) {
			$settings['posts_per_page'] = get_option( 'posts_per_page' );
		}

		/** define block index */
		$settings['block_structure'] = 'thumbnail,title,meta,excerpt';
		$settings['columns']         = '3';
		$settings['columns_tablet']  = '2';
		$settings['columns_mobile']  = '1';
		$settings['crop_size']       = 'foxiz_crop_g2';
		$settings['title_index']     = '1';
		$settings['entry_meta']      = 'play,author';
		$settings['excerpt_length']  = '20';
		$settings['excerpt_source']  = 'tagline';

		return $settings;
	}
}
