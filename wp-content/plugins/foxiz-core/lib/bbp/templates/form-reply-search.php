<?php

/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( bbp_allow_search() ) : ?>

    <div class="bbp-search-form">
        <form role="search" method="get" id="bbp-reply-search-form">
            <div class="bbp-search-form-inner">
                <label class="screen-reader-text hidden" for="rs"><?php esc_html_e( 'Search replies:', 'bbpress' ); ?></label>
                <input type="text" placeholder="<?php echo get_option( 'ruby_bbp_search_reply_placeholder', foxiz_attr__( 'Search for Replies', 'ruby-bbp' ) ); ?>" value="<?php bbp_search_terms(); ?>" name="rs" id="rs"/>
                <div class="bbp-search-btn">
                    <i class="bbp-search-icon bbp-rbi-search" aria-hidden="true"></i>
                    <input class="button" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>"/>
                </div>
            </div>
        </form>
    </div>

<?php endif;
