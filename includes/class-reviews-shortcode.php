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
        
        // Output Schema.org markup
        $this->output_schema_markup($reviews, $data);
        
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
     * Output Schema.org JSON-LD markup for rich snippets
     * 
     * @param array $reviews
     * @param array $data
     */
    private function output_schema_markup($reviews, $data) {
        $business_name = get_option('reviews_plugin_business_name', '');
        $business_type = get_option('reviews_plugin_business_type', 'LocalBusiness');
        
        // Don't output schema if business name is not set
        if (empty($business_name)) {
            return;
        }
        
        // Calculate aggregate rating
        $total_rating = 0;
        $review_count = count($reviews);
        
        foreach ($reviews as $review) {
            $total_rating += floatval($review['rating']);
        }
        
        $average_rating = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;
        
        // Build schema data
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $business_type,
            'name' => $business_name,
            'aggregateRating' => array(
                '@type' => 'AggregateRating',
                'ratingValue' => $average_rating,
                'reviewCount' => $review_count,
                'bestRating' => '5',
                'worstRating' => '1'
            ),
            'review' => array()
        );
        
        // Add individual reviews to schema
        foreach ($reviews as $review) {
            $review_schema = array(
                '@type' => 'Review',
                'author' => array(
                    '@type' => 'Person',
                    'name' => isset($review['author_name']) ? $review['author_name'] : 'Anonymous'
                ),
                'reviewRating' => array(
                    '@type' => 'Rating',
                    'ratingValue' => $review['rating'],
                    'bestRating' => '5',
                    'worstRating' => '1'
                ),
                'datePublished' => date('Y-m-d', $review['time']),
                'reviewBody' => isset($review['text']) ? wp_trim_words($review['text'], 50) : ''
            );
            
            $schema['review'][] = $review_schema;
        }
        
        // Output JSON-LD
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
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
