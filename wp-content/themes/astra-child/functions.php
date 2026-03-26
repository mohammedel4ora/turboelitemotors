<?php
/**
 * Astra Child Theme Functions
 * 
 * @package Astra Child - Toyota Parts
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Parent and Child Theme Styles
 */
function astra_child_enqueue_styles() {
    // Parent theme stylesheet
    wp_enqueue_style('astra-parent-theme', get_template_directory_uri() . '/style.css', array(), '1.0.0');

    // Saudi Identity Fonts (Google Fonts: Cairo & Almarai)
    wp_enqueue_style('saudi-fonts', 'https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@300;400;600;700;900&display=swap', array(), null);
    
    
    // Child theme stylesheet
    wp_enqueue_style('astra-child-theme', get_stylesheet_uri(), array('astra-parent-theme'), '1.0.0');
    
    // PWA Global Styles (Header & Footer)
    wp_enqueue_style('header-pwa', get_stylesheet_directory_uri() . '/assets/css/header-pwa.css', array('astra-child-theme'), '1.2.1');
    wp_enqueue_style('cart-sidebar', get_stylesheet_directory_uri() . '/assets/css/cart-sidebar.css', array('header-pwa'), '1.1.0');
    
    // PWA Global Scripts
    wp_enqueue_script('header-pwa-js', get_stylesheet_directory_uri() . '/assets/js/header-pwa.js', array('jquery'), '1.1.0', true);
    wp_enqueue_script('cart-sidebar-js', get_stylesheet_directory_uri() . '/assets/js/cart-sidebar.js', array('jquery'), '1.0.0', true);

    // Homepage PWA styles (only on homepage template)
    if (is_page_template('template-homepage-pwa.php')) {
        wp_enqueue_style('layout-fix', get_stylesheet_directory_uri() . '/assets/css/layout-fix.css', array('astra-child-theme'), '2.0.0');
        wp_enqueue_style('homepage-pwa', get_stylesheet_directory_uri() . '/assets/css/homepage-pwa.css', array('layout-fix'), '2.0.0');
        wp_enqueue_style('hero-enhanced', get_stylesheet_directory_uri() . '/assets/css/hero-enhanced.css', array('homepage-pwa'), '1.0.0');
        wp_enqueue_style('ymm-enhanced', get_stylesheet_directory_uri() . '/assets/css/ymm-enhanced.css', array('hero-enhanced'), '1.0.0');
        wp_enqueue_style('products-enhanced', get_stylesheet_directory_uri() . '/assets/css/products-enhanced.css', array('ymm-enhanced'), '1.0.0');
        wp_enqueue_style('product-image-fix', get_stylesheet_directory_uri() . '/assets/css/product-image-fix.css', array('products-enhanced'), '1.0.1');
    }
    
    // JS Cookie Library (Fix for "Cookies is not defined" error)
    wp_enqueue_script('js-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js', array(), '3.0.1', true);

    // Dark Mode Toggle Script
    wp_enqueue_script('dark-mode-toggle', get_stylesheet_directory_uri() . '/assets/js/dark-mode.js', array('js-cookie'), '1.0.0', true);

    // Contact Page styles
    if (is_page_template('template-contact.php')) {
        wp_enqueue_style('contact-page-style', get_stylesheet_directory_uri() . '/assets/css/contact-page.css', array('astra-child-theme'), '1.0.0');
    }

    // About Page styles
    if (is_page_template('template-about.php')) {
        wp_enqueue_style('about-page-style', get_stylesheet_directory_uri() . '/assets/css/about-page.css', array('astra-child-theme'), '1.0.0');
    }

    // Policy Pages styles
    if (is_page_template('template-policy.php')) {
        wp_enqueue_style('policy-page-style', get_stylesheet_directory_uri() . '/assets/css/policy-pages.css', array('astra-child-theme'), '1.0.0');
    }

    // FAQ Page Script
    if (is_page_template('template-faq.php')) {
        wp_enqueue_script('faq-interaction', get_stylesheet_directory_uri() . '/assets/js/faq-interaction.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');

// Legacy Dark Mode Toggle removed (Functionality moved to alrajhi-custom.php global header)
// previously: astra_child_dark_mode_toggle() hooked to wp_footer

/**
 * Register Custom Page Templates Directory
 */
function astra_child_custom_templates($templates) {
    return array_merge($templates, array(
        'template-homepage-pwa.php' => 'Homepage PWA',
        'template-contact.php'      => 'Contact Page Custom',
        'template-about.php'        => 'About Page Custom',
        'template-policy.php'       => 'Policy Page Custom',
        'template-faq.php'          => 'FAQ Page Custom',
        'template-tracking.php'     => 'Order Tracking Page',
        'template-general-pwa.php'  => 'General PWA Page',
        'template-login.php'        => 'صفحة تسجيل الدخول'
    ));
}
add_filter('theme_page_templates', 'astra_child_custom_templates');

/**
 * =========================================
 * MOBILE LAYOUT REDESIGN - Customizer Support
 * =========================================
 */

/**
 * Register Theme Support
 */
function alrajhi_theme_support() {
    // Custom Logo Support
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'alrajhi_theme_support');

/**
 * Register Navigation Menus
 */
function alrajhi_register_menus() {
    register_nav_menus(array(
        'mobile-menu'     => __('قائمة الموبايل', 'astra-child'),
        'mobile-categories' => __('فئات الموبايل', 'astra-child'),
    ));
}
add_action('after_setup_theme', 'alrajhi_register_menus');

/**
 * Customizer Settings
 */
/**
 * Customizer Configuration
 */
require_once get_stylesheet_directory() . '/inc/customizer.php';

/**
 * Secure Login Handler
 */
require_once get_stylesheet_directory() . '/inc/class-arj-secure-login.php';

/**
 * Login Page Customizer Settings
 */
require_once get_stylesheet_directory() . '/inc/arj-login-customizer.php';

/**
 * Astra Hooks Integration
 */
require_once get_stylesheet_directory() . '/inc/astra-hooks.php';

/**
 * Disable Astra Cart Drawer (conflicts with custom cart-sidebar)
 */
function alrajhi_disable_astra_cart_drawer() {
    // Disable Astra's native cart drawer feature
    add_filter('astra_woo_shop_flyout_cart', '__return_false');
    
    // Add CSS to hide any remaining cart drawer elements
    wp_add_inline_style('astra-child-theme', '
        .astra-cart-drawer,
        .astra-mobile-cart-overlay,
        .ast-cart-drawer {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    ');
}
add_action('wp_enqueue_scripts', 'alrajhi_disable_astra_cart_drawer', 20);

/**
 * =========================================
 * PWA INSTALLATION SUPPORT
 * =========================================
 */

/**
 * Add PWA Meta Tags to Head
 */
function alrajhi_pwa_meta_tags() {
    ?>
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?php echo esc_url(home_url('/manifest.json')); ?>">
    <meta name="theme-color" content="#eb0a1e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="الراجحي">
    
    <!-- iOS Icons -->
    <link rel="apple-touch-icon" href="<?php echo esc_url(home_url('/icons/icon-152.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(home_url('/icons/icon-192.png')); ?>">
    <?php
}
add_action('wp_head', 'alrajhi_pwa_meta_tags', 1);

/**
 * Enqueue PWA Install Scripts and Styles
 */
function alrajhi_pwa_scripts() {
    // PWA Install Banner CSS
    wp_enqueue_style('pwa-install', get_stylesheet_directory_uri() . '/assets/css/pwa-install.css', array(), '1.0.0');
    
    // PWA Install Prompt JS
    wp_enqueue_script('pwa-install', get_stylesheet_directory_uri() . '/assets/js/pwa-install.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'alrajhi_pwa_scripts');

/**
 * Register Service Worker
 */
function alrajhi_register_service_worker() {
    ?>
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then(function(registration) {
                        console.log('[PWA] Service Worker registered with scope:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('[PWA] Service Worker registration failed:', error);
                    });
            });
        }
    </script>
    <?php
}
add_action('wp_footer', 'alrajhi_register_service_worker', 99);

/**
 * =========================================
 * DARK MODE WIDGET
 * =========================================
 */
require_once get_stylesheet_directory() . '/inc/class-dark-mode-widget.php';

// Register Dark Mode Widget
function alrajhi_register_dark_mode_widget() {
    register_widget('Alrajhi_Dark_Mode_Widget');
}
add_action('widgets_init', 'alrajhi_register_dark_mode_widget');
