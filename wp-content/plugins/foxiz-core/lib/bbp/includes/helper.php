<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'ruby_bbp_get_sidebar_name' ) ) {
	/**
	 * @return false|mixed|void
	 */
	function ruby_bbp_get_sidebar_name() {

		/** disable sidebar on user page */
		if ( bbp_is_user_home() || bbp_is_single_user() || bbp_is_user_home_edit() || bbp_is_single_user_edit() ) {
			return false;
		}

		if ( function_exists( 'bbp_is_single_forum' ) && ( bbp_is_single_forum() || bbp_is_forum_archive() ) ) {
			$sidebar = get_option( 'ruby_bbp_forum_sidebar' );
			if ( '_default' !== $sidebar ) {
				return $sidebar;
			}
		} elseif ( function_exists( 'bbp_is_single_topic' ) && bbp_is_single_topic() ) {
			$sidebar = get_option( 'ruby_bbp_topic_sidebar' );
			if ( '_default' !== $sidebar ) {
				return $sidebar;
			}
		} elseif ( function_exists( 'bbp_has_shortcode' ) && bbp_has_shortcode() ) {
			$sidebar = get_option( 'ruby_bbp_shortcode_sidebar' );
			if ( '_default' !== $sidebar ) {
				return $sidebar;
			}
		}

		return get_option( 'ruby_bbp_sidebar' );
	}
}

if ( ! function_exists( 'ruby_bbp_validate_html_field' ) ) {
	function ruby_bbp_validate_html_field( $content ) {

		return wp_kses( $content, [
			'a' => [
				'href'   => true,
				'title'  => true,
				'rel'    => true,
				'target' => true,
			],

			'blockquote' => [
				'cite' => true,
			],

			'code' => [],
			'pre'  => [
				'class' => true,
			],

			'em'     => [],
			'strong' => [],
			'del'    => [
				'datetime' => true,
				'cite'     => true,
			],
			'ins'    => [
				'datetime' => true,
				'cite'     => true,
			],

			'img' => [
				'src'    => true,
				'border' => true,
				'alt'    => true,
				'height' => true,
				'width'  => true,
			],
		] );
	}
}

if ( ! function_exists( 'ruby_bbp_status_config' ) ) {
	function ruby_bbp_status_config() {

		return apply_filters( 'ruby_bbp_status', [
			'0'          => esc_html__( '- Default -', 'ruby-bbp' ),
			'resolved'   => get_option( 'ruby_bbp_topic_status_1', esc_html__( 'Resolved', 'ruby-bbp' ) ),
			'unresolved' => get_option( 'ruby_bbp_topic_status_2', esc_html__( 'Not Resolved', 'ruby-bbp' ) ),
			'solution'   => get_option( 'ruby_bbp_topic_status_3', esc_html__( 'Has a Solution', 'ruby-bbp' ) ),
		] );
	}
}