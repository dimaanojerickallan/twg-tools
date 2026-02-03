<?php
/**
 * Plugin Name: TWG Tools
 * Description: Internal development tools for TWG websites.
 * Version: 1.0.0
 * Author: Jerick Allan Dimaano
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TWG_TOOLS_PATH', plugin_dir_path( __FILE__ ) );
define( 'TWG_TOOLS_URL', plugin_dir_url( __FILE__ ) );

// Load core files
require_once TWG_TOOLS_PATH . 'includes/settings.php';
require_once TWG_TOOLS_PATH . 'includes/admin-menu.php';
require_once TWG_TOOLS_PATH . 'includes/image-optimizer.php';
require_once TWG_TOOLS_PATH . 'includes/global-contact-info.php';
require_once TWG_TOOLS_PATH . 'includes/login-design.php';

// Disable theme & plugin auto-updates
add_filter('auto_update_theme', '__return_false');
add_filter('auto_update_plugin', '__return_false');

// Disable WordPress core auto-updates
add_filter('allow_major_auto_core_updates', '__return_false');
add_filter('allow_minor_auto_core_updates', '__return_false');
add_filter('allow_dev_auto_core_updates', '__return_false');

// Disable theme & plugin editor
add_action('admin_menu', function () {
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('plugins.php', 'plugin-editor.php');
}, 999);

add_action('admin_init', function () {
    if ( ! is_admin() ) {
        return;
    }

    $pagenow = $GLOBALS['pagenow'] ?? '';

    if ( in_array($pagenow, ['theme-editor.php', 'plugin-editor.php'], true) ) {
        wp_die(
            __('File editing has been disabled on this site.'),
            __('Access denied'),
            ['response' => 403]
        );
    }
});
