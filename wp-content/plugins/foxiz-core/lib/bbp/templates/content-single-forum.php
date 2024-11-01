<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="bbpress-forums" class="bbpress-wrapper">

	<?php bbp_breadcrumb();

	the_title( '<h1 class="entry-title">', '</h1>' );
	bbp_get_template_part( 'form', 'search' ); ?>

	<?php do_action( 'bbp_template_before_single_forum' );

	if ( post_password_required() ) : ?>
		<?php bbp_get_template_part( 'form', 'protected' ); ?>
	<?php else : ?>
		<?php bbp_single_forum_description(); ?>
        <div class="bbp-bookmarks h6">
			<?php bbp_forum_subscription_link(); ?>
        </div>
		<?php if ( bbp_has_forums() ) : ?>

			<?php bbp_get_template_part( 'loop', 'forums' ); ?>

		<?php endif; ?>

		<?php if ( ! bbp_is_forum_category() && bbp_has_topics() ) :
			bbp_get_template_part( 'loop', 'topics' );
			bbp_get_template_part( 'pagination', 'topics' );
			ruby_bbp_toggle_topic();
        elseif ( ! bbp_is_forum_category() ) :
			bbp_get_template_part( 'feedback', 'no-topics' );
			ruby_bbp_toggle_topic();
		endif;
	endif; ?>

	<?php do_action( 'bbp_template_after_single_forum' ); ?>

</div>
