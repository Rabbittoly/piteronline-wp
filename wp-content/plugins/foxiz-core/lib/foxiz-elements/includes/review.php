<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'save_post', 'foxiz_set_main_block_review', 20 );

if ( ! function_exists( 'foxiz_render_block_review' ) ) {
	function foxiz_render_block_review( $attributes ) {

		$wrapperClassName = 'gb-wrap gb-review none-padding';
		if ( ! empty( $attributes['shadow'] ) ) {
			$wrapperClassName .= ' yes-shadow';
		}
		$attributes['post_id'] = get_the_ID();

		$output = '';

		$heading_tag   = ! empty( $attributes['headingHTMLTag'] ) ? esc_attr( $attributes['headingHTMLTag'] ) : 'h2';
		$heading_class = ! empty( $attributes['tocAdded'] ) ? 'gb-heading' : 'gb-heading none-toc';

		$avg_value = foxiz_block_review_get_average( $attributes );

		$output .= '<div ' . get_block_wrapper_attributes( [
				'class' => $wrapperClassName,
				'style' => foxiz_get_block_review_style( $attributes ),
			] ) . '>';

		if ( ! empty( $attributes['overlayLink'] ) ) {
			$overlaySponsored    = ! empty( $attributes['overlayLinkSponsored'] );
			$overlayLinkInternal = ! empty( $attributes['overlayLinkInternal'] );
			$overlayLinkRel      = '';
			$overlayTarget       = '_self';

			if ( ! $overlayLinkInternal ) {
				$overlayLinkRel .= 'nofollow noreferrer';
				$overlayTarget  = '_blank';
				if ( ! $overlaySponsored ) {
					$overlayLinkRel .= ' sponsored';
				}
			}
			$output .= '<a class="gb-overlay-link" href="' . esc_url( $attributes['overlayLink'] ) . '" target="' . esc_attr( $overlayTarget ) . '" rel="' . esc_attr( trim( $overlayLinkRel ) ) . '"></a>';
		}

		if ( ! empty( $attributes['shadow'] ) ) {
			$output .= foxiz_block_review_featured_image( $attributes );
		}

		$output .= '<div class="gb-content gb-review-content">';

		if ( empty( $attributes['shadow'] ) ) {
			$output .= foxiz_block_review_featured_image( $attributes );
		}
		$output .= '<div class="gb-review-header">';
		$output .= '<div class="gb-review-heading">';
		if ( ! empty( $attributes['heading'] ) ) {
			$output .= '<' . $heading_tag . ' class="' . $heading_class . '">' . $attributes['heading'] . '</' . $heading_tag . '>';
		}
		if ( ! empty( $attributes['price'] ) ) {
			$output .= '<span class="af-price ' . $heading_tag . '">';
			if ( ! empty( $attributes['salePrice'] ) ) {
				$output .= '<del>' . $attributes['price'] . '</del>' . $attributes['salePrice'];
			} else {
				$output .= $attributes['price'];
			}
			$output .= '</span>';
		}
		$output .= '</div>';

		if ( $avg_value && function_exists( 'foxiz_get_review_stars' ) ) {
			$output .= '<div class="review-total-stars">';
			if ( function_exists( 'foxiz_html__' ) ) {
				$out_of_label = foxiz_html__( 'out of 5', 'foxiz-core' );
			} else {
				$out_of_label = 'out of 5';
			}

			$output .= foxiz_get_review_stars( $avg_value );
			$output .= '<span>' . $avg_value . ' <em>' . $out_of_label . '</em>' . '</span>';

			$output .= '</div>';
		}
		$output .= '</div>';
		if ( ! empty( $attributes['description'] ) ) {
			$output .= '<div class="gb-review-description top-divider"><div class="gb-description rb-text">' . foxiz_strip_tags( $attributes['description'] ) . '</div></div>';
		}
		$output .= foxiz_block_review_features( $attributes );
		$output .= foxiz_block_review_pros_cons( $attributes );
		$output .= foxiz_block_review_buttons( $attributes );
		$output .= '</div>';
		$output .= '</div>';

		add_action(
			'wp_footer',
			function () use ( $attributes ) {

				foxiz_block_review_schema_markup( $attributes );
			}, 5
		);

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_block_review_style' ) ) {
	function foxiz_get_block_review_style( $attributes ) {

		$css = [];

		if ( ! empty( $attributes['headingColor'] ) ) {
			$css['--heading-color'] = $attributes['headingColor'];
		}
		if ( ! empty( $attributes['darkHeadingColor'] ) ) {
			$css['--dark-heading-color'] = $attributes['darkHeadingColor'];
		}
		if ( ! empty( $attributes['desktopHeadingSize'] ) ) {
			$css['--desktop-heading-size'] = $attributes['desktopHeadingSize'] . 'px';
		}
		if ( ! empty( $attributes['tabletHeadingSize'] ) ) {
			$css['--tablet-heading-size'] = $attributes['tabletHeadingSize'] . 'px';
		}
		if ( ! empty( $attributes['mobileHeadingSize'] ) ) {
			$css['--mobile-heading-size'] = $attributes['mobileHeadingSize'] . 'px';
		}
		if ( ! empty( $attributes['descriptionColor'] ) ) {
			$css['--description-color'] = $attributes['descriptionColor'];
		}
		if ( ! empty( $attributes['darkDescriptionColor'] ) ) {
			$css['--dark-description-color'] = $attributes['darkDescriptionColor'];
		}
		if ( ! empty( $attributes['desktopDescriptionSize'] ) ) {
			$css['--desktop-description-size'] = $attributes['desktopDescriptionSize'] . 'px';
		}
		if ( ! empty( $attributes['tabletDescriptionSize'] ) ) {
			$css['--tablet-description-size'] = $attributes['tabletDescriptionSize'] . 'px';
		}
		if ( ! empty( $attributes['mobileDescriptionSize'] ) ) {
			$css['--mobile-description-size'] = $attributes['mobileDescriptionSize'] . 'px';
		}

		if ( ! empty( $attributes['priceColor'] ) ) {
			$css['--price-color'] = $attributes['priceColor'];
		}
		if ( ! empty( $attributes['darkPriceColor'] ) ) {
			$css['--dark-price-color'] = $attributes['darkPriceColor'];
		}
		if ( ! empty( $attributes['desktopPriceSize'] ) ) {
			$css['--desktop-price-size'] = $attributes['desktopPriceSize'] . 'px';
		}
		if ( ! empty( $attributes['tabletPriceSize'] ) ) {
			$css['--tablet-price-size'] = $attributes['tabletPriceSize'] . 'px';
		}
		if ( ! empty( $attributes['mobilePriceSize'] ) ) {
			$css['--mobile-price-size'] = $attributes['mobilePriceSize'] . 'px';
		}
		if ( ! empty( $attributes['overlayMetaColor'] ) ) {
			$css['--overlay-meta-color'] = $attributes['overlayMetaColor'];
		}
		if ( ! empty( $attributes['overlayMetaBg'] ) ) {
			$css['--overlay-meta-bg'] = $attributes['overlayMetaBg'];
		}
		if ( ! empty( $attributes['starColor'] ) ) {
			$css['--review-color'] = $attributes['starColor'];
		}
		/** buttons */
		if ( ! empty( $attributes['desktopButtonSize'] ) ) {
			$css['--desktop-button-size'] = $attributes['desktopButtonSize'] . 'px';
		}
		if ( ! empty( $attributes['tabletButtonSize'] ) ) {
			$css['--tablet-button-size'] = $attributes['tabletButtonSize'] . 'px';
		}
		if ( ! empty( $attributes['mobileButtonSize'] ) ) {
			$css['--mobile-button-size'] = $attributes['mobileButtonSize'] . 'px';
		}
		if ( ! empty( $attributes['buttonColor'] ) ) {
			$css['--button-color'] = $attributes['buttonColor'];
		}
		if ( ! empty( $attributes['buttonBg'] ) ) {
			$css['--button-bg'] = $attributes['buttonBg'];
		}
		if ( ! empty( $attributes['darkButtonColor'] ) ) {
			$css['--dark-button-color'] = $attributes['darkButtonColor'];
		}
		if ( ! empty( $attributes['darkButtonBg'] ) ) {
			$css['--dark-button-bg'] = $attributes['darkButtonBg'];
		}
		if ( ! empty( $attributes['isBorderButtonColor'] ) ) {
			$css['--is-border-button-color'] = $attributes['isBorderButtonColor'];
		}
		if ( ! empty( $attributes['isBorderButtonBorder'] ) ) {
			$css['--is-border-button-border'] = $attributes['isBorderButtonBorder'];
		}
		if ( ! empty( $attributes['isBorderDarkButtonColor'] ) ) {
			$css['--dark-is-border-button-color'] = $attributes['isBorderDarkButtonColor'];
		}
		if ( ! empty( $attributes['isBorderDarkButtonBg'] ) ) {
			$css['--dark-is-border-button-border'] = $attributes['isBorderDarkButtonBg'];
		}
		/** border and padding */
		if ( ! empty( $attributes['borderStyle'] ) ) {
			$css['--border-style'] = $attributes['borderStyle'];
		}
		if ( ! empty( $attributes['borderRadius'] ) ) {
			$css['--border-radius'] = $attributes['borderRadius'] . 'px';
		}
		if ( ! empty( $attributes['borderWidth'] ) ) {
			$css['--border-width'] = foxiz_get_block_border_width_css( $attributes['borderWidth'] );
		}
		if ( ! empty( $attributes['borderColor'] ) ) {
			$css['--border-color'] = $attributes['borderColor'];
		}
		if ( ! empty( $attributes['darkBorderColor'] ) ) {
			$css['--dark-border-color'] = $attributes['darkBorderColor'];
		}
		if ( ! empty( $attributes['background'] ) ) {
			$css['--bg'] = $attributes['background'];
		}
		if ( ! empty( $attributes['darkBackground'] ) ) {
			$css['--dark-bg'] = $attributes['darkBackground'];
		}
		if ( ! empty( $attributes['desktopPadding'] ) ) {
			$css['--desktop-padding'] = foxiz_get_block_padding_css( $attributes['desktopPadding'] );
		}
		if ( ! empty( $attributes['tabletPadding'] ) ) {
			$css['--tablet-padding'] = foxiz_get_block_padding_css( $attributes['tabletPadding'] );
		}
		if ( ! empty( $attributes['mobilePadding'] ) ) {
			$css['--mobile-padding'] = foxiz_get_block_padding_css( $attributes['mobilePadding'] );
		}

		$css_attributes = '';
		foreach ( $css as $key => $value ) {
			$css_attributes .= "$key: $value;";
		}

		return $css_attributes;
	}
}

if ( ! function_exists( 'foxiz_block_review_featured_image' ) ) {

	function foxiz_block_review_featured_image( $attributes ) {

		$output = '';
		$attr   = 'loading="lazy"';

		if ( empty( $attributes['image'] ) && ! empty( $attributes['imageURL'] ) ) {
			$attributes['image'] = $attributes['imageURL'];
			$attr                .= ' rel="nofollow"';
		}

		if ( ! empty( $attributes['image'] ) ) {
			$size   = foxiz_get_image_size( $attributes['image'] );
			$output .= '<div class="gb-review-featured">';
			$output .= '<img  ' . $attr . ' src="' . $attributes['image'] . '" alt="' . $attributes['imageAlt'] . '" ';
			if ( ! empty( $size[3] ) ) {
				$output .= $size[3];
			}
			$output .= '>';
			if ( ! empty( $attributes['meta'] ) ) {
				$output .= '<span class="gb-absolute-meta review-quickview-meta">';
				$output .= '<span class="meta-score h4">';
				$output .= foxiz_block_review_get_average( $attributes );
				$output .= '</span>';
				$output .= '<span class="meta-text">' . $attributes['meta'] . '</span>';
				$output .= '</span>';
			}
			$output .= '</div>';
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_block_review_get_average' ) ) {

	function foxiz_block_review_get_average( $attributes ) {

		if ( empty( $attributes['productFeatures'] ) || ! is_array( $attributes['productFeatures'] ) ) {

			if ( ! empty( $attributes['metaScore'] ) ) {
				$average_rating = (float) $attributes['metaScore'];
				if ( $average_rating > 5 ) {
					$average_rating = 5;
				}

				return round( $average_rating, 1 );
			}

			return false;
		}

		$total = 0;
		$count = 0;
		foreach ( $attributes['productFeatures'] as $feature ) {
			if ( ! empty( $feature['rating'] ) && ! empty( $feature['label'] ) ) {
				$total += (float) $feature['rating'];
				$count ++;
			}
		}

		$average_rating = $count > 0 ? $total / $count : 0;

		return round( $average_rating, 1 );
	}
}

if ( ! function_exists( 'foxiz_block_review_pros_cons' ) ) {
	function foxiz_block_review_pros_cons( $attributes ) {

		if ( ! function_exists( 'foxiz_render_review_pros_cons' ) ) {
			return false;
		}
		if ( empty( $attributes['productPros'] ) && empty( $attributes['productCons'] ) ) {
			return false;
		}

		$settings = [
			'cons'    => [],
			'pros'    => [],
			'classes' => 'top-divider',
		];

		if ( ! empty( $attributes['prosLabel'] ) ) {
			$settings['pros_label'] = $attributes['prosLabel'];
		}

		if ( ! empty( $attributes['consLabel'] ) ) {
			$settings['cons_label'] = $attributes['consLabel'];
		}

		if ( ! empty( $attributes['productPros'] ) && is_array( $attributes['productPros'] ) ) {
			foreach ( $attributes['productPros'] as $index => $item ) {
				if ( ! empty( $item['content'] ) ) {
					$settings['pros'][ $index ]['pros_item'] = $item['content'];
				}
			}
		}

		if ( ! empty( $attributes['productCons'] ) && is_array( $attributes['productCons'] ) ) {
			foreach ( $attributes['productCons'] as $index => $item ) {
				if ( ! empty( $item['content'] ) ) {
					$settings['cons'][ $index ]['cons_item'] = $item['content'];
				}
			}
		}

		ob_start();
		foxiz_render_review_pros_cons( $settings );

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_block_review_features' ) ) {
	function foxiz_block_review_features( $attributes ) {

		if ( ! function_exists( 'foxiz_get_review_stars' ) ) {
			return false;
		}
		if ( empty( $attributes['productFeatures'] ) || ! is_array( $attributes['productFeatures'] ) ) {
			return false;
		}

		ob_start(); ?>
		<div class="review-specs top-divider">
			<?php foreach ( $attributes['productFeatures'] as $element ) :
				if ( empty( $element['label'] ) || empty( $element['rating'] ) ) {
					continue;
				}
				if ( $element['rating'] > 5 ) {
					$element['rating'] = 5;
				} elseif ( $element['rating'] < 1 ) {
					$element['rating'] = 1;
				} ?>
				<div class="review-el">
					<div class="review-label">
						<span class="review-label-info h4"><?php echo esc_html( $element['label'] ); ?></span>
						<span class="rating-info is-meta"><?php printf( foxiz_html__( '%s out of 5', 'foxiz' ), $element['rating'] ); ?></span>
					</div>
					<span class="review-rating"><?php echo foxiz_get_review_stars( $element['rating'] ); ?></span>
				</div>
			<?php endforeach;
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_block_review_buttons' ) ) {
	function foxiz_block_review_buttons( $attributes ) {

		if ( empty( $attributes['buyButtons'] ) || ! is_array( $attributes['buyButtons'] ) ) {
			return false;
		}

		$output = '';
		$output .= '<div class="review-buttons top-divider">';
		foreach ( $attributes['buyButtons'] as $item ) {

			$class_name = 'review-btn is-btn gb-btn';
			if ( ! empty( $item['isButtonBorder'] ) ) {
				$class_name .= ' is-border-style';
			}

			$isSponsored = ! empty( $item['isSponsored'] ) ? $item['isSponsored'] : false;
			$link        = ! empty( $item['link'] ) ? esc_url( $item['link'] ) : '#';
			$output      .= '<a class="' . $class_name . '" href="' . $link . '" target="_blank" rel="nofollow noreferrer' . ( $isSponsored ? ' sponsored' : '' ) . '">' . esc_html( $item['label'] ) . '</a>';
		}
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'foxiz_set_main_block_review' ) ) {
	/**
	 * @param $post_id
	 */
	function foxiz_set_main_block_review( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( ! has_blocks( $post_id ) ) {
			return;
		}

		if ( function_exists( 'foxiz_is_review_post' ) && foxiz_is_review_post( $post_id ) ) {
			return;
		}

		/** remove if empty data */
		delete_post_meta( $post_id, 'foxiz_review_average' );
		delete_post_meta( $post_id, 'foxiz_block_review_metadata' );

		// Check if the post type supports blocks.
		$post_content     = get_post_field( 'post_content', $post_id );
		$block_attributes = foxiz_extract_first_review_block( $post_content );
		if ( empty( $block_attributes ) ) {
			$block_attributes = foxiz_extract_first_review_reusable( $post_content );
		}

		if ( empty( $block_attributes ) ) {
			return;
		}

		$avg_value = foxiz_block_review_get_average( $block_attributes );
		update_post_meta( $post_id, 'foxiz_block_review_metadata',
			[
				'type'    => 'star',
				'meta'    => ! empty( $block_attributes['meta'] ) ? $block_attributes['meta'] : '',
				'average' => $avg_value,
			] );

		update_post_meta( $post_id, 'foxiz_review_average', floatval( $avg_value ) * 2 );
	}
}

if ( ! function_exists( 'foxiz_extract_first_review_block' ) ) {
	/**
	 * @param $content
	 *
	 * @return array|mixed
	 */
	function foxiz_extract_first_review_block( $content ) {

		$pattern = '/<!--\s*wp:foxiz-elements\/review\s*(.*?)\s*\/-->/s';
		$matches = [];

		if ( preg_match_all( $pattern, $content, $matches ) ) {
			if ( ! empty( $matches[1] && is_array( $matches[1] ) ) ) {
				foreach ( $matches[1] as $review_block ) {
					$block_attributes = json_decode( $review_block, true );
					/** return 1st block */
					if ( $block_attributes !== null && ! empty( $block_attributes['isMainReview'] ) ) {
						return $block_attributes;
					}
				}
			}
		}

		return [];
	}
}

if ( ! function_exists( 'foxiz_extract_first_review_reusable' ) ) {
	/**
	 * @param $content
	 *
	 * @return array|mixed
	 */
	function foxiz_extract_first_review_reusable( $content ) {

		$pattern = '/<!-- wp:block {"ref":(\d+)}/';
		$matches = [];
		if ( preg_match_all( $pattern, $content, $matches ) ) {
			if ( ! empty( $matches[1] && is_array( $matches[1] ) ) ) {
				foreach ( $matches[1] as $post_id ) {
					$post_content     = get_post_field( 'post_content', $post_id );
					$block_attributes = foxiz_extract_first_review_block( $post_content );
					if ( ! empty( $block_attributes ) ) {
						return $block_attributes;
					}
				}
			}
		}

		return [];
	}
}
