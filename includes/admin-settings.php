<?php
if (!defined('ABSPATH')) {
    exit;
}


function sws_add_settings_page()
{
    add_menu_page(
        'Star Wars Starships Settings',
        'Star Wars Starships',
        'manage_options',
        'sws-settings',
        'sws_render_settings_page',
        'dashicons-star-filled',
        3
    );
}
add_action('admin_menu', 'sws_add_settings_page');



function sws_render_settings_page()
{
    include plugin_dir_path(__FILE__) . '/admin-settings-view.php';
}



/**
 * Register the settings, create a dropdown of pages.
 */
function sws_register_settings()
{
    register_setting(
        'sws_settings_group',          // Settings group
        'sws_selected_page'            // Option name
    );

    add_settings_section(
        'sws_main_section',            // Section ID
        'Main Settings',               // Section title
        '',                            // Callback function for section description (we don't need one in this case)
        'sws-settings'                 // Menu slug
    );

    add_settings_field(
        'sws_page_dropdown',           // Field ID
        'Select a page',               // Field title/label
        'sws_render_page_dropdown',    // Callback function to render the field
        'sws-settings',                // Menu slug
        'sws_main_section'             // Section ID
    );
}
add_action('admin_init', 'sws_register_settings');

/**
 * Render the dropdown of pages.
 */
function sws_render_page_dropdown()
{
    $selected_page = get_option('sws_selected_page');
    $pages = get_pages();
    echo '<select name="sws_selected_page">';
    foreach ($pages as $page) {
        echo '<option value="' . esc_attr($page->ID) . '" ' . selected($selected_page, $page->ID, false) . '>' . esc_html($page->post_title) . '</option>';
    }
    echo '</select>';
}

/**
 * Enqueue admin scripts.
 */
function sws_enqueue_admin_scripts()
{
    $screen = get_current_screen();
    if ($screen->id == 'toplevel_page_sws-settings') {
        wp_enqueue_script(
            'sws-admin-scripts',
            plugin_dir_url(__FILE__) . '../assets/js/admin-scripts.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script(
            'sws-admin-scripts',
            'sws_ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('sws_nonce')
            )
        );
    }
}
add_action('admin_enqueue_scripts', 'sws_enqueue_admin_scripts');

function sws_save_settings()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sws_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
    }
    parse_str($_POST['data'], $settings_data);

    if (isset($settings_data['sws_selected_page'])) {
        update_option('sws_selected_page', sanitize_text_field($settings_data['sws_selected_page']));
    }

    wp_send_json_success(array('message' => 'Settings saved successfully.'));
}
add_action('wp_ajax_sws_save_settings', 'sws_save_settings');
