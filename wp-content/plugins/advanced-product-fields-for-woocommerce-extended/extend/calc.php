<?php

if (!defined('ABSPATH')) die;

add_filter( 'wapf/field_types', function( $fields ) {

    $fields['calc'] = [
        'id'            => 'calc',
        'title'         => __('Calculation','sw-wapf'),
        'description'   => __('Show a calculation result or optionally adjust the product price.', 'sw-wapf'),
        'type'          => 'field',
        'subtype'       => __('Advanced', 'sw-wapf'),
        'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" stroke-width="2" stroke="#828282" viewBox="0 0 66 66"><path d="m20.7 23-1.5 1.7.1 1.2h4c-.4 4-1.5 8.1-2.6 14.5a36.6 36.6 0 0 1-2.6 10.8c-.4.8-1 2-1.8 2L13 50.9c-.3-.2-.2-.2-1 0-.8.7-1.5 1.7-1.5 2.6.7 1.2 1.5 2.2 3 2.2C15 55.7 17 55 19 53c2.8-2.7 5-6.4 6.7-14.4C27 34.2 28 30 28.2 26l4.8-1.1 1-2h-5.3c1.4-8.7 2.5-10 3.8-10s2.7.7 3.1 2c.4.5 1 1 1.3.1.7-.4 1.5-1.4 1.6-2.4 0-1-1.2-2.3-3.3-2.3-2 0-5 1.3-7.4 3.8-2.2 2.3-2.6 5.8-4.1 8.9h-3Zm15.4 5.9c1.5-2 2.4-2.7 2.8-2.7 1 0 .9.5 1.7 4l1.4 3.7c-2.7 4.1-4.7 6.8-5.9 6.8-.4 0-.8-.5-1-.8-.3-.3.2-.5-1-.5-1 0-2.2 1.2-2.2 2.7 0 1.5 1 2.6 2.4 2.6 2.4 0 4.5-1.7 8.4-8.6l1.1 3.8c1 3.5 2.2 4.8 4.1 4.8 1.3 0 3.7-1.5 6.3-5.6l-1-1.3c-1.7 1.9-2.7 2.8-3.3 2.8-.7 0-1.3-1-2.1-3.6l-1.7-5.5c1-1.5 2-2.7 2.8-3.7 1-1.1 1.9-1.6 2.4-1.6.4 0 .8 1 1.2.4.2.4.4.6.8.6.8 0 2.2-1.1 2.2-2.6 0-1.3-.8-2.5-2.2-2.5-2.2 0-4.2 2-7.2 7.4l-1.4-2.3c-1-3.4-1.8-5-3-5-2 0-4.4 2-6.8 5.5l1.2 1.2Z"/></svg>'
    ];

    return $fields;

});

add_filter( 'wapf/field_options', function( $options ) {

    $options['calc'] = [
        [
            'type'          => 'select',
            'id'            => 'calc_type',
            'label'         => __('Calculation type','sw-wapf'),
            'options'       => [
                'default'   => __('Informational calculation','sw-wapf'),
                'cost'      => __('Cost calculation','sw-wapf')
            ],
            'default'       => 'default',
            'description'   => __('Select the type of calculation.','sw-wapf'),
            'note'          => __('Is the calculation being shown for informational purpose only, or should it also adjust the product price?', 'sw-wapf')
        ],
        [
            'type'          => 'select',
            'id'            => 'result_format',
            'label'         => __('Result format','sw-wapf'),
            'show_if'       => "calc_type | eq 'default'",
            'options'       => [
                'none'      => __('Don\'t apply formatting','sw-wapf'),
                ''          => __('Format as number', 'sw-wapf'),
            ],
        ],
        [
            'type'          => 'formula-builder',
            'id'            => 'formula',
            'label'         => __('Formula','sw-wapf'),
            'description'   => __('Build your formula.','sw-wapf'),
        ], [
            'type'          => 'text',
            'id'            => 'result_text',
            'label'         => __('Result text','sw-wapf'),
            'description'   => __('The text displayed before & after the result.','sw-wapf'),
            'note'          => __('Enter the text to display before/after the calculation result. Use <i>{{result}}</i> to refer to the result. Leave blank if you only want to show the result.', 'sw-wapf')
        ],
    ];

    return $options;

});

add_filter( 'wapf/cart/cart_item_field', function($cart_item_field, $field, $clone_idx) {

    if( $field->type === 'calc' && isset( $field->options['calc_type'] ) && $field->options['calc_type'] === 'cost' ) {
        $cart_item_field['hide_price_hint'] = true;
        $cart_item_field['calc_type'] = 'cost';
        $cart_item_field['calc_text'] =  empty( $field->options['result_text'] ) ? '{result}' : $field->options['result_text'];
        $cart_item_field['values'][0]['price_type'] = 'fx';
        $cart_item_field['values'][0]['price'] = empty( $field->options['formula'] ) ? '0' : $field->options['formula'];
    }

    return $cart_item_field;

}, 10, 3);

add_filter( 'wapf/cart/item_values_label', function( $str, $cartitem_field, $cart_item, $simple_mode ) {

    // Pricing fields don't need a pricing hint but a pricing label, so we use this code to recalc price and add it as label.
    if( $cartitem_field['type'] === 'calc' && isset( $cartitem_field['calc_type'] ) && $cartitem_field['calc_type'] === 'cost' ) {

        if( ! empty( $cartitem_field['values'] ) ) {
            $amount = apply_filters('wapf/html/pricing_hint/amount', $cartitem_field['values'][0]['calc_price'], $cart_item['data'], 'fx', 'cart' );
            $pricing_hint = \SW_WAPF_PRO\Includes\Classes\Helper::format_price( \SW_WAPF_PRO\Includes\Classes\Helper::adjust_addon_price($cart_item['data'],empty( $amount ) ? 0 : $amount, 'fx', 'cart' ) );
            return str_replace( '{result}', $pricing_hint, $cartitem_field['calc_text'] );
        }

    }

    return $str;

}, 10, 4 );
