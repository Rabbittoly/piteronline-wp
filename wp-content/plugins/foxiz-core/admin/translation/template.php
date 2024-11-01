<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

?>
<div class="rb-dashboard-wrap rb-dashboard-translation">
	<div class="rb-dashboard-section rb-dashboard-fw">
		<div class="rb-intro-content yes-button">
			<div class="rb-intro-content-inner">
				<h2 class="rb-dashboard-title">
					<i class="rbi-dash rbi-dash-translate" aria-hidden="true"></i><?php esc_html_e( 'Quick Translation', 'foxiz-core' ); ?>
				</h2>
				<p class="rb-dashboard-tagline"><?php esc_html_e( 'Allows you to quickly translate front-end strings to your language.', 'foxiz-core' ); ?></p>
			</div>
			<div class="rb-intro-buttons">
				<button type="submit" class="rb-panel-button is-outlined" name="fetch-translation" id="rb-fetch-translation">
					<i class="rbi-dash rbi-dash-cloud"></i><?php esc_html_e( 'Update Strings', 'foxiz-core' ) ?>
				</button>
			</div>
		</div>
		<div class="rb-dashboard-tips">
			<p><i class="dashicons dashicons-info"></i><?php echo esc_html__( 'PLEASE NOTE: Please keep "%s" as it is in the translated text if the string contains this variable. Incorrect formatting can cause fatal errors in PHP code and prevent the site from loading correctly.', 'foxiz-core' ); ?></p>
		</div>
		<div class="rb-translation">
			<?php if ( empty( $data ) || ! is_array( $data ) || ! count( $data ) ) : ?>
				<h3 class="rb-notice"><?php esc_html_e( 'POT files not found.', 'foxiz-core' ); ?></h3>
			<?php else : ?>
			<div id="rb-translation-form">
				<div class="rb-translation-wrap">
					<div class="rb-translation-header">
						<h3 class="source-label">
							<i class="dashicons dashicons-admin-site"></i>
							<?php esc_html_e( 'Source String - English', 'foxiz-core' ); ?></h3>
						<h3 class="translation-label">
							<i class="rbi-dash rbi-dash-translate"></i>
							<?php esc_html_e( 'Translation', 'foxiz-core' ); ?>
						</h3>
					</div>
					<div class="rb-translation-form rb-search-area">
						<?php foreach ( $data as $item ) : ?>
							<div class="item rb-search-item">
								<label for="<?php esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['str'] ); ?></label>
								<input class="yes-searchable" placeholder="<?php echo strlen( $item['str'] ) > 50 ? substr( $item['str'], 0, 50 ) . '...' : $item['str']; ?>" type="text" name="<?php echo $item['id']; ?>" value="<?php echo ( ! empty( $item['translated'] ) ) ? esc_html( $item['translated'] ) : ''; ?>">
							</div>
						<?php endforeach; ?>
					</div>
					<div class="rb-form-sticky">
						<div class="rb-translation-search">
							<div class="scs-form">
								<input id="rb-search-form" type="text" placeholder="<?php esc_html_e( 'Search source string...', 'foxiz-core' ); ?>">
								<i class="rbi-dash rbi-dash-search"></i>
							</div>
						</div>
						<div class="rb-translation-submit">
							<p class="rb-info"></p>
							<span class="rb-loading is-hidden"><i class="rbi-dash rbi-dash-load"></i></span>
							<input type="submit" class="rb-panel-button is-big-button" value="<?php esc_html_e( 'Save Changes', 'foxiz-core' ) ?>" name="update-translation" id="rb-update-translation">
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>