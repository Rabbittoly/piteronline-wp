<?php

if (!defined('ABSPATH')) die;

use \SW_WAPF_PRO\Includes\Classes\Field_Groups;
use \SW_WAPF_PRO\Includes\Classes\Enumerable;

add_filter('wapf/field_options','wapfe_add_weight_options');

function wapfe_add_weight_options($opts) {

	$weight_unit = get_option('woocommerce_weight_unit');
	$add_weight_options_to = ['select','checkboxes','radio','image-swatch','image-swatch-qty','multi-image-swatch','color-swatch','multi-color-swatch','text-swatch','multi-text-swatch'];

	foreach ($opts as $key => &$option) {
		if(in_array($key,$add_weight_options_to)) { // choice fields
			foreach ($option as &$o ) {
				if ( ! empty( $o['id'] ) && $o['id'] === 'options' ) {

                    $input = [
                        'title' => sprintf( __( 'Weight (%s)', 'swp-wapf' ), $weight_unit ),
                        'type'  => 'text',
                        'key'   => 'weight'
                    ];

                    if( ! empty( $o['inputs'] ) )
                        $o['inputs'][] = $input;
                    else $o['inputs'] = [ $input ];

					break;
				}
			}
		} else { // other fields

			if(in_array($key,[ 'p', 'img', 'section', 'sectionend', 'calc' ])) continue;

			$option[] = [
				'type'                  => 'text',
				'id'                    => 'weight',
				'label'                 => sprintf( __( 'Extra weight (%s)', 'swp-wapf' ), $weight_unit ),
				'description'           => __('Increase product weight when this field is used.', 'sw-wapf')
			];

		}
	}

	return $opts;

}

add_filter('wapf/cart/cart_item_field', 'wapfe_should_calculate_weight_of_cart_item_field',10, 3);
function wapfe_should_calculate_weight_of_cart_item_field($cart_item_field, \SW_WAPF_PRO\Includes\Models\Field $field, $clone_idx) {
	$cart_item_field['calc_weight'] =
		isset($field->options['weight']) ||
		( !empty($field->options['choices']) && Enumerable::from($field->options['choices'])->any(function($x){ return !empty($x['options']['weight']); }) );
	return $cart_item_field;
}

add_action('woocommerce_before_calculate_totals', 'wapfe_change_cart_item_weight',10,1);
function wapfe_change_cart_item_weight($cart_obj) {

	foreach( $cart_obj->get_cart() as $key => $cart_item ) {

		if(empty($cart_item['wapf'])) continue;

		$should_calculate = Enumerable::from($cart_item['wapf'])->any(function($x){ return isset($x['calc_weight']) && $x['calc_weight'];});

		if($should_calculate) {

			$additional_weight = 0;

			$field_groups = Field_Groups::get_by_ids($cart_item['wapf_field_groups']);
			$fields = Enumerable::from($field_groups)->merge(function($x){return $x->fields; })->toArray();
			$quantity = isset($cart_item['quantity']) ? $cart_item['quantity'] : 1;

			foreach ($cart_item['wapf'] as $cart_field) {

				if(empty($cart_field['values'])) continue;
				if(!isset($cart_field['calc_weight']) || $cart_field['calc_weight'] === false) continue;

				$field = Enumerable::from($fields)->firstOrDefault(function($x) use($cart_field) { return $x->id === $cart_field['id'];});
				if(!$field) continue;
				foreach ($cart_field['values'] as $value) {

					// The "raw" value for a true-false field will be empty (''), so we can check it here:
					if(!isset($cart_field['raw']) || $cart_field['raw'] === '') continue;

					$v = isset($value['slug']) ? $value['label'] : $cart_field['raw'];

					$weight_formula = 0;

					// It's a choice field
					if(isset($value['slug'])) {
						$choice = Enumerable::from($field->options['choices'])->firstOrDefault(function($x) use($value) { return $x['slug'] === $value['slug']; });
						if($choice && isset($choice['options']['weight']))
							$weight_formula = $choice['options']['weight'];
					} else { // It's a normal field
						if(isset($field->options['weight']))
							$weight_formula = $field->options['weight'];
					}

					$weight_formula = apply_filters(
						'wapf/field_weight',
						str_replace( ['[qty]','[x]'], [$quantity,$v], $weight_formula ),
						[
							'field'     => $field,
							'value'     => $v,
							'cart_item' => $cart_item
						]
					);

					$additional_weight += floatval($weight_formula);

				}

			}

			$product = $cart_item['data'];
			if(!$product->get_virtual() ) {
				$new_weight = $additional_weight + floatval($product->get_weight());
				$product->set_weight($new_weight > 0 ? $new_weight : 0);
			}

		}

	}

}