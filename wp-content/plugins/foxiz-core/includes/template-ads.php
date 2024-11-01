<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_adsense' ) ) {
	function foxiz_get_adsense( $settings ) {

		if ( empty( $settings['code'] ) || foxiz_is_amp() || ( is_404() && ! foxiz_get_option( 'page_404_ads' ) ) ) {
			return false;
		}

		if ( empty( $settings['uuid'] ) ) {
			$settings['uuid'] = rand( 1, 1000 );
		}

		$classes = 'ad-wrap ad-script-wrap';
		if ( empty( $settings['no_spacing'] ) ) {
			$classes = ' edge-padding';
		}
		$spot = foxiz_adsense_spot( $settings['code'] );

		/** disable adsense unit if have auto ads */
		if ( ! empty( $spot['data_ad_slot'] ) && ! empty( $spot['data_ad_client'] ) && ! empty( $GLOBALS['foxiz_disallowed_ads'] ) ) {
			return false;
		}

		ob_start(); ?>
		<div class="<?php echo strip_tags( $classes ); ?>">
			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<span class="ad-description is-meta"><?php foxiz_render_inline_html( $settings['description'] ); ?></span>
			<?php endif;
			if ( ! empty( $spot['data_ad_slot'] ) && ! empty( $spot['data_ad_client'] ) && ! empty( $settings['size'] ) ) : ?>
				<div class="ad-script is-adsense">
					<style>
						<?php echo '.res-'.trim($settings['uuid']); ?><?php echo foxiz_get_adsense_css($settings['mobile_size']); ?>
                        @media (min-width: 500px) {
						<?php echo '.res-'.trim($settings['uuid']); ?><?php echo foxiz_get_adsense_css($settings['tablet_size']); ?>
                        }

                        @media (min-width: 800px) {
						<?php echo '.res-'.trim($settings['uuid']); ?><?php echo foxiz_get_adsense_css($settings['desktop_size']); ?>
                        }
					</style>
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<ins class="adsbygoogle<?php echo ' res-' . trim( $settings['uuid'] ); ?>" style="display:inline-block" data-ad-client="<?php echo strip_tags( $spot['data_ad_client'] ); ?>" data-ad-slot="<?php echo strip_tags( $spot['data_ad_slot'] ); ?>"></ins>
					<script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			<?php else : ?>
				<div class="ad-script non-adsense edge-padding">
					<?php echo do_shortcode( $settings['code'] ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php return ob_get_clean();
	}
}

/**
 * @param $ad_script
 *
 * @return array|bool
 * get ad spot
 */
if ( ! function_exists( 'foxiz_adsense_spot' ) ) {
	function foxiz_adsense_spot( $ad_script ) {

		$data_ad = [];

		if ( preg_match( '/googlesyndication.com/', $ad_script ) ) {

			$array_ad_client_code = explode( 'data-ad-client', $ad_script );
			if ( empty( $array_ad_client_code[1] ) ) {
				return false;
			}
			preg_match( '/"([a-zA-Z0-9-\s]+)"/', $array_ad_client_code[1], $match_data_ad_client );
			$data_ad_client = str_replace( [ '"', ' ' ], [ '' ], $match_data_ad_client[1] );

			$array_ad_slot_code = explode( 'data-ad-slot', $ad_script );
			if ( empty( $array_ad_slot_code[1] ) ) {
				return false;
			}
			preg_match( '/"([a-zA-Z0-9\s]+)"/', $array_ad_slot_code[1], $match_data_add_slot );
			$data_ad_slot = str_replace( [ '"', ' ' ], [ '' ], $match_data_add_slot[1] );

			if ( ! empty( $data_ad_client ) && ! empty( $data_ad_slot ) ) {
				$data_ad['data_ad_client'] = $data_ad_client;
				$data_ad['data_ad_slot']   = $data_ad_slot;
			}

			return $data_ad;
		} else {
			return false;
		}
	}
}

/**
 * @param $size
 *
 * @return string
 * ad css
 */
if ( ! function_exists( 'foxiz_get_adsense_css' ) ) {
	function foxiz_get_adsense_css( $size ) {

		switch ( $size ) {
			case '1' :
				return '{ width: 728px; height: 90px; }';
			case '2' :
				return '{ width: 468px; height: 60px; }';
			case '3' :
				return '{ width: 234px; height: 60px; }';
			case '4' :
				return '{ width: 125px; height: 125px; }';
			case '5' :
				return '{ width: 120px; height: 600px; }';
			case '6' :
				return '{ width: 160px; height: 600px; }';
			case '7' :
				return '{ width: 180px; height: 150px; }';
			case '8' :
				return '{ width: 120px; height: 240px; }';
			case '9' :
				return '{ width: 200px; height: 200px; }';
			case '10' :
				return '{ width: 250px; height: 250px; }';
			case '11' :
				return '{ width: 300px; height: 250px; }';
			case '12' :
				return '{ width: 336px; height: 280px; }';
			case '13' :
				return '{ width: 300px; height: 600px; }';
			case '14' :
				return '{ width: 300px; height: 1050px; }';
			case '15' :
				return '{ width: 320px; height: 50px; }';
			case '16' :
				return '{ width: 970px; height: 90px; }';
			case '17' :
				return '{ width: 970px; height: 250px; }';
			case '18' :
				return '{ width: 320px; height: 100px; }';
			case '19' :
				return '{ width: 300px; height: 100px; }';
			default :
				return '{ display: none; }';
		}
	}
}

/**
 * widget custom ad
 */
if ( ! function_exists( 'foxiz_get_ad_image' ) ) {
	function foxiz_get_ad_image( $settings ) {

		if ( empty( $settings['image']['url'] ) ) {
			return false;
		}

		if ( ! empty( $settings['image']['height'] ) && ! empty( $settings['image']['width'] ) ) {
			$size = [
				$settings['image']['width'],
				$settings['image']['height'],
			];
		} else {
			$size = foxiz_get_image_size( $settings['image']['url'] );
		}

		if ( empty( $settings['image']['alt'] ) ) {
			$settings['image']['alt'] = esc_html__( 'Ad image', 'foxiz-core' );
		}
		if ( empty( $settings['dark_image']['alt'] ) ) {
			$settings['dark_image']['alt'] = esc_html__( 'Ad image', 'foxiz-core' );
		}

		$classes = 'ad-wrap ad-image-wrap';
		if ( empty( $settings['no_spacing'] ) ) {
			$classes .= ' edge-padding';
		}

		if ( ! empty( $settings['feat_lazyload'] ) && 'none' === $settings['feat_lazyload'] ) {
			$loading = 'loading="eager" decoding="async" ';
		} else {
			$loading = 'loading="lazy" decoding="async" ';
		}

		if ( foxiz_is_amp() ) {
			$loading = '';
		}

		$output = '<div class="' . strip_tags( $classes ) . '">';
		if ( ! empty( $settings['description'] ) ) {
			$output .= '<span class="ad-description is-meta">' . foxiz_strip_tags( $settings['description'] ) . '</span>';
		}
		$output .= '<div class="ad-image">';

		if ( ! empty( $settings['destination'] ) ) {
			$output .= '<a class="ad-destination" target="_blank" rel="noopener nofollow" href="' . esc_url( $settings['destination'] ) . '">';
		}
		if ( ! empty( $settings['dark_image']['url'] ) ) {
			$output .= '<img ' . $loading . 'data-mode="default" src="' . esc_url( $settings['image']['url'] ) . '" alt="' . strip_tags( $settings['image']['alt'] ) . '"';
			if ( ! empty( $size[0] ) && $size[1] ) {
				$output .= ' width="' . strip_tags( $size[0] ) . '" height="' . strip_tags( $size[1] ) . '"';
			}
			$output .= '/>';
			$output .= '<img ' . $loading . 'data-mode="dark" src="' . esc_url( $settings['dark_image']['url'] ) . '" alt="' . strip_tags( $settings['dark_image']['alt'] ) . '"';
			if ( ! empty( $size[0] ) && $size[1] ) {
				$output .= ' width="' . strip_tags( $size[0] ) . '" height="' . strip_tags( $size[1] ) . '"';
			}
			$output .= '/>';
		} else {
			$output .= '<img ' . $loading . 'src="' . esc_url( $settings['image']['url'] ) . '" alt="' . strip_tags( $settings['image']['alt'] ) . '"';
			if ( ! empty( $size[0] ) && $size[1] ) {
				$output .= ' width="' . strip_tags( $size[0] ) . '" height="' . strip_tags( $size[1] ) . '"';
			}
			$output .= '/>';
		}

		if ( ! empty( $settings['destination'] ) ) {
			$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'foxiz_auto_adsense' ) ) {
	function foxiz_auto_adsense() {

		/** amp auto ad */
		if ( foxiz_is_amp() ) {
			if ( foxiz_get_option( 'amp_ad_auto_code' ) ) {
				echo foxiz_get_option( 'amp_ad_auto_code' );
				if ( ! foxiz_get_option( 'ad_auto_allowed' ) ) {
					$GLOBALS['foxiz_disallowed_ads'] = 'yes';
				}
			}

			return;
		}

		$code = foxiz_get_auto_adsense();

		if ( ! empty( $code ) ) {
			if ( strpos( $code, 'googlesyndication.com/pagead/js/adsbygoogle.js' ) ) {
				if ( ! foxiz_get_option( 'ad_auto_allowed' ) ) {
					$GLOBALS['foxiz_disallowed_ads'] = 'yes';
				}
			}

			echo foxiz_get_auto_adsense();
		}
	}
}

if ( ! function_exists( 'foxiz_get_auto_adsense' ) ) {
	function foxiz_get_auto_adsense() {

		$code = foxiz_get_option( 'ad_auto_code' );

		if ( empty( $code ) ) {
			return false;
		}

		if ( is_404() && ! foxiz_get_option( 'page_404_ads' ) ) {
			return false;
		}

		$woocommerce_disabled = foxiz_get_option( 'disable_ad_auto_wc' );
		if ( ! empty( $woocommerce_disabled ) && class_exists( 'WooCommerce' ) && ( is_shop() || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
			return false;
		}

		if ( is_singular() ) {
			$auto_ads = rb_get_meta( 'auto_ads' );
			if ( '-1' === (string) $auto_ads ) {
				return false;
			}
		}

		return $code;
	}
}

if ( ! function_exists( 'foxiz_amp_ad' ) ) {
	function foxiz_amp_ad( $settings ) {

		if ( ! empty( $GLOBALS['foxiz_disallowed_ads'] ) || empty( $settings['type'] ) || ( is_404() && ! foxiz_get_option( 'page_404_ads' ) ) ) {
			return;
		}

		if ( empty( $settings['classname'] ) ) {
			$settings['classname'] = 'amp-ad';
		}

		if ( '1' === (string) $settings['type'] ) {
			if ( ! empty( $settings['client'] ) && ! empty ( $settings['slot'] ) ) {
				echo '<div class="' . strip_tags( $settings['classname'] ) . '"><amp-ad ';
				if ( empty( $settings['size'] ) || '1' === (string) $settings['size'] ) {
					echo 'layout="responsive" width="300" height="250"';
				} else {
					echo 'layout="fixed-height" height="90"';
				}
				echo ' type="adsense" data-ad-format="auto" data-ad-client="ca-pub-' . $settings['client'] . '"  data-ad-slot="' . $settings['slot'] . '">';
				echo '</amp-ad></div>';
			}
		} else {
			if ( ! empty( $settings['custom'] ) ) {
				echo '<div class="' . strip_tags( $settings['classname'] ) . '">' . html_entity_decode( $settings['custom'] ) . '</div>';
			}
		}
	}
}
