<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_list_style' ) ) {
	function foxiz_render_list_style( $attributes, $content ) {

		$icon        = ! empty( $attributes['icon'] ) ? $attributes['icon'] : '';
		$emoji       = ! empty( $attributes['emoji'] ) ? $attributes['emoji'] : '';
		$desktopSize = ! empty( $attributes['desktopSize'] ) ? $attributes['desktopSize'] : '';
		$tabletSize  = ! empty( $attributes['tabletSize'] ) ? $attributes['tabletSize'] : '';
		$mobileSize  = ! empty( $attributes['mobileSize'] ) ? $attributes['mobileSize'] : '';
		$color       = ! empty( $attributes['color'] ) ? $attributes['color'] : '';
		$darkColor   = ! empty( $attributes['darkColor'] ) ? $attributes['darkColor'] : '';
		$spacing     = ! empty( $attributes['spacing'] ) ? $attributes['spacing'] : '';

		$wrapperClassName = 'list-style-element';
		if ( $emoji ) {
			$wrapperClassName .= ' is-emoji';
		} else {
			$wrapperClassName .= ' is-icon';
		}

		$styleAttribute = '';

		if ( $emoji ) {
			$styleAttribute .= '--icon-code: "' . $emoji . '"; ';
		} elseif ( ! empty( $icon ) ) {
			$styleAttribute .= '--icon-code: "\\' . $icon . '"; ';
		}

		if ( $desktopSize ) {
			$styleAttribute .= '--desktop-icon-size: ' . $desktopSize . 'px; ';
		}
		if ( $tabletSize ) {
			$styleAttribute .= '--tablet-icon-size: ' . $tabletSize . 'px; ';
		}
		if ( $mobileSize ) {
			$styleAttribute .= '--mobile-icon-size: ' . $mobileSize . 'px; ';
		}
		if ( $spacing ) {
			$styleAttribute .= '--item-spacing: ' . $spacing . 'px; ';
		}
		if ( $color ) {
			$styleAttribute .= '--icon-color: ' . $color . '; ';
		}
		if ( $darkColor ) {
			$styleAttribute .= '--dark-icon-color: ' . $darkColor . '; ';
		}

		return '<div ' . get_block_wrapper_attributes( [
				'class' => $wrapperClassName,
				'style' => $styleAttribute,
			] ) . '>' . $content . '</div>';
	}
}
