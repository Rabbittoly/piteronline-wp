<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Menu', false)) {
    class Ruby_Submission_Menu
    {
        private static $instance;

        public static function get_instance()
        {

            if (self::$instance === null) {
                return new self();
            }

            return self::$instance;
        }

        private function __construct()
        {

            add_action('admin_menu', [ $this, 'register_page_panel' ], 2900);
            add_filter('ruby_dashboard_menu', [ $this, 'dashboard_menu' ], 10, 1);
        }

        public function load_assets()
        {

            add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue' ]);
        }

        public function admin_enqueue()
        {

            wp_register_style('rbsm-mdi-style', RUBY_SUBMISSION_URL . 'assets/css/materialdesignicons.min.css', [], RUBY_SUBMISSION_VERSION);
            wp_register_style('rbsm-vuetify-style', RUBY_SUBMISSION_URL . 'assets/css/vuetify.min.css', [], RUBY_SUBMISSION_VERSION);
            wp_register_style('rbsm-quill-style', RUBY_SUBMISSION_URL . 'assets/css/quill.snow.min.css', [], RUBY_SUBMISSION_VERSION);

            $file_name = ! is_rtl() ? 'admin' : 'admin-rtl';

            wp_enqueue_style('rbsm-admin-style', RUBY_SUBMISSION_URL . 'admin/assets/css/' . $file_name . '.css', [
                'rbsm-mdi-style',
                'rbsm-vuetify-style',
                'rbsm-quill-style',
            ], time());

            wp_enqueue_media();

            wp_register_script('rbsm-vue', RUBY_SUBMISSION_URL . 'assets/js/vue.global.prod.js', [], '3.4.33', true);
            wp_register_script('rbms-vuetify', RUBY_SUBMISSION_URL . 'assets/js/vuetify.min.js', ['rbsm-vue'], '3.6.13', true);
            wp_register_script('rbsm-quill', RUBY_SUBMISSION_URL . 'assets/js/quill.min.js', ['rbsm-vue'], '2.0.2', true);

            $core_deps = [
                'rbsm-vue',
                'rbms-vuetify',
                'rbsm-quill',
            ];

            wp_register_script('rbsm-recaptcha-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/recaptcha.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            wp_register_script('rbsm-preview-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/previewContent.js', [ 'rbsm-recaptcha-content' ], RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-preview-content';

            wp_register_script('rbsm-general-settings-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/generalSettings.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-general-settings-content';

            wp_register_script('rbsm-user-settings-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/userSettings.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-user-settings-content';

            wp_register_script('rbsm-form-fields-contents', RUBY_SUBMISSION_URL . 'admin/assets/js/components/formFields.js', [
                'rbsm-vue',
                'rbms-vuetify',
                'rbsm-quill',
                'wp-mediaelement',
            ], RUBY_SUBMISSION_VERSION, true);

            $script_deps[] = 'rbsm-form-fields-contents';

            wp_register_script('rbsm-security-fields-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/securityFields.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-security-fields-content';

            wp_register_script('rbsm-emails-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/emails.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-emails-content';

            wp_register_script('rbsm-restore-and-backup', RUBY_SUBMISSION_URL . 'admin/assets/js/components/restoreAndBackup.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-restore-and-backup';

            wp_register_script('rbsm-form-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/formContent.js', $core_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-form-content';

            wp_register_script('rbsm-form-settings-content', RUBY_SUBMISSION_URL . 'admin/assets/js/components/formSettingsContent.js', $script_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-form-settings-content';

            wp_register_script('rbsm-post-manager', RUBY_SUBMISSION_URL . 'admin/assets/js/components/postManagerContent.js', $script_deps, RUBY_SUBMISSION_VERSION, true);
            $script_deps[] = 'rbsm-post-manager';

            wp_register_script(
                'rbsm-admin',
                RUBY_SUBMISSION_URL . 'admin/assets/js/app.js',
                $script_deps,
                RUBY_SUBMISSION_VERSION,
                true
            );

            wp_localize_script(
                'rbsm-vue',
                'rbAjax',
                [
                    'ajaxUrl'   => admin_url('admin-ajax.php'),
                    'nonce'     => wp_create_nonce('ruby-submission'),
                    'translate' => $this->get_translate_array(),
                ]
            );

            wp_enqueue_script('rbsm-admin');
        }

        public function register_page_panel()
        {

            if (is_plugin_active('foxiz-core/foxiz-core.php')) {
                $panel_hook_suffix = add_submenu_page(
                    'foxiz-admin',
                    esc_html__('Ruby Submission', 'ruby-submission'),
                    esc_html__('Ruby Submission', 'ruby-submission'),
                    'manage_options',
                    'ruby-submission',
                    [ $this, 'ruby_submission_render_menu_page' ],
                    100
                );
            } else {
                $panel_hook_suffix = add_menu_page(
                    esc_html__('Ruby Submission', 'ruby-submission'),
                    esc_html__('Ruby Submission', 'ruby-submission'),
                    'manage_options',
                    'ruby-submission',
                    [ $this, 'ruby_submission_render_menu_page' ],
                    'dashicons-welcome-write-blog',
                    61
                );
            }

            /** load script & css */
            add_action('load-' . $panel_hook_suffix, [ $this, 'load_assets' ]);
        }

        public function dashboard_menu($menu)
        {

            if (isset($menu['more'])) {
                $menu['more']['sub_items']['rbsm'] = [
                    'title' => esc_html__('Ruby Submission', 'ruby-submission'),
                    'icon'  => 'rbi-dash rbi-dash-writing',
                    'url'   => admin_url('admin.php?page=ruby-submission'),
                ];
            }

            return $menu;
        }

        private function get_translate_array()
        {

            return Ruby_Submission_Translate::get_instance()->get_translate_array();
        }

        public static function ruby_submission_render_menu_page()
        {

            if (class_exists('RB_ADMIN_CORE')) {
                RB_ADMIN_CORE::get_instance()->header_template();
            }

            include(RUBY_SUBMISSION_PATH . 'admin/dashboard-template.php');
        }
    }
}

Ruby_Submission_Menu::get_instance();
