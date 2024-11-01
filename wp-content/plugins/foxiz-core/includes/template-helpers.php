<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_breadcrumb' ) ) {
	function foxiz_get_breadcrumb( $classes = '', $check_setting = true ) {

		if ( $check_setting && ! foxiz_get_option( 'breadcrumb' ) ) {
			return false;
		}

		ob_start();
		$class_name = 'breadcrumb-wrap';
		if ( foxiz_get_option( 'breadcrumb_style' ) ) {
			$class_name .= ' breadcrumb-line-wrap';
		}

		if ( function_exists( 'bcn_display' ) ) :
			$class_name .= ' breadcrumb-navxt';
			if ( ! empty( $classes ) ) {
				$class_name .= ' ' . $classes;
			} ?>
			<aside class="<?php echo esc_attr( $class_name ); ?>">
				<div class="breadcrumb-inner" vocab="<?php echo foxiz_protocol(); ?>://schema.org/" typeof="BreadcrumbList"><?php bcn_display(); ?></div>
			</aside>
		<?php
		else :
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				$class_name .= ' breadcrumb-yoast';
				if ( ! empty( $classes ) ) {
					$class_name .= ' ' . $classes;
				}
				yoast_breadcrumb( '<aside class="' . esc_attr( $class_name ) . '"><div class="breadcrumb-inner">', '</div></aside>' );
			} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
				$class_name .= ' rank-math-breadcrumb';
				if ( ! empty( $classes ) ) {
					$class_name .= ' ' . $classes;
				}
				rank_math_the_breadcrumbs( [
					'wrap_before' => '<nav aria-label="breadcrumbs" class="' . esc_attr( $class_name ) . '"><p class="breadcrumb-inner">',
					'wrap_after'  => '</p></nav>',
				] );
			}

		endif;

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_get_wp_errors' ) ) {
	function foxiz_get_wp_errors( $errors ) {

		if ( is_wp_error( $errors ) && $errors->has_errors() ) {
			$error_codes = $errors->get_error_codes();

			$output = '<div class="rb-wp-errors">';
			foreach ( $error_codes as $code ) {
				$messages = $errors->get_error_messages( $code );
				foreach ( $messages as $message ) {
					$output .= '<div class="rb-mgs">' . $message . '</div>';
				}
			}
			$output .= '</div>';

			return $output;
		} else {
			return false;
		}
	}
}