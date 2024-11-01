<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;
?>
<div class="rb-dashboard-wrap">
	<div class="rb-dashboard-intro rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content">
			<div class="rb-intro-content-inner">
				<h1 class="rb-dashboard-headline">
					<i class="rbi-dash rbi-dash-license" aria-hidden="true"></i><?php esc_html_e( 'Welcome to Foxiz', 'foxiz-core' ); ?>
				</h1>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'Foxiz is all set up! Begin crafting something remarkable. We’re excited for you to use it!', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-featured">
				<img src="<?php echo FOXIZ_CORE_URL . 'admin/assets/dashboard.jpg'; ?>" alt="<?php esc_html_e( 'Home', 'foxiz-core' ); ?>">
			</div>
		</div>
		<div class="rb-dashboard-steps">
			<div class="rb-dashboard-step is-checked">
				<h3><?php esc_html_e( 'Register Your Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Start by registering your sites to initiate the setup procedure.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-check"></i></div>
			</div>
			<?php
			$class_import_step = get_option('_rb_flag_imported', false) ? 'rb-dashboard-step is-checked' : 'rb-dashboard-step';
			?>
			<a class="<?php echo esc_attr($class_import_step); ?>" href="<?php echo ! empty( $menu['import']['url'] ) ? esc_url( $menu['import']['url'] ) : '#'; ?>">
				<h3><?php esc_html_e( 'Select a Rebuild Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Select a rebuilt website to import and update your site\'s look.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon is-checked"><i class="rbi-dash rbi-dash-layer"></i></div>
			</a>
			<a class="rb-dashboard-step" href="<?php echo esc_url( $menu['options']['url'] ); ?>">
				<h3><?php esc_html_e( 'Personalize Your Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Adjust your site\'s design and features to align with your preferences and requirements.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon is-checked"><i class="rbi-dash rbi-dash-arrow-right"></i></div>
			</a>
		</div>
		<div class="rb-activate-form is-activated">
			<form method="post" action="" id="rb-deregister-theme-form">
				<div class="rb-inline-big-form-wrap">
					<div class="rb-panel-input">
						<label class="rb-panel-label" for="purchase_code"><?php esc_html_e( 'Purchase Code', 'foxiz-core' ); ?></label>
						<input type="text" value="<?php echo rb_admin_hide_code( $purchase_code ); ?>" name="activated_purchase_code" class="rb-panel-input-text" readonly>
					</div>
					<div class="rb-panel-submit deregister-action">
						<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span>
						<input type="submit" class="rb-panel-button deregister-button" value="<?php esc_attr_e( 'Deactivate License', 'foxiz-core' ); ?>" name="deregister-theme" id="rb-deregister-theme-btn"/>
					</div>
				</div>
				<div class="rb-response-info is-hidden"></div>
			</form>
			<p class="reactivation-info">
				<i class="rbi-dash rbi-dash-info"></i><?php esc_html_e( 'For theme reactivation, Please deactivate the license before utilizing the purchase code once again!', 'foxiz-core' ); ?>
			</p>
		</div>
	</div>
	<?php if ( $can_install_plugins ) : ?>
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-section-header">
			<h2><i class="rbi-dash rbi-dash-plugin"></i><?php esc_html_e( 'Recommended Plugins', 'foxiz-core' ); ?></h2>
			<div class="scs-form">
				<input id="rb-search-form" type="text" placeholder="<?php esc_html_e( 'Search plugins...', 'foxiz-core' ); ?>">
				<i class="rbi-dash rbi-dash-search"></i>
			</div>
		</div>
		<div class="rb-plugins-wrap rb-search-area">
			<?php foreach ( $recommended_plugins as $name => $plugin ) :
				$status_label = [
					'not_found' => esc_html__( 'Not Installed', 'foxiz-core' ),
					'inactive'  => esc_html__( 'Inactive', 'foxiz-core' ),
					'active'    => esc_html__( 'Activated', 'foxiz-core' ),
				];

				$button_label = [
					'not_found' => esc_html__( 'Install', 'foxiz-core' ),
					'inactive'  => esc_html__( 'Activate', 'foxiz-core' ),
					'active'    => esc_html__( 'Deactivate', 'foxiz-core' ),
				]; ?>
				<div class="rb-plugin rb-search-item" data-status="is-<?php echo esc_attr( $plugin['status'] ); ?>">
					<div class="rb-plugin-content">
						<div class="rb-plugin-header">
							<span class="rb-plugin-status"><?php echo esc_html( $status_label[ $plugin['status'] ] ); ?></span>
							<img class="rb-plugin-icon" src="<?php echo esc_url( $plugin['icon'] ); ?>" alt="<?php echo esc_attr( $plugin['title'] ); ?>">
							<h3 class="rb-plugin-title"><?php echo esc_html( $plugin['title'] ); ?></h3>
						</div>
						<p class="rb-plugin-description"><?php echo esc_html( $plugin['description'] ); ?></p>
					</div>
					<div class="rb-plugin-button">
						<div class="rb-plugin-error"></div>
						<?php
						echo sprintf(
							'<button class="ruby-install-plugin ruby-button rb-panel-button is-outlined" data-plugin="%s" data-status="%s"><span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load" aria-hidden="true"></i></span><span class="button-label">%s</span></button>',
							esc_attr( $name ),
							esc_attr( $plugin['status'] ),
							esc_html( $button_label[ $plugin['status'] ] )
						); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-section-header"><h2>
				<i class="rbi-dash rbi-dash-phpinfo"></i><?php esc_html_e( 'PHP Information', 'foxiz-core' ); ?></h2>
		</div>
		<div class="rb-info-content">
			<?php if ( ! empty( $system_info ) && is_array( $system_info ) ) :
				foreach ( $system_info as $info => $val ) :
					$class_name = 'info-el';
					if ( isset( $val['passed'] ) && ! $val['passed'] ) {
						$class_name .= ' is-warning';
					} ?>
					<div class="<?php echo esc_attr( $class_name ); ?>">
						<div class="info-content">
							<span class="info-label"><?php echo esc_attr( $val['title'] ) ?></span>
							<span class="info-value"><?php echo esc_attr( $val['value'] ) ?></span>
						</div>
						<?php if ( isset( $val['passed'] ) && ! $val['passed'] ) : ?>
							<span class="info-warning"><?php echo esc_attr( $val['warning'] ) ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach;
			endif; ?>
		</div>
	</div>
</div>