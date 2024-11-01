<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'init', [ 'Ruby_Reaction', 'get_instance' ], PHP_INT_MAX );

if ( ! class_exists( 'Ruby_Reaction', false ) ) {
	class Ruby_Reaction {

		private static $instance;
		public $defaults = [];

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		/**
		 * Ruby_Reaction constructor.
		 */
		public function __construct() {

			self::$instance = $this;

			$this->register_reaction();

			add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 10 );
			add_action( 'wp_ajax_nopriv_rbreaction', [ $this, 'add_reaction' ] );
			add_action( 'wp_ajax_rbreaction', [ $this, 'add_reaction' ] );
			add_shortcode( 'ruby_reaction', [ $this, 'render' ] );
		}

		/**
		 * @return false|mixed|void
		 * get settings
		 */
		public function get_settings() {

			$settings  = [];
			$reactions = foxiz_get_option( 'reaction_items' );

			if ( isset( $reactions['enabled']['placebo'] ) ) {
				unset( $reactions['enabled']['placebo'] );
			}

			if ( empty( $reactions['enabled'] ) || ! is_array( $reactions['enabled'] ) ) {
				return false;
			}

			foreach ( array_keys( $reactions['enabled'] ) as $reaction ) {
				if ( isset( $this->defaults[ $reaction ] ) ) {
					array_push( $settings, $this->defaults[ $reaction ] );
				}
			}

			return apply_filters( 'ruby_reactions', $settings );
		}

		/**
		 * @return bool
		 *  check AMP
		 */
		public function is_amp() {

			if ( function_exists( 'foxiz_is_amp' ) ) {
				return foxiz_is_amp();
			}

			return false;
		}

		/**
		 * register scripts
		 */
		function register_scripts() {

			if ( ! $this->is_amp() ) {
				wp_register_script( 'rb-reaction', plugin_dir_url( __FILE__ ) . 'reaction.js', [
					'jquery',
					'foxiz-core',
				], FOXIZ_CORE_VERSION, true );
			}
		}

		/**
		 * enqueue_scripts
		 */
		public function enqueue_scripts() {

			if ( ! wp_script_is( 'rb-reaction' ) ) {
				wp_enqueue_script( 'rb-reaction' );
			}
		}

		/**
		 * register_reaction
		 */
		function register_reaction() {

			$this->defaults = [
				'love'      => [
					'id'    => 'love',
					'title' => foxiz_html__( 'Love', 'foxiz-core' ),
					'icon'  => 'icon-love',
				],
				'sad'       => [
					'id'    => 'sad',
					'title' => foxiz_html__( 'Sad', 'foxiz-core' ),
					'icon'  => 'icon-sad',
				],
				'happy'     => [
					'id'    => 'happy',
					'title' => foxiz_html__( 'Happy', 'foxiz-core' ),
					'icon'  => 'icon-happy',
				],
				'sleepy'    => [
					'id'    => 'sleepy',
					'title' => foxiz_html__( 'Sleepy', 'foxiz-core' ),
					'icon'  => 'icon-sleepy',
				],
				'angry'     => [
					'id'    => 'angry',
					'title' => foxiz_html__( 'Angry', 'foxiz-core' ),
					'icon'  => 'icon-angry',
				],
				'dead'      => [
					'id'    => 'dead',
					'title' => foxiz_html__( 'Dead', 'foxiz-core' ),
					'icon'  => 'icon-dead',
				],
				'wink'      => [
					'id'    => 'wink',
					'title' => foxiz_html__( 'Wink', 'foxiz-core' ),
					'icon'  => 'icon-wink',
				],
				'cry'       => [
					'id'    => 'cry',
					'title' => foxiz_html__( 'Cry', 'foxiz-core' ),
					'icon'  => 'icon-cry',
				],
				'embarrass' => [
					'id'    => 'embarrass',
					'title' => foxiz_html__( 'Embarrass', 'foxiz-core' ),
					'icon'  => 'icon-embarrass',
				],
				'joy'       => [
					'id'    => 'cry',
					'title' => foxiz_html__( 'Joy', 'foxiz-core' ),
					'icon'  => 'icon-joy',
				],
				'shy'       => [
					'id'    => 'shy',
					'title' => foxiz_html__( 'Shy', 'foxiz-core' ),
					'icon'  => 'icon-shy',
				],
				'surprise'  => [
					'id'    => 'surprise',
					'title' => foxiz_html__( 'Surprise', 'foxiz-core' ),
					'icon'  => 'icon-surprise',
				],
			];
		}

		/**
		 * @param $icon
		 *
		 * @return false|string
		 * get svg
		 */
		public function get_svg( $icon ) {

			if ( function_exists( 'foxiz_get_svg' ) ) {
				return foxiz_get_svg( $icon, '', 'reaction' );
			}

			return false;
		}

		/**
		 * @param $attrs
		 *
		 * @return false|string
		 * render reactions
		 */
		function render( $attrs ) {

			if ( $this->is_amp() ) {
				return false;
			}

			$attrs = shortcode_atts( [
				'id' => '',
			], $attrs );

			$post_id = $attrs['id'];

			if ( empty( $post_id ) ) {
				$post_id = get_the_ID();
			}

			if ( empty( $post_id ) ) {
				return false;
			}

			$this->enqueue_scripts();
			$output    = '';
			$reactions = $this->get_settings();
			$total     = $this->get_count( $post_id );

			if ( is_array( $reactions ) && count( $reactions ) ) {
				$output .= '<aside id="reaction-' . $post_id . '" class="rb-reaction reaction-wrap" data-pid="' . esc_attr( $post_id ) . '">';
				foreach ( $reactions as $reaction ) {
					if ( empty( $reaction['id'] ) ) {
						continue;
					}
					$output .= '<div class="reaction" data-reaction="' . $reaction['id'] . '">';
					$output .= '<span class="reaction-content">';
					$output .= '<i class="reaction-icon">' . $this->get_svg( $reaction['icon'] ) . '</i>';
					$output .= '<span class="reaction-title h6">' . foxiz_strip_tags( $reaction['title'] ) . '</span>';
					$output .= '</span>';
					$output .= '<span class="reaction-count">';
					if ( empty( $total[ $reaction['id'] ] ) ) {
						$output .= '0';
					} else {
						$output .= foxiz_pretty_number( $total[ $reaction['id'] ] );
					}
					$output .= '</span>';
					$output .= '</div>';
				}

				$output .= '</aside>';
			}

			return $output;
		}

		/**
		 * add reaction
		 */
		function add_reaction() {

			if ( empty( $_GET['pid'] ) || empty( $_GET['reaction'] ) || empty( $_GET['type'] ) ) {
				wp_send_json_error();
			}

			$post_id  = esc_attr( $_GET['pid'] );
			$reaction = esc_attr( $_GET['reaction'] );
			$type     = esc_attr( $_GET['type'] );

			if ( 'add' === $type ) {
				Foxiz_Personalize_Helper::get_instance()->save_reaction( $reaction, $post_id );
			} elseif ( 'delete' === $type ) {
				Foxiz_Personalize_Helper::get_instance()->delete_reaction( $reaction, $post_id );
			}

			wp_send_json_success( '' );
		}

		/**
		 * @param $post_id
		 *
		 * @return array|string
		 */
		function get_count( $post_id = '' ) {

			if ( empty( $post_id ) ) {
				$post_id = get_the_ID();
			}

			/** safe update reactions */
			$old_reactions = get_post_meta( $post_id, 'ruby_reactions', true );
			if ( ! empty( $old_reactions ) && is_array( $old_reactions ) ) {
				$temp = [];
				foreach ( $old_reactions as $key => $values ) {
					$temp[ $key ] = count( $values );
				}
				update_post_meta( $post_id, 'rb_total_reaction', $temp );
				delete_post_meta( $post_id, 'ruby_reactions' );

				return $temp;
			}

			$data = get_post_meta( $post_id, 'rb_total_reaction', true );

			if ( ! is_array( $data ) ) {
				return [];
			}

			return $data;
		}
	}
}