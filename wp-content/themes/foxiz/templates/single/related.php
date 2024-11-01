<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_layout_related_1' ) ) {
	function foxiz_get_layout_related_1( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h4';
		}
		$flag   = true;
		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		} ?>
		<div class="related-sec related-1">
			<div class="inner">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php while ( $_query->have_posts() ) :
						$_query->the_post();
						if ( $flag ) {
							foxiz_list_small_2( [
								'featured_position' => 'right',
								'crop_size'         => 'thumbnail',
								'title_tag'         => 'h5',
								'title_classes'     => 'none-toc',
							] );
							$flag = false;
						} else {
							foxiz_list_inline( [
								'title_tag'     => 'h6',
								'title_classes' => 'none-toc',
							] );
						}
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_layout_related_2' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false
	 */
	function foxiz_get_layout_related_2( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h4';
		}
		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		} ?>
		<div class="related-sec related-2">
			<div class="inner block-list-small-2">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php while ( $_query->have_posts() ) :
						$_query->the_post();
						foxiz_list_small_2( [
							'featured_position' => 'right',
							'crop_size'         => 'thumbnail',
							'title_tag'         => 'h5',
							'title_classes'     => 'none-toc',
						] );
					endwhile;
					wp_reset_postdata();
					?></div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_layout_related_3' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false
	 */
	function foxiz_get_layout_related_3( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h4';
		}
		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		} ?>
		<div class="related-sec related-3">
			<div class="inner block-small block-hrc hrc-1">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php foxiz_loop_hierarchical_1( [
						'title_tag'     => 'h4',
						'sub_title_tag' => 'h6',
						'crop_size'     => 'foxiz_crop_g1',
						'title_classes' => 'none-toc',
					], $_query );
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_layout_related_4' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false
	 */
	function foxiz_get_layout_related_4( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h4';
		}
		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h5';
		}

		$settings['title_classes'] = 'none-toc';

		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		} ?>
		<div class="related-sec related-4">
			<div class="inner">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php while ( $_query->have_posts() ) :
						$_query->the_post();
						foxiz_list_inline( $settings );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_layout_related_5' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false
	 */
	function foxiz_get_layout_related_5( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h3';
		}
		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h4';
		}

		$settings['title_classes'] = 'none-toc';

		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		}
		?>
		<div class="related-sec related-5">
			<div class="inner">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php while ( $_query->have_posts() ) :
						$_query->the_post();
						foxiz_list_inline( $settings );
					endwhile;
					wp_reset_postdata();
					?></div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_layout_related_6' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false
	 */
	function foxiz_get_layout_related_6( $settings ) {

		if ( empty( $settings['post_id'] ) ) {
			$settings['post_id'] = get_the_ID();
		}
		if ( empty( $settings['heading_tag'] ) ) {
			$settings['heading_tag'] = 'h4';
		}
		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h5';
		}

		$_query = foxiz_get_related_data( $settings );
		if ( empty( $_query ) || ! method_exists( $_query, 'have_posts' ) || ! $_query->have_posts() ) {
			return false;
		} ?>
		<div class="related-sec related-6">
			<div class="inner block-grid-small-1 rb-columns rb-col-3 is-gap-10">
				<?php if ( ! empty( $settings['heading'] ) ) {
					echo foxiz_get_heading( [
						'title'    => $settings['heading'],
						'layout'   => $settings['heading_layout'],
						'html_tag' => $settings['heading_tag'],
						'classes'  => 'none-toc',
					] );
				} ?>
				<div class="block-inner">
					<?php foxiz_loop_grid_small_1( [
						'title_tag'       => $settings['title_tag'],
						'columns'         => 3,
						'columns_tablet'  => 3,
						'columns_mobile'  => 1,
						'crop_size'       => 'foxiz_crop_g1',
						'design_override' => true,
						'title_classes'   => 'none-toc',
					], $_query );
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_inline_content_related' ) ) {
	function foxiz_inline_content_related( $data, $content ) {

		if ( empty( $data ) ) {
			$data = [];
		}
		if ( ! is_single() || is_singular( 'product' ) || is_singular('web-story') ) {
			return $data;
		}

		$setting   = rb_get_meta( 'inline_related' );
		$positions = foxiz_get_single_setting( 'inline_related_pos' );

		if ( ( ! empty( $setting ) && '-1' === (string) $setting ) || empty( $positions ) ) {
			return $data;
		}

		$shortcode = trim( foxiz_get_option( 'single_post_inline_related' ) );
		if ( empty( $shortcode ) || false !== strpos( $content, '"related-sec' ) || false !== strpos( $content, 'rb-gutenberg-related' ) ) {
			return $data;
		}

		$positions = array_map( 'absint', explode( ',', $positions ) );
		array_push( $data, [
			'render'    => do_shortcode( $shortcode ),
			'positions' => $positions,
		] );

		return $data;
	}
}