<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<div id="post-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

    <div class="bbp-meta bbp-reply-header">
        <a class="bbp-search-topic-link h3" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
        <span class="bbp-topic-title-meta bbp-reply-to-meta">
              <?php if ( function_exists( 'bbp_is_forum_group_forum' ) && bbp_is_forum_group_forum( bbp_get_topic_forum_id() ) ) : ?>
	              <?php esc_html_e( 'in group forum ', 'bbpress' ); ?>
              <?php else : ?>
	              <?php esc_html_e( 'in forum ', 'bbpress' ); ?>
              <?php endif; ?>
                <a href="<?php bbp_forum_permalink( bbp_get_topic_forum_id() ); ?>"><?php bbp_forum_title( bbp_get_topic_forum_id() ); ?></a>
             </span>
    </div>

    <div class="bbp-reply-author">
		<?php echo ruby_bbp_get_topic_author_link( array( 'type' => 'avatar', 'size' => 120 ) ); ?>
        <div class="bbb-reply-author-content">
            <span class="bbp-reply-author-name"><?php echo bbp_get_topic_author_link( array( 'type' => 'name' ) ); ?></span>
			<span class="is-meta"><?php ruby_bbp_topic_status(); ?></span>
            <span class="bbp-reply-post-date is-meta"><?php bbp_topic_post_date( bbp_get_topic_id() ); ?></span>
			<?php if ( bbp_is_user_keymaster() ) : ?>
                <div class="bbp-reply-ip is-meta"><?php bbp_author_ip( bbp_get_topic_id() ); ?></div>
			<?php endif; ?>
        </div>
    </div>

    <div class="bbp-reply-content rbct">
		<?php do_action( 'bbp_theme_before_topic_content' ); ?>
		<?php bbp_topic_content(); ?>
		<?php do_action( 'bbp_theme_after_topic_content' ); ?>
    </div>

</div>