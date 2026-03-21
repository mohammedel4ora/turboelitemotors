/**
 * Header Customization JavaScript
 * Handles Cart Badge, Compare Badge, and Navigation Active States
 */

(function () {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function () {
        initHeaderCustomization();
    });

    function initHeaderCustomization() {
        // Update Cart Badge
        updateCartBadge();

        // Update Compare Badge
        updateCompareBadge();

        // Set Active Navigation Link
        setActiveNavLink();

        // Listen for WooCommerce cart updates
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('added_to_cart updated_wc_div', function () {
                updateCartBadge();
            });
        }

        // Listen for cart sidebar updates
        document.addEventListener('cartUpdated', function () {
            updateCartBadge();
        });
    }

    /**
     * Update Cart Badge Count
     */
    function updateCartBadge() {
        if (typeof wc_add_to_cart_params === 'undefined' && typeof jQuery === 'undefined') {
            return;
        }

        // Try to get cart count from WooCommerce
        if (typeof jQuery !== 'undefined') {
            jQuery.ajax({
                url: wc_add_to_cart_params?.wc_ajax_url?.toString().replace('%%endpoint%%', 'get_refreshed_fragments') || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'woocommerce_get_refreshed_fragments'
                },
                success: function (response) {
                    if (response && response.fragments) {
                        // Update cart badge from fragments
                        const cartCount = jQuery('.ast-site-header-cart .count').text() || '0';
                        updateBadge('.custom-cart-badge, .ast-site-header-cart .count', cartCount);
                    }
                }
            });
        }

        // Fallback: Get from cart count element if exists
        const cartCountElement = document.querySelector('.ast-site-header-cart .count');
        if (cartCountElement) {
            const count = cartCountElement.textContent.trim() || '0';
            updateBadge('.custom-cart-badge', count);
        }
    }

    /**
     * Update Compare Badge Count
     */
    function updateCompareBadge() {
        // Get compare list from localStorage
        const compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
        const count = compareList.length || 0;
        updateBadge('.custom-compare-badge', count);
    }

    /**
     * Update Badge Element
     */
    function updateBadge(selector, count) {
        const badges = document.querySelectorAll(selector);
        badges.forEach(function (badge) {
            const numCount = parseInt(count) || 0;
            if (numCount > 0) {
                badge.textContent = numCount > 99 ? '99+' : numCount;
                badge.style.display = 'flex';
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        });
    }

    /**
     * Set Active Navigation Link based on current page
     */
    function setActiveNavLink() {
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('.custom-nav-link');

        navLinks.forEach(function (link) {
            link.classList.remove('active');

            const href = link.getAttribute('href');
            if (href) {
                // Check if current URL matches link
                if (currentUrl.includes(href) ||
                    (href === '#' && currentUrl.includes('home')) ||
                    (href.includes('#parts') && currentUrl.includes('parts')) ||
                    (href.includes('#about') && currentUrl.includes('about'))) {
                    link.classList.add('active');
                }
            }
        });
    }

    /**
     * Open Cart Sidebar when cart icon is clicked
     */
    const cartIcons = document.querySelectorAll('.custom-cart-icon, .ast-site-header-cart a');
    cartIcons.forEach(function (icon) {
        icon.addEventListener('click', function (e) {
            // Only prevent default if cart sidebar exists
            const cartSidebar = document.querySelector('.cart-sidebar');
            if (cartSidebar) {
                e.preventDefault();
                // Trigger cart sidebar open
                if (typeof jQuery !== 'undefined') {
                    jQuery('.cart-sidebar').addClass('active');
                    jQuery('.cart-sidebar-overlay').addClass('active');
                    jQuery('body').css('overflow', 'hidden');
                } else {
                    cartSidebar.classList.add('active');
                    const overlay = document.querySelector('.cart-sidebar-overlay');
                    if (overlay) overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    });

    /**
     * Open Compare Sidebar when compare icon is clicked
     */
    const compareIcons = document.querySelectorAll('.custom-compare-icon');
    compareIcons.forEach(function (icon) {
        icon.addEventListener('click', function (e) {
            e.preventDefault();
            // TODO: Implement compare sidebar/modal
            console.log('Compare functionality - to be implemented');
        });
    });
})();

