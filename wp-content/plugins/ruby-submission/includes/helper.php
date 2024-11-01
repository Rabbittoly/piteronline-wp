<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ruby_Submission_Client_Helper', false ) ) {
	class Ruby_Submission_Client_Helper {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		private function __construct() {

		}

		/**
		 * @param $form_setting_id
		 *
		 * @return array|array[]
		 */
		public function get_categories( $form_setting_id ) {

			$form_settings        = $this->get_submission_form_setting( $form_setting_id );
			$form_settings_result = json_decode( stripslashes( $form_settings->data ), true );

			$exclude_categories = ! empty( $form_settings_result['form_fields']['categories']['exclude_category_ids'] )
				? array_map( 'intval', (array) $form_settings_result['form_fields']['categories']['exclude_category_ids'] )
				: [];

			$params = [
				'taxonomy'   => 'category',
				'hide_empty' => false,
			];

			if ( ! empty( $exclude_categories ) ) {
				$params['exclude'] = $exclude_categories;
			}

			$terms = get_terms( $params );

			return array_map( function ( $term ) {

				return [
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'slug'    => $term->slug,
				];
			}, $terms );
		}

		/**
		 * @param $form_setting_id
		 *
		 * @return array|array[]
		 */
		public function get_tags( $form_setting_id ) {

			$form_settings        = $this->get_submission_form_setting( $form_setting_id );
			$form_settings_result = json_decode( stripslashes( $form_settings->data ), true );

			$exclude_tags = isset( $form_settings_result['form_fields']['tags']['exclude_tag_ids'] )
				? array_map( 'intval', (array) $form_settings_result['form_fields']['tags']['exclude_tag_ids'] )
				: [];

			$params = [
				'taxonomy'   => 'post_tag',
				'hide_empty' => false,
			];

			if ( ! empty( $exclude_tags ) ) {
				$params['exclude'] = $exclude_tags;
			}

			$terms = get_terms( $params );

			return array_map( function ( $term ) {

				return [
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'slug'    => $term->slug,
				];
			}, $terms );
		}

		public function get_submission_form_setting( $setting_form_id ) {

			global $wpdb;
			$table_name = $wpdb->prefix . 'rb_submission';

			$query = "SELECT * FROM {$table_name} WHERE id = %d";
			$sql   = $wpdb->prepare( $query, $setting_form_id );

			return $wpdb->get_row( $sql );
		}

		/**
		 * @param $paged
		 *
		 * @return array
		 */
		public function get_user_posts_data( $paged ) {

			if ( ! is_user_logged_in() ) {
				return [
					'user_posts'               => [],
					'should_display_post_view' => false,
					'is_final_page'            => true,
				];
			}

			$args = [
				'post_type'      => 'post',
				'author'         => get_current_user_id(),
				'posts_per_page' => 10,
				'paged'          => $paged,
				'meta_key'       => 'rbsm_form_id',
				'post_status'    => [ 'publish', 'pending', 'draft' ],
			];

			$query      = new WP_Query( $args );
			$user_posts = [];

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					global $post;

					$post_id        = $post->ID;
					$title          = $post->post_title;
					$date           = $post->post_date;
					$status         = $post->post_status;
					$short_desc     = $post->post_excerpt;
					$categories_raw = get_the_category( $post_id );
					$tags_raw       = get_the_tags( $post_id );
					$link           = get_permalink( $post_id );

					if ( empty( $short_desc ) ) {
						$short_desc = $post->post_content;
					}
					$short_desc = wp_trim_words( strip_tags( $short_desc ), 10, '...' );

					$categories = [];
					if ( $categories_raw ) {
						$categories = array_map( function ( $category ) {

							return $category->name;
						}, $categories_raw );
					}

					$tags = [];
					if ( $tags_raw ) {
						$tags = array_map( function ( $tag ) {

							return $tag->name;
						}, $tags_raw );
					}

					$post_view = 0;
					if ( function_exists( 'pvc_get_post_views' ) ) {
						$post_view = pvc_get_post_views( $post_id );
					}

					$user_posts[] = [
						'title'      => $title,
						'categories' => $categories,
						'tags'       => $tags,
						'date'       => $date,
						'post_id'    => $post_id,
						'post_view'  => $post_view,
						'status'     => $status,
						'link'       => $link,
						'short_desc' => $short_desc,
					];
				}

				wp_reset_postdata();
			}

			$should_display_post_view = function_exists( 'pvc_get_post_views' );
			$is_final_page            = $query->max_num_pages === $query->get( 'paged' ) || false;

			return [
				'user_posts'               => $user_posts,
				'should_display_post_view' => $should_display_post_view,
				'is_final_page'            => $is_final_page,
			];
		}

		public function get_post_manager_settings() {

			return get_option( 'ruby_submission_post_manager_settings' );
		}

		public function get_form_settings_id_by_post( $post_id ) {

			if ( empty( $post_id ) ) {
				return false;
			}

			$form_settings_id = get_post_meta( $post_id, 'rbsm_form_id', true );
			if ( empty( $form_settings_id ) || ! $this->check_form_settings_id_exist( $form_settings_id ) ) {

				$post_manager_settings = get_option( 'ruby_submission_post_manager_settings' );
				if ( empty( $post_manager_settings ) ) {
					return false;
				}

				$form_settings_id = filter_var( $post_manager_settings['user_profile']['form_submission_default_id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE );
				if ( is_null( $form_settings_id ) ) {
					return false;
				}
			}

			return $form_settings_id;
		}

		private function check_form_settings_id_exist( $id ) {

			global $wpdb;
			$table_name = $wpdb->prefix . 'rb_submission';
			$query      = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %s", $id );
			$results    = $wpdb->get_results( $query );

			return count( $results ) > 0;
		}

	}
}
