<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_quick_links' ) ) {
	function foxiz_get_quick_links( $settings = [] ) {

		$settings = wp_parse_args( $settings, [
			'uuid'         => '',
			'overflow'     => '',
			'name'         => 'quick-links',
			'header'       => '',
			'quick_links'  => [],
			'layout'       => '1',
			'hover_effect' => 'underline',
			'source'       => '',
		] );

		$settings['classes'] = 'block-qlinks qlayout-' . $settings['layout'];

		if ( '3' === (string) $settings['layout'] ) {
			$settings['classes'] .= ' qlayout-1';
			$settings['classes'] .= ' effect-' . $settings['hover_effect'];
		} elseif ( '1' === (string) $settings['layout'] ) {
			$settings['classes'] .= ' effect-' . $settings['hover_effect'];
		}

		switch ( $settings['overflow'] ) {
			case '3' :
				$settings['classes'] .= ' yes-nowrap qlinks-scroll';
				break;
			case '2' :
				$settings['classes'] .= ' yes-wrap';
				break;
			default :
				$settings['classes'] .= ' res-nowrap qlinks-scroll';
		}
		ob_start();
		foxiz_block_open_tag( $settings );
		?>
		<ul class="qlinks-inner">
			<?php if ( ! empty( $settings['header'] ) ) : ?>
				<li class="qlink qlinks-heading">
					<div class="qlink-label"><?php foxiz_render_inline_html( $settings['header'] ); ?></div>
				</li>
			<?php endif;

			switch ( $settings['source'] ) {
				case 'tax' :
					foxiz_quick_items_from_tax( $settings );
					break;
				case 'both' :
					foxiz_quick_items_from_input( $settings );
					foxiz_quick_items_from_tax( $settings );
					break;
				default :
					foxiz_quick_items_from_input( $settings );
			}
			?></ul>
		<?php
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_quick_items_from_input' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_quick_items_from_input( $settings ) {

		if ( is_array( $settings['quick_links'] ) && count( $settings['quick_links'] ) ) : ?>
			<?php foreach ( $settings['quick_links'] as $item ) : ?>
				<li class="qlink h5"><?php echo foxiz_render_elementor_link( $item['url'], $item['title'] ); ?></li>
			<?php endforeach; ?>
		<?php endif;
	}
}

if ( ! function_exists( 'foxiz_quick_items_from_tax' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_quick_items_from_tax( $settings ) {

		if ( empty( $settings['source_tax'] ) ) {
			return;
		}

		$number = ! empty( $settings['total'] ) ? absint( $settings['total'] ) : 10;
		$tax    = array_map( 'trim', explode( ',', $settings['source_tax'] ) );

		$terms = get_terms( [
			'taxonomy'   => $tax,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'hide_empty' => true,
			'number'     => $number,
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {
			echo '<li class="qlink h5"><a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a></li>';
		}
	}
}