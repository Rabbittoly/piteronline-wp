<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

?>
<div class="rb-dashboard-wrap rb-dashboard-server-info">
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content">
			<div class="rb-intro-content-inner">
				<h2 class="rb-dashboard-title">
					<?php esc_html_e( 'System Information', 'foxiz-core' ); ?>
				</h2>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'Foxiz can operate on nearly all servers. However, we recommend following the server settings below if you encounter any red values', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-big-icon"><i class="rbi-dash rbi-dash-server"></i></div>
		</div>
		<div class="rb-section-header">
			<h2><i class="rbi-dash rbi-dash-phpinfo"></i><?php esc_html_e( 'PHP Information', 'foxiz-core' ); ?></h2>
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

		<div class="rb-section-header">
			<h2><i class="rbi-dash rbi-dash-wordpress"></i><?php esc_html_e( 'WordPress Info', 'foxiz-core' ); ?></h2>
		</div>
		<div class="rb-info-content">
			<?php if ( ! empty( $wp_info ) && is_array( $wp_info ) ) :
				foreach ( $wp_info as $info => $val ) :
					$class_name = 'info-el';
					if ( isset( $val['passed'] ) && ! $val['passed'] ) {
						$class_name .= ' is-warning';
					} ?>
					<div class="<?php echo esc_attr( $class_name ); ?>">
						<div class="info-content">
							<span class="info-label"><?php echo esc_attr( $val['title'] ) ?></span>
							<span class="info-value"><?php echo foxiz_strip_tags( $val['value'] ) ?></span>
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