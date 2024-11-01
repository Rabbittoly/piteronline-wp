<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/** feature supported */
add_filter( 'bbp_get_the_content', 'ruby_bbp_private_input_field', 20, 3 );
add_action( 'bbp_new_topic_pre_extras', 'ruby_bbp_validate_custom_fields', 10 );
add_action( 'bbp_theme_after_topic_form_content', 'ruby_bbp_topic_custom_fields', 20 );
add_action( 'bbp_new_topic_post_extras', 'ruby_bbp_update_extra_fields', 20, 10 );
add_action( 'bbp_edit_topic_post_extras', 'ruby_bbp_update_extra_fields', 20, 10 );
add_action( 'bbp_new_reply_post_extras', 'ruby_bbp_update_private_field', 20, 10 );
add_action( 'bbp_edit_reply_post_extras', 'ruby_bbp_update_private_field', 20, 10 );
add_action( 'bbp_topic_metabox', 'ruby_bbp_register_topic_status', 10, 1 );
add_action( 'bbp_author_metabox_save', 'ruby_bbp_save_topic_status', 10, 1 );

/** template actions */
add_action( 'bbp_get_topic_content', 'ruby_bbp_render_private', 1, 2 );
add_action( 'bbp_get_topic_content', 'ruby_bbp_render_extra_fields', 100, 2 );
add_action( 'bbp_get_reply_content', 'ruby_bbp_render_private', 1, 2 );
add_action( 'bbp_get_reply_content', 'ruby_bbp_render_extra_fields', 100, 2 );

if ( ! function_exists( 'ruby_bbp_update_extra_fields' ) ) {
	/**
	 * @param $topic_id
	 */
	function ruby_bbp_update_extra_fields( $topic_id ) {

		if ( isset( $_POST['ruby_bbp_private_content'] ) ) {

			$messenger = ruby_bbp_validate_html_field( $_POST['ruby_bbp_private_content'] );
			update_post_meta( $topic_id, 'ruby_bbp_private_content', $messenger );
		}
		if ( isset( $_POST['ruby_bbp_topic_custom_field_1'] ) ) {
			update_post_meta( $topic_id, 'ruby_bbp_topic_custom_field_1', sanitize_text_field( $_POST['ruby_bbp_topic_custom_field_1'] ) );
		}
		if ( isset( $_POST['ruby_bbp_topic_custom_field_2'] ) ) {
			update_post_meta( $topic_id, 'ruby_bbp_topic_custom_field_2', sanitize_text_field( $_POST['ruby_bbp_topic_custom_field_2'] ) );
		}
	}
}

if ( ! function_exists( 'ruby_bbp_update_private_field' ) ) {
	/**
	 * @param $reply_id
	 */
	function ruby_bbp_update_private_field( $reply_id ) {

		if ( isset( $_POST['ruby_bbp_private_content'] ) ) {
			$messenger = ruby_bbp_validate_html_field( $_POST['ruby_bbp_private_content'] );
			update_post_meta( $reply_id, 'ruby_bbp_private_content', $messenger );
		}
	}
}

if ( ! function_exists( 'ruby_bbp_topic_custom_fields' ) ) {
	/**
	 * add custom fields to topic
	 */
	function ruby_bbp_topic_custom_fields() {

		$data = [
			get_option( 'ruby_bbp_topic_custom_field_1', [] ),
			get_option( 'ruby_bbp_topic_custom_field_2', [] ),
		];

		?>
        <div class="bbp-custom-fields">
			<?php
			foreach ( $data as $index => $field ) :
				$index ++;
				$id = 'ruby_bbp_topic_custom_field_' . $index;
				if ( empty( $field['title'] ) ) {
					continue;
				}
				if ( isset( $_POST[ $id ] ) ) {
					$value = sanitize_text_field( $_POST[ $id ] );
				} elseif ( get_the_ID() ) {
					$value = get_post_meta( get_the_ID(), $id, true );
				} else {
					$value = '';
				} ?>
                <p>
                    <label for="<?php echo $id; ?>">
						<?php if ( ! empty( $field['private'] ) ) : ?><i class="bbp-rbi-lock" aria-hidden="true"></i><?php endif;
						echo esc_html( $field['title'] ); ?></label><br>
                    <input type="text" value="<?php echo $value; ?>" size="40" name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                </p>
			<?php
			endforeach; ?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'ruby_bbp_validate_custom_fields' ) ) {
	function ruby_bbp_validate_custom_fields() {

		$data = [
			get_option( 'ruby_bbp_topic_custom_field_1', [] ),
			get_option( 'ruby_bbp_topic_custom_field_2', [] ),
		];

		foreach ( $data as $index => $field ) :
			$index ++;

			if ( empty( $field['title'] ) ) {
				continue;
			}

			$id = 'ruby_bbp_topic_custom_field_' . $index;

			if ( ! empty( $_POST[ $id ] ) ) {
				$content = sanitize_text_field( $_POST[ $id ] );
			}
			if ( empty( $content ) ) {
				bbp_add_error( $id, foxiz_attr__( 'Your topic needs a ', 'ruby-bbp' ) . strtolower( $field['title'] . '.' ) );
			}

		endforeach;
	}
}

if ( ! function_exists( 'ruby_bbp_private_input_field' ) ) {
	function ruby_bbp_private_input_field( $output, $args, $post_content ) {

		if ( ! get_option( 'ruby_bbp_private' ) ) {
			return $output;
		}

		if ( ! empty( $args['context'] ) && 'topic' === $args['context'] ) {
			$id = get_the_ID();
		} else {
			$id = bbp_get_reply_id();
		}

		if ( isset( $_POST['ruby_bbp_private_content'] ) ) {
			$messenger = wp_unslash( $_POST['ruby_bbp_private_content'] );
		} else {
			$messenger = get_post_meta( $id, 'ruby_bbp_private_content', true );
		}

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'context'       => 'topic',
			'wpautop'       => true,
			'media_buttons' => false,
			'textarea_rows' => '5',
			'tabindex'      => false,
			'editor_class'  => 'bbp-the-content',
			'tinymce'       => false,
			'teeny'         => true,
			'quicktags'     => true,
			'dfw'           => false
		), 'get_the_content' );

		ob_start(); ?>
        <div class="bbp-the-content-wrapper">
            <label for="ruby_bbp_private_content"><i class="bbp-rbi-lock" aria-hidden="true"></i><?php echo foxiz_attr__( 'Private Content', 'ruby-bbp' ); ?>
            </label>
			<?php
			if ( bbp_use_wp_editor() ) :
				if ( bbp_use_wp_editor() && ( false !== $r['tinymce'] ) ) {
					remove_filter( 'bbp_get_form_forum_content', 'esc_textarea' );
					remove_filter( 'bbp_get_form_topic_content', 'esc_textarea' );
					remove_filter( 'bbp_get_form_reply_content', 'esc_textarea' );
				}

				add_filter( 'tiny_mce_plugins', 'bbp_get_tiny_mce_plugins' );
				add_filter( 'teeny_mce_plugins', 'bbp_get_tiny_mce_plugins' );
				add_filter( 'teeny_mce_buttons', 'bbp_get_teeny_mce_buttons' );
				add_filter( 'quicktags_settings', 'bbp_get_quicktags_settings' );

				// Output the editor
				wp_editor( $messenger, 'ruby_bbp_private_content', array(
					'wpautop'       => $r['wpautop'],
					'media_buttons' => $r['media_buttons'],
					'textarea_rows' => $r['textarea_rows'],
					'tabindex'      => $r['tabindex'],
					'editor_class'  => $r['editor_class'],
					'tinymce'       => $r['tinymce'],
					'teeny'         => $r['teeny'],
					'quicktags'     => $r['quicktags'],
					'dfw'           => $r['dfw'],
				) );

				// Remove additional TinyMCE plugins after outputting the editor
				remove_filter( 'tiny_mce_plugins', 'bbp_get_tiny_mce_plugins' );
				remove_filter( 'teeny_mce_plugins', 'bbp_get_tiny_mce_plugins' );
				remove_filter( 'teeny_mce_buttons', 'bbp_get_teeny_mce_buttons' );
				remove_filter( 'quicktags_settings', 'bbp_get_quicktags_settings' );
			else : ?>
                <textarea id="ruby_bbp_private_content" class="bbp-private-textarea" name="ruby_bbp_private_content" cols="60" rows="6"><?php echo $messenger; ?></textarea>
			<?php endif; ?>
        </div>
		<?php
		$output .= ob_get_clean();

		return $output;
	}
}

if ( ! function_exists( 'ruby_bbp_register_topic_status' ) ) {
	function ruby_bbp_register_topic_status( $post ) {

		$meta_ID  = 'ruby_topic_status';
		$selected = get_post_meta( $post->ID, $meta_ID, true );
		$data     = ruby_bbp_status_config();
		?>
        <p>
            <strong class="label"><?php echo foxiz_attr__( 'Frontend Status:', 'ruby-bbp' ); ?></strong>
            <label class="screen-reader-text" for="parent_id"><?php echo foxiz_attr__( 'Frontend Status', 'ruby-bbp' ); ?></label>
            <select id="<?php echo esc_attr( $meta_ID ); ?>_select" name="<?php echo esc_attr( $meta_ID ); ?>">
				<?php foreach ( $data as $key => $name ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $selected ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
            </select>
        </p>
	<?php }
}

if ( ! function_exists( 'ruby_bbp_save_topic_status' ) ) {
	function ruby_bbp_save_topic_status( $topic_ID ) {

		$meta_ID = 'ruby_topic_status';

		if ( ! empty( $_POST['ruby_topic_status'] ) ) {
			update_post_meta( $topic_ID, $meta_ID, esc_attr( $_POST['ruby_topic_status'] ) );
		}
	}
}

