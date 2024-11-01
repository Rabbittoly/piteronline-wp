<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_reading_list_template' ) ) {
	function foxiz_render_reading_list_template() {

		foxiz_my_personalize();
		foxiz_my_recommended();
	}
}

if ( ! function_exists( 'foxiz_my_personalize' ) ) {
	function foxiz_my_personalize() {

		echo '<div class="my-personalized">';
		foxiz_my_saved();
		foxiz_my_categories();
		foxiz_my_writers();
		echo '</div>';
	}
}

if ( ! function_exists( 'foxiz_my_saved' ) ) {
	function foxiz_my_saved() {

		$display_mode           = foxiz_get_option( 'reading_list_display_mode' );
		$image_description      = foxiz_get_option( 'saved_image' );
		$image_description_dark = foxiz_get_option( 'saved_image_dark' );
		$pattern                = foxiz_get_option( 'saved_pattern' );

		$heading_classes = 'bookmark-section-header';
		if ( ! empty( $pattern ) && '-1' !== (string) $pattern ) {
			$heading_classes .= ' is-pattern pattern-' . $pattern;
		} else {
			$heading_classes .= ' solid-bg';
		} ?>
		<div class="saved-section">
			<div class="<?php echo strip_tags( $heading_classes ); ?>">
				<div class="rb-container edge-padding">
					<?php if ( ! empty( $image_description['url'] ) ) : ?>
						<div class="bookmark-section-header-image">
							<?php if ( ! empty( $image_description_dark['url'] ) ) : ?>
								<img loading="lazy" decoding="async" data-mode="default" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
								<img loading="lazy" decoding="async" data-mode="dark" src="<?php echo esc_url( $image_description_dark['url'] ); ?>" alt="<?php echo strip_tags( $image_description_dark['alt'] ); ?>" height="<?php echo strip_tags( $image_description_dark['height'] ); ?>" width="<?php echo strip_tags( $image_description_dark['width'] ); ?>">
							<?php else : ?>
								<img loading="lazy" decoding="async" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="bookmark-section-header-inner">
						<h2 class="bookmark-section-title"><?php foxiz_render_inline_html( foxiz_get_option( 'saved_heading' ) ); ?></h2>
						<?php if ( foxiz_get_option( 'saved_description' ) ) : ?>
							<p class="bookmark-section-decs is-meta"><?php foxiz_render_inline_html( foxiz_get_option( 'saved_description' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if ( empty( $display_mode ) ) : ?>
				<div id="my-saved" class="my-saved">
					<div class=" rb-container edge-padding"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
				</div>
			<?php else : echo foxiz_my_saved_listing(); endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_my_categories' ) ) {
	function foxiz_my_categories() {

		if ( ! foxiz_get_option( 'interest_category' ) ) {
			return false;
		}

		$display_mode    = foxiz_get_option( 'reading_list_display_mode' );
		$heading_classes = 'bookmark-section-header';
		$pattern         = foxiz_get_option( 'interest_pattern' );
		if ( ! empty( $pattern ) && ( '-1' !== (string) $pattern ) ) {
			$heading_classes .= ' is-pattern pattern-' . $pattern;
		} else {
			$heading_classes .= ' solid-bg';
		}
		$image_description      = foxiz_get_option( 'interest_image' );
		$image_description_dark = foxiz_get_option( 'interest_image_dark' );
		?>
		<div class="interest-section">
			<div class="<?php echo strip_tags( $heading_classes ); ?>">
				<div class="rb-container edge-padding">
					<?php if ( ! empty( $image_description['url'] ) ) : ?>
						<div class="bookmark-section-header-image">
							<?php if ( ! empty( $image_description_dark['url'] ) ) : ?>
								<img loading="lazy" decoding="async" data-mode="default" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
								<img loading="lazy" decoding="async" data-mode="dark" src="<?php echo esc_url( $image_description_dark['url'] ); ?>" alt="<?php echo strip_tags( $image_description_dark['alt'] ); ?>" height="<?php echo strip_tags( $image_description_dark['height'] ); ?>" width="<?php echo strip_tags( $image_description_dark['width'] ); ?>">
							<?php else : ?>
								<img loading="lazy" decoding="async" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="bookmark-section-header-inner">
						<h2 class="bookmark-section-title"><?php foxiz_render_inline_html( foxiz_get_option( 'interest_heading' ) ); ?></h2>
						<?php if ( foxiz_get_option( 'saved_description' ) ) : ?>
							<p class="bookmark-section-decs is-meta"><?php foxiz_render_inline_html( foxiz_get_option( 'interest_description' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="interest-content rb-container edge-padding">
				<?php if ( empty( $display_mode ) ) : ?>
					<div id="my-categories">
						<div class="interest-loader"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
						<div class="interest-loader"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
					</div>
				<?php else : echo foxiz_my_categories_listing(); endif; ?>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'foxiz_my_writers' ) ) {
	function foxiz_my_writers() {

		if ( ! foxiz_get_option( 'interest_author' ) ) {
			return false;
		}

		$display_mode    = foxiz_get_option( 'reading_list_display_mode' );
		$heading_classes = 'bookmark-section-header';
		$pattern         = foxiz_get_option( 'interest_pattern' );
		if ( ! empty( $pattern ) && ( '-1' !== (string) $pattern ) ) {
			$heading_classes .= ' is-pattern pattern-' . $pattern;
		} else {
			$heading_classes .= ' solid-bg';
		}
		$image_description      = foxiz_get_option( 'interest_author_image' );
		$image_description_dark = foxiz_get_option( 'interest_author_image_dark' );
		?>
		<div class="interest-section">
			<div class="<?php echo strip_tags( $heading_classes ); ?>">
				<div class="rb-container edge-padding">
					<?php if ( ! empty( $image_description['url'] ) ) : ?>
						<div class="bookmark-section-header-image">
							<?php if ( ! empty( $image_description_dark['url'] ) ) : ?>
								<img data-mode="default" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
								<img data-mode="dark" src="<?php echo esc_url( $image_description_dark['url'] ); ?>" alt="<?php echo strip_tags( $image_description_dark['alt'] ); ?>" height="<?php echo strip_tags( $image_description_dark['height'] ); ?>" width="<?php echo strip_tags( $image_description_dark['width'] ); ?>">
							<?php else : ?>
								<img src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo strip_tags( $image_description['alt'] ); ?>" height="<?php echo strip_tags( $image_description['height'] ); ?>" width="<?php echo strip_tags( $image_description['width'] ); ?>">
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="bookmark-section-header-inner">
						<h2 class="bookmark-section-title"><?php foxiz_render_inline_html( foxiz_get_option( 'interest_author_heading' ) ); ?></h2>
						<?php if ( foxiz_get_option( 'saved_description' ) ) : ?>
							<p class="bookmark-section-decs is-meta"><?php foxiz_render_inline_html( foxiz_get_option( 'interest_author_description' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="interest-content rb-container edge-padding">
				<?php if ( empty( $display_mode ) ) : ?>
					<div id="my-writers">
						<div class="interest-loader"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
						<div class="interest-loader"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
					</div>
				<?php else : echo foxiz_my_writers_listing(); endif; ?>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'foxiz_my_recommended' ) ) {
	/**
	 * @return false
	 */
	function foxiz_my_recommended() {

		if ( ! foxiz_get_option( 'recommended_interested' ) ) {
			return false;
		}

		$display_mode = foxiz_get_option( 'reading_list_display_mode' );
		?><?php if ( empty( $display_mode ) ) : ?>
			<div id="my-recommended" class="my-recommended">
				<div class="rb-container edge-padding"><?php foxiz_render_svg( 'loading', '', 'animation' ); ?></div>
			</div>
		<?php else : echo foxiz_my_recommended_listing();
		endif;
	}
}

if ( ! function_exists( 'foxiz_my_saved_listing' ) ) {
	/**
	 * @return false|string
	 */
	function foxiz_my_saved_listing() {

		if ( ! is_user_logged_in() && ! empty( foxiz_get_option( 'bookmark_enable_when' ) ) ) {
			ob_start();
			echo '<div class="rb-container edge-padding">';
			foxiz_saved_restrict_info();
			echo '</div>';

			return ob_get_clean();
		}

		$_query = Foxiz_Personalize::get_instance()->saved_posts_query();

		ob_start();
		if ( ! empty( $_query ) && $_query->have_posts() ) {
			if ( ! empty( $template ) ) {
				$GLOBALS['ruby_template_query'] = $_query;
				echo do_shortcode( $template );
			} else {
				$settings = foxiz_get_archive_page_settings( 'saved_', [
					'uuid'    => 'uid_saved',
					'classes' => 'my-saved',
				] );
				/** disable pagination and bookmark */
				$settings['pagination'] = false;
				$settings['bookmark']   = '1';

				foxiz_the_blog( $settings, $_query );
			}
		} else {
			foxiz_saved_empty();
		}

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_my_categories_listing' ) ) {
	/**
	 * @return false|string
	 */
	function foxiz_my_categories_listing() {

		if ( ! is_user_logged_in() && 'logged' === foxiz_get_option( 'bookmark_enable_when' ) ) {
			return false;
		}

		$data = Foxiz_Personalize::get_instance()->get_my_categories();
		if ( empty( $data ) || ! count( $data ) ) {
			return false;
		}

		$settings = [
			'uuid'           => 'my-categories',
			'classes'        => 'block-categories block-categories-1',
			'url'            => foxiz_get_option( 'interest_url' ),
			'allowed_tax'    => [ 'category' ],
			'selected_ids'   => [],
			'follow'         => true,
			'title_tag'      => 'h4',
			'count_posts'    => true,
			'columns'        => 5,
			'columns_tablet' => 3,
			'columns_mobile' => 1,
			'column_gap'     => 10,
		];
		ob_start();
		foxiz_block_open_tag( $settings ); ?>
		<div class="block-inner">
			<?php
			foreach ( $data as $index => $id ) {
				$settings['cid'] = $id;
				foxiz_category_item_1( $settings );
			}
			foxiz_render_follow_redirect( $settings ); ?>
		</div>
		<?php
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_my_writers_listing' ) ) {
	function foxiz_my_writers_listing() {

		if ( ! is_user_logged_in() && 'logged' === foxiz_get_option( 'bookmark_enable_when' ) ) {
			return false;
		}

		$data = Foxiz_Personalize::get_instance()->get_my_writers();

		if ( empty( $data ) || ! count( $data ) ) {
			return false;
		}

		$settings = [
			'classes'            => 'block-authors is-box-shadow',
			'uuid'               => 'my-writers',
			'url'                => foxiz_get_option( 'interest_url' ),
			'follow'             => true,
			'title_tag'          => 'h4',
			'count_posts'        => true,
			'description_length' => 20,
			'columns'            => 2,
			'columns_tablet'     => 1,
			'columns_mobile'     => 1,
			'column_gap'         => 20,

		];

		ob_start();
		foxiz_block_open_tag( $settings ); ?>
		<div class="block-inner">
			<?php foreach ( $data as $index => $id ) {
				$settings['author'] = $id;
				if ( ! get_user_by( 'id', $settings['author'] ) ) {
					unset( $data[ $index ] );
					continue;
				}
				foxiz_author_card_1( $settings );
			}
			foxiz_render_follow_redirect( $settings );
			?>
		</div>
		<?php foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_my_recommended_listing' ) ) {
	/**
	 * @return false
	 */
	function foxiz_my_recommended_listing() {

		if ( ! is_user_logged_in() && 'logged' === foxiz_get_option( 'bookmark_enable_when' ) ) {
			return false;
		}

		$template = foxiz_get_option( 'recommended_template' );
		$settings = [
			'uuid' => 'uid_recommended',
		];
		$_query   = Foxiz_Personalize::get_instance()->recommended_query( $settings );

		if ( empty( $_query ) || ! $_query->have_posts() ) {
			return false;
		}

		ob_start();
		if ( ! empty( $template ) ) : ?>
			<div class="rec-builder-section"><?php
				$GLOBALS['ruby_template_query'] = $_query;
				echo do_shortcode( $template );
				?></div>
		<?php else :
			$settings = foxiz_get_archive_page_settings( 'recommended_', $settings ); ?>
			<div class="rec-section light-scheme"><?php foxiz_the_blog( $settings, $_query ) ?></div>
		<?php endif;

		return ob_get_clean();
	}
}
