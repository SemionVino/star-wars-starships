<?php

/**
 * Plugin Name: Star Wars Starships
 * Plugin URI: https://github.com/SemionVino/star-wars-starships
 * Description: A WP plugin to  list all the star wars ship in whatever page you choose :)
 * Version: 1.0.0
 * Author: Semion Vinogradov
 * Author URI: https://github.com/SemionVino/star-wars-starships
 * License: GPL2
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Constants and Definitions
define('SWS_VERSION', '1.0.0');

// Include Dependencies
include plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
include plugin_dir_path(__FILE__) . 'includes/display-functions.php';


/**
 * Delete the 'sws_selected_page' option from the database.
 */
function sws_deactivation()
{
    delete_option('sws_selected_page');
}

register_deactivation_hook(__FILE__, 'sws_deactivation');
