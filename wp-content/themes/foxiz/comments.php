<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

/** comment template */
if ( post_password_required() ) {
	return false;
}

if ( comments_open() || pings_open() ) : ?>
    <div id="comments" class="comments-area">
		<?php if ( have_comments() ) : ?>
            <div class="rb-section">
                <ul class="comment-list entry">
					<?php wp_list_comments( array(
							'avatar_size' => 100,
							'style'       => 'ul',
							'short_ping'  => true,
						)
					); ?>
                </ul>
				<?php the_comments_pagination( array(
						'prev_text' => '<span class="nav-previous">' . foxiz_html__( '&larr; Older Comments', 'foxiz' ) . '</span>',
						'next_text' => '<span class="nav-next">' . foxiz_html__( 'Newer Comments &rarr;', 'foxiz' ) . '</span>',
					)
				); ?>
            </div>
		<?php endif;
		if ( ! comments_open() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
            <p class="no-comments"><?php echo foxiz_html__( 'Comments are closed.', 'foxiz' ); ?></p>
		<?php endif; ?>
		<?php comment_form(); ?>
    </div>
<?php endif;