<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_lead_topic' ); ?>

    <div id="bbp-topic-<?php bbp_topic_id(); ?>-lead" class="bbp-lead-topic">

        <div id="post-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

			<?php the_title( '<h2 class="bbp-single-topic-title">', '</h3>' ); ?>

            <div class="bbp-reply-author">
				<?php bbp_topic_author_link( array( 'type' => 'avatar', 'size' => 160 ) ); ?>
                <div class="bbb-reply-author-content">
					<?php do_action( 'bbp_theme_before_topic_author_details' ); ?>
                    <div class="bbp-reply-author-name">
	                    <?php bbp_topic_author_link( array( 'type' => 'name' ) ); ?>
	                    <?php $role = bbp_get_user_display_role( bbp_get_topic_author_id( bbp_get_topic_id() ) ); ?>
                        <span class="meta-role is-role-<?php echo strtolower( trim( $role ) ); ?>"><?php echo esc_html( $role ); ?></span>
                    </div>
                    <span class="bbp-reply-post-date is-meta"><?php bbp_topic_post_date(); ?></span>
					<?php if ( current_user_can( 'moderate', bbp_get_reply_id() ) ) : ?>
                        <span class="bbp-reply-ip is-meta"><?php bbp_author_ip( bbp_get_topic_id() ); ?></span>
					<?php endif; ?>
					<?php do_action( 'bbp_theme_after_topic_author_details' ); ?>
                </div>
            </div>

            <div class="bbp-reply-content rbct">

				<?php do_action( 'bbp_theme_before_topic_content' ); ?>

				<?php bbp_topic_content(); ?>

				<?php do_action( 'bbp_theme_after_topic_content' ); ?>

            </div>

            <div class="bbp-reply-footer meta-text">
                <a href="<?php bbp_topic_permalink(); ?>" data-link="<?php bbp_topic_permalink(); ?>" class="copy-trigger bbp-reply-permalink bbp-copy-link" data-copy="<?php echo foxiz_attr__( 'Copy Link', 'ruby-bbp' ); ?>" data-copied="<?php echo foxiz_attr__( 'Added to Clipboard', 'ruby-bbp' ); ?>"><i class="bbp-rbi-copy" aria-hidden="true"></i> #<?php bbp_topic_id(); ?>
                </a>
	            <?php do_action( 'bbp_theme_before_topic_admin_links' ); ?>
				<?php bbp_topic_admin_links(); ?>
	            <?php do_action( 'bbp_theme_after_topic_admin_links' ); ?>
            </div>
        </div>
    </div>
<?php do_action( 'bbp_template_after_lead_topic' );
