<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: Яндекс.Карты API
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-21 03:34:40
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
function enqueue_yandex_maps_script() {
    // Подключаем Яндекс.Карты API на фронтенде
    if (!is_admin()) {
        wp_enqueue_script(
            'yandex-maps',
            'https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=e55f2e93-4a3a-44c8-a5c0-c796ce0b3d91',
            null,
            null,
            true
        );

        // Подключаем кастомный скрипт для работы с картой
        wp_enqueue_script(
            'custom-yandex-autocomplete',
            get_stylesheet_directory_uri() . '/js/custom-yandex-autocomplete.js',
            array('yandex-maps'),
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_yandex_maps_script');
