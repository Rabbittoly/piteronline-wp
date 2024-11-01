<?php

namespace SW_WAPF_PRO\Includes\Controllers {

	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

	class Extended_Controller {

		public function __construct() {

			add_action( 'wp_enqueue_scripts',   [$this, 'register_assets']);

			add_action('admin_init',            [$this,'deactivate_other_versions']);

            $path = trailingslashit( wapf_get_setting( 'path' ) );
			include_once( $path . 'extend/formulas.php');
			include_once( $path . 'extend/date.php');
			include_once( $path . 'extend/weight.php');
			include_once( $path . 'extend/calc.php');

		}

		public function register_assets() {

			$url =  trailingslashit(wapf_get_setting('url')) . 'assets/';
			$version = wapf_get_setting('version');

			wp_enqueue_script('wapf-extended', $url . 'js/extended.min.js', ['jquery','wapf-frontend'], $version, true);

		}

		public function deactivate_other_versions() {
			if(current_user_can('activate_plugins')) {
				deactivate_plugins('advanced-product-fields-for-woocommerce-pro/advanced-product-fields-for-woocommerce-pro.php');
			}
		}

	}
}