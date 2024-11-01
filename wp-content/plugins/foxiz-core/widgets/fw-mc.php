<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Fw_Mc' ) ) {
	class Foxiz_Fw_Mc extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-mc';

		function __construct() {

			$this->params = [
				'title'       => 'Subscribe to Our Newsletter',
				'description' => 'Subscribe to our newsletter to get our newest articles instantly!',
				'shortcode'   => '[mc4wp_form]',
				'text_style'  => '1',
				'image'       => '',
				'bg_color'    => '',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - FW Newsletter', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Full Width Widget] Display a Mailchimp sign-up form in the full width sections.', 'foxiz-core' ),
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

			foxiz_create_widget_textarea_field( [
				'id'    => $this->get_field_id( 'description' ),
				'name'  => $this->get_field_name( 'description' ),
				'title' => esc_html__( 'Description', 'foxiz-core' ),
				'value' => $instance['description'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'shortcode' ),
				'name'  => $this->get_field_name( 'shortcode' ),
				'title' => esc_html__( 'Mailchimp Form Shortcode', 'foxiz-core' ),
				'value' => $instance['shortcode'],
			] );

			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'text_style' ),
				'name'    => $this->get_field_name( 'text_style' ),
				'title'   => esc_html__( 'Style', 'foxiz-core' ),
				'options' => [
					'0' => esc_html__( 'Light Text', 'foxiz-core' ),
					'1' => esc_html__( 'Dark Text', 'foxiz-core' ),
				],
				'value'   => $instance['text_style'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'bg_color' ),
				'name'  => $this->get_field_name( 'bg_color' ),
				'title' => esc_html__( 'Background Color (hex value)', 'foxiz-core' ),
				'value' => $instance['bg_color'],
			] );

			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'image' ),
				'name'  => $this->get_field_name( 'image' ),
				'title' => esc_html__( 'Background Image URL', 'foxiz-core' ),
				'desc'  => esc_html__( 'Input a background image URL (attachment URL) for this widget.', 'foxiz-core' ),
				'value' => $instance['image'],
			] );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];

			$classes = 'newsletter-box-2 newsletter-fw';
			if ( ! empty( $instance['image'] ) || ! empty( $instance['bg_color'] ) ) {
				$classes .= ' has-bg';
			}
			if ( empty( $instance['text_style'] ) ) {
				$classes .= ' light-scheme';
			}
			$style = ' style="';
			if ( ! empty( $instance['image'] ) ) {
				$style .= 'background-image: url( ' . esc_url( $instance['image'] ) . ');';
			}
			if ( ! empty( $instance['bg_color'] ) ) {
				$style .= 'background-color:' . esc_attr( $instance['bg_color'] ) . ';';
			}
			$style .= '"';
			?>
			<div class="<?php echo esc_attr( $classes ); ?>" <?php echo $style; ?>>
				<div class="newsletter-inner">
					<?php if ( ! empty( $instance['title'] ) || ! empty( $instance['description'] ) ) : ?>
						<div class="newsletter-content">
							<?php if ( ! empty( $instance['title'] ) ) : ?>
								<span class="h2 newsletter-title"><?php foxiz_render_inline_html( $instance['title'] ); ?></span>
							<?php endif;
							if ( ! empty( $instance['description'] ) ) : ?>
								<div class="newsletter-description rb-text"><?php foxiz_render_inline_html($instance['description']); ?></div>
							<?php endif; ?>
						</div>
					<?php endif;
					if ( ! empty( $instance['shortcode'] ) ) : ?>
						<div class="newsletter-form"><?php echo do_shortcode( $instance['shortcode'] ); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $args['after_widget'];
		}
	}
}