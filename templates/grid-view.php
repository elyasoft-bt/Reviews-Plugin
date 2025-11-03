<?php
/**
 * Template Name: Grid View
 */

get_header();
?>

<div class="reviews-grid">
    <?php
    // Fetch and loop through reviews
    $reviews = get_reviews();
    foreach ( $reviews as $review ) :
    ?>
        <div class="review-item">
            <h3><?php echo esc_html( $review->title ); ?></h3>
            <p><?php echo esc_html( $review->content ); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php
get_footer();
?>
