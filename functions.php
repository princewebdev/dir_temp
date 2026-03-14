<?php
/**
 * OneListing Pro Child Theme Functions
 */

// Enqueue parent and child theme styles and scripts
function enqueue_the_reporter_child() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        'onelisting-pro-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Enqueue child theme custom CSS (assets/css/styles.css)
    wp_enqueue_style(
        'onelisting-pro-child-custom-style',
        get_stylesheet_directory_uri() . '/assests/css/styles.css',
        array('onelisting-pro-parent-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue child theme main stylesheet (style.css)
    wp_enqueue_style(
        'onelisting-pro-child-style',
        get_stylesheet_uri(),
        array('onelisting-pro-parent-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue child theme JS (assets/js/main.js)
    wp_enqueue_script(
        'onelisting-pro-child-script',
        get_stylesheet_directory_uri() . '/assests/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_the_reporter_child');




