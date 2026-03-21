/**
 * Customizer Live Preview Logic
 * 
 * Handles strict 'postMessage' transport for instant updates.
 */
(function ($) {

    // Helper: Update CSS variable
    function updateRootVar(varName, value) {
        document.documentElement.style.setProperty(varName, value);
    }

    // ---------------------------------------------
    // Global Colors
    // ---------------------------------------------

    // Primary Color
    wp.customize('alrajhi_primary_color', function (value) {
        value.bind(function (newval) {
            updateRootVar('--color-primary', newval);
            updateRootVar('--alrajhi-primary', newval);
        });
    });

    // Secondary Color
    wp.customize('alrajhi_secondary_color', function (value) {
        value.bind(function (newval) {
            updateRootVar('--color-primary-dark', newval);
            updateRootVar('--alrajhi-secondary', newval);
        });
    });

    // Logo Size
    wp.customize('alrajhi_logo_size', function (value) {
        value.bind(function (newval) {
            updateRootVar('--logo-size', newval + 'px');
        });
    });

    // Header Icon Size
    wp.customize('alrajhi_header_icon_size', function (value) {
        value.bind(function (newval) {
            updateRootVar('--header-icon-size', newval + 'px');
        });
    });

    // ---------------------------------------------
    // Hero Section Preview Logic
    // ---------------------------------------------

    // Hero Title
    wp.customize('alrajhi_hero_title', function (value) {
        value.bind(function (newval) {
            $('.hero-title').text(newval);
        });
    });

    // Hero Subtitle
    wp.customize('alrajhi_hero_subtitle', function (value) {
        value.bind(function (newval) {
            $('.hero-subtitle').text(newval);
        });
    });

    // Hero Background Image
    wp.customize('alrajhi_hero_bg_image', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.pwa-hero .hero-bg').css('background-image', 'url("' + newval + '")');
            } else {
                $('.pwa-hero .hero-bg').css('background-image', ''); // Fallback to CSS default
            }
        });
    });

    // ---------------------------------------------
    // Footer Section Preview Logic
    // ---------------------------------------------

    // About Title
    wp.customize('alrajhi_footer_about_title', function (value) {
        value.bind(function (newval) {
            $('.footer-section.about .footer-title').text(newval);
        });
    });

    // About Text
    wp.customize('alrajhi_footer_about_text', function (value) {
        value.bind(function (newval) {
            $('.footer-about-text').text(newval);
        });
    });

    // Phone
    wp.customize('alrajhi_footer_phone', function (value) {
        value.bind(function (newval) {
            $('.footer-phone-text').text(newval);
        });
    });

    // Email
    wp.customize('alrajhi_footer_email', function (value) {
        value.bind(function (newval) {
            $('.footer-email-text').text(newval);
        });
    });

    // Location
    wp.customize('alrajhi_footer_location', function (value) {
        value.bind(function (newval) {
            $('.footer-location-text').text(newval);
        });
    });

    // Copyright
    wp.customize('alrajhi_footer_copyright', function (value) {
        value.bind(function (newval) {
            $('.footer-copyright-text').text(newval);
        });
    });

    // ---------------------------------------------
    // Header & Search Preview Logic
    // ---------------------------------------------

    // Header Title
    wp.customize('alrajhi_header_title', function (value) {
        value.bind(function (newval) {
            $('.logo-text h1').text(newval);
        });
    });

    // Header Tagline
    wp.customize('alrajhi_header_tagline', function (value) {
        value.bind(function (newval) {
            $('.logo-text span').text(newval);
        });
    });

    // Search Placeholder
    wp.customize('alrajhi_search_placeholder', function (value) {
        value.bind(function (newval) {
            $('.search-input').attr('placeholder', newval);
        });
    });

    // Search Button Text
    wp.customize('alrajhi_search_button_text', function (value) {
        value.bind(function (newval) {
            $('.quick-search button[type="submit"]').text(newval);
        });
    });

    // YMM Title
    wp.customize('alrajhi_ymm_section_title', function (value) {
        value.bind(function (newval) {
            $('.ymm-search-section .section-title').text(newval);
        });
    });

    // Results Title
    wp.customize('alrajhi_results_title', function (value) {
        value.bind(function (newval) {
            $('.results-title').text(newval);
        });
    });

    // Top Bar Message
    wp.customize('alrajhi_topbar_text', function (value) {
        value.bind(function (newval) {
            $('.alrajhi-top-bar .top-bar-message').text(newval);
        });
    });

    // Top Bar Colors (Dynamic CSS Variables)
    wp.customize('alrajhi_topbar_bg', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--topbar-bg', newval);
        });
    });

    wp.customize('alrajhi_topbar_color', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--topbar-color', newval);
        });
    });

})(jQuery);
