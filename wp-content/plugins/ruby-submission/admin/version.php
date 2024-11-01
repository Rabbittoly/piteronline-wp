<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

class Ruby_Submission_Version {

	protected static $instance = null;

	private static $plugin_id = 'ruby-submission/ruby-submission.php';
	private static $apiURL = 'https://api.themeruby.com';

	public function __construct() {

		self::$instance = $this;
		add_filter( 'http_request_args', [ $this, 'is_premium' ], PHP_INT_MAX, 2 );
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'plugin_version' ], 50, 1 );
		add_filter( 'pre_set_transient_update_plugins', [ $this, 'plugin_version' ], 50, 1 );
	}

	static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_api_url() {

		return self::$apiURL . '/wp-json';
	}

	public function api_path() {

		return '/ruby/version';
	}

	/**
	 * @param       $url
	 * @param array $args
	 *
	 * @return mixed|WP_Error
	 */
	public function request( $url, $args = [] ) {

		$defaults = [
			'sslverify' => true,
			'headers'   => [ 'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ) ],
			'timeout'   => 5,
		];

		$args = wp_parse_args( $args, $defaults );

		$response = wp_remote_get( esc_url_raw( $url ), $args );

		$response_code    = wp_remote_retrieve_response_code( $response );
		$response_message = wp_remote_retrieve_response_message( $response );

		if ( ! empty( $response->errors ) && isset( $response->errors['http_request_failed'] ) ) {
			return new WP_Error( 'http_error', esc_html( current( $response->errors['http_request_failed'] ) ) );
		}

		if ( 200 !== $response_code && ! empty( $response_message ) ) {
			return new WP_Error( $response_code, $response_message );
		} elseif ( 200 !== $response_code ) {
			return new WP_Error( $response_code, __( 'An unknown API error occurred.', 'foxiz-core' ) );
		} else {
			$return = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( null === $return ) {
				return new WP_Error( 'api_error', __( 'An unknown API error occurred.', 'foxiz-core' ) );
			}

			return $return;
		}
	}

	/**
	 * @param $transient
	 *
	 * @return mixed
	 */
	public function plugin_version( $transient ) {

		$ver = get_site_transient( '_rbsm_NewVersion' );

		if ( empty( $ver ) ) {
			$args   = [ 'type' => 'version' ];
			$domain = $this->get_api_url();
			$path   = $this->api_path();

			$url      = $domain . $path . '?itemID=ruby-post-submission&type=plugin';
			$response = $this->request( $url, $args );

			if ( ! is_wp_error( $response ) && ! empty( $response ) ) {
				$response['slug'] = null;
				$ver              = (object) $response;
			} else {
				$ver = 'api_error';
			}

			set_site_transient( '_rbsm_NewVersion', $ver, HOUR_IN_SECONDS );
		}

		if ( ! empty( $ver->new_version ) && version_compare( $ver->new_version, RUBY_SUBMISSION_VERSION, '>' ) ) {
			$transient->response[ self::$plugin_id ] = $ver;
		} else {
			unset( $transient->response[ self::$plugin_id ] );
		}

		return $transient;
	}

	/**
	 * @param $request
	 * @param $url
	 *
	 * @return mixed
	 */
	public function is_premium( $request, $url ) {

		if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/1.1/' ) ) {

			$data = json_decode( $request['body']['plugins'] );
			unset( $data->plugins->{self::$plugin_id} );

			$request['body']['plugins'] = wp_json_encode( $data );
		}

		return $request;
	}

}

/** load */
Ruby_Submission_Version::get_instance();