<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="bbpress-forums" class="bbpress-wrapper">

	<?php bbp_get_template_part( 'form', 'search' ); ?>

	<?php bbp_forum_subscription_link(); ?>

	<?php do_action( 'bbp_template_before_forums_index' ); ?>

	<?php if ( bbp_has_forums() ) : ?>
		<?php bbp_get_template_part( 'loop', 'forums' ); ?>
		<?php if ( get_option( 'ruby_bbp_topic_forums', 1 ) ) {
			ruby_bbp_toggle_topic();
		} ?>
	<?php else : ?>
		<?php bbp_get_template_part( 'feedback', 'no-forums' ); ?>

	<?php endif; ?>
	<?php do_action( 'bbp_template_after_forums_index' ); ?>
</div>
