<?php
/**
 * Plugin Name: MG Recipe Manager
 * Plugin URI: https://github.com/mastergraphiks/mg-recipe-manager
 * Description: Create and manage recipes with a custom post type and display random recipes using a shortcode.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Author: Syed Ali
 * Author URI: https://mastergraphiks.no
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 04_23-11-57_mg-recipe-manager
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MG_RECIPE_MANAGER_VERSION', '1.0.0');
define('MG_RECIPE_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MG_RECIPE_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once MG_RECIPE_MANAGER_PLUGIN_DIR . 'includes/class-mg-recipe-post-type.php';
require_once MG_RECIPE_MANAGER_PLUGIN_DIR . 'includes/class-mg-recipe-shortcode.php';
require_once MG_RECIPE_MANAGER_PLUGIN_DIR . 'admin/class-mg-recipe-admin.php';

// Initialize the plugin
function mg_recipe_manager_init() {
    new MG_Recipe_Post_Type();
    new MG_Recipe_Shortcode();
    new MG_Recipe_Admin();
}
add_action('plugins_loaded', 'mg_recipe_manager_init');

// Activation hook
register_activation_hook(__FILE__, 'mg_recipe_manager_activate');

function mg_recipe_manager_activate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'mg_recipe_manager_deactivate');

function mg_recipe_manager_deactivate() {
    // Clean up if needed
}

// Enqueue scripts and styles
function mg_recipe_manager_enqueue_scripts() {
    wp_enqueue_style('mg-recipe-manager-style', MG_RECIPE_MANAGER_PLUGIN_URL . 'assets/css/mg-recipe-manager.css', array(), MG_RECIPE_MANAGER_VERSION);
    wp_enqueue_script('mg-recipe-manager-script', MG_RECIPE_MANAGER_PLUGIN_URL . 'assets/js/mg-recipe-manager.js', array('jquery'), MG_RECIPE_MANAGER_VERSION, true);
}
add_action('wp_enqueue_scripts', 'mg_recipe_manager_enqueue_scripts');

// Custom template for single recipe
function mg_recipe_manager_template_include($template) {
    if (is_singular('recipe')) {
        $custom_template = MG_RECIPE_MANAGER_PLUGIN_DIR . 'templates/single-recipe.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'mg_recipe_manager_template_include');