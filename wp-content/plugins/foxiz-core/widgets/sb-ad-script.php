<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Ad_Script' ) ) :
	class Foxiz_Ad_Script extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-ad-script';

		function __construct() {

			$this->params = [
				'title'        => esc_html__( '- Advertisement -', 'foxiz-core' ),
				'code'         => '',
				'size'         => 0,
				'desktop_size' => 1,
				'tablet_size'  => 2,
				'mobile_size'  => 3,
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Widget Ad Script', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( 'Display your Js ad or Google Adsense in the sidebars or full width widget areas.', 'foxiz-core' ),
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
				'title' => esc_html__( 'Description', 'foxiz-core' ),
				'value' => $instance['title'],
			] );

			foxiz_create_widget_textarea_field( [
				'id'          => $this->get_field_id( 'code' ),
				'name'        => $this->get_field_name( 'code' ),
				'title'       => esc_html__( 'Ad/Adsense Code', 'foxiz-core' ),
				'description' => esc_html__( 'Input your custom ad or Adsense code. Use Adsense units code to ensure it display exactly where you put. The widget will not work if you are using auto ads.', 'foxiz-core' ),
				'value'       => $instance['code'],
			] );

			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'size' ),
				'name'        => $this->get_field_name( 'size' ),
				'title'       => esc_html__( 'Ad Size', 'foxiz-core' ),
				'description' => esc_html__( 'Select a custom size for this ad if you use the adsense ad units code.', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( 'Do not Override', 'foxiz-core' ),
					'1' => esc_html__( 'Custom Size Below', 'foxiz-core' ),
				],
				'value'       => $instance['size'],
			] );

			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'desktop_size' ),
				'name'        => $this->get_field_name( 'desktop_size' ),
				'title'       => esc_html__( 'Size on Desktop', 'foxiz-core' ),
				'description' => esc_html__( 'Select a size on desktop devices.', 'foxiz-core' ),
				'options'     => $this->ad_sizes_config(),
				'value'       => $instance['desktop_size'],
			] );

			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'tablet_size' ),
				'name'        => $this->get_field_name( 'tablet_size' ),
				'title'       => esc_html__( 'Size on Tablet', 'foxiz-core' ),
				'description' => esc_html__( 'Select a size on tablet devices.', 'foxiz-core' ),
				'options'     => $this->ad_sizes_config(),
				'value'       => $instance['tablet_size'],
			] );

			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'mobile_size' ),
				'name'        => $this->get_field_name( 'mobile_size' ),
				'title'       => esc_html__( 'Size on Mobile', 'foxiz-core' ),
				'description' => esc_html__( 'Select a size on mobile devices/', 'foxiz-core' ),
				'options'     => $this->ad_sizes_config(),
				'value'       => $instance['mobile_size'],
			] );
		}

		function widget( $args, $instance ) {

			$instance['cache_id'] = $args['widget_id'];

			$instance = wp_parse_args( (array) $instance, $this->params );

			echo $args['before_widget'];
			$instance['id']         = $args['widget_id'];
			$instance['no_spacing'] = true;

			if ( ! empty( $instance['title'] ) ) {
				$instance['description'] = $instance['title'];
			}

			if ( ! empty( $instance['code'] ) ) : ?>
				<?php echo foxiz_get_adsense( $instance ); ?>
			<?php endif;

			echo $args['after_widget'];
		}

		public function ad_sizes_config() {

			return [
				'0'  => esc_html__( 'Hide on Desktop', 'foxiz-core' ),
				'1'  => esc_html__( 'Leaderboard (728x90)', 'foxiz-core' ),
				'2'  => esc_html__( 'Banner (468x60)', 'foxiz-core' ),
				'3'  => esc_html__( 'Half banner (234x60)', 'foxiz-core' ),
				'4'  => esc_html__( 'Button (125x125)', 'foxiz-core' ),
				'5'  => esc_html__( 'Skyscraper (120x600)', 'foxiz-core' ),
				'6'  => esc_html__( 'Wide Skyscraper (160x600)', 'foxiz-core' ),
				'7'  => esc_html__( 'Small Rectangle (180x150)', 'foxiz-core' ),
				'8'  => esc_html__( 'Vertical Banner (120 x 240)', 'foxiz-core' ),
				'9'  => esc_html__( 'Small Square (200x200)', 'foxiz-core' ),
				'10' => esc_html__( 'Square (250x250)', 'foxiz-core' ),
				'11' => esc_html__( 'Medium Rectangle (300x250)', 'foxiz-core' ),
				'12' => esc_html__( 'Large Rectangle (336x280)', 'foxiz-core' ),
				'13' => esc_html__( 'Half Page (300x600)', 'foxiz-core' ),
				'14' => esc_html__( 'Portrait (300x1050)', 'foxiz-core' ),
				'15' => esc_html__( 'Mobile Banner (320x50)', 'foxiz-core' ),
				'16' => esc_html__( 'Large Leaderboard (970x90)', 'foxiz-core' ),
				'17' => esc_html__( 'Billboard (970x250)', 'foxiz-core' ),
				'18' => esc_html__( 'Mobile Banner (320x100)', 'foxiz-core' ),
				'19' => esc_html__( 'Mobile Friendly (300x100)', 'foxiz-core' ),
			];
		}
	}
endif;