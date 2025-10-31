<?php
/**
 * Admin Panel Class
 * 
 * @package Reviews_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Reviews Settings', 'reviews-plugin'),
            __('Reviews Plugin', 'reviews-plugin'),
            'manage_options',
            'reviews-plugin-settings',
            array($this, 'settings_page'),
            'dashicons-star-filled',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('reviews_plugin_settings', 'reviews_plugin_api_key');
        register_setting('reviews_plugin_settings', 'reviews_plugin_place_id');
        register_setting('reviews_plugin_settings', 'reviews_plugin_cache_duration');
        register_setting('reviews_plugin_settings', 'reviews_plugin_max_reviews');
        register_setting('reviews_plugin_settings', 'reviews_plugin_min_rating');
        
        add_settings_section(
            'reviews_plugin_main_section',
            __('Google API Ayarları', 'reviews-plugin'),
            array($this, 'section_callback'),
            'reviews-plugin-settings'
        );
        
        add_settings_field(
            'reviews_plugin_api_key',
            __('Google API Key', 'reviews-plugin'),
            array($this, 'api_key_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_main_section'
        );
        
        add_settings_field(
            'reviews_plugin_place_id',
            __('Google Place ID', 'reviews-plugin'),
            array($this, 'place_id_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_main_section'
        );
        
        add_settings_field(
            'reviews_plugin_cache_duration',
            __('Cache Süresi (Saat)', 'reviews-plugin'),
            array($this, 'cache_duration_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_main_section'
        );
        
        add_settings_field(
            'reviews_plugin_max_reviews',
            __('Maksimum Yorum Sayısı', 'reviews-plugin'),
            array($this, 'max_reviews_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_main_section'
        );
        
        add_settings_field(
            'reviews_plugin_min_rating',
            __('Minimum Yıldız', 'reviews-plugin'),
            array($this, 'min_rating_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_main_section'
        );
    }
    
    public function section_callback() {
        echo '<p>' . __('Google Places API ayarlarınızı buradan yapılandırın.', 'reviews-plugin') . '</p>';
    }
    
    public function api_key_callback() {
        $value = get_option('reviews_plugin_api_key', '');
        echo '<input type="text" name="reviews_plugin_api_key" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('Google Cloud Console\'dan API key alın.', 'reviews-plugin') . '</p>';
    }
    
    public function place_id_callback() {
        $value = get_option('reviews_plugin_place_id', '');
        echo '<input type="text" name="reviews_plugin_place_id" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('İşletmenizin Google Place ID\'sini girin.', 'reviews-plugin') . '</p>';
    }
    
    public function cache_duration_callback() {
        $value = get_option('reviews_plugin_cache_duration', '24');
        echo '<input type="number" name="reviews_plugin_cache_duration" value="' . esc_attr($value) . '" min="1" max="168" />';
        echo '<p class="description">' . __('Yorumların önbellekte tutulma süresi (1-168 saat).', 'reviews-plugin') . '</p>';
    }
    
    public function max_reviews_callback() {
        $value = get_option('reviews_plugin_max_reviews', '10');
        echo '<input type="number" name="reviews_plugin_max_reviews" value="' . esc_attr($value) . '" min="1" max="50" />';
        echo '<p class="description">' . __('Gösterilecek maksimum yorum sayısı.', 'reviews-plugin') . '</p>';
    }
    
    public function min_rating_callback() {
        $value = get_option('reviews_plugin_min_rating', '1');
        echo '<select name="reviews_plugin_min_rating">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '" ' . selected($value, $i, false) . '>' . $i . ' ' . __('Yıldız', 'reviews-plugin') . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Gösterilecek minimum yıldız sayısı.', 'reviews-plugin') . '</p>';
    }
    
    /**
     * Settings page HTML
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="card">
                <h2><?php _e('Kurulum Talimatları', 'reviews-plugin'); ?></h2>
                <ol>
                    <li><?php _e('Google Cloud Console\'a gidin ve yeni bir proje oluşturun.', 'reviews-plugin'); ?></li>
                    <li><?php _e('Places API\'yi etkinleştirin.', 'reviews-plugin'); ?></li>
                    <li><?php _e('API anahtarı oluşturun ve aşağıya yapıştırın.', 'reviews-plugin'); ?></li>
                    <li><?php _e('İşletmenizin Place ID\'sini bulun ve girin.', 'reviews-plugin'); ?></li>
                </ol>
                
                <h3><?php _e('Shortcode Kullanımı', 'reviews-plugin'); ?></h3>
                <p><code>[google_reviews]</code> - Varsayılan ayarlarla yorumları göster</p>
                <p><code>[google_reviews limit="5" style="slider"]</code> - 5 yorum, slider görünümü</p>
                <p><code>[google_reviews style="grid" min_rating="4"]</code> - Grid görünüm, minimum 4 yıldız</p>
                
                <h3><?php _e('Stil Seçenekleri', 'reviews-plugin'); ?></h3>
                <ul>
                    <li><strong>slider</strong> - Kaydırmalı görünüm (varsayılan)</li>
                    <li><strong>grid</strong> - Izgara görünümü</li>
                    <li><strong>list</strong> - Liste görünümü</li>
                </ul>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('reviews_plugin_settings');
                do_settings_sections('reviews-plugin-settings');
                submit_button();
                ?>
            </form>
            
            <div class="card">
                <h2><?php _e('Cache Temizle', 'reviews-plugin'); ?></h2>
                <p><?php _e('Yorumları hemen güncellemek için cache\'i temizleyebilirsiniz.', 'reviews-plugin'); ?></p>
                <?php
                if (isset($_POST['clear_cache']) && check_admin_referer('clear_reviews_cache')) {
                    delete_transient('reviews_plugin_cached_data');
                    echo '<div class="notice notice-success"><p>' . __('Cache başarıyla temizlendi!', 'reviews-plugin') . '</p></div>';
                }
                ?>
                <form method="post">
                    <?php wp_nonce_field('clear_reviews_cache'); ?>
                    <input type="submit" name="clear_cache" class="button button-secondary" value="<?php _e('Cache Temizle', 'reviews-plugin'); ?>" />
                </form>
            </div>
        </div>
        <?php
    }
}