<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Form_Shortcode', false)) {
    class Ruby_Submission_Form_Shortcode
    {
        private static $instance;

        public static function get_instance()
        {

            if (self::$instance === null) {
                return new self();
            }

            return self::$instance;
        }

        private $initial_localize_data;

        private function __construct()
        {

            add_action('wp_enqueue_scripts', [ $this, 'enqueue_client' ]);
            add_shortcode('ruby_submission_form', [ $this, 'render_post_creation' ]);
            add_shortcode('ruby_submission_manager', [ $this, 'render_post_manager' ]);
            add_shortcode('ruby_submission_edit', [ $this, 'render_post_editing' ]);
        }

        /** register */
        public function enqueue_client()
        {

            $js_path  = RUBY_SUBMISSION_URL . 'assets/js/';
            $css_path = RUBY_SUBMISSION_URL . 'assets/css/';

            $css_file = ! is_rtl() ? 'main' : 'main-rtl';
            wp_register_style('rbsm-mdi-style', $css_path . 'materialdesignicons.min.css', [], null);
            wp_register_style('rbsm-vuetify-style', $css_path . 'vuetify.min.css', [], null);
            wp_register_style('rbsm-quill-style', $css_path . 'quill.snow.min.css', [], null);
            wp_register_style('rbsm-client-style', $css_path . $css_file . '.css', [
                'rbsm-mdi-style',
                'rbsm-vuetify-style',
                'rbsm-quill-style',
            ], RUBY_SUBMISSION_VERSION);

            wp_register_script('rbsm-vue', $js_path . 'vue.global.prod.js', [], '3.4.33', true);

            $this->initial_localize_data = $this->initial_localize_data();
            wp_localize_script('rbsm-vue', 'rbLocalizeData', $this->initial_localize_data);

            wp_register_script('rbsm-vuetify', $js_path . 'vuetify.min.js', [ 'rbsm-vue' ], '3.6.13', true);
            wp_register_script('rbsm-quill', $js_path . 'quill.min.js', [ 'rbsm-vue' ], '2.0.2', true);

            $main_deps = [
                'rbsm-vue',
                'rbsm-vuetify',
                'rbsm-quill',
            ];

            wp_register_script('rbsm-login', $js_path . 'login.js', $main_deps, RUBY_SUBMISSION_VERSION, true);

            wp_register_script('rbsm-client-recaptcha', $js_path . 'recaptcha.js', [ 'rbsm-vue' ], RUBY_SUBMISSION_VERSION, true);
            $main_deps[] = 'rbsm-client-recaptcha';

            wp_register_script('rbsm-form-content', $js_path . 'submissionFormContent.js', $main_deps, RUBY_SUBMISSION_VERSION, true);
            $main_deps[] = 'rbsm-form-content';

            wp_register_script('rbsm-user-posts-content', $js_path . 'userPostsContent.js', $main_deps, RUBY_SUBMISSION_VERSION, true);
            $main_deps[] = 'rbsm-user-posts-content';

            wp_register_script('rbsm-post-manager', $js_path . 'userPosts.js', $main_deps, RUBY_SUBMISSION_VERSION, true);
            wp_register_script('rbsm-form-shortcode', $js_path . 'app.js', $main_deps, RUBY_SUBMISSION_VERSION, true);
            wp_register_script('rbsm-post-editing', $js_path . 'postEditingContent.js', $main_deps, RUBY_SUBMISSION_VERSION, true);
        }

        private function initial_localize_data()
        {
            $post_manager_settings = Ruby_Submission_Client_Helper::get_instance()->get_post_manager_settings();

            return [
                'nonce'               => wp_create_nonce('ruby-submission'),
                'ajaxUrl'             => admin_url('admin-ajax.php'),
                'userPost'            => $this->try_get_user_post(),
                'loginUrl'            => $this->get_login_url($post_manager_settings),
                'registerURL'         => $this->get_registration_link($post_manager_settings),
                'translate'           => $this->get_translate_array(),
                'postManagerSettings' => $post_manager_settings,
            ];
        }

        private function get_login_url($post_manager_settings)
        {
            $custom_login_link = isset($post_manager_settings['custom_login_and_registration']['custom_login_link']) ? esc_url($post_manager_settings['custom_login_and_registration']['custom_login_link']) : '';
            if (empty($custom_login_link)) {
                $custom_login_link = wp_login_url();
            }

            return $custom_login_link;
        }

        private function get_registration_link($post_manager_settings)
        {
            $custom_registration_link = isset($post_manager_settings['custom_login_and_registration']['custom_registration_link']) ? esc_url($post_manager_settings['custom_login_and_registration']['custom_registration_link']) : '';
            if (empty($custom_registration_link)) {
                $custom_registration_link = get_option('users_can_register') ? wp_registration_url() : '';
            }

            return $custom_registration_link;
        }

        /**
         * @param $atts
         *
         * @return string
         */
        public function render_post_manager($atts)
        {

            if (is_admin() && isset($_GET['action']) && 'elementor' === $_GET['action']) {
                return '<div class="rbsm-admin-placeholder"><h4>' . esc_html__('Ruby Submission Post Manager Placeholder', 'ruby-submission') . '</h4></div>';
            }

            $user_posts_data = $this->get_user_posts_data();
            wp_localize_script('rbsm-vue', 'rbsmUserPostsData', $user_posts_data);

            wp_enqueue_style('rbsm-client-style');
            wp_enqueue_script('rbsm-post-manager');

            return '<div id="rbsm-user-posts" class="rbsm-container"></div>';
        }

        public function render_post_creation($atts)
        {

            if (is_admin() && isset($_GET['action']) && 'elementor' === $_GET['action']) {
                return '<div class="rbsm-admin-placeholder"><h4>' . esc_html__('Ruby submission Form Placeholder', 'ruby-submission') . '</h4></div>';
            }

            $form_setting_id      = ! empty($atts['id']) ? esc_attr($atts['id']) : '';
            $submission_form_data = $this->get_submission_form_data($form_setting_id);
            wp_localize_script('rbsm-vue', 'rbSubmissionForm', $submission_form_data);

            wp_enqueue_style('rbsm-client-style');
            wp_enqueue_script('rbsm-form-shortcode');

            return '<div id="rbsm-form-shortcode" class="rbsm-container"></div>';
        }

        public function render_post_editing()
        {

            if (is_admin() && isset($_GET['action']) && 'elementor' === $_GET['action']) {
                return '<div class="rbsm-admin-placeholder"><h4>' . esc_html__('Ruby Submission Edit Post Form Placeholder', 'ruby-submission') . '</h4></div>';
            }

            if (! is_user_logged_in()) {
                return $this->render_login_page();
            }

            wp_enqueue_style('rbsm-client-style');
            $post_id = isset($_GET['rbsm-id']) ? intval($_GET['rbsm-id']) : '';

            if (empty($post_id)) {
                return $this->get_error_box([
                    'icon'  => 'mdi-note-off-outline',
                    'title' => esc_html__('Post ID Not Found', 'ruby-submission'),
                    'desc'  => esc_html__('The Post ID you are trying to access does not exist. Please ensure that the ID is correct and try again. If you believe this is an error, contact support for assistance.', 'ruby-submission'),
                ]);
            }

            if (! $this->validateAuthorWithPostId($post_id)) {
                return $this->get_error_box([
                    'icon'  => 'mdi-shield-off-outline',
                    'title' => esc_html__('Permission Denied', 'ruby-submission'),
                    'desc'  => esc_html__('You do not have permission to edit this post. Please check if you are logged in with the correct account that has editing privileges. If you believe this is an error, contact support for assistance.', 'ruby-submission'),
                ]);
            }

            $form_settings_id = Ruby_Submission_Client_Helper::get_instance()->get_form_settings_id_by_post($post_id);
            if (empty($form_settings_id)) {
                return $this->get_error_box([
                    'icon'  => 'mdi-database-off-outline',
                    'title' => esc_html__('Data Error', 'ruby-submission'),
                    'desc'  => esc_html__('The specified form settings ID could not be found. Please contact support for assistance.', 'ruby-submission'),
                ]);
            }

            $submission_form_data = $this->get_submission_form_data($form_settings_id);
            wp_localize_script('rbsm-vue', 'rbSubmissionForm', $submission_form_data);
            wp_enqueue_script('rbsm-post-editing');

            return '<div id="rbsm-post-editing" class="rbsm-container"></div>';
        }

        public function get_error_box($settings)
        {

            $icon        = ! empty($settings['icon']) ? $settings['icon'] : 'mdi-information-outline';
            $title       = ! empty($settings['title']) ? $settings['title'] : esc_html__('Information', 'ruby-submission');
            $description = ! empty($settings['desc']) ? $settings['desc'] : '';

            $output = '<div class="rbsm-table-empty yes-left">';
            $output .= '<i class="' . esc_attr($icon) . ' mdi v-icon notranslate v-theme--light v-icon--size-default" aria-hidden="true"></i>';
            $output .= '<h3 class="rbsm-table-empty-title">' . esc_html($title) . '</h3>';
            $output .= '<p class="rbsm-table-empty-desc">' . esc_html($description) . '</p>';
            $output .= '</div>';

            return $output;
        }

        private function render_login_page()
        {

            wp_localize_script('rbsm-vue', 'rbLocalizeData', [
                'loginUrl'            => $this->initial_localize_data['loginUrl'],
                'translate'           => $this->initial_localize_data['translate'],
                'registerURL'         => $this->initial_localize_data['registerURL'],
                'postManagerSettings' => $this->initial_localize_data['postManagerSettings'],
            ]);

            wp_enqueue_style('rbsm-client-style');
            wp_enqueue_script('rbsm-login');

            return '<div id="rbsm-login" class="rbsm-container"></div>';
        }

        private function validateAuthorWithPostId($post_id)
        {

            $post = get_post($post_id);
            if (! $post) {
                return false;
            }

            if (current_user_can('edit_post', $post_id)) {
                return true;
            }

            if (get_current_user_id() === (int) $post->post_author) {
                return true;
            }

            return false;
        }

        private function get_submission_form_data($form_setting_id)
        {

            $result['hasError']     = false;
            $result['errorMessage'] = '';
            $result['formId']       = $form_setting_id;

            $client_helper = Ruby_Submission_Client_Helper::get_instance();
            $form_settings = $client_helper->get_submission_form_setting($form_setting_id);

            if (empty($form_settings)) {
                $result['hasError']     = true;
                $result['errorMessage'] = esc_html__('Unable to locate form settings.', 'ruby-submission');

                return $result;
            }

            $result['isUserLogged'] = is_user_logged_in();
            $result['categories']   = $client_helper->get_categories($form_setting_id);
            $result['tags']         = $client_helper->get_tags($form_setting_id);
            $result['formSettings'] = $form_settings;

            return $result;
        }

        private function try_get_user_post()
        {

            $user_post_id = isset($_GET['rbsm-id']) ? intval($_GET['rbsm-id']) : '';

            if (empty($user_post_id) || ! is_user_logged_in()) {
                return [];
            }

            return $this->get_user_post($user_post_id);
        }

        private function get_user_posts_data()
        {

            $result['isUserLogged']        = is_user_logged_in();
            $result['userPostsData']       = Ruby_Submission_Client_Helper::get_instance()->get_user_posts_data(1);
            $result['postManagerSettings'] = $this->initial_localize_data['postManagerSettings'];

            return $result;
        }

        private function get_user_post($user_post_id)
        {

            $post = get_post((int) $user_post_id);

            if (! $post || is_wp_error($post)) {
                return [];
            }

            $post_author = (int) $post->post_author;
            if ($post_author !== get_current_user_id()) {
                return [];
            }

            $post_id             = $post->ID;
            $title               = $post->post_title;
            $excerpt             = $post->post_excerpt;
            $content             = $post->post_content;
            $content             = preg_replace('/<!--.*?-->/s', '', $content);
            $content             = preg_replace('/[\r\n]+/', '', $content);
            $categories_raw      = get_the_category($post_id);
            $tags_raw            = get_the_tags($post_id);
            $featured_image      = get_the_post_thumbnail($post_id);
            $featured_image_size = $this->get_feature_image_size($post_id);

            $categories = [];
            if ($categories_raw) {
                $categories = array_map(function ($category) {

                    return $category->term_id;
                }, $categories_raw);
            }

            $tags = [];
            if ($tags_raw) {
                $tags = array_map(function ($tag) {

                    return $tag->name;
                }, $tags_raw);
            }

            $form_submission_id     = get_post_meta($post_id, 'rbsm_form_id', true);
            $custom_field_meta_keys = $this->get_custom_field_meta_keys($form_submission_id);
            $custom_fields          = $this->get_custom_field_data($post_id, $custom_field_meta_keys);
            $user_name              = $this->get_user_name($post_id);
            $user_email             = $this->get_user_email($post_id);

            $user_post[] = [
                'title'               => $title,
                'excerpt'             => $excerpt,
                'content'             => $content,
                'categories'          => $categories,
                'tags'                => $tags,
                'featured_image'      => $featured_image,
                'featured_image_size' => $featured_image_size,
                'post_id'             => $post_id,
                'custom_fields'       => $custom_fields,
                'user_name'           => $user_name,
                'user_email'          => $user_email,
            ];

            return $user_post;
        }

        private function get_user_name($user_post_id)
        {

            $user_name   = '';
            $author_info = get_post_meta($user_post_id, 'rbsm_author_info', true);

            if (! empty($author_info)) {
                $user_name = isset($author_info['user_name']) ? sanitize_text_field($author_info['user_name']) : '';
            }

            return $user_name;
        }

        private function get_user_email($user_post_id)
        {

            $user_email  = '';
            $author_info = get_post_meta($user_post_id, 'rbsm_author_info', true);

            if (! empty($author_info)) {
                $user_email = isset($author_info['user_email']) ? sanitize_text_field($author_info['user_email']) : '';
            }

            return $user_email;
        }

        private function get_feature_image_size($user_post_id)
        {

            $file_size    = 0;
            $thumbnail_id = get_post_thumbnail_id($user_post_id);

            if ($thumbnail_id) {
                $file_path = get_attached_file($thumbnail_id);

                if ($file_path && file_exists($file_path) && function_exists('filesize')) {
                    $file_size = filesize($file_path);
                }
            }

            return $file_size;
        }

        private function get_custom_field_data($user_post_id, $custom_field_meta_keys)
        {

            $custom_fields = [];

            foreach ($custom_field_meta_keys as $meta_key) {
                $custom_field = get_post_meta($user_post_id, $meta_key, true);

                if (! empty($custom_field)) {
                    $custom_fields[] = $custom_field;
                }
            }

            return $custom_fields;
        }

        private function get_custom_field_meta_keys($form_submission_id)
        {

            global $wpdb;
            $table_name = $wpdb->prefix . 'rb_submission';

            $query = "SELECT data FROM {$table_name} WHERE id = %d";
            $sql   = $wpdb->prepare($query, $form_submission_id);
            $row   = $wpdb->get_row($sql);

            if (! $row || empty($row->data)) {
                return [];
            }

            $data = json_decode($row->data);
            if (json_last_error() !== JSON_ERROR_NONE || ! isset($data->form_fields->custom_field)) {
                return [];
            }

            $custom_fields = $data->form_fields->custom_field;
            if (! is_array($custom_fields)) {
                return [];
            }

            $metakeys = array_map(function ($custom_field) {

                return isset($custom_field->custom_field_name) ?? '';
            }, $custom_fields);

            return array_filter($metakeys);
        }

        private function get_translate_array()
        {

            return Ruby_Submission_Client_Translate::get_instance()->get_translate_array();
        }
    }
}

/** load */
Ruby_Submission_Form_Shortcode::get_instance();
