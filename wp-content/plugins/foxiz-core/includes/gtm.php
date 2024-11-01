<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

add_action( 'init', [ 'Foxiz_GTM_Tags', 'get_instance' ], 10 );

if ( ! class_exists( 'Foxiz_GTM_Tags', false ) ) {
	class Foxiz_GTM_Tags {

		protected static $instance = null;
		static $tag_added = false;

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			self::$instance = $this;

			$gtm_id  = get_option( 'simple_gtm_id' );
			$gtag_id = get_option( 'simple_gtag_id' );

			if ( empty( $gtm_id ) && empty( $gtag_id ) ) {
				return;
			}

			add_action( 'wp_head', [ $this, 'add_script_tag' ] );
			add_action( 'wp_body_open', [ $this, 'add_noscript_tag' ], 1 );
			add_action( 'wp_body_open', [ $this, 'add_amp_tag' ], 15 );
			add_action( 'wp_footer', [ $this, 'add_noscript_tag' ] );
		}

		public function add_script_tag() {

			if ( foxiz_is_amp() ) {
				return;
			}

			$gtm_id  = get_option( 'simple_gtm_id' );
			$gtag_id = get_option( 'simple_gtag_id' );

			if ( empty( $gtm_id ) && empty( $gtag_id ) ) {
				return;
			}

			if ( ! empty( $gtm_id ) ) : ?>
				<!-- Google Tag Manager -->
				<script>(function (w, d, s, l, i) {
                        w[l] = w[l] || [];
                        w[l].push({
                            'gtm.start':
                                new Date().getTime(), event: 'gtm.js'
                        });
                        var f = d.getElementsByTagName(s)[0],
                            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                        j.async = true;
                        j.src =
                            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                        f.parentNode.insertBefore(j, f);
                    })(window, document, 'script', 'dataLayer', '<?php echo esc_attr( $gtm_id ); ?>');</script><!-- End Google Tag Manager -->
			<?php else: ?>
				<!-- Google tag (gtag.js) -->
				<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $gtag_id ); ?>"></script>
				<script> window.dataLayer = window.dataLayer || [];

                    function gtag() {
                        dataLayer.push(arguments);
                    }

                    gtag('js', new Date());
                    gtag('config', '<?php echo esc_attr( $gtag_id ); ?>');
				</script>
			<?php endif;
		}


		public function add_amp_tag() {

			if ( ! foxiz_is_amp() ) {
				return;
			}

			$gtm_id  = get_option( 'simple_gtm_id' );
			$gtag_id = get_option( 'simple_gtag_id' );

			if ( ! empty( $gtm_id ) ) : ?>
				<!-- Google Tag Manager -->
				<amp-analytics config="https://www.googletagmanager.com/amp.json?id=<?php echo esc_attr( $gtm_id ); ?>" data-credentials="include"></amp-analytics>
			<?php else: ?>
				<!-- Google tag (gtag.js) -->
				<amp-analytics type="gtag" data-credentials="include">
					<script type="application/json">
						{
							"vars" : {
								"gtag_id": "<?php echo esc_attr( $gtag_id ); ?>",
								"config" : {
									"<?php echo esc_attr( $gtag_id ); ?>": { "groups": "default" }
								}
							}
						}





					</script>
				</amp-analytics>
			<?php endif;
		}

		public function add_noscript_tag() {
			
			$gtm_id = get_option( 'simple_gtm_id' );
			if ( empty( $gtm_id ) || self::$tag_added ) {
				return;
			}
			?>
			<!-- Google Tag Manager (noscript) -->
			<noscript>
				<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
			</noscript><!-- End Google Tag Manager (noscript) -->
			<?php
			self::$tag_added = true;
		}
	}
}