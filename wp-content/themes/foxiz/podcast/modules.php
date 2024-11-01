<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_podcast_grid_flex_1' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_podcast_grid_flex_1( $settings = array() ) {

		if ( empty( $settings['block_structure'] ) || ! is_array( $settings['block_structure'] ) ) {
			return;
		}

		$settings['post_classes'] = 'p-grid p-grid-1 podcast-grid-flex-1';
		if ( ! empty( $settings['box_style'] ) ) {
			$settings['post_classes'] = 'p-box p-grid-box-1 podcast-grid-flex-1 box-' . $settings['box_style'];
		}

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}

		foxiz_post_open_tag( $settings );
		if ( ! empty( $settings['box_style'] ) ) {
			echo '<div class="grid-box">';
		}
		foreach ( $settings['block_structure'] as $element ) :
			switch ( $element ) {
				case 'thumbnail' :
					if ( ! empty( $settings['overlay_category'] ) ) {
						foxiz_podcast_featured_with_category( $settings );
					} else {
						foxiz_podcast_featured_only( $settings );
					}
					break;
				case 'category' :
					foxiz_entry_top( $settings );
					break;
				case 'title' :
					foxiz_podcast_title( $settings );
					break;
				case 'excerpt' :
					foxiz_entry_excerpt( $settings );
					break;
				case 'meta' :
					foxiz_podcast_entry_meta( $settings );
					break;
				case 'readmore' :
					foxiz_entry_readmore( $settings );
					break;
				case 'divider' :
					foxiz_entry_divider( $settings );
					break;
				case 'player' :
					foxiz_podcast_entry_player( $settings );
					break;
			}
		endforeach;
		if ( ! empty( $settings['box_style'] ) ) {
			echo '</div>';
		}
		foxiz_post_close_tag();
	}
}

if ( ! function_exists( 'foxiz_podcast_overlay_flex_1' ) ) {
	/**
	 * @param array $settings
	 *
	 */
	function foxiz_podcast_overlay_flex_1( $settings = array() ) {

		if ( empty( $settings['block_structure'] ) || ! is_array( $settings['block_structure'] ) ) {
			return;
		}

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		$settings['post_classes'] = 'p-overlay podcast-overlay-flex-1';
		foxiz_post_open_tag( $settings );
		?>
        <div class="overlay-holder">
			<?php foxiz_podcast_featured( $settings ); ?>
            <div class="overlay-wrap">
                <div class="p-content overlay-text overlay-inner">
					<?php
					foreach ( $settings['block_structure'] as $element ) :
						switch ( $element ) {
							case 'category' :
								foxiz_entry_top( $settings );
								break;
							case 'title' :
								foxiz_podcast_title( $settings );
								break;
							case 'excerpt' :
								foxiz_entry_excerpt( $settings );
								break;
							case 'meta' :
								foxiz_podcast_entry_meta( $settings );
								break;
							case 'readmore' :
								foxiz_entry_readmore( $settings );
								break;
							case 'divider' :
								foxiz_entry_divider( $settings );
								break;
							case 'player' :
								foxiz_podcast_entry_player( $settings );
								break;
						}
					endforeach;
					?>
                </div>
            </div>
        </div>
		<?php foxiz_post_close_tag();
	}
}

if ( ! function_exists( 'foxiz_podcast_list_flex_1' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_podcast_list_flex_1( $settings = array() ) {

		if ( empty( $settings['block_structure'] ) || ! is_array( $settings['block_structure'] ) ) {
			return;
		}

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		$settings['post_classes'] = 'p-list podcast-list-flex-1 p-list-1';
		foxiz_post_open_tag( $settings );
		?>
        <div class="list-holder">
            <div class="list-feat-holder">
				<?php if ( ! empty( $settings['overlay_category'] ) ) {
					foxiz_podcast_featured_with_category( $settings );
				} else {
					foxiz_podcast_featured_only( $settings );
				} ?>
            </div>
            <div class="p-content">
				<?php
				foreach ( $settings['block_structure'] as $element ) :
					switch ( $element ) {
						case 'category' :
							foxiz_entry_top( $settings );
							break;
						case 'title' :
							foxiz_podcast_title( $settings );
							break;
						case 'excerpt' :
							foxiz_entry_excerpt( $settings );
							break;
						case 'meta' :
							foxiz_podcast_entry_meta( $settings );
							break;
						case 'readmore' :
							foxiz_entry_readmore( $settings );
							break;
						case 'divider' :
							foxiz_entry_divider( $settings );
							break;
						case 'player' :
							foxiz_podcast_entry_player( $settings );
							break;
					}
				endforeach;
				?>
            </div>
        </div>
		<?php foxiz_post_close_tag();
	}
}