<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_topics_loop' ); ?>
    <ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">
        <li class="bbp-header">
            <div class="bbp-topic-title"><?php esc_html_e( 'Topics', 'bbpress' ); ?></div>
            <div class="bbp-topic-last-reply"><i class="bbp-rbi-discussion" aria-hidden="true"></i><?php echo foxiz_attr__( 'Last Reply', 'ruby-bbp' ); ?></div>
            <div class="bbp-topic-voice-count"><i class="bbp-rbi-people" aria-hidden="true"></i></div>
            <div class="bbp-topic-reply-count"><?php
				if ( bbp_show_lead_topic() ) {
					echo '<i class="bbp-rbi-discussion" aria-hidden="true"></i>';
				} else {
					echo '<i class="bbp-rbi-file" aria-hidden="true"></i>';
				}
				?></div>
        </li>
        <li class="bbp-body">
			<?php while ( bbp_topics() ) : bbp_the_topic(); ?>
				<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>
			<?php endwhile; ?>
        </li>
    </ul>

<?php do_action( 'bbp_template_after_topics_loop' );
