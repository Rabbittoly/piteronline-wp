<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_pretty_number' ) ) {
	/**
	 * @param $number
	 *
	 * @return int|string
	 * pretty number
	 */
	function foxiz_pretty_number( $number ) {

		$number = intval( $number );
		if ( $number > 999999 ) {
			$number = str_replace( '.00', '', number_format( ( $number / 1000000 ), 2 ) ) . foxiz_attr__( 'M', 'foxiz-core' );
		} elseif ( $number > 999 ) {
			$number = str_replace( '.0', '', number_format( ( $number / 1000 ), 1 ) ) . foxiz_attr__( 'k', 'foxiz-core' );
		}

		return $number;
	}
}

if ( ! function_exists( 'foxiz_is_ruby_template' ) ) {
	function foxiz_is_ruby_template() {

		if ( 'rb-etemplate' !== get_post_type() && is_admin() ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'foxiz_extract_number' ) ) {
	/**
	 * @param $str
	 *
	 * @return int
	 * extract number
	 */
	function foxiz_extract_number( $str ) {

		return intval( preg_replace( '/[^0-9]+/', '', $str ), 10 );
	}
}

/**
 * @param        $text
 * @param string $domain
 *
 * @return mixed|string|void
 * foxiz html
 */
if ( ! function_exists( 'foxiz_html__' ) ) {
	function foxiz_html__( $text, $domain = 'foxiz-core' ) {

		$id   = foxiz_convert_to_id( $text );
		$data = get_option( 'rb_translated_data', [] );
		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		} else {
			$translated = esc_html__( $text, $domain );
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_attr__' ) ) {
	/**
	 * @param        $text
	 * @param string $domain
	 *
	 * @return mixed|string|void
	 * foxiz translate
	 */
	function foxiz_attr__( $text, $domain = 'foxiz-core' ) {

		$id   = foxiz_convert_to_id( $text );
		$data = get_option( 'rb_translated_data', [] );

		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		} else {
			$translated = esc_attr__( $text, $domain );
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_html_e' ) ) {
	/**
	 * @param        $text
	 * @param string $domain
	 * foxiz html e
	 */
	function foxiz_html_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_html__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_attr_e' ) ) {
	/**
	 * @param        $text
	 * @param string $domain
	 * foxiz attr e
	 */
	function foxiz_attr_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_attr__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_page_selection' ) ) {
	/**
	 * @return array
	 * get page select
	 */
	function foxiz_page_selection() {

		$data                   = [];
		$args['posts_per_page'] = - 1;
		$pages                  = get_pages( $args );

		if ( ! empty ( $pages ) ) {
			foreach ( $pages as $page ) {
				$data[ $page->ID ] = $page->post_title;
			}
		}

		return $data;
	}
}

if ( ! function_exists( 'foxiz_is_svg' ) ) {
	function foxiz_is_svg( $attachment = '' ) {

		return substr( $attachment, - 4 ) === '.svg';
	}
}

if ( ! function_exists( 'foxiz_calc_average_rating' ) ) {
	function foxiz_calc_average_rating( $post_id ) {

		global $wpdb;

		$data         = [];
		$total_review = [];
		$raw_total    = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rbrating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
			GROUP BY meta_value",
				$post_id
			)
		);

		foreach ( $raw_total as $count ) {
			$total_review[] = absint( $count->meta_value_count );
		}

		$data['count'] = array_sum( $total_review );

		$ratings = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rbrating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0",
				$post_id
			)
		);

		if ( ! empty( $data['count'] ) && ! empty( $ratings ) ) {
			$data['average'] = number_format( $ratings / $data['count'], 1, '.', '' );
		}

		update_post_meta( $post_id, 'foxiz_user_rating', $data );

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_image_size' ) ) {
	/**
	 * @param $filename
	 *
	 * @return array|false
	 */
	function foxiz_get_image_size( $filename ) {

		if ( is_string( $filename ) ) {
			return @getimagesize( $filename );
		}

		return [];
	}
}

if ( ! function_exists( 'foxiz_get_theme_mode' ) ) {
	/**
	 * @return string
	 */
	function foxiz_get_theme_mode() {

		$dark_mode = foxiz_get_option( 'dark_mode' );

		if ( empty( $dark_mode ) || 'browser' === $dark_mode ) {
			return 'default';
		} elseif ( 'dark' === $dark_mode ) {
			return 'dark';
		}

		$is_cookie_mode = (string) foxiz_get_option( 'dark_mode_cookie' );
		if ( '1' === $is_cookie_mode ) {
			$id = FOXIZ_CORE::get_instance()->get_dark_mode_id();
			if ( ! empty( $_COOKIE[ $id ] ) ) {
				return $_COOKIE[ $id ];
			}
		}

		$first_visit_mode = foxiz_get_option( 'first_visit_mode' );
		if ( empty( $first_visit_mode ) ) {
			$first_visit_mode = 'default';
		}

		return $first_visit_mode;
	}
}

if ( ! function_exists( 'foxiz_conflict_schema' ) ) {
	/**
	 * @return bool
	 */
	function foxiz_conflict_schema() {

		$schema_conflicting_plugins = [
			'seo-by-rank-math/rank-math.php',
			'all-in-one-seo-pack/all_in_one_seo_pack.php',
		];

		$active_plugins = foxiz_get_active_plugins();

		if ( ! empty( $active_plugins ) ) {
			foreach ( $schema_conflicting_plugins as $plugin ) {
				if ( in_array( $plugin, $active_plugins, true ) ) {
					return true;
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_ajax_localize_script' ) ) {
	/**
	 * @param $id
	 * @param $js_settings
	 *
	 * @return false
	 */
	function foxiz_ajax_localize_script( $id, $js_settings ) {

		if ( empty( $id ) ) {
			return false;
		}

		if ( ! empty( $js_settings['live_block'] ) ) {
			if ( ! empty( $js_settings['paged'] ) ) {
				$js_settings['paged'] = 0;
			}
			if ( empty( $js_settings['page_max'] ) ) {
				$js_settings['page_max'] = 2;
			}
			$output = '<script>';
			$output .= esc_attr( $id ) . '.paged = ' . $js_settings['paged'] . ';';
			$output .= esc_attr( $id ) . '.page_max = ' . $js_settings['page_max'] . ';';
			$output .= '</script>';
			echo $output;
		} else {
			echo '<script> var ' . esc_attr( $id ) . ' = ' . wp_json_encode( $js_settings ) . '</script>';
		}
	}
}

if ( ! function_exists( 'foxiz_wc_strip_wrapper' ) ) {
	function foxiz_wc_strip_wrapper( $html ) {

		if ( empty( $html ) || ! class_exists( 'DOMDocument', false ) ) {
			return false;
		}

		$output = '';
		libxml_use_internal_errors( true );
		$dom = new DOMDocument();
		@$dom->loadHTML( '<?xml encoding="' . get_bloginfo( 'charset' ) . '" ?>' . $html );
		libxml_clear_errors();
		$xpath = new DomXPath( $dom );
		$nodes = $xpath->query( "//*[contains(@class, 'products ')]" );
		if ( $nodes->item( 0 ) ) {
			foreach ( $nodes->item( 0 )->childNodes as $node ) {
				$output .= $dom->saveHTML( $node );
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_term_link' ) ) {
	function foxiz_get_term_link( $term, $taxonomy = '' ) {

		if ( ! is_object( $term ) ) {
			$term = (int) $term;
		}

		$link = get_term_link( $term, $taxonomy );
		if ( empty( $link ) || is_wp_error( $link ) ) {
			return '#';
		}

		return $link;
	}
}

if ( ! function_exists( 'foxiz_amp_suppressed_elementor' ) ) {
	function foxiz_amp_suppressed_elementor() {

		if ( foxiz_is_amp() ) {
			$amp_options        = get_option( 'amp-options' );
			$suppressed_plugins = ( ! empty( $amp_options['suppressed_plugins'] ) && is_array( $amp_options['suppressed_plugins'] ) ) ? $amp_options['suppressed_plugins'] : [];
			if ( ! empty( $suppressed_plugins['elementor'] ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_get_twitter_name' ) ) {
	function foxiz_get_twitter_name() {

		if ( is_single() ) {
			global $post;
			$name = get_the_author_meta( 'twitter_url', $post->post_author );
		}

		if ( empty( $name ) ) {
			$name = foxiz_get_option( 'twitter' );
		}

		if ( empty( $name ) ) {
			$name = get_bloginfo( 'name' );
		}

		$name = parse_url( $name, PHP_URL_PATH );

		$name = str_replace( '/', '', (string) $name );

		return $name;
	}
}

if ( ! function_exists( 'foxiz_get_current_permalink' ) ) {
	function foxiz_get_current_permalink() {

		if ( isset( $_SERVER ) && is_array( $_SERVER ) ) {
			$scheme = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
			$host   = ! empty( $_SERVER['HTTP_HOST'] ) ? wp_unslash( $_SERVER['HTTP_HOST'] ) : null;
			$path   = ! empty( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

			if ( $host ) {
				return esc_url_raw( sprintf( '%s://%s%s', $scheme, $host, $path ) );
			}
		}

		global $wp;

		return home_url( add_query_arg( [], $wp->request ) );
	}
}

if ( ! function_exists( 'foxiz_count_content' ) ) {
	function foxiz_count_content( $content = '' ) {

		if ( empty( $content ) ) {
			return '-1';
		}

		// Separate HTML tags with spaces to prevent them from being concatenated
		$content = preg_replace( '/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $content );

		// Convert newlines to <br> tags to handle line breaks
		$content = nl2br( $content );

		// Strip all HTML tags from the content
		$content = strip_tags( $content );

		if ( preg_match( "/[\x{4e00}-\x{9fa5}]+/u", $content ) ) {
			// Chinese characters
			$count = mb_strlen( $content, get_bloginfo( 'charset' ) );
		} elseif ( preg_match( "/[А-Яа-яЁё]/u", $content ) ) {
			// Cyrillic characters
			$count = count( preg_split( '~[^\p{L}\p{N}\']+~u', $content ) );
		} elseif ( preg_match( "/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7A3}]+/u", $content ) ) {
			// Korean characters
			$count = count( preg_split( '/[^\p{L}\p{N}\']+/', $content ) );
		} elseif ( preg_match( "/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}]+/u", $content ) ) {
			// Japanese characters
			$count = count( preg_split( '/[^\p{L}\p{N}\']+/', $content ) );
		} else {
			// Default to word count for other languages
			$count = count( preg_split( '/\s+/', $content ) );
		}

		if ( empty( $count ) ) {
			$count = '-1';
		}

		return $count;
	}
}

