<?php
/**
 * Template Name: Order Tracking Page
 */

get_header();
?>

<div class="pwa-page-wrapper tracking-page">
    <div class="container" style="padding-top: 40px; padding-bottom: 100px;">
        
        <header class="page-header" style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 2rem; color: var(--color-primary); margin-bottom: 10px;">تتبع طلبك</h1>
            <p style="color: var(--text-secondary);">أدخل رقم الطلب والبريد الإلكتروني لمعرفة حالة شحنتك</p>
        </header>

        <div class="tracking-form-container box-shadow-card" style="background: var(--bg-primary); padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
            <?php echo do_shortcode('[woocommerce_order_tracking]'); ?>
        </div>

        <!-- Additional Help -->
        <div style="text-align: center; margin-top: 40px;">
            <p style="color: var(--text-secondary); margin-bottom: 15px;">واجهت مشكلة؟</p>
            <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="btn btn-secondary" style="display: inline-block; width: auto; padding: 10px 30px;">تواصل مع الدعم الفني</a>
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

<style>
    /* Tracking Form Customization */
    .tracking-form-container form p {
        margin-bottom: 20px;
    }
    .tracking-form-container label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .tracking-form-container input[type="text"],
    .tracking-form-container input[type="email"] {
        width: 100%;
        padding: 12px;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 8px;
        background: var(--bg-secondary);
    }
    .tracking-form-container button[type="submit"] {
        width: 100%;
        padding: 15px;
        background: var(--color-primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }
    .tracking-form-container button[type="submit"]:hover {
        background: var(--color-primary-dark);
    }
</style>

<?php get_footer(); ?>
