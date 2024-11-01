<?php
/**
 * Redux Search Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy)
 * @class   Redux_Extension_Search
 * @version 3.4.5
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extension_Search' ) ) {

	/**
	 * Class Redux_Extension_Search
	 */
	class ReduxFramework_Extension_search {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		protected $parent;
		public $extension_url;
		public $extension_dir;
		public static $theInstance;

		public $is_field = false;
		public $field_name;

		public static $version = '3.4.5';
		public $extension_name = 'Search';

		/**
		 * Redux_Extension_Search constructor.
		 *
		 * @param object $parent ReduxFramework object pointer.
		 */
		public function __construct( $parent ) {

			$this->parent     = $parent;
			$this->field_name = 'search';

			self::$theInstance = $this;

			if ( empty( $this->extension_dir ) ) {
				$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
				$this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
			}

			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] === $this->parent->args['page_slug'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 0 );
			}
		}

		/**
		 * Support file enqueue.
		 */
		public function enqueue() {

			/**
			 * Redux search JS
			 * filter 'redux/page/{opt_name}/enqueue/redux-extension-search-js
			 *
			 * @param string  bundled javascript
			 */
			wp_enqueue_script(
				'redux-extension-search-js', $this->extension_url . 'redux-extension-search' . Redux_Functions::isMin() . '.js',
				'',
				self::$version,
				true
			);

			// Values used by the javascript.
			wp_localize_script(
				'redux-extension-search-js',
				'reduxSearch',
				array(
					'search' => esc_html__( 'Search for settings...', 'redux-framework' ),
				)
			);
		}
	}
}
