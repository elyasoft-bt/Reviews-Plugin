<?php
/**
 * Shortcode Handler Class
 * 
 * @package Reviews_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Shortcode {
    
    public function __construct() {
        add_shortcode('google_reviews', array($this, 'render_shortcode'));
    }
    
    /**
     * Render shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'limit' => get_option('reviews_plugin_max_reviews', '10'),
                'style' => 'slider',
                'min_rating' => get_option('reviews_plugin_min_rating', '1'),
            ),
            $atts,
            'google_reviews'
        );
        
        // Get reviews
        $api = new Reviews_API();
        $data = $api->get_reviews();
        
        if (is_wp_error($data)) {
            return '<div class="reviews-error">' . esc_html($data->get_error_message()) . '</div>';
        }
        
        $reviews = isset($data['reviews']) ? $data['reviews'] : array();
        
        // Filter by minimum rating
        $reviews = $api->filter_reviews($reviews, intval($atts['min_rating']));
        
        // Limit reviews
        $reviews = array_slice($reviews, 0, intval($atts['limit']));
        
        if (empty($reviews)) {
            return '<div class="reviews-empty">' . __('Henüz yorum bulunmuyor.', 'reviews-plugin') . '</div>';
        }
        
        // Get template based on style
        ob_start();
        
        switch ($atts['style']) {
            case 'grid':
                $this->render_grid_view($reviews, $data);
                break;
            case 'list':
                $this->render_list_view($reviews, $data);
                break;
            case 'slider':
            default:
                $this->render_slider_view($reviews, $data);
                break;
        }
        
        return ob_get_clean();
    }
    
    /**
     * Render slider view
     * 
     * @param array $reviews
     * @param array $data
     */
    private function render_slider_view($reviews, $data) {
        include REVIEWS_PLUGIN_PATH . 'templates/slider-view.php';
    }
    
    /**
     * Render grid view
     * 
     * @param array $reviews
     * @param array $data
     */
    private function render_grid_view($reviews, $data) {
        include REVIEWS_PLUGIN_PATH . 'templates/grid-view.php';
    }
    
    /**
     * Render list view
     * 
     * @param array $reviews
     * @param array $data
     */
    private function render_list_view($reviews, $data) {
        include REVIEWS_PLUGIN_PATH . 'templates/list-view.php';
    }
}