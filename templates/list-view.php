<?php
/**
 * List View Template
 * 
 * @package Reviews_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="reviews-plugin-wrapper reviews-list-wrapper">
    <?php if (isset($data['rating']) && $data['rating'] > 0): ?>
    <div class="reviews-header">
        <div class="reviews-summary">
            <div class="reviews-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="reviews-business-name"><?php echo esc_html($data['name']); ?></span>
            </div>
            <div class="reviews-rating-summary">
                <span class="reviews-rating-number"><?php echo number_format($data['rating'], 1); ?></span>
                <?php echo Reviews_API::get_star_rating($data['rating']); ?>
                <span class="reviews-total-count"><?php echo sprintf(__('%s değerlendirme', 'reviews-plugin'), number_format($data['total_ratings'])); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
        <div class="review-card">
            <div class="review-header">
                <img src="<?php echo esc_url($review['profile_photo_url']); ?>" alt="<?php echo esc_attr($review['author_name']); ?>" class="review-avatar" />
                <div class="review-author-info">
                    <h4 class="review-author-name"><?php echo esc_html($review['author_name']); ?></h4>
                    <div class="review-meta">
                        <?php echo Reviews_API::get_star_rating($review['rating']); ?>
                        <span class="review-date"><?php echo esc_html(Reviews_API::format_date($review['time'])); ?></span>
                    </div>
                </div>
            </div>
            <div class="review-content">
                <p><?php echo esc_html($review['text']); ?></p>
            </div>
            <div class="review-footer">
                <span class="review-source">Google'da yayınlandı</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="reviews-powered">
        <span>Powered by Google</span>
    </div>
</div>
