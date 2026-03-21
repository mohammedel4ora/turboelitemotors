/**
 * Compare Functionality
 * Handles product comparison feature
 */

(function($) {
    'use strict';

    const STORAGE_KEY = 'compareList';
    const MAX_COMPARE_ITEMS = 4;

    let compareList = [];

    $(document).ready(function() {
        initCompare();
    });

    /**
     * Initialize Compare Functionality
     */
    function initCompare() {
        // Load compare list from storage
        loadCompareList();

        // Update badge
        updateCompareBadge();

        // Handle compare button clicks
        $(document).on('click', '.add-to-compare, [data-action="compare"]', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id') || $(this).closest('.product-card').data('product-id');
            if (productId) {
                toggleCompare(productId);
            }
        });

        // Handle compare icon click in header
        $(document).on('click', '#compareBtn, .custom-compare-icon', function(e) {
            e.preventDefault();
            openCompareSidebar();
        });

        // Handle remove from compare
        $(document).on('click', '.compare-remove', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            if (productId) {
                removeFromCompare(productId);
            }
        });

        // Handle clear compare
        $(document).on('click', '.compare-clear', function(e) {
            e.preventDefault();
            clearCompare();
        });
    }

    /**
     * Load Compare List from Storage
     */
    function loadCompareList() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            compareList = stored ? JSON.parse(stored) : [];
        } catch (e) {
            compareList = [];
        }
    }

    /**
     * Save Compare List to Storage
     */
    function saveCompareList() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(compareList));
        } catch (e) {
            console.error('Error saving compare list:', e);
        }
    }

    /**
     * Toggle Product in Compare List
     */
    function toggleCompare(productId) {
        productId = parseInt(productId);
        const index = compareList.indexOf(productId);

        if (index > -1) {
            // Remove from compare
            compareList.splice(index, 1);
            showNotification('تم إزالة المنتج من المقارنة', 'info');
        } else {
            // Add to compare
            if (compareList.length >= MAX_COMPARE_ITEMS) {
                showNotification('يمكنك مقارنة ' + MAX_COMPARE_ITEMS + ' منتجات كحد أقصى', 'warning');
                return;
            }
            compareList.push(productId);
            showNotification('تم إضافة المنتج للمقارنة', 'success');
        }

        saveCompareList();
        updateCompareBadge();
        updateCompareButtons();
        updateCompareSidebar();
    }

    /**
     * Remove Product from Compare
     */
    function removeFromCompare(productId) {
        productId = parseInt(productId);
        const index = compareList.indexOf(productId);
        
        if (index > -1) {
            compareList.splice(index, 1);
            saveCompareList();
            updateCompareBadge();
            updateCompareButtons();
            updateCompareSidebar();
        }
    }

    /**
     * Clear Compare List
     */
    function clearCompare() {
        compareList = [];
        saveCompareList();
        updateCompareBadge();
        updateCompareButtons();
        updateCompareSidebar();
        showNotification('تم مسح قائمة المقارنة', 'info');
    }

    /**
     * Update Compare Badge
     */
    function updateCompareBadge() {
        const count = compareList.length;
        $('.custom-compare-badge, #compareBadge').text(count > 0 ? count : '').toggle(count > 0);
    }

    /**
     * Update Compare Buttons
     */
    function updateCompareButtons() {
        $('.add-to-compare, [data-action="compare"]').each(function() {
            const productId = parseInt($(this).data('product-id') || $(this).closest('.product-card').data('product-id'));
            if (compareList.indexOf(productId) > -1) {
                $(this).addClass('in-compare').text('إزالة من المقارنة');
            } else {
                $(this).removeClass('in-compare').text('إضافة للمقارنة');
            }
        });
    }

    /**
     * Open Compare Sidebar
     */
    function openCompareSidebar() {
        if (compareList.length === 0) {
            showNotification('قائمة المقارنة فارغة', 'info');
            return;
        }

        // Create or show compare sidebar
        let $sidebar = $('#compareSidebar');
        if (!$sidebar.length) {
            $sidebar = $('<div id="compareSidebar" class="compare-sidebar"></div>');
            $('body').append($sidebar);
        }

        updateCompareSidebar();
        $sidebar.addClass('active');
        $('.compare-sidebar-overlay').addClass('active');
        $('body').css('overflow', 'hidden');
    }

    /**
     * Close Compare Sidebar
     */
    function closeCompareSidebar() {
        $('#compareSidebar').removeClass('active');
        $('.compare-sidebar-overlay').removeClass('active');
        $('body').css('overflow', '');
    }

    /**
     * Update Compare Sidebar Content
     */
    function updateCompareSidebar() {
        const $sidebar = $('#compareSidebar');
        if (!$sidebar.length) return;

        if (compareList.length === 0) {
            $sidebar.html(`
                <div class="compare-sidebar-header">
                    <h3>المقارنة</h3>
                    <button class="compare-close">&times;</button>
                </div>
                <div class="compare-sidebar-content">
                    <div class="empty-state">
                        <p>قائمة المقارنة فارغة</p>
                    </div>
                </div>
            `);
            return;
        }

        // Load products via AJAX
        $.ajax({
            url: ymmWooCommerce.ajax_url,
            type: 'POST',
            data: {
                action: 'get_compare_products',
                product_ids: compareList
            },
            success: function(response) {
                if (response.success) {
                    $sidebar.html(response.data.html);
                }
            }
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message, type) {
        // Simple notification - can be enhanced
        const $notification = $('<div class="compare-notification ' + type + '">' + message + '</div>');
        $('body').append($notification);
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    // Close sidebar handlers
    $(document).on('click', '.compare-close, .compare-sidebar-overlay', function() {
        closeCompareSidebar();
    });

    // Expose functions globally
    window.compareFunctionality = {
        toggle: toggleCompare,
        open: openCompareSidebar,
        close: closeCompareSidebar,
        clear: clearCompare,
        getList: function() { return compareList; }
    };

})(jQuery);

