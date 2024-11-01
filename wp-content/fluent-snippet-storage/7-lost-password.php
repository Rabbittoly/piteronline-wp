<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: lost-password
* @type: PHP
* @status: draft
* @created_by: 
* @created_at: 
* @updated_at: 2024-02-01 10:01:02
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: all
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<?php
function custom_lost_password_form() {
    if (is_page('lost-password')) {
        wc_get_template('myaccount/form-lost-password.php');
    }
}
add_action('wp', 'custom_lost_password_form');