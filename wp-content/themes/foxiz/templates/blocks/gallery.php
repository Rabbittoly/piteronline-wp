<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_simple_gallery' ) ) {
	/**
	 * @param array $settings
	 * @param null  $_query
	 *
	 * @return false|string
	 */
	function foxiz_get_simple_gallery( $settings = [], $_query = null ) {

		$settings = wp_parse_args( $settings, [
			'uuid' => '',
			'name' => 'simple_gallery',
		] );

		if ( empty( $settings['columns'] ) ) {
			$settings['columns'] = 3;
		}
		if ( empty( $settings['column_gap'] ) ) {
			$settings['column_gap'] = 20;
		}

		if ( ! empty( $settings['center_mode'] ) && ( '-1' === (string) $settings['center_mode'] ) ) {
			$settings['center_mode'] = false;
		}

		$settings['classes'] = 'block-simple-gallery';
		if ( ! empty( $settings['image_style'] ) ) {
			$settings['classes'] .= ' is-style-' . $settings['image_style'];
		}

		$lazy_load = foxiz_get_option( 'lazy_load' );
		if ( ! empty( $settings['feat_lazyload'] ) ) {
			if ( 'none' === $settings['feat_lazyload'] ) {
				$lazy_load = false;
			} else {
				$lazy_load = true;
			}
		}

		ob_start();
		foxiz_block_open_tag( $settings );
		foxiz_block_inner_open_tag( $settings );
		if ( is_array( $settings['gallery_data'] ) ) {
			foreach ( $settings['gallery_data'] as $item ) {
				echo foxiz_get_simple_gallery_item( $item, $lazy_load );
			}
		}
		foxiz_block_inner_close_tag( $settings );
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_get_simple_gallery_item' ) ) {
	function foxiz_get_simple_gallery_item( $item = [], $lazy_load = true ) {

		$output = '';

		$output .= '<div class="simple-gallery-item">';
		if ( ! empty( $item['image']['id'] ) ) {
			if ( ! $lazy_load ) {
				$image = wp_get_attachment_image( $item['image']['id'], 'full', false, [ 'loading' => 'eager' ] );
			} else {
				$image = wp_get_attachment_image( $item['image']['id'], 'full', false, [ 'loading' => 'lazy' ] );
			}
		} elseif ( ! empty( $image['url'] ) ) {
			if ( ! $lazy_load ) {
				$attr = 'loading ="eager"';
			} else {
				$attr = 'loading ="lazy"';
			}
			$image = '<img src="' . $image['url'] . '" alt="' . esc_html__( 'gallery image', 'foxiz' ) . '" ' . $attr . '>';
		}

		if ( ! empty( $image ) ) {
			$output .= '<div class="simple-gallery-image">';
			if ( ! empty( $item['link']['url'] ) ) {
				$output .= foxiz_render_elementor_link( $item['link'], $image, '', $item['title'] );
			} else {
				$output .= $image;
			}
			if ( ! empty( $item['meta'] ) ) {
				$output .= '<div class="p-categories simple-gallery-meta">' . esc_html( $item['meta'] ) . '</div>';
			}
			$output .= '</div>';
		}
		if ( ! empty( $item['title'] ) ) {
			$output .= '<span class="simple-gallery-title h4">';
			if ( ! empty( $item['link']['url'] ) ) {
				$output .= foxiz_render_elementor_link( $item['link'], esc_html( $item['title'] ) );
			} else {
				$output .= esc_html( $item['title'] );
			}
			$output .= '</span>';
		}

		if ( ! empty( $item['description'] ) ) {
			$output .= '<span class="simple-gallery-desc">';
			$output .= esc_html( $item['description'] );
			$output .= '</span>';
		}

		$output .= '</div>';

		return $output;
	}
}