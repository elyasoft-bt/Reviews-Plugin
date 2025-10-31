/**
 * Reviews Plugin JavaScript
 * 
 * @package Reviews_Plugin
 */

(function($) {
    'use strict';

    /**
     * Initialize Swiper Slider
     */
    function initReviewsSlider() {
        if (typeof Swiper === 'undefined') {
            console.error('Swiper library not loaded');
            return;
        }

        const swiperElements = document.querySelectorAll('.reviews-swiper');
        
        swiperElements.forEach(function(element) {
            new Swiper(element, {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
            });
        });
    }

    /**
     * Truncate long review text
     */
    function truncateReviews() {
        const reviewContents = document.querySelectorAll('.review-content p');
        const maxLength = 200;

        reviewContents.forEach(function(content) {
            const text = content.textContent;
            
            if (text.length > maxLength) {
                const truncated = text.substring(0, maxLength) + '...';
                const fullText = text;
                
                content.textContent = truncated;
                content.style.cursor = 'pointer';
                content.title = 'Devamını görmek için tıklayın';
                
                let isExpanded = false;
                
                content.addEventListener('click', function() {
                    if (isExpanded) {
                        content.textContent = truncated;
                        isExpanded = false;
                    } else {
                        content.textContent = fullText;
                        isExpanded = true;
                    }
                });
            }
        });
    }

    /**
     * Add animations on scroll
     */
    function animateOnScroll() {
        const reviewCards = document.querySelectorAll('.review-card');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        reviewCards.forEach(function(card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    }

    /**
     * Initialize all functions on document ready
     */
    $(document).ready(function() {
        // Wait for Swiper library to load
        setTimeout(function() {
            initReviewsSlider();
        }, 100);

        truncateReviews();
        animateOnScroll();
    });

    /**
     * Reinitialize on AJAX content load (for page builders)
     */
    $(document).on('ajaxComplete', function() {
        setTimeout(function() {
            initReviewsSlider();
            truncateReviews();
            animateOnScroll();
        }, 100);
    });

})(jQuery);
