<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<div id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class( 0, 'ruby-single-forum' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
        <div class="bbp-forum-image"><?php the_post_thumbnail(); ?></div>
	<?php endif; ?>

    <div class="bbp-forum-info">

		<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>
            <span class="bbp-row-actions">
				<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>
				<?php bbp_forum_subscription_link( array(
					'before'      => '',
					'subscribe'   => '+',
					'unsubscribe' => '&times;'
				) ); ?>
				<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>
			</span>
		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>

        <a class="bbp-forum-title h3" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>

		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>

        <div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php bbp_list_forums( [
			'before'      => '<div class="bbp-forums-list">',
			'after'       => '</div>',
			'link_before' => '<div class="bbp-forum css-sep h6">',
			'link_after'  => '</div>',
		] ); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

    </div>

    <div class="bbp-forum-freshness">
		<?php echo ruby_bbp_get_author_link( array(
			'post_id' => bbp_get_forum_last_active_id(),
			'size'    => 100,
			'type'    => 'avatar'
		) ); ?>
        <span class="bbp-forum-freshness-inner">
                <span class="h6">
                    	<?php do_action( 'bbp_theme_before_topic_author' ); ?>
	                    <?php bbp_author_link( array(
		                    'post_id' => bbp_get_forum_last_active_id(),
		                    'type'    => 'name'
	                    ) ); ?>
	                    <?php do_action( 'bbp_theme_after_topic_author' ); ?>
                </span>
                <?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>
			<?php bbp_forum_freshness_link(); ?>
			<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>
        </span>
    </div>

    <div class="bbp-forum-topic-count">
		<?php
		$topics = bbp_get_forum_topic_count( 0, true, true );
		if ( 1 === $topics || 0 === $topics ) {
			$label = foxiz_attr__( 'Topic', 'ruby-bbp' );
		} else {
			$label = foxiz_attr__( 'Topics', 'ruby-bbp' );
		} ?>
        <span class="count-total h5"><?php echo $topics; ?></span>
        <span class="count-label is-meta"><?php echo $label; ?></span>
    </div>

    <div class="bbp-forum-reply-count"><?php
		if ( bbp_show_lead_topic() ) :
			$replies = bbp_get_forum_reply_count( 0, true, true );
			if ( 1 === $replies || 0 === $replies ) {
				$replies_label = foxiz_attr__( 'Reply', 'ruby-bbp' );
			} else {
				$replies_label = foxiz_attr__( 'Replies', 'ruby-bbp' );
			} ?>
            <span class="count-total h5"><?php echo $replies; ?></span>
            <span class="count-label is-meta"><?php echo $replies_label; ?></span>
		<?php else :
			$posts = bbp_get_forum_post_count( 0, true, true );
			if ( 1 === $posts || 0 === $posts ) {
				$posts_label = foxiz_attr__( 'Post', 'ruby-bbp' );
			} else {
				$posts_label = foxiz_attr__( 'Posts', 'ruby-bbp' );
			}
			?>
            <span class="count-total h5"><?php echo $posts; ?></span>
            <span class="count-label is-meta"><?php echo $posts_label; ?></span>
		<?php endif; ?>
    </div>
</div>
