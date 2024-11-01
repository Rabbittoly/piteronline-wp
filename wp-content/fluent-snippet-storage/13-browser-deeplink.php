<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: browser-deeplink
* @type: PHP
* @status: draft
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-20 12:01:19
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
function add_deeplink_script() {
    // Подключаем библиотеку browser-deeplink через CDN
    wp_enqueue_script('browser-deeplink', 'https://cdn.jsdelivr.net/npm/browser-deeplink@1.0.1/dist/deeplink.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'add_deeplink_script');
