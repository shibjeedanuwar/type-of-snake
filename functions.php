<?php
// Include the Bootstrap Navwalker class
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// Enqueue styles and scripts
function snake_enqueue_styles() {
    // Deregister the default jQuery
    wp_deregister_script('jquery');

    // Enqueue jQuery from a CDN
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', [], null, true);

    // Enqueue styles
    wp_enqueue_style('remix-icons', 'https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css');
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('main-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/main.css');

    // Enqueue scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('admin-ajax-url', get_template_directory_uri() . '/assets/js/ajax.js', ['jquery'], null, true);

    // Localize the AJAX URL
    wp_localize_script('admin-ajax-url', 'my_ajax_obj', ['ajax_url' => admin_url('admin-ajax.php')]);

    // Custom scripts
    wp_enqueue_script('custom-script1', get_template_directory_uri() . '/assets/js/script.js', ['jquery'], null, true);
    wp_enqueue_script('custom-slider-init', get_template_directory_uri() . '/assets/js/slider-init.js', ['jquery', 'bootstrap-js'], null, true);
}
add_action('wp_enqueue_scripts', 'snake_enqueue_styles');

// Register Menus
function snake_register_menus() {
    register_nav_menus([
        'snake' => __('Snake Menu', 'type-of-snake'),
    ]);
}
add_action('init', 'snake_register_menus');

// Custom Walker Class
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $current_object_id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'nav__item';

        // Check if this item has a submenu
        $has_submenu = !empty($item->classes) && in_array('menu-item-has-children', $item->classes);
        if ($has_submenu) {
            $classes[] = 'has-submenu';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = [
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
            'href'   => !empty($item->url) ? $item->url : '',
            'class'  => 'nav__link',
        ];

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // Add icon to the link
        $icon = '<i class="arrowIcon ri-arrow-right-up-line"></i>';
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>' . $icon . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
        
        // Add dropdown icon on the right side if it has a submenu
        if ($has_submenu) {
            $item_output .= ' <i class="ri-arrow-down-s-line dropdown-icon"></i>'; // Right-side dropdown icon
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"nav__submenu\">\n"; // Add class for submenu
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n"; // Close submenu
    }
}

// Pagination Code
add_action('wp_ajax_load_posts', 'load_posts_callback');
add_action('wp_ajax_nopriv_load_posts', 'load_posts_callback');

function load_posts_callback() {
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];
    $current_page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 9;

    // Query arguments
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $current_page,
    ];

    if (in_array('uncategorized', $categories)) {
        $args['tax_query'] = [[
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => 'uncategorized',
            'operator' => 'NOT IN',
        ]];
        $args['orderby'] = 'rand';
    } else {
        if (!empty($categories)) {
            $args['category_name'] = implode(',', $categories);
        }
    }

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $thumbnail_id = get_post_thumbnail_id(); // Get the thumbnail ID
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); // Get alt text

            $posts[] = [
                'title' => get_the_title(),
                'imageUrl' => get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'default-image.jpg',
                'imageAlt' => $alt_text, // Include alt text
                'postDesc' => get_the_excerpt(),
                'permalink' => get_permalink(),
                'categories' => wp_get_post_categories(get_the_ID(), ['fields' => 'names']),
                'date' => get_the_date('F j, Y'), // Add post date
            ];
        }
        wp_reset_postdata();
    }

    echo json_encode(['posts' => $posts, 'total' => $query->found_posts]);
    wp_die();
}

// Custom Comment Box
function my_custom_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields', 'my_custom_comment_fields');

// Create Custom Table for Venomous Creatures
function create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'venomous_creatures';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL UNIQUE,
        venomous text NOT NULL,
        antibiotic text NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_custom_table');

// Add Meta Box
function add_custom_meta_boxes() {
    add_meta_box(
        'venomous_meta',
        'Venomous and Antibiotic',
        'render_custom_meta_box',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_boxes');

// Enqueue Quill Editor Scripts and Styles
function enqueue_quill_editor() {
    wp_enqueue_script('quill-js', 'https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js', [], null, true);
    wp_enqueue_style('quill-css', 'https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css');
    wp_enqueue_script('quill-init', get_template_directory_uri() . '/assets/js/quill-init.js', ['quill-js'], null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_quill_editor');

// Render the Meta Box
function render_custom_meta_box($post) {
    global $wpdb;

    wp_nonce_field('save_venomous_meta', 'venomous_meta_nonce');

    $table_name = $wpdb->prefix . 'venomous_creatures';
    $data = $wpdb->get_row($wpdb->prepare("SELECT venomous, antibiotic FROM $table_name WHERE post_id = %d", $post->ID));

    $venomous = $data ? $data->venomous : '';
    $antibiotic = $data ? $data->antibiotic : '';

    echo '<label for="venomous_editor"><strong>Venomous:</strong></label>';
    echo '<div id="venomous_editor" style="height: 200px;">' . wp_kses_post($venomous) . '</div>';
    echo '<label for="antibiotic_editor"><strong>Antibiotic:</strong></label>';
    echo '<div id="antibiotic_editor" style="height: 200px;">' . wp_kses_post($antibiotic) . '</div>';
    echo '<input type="hidden" name="venomous" id="venomous" value="' . esc_attr($venomous) . '">';
    echo '<input type="hidden" name="antibiotic" id="antibiotic" value="' . esc_attr($antibiotic) . '">';
}



function save_custom_meta_box_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['venomous_meta_nonce']) || !wp_verify_nonce($_POST['venomous_meta_nonce'], 'save_venomous_meta')) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'venomous_creatures';

    $venomous = isset($_POST['venomous']) ? wp_kses_post($_POST['venomous']) : '';
    $antibiotic = isset($_POST['antibiotic']) ? wp_kses_post($_POST['antibiotic']) : '';

    // Check if a row already exists
    $existing_entry = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE post_id = %d", $post_id));

    if ($existing_entry) {
        // Update the existing row
        $wpdb->update(
            $table_name,
            ['venomous' => $venomous, 'antibiotic' => $antibiotic],
            ['post_id' => $post_id],
            ['%s', '%s'],
            ['%d']
        );
    } else {
        // Insert a new row
        $wpdb->insert(
            $table_name,
            ['post_id' => $post_id, 'venomous' => $venomous, 'antibiotic' => $antibiotic],
            ['%d', '%s', '%s']
        );
    }
}

add_action('save_post', 'save_custom_meta_box_data');

// Get Venomous Data
function get_venomous_data($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'venomous_creatures';

    $result = $wpdb->get_row($wpdb->prepare("SELECT venomous, antibiotic FROM $table_name WHERE post_id = %d", $post_id));

    return $result ? ['venomous' => $result->venomous, 'antibiotic' => $result->antibiotic] : null;
}



// Customizer settings for social media links
function mytheme_customize_register($wp_customize) {
    // Add a section for Social Media Accounts
    $wp_customize->add_section('social_media_section', array(
        'title'    => __('Social Network Accounts', 'mytheme'),
        'priority' => 30,
    ));

    // List of social media platforms
    $social_media = array('facebook' => 'Facebook', 'twitter' => 'Twitter', 'instagram' => 'Instagram', 'pinterest' => 'Pinterest', 'reddit' => 'Reddit');

    foreach ($social_media as $key => $platform) {
        $wp_customize->add_setting("{$key}_url", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("{$key}_url_control", array(
            'label'   => __("$platform URL", 'mytheme'),
            'section' => 'social_media_section',
            'settings' => "{$key}_url",
            'type'    => 'url',
        ));
    }
}

add_action('customize_register', 'mytheme_customize_register');

// Site identity settings
function type_of_snake_customize_register($wp_customize) {
    // Add Site Identity section
    $wp_customize->add_section('title_tagline', array(
        'title'    => __('Site Identity', 'type-of-snake'),
        'priority' => 30,
    ));

    // Site Title
    $wp_customize->add_setting('blogname', array(
        'default'           => 'Snake Blog',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('blogname', array(
        'label'   => __('Site Title', 'type-of-snake'),
        'section' => 'title_tagline',
        'type'    => 'text',
    ));

    // Tagline
    $wp_customize->add_setting('blogdescription', array(
        'default'           => 'Explore diverse snake species, their characteristics, and habitats. Your go-to resource for serpent enthusiasts and curious minds.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('blogdescription', array(
        'label'   => __('Tagline', 'type-of-snake'),
        'section' => 'title_tagline',
        'type'    => 'textarea',
    ));

    // Site Icon
    $wp_customize->add_setting('site_icon', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_icon', array(
        'label'   => __('Site Icon', 'type-of-snake'),
        'section' => 'title_tagline',
        'settings' => 'site_icon',
    )));
}

add_action('customize_register', 'type_of_snake_customize_register');

// Theme setup function
function type_of_snake_setup() {
    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
    add_theme_support('custom-header', array(
        'width'         => 1000,
        'height'        => 250,
        'flex-height'   => true,
        'flex-width'    => true,
    ));
}

add_action('after_setup_theme', 'type_of_snake_setup');

// Redirect all category pages to home
function redirect_all_categories_to_home() {
    if (is_category()) {
        wp_redirect(home_url(), 301);
        exit();
    }
}

add_action('template_redirect', 'redirect_all_categories_to_home');
?>