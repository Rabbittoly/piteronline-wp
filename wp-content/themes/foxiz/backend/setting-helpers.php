<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_asset_image' ) ) {
	function foxiz_get_asset_image( $file ) {

		return foxiz_get_file_uri( 'backend/assets/' . $file );
	}
}

if ( ! function_exists( 'foxiz_config_sidebar_name' ) ) {
	function foxiz_config_sidebar_name( $default = true ) {

		$sidebar_data = [];
		$settings     = foxiz_get_option();

		if ( $default ) {
			$sidebar_data['default'] = esc_html__( '- Default -', 'foxiz' );
		}
		$sidebar_data['foxiz_sidebar_default'] = esc_html__( 'Standard Sidebar', 'foxiz' );
		if ( ! empty( $settings['multi_sidebars'] ) && is_array( $settings['multi_sidebars'] ) ) {
			foreach ( $settings['multi_sidebars'] as $sidebar ) {
				$id                  = 'foxiz_ms_' . foxiz_convert_to_id( trim( $sidebar ) );
				$sidebar_data[ $id ] = $sidebar;
			}
		}

		return $sidebar_data;
	}
}

if ( ! function_exists( 'foxiz_config_header_style' ) ) {
	function foxiz_config_header_style( $default = false, $transparent = false, $template = false, $no_header = false ) {

		$settings = [
			'0' => esc_html__( '- Default -', 'foxiz' ),
			'1' => esc_html__( 'Layout 1 (Left Menu)', 'foxiz' ),
			'2' => esc_html__( 'Layout 2 (Right Menu)', 'foxiz' ),
			'3' => esc_html__( 'Layout 3 (Center Menu)', 'foxiz' ),
			'4' => esc_html__( 'Layout 4 (Border)', 'foxiz' ),
			'5' => esc_html__( 'Layout 5 (Center Logo)', 'foxiz' ),
		];

		if ( $transparent ) {
			$settings['t1'] = esc_html__( 'Transparent - Layout 1', 'foxiz' );
			$settings['t2'] = esc_html__( 'Transparent - Layout 2', 'foxiz' );
			$settings['t3'] = esc_html__( 'Transparent - Layout 3', 'foxiz' );
		}

		if ( $no_header ) {
			$settings['none']        = esc_html__( 'No Header on Desktop, Mobile Header Only', 'foxiz' );
			$settings['none_mobile'] = esc_html__( 'No Header on Any Device', 'foxiz' );
		}

		if ( $template ) {
			$settings['rb_template'] = esc_html__( 'Use Ruby Template', 'foxiz' );
		}

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_heading_layout' ) ) {
	function foxiz_config_heading_layout( $default = false ) {

		$settings = [
			'0'   => esc_html__( '- Default -', 'foxiz' ),
			'1'   => esc_html__( '01 - Two Slashes', 'foxiz' ),
			'2'   => esc_html__( '02 - Left Dot', 'foxiz' ),
			'3'   => esc_html__( '03 - Bold Underline', 'foxiz' ),
			'4'   => esc_html__( '04 - Multiple Underline', 'foxiz' ),
			'5'   => esc_html__( '05 - Top Line', 'foxiz' ),
			'6'   => esc_html__( '06 - Parallelogram Background', 'foxiz' ),
			'7'   => esc_html__( '07 - Left Border', 'foxiz' ),
			'8'   => esc_html__( '08 - Half Elegant Background', 'foxiz' ),
			'9'   => esc_html__( '09 - Small Corners', 'foxiz' ),
			'10'  => esc_html__( '10 - Only Text', 'foxiz' ),
			'11'  => esc_html__( '11 - Big Tagline Overlay', 'foxiz' ),
			'12'  => esc_html__( '12 - Mixed Underline', 'foxiz' ),
			'13'  => esc_html__( '13 - Rectangle Background', 'foxiz' ),
			'14'  => esc_html__( '14 - Top Solid', 'foxiz' ),
			'15'  => esc_html__( '15 - Top & Bottom Solid', 'foxiz' ),
			'16'  => esc_html__( '16 - Mixed Background', 'foxiz' ),
			'17'  => esc_html__( '17 - Centered Solid', 'foxiz' ),
			'18'  => esc_html__( '18 - Centered Dotted', 'foxiz' ),
			'19'  => esc_html__( '19 - Line Break for Tagline', 'foxiz' ),
			'c1'  => esc_html__( 'Center 01 - Two Slashes', 'foxiz' ),
			'c2'  => esc_html__( 'Center 02 - Two Dots', 'foxiz' ),
			'c3'  => esc_html__( 'Center 03 - Underline', 'foxiz' ),
			'c4'  => esc_html__( 'Center 04 - Bold Underline', 'foxiz' ),
			'c5'  => esc_html__( 'Center 05 - Top Line', 'foxiz' ),
			'c6'  => esc_html__( 'Center 06 - Parallelogram Background', 'foxiz' ),
			'c7'  => esc_html__( 'Center 07 - Two Square Dots', 'foxiz' ),
			'c8'  => esc_html__( 'Center 08 - Elegant Lines', 'foxiz' ),
			'c9'  => esc_html__( 'Center 09 - Small Corners', 'foxiz' ),
			'c10' => esc_html__( 'Center 10 - Only Text', 'foxiz' ),
			'c11' => esc_html__( 'Center 11 - Big Tagline Overlay', 'foxiz' ),
			'c12' => esc_html__( 'Center 12 - Mixed Underline', 'foxiz' ),
			'c13' => esc_html__( 'Center 13 - Rectangle Background', 'foxiz' ),
			'c14' => esc_html__( 'Center 14 - Top Solid', 'foxiz' ),
			'c15' => esc_html__( 'Center 15 - Top & Bottom Solid', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_sidebar_position' ) ) {
	function foxiz_config_sidebar_position( $default = true, $none = true ) {

		if ( ! is_admin() ) {
			return false;
		}

		$sidebars = [];
		if ( true === $default ) {
			$sidebars['default'] = [
				'alt'   => '- Default -',
				'img'   => foxiz_get_asset_image( 'sidebar-default.png' ),
				'title' => esc_html__( 'Default', 'foxiz' ),
			];
		};
		if ( true === $none ) {
			$sidebars['none'] = [
				'alt'   => 'none',
				'img'   => foxiz_get_asset_image( 'sidebar-none.png' ),
				'title' => esc_html__( 'No Sidebar', 'foxiz' ),
			];
		};

		$sidebars['left'] = [
			'alt'   => 'left sidebar',
			'img'   => foxiz_get_asset_image( 'sidebar-left.png' ),
			'title' => esc_html__( 'Left', 'foxiz' ),
		];

		$sidebars['right'] = [
			'alt'   => 'right sidebar',
			'img'   => foxiz_get_asset_image( 'sidebar-right.png' ),
			'title' => esc_html__( 'Right', 'foxiz' ),
		];

		return $sidebars;
	}
}

if ( ! function_exists( 'foxiz_config_switch_dropdown' ) ) {
	function foxiz_config_switch_dropdown() {

		return [
			'0'  => esc_html__( '- Default -', 'foxiz' ),
			'1'  => esc_html__( 'Enable', 'foxiz' ),
			'-1' => esc_html__( 'Disable', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_excerpt_dropdown' ) ) {
	function foxiz_config_excerpt_dropdown() {

		return [
			'0' => esc_html__( '- Default -', 'foxiz' ),
			'1' => esc_html__( 'Custom Settings Below', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_excerpt_source' ) ) {
	function foxiz_config_excerpt_source() {

		return [
			'0'       => esc_html__( 'Use Post Excerpt', 'foxiz' ),
			'tagline' => esc_html__( 'Use Title Tagline', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_heading_tag' ) ) {
	function foxiz_config_heading_tag() {

		return [
			'0'    => esc_html__( '- Default -', 'foxiz' ),
			'h1'   => esc_html__( 'H1', 'foxiz' ),
			'h2'   => esc_html__( 'H2', 'foxiz' ),
			'h3'   => esc_html__( 'H3', 'foxiz' ),
			'h4'   => esc_html__( 'H4', 'foxiz' ),
			'h5'   => esc_html__( 'H5', 'foxiz' ),
			'h6'   => esc_html__( 'H6', 'foxiz' ),
			'p'    => esc_html__( 'p', 'foxiz' ),
			'span' => esc_html__( 'span', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_hide_dropdown' ) ) {
	function foxiz_config_hide_dropdown() {

		return [
			'0'      => esc_html__( '- Disable -', 'foxiz' ),
			'mobile' => esc_html__( 'On Mobile', 'foxiz' ),
			'tablet' => esc_html__( 'On Tablet', 'foxiz' ),
			'all'    => esc_html__( 'On Tablet & Mobile', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_menu_slug' ) ) {
	function foxiz_config_menu_slug() {

		$settings = [];
		$menus    = wp_get_nav_menus();
		if ( ! empty ( $menus ) ) {
			foreach ( $menus as $item ) {
				$settings[ $item->slug ] = $item->name;
			}
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_standard_entry_category' ) ) {
	function foxiz_config_standard_entry_category( $default = false ) {

		$settings = [
			'0'        => esc_html__( '- Default -', 'foxiz' ),
			'bg-1'     => esc_html__( 'Background 1', 'foxiz' ),
			'bg-1,big' => esc_html__( 'Background 1 (Big)', 'foxiz' ),
			'bg-2'     => esc_html__( 'Background 2', 'foxiz' ),
			'bg-2,big' => esc_html__( 'Background 2 (Big)', 'foxiz' ),
			'bg-3'     => esc_html__( 'Background 3', 'foxiz' ),
			'bg-3,big' => esc_html__( 'Background 3 (Big)', 'foxiz' ),
			'bg-4'     => esc_html__( 'Background 4', 'foxiz' ),
			'bg-4,big' => esc_html__( 'Background 4 (Big)', 'foxiz' ),
			'-1'       => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
			unset( $settings['-1'] );
			$settings['0'] = esc_html__( 'Disable', 'foxiz' );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_extended_entry_category' ) ) {
	function foxiz_config_extended_entry_category( $default = false ) {

		$settings = [
			'0'            => esc_html__( '- Default -', 'foxiz' ),
			'bg-1'         => esc_html__( 'Background 1', 'foxiz' ),
			'bg-1,big'     => esc_html__( 'Background 1 (Big)', 'foxiz' ),
			'bg-2'         => esc_html__( 'Background 2', 'foxiz' ),
			'bg-2,big'     => esc_html__( 'Background 2 (Big)', 'foxiz' ),
			'bg-3'         => esc_html__( 'Background 3', 'foxiz' ),
			'bg-3,big'     => esc_html__( 'Background 3 (Big)', 'foxiz' ),
			'bg-4'         => esc_html__( 'Background 4', 'foxiz' ),
			'bg-4,big'     => esc_html__( 'Background 4 (Big)', 'foxiz' ),
			'text'         => esc_html__( 'Only Text', 'foxiz' ),
			'text,big'     => esc_html__( 'Only Text (Big)', 'foxiz' ),
			'border'       => esc_html__( 'Border', 'foxiz' ),
			'border,big'   => esc_html__( 'Border (Big)', 'foxiz' ),
			'b-dotted'     => esc_html__( 'Bottom Dotted', 'foxiz' ),
			'b-dotted,big' => esc_html__( 'Bottom Dotted (Big)', 'foxiz' ),
			'b-border'     => esc_html__( 'Bottom Border', 'foxiz' ),
			'b-border,big' => esc_html__( 'Bottom Border (Big)', 'foxiz' ),
			'l-dot'        => esc_html__( 'Left Dot', 'foxiz' ),
			'-1'           => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
			unset( $settings['-1'] );
			$settings['0'] = esc_html__( 'Disable', 'foxiz' );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_entry_meta_bar' ) ) {
	function foxiz_config_entry_meta_bar() {

		return [
			'0'      => esc_html__( '- Default -', 'foxiz' ),
			'-1'     => esc_html__( 'Disable', 'foxiz' ),
			'custom' => esc_html__( 'Use Custom', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_entry_format' ) ) {
	function foxiz_config_entry_format( $default = false ) {

		$settings = [
			'0'              => esc_html__( '- Default -', 'foxiz' ),
			'bottom'         => esc_html__( 'Bottom Right', 'foxiz' ),
			'bottom,big'     => esc_html__( 'Bottom Right (Big Icon)', 'foxiz' ),
			'top'            => esc_html__( 'Top', 'foxiz' ),
			'top,big'        => esc_html__( 'Top (Big Icon)', 'foxiz' ),
			'after-category' => esc_html__( 'After Entry Category', 'foxiz' ),
			'center'         => esc_html__( 'Center', 'foxiz' ),
			'center,big'     => esc_html__( 'Center (Big Icon)', 'foxiz' ),
			'-1'             => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
			unset( $settings['-1'] );
			$settings['0'] = esc_html__( 'Disable', 'foxiz' );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_entry_meta_tags' ) ) {
	function foxiz_config_entry_meta_tags() {

		return [
			'avatar'    => esc_html__( 'avatar (Avatar)', 'foxiz' ),
			'author'    => esc_html__( 'author (Author)', 'foxiz' ),
			'date'      => esc_html__( 'date (Publish Date)', 'foxiz' ),
			'category'  => esc_html__( 'category (Categories)', 'foxiz' ),
			'tag'       => esc_html__( 'tag (Tags)', 'foxiz' ),
			'view'      => esc_html__( 'view (Post Views)', 'foxiz' ),
			'comment'   => esc_html__( 'comment (Comments)', 'foxiz' ),
			'update'    => esc_html__( 'update  (Last Updated)', 'foxiz' ),
			'read'      => esc_html__( 'read (Reading Time)', 'foxiz' ),
			'bookmark'  => esc_html__( 'bookmark (Bookmark)', 'foxiz' ),
			'like'      => esc_html__( 'like (Like/Dislike)', 'foxiz' ),
			'custom'    => esc_html__( 'custom (Custom)', 'foxiz' ),
			'_disabled' => esc_html__( 'Disabled', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_entry_review' ) ) {
	function foxiz_config_entry_review( $default = false ) {

		$settings = [
			'0'       => esc_html__( '- Default -', 'foxiz' ),
			'1'       => esc_html__( 'Enable', 'foxiz' ),
			'replace' => esc_html__( 'Replace for Entry Meta', 'foxiz' ),
			'-1'      => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
			unset( $settings['-1'] );
			$settings['0'] = esc_html__( 'Disable', 'foxiz' );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_blog_layout' ) ) {
	function foxiz_config_blog_layout() {

		return [
			'classic_1'    => [
				'img'   => foxiz_get_asset_image( 'classic-1.jpg' ),
				'title' => esc_html__( 'Classic', 'foxiz' ),
			],
			'grid_1'       => [
				'img'   => foxiz_get_asset_image( 'grid-1.jpg' ),
				'title' => esc_html__( 'Grid 1', 'foxiz' ),
			],
			'grid_2'       => [
				'img'   => foxiz_get_asset_image( 'grid-1.jpg' ),
				'title' => esc_html__( 'Grid 2', 'foxiz' ),
			],
			'grid_box_1'   => [
				'img'   => foxiz_get_asset_image( 'grid-box-1.jpg' ),
				'title' => esc_html__( 'Boxed Grid 1', 'foxiz' ),
			],
			'grid_box_2'   => [
				'img'   => foxiz_get_asset_image( 'grid-box-2.jpg' ),
				'title' => esc_html__( 'Boxed Grid 2', 'foxiz' ),
			],
			'grid_small_1' => [
				'img'   => foxiz_get_asset_image( 'grid-small-1.jpg' ),
				'title' => esc_html__( 'Small Grid', 'foxiz' ),
			],
			'list_1'       => [
				'img'   => foxiz_get_asset_image( 'list-1.jpg' ),
				'title' => esc_html__( 'List 1', 'foxiz' ),
			],
			'list_2'       => [
				'img'   => foxiz_get_asset_image( 'list-2.jpg' ),
				'title' => esc_html__( 'List 2', 'foxiz' ),
			],
			'list_box_1'   => [
				'img'   => foxiz_get_asset_image( 'list-box-1.jpg' ),
				'title' => esc_html__( 'Boxed List 1', 'foxiz' ),
			],
			'list_box_2'   => [
				'img'   => foxiz_get_asset_image( 'list-box-2.jpg' ),
				'title' => esc_html__( 'Boxed List 2', 'foxiz' ),
			],
		];
	}
}

if ( ! function_exists( 'foxiz_config_blog_columns' ) ) {
	function foxiz_config_blog_columns( $configs = [] ) {

		$settings = [];
		$default  = [
			'0' => esc_html__( '- Default -', 'foxiz' ),
			'1' => esc_html__( '1 Column', 'foxiz' ),
			'2' => esc_html__( '2 Columns', 'foxiz' ),
			'3' => esc_html__( '3 Columns', 'foxiz' ),
			'4' => esc_html__( '4 Columns', 'foxiz' ),
			'5' => esc_html__( '5 Columns', 'foxiz' ),
			'6' => esc_html__( '6 Columns', 'foxiz' ),
			'7' => esc_html__( '7 Columns', 'foxiz' ),
		];

		if ( ! is_array( $configs ) || ! count( $configs ) ) {
			return $default;
		}
		foreach ( $configs as $item ) {
			$settings[ $item ] = $default[ $item ];
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_blog_column_gap' ) ) {
	function foxiz_config_blog_column_gap() {

		return [
			'0'    => esc_html__( '- Default -', 'foxiz' ),
			'none' => esc_html__( 'No Gap', 'foxiz' ),
			'5'    => esc_html__( '10px', 'foxiz' ),
			'7'    => esc_html__( '14px', 'foxiz' ),
			'10'   => esc_html__( '20px', 'foxiz' ),
			'15'   => esc_html__( '30px', 'foxiz' ),
			'20'   => esc_html__( '40px', 'foxiz' ),
			'25'   => esc_html__( '50px', 'foxiz' ),
			'30'   => esc_html__( '60px', 'foxiz' ),
			'35'   => esc_html__( '70px', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_category_sidebar_position' ) ) {
	function foxiz_config_category_sidebar_position() {

		return [
			'0'     => esc_html__( '- Default -', 'foxiz' ),
			'none'  => esc_html__( 'No Sidebar', 'foxiz' ),
			'left'  => esc_html__( 'Left', 'foxiz' ),
			'right' => esc_html__( 'Right', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_blog_pagination' ) ) {
	function foxiz_config_blog_pagination( $default = false ) {

		$settings = [
			'0'               => esc_html__( '- Default -', 'foxiz' ),
			'number'          => esc_html__( 'Numeric', 'foxiz' ),
			'simple'          => esc_html__( 'Simple', 'foxiz' ),
			'load_more'       => esc_html__( 'Load More (Ajax)', 'foxiz' ),
			'infinite_scroll' => esc_html__( 'Infinite Scroll (Ajax)', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_category_header' ) ) {
	function foxiz_config_category_header( $default = false ) {

		$settings = [
			'0'    => esc_html__( '- Default -', 'foxiz' ),
			'1'    => esc_html__( 'Layout 1 (Right Featured Image)', 'foxiz' ),
			'2'    => esc_html__( 'Layout 2 (Background Image)', 'foxiz' ),
			'3'    => esc_html__( 'Layout 3 (Minimalist)', 'foxiz' ),
			'4'    => esc_html__( 'Layout 4 (Minimalist Center)', 'foxiz' ),
			'none' => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_archive_header' ) ) {
	function foxiz_config_archive_header( $default = false ) {

		$settings = [
			'0'    => esc_html__( '- Default -', 'foxiz' ),
			'1'    => esc_html__( 'Left', 'foxiz' ),
			'2'    => esc_html__( 'Center', 'foxiz' ),
			'none' => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_archive_header_bg' ) ) {
	function foxiz_config_archive_header_bg( $default = false ) {

		$settings = [
			'0'         => esc_html__( '- Default -', 'foxiz' ),
			'dot'       => esc_html__( 'Pattern Dotted', 'foxiz' ),
			'dot2'      => esc_html__( 'Pattern Dotted 2', 'foxiz' ),
			'diagonal'  => esc_html__( 'Pattern Diagonal', 'foxiz' ),
			'diagonal2' => esc_html__( 'Pattern Diagonal 2', 'foxiz' ),
			'-1'        => esc_html__( 'Solid Light Gray', 'foxiz' ),
		];
		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_single_standard_layouts' ) ) {
	function foxiz_config_single_standard_layouts( $default = true ) {

		$settings = [
			'default'     => [
				'img'   => foxiz_get_asset_image( 'default.png' ),
				'title' => esc_html__( '- Default -', 'foxiz' ),
			],
			'standard_1'  => [
				'img'   => foxiz_get_asset_image( 'single-1.png' ),
				'title' => esc_html__( 'Layout 1', 'foxiz' ),
			],
			'standard_2'  => [
				'img'   => foxiz_get_asset_image( 'single-2.png' ),
				'title' => esc_html__( 'Layout 2', 'foxiz' ),
			],
			'standard_3'  => [
				'img'   => foxiz_get_asset_image( 'single-3.png' ),
				'title' => esc_html__( 'Layout 3', 'foxiz' ),
			],
			'standard_4'  => [
				'img'   => foxiz_get_asset_image( 'single-4.png' ),
				'title' => esc_html__( 'Layout 4', 'foxiz' ),
			],
			'standard_5'  => [
				'img'   => foxiz_get_asset_image( 'single-5.png' ),
				'title' => esc_html__( 'Layout 5', 'foxiz' ),
			],
			'standard_6'  => [
				'img'   => foxiz_get_asset_image( 'single-6.png' ),
				'title' => esc_html__( 'Layout 6', 'foxiz' ),
			],
			'standard_7'  => [
				'img'   => foxiz_get_asset_image( 'single-7.png' ),
				'title' => esc_html__( 'Layout 7', 'foxiz' ),
			],
			'standard_8'  => [
				'img'   => foxiz_get_asset_image( 'single-8.png' ),
				'title' => esc_html__( 'Layout 8', 'foxiz' ),
			],
			'standard_9'  => [
				'img'   => foxiz_get_asset_image( 'single-9.png' ),
				'title' => esc_html__( 'Layout 9 (No Featured)', 'foxiz' ),
			],
			'standard_10' => [
				'img'   => foxiz_get_asset_image( 'single-10.png' ),
				'title' => esc_html__( 'Layout 10', 'foxiz' ),
			],
			'standard_11' => [
				'img'   => foxiz_get_asset_image( 'single-11.png' ),
				'title' => esc_html__( 'Layout 11', 'foxiz' ),
			],
		];

		if ( ! $default ) {
			unset( $settings['default'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_single_video_layouts' ) ) {
	function foxiz_config_single_video_layouts( $default = true ) {

		$settings = [
			'default' => [
				'img'   => foxiz_get_asset_image( 'default.png' ),
				'title' => esc_html__( '- Default -', 'foxiz' ),
			],
			'video_1' => [
				'img'   => foxiz_get_asset_image( 'single-video-1.png' ),
				'title' => esc_html__( 'Layout 1', 'foxiz' ),
			],
			'video_2' => [
				'img'   => foxiz_get_asset_image( 'single-video-2.png' ),
				'title' => esc_html__( 'Layout 2', 'foxiz' ),
			],
			'video_3' => [
				'img'   => foxiz_get_asset_image( 'single-video-3.png' ),
				'title' => esc_html__( 'Layout 3', 'foxiz' ),
			],
			'video_4' => [
				'img'   => foxiz_get_asset_image( 'single-video-4.png' ),
				'title' => esc_html__( 'Layout 4', 'foxiz' ),
			],
		];

		if ( ! $default ) {
			unset( $settings['default'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_single_audio_layouts' ) ) {
	function foxiz_config_single_audio_layouts( $default = true ) {

		$settings = [
			'default' => [
				'img'   => foxiz_get_asset_image( 'default.png' ),
				'title' => esc_html__( '- Default -', 'foxiz' ),
			],
			'audio_1' => [
				'img'   => foxiz_get_asset_image( 'single-audio-1.png' ),
				'title' => esc_html__( 'Layout 1', 'foxiz' ),
			],
			'audio_2' => [
				'img'   => foxiz_get_asset_image( 'single-audio-2.png' ),
				'title' => esc_html__( 'Layout 2', 'foxiz' ),
			],
			'audio_3' => [
				'img'   => foxiz_get_asset_image( 'single-audio-3.png' ),
				'title' => esc_html__( 'Layout 3', 'foxiz' ),
			],
			'audio_4' => [
				'img'   => foxiz_get_asset_image( 'single-audio-4.png' ),
				'title' => esc_html__( 'Layout 4', 'foxiz' ),
			],
		];

		if ( ! $default ) {
			unset( $settings['default'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_single_gallery_layouts' ) ) {
	function foxiz_config_single_gallery_layouts( $default = true ) {

		$settings = [
			'default'   => [
				'img'   => foxiz_get_asset_image( 'default.png' ),
				'title' => esc_html__( '- Default -', 'foxiz' ),
			],
			'gallery_1' => [
				'img'   => foxiz_get_asset_image( 'single-gallery-1.png' ),
				'title' => esc_html__( 'Layout 1', 'foxiz' ),
			],
			'gallery_2' => [
				'img'   => foxiz_get_asset_image( 'single-gallery-2.png' ),
				'title' => esc_html__( 'Layout 2', 'foxiz' ),
			],
			'gallery_3' => [
				'img'   => foxiz_get_asset_image( 'single-gallery-3.png' ),
				'title' => esc_html__( 'Layout 3', 'foxiz' ),
			],
		];
		if ( ! $default ) {
			unset( $settings['default'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_crop_size' ) ) {
	function foxiz_config_crop_size() {

		$sizes    = foxiz_calc_crop_sizes();
		$settings = [ '0' => esc_html__( '- Default -', 'foxiz' ) ];

		foreach ( $sizes as $size => $data ) {
			if ( isset( $data[0] ) && isset( $data[1] ) ) {
				$settings[ $size ] = $data[0] . 'x' . $data[1];
			}
		}

		$settings['thumbnail']    = esc_html__( 'Thumbnail (Core WP)', 'foxiz' );
		$settings['medium']       = esc_html__( 'Medium (Core WP)', 'foxiz' );
		$settings['medium_large'] = esc_html__( 'Medium Large (Core WP)', 'foxiz' );
		$settings['large']        = esc_html__( 'Large (Core WP)', 'foxiz' );
		$settings['1536x1536']    = esc_html__( '1536x1536 (Core WP)', 'foxiz' );
		$settings['2048x2048']    = esc_html__( '2048x2048 (Core WP)', 'foxiz' );

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_featured_position' ) ) {
	function foxiz_config_featured_position( $default = false ) {

		$settings = [
			'0'     => esc_html__( '- Default -', 'foxiz' ),
			'left'  => esc_html__( 'Left', 'foxiz' ),
			'right' => esc_html__( 'Right', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings['0'] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_ad_size_dropdown' ) ) {
	function foxiz_ad_size_dropdown() {

		return [
			'1'  => esc_html__( 'Leaderboard (728x90)', 'foxiz' ),
			'2'  => esc_html__( 'Banner (468x60)', 'foxiz' ),
			'3'  => esc_html__( 'Half banner (234x60)', 'foxiz' ),
			'4'  => esc_html__( 'Button (125x125)', 'foxiz' ),
			'5'  => esc_html__( 'Skyscraper (120x600)', 'foxiz' ),
			'6'  => esc_html__( 'Wide Skyscraper (160x600)', 'foxiz' ),
			'7'  => esc_html__( 'Small Rectangle (180x150)', 'foxiz' ),
			'8'  => esc_html__( 'Vertical Banner (120 x 240)', 'foxiz' ),
			'9'  => esc_html__( 'Small Square (200x200)', 'foxiz' ),
			'10' => esc_html__( 'Square (250x250)', 'foxiz' ),
			'11' => esc_html__( 'Medium Rectangle (300x250)', 'foxiz' ),
			'12' => esc_html__( 'Large Rectangle (336x280)', 'foxiz' ),
			'13' => esc_html__( 'Half Page (300x600)', 'foxiz' ),
			'14' => esc_html__( 'Portrait (300x1050)', 'foxiz' ),
			'15' => esc_html__( 'Mobile Banner (320x50)', 'foxiz' ),
			'16' => esc_html__( 'Large Leaderboard (970x90)', 'foxiz' ),
			'17' => esc_html__( 'Billboard (970x250)', 'foxiz' ),
			'18' => esc_html__( 'Mobile Banner (320x100)', 'foxiz' ),
			'19' => esc_html__( 'Mobile Friendly (300x100)', 'foxiz' ),
			'-1' => esc_html__( 'Hide on Desktop', 'foxiz' ),
		];
	}
}

if ( ! function_exists( 'foxiz_config_box_style' ) ) {
	function foxiz_config_box_style( $default = false ) {

		$settings = [
			'0'      => esc_html__( '- None -', 'foxiz' ),
			'bg'     => esc_html__( 'Background', 'foxiz' ),
			'border' => esc_html__( 'Border', 'foxiz' ),
			'shadow' => esc_html__( 'Shadow', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_review_desc_dropdown' ) ) {
	function foxiz_config_review_desc_dropdown( $default = true ) {

		$settings = [
			'0'  => esc_html__( '- Default -', 'foxiz' ),
			'1'  => esc_html__( 'No Wrap', 'foxiz' ),
			'2'  => esc_html__( 'Desktop No Wrap - Mobile Line Wrap', 'foxiz' ),
			'3'  => esc_html__( 'Line Wrap', 'foxiz' ),
			'4'  => esc_html__( 'No Wrap (Show Score Only)', 'foxiz' ),
			'5'  => esc_html__( 'Line Wrap (Show Score Only)', 'foxiz' ),
			'-1' => esc_html__( 'Disable', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_config_page_header_dropdown' ) ) {
	function foxiz_config_page_header_dropdown( $default = true ) {

		$settings = [
			'0'  => esc_html__( '- Default -', 'foxiz' ),
			'1'  => esc_html__( 'Left Heading', 'foxiz' ),
			'2'  => esc_html__( 'Overlay Heading (Featured image required)', 'foxiz' ),
			'3'  => esc_html__( 'Overlay Center Heading (Featured image required)', 'foxiz' ),
			'4'  => esc_html__( 'Wrapper Overlay Heading (Require featured image)', 'foxiz' ),
			'-1' => esc_html__( 'No Header', 'foxiz' ),
		];

		if ( ! $default ) {
			unset( $settings[0] );
		}

		return $settings;
	}
}

if ( ! function_exists( 'foxiz_get_post_types_list' ) ) {
	function foxiz_get_post_types_list( $disabled = [] ) {

		$args       = [ 'public' => true ];
		$post_types = get_post_types( $args, 'objects' );

		unset( $post_types['post'], $post_types['page'], $post_types['attachment'], $post_types['podcast'], $post_types['rb-etemplate'], $post_types['product'], $post_types['e-landing-page'], $post_types['elementor_library'] );

		if ( ! empty( $disabled ) ) {
			foreach ( $disabled as $type ) {
				unset( $post_types[ $type ] );
			}
		}

		return $post_types;
	}
}