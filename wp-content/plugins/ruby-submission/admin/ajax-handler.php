<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Admin_Ajax_Handler', false)) {
    class Ruby_Submission_Admin_Ajax_Handler
    {
        private static $instance;

        public static function get_instance()
        {

            if (self::$instance === null) {
                return new self();
            }

            return self::$instance;
        }

        public function __construct()
        {

            add_action('wp_ajax_rbsm_submit_form', [ $this, 'submit_form' ]);
            add_action('wp_ajax_rbsm_get_forms', [ $this, 'get_forms' ]);
            add_action('wp_ajax_rbsm_update_form', [ $this, 'update_form' ]);
            add_action('wp_ajax_rbsm_delete_form', [ $this, 'delete_form' ]);
            add_action('wp_ajax_rbsm_get_authors', [ $this, 'get_authors' ]);
            add_action('wp_ajax_rbsm_admin_get_categories', [ $this, 'admin_get_categories' ]);
            add_action('wp_ajax_rbsm_admin_get_tags', [ $this, 'admin_get_tags' ]);
            add_action('wp_ajax_rbsm_restore_data', [ $this, 'restore_data' ]);
            add_action('wp_ajax_rbsm_get_post_manager', [ $this, 'get_post_manager' ]);
            add_action('wp_ajax_rbsm_update_post_manager', [ $this, 'update_post_manager' ]);
        }

        /**
         * Save the new form into database.
         */
        public function submit_form()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('No data to save.', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Data invalid.', 'ruby-submission'));
                wp_die();
            }

            $title = isset($data['title']) ? sanitize_text_field($data['title']) : '';
            $data  = isset($data['data']) ? wp_json_encode($data['data']) : '';

            if (json_last_error() !== JSON_ERROR_NONE || empty($title) || empty($data)) {
                wp_send_json_error(esc_html__('Title or data is missing.', 'ruby-submission'));
                wp_die();
            }

            if($this->check_title_exist($title)) {
                wp_send_json_error(esc_html__('Title was existed', 'ruby-submission'));
                wp_die();
            }

            $data_before_save_validate = $this->validate_data_before_saving($data);
            if (! $data_before_save_validate['status']) {
                wp_send_json_error($data_before_save_validate['message']);
                wp_die();
            }

            $data_after_sanitize = wp_json_encode($data_before_save_validate['data']);

            $result = $wpdb->insert(
                $table_name,
                [
                    'title' => $title,
                    'data'  => $data_after_sanitize,
                ],
                [
                    '%s',
                    '%s',
                ]
            );

            if ($result) {
                wp_send_json_success(esc_html__('Save successfully!', 'ruby-submission'));
            } else {
                wp_send_json_error(esc_html__('Save to Database failed.', 'ruby-submission'));
            }

            wp_die();
        }

        private function check_title_exist($title)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';
            $query = $wpdb->prepare("SELECT * FROM $table_name WHERE title = %s", $title);
            $results = $wpdb->get_results($query);

            return count($results) > 0;
        }

        /**
         * Get all forms from the database.
         */
        public function get_forms()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            $query_string = "SELECT * FROM $table_name";
            $result       = $wpdb->get_results($wpdb->prepare($query_string));

            if ($result) {
                wp_send_json_success($result);
            } else {
                wp_send_json_error(esc_html__('No records found', 'ruby-submission'));
            }

            wp_die();
        }

        /**
         * Validate data of form settings before save into database.
         */
        private function validate_data_before_saving($data)
        {

            $data_object = json_decode($data, true);
            $new_data    = [];

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Invalid data', 'ruby-submission'),
                ];
            }

            $general_settings = $data_object['general_setting'] ?? null;
            $user_login       = $data_object['user_login'] ?? null;
            $form_fields      = $data_object['form_fields'] ?? null;
            $security_fields  = $data_object['security_fields'] ?? null;
            $email            = $data_object['email'] ?? null;

            if (empty($general_settings) || empty($user_login) || empty($form_fields) || empty($security_fields) || empty($email)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Data is missing some fields!', 'ruby-submission'),
                ];
            }

            // validate general_setting data
            $post_status     = sanitize_text_field($general_settings['post_status'] ?? null);
            $url_direction   = sanitize_text_field($general_settings['url_direction'] ?? null);
            $success_message = sanitize_text_field($general_settings['success_message'] ?? null);
            $error_message   = sanitize_text_field($general_settings['error_message'] ?? null);
            $unique_title    = filter_var($general_settings['unique_title'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $form_layout_type = sanitize_text_field($general_settings['form_layout_type'] ?? null);

            if (is_null($post_status) || is_null($url_direction) || is_null($success_message) || is_null($error_message) || is_null($unique_title) || is_null($form_layout_type)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('General setting data is invalid!', 'ruby-submission'),
                ];
            }

            $new_data['general_setting'] = [
                'post_status'     => $post_status,
                'url_direction'   => $url_direction,
                'success_message' => $success_message,
                'error_message'   => $error_message,
                'unique_title'    => $unique_title,
                'form_layout_type' => $form_layout_type
            ];

            // validate user_login data
            $author_access    = sanitize_text_field($user_login['author_access'] ?? null);
            $assign_author    = sanitize_text_field($user_login['assign_author'] ?? null);
            $assign_author_id = filter_var($user_login['assign_author_id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $login_type       = sanitize_text_field($user_login['login_type']['type'] ?? null);
            $login_message    = sanitize_text_field($user_login['login_type']['login_message'] ?? null);
            $required_login_title   = sanitize_text_field($user_login['login_type']['required_login_title'] ?? null);
            $required_login_title_desc   = sanitize_text_field($user_login['login_type']['required_login_title_desc'] ?? null);

            if (
                is_null($author_access) || is_null($assign_author) || is_null($assign_author_id) || is_null($login_type) || is_null($login_message)
                || is_null($required_login_title) || is_null($required_login_title_desc)
            ) {
                return [
                    'status'  => false,
                    'message' => esc_html__('User login setting data is invalid!', 'ruby-submission'),
                ];
            }

            $new_data['user_login'] = [
                'author_access'    => $author_access,
                'assign_author'    => $assign_author,
                'assign_author_id' => $assign_author_id,
                'login_type'       => [
                    'type'             => $login_type,
                    'login_message'    => $login_message,
                    'required_login_title' => $required_login_title,
                    'required_login_title_desc' => $required_login_title_desc,
                ],
            ];

            // validate form_fields data
            $user_name             = sanitize_text_field($form_fields['user_name'] ?? null);
            $user_email            = sanitize_text_field($form_fields['user_email'] ?? null);
            $post_title            = sanitize_text_field($form_fields['post_title'] ?? null);
            $tagline               = sanitize_text_field($form_fields['tagline'] ?? null);
            $editor_type           = sanitize_text_field($form_fields['editor_type'] ?? null);
            $featured_image_status = sanitize_text_field($form_fields['featured_image']['status'] ?? null);

            $upload_file_size_limit = isset($form_fields['featured_image']['upload_file_size_limit'])
                ? absint($form_fields['featured_image']['upload_file_size_limit'])
                : null;

            $default_featured_image = sanitize_text_field($form_fields['featured_image']['default_featured_image'] ?? null);
            $categories_multi       = filter_var($form_fields['categories']['multiple_categories'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $exclude_categories = isset($form_fields['categories']['exclude_categories'])
                ? array_map('sanitize_text_field', $form_fields['categories']['exclude_categories'])
                : null;

            $exclude_category_ids = isset($form_fields['categories']['exclude_category_ids'])
                ? array_map('sanitize_text_field', $form_fields['categories']['exclude_category_ids'])
                : null;

            $auto_assign_categories = isset($form_fields['categories']['auto_assign_categories'])
                ? array_map('sanitize_text_field', $form_fields['categories']['auto_assign_categories'])
                : null;

            $auto_assign_category_ids = isset($form_fields['categories']['auto_assign_category_ids'])
                ? array_map('sanitize_text_field', $form_fields['categories']['auto_assign_category_ids'])
                : null;

            $tags_multi   = filter_var($form_fields['tags']['multiple_tags'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $tags_add_new = filter_var($form_fields['tags']['allow_add_new_tag'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $exclude_tags = isset($form_fields['tags']['exclude_tags'])
                ? array_map('sanitize_text_field', $form_fields['tags']['exclude_tags'])
                : null;

            $exclude_tag_ids = isset($form_fields['tags']['exclude_tag_ids'])
                ? array_map('sanitize_text_field', $form_fields['tags']['exclude_tag_ids'])
                : null;

            $auto_assign_tags = isset($form_fields['tags']['auto_assign_tags'])
                ? array_map('sanitize_text_field', $form_fields['tags']['auto_assign_tags'])
                : null;

            $auto_assign_tag_ids = isset($form_fields['tags']['auto_assign_tag_ids'])
                ? array_map('sanitize_text_field', $form_fields['tags']['auto_assign_tag_ids'])
                : null;

            $is_valid_custom_field = $this->validate_custom_field($form_fields)['status'];
            $custom_field          = $this->validate_custom_field($form_fields)['data'];

            if (
                is_null($user_name) || is_null($user_email) || is_null($post_title) || is_null($tagline) || is_null($editor_type)
                || is_null($featured_image_status) || is_null($upload_file_size_limit)
                || is_null($default_featured_image) || is_null($categories_multi) || is_null($exclude_categories)
                || is_null($auto_assign_categories) || is_null($tags_multi) || is_null($tags_add_new) || is_null($exclude_tags)
                || is_null($auto_assign_tags) || is_null($exclude_category_ids) || is_null($auto_assign_category_ids) || is_null($exclude_tag_ids)
                || is_null($auto_assign_tag_ids) || ! $is_valid_custom_field
            ) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Form fields setting data is invalid!', 'ruby-submission'),
                ];
            }

            $new_data['form_fields'] = [
                'user_name'      => $user_name,
                'user_email'     => $user_email,
                'post_title'     => $post_title,
                'tagline'        => $tagline,
                'editor_type'    => $editor_type,
                'featured_image' => [
                    'status'                 => $featured_image_status,
                    'upload_file_size_limit' => $upload_file_size_limit,
                    'default_featured_image' => $default_featured_image,
                ],
                'categories'     => [
                    'multiple_categories'      => $categories_multi,
                    'exclude_categories'       => $exclude_categories,
                    'exclude_category_ids'     => $exclude_category_ids,
                    'auto_assign_categories'   => $auto_assign_categories,
                    'auto_assign_category_ids' => $auto_assign_category_ids,
                ],
                'tags'           => [
                    'multiple_tags'       => $tags_multi,
                    'allow_add_new_tag'   => $tags_add_new,
                    'exclude_tags'        => $exclude_tags,
                    'exclude_tag_ids'     => $exclude_tag_ids,
                    'auto_assign_tags'    => $auto_assign_tags,
                    'auto_assign_tag_ids' => $auto_assign_tag_ids,
                ],
                'custom_field'   => $custom_field,
            ];

            // validate security_fields field
            $challenge_status     = filter_var($security_fields['challenge']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $challenge_question   = sanitize_text_field($security_fields['challenge']['question'] ?? null);
            $challenge_response   = sanitize_text_field($security_fields['challenge']['response'] ?? null);
            $recaptcha            = filter_var($security_fields['recaptcha']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $recaptcha_site_key   = sanitize_text_field($security_fields['recaptcha']['recaptcha_site_key'] ?? null);
            $recaptcha_secret_key = sanitize_text_field($security_fields['recaptcha']['recaptcha_secret_key'] ?? null);

            if (is_null($challenge_status) ||
                 is_null($challenge_question) || is_null($challenge_response) || is_null($recaptcha)
                 || is_null($recaptcha_site_key) || is_null($recaptcha_secret_key)
            ) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Security setting data is invalid!', 'ruby-submission'),
                ];
            }

            $new_data['security_fields'] = [
                'challenge' => [
                    'status'   => $challenge_status,
                    'question' => $challenge_question,
                    'response' => $challenge_response,
                ],
                'recaptcha' => [
                    'status'               => $recaptcha,
                    'recaptcha_site_key'   => $recaptcha_site_key,
                    'recaptcha_secret_key' => $recaptcha_secret_key,
                ],
            ];

            // validate emails field
            $admin_email          = sanitize_text_field($email['admin_mail']['email'] ?? null);
            $admin_mail_status    = filter_var($email['admin_mail']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $admin_subject        = sanitize_text_field($email['admin_mail']['subject'] ?? null);
            $admin_title          = sanitize_text_field($email['admin_mail']['title'] ?? null);
            $admin_message        = $this->validate_textarea_content($email['admin_mail']['message'] ?? null);
            $post_submit          = filter_var($email['post_submit_notification']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $post_submit_subject  = sanitize_text_field($email['post_submit_notification']['subject'] ?? null);
            $post_submit_title    = sanitize_text_field($email['post_submit_notification']['title'] ?? null);
            $post_submit_message  = $this->validate_textarea_content($email['post_submit_notification']['message'] ?? null);
            $post_publish         = filter_var($email['post_publish_notification']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $post_publish_subject = sanitize_text_field($email['post_publish_notification']['subject'] ?? null);
            $post_publish_title   = sanitize_text_field($email['post_publish_notification']['title'] ?? null);
            $post_publish_message = $this->validate_textarea_content($email['post_publish_notification']['message'] ?? null);
            $post_trash           = filter_var($email['post_trash_notification']['status'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $post_trash_subject   = sanitize_text_field($email['post_trash_notification']['subject'] ?? null);
            $post_trash_title     = sanitize_text_field($email['post_trash_notification']['title'] ?? null);
            $post_trash_message   = $this->validate_textarea_content($email['post_trash_notification']['message'] ?? null);

            if (
                is_null($admin_email) || is_null($admin_mail_status) || is_null($admin_subject) || is_null($admin_title) || is_null($admin_message)
                || is_null($post_submit) || is_null($post_submit_subject) || is_null($post_submit_title) || is_null($post_submit_message)
                || is_null($post_publish) || is_null($post_publish_subject) || is_null($post_publish_title) || is_null($post_publish_message)
                || is_null($post_trash) || is_null($post_trash_subject) || is_null($post_trash_title) || is_null($post_trash_message)
            ) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Email setting data is invalid!', 'ruby-submission'),
                ];
            }

            $new_data['email'] = [
                'admin_mail'                => [
                    'email'   => $admin_email,
                    'status'  => $admin_mail_status,
                    'subject' => $admin_subject,
                    'title'   => $admin_title,
                    'message' => $admin_message,
                ],
                'post_submit_notification'  => [
                    'status'  => $post_submit,
                    'subject' => $post_submit_subject,
                    'title'   => $post_submit_title,
                    'message' => $post_submit_message,
                ],
                'post_publish_notification' => [
                    'status'  => $post_publish,
                    'subject' => $post_publish_subject,
                    'title'   => $post_publish_title,
                    'message' => $post_publish_message,
                ],
                'post_trash_notification'   => [
                    'status'  => $post_trash,
                    'subject' => $post_trash_subject,
                    'title'   => $post_trash_title,
                    'message' => $post_trash_message,
                ],
            ];

            return [
                'status'  => true,
                'message' => esc_html__('valid data before saving!', 'ruby-submission'),
                'data'    => $new_data,
            ];
        }

        private function validate_textarea_content($content)
        {
            if(current_user_can('unfiltered_html')) {
                return $content;
            }

            return strip_tags($content, '<h1><h2><h3><h4><h5><h6><strong><b><em><i><a><code><p><div><ol><ul><li><br><button><figure><img><iframe><video><audio>');
        }

        /**
         * Validate custom field data in form fields section
         */
        private function validate_custom_field($form_fields)
        {

            $custom_field_array = isset($form_fields['custom_field']) ? (array) $form_fields['custom_field'] : null;
            $data               = [];

            if (is_null($custom_field_array)) {
                return [
                    'status' => false,
                    'data'   => $data,
                ];
            }

            foreach ($custom_field_array as $custom_field) {
                $custom_field_name  = sanitize_text_field($custom_field['custom_field_name'] ?? '');
                $custom_field_label = sanitize_text_field($custom_field['custom_field_label'] ?? '');
                $field_type         = sanitize_text_field($custom_field['field_type'] ?? '');

                if (empty($custom_field_name) || empty($custom_field_label) || empty($field_type)) {
                    return [
                        'status' => false,
                        'data'   => $data,
                    ];
                }

                $data[] = [
                    'custom_field_name'  => $custom_field_name,
                    'custom_field_label' => $custom_field_label,
                    'field_type'         => $field_type,
                ];
            }

            return [
                'status' => true,
                'data'   => $data,
            ];
        }

        /**
         * Update form settings into database.
         */
        public function update_form()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Data invalid', 'ruby-submission'));
                wp_die();
            }

            $id       = isset($data['id']) ? intval(sanitize_text_field($data['id'])) : '';
            $new_data = isset($data['data']) ? wp_json_encode($data['data']) : '';

            if (json_last_error() !== JSON_ERROR_NONE || empty($id) || empty($new_data)) {
                wp_send_json_error(esc_html__('Id or new data is missing.', 'ruby-submission'));
                wp_die();
            }

            $data_before_save_validate = $this->validate_data_before_saving($new_data);
            if (! $data_before_save_validate['status']) {
                wp_send_json_error($data_before_save_validate['message']);
                wp_die();
            }

            $data_after_sanitize = wp_json_encode($data_before_save_validate['data']);

            $result = $wpdb->update(
                $table_name,
                [ 'data' => $data_after_sanitize ],
                [ 'id' => $id ],
                [ '%s' ],
                [ '%d' ]
            );

            if ($result !== false) {
                wp_send_json_success(esc_html__('Save successfully!', 'ruby-submission'));
            } else {
                wp_send_json_error(esc_html__('Save to Database failed.', 'ruby-submission'));
            }

            wp_die();
        }

        /**
         * Delete form from database
         */
        public function delete_form()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Json data invalid', 'ruby-submission'));
                wp_die();
            }

            $id = isset($data['id']) ? intval(sanitize_text_field($data['id'])) : '';
            if (empty($id)) {
                wp_send_json_error(esc_html__('ID is missing', 'ruby-submission'));
                wp_die();
            }

            $wpdb->delete($table_name, [ 'id' => $id ]);

            wp_send_json_success(esc_html__('Removed successfully!', 'ruby-submission'));
            wp_die();
        }

        /**
         * Get all authors
         */
        public function get_authors()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            $authors = get_users([
                'fields'  => [ 'ID', 'display_name' ],
                'who'     => 'author',
                'orderby' => 'display_name',
            ]);

            $result = array_map(function ($author) {

                return [
                    'ID'           => $author->ID,
                    'display_name' => $author->display_name,
                ];
            }, $authors);

            wp_send_json_success($result);
            wp_die();
        }

        /**
         * Get all categories
         */
        public function admin_get_categories()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            $categories = get_terms([
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ]);

            wp_send_json_success($categories);
            wp_die();
        }

        /**
         * Get all tags
         */
        public function admin_get_tags()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            $tags = get_terms([
                'taxonomy'   => 'post_tag',
                'hide_empty' => false,
            ]);

            wp_send_json_success($tags);
            wp_die();
        }

        public function restore_data()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing', 'ruby-submission'));
                wp_die();
            }

            $restore_data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Json data invalid', 'ruby-submission'));
                wp_die();
            }

            $restore_data_sanitize = [];
            foreach ($restore_data as $form_data) {
                $form_data_validate = $this->validate_restore_form_data($form_data);

                if (! $form_data_validate['status']) {
                    wp_send_json_error($form_data_validate['message']);
                    wp_die();
                }

                $restore_data_sanitize[] = $form_data_validate['data'];
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            $values = [];
            foreach ($restore_data_sanitize as $row) {
                $values[] = $wpdb->prepare("(%d, %s, %s)", $row['id'], $row['title'], $row['data']);
            }

            $query = "INSERT IGNORE INTO $table_name (id, title, data) VALUES " . implode(', ', $values);

            $wpdb->query($query);

            wp_send_json_success(esc_html__('restore data success', 'ruby-submission'));
            wp_die();
        }

        private function validate_restore_form_data($form_data)
        {

            $result = [];
            $title  = isset($form_data['title']) ? sanitize_text_field($form_data['title']) : '';
            $id     = isset($form_data['id']) ? intval(sanitize_text_field($form_data['id'])) : '';

            if (empty($id) || empty($title)) {
                return [
                    'status'  => false,
                    'data'    => $result,
                    'message' => esc_html__('ID or Title is missing', 'ruby-submission'),
                ];
            }

            $data = isset($form_data['data']) ? wp_json_encode($form_data['data']) : '';
            if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
                return [
                    'status'  => false,
                    'data'    => $result,
                    'message' => esc_html__('Form data is missing', 'ruby-submission'),
                ];
            }

            $data_validate = $this->validate_data_before_saving($data);
            if (! $data_validate['status']) {
                return [
                    'status'  => false,
                    'data'    => $result,
                    'message' => $data_validate['message'],
                ];
            }

            $result['id']    = $id;
            $result['title'] = $title;
            $result['data']  = wp_json_encode($data_validate['data']);

            return [
                'status'  => true,
                'data'    => $result,
                'message' => 'Valid data',
            ];
        }

        public function get_post_manager()
        {
            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            $post_manager_data = get_option('ruby_submission_post_manager_settings');

            wp_send_json_success($post_manager_data);
            wp_die();
        }

        public function update_post_manager()
        {
            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if(!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You are not allowed to access this feature.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing', 'ruby-submission'));
                wp_die();
            }

            $post_manager_data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Json data invalid', 'ruby-submission'));
                wp_die();
            }

            $validate_post_manager_data = $this->validate_post_manager_data($post_manager_data);
            if(!$validate_post_manager_data['status']) {
                wp_send_json_error(esc_html__($validate_post_manager_data['message'], 'ruby-submission'));
                wp_die();
            }

            update_option('ruby_submission_post_manager_settings', $validate_post_manager_data['result']);

            wp_send_json_success(esc_html__('Post manager settings updated successfully.', 'ruby-submission'));
            wp_die();
        }

        private function validate_post_manager_data($post_manager_data)
        {
            $edit_post_form = isset($post_manager_data['edit_post_form']) ? $post_manager_data['edit_post_form'] : '';
            if(empty($edit_post_form)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $edit_post_url = isset($edit_post_form['edit_post_url']) ? sanitize_text_field($edit_post_form['edit_post_url']) : null;
            if(is_null($edit_post_url)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $edit_login_action_choice = isset($edit_post_form['login_action_choice']) ? sanitize_text_field($edit_post_form['login_action_choice']) : null;
            if(is_null($edit_login_action_choice)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $edit_post_required_login_title = isset($edit_post_form['edit_post_required_login_title']) ? sanitize_text_field($edit_post_form['edit_post_required_login_title']) : null;
            if(is_null($edit_post_required_login_title)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $edit_post_required_login_message = isset($edit_post_form['edit_post_required_login_message']) ? sanitize_text_field($edit_post_form['edit_post_required_login_message']) : null;
            if(is_null($edit_post_required_login_message)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $edit_post_form = [
                'edit_post_url' => $edit_post_url,
                'login_action_choice' => $edit_login_action_choice,
                'edit_post_required_login_title' => $edit_post_required_login_title,
                'edit_post_required_login_message' => $edit_post_required_login_message
            ];

            $user_profile = isset($post_manager_data['user_profile']) ? $post_manager_data['user_profile'] : '';
            if(empty($user_profile)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $allow_delete_post = filter_var($user_profile['allow_delete_post'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $allow_edit_post = filter_var($user_profile['allow_edit_post'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $form_submission_default_id = filter_var($user_profile['form_submission_default_id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if(is_null($allow_delete_post) || is_null($allow_edit_post) || is_null($form_submission_default_id)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $user_posts_login_action_choice = isset($user_profile['login_action_choice']) ? sanitize_text_field($user_profile['login_action_choice']) : null;
            if(is_null($user_posts_login_action_choice)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $user_posts_required_login_title = isset($user_profile['user_posts_required_login_title']) ? sanitize_text_field($user_profile['user_posts_required_login_title']) : null;
            if(is_null($user_posts_required_login_title)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $user_posts_required_login_message = isset($user_profile['user_posts_required_login_message']) ? sanitize_text_field($user_profile['user_posts_required_login_message']) : null;
            if(is_null($user_posts_required_login_message)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $user_profile = [
                'allow_delete_post' => $allow_delete_post,
                'allow_edit_post' => $allow_edit_post,
                'form_submission_default_id' => $form_submission_default_id,
                'login_action_choice' => $user_posts_login_action_choice,
                'user_posts_required_login_title' => $user_posts_required_login_title,
                'user_posts_required_login_message' => $user_posts_required_login_message
            ];

            $custom_login_and_registration = isset($post_manager_data['custom_login_and_registration']) ? $post_manager_data['custom_login_and_registration'] : '';
            if(empty($custom_login_and_registration)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $custom_login_button_label = isset($custom_login_and_registration['custom_login_button_label']) ? sanitize_text_field($custom_login_and_registration['custom_login_button_label']) : null;
            if(is_null($custom_login_button_label)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $custom_login_link = isset($custom_login_and_registration['custom_login_link']) ? sanitize_text_field($custom_login_and_registration['custom_login_link']) : null;
            if(is_null($custom_login_link)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $custom_registration_button_label = isset($custom_login_and_registration['custom_registration_button_label']) ? sanitize_text_field($custom_login_and_registration['custom_registration_button_label']) : null;
            if(is_null($custom_registration_button_label)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $custom_registration_link = isset($custom_login_and_registration['custom_registration_link']) ? sanitize_text_field($custom_login_and_registration['custom_registration_link']) : null;
            if(is_null($custom_registration_link)) {
                return [
                    'status' => false,
                    'message' => esc_html__('Data is invalid', 'ruby-submission')
                ];
            }

            $custom_login_and_registration = [
                'custom_login_button_label' => $custom_login_button_label,
                'custom_login_link' => $custom_login_link,
                'custom_registration_button_label' => $custom_registration_button_label,
                'custom_registration_link' => $custom_registration_link
            ];

            $result = [
                'edit_post_form' => $edit_post_form,
                'user_profile' => $user_profile,
                'custom_login_and_registration' => $custom_login_and_registration
            ];

            return [
                'status' => true,
                'result' => $result
            ];
        }
    }
}

/** load */
Ruby_Submission_Admin_Ajax_Handler::get_instance();
