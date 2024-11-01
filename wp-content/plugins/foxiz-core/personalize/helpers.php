<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Personalize_Helper' ) ) {
	class Foxiz_Personalize_Helper {

		private static $instance;
		private $wpdb;
		private $cookie_path;
		private $cookie_domain;
		const TABLE_NAME = 'rb_personalize';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;
			global $wpdb;
			$this->wpdb          = $wpdb;
			$this->cookie_path   = defined( 'COOKIEPATH' ) ? COOKIEPATH : '/';
			$this->cookie_domain = defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '';

			add_action( 'set_logged_in_cookie', [ $this, 'set_logged_cookies' ], 10, 4 );
			add_action( 'clear_auth_cookie', [ $this, 'clear_cookies' ] );
			add_action( 'wp', [ $this, 'register_schedule' ] );
			add_action( 'foxiz_schedule_history_cleanup', [ $this, 'history_cleanup' ], 10 );
			add_action( 'foxiz_schedule_bookmark_cleanup', [ $this, 'guest_bookmark_cleanup' ], 10 );
			add_action( 'foxiz_schedule_voting_cleanup', [ $this, 'voting_cleanup' ], 20 );
			add_action( 'foxiz_schedule_reaction_cleanup', [ $this, 'reaction_cleanup' ], 30 );
			add_action( 'delete_post', [ $this, 'delete_post' ], 10, 1 );
		}

		public function get_table_name() {

			return $this->wpdb->prefix . self::TABLE_NAME;
		}

		/**
		 * @return false|string
		 */
		function get_identifier() {

			if ( ! empty( $_COOKIE['RBUUID'] ) ) {
				return sanitize_text_field( trim( $_COOKIE['RBUUID'] ) );
			}

			return 0;
		}

		/**
		 * @param $logged_in_cookie
		 * @param $expire
		 * @param $expiration
		 * @param $user_id
		 */
		function set_logged_cookies( $logged_in_cookie, $expire, $expiration, $user_id ) {

			$secure = is_ssl();
			setcookie( 'RBUUID', 'rbu' . $user_id, $expire, $this->cookie_path, $this->cookie_domain, $secure );
			setcookie( 'u_logged', 'yes', $expire, $this->cookie_path, $this->cookie_domain, $secure );
			setcookie( 'personalize_sync', 'yes', 0, $this->cookie_path, $this->cookie_domain, $secure );
		}

		function clear_cookies() {

			$secure = is_ssl();
			setcookie( 'RBUUID', ' ', time() - 86400, $this->cookie_path, $this->cookie_domain, $secure );
			setcookie( 'u_logged', ' ', time() - 86400, $this->cookie_path, $this->cookie_domain, $secure );
		}

		public function get_ip() {

			$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
			foreach (
				[
					'HTTP_CLIENT_IP',
					'HTTP_X_FORWARDED_FOR',
					'HTTP_X_FORWARDED',
					'HTTP_X_CLUSTER_CLIENT_IP',
					'HTTP_FORWARDED_FOR',
					'HTTP_FORWARDED',
					'REMOTE_ADDR',
				] as $key
			) {
				if ( array_key_exists( $key, $_SERVER ) === true ) {
					foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
						$ip = trim( $ip );
						if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) === false ) {
							continue;
						}
					}
				}
			}

			return (string) $ip;
		}

		public function get_user_id() {

			if ( is_user_logged_in() ) {
				return get_current_user_id();
			}

			return 0;
		}

		function register_schedule() {

			if ( foxiz_get_option( 'reading_history' ) ) {
				if ( ! wp_next_scheduled( 'foxiz_schedule_history_cleanup' ) ) {
					wp_schedule_event( time(), 'daily', 'foxiz_schedule_history_cleanup' );
				}
			}

			if ( foxiz_get_option( 'bookmark_system' ) ) {
				if ( ! wp_next_scheduled( 'foxiz_schedule_bookmark_cleanup' ) ) {
					wp_schedule_event( time(), 'daily', 'foxiz_schedule_bookmark_cleanup' );
				}
			}

			if ( foxiz_get_option( 'single_post_reaction' ) ) {
				if ( ! wp_next_scheduled( 'foxiz_schedule_voting_cleanup' ) ) {
					wp_schedule_event( time(), 'daily', 'foxiz_schedule_voting_cleanup' );
				}
			}

			if ( ! wp_next_scheduled( 'foxiz_schedule_reaction_cleanup' ) ) {
				wp_schedule_event( time(), 'daily', 'foxiz_schedule_reaction_cleanup' );
			}
		}

		/**
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public function save_bookmark( $post_id = '' ) {

			if ( empty( $post_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name = $this->get_table_name();
			$action_key = 'bookmark';

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $post_id );
			$ip         = $this->get_ip();
			$current    = current_time( 'mysql' );

			if ( ! empty( $user_id ) ) {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id ) );
			} else {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ) );
			}

			if ( ! empty( $existing_row ) ) {
				return true;
			}

			$query = $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, %s, %s, %d, null, %s)", $identifier, $user_id, $ip, $action_key, $action_id, $current );
			$this->wpdb->query( $query );

			return true;
		}

		/**
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public function delete_bookmark( $post_id = '' ) {

			if ( empty( $post_id ) ) {
				return false;
			}

			$user_id    = $this->get_user_id();
			$action_key = 'bookmark';

			if ( ! empty( $user_id ) ) {
				$this->delete_id_by_user( $user_id, $action_key, $post_id );
			} else {
				$this->delete_id_by_identifier( $this->get_identifier(), $action_key, $post_id );
			}

			return true;
		}

		/**
		 * delete single row by identifier
		 *
		 * @param $action_key
		 * @param $identifier
		 * @param $action_id
		 *
		 * @return false
		 */
		public function delete_id_by_identifier( $identifier, $action_key, $action_id ) {

			if ( empty( $identifier ) || empty( $action_key ) || empty( $action_id ) ) {
				return false;
			}

			$table_name = $this->get_table_name();

			$identifier = sanitize_text_field( $identifier );
			$action_key = sanitize_text_field( $action_key );
			$action_id  = absint( $action_id );

			$query = $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id );

			return $this->wpdb->query( $query );
		}

		/**
		 * delete single row by user
		 *
		 * @param $user_id
		 * @param $action_key
		 * @param $action_id
		 *
		 * @return false
		 */
		public function delete_id_by_user( $user_id, $action_key, $action_id ) {

			if ( empty( $action_key ) || empty( $user_id ) || empty( $action_id ) ) {
				return false;
			}

			$table_name = $this->get_table_name();

			$action_key = sanitize_text_field( $action_key );
			$action_id  = absint( $action_id );

			$query = $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id );

			return $this->wpdb->query( $query );
		}

		/**
		 * @return array
		 */
		public function get_bookmarks() {

			$user_id = $this->get_user_id();

			if ( ! empty( $user_id ) ) {
				return $this->get_action_ids_by_user( $user_id );
			} else {
				return $this->get_action_ids_by_identifier( $this->get_identifier() );
			}
		}

		/**
		 * get posts, authors and category based on identifier
		 *
		 * @param string $identifier
		 * @param string $action_key
		 *
		 * @return array
		 */
		public function get_action_ids_by_identifier( $identifier = '', $action_key = 'bookmark' ) {

			if ( empty( $identifier ) ) {
				return [];
			}

			$table_name = $this->get_table_name();
			$results    = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT action_id FROM {$table_name} WHERE identifier = %s AND action_key = %s ORDER BY created DESC", $identifier, $action_key ), ARRAY_A );

			if ( ! empty( $results ) ) {
				$action_ids = [];
				foreach ( $results as $row ) {
					$action_ids[] = $row['action_id'];
				}

				return array_unique( $action_ids );
			}

			return [];
		}

		/**
		 * get posts, authors and category based on user_id
		 *
		 * @param string $user_id
		 * @param string $action_key
		 *
		 * @return array
		 */
		public function get_action_ids_by_user( $user_id = '', $action_key = 'bookmark' ) {

			if ( empty( $user_id ) ) {
				return [];
			}

			$table_name = $this->get_table_name();
			$results    = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT action_id FROM {$table_name} WHERE user_id = %d AND action_key = %s ORDER BY created DESC", $user_id, $action_key ), ARRAY_A );

			if ( ! empty( $results ) ) {
				$action_ids = [];
				foreach ( $results as $row ) {
					$action_ids[] = $row['action_id'];
				}

				return array_unique( $action_ids );
			}

			return [];
		}

		/**
		 * follows
		 *
		 * @param string $category_id
		 *
		 * @return bool
		 */
		public function save_category( $category_id = '' ) {

			if ( empty( $category_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name = $this->get_table_name();
			$action_key = 'follow_cat';

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $category_id );
			$ip         = $this->get_ip();

			if ( ! empty( $user_id ) ) {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id ) );
			} else {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ) );
			}

			if ( ! empty( $existing_row ) ) {
				return true;
			}
			$current = current_time( 'mysql' );
			$query   = $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, %s, %s, %d, null, %s)", $identifier, $user_id, $ip, $action_key, $action_id, $current );
			$this->wpdb->query( $query );

			return true;
		}

		/**
		 * @param $category_id
		 *
		 * @return bool
		 */
		public function delete_category( $category_id ) {

			if ( empty( $category_id ) ) {
				return false;
			}
			$action_key = 'follow_cat';

			$user_id = $this->get_user_id();
			if ( ! empty( $user_id ) ) {
				$this->delete_id_by_user( $user_id, $action_key, $category_id );
			} else {
				$this->delete_id_by_identifier( $this->get_identifier(), $action_key, $category_id );
			}

			return true;
		}

		/**
		 * @return array
		 */
		public function get_categories_followed() {

			$user_id    = $this->get_user_id();
			$action_key = 'follow_cat';

			if ( ! empty( $user_id ) ) {
				return $this->get_action_ids_by_user( $user_id, $action_key );
			} else {
				return $this->get_action_ids_by_identifier( $this->get_identifier(), $action_key );
			}
		}

		/**
		 * @param string $writer_id
		 *
		 * @return bool
		 */
		public function save_writer( $writer_id = '' ) {

			if ( empty( $writer_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name = $this->get_table_name();
			$action_key = 'follow_auth';

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $writer_id );
			$ip         = $this->get_ip();

			if ( ! empty( $user_id ) ) {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id ) );
			} else {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ) );
			}

			if ( ! empty( $existing_row ) ) {
				return true;
			}
			$current = current_time( 'mysql' );
			$query   = $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, %s, %s, %d, null, %s)", $identifier, $user_id, $ip, $action_key, $action_id, $current );
			$this->wpdb->query( $query );

			return true;
		}

		/**
		 * @param $writer_id
		 *
		 * @return bool
		 */
		public function delete_writer( $writer_id = '' ) {

			if ( empty( $writer_id ) ) {
				return false;
			}

			$user_id    = $this->get_user_id();
			$action_key = 'follow_auth';

			if ( ! empty( $user_id ) ) {
				$this->delete_id_by_user( $user_id, $action_key, $writer_id );
			} else {
				$this->delete_id_by_identifier( $this->get_identifier(), $action_key, $writer_id );
			}

			return true;
		}

		/**
		 * @return array
		 */
		public function get_writers_followed() {

			$user_id    = $this->get_user_id();
			$action_key = 'follow_auth';

			if ( ! empty( $user_id ) ) {
				return $this->get_action_ids_by_user( $user_id, $action_key );
			}

			return $this->get_action_ids_by_identifier( $this->get_identifier(), $action_key );
		}

		/**
		 * @param string $post_id
		 *
		 * @return bool
		 * read history
		 */
		public function save_history( $post_id = '' ) {

			if ( empty( $post_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name = $this->get_table_name();
			$action_key = 'history';

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $post_id );
			$current    = current_time( 'mysql' );

			$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ), ARRAY_A );

			if ( ! empty( $existing_row ) ) {
				$this->wpdb->query( $this->wpdb->prepare( "UPDATE {$table_name} SET created = %s WHERE id = %d", $current, $existing_row['id'] ) );
			} else {
				$query = $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, null, %s, %d, null, %s)", $identifier, $user_id, $action_key, $action_id, $current );
				$this->wpdb->query( $query );
			}

			return true;
		}

		/**
		 * @return array
		 */
		public function get_history() {

			$user_id    = $this->get_user_id();
			$action_key = 'history';

			if ( ! empty( $user_id ) ) {
				return $this->get_action_ids_by_user( $user_id, $action_key );
			} else {
				return $this->get_action_ids_by_identifier( $this->get_identifier(), $action_key );
			}
		}

		/** clear up */
		function history_cleanup() {

			$expired    = absint( foxiz_get_option( 'reading_history_expired', 2 ) );
			$table_name = $this->get_table_name();
			$days_ago   = date( 'Y-m-d H:i:s', strtotime( '-' . $expired . ' days' ) );
			$action_key = 'history';

			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE action_key = %s AND created < %s", $action_key, $days_ago ) );
		}

		/**
		 * @return bool|int|mysqli_result|resource
		 */
		function guest_bookmark_cleanup() {

			$expired = absint( foxiz_get_option( 'bookmark_guest_expired', 14 ) );

			$table_name = $this->get_table_name();
			$days_ago   = date( 'Y-m-d H:i:s', strtotime( '-' . $expired . ' days' ) );

			$recent_identifiers_sql = $this->wpdb->prepare( "SELECT DISTINCT identifier FROM {$table_name} WHERE created >= %s AND user_id = %d", $days_ago, 0 );
			$recent_identifiers     = $this->wpdb->get_col( $recent_identifiers_sql );

			if ( ! empty( $recent_identifiers ) ) {
				$recent_identifiers_string = "'" . implode( "', '", $recent_identifiers ) . "'";

				$old_rows_sql = $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE created < %s AND identifier NOT IN ({$recent_identifiers_string}) AND user_id = %d", $days_ago, 0 );
			} else {
				$old_rows_sql = $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE created < %s AND user_id = %d", $days_ago, 0 );
			}

			return $this->wpdb->query( $old_rows_sql );
		}

		/**
		 * @param $post_id
		 *
		 * @return false
		 */
		function delete_post( $post_id ) {

			if ( empty( $post_id ) ) {
				return false;
			}

			$table_name = $this->get_table_name();

			$this->wpdb->query( $this->wpdb->prepare(
				"DELETE FROM {$table_name} WHERE action_key IN (%s, %s, %s, %s ) AND action_id = %d",
				'bookmark', 'history', 'vote', 'reaction', $post_id
			) );

			return false;
		}

		/**
		 * @param string $reaction
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public function save_vote( $reaction = 'like', $post_id = '' ) {

			if ( empty( $post_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name   = $this->get_table_name();
			$track_ip     = foxiz_get_option( 'reaction_ip' );
			$action_key   = 'vote';
			$action_value = 'like';

			if ( 'like' !== $reaction ) {
				$action_value = 'dislike';
			}

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $post_id );
			$ip         = $this->get_ip();
			$current    = current_time( 'mysql' );

			if ( ! empty( $user_id ) ) {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id ) );
			} else {
				if ( ! $track_ip ) {
					$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ) );
				} else {
					$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE ip = %s AND action_key = %s AND action_id = %d", $ip, $action_key, $action_id ) );
				}
			}

			if ( empty( $existing_row ) ) {
				$this->wpdb->query( $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, %s, %s, %d, %s, %s)", $identifier, $user_id, $ip, $action_key, $action_id, $action_value, $current ) );

				$this->increase_votes( $action_value, $action_id );
			} elseif ( $action_value !== $existing_row->action_value ) {
				$this->wpdb->query( $this->wpdb->prepare( "UPDATE {$table_name} SET action_value = %s, created = %s WHERE id = %d", $action_value, $current, $existing_row->id ) );

				$this->increase_votes( $action_value, $action_id );
				$this->decrease_votes( $existing_row->action_value, $action_id );
			}

			return true;
		}

		/**
		 * @param string $reaction
		 * @param string $action_id
		 *
		 * @return bool
		 */
		public function delete_vote( $reaction = '', $action_id = '' ) {

			if ( empty( $reaction ) || empty( $action_id ) ) {
				return false;
			}

			$action_id  = absint( $action_id );
			$user_id    = $this->get_user_id();
			$action_key = 'vote';

			if ( ! empty( $user_id ) ) {
				$query = $this->delete_id_by_user( $user_id, $action_key, $action_id );
			} else {
				$query = $this->delete_id_by_identifier( $this->get_identifier(), $action_key, $action_id );
			}

			if ( $query ) {
				$this->decrease_votes( $reaction, $action_id );
			}

			return true;
		}

		/**
		 * @param $reaction
		 * @param $action_id
		 * use post meta for filters
		 */
		private function increase_votes( $reaction, $action_id ) {

			$count = get_post_meta( $action_id, 'rb_total_' . $reaction, true );
			update_post_meta( $action_id, 'rb_total_' . $reaction, intval( $count ) + 1 );
		}

		/**
		 * @param $reaction
		 * @param $action_id
		 * use post meta for filters
		 */
		private function decrease_votes( $reaction, $action_id ) {

			$count = get_post_meta( $action_id, 'rb_total_' . $reaction, true );
			$count = intval( $count ) - 1;
			if ( $count < 0 ) {
				$count = 0;
			}
			update_post_meta( $action_id, 'rb_total_' . $reaction, $count );
		}

		/**
		 * voting clear up
		 */
		function voting_cleanup() {

			$guest_expired = absint( foxiz_get_option( 'reaction_guest_expired', 14 ) );
			$user_expired  = absint( foxiz_get_option( 'reaction_logged_expired', 14 ) );

			$table_name = $this->get_table_name();
			$action_key = 'vote';
			$guest_days = date( 'Y-m-d H:i:s', strtotime( '-' . $guest_expired . ' days' ) );
			$user_days  = date( 'Y-m-d H:i:s', strtotime( '-' . $user_expired . ' days' ) );

			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE user_id = 0 AND action_key = %s AND created < %s", $action_key, $guest_days ) );
			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE user_id <> 0 AND action_key = %s AND created < %s", $action_key, $user_days ) );
		}

		/**
		 * reactions
		 *
		 * @param string $reaction
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public function save_reaction( $reaction = '', $post_id = '' ) {

			if ( empty( $post_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$table_name = $this->get_table_name();
			$track_ip   = foxiz_get_option( 'reaction_ip' );

			$action_key   = 'reaction';
			$action_value = strip_tags( $reaction );

			$identifier = $this->get_identifier();
			$user_id    = $this->get_user_id();
			$action_id  = absint( $post_id );
			$ip         = $this->get_ip();
			$current    = current_time( 'mysql' );

			if ( ! empty( $user_id ) ) {
				$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d AND action_key = %s AND action_id = %d", $user_id, $action_key, $action_id ) );
			} else {
				if ( ! $track_ip ) {
					$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE identifier = %s AND action_key = %s AND action_id = %d", $identifier, $action_key, $action_id ) );
				} else {
					$existing_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE ip = %s AND action_key = %s AND action_id = %d", $ip, $action_key, $action_id ) );
				}
			}

			if ( empty( $existing_row ) ) {
				$this->wpdb->query( $this->wpdb->prepare( "INSERT INTO {$table_name} (identifier, user_id, ip, action_key, action_id, action_value, created) VALUES (%s, %d, %s, %s, %d, %s, %s)", $identifier, $user_id, $ip, $action_key, $action_id, $action_value, $current ) );
				$this->update_reactions( $action_value, '', $action_id );
			} elseif ( $action_value !== $existing_row->action_value ) {
				$this->wpdb->query( $this->wpdb->prepare( "UPDATE {$table_name} SET action_value = %s, created = %s WHERE id = %d", $action_value, $current, $existing_row->id ) );
				$this->update_reactions( $action_value, $existing_row->action_value, $action_id );
			}

			return true;
		}

		/**
		 * @param string $reaction
		 * @param string $post_id
		 *
		 * @return bool
		 */
		public function delete_reaction( $reaction = '', $post_id = '' ) {

			if ( empty( $post_id ) || ! $this->get_identifier() ) {
				return false;
			}

			$user_id    = $this->get_user_id();
			$action_key = 'reaction';

			if ( ! empty( $user_id ) ) {
				$query = $this->delete_id_by_user( $user_id, $action_key, $post_id );
			} else {
				$query = $this->delete_id_by_identifier( $this->get_identifier(), $action_key, $post_id );
			}

			if ( $query ) {
				$this->update_reactions( '', strip_tags( $reaction ), $post_id );
			}

			return true;
		}

		/**
		 * @param $reaction_up
		 * @param $reaction_down
		 * @param $action_id
		 *
		 * @return bool
		 */
		public function update_reactions( $reaction_up, $reaction_down, $action_id ) {

			$meta_key = 'rb_total_reaction';

			$counts = get_post_meta( $action_id, $meta_key, true );
			if ( ! $counts || ! is_array( $counts ) ) {
				$counts = [];

				if ( ! empty( $reaction_up ) ) {
					$counts[ $reaction_up ] = 1;
				}
			} else {

				if ( ! empty( $reaction_up ) ) {
					if ( isset( $counts[ $reaction_up ] ) ) {
						$counts[ $reaction_up ] ++;
					} else {
						$counts[ $reaction_up ] = 1;
					}
				}

				if ( ! empty( $reaction_down ) ) {
					if ( isset( $counts[ $reaction_down ] ) && $counts[ $reaction_down ] > 0 ) {
						$counts[ $reaction_down ] --;
					} else {
						$counts[ $reaction_down ] = 0;
					}
				}
			}

			update_post_meta( $action_id, $meta_key, $counts );

			return true;
		}

		function reaction_cleanup() {

			$guest_expired = absint( foxiz_get_option( 'reaction_guest_expired', 14 ) );
			$user_expired  = absint( foxiz_get_option( 'reaction_logged_expired', 14 ) );

			$table_name = $this->get_table_name();
			$action_key = 'reaction';
			$guest_days = date( 'Y-m-d H:i:s', strtotime( '-' . $guest_expired . ' days' ) );
			$user_days  = date( 'Y-m-d H:i:s', strtotime( '-' . $user_expired . ' days' ) );

			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE user_id = 0 AND action_key = %s AND created < %s", $action_key, $guest_days ) );
			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$table_name} WHERE user_id <> 0 AND action_key = %s AND created < %s", $action_key, $user_days ) );
		}

	}
}

/** load */
Foxiz_Personalize_Helper::get_instance();