<?php

if (!defined('ABSPATH')) {
    exit;
}

class SWS_Admin_Settings
{

    /**
     * Constructor: Register hooks and filters.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_sws_save_settings', array($this, 'save_settings'));
        add_action('admin_bar_menu', array($this, 'sws_add_to_toolbar'), 999);
    }

    /**
     * Add the settings page to the WordPress admin menu.
     */
    public function add_settings_page()
    {
        add_menu_page(
            'Star Wars Starships Settings',
            'Star Wars Starships',
            'manage_options',
            'sws-settings',
            array($this, 'render_settings_page'),
            'none',
            3
        );
    }

    /**
     * Render the content of the settings page.
     */
    public function render_settings_page()
    {
        include plugin_dir_path(__FILE__) . '/admin-settings-view.php';
    }

    /**
     * Register the plugin settings and add settings fields and sections.
     */
    public function register_settings()
    {
        register_setting('sws_settings_group', 'sws_selected_page');
        add_settings_section('sws_main_section', '', '', 'sws-settings');
        add_settings_field('sws_page_dropdown', 'Choose a page to diplay the starships table', array($this, 'render_page_dropdown'), 'sws-settings', 'sws_main_section');
    }

    /**
     * Render a dropdown containing a list of pages.
     */
    public function render_page_dropdown()
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
     * Enqueue admin-specific styles and scripts.
     */
    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('sws-admin-styles', plugin_dir_url(__FILE__) . '../assets/css/sws-admin-styles.css');
        $screen = get_current_screen();
        if ($screen->id == 'toplevel_page_sws-settings') {
            wp_enqueue_script('sws-admin-scripts', plugin_dir_url(__FILE__) . '../assets/js/admin-scripts.js', array('jquery'), '1.0.0', true);
            wp_localize_script('sws-admin-scripts', 'sws_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('sws_nonce')));
        }
    }

    /**
     * Handle AJAX request to save plugin settings.
     */
    public function save_settings()
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
    /**
     * Add a link to the plugin's settings on the admin toolbar
     */
    public function sws_add_to_toolbar($wp_admin_bar)
    {
        $wp_admin_bar->add_node(array(
            'id'    => 'sws_toolbar_link',
            'title' => '<img src="' . plugin_dir_url(__FILE__) . '../assets/images/stormtrooper.png" class="sws-toolbar-logo"> Star Wars Starships',
            'href'  => admin_url('admin.php?page=sws-settings'),
            'meta'  => array(
                'title' => 'Star Wars Starships Settings',
                'class' => 'sws-toolbar-page'
            )
        ));
    }
}

// Instantiate the class
$sws_admin_settings = new SWS_Admin_Settings();
