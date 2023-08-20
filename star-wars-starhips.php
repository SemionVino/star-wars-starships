<?php

/**
 * Plugin Name: Star Wars Starships
 * Plugin URI: https://github.com/SemionVino/star-wars-starships
 * Description: A WP plugin to show list all the star wars ship in your admin panel :)
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


