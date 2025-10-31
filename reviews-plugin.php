<?php
/**
 * Plugin Name: Reviews Plugin
 * Plugin URI: https://github.com/elyasoft-bt/Reviews-Plugin
 * Description: Google Business yorumlarını WordPress sitenize entegre edin. TrustIndex benzeri modern görünüm.
 * Version: 1.0.0
 * Author: Elyasoft
 * Author URI: https://github.com/elyasoft-bt
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reviews-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('REVIEWS_PLUGIN_VERSION', '1.0.0');
define('REVIEWS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('REVIEWS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once REVIEWS_PLUGIN_PATH . 'includes/class-reviews-admin.php';
require_once REVIEWS_PLUGIN_PATH . 'includes/class-reviews-api.php';
require_once REVIEWS_PLUGIN_PATH . 'includes/class-reviews-shortcode.php';

// Initialize the plugin
function reviews_plugin_init() {
    // Initialize admin panel
    if (is_admin()) {
        new Reviews_Admin();
    }
    
    // Initialize shortcode
    new Reviews_Shortcode();
}
add_action('plugins_loaded', 'reviews_plugin_init');

// Activation hook
register_activation_hook(__FILE__, 'reviews_plugin_activate');
function reviews_plugin_activate() {
    // Set default options
    add_option('reviews_plugin_api_key', '');
    add_option('reviews_plugin_place_id', '');
    add_option('reviews_plugin_cache_duration', '24');
    add_option('reviews_plugin_max_reviews', '10');
    add_option('reviews_plugin_min_rating', '1');
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'reviews_plugin_deactivate');
function reviews_plugin_deactivate() {
    // Clean up transients
    delete_transient('reviews_plugin_cached_data');
}

// Enqueue styles and scripts
add_action('wp_enqueue_scripts', 'reviews_plugin_enqueue_assets');
function reviews_plugin_enqueue_assets() {
    wp_enqueue_style('reviews-plugin-styles', REVIEWS_PLUGIN_URL . 'assets/css/reviews-styles.css', array(), REVIEWS_PLUGIN_VERSION);
    wp_enqueue_script('reviews-plugin-script', REVIEWS_PLUGIN_URL . 'assets/js/reviews-script.js', array('jquery'), REVIEWS_PLUGIN_VERSION, true);
    
    // Enqueue Swiper for slider
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
}