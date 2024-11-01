<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_block_attributes' ) ) {
	function foxiz_get_block_attributes( $block_name, $post_id, $client_id ) {

		if ( empty( $block_name ) || empty( $client_id ) || empty( $post_id ) ) {
			return false;
		}

		$post = get_post( $post_id );
		if ( $post ) {
			$blocks = parse_blocks( $post->post_content );
			foreach ( $blocks as $block ) {
				if ( isset( $block['blockName'] ) && $block['blockName'] === $block_name ) {
					$block_attributes = $block['attrs'];
					if ( isset( $block_attributes['blockId'] ) && $block_attributes['blockId'] === $client_id ) {
						return $block_attributes;
					}
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_block_padding_css' ) ) {
	function foxiz_get_block_padding_css( $padding = [] ) {

		$output = '';

		if ( isset( $padding['top'] ) ) {
			$output .= $padding['top'] . ' ';
		} else {
			$output .= '20px ';
		}

		if ( isset( $padding['right'] ) ) {
			$output .= $padding['right'] . ' ';
		} else {
			$output .= '20px ';
		}

		if ( isset( $padding['bottom'] ) ) {
			$output .= $padding['bottom'] . ' ';
		} else {
			$output .= '20px ';
		}

		if ( isset( $padding['left'] ) ) {
			$output .= $padding['left'] . ' ';
		} else {
			$output .= '20px';
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_block_border_width_css' ) ) {
	function foxiz_get_block_border_width_css( $border = [] ) {

		$output = '';

		if ( isset( $border['top'] ) ) {
			$output .= $border['top'] . ' ';
		} else {
			$output .= '0 ';
		}

		if ( isset( $border['right'] ) ) {
			$output .= $border['right'] . ' ';
		} else {
			$output .= '0 ';
		}

		if ( isset( $border['bottom'] ) ) {
			$output .= $border['bottom'] . ' ';
		} else {
			$output .= '0 ';
		}

		if ( isset( $border['left'] ) ) {
			$output .= $border['left'] . ' ';
		} else {
			$output .= '0';
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_block_review_schema_markup' ) ) {
	function foxiz_block_review_schema_markup( $attributes ) {

		if ( ! empty( $attributes['post_id'] ) ) {
			$post_id = $attributes['post_id'];
		} else {
			$post_id = get_the_ID();
		}

		if ( empty( $attributes['heading'] ) ) {
			return false;
		}

		$item = [
			'name'        => ! empty( $attributes['heading'] ) ? $attributes['heading'] : '',
			'image'       => ! empty( $attributes['image'] ) ? $attributes['image'] : '#',
			'description' => ! empty( $attributes['description'] ) ? $attributes['description'] : '',
			'author'      => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			'average'     => '',
			'best_rating' => 5,
			'price'       => '',
			'currency'    => 'USD',
			'offer_until' => '',
		];

		$item['average'] = foxiz_block_review_get_average( $attributes );
		if ( empty( $item['average'] ) ) {
			$item['average'] = 5;
		}

		if ( ! empty( $attributes['productPros'] ) ) {
			$item['pros'] = $attributes['productPros'];
		}

		if ( ! empty( $attributes['productCons'] ) ) {
			$item['pros'] = $attributes['productCons'];
		}

		if ( ! empty( $attributes['buyButtons'] ) ) {
			$item['offers'] = $attributes['buyButtons'];
			if ( ! empty( $attributes['salePrice'] ) ) {
				$item['price'] = foxiz_get_price_number( $attributes['salePrice'] );
			} elseif ( ! empty( $attributes['price'] ) ) {
				$item['price'] = foxiz_get_price_number( $attributes['price'] );
			}

			if ( ! empty( $attributes['offerUntil'] ) ) {
				$item['offer_until'] = $attributes['offerUntil'];
			}

			if ( ! empty( $attributes['priceCurrency'] ) ) {
				$item['currency'] = $attributes['priceCurrency'];
			}
		}

		foxiz_review_product_schema_markup( $item );
	}
}

if ( ! function_exists( 'foxiz_get_price_number' ) ) {
	function foxiz_get_price_number( $price ) {

		if ( empty( $price ) ) {
			return 0;
		}

		return preg_replace( '/[^0-9,.]/', '', $price );
	}
}

if ( ! function_exists( 'foxiz_review_product_schema_markup' ) ) {
	function foxiz_review_product_schema_markup( $item ) {

		$json_ld = [
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'description' => $item['description'],
			'image'       => $item['image'],
			'name'        => $item['name'],
		];

		$json_ld['review'] = [
			'author'       => [
				'@type' => 'Person',
				'name'  => $item['author'],
			],
			'@type'        => 'Review',
			'reviewRating' => [
				'@type'       => 'Rating',
				'ratingValue' => $item['average'],
				'bestRating'  => $item['best_rating'],
				'worstRating' => 1,
			],
		];

		if ( ! empty( $item['pros'] ) ) {
			$json_ld['review']['positiveNotes'] = [
				'@type'           => 'ItemList',
				'itemListElement' => [],
			];

			foreach ( $item['pros'] as $key => $val ) {
				$json_ld['review']['positiveNotes']['itemListElement'][] = [
					'@type'    => 'ListItem',
					'position' => $key + 1,
					'name'     => $val['content'],
				];
			}
		}

		if ( ! empty( $item['cons'] ) ) {
			$json_ld['review']['negativeNotes'] = [
				'@type'           => 'ItemList',
				'itemListElement' => [],
			];

			foreach ( $item['cons'] as $key => $val ) {
				$json_ld['review']['negativeNotes']['itemListElement'][] = [
					'@type'    => 'ListItem',
					'position' => $key + 1,
					'name'     => $val['content'],
				];
			}
		}

		if ( ! empty( $item['offers'] ) ) {

			$total_offers = [];
			foreach ( $item['offers'] as $offer ) {
				$total_offers[] = [
					'@type'           => 'Offer',
					'url'             => ! empty( $offer['link'] ) ? $offer['link'] : '#',
					'priceCurrency'   => $item['currency'],
					'price'           => $item['price'],
					'availability'    => 'https://schema.org/InStock',
					'priceValidUntil' => $item['offer_until'],
				];
			}

			if ( count( $total_offers ) > 1 ) {
				$json_ld['offers'] = $total_offers;
			} elseif ( count( $total_offers ) === 1 ) {
				$json_ld['offers'] = $total_offers[0];
			}
		}

		echo '<script type="application/ld+json">';
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			echo wp_json_encode( $json_ld, JSON_UNESCAPED_SLASHES );
		} else {
			echo wp_json_encode( $json_ld );
		}
		echo '</script>', "\n";
	}
}
