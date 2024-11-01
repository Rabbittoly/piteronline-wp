<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'init', [ 'Foxiz_Optimized', 'get_instance' ] );

if ( ! class_exists( 'Foxiz_Optimized', false ) ) {
	class Foxiz_Optimized {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'wp_head', [ $this, 'site_description' ], 2 );
			add_action( 'wp_head', [ $this, 'schema_organization' ], 5 );
			add_action( 'wp_head', [ $this, 'site_links' ], 10 );
			add_action( 'wp_head', [ $this, 'article_markup' ], 12 );
			add_action( 'wp_head', [ $this, 'review_markup' ], 15 );
			add_action( 'wp_head', [ $this, 'live_blog_markup' ], 16 );
			add_action( 'wp_head', [ $this, 'podcast_markup' ], 20 );
			add_action( 'wp_head', [ $this, 'breadcrumb_markup' ], 25 );

			/** use jetpack og tags */
			if ( foxiz_get_option( 'open_graph' ) ) {
				if ( class_exists( 'Jetpack' ) ) {
					add_filter( 'jetpack_enable_open_graph', '__return_true', 10 );
				} else {
					add_action( 'wp_head', [ $this, 'open_graph' ], 20 );
				}
			}
			add_action( 'wp_footer', [ $this, 'post_list_markup' ] );
			add_filter( 'post_class', [ $this, 'remove_hatom' ], 10, 1 );

			add_action( 'init', [ $this, 'set_elementor_font_display' ], 20 );
			add_filter( 'pvc_enqueue_styles', '__return_false' );
			add_filter( 'wp_get_attachment_image_attributes', [ $this, 'optimize_featured_image' ], 10, 3 );
			add_filter( 'wp_lazy_loading_enabled', [ $this, 'lazyload_content_image' ] );
			add_filter( 'wp_img_tag_add_srcset_and_sizes_attr', [ $this, 'remove_srcset_content_image' ], 10, 1 );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_optimized' ], 999 );
			add_action( 'wp_head', [ $this, 'start_head_buffer' ], 0 );
			add_action( 'wp_head', [ $this, 'end_head_buffer' ], PHP_INT_MAX );
			add_action( 'wp_head', [ $this, 'preload_font_icon' ], 9 );
			add_filter( 'get_the_date', [ $this, 'force_updated_date' ], 999, 3 );
			add_filter( 'get_the_time', [ $this, 'force_updated_time' ], 999, 3 );
		}

		private $open_graph_conflicting_plugins = [
			'2-click-socialmedia-buttons/2-click-socialmedia-buttons.php',
			'add-link-to-facebook/add-link-to-facebook.php',
			'add-meta-tags/add-meta-tags.php',
			'complete-open-graph/complete-open-graph.php',
			'easy-facebook-share-thumbnails/esft.php',
			'heateor-open-graph-meta-tags/heateor-open-graph-meta-tags.php',
			'facebook/facebook.php',
			'facebook-awd/AWD_facebook.php',
			'facebook-featured-image-and-open-graph-meta-tags/fb-featured-image.php',
			'facebook-meta-tags/facebook-metatags.php',
			'wonderm00ns-simple-facebook-open-graph-tags/wonderm00n-open-graph.php',
			'facebook-revised-open-graph-meta-tag/index.php',
			'facebook-thumb-fixer/_facebook-thumb-fixer.php',
			'facebook-and-digg-thumbnail-generator/facebook-and-digg-thumbnail-generator.php',
			'network-publisher/networkpub.php',
			'nextgen-facebook/nextgen-facebook.php',
			'social-networks-auto-poster-facebook-twitter-g/NextScripts_SNAP.php',
			'og-tags/og-tags.php',
			'opengraph/opengraph.php',
			'open-graph-protocol-framework/open-graph-protocol-framework.php',
			'seo-facebook-comments/seofacebook.php',
			'seo-ultimate/seo-ultimate.php',
			'sexybookmarks/sexy-bookmarks.php',
			'shareaholic/sexy-bookmarks.php',
			'sharepress/sharepress.php',
			'simple-facebook-connect/sfc.php',
			'social-discussions/social-discussions.php',
			'social-sharing-toolkit/social_sharing_toolkit.php',
			'socialize/socialize.php',
			'squirrly-seo/squirrly.php',
			'only-tweet-like-share-and-google-1/tweet-like-plusone.php',
			'wordbooker/wordbooker.php',
			'wpsso/wpsso.php',
			'wp-caregiver/wp-caregiver.php',
			'wp-facebook-like-send-open-graph-meta/wp-facebook-like-send-open-graph-meta.php',
			'wp-facebook-open-graph-protocol/wp-facebook-ogp.php',
			'wp-ogp/wp-ogp.php',
			'zoltonorg-social-plugin/zosp.php',
			'wp-fb-share-like-button/wp_fb_share-like_widget.php',
			'open-graph-metabox/open-graph-metabox.php',
			'seo-by-rank-math/rank-math.php',
			'slim-seo/slim-seo.php',
			'all-in-one-seo-pack/all_in_one_seo_pack.php',
		];

		private $meta_description_conflicting_plugins = [
			'seo-by-rank-math/rank-math.php',
			'slim-seo/slim-seo.php',
			'all-in-one-seo-pack/all_in_one_seo_pack.php',
			'wp-wordpress-seo/wp-seo.php',
			'seo-ultimate/seo-ultimate.php',
		];

		/**
		 * @return bool
		 */
		public function check_conflict_open_graph() {

			$active_plugins = foxiz_get_active_plugins();
			if ( in_array( 'wp-wordpress-seo/wp-seo.php', $active_plugins, true ) ) {
				$yoast_social = get_option( 'wpseo_social' );
				if ( ! empty( $yoast_social['opengraph'] ) ) {
					return true;
				}
			}

			if ( in_array( 'wp-seopress/seopress.php', $active_plugins, true ) ) {
				$seopress_setting = get_option( 'seopress_social_option_name' );
				if ( ! empty( $seopress_setting['seopress_social_facebook_og'] ) ) {
					return true;
				}
			}

			if ( ! empty( $active_plugins ) ) {
				foreach ( $this->open_graph_conflicting_plugins as $plugin ) {
					if ( in_array( $plugin, $active_plugins, true ) ) {
						return true;
					}
				}
			}

			return false;
		}

		public function get_description( $post_id ) {

			if ( empty( $post_id ) ) {
				return false;
			}

			$description = rb_get_meta( 'meta_description', $post_id );

			if ( empty( $description ) ) {
				$description = rb_get_meta( 'tagline', $post_id );
			}
			if ( empty( $description ) ) {
				$description = get_post_field( 'post_excerpt', $post_id );
			}
			if ( empty( $description ) ) {
				$description = wp_trim_words( get_post_field( 'post_content', $post_id ), 52, '' );
			}

			return $description;
		}

		/**
		 * @return false
		 */
		function site_links() {

			if ( ! foxiz_get_option( 'website_markup' ) ) {
				return false;
			}

			$home_url = home_url( '/' );
			$json_ld  = [
				'@context'        => foxiz_protocol() . '://schema.org',
				'@type'           => 'WebSite',
				'@id'             => $home_url . '#website',
				'url'             => $home_url,
				'name'            => get_bloginfo( 'name' ),
				'potentialAction' => [
					'@type'       => 'SearchAction',
					'target'      => $home_url . '?s={search_term_string}',
					'query-input' => 'required name=search_term_string',
				],
			];

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		public function site_description() {

			if ( ! foxiz_get_option( 'website_description' ) ) {
				return false;
			}

			$active_plugins = foxiz_get_active_plugins();
			if ( ! empty( $active_plugins ) ) {
				foreach ( $this->meta_description_conflicting_plugins as $plugin ) {
					if ( in_array( $plugin, $active_plugins, true ) ) {
						return false;
					}
				}
			}

			if ( is_front_page() ) {
				$content = foxiz_get_option( 'site_description' );
				if ( empty( $content ) ) {
					$content = get_bloginfo( 'description' );
				}
				if ( ! empty ( $content ) ) {
					echo '<meta name="description" content="' . wp_strip_all_tags( $content ) . '">';
				}
			} elseif ( is_single() ) {
				$content = $this->get_description( get_the_ID() );
				if ( ! empty( $content ) ) {
					echo '<meta name="description" content="' . wp_strip_all_tags( $content ) . '">';
				}
			} elseif ( is_page() ) {
				$content = rb_get_meta( 'meta_description' );
				if ( ! empty( $content ) ) {
					echo '<meta name="description" content="' . wp_strip_all_tags( $content ) . '">';
				}
			} elseif ( is_archive() ) {
				$content = get_the_archive_description();
				if ( ! empty( $content ) ) {
					echo '<meta name="description" content="' . wp_strip_all_tags( $content ) . '">';
				}
			}

			return false;
		}

		function schema_organization() {

			if ( ! foxiz_get_option( 'organization_markup' ) ) {
				return false;
			}

			$site_street   = foxiz_get_option( 'site_street' );
			$site_locality = foxiz_get_option( 'site_locality' );
			$site_phone    = foxiz_get_option( 'site_phone' );
			$site_email    = foxiz_get_option( 'site_email' );
			$postal_code   = foxiz_get_option( 'postal_code' );

			$home_url = home_url( '/' );

			$json_ld = [
				'@context'  => foxiz_protocol() . '://schema.org',
				'@type'     => 'Organization',
				'legalName' => get_bloginfo( 'name' ),
				'url'       => $home_url,
			];

			if ( ! empty( $site_street ) || ! empty( $site_locality ) ) {
				$json_ld['address']['@type'] = 'PostalAddress';

				if ( ! empty( $site_street ) ) {
					$json_ld['address']['streetAddress'] = esc_html( $site_street );
				}

				if ( ! empty( $site_locality ) ) {
					$json_ld['address']['addressLocality'] = esc_html( $site_locality );
				}

				if ( ! empty( $postal_code ) ) {
					$json_ld['address']['postalCode'] = esc_html( $postal_code );
				}
			}

			if ( ! empty( $site_email ) ) {
				$json_ld['email'] = esc_html( $site_email );
			}

			if ( ! empty( $site_phone ) ) {
				$json_ld['contactPoint'] = [
					'@type'       => 'ContactPoint',
					'telephone'   => esc_html( $site_phone ),
					'contactType' => 'customer service',
				];
			}

			$logo = foxiz_get_option( 'logo_organization' );
			if ( empty( $logo['url'] ) ) {
				$logo = foxiz_get_option( 'logo' );
			}
			if ( ! empty( $logo['url'] ) ) {
				$json_ld['logo'] = $logo['url'];
			}
			$social = [
				foxiz_get_option( 'facebook' ),
				foxiz_get_option( 'twitter' ),
				foxiz_get_option( 'instagram' ),
				foxiz_get_option( 'pinterest' ),
				foxiz_get_option( 'linkedin' ),
				foxiz_get_option( 'tumblr' ),
				foxiz_get_option( 'flickr' ),
				foxiz_get_option( 'skype' ),
				foxiz_get_option( 'snapchat' ),
				foxiz_get_option( 'myspace' ),
				foxiz_get_option( 'youtube' ),
				foxiz_get_option( 'bloglovin' ),
				foxiz_get_option( 'digg' ),
				foxiz_get_option( 'dribbble' ),
				foxiz_get_option( 'soundcloud' ),
				foxiz_get_option( 'vimeo' ),
				foxiz_get_option( 'reddit' ),
				foxiz_get_option( 'vk' ),
				foxiz_get_option( 'telegram' ),
				foxiz_get_option( 'whatsapp' ),
				foxiz_get_option( 'rss' ),
			];

			foreach ( $social as $key => $el ) {
				if ( empty( $el ) || '#' === $el ) {
					unset( $social[ $key ] );
				}
			}

			if ( count( $social ) ) {
				$json_ld['sameAs'] = array_values( $social );
			}

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		function get_authors( $post_id ) {

			$data        = [];
			$author_id   = get_post_field( 'post_author', $post_id );
			$author_name = get_the_author_meta( 'display_name', $author_id );
			$author_link = get_the_author_meta( 'url', $author_id );
			if ( empty( $author_link ) ) {
				$author_link = get_author_posts_url( $author_id );
			}

			if ( ! function_exists( 'get_post_authors' ) ) {
				$data = [
					'@type' => 'Person',
					'name'  => $author_name,
					'url'   => $author_link,
				];
			} else {
				$authors = get_post_authors( $post_id );
				if ( is_array( $authors ) && count( $authors ) > 1 ) {
					foreach ( $authors as $auth ) {
						$auth_id   = $auth->ID;
						$auth_link = get_the_author_meta( 'url', $auth_id );
						if ( empty( $auth_link ) ) {
							$auth_link = get_author_posts_url( $auth_id );
						}
						$data[] = [
							'@type' => 'Person',
							'name'  => get_the_author_meta( 'display_name', $auth_id ),
							'url'   => $auth_link,
						];
					}
				} else {
					$data = [
						'@type' => 'Person',
						'name'  => $author_name,
						'url'   => $author_link,
					];
				}
			}

			return $data;
		}

		function get_publisher() {

			$logo = foxiz_get_option( 'logo_organization' );
			if ( empty( $logo['url'] ) ) {
				$logo = foxiz_get_option( 'logo' );
			}
			if ( empty( $logo['url'] ) ) {
				$logo = foxiz_get_option( 'dark_logo' );
			}
			if ( ! empty( $logo['url'] ) ) {
				$publisher_logo = esc_url( $logo['url'] );
			} else {
				$publisher_logo = '';
			}

			return [
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url( '/' ),
				'logo'  => [
					'@type' => 'ImageObject',
					'url'   => $publisher_logo,
				],
			];
		}

		function get_current_page() {

			$page = get_query_var( 'page' );
			if ( ! empty( $page ) ) {
				return (int) $page;
			}

			$paged = get_query_var( 'paged' );
			if ( ! empty( $paged ) ) {
				return (int) $paged;
			}

			return 1;
		}

		function article_markup() {

			if ( ! is_singular( 'post' ) || foxiz_conflict_schema() || foxiz_is_doing_ajax() ) {
				return false;
			}

			$post_id = get_the_ID();
			$markup  = rb_get_meta( 'article_markup' );

			if ( empty( $markup ) || 'default' === $markup ) {
				$markup = foxiz_get_option( 'single_post_article_markup' );
			}

			if ( empty( $markup ) || '-1' === $markup ) {
				return false;
			}

			$type = 'Article';

			if ( '2' === (string) $markup ) {
				$type = 'NewsArticle';
			}

			$categories = get_the_category( $post_id );
			$image      = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

			$json_ld = [
				'@context'         => foxiz_protocol() . '://schema.org',
				'@type'            => $type,
				'headline'         => get_the_title(),
				'description'      => $this->get_description( $post_id ),
				'mainEntityOfPage' => [
					"@type" => 'WebPage',
					'@id'   => get_permalink(),
				],
				'author'           => $this->get_authors( $post_id ),
				'publisher'        => $this->get_publisher(),
				'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
				'datePublished'    => get_the_date( DATE_W3C, $post_id ),
				'image'            => [
					'@type'  => 'ImageObject',
					'url'    => ! empty( $image[0] ) ? esc_url( $image[0] ) : '',
					'width'  => ! empty( $image[1] ) ? esc_attr( $image[1] ) : '',
					'height' => ! empty( $image[2] ) ? esc_attr( $image[2] ) : '',
				],
				'commentCount'     => get_comment_count( $post_id )['approved'],
			];

			if ( is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					$json_ld['articleSection'][] = $category->name;
				}
			}

			$current_page = $this->get_current_page();
			if ( 1 < $current_page ) {
				$json_ld['pagination'] = $current_page;
			}

			if ( foxiz_get_option( 'single_post_video_markup' ) && 'video' === get_post_format( $post_id ) ) {

				$video_hosted = rb_get_meta( 'video_hosted', $post_id );
				$duration     = rb_get_meta( 'duration', $post_id );

				if ( ! empty( $hosted_video ) ) {
					$attachment_url = wp_get_attachment_url( $video_hosted );
					if ( ! empty( $attachment_url ) ) {

						$json_ld['video'] = [
							'@type'        => 'VideoObject',
							'name'         => get_the_title(),
							'thumbnailUrl' => ! empty( $image[0] ) ? esc_url( $image[0] ) : '',
							'description'  => $this->get_description( $post_id ),
							'contentUrl'   => $attachment_url,
							'uploadDate'   => get_the_date( DATE_W3C, $post_id ),
						];
					}
				} else {

					$iframe_code = rb_get_meta( 'video_embed', $post_id );
					if ( empty( $iframe_code ) ) {
						$video_url = rb_get_meta( 'video_url', $post_id );
						if ( ! empty( $video_url ) ) {
							$iframe_code = wp_oembed_get( $video_url, [
								'width'  => 740,
								'height' => 415,
							] );
						}
					}
					if ( ! empty( $iframe_code ) ) {
						$pattern = '/<iframe.*?src="(.*?)".*?>/i';
						if ( preg_match( $pattern, $iframe_code, $matches ) ) {
							$embed_url = $matches[1];
						}
					}

					if ( ! empty( $embed_url ) ) {
						$embed_url        = explode( '?', $embed_url )[0];
						$json_ld['video'] = [
							'@type'        => 'VideoObject',
							'name'         => get_the_title(),
							'thumbnailUrl' => ! empty( $image[0] ) ? esc_url( $image[0] ) : '',
							'description'  => $this->get_description( $post_id ),
							'embedUrl'     => $embed_url,
							'uploadDate'   => get_the_date( DATE_W3C, $post_id ),
						];
					}
				}

				if ( ! empty( $duration ) && ! empty( $json_ld['video'] ) ) {
					$sec = strtotime( $duration, 0 );
					if ( intval( $sec ) ) {
						$json_ld['video']['duration'] = $this->iso8601_duration( $sec );
					}
				}
			}

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		/**
		 * @return false
		 */
		function review_markup() {

			if ( ! is_single() || ! function_exists( 'foxiz_get_review_settings' ) || foxiz_is_doing_ajax() ) {
				return false;
			}

			$markup = rb_get_meta( 'review_markup' );
			if ( ( ! empty( $markup ) && '-1' === (string) $markup ) || ( empty( $markup ) || 'default' === $markup ) && ! foxiz_get_option( 'single_post_review_markup' ) ) {
				return false;
			}

			$post_id  = get_the_ID();
			$settings = foxiz_get_review_settings( $post_id );
			if ( empty( $settings ) || ! is_array( $settings ) ) {
				return false;
			}

			$bestRating   = 5;
			$worstRating  = 1;
			$average      = 5;
			$rating_value = 5;
			$rating_count = 3;

			if ( empty( $settings['type'] ) || 'score' === $settings['type'] ) {
				$bestRating   = 10;
				$worstRating  = 1;
				$average      = 10;
				$rating_value = 10;
				$rating_count = 3;
			}

			if ( isset( $settings['average'] ) ) {
				$average      = $settings['average'];
				$rating_value = $settings['average'];
			}

			$author = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
			$image  = get_the_post_thumbnail_url( $post_id, 'full' );
			$sku    = get_post_field( 'post_name', $post_id );
			$name   = get_the_title( $post_id );

			if ( isset( $settings['summary'] ) ) {
				$description = $settings['summary'];
			} else {
				$description = $this->get_description( $post_id );
			}

			if ( ! empty( $settings['user_rating']['count'] ) ) {
				$rating_count = $settings['user_rating']['count'];
			}

			if ( ! empty( $settings['user_rating']['average'] ) ) {
				if ( empty( $settings['type'] ) || 'score' === $settings['type'] ) {
					$rating_value = floatval( $settings['user_rating']['average'] ) * 2;
				} else {
					$rating_value = $settings['user_rating']['average'];
				}
			}

			$json_ld = [
				'@context'    => foxiz_protocol() . '://schema.org',
				'@type'       => 'Product',
				'description' => $description,
				'image'       => $image,
				'name'        => $name,
				'mpn'         => $post_id,
				'sku'         => $sku,
				'brand'       => [
					'@type' => 'Brand',
					'name'  => get_bloginfo( 'name' ),
				],
			];

			$json_ld['review'] = [
				'author'       => [
					'@type' => 'Person',
					'name'  => $author,
				],
				'@type'        => 'Review',
				'reviewRating' => [
					'@type'       => 'Rating',
					'ratingValue' => $average,
					'bestRating'  => $bestRating,
					'worstRating' => $worstRating,
				],
			];

			if ( ! empty( $settings['user'] ) ) {
				$json_ld['aggregateRating'] = [
					'@type'       => 'AggregateRating',
					'ratingValue' => $rating_value,
					'ratingCount' => $rating_count,
					'bestRating'  => $bestRating,
					'worstRating' => $worstRating,
				];
			}

			if ( ! empty( $settings['destination'] ) && ! empty( $settings['price'] ) ) {
				if ( empty( $settings['currency'] ) ) {
					$settings['currency'] = 'USD';
				}
				$json_ld['offers'] = [
					'@type'         => 'Offer',
					'url'           => esc_url( $settings['destination'] ),
					'price'         => preg_replace( '/\D/', '', $settings['price'] ),
					'priceCurrency' => $settings['currency'],
					'itemCondition' => 'https://schema.org/UsedCondition',
					'availability'  => 'https://schema.org/InStock',
				];

				if ( ! empty( $settings['expired'] ) ) {
					$json_ld['offers']['priceValidUntil'] = esc_attr( $settings['expired'] );
				}
			}

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		function is_live_blog( $post_id ) {

			$live_blog = rb_get_meta( 'live_blog', $post_id );
			if ( empty( $live_blog ) || 'default' === $live_blog ) {
				return false;
			}

			return true;
		}

		function live_blog_markup() {

			if ( ! is_single() || ! foxiz_get_option( 'single_post_live_markup' ) || foxiz_is_doing_ajax() ) {
				return false;
			}
			$post_id = get_the_ID();
			if ( ! $this->is_live_blog( $post_id ) ) {
				return false;
			}
			$location = rb_get_meta( 'live_location', $post_id );

			$json_ld = [
				'@context'          => foxiz_protocol() . '://schema.org',
				'@type'             => 'LiveBlogPosting',
				'@id'               => get_permalink( $post_id ),
				'headline'          => get_the_title( $post_id ),
				'description'       => $this->get_description( $post_id ),
				'about'             => [
					'@type'     => 'Event',
					'startDate' => get_the_date( DATE_W3C, $post_id ),
					'name'      => get_the_title( $post_id ),
					'location'  => $location ? $location : '',
				],
				'author'            => $this->get_authors( $post_id ),
				'publisher'         => $this->get_publisher(),
				'datePublished'     => get_the_date( DATE_W3C, $post_id ),
				'coverageStartTime' => get_the_date( DATE_W3C, $post_id ),
				'dateModified'      => get_the_modified_date( DATE_W3C, $post_id ),
			];
			$medata  = get_post_meta( $post_id, 'ruby_live_metadata', true );
			if ( ! empty( $medata ) ) {
				foreach ( $medata as $index => $metas ) {
					$medata[ $index ]['@type'] = 'BlogPosting';
				}
				$json_ld['liveBlogUpdate'] = $medata;
			}

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		function open_graph() {

			if ( $this->check_conflict_open_graph() || foxiz_is_amp() ) {
				return false;
			}

			$facebook_app_id = foxiz_get_option( 'facebook_app_id' ); ?>
			<meta property="og:title" content="<?php echo $this->get_og_title(); ?>"/>
			<meta property="og:url" content="<?php echo $this->get_og_permalink(); ?>"/>
			<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>"/>
			<?php if ( is_front_page() ) : ?>
				<meta property="og:description" content="<?php get_bloginfo( 'description' ); ?>"/>
			<?php endif;
			if ( $this->og_get_image() ) : ?>
				<meta property="og:image" content="<?php echo $this->og_get_image(); ?>"/>
			<?php endif; ?><?php if ( ! empty( $facebook_app_id ) ) : ?>
				<meta property="fb:facebook_app_id" content="<?php echo esc_attr( $facebook_app_id ); ?>"/>
			<?php endif;
			if ( is_single() ) :

				global $post;

				$count = intval( get_post_meta( $post->ID, 'foxiz_content_total_word', true ) );
				if ( $count > 0 ) {
					$read_speed = intval( foxiz_get_option( 'read_speed' ) );
					if ( empty( $read_speed ) ) {
						$read_speed = 130;
					}
					$minutes = floor( $count / $read_speed );
					$second  = floor( ( $count / $read_speed ) * 60 ) % 60;
					if ( $second > 30 ) {
						$minutes ++;
					}
				}
				?>
				<meta property="og:type" content="article"/>
				<meta property="article:published_time" content="<?php echo get_the_date( DATE_W3C, $post ); ?>"/>
				<meta property="article:modified_time" content="<?php echo get_the_modified_date( DATE_W3C, $post ); ?>"/>
				<meta name="author" content="<?php the_author_meta( 'display_name', $post->post_author ); ?>"/>
				<meta name="twitter:card" content="summary_large_image"/>
				<meta name="twitter:creator" content="<?php echo '@' . foxiz_get_twitter_name(); ?>"/>
				<meta name="twitter:label1" content="<?php esc_html_e( 'Written by', 'foxiz-core' ) ?>"/>
				<meta name="twitter:data1" content="<?php the_author_meta( 'display_name', $post->post_author ); ?>"/>
				<?php if ( ! empty( $minutes ) ) : ?>
				<meta name="twitter:label2" content="<?php esc_html_e( 'Est. reading time', 'foxiz-core' ) ?>"/>
				<meta name="twitter:data2" content="<?php echo $minutes; ?> minutes"/>
			<?php endif;
			endif;
		}

		function get_og_title() {

			if ( is_singular() ) {
				return get_the_title();
			}

			return wp_get_document_title();
		}

		function get_og_permalink() {

			if ( is_singular() ) {
				return get_permalink();
			}

			global $wp;

			return home_url( add_query_arg( [], $wp->request ) );
		}

		function og_get_image() {

			if ( is_singular() && has_post_thumbnail( get_the_ID() ) ) {

				$thumbnail_url = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'full' );
				if ( ! empty( $thumbnail_url ) ) {
					return $thumbnail_url;
				}
			} elseif ( is_author() ) {
				return get_avatar_url( get_queried_object_id(), [ 'size' => '999' ] );
			} elseif ( is_home() || is_front_page() ) {
				$image = foxiz_get_option( 'logo_organization' );
				if ( empty( $image['url'] ) ) {
					$image = foxiz_get_option( 'logo' );
				}
				if ( empty( $image['url'] ) ) {
					$image = foxiz_get_option( 'dark_logo' );
				}
				if ( ! empty( $image['url'] ) ) {
					return $image['url'];
				}
			} elseif ( is_404() ) {
				$image = foxiz_get_option( 'page_404_featured' );
				if ( ! empty( $image['url'] ) ) {
					return $image['url'];
				}
			} elseif ( is_archive() ) {

				$data = rb_get_term_meta( 'foxiz_category_meta', get_queried_object_id() );
				if ( ! empty( $data['featured_image'][0] ) ) {
					$thumbnail_url = wp_get_attachment_image_url( $data['featured_image'][0], 'full' );
					if ( ! empty( $thumbnail_url ) ) {
						return $thumbnail_url;
					}
				}
			}

			$image = foxiz_get_option( 'facebook_default_img' );
			if ( ! empty( $image['url'] ) ) {
				return $image['url'];
			}

			return false;
		}

		/**
		 * @return false
		 */
		function post_list_markup() {

			if ( ! foxiz_get_option( 'site_itemlist' ) || is_single() || ! isset( $GLOBALS['foxiz_queried_ids'] ) || ! array_filter( $GLOBALS['foxiz_queried_ids'] ) ) {
				return false;
			}

			$items_list = [];
			$index      = 1;
			$items      = array_unique( $GLOBALS['foxiz_queried_ids'] );
			foreach ( $items as $post_id ) {
				$data = [
					'@type'    => "ListItem",
					'position' => $index,
					'url'      => get_permalink( $post_id ),
					'name'     => get_the_title( $post_id ),
					'image'    => get_the_post_thumbnail_url( $post_id, 'full' ),
				];
				array_push( $items_list, $data );
				$index ++;
			}
			$post_data = [
				'@context'        => 'https://schema.org',
				'@type'           => 'ItemList',
				"itemListElement" => $items_list,
			];

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $post_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $post_data );
			}
			echo '</script>', "\n";

			return false;
		}

		/**
		 * @param $classes
		 *
		 * @return mixed
		 */
		function remove_hatom( $classes ) {

			foreach ( $classes as $key => $value ) {
				if ( $value === 'hentry' ) {
					unset( $classes[ $key ] );
				}
			}

			return $classes;
		}

		function lazyload_content_image() {

			if ( empty( foxiz_get_option( 'lazy_load_content' ) ) ) {
				return false;
			} else {
				return true;
			}
		}

		function set_elementor_font_display() {

			if ( empty( get_option( 'elementor_font_display' ) ) ) {
				update_option( 'elementor_font_display', 'swap' );
			}
		}

		/**
		 * @param $attr
		 * @param $attachment
		 * @param $size
		 *
		 * @return mixed
		 */
		function optimize_featured_image( $attr, $attachment, $size ) {

			if ( foxiz_get_option( 'disable_srcset' ) ) {
				unset( $attr['srcset'] );
				unset( $attr['sizes'] );
			}

			return $attr;
		}

		/**
		 * @param $add
		 *
		 * @return false
		 */
		function remove_srcset_content_image( $add ) {

			if ( foxiz_get_option( 'disable_srcset_content' ) ) {
				return false;
			}

			return $add;
		}

		function is_elementor_page() {

			if ( ! is_page() || ! foxiz_is_elementor_active() || foxiz_amp_suppressed_elementor() ) {
				return false;
			}

			$document = Elementor\Plugin::$instance->documents->get( get_the_ID() );
			if ( $document && $document->is_built_with_elementor() ) {
				return true;
			}

			return false;
		}

		function enqueue_optimized() {

			if ( ! empty( $_GET['elementor-preview'] ) || is_admin() || foxiz_is_amp() ) {
				return false;
			}

			if ( foxiz_get_option( 'disable_dashicons' ) && ! is_user_logged_in() ) {
				wp_deregister_style( 'dashicons' );
			}

			if ( foxiz_get_option( 'disable_block_style' ) && $this->is_elementor_page() && ! is_admin() ) {
				wp_deregister_style( 'wp-block-library' );
			}
		}

		function start_head_buffer() {

			if ( foxiz_is_amp() ) {
				return false;
			}

			ob_start();
		}

		/**
		 * @return false
		 */
		function end_head_buffer() {

			if ( foxiz_is_amp() ) {
				return false;
			}

			$in = ob_get_clean();

			$setting              = foxiz_get_option( 'preload_gfonts' );
			$disable_google_fonts = foxiz_get_option( 'disable_google_font' );

			if ( ! empty( $disable_google_fonts ) ) {
				$setting = true;
			}

			if ( ! $setting || is_admin() || ! empty( $_GET['elementor-preview'] ) ) {
				echo $in;

				return false;
			}

			$markup = preg_replace( '/<!--(.*)-->/Uis', '', $in );
			preg_match_all( '#<link(?:\s+(?:(?!href\s*=\s*)[^>])+)?(?:\s+href\s*=\s*([\'"])((?:https?:)?\/\/fonts\.googleapis\.com\/css(?:(?!\1).)+)\1)(?:\s+[^>]*)?>#iU', $markup, $matches );

			if ( ! $matches[2] ) {
				echo $in;

				return false;
			}

			$fonts_data    = [];
			$index         = 0;
			$fonts_string  = '';
			$subset_string = '';
			$add_pos       = '<link';

			foreach ( $matches[2] as $font ) {
				if ( ! preg_match( '/rel=["\']dns-prefetch["\']/', $matches[0][ $index ] ) ) {
					$font = str_replace( [ '%7C', '%7c' ], '|', $font );
					if ( strpos( $font, 'fonts.googleapis.com/css2' ) !== false ) {
						$font = rawurldecode( $font );
						$font = str_replace( [
							'css2?',
							'ital,wght@',
							'wght@',
							'ital@',
							'0,',
							'1,',
							':1',
							';',
							'&family=',
						], [ 'css?', '', '', '', '', 'italic', ':italic', ',', '%7C' ], $font );
					}
					$font      = explode( 'family=', $font );
					$font      = ( isset( $font[1] ) ) ? explode( '&', $font[1] ) : [];
					$this_font = array_values( array_filter( explode( '|', reset( $font ) ) ) );
					if ( ! empty( $this_font ) ) {
						$fonts_data[ $index ]['fonts'] = $this_font;
						$subset                        = ( is_array( $font ) ) ? end( $font ) : '';
						if ( false !== strpos( $subset, 'subset=' ) ) {
							$subset                          = str_replace( [ '%2C', '%2c' ], ',', $subset );
							$subset                          = explode( 'subset=', $subset );
							$fonts_data[ $index ]['subsets'] = explode( ',', $subset[1] );
						}
					}
					$in = str_replace( $matches[0][ $index ], '', $in );
				}
				$index ++;
			}

			foreach ( $fonts_data as $font ) {
				$fonts_string .= '|' . trim( implode( '|', $font['fonts'] ), '|' );
				if ( ! empty( $font['subsets'] ) ) {
					$subset_string .= ',' . trim( implode( ',', $font['subsets'] ), ',' );
				}
			}

			if ( ! empty( $subset_string ) ) {
				$subset_string = str_replace( ',', '%2C', ltrim( $subset_string, ',' ) );
				$fonts_string  = $fonts_string . '&#038;subset=' . $subset_string;
			}

			$fonts_string = str_replace( '|', '%7C', ltrim( $fonts_string, '|' ) );
			$fonts_string .= '&amp;display=swap';

			$fonts_html = '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
			$fonts_html .= '<link rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id="rb-preload-gfonts" href="https://fonts.googleapis.com/css?family=' . $fonts_string . '" crossorigin>';
			$fonts_html .= '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $fonts_string . '"></noscript>';

			/** remove all google fonts */
			if ( $disable_google_fonts ) {
				$fonts_html = '';
			}

			echo substr_replace( $in, $fonts_html . $add_pos, strpos( $in, $add_pos ), strlen( $add_pos ) );

			return false;
		}

		function preload_font_icon() {

			if ( foxiz_get_option( 'preload_font_icon' ) && ! is_admin() ) {
				echo '<link rel="preload" href="' . get_theme_file_uri( 'assets/fonts/icons.woff2?ver=2.5.0' ) . '" as="font" type="font/woff2" crossorigin="anonymous"> ';
				if ( foxiz_get_option( 'font_awesome' ) ) {
					echo '<link rel="preload" href="' . get_theme_file_uri( 'assets/fonts/fa-brands-400.woff2' ) . '" as="font" type="font/woff2" crossorigin="anonymous"> ';
					echo '<link rel="preload" href="' . get_theme_file_uri( 'assets/fonts/fa-regular-400.woff2' ) . '" as="font" type="font/woff2" crossorigin="anonymous"> ';
				}
			}
		}

		/**
		 * @return false
		 */
		public function podcast_markup() {

			if ( ! is_singular( 'podcast' ) || ! foxiz_get_option( 'single_podcast_markup' ) ) {
				return false;
			}

			$post_id  = get_the_ID();
			$duration = rb_get_meta( 'duration', $post_id );
			$terms    = get_the_terms( $post_id, 'series' );

			$self_hosted_audio_id = rb_get_meta( 'audio_hosted', $post_id );
			if ( ! empty( $self_hosted_audio_id ) ) {
				$attachment_url = wp_get_attachment_url( $self_hosted_audio_id );
			} else {
				$attachment_url = rb_get_meta( 'audio_url', $post_id );
			}

			$json_ld = [
				'@context'        => foxiz_protocol() . '://schema.org',
				'@type'           => 'PodcastEpisode',
				'description'     => $this->get_description( $post_id ),
				'name'            => get_the_title( $post_id ),
				'url'             => get_permalink( $post_id ),
				'datePublished'   => get_the_date( DATE_W3C, $post_id ),
				'dateModified'    => get_the_modified_date( DATE_W3C, $post_id ),
				'associatedMedia' => [
					'@type'      => 'MediaObject',
					'contentUrl' => $attachment_url,
				],
			];

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$json_ld['partOfSeries'] = [
						'@type' => 'PodcastSeries',
						'name'  => $term->name,
						'url'   => foxiz_get_term_link( $term ),
					];
					break;
				}
			}

			if ( ! empty( $duration ) ) {
				$sec = strtotime( $duration, 0 );
				if ( intval( $sec ) ) {
					$json_ld['timeRequired'] = $this->iso8601_duration( $sec );
				}
			}

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				echo wp_json_encode( $json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			} else {
				echo wp_json_encode( $json_ld );
			}
			echo '</script>', "\n";

			return false;
		}

		public function breadcrumb_markup() {

			if ( ! foxiz_get_option( 'site_breadcrumb' ) ) {
				return;
			}

			if ( function_exists( 'bcn_display_json_ld' ) ) {
				echo '<script type="application/ld+json">';
				bcn_display_json_ld( false, true, true );
				echo '</script>', "\n";
			}
		}

		function iso8601_duration( $seconds ) {

			$hours   = floor( $seconds / 3600 );
			$seconds = $seconds % 3600;

			$minutes = floor( $seconds / 60 );
			$seconds = $seconds % 60;

			return sprintf( 'PT%dH%dM%dS', $hours, $minutes, $seconds );
		}

		/**
		 * @param $the_date
		 * @param $format
		 * @param $post
		 *
		 * @return false|int|string
		 */
		function force_updated_date( $the_date, $format, $post ) {

			if ( foxiz_get_option( 'force_modified_date' ) && ! is_admin() ) {
				return get_the_modified_date( $format, $post );
			} else {
				return $the_date;
			}
		}

		/**
		 * @param $the_time
		 * @param $format
		 * @param $post
		 *
		 * @return false|int|string
		 */
		function force_updated_time( $the_time, $format, $post ) {

			if ( foxiz_get_option( 'force_modified_date' ) && ! is_admin() ) {
				return get_the_modified_time( $format, $post );
			} else {
				return $the_time;
			}
		}
	}
}
