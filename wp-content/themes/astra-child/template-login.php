<?php
/**
 * Template Name: صفحة تسجيل الدخول
 * Template Post Type: page
 * 
 * Custom secure login page template with full security implementation.
 * 
 * Features:
 * - CSRF Protection via WordPress Nonce
 * - XSS Protection via input sanitization
 * - Integration with WordPress Customizer
 * - RTL/Arabic support
 * - Responsive design
 * - reCAPTCHA support (optional)
 * 
 * @package Astra-Child
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Redirect logged-in users to their account
if (is_user_logged_in()) {
    $redirect_url = '';
    
    // Try Customizer setting first
    $customizer_redirect = get_theme_mod('arj_login_redirect_page', '');
    if (!empty($customizer_redirect) && is_numeric($customizer_redirect)) {
        $redirect_url = get_permalink((int) $customizer_redirect);
    }
    
    // Fall back to WooCommerce My Account
    if (empty($redirect_url) && function_exists('wc_get_page_id')) {
        $myaccount_page = wc_get_page_id('myaccount');
        if ($myaccount_page > 0) {
            $redirect_url = get_permalink($myaccount_page);
        }
    }
    
    // Final fallback to home
    if (empty($redirect_url)) {
        $redirect_url = home_url('/');
    }
    
    wp_safe_redirect($redirect_url);
    exit;
}

// Get the login handler instance
$login_handler = ARJ_Secure_Login::get_instance();

// Get customizer settings for styling
$logo_id = get_theme_mod('arj_login_logo', '');
$background_type = get_theme_mod('arj_login_bg_type', 'color');
$background_color = get_theme_mod('arj_login_bg_color', '#f5f5f5');
$background_image = get_theme_mod('arj_login_bg_image', '');
$form_background = get_theme_mod('arj_login_form_bg', '#ffffff');
$primary_color = get_theme_mod('arj_login_primary_color', '#eb0a1e');

// Build inline styles
$page_styles = '';
if ($background_type === 'image' && !empty($background_image)) {
    $page_styles .= "background-image: url('" . esc_url($background_image) . "'); background-size: cover; background-position: center;";
} else {
    $page_styles .= "background-color: " . esc_attr($background_color) . ";";
}

// Force Full Width Layout (No Sidebar)
add_filter('astra_page_layout', function() { return 'no-sidebar'; });

// Enqueue login styles and scripts
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('arj-login', get_stylesheet_directory_uri() . '/assets/css/login.css', array(), '1.0.0');
    wp_enqueue_script('arj-login', get_stylesheet_directory_uri() . '/assets/js/login.js', array('jquery'), '1.0.0', true);
    
    // Dashicons for password toggle
    wp_enqueue_style('dashicons');
    
    // reCAPTCHA if enabled
    $recaptcha_site_key = get_theme_mod('arj_recaptcha_site_key', '');
    if (!empty($recaptcha_site_key)) {
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
    }
}, 20);

get_header();
?>

<div class="arj-login-page" style="<?php echo esc_attr($page_styles); ?>">
    <div class="arj-login-container">
        
        <!-- Login Card -->
        <div class="arj-login-card" style="background-color: <?php echo esc_attr($form_background); ?>;">
            
            <!-- Logo -->
            <?php if (!empty($logo_id)) : 
                $logo_url = wp_get_attachment_image_url($logo_id, 'medium');
            ?>
                <div class="arj-login-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
                    </a>
                </div>
            <?php else : ?>
                <div class="arj-login-logo arj-login-logo-text">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php echo esc_html(get_bloginfo('name')); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <?php 
            // Get redirect URL from query string if provided
            $redirect_to = isset($_GET['redirect_to']) 
                ? esc_url_raw(wp_unslash($_GET['redirect_to'])) 
                : '';
            
            $login_handler->render_login_form(array(
                'redirect_to'    => $redirect_to,
                'show_remember'  => true,
                'show_lost_pass' => true,
                'show_register'  => get_option('users_can_register'),
            )); 
            ?>

        </div><!-- .arj-login-card -->

        <!-- Security Notice -->
        <div class="arj-login-security-notice">
            <span class="arj-security-icon">🔒</span>
            <span class="arj-security-text">
                <?php esc_html_e('اتصال آمن ومشفر', 'astra-child'); ?>
            </span>
        </div>

    </div><!-- .arj-login-container -->
</div><!-- .arj-login-page -->

<?php
/**
 * Add CSS Variables for dynamic styling
 */
add_action('wp_head', function() use ($primary_color, $form_background) {
    ?>
    <style id="arj-login-dynamic-css">
        :root {
            --arj-login-primary: <?php echo esc_attr($primary_color); ?>;
            --arj-login-primary-hover: <?php echo esc_attr($primary_color); ?>dd;
            --arj-login-form-bg: <?php echo esc_attr($form_background); ?>;
        }
    </style>
    <?php
}, 100);

get_footer();
?>
