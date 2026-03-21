<?php
/**
 * Template Name: About Page Custom
 */

get_header();

// Fetch content from Customizer
$title = get_theme_mod('alrajhi_about_title', __('الراجحي لقطع الغيار: ريادة وتميز في عالم تويوتا', 'astra-child'));
$description = get_theme_mod('alrajhi_about_description', __('نحن مؤسسة رائدة متخصصة في توفير كافة قطع غيار تويوتا الأصلية بالمملكة العربية السعودية، نهدف إلى تقديم أفضل جودة بأسعار تنافسية.', 'astra-child'));
$image = get_theme_mod('alrajhi_about_image');

// Default image if not set
if (empty($image)) {
    $image = get_stylesheet_directory_uri() . '/assets/images/about-default.jpg';
}
?>

<div class="about-page-wrapper">
    <div class="about-page-container">
        <section class="about-hero">
            <div class="about-content-side">
                <h1><?php echo esc_html($title); ?></h1>
                <p><?php echo nl2br(esc_html($description)); ?></p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="number">+15</span>
                        <span class="label"><?php echo esc_html__('سنة خبرة', 'astra-child'); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="number">+10k</span>
                        <span class="label"><?php echo esc_html__('عميل سعيد', 'astra-child'); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="number">+50k</span>
                        <span class="label"><?php echo esc_html__('قطعة متوفرة', 'astra-child'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="about-image-side">
                <?php if ($image) : ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                <?php endif; ?>
            </div>
        </section>

        <!-- Placeholder for Vision & Mission -->
        <section class="about-values" style="margin-top: 100px; text-align: center;">
            <h2 style="font-size: 2.5rem; margin-bottom: 40px; color: #333;"><?php echo esc_html__('قيمنا ورؤيتنا', 'astra-child'); ?></h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <h3 style="color: var(--color-primary); margin-bottom: 20px;"><?php echo esc_html__('رؤيتنا', 'astra-child'); ?></h3>
                    <p><?php echo esc_html__('أن نكون الخيار الأول والأكثر موثوقية لعملاء تويوتا في المملكة لتأمين احتياجاتهم من قطع الغيار الأصلية.', 'astra-child'); ?></p>
                </div>
                <div style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <h3 style="color: var(--color-primary); margin-bottom: 20px;"><?php echo esc_html__('مهمتنا', 'astra-child'); ?></h3>
                    <p><?php echo esc_html__('توفير تجربة تسوق سهلة وآمنة مع ضمان أعلى معايير الجودة والدقة في اختيار القطع المناسبة لكل عميل.', 'astra-child'); ?></p>
                </div>
            </div>
        </section>
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

<?php
get_footer();
?>
