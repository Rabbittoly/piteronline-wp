<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_accordion_item' ) ) {
	function foxiz_accordion_item( $attributes, $content ) {

		if ( empty( $content ) ) {
			return false;
		}

		if ( empty( $attributes['heading'] ) ) {
			$attributes['heading'] = '';
		}

		if ( empty( $attributes['headingTag'] ) ) {
			$attributes['headingTag'] = 'h3';
		}

		$title_classes = 'accordion-title gb-heading';
		if ( empty( $attributes['tocAdded'] ) ) {
			$title_classes .= ' none-toc';
		}

		ob_start();
		?>
		<div <?php echo get_block_wrapper_attributes( [ 'class' => 'gb-accordion-item' ] ); ?>>
			<div class="accordion-item-header">
				<?php echo '<' . $attributes['headingTag'] . ' class="' . $title_classes . '">' . $attributes['heading'] . '</' . $attributes['headingTag'] . '>'; ?>
				<i class="rbi rbi-angle-down gb-heading"></i>
			</div>
			<div class="accordion-item-content rb-text">
				<?php echo $content; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}