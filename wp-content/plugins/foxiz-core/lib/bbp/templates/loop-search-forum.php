<?php

/**
 * Search Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<div id="post-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>
    <div class="bbp-reply-author">
	    <?php if ( has_post_thumbnail() ) : ?>
            <div class="bbp-forum-image"><?php the_post_thumbnail(); ?></div>
	    <?php endif; ?>
        <div class="bbb-reply-author-content">
            <h3><span><a href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a></h3>
            <span class="bbp-forum-post-date is-meta"><?php printf( esc_html__( 'Last updated %s', 'bbpress' ), bbp_get_forum_last_active_time() ); ?></span>
        </div>
    </div>
    <div class="bbp-reply-content rbct">
		<?php do_action( 'bbp_theme_before_forum_content' ); ?>
		<?php bbp_forum_content(); ?>
		<?php do_action( 'bbp_theme_after_forum_content' ); ?>
    </div>

    <div class="bbp-reply-footer meta-text">
        <a href="<?php bbp_forum_permalink(); ?>" data-link="<?php bbp_forum_permalink(); ?>" class="bbp-forum-permalink" class="copy-trigger bbp-reply-permalink bbp-copy-link" data-copy="<?php echo foxiz_attr__( 'Copy Link', 'ruby-bbp' ); ?>" data-copied="<?php echo foxiz_attr__( 'Added to Clipboard', 'ruby-bbp' ); ?>"><i class="bbp-rbi-copy" aria-hidden="true"></i> #<?php bbp_forum_id(); ?>
        </a>
    </div>
</div>
