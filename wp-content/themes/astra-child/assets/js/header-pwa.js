jQuery(document).ready(function ($) {
    // Sticky Header Effect (Applying to Astra Header)
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.main-header-bar, .ast-primary-header-bar').addClass('ast-header-scrolled');
        } else {
            $('.main-header-bar, .ast-primary-header-bar').removeClass('ast-header-scrolled');
        }
    });

    // Astra Mobile Menu - Using Native Implementation
    // Custom mobile sidebar removed - now using ast-mobile-popup-inner

    // ==========================================
    // BOTTOM NAVIGATION LOGIC
    // ==========================================

    // 1. Search Trigger
    $('.pwa-bottom-nav .search-trigger').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Scroll to Hero Search
        const searchInput = $('.pwa-hero input[type="search"]');
        if (searchInput.length) {
            $('html, body').animate({
                scrollTop: $('.pwa-hero').offset().top - 100
            }, 500, function () {
                searchInput.focus();
            });
        }
    });

    // 2. Active State Management
    const currentUrl = window.location.href;
    $('.pwa-bottom-nav .nav-item').each(function () {
        const linkUrl = $(this).attr('href');
        if (currentUrl === linkUrl) {
            $('.pwa-bottom-nav .nav-item').removeClass('active');
            $(this).addClass('active');
        }
    });

    // 3. Search Active State (Special Case)
    $('.pwa-hero input[type="search"]').on('focus', function () {
        $('.pwa-bottom-nav .nav-item').removeClass('active');
        $('.pwa-bottom-nav .search-trigger').addClass('active');
    });

    $('.pwa-hero input[type="search"]').on('blur', function () {
        // Delay to see if user clicked something else
        setTimeout(function () {
            $('.pwa-bottom-nav .search-trigger').removeClass('active');
            // Restore Home active if on home
            if (window.location.pathname === '/' || window.location.href === '<?php echo home_url(); ?>') {
                $('.pwa-bottom-nav .nav-item:first-child').addClass('active');
            }
        }, 200);
    });

});
