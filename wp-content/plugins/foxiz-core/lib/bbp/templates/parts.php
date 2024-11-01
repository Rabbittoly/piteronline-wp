<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ruby_bbp_render_private' ) ) {
	/**
	 * @param $output
	 * @param $id
	 *
	 * @return string
	 */
	function ruby_bbp_render_private( $output, $id ) {

		if ( ! get_option( 'ruby_bbp_private' ) ) {
			return $output;
		}

		$private_html = get_post_meta( $id, 'ruby_bbp_private_content', true );

		if ( empty( $private_html ) ) {
			return $output;
		}

		$content = '<div class="bbp-private-section">';

		if ( ! current_user_can( 'moderate' ) && ! current_user_can( 'edit_reply', $id ) ) {
			$content .= '<div class="content-blocked"><i class="bbp-rbi-shield-lock" aria-hidden="true"></i><span>' . foxiz_attr__( 'Private content area', 'ruby-bbp' ) . '</span></div>';
		} else {
			$content .= '<div class="content-unlocked">';
			$content .= '<span class="h4"><i class="bbp-rbi-unlock" aria-hidden="true"></i>' . foxiz_attr__( 'Private content', 'ruby-bbp' ) . '</span>';
			$content .= '<div class="private-content">' . $private_html . '</div>';
			$content .= '</div>';
		}

		$content .= '</div>';

		$output .= $content;

		return $output;
	}
}

if ( ! function_exists( 'ruby_bbp_sidebar' ) ) {
	function ruby_bbp_sidebar( $name = '' ) {

		if ( is_active_sidebar( $name ) ) : ?>
            <div class="sidebar-wrap ruby-bbp-sidebar">
                <div class="sidebar-inner clearfix"><?php dynamic_sidebar( $name ); ?></div>
            </div>
		<?php endif;
	}
}

if ( ! function_exists( 'ruby_bbp_toggle_topic' ) ) {

	function ruby_bbp_toggle_topic() {

		if ( ! bbp_current_user_can_access_create_topic_form() ) {
			return false;
		}

		$classes = 'bbp-toggle-topic';
		if ( bbp_has_errors() ) {
			$classes .= ' is-topic-error';
		}
		?>
        <div class="<?php echo esc_attr( $classes ); ?>">
            <div class="bbp-toggle-intro">
				<?php if ( get_option( 'ruby_bbp_topic_image' ) ) : ?>
                    <img src="<?php echo esc_url( get_option( 'ruby_bbp_topic_image' ) ); ?>" alt="<?php echo foxiz_attr__( 'create new topic', 'ruby-bbp' ); ?>" class="bbp-toggle-intro-image">
				<?php endif; ?>
                <div class="bbp-toggle-intro-content">
					<?php if ( get_option( 'ruby_bbp_topic_title' ) ) : ?>
                        <span class="h2 bbp-toggle-intro-title"><?php echo esc_html( get_option( 'ruby_bbp_topic_title' ) ); ?></span>
					<?php endif; ?>
					<?php if ( get_option( 'ruby_bbp_topic_description' ) ) : ?>
                        <span class="bbp-toggle-intro-desc"><?php echo esc_html( get_option( 'ruby_bbp_topic_description' ) ); ?></span>
					<?php endif; ?>
                </div>
                <a id="bbp-new-topic-toggle-btn" class="is-btn" href="#"><?php echo get_option( 'ruby_bbp_topic_button', 'Add a New Topic' ); ?></a>
            </div>
            <div id="bbp-new-topic-toggle" class="bbp-new-topic-form">
				<?php bbp_get_template_part( 'form', 'topic' ); ?>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'ruby_bbp_topic_status' ) ) {
	/**
	 * topic status
	 */
	function ruby_bbp_topic_status() {

		$status          = bbp_get_topic_status();
		$frontend_status = get_post_meta( get_the_ID(), 'ruby_topic_status', true );
		$status_data     = ruby_bbp_status_config();
		$class_name      = 'meta-status meta-status-' . trim( $status );
		$label           = '';

		switch ( $status ) {
			case  'closed'  :
				$label = foxiz_attr__( 'Closed', 'ruby-bbp' );
				break;
			case  'publish'  :
				$label = foxiz_attr__( 'Open', 'ruby-bbp' );
				break;
		}

		if ( ! empty( $frontend_status ) ) {
			$class_name .= ' bbp-status-' . trim( $frontend_status );
			if ( ! empty( $status_data[ $frontend_status ] ) ) {
				$label = $status_data[ $frontend_status ];
			}
		}

		echo '<span class="' . $class_name . '">' . $label . '</span>';
	}
}

if ( ! function_exists( 'ruby_bbp_render_extra_fields' ) ) {
	/**
	 * @param $output
	 * @param $id
	 *
	 * @return string
	 */
	function ruby_bbp_render_extra_fields( $output, $id ) {

		if ( 'topic' !== get_post_type( $id ) ) {
			return $output;
		}
		$buffer = '';
		$data   = [
			get_option( 'ruby_bbp_topic_custom_field_1', [] ),
			get_option( 'ruby_bbp_topic_custom_field_2', [] ),
		];

		foreach ( $data as $index => $field ) :
			$index ++;

			if ( empty( $field['title'] ) ) {
				continue;
			}

			$str = get_post_meta( $id, 'ruby_bbp_topic_custom_field_' . $index, true );
			if ( empty( $str ) ) {
				continue;
			}

			$buffer .= '<div class="bbp-extra-meta">';
			$buffer .= '<div class="extra-meta-title">' . esc_html( $field['title'] ) . '</div>';
			if ( ! empty( $field['private'] ) ) {
				if ( ! current_user_can( 'moderate' ) && ! current_user_can( 'edit_reply', $id ) ) {
					$buffer .= '<span class="extra-meta-content"><i class="bbp-rbi-lock" aria-hidden="true"></i>' . foxiz_attr__( 'hidden', 'ruby-bbp' ) . '</span>';
				} else {
					$buffer .= '<span class="extra-meta-content">' . esc_html( $str ) . '</span>';
				}
			} else {
				$buffer .= '<span class="extra-meta-content">' . esc_html( $str ) . '</span>';
			}
			$buffer .= '</div>';
		endforeach;

		if ( ! empty( $buffer ) ) {
			$output .= '<div class="bbp-extra-meta-wrap">' . $buffer . '</div>';
		}

		return $output;
	}
}


if ( ! function_exists( 'ruby_bbp_get_author_link' ) ) {
	function ruby_bbp_get_author_link( $args = array() ) {

		$post_id = is_numeric( $args ) ? (int) $args : 0;

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'post_id'    => $post_id,
			'link_title' => '',
			'type'       => 'both',
			'size'       => 80,
			'sep'        => ''
		), 'get_author_link' );

		// Confirmed topic
		if ( bbp_is_topic( $r['post_id'] ) ) {
			return ruby_bbp_get_topic_author_link( $r );
			// Confirmed reply
		} elseif ( bbp_is_reply( $r['post_id'] ) ) {
			return ruby_bbp_get_reply_author_link( $r );
		}

		// Default return value
		$author_link = '';

		// Neither a reply nor a topic, so could be a revision
		if ( ! empty( $r['post_id'] ) ) {

			// Get some useful reply information
			$user_id    = get_post_field( 'post_author', $r['post_id'] );
			$author_url = bbp_get_user_profile_url( $user_id );
			$anonymous  = bbp_is_reply_anonymous( $r['post_id'] );

			// Generate title with the display name of the author
			if ( empty( $r['link_title'] ) ) {
				$author = get_the_author_meta( 'display_name', $user_id );
				$title  = empty( $anonymous )
					? foxiz_attr__( "View %s's profile", 'ruby-bbp' )
					: foxiz_attr__( "Visit %s's website", 'ruby-bbp' );

				$r['link_title'] = sprintf( $title, $author );
			}

			// Setup title and author_links array
			$author_links = array();
			$link_title   = ! empty( $r['link_title'] )
				? ' title="' . esc_attr( $r['link_title'] ) . '"'
				: '';

			// Get avatar (unescaped, because HTML)
			if ( ( 'avatar' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['avatar'] = ruby_bbp_get_avatar( $user_id, $r['size'] );
			}

			// Get display name (escaped, because never HTML)
			if ( ( 'name' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['name'] = esc_html( get_the_author_meta( 'display_name', $user_id ) );
			}

			// Empty array
			$links  = array();
			$sprint = '<span %1$s>%2$s</span>';

			// Wrap each link
			foreach ( $author_links as $link => $link_text ) {
				$link_class = ' class="bbp-author-' . esc_attr( $link ) . '"';
				$links[]    = sprintf( $sprint, $link_class, $link_text );
			}

			// Juggle
			$author_links = $links;
			unset( $links );

			// Filter sections
			$sections = apply_filters( 'bbp_get_author_links', $author_links, $r, $args );

			// Assemble sections into author link
			$author_link = implode( $r['sep'], $sections );

			// Only wrap in link if profile exists
			if ( empty( $anonymous ) && bbp_user_has_profile( $user_id ) ) {
				$author_link = sprintf( '<a href="%1$s"%2$s%3$s>%4$s</a>', esc_url( $author_url ), $link_title, ' class="bbp-author-link"', $author_link );
			}
		}

		// Filter & return
		return apply_filters( 'bbp_get_author_link', $author_link, $r, $args );
	}
}

if ( ! function_exists( 'ruby_bbp_get_topic_author_link' ) ) {
	function ruby_bbp_get_topic_author_link( $args = array() ) {

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'post_id'    => 0,
			'link_title' => '',
			'type'       => 'both',
			'size'       => 80,
			'sep'        => '',
			'show_role'  => false
		), 'get_topic_author_link' );

		// Default return value
		$author_link = '';

		// Used as topic_id
		$topic_id = is_numeric( $args )
			? bbp_get_topic_id( $args )
			: bbp_get_topic_id( $r['post_id'] );

		// Topic ID is good
		if ( ! empty( $topic_id ) ) {

			// Get some useful topic information
			$author_url = bbp_get_topic_author_url( $topic_id );
			$anonymous  = bbp_is_topic_anonymous( $topic_id );

			// Tweak link title if empty
			if ( empty( $r['link_title'] ) ) {
				$author = bbp_get_topic_author_display_name( $topic_id );
				$title  = empty( $anonymous )
					? foxiz_attr__( "View %s's profile", 'ruby-bbp' )
					: foxiz_attr__( "Visit %s's website", 'ruby-bbp' );

				$link_title = sprintf( $title, $author );
				// Use what was passed if not
			} else {
				$link_title = $r['link_title'];
			}

			// Setup title and author_links array
			$author_links = array();
			$link_title   = ! empty( $link_title )
				? ' title="' . esc_attr( $link_title ) . '"'
				: '';

			// Get avatar (unescaped, because HTML)
			if ( ( 'avatar' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['avatar'] = ruby_bbp_get_topic_author_avatar( $topic_id, $r['size'] );
			}

			// Get display name (escaped, because never HTML)
			if ( ( 'name' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['name'] = esc_html( bbp_get_topic_author_display_name( $topic_id ) );
			}

			// Empty array
			$links  = array();
			$sprint = '<span %1$s>%2$s</span>';

			// Wrap each link
			foreach ( $author_links as $link => $link_text ) {
				$link_class = ' class="bbp-author-' . esc_attr( $link ) . '"';
				$links[]    = sprintf( $sprint, $link_class, $link_text );
			}

			// Juggle
			$author_links = $links;
			unset( $links );

			// Filter sections
			$sections = apply_filters( 'bbp_get_topic_author_links', $author_links, $r, $args );

			// Assemble sections into author link
			$author_link = implode( $r['sep'], $sections );

			// Only wrap in link if profile exists
			if ( empty( $anonymous ) && bbp_user_has_profile( bbp_get_topic_author_id( $topic_id ) ) ) {
				$author_link = sprintf( '<a href="%1$s"%2$s%3$s>%4$s</a>', esc_url( $author_url ), $link_title, ' class="bbp-author-link"', $author_link );
			}

			// Role is not linked
			if ( true === $r['show_role'] ) {
				$author_link .= bbp_get_topic_author_role( array( 'topic_id' => $topic_id ) );
			}
		}

		// Filter & return
		return apply_filters( 'bbp_get_topic_author_link', $author_link, $r, $args );
	}
}

if ( ! function_exists( 'ruby_bbp_get_reply_author_link' ) ) {
	function ruby_bbp_get_reply_author_link( $args = array() ) {

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'post_id'    => 0,
			'link_title' => '',
			'type'       => 'both',
			'size'       => 80,
			'sep'        => '',
			'show_role'  => false
		), 'get_reply_author_link' );

		// Default return value
		$author_link = '';

		// Used as reply_id
		$reply_id = is_numeric( $args )
			? bbp_get_reply_id( $args )
			: bbp_get_reply_id( $r['post_id'] );

		// Reply ID is good
		if ( ! empty( $reply_id ) ) {

			// Get some useful reply information
			$author_url = bbp_get_reply_author_url( $reply_id );
			$anonymous  = bbp_is_reply_anonymous( $reply_id );

			// Tweak link title if empty
			if ( empty( $r['link_title'] ) ) {
				$author = bbp_get_reply_author_display_name( $reply_id );
				$title  = empty( $anonymous )
					? foxiz_attr__( "View %s's profile", 'ruby-bbp' )
					: foxiz_attr__( "Visit %s's website", 'ruby-bbp' );

				$link_title = sprintf( $title, $author );
				// Use what was passed if not
			} else {
				$link_title = $r['link_title'];
			}

			// Setup title and author_links array
			$author_links = array();
			$link_title   = ! empty( $link_title )
				? ' title="' . esc_attr( $link_title ) . '"'
				: '';

			// Get avatar (unescaped, because HTML)
			if ( ( 'avatar' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['avatar'] = ruby_bbp_get_reply_author_avatar( $reply_id, $r['size'] );
			}

			// Get display name (escaped, because never HTML)
			if ( ( 'name' === $r['type'] ) || ( 'both' === $r['type'] ) ) {
				$author_links['name'] = esc_html( bbp_get_reply_author_display_name( $reply_id ) );
			}

			// Empty array
			$links  = array();
			$sprint = '<span %1$s>%2$s</span>';

			// Wrap each link
			foreach ( $author_links as $link => $link_text ) {
				$link_class = ' class="bbp-author-' . esc_attr( $link ) . '"';
				$links[]    = sprintf( $sprint, $link_class, $link_text );
			}

			// Juggle
			$author_links = $links;
			unset( $links );

			// Filter sections
			$sections = apply_filters( 'bbp_get_reply_author_links', $author_links, $r, $args );

			// Assemble sections into author link
			$author_link = implode( $r['sep'], $sections );

			// Only wrap in link if profile exists
			if ( empty( $anonymous ) && bbp_user_has_profile( bbp_get_reply_author_id( $reply_id ) ) ) {
				$author_link = sprintf( '<a href="%1$s"%2$s%3$s>%4$s</a>', esc_url( $author_url ), $link_title, ' class="bbp-author-link"', $author_link );
			}

			// Role is not linked
			if ( true === $r['show_role'] ) {
				$author_link .= bbp_get_reply_author_role( array( 'reply_id' => $reply_id ) );
			}
		}

		// Filter & return
		return apply_filters( 'bbp_get_reply_author_link', $author_link, $r, $args );
	}
}

if ( ! function_exists( 'ruby_bbp_get_reply_author_avatar' ) ) {
	/**
	 * @param int $reply_id
	 * @param int $size
	 *
	 * @return false|mixed|string|void
	 */
	function ruby_bbp_get_reply_author_avatar( $reply_id = 0, $size = 40 ) {

		$reply_id = bbp_get_reply_id( $reply_id );

		if ( empty( $reply_id ) ) {
			return false;
		}

		if ( ! bbp_is_reply_anonymous( $reply_id ) ) {
			$id_or_email = bbp_get_reply_author_id( $reply_id );
		} else {
			$id_or_email = get_post_meta( $reply_id, '_bbp_anonymous_email', true );
		}

		return ruby_bbp_get_avatar( $id_or_email, $size );
	}
}

if ( ! function_exists( 'ruby_bbp_get_topic_author_avatar' ) ) {

	function ruby_bbp_get_topic_author_avatar( $topic_id = 0, $size = 40 ) {

		$topic_id = bbp_get_topic_id( $topic_id );
		if ( empty( $topic_id ) ) {
			return '';
		}

		if ( ! bbp_is_topic_anonymous( $topic_id ) ) {
			$id_or_email = bbp_get_topic_author_id( $topic_id );
		} else {
			$id_or_email = get_post_meta( $topic_id, '_bbp_anonymous_email', true );
		}

		return ruby_bbp_get_avatar( $id_or_email, $size );
	}
}

function ruby_bbp_get_avatar( $id_or_email, $size ) {

	if ( ! get_option( 'ruby_bbp_avatar' ) ) {
		return get_avatar( $id_or_email, $size );
	}

	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', absint( $id_or_email ) );
	} else {
		$user = get_user_by( 'email', absint( $id_or_email ) );
	}

	if ( empty( $user->ID ) ) {
		return get_avatar( $id_or_email, $size );
	}

	$args     = get_avatar_data( $id_or_email, [ 'default' => 404 ] );
	$cache_ID = 'ruby_avatar_code_' . $user->ID;
	$code     = get_transient( $cache_ID );
	if ( empty( $code ) ) {
		$response = wp_remote_get( $args['url'] );
		$code     = wp_remote_retrieve_response_code( $response );
		set_transient( $cache_ID, $code, 2592000 );
	}

	if ( '404' === (string) $code ) {
		$letter = substr( trim( $user->display_name ), 0, 1 );

		$output = '<div class="ruby-avatar ruby-letter-' . strtolower( $letter ) . '" data-letter="' . $letter . '">';
		$output .= '<img class="avatar wp-user-avatar" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==">';
		$output .= '</div>';

		return $output;
	}

	return get_avatar( $id_or_email, $size );
}