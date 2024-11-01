<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Fw_Instagram' ) ) {
	class Foxiz_Fw_Instagram extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-instagram';

		function __construct() {

			$this->params = [
				'header_intro'    => esc_html__( '<span>Follow @ Instagram</span><h6>Our Profile</h6>', 'foxiz-core' ),
				'url'             => '#',
				'user_name'       => '',
				'instagram_token' => '',
				'grid_layout'     => 'rb-cmix',
				'total_images'    => 7,
				'total_cols'      => 'rb-c7',
				'layout'          => 'full',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Fw Instagram', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Full Width Widget] Display a grid of instagram images in the full width sections.', 'foxiz-core' ),
			] );
		}

		function update( $new_instance, $old_instance ) {

			if ( current_user_can( 'unfiltered_html' ) ) {
				return wp_parse_args( (array) $new_instance, $this->params );
			} else {
				$instance = [];
				foreach ( $new_instance as $id => $value ) {
					$instance[ $id ] = sanitize_text_field( $value );
				}

				return wp_parse_args( $instance, $this->params );
			}
		}

		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			foxiz_create_widget_textarea_field( [
				'id'    => $this->get_field_id( 'header_intro' ),
				'name'  => $this->get_field_name( 'header_intro' ),
				'title' => esc_html__( 'Header Intro (raw HTML allowed)', 'foxiz-core' ),
				'value' => $instance['header_intro'],
			] );

			foxiz_create_widget_textarea_field( [
				'id'          => $this->get_field_id( 'instagram_token' ),
				'name'        => $this->get_field_name( 'instagram_token' ),
				'title'       => esc_html__( 'Instagram Token', 'foxiz-core' ),
				'description' => esc_html__( 'Refer to this <a target="_blank" href="//help.themeruby.com/foxiz/how-to-create-a-new-instagram-access-token/">Documentation</a> to create an Instagram token', 'foxiz-core' ),
				'value'       => $instance['instagram_token'],
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'grid_layout' ),
				'name'    => $this->get_field_name( 'grid_layout' ),
				'title'   => esc_html__( 'Style', 'foxiz-core' ),
				'options' => [
					'rb-cmix'  => esc_html__( 'Wrapper Masonry', 'foxiz-core' ),
					'rb-cfmix' => esc_html__( 'Wide Masonry', 'foxiz-core' ),
					'rb-grid'  => esc_html__( 'Default Grid', 'foxiz-core' ),
				],
				'value'   => $instance['grid_layout'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'total_images' ),
				'name'        => $this->get_field_name( 'total_images' ),
				'title'       => esc_html__( 'Total Images (Default Grid)', 'foxiz-core' ),
				'description' => esc_html__( 'This setting will apply only to the default grid layout.', 'foxiz-core' ),
				'value'       => $instance['total_images'],
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'total_cols' ),
				'name'    => $this->get_field_name( 'total_cols' ),
				'title'   => esc_html__( 'Style', 'foxiz-core' ),
				'options' => [
					'rb-c5' => esc_html__( '5 columns', 'foxiz-core' ),
					'rb-c6' => esc_html__( '6 columns', 'foxiz-core' ),
					'rb-c7' => esc_html__( '7 columns', 'foxiz-core' ),
					'rb-c8' => esc_html__( '8 columns', 'foxiz-core' ),
					'rb-c9' => esc_html__( '9 columns', 'foxiz-core' ),
				],
				'value'   => $instance['total_cols'],
			] );
		}

		function widget( $args, $instance ) {

			$instance             = wp_parse_args( (array) $instance, $this->params );
			$instance['cache_id'] = $args['widget_id'];
			$flag                 = true;
			$classes              = [];

			echo $args['before_widget'];
			$data_images = $this->foxiz_data_fw_instagram_token( $instance );

			if ( $instance['grid_layout'] === 'rb-cmix' ) {
				$instance['total_images'] = 7;
				if ( empty( $instance['header_intro'] ) ) {
					$instance['total_images'] = 8;
				}
				$instance['total_cols'] = 'rb-masonry';
				$classes[]              = 'instagram-grid layout-grid grid-masonry is-wrap rb-container edge-padding';
			} elseif ( $instance['grid_layout'] === 'rb-cfmix' ) {
				$instance['total_images'] = 11;
				if ( empty( $instance['header_intro'] ) ) {
					$instance['total_images'] = 12;
				}
				$instance['total_cols'] = 'rb-masonry';
				$classes[]              = 'instagram-grid layout-grid grid-fmasonry is-wide';
			} else {
				$flag      = false;
				$classes[] = 'instagram-grid layout-default grid-default';
				if ( 'wrapper' === $instance['layout'] ) {
					$classes[] = 'is-wrap rb-container';
				} else {
					$classes[] = 'is-wide';
				}
			}

			if ( ! empty( $data_images['error'] ) ) :
				if ( current_user_can( 'manage_options' ) ) :
					echo '<div class="rb-error"><strong>' . esc_html__( 'Instagram Error: ', 'foxiz-core' ) . '</strong>' . foxiz_strip_tags( $data_images['error'] ) . '</div>';
				endif;
			else :
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
				} ?>
				<div class="<?php echo join( ' ', $classes ); ?>">
					<?php if ( ! empty( $instance['header_intro'] ) && ! $flag ) : ?>
						<div class="grid-header">
							<a href="<?php echo esc_url( $instance['url'] ); ?>" target="_blank"><?php echo foxiz_strip_tags( $instance['header_intro'] ); ?></a>
						</div>
					<?php endif; ?>
					<div class="grid-holder <?php echo strip_tags( $instance['total_cols'] ) ?>">
						<?php $data_images = array_slice( $data_images, 0, $instance['total_images'] );
						foreach ( $data_images as $image ) :?>
							<?php if ( $flag && ! empty( $instance['header_intro'] ) ) : ?>
								<div class="grid-el intro-el">
									<div class="instagram-box box-intro">
										<a href="<?php echo esc_url( $instance['url'] ); ?>" target="_blank" rel="noopener nofollow"></a>
										<div class="intro-inner">
											<i class="rbi rbi-instagram" aria-hidden="true"></i>
											<span class="intro-content"><?php foxiz_render_inline_html( $instance['header_intro'] ); ?></span>
										</div>
									</div>
								</div>
								<?php $flag = false;
							endif; ?>
							<div class="grid-el">
								<div class="instagram-box">
									<a href="<?php echo esc_url( $image['link'] ); ?>" target="_blank" rel="noopener nofollow">
										<?php
										if ( $image['media'] === "VIDEO" && $image['media'] !== "IMAGE" ) : ?>
											<img src="<?php echo esc_url( $image['thumbnail_url'] ); ?>" alt="<?php echo strip_tags( $image['caption'] ); ?>" loading="lazy" width="<?php if ( ! empty( $image_size[0] ) ) {
												echo strip_tags( $image_size[0] );
											} ?>" height="<?php if ( ! empty( $image_size[1] ) ) {
												echo strip_tags( $image_size[1] );
											} ?>">
										<?php else : ?>
											<img src="<?php echo esc_url( $image['thumbnail_src'] ); ?>" alt="<?php echo strip_tags( $image['caption'] ); ?>" loading="lazy" width="<?php if ( ! empty( $image_size[0] ) ) {
												echo strip_tags( $image_size[0] );
											} ?>" height="<?php if ( ! empty( $image_size[1] ) ) {
												echo strip_tags( $image_size[1] );
											} ?>">
										<?php endif; ?>
									</a>
									<div class="box-content">
										<?php if ( ! empty( $image['likes'] ) ) : ?>
											<span class="likes"><i class="rbi rbi-heart" aria-hidden="true"></i><?php foxiz_render_inline_html( $image['likes'] ); ?></span>
										<?php endif;
										if ( ! empty( $image['comments'] ) ) : ?>
											<span class="comments"><i class="rbi rbi-comment" aria-hidden="true"></i><?php foxiz_render_inline_html( $image['comments'] ); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif;
			echo $args['after_widget'];
		}

		function foxiz_data_fw_instagram_token( $settings = [] ) {

			$cache_name  = 'foxiz_fw_instagram_cache';
			$cache_data  = get_transient( $cache_name );
			$data_images = [];
			$cache_id    = 0;

			if ( empty( $settings['instagram_token'] ) ) {
				$data_images['error'] = esc_html__( 'Instagram token not found', 'foxiz-core' );

				return $data_images;
			}

			if ( ! empty( $settings['cache_id'] ) ) {
				$cache_id = $settings['cache_id'];
			}
			if ( empty( $cache_data ) || ! is_array( $cache_data ) ) {
				$cache_data = [];
			}

			if ( ! empty( $cache_data[ $cache_id ] ) ) {
				return $cache_data[ $cache_id ];
			} else {
				$url      = 'https://graph.instagram.com/me/media?fields=id,caption,media_url,permalink,media_type,thumbnail_url&access_token=' . trim( $settings['instagram_token'] );
				$response = wp_remote_get( $url, [
					'sslverify' => false,
					'timeout'   => 100,
				] );

				if ( is_wp_error( $response ) || empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
					$response = json_decode( wp_remote_retrieve_body( $response ) );
					if ( ! empty( $response->error->message ) ) {
						$data_images['error'] = foxiz_strip_tags( $response->error->message );
					} else {
						$data_images['error'] = esc_html__( 'Could not connect to Instagram API server.', 'foxiz-core' );
					}

					return $data_images;
				}

				$response = json_decode( wp_remote_retrieve_body( $response ) );
				if ( ! empty( $response->data ) && is_array( $response->data ) ) {
					foreach ( $response->data as $image ) {

						$caption    = esc_html__( 'instagram image', 'foxiz-core' );
						$link       = '#';
						$likes      = '';
						$comments   = '';
						$thumbnail  = '#';
						$media      = '';
						$images_url = '';

						if ( ! empty( $image->permalink ) ) {
							$link = esc_url( $image->permalink );
						}

						if ( ! empty( $image->media_url ) ) {
							$thumbnail = esc_url( $image->media_url );
						}

						if ( ! empty( $image->media_type ) ) {
							$media = esc_html( $image->media_type );
						}

						if ( ! empty( $image->thumbnail_url ) ) {
							$images_url = esc_url( $image->thumbnail_url );
						}

						if ( ! empty( $image->$caption ) ) {
							$caption = $image->$caption;
						}

						$data_images[] = [
							'thumbnail_src' => $thumbnail,
							'caption'       => $caption,
							'link'          => $link,
							'likes'         => $likes,
							'comments'      => $comments,
							'media'         => $media,
							'thumbnail_url' => $images_url,
						];
					}

					$cache_data[ $cache_id ] = $data_images;
					delete_transient( $cache_name );
					set_transient( $cache_name, $cache_data, 21600 );
				} else {
					$data_images['error'] = esc_html__( 'Incorrect token or has been expired, Please create a new token and try again!', 'foxiz-core' );
				}

				return $data_images;
			}
		}
	}
}