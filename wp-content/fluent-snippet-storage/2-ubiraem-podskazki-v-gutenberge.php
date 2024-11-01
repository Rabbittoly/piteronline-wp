<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: Убираем подсказки в гутенберге
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-20 09:39:29
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: all
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<?php
function custom_admin_styles() {
    echo '<style>
        .components-tooltip {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'custom_admin_styles');

function my_custom_gutenberg_styles() {
    wp_enqueue_style( 'my-custom-gutenberg-styles', get_theme_file_uri( 'style.css' ), false, '1.0', 'all' );
}
add_action( 'enqueue_block_editor_assets', 'my_custom_gutenberg_styles' );