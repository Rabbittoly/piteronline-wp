<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_W_Post', false ) ) {
	class Foxiz_W_Post extends WP_Widget {

		private $params = [];
		private $widgetID = 'widget-post';

		function __construct() {

			$this->params = [
				'title'             => '',
				'posts_per_page'    => '4',
				'category'          => '',
				'categories'        => '',
				'category_not_in'   => '',
				'tags'              => '',
				'tag_not_in'        => '',
				'format'            => '0',
				'post_not_in'       => '',
				'post_in'           => '',
				'offset'            => '',
				'featured_position' => '',
				'order'             => 'date_post',
				'entry_meta'        => 'category',
			];

			parent::__construct( $this->widgetID, esc_html__( 'Foxiz - Post Listing', 'foxiz-core' ), [
				'classname'   => $this->widgetID,
				'description' => esc_html__( '[Sidebar Widget] Display a small list latest post listing in the sidebar.', 'foxiz-core' ),
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
				'id'          => $this->get_field_id( 'category' ),
				'name'        => $this->get_field_name( 'category' ),
				'title'       => esc_html__( 'Category Filter', 'foxiz-core' ),
				'description' => esc_html__( 'Select a category you would like to show.', 'foxiz-core' ),
				'data'        => 'category',
				'value'       => $instance['category'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'categories' ),
				'name'        => $this->get_field_name( 'categories' ),
				'title'       => esc_html__( 'Categories Filter', 'foxiz-core' ),
				'description' => esc_html__( 'Filter posts by multiple category IDs. Separated by commas (e.g. 1, 2, 3).', 'foxiz-core' ),
				'value'       => $instance['categories'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'category_not_in' ),
				'name'        => $this->get_field_name( 'category_not_in' ),
				'title'       => esc_html__( 'Exclude Category IDs', 'foxiz-core' ),
				'description' => esc_html__( 'Exclude category IDs. This setting is only available when selecting all categories, separated by commas (e.g. 1, 2, 3).', 'foxiz-core' ),
				'value'       => $instance['category_not_in'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'tags' ),
				'name'        => $this->get_field_name( 'tags' ),
				'title'       => esc_html__( 'Tags Slug Filter', 'foxiz-core' ),
				'description' => esc_html__( 'Filter posts by tag slugs, separated by commas (e.g. tag1,tag2,tag3).', 'foxiz-core' ),
				'value'       => $instance['tags'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'tag_not_in' ),
				'name'        => $this->get_field_name( 'tag_not_in' ),
				'title'       => esc_html__( 'Exclude Tags Slug', 'foxiz-core' ),
				'description' => esc_html__( 'Exclude tag slugs, separated by commas (e.g. tag1,tag2,tag3).', 'foxiz-core' ),
				'value'       => $instance['tag_not_in'],
			] );
			foxiz_create_widget_select_field( [
				'id'          => $this->get_field_id( 'format' ),
				'name'        => $this->get_field_name( 'format' ),
				'title'       => esc_html__( 'Post Format', 'foxiz-core' ),
				'description' => esc_html__( 'Filter posts by post format.', 'foxiz-core' ),
				'options'     => [
					'0'       => esc_html__( '- All -', 'foxiz-core' ),
					'default' => esc_html__( 'Post Only', 'foxiz-core' ),
					'gallery' => esc_html__( 'Gallery', 'foxiz-core' ),
					'video'   => esc_html__( 'Video', 'foxiz-core' ),
					'audio'   => esc_html__( 'Audio', 'foxiz-core' ),
				],
				'value'       => $instance['format'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'post_not_in' ),
				'name'        => $this->get_field_name( 'post_not_in' ),
				'title'       => esc_html__( 'Exclude Post IDs', 'foxiz-core' ),
				'description' => esc_html__( 'Exclude posts by Post IDs, separated by commas (e.g. 1,2,3).', 'foxiz-core' ),
				'value'       => $instance['post_not_in'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'post_in' ),
				'name'        => $this->get_field_name( 'post_in' ),
				'title'       => esc_html__( 'Post IDs Filter', 'foxiz-core' ),
				'description' => esc_html__( 'Filter posts by post IDs. separated by commas (e.g. 1,2,3).', 'foxiz-core' ),
				'value'       => $instance['post_in'],
			] );
			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'order' ),
				'name'    => $this->get_field_name( 'order' ),
				'title'   => esc_html__( 'Order By', 'foxiz-core' ),
				'options' => foxiz_query_order_selection(),
				'value'   => $instance['order'],
			] );
			foxiz_create_widget_text_field( [
				'id'    => $this->get_field_id( 'posts_per_page' ),
				'name'  => $this->get_field_name( 'posts_per_page' ),
				'title' => esc_html__( 'Posts per Page', 'foxiz-core' ),
				'value' => $instance['posts_per_page'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'offset' ),
				'name'        => $this->get_field_name( 'offset' ),
				'title'       => esc_html__( 'Post Offset', 'foxiz-core' ),
				'description' => esc_html__( 'Select number of posts to pass over. Leave this option blank to set at the beginning.', 'foxiz-core' ),
				'value'       => $instance['offset'],
			] );
			foxiz_create_widget_text_field( [
				'id'          => $this->get_field_id( 'entry_meta' ),
				'name'        => $this->get_field_name( 'entry_meta' ),
				'title'       => esc_html__( 'Entry Meta Tags', 'foxiz-core' ),
				'description' => esc_html__( 'Input custom entry meta tags to show, separate by comma. e.g. avatar,author,update. Keys include: [avatar, author, date, category, tag, view, comment, update, read, like, bookmark, custom]', 'foxiz-core' ),
				'value'       => $instance['entry_meta'],
			] );
			foxiz_create_widget_select_field( [
				'id'      => $this->get_field_id( 'featured_position' ),
				'name'    => $this->get_field_name( 'featured_position' ),
				'title'   => esc_html__( 'Featured Image Position', 'foxiz-core' ),
				'options' => [
					'0'     => esc_html__( 'Left', 'foxiz-core' ),
					'right' => esc_html__( 'Right', 'foxiz-core' ),
				],
				'value'   => $instance['featured_position'],
			] );
		}

		function widget( $args, $instance ) {

			$instance           = wp_parse_args( (array) $instance, $this->params );
			$instance['review'] = 'replace';
			$instance['unique'] = '1';

			if ( empty( $instance['entry_meta'] ) ) {
				$instance['entry_meta'] = [ 'category' ];
			} else {
				$instance['entry_meta'] = explode( ',', strval( $instance['entry_meta'] ) );
				$instance['entry_meta'] = array_map( 'trim', $instance['entry_meta'] );
			}

			if ( ! function_exists( 'foxiz_query' ) || ! function_exists( 'foxiz_loop_list_small_2' ) ) {
				return;
			}

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . foxiz_strip_tags( $instance['title'] ) . $args['after_title'];
			}

			$_query = foxiz_query( $instance );
			echo '<div class="widget-p-listing">';
			if ( $_query->have_posts() ) {
				foxiz_loop_list_small_2( $instance, $_query );
				wp_reset_postdata();
			}
			echo '</div>';

			echo $args['after_widget'];
		}
	}
}