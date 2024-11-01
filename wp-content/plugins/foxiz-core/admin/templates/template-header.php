<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

$theme_ver = esc_html__( 'Ver: ', 'foxiz-core' ) . ( defined( 'FOXIZ_THEME_VERSION' ) ? FOXIZ_THEME_VERSION : FOXIZ_CORE_VERSION );
$query_str = isset( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '';

?>
<div class="rb-dashboard-header">
	<div class="rb-dashboard-header-inner">
		<div class="rb-dashboard-topbar">
			<div class="rb-dashboard-topbar-left">
				<div class="rb-dashboard-logo">
					<i class="rbi-dash rbi-dash-brand"></i>
					<h2 class="rb-theme-name"><?php echo esc_html( $title ); ?></h2>
				</div>
				<div class="rb-dashboard-meta rb-ver"><?php echo esc_html( $theme_ver ); ?></div>
				<?php if ( ! empty( $is_activated ) ) : ?>
					<h3 class="rb-theme-status is-registered"><?php esc_html_e( 'Registered', 'foxiz-core' ); ?></h3>
				<?php else : ?>
					<h3 class="rb-theme-status is-unregistered"><?php esc_html_e( 'Unregistered', 'foxiz-core' ); ?></h3>
				<?php endif; ?>
			</div>
			<div class="rb-links">
				<a href="https://themeruby.com" target="_blank"><?php esc_html_e( 'Themes', 'foxiz-core' ) ?></a>
				<a href="https:////help.themeruby.com/foxiz/" target="_blank"><?php esc_html_e( 'Documentation', 'foxiz-core' ) ?></a>
				<a href="https://ruby.ticksy.com/" target="_blank"><?php esc_html_e( 'Open a Ticket', 'foxiz-core' ) ?></a>
				<a href="https://foxiz.themeruby.com/whats-new/" class="rb-dashboard-changelog" target="_blank"><i class="rbi-dash rbi-dash-horn"></i><?php esc_html_e( 'What\'s new', 'foxiz-core' ); ?>
				</a>
			</div>
		</div>
		<div class="rbi-dashboard-menu-wrap">
			<div class="rb-dashboard-menu">
				<?php
				if ( ! empty( $menu ) && is_array( $menu ) ) :
					$menu = apply_filters( 'ruby_dashboard_menu', $menu );
					foreach ( $menu as $key => $menu_item ) :
						if ( empty( $key ) ) {
							continue;
						}
						$class_name = strpos( $menu_item['url'], $query_str ) ? 'rb-menu-item active' : 'rb-menu-item';
						if ( empty( $menu_item['sub_items'] ) || ! is_array( $menu_item['sub_items'] ) ) : ?>
							<a class="<?php echo esc_attr( $class_name ); ?>" href="<?php echo esc_url( $menu_item['url'] ); ?>">
								<i class="rbi-dash <?php echo esc_attr( $menu_item['icon'] ); ?>"></i>
								<?php echo esc_html( $menu_item['title'] ); ?></a>
						<?php else: ?>
							<div class="rb-menu-has-child">
								<a class="<?php echo esc_attr( $class_name ); ?>" href="<?php echo esc_url( $menu_item['url'] ); ?>">
									<i class="rbi-dash <?php echo esc_attr( $menu_item['icon'] ); ?>"></i>
									<?php echo esc_html( $menu_item['title'] ); ?></a>
								<div class="rb-submenu-items">
									<?php foreach ( $menu_item['sub_items'] as $sub_item ) :
										$class_name = strpos( $sub_item['url'], $query_str ) ? 'rb-submenu-item active' : 'rb-submenu-item';
										?>
										<a class="<?php echo esc_attr( $class_name ); ?>" href="<?php echo esc_url( $sub_item['url'] ); ?>">
											<i class="rbi-dash <?php echo esc_attr( $sub_item['icon'] ); ?>"></i>
											<?php echo esc_html( $sub_item['title'] ); ?></a>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach;
				endif; ?>
			</div>
			<div class="rb-dashboard-menu-right">
				<?php if ( $expiration && time() < $expiration ) : ?>
					<div class="rb-dashboard-meta"><?php printf(
							esc_html__( 'Supported Until: %s', 'foxiz-core' ),
							date( 'F j, Y', $expiration )
						); ?></div>
				<?php else : ?>
					<div class="rb-dashboard-meta is-expired"><?php esc_html_e( 'Support Status:', 'foxiz-core' ); ?>
						<span><?php echo ( $is_activated ) ? esc_html__( 'Expired', 'foxiz-core' ) : esc_html__( 'Invalid', 'foxiz-core' ); ?></span>
					</div>
				<?php endif; ?>
				<a class="buy-now-btn" target="_blank" rel="nofollow" href="//1.envato.market/MXYjYo" aria-label="buy now"><i class="rbi-dash rbi-dash-bag"></i><?php esc_html_e( 'Buy License', 'foxiz-core' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="wrap"><h2></h2></div><!-- notices -->
