<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'rb_social_follower' ) ) {
	function rb_social_follower( $instance = [], $style = 1 ) {

		$classes   = [];
		$classes[] = 'socials-counter h6';
		if ( ! empty( $instance['color_scheme'] ) ) {
			$classes[] = 'light-scheme';
		}

		$style = (int) $style;

		switch ( $style ) {
			case 1:
				$classes[] = 'is-style-1 is-gcol-4 is-icon-bg';
				break;
			case 2:
				$classes[] = 'is-style-2 is-gcol-4 is-icon-color is-h-icon-bg';
				break;
			case 3:
				$classes[] = 'is-style-3 is-icon-color is-h-bg';
				break;
			case 4:
				$classes[] = 'is-style-4 is-icon-color is-h-bg';
				break;
			case 5:
				$classes[] = 'is-style-5 is-bg';
				break;
			case 6:
				$classes[] = 'is-style-6 is-icon-color is-h-bg';
				break;
			case 7:
				$classes[] = 'is-style-7 is-icon-color is-h-bg';
				break;
			case 8:
				$classes[] = 'is-style-8 is-bg';
				break;
			case 9:
				$classes[] = 'is-style-9 is-gcol-4 is-icon-color is-h-icon-bg';
				break;
			case 10:
				$classes[] = 'is-style-10 is-gstyle-3 is-gcol-1 is-icon-color is-h-icon-bg';
				break;
			case 11:
				$classes[] = 'is-style-11 is-gstyle-3 is-gcol-1 is-icon-bg';
				break;
			case 12:
				$classes[] = 'is-style-12 is-gstyle-3 is-gcol-1 is-icon-color is-h-bg';
				break;
			case 13:
				$classes[] = 'is-style-13 is-gstyle-3 is-gcol-1 is-bg';
				break;
			case 14:
				$classes[] = 'is-style-14 is-gstyle-3 is-bg';
				break;
			case 15:
				$classes[] = 'is-style-15 is-gstyle-3 is-icon-color is-h-bg';
				break;
		}
		$classes = implode( ' ', $classes );
		$output  = '';

		$output .= '<div class="' . esc_attr( $classes ) . '">';
		$output .= '<div class="social-follower effect-fadeout">';

		if ( ! empty( $instance['facebook_page'] ) ) {
			$output .= '<div class="follower-el fb-follower">';
			$output .= '<a target="_blank" href="https://facebook.com/' . esc_html( $instance['facebook_page'] ) . '" class="facebook" aria-label="Facebook" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-facebook" aria-hidden="true"></i></span>';
			if ( ! empty( $instance['facebook_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['facebook_count'] ) . '</span>';
				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Like', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Facebook', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Like', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['twitter_user'] ) ) {
			$output .= '<div class="follower-el twitter-follower">';
			$output .= '<a target="_blank" href="https://twitter.com/' . esc_html( $instance['twitter_user'] ) . '" class="twitter" aria-label="Twitter" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-twitter" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['twitter_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['twitter_count'] ) . '</span>';
				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Twitter', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['pinterest_user'] ) ) {
			$output .= '<div class="follower-el pinterest-follower">';
			$output .= '<a target="_blank" href="https://pinterest.com/' . esc_html( $instance['pinterest_user'] ) . '" class="pinterest" aria-label="Pinterest" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-pinterest" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['pinterest_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['pinterest_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Pin', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Pinterest', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Pin', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['instagram_user'] ) ) {
			$output .= '<div class="follower-el instagram-follower">';
			$output .= '<a target="_blank" href="https://instagram.com/' . esc_html( $instance['instagram_user'] ) . '" class="instagram" aria-label="Instagram" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-instagram" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['instagram_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['instagram_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Instagram', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['youtube_link'] ) ) {
			$output .= '<div class="follower-el youtube-follower">';
			$output .= '<a target="_blank" href="' . esc_html( $instance['youtube_link'] ) . '" class="youtube" aria-label="Youtube" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-youtube" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['youtube_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['youtube_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Subscribers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Subscribe', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Youtube', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Subscribe', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['tiktok_link'] ) ) {
			$output .= '<div class="follower-el tiktok-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['tiktok_link'] ) . '" class="tiktok" aria-label="Tiktok" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-tiktok" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['tiktok_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['tiktok_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Tiktok', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['telegram_link'] ) ) {
			$output .= '<div class="follower-el telegram-follower">';
			$output .= '<a target="_blank" href="' . esc_html( $instance['telegram_link'] ) . '" class="telegram" aria-label="Telegram" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-telegram" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['telegram_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['telegram_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Members', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Telegram', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['whatsapp_link'] ) ) {
			$output .= '<div class="follower-el whatsapp-follower">';
			$output .= '<a target="_blank" href="' . esc_html( $instance['whatsapp_link'] ) . '" class="whatsapp" aria-label="WhatsApp" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-whatsapp" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['whatsapp_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['whatsapp_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'WhatsApp', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['gnews_link'] ) ) {
			$output .= '<div class="follower-el gnews-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['gnews_link'] ) . '" class="gnews" aria-label="Google News" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-gnews" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['gnews_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['gnews_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Google News', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['linkedin_link'] ) ) {
			$output .= '<div class="follower-el linkedin-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['linkedin_link'] ) . '" class="linkedin" aria-label="LinkedIn" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-linkedin" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['linkedin_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['linkedin_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'LinkedIn', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['medium_link'] ) ) {
			$output .= '<div class="follower-el medium-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['medium_link'] ) . '" class="medium" aria-label="Medium" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-medium" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['medium_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['medium_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Medium', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['flipboard_link'] ) ) {
			$output .= '<div class="follower-el flipboard-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['flipboard_link'] ) . '" class="flipboard" aria-label="Flipboard" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-flipboard" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['flipboard_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['flipboard_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Flipboard', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['twitch_link'] ) ) {
			$output .= '<div class="follower-el twitch-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['twitch_link'] ) . '" class="twitch" aria-label="Twitch" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-twitch" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['twitch_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['twitch_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Twitch', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['steam_link'] ) ) {
			$output .= '<div class="follower-el steam-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['steam_link'] ) . '" class="steam" aria-label="Steam" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-steam" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['steam_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['steam_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Members', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Steam', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['tumblr_link'] ) ) {
			$output .= '<div class="follower-el tumblr-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['tumblr_link'] ) . '" class="tumblr" aria-label="Tumblr" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-tumblr" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['tumblr_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['tumblr_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Tumblr', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['discord_link'] ) ) {
			$output .= '<div class="follower-el discord-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['discord_link'] ) . '" class="discord" aria-label="Discord" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-discord" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['discord_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['discord_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Members', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Join', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Discord', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Join', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['paypal_link'] ) ) {
			$output .= '<div class="follower-el paypal-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['paypal_link'] ) . '" class="paypal" aria-label="Paypal" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-paypal" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['paypal_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['paypal_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Send', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'PayPal', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Send', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['patreon_link'] ) ) {
			$output .= '<div class="follower-el patreon-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['patreon_link'] ) . '" class="patreon" aria-label="Patreon" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-patreon" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['patreon_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['patreon_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Members', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Patreon', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['soundcloud_user'] ) ) {
			$output .= '<div class="follower-el soundcloud-follower">';
			$output .= '<a target="_blank" href="https://soundcloud.com/' . esc_html( $instance['soundcloud_user'] ) . '" class="soundcloud" aria-label="SoundCloud" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-soundcloud" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['soundcloud_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['soundcloud_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'SoundCloud', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['vimeo_user'] ) ) {
			$output .= '<div class="follower-el vimeo-follower">';
			$output .= '<a target="_blank" href="https://vimeo.com/' . esc_html( $instance['vimeo_user'] ) . '" class="vimeo" aria-label="Vimeo" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-vimeo" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['vimeo_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['vimeo_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Vimeo', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['dribbble_user'] ) ) {
			$output .= '<div class="follower-el dribbble-follower">';
			$output .= '<a target="_blank" href="https://dribbble.com/' . esc_html( $instance['dribbble_user'] ) . '" class="dribbble" aria-label="Dribbble" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-dribbble" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['dribbble_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['dribbble_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}

				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Dribbble', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['snapchat_link'] ) ) {
			$output .= '<div class="follower-el snapchat-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['snapchat_link'] ) . '" class="snapchat" aria-label="Snapchat" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-snapchat" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['snapchat_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['snapchat_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Subscribers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Subscribe', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Snapchat', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Subscribe', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['quora_link'] ) ) {
			$output .= '<div class="follower-el quora-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['quora_link'] ) . '" class="quora" aria-label="Quora" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-quora" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['quora_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['quora_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Quora', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['spotify_link'] ) ) {
			$output .= '<div class="follower-el spotify-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['spotify_link'] ) . '" class="spotify" aria-label="Spotify" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-spotify" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['spotify_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['spotify_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Listeners', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Spotify', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['truth_link'] ) ) {
			$output .= '<div class="follower-el truth-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['truth_link'] ) . '" class="truth" aria-label="Truth Social" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-truth" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['truth_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['truth_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Truth', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['threads_link'] ) ) {
			$output .= '<div class="follower-el threads-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['threads_link'] ) . '" class="threads" aria-label="Threads" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-threads" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['threads_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['threads_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Followers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'Threads', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		if ( ! empty( $instance['rss_link'] ) ) {
			$output .= '<div class="follower-el rss-follower">';
			$output .= '<a target="_blank" href="' . esc_url( $instance['rss_link'] ) . '" class="rss" aria-label="rss" rel="noopener nofollow"></a>';
			$output .= '<span class="follower-inner">';
			$output .= '<span class="fnicon"><i class="rbi rbi-rss" aria-hidden="true"></i></span>';

			if ( ! empty( $instance['rss_count'] ) ) {
				$output .= '<span class="fntotal">' . foxiz_pretty_number( $instance['rss_count'] ) . '</span>';

				if ( $style === 10 || $style === 11 || $style === 12 || $style === 13 || $style === 14 || $style === 15 ) {
					$output .= '<span class="fnlabel">' . foxiz_html__( 'Readers', 'foxiz-core' ) . '</span>';
				}
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			} else {
				$output .= '<span class="fnlabel">' . foxiz_html__( 'RSS Feed', 'foxiz-core' ) . '</span>';
				$output .= '<span class="text-count">' . foxiz_html__( 'Follow', 'foxiz-core' ) . '</span>';
			}

			$output .= '</span>';
			$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}




