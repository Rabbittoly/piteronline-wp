<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_podcast_list_flex_1' ) ) {
	/**
	 * @param array $settings
	 * @param null $_query
	 *
	 * @return false|string
	 */
	function foxiz_get_podcast_list_flex_1( $settings = array(), $_query = null ) {

		$settings = wp_parse_args( $settings, array(
			'uuid' => '',
			'name' => 'podcast_list_flex_1'
		) );

		$settings = foxiz_detect_dynamic_query( $settings );

		if ( empty( $settings['pagination'] ) ) {
			$settings['no_found_rows'] = true;
		}
		$settings['classes'] = 'block-list block-podcast-list-flex-1';

		if ( ! $_query ) {
			$_query = foxiz_query( $settings );
		}

		$settings = foxiz_get_design_builder_block( $settings );

		ob_start();
		foxiz_block_open_tag( $settings, $_query );
		if ( ! $_query->have_posts() ) {
			foxiz_error_posts( $_query );
		} else {
			foxiz_block_inner_open_tag( $settings );
			foxiz_loop_podcast_list_flex_1( $settings, $_query );
			foxiz_block_inner_close_tag( $settings );
			foxiz_render_pagination( $settings, $_query );
			wp_reset_postdata();
		}
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_loop_podcast_list_flex_1' ) ) {
	/**
	 * @param  $settings
	 * @param $_query
	 */
	function foxiz_loop_podcast_list_flex_1( $settings, $_query ) {

		if ( empty( $settings['block_structure'] ) ) {
			$settings['block_structure'] = 'title,excerpt,meta';
		}
		$settings['block_structure'] = explode( ',', preg_replace( '/\s+/', '', $settings['block_structure'] ) );

		while ( $_query->have_posts() ) :
			$_query->the_post();
			foxiz_podcast_list_flex_1( $settings );
		endwhile;
	}
}