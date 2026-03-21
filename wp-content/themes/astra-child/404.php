<?php
/**
 * The template for displaying 404 pages (not found).
 */

get_header();
?>

<div class="pwa-page-wrapper error-404-page">
    <div class="container" style="padding-top: 60px; padding-bottom: 100px; text-align: center;">
        
        <div class="error-image" style="margin-bottom: 30px;">
            <svg width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); opacity: 0.5;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <h1 class="page-title" style="font-size: 4rem; color: var(--color-primary); line-height: 1; margin-bottom: 10px;">404</h1>
        <h2 style="font-size: 1.5rem; margin-bottom: 20px; color: var(--text-primary);">عفواً، الصفحة غير موجودة</h2>
        <p style="color: var(--text-secondary); max-width: 400px; margin: 0 auto 40px;">قد يكون الرابط الذي تتبعه معطلاً أو تم نقل الصفحة. لا تقلق، يمكنك العثور على ما تبحث عنه أدناه.</p>

        <!-- Search Form -->
        <div class="error-search" style="max-width: 500px; margin: 0 auto 40px;">
             <?php get_search_form(); ?>
        </div>

        <!-- Action Buttons -->
        <div class="error-actions" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 12px 30px;">العودة للرئيسية</a>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-secondary" style="padding: 12px 30px;">تصفح المنتجات</a>
        </div>

    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="pwa-bottom-nav">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item">
            <span class="icon">🏠</span>
            <span class="label">الرئيسية</span>
        </a>
        <a href="#" class="nav-item search-trigger">
            <span class="icon">🔍</span>
            <span class="label">بحث</span>
        </a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="nav-item cart-trigger">
            <span class="icon">
                🛒
                <?php 
                $count = WC()->cart->get_cart_contents_count();
                if ($count > 0) {
                    echo '<span class="cart-count">' . $count . '</span>';
                }
                ?>
            </span>
            <span class="label">السلة</span>
        </a>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>" class="nav-item">
            <span class="icon">👤</span>
            <span class="label">حسابي</span>
        </a>
    </nav>
</div>

<?php get_footer(); ?>
