<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Video_Thumb', false ) ) {
	class Foxiz_Video_Thumb {

		protected static $instance = null;

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;

			add_action( 'save_post_post', [ $this, 'post_video_thumbnail' ], PHP_INT_MAX, 1 );
			add_filter( 'foxiz_playlist_thumbnails', [ $this, 'playlist_thumbnails' ], 10, 1 );
		}

		/**
		 * @param $settings
		 *
		 * @return array
		 */
		public function playlist_thumbnails( $settings ) {

			$playlist_data = get_option( 'rb_yt_playlist_thumbnail', [] );
			$flag          = false;

			if ( ! empty( $settings['videos'] ) ) {
				foreach ( $settings['videos'] as $index => $item ) {
					if ( ! empty( $item['url'] ) && ( empty( $item['image']['url'] ) || strpos( $item['image']['url'], 'placeholder' ) ) ) {
						$video_id = $this->get_video_id( $item['url'] );
						if ( ! empty( $video_id ) ) {
							if ( ! empty( $playlist_data[ $video_id ] ) && file_exists( get_attached_file( $playlist_data[ $video_id ] ) ) ) {
								$settings['videos'][ $index ]['image']['url'] = wp_get_attachment_url( $playlist_data[ $video_id ] );
							} elseif ( current_user_can( 'manage_options' ) ) {
								$thumbnail = $this->get_video_thumbnail( $item['url'] );
								if ( ! empty( $thumbnail ) ) {
									if ( ! function_exists( 'media_sideload_image' ) ) {
										require_once( ABSPATH . 'wp-admin/includes/media.php' );
										require_once( ABSPATH . 'wp-admin/includes/file.php' );
										require_once( ABSPATH . 'wp-admin/includes/image.php' );
									}
									$playlist_data[ $video_id ]                   = media_sideload_image( $thumbnail, false, null, 'id' );
									$settings['videos'][ $index ]['image']['url'] = wp_get_attachment_url( $playlist_data[ $video_id ] );

									$flag = true;
								}
							}
						}
					}
				}
			}

			if ( $flag ) {
				update_option( 'rb_yt_playlist_thumbnail', $playlist_data );
			}

			return $settings;
		}

		/**
		 * @param $post_id
		 *
		 * @return false|void
		 */
		public function post_video_thumbnail( $post_id ) {

			if ( ! foxiz_get_option( 'auto_video_featured' ) ) {
				return;
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! empty( $_GET['rest_route'] ) ) {
				return;
			}

			if ( empty( $post_id ) || get_post_status( $post_id ) !== 'publish' || 'post' !== get_post_type( $post_id ) ) {
				return;
			}

			if ( ! empty( get_post_meta( $post_id, '_thumbnail_id', true ) ) ) {
				return;
			}

			$video_url = rb_get_meta( 'video_url', $post_id );
			$video_url = trim( $video_url );
			if ( ! empty( $video_url ) ) {
				$thumbnail = $this->get_video_thumbnail( $video_url );
				if ( ! empty( $thumbnail ) ) {
					if ( ! function_exists( 'media_sideload_image' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
					}
					$attr_id = media_sideload_image( $thumbnail, $post_id, null, 'id' );
					set_post_thumbnail( $post_id, $attr_id );
				}
			}
		}

		/**
		 * @param $video_url
		 *
		 * @return false|mixed|string
		 */
		public function get_video_id( $video_url ) {

			$host_name = $this->detect_url( $video_url );
			switch ( $host_name ) {
				case 'youtube' :
					return $this->get_video_yt_id( $video_url );
				case 'vimeo' :
					return $this->get_video_vimeo_id( $video_url );
				case 'dailymotion' :
					return $this->get_video_dailymotion_id( $video_url );
				default :
					return false;
			}
		}

		/**
		 * @param $video_url
		 *
		 * @return false|string
		 */
		function get_video_thumbnail( $video_url ) {

			if ( empty( $video_url ) ) {
				return false;
			}

			$host_name = $this->detect_url( $video_url );
			switch ( $host_name ) {
				case 'youtube' :
					return $this->get_yt_thumbnail( $video_url );
				case 'vimeo' :
					return $this->get_vimeo_thumbnail( $video_url );
				case 'dailymotion' :
					return $this->get_dailymotion_thumbnail( $video_url );
				default :
					return false;
			}
		}

		/**
		 * @param $video_url
		 *
		 * @return false|string
		 */
		public function detect_url( $video_url ) {

			$video_url = strtolower( $video_url );
			if ( strpos( $video_url, 'youtube.com' ) !== false or strpos( $video_url, 'youtu.be' ) !== false ) {
				return 'youtube';
			}
			if ( strpos( $video_url, 'dailymotion.com' ) !== false ) {
				return 'dailymotion';
			}
			if ( strpos( $video_url, 'vimeo.com' ) !== false ) {
				return 'vimeo';
			}

			return false;
		}

		/**
		 * @param $video_url
		 *
		 * @return mixed|string
		 */
		public function get_video_yt_id( $video_url ) {

			if ( empty( $video_url ) ) {
				return false;
			}

			$s = [];
			parse_str( parse_url( $video_url, PHP_URL_QUERY ), $s );

			if ( empty( $s["v"] ) ) {
				$youtube_sl_explode = explode( '?', $video_url );

				$youtube_sl = explode( '/', $youtube_sl_explode[0] );
				if ( ! empty( $youtube_sl[3] ) ) {
					return $youtube_sl [3];
				}

				return $youtube_sl [0];
			} else {
				return $s["v"];
			}
		}

		/**
		 * @param $video_url
		 *
		 * @return mixed
		 */
		public function get_video_vimeo_id( $video_url ) {

			sscanf( parse_url( $video_url, PHP_URL_PATH ), '/%d', $video_id );

			return $video_id;
		}

		/**
		 * @param $video_url
		 *
		 * @return mixed|string
		 */
		public function get_video_dailymotion_id( $video_url ) {

			$video_id = strtok( basename( $video_url ), '_' );
			if ( strpos( $video_id, '#video=' ) !== false ) {
				$video_parts = explode( '#video=', $video_id );
				if ( ! empty( $video_parts[1] ) ) {
					return $video_parts[1];
				}
			};

			return $video_id;
		}

		/**
		 * @param $video_url
		 *
		 * @return false|string
		 */
		public function get_yt_thumbnail( $video_url ) {

			$protocol = foxiz_protocol();
			$video_id = $this->get_video_yt_id( $video_url );

			$thumbnail_1920 = $protocol . '://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
			$thumbnail_640  = $protocol . '://img.youtube.com/vi/' . $video_id . '/sddefault.jpg';
			$thumbnail_480  = $protocol . '://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';

			if ( ! $this->yt_respone( $thumbnail_1920 ) ) {
				return $thumbnail_1920;
			} elseif ( ! $this->yt_respone( $thumbnail_640 ) ) {
				return $thumbnail_640;
			} elseif ( ! $this->yt_respone( $thumbnail_480 ) ) {
				return $thumbnail_480;
			} else {
				return false;
			}
		}

		public function yt_respone( $url ) {

			$headers = @get_headers( $url );
			if ( ! empty( $headers[0] ) and strpos( $headers[0], '404' ) !== false ) {
				return true;
			}

			return false;
		}

		/**
		 * @param $video_url
		 *
		 * @return false
		 */
		function get_vimeo_thumbnail( $video_url ) {

			$video_id = $this->get_video_vimeo_id( $video_url );
			$api_url  = 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $video_id;

			$data_response = wp_remote_get( $api_url, [
					'timeout'    => 60,
					'sslverify'  => false,
					'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0',
				]
			);

			if ( ! is_wp_error( $data_response ) ) {
				$data_response = wp_remote_retrieve_body( $data_response );
				$data_response = json_decode( $data_response );

				return $data_response->thumbnail_url;
			} else {
				return false;
			}
		}

		/**
		 * @param $video_url
		 *
		 * @return false
		 */
		public function get_dailymotion_thumbnail( $video_url ) {

			$video_id = $this->get_video_dailymotion_id( $video_url );
			$protocol = foxiz_protocol();

			$param         = $protocol . '://api.dailymotion.com/video/' . $video_id . '?fields=thumbnail_url';
			$data_response = wp_remote_get( $param );
			if ( ! is_wp_error( $data_response ) ) {
				$data_response = json_decode( $data_response['body'] );

				return $data_response->thumbnail_url;
			} else {
				return false;
			}
		}
	}
}

/** load */
Foxiz_Video_Thumb::get_instance();
