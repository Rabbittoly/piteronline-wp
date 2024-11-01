<?php

namespace foxizElementorControl;

use function foxiz_count_posts_category;
use function foxiz_query_order_selection;

defined( 'ABSPATH' ) || exit;

/**
 * Class Options
 *
 * @package foxizElementorControl
 * options
 */
class Options {

	static function switch_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'  => esc_html__( '- Default -', 'foxiz-core' ),
				'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				'-1' => esc_html__( 'Disable', 'foxiz-core' ),
			];
		} else {
			return [
				'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				'-1' => esc_html__( 'Disable', 'foxiz-core' ),
			];
		}
	}

	/**
	 * @param false  $dynamic
	 * @param string $taxonomy
	 *
	 * @return array|false
	 */
	static function cat_dropdown( $dynamic = false, $taxonomy = 'category' ) {

		$data = [
			'0' => esc_html__( '- All -', 'foxiz-core' ),
		];

		if ( $dynamic ) {
			$data['dynamic'] = esc_html__( 'Dynamic Query', 'foxiz-core' );
		}

		$categories = get_categories( [
			'hide_empty' => 0,
			'taxonomy'   => $taxonomy,
			'parent'     => '0',
		] );

		$pos = 1;
		foreach ( $categories as $index => $item ) {
			$children = get_categories( [
				'hide_empty' => 0,
				'taxonomy'   => $taxonomy,
				'child_of'   => $item->term_id,
			] );
			if ( ! empty( $children ) ) {
				array_splice( $categories, $pos + $index, 0, $children );
				$pos += count( $children );
			}
		}

		foreach ( $categories as $item ) {
			$deep = '';
			if ( ! empty( $item->parent ) ) {
				$deep = '--';
			}

			$data[ $item->term_id ] = $deep . ' ' . esc_attr( $item->name ) . ' - [ID: ' . esc_attr( $item->term_id ) . ' / Posts: ' . foxiz_count_posts_category( $item ) . ']';
		}

		return $data;
	}

	/**
	 * @param string $post_type
	 * @param string $empty_label
	 *
	 * @return array|false|string[]
	 */
	static function cat_slug_dropdown( $post_type = 'post', $empty_label = '' ) {

		if ( empty( $empty_label ) ) {
			$empty_label = esc_html__( '-- All categories --', 'foxiz-core' );
		}

		$data = [
			0 => $empty_label,
		];

		$categories = get_categories( [
			'hide_empty' => 0,
			'type'       => $post_type,
			'parent'     => '0',
		] );

		$pos = 1;
		foreach ( $categories as $index => $item ) {
			$children = get_categories( [
				'hide_empty' => 0,
				'type'       => $post_type,
				'child_of'   => $item->term_id,
			] );
			if ( ! empty( $children ) ) {
				array_splice( $categories, $pos + $index, 0, $children );
				$pos += count( $children );
			}
		}

		foreach ( $categories as $item ) {

			$deep = '';
			if ( ! empty( $item->parent ) ) {
				$deep = '--';
			}
			$data[ $item->slug ] = $deep . ' ' . $item->name . ' [Posts: ' . foxiz_count_posts_category( $item ) . ']';
		}

		return $data;
	}

	static function post_type_dropdown() {

		$post_types = get_post_types( [ 'public' => true ], 'objects' );

		/** unset post types */
		unset( $post_types['post'], $post_types['rb-etemplate'], $post_types['e-landing-page'], $post_types['elementor_library'] );

		$list = [
			'0' => esc_html__( '- Default -', 'foxiz-core' ),
		];

		foreach ( $post_types as $post_type ) {
			$core_label = in_array(
				$post_type->name,
				[
					'post',
					'page',
					'attachment',
				],
				true
			) ? esc_html__( '(WP Core)', 'foxiz-core' ) : '';

			$list[ $post_type->name ] = $post_type->label . ' ' . $core_label;
		}

		return $list;
	}

	static function taxonomy_dropdown() {

		$taxes = get_taxonomies( [ 'public' => true ], 'objects' );
		unset( $taxes['nav_menu'], $taxes['post_format'] );
		$list = [
			'0' => esc_html__( '- Default -', 'foxiz-core' ),
		];

		foreach ( $taxes as $tax ) {
			$list[ $tax->name ] = $tax->label;
		}

		return $list;
	}

	static function followed_dropdown() {

		return [
			'1'   => esc_html__( 'Categories', 'foxiz-core' ),
			'2'   => esc_html__( 'Tags', 'foxiz-core' ),
			'all' => esc_html__( 'All Taxonomies', 'foxiz-core' ),
			'-1'  => esc_html__( 'Disable', 'foxiz-core' ),
		];
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	static function order_dropdown( $settings = [] ) {

		return foxiz_query_order_selection( $settings );
	}

	static function format_dropdown() {

		return [
			'0'       => esc_html__( '- All -', 'foxiz-core' ),
			'default' => esc_html__( 'Post Only', 'foxiz-core' ),
			'gallery' => esc_html__( 'Gallery', 'foxiz-core' ),
			'video'   => esc_html__( 'Video', 'foxiz-core' ),
			'audio'   => esc_html__( 'Audio', 'foxiz-core' ),
		];
	}

	static function author_dropdown( $dynamic = false, $all = true ) {

		$blogusers = get_users( [
			'role__not_in' => [ 'subscriber' ],
			'fields'       => [ 'ID', 'display_name' ],
		] );

		$dropdown = [];

		if ( $all ) {
			$dropdown = [
				'0' => esc_html__( '-- All Authors --', 'foxiz-core' ),
			];
		}

		if ( $dynamic ) {
			$dropdown['dynamic_author'] = esc_html__( 'Dynamic Query', 'foxiz-core' );
		}

		if ( is_array( $blogusers ) ) {
			foreach ( $blogusers as $user ):
				$dropdown[ esc_attr( $user->ID ) ] = esc_attr( $user->display_name );
			endforeach;
		}

		return $dropdown;
	}

	/**
	 * @param bool $default
	 *
	 * @return array|string[]
	 */
	static function heading_html_dropdown( $default = true ) {

		$settings = [
			'0'    => esc_html__( '- Default -', 'foxiz-core' ),
			'h1'   => esc_html__( 'H1', 'foxiz-core' ),
			'h2'   => esc_html__( 'H2', 'foxiz-core' ),
			'h3'   => esc_html__( 'H3', 'foxiz-core' ),
			'h4'   => esc_html__( 'H4', 'foxiz-core' ),
			'h5'   => esc_html__( 'H5', 'foxiz-core' ),
			'h6'   => esc_html__( 'H6', 'foxiz-core' ),
			'p'    => esc_html__( 'p', 'foxiz-core' ),
			'span' => esc_html__( 'span', 'foxiz-core' ),
			'div'  => esc_html__( 'div', 'foxiz-core' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}

	static function excerpt_dropdown() {

		return [
			'0' => esc_html__( '- Default -', 'foxiz-core' ),
			'1' => esc_html__( 'Custom Settings Below', 'foxiz-core' ),
		];
	}

	static function excerpt_source_dropdown() {

		return [
			'0'       => esc_html__( 'Use Post Excerpt', 'foxiz-core' ),
			'tagline' => esc_html__( 'Use Title Tagline', 'foxiz-core' ),
		];
	}

	/** featured dropdown */
	static function feat_hover_dropdown() {

		return [
			'0'         => esc_html__( '- Disable -', 'foxiz-core' ),
			'scale'     => esc_html__( 'Scale', 'foxiz-core' ),
			'fade'      => esc_html__( 'Fade Out', 'foxiz-core' ),
			'bw'        => esc_html__( 'Black & White', 'foxiz-core' ),
			'bw-invert' => esc_html__( 'Black & White Invert', 'foxiz-core' ),
		];
	}

	static function feat_lazyload_dropdown() {

		return [
			'0'    => esc_html__( '- Default -', 'foxiz-core' ),
			'none' => esc_html__( 'Disable', 'foxiz-core' ),
			'1'    => esc_html__( 'Enable', 'foxiz-core' ),
		];
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function extended_entry_category_dropdown( $default = true ) {

		$settings = [
			'0'            => esc_html__( 'Default from Theme Option', 'foxiz-core' ),
			'bg-1'         => esc_html__( 'Background 1', 'foxiz-core' ),
			'bg-1,big'     => esc_html__( 'Background 1 (Big)', 'foxiz-core' ),
			'bg-2'         => esc_html__( 'Background 2', 'foxiz-core' ),
			'bg-2,big'     => esc_html__( 'Background 2 (Big)', 'foxiz-core' ),
			'bg-3'         => esc_html__( 'Background 3', 'foxiz-core' ),
			'bg-3,big'     => esc_html__( 'Background 3 (Big)', 'foxiz-core' ),
			'bg-4'         => esc_html__( 'Background 4', 'foxiz-core' ),
			'bg-4,big'     => esc_html__( 'Background 4 (Big)', 'foxiz-core' ),
			'text'         => esc_html__( 'Only Text', 'foxiz-core' ),
			'text,big'     => esc_html__( 'Only Text (Big)', 'foxiz-core' ),
			'border'       => esc_html__( 'Border', 'foxiz-core' ),
			'border,big'   => esc_html__( 'Border (Big)', 'foxiz-core' ),
			'b-dotted'     => esc_html__( 'Bottom Dotted', 'foxiz-core' ),
			'b-dotted,big' => esc_html__( 'Bottom Dotted (Big)', 'foxiz-core' ),
			'b-border'     => esc_html__( 'Bottom Border', 'foxiz-core' ),
			'b-border,big' => esc_html__( 'Bottom Border (Big)', 'foxiz-core' ),
			'l-dot'        => esc_html__( 'Left Dot', 'foxiz-core' ),
			'-1'           => esc_html__( 'Disable', 'foxiz-core' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}

	static function entry_meta_dropdown() {

		return [
			'0'      => esc_html__( 'Default from Theme Option', 'foxiz-core' ),
			'custom' => esc_html__( 'Custom Below', 'foxiz-core' ),
			'-1'     => esc_html__( 'Disable', 'foxiz-core' ),
		];
	}

	static function sponsor_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'  => esc_html__( '- Default -', 'foxiz-core' ),
				'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				'2'  => esc_html__( 'Enable without Label', 'foxiz-core' ),
				'-1' => esc_html__( 'Disable', 'foxiz-core' ),
			];
		} else {
			return [
				'1'  => esc_html__( 'Enable', 'foxiz-core' ),
				'2'  => esc_html__( 'Enable without Label', 'foxiz-core' ),
				'-1' => esc_html__( 'Disable', 'foxiz-core' ),
			];
		}
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function entry_format_dropdown( $default = true ) {

		$settings = [
			'0'              => esc_html__( 'Default from Theme Option', 'foxiz-core' ),
			'bottom'         => esc_html__( 'Bottom Right', 'foxiz-core' ),
			'bottom,big'     => esc_html__( 'Bottom Right (Big Icon) ', 'foxiz-core' ),
			'top'            => esc_html__( 'Top', 'foxiz-core' ),
			'top,big'        => esc_html__( 'Top (Big Icon)', 'foxiz-core' ),
			'center'         => esc_html__( 'Center', 'foxiz-core' ),
			'center,big'     => esc_html__( 'Center (Big Icon)', 'foxiz-core' ),
			'after-category' => esc_html__( 'After Entry Category', 'foxiz-core' ),
			'-1'             => esc_html__( 'Disable', 'foxiz-core' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function review_dropdown( $default = true ) {

		$settings = [
			'0'       => esc_html__( 'Default from Theme Option', 'foxiz-core' ),
			'1'       => esc_html__( 'Enable', 'foxiz-core' ),
			'replace' => esc_html__( 'Replace for Entry Meta', 'foxiz-core' ),
			'-1'      => esc_html__( 'Disable', 'foxiz-core' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}

	/**
	 * @return array
	 */
	static function flex_review_dropdown() {

		return [
			'0'       => esc_html__( '- Default -', 'foxiz-core' ),
			'replace' => esc_html__( 'Replace for Entry Meta', 'foxiz-core' ),

		];
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function review_meta_dropdown( $default = true ) {

		$settings = [
			'0'  => esc_html__( '- Default -', 'foxiz-core' ),
			'1'  => esc_html__( 'No Wrap', 'foxiz-core' ),
			'2'  => esc_html__( 'Desktop No Wrap - Mobile Line Wrap', 'foxiz-core' ),
			'3'  => esc_html__( 'Line Wrap', 'foxiz-core' ),
			'4'  => esc_html__( 'No Wrap (Show Score Only)', 'foxiz-core' ),
			'5'  => esc_html__( 'Line Wrap (Show Score Only)', 'foxiz-core' ),
			'-1' => esc_html__( 'Disable', 'foxiz-core' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}

	/**
	 * @param array $configs
	 *
	 * @return array
	 * columns_dropdown
	 */
	static function columns_dropdown( $configs = [] ) {

		$settings = [];

		$default = [
			'0' => esc_html__( '- Default -', 'foxiz-core' ),
			'1' => esc_html__( '1 Column', 'foxiz-core' ),
			'2' => esc_html__( '2 Columns', 'foxiz-core' ),
			'3' => esc_html__( '3 Columns', 'foxiz-core' ),
			'4' => esc_html__( '4 Columns', 'foxiz-core' ),
			'5' => esc_html__( '5 Columns', 'foxiz-core' ),
			'6' => esc_html__( '6 Columns', 'foxiz-core' ),
			'7' => esc_html__( '7 Columns', 'foxiz-core' ),
		];

		if ( ! is_array( $configs ) || ! count( $configs ) ) {
			return $default;
		}
		foreach ( $configs as $item ) {
			$settings[ $item ] = $default[ $item ];
		}

		return $settings;
	}

	/**
	 * @return array
	 * column_gap_dropdown
	 */
	static function column_gap_dropdown() {

		return [
			'0'      => esc_html__( '- Default -', 'foxiz-core' ),
			'none'   => esc_html__( 'No Gap', 'foxiz-core' ),
			'5'      => esc_html__( '10px', 'foxiz-core' ),
			'7'      => esc_html__( '14px', 'foxiz-core' ),
			'10'     => esc_html__( '20px', 'foxiz-core' ),
			'15'     => esc_html__( '30px', 'foxiz-core' ),
			'20'     => esc_html__( '40px', 'foxiz-core' ),
			'25'     => esc_html__( '50px', 'foxiz-core' ),
			'30'     => esc_html__( '60px', 'foxiz-core' ),
			'35'     => esc_html__( '70px', 'foxiz-core' ),
			'custom' => esc_html__( 'Custom Value', 'foxiz-core' ),
		];
	}

	/**
	 * @param array $disabled
	 *
	 * @return array
	 * pagination dropdown
	 */
	static function pagination_dropdown( $disabled = [] ) {

		$settings = [
			'0'               => esc_html__( '- Disable -', 'foxiz-core' ),
			'next_prev'       => esc_html__( 'Next Prev', 'foxiz-core' ),
			'load_more'       => esc_html__( 'Show More', 'foxiz-core' ),
			'infinite_scroll' => esc_html__( 'Infinite Scroll', 'foxiz-core' ),
		];

		if ( count( $disabled ) ) {
			foreach ( $disabled as $key ) {
				unset( $settings[ $key ] );
			}
		}

		return $settings;
	}

	/**
	 * @return array
	 */
	static function template_builder_pagination_dropdown() {

		return [
			'0'               => esc_html__( '- Disable -', 'foxiz-core' ),
			'number'          => esc_html__( 'Numeric', 'foxiz-core' ),
			'simple'          => esc_html__( 'Simple', 'foxiz-core' ),
			'load_more'       => esc_html__( 'Show More (ajax)', 'foxiz-core' ),
			'infinite_scroll' => esc_html__( 'Infinite Scroll (ajax)', 'foxiz-core' ),
		];
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function crop_size_dropdown( $default = true ) {

		global $_wp_additional_image_sizes;

		$settings = [];
		if ( $default ) {
			$settings['0'] = esc_html__( '- Default -', 'foxiz-core' );
		}

		if ( ! empty( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $size => $data ) {
				$settings[ $size ] = $data['width'] . 'x' . $data['height'] . ' (' . $size . ')';
			}
		}

		$settings['thumbnail']    = esc_html__( 'Thumbnail (Core WP)', 'foxiz-core' );
		$settings['medium']       = esc_html__( 'Medium (Core WP)', 'foxiz-core' );
		$settings['medium_large'] = esc_html__( 'Medium Large (Core WP)', 'foxiz-core' );
		$settings['large']        = esc_html__( 'Large (Core WP)', 'foxiz-core' );

		return $settings;
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function featured_position_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'     => esc_html__( '- Default -', 'foxiz-core' ),
				'left'  => esc_html__( 'Left', 'foxiz-core' ),
				'right' => esc_html__( 'Right', 'foxiz-core' ),
			];
		} else {
			return [
				'left'  => esc_html__( 'Left', 'foxiz-core' ),
				'right' => esc_html__( 'Right', 'foxiz-core' ),
			];
		}
	}

	/**
	 * @param bool $default
	 *
	 * @return array
	 */
	static function hide_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'      => esc_html__( '- Default -', 'foxiz-core' ),
				'mobile' => esc_html__( 'On Mobile', 'foxiz-core' ),
				'tablet' => esc_html__( 'On Tablet', 'foxiz-core' ),
				'all'    => esc_html__( 'On Tablet & Mobile', 'foxiz-core' ),
				'-1'     => esc_html__( 'Disable', 'foxiz-core' ),
			];
		} else {
			return [
				'0'      => esc_html__( '- Disable -', 'foxiz-core' ),
				'mobile' => esc_html__( 'On Mobile', 'foxiz-core' ),
				'tablet' => esc_html__( 'On Tablet', 'foxiz-core' ),
				'all'    => esc_html__( 'Tablet & Mobile', 'foxiz-core' ),
			];
		}
	}

	static function box_style_dropdown() {

		return [
			'0'      => esc_html__( '- Default -', 'foxiz-core' ),
			'bg'     => esc_html__( 'Background', 'foxiz-core' ),
			'border' => esc_html__( 'Border', 'foxiz-core' ),
			'shadow' => esc_html__( 'Shadow', 'foxiz-core' ),
		];
	}

	static function column_border_dropdown() {

		return [
			'0'         => esc_html__( '- Disable -', 'foxiz-core' ),
			'gray'      => esc_html__( 'Gray Solid', 'foxiz-core' ),
			'dark'      => esc_html__( 'Dark Solid', 'foxiz-core' ),
			'gray-dot'  => esc_html__( 'Gray Dotted', 'foxiz-core' ),
			'dark-dot'  => esc_html__( 'Dark Dotted', 'foxiz-core' ),
			'gray-dash' => esc_html__( 'Gray Dashed', 'foxiz-core' ),
			'dark-dash' => esc_html__( 'Dark Dashed', 'foxiz-core' ),
		];
	}

	static function pagination_style_dropdown() {

		return [
			'0'      => esc_html__( '- Default -', 'foxiz-core' ),
			'border' => esc_html__( 'Border', 'foxiz-core' ),
			'text'   => esc_html__( 'Text Only', 'foxiz-core' ),
		];
	}

	/**
	 * @return array
	 */
	static function ad_size_dropdown() {

		return [
			'1'  => esc_html__( 'Leaderboard (728x90)', 'foxiz-core' ),
			'2'  => esc_html__( 'Banner (468x60)', 'foxiz-core' ),
			'3'  => esc_html__( 'Half banner (234x60)', 'foxiz-core' ),
			'4'  => esc_html__( 'Button (125x125)', 'foxiz-core' ),
			'5'  => esc_html__( 'Skyscraper (120x600)', 'foxiz-core' ),
			'6'  => esc_html__( 'Wide Skyscraper (160x600)', 'foxiz-core' ),
			'7'  => esc_html__( 'Small Rectangle (180x150)', 'foxiz-core' ),
			'8'  => esc_html__( 'Vertical Banner (120 x 240)', 'foxiz-core' ),
			'9'  => esc_html__( 'Small Square (200x200)', 'foxiz-core' ),
			'10' => esc_html__( 'Square (250x250)', 'foxiz-core' ),
			'11' => esc_html__( 'Medium Rectangle (300x250)', 'foxiz-core' ),
			'12' => esc_html__( 'Large Rectangle (336x280)', 'foxiz-core' ),
			'13' => esc_html__( 'Half Page (300x600)', 'foxiz-core' ),
			'14' => esc_html__( 'Portrait (300x1050)', 'foxiz-core' ),
			'15' => esc_html__( 'Mobile Banner (320x50)', 'foxiz-core' ),
			'16' => esc_html__( 'Large Leaderboard (970x90)', 'foxiz-core' ),
			'17' => esc_html__( 'Billboard (970x250)', 'foxiz-core' ),
			'18' => esc_html__( 'Mobile Banner (320x100)', 'foxiz-core' ),
			'19' => esc_html__( 'Mobile Friendly (300x100)', 'foxiz-core' ),
			'-1' => esc_html__( 'Hide on Desktop', 'foxiz-core' ),
		];
	}

	static function vertical_align_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'  => esc_html__( '- Default -', 'foxiz-core' ),
				'1'  => esc_html__( 'Middle', 'foxiz-core' ),
				'-1' => esc_html__( 'Bottom', 'foxiz-core' ),
				'2'  => esc_html__( 'Top', 'foxiz-core' ),
			];
		} else {
			return [
				'1'  => esc_html__( 'Middle', 'foxiz-core' ),
				'-1' => esc_html__( 'Bottom', 'foxiz-core' ),
				'2'  => esc_html__( 'Top', 'foxiz-core' ),
			];
		}
	}

	static function mobile_layout_dropdown( $default = true ) {

		if ( $default ) {
			return [
				'0'    => esc_html__( '- Default -', 'foxiz-core' ),
				'grid' => esc_html__( 'Grid', 'foxiz-core' ),
				'list' => esc_html__( 'List', 'foxiz-core' ),
			];
		} else {
			return [
				'grid' => esc_html__( 'Grid', 'foxiz-core' ),
				'list' => esc_html__( 'List', 'foxiz-core' ),
			];
		}
	}

	/**
	 * @return array
	 */
	static function divider_style_dropdown() {

		return [
			'solid'   => esc_html__( 'Solid', 'foxiz-core' ),
			'bold'    => esc_html__( 'Bold Solid', 'foxiz-core' ),
			'dashed'  => esc_html__( 'Dashed', 'foxiz-core' ),
			'bdashed' => esc_html__( 'Bold Dashed', 'foxiz-core' ),
			'zigzag'  => esc_html__( 'Zigzag', 'foxiz-core' ),
		];
	}

	static function horizontal_scroll_dropdown() {

		return [
			'0'      => esc_html__( '- Disable -', 'foxiz-core' ),
			'1'      => esc_html__( 'Tablet & Mobile', 'foxiz-core' ),
			'tablet' => esc_html__( 'Tablet Only', 'foxiz-core' ),
			'mobile' => esc_html__( 'Mobile Only', 'foxiz-core' ),
		];
	}

	static function meta_divider_dropdown() {

		return [
			'0'         => esc_html__( '- Default -', 'foxiz-core' ),
			'default'   => esc_html__( 'Vertical Line', 'foxiz-core' ),
			'line'      => esc_html__( 'Solid Line', 'foxiz-core' ),
			'gray-line' => esc_html__( 'Gray Solid Line', 'foxiz' ),
			'dot'       => esc_html__( 'Dot', 'foxiz-core' ),
			'gray-dot'  => esc_html__( 'Gray Dot', 'foxiz-core' ),
			'none'      => esc_html__( 'White Spacing', 'foxiz-core' ),
			'wrap'      => esc_html__( 'Line Wrap', 'foxiz-core' ),
		];
	}

	static function count_posts_dropdown( $default = true ) {

		return [
			'1'  => esc_html__( '- Enable -', 'foxiz-core' ),
			'2'  => esc_html__( 'Include Children Taxonomies', 'foxiz-core' ),
			'-1' => esc_html__( 'Disable', 'foxiz-core' ),
		];
	}

	static function menu_divider_dropdown() {

		return [
			'0'      => esc_html__( 'None', 'foxiz-core' ),
			'slash'  => esc_html__( 'Slash (/)', 'foxiz-core' ),
			'pipe'   => esc_html__( 'Pipe (|)', 'foxiz-core' ),
			'pipe-2' => esc_html__( 'Pipe 2 (|)', 'foxiz-core' ),
			'hyphen' => esc_html__( 'Hyphen (-)', 'foxiz-core' ),
			'dot'    => esc_html__( 'Dot (.)', 'foxiz-core' ),
			'dot-2'  => esc_html__( 'Dot 2(.)', 'foxiz-core' ),
		];
	}

	/** settings subtitle & description */
	static function category_description() {

		return esc_html__( 'Filter posts by category.', 'foxiz-core' );
	}

	static function categories_description() {

		return esc_html__( 'Filter posts by multiple category IDs, separated by commas (e.g. 1, 2, 3).', 'foxiz-core' );
	}

	static function category_not_in_description() {

		return esc_html__( 'Exclude category IDs. This setting is only available when selecting all categories, separated by commas (e.g. 1, 2, 3).', 'foxiz-core' );
	}

	static function tags_description() {

		return esc_html__( 'Filter posts by tag slugs, separated by commas (e.g. tagslug1, tagslug2, tagslug3).', 'foxiz-core' );
	}

	static function tag_not_in_description() {

		return esc_html__( 'Exclude tag slugs, separated by commas (e.g. tagslug1, tagslug2, tagslug3).', 'foxiz-core' );
	}

	static function format_description() {

		return esc_html__( 'Filter posts by post format.', 'foxiz-core' );
	}

	static function author_description() {

		return esc_html__( 'Filter posts by post author.', 'foxiz-core' );
	}

	static function post_not_in_description() {

		return esc_html__( 'Exclude posts by Post IDs, separated by commas (e.g. 1,2,3).', 'foxiz-core' );
	}

	static function post_in_description() {

		return esc_html__( 'Filter posts by post IDs. separated by commas (e.g. 1,2,3).', 'foxiz-core' );
	}

	static function order_description() {

		return esc_html__( 'Please select a type to order the query results.', 'foxiz-core' );
	}

	static function posts_per_page_description() {

		return esc_html__( 'Select the number of posts to display at once.', 'foxiz-core' );
	}

	static function offset_description() {

		return esc_html__( 'Specify the number of posts to skip. Leaving it blank starts from the beginning. Use with caution: enabling this setting may result in missing posts if Unique posts is activated', 'foxiz-core' );
	}

	static function heading_html_description() {

		return esc_html__( 'Select a title HTML tag for the main title.', 'foxiz-core' );
	}

	static function sub_heading_html_description() {

		return esc_html__( 'Select a title HTML tag for the secondary titles.', 'foxiz-core' );
	}

	static function crop_size() {

		return esc_html__( 'Select a featured image size to optimize with the columns setting.', 'foxiz-core' );
	}

	static function featured_width_description() {

		return esc_html__( 'Input custom width values (in pixels) for the featured image.', 'foxiz-core' );
	}

	static function featured_position_description() {

		return esc_html__( 'Select a position of the featured image for this layout.', 'foxiz-core' );
	}

	static function display_ratio_description() {

		return esc_html__( 'Input custom ratio percent (height*100/width) for featured image you would like. e.g. 50', 'foxiz-core' );
	}

	static function feat_hover_description() {

		return esc_html__( 'Select a hover effect for this block featured images.', 'foxiz-core' );
	}

	static function feat_align_description() {

		return esc_html__( 'Align the featured images for this block.', 'foxiz-core' );
	}

	static function feat_lazyload_description() {

		return esc_html__( 'Disable lazy load image if this block is above the fold. The default is base on Theme Options > Performance > Featured Image - Lazy Load', 'foxiz-core' );
	}

	static function entry_category_description() {

		return esc_html__( 'Select category icons style in this block. Access "Theme Options > Theme Design > Entry Category" for global settings, including colors, the total limit to display, and more.', 'foxiz-core' );
	}

	static function entry_category_size_description() {

		return esc_html__( 'Quickly edit the entry category font size. Leave it blank if you want to control additional font values via font settings.', 'foxiz-core' );
	}

	static function entry_meta_description() {

		return esc_html__( 'Enable or disable the post entry meta.', 'foxiz-core' );
	}

	static function entry_meta_tags_description() {

		return esc_html__( 'Input custom entry meta tags to show, separate by comma. e.g. avatar,author,update. Keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom].', 'foxiz-core' );
	}

	static function entry_meta_tags_placeholder() {

		return 'avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom, taxonomy-slug';
	}

	static function podcast_entry_meta_tags_description() {

		return esc_html__( 'Input custom entry meta tags to show, separate by comma. e.g. avatar,author,update. Keys include: [avatar, author, date, category, tag, view, comment, update, read, duration, play].', 'foxiz-core' );
	}

	static function podcast_entry_meta_tags_placeholder() {

		return 'avatar, author, date, category, tag, view, comment, update, read, duration, index, play, taxonomy-slug';
	}

	static function meta_prefix_description() {

		return esc_html__( 'Prefix & Suffix: You can add a prefix or suffix to a meta using the following format: prefix {meta_key} suffix. For example: author, Categories: {category}, view. You can also allow inline HTML tags such as <i>, <span>, etc.', 'foxiz-core' );
	}

	static function meta_flex_description() {

		return esc_html__( 'Taxonomy & Custom Field: Input the "Taxonomy Key" or the "custom field ID" (meta boxes) to display the custom taxonomy or custom field value', 'foxiz-core' );
	}

	static function flex_1_structure_placeholder() {

		return 'title, thumbnail, meta, review, excerpt, readmore';
	}

	static function flex_2_structure_placeholder() {

		return 'category, title, thumbnail, meta, review, excerpt, readmore';
	}

	static function entry_meta_size_description() {

		return esc_html__( 'Input custom font size value for the entry meta of this layout. Leave blank if you would like to set it as the default.', 'foxiz-core' );
	}

	static function avatar_size_description() {

		return esc_html__( 'Input custom avatar size for this layout. Leave blank if you would like to set it as the default (22px).', 'foxiz-core' );
	}

	static function review_description() {

		return esc_html__( 'Disable or select setting for the post review meta.', 'foxiz-core' );
	}

	static function entry_format_description() {

		return esc_html__( 'Enable or disable the post format icon.', 'foxiz-core' );
	}

	static function entry_format_size_description() {

		return esc_html__( 'Input custom font size value for the post format icon of this layout. Leave blank if you would like to set it as the default.', 'foxiz-core' );
	}

	static function excerpt_size_description() {

		return esc_html__( 'Input font size values for the excerpt.', 'foxiz-core' );
	}

	static function excerpt_columns_description() {

		return esc_html__( 'Select columns for the excerpt, This setting will apply to the desktop and tablet devices.', 'foxiz-core' );
	}

	static function review_meta_description() {

		return esc_html__( 'Select a layout or disable the meta description at the end of the review bar.', 'foxiz-core' );
	}

	static function bookmark_description() {

		return esc_html__( 'Enable or disable the bookmark icon. Please make sure at least one entry meta is enabled if you enable it.', 'foxiz-core' );
	}

	static function excerpt_description() {

		return esc_html__( 'Customize the post excerpt.', 'foxiz-core' );
	}

	static function max_excerpt_description() {

		return esc_html__( 'Leave this option blank or set it to 0 to disable the custom excerpt length. Choose "Custom Settings Below" in the above "Excerpt" option to activate this setting.', 'foxiz-core' );
	}

	static function excerpt_source_description() {

		return esc_html__( 'Select a source of content to display for the post excerpt. To activate this setting, choose "Custom Settings Below" in the "Excerpt" option above.', 'foxiz-core' );
	}

	static function readmore_description() {

		return esc_html__( 'Enable or disable the read more button.', 'foxiz-core' );
	}

	static function readmore_size_description() {

		return esc_html__( 'Input custom font sizes for the read more button.', 'foxiz-core' );
	}

	static function columns_description() {

		return esc_html__( 'Select the total number of columns to show per row on desktop devices.', 'foxiz-core' );
	}

	static function columns_tablet_description() {

		return esc_html__( 'Select the total number of columns to show per row on tablet devices.', 'foxiz-core' );
	}

	static function columns_mobile_description() {

		return esc_html__( 'Select the total number of columns to show per row on mobile devices.', 'foxiz-core' );
	}

	static function column_gap_description() {

		return esc_html__( 'Choose a column spacing. Select "Custom" to enter specific values manually.', 'foxiz-core' );
	}

	static function column_gap_custom_description() {

		return esc_html__( 'Input custom gap between columns (in pixels) for desktop, tablet, and mobile devices. The spacing will be 2x your input values.', 'foxiz-core' );
	}

	static function column_border_description() {

		return esc_html__( 'Show vertical borders between columns.', 'foxiz-core' );
	}

	static function pagination_description() {

		return esc_html__( 'Select an AJAX pagination type.', 'foxiz-core' );
	}

	static function unique_info() {

		return esc_html__( 'OFFSET Notice: If you enable the Unique Post, it\'s recommended to leave the "Post Offset" field in Query settings blank. Enabling the offset may cause the beginning posts to be bypassed.', 'foxiz-core' );
	}

	static function unique_description() {

		return esc_html__( 'Avoid duplicate posts that have been queried before this block.', 'foxiz-core' );
	}

	static function dynamic_query_info() {

		return esc_html__( 'If you assign this template to a category, author, tag, or taxonomy page, the dynamic query helps you create featured or additional sections. It filters posts based on the current page where it is displayed.', 'foxiz-core' );
	}

	static function dynamic_tag_info() {

		return esc_html__( 'You can input "{dynamic}" into the "Tags Slug Filter" or "Define Taxonomy" if you want to filter tags or taxonomy dynamically based on the current page.', 'foxiz-core' );
	}

	static function dynamic_render_info() {

		return esc_html__( 'Dynamic query cannot execute in this live editor. The latest posts will be displayed. Your change will be effect when you assign this template to a category page.', 'foxiz-core' );
	}

	static function scroll_description() {

		return esc_html__( 'Enable the scroll bar.', 'foxiz-core' );
	}

	static function scroll_height_description() {

		return esc_html__( 'Input the max block height (in px) when you would like to enable scrollbar. Leave this option blank to disable the scroll bar.', 'foxiz-core' );
	}

	static function color_scheme_description() {

		return esc_html__( 'Select a text color scheme (light or dark) to suit with the background of the block it will be displayed on.', 'foxiz-core' );
	}

	static function box_style_description() {

		return esc_html__( 'Select a box style for the post listing .', 'foxiz-core' );
	}

	static function box_color_description() {

		return esc_html__( 'Select a color for the background or border style.', 'foxiz-core' );
	}

	static function box_dark_color_description() {

		return esc_html__( 'Select a color in dark mode or light scheme mode for the background or border style.', 'foxiz-core' );
	}

	static function custom_font_info_description() {

		return esc_html__( 'The settings below will override on theme option settings and the above font size settings.', 'foxiz-core' );
	}

	static function counter_description() {

		return esc_html__( 'Display counter in the post listing. It will not compatible with the slider or carousel mode.', 'foxiz-core' );
	}

	static function counter_set_description() {

		return esc_html__( 'Set a start value (index -1) for the counter.', 'foxiz-core' );
	}

	static function counter_size_description() {

		return esc_html__( 'Select a style for the divider if you use it.', 'foxiz-core' );
	}

	static function divider_style_description() {

		return esc_html__( 'Input custom font sizes for the counter. Please blank to set it as the default.', 'foxiz-core' );
	}

	static function divider_width_description() {

		return esc_html__( 'Input a custom width (in pixels) for the divider.', 'foxiz-core' );
	}

	static function divider_color_description() {

		return esc_html__( 'Select a color for the divider.', 'foxiz-core' );
	}

	static function divider_dark_color_description() {

		return esc_html__( 'Select a color for the divider in dark mode.', 'foxiz-core' );
	}

	static function hide_divider_description() {

		return esc_html__( 'Hide the divider on tablet and mobile devices.', 'foxiz-core' );
	}

	static function title_size_description() {

		return esc_html__( 'Quickly edit title size. Leave it blank if you want to control additional font values via font settings.', 'foxiz-core' );
	}

	static function title_color_description() {

		return esc_html__( 'Select a color for the post title. The title is set to white in the dark mode.', 'foxiz-core' );
	}

	static function sub_title_size_description() {

		return esc_html__( 'Input custom font size values (in pixels) for the secondary post title for displaying in this block.', 'foxiz-core' );
	}

	static function meta_divider_description() {

		return esc_html__( 'Select a divider style between entry metas.', 'foxiz-core' );
	}

	static function sponsor_meta_description() {

		return esc_html__( 'Enable or disable the "sponsored by" meta for this post listing.', 'foxiz-core' );
	}

	static function hide_category_description() {

		return esc_html__( 'Hide the entry category on tablet and mobile devices.', 'foxiz-core' );
	}

	static function hide_excerpt_description() {

		return esc_html__( 'Hide the post excerpt on tablet and mobile devices.', 'foxiz-core' );
	}

	static function tablet_hide_meta_description() {

		return esc_html__( 'Input the entry meta tags that you want to hide on tablet devices, separated by a comma. e.g. avatar, author. Keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom]. If you want to re-enable all metas input "-1"', 'foxiz-core' );
	}

	static function mobile_hide_meta_description() {

		return esc_html__( 'Input the entry meta tags that you want to hide on mobile devices, separate by comma. e.g. avatar, author Keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom]. If you want to re-enable all metas input "-1"', 'foxiz-core' );
	}

	static function bold_meta_color_description() {

		return esc_html__( 'This setting applies to prominent metas, including author, category, and taxonomy. It also takes precedence over the icon color of taxonomy.', 'foxiz-core' );
	}

	static function slider_mode_description() {

		return esc_html__( 'Display this block in the slider layout if it has more than one post.', 'foxiz-core' );
	}

	static function carousel_info_description() {

		return esc_html__( 'The AJAX pagination will be not available if you activate the carousel mode.', 'foxiz-core' );
	}

	static function carousel_mode_description() {

		return esc_html__( 'Display this block in the carousel layout.', 'foxiz-core' );
	}

	static function carousel_columns_description() {

		return esc_html__( 'Input the total number of slides to show for this carousel. You can also use decimal values such as 2.3, 2.4, etc.', 'foxiz-core' );
	}

	static function wide_carousel_columns_description() {

		return esc_html__( 'Input the total number of slides to display for the carousel on wide screen devices (wider than 1500px).', 'foxiz-core' );
	}

	static function carousel_gap_description() {

		return esc_html__( 'Input custom spacing values between carousel items. The spacing will be the same as your input value. Set "-1" to disable the gap.', 'foxiz-core' );
	}

	static function carousel_dot_description() {

		return esc_html__( 'Enable or disable the pagination dot for this carousel.', 'foxiz-core' );
	}

	static function carousel_nav_description() {

		return esc_html__( 'Enable or disable the next/prev navigation dots for this carousel.', 'foxiz-core' );
	}

	static function carousel_nav_spacing_description() {

		return esc_html__( 'Input custom spacing values (in pixels) for the carousel navigation bar.', 'foxiz-core' );
	}

	static function carousel_autoplay_description() {

		return esc_html__( 'Enable or disable automatic sliding for this slider.', 'foxiz-core' );
	}

	static function carousel_speed_description() {

		return esc_html__( 'Input a custom time (in milliseconds) for the slide transition. Leave blank to use the default setting specified in the Theme Options.', 'foxiz-core' );
	}

	static function carousel_freemode_description() {

		return esc_html__( 'Enable or disable the free mode scrolling for this carousel.', 'foxiz-core' );
	}

	static function carousel_centered_description() {

		return esc_html__( 'Enable centered mode for this carousel in case you set decimal sliders.', 'foxiz-core' );
	}

	static function carousel_nav_color_description() {

		return esc_html__( 'Select a color for the slider navigation at the footer of this carousel.', 'foxiz-core' );
	}

	static function el_spacing_description() {

		return esc_html__( 'Please input custom spacing values (in pixels) between the elements to be displayed.', 'foxiz-core' );
	}

	static function featured_spacing_description() {

		return esc_html__( 'Input custom spacing values (in pixels) between the featured image and other elements.', 'foxiz-core' );
	}

	static function el_margin_description() {

		return esc_html__( 'Input custom bottom margin values (in pixels) between posts in the listing.', 'foxiz-core' );
	}

	static function bottom_border_description() {

		return esc_html__( 'Show borders at the bottom of the post listings. The bottom spacing will be doubled if you enable this option.', 'foxiz-core' );
	}

	static function last_bottom_border_description() {

		return esc_html__( 'Disable border for the last posts in this listing.', 'foxiz-core' );
	}

	static function center_mode_description() {

		return esc_html__( 'Center title and content in the post listing.', 'foxiz-core' );
	}

	static function middle_mode_description() {

		return esc_html__( 'Vertically align elements in the post listing to the middle. This setting will only apply to desktop and tablet devices.', 'foxiz-core' );
	}

	static function border_description() {

		return esc_html__( 'Input a custom border radius (in px) for the featured image or boxed layout. Set 0 to disable it.', 'foxiz-core' );
	}

	static function list_gap_description() {

		return esc_html__( 'Input 1/2 value of the custom gap between the featured image and list post content (in px) for desktop, tablet devices. The spacing will be 2x your input value.', 'foxiz-core' );
	}

	static function template_builder_info() {

		return esc_html__( 'Settings below allow you to apply the global query loop to this block and show it as a the main listing for the index blog, category, archive, single related, reading list etc...', 'foxiz-core' );
	}

	static function template_builder_unique_info() {

		return esc_html__( 'Don\'t apply the WP global query mode for more than one block in a template to avoid duplicated query loop.', 'foxiz-core' );
	}

	static function template_builder_available_info() {

		return esc_html__( 'The "Query Settings" will be not available in the "WP global query" mode.', 'foxiz-core' );
	}

	static function template_builder_pagination_info() {

		return esc_html__( 'Use "WP Global Query Pagination" because the "Ajax Pagination" settings will be not available when you enable "WP global query" mode.', 'foxiz-core' );
	}

	static function template_builder_admin_info() {

		return esc_html__( 'The "WP global query mode" layout cannot execute in this live editor. Please check the frontend to see your changes.', 'foxiz-core' );
	}

	static function template_builder_posts_info() {

		return esc_html__( '"Number of posts" in the frontend will be set in the Theme Options panel (Theme Options > Category, Tags & Archive > Posts per Page). Base on the page has been assigned this template shortcode.', 'foxiz-core' );
	}

	static function template_builder_total_posts_info() {

		return esc_html__( 'Tips: You can change the "Number of Posts" setting in "Query Settings" the same as the frontend (in Theme Options panel). It will help you to easy to edit but that value will not apply in the frontend.', 'foxiz-core' );
	}

	static function column_border_info() {

		return esc_html__( 'The settings below require all responsive column values to be set.', 'foxiz-core' );
	}

	static function template_pagination_description() {

		return esc_html__( 'Ajax pagination types may not be available in some cases (archive and taxonomy pages). depending on where you assigned this template. The theme will automatically return an appropriate setting.', 'foxiz-core' );
	}

	static function query_mode_description() {

		return esc_html__( 'Choose to use the global query or use the "Query settings" panel. Please read the above notices for further information.', 'foxiz-core' );
	}

	static function mobile_layout_description() {

		return esc_html__( 'Convert this layout to a grid or a list for mobile devices.', 'foxiz-core' );
	}

	static function tablet_layout_description() {

		return esc_html__( 'Convert this layout to a grid or a list for tablet devices.', 'foxiz-core' );
	}

	static function mobile_featured_width_description() {

		return esc_html__( 'input custom width values (in pixels) for the featured image in the mobile list mode. Navigate to "Style > Featured Image" to set other values.', 'foxiz-core' );
	}

	static function pagination_style_description() {

		return esc_html__( 'Select a style for the AJAX pagination.', 'foxiz-core' );
	}

	static function pagination_size_description() {

		return esc_html__( 'Input custom font size values for the AJAX pagination.', 'foxiz-core' );
	}

	static function pagination_color_description() {

		return esc_html__( 'Select a color for the AJAX pagination.', 'foxiz-core' );
	}

	static function pagination_accent_color_description() {

		return esc_html__( 'select an accent color (background) for the AJAX pagination.', 'foxiz-core' );
	}

	static function pagination_dark_color_description() {

		return esc_html__( 'Select a color for the AJAX pagination in dark mode.', 'foxiz-core' );
	}

	static function pagination_dark_accent_color_description() {

		return esc_html__( 'select an accent color (background) for the AJAX pagination in dark mode.', 'foxiz-core' );
	}

	static function horizontal_scroll_info() {

		return esc_html__( 'IMPORTANT: This feature is not compatible with AJAX pagination and carousel mode. Please disable it if you are using those features.', 'foxiz-core' );
	}

	static function horizontal_scroll_description() {

		return esc_html__( 'Enable or disable the horizontal scrolling for this block on tablet and mobile devices.', 'foxiz-core' );
	}

	static function scroll_width_tablet_description() {

		return esc_html__( 'Input a width value (in pixels) for the modules on tablet devices.', 'foxiz-core' );
	}

	static function scroll_width_mobile_description() {

		return esc_html__( 'Input a width value (in pixels) for the modules on mobile devices.', 'foxiz-core' );
	}

	static function extend_query_info_description() {

		return esc_html__( 'The settings below allow you to query any taxonomies and post types created by code or third-party plugins.', 'foxiz-core' );
	}

	static function post_type_tax_info_description() {

		return esc_html__( 'Select a taxonomy to display as the entry category in the post listing. Please choose the correct taxonomy for your post type.', 'foxiz-core' );
	}

	static function post_type_query_info_description() {

		return esc_html__( 'The Category or categories filters will not be available if you choose to query a custom post type. Please use the taxonomy settings below to set up your query.', 'foxiz-core' );
	}

	static function podcast_tax_query_info_description() {

		return esc_html__( 'The Show or multiple shows filters will not be available if you choose to query a custom tax.', 'foxiz-core' );
	}

	static function taxonomy_query_description() {

		return esc_html__( 'Input the taxonomy slug/name/key you created via code or a 3rd party plugin. It is the string after "...wp-admin/edit-tags.php?taxonomy=" when you are on the edit page of the taxonomy.', 'foxiz-core' );
	}

	static function post_type_description() {

		return esc_html__( 'Select a custom post type. Default is POST.', 'foxiz-core' );
	}

	static function term_slugs_description() {

		return esc_html__( 'Filter posts by multiple term slugs, separated by commas (e.g., termslug1, termslug2, termslug3). Please ensure the input term slugs belong to the "DEFINE TAXONOMY" above. Leave blank to disable the term slugs filter.', 'foxiz-core' );
	}

	static function display_mode_info() {

		return esc_html__( 'Ajax mode is compatible with cache plugins, while direct mode can improve user experience. However, if you enable direct mode, you will need to exclude this page contain the block from the cache.', 'foxiz-core' );
	}

	static function tax_name_description() {

		return esc_html__( 'The taxonomy slug/name/key is the string after "...wp-admin/edit-tags.php?taxonomy=" when you are on the edit page of the taxonomy.', 'foxiz-core' );
	}

	static function tax_featured_info() {

		return esc_html__( 'To set featured images for each category, tag or taxonomy, navigate to "Posts > Categories, Tags, or Your Taxonomies > Edit > Featured Images".', 'foxiz-core' );
	}

	static function display_mode_description() {

		return esc_html__( 'Select a display mode.', 'foxiz-core' );
	}

	static function taxonomies_followed_description() {

		return esc_html__( 'Show followed categories, post tags or custom taxonomies based on the visitor.', 'foxiz-core' );
	}

	static function tax_slug_followed_description() {

		return esc_html__( 'Input the taxonomy slugs/names/keys separated by commas (e.g., category, post_tag, genre). This setting will take precedence over the above setting; Leave it blank to use the above setting.', 'foxiz-core' );
	}

	static function categories_display_mode_description() {

		return esc_html__( 'Select a display mode. This setting will apply when you enable user followed categories.', 'foxiz-core' );
	}

	static function content_source_description() {

		return esc_html__( 'Select the source content for display.', 'foxiz-core' );
	}

	static function source_post_type_description() {

		return esc_html__( 'Select a post type. The default is POST if "Recommended Based on User Followed" is selected, and ANY if for "User Saved" and "User Read History".', 'foxiz-core' );
	}

	static function reading_history_info() {

		return esc_html__( 'To utilize the user read history query, Make sure to enable the Read History option under "Theme Options > Personalized > Read History"', 'foxiz-core' );
	}

	static function count_posts_description() {

		return esc_html__( 'Toggle the display of total posts information.', 'foxiz-core' );
	}
}