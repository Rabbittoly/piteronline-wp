<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_user_login' ) ) {
	function foxiz_render_user_login( $settings ) {

		if ( is_user_logged_in() ) {
			foxiz_render_mini_profile( $settings );
		} else {
			foxiz_render_frontend_login( $settings );
		}
	}
}

if ( ! function_exists( 'foxiz_render_user_register' ) ) {
	function foxiz_render_user_register( $settings = [] ) {

		if ( ! get_option( 'users_can_register' ) ) {
			echo '<div class="rb-wp-errors">' . esc_html__( 'Registering new users is currently not allowed.', 'foxiz-core' ) . '</div>';

			return;
		}

		if ( is_user_logged_in() ) {
			foxiz_render_mini_profile( $settings );

			return;
		}

		if ( is_multisite() ) {
			if ( is_admin() ) {
				echo '<div class="rb-wp-errors">' . esc_html__( 'Registering new user form is not supported for multiple sizes. Use the wp-signup.php form instance instead.', 'foxiz-core' ) . '</div>';
			}

			return;
		}

		foxiz_render_frontend_register( $settings );
	}
}

if ( ! function_exists( 'foxiz_login_form' ) ) {
	function foxiz_login_form( $args = [] ) {

		$defaults = [
			'echo'           => true,
			'redirect'       => foxiz_get_current_permalink(),
			'form_id'        => 'loginform',
			'label_username' => foxiz_html__( 'Username or Email Address', 'foxiz-core' ),
			'label_password' => foxiz_html__( 'Password', 'foxiz-core' ),
			'label_remember' => foxiz_html__( 'Remember me', 'foxiz-core' ),
			'label_log_in'   => foxiz_html__( 'Log In', 'foxiz-core' ),
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => '',
			'value_remember' => false,
		];

		$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

		$login_form_top    = apply_filters( 'login_form_top', '', $args );
		$login_form_middle = apply_filters( 'login_form_middle', '', $args );
		$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

		$form =
			sprintf(
				'<form name="%1$s" id="%1$s" action="%2$s" method="post">',
				esc_attr( $args['form_id'] ),
				esc_url( site_url( 'wp-login.php', 'login_post' ) )
			) .
			$login_form_top .
			sprintf(
				'<div class="login-username">
				<div class="rb-login-label">%1$s</div>
				<input type="text" name="log" required="required" autocomplete="username" class="input" value="%2$s" />
				</div>',
				esc_html( $args['label_username'] ),
				esc_attr( $args['value_username'] )
			) .
			sprintf(
				'<div class="login-password">
				<div class="rb-login-label">%1$s</div>
				<div class="is-relative">
				<input type="password" name="pwd" required="required" autocomplete="current-password" spellcheck="false" class="input" value="" />
				<span class="rb-password-toggle"><i class="rbi rbi-show"></i></span></div>
				</div>',
				esc_html( $args['label_password'] )
			) .
			$login_form_middle .
			'<div class="remember-wrap">' .
			( $args['remember'] ?
				sprintf(
					'<p class="login-remember"><label class="rb-login-label"><input name="rememberme" type="checkbox" id="%1$s" value="forever"%2$s /> %3$s</label></p>',
					esc_attr( $args['id_remember'] ),
					( $args['value_remember'] ? ' checked="checked"' : '' ),
					esc_html( $args['label_remember'] )
				) : ''
			) . '<a class="lostpassw-link" href="' . wp_lostpassword_url() . '">' . foxiz_html__( 'Lost your password?', 'foxiz-core' ) . '</a></div>' .
			sprintf(
				'<p class="login-submit">
				<input type="submit" name="wp-submit" class="button button-primary" value="%1$s" />
				<input type="hidden" name="redirect_to" value="%2$s" />
			</p>',
				esc_attr( $args['label_log_in'] ),
				esc_url( $args['redirect'] )
			) .
			$login_form_bottom .
			'</form>';

		if ( $args['echo'] ) {
			echo $form;
		} else {
			return $form;
		}
	}
}

if ( ! function_exists( 'foxiz_render_frontend_login' ) ) {
	function foxiz_render_frontend_login( $settings ) {

		$action    = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
		$http_post = ( 'POST' === $_SERVER['REQUEST_METHOD'] );

		$class_name = 'user-login-form';
		$args       = [ 'form_id' => 'form' . $settings['uuid'] ];

		foreach ( [ 'label_username', 'label_password', 'label_remember', 'label_log_in' ] as $label ) {

			if ( ! empty( $settings[ $label ] ) ) {
				$args[ $label ] = $settings[ $label ];
			}
		}

		$login_url = foxiz_get_option( 'login_page' );
		$redirect  = foxiz_get_option( 'login_redirect' );

		$args['redirect'] = ! empty( $redirect ) ? esc_url( $redirect ) : foxiz_get_current_permalink();

		$can_register = get_option( 'users_can_register', false );
		if ( $can_register ) {
			$class_name .= ' can-register';
		} ?>
		<div class="<?php echo strip_tags( $class_name ); ?>">
			<?php if ( $action === 'lostpassword' ) : ?>
				<?php if ( ! empty( $settings['lostpassword_header'] ) ) : ?>
					<div class="login-form-header rb-text">
						<?php foxiz_render_inline_html( $settings['lostpassword_header'] ); ?>
					</div>
				<?php endif;
				if ( isset( $_GET['passw_error_msg'] ) ): ?>
					<div class="rb-wp-errors"><?php echo esc_html( sanitize_text_field( $_GET['passw_error_msg'] ) ); ?></div>
				<?php endif;
				$user_login = '';
				if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
					$user_login = wp_unslash( $_POST['user_login'] );
				} ?>
				<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ); ?>" method="post">
					<p class="login-username">
						<label class="rb-login-label"><?php foxiz_html_e( 'Username or Email Address', 'foxiz-core' ); ?></label>
						<input type="text" name="user_login" required="required" class="input" value="<?php echo esc_attr( $user_login ); ?>" autocapitalize="off" autocomplete="username"/>
					</p>
					<?php do_action( 'lostpassword_form' ); ?>
					<?php if ( ! empty( $login_url ) ) : ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( add_query_arg( 'action', 'confirmemail', esc_url( $login_url ) ) ); ?>"/>
					<?php endif; ?>
					<p class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php foxiz_html_e( 'Get New Password', 'foxiz-core' ); ?>"/>
					</p>
				</form>
				<div class="login-form-footer">
					<?php printf(
						'%s <a href="%s">%s</a>',
						foxiz_html__( 'Are you a member?', 'foxiz-core' ),
						wp_login_url(),
						foxiz_html__( 'Login', 'foxiz-core' )
					); ?>
				</div>
			<?php elseif ( $action === 'confirmemail' ) : ?>
				<?php if ( ! empty( $settings['confirmemail_header'] ) ) : ?>
					<div class="login-form-header rb-text">
						<?php foxiz_render_inline_html( $settings['confirmemail_header'] ); ?>
					</div>
				<?php endif; ?>
				<div class="rb-wp-errors email-sent-msg"><?php foxiz_html_e( 'Password reset email has been sent.', 'foxiz-core' ); ?></div>
				<div class="login-form-footer">
					<?php printf(
						'%s <a href="%s">%s</a>',
						foxiz_html__( 'Check your email, then visit the', 'foxiz-core' ),
						wp_login_url(),
						foxiz_html__( 'Login page', 'foxiz-core' )
					); ?>
				</div>
			<?php else :
				if ( ! empty( $settings['login_header'] ) ) : ?>
					<div class="login-form-header rb-text"><?php foxiz_render_inline_html( $settings['login_header'] ); ?></div>
				<?php endif;
				if ( isset( $_GET['auth_error_msg'] ) ): ?>
					<div class="rb-wp-errors"><?php echo esc_html( sanitize_text_field( $_GET['auth_error_msg'] ) ); ?></div>
				<?php endif;
				foxiz_login_form( $args ); ?>
				<div class="login-form-footer">
					<?php if ( $can_register ) {
						printf(
							'%s <a class="register-link" href="%s">%s</a>',
							foxiz_html__( 'Not a member?', 'foxiz-core' ),
							wp_registration_url(),
							foxiz_html__( 'Sign Up', 'foxiz-core' )
						);
					} ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_render_mini_profile' ) ) {
	function foxiz_render_mini_profile( $settings ) {

		if ( empty( $settings['logged_status'] ) ) {
			return;
		}

		global $current_user;
		$logout_redirect = foxiz_get_option( 'logout_redirect' );

		if ( empty( $logout_redirect ) ) {
			if ( function_exists( 'foxiz_get_current_permalink' ) ) {
				$logout_redirect = foxiz_get_current_permalink();
			} else {
				$logout_redirect = get_home_url( '/' );
			}
		}

		if ( '2' === (string) $settings['logged_status'] ) : ?>
			<div class="logged-status">
				<span class="logged-welcome"><?php echo foxiz_html__( 'Hi,', 'foxiz-core' ) . '<strong>' . foxiz_strip_tags( $current_user->display_name ) . '</strong>'; ?></span>
				<a class="s-logout-link" href="<?php echo wp_logout_url( $logout_redirect ); ?>"><?php echo foxiz_html__( 'Sign Out', 'foxiz-core' ); ?>
					<i class="rbi rbi-logout"></i></a>
			</div>
		<?php else :
			if ( ! empty( $current_user->roles[0] ) ) {
				$role = translate_user_role( $current_user->roles[0] );
			} ?>
			<div class="logged-status">
				<div class="logged-status-inner">
					<div class="logged-status-avatar"><?php echo get_avatar( $current_user->ID ); ?></div>
					<div class=logged-status-info>
						<span class="logged-welcome"><?php echo foxiz_html__( 'Hi,', 'foxiz-core' ) . '<strong>' . foxiz_strip_tags( $current_user->display_name ) . '</strong>'; ?></span>
						<?php if ( ! empty( $role ) ) : ?>
							<span class="status-role"><?php foxiz_render_inline_html( $role ); ?></span><?php endif; ?>
					</div>
				</div>
				<a class="s-logout-link" href="<?php echo wp_logout_url( $logout_redirect ); ?>"><?php echo foxiz_html__( 'Sign Out', 'foxiz-core' ); ?>
					<i class="rbi rbi-logout"></i></a>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'foxiz_render_frontend_register' ) ) {
	function foxiz_render_frontend_register( $settings = [] ) {

		$action    = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'register';
		$http_post = ( 'POST' === $_SERVER['REQUEST_METHOD'] );

		if ( empty( $settings['label_username'] ) ) {
			$settings['label_username'] = foxiz_html__( 'Username', 'foxiz-core' );
		}
		if ( empty( $settings['label_email'] ) ) {
			$settings['label_email'] = foxiz_html__( 'Email', 'foxiz-core' );
		}
		if ( empty( $settings['label_register'] ) ) {
			$settings['label_register'] = foxiz_html__( 'Sign Up', 'foxiz-core' );
		}
		if ( empty( $settings['reg_passmail'] ) ) {
			$settings['reg_passmail'] = foxiz_html__( 'Registration confirmation will be emailed to you', 'foxiz-core' );
		}
		$redirect_to = foxiz_get_current_permalink();

		if ( empty( $settings['register_complete_header'] ) ) {
			$settings['register_complete_header'] = esc_html__( 'Thank you for registering! Please check your email for confirmation', 'foxiz-core' );
		}

		$user_login = '';
		$user_email = '';

		if ( $http_post && ! is_user_logged_in() ) {

			if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
				$user_login = wp_unslash( $_POST['user_login'] );
			}

			if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
				$user_email = wp_unslash( $_POST['user_email'] );
			}

			$errors = register_new_user( $user_login, $user_email );

			if ( ! is_wp_error( $errors ) ) {
				$redirect_to = foxiz_get_option( 'login_register' );
				if ( empty( $redirect_to ) ) {
					$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : foxiz_get_current_permalink();
				}
				$redirect_to = add_query_arg( 'action', 'regcheckemail', esc_url( $redirect_to ) );
				wp_safe_redirect( $redirect_to );
				exit;
			}
		}
		?>
		<div class="user-login-form is-register">
			<?php if ( $action === 'regcheckemail' ) : ?>
				<div class="login-form-header reg-complete-info rb-text">
					<?php foxiz_render_inline_html( $settings['register_complete_header'] ); ?>
					<div class="p-divider is-divider-zigzag"></div>
				</div>
			<?php else : ?>
				<?php if ( ! empty( $settings['register_header'] ) ) : ?>
					<div class="login-form-header rb-text">
						<?php foxiz_render_inline_html( $settings['register_header'] ); ?>
					</div>
				<?php endif;
				if ( $http_post && ! empty( $errors ) ) {
					echo foxiz_get_wp_errors( $errors );
				} ?>
				<form name="registerform" action="" method="post">
					<div>
						<div class="rb-login-label"><?php echo esc_attr( $settings['label_username'] ); ?></div>
						<input type="text" name="user_login" class="input" value="" required="required" autocapitalize="off" autocomplete="username"/>
						<?php if ( ! empty( $settings['username_desc'] ) ) : ?>
							<div class="username-desc is-meta"><?php foxiz_render_inline_html( $settings['username_desc'] ); ?></div>
						<?php endif; ?>
					</div>
					<div>
						<div class="rb-login-label"><?php echo esc_attr( $settings['label_email'] ); ?></div>
						<input type="email" name="user_email" class="input" value="" required="required" autocomplete="email"/>
					</div>
					<?php do_action( 'register_form' ); ?>
					<div class="reg-passmail">
						<i class="rbi rbi-shield"></i><?php echo esc_attr( $settings['reg_passmail'] ); ?></div>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>"/>
					<div class="submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr( $settings['label_register'] ); ?>"/>
					</div>
				</form>
			<?php endif; ?>
			<div class="login-form-footer">
				<?php printf(
					'%s <a href="%s">%s</a>',
					foxiz_html__( 'Are you a member?', 'foxiz-core' ),
					wp_login_url(),
					foxiz_html__( 'Login', 'foxiz-core' )
				); ?>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'foxiz_render_register_link_edit_mode' ) ) {
	function foxiz_render_register_link_edit_mode( $settings ) {

		if ( ! get_option( 'users_can_register' ) ) {
			echo '<div class="rb-error">' . esc_html__( 'Register disabled!', 'foxiz-core' ) . '</div>';

			return;
		}

		foxiz_register_link( $settings );
	}
}

if ( ! function_exists( 'foxiz_render_register_link' ) ) {
	function foxiz_render_register_link( $settings ) {

		if ( is_user_logged_in() || ! get_option( 'users_can_register' ) ) {
			return;
		}

		foxiz_register_link( $settings );
	}
}

if ( ! function_exists( 'foxiz_register_link' ) ) {
	function foxiz_register_link( $settings ) {

		$label = ! empty( $settings['label_text'] ) ? $settings['label_text'] : foxiz_html__( 'Sign Up', 'foxiz' );
		?>
		<div class="wnav-holder widget-h-login is-register header-dropdown-outer">
		<a href="<?php echo wp_registration_url(); ?>" class="reg-link is-btn header-element" data-title="<?php echo esc_attr( $label ); ?>" aria-label="<?php echo esc_attr( $label ); ?>"><span><?php foxiz_render_inline_html( $label ); ?></span></a>
		</div>
	<?php }
}