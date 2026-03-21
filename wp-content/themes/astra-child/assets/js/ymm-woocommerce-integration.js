/**
 * YMM WooCommerce Integration
 * Handles dynamic product filtering based on YMM selections
 */

(function($) {
    'use strict';

    let currentFilters = {
        make: '',
        model: '',
        year: '',
        category: ''
    };

    let isLoading = false;
    let currentPage = 1;

    $(document).ready(function() {
        initYMMIntegration();
    });

    /**
     * Initialize YMM Integration
     */
    function initYMMIntegration() {
        // Listen for YMM filter changes
        $(document).on('change', '.ymm-selector-form select, .alrajhi-ymm-container select', function() {
            handleYMMChange();
        });

        // Listen for YMM form submission
        $(document).on('submit', '.ymm-selector-form, .alrajhi-ymm-container form', function(e) {
            e.preventDefault();
            handleYMMChange();
        });

        // Load initial products
        loadProducts();
    }

    /**
     * Handle YMM Filter Changes
     */
    function handleYMMChange() {
        // Get current YMM values
        const makeSelect = $('.ymm-selector-form select[name*="make"], .alrajhi-ymm-container select[name*="make"]').first();
        const modelSelect = $('.ymm-selector-form select[name*="model"], .alrajhi-ymm-container select[name*="model"]').first();
        const yearSelect = $('.ymm-selector-form select[name*="year"], .alrajhi-ymm-container select[name*="year"]').first();
        const categorySelect = $('.ymm-selector-form select[name*="category"], .alrajhi-ymm-container select[name*="category"]').first();

        currentFilters.make = makeSelect.val() || '';
        currentFilters.model = modelSelect.val() || '';
        currentFilters.year = yearSelect.val() || '';
        currentFilters.category = categorySelect.val() || '';

        // Reset to first page
        currentPage = 1;

        // Update active filters display
        updateActiveFilters();

        // Load filtered products
        loadProducts();
    }

    /**
     * Load Products via AJAX
     */
    function loadProducts(append = false) {
        if (isLoading) return;

        isLoading = true;
        const $productsGrid = $('.products-grid');
        const $resultsCount = $('.results-count');
        const $loadMoreContainer = $('.load-more-container');

        // Show loading state
        if (!append) {
            $productsGrid.html('<div class="loading-skeleton"></div>');
        }

        // Prepare AJAX data
        const ajaxData = {
            action: 'ymm_filter_products',
            make: currentFilters.make,
            model: currentFilters.model,
            year: currentFilters.year,
            category: currentFilters.category,
            page: currentPage,
            append: append ? 1 : 0
        };

        $.ajax({
            url: ymmWooCommerce.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    if (append) {
                        $productsGrid.append(response.data.html);
                    } else {
                        $productsGrid.html(response.data.html);
                    }

                    // Update results count
                    if ($resultsCount.length) {
                        $resultsCount.text(response.data.count + ' قطعة');
                    }

                    // Show/hide load more button
                    if (response.data.has_more) {
                        $loadMoreContainer.show();
                    } else {
                        $loadMoreContainer.hide();
                    }

                    // Trigger WooCommerce fragments update
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        $(document.body).trigger('wc_fragment_refresh');
                    }
                } else {
                    $productsGrid.html('<div class="empty-state"><h4>لا توجد منتجات</h4><p>' + (response.data.message || 'لم يتم العثور على منتجات') + '</p></div>');
                }
            },
            error: function() {
                $productsGrid.html('<div class="empty-state"><h4>خطأ</h4><p>حدث خطأ أثناء تحميل المنتجات</p></div>');
            },
            complete: function() {
                isLoading = false;
            }
        });
    }

    /**
     * Update Active Filters Display
     */
    function updateActiveFilters() {
        const $activeFilters = $('#activeFilters');
        if (!$activeFilters.length) {
            // Create active filters container if it doesn't exist
            $('.ymm-search-section .container').append('<div class="active-filters" id="activeFilters"></div>');
        }

        const $container = $('#activeFilters');
        $container.empty();

        const filters = [];
        if (currentFilters.make) filters.push({ label: 'الصانع', value: currentFilters.make });
        if (currentFilters.model) filters.push({ label: 'الموديل', value: currentFilters.model });
        if (currentFilters.year) filters.push({ label: 'السنة', value: currentFilters.year });
        if (currentFilters.category) filters.push({ label: 'الفئة', value: currentFilters.category });

        if (filters.length > 0) {
            filters.forEach(function(filter) {
                const $filterTag = $('<span class="filter-tag">')
                    .html(filter.label + ': <strong>' + filter.value + '</strong> <button class="filter-remove" data-filter="' + filter.label + '">×</button>');
                $container.append($filterTag);
            });
            $container.show();
        } else {
            $container.hide();
        }

        // Handle filter removal
        $container.find('.filter-remove').on('click', function() {
            const filterType = $(this).data('filter');
            clearFilter(filterType);
        });
    }

    /**
     * Clear Specific Filter
     */
    function clearFilter(filterType) {
        if (filterType === 'الصانع') {
            currentFilters.make = '';
            $('.ymm-selector-form select[name*="make"], .alrajhi-ymm-container select[name*="make"]').val('').trigger('change');
        } else if (filterType === 'الموديل') {
            currentFilters.model = '';
            $('.ymm-selector-form select[name*="model"], .alrajhi-ymm-container select[name*="model"]').val('').trigger('change');
        } else if (filterType === 'السنة') {
            currentFilters.year = '';
            $('.ymm-selector-form select[name*="year"], .alrajhi-ymm-container select[name*="year"]').val('').trigger('change');
        } else if (filterType === 'الفئة') {
            currentFilters.category = '';
            $('.ymm-selector-form select[name*="category"], .alrajhi-ymm-container select[name*="category"]').val('').trigger('change');
        }

        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    }

    /**
     * Load More Products
     */
    $(document).on('click', '#loadMoreBtn', function() {
        currentPage++;
        loadProducts(true);
    });

    // Expose functions globally if needed
    window.ymmWooCommerceIntegration = {
        loadProducts: loadProducts,
        handleYMMChange: handleYMMChange
    };

})(jQuery);

