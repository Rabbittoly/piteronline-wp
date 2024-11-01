<?php
/* foxiz child theme */
function load_place_template($template) {
    if (is_singular('place')) {
        $custom_template = locate_template('templates/single-place.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_place_template');

function custom_generate_uid( $output ) {
    $output = cyrillic_to_latin( $output ); // Добавление вашей функции
    return $output;
}
add_filter( 'foxiz_generate_uid', 'custom_generate_uid' );


/*if (!function_exists('cyrillic_to_latin')) {
    function cyrillic_to_latin($title) {
        $title = str_replace('nbsp', ' ', $title);
        $title = str_replace('8212', '', $title);
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',    'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($title, $converter);
}
}

// Применяем нашу функцию с высоким приоритетом
add_filter('sanitize_title', 'cyrillic_to_latin', 1, 1); */

// Преобразование ссылок в формат премиальная-категория/slug

/*add_filter('category_link', function ($link, $term_id) {
    $term = get_term($term_id, 'category');
    return ($term && !is_wp_error($term)) ? home_url("/{$term->slug}/") : $link;
}, 10, 2);

add_filter('post_link', function ($permalink, $post, $leavename) {
    if (get_post_type($post) != 'post') {
        return $permalink;
    }

    // Массив исключений для URL, которые не должны быть изменены
    $exclusions = [
        'author/', 'tag/', 'wp-content/uploads/',
        'wp-json/', 'feed/', '/feed', 'rss/', '/rss', '/search/',
    ];

    foreach ($exclusions as $exclusion) {
        if (str_contains($permalink, $exclusion)) {
            return $permalink;
        }
    }

    if (preg_match('/\d{4}\/\d{2}/', $permalink)) {
        return $permalink;
    }

    $terms = get_the_terms($post->ID, 'category');
    if (!$terms || is_wp_error($terms) || empty($terms)) {
        return $permalink;
    }

    $term = end($terms);
    return home_url("/{$term->slug}/{$post->post_name}/");
}, 10, 3);




function custom_rewrite_rules() {
    global $wp_rewrite;
    
    // Для URL вида https://yourwebsite.com/some-category/feed
    add_rewrite_rule('^([^/]+)/feed/?$', 'index.php?category_name=' . $wp_rewrite->preg_index(1) . '&feed=rss2', 'top');

    // Для URL вида https://yourwebsite.com/some-category/feed/rss2 или /feed/atom и т.д.
    add_rewrite_rule('^([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?category_name=' . $wp_rewrite->preg_index(1) . '&feed=' . $wp_rewrite->preg_index(2), 'top');
    
    // Для тегов
    add_rewrite_rule('^([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?tag=' . $wp_rewrite->preg_index(1) . '&feed=' . $wp_rewrite->preg_index(2), 'top');
}
add_action('init', 'custom_rewrite_rules');




//убираем посказки в гутенберге

function custom_admin_styles() {
    echo '<style>
        .components-tooltip {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'custom_admin_styles');

function my_custom_gutenberg_styles() {
    wp_enqueue_style( 'my-custom-gutenberg-styles', get_theme_file_uri( 'style.css' ), false, '1.0', 'all' );
}
add_action( 'enqueue_block_editor_assets', 'my_custom_gutenberg_styles' );*/

/*// Настройки Heartbeat API

add_action('init', 'stop_heartbeat', 1);
function stop_heartbeat() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-admin/edit.php') === false) { // разрешить только на страницах редактирования записей
        wp_deregister_script('heartbeat');
    }
}

// Увеличиваем интервал Heartbeat до 60 секунд
add_filter('heartbeat_settings', 'change_heartbeat_settings');
function change_heartbeat_settings($settings) {
    $settings['interval'] = 60; // значение в секундах
    return $settings;
}

add_action('wpmu_new_user', 'add_new_user_to_all_sites');

function add_new_user_to_all_sites(int $user_id): void {
    foreach (get_sites() as $site) {
        add_user_to_blog($site->blog_id, $user_id, 'subscriber');
    }
}


//////////////////////////

//Убираем категории из ссылок в тексте через WP-CLI
//wp search-replace 'https:\/\/piteronline\.tv\/(?!wp-content\/uploads\/|wp-admin\/|wp-includes\/)[^\/<>]+\/([^\"]+)' 'https://piteronline.tv/$1/' --regex --include-columns=post_content


function remove_version() {
					return '';
				}
			add_filter('the_generator', 'remove_version');
			
add_filter('wp_is_application_passwords_available', '__return_false');

Remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
Remove_action('wp_head', 'rest_output_link_wp_head');
Remove_action('template_redirect', 'rest_output_link_header', 11, 0);*/

// Подсветка двойных пробелов в редакторе gutenberg

//function my_custom_gutenberg_scripts() {
//    wp_enqueue_script('my-custom-gutenberg-script', get_stylesheet_directory_uri() . '/js/custom-gutenberg.js', array('wp-blocks', 'wp-element', 'wp-data', 'wp-compose'), true);
//}
//add_action('enqueue_block_editor_assets', 'my_custom_gutenberg_scripts');








