<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Client_Ajax_Handler', false)) {
    class Ruby_Submission_Client_Ajax_Handler
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

            add_action('wp_ajax_rbsm_submit_post', [ $this, 'create_post' ]);
            add_action('wp_ajax_nopriv_rbsm_submit_post', [ $this, 'create_post' ]);
            add_action('wp_ajax_rbsm_update_post', [ $this, 'update_post' ]);
            add_action('wp_ajax_rbsm_get_form_by_id', [ $this, 'get_form_by_id' ]);
            add_action('wp_ajax_nopriv_rbsm_get_form_by_id', [ $this, 'get_form_by_id' ]);
            add_action('wp_ajax_rbsm_get_user_posts', [ $this, 'get_user_posts' ]);
            add_action('wp_ajax_rbsm_trash_post', [ $this, 'trash_post' ]);
            add_action('publish_post', [ $this, 'try_notify_on_post_publish' ], 10, 2);
        }

        public function trash_post()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if (! is_user_logged_in()) {
                wp_send_json_error(esc_html__('You need to log in before do this action.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing.', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Invalid json data.', 'ruby-submission'));
                wp_die();
            }

            $post_id = isset($data['postId']) ? intval($data['postId']) : '';
            $title   = isset($data['title']) ? sanitize_text_field(($data['title'])) : '';

            if (empty($post_id)) {
                wp_send_json_error(esc_html__('Post ID is missing.', 'ruby-submission'));
                wp_die();
            }

            // Check if the current user can delete the post
            if (! current_user_can('delete_post', $post_id)) {
                wp_send_json_error(esc_html__('Sorry, you are not allowed to delete this post.', 'ruby-submission'));
                wp_die();
            }

            if (get_post($post_id)) {
                $this->try_notify_trash_email($post_id, $title);

                wp_trash_post($post_id);
                wp_send_json_success(sprintf(esc_html__('The post with post ID: %d has been deleted.', 'ruby-submission'), $post_id));
            } else {
                wp_send_json_error(sprintf(esc_html__('Post ID %d does not exist.', 'ruby-submission'), $post_id));
            }

            wp_die();
        }

        /**
         * @param $post_id
         * @param $title
         */
        private function try_notify_trash_email($post_id, $title)
        {

            $post_link     = get_permalink($post_id);
            $form_settings = $this->get_form_settings_by_post_id($post_id);

            if (! $form_settings['status']) {
                return;
            }

            $form_settings_result = json_decode($form_settings['data']->data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return;
            }

            $allow_notify_trash_email = (bool) ($form_settings_result['email']['post_trash_notification']['status'] ?? false);
            if (! $allow_notify_trash_email) {
                return;
            }

            $user_email = $this->get_user_email_by_post_id($post_id);
            if (empty($user_email)) {
                return;
            }

            $this->notify_user_when_post_trashed($post_link, $title, $form_settings_result, $user_email);
        }

        /**
         * @param $name
         *
         * @return string
         */
        private function convert_to_id($name)
        {

            $name = ! empty($name) ? $name : date('Y-m-d');
            $name = preg_replace('/[^a-zA-Z0-9]+/', '-', trim($name));
            $name = trim($name, '-');

            return substr($name, 0, 20);
        }

        /**
         * Convert base64 to images, after that upload images and get image urls, image id
         */
        private function upload_images_and_get_properties($post_title, $base64ImageData)
        {

            $image_urls  = [];
            $image_ids   = [];
            $typePattern = '/data:image\/([a-zA-Z0-9]+);base64/';

            $base64FullTags = $base64ImageData[0];
            $base64Contents = $base64ImageData[1];

            foreach ($base64Contents as $index => $base64Data) {
                $image_data = base64_decode($base64Data);
                preg_match($typePattern, $base64FullTags[ $index ], $image_type);
                $image_extension = $image_type[1] === 'jpeg' ? 'jpg' : $image_type[1];

                $upload_dir = wp_upload_dir();
                $file_name  = $this->convert_to_id($post_title) . '-' . uniqid() . '.' . $image_extension;
                $file_path  = $upload_dir['path'] . '/' . $file_name;

                if (! function_exists('WP_Filesystem')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                WP_Filesystem();
                global $wp_filesystem;
                if (! $wp_filesystem) {
                    return [];
                }

                $wp_filesystem->put_contents($file_path, $image_data, FS_CHMOD_FILE);

                $file = [
                    'name'     => $file_name,
                    'type'     => 'image/' . $image_extension,
                    'tmp_name' => $file_path,
                    'error'    => 0,
                    'size'     => filesize($file_path),
                ];

                if (! function_exists('media_handle_sideload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                }

                $attachment_id = media_handle_sideload($file, 0);

                if (is_wp_error($attachment_id)) {
                    @unlink($file_path);
                    wp_send_json_error([ 'message' => $attachment_id->get_error_message() ]);
                    wp_die();
                }

                $attachment_url = wp_get_attachment_url($attachment_id);
                if ($attachment_url) {
                    $image_urls[] = $attachment_url;
                    $image_ids[]  = $attachment_id;
                } else {
                    wp_send_json_error([ 'message' => esc_html__('Failed to get attachment URL', 'ruby-submission') ]);
                    wp_die();
                }
            }

            return [ 'image_urls' => $image_urls, 'image_ids' => $image_ids ];
        }

        /**
         * Create image tag in content of the post with image url
         */
        private function create_image_tag_in_post($image_urls)
        {

            $image_tags = [];
            foreach ($image_urls as $image_url) {
                $image_tag    = '<img class="alignnone size-full" src="' . $image_url . '" alt="" />';
                $image_tags[] = $image_tag;
            }

            return $image_tags;
        }

        private function convert_base64_to_img_tag($content, $base64_data, $image_tags)
        {

            foreach ($image_tags as $index => $image_tag) {
                $content = str_replace($base64_data[ $index ], $image_tag, $content);
            }

            return $content;
        }

        private function is_not_exist_post_title($created_post_id, $title, $form_settings_result)
        {

            $is_unique_title = (bool) ($form_settings_result['general_setting']['unique_title'] ?? true);

            if (! $is_unique_title) {
                return [ 'status' => true, 'message' => 'valid title!' ];
            }

            $post = get_posts(
                [
                    'post_type'   => 'post',
                    'title'       => trim($title),
                    'post_status' => 'all',
                    'numberposts' => 1,
                ]
            );

            if (! empty($post[0]->ID) && $created_post_id !== $post[0]->ID) {
                return [
                    'status'  => false,
                    'message' => esc_html__('The title already exists.', 'ruby-submission'),
                ];
            }

            return [
                'status'  => true,
                'message' => esc_html__('Valid title!', 'ruby-submission'),
            ];
        }

        private function get_post_status($form_settings_result)
        {

            $post_status_key = (string) ($form_settings_result['general_setting']['post_status'] ?? '');

            $post_status_list = [
                'Draft'          => 'draft',
                'Pending Review' => 'pending',
                'Private'        => 'private',
                'Publish'        => 'publish',
            ];

            return $post_status_list[ $post_status_key ] ?? 'pending';
        }

        private function validate_recaptcha($recaptcha_response, $form_settings_result)
        {

            $recaptcha_setting_status = (bool) ($form_settings_result['security_fields']['recaptcha']['status'] ?? false);
            if (! $recaptcha_setting_status) {
                return [
                    'status'  => true,
                    'message' => esc_html__('reCAPTCHA is disabled.', 'ruby-submission'),
                ];
            }

            if (empty($recaptcha_response)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('reCAPTCHA response is missing.', 'ruby-submission'),
                ];
            }

            $recaptcha_secret_key = (string) ($form_settings_result['security_fields']['recaptcha']['recaptcha_secret_key'] ?? '');
            if (empty($recaptcha_secret_key)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('reCAPTCHA secret key is missing.', 'ruby-submission'),
                ];
            }

            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret'   => $recaptcha_secret_key,
                    'response' => $recaptcha_response,
                ],
            ]);

            $response_body = wp_remote_retrieve_body($response);
            $result        = json_decode($response_body);

            if ($result->success) {
                return [
                    'status'  => true,
                    'message' => esc_html__('Valid reCAPTCHA.', 'ruby-submission'),
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => esc_html__('Invalid reCAPTCHA.', 'ruby-submission'),
                ];
            }
        }

        private function validate_title($created_post_id, $title, $form_settings_result)
        {

            $title_setting = (string) ($form_settings_result['form_fields']['post_title'] ?? 'Require');

            if ($title_setting === 'Disable' && $title !== '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('The post title is not allowed!', 'ruby-submission'),
                ];
            } elseif ($title_setting === 'Require' && $title === '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('Title is missing!', 'ruby-submission'),
                ];
            }

            return $this->is_not_exist_post_title($created_post_id, $title, $form_settings_result);
        }

        private function validate_excerpt($excerpt, $form_settings_result)
        {

            $excerpt_setting = (string) ($form_settings_result['form_fields']['tagline'] ?? 'Require');

            if ($excerpt_setting === 'Disable' && $excerpt !== '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('The post excerpt is not allowed!', 'ruby-submission'),
                ];
            } elseif ($excerpt_setting === 'Require' && $excerpt === '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('Post excerpt is missing!', 'ruby-submission'),
                ];
            }

            return [
                'status'  => true,
                'message' => esc_html__('Valid excerpt!', 'ruby-submission'),
            ];
        }

        private function validate_user_name($user_name, $form_settings_result)
        {

            $user_name_setting = (string) ($form_settings_result['form_fields']['user_name'] ?? 'Require');

            if ($user_name_setting === 'Disable' && $user_name !== '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('The user name is not allowed!', 'ruby-submission'),
                ];
            } elseif ($user_name_setting === 'Require' && $user_name === '' && ! is_user_logged_in()) {
                return [
                    'status'  => false,
                    'message' => esc_html__('User name is missing!', 'ruby-submission'),
                ];
            }

            return [
                'status'  => true,
                'message' => esc_html__('Valid user name!', 'ruby-submission'),
            ];
        }

        private function validate_user_email($user_email, $form_settings_result)
        {

            $user_email_setting = (string) ($form_settings_result['form_fields']['user_email'] ?? 'Require');

            if ($user_email_setting === 'Disable' && $user_email !== '') {
                return [
                    'status'  => false,
                    'message' => esc_html__('User email is not allowed!', 'ruby-submission'),
                ];
            } elseif ($user_email_setting === 'Require' && $user_email === '' && ! is_user_logged_in()) {
                return [
                    'status'  => false,
                    'message' => esc_html__('User email is missing!', 'ruby-submission'),
                ];
            }

            $email_regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (! preg_match($email_regex, $user_email)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('User email is invalid', 'ruby-submission'),
                ];
            }

            return [
                'status'  => true,
                'message' => esc_html__('Valid user email!', 'ruby-submission'),
            ];
        }

        private function try_get_old_title($created_post_id, $title, $form_settings_result)
        {

            $title_setting = (string) ($form_settings_result['form_fields']['post_title'] ?? 'Require');

            if (! is_null($created_post_id) && $title_setting === 'Disable') {
                return get_the_title($created_post_id);
            }

            return $title;
        }

        private function try_get_old_excerpt($created_post_id, $excerpt, $form_settings_result)
        {

            $excerpt_setting = (string) ($form_settings_result['form_fields']['tagline'] ?? 'Require');

            if (! is_null($created_post_id) && $excerpt_setting === 'Disable') {
                return get_post($created_post_id)->post_excerpt;
            }

            return $excerpt;
        }

        private function validate_featured_image($created_post_id, $form_settings_result, $featured_image)
        {

            $featured_image_status     = (string) ($form_settings_result['form_fields']['featured_image']['status'] ?? 'Require');
            $featured_image_size_limit = (int) ($form_settings_result['form_fields']['featured_image']['upload_file_size_limit'] ?? 0);
            $featured_image_size_limit = max(0, $featured_image_size_limit);

            if ($featured_image_status === 'Disable' && ! empty($featured_image)) {
                return [
                    'status'  => false,
                    'message' => esc_html__('The featured image is not allowed!', 'ruby-submission'),
                ];
            }

            if (! is_null($created_post_id)
                 && $featured_image_status === 'Require'
                 && empty($featured_image)
                 && ! has_post_thumbnail($created_post_id)
            ) {
                return [
                    'status'  => false,
                    'message' => esc_html__('Featured image is missing!', 'ruby-submission'),
                ];
            }

            if ($featured_image_size_limit > 0 && ! empty($featured_image)) {
                $limit_byte_size          = $featured_image_size_limit * 1024 * 1024;
                $featured_image_byte_size = isset($featured_image['size']) ? $featured_image['size'] : 0;

                if ($featured_image_byte_size > 0 && $featured_image_byte_size > $limit_byte_size) {
                    return [
                        'status'  => false,
                        'message' => esc_html__('Image size exceeds the allowed limit! Please choose an image with a smaller size.', 'ruby-submission'),
                    ];
                }
            }

            return [
                'status'  => true,
                'message' => esc_html__('Valid featured image.', 'ruby-submission'),
            ];
        }

        public function update_post()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing!', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);

            $created_post_id = isset($data['postId']) ? intval($data['postId']) : null;

            if (is_null($created_post_id)) {
                wp_send_json_error(esc_html__('Post ID is null', 'ruby-submission'));
                wp_die();
            }

            $post = get_post($created_post_id);
            if (empty($post)) {
                wp_send_json_error(esc_html__('Post ID was not existed.', 'ruby-submission'));
                wp_die();
            }

            $author_id       = (int) $post->post_author;
            $current_user_id = get_current_user_id();

            if ($author_id !== $current_user_id) {
                wp_send_json_error(esc_html__('You are not allowed to edit this post.', 'ruby-submission'));
                wp_die();
            }

            $this->handle_data_to_submit_post();
        }

        /**
         * Submit post and set featured image.
         */
        public function create_post()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing!', 'ruby-submission'));
                wp_die();
            }

            $data            = json_decode(stripslashes($_POST['data']), true);
            $created_post_id = isset($data['postId']) ? intval($data['postId']) : null;

            if (! empty($created_post_id)) {
                wp_send_json_error(esc_html__('Invalid Data', 'ruby-submission'));
                wp_die();
            }

            $this->handle_data_to_submit_post();
        }

        private function handle_data_to_submit_post()
        {

            $data                     = json_decode(stripslashes($_POST['data']), true);
            $title                    = isset($data['title']) ? sanitize_text_field($data['title']) : '';
            $excerpt                  = isset($data['excerpt']) ? sanitize_text_field($data['excerpt']) : '';
            $content                  = isset($data['content']) ? $data['content'] : '';
            $form_id                  = isset($data['formId']) ? intval($data['formId']) : '';
            $created_post_id          = isset($data['postId']) ? intval($data['postId']) : null;
            $is_remove_featured_image = filter_var($data['isRemoveFeaturedImage'] ?? false, FILTER_VALIDATE_BOOLEAN, false);
            $user_name                = isset($data['userName']) ? sanitize_text_field($data['userName']) : '';
            $user_email               = isset($data['userEmail']) ? sanitize_text_field($data['userEmail']) : '';
            $custom_fields_data       = isset($data['customFieldsData']) ? $data['customFieldsData'] : [];
            $recaptcha_response       = isset($data['recaptchaResponse']) ? $data['recaptchaResponse'] : '';

            $categories = [];
            $post_tags  = [];

            if (isset($data['categories'])) {
                $categories = is_array($data['categories'])
                    ? array_map('intval', $data['categories'])
                    : [ intval($data['categories']) ];
            }

            if (isset($data['tags'])) {
                $post_tags = is_array($data['tags'])
                    ? array_map('esc_attr', $data['tags'])
                    : [ esc_attr($data['tags']) ];
            }

            if (empty($form_id)) {
                wp_send_json_error(esc_html__('Form ID is missing.', 'ruby-submission'));
                wp_die();
            }

            $form_settings = $this->get_form_settings_by_id($form_id);
            if ($form_settings === false) {
                wp_send_json_error(esc_html__('Cannot find form settings.', 'ruby-submission'));
                wp_die();
            }

            $form_settings_result = json_decode(($form_settings->data), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Cannot find form settings.', 'ruby-submission'));
                wp_die();
            }

            $recaptcha_validate = $this->validate_recaptcha($recaptcha_response, $form_settings_result);
            if (! $recaptcha_validate['status']) {
                wp_send_json_error($recaptcha_validate['message']);
                wp_die();
            }

            if (empty($content) || $content === '<p><br></p>') {
                wp_send_json_error(esc_html__('Content is missing.', 'ruby-submission'));
                wp_die();
            }

            $title_validate = $this->validate_title($created_post_id, $title, $form_settings_result);

            if (! $title_validate['status']) {
                wp_send_json_error($title_validate['message']);
                wp_die();
            }

            $excerpt_validate = $this->validate_excerpt($excerpt, $form_settings_result);
            if (! $excerpt_validate['status']) {
                wp_send_json_error($excerpt_validate['message']);
                wp_die();
            }

            if (! is_user_logged_in()) {

                $user_name_validate = $this->validate_user_name($user_name, $form_settings_result);
                if (! $user_name_validate['status']) {
                    wp_send_json_error($user_name_validate['message']);
                    wp_die();
                }

                $user_email_validate = $this->validate_user_email($user_email, $form_settings_result);
                if (! $user_email_validate['status']) {
                    wp_send_json_error($user_email_validate['message']);
                    wp_die();
                }
            } else {

                $current_user = wp_get_current_user();
                $user_email   = $current_user->user_email;
                $user_name    = $current_user->display_name;
            }

            $featured_image_data = isset($_FILES['image']) ? $_FILES['image'] : '';
            if (! empty($featured_image_data)) {
                $featured_image_validate = $this->validate_featured_image($created_post_id, $form_settings_result, $_FILES['image']);
                if (! $featured_image_validate['status']) {
                    wp_send_json_error($featured_image_validate['message']);
                    wp_die();
                }
            }

            $post_author_validate = $this->validate_post_author($form_settings_result);
            if (! $post_author_validate['status']) {
                wp_send_json_error($post_author_validate['message']);
                wp_die();
            }

            $post_author = $post_author_validate['post_author'];
            $title       = $this->try_get_old_title($created_post_id, $title, $form_settings_result);
            $excerpt     = $this->try_get_old_excerpt($created_post_id, $excerpt, $form_settings_result);
            $post_status = $this->get_post_status($form_settings_result);

            $post_images_handled = $this->try_handle_image_in_post($content, $title);
            $content             = wp_kses_post($post_images_handled['content']);

            $image_ids = $post_images_handled['image_ids'];

            $post_data = [
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => $post_status,
                'post_author'   => $post_author,
                'post_excerpt'  => $excerpt,
                'post_type'     => 'post',
                'post_category' => $this->filter_categories($categories, $form_settings_result),
                'tags_input'    => $this->filter_tags($post_tags, $form_settings_result),
            ];

            $is_new_post = is_null($created_post_id);
            if (! $is_new_post) {
                $post_data['ID'] = $created_post_id;
            }

            $post_id = is_null($created_post_id) ? wp_insert_post($post_data) : wp_update_post($post_data);

            if (is_wp_error($post_id)) {
                return new WP_Error('post_creation_failed', esc_html__('Failed to create post', 'ruby-submission'), [ 'status' => 500 ]);
            }

            $this->try_store_images_from_post_content($image_ids, $post_id, $title);
            $this->try_set_featured_image($post_id, $form_settings_result, $featured_image_data, $is_remove_featured_image);
            $this->update_author_info($post_id, $user_name, $user_email, $form_settings_result);
            $this->update_custom_field_data($post_id, $custom_fields_data);

            update_post_meta($post_id, 'rbsm_form_id', $form_id);

            $post_link    = get_permalink($post_id);
            $mail_message = $this->try_send_email_notification($post_link, $title, $form_settings_result, $user_email, $post_status, $is_new_post);

            $submit_post_result = [
                'post_id' => $post_id,
                'url'     => $post_link,
                'message' => esc_html__('Post submitted successfully!', 'ruby-submission'),
            ];

            if (! empty($mail_message)) {
                $submit_post_result['email_message'] = $mail_message;
            }

            wp_send_json_success($submit_post_result);
            wp_die();
        }

        /**
         * @param $categories
         * @param $form_settings_result
         *
         * @return array
         * not allow add new
         */
        private function filter_categories($categories, $form_settings_result)
        {

            if ((empty($categories) || ! is_array($categories)) && ! empty($form_settings_result['form_fields']['categories']['auto_assign_category_ids'])) {
                return (array) $form_settings_result['form_fields']['categories']['auto_assign_category_ids'];
            }

            $category_ids = [];

            foreach ($categories as $id) {
                $category = get_term_by('term_id', $id, 'category');
                if (! empty($category) && ! is_wp_error($category)) {
                    $category_ids[] = $category->term_id;
                }
            }

            return $category_ids;
        }

        /**
         * @param $tags
         * @param $form_settings_result
         *
         * @return array
         * check tags and add tags
         */
        private function filter_tags($tags, $form_settings_result)
        {

            if ((empty($tags) || ! is_array($tags)) && ! empty($form_settings_result['form_fields']['tags']['auto_assign_tags'])) {
                return (array) $form_settings_result['form_fields']['tags']['auto_assign_tags'];
            }

            if (empty($form_settings_result['form_fields']['tags']['allow_add_new_tag'])) {

                $filtered_tags = [];
                foreach ($tags as $name) {
                    $tag = get_term_by('name', $name, 'post_tag');
                    if (! empty($tag) && ! is_wp_error($tag)) {
                        $filtered_tags[] = $name;
                    }
                }

                return $filtered_tags;
            }

            return $tags;
        }

        private function try_send_email_notification($post_link, $title, $form_settings_result, $user_email, $post_status, $is_new_post)
        {

            $email_message = [];

            $should_notify_admin = (bool) ($form_settings_result['email']['admin_mail']['status'] ?? false);
            if ($should_notify_admin) {
                $admin_message                  = $this->notify_admin_on_post_submission($post_link, $title, $form_settings_result);
                $email_message['admin_message'] = $admin_message;
            }

            $should_notify_user_on_post_submission = (bool) ($form_settings_result['email']['post_submit_notification']['status'] ?? false);
            if ($should_notify_user_on_post_submission) {
                $user_submitted_message                  = $this->notify_user_on_post_submission($post_link, $title, $form_settings_result, $user_email, $is_new_post);
                $email_message['user_submitted_message'] = $user_submitted_message;
            }

            $should_notify_user_on_post_publish = (bool) ($form_settings_result['email']['post_publish_notification']['status'] ?? false);
            if ($should_notify_user_on_post_publish && $post_status === 'publish') {
                $user_published_message                  = $this->notify_user_on_post_publish($post_link, $title, $form_settings_result, $user_email);
                $email_message['user_published_message'] = $user_published_message;
            }

            return $email_message;
        }

        private function notify_admin_on_post_submission($post_link, $title, $form_settings_result)
        {

            $admin_mail = (string) ($form_settings_result['email']['admin_mail']['email'] ?? '');
            if (empty($admin_mail)) {
                $admin_mail = get_option('admin_email');
            }

            $subject = (string) ($form_settings_result['email']['admin_mail']['subject'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just submitted.', 'ruby-submission'));
            $message = (string) ($form_settings_result['email']['admin_mail']['message'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just submitted. Please check at: ', 'ruby-submission') . $post_link);
            $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

            $placeholders = [
                '/{{post_title}}/' => $title,
                '/{{post_link}}/'  => $post_link,
            ];

            $subject = preg_replace(array_keys($placeholders), array_values($placeholders), $subject);
            $message = preg_replace(array_keys($placeholders), array_values($placeholders), $message);
            $headers = preg_replace(array_keys($placeholders), array_values($placeholders), $headers);

            if (wp_mail($admin_mail, $subject, $message, $headers)) {
                return esc_html__('Admin mail was sent successfully.', 'ruby-submission');
            } else {
                return esc_html__('Admin mail sending failed.', 'ruby-submission');
            }
        }

        private function notify_user_on_post_submission($post_link, $title, $form_settings_result, $user_email, $is_new_post)
        {

            if (empty($user_email)) {
                $current_user = wp_get_current_user();
                $user_email   = $current_user->user_email;
            }

            if (empty($user_email)) {
                return 'The user email is empty';
            }

            $subject = (string) ($form_settings_result['email']['post_submit_notification']['subject'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just submitted.', 'ruby-submission'));
            $message = (string) ($form_settings_result['email']['post_submit_notification']['message'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just submitted. Please check at: ', 'ruby-submission') . $post_link);
            $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

            $subject = $is_new_post ? '[NEW POST]: ' . $subject : '[POST EDITED]: ' . $subject;

            $placeholders = [
                '/{{post_title}}/' => $title,
                '/{{post_link}}/'  => $post_link,
            ];

            $subject = preg_replace(array_keys($placeholders), array_values($placeholders), $subject);
            $message = preg_replace(array_keys($placeholders), array_values($placeholders), $message);
            $headers = preg_replace(array_keys($placeholders), array_values($placeholders), $headers);

            if (wp_mail($user_email, $subject, $message, $headers)) {
                return esc_html__('User mail was sent successfully.', 'ruby-submission');
            } else {
                return esc_html__('User mail sending failed.', 'ruby-submission');
            }
        }

        private function notify_user_on_post_publish($post_link, $title, $form_settings_result, $user_email)
        {

            if (empty($user_email)) {
                $current_user = wp_get_current_user();
                $user_email   = $current_user->user_email;
            }

            if (empty($user_email)) {
                return esc_html__('The user email address is empty.', 'ruby-submission');
            }

            $subject = (string) ($form_settings_result['email']['post_publish_notification']['subject'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just published.', 'ruby-submission'));
            $message = (string) ($form_settings_result['email']['post_publish_notification']['message'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just published. Please check at: ', 'ruby-submission') . $post_link);
            $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

            $placeholders = [
                '/{{post_title}}/' => $title,
                '/{{post_link}}/'  => $post_link,
            ];

            $subject = preg_replace(array_keys($placeholders), array_values($placeholders), $subject);
            $message = preg_replace(array_keys($placeholders), array_values($placeholders), $message);
            $headers = preg_replace(array_keys($placeholders), array_values($placeholders), $headers);

            if (wp_mail($user_email, $subject, $message, $headers)) {
                return esc_html__('User mail was sent successfully.', 'ruby-submission');
            } else {
                return esc_html__('User mail sending failed.', 'ruby-submission');
            }
        }

        private function notify_user_when_post_trashed($post_link, $title, $form_settings_result, $user_email)
        {

            if (empty($user_email)) {
                $current_user = wp_get_current_user();
                $user_email   = $current_user->user_email;
            }

            if (empty($user_email)) {
                return esc_html__('The user email address is empty.', 'ruby-submission');
            }

            $subject = (string) ($form_settings_result['email']['post_trash_notification']['subject'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just trashed.', 'ruby-submission'));
            $subject = '[POST TRASHED]: ' . $subject;
            $message = (string) ($form_settings_result['email']['post_trash_notification']['message'] ?? esc_html__('The post ', 'ruby-submission') . $title . esc_html__(' has just trashed. Please check at: ', 'ruby-submission') . $post_link);
            $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

            $placeholders = [
                '/{{post_title}}/' => $title,
                '/{{post_link}}/'  => $post_link,
            ];

            $subject = preg_replace(array_keys($placeholders), array_values($placeholders), $subject);
            $message = preg_replace(array_keys($placeholders), array_values($placeholders), $message);
            $headers = preg_replace(array_keys($placeholders), array_values($placeholders), $headers);

            if (wp_mail($user_email, $subject, $message, $headers)) {
                return esc_html__('User mail was sent successfully.', 'ruby-submission');
            } else {
                return esc_html__('User mail sending failed.', 'ruby-submission');
            }
        }

        private function try_set_featured_image($post_id, $form_settings_result, $featured_image_data, $is_remove_featured_image)
        {

            $featuredImageStatus = (string) ($form_settings_result['form_fields']['featured_image']['status'] ?? 'Disable');
            if (! empty($featured_image_data) && $featuredImageStatus !== 'Disable') {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('image', $post_id);

                if (is_wp_error($attachment_id)) {
                    wp_send_json_error($attachment_id->get_error_message());
                }

                set_post_thumbnail($post_id, $attachment_id);
            } elseif ($is_remove_featured_image) {
                delete_post_thumbnail($post_id);
            }

            $this->try_set_default_featured_image($post_id, $form_settings_result);
        }

        private function try_store_images_from_post_content($image_ids, $post_id, $title)
        {

            if (! empty($image_ids)) {
                $meta_key = $title . '_images';
                update_post_meta($post_id, $meta_key, $image_ids);
            }
        }

        /**
         * @param $content
         * @param $title
         *
         * @return array
         */
        private function try_handle_image_in_post($content, $title)
        {

            $base64_pattern = '/<img[^>]+src="data:image\/[^;]+;base64,([^"]+)"[^>]*>/i';

            preg_match_all($base64_pattern, $content, $base64_matches);

            $image_properties = $this->upload_images_and_get_properties($title, $base64_matches);
            $image_urls       = $image_properties['image_urls'];
            $image_ids        = $image_properties['image_ids'];

            $image_tags = $this->create_image_tag_in_post($image_urls);
            $content    = $this->convert_base64_to_img_tag($content, $base64_matches[0], $image_tags);

            return [
                'content'   => $content,
                'image_ids' => $image_ids,
            ];
        }

        /**
         * @param $form_settings_result
         *
         * @return array
         */
        private function validate_post_author($form_settings_result)
        {

            $author_access = (string) ($form_settings_result['user_login']['author_access'] ?? 'Only Logged User');

            if ($author_access === 'Only Logged User') {
                if (! is_user_logged_in()) {
                    return [
                        'status'      => false,
                        'message'     => esc_html__('You need to log in before submitting a post.', 'ruby-submission'),
                        'post_author' => 0,
                    ];
                }

                $author_id = get_current_user_id();
            } else {

                $author_id = (int) ($form_settings_result['user_login']['assign_author_id'] ?? 0);
                if (empty($author_id)) {
                    return [
                        'status'      => false,
                        'message'     => esc_html__('Default author is not configured.', 'ruby-submission'),
                        'post_author' => 0,
                    ];
                }

                $user = get_user_by('ID', $author_id);

                if (! $user) {
                    return [
                        'status'      => false,
                        'message'     => esc_html__('Default author is not exist.', 'ruby-submission'),
                        'post_author' => 0,
                    ];
                }
            }

            return [
                'status'      => true,
                'message'     => esc_html__('Valid author post.', 'ruby-submission'),
                'post_author' => $author_id,
            ];
        }

        private function update_author_info($post_id, $user_name, $user_email, $form_settings_result)
        {

            $user_name_setting = isset($form_settings_result['form_fields']['user_name'])
                ? sanitize_text_field($form_settings_result['form_fields']['user_name'])
                : 'Disable';

            $user_email_setting = isset($form_settings_result['form_fields']['user_email'])
                ? sanitize_text_field($form_settings_result['form_fields']['user_email'])
                : 'Disable';

            $old_author_info = get_post_meta($post_id, 'rbsm_author_info', true);
            $old_user_name   = '';
            $old_user_email  = '';

            if (! empty($old_author_info)) {
                $old_user_name  = isset($old_author_info['user_name']) ? sanitize_text_field($old_author_info['user_name']) : '';
                $old_user_email = isset($old_author_info['user_email']) ? sanitize_text_field($old_author_info['user_email']) : '';
            }

            $author_info = [
                'user_name'  => $user_name_setting === 'Disable' ? $old_user_name : $user_name,
                'user_email' => $user_email_setting === 'Disable' ? $old_user_email : $user_email,
            ];

            update_post_meta($post_id, 'rbsm_author_info', $author_info);
        }

        private function try_set_default_featured_image($post_id, $form_settings_result)
        {

            $default_featured_image_id     = (int) ($form_settings_result['form_fields']['featured_image']['default_featured_image'] ?? 0);
            $default_featured_image_status = (string) ($form_settings_result['form_fields']['featured_image']['status'] ?? 'Disable');
            $image                         = wp_get_attachment_image_src($default_featured_image_id, 'full');

            if ($default_featured_image_status === 'Disable' || $image === false || has_post_thumbnail($post_id)) {
                return;
            }

            set_post_thumbnail($post_id, $default_featured_image_id);
        }

        private function update_custom_field_data($post_id, $custom_fields_data)
        {

            if (! empty($custom_fields_data)) {
                foreach ($custom_fields_data as $custom_field_data) {
                    $content = isset($custom_field_data['content']) ? sanitize_text_field($custom_field_data['content']) : '';
                    $label   = isset($custom_field_data['label']) ? sanitize_text_field($custom_field_data['label']) : '';
                    $name    = isset($custom_field_data['name']) ? sanitize_text_field($custom_field_data['name']) : '';
                    $type    = isset($custom_field_data['type']) ? sanitize_text_field($custom_field_data['type']) : '';

                    if (empty($content) || empty($label) || empty($name) || empty($type)) {
                        continue;
                    }

                    update_post_meta($post_id, $name, [
                        'content'  => $content,
                        'label'    => $label,
                        'meta_key' => $name,
                        'type'     => $type,
                    ]);
                }
            }
        }

        public function get_form_by_id()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing.', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Invalid json data.', 'ruby-submission'));
                wp_die();
            }

            $id = isset($data['id']) ? intval($data['id']) : '';
            if (empty($id)) {
                wp_send_json_error(esc_html__('Form ID is missing.', 'ruby-submission'));
                wp_die();
            }

            $row = $this->get_form_settings_by_id($id);

            if ($row !== false) {
                wp_send_json_success($row);
            } else {
                wp_send_json_error(esc_html__('No records found!', 'ruby-submission'));
            }

            wp_die();
        }

        private function get_form_settings_by_id($form_id)
        {

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            $query = "SELECT * FROM {$table_name} WHERE id = %d";
            $sql   = $wpdb->prepare($query, $form_id);
            $row   = $wpdb->get_row($sql);

            return $row;
        }

        /**
         * Get all posts of the current user who was logged in.
         */
        public function get_user_posts()
        {

            if (! isset($_POST['_nonce']) || ! wp_verify_nonce($_POST['_nonce'], 'ruby-submission')) {
                wp_send_json_error(esc_html__('Invalid nonce.', 'ruby-submission'));
                wp_die();
            }

            if (! is_user_logged_in()) {
                wp_send_json_error(esc_html__('You are not logged in. Please log in to continue!', 'ruby-submission'));
                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('Data is missing.', 'ruby-submission'));
                wp_die();
            }

            $data = json_decode(stripslashes($_POST['data']), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(esc_html__('Invalid json data.', 'ruby-submission'));
                wp_die();
            }

            $paged = isset($data['paged']) ? intval($data['paged']) : null;
            if (is_null($paged)) {
                wp_send_json_error(esc_html__('Page number is empty!', 'ruby-submission'));
                wp_die();
            }

            $current_user = wp_get_current_user();
            $user_id      = $current_user->ID;

            $should_display_post_view = function_exists('pvc_get_post_views');

            $args = [
                'post_type'      => 'post',
                'author'         => $user_id,
                'posts_per_page' => 10,
                'paged'          => $paged,
                'meta_key'       => 'rbsm_form_id',
                'post_status'    => [ 'publish', 'pending', 'draft' ],
            ];

            $query      = new WP_Query($args);
            $user_posts = [];

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    global $post;

                    $post_id        = $post->ID;
                    $title          = $post->post_title;
                    $date           = $post->post_date;
                    $status         = $post->post_status;
                    $short_desc     = $post->post_excerpt;
                    $categories_raw = get_the_category($post_id);
                    $tags_raw       = get_the_tags($post_id);
                    $link           = get_permalink($post_id);

                    if (empty($short_desc)) {
                        $short_desc = get_the_content();
                    }

                    if (! empty($short_desc)) {
                        $short_desc = wp_trim_words($short_desc, 12, '...');
                    }

                    $categories = [];
                    if ($categories_raw) {
                        $categories = array_map(function ($category) {

                            return $category->name;
                        }, $categories_raw);
                    }

                    $tags = [];
                    if ($tags_raw) {
                        $tags = array_map(function ($tag) {

                            return $tag->name;
                        }, $tags_raw);
                    }

                    $post_view = 0;
                    if (function_exists('pvc_get_post_views')) {
                        $post_view = pvc_get_post_views($post_id);
                    }

                    $user_posts[] = [
                        'title'      => $title,
                        'categories' => $categories,
                        'tags'       => $tags,
                        'date'       => $date,
                        'post_id'    => $post_id,
                        'post_view'  => $post_view,
                        'status'     => $status,
                        'link'       => $link,
                        'short_desc' => $short_desc,
                    ];
                }

                $is_final_page = $query->max_num_pages === $query->get('paged') || false;
                wp_reset_postdata();
                wp_send_json_success([
                    'user_posts'               => $user_posts,
                    'should_display_post_view' => $should_display_post_view,
                    'is_final_page'            => $is_final_page,
                ]);

                wp_die();
            }

            if (! isset($_POST['data'])) {
                wp_send_json_error(esc_html__('No more posts found.', 'ruby-submission'));
                wp_die();
            }
        }

        public function try_notify_on_post_publish($ID, $post)
        {

            $form_submission_id = get_post_meta($ID, 'rbsm_form_id', true);

            if (! $form_submission_id) {
                return;
            }

            $form_settings = $this->get_form_settings_by_id($form_submission_id);

            if (! $form_settings) {
                return;
            }

            $form_settings_result = json_decode(stripslashes($form_settings->data), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return;
            }

            $user_published_message = (bool) ($form_settings_result['email']['post_publish_notification']['status'] ?? false);
            if (! $user_published_message) {
                return;
            }

            $title      = $post->post_title;
            $post_link  = get_permalink($ID);
            $user_email = get_the_author_meta('user_email', $post->post_author);

            $author_info = get_post_meta($ID, 'rbsm_author_info', true);
            if ($author_info) {
                $user_email = (string) ($author_info['user_email'] ?? '');
            }

            if (empty($user_email)) {
                $user_email = get_the_author_meta('user_email', $post->post_author);
            }

            $this->notify_user_on_post_publish($post_link, $title, $form_settings_result, $user_email);
        }

        private function get_form_settings_by_post_id($post_id)
        {

            $form_submission_id = get_post_meta($post_id, 'rbsm_form_id', true);
            if (! $form_submission_id) {
                return [
                    'status' => false,
                    'data'   => null,
                ];
            }

            $form_submission = $this->get_form_settings_by_id($form_submission_id);
            if (! $form_submission) {
                return [
                    'status' => false,
                    'data'   => null,
                ];
            }

            return [
                'status' => true,
                'data'   => $form_submission,
            ];
        }

        private function get_user_email_by_post_id($post_id)
        {

            $user_email  = '';
            $author_info = get_post_meta($post_id, 'rbsm_author_info', true);

            if ($author_info) {
                $user_email = (string) ($author_info['user_email'] ?? '');
            }

            if (! empty($user_email)) {
                return $user_email;
            }

            $author_id = get_post_field('post_author', $post_id);
            $user      = get_user_by('id', $author_id);
            if ($user) {
                $user_email = $user->user_email;
            }

            return $user_email;
        }
    }
}
/** load */
Ruby_Submission_Client_Ajax_Handler::get_instance();
