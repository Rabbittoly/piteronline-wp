<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_block_related' ) ) {
	function foxiz_render_block_related( $attributes ) {

		if ( empty( $attributes['layout'] ) ) {
			$attributes['layout'] = 1;
		}
		if ( empty( $attributes['total'] ) ) {
			$attributes['total'] = 4;
		}
		if ( empty( $attributes['heading_tag'] ) ) {
			$attributes['heading_tag'] = 'h4';
		}
		if ( empty( $attributes['ids'] ) ) {
			$attributes['ids'] = '';
		} else {
			$attributes['ids'] = trim( $attributes['ids'] );
		}
		if ( empty( $attributes['where'] ) ) {
			$attributes['where'] = 'all';
		}
		if ( empty( $attributes['order'] ) ) {
			$attributes['order'] = 'rand';
		}

		$output = '<div class="rb-gutenberg-related">';
		$output .= do_shortcode(
			'[ruby_related heading_tag="' . $attributes['heading_tag'] .
			'" heading="' . esc_html( $attributes['heading'] ) .
			'" total="' . $attributes['total'] .
			'" ids="' . $attributes['ids'] .
			'" layout="' . $attributes['layout'] .
			'" order="' . $attributes['order'] .
			'" where="' . $attributes['where'] . '"]'
		);
		$output .= '</div>';

		return $output;
	}
}