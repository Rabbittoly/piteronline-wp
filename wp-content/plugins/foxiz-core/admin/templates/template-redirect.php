<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;
?>
<div class="rb-dashboard-wrap">
	<div class="rb-dashboard-section rb-dashboard-fw">
		<h1 class="rb-dashboard-headline"><i class="rbi-dash rbi-dash-unlock"></i><?php echo esc_html__( 'Foxiz Activation Required', 'foxiz-core' ); ?></h1>
		<p class="sub-heading"><?php echo esc_html__( 'Please activate your Foxiz theme to gain full access to the Import Demos and Theme Options for a complete site-building experience.', 'foxiz-core' ); ?></p>
		<a class="rb-redirect-button rb-panel-button" href="<?php echo admin_url( 'admin.php/?page=foxiz-admin' ); ?>"><?php esc_html_e('Go to Activate Page', 'foxiz-core'); ?></a>
</div>