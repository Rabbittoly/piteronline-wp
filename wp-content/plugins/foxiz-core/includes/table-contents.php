<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Table_Contents', false ) ) {
	class Foxiz_Table_Contents {

		private static $instance;
		private static $is_enabled;

		public $settings;
		public $supported_headings;
		public $table_box_html;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		function __construct() {

			self::$instance = $this;

			if ( ! is_admin() ) {
				add_action( 'wp', [ $this, 'load' ], 10 );
				add_filter( 'ruby_content_elements', [ $this, 'add_table_contents' ], 20 );
			}

			add_filter( 'rank_math/researches/toc_plugins', [ $this, 'rank_math_detection' ], 1000 , 1 );
		}

		function load() {
			self::$is_enabled = $this->is_enabled();
			$this->get_settings();
			$this->get_supported_headings();
			add_filter( 'the_content', [ $this, 'the_content' ], 10 );
		}

		function rank_math_detection( $toc_plugins ) {

			if ( $this->is_post_enabled( get_the_ID() ) ) {
				$toc_plugins['foxiz-core/foxiz-core.php'] = 'Foxiz Core';
			}

			return $toc_plugins;
		}

		/** get all settings */
		public function get_settings() {

			$this->settings = [
				'enable'    => $this->get_setting( 'table_contents_enable' ),
				'heading'   => $this->get_setting( 'table_contents_heading' ),
				'layout'    => $this->get_setting( 'table_contents_layout' ),
				'position'  => $this->get_setting( 'table_contents_position' ),
				'hierarchy' => $this->get_setting( 'table_contents_hierarchy' ),
				'numlist'   => $this->get_setting( 'table_contents_numlist' ),
				'scroll'    => $this->get_setting( 'table_contents_scroll' ),
				'toggle'    => $this->get_setting( 'table_contents_toggle' ),
				'hide'      => $this->get_setting( 'table_contents_hide' ),
			];
		}

		/**
		 * get supported heading settings
		 */
		public function get_supported_headings() {

			$this->supported_headings = [];
			for ( $i = 1; $i <= 6; $i ++ ) {
				if ( $this->get_setting( 'table_contents_h' . $i ) ) {
					array_push( $this->supported_headings, $i );
				}
			}
		}

		/**
		 * @param string $setting_id
		 * @param null   $post_ID
		 *
		 * @return array|false|mixed|void
		 */
		public function get_setting( $setting_id = '', $post_ID = null ) {

			$setting = rb_get_meta( $setting_id, $post_ID );

			if ( ! $setting || 'default' === $setting ) {
				$setting = foxiz_get_option( $setting_id );
			} elseif ( '-1' === (string) $setting ) {
				return false;
			}

			return $setting;
		}

		/**
		 * @param $content
		 *
		 * @return string|string[]
		 * the_content filter
		 */
		public function the_content( $content ) {

			if ( ! self::$is_enabled ) {
				return $content;
			}

			/** check if duplicated add */
			if ( strpos( $content, 'class="ruby-table-contents' ) ) {
				return $content;
			}

			$matches = $this->extract_headings( $content );

			if ( ! $matches || ! is_array( $matches ) || ! $this->minimum_headings( $matches ) ) {
				return $content;
			}

			$this->table_box_html = $this->create_table_contents( $matches );
			$content              = $this->replace_content( $content, $matches );

			/** directly added */
			if ( strpos( $content, '<!--RUBY:TOC-->' ) ) {
				$content              = str_replace( '<!--RUBY:TOC-->', $this->table_box_html, $content );
				$this->table_box_html = '';
			} elseif ( empty( $this->settings['position'] ) ) {
				$content              = $this->table_box_html . $content;
				$this->table_box_html = '';
			}

			return $content;
		}

		/**
		 * @param $content
		 * @param $matches
		 *
		 * @return string|string[]
		 */
		function replace_content( $content, $matches ) {

			$find    = [];
			$replace = [];
			foreach ( $matches as $index => $value ) {
				if ( ! empty( $value[0] ) && ! empty( $value[1] ) && ! empty( $value[2] ) ) {
					array_push( $find, $value[0] );

					if ( foxiz_get_option( 'single_post_ajax_next_post' ) ) {
						$index .= '-' . get_the_ID();
					}

					$classname  = 'rb-heading-index-' . $index;
					$style_attr = '';

					if ( preg_match( '/class="(.*?)"/', $value[0], $match ) ) {
						if ( ! empty( $match[1] ) ) {
							$classname .= ' ' . $match[1];
						}
					}

					if ( preg_match( '/style="(.*?)"/', $value[0], $style_match ) ) {
						$style_attr = ' style="' . $style_match[1] . '"';
					}

					array_push( $replace, '<h' . $value[2] . ' id="' . $this->generate_uid( $this->strip_all_tags_title( $value[0] ) ) . '" class="' . strip_tags( $classname ) . '"' . $style_attr . '>' . $this->remove_heading_tags( $value[0] ) . '</h' . $value[2] . '>' );
				}
			}

			return str_replace( $find, $replace, $content );
		}

		/** remove all tags, shortcode */
		function strip_all_tags_title( $title ) {

			$title = strip_shortcodes( $title );
			$title = preg_replace( "~(?:\[/?)[^/\]]+/?\]~s", '', $title );
			$title = str_replace( ']]>', ']]&gt;', $title );

			return wp_strip_all_tags( $title );
		}

		/**
		 * @param string $string
		 *
		 * @return mixed|string
		 */
		function remove_heading_tags( $string = '' ) {

			if ( preg_match( '|<\s*h[1-6](?:.*)>(.*)</\s*h|Ui', $string, $match ) ) {
				if ( ! empty( $match[1] ) ) {
					return $match[1];
				}
			}

			return $string;
		}

		/**
		 * @param $matches
		 *
		 * @return string
		 * create table contents
		 */
		function create_table_contents( $matches ) {

			if ( $this->settings['hierarchy'] ) {
				$min_depth = 6;

				foreach ( $matches as $index => $value ) {
					if ( $min_depth > $value[2] ) {
						$min_depth = intval( $value[2] );
					}
				}
				foreach ( $matches as $index => $value ) {
					$matches[ $index ]['depth'] = intval( $value[2] ) - $min_depth;
				}
			}

			$class_name = 'ruby-table-contents rbtoc';
			if ( ! empty( $this->settings['layout'] ) && '2' === (string) $this->settings['layout'] ) {
				$class_name .= ' table-left';
			} elseif ( ! empty( $this->settings['layout'] ) && '3' === (string) $this->settings['layout'] ) {
				$class_name .= ' table-left table-fw-single-col';
			} else {
				$class_name .= ' table-fw';
			}

			if ( empty( $this->settings['numlist'] ) ) {
				$class_name .= ' no-numlist';
			}

			$output = '<div class="' . strip_tags( $class_name ) . '">';
			$output .= '<div class="toc-header">';
			if ( ! empty( $this->settings['heading'] ) ) {
				$output .= '<i class="rbi rbi-read"></i><span class="h3">' . esc_html( $this->settings['heading'] ) . '</span>';
			}
			if ( ! empty( $this->settings['toggle'] ) && ! foxiz_is_amp() ) {
				if ( ! empty( $this->settings['hide'] ) ) {
					$output .= '<div class="toc-toggle no-link activate"><i class="rbi rbi-angle-up"></i></div>';
				} else {
					$output .= '<div class="toc-toggle no-link"><i class="rbi rbi-angle-up"></i></div>';
				}
			}
			$output .= '</div>';
			if ( ! empty( $this->settings['hide'] ) && ! empty( $this->settings['toggle'] && ! foxiz_is_amp() ) ) {
				$output .= '<div class="toc-content h5" style="display: none;">';
			} else {
				$output .= '<div class="toc-content h5">';
			}
			foreach ( $matches as $index => $value ) {

				if ( foxiz_get_option( 'single_post_ajax_next_post' ) ) {
					$index .= '-' . get_the_ID();
				}

				$class_name = 'table-link no-link anchor-link';
				if ( ! empty( $value['depth'] ) ) {
					$class_name = 'no-link table-link-depth anchor-link h5 depth-' . $value['depth'];
				}
				$output .= '<a href="#' . $this->generate_uid( $this->strip_all_tags_title( $value[0] ) ) . '" class="' . strip_tags( $class_name ) . '" data-index="rb-heading-index-' . $index . '">';
				$output .= $this->strip_all_tags_title( $value[0] );
				$output .= '</a>';
			}
			$output .= '</div></div>';

			return $output;
		}

		function add_table_contents( $data ) {

			if ( empty( $this->table_box_html ) || empty( $this->settings['position'] ) ) {
				return $data;
			}

			array_push( $data, [
				'render'    => $this->table_box_html,
				'positions' => [ absint( $this->settings['position'] ) ],
			] );

			return $data;
		}

		/**
		 * @param $content
		 *
		 * @return false|mixed
		 */
		public function extract_headings( $content ) {

			$matches = [];
			if ( preg_match_all( '/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER ) ) {

				$matches = $this->filter_headings( $matches );

				return $this->remove_empty( $matches );
			}

			return false;
		}

		/** filter supported headings */
		public function filter_headings( $matches ) {

			$wprm_toc = foxiz_get_option( 'wprm_toc' );

			if ( empty( $wprm_toc ) || '-1' !== (string) $wprm_toc ) {
				foreach ( $matches as $index => $value ) {
					if ( ! in_array( $value[2], $this->supported_headings ) || strpos( $value[1], "none-toc" ) !== false ) {
						unset( $matches[ $index ] );
					}
				}
			} else {

				$pattern = '/(none-toc|wprm-recipe-name|wprm-recipe-header)/';
				foreach ( $matches as $index => $value ) {
					if ( ! in_array( $value[2], $this->supported_headings ) || preg_match( $pattern, $value[1] ) ) {
						unset( $matches[ $index ] );
					}
				}
			}

			return $matches;
		}

		/** remove empty */
		function remove_empty( $matches ) {

			foreach ( $matches as $index => $value ) {
				$text = trim( strip_tags( $value[0] ) );
				if ( empty( $text ) ) {
					unset( $matches[ $index ] );
				}
			}

			return $matches;
		}

		/**
		 * @param $matches
		 *
		 * @return bool
		 * minimum headings
		 */
		public function minimum_headings( $matches ) {

			if ( count( $matches ) < $this->settings['enable'] ) {
				return false;
			}

			return true;
		}

		/**
		 * @param $text
		 *
		 * @return string
		 * generate ID
		 */
		public function generate_uid( $text ) {

			$output = preg_replace( "/\p{P}/u", "", $text );
			$output = str_replace( "&nbsp;", " ", $output );
			$output = remove_accents( $output );
			$output = cyrillic_to_latin( $output );

			$output = sanitize_title_with_dashes( $output );

			return $output;
		}

		function is_enabled() {

			if ( ! is_singular() || is_singular( 'product' ) || is_singular( 'web-story' ) ) {
				return false;
			}

			$current_type = get_post_type();

			if ( 'page' === $current_type ) {
				$individual_setting = rb_get_meta( 'table_contents_page' );
			} else {
				$individual_setting = rb_get_meta( 'table_contents_post' );
			}

			if ( '1' === (string) $individual_setting ) {
				return true;
			} elseif ( '-1' === (string) $individual_setting ) {
				return false;
			}

			/** all post type supported */
			$post_types = trim( foxiz_get_option( 'table_contents_post_types' ) );
			if ( empty( $post_types ) ) {
				return false;
			} elseif ( 'all' === $post_types ) {
				return true;
			}

			$post_types = array_map( 'trim', explode( ',', $post_types ) );

			return in_array( $current_type, $post_types );
		}

		/**
		 * @param $post_id
		 *
		 * @return bool
		 */
		function is_post_enabled( $post_id ) {

			$current_type = get_post_type( $post_id );

			if ( 'page' === $current_type ) {
				$individual_setting = rb_get_meta( 'table_contents_page', $post_id );
			} else {
				$individual_setting = rb_get_meta( 'table_contents_post', $post_id );
			}

			if ( '1' === (string) $individual_setting ) {
				return true;
			} elseif ( '-1' === (string) $individual_setting ) {
				return false;
			}

			/** all post type supported */
			$post_types = trim( foxiz_get_option( 'table_contents_post_types' ) );
			if ( empty( $post_types ) ) {
				return false;
			} elseif ( 'all' === $post_types ) {
				return true;
			}

			$post_types = array_map( 'trim', explode( ',', $post_types ) );

			return in_array( $current_type, $post_types );
		}
	}
}

/** load */
Foxiz_Table_Contents::get_instance();