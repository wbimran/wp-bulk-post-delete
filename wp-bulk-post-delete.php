<?php
/**
 * Plugin Name: Wp Bulk Post Delete
 * Description: Adds a bulk action to permanently delete posts from the All Posts admin screen.
 * Version: 1.0
 * Author: Mohammad Imran
 * Author URI:        https://github.com/wbimran/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-bulk-post-delete
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'includes/class-wp-bulk-post-delete-admin.php';


/**
 * Initialize the WP Bulk Post Delete plugin.
 *
 * Sets up the plugin's main admin handler on 'plugins_loaded' hook.
 */
function mi_wbpd_init_plugin() {
    $handler = new Wp_Bulk_Post_Delete_Admin();
    $handler->init();
}
add_action('plugins_loaded', 'mi_wbpd_init_plugin');
