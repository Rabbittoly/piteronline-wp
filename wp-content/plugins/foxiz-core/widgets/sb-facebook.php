<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Facebook' ) ) {

	class Foxiz_W_Facebook extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-facebook';

		function __construct() {

			$this->params = [
				'title'        => '',
				'fanpage_name' => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Facebook', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display the Facebook Like box in the sidebar.', 'foxiz-core' ),
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

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'fanpage_name' ),
				'name'  => $this->get_field_name( 'fanpage_name' ),
				'title' => esc_html__( 'Fan Page URL', 'foxiz-core' ),
				'value' => $instance['fanpage_name'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
			}

			if ( $instance['fanpage_name'] ) :
				if ( ! strpos( $instance['fanpage_name'], 'facebook.com' ) ) {
					$url = 'https://facebook.com/' . trim( $instance['fanpage_name'] );
				} else {
					$url = $instance['fanpage_name'];
				}
				?>
				<div class="fb-container">
					<div id="fb-root"></div>
					<script>(function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=1385724821660962";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-page" data-href="<?php echo esc_url( $url ); ?>" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
				</div>
			<?php endif;
			echo $args['after_widget'];
		}

	}
}