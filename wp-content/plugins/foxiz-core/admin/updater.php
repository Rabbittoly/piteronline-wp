<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', [ 'Foxiz_Updater', 'get_instance' ] );

if ( ! class_exists( 'Foxiz_Updater' ) ) {
	class Foxiz_Updater {

		protected static $instance = null;

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			self::$instance = $this;
			$this->update_logos();
		}

		/** update logo to retina size */
		public function update_logos() {

			$flag_update_logo = get_option( '_rb_flag_update_logo', false );
			if ( $flag_update_logo || is_network_admin() ) {
				return;
			}

			$theme_options = get_option( FOXIZ_TOS_ID, [] );
			set_transient( FOXIZ_TOS_ID, $theme_options, 2592000 );

			if ( ! empty( $theme_options['retina_logo']['url'] ) ) {
				$theme_options['logo'] = $theme_options['retina_logo'];
			}
			if ( ! empty( $theme_options['dark_retina_logo']['url'] ) ) {
				$theme_options['dark_logo'] = $theme_options['dark_retina_logo'];
			}
			if ( ! empty( $theme_options['transparent_retina_logo']['url'] ) ) {
				$theme_options['transparent_logo'] = $theme_options['transparent_retina_logo'];
			}

			update_option( FOXIZ_TOS_ID, $theme_options );
			update_option( '_rb_flag_update_logo', true );
		}

		/** update tag meta key */
		public function update_tax_meta() {

			$flag_update_tax_meta = get_option( '_rb_flag_update_tax_meta', false );
			if ( $flag_update_tax_meta || is_network_admin() ) {
				return;
			}

			$category_terms = get_terms( [
				'taxonomy'   => 'category',
				'hide_empty' => false,
			] );

			$old_metas = get_option( 'foxiz_category_meta', [] );
			if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) {
				foreach ( $category_terms as $term ) {

					$id      = $term->term_id;
					$current = get_term_meta( $id, 'foxiz_category_meta', true );
					if ( ! empty( $current ) ) {
						continue;
					}

					if ( ! empty( $old_metas[ $id ] ) && is_array( $old_metas[ $id ] ) ) {
						update_term_meta( $id, 'foxiz_category_meta', $old_metas[ $id ] );
					}
				}
			}

			/** update_tag */
			$post_tag_terms = get_terms( [
				'taxonomy'   => 'post_tag',
				'hide_empty' => false,
			] );

			if ( ! empty( $post_tag_terms ) && ! is_wp_error( $post_tag_terms ) ) {
				foreach ( $post_tag_terms as $term ) {

					$id  = $term->term_id;
					$new = get_term_meta( $id, 'foxiz_category_meta', true );
					if ( ! empty( $new ) ) {
						continue;
					}

					$old_meta = get_term_meta( $id, 'foxiz_tag_meta', true );

					if ( ! empty( $old_meta ) && is_array( $old_meta ) && count( $old_meta ) ) {
						update_term_meta( $id, 'foxiz_category_meta', $old_meta );
						delete_term_meta( $id, 'foxiz_tag_meta' );
					}
				}
			}

			update_option( '_rb_flag_update_tax_meta', true );
			delete_option( 'foxiz_category_meta' );
		}
	}
}
