<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<div id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<div class="bbp-topic-avatar">
		<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>
		<?php echo ruby_bbp_get_topic_author_link( array( 'type' => 'avatar', 'size' => 100 ) ); ?>
		<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>
	</div>
	<div class="bbp-topic-title">

		<?php if ( bbp_is_user_home() ) : ?>
			<?php if ( bbp_is_favorites() ) : ?>
				<span class="bbp-row-actions">
					<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>
					<?php bbp_topic_favorite_link( array(
						'before'    => '',
						'favorite'  => '+',
						'favorited' => '&times;'
					) ); ?>
					<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>
				</span>
			<?php elseif ( bbp_is_subscriptions() ) : ?>
				<span class="bbp-row-actions">
					<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>
					<?php bbp_topic_subscription_link( array(
						'before'      => '',
						'subscribe'   => '+',
						'unsubscribe' => '&times;'
					) ); ?>
					<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>
				</span>
			<?php endif; ?>
		<?php endif; ?>

		<div class="bbp-topic-heading">
			<?php do_action( 'bbp_theme_before_topic_title' ); ?>
			<a class="bbp-topic-permalink h4" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
			<?php do_action( 'bbp_theme_after_topic_title' ); ?>
		</div>
		<span class="bbp-topic-meta is-meta">
            <?php ruby_bbp_topic_status(); ?>
            <span class="bbp-author-meta"><?php printf( foxiz_attr__( 'Started by: %1$s', 'bbpress' ), bbp_get_topic_author_display_name() ); ?></span>
        </span>

		<?php do_action( 'bbp_theme_before_topic_meta' ); ?>

		<?php if ( ! bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>
			<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>
			<span class="bbp-topic-started-in is-meta"><?php printf( foxiz_attr__( 'in: %1$s', 'bbpress' ), '<a href="' . bbp_get_forum_permalink( bbp_get_topic_forum_id() ) . '">' . bbp_get_forum_title( bbp_get_topic_forum_id() ) . '</a>' ); ?></span>
			<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>
		<?php endif; ?>

		<?php do_action( 'bbp_theme_after_topic_meta' ); ?>

		<?php bbp_topic_row_actions(); ?>
	</div>
	<div class="bbp-topic-last-reply">
		<i class="bbp-rbi-reply-fill" aria-hidden="true"></i>
		<span class="bbp-last-reply-author is-meta">
            <?php echo ruby_bbp_get_topic_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => '40' ) ); ?>
            <?php bbp_topic_freshness_link(); ?>
        </span>
	</div>
	<div class="bbp-topic-voice-count"><?php
		$voices = bbp_get_topic_voice_count( 0, true );
		if ( 1 === $voices || 0 === $voices ) {
			$voice_label = foxiz_attr__( 'Voice', 'ruby-bbp' );
		} else {
			$voice_label = foxiz_attr__( 'Voices', 'ruby-bbp' );
		} ?>
		<span class="count-total h5"><?php echo $voices; ?></span>
		<span class="count-label is-meta"><?php echo $voice_label; ?></span>
	</div>
	<div class="bbp-topic-reply-count"><?php
		if ( bbp_show_lead_topic() ) :
			$replies = bbp_get_topic_reply_count( 0, true );
			if ( 1 === $replies || 0 === $replies ) {
				$replies_label = foxiz_attr__( 'Reply', 'ruby-bbp' );
			} else {
				$replies_label = foxiz_attr__( 'Replies', 'ruby-bbp' );
			} ?>
			<span class="count-total h5"><?php echo $replies; ?></span>
			<span class="count-label is-meta"><?php echo $replies_label; ?></span>
		<?php else :
			$posts = bbp_get_topic_post_count( 0, true );
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
