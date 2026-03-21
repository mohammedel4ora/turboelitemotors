/**
 * Infinite Scroll for Products
 * Handles automatic loading of more products on scroll
 */

(function($) {
    'use strict';

    let isLoading = false;
    let hasMore = false;
    let currentPage = 1;

    $(document).ready(function() {
        initInfiniteScroll();
    });

    /**
     * Initialize Infinite Scroll
     */
    function initInfiniteScroll() {
        // Check if we're on the homepage template
        if (!$('.products-grid').length) return;

        // Check if load more button exists
        const $loadMoreBtn = $('#loadMoreBtn');
        if ($loadMoreBtn.length) {
            // Use button-based loading
            $loadMoreBtn.on('click', function() {
                if (!isLoading && hasMore) {
                    loadMoreProducts();
                }
            });
        } else {
            // Use scroll-based loading
            $(window).on('scroll', handleScroll);
        }

        // Check initial state
        checkHasMore();
    }

    /**
     * Handle Scroll Event
     */
    function handleScroll() {
        if (isLoading || !hasMore) return;

        const scrollTop = $(window).scrollTop();
        const windowHeight = $(window).height();
        const documentHeight = $(document).height();
        const scrollPercent = (scrollTop + windowHeight) / documentHeight;

        // Load more when 80% scrolled
        if (scrollPercent > 0.8) {
            loadMoreProducts();
        }
    }

    /**
     * Load More Products
     */
    function loadMoreProducts() {
        if (isLoading || !hasMore) return;

        isLoading = true;
        currentPage++;

        const $productsGrid = $('.products-grid');
        const $loadMoreBtn = $('#loadMoreBtn');
        const $loadMoreContainer = $('.load-more-container');

        // Show loading state
        if ($loadMoreBtn.length) {
            $loadMoreBtn.prop('disabled', true).text('جاري التحميل...');
        } else {
            $productsGrid.append('<div class="loading-skeleton"></div>');
        }

        // Get current filters from YMM integration if available
        const filters = window.ymmWooCommerceIntegration ? 
            window.ymmWooCommerceIntegration.getCurrentFilters() : {};

        // Prepare AJAX data
        const ajaxData = {
            action: 'ymm_filter_products',
            make: filters.make || '',
            model: filters.model || '',
            year: filters.year || '',
            category: filters.category || '',
            page: currentPage,
            append: 1
        };

        $.ajax({
            url: ymmWooCommerce.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    // Remove loading skeleton
                    $productsGrid.find('.loading-skeleton').remove();

                    // Append new products
                    $productsGrid.append(response.data.html);

                    // Update has more state
                    hasMore = response.data.has_more;

                    if (hasMore) {
                        if ($loadMoreBtn.length) {
                            $loadMoreBtn.prop('disabled', false).text('تحميل المزيد');
                        }
                    } else {
                        if ($loadMoreBtn.length) {
                            $loadMoreBtn.hide();
                        }
                        $loadMoreContainer.hide();
                    }

                    // Trigger WooCommerce fragments update
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        $(document.body).trigger('wc_fragment_refresh');
                    }

                    // Smooth scroll to first new product
                    const $newProducts = $productsGrid.children('.product-card').slice(-response.data.html.match(/product-card/g).length);
                    if ($newProducts.length) {
                        $('html, body').animate({
                            scrollTop: $newProducts.first().offset().top - 100
                        }, 500);
                    }
                } else {
                    hasMore = false;
                    if ($loadMoreBtn.length) {
                        $loadMoreBtn.hide();
                    }
                    $loadMoreContainer.hide();
                }
            },
            error: function() {
                hasMore = false;
                if ($loadMoreBtn.length) {
                    $loadMoreBtn.prop('disabled', false).text('تحميل المزيد');
                }
                $productsGrid.find('.loading-skeleton').remove();
            },
            complete: function() {
                isLoading = false;
            }
        });
    }

    /**
     * Check if there are more products to load
     */
    function checkHasMore() {
        const $loadMoreContainer = $('.load-more-container');
        if ($loadMoreContainer.is(':visible')) {
            hasMore = true;
        } else {
            // Check if we have products and if there might be more
            const productCount = $('.products-grid .product-card').length;
            hasMore = productCount >= 12; // Assuming 12 products per page
        }
    }

    /**
     * Reset Infinite Scroll State
     */
    function resetInfiniteScroll() {
        currentPage = 1;
        hasMore = false;
        isLoading = false;
        checkHasMore();
    }

    // Listen for YMM filter changes to reset scroll
    $(document).on('ymmFiltersChanged', function() {
        resetInfiniteScroll();
    });

    // Expose functions globally
    window.infiniteScroll = {
        loadMore: loadMoreProducts,
        reset: resetInfiniteScroll
    };

})(jQuery);

