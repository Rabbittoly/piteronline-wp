<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: Функции безопасности
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-01-09 12:03:04
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: all
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<?php
function remove_version() {
  return '';
}
add_filter('the_generator', 'remove_version');

add_filter('xmlrpc_enabled', '__return_false');

function no_wordpress_errors(){
  return 'Что-то пошло не так!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );

remove_action('wp_head', 'wp_generator');

function remove_script_version( $src ){
  return remove_query_arg( 'ver', $src );
}
add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );

add_filter('wp_is_application_passwords_available', '__return_false');

remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
