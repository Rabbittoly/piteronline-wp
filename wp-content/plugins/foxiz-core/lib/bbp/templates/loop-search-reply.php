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
<div id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(); ?>>
    <div class="bbp-meta bbp-reply-header">
          <span class="bbp-reply-to-meta">
				<?php esc_html_e( 'in reply to: ', 'bbpress' ); ?>
				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>
    </div>

    <div class="bbp-reply-author">
		<?php echo ruby_bbp_get_reply_author_link( array( 'type' => 'avatar', 'size' => 120 ) ); ?>
        <div class="bbb-reply-author-content">
			<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

            <span class="bbp-reply-author-name">
                <?php bbp_reply_author_link( array( 'type' => 'name' ) ); ?>
				<?php $role = bbp_get_user_display_role( bbp_get_reply_author_id( bbp_get_reply_id() ) ); ?>
                <span class="meta-role is-role-<?php echo strtolower( trim( $role ) ); ?>"><?php echo esc_html( $role ); ?></span>
            </span>
            <span class="bbp-reply-post-date is-meta"><?php bbp_reply_post_date(); ?></span>

	        <?php if ( bbp_is_user_keymaster() ) : ?>
                <div class="bbp-reply-ip is-meta"><?php bbp_author_ip( bbp_get_reply_id() ); ?></div>
	        <?php endif; ?>
			<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>
        </div>
    </div>

    <div class="bbp-reply-content rbct">
		<?php
		do_action( 'bbp_theme_before_reply_content' );
		bbp_reply_content();
		do_action( 'bbp_theme_after_reply_content' );
		?>
    </div>

    <div class="bbp-reply-footer meta-text">
        <a href="<?php bbp_reply_url(); ?>" data-link="<?php bbp_reply_url(); ?>" class="copy-trigger bbp-reply-permalink bbp-copy-link" data-copy="<?php echo foxiz_attr__( 'Copy Link', 'ruby-bbp' ); ?>" data-copied="<?php echo foxiz_attr__( 'Added to Clipboard', 'ruby-bbp' ); ?>"><i class="bbp-rbi-copy" aria-hidden="true"></i> #<?php bbp_reply_id(); ?></a>
    </div>

</div>
