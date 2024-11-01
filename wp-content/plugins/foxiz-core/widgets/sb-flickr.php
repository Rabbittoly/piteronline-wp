<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Flickr' ) ) {

	class Foxiz_W_Flickr extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-flickr';

		function __construct() {

			$this->params = [
				'title'        => '',
				'flickr_id'    => '',
				'tags'         => '',
				'total_images' => '9',
				'total_cols'   => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Flickr Grid', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display a grid of Flickr images in the sidebar.', 'foxiz-core' ),
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

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'title' => esc_html__( 'Title', 'foxiz-core' ),
				'value' => $instance['title'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'flickr_set' ),
				'name'  => $this->get_field_name( 'flickr_set' ),
				'title' => esc_html__( 'Flickr Settings', 'foxiz-core' ),
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'flickr_id' ),
				'name'        => $this->get_field_name( 'flickr_id' ),
				'description' => esc_html__( '<a href="//www.idgettr.com" target="_blank">Get Flickr Id</a>', 'foxiz-core' ),
				'title'       => esc_html__( 'Flickr User ID', 'foxiz-core' ),
				'value'       => $instance['flickr_id'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'total_images' ),
				'name'  => $this->get_field_name( 'total_images' ),
				'title' => esc_html__( 'Total Images', 'foxiz-core' ),
				'value' => $instance['total_images'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'tags' ),
				'name'  => $this->get_field_name( 'tags' ),
				'title' => esc_html__( 'Tags (optional, Separate tags with comma. i.e. tag1,tag2)', 'foxiz-core' ),
				'value' => $instance['tags'],
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'total_cols' ),
				'name'    => $this->get_field_name( 'total_cols' ),
				'title'   => esc_html__( 'Style', 'foxiz-core' ),
				'options' => [
					'rb-c2' => esc_html__( '2 columns', 'foxiz-core' ),
					'rb-c3' => esc_html__( '3 columns', 'foxiz-core' ),
					'rb-c4' => esc_html__( '4 columns', 'foxiz-core' ),
				],
				'value'   => $instance['total_cols'],
			] );
		}

		function widget( $args, $instance ) {

			$instance             = wp_parse_args( (array) $instance, $this->params );
			$instance['cache_id'] = $args['widget_id'];

			echo $args['before_widget'];
			$flickr_data = $this->foxiz_data_flickr( $instance );

			if ( ! empty( $flickr_data['error'] ) ) :
				if ( current_user_can( 'manage_options' ) ) :
					echo '<div class="rb-error"><strong>' . esc_html__( 'Flickr Error: ', 'foxiz-core' ) . '</strong>' . foxiz_strip_tags( $flickr_data['error'] ) . '</div>';
				endif;
			else :
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
				} ?>
				<div class="flickr-grid layout-default clearfix">
					<div class="grid-holder <?php echo strip_tags( $instance['total_cols'] ) ?>">
						<?php $flickr_data = array_slice( $flickr_data, 0, $instance['total_images'] );
						if ( ! empty( $flickr_data ) && is_array( $flickr_data ) ) :
							foreach ( $flickr_data as $item ): ?>
								<div class="grid-el">
									<a href="<?php echo esc_url( $item['link'] ); ?>"><img src="<?php echo esc_url( $item['media'] ); ?>" alt="<?php echo strip_tags( $item['title'] ); ?>"/></a>
								</div>
							<?php endforeach;
						endif; ?>
					</div>
				</div>
			<?php endif;
			echo $args['after_widget'];
		}

		function foxiz_data_flickr( $settings = [] ) {

			$cache_name = 'foxiz_flickr_cache';

			if ( empty( $settings['flickr_id'] ) ) {
				$data_images['error'] = esc_html__( 'Flickr use ID not found', 'foxiz-core' );

				return $data_images;
			}

			if ( ! empty( $settings['cache_id'] ) ) {
				$cache_id = $settings['cache_id'];
			} else {
				$cache_id = 0;
			}

			$cache_data = get_transient( $cache_name );

			if ( ! is_array( $cache_data ) ) {
				$cache_data = [];
			}

			if ( ! empty( $cache_data[ $cache_id ] ) ) {
				return $cache_data[ $cache_id ];
			} else {

				if ( empty( $settings['tag'] ) ) {
					$settings['tag'] = '';
				}

				if ( empty( $settings['total_images'] ) ) {
					$settings['total_images'] = 9;
				}

				$params = [ 'timeout' => 100, 'sslverify' => false ];

				$response = wp_remote_get( '//api.flickr.com/services/feeds/photos_public.gne?format=json&id=' . urlencode( $settings['flickr_id'] ) . '&nojsoncallback=1&tags=' . urlencode( $settings['tag'] ), $params );

				if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
					return false;
				}

				$response    = wp_remote_retrieve_body( $response );
				$response    = str_replace( "\\'", "'", $response );
				$data_images = json_decode( $response, true );

				if ( is_array( $data_images ) ) {
					$data_images = array_slice( $data_images['items'], 0, $settings['total_images'] );
					foreach ( $data_images as $i => $v ) {
						$data_images[ $i ]['media'] = preg_replace( '/_m\.(jp?g|png|gif)$/', '_m.\\1', $v['media']['m'] );
					}

					$cache_data[ $cache_id ] = $data_images;
					delete_transient( $cache_name );
					set_transient( $cache_name, $cache_data, 21600 );

					return $data_images;
				} else {
					return false;
				}
			}
		}

	}
}