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
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_reviews-plugin-settings' !== $hook) {
            return;
        }
        
        // Add CodeMirror for CSS editor
        wp_enqueue_code_editor(array('type' => 'text/css'));
        wp_enqueue_script('wp-theme-plugin-editor');
        wp_enqueue_style('wp-codemirror');
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
        // API Settings
        register_setting('reviews_plugin_settings', 'reviews_plugin_api_key');
        register_setting('reviews_plugin_settings', 'reviews_plugin_place_id');
        register_setting('reviews_plugin_settings', 'reviews_plugin_cache_duration');
        register_setting('reviews_plugin_settings', 'reviews_plugin_max_reviews');
        register_setting('reviews_plugin_settings', 'reviews_plugin_min_rating');
        
        // Schema Settings
        register_setting('reviews_plugin_settings', 'reviews_plugin_business_name');
        register_setting('reviews_plugin_settings', 'reviews_plugin_business_type');
        
        // Custom CSS
        register_setting('reviews_plugin_settings', 'reviews_plugin_custom_css', array(
            'sanitize_callback' => 'wp_strip_all_tags'
        ));
        
        // API Settings Section
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
        
        // Schema Settings Section
        add_settings_section(
            'reviews_plugin_schema_section',
            __('Google Schema Ayarları (SEO)', 'reviews-plugin'),
            array($this, 'schema_section_callback'),
            'reviews-plugin-settings'
        );
        
        add_settings_field(
            'reviews_plugin_business_name',
            __('İşletme Adı', 'reviews-plugin'),
            array($this, 'business_name_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_schema_section'
        );
        
        add_settings_field(
            'reviews_plugin_business_type',
            __('İşletme Tipi', 'reviews-plugin'),
            array($this, 'business_type_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_schema_section'
        );
        
        // Custom CSS Section
        add_settings_section(
            'reviews_plugin_css_section',
            __('Özel CSS', 'reviews-plugin'),
            array($this, 'css_section_callback'),
            'reviews-plugin-settings'
        );
        
        add_settings_field(
            'reviews_plugin_custom_css',
            __('CSS Kodu', 'reviews-plugin'),
            array($this, 'custom_css_callback'),
            'reviews-plugin-settings',
            'reviews_plugin_css_section'
        );
    }
    
    public function section_callback() {
        echo '<p>' . __('Google Places API ayarlarınızı buradan yapılandırın.', 'reviews-plugin') . '</p>';
    }
    
    public function schema_section_callback() {
        echo '<p>' . __('Google arama sonuçlarında yıldızların görünmesi için işletme bilgilerinizi girin. Bu bilgiler Schema.org formatında yapılandırılmış veri olarak eklenir.', 'reviews-plugin') . '</p>';
    }
    
    public function css_section_callback() {
        echo '<p>' . __('Yorumların görünümünü özelleştirmek için kendi CSS kodunuzu buraya ekleyin.', 'reviews-plugin') . '</p>';
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
    
    public function business_name_callback() {
        $value = get_option('reviews_plugin_business_name', '');
        echo '<input type="text" name="reviews_plugin_business_name" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('İşletmenizin adı (örn: Elyasoft Teknoloji). Google arama sonuçlarında görünecektir.', 'reviews-plugin') . '</p>';
    }
    
    public function business_type_callback() {
        $value = get_option('reviews_plugin_business_type', 'LocalBusiness');
        $types = array(
            'LocalBusiness' => __('Yerel İşletme', 'reviews-plugin'),
            'Restaurant' => __('Restoran', 'reviews-plugin'),
            'Store' => __('Mağaza', 'reviews-plugin'),
            'Hotel' => __('Otel', 'reviews-plugin'),
            'AutoDealer' => __('Oto Galeri', 'reviews-plugin'),
            'BeautySalon' => __('Güzellik Salonu', 'reviews-plugin'),
            'Dentist' => __('Diş Kliniği', 'reviews-plugin'),
            'Doctor' => __('Doktor', 'reviews-plugin'),
            'LegalService' => __('Hukuk Bürosu', 'reviews-plugin'),
            'RealEstateAgent' => __('Emlakçı', 'reviews-plugin'),
            'HealthClub' => __('Spor Salonu', 'reviews-plugin'),
            'ProfessionalService' => __('Profesyonel Hizmet', 'reviews-plugin'),
        );
        
        echo '<select name="reviews_plugin_business_type" class="regular-text">';
        foreach ($types as $type_key => $type_label) {
            echo '<option value="' . esc_attr($type_key) . '" ' . selected($value, $type_key, false) . '>' . esc_html($type_label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('İşletmenizin türünü seçin. Bu bilgi Google\'a gönderilir.', 'reviews-plugin') . '</p>';
    }
    
    public function custom_css_callback() {
        $value = get_option('reviews_plugin_custom_css', '');
        echo '<textarea id="reviews_plugin_custom_css" name="reviews_plugin_custom_css" rows="15" class="large-text code">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . __('Özel CSS kodunuzu buraya yazın. Örnek: .review-card { border-radius: 15px; }', 'reviews-plugin') . '</p>';
        
        // Initialize CodeMirror
        ?>
        <script>
        jQuery(document).ready(function($) {
            if (typeof wp !== 'undefined' && wp.codeEditor) {
                wp.codeEditor.initialize($('#reviews_plugin_custom_css'), {
                    codemirror: {
                        mode: 'css',
                        lineNumbers: true,
                        lineWrapping: true,
                        styleActiveLine: true,
                        continueComments: true
                    }
                });
            }
        });
        </script>
        <?php
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
