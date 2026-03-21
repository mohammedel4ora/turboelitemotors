<?php
/**
 * PWA Header Template
 */
$cart_count = WC()->cart->get_cart_contents_count();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top Bar -->
<div class="alrajhi-top-bar">
    <div class="container">
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

<!-- My Garage Bar -->
<div id="alrajhi-garage-bar" class="alrajhi-garage-bar" style="display:none;">
    <div class="garage-content">
        <span class="garage-icon">🚗</span>
        <span class="garage-text">سيارتي: <strong id="garage-vehicle"></strong></span>
        <button class="garage-change-btn" onclick="alrajhiClearGarage()">تغيير</button>
        <button class="garage-close-btn" onclick="alrajhiCloseGarage()">✕</button>
    </div>
</div>

<!-- PWA Header -->
<header class="pwa-header">
    <div class="container">
        <div class="header-content">
            
            <!-- Mobile Hamburger Menu (Left) -->
            <button class="mobile-menu-btn btn-icon" aria-label="القائمة">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            <!-- Logo -->
            <div class="logo">
                <a href="<?php echo home_url('/'); ?>" class="logo-link">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <svg class="logo-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="50" cy="50" rx="35" ry="20" fill="none" stroke="currentColor" stroke-width="3" />
                            <ellipse cx="50" cy="50" rx="20" ry="35" fill="none" stroke="currentColor" stroke-width="3" />
                            <circle cx="50" cy="50" r="12" fill="none" stroke="currentColor" stroke-width="3" />
                        </svg>
                        <div class="logo-text">
                            <span class="site-title"><?php echo esc_html(get_theme_mod('alrajhi_header_title', get_bloginfo('name'))); ?></span>
                            <span><?php echo esc_html(get_theme_mod('alrajhi_header_tagline', get_bloginfo('description'))); ?></span>
                        </div>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Navigation (Desktop) -->
            <nav class="pwa-nav">
                <a href="<?php echo home_url('/'); ?>" class="nav-link <?php echo is_front_page() ? 'active' : ''; ?>"><?php echo esc_html__('الرئيسية', 'astra-child'); ?></a>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="nav-link <?php echo is_post_type_archive('product') || is_tax('product_cat') ? 'active' : ''; ?>"><?php echo esc_html__('القطع', 'astra-child'); ?></a>
                
                <?php 
                $about_id = get_theme_mod('alrajhi_about_page');
                if ($about_id) : ?>
                    <a href="<?php echo get_permalink($about_id); ?>" class="nav-link <?php echo is_page($about_id) ? 'active' : ''; ?>"><?php echo get_the_title($about_id); ?></a>
                <?php endif; ?>

                <?php 
                $contact_id = get_theme_mod('alrajhi_contact_page');
                if ($contact_id) : ?>
                    <a href="<?php echo get_permalink($contact_id); ?>" class="nav-link <?php echo is_page($contact_id) ? 'active' : ''; ?>"><?php echo get_the_title($contact_id); ?></a>
                <?php endif; ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <button id="pwaDarkModeToggle" class="btn-icon" aria-label="Dark Mode">
                    <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>

                <!-- Cart Button -->
                <button class="btn-icon header-cart-icon" aria-label="سلة التسوق">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="badge cart-count"><?php echo $cart_count; ?></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar-overlay"></div>
<div class="mobile-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <?php echo esc_html(get_theme_mod('alrajhi_header_title', get_bloginfo('name'))); ?>
        </div>
        <button class="close-sidebar">&times;</button>
    </div>
    <div class="sidebar-content">
        <nav class="mobile-nav">
            <a href="<?php echo home_url('/'); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">🏠 <?php echo esc_html__('الرئيسية', 'astra-child'); ?></a>
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="<?php echo is_post_type_archive('product') ? 'active' : ''; ?>">📦 <?php echo esc_html__('المتجر', 'astra-child'); ?></a>
            
            <?php if ($about_id = get_theme_mod('alrajhi_about_page')) : ?>
                <a href="<?php echo get_permalink($about_id); ?>" class="<?php echo is_page($about_id) ? 'active' : ''; ?>">ℹ️ <?php echo get_the_title($about_id); ?></a>
            <?php endif; ?>

            <?php if ($contact_id = get_theme_mod('alrajhi_contact_page')) : ?>
                <a href="<?php echo get_permalink($contact_id); ?>" class="<?php echo is_page($contact_id) ? 'active' : ''; ?>">📞 <?php echo get_the_title($contact_id); ?></a>
            <?php endif; ?>
            
            <hr>
            
            <?php if ($privacy_id = get_theme_mod('alrajhi_privacy_page')) : ?>
                <a href="<?php echo get_permalink($privacy_id); ?>"><?php echo get_the_title($privacy_id); ?></a>
            <?php endif; ?>
            
            <?php if ($terms_id = get_theme_mod('alrajhi_terms_page')) : ?>
                <a href="<?php echo get_permalink($terms_id); ?>"><?php echo get_the_title($terms_id); ?></a>
            <?php endif; ?>
        </nav>
        
        <div class="sidebar-footer">
            <p><?php echo esc_html(get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567')); ?></p>
            <p><?php echo esc_html(get_theme_mod('alrajhi_footer_email', 'info@toyotaparts.sa')); ?></p>
        </div>
    </div>
</div>
