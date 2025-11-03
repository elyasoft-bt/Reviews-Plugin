<?php
/*
 * Slider View Template
 *
 * @author Elyasoft
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'get_option' ) ) {
    return;
}

$reviews_per_page = get_option( 'reviews_per_page', 5 );

// Your other functions and HTML layout

?>
<!-- Other HTML Code -->
<!-- Removed "Powered by Google" section -->
<!-- This div has been removed -->