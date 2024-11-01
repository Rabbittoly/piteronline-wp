<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: recaptcha
* @type: PHP
* @status: draft
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-08 20:42:56
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
function add_recaptcha_to_registration() {
    ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <p>
        <div class="g-recaptcha" data-sitekey="6Len8VApAAAAAJE0EOYijj7reXzqnhVHB-aA_zNF"></div>
    </p>
    <?php
}
add_action('register_form', 'add_recaptcha_to_registration');
function verify_recaptcha_on_registration($errors, $sanitized_user_login, $user_email) {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret_key = '6Len8VApAAAAAGJCJrF5vWeydaRXi61HO7dkTzJ0';
        $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response=" . $_POST['g-recaptcha-response']);
        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);
        if (true !== $result['success']) {
            $errors->add('recaptcha_error', __('Пожалуйста, подтвердите, что вы не робот.'));
        }
    } else {
        $errors->add('recaptcha_blank', __('Пожалуйста, подтвердите ReCaptcha.'));
    }
    return $errors;
}
add_filter('registration_errors', 'verify_recaptcha_on_registration', 10, 3);
