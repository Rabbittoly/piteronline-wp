<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_single_standard_6' ) ) {
	function foxiz_render_single_standard_6() {

		$classes          = [ 'single-standard-6' ];
		$sidebar_name     = foxiz_get_single_setting( 'sidebar_name' );
		$sidebar_position = foxiz_get_single_sidebar_position();
		$crop_size        = foxiz_get_single_crop_size( 'foxiz_crop_o1' );

		if ( 'none' === $sidebar_position ) {
			$sidebar_name = false;
		}
		if ( empty( $sidebar_name ) || ! is_active_sidebar( $sidebar_name ) ) {
			$classes[] = 'without-sidebar';
		} else {
			$classes[] = 'is-sidebar-' . $sidebar_position;
			$classes[] = foxiz_get_single_sticky_sidebar();
		} ?>
		<div class="<?php echo join( ' ', $classes ); ?>">
			<?php foxiz_single_open_tag(); ?>
			<header class="single-header">
				<div class="rb-wide-container edge-padding">
					<div class="single-header-columns">
						<div class="single-header-left">
							<?php
							foxiz_single_breadcrumb();
							foxiz_single_entry_category();
							foxiz_single_title( 'fw-headline' );
							foxiz_single_tagline();
							foxiz_single_header_meta();
							foxiz_disclosure_box();
							?>
						</div>
						<div class="s-feat-outer">
							<div class="featured-vertical"><?php foxiz_single_featured_image( $crop_size, [ 'class' => 'featured-img' ] ); ?></div>
							<?php if ( foxiz_get_single_featured_caption() ) : ?>
								<div class="caption-holder overlay-text">
									<?php foxiz_single_featured_caption(); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</header>
			<div class="rb-s-container edge-padding">
				<div class="grid-container">
					<div class="s-ct">
						<?php
						foxiz_single_content();
						foxiz_single_author_box();
						foxiz_single_next_prev();
						foxiz_single_comment();
						?>
					</div>
					<?php foxiz_single_sidebar( $sidebar_name ); ?>
				</div>
			</div>
			<?php foxiz_single_close_tag(); ?>
			<div class="single-footer rb-s-container edge-padding">
				<?php foxiz_single_footer(); ?>
			</div>
		</div>
		<?php
	}
}