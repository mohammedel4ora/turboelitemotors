<?php
/**
 * Astra Theme Hooks Integration
 * 
 * This file injects custom PWA features into the native Astra theme hooks.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Inject Top Bar (Shipping) - Early Priority
 */
function alrajhi_astra_top_bar_injection() {
    if (!get_theme_mod('alrajhi_show_topbar', true)) {
        return;
    }
    $mobile_class = !get_theme_mod('alrajhi_show_topbar_mobile', false) ? ' hide-on-mobile' : '';
    ?>
    <div class="alrajhi-top-bar<?php echo esc_attr($mobile_class); ?>">
        <div class="ast-container">
            <div class="top-bar-content">
                <div class="top-bar-message">
                    <?php echo esc_html(get_theme_mod('alrajhi_topbar_text', __('🚀 شحن سريع لجميع أنحاء المملكة | قطع غيار تويوتا الأصلية 100%', 'astra-child'))); ?>
                </div>
                <div class="top-bar-links">
                    <a href="tel:<?php echo esc_attr(get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567')); ?>">📞 <?php echo esc_html(get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567')); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('astra_header_before', 'alrajhi_astra_top_bar_injection', 10);

/**
 * 2. Inject Garage Bar (Vehicle) - Later Priority
 */
function alrajhi_astra_garage_bar_injection() {
    ?>
    <div id="alrajhi-garage-bar" class="alrajhi-garage-bar" style="display:none;">
        <div class="ast-container">
            <div class="garage-content">
                <span class="garage-icon">🚗</span>
                <span class="garage-text">سيارتي: <strong id="garage-vehicle"></strong></span>
                <button class="garage-change-btn" onclick="alrajhiClearGarage()">تغيير</button>
                <button class="garage-close-btn" onclick="alrajhiCloseGarage()">✕</button>
            </div>
        </div>
    </div>
    <?php
}
add_action('astra_header_before', 'alrajhi_astra_garage_bar_injection', 11);

/**
 * 3. Mobile Sidebar - REMOVED
 * Now using Astra's native mobile menu (ast-mobile-popup-inner)
 */
// Function removed to prevent duplicate mobile menus

/**
 * 4. Inject Cart Sidebar
 */
function alrajhi_astra_cart_sidebar() {
    ?>
    <!-- Cart Sidebar -->
    <div class="cart-sidebar-overlay"></div>
    <div class="cart-sidebar">
        <div class="cart-sidebar-header">
            <h3 class="cart-sidebar-title">🛒 سلة المشتريات</h3>
            <button class="close-cart-sidebar">&times;</button>
        </div>
        <div class="cart-sidebar-content">
            <?php the_widget('WC_Widget_Cart', array('title' => '')); ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'alrajhi_astra_cart_sidebar');

/**
 * 5. Inject Dark Mode Toggle (Floating) - REMOVED
 * Replaced by Dark Mode Widget (Alrajhi_Dark_Mode_Widget)
 */
// function alrajhi_inject_dark_mode_toggle() { ... } removed
// add_action('wp_footer', 'alrajhi_inject_dark_mode_toggle');

/**
 * 6. SEO: Site Title/Logo as H1 only on Front Page
 */
add_filter('astra_logo', function($html) {
    if (is_front_page()) {
        return '<h1 class="site-title-h1">' . $html . '</h1>';
    }
    return $html;
});

// Also keep the tag filters just in case
add_filter('astra_site_title_tag', 'alrajhi_h1_on_home');
add_filter('astra_logo_wrapper_tag', 'alrajhi_h1_on_home');

function alrajhi_h1_on_home($tag) {
    if (is_front_page()) {
        return 'h1';
    }
    return $tag;
}
