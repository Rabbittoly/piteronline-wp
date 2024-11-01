<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;
?>
<div class="rb-dashboard-wrap rb-dashboard-gtm">
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content">
			<div class="rb-intro-content-inner">
				<h2 class="rb-dashboard-title">
					<?php esc_html_e( 'Google Tag Manager & Analytics 4', 'foxiz-core' ); ?>
				</h2>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'You can choose to input either the Google Tag Manager Container ID or the Gtag Measurement ID. If both are provided, Google Tag Manager will take priority.', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-big-icon"><i class="rbi-dash rbi-dash-gtm"></i></div>
		</div>
		<div class="rb-dashboard-steps col-50">
			<?php $step_class = ( ! empty( $gtm_id ) || ! empty( $gtag_id ) ) ? 'rb-dashboard-step is-checked' : 'rb-dashboard-step'; ?>
			<a class="<?php echo esc_attr( $step_class ); ?>" href="https://tagmanager.google.com/" target="_blank" rel="nofollow">
				<h3><?php esc_html_e( 'Sign Up for GTM', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'With Google Tag Manager, you can easily manage and deploy marketing tags and tracking traffic on your website.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-code"></i></div>
			</a>
			<div class="<?php echo esc_attr( $step_class ); ?>">
				<h3><?php esc_html_e( 'Integrate GTM Container ID', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Enter the GTM Container ID in the form to connect the Google Analytics service to your website.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-link"></i></div>
			</div>
		</div>
		<?php if ( empty( $gtag_id ) && empty( $gtm_id ) ) : ?>
			<div class="rb-panel-form" id="rb-gtm-form">
				<div class="rb-panel-input">
					<label class="rb-panel-label" for="rb-gtm-input"><?php echo esc_html__( 'Google Tag Manager Container ID', 'foxiz-core' ); ?>
						<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php
								esc_html_e( 'Formatted as GTM-XXXXXX. You can find your container ID in the Google Tag Manager interface.', 'foxiz-core' ); ?></span></span></label>
					<input class="rb-panel-input-text" type="text" name="simple_gtm_id" id="rb-gtm-input" value="" placeholder="GTM-A1KEAZD"/>
				</div>
				<div class="rb-panel-input">
					<label class="rb-panel-label" for="rb-gtag-input"><?php echo esc_html__( 'or Gtag Measurement ID', 'foxiz-core' ); ?>
						<span class="rb-form-tip"><i class="rbi-dash rbi-dash-info"></i><span class="rb-form-tip-content"><?php
								esc_html_e( 'A Measurement ID is an identifier (e.g., G-12345) for a web data stream. You can find this ID in the Google Analytics interface.', 'foxiz-core' ); ?></span></span></label>
					<input class="rb-panel-input-text" type="text" name="simple_gtag_id" id="rb-gtag-input" value="" placeholder="G-KV4C5NT2Z1"/>
				</div>
				<button type="submit" name="action" class="rb-panel-button is-big-button" id="rb-gtm-submit">
					<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span><?php echo esc_html__( 'Add New Tag', 'foxiz-core' ); ?>
				</button>
			</div>
		<?php else : ?>
			<div class="rb-inline-big-form-wrap">
				<?php if ( ! empty( $gtm_id ) ) : ?>
					<div class="rb-panel-input">
						<label class="rb-panel-label" for="gtm_id"><?php esc_html_e( 'Google Tag Manager Container ID', 'foxiz-core' ); ?></label>
						<input class="rb-panel-input-text" type="text" name="gtm_id" disabled="disabled" value="<?php echo esc_html( $gtm_id ); ?>">
					</div>
				<?php else: ?>
					<div class="rb-panel-input">
						<label class="rb-panel-label" for="gtag_id"><?php esc_html_e( 'Gtag Measurement ID', 'foxiz-core' ); ?></label>
						<input class="rb-panel-input-text" type="text" name="gtag_id" disabled="disabled" value="<?php echo esc_html( $gtag_id ); ?>">
					</div>
				<?php endif; ?>
				<button type="submit" name="action" class="rb-panel-button is-big-button hover-warning" id="rb-gtm-delete">
					<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span><?php echo esc_html__( 'Delete Tag ID', 'foxiz-core' ); ?>
				</button>
			</div>
		<?php endif; ?>
		<div class="rb-response-info is-hidden"></div>
	</div>
</div>