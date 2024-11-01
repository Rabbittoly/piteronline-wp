<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

?>
<div class="rb-dashboard-wrap">
	<div class="rb-dashboard-intro rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content">
			<div class="rb-intro-content-inner">
				<h1 class="rb-dashboard-headline"><?php esc_html_e( 'Registration License', 'foxiz-core' ); ?></h1>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'Please enter the purchase code in the input field provided. This code verifies your license and unlocks all premium features of the theme.', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-featured">
				<img src="<?php echo FOXIZ_CORE_URL . 'admin/assets/dashboard.jpg'; ?>" alt="<?php esc_html_e( 'Home', 'foxiz-core' ); ?>">
			</div>
		</div>
		<div class="rb-dashboard-steps">
			<div class="rb-dashboard-step is-checked">
				<h3><?php esc_html_e( 'Register Your Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Start by registering your sites to initiate the setup procedure.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-key"></i></div>
			</div>
			<div class="rb-dashboard-step disabled">
				<h3><?php esc_html_e( 'Select a Rebuild Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Select a rebuilt website to import and update your site\'s look.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon is-checked"><i class="rbi-dash rbi-dash-layer"></i></div>
			</div>
			<div class="rb-dashboard-step disabled">
				<h3><?php esc_html_e( 'Personalize Your Website', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Adjust your site\'s design and features to align with your preferences and requirements.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon is-checked"><i class="rbi-dash rbi-dash-arrow-right"></i></div>
			</div>
		</div>
		<div class="rb-activate-form">
			<form method="post" action="" id="rb-register-theme-form">
				<div class="rb-panel-input">
					<label class="rb-panel-label" for="purchase_code"><?php esc_html_e( 'Purchase Code', 'foxiz-core' ); ?></label>
					<input type="text" name="purchase_code" class="rb-panel-input-text" placeholder="<?php esc_html_e( 'Input purchase code...', 'foxiz-core' ); ?>" required>
					<span class="rb-error-info is-hidden"><i class="dashicons-info-outline dashicons-before" aria-hidden="true"></i></span>
				</div>
				<div class="rb-panel-input">
					<label class="rb-panel-label" for="email"><?php esc_html_e( 'Support Email', 'foxiz-core' ); ?></label>
					<input type="email" name="email" class="rb-panel-input-text" placeholder="<?php esc_html_e( 'Input your email...', 'foxiz-core' ); ?>" required>
					<span class="rb-error-info is-hidden"><i class="dashicons-info-outline dashicons-before" aria-hidden="true"></i></span>
				</div>
				<div class="rb-panel-submit">
					<input id="rb-register-theme-btn" type="submit" class="rb-panel-button is-big-button" name="register-theme" value="<?php esc_attr_e( 'Activate License', 'foxiz-core' ); ?>">
					<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span>
					<span class="rb-purchase-code-info"><a href="#"><?php esc_html_e( 'How to find your purchase code?', 'foxiz-core' ) ?></a></span>
				</div>
				<div class="rb-response-info is-hidden"></div>
			</form>
		</div>
	</div>
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-section-header"><h2>
				<i class="rbi-dash rbi-dash-info"></i><?php esc_html_e( 'PHP Information', 'foxiz-core' ); ?></h2></div>
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