<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Ad_Image' ) ) :
	class Foxiz_Ad_Image extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-ad-image';

		function __construct() {

			$this->params = [
				'title'       => esc_html__( '- Advertisement -', 'foxiz-core' ),
				'destination' => '',
				'image'       => '',
				'dark_image'  => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Ad Image', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display your custom ad image in the sidebar.', 'foxiz-core' ),
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
				'id'    => $this->get_field_id( 'destination' ),
				'name'  => $this->get_field_name( 'destination' ),
				'title' => esc_html__( 'Destination URL', 'foxiz-core' ),
				'value' => $instance['destination'],
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'image' ),
				'name'        => $this->get_field_name( 'image' ),
				'title'       => esc_html__( 'Ad Image URL', 'foxiz-core' ),
				'description' => esc_html__( 'Input your advert image URL (attachment URL) for this widget.', 'foxiz-core' ),
				'value'       => $instance['image'],
			] );

			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'dark_image' ),
				'name'        => $this->get_field_name( 'dark_image' ),
				'title'       => esc_html__( 'Dark Mode - Ad Image URL', 'foxiz-core' ),
				'description' => esc_html__( 'Input your advert image URL (attachment URL) for this widget in dark mode.', 'foxiz-core' ),
				'value'       => $instance['dark_image'],
			] );
		}

		function widget( $args, $instance ) {

			$instance['cache_id'] = $args['widget_id'];

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];
			$instance['id'] = $args['widget_id'];

			if ( ! empty( $instance['title'] ) ) : ?>
				<span class="ad-description is-meta"><?php foxiz_render_inline_html( $instance['title'] ); ?></span>
			<?php endif;
			if ( ! empty( $instance['image'] ) ) : ?>
				<aside class="advert-wrap advert-image">
					<?php
					$settings                 = [
						'no_spacing' => true,
					];
					$settings['image']['alt'] = '';
					$settings['image']['url'] = $instance['image'];
					$settings['destination']  = $instance['destination'];
					if ( ! empty( $instance['dark_image'] ) ) {
						$settings['dark_image']['url'] = $instance['dark_image'];
					}
					echo foxiz_get_ad_image( $settings ); ?>
				</aside>
			<?php endif;

			echo $args['after_widget'];
		}
	}
endif;