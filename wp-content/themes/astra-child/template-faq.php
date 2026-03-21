<?php
/**
 * Template Name: FAQ Page Custom
 */

get_header();
?>

<div class="pwa-page-wrapper faq-page">
    <div class="container" style="padding-top: 40px; padding-bottom: 100px;">
        
        <header class="page-header" style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 2.2rem; color: var(--color-primary); margin-bottom: 15px;">الأسئلة الشائعة</h1>
            <p style="color: var(--text-secondary);">إليك إجابات على أكثر الأسئلة تكراراً من عملائنا</p>
        </header>

        <div class="faq-container" style="max-width: 800px; margin: 0 auto;">
            
            <!-- Category: Orders -->
            <div class="faq-category" style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px; font-size: 1.3rem;">📦 الطلبات والشحن</h3>
                
                <div class="faq-item box-shadow-card">
                    <div class="faq-question">
                        <span>كم يستغرق توصيل الطلب؟</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>يستغرق التوصيل داخل الرياض 1-2 يوم عمل، وإلى باقي مدن المملكة 3-5 أيام عمل.</p>
                    </div>
                </div>

                <div class="faq-item box-shadow-card">
                    <div class="faq-question">
                        <span>كيف يمكنني تتبع طلبي؟</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>يمكنك تتبع طلبك من خلال صفحة "حسابي" ثم "الطلبات"، أو عبر صفحة تتبع الطلب باستخدام رقم الطلب ورقم الجوال.</p>
                    </div>
                </div>
            </div>

            <!-- Category: Products -->
            <div class="faq-category" style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px; font-size: 1.3rem;">🔧 المنتجات والضمان</h3>
                
                <div class="faq-item box-shadow-card">
                    <div class="faq-question">
                        <span>هل جميع القطع أصلية؟</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>نعم، نحن نضمن أن جميع قطع الغيار المعروضة في متجر الراجحي هي قطع أصلية 100% من تويوتا (Toyota Genuine Parts).</p>
                    </div>
                </div>

                <div class="faq-item box-shadow-card">
                    <div class="faq-question">
                        <span>ما هي سياسة الاسترجاع؟</span>
                        <span class="toggle-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>يمكنك استرجاع القطعة خلال 7 أيام من تاريخ الاستلام بشرط أن تكون في حالتها الأصلية ولم يتم استخدامها. تطبق الشروط والأحكام.</p>
                    </div>
                </div>
            </div>

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

<!-- Inline CSS for FAQ (or move to style file) -->
<style>
    .faq-item {
        background: var(--bg-primary);
        border-radius: 12px;
        margin-bottom: 15px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .faq-question {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: var(--text-primary);
    }
    .faq-answer {
        display: none;
        padding: 0 20px 20px;
        color: var(--text-secondary);
        line-height: 1.6;
        border-top: 1px solid rgba(0,0,0,0.05);
        margin-top: -10px;
        padding-top: 15px;
    }
    .faq-item.active {
        box-shadow: 0 5px 15px rgba(235,10,30,0.05);
        border-color: rgba(235,10,30,0.2);
    }
    .faq-item.active .toggle-icon {
        transform: rotate(45deg);
        color: var(--color-primary);
    }
    .toggle-icon {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }
</style>

<script>
jQuery(document).ready(function($) {
    $('.faq-question').on('click', function() {
        const parent = $(this).closest('.faq-item');
        parent.toggleClass('active');
        parent.find('.faq-answer').slideToggle(200);
    });
});
</script>

<?php get_footer(); ?>
