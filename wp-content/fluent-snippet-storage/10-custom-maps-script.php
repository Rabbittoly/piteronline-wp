<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: custom-maps-script
* @type: PHP
* @status: draft
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-21 01:58:58
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
function enqueue_custom_maps_script() {
    // Проверка, чтобы скрипт подключался только на фронтенде
    if ( !is_admin() ) {
        wp_enqueue_script(
            'custom-maps-script',
            get_stylesheet_directory_uri() . '/js/custom-maps.js', // Изменен путь на foxiz-child
            array('jquery'),
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_maps_script');