<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The template for displaying for bbpress pages */
get_header();

if ( have_posts() ) :

	$classes = [ 'ruby-bbp-page' ];
	$sidebar_name = ruby_bbp_get_sidebar_name();
	if ( empty( $sidebar_name ) || ! is_active_sidebar( $sidebar_name ) ) {
		$classes[] = 'without-sidebar';
	} else {
		$classes[] = 'is-sidebar-right sticky-sidebar';
	}
	while ( have_posts() ) : the_post(); ?>
		<div class="<?php echo join( ' ', $classes ); ?>">
			<div class="rb-container edge-padding">
				<div class="bbp-grid-container">
					<div class="s-ct">
						<?php the_content(); ?>
					</div>
					<?php ruby_bbp_sidebar( $sidebar_name ); ?>
				</div>
			</div>
		</div>
	<?php
	endwhile;
endif;

/** get footer */
get_footer();