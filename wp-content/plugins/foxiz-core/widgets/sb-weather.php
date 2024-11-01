<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Weather' ) ) {

	class Foxiz_W_Weather extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-weather';

		function __construct() {

			$this->params = [
				'title'         => 'Weather',
				'location'      => '',
				'api_key'       => '',
				'units'         => '',
				'forecast_days' => '5',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Weather', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display today weather information in the sidebar.', 'foxiz-core' ),
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

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'units' ),
				'name'    => $this->get_field_name( 'units' ),
				'title'   => esc_html__( 'Units', 'foxiz-core' ),
				'options' => [
					'C' => esc_html__( '&deg;C', 'foxiz-core' ),
					'F' => esc_html__( '&deg;F', 'foxiz-core' ),
				],
				'value'   => $instance['units'],
			] );

			foxiz_create_widget_heading_field( [
				'id'    => $this->get_field_id( 'head_weather' ),
				'name'  => $this->get_field_name( 'head_weather' ),
				'title' => esc_html__( 'Weather Settings', 'foxiz-core' ),
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'location' ),
				'name'        => $this->get_field_name( 'location' ),
				'title'       => esc_html__( 'Digit ISO Location Code', 'foxiz-core' ),
				'value'       => $instance['location'],
				'description' => '<a target="_blank" href="https://openweathermap.org/find/">' . esc_html__( 'Find your location', 'foxiz-core' ) . '</a>&nbsp;&nbsp;' . esc_html__( 'Put the city\'s name, comma, 2-letter country code. i.e: London, GB)', 'foxiz-core' ) . '',
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'api_key' ),
				'name'        => $this->get_field_name( 'api_key' ),
				'title'       => esc_html__( 'Weather API Key', 'foxiz-core' ),
				'value'       => $instance['api_key'],
				'description' => '<a target="_blank" href="https://openweathermap.org/appid#get">How to get API key</a>',
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'forecast_days' ),
				'name'    => $this->get_field_name( 'forecast_days' ),
				'title'   => esc_html__( 'Forecast', 'foxiz-core' ),
				'options' => [
					'1'    => esc_html__( '1 day', 'foxiz-core' ),
					'2'    => esc_html__( '2 days', 'foxiz-core' ),
					'3'    => esc_html__( '3 days', 'foxiz-core' ),
					'4'    => esc_html__( '4 days', 'foxiz-core' ),
					'5'    => esc_html__( '5 days', 'foxiz-core' ),
					'hide' => esc_html__( 'Do not display', 'foxiz-core' ),
				],
				'value'   => $instance['forecast_days'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget']; ?>
			<?php if ( ! empty( $instance['location'] ) && ! empty( $instance['api_key'] ) ) : ?>
				<?php echo rb_weather_data(
					[
						'title'         => $instance['title'],
						'location'      => $instance['location'],
						'api_key'       => $instance['api_key'],
						'units'         => $instance['units'],
						'forecast_days' => $instance['forecast_days'],
					] ); ?>
			<?php endif; ?>
			<?php echo $args['after_widget'];
		}

	}
}