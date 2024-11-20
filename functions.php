<?php
// Include the Bootstrap Navwalker class
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// Enqueue styles and scripts
function snake_enqueue_styles() {
    // Deregister the default jQuery
    wp_deregister_script('jquery');

      // Enqueue jQuery from CDN with high priority
      wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', [], null, false);

    // Enqueue styles
    wp_enqueue_style('remix-icons', 'https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css');
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('main-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('tailwindcss', get_template_directory_uri() . '/assets/css/tailwind.css', array(), null);

    // Enqueue scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('admin-ajax-url', get_template_directory_uri() . '/assets/js/ajax.js', ['jquery'], null, true);
    wp_localize_script('admin-ajax-url', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'), // URL for AJAX requests
    ));

    wp_enqueue_script('custom-script1', get_template_directory_uri() . '/assets/js/script.js', ['jquery'], null, true);
    wp_enqueue_script('static-js', get_template_directory_uri() . '/assets/js/static.js', ['jquery'], null, true);
    wp_localize_script('static', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'), // URL for AJAX requests
    ));
    wp_enqueue_script('custom-slider-init', get_template_directory_uri() . '/assets/js/slider-init.js', ['jquery', 'bootstrap-js'], null, true);

    // Initialize the carousel if needed
    // echo '<script>document.addEventListener("DOMContentLoaded", function() { var myCarousel = document.querySelector("#carouselExample"); var carousel = new bootstrap.Carousel(myCarousel); });</script>';
}
add_action('wp_enqueue_scripts', 'snake_enqueue_styles');



// Register custom navigation menus
function register_my_menus() {
    register_nav_menus(
        array(
            'snake' => __( 'Snake Menu' ),
            // Add more menu locations as needed
        )
    );
}
add_action( 'init', 'register_my_menus' );

// Custom Walker Class
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $current_object_id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'nav__item';

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

        $icon = '<i class="arrowIcon ri-arrow-right-up-line"></i>';

        // Ensure $args is an object
        if (!is_object($args)) {
            $args = (object) array('before' => '', 'after' => '');
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>' . $icon . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
        
        if ($has_submenu) {
            $item_output .= ' <i class="ri-arrow-down-s-line dropdown-icon"></i>';
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



//  home page post

add_action('wp_ajax_get_snake_images', 'get_snake_images');
add_action('wp_ajax_nopriv_get_snake_images', 'get_snake_images');

function get_snake_images() {
    global $wpdb;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $title = isset($data['title']) ? sanitize_text_field($data['title']) : '';

        $args = [
            'post_type' => 'post',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'category_name' => ($title) 
        ];

        $query = new WP_Query($args);
        $posts = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $thumbnail_id = get_post_thumbnail_id();
                $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                
                // Get snake_name from venomous_creatures table
                $snake_data = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT snake_name 
                         FROM {$wpdb->prefix}venomous_creatures 
                         WHERE post_id = %d",
                        $post_id
                    )
                );

                /$snake_name = $snake_data ? $snake_data->snake_name : get_the_title();
    
                $posts[] = [
                    'imageUrl' => get_the_post_thumbnail_url($post_id, 'full') ?: 'default-image.jpg',
                    'imageAlt' => $alt_text,
                    'permalink' => get_permalink(),
                    'post_id' => $post_id
                    'name' => $snake_name // Using snake_name from the custom table
                ];
            }

            wp_send_json(array(
                'data' => $posts
            ));
        } else {
            wp_send_json_error('No posts found.');
        }
    } else {
        wp_send_json_error('Invalid request method.');
    }

    wp_die();
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
        snake_name text NOT NULL,
        description text NOT NULL,
        danger_level text NOT NULL,
        temperament text NOT NULL,
        size_range text NOT NULL,
        habitat text NOT NULL,
        lifespan text NOT NULL,
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
    $data = $wpdb->get_row($wpdb->prepare("SELECT snake_name, description, danger_level, temperament, size_range, habitat, lifespan FROM $table_name WHERE post_id = %d", $post->ID));

    $snake_name = $data ? $data->snake_name : '';
    $description = $data ? $data->description : '';
    $danger_level = $data ? $data->danger_level : '';
    $temperament = $data ? $data->temperament : '';
    $size_range = $data ? $data->size_range : '';
    $habitat = $data ? $data->habitat : '';
    $lifespan = $data ? $data->lifespan : '';

    // Simple input for Snake Name
    echo '<label for="snake_name"><strong>Snake Name:</strong></label>';
    echo '<input type="text" name="snake_name" id="snake_name" value="' . esc_attr($snake_name) . '" style="width: 100%;">';

    // Quill editor for Description
    echo '<label for="description_editor"><strong>Description:</strong></label>';
    echo '<div id="description_editor" style="height: 200px;">' . wp_kses_post($description) . '</div>';
    echo '<input type="hidden" name="description" id="description" value="' . esc_attr($description) . '">';

    // New fields for additional data with Quill editors
    echo '<label for="danger_level_editor"><strong>Danger Level:</strong></label>';
    echo '<div id="danger_level_editor" style="height: 200px;">' . wp_kses_post($danger_level) . '</div>';
    echo '<input type="hidden" name="danger_level" id="danger_level" value="' . esc_attr($danger_level) . '">';

    echo '<label for="temperament_editor"><strong>Temperament:</strong></label>';
    echo '<div id="temperament_editor" style="height: 200px;">' . wp_kses_post($temperament) . '</div>';
    echo '<input type="hidden" name="temperament" id="temperament" value="' . esc_attr($temperament) . '">';

    echo '<label for="size_range_editor"><strong>Size Range:</strong></label>';
    echo '<div id="size_range_editor" style="height: 200px;">' . wp_kses_post($size_range) . '</div>';
    echo '<input type="hidden" name="size_range" id="size_range" value="' . esc_attr($size_range) . '">';

    echo '<label for="habitat_editor"><strong>Habitat:</strong></label>';
    echo '<div id="habitat_editor" style="height: 200px;">' . wp_kses_post($habitat) . '</div>';
    echo '<input type="hidden" name="habitat" id="habitat" value="' . esc_attr($habitat) . '">';

    echo '<label for="lifespan_editor"><strong>Lifespan:</strong></label>';
    echo '<div id="lifespan_editor" style="height: 200px;">' . wp_kses_post($lifespan) . '</div>';
    echo '<input type="hidden" name="lifespan" id="lifespan" value="' . esc_attr($lifespan) . '">';
}

// Save the custom meta box data
function save_custom_meta_box_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['venomous_meta_nonce']) || !wp_verify_nonce($_POST['venomous_meta_nonce'], 'save_venomous_meta')) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'venomous_creatures';

    $snake_name = isset($_POST['snake_name']) ? wp_kses_post($_POST['snake_name']) : '';
    $description = isset($_POST['description']) ? wp_kses_post($_POST['description']) : '';
    $danger_level = isset($_POST['danger_level']) ? wp_kses_post($_POST['danger_level']) : '';
    $temperament = isset($_POST['temperament']) ? wp_kses_post($_POST['temperament']) : '';
    $size_range = isset($_POST['size_range']) ? wp_kses_post($_POST['size_range']) : '';
    $habitat = isset($_POST['habitat']) ? wp_kses_post($_POST['habitat']) : '';
    $lifespan = isset($_POST['lifespan']) ? wp_kses_post($_POST['lifespan']) : '';

    // Check if a row already exists
    $existing_entry = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE post_id = %d", $post_id));

    if ($existing_entry) {
        // Update the existing row
        $wpdb->update(
            $table_name,
            [
                'snake_name' => $snake_name,
                'description' => $description,
                'danger_level' => $danger_level,
                'temperament' => $temperament,
                'size_range' => $size_range,
                'habitat' => $habitat,
                'lifespan' => $lifespan
            ],
            ['post_id' => $post_id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );
    } else {
        // Insert a new row
        $wpdb->insert(
            $table_name,
            [
                'post_id' => $post_id,
                'snake_name' => $snake_name,
                'description' => $description,
                'danger_level' => $danger_level,
                'temperament' => $temperament,
                'size_range' => $size_range,
                'habitat' => $habitat,
                'lifespan' => $lifespan
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
    }
}

add_action('save_post', 'save_custom_meta_box_data');

// Get Venomous Data
function get_venomous_data($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'venomous_creatures';

    $result = $wpdb->get_row($wpdb->prepare("SELECT snake_name, description, danger_level, temperament, size_range, habitat, lifespan FROM $table_name WHERE post_id = %d", $post_id));

    return $result ? [
        'snake_name' => $result->snake_name,
        'description' => $result->description,
        'danger_level' => $result->danger_level,
        'temperament' => $result->temperament,
        'size_range' => $result->size_range,
        'habitat' => $result->habitat,
        'lifespan' => $result->lifespan
    ] : null;
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