<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Youtube_Subscribe' ) ) :
	class Foxiz_W_Youtube_Subscribe extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-youtube';

		function __construct() {

			$this->params = [
				'title'        => '',
				'channel_name' => '',
				'channel_id'   => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Youtube Subscribe', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display YouTube subscribe box in the sidebar.', 'foxiz-core' ),
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
				'id'    => $this->get_field_id( 'youtube_set' ),
				'name'  => $this->get_field_name( 'youtube_set' ),
				'title' => esc_html__( 'Youtube Settings', 'foxiz-core' ),
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'channel_name' ),
				'name'  => $this->get_field_name( 'channel_name' ),
				'title' => esc_html__( 'Channel Name', 'foxiz-core' ),
				'value' => $instance['channel_name'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'channel_id' ),
				'name'  => $this->get_field_name( 'channel_id' ),
				'title' => esc_html__( 'or Channel ID', 'foxiz-core' ),
				'desc'  => esc_html__( 'this setting will override on the above channel name.', 'foxiz-core' ),
				'value' => $instance['channel_id'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
			}
			if ( ! empty( $instance['channel_name'] ) ) : ?>
				<div class="subscribe-youtube-wrap">
					<script src="https://apis.google.com/js/platform.js"></script>
					<div class="g-ytsubscribe" data-channel="<?php echo esc_attr( $instance['channel_name'] ) ?>" data-layout="default" data-count="default"></div>
				</div>
			<?php elseif ( ! empty( $instance['channel_id'] ) ) : ?>
				<div class="subscribe-youtube-wrap">
					<script src="https://apis.google.com/js/platform.js"></script>
					<div class="g-ytsubscribe" data-channelid="<?php echo esc_attr( $instance['channel_id'] ); ?>" data-layout="default" data-count="default"></div>
				</div>
			<?php endif; ?>
			<?php echo $args['after_widget'];
		}

	}
endif;