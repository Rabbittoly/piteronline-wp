<?php
/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */
defined( 'ABSPATH' ) || exit;

if ( bbp_allow_search() ) : ?>
	<div class="bbp-search-form">
		<form role="search" method="get" id="bbp-search-form">
			<div class="bbp-search-form-inner">
				<label class="screen-reader-text hidden" for="bbp_search"><?php esc_html_e( 'Search for:', 'bbpress' ); ?></label>
				<input type="hidden" name="action" value="bbp-search-request" />
				<input type="text" placeholder="<?php echo get_option( 'ruby_bbp_search_placeholder', foxiz_attr__( 'Search All Forums', 'ruby-bbp' ) ); ?>" value="<?php bbp_search_terms(); ?>" name="bbp_search" id="bbp_search" />
                <div class="bbp-search-btn">
                    <i class="bbp-search-icon bbp-rbi-search" aria-hidden="true"></i>
                    <input class="is-btn" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
                </div>
			</div>
		</form>
	</div>
<?php endif;
