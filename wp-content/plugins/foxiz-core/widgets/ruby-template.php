<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Ruby_Template' ) ) {
	class Foxiz_W_Ruby_Template extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-template';

		function __construct() {

			$this->params = [
				'template_id' => '',
				'shortcode'   => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Ruby Template', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( 'Display a ruby template in widget sections.', 'foxiz-core' ),
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

			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'template_id' ),
				'name'        => $this->get_field_name( 'template_id' ),
				'data'        => 'template',
				'title'       => esc_html__( 'Select a Template', 'foxiz-core' ),
				'description' => esc_html__( 'Select a Ruby template to display in this sidebar.', 'foxiz-core' ),
				'value'       => $instance['template_id'],
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'shortcode' ),
				'name'        => $this->get_field_name( 'shortcode' ),
				'title'       => esc_html__( 'or Shortcode', 'foxiz-core' ),
				'description' => esc_html__( 'Input a shortcode, this setting will override the setting above.', 'foxiz-core' ),
				'value'       => $instance['shortcode'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			if ( ! empty( $instance['shortcode'] ) ) {
				echo do_shortcode( $instance['shortcode'] );
			} elseif ( ! empty( $instance['template_id'] ) ) {
				echo do_shortcode( '[Ruby_E_Template id="' . $instance['template_id'] . '"]' );
			}
		}
	}
}