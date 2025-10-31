<?php
/**
 * Google Places API Class
 * 
 * @package Reviews_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Reviews_API {
    
    private $api_key;
    private $place_id;
    private $cache_duration;
    
    public function __construct() {
        $this->api_key = get_option('reviews_plugin_api_key', '');
        $this->place_id = get_option('reviews_plugin_place_id', '');
        $this->cache_duration = get_option('reviews_plugin_cache_duration', '24');
    }
    
    /**
     * Get reviews from Google Places API
     * 
     * @return array|WP_Error
     */
    public function get_reviews() {
        // Check if API key and Place ID are set
        if (empty($this->api_key) || empty($this->place_id)) {
            return new WP_Error('missing_credentials', __('API Key veya Place ID eksik. Lütfen ayarları kontrol edin.', 'reviews-plugin'));
        }
        
        // Check cache first
        $cached_data = get_transient('reviews_plugin_cached_data');
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        // Fetch from API
        $reviews = $this->fetch_from_api();
        
        if (is_wp_error($reviews)) {
            return $reviews;
        }
        
        // Cache the results
        $cache_seconds = intval($this->cache_duration) * HOUR_IN_SECONDS;
        set_transient('reviews_plugin_cached_data', $reviews, $cache_seconds);
        
        return $reviews;
    }
    
    /**
     * Fetch reviews from Google Places API
     * 
     * @return array|WP_Error
     */
    private function fetch_from_api() {
        $url = add_query_arg(
            array(
                'place_id' => $this->place_id,
                'key' => $this->api_key,
                'fields' => 'name,rating,reviews,user_ratings_total',
                'language' => 'tr'
            ),
            'https://maps.googleapis.com/maps/api/place/details/json'
        );
        
        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'sslverify' => true
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!isset($data['status']) || $data['status'] !== 'OK') {
            $error_message = isset($data['error_message']) ? $data['error_message'] : __('API yanıtı alınamadı', 'reviews-plugin');
            return new WP_Error('api_error', $error_message);
        }
        
        if (!isset($data['result']['reviews'])) {
            return new WP_Error('no_reviews', __('Yorum bulunamadı', 'reviews-plugin'));
        }
        
        return array(
            'reviews' => $data['result']['reviews'],
            'rating' => isset($data['result']['rating']) ? $data['result']['rating'] : 0,
            'total_ratings' => isset($data['result']['user_ratings_total']) ? $data['result']['user_ratings_total'] : 0,
            'name' => isset($data['result']['name']) ? $data['result']['name'] : ''
        );
    }
    
    /**
     * Filter reviews by minimum rating
     * 
     * @param array $reviews
     * @param int $min_rating
     * @return array
     */
    public function filter_reviews($reviews, $min_rating = 1) {
        if ($min_rating <= 1) {
            return $reviews;
        }
        
        return array_filter($reviews, function($review) use ($min_rating) {
            return isset($review['rating']) && $review['rating'] >= $min_rating;
        });
    }
    
    /**
     * Format review date
     * 
     * @param int $timestamp
     * @return string
     */
    public static function format_date($timestamp) {
        return human_time_diff($timestamp, current_time('timestamp')) . ' ' . __('önce', 'reviews-plugin');
    }
    
    /**
     * Generate star rating HTML
     * 
     * @param float $rating
     * @return string
     */
    public static function get_star_rating($rating) {
        $output = '<div class="reviews-stars">';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $output .= '<span class="star star-full">★</span>';
            } elseif ($rating >= ($i - 0.5)) {
                $output .= '<span class="star star-half">★</span>';
            } else {
                $output .= '<span class="star star-empty">☆</span>';
            }
        }
        
        $output .= '</div>'; 
        
        return $output;
    }
}