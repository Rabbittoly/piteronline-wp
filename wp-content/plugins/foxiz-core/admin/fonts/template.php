<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;
?>
<div class="rb-dashboard-wrap rb-dashboard-adobe-fonts">
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content">
			<div class="rb-intro-content-inner">
				<h2 class="rb-dashboard-title">
					<?php esc_html_e( 'Adobe Fonts', 'foxiz-core' ); ?>
				</h2>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'Foxiz supports Adobe Fonts (formerly Typekit), allowing you to easily integrate custom fonts into your website.', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-big-icon"><i class="rbi-dash rbi-dash-adobe"></i></div>
		</div>
		<div class="rb-dashboard-steps">
			<?php $step_class = empty( $project_id ) ? 'rb-dashboard-step' : 'rb-dashboard-step is-checked'; ?>
			<a class="<?php echo esc_attr( $step_class ); ?>" href="//fonts.adobe.com/my_fonts?browse_mode=all#web_projects-section" target="_blank" rel="nofollow">
				<h3><?php esc_html_e( 'Create a Font Project', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'You can create an get the project IDs from your Adobe(Typekit) Account.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-archive"></i></div>
			</a>
			<div class="rb-dashboard-step">
				<h3><?php esc_html_e( 'Integrate Project ID', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Enter the font project ID in the form below to integrate custom fonts into your website.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-edit"></i></div>
			</div>
			<a class="rb-dashboard-step" href="<?php echo admin_url( 'admin.php?page=ruby-options&tab=84' ); ?>">
				<h3><?php esc_html_e( 'Personalize Your Fonts', 'foxiz-core' ); ?></h3>
				<p class="rb-dashboard-desc"><?php esc_html_e( 'Find and assign your Adobe fonts to elements through the Theme Options panel.', 'foxiz-core' ); ?></p>
				<div class="rb-step-icon"><i class="rbi-dash rbi-dash-setting"></i></div>
			</a>
		</div>
		<?php if ( ! empty( $project_id ) ) : ?>
			<div class="font-details-wrap is-boxed">
				<div class="font-details">
					<?php if ( empty( $fonts ) ) : ?>
						<div class="rb-section-header">
							<h2>
								<i class="rbi-dash rbi-dash-font"></i><?php esc_html_e( 'Font Details', 'foxiz-core' ); ?>
							</h2>
						</div>
						<p class="rb-font-notice"><?php esc_html_e( 'No webfont found in your project.', 'foxiz-core' ); ?></p>
					<?php else : ?>
						<div class="rb-font-item is-top">
							<p class="rb-font-detail">
								<i class="rbi-dash rbi-dash-font"></i><?php esc_html_e( 'Font Name', 'foxiz-core' ); ?>
							</p>
							<p class="rb-family-detail"><?php esc_html_e( 'Font Family', 'foxiz-core' ); ?></p>
							<p class="rb-weight-detail"><?php esc_html_e( 'Weight & Style', 'foxiz-core' ); ?></p>
						</div>
						<?php foreach ( $fonts as $font ) : ?>
							<div class="rb-font-item">
								<p class="rb-font-detail"><?php echo esc_html( $font['family'] ); ?></p>
								<p class="rb-family-detail"><?php echo esc_html( $font['backup'] ); ?></p>
								<p class="rb-weight-detail"><?php echo esc_html( implode( ',', $font['variations'] ) ); ?></p>
							</div>
						<?php endforeach;
					endif; ?>
				</div>
				<div class="font-details-footer">
					<h4>
						<i class="rbi-dash rbi-dash-trash"></i><?php esc_html_e( 'Delete Font Project?', 'foxiz-core' ); ?>
					</h4>
					<button type="submit" class="rb-panel-button is-outlined hover-warning" id="delete-project-id"><?php echo esc_attr( $delete ); ?></button>
				</div>
			</div>
		<?php endif; ?>
		<form class="rb-adobe-font" name="rb-adobe-font" method="post" action="">
			<?php if ( ! empty( $project_id ) ) : ?>
				<div class="rb-inline-big-form-wrap">
					<div class="rb-panel-input">
						<label class="rb-panel-label" for="rb-project-id"><?php esc_html_e( 'Project ID', 'foxiz-core' ); ?></label>
						<input class="rb-panel-input-text" type="text" name="rb_fonts_project_id" placeholder="<?php esc_html_e( 'Your project ID', 'foxiz-core' ); ?>" id="rb-project-id" readonly value="<?php echo esc_attr( $project_id ); ?>">
					</div>
					<a href="#" id="rb-edit-project-id" class="rb-panel-button is-big-button"><?php esc_html_e( 'Edit Project ID', 'foxiz-core' ); ?></a>
					<button type="submit" name="action" class="rb-panel-button is-hidden is-big-button" id="submit-project-id" value="update">
						<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span><?php echo esc_attr( $button ); ?>
					</button>
				</div>
			<?php else : ?>
				<div class="rb-inline-big-form-wrap">
					<div class="rb-panel-input">
						<label class="rb-panel-label" for="rb-project-id"><?php esc_html_e( 'Project ID', 'foxiz-core' ); ?></label>
						<input class="rb-panel-input-text" type="text" name="rb_fonts_project_id" id="rb-project-id" placeholder="<?php esc_html_e( 'Add your project ID here...', 'foxiz-core' ); ?>" value="">
					</div>
					<button type="submit" name="action" class="rb-panel-button is-big-button" id="submit-project-id" value="update">
						<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span><?php echo esc_html__( 'Add New Project', 'foxiz-core' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</form>
		<div class="rb-response-info is-hidden"></div>
	</div>
</div>