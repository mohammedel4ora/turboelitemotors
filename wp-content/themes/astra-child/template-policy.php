<?php
/**
 * Template Name: Policy Page Custom
 */

get_header();
?>

<div class="policy-page-wrapper">
    <div class="policy-page-container">
        <?php while (have_posts()) : the_post(); ?>
            <header class="policy-header">
                <h1><?php the_title(); ?></h1>
                <div class="last-updated">
                    <?php echo esc_html__('آخر تحديث:', 'astra-child'); ?> <?php echo get_the_modified_date(); ?>
                </div>
            </header>

            <div class="policy-content">
                <?php the_content(); ?>
            </div>

            <footer class="policy-footer">
                <p><?php echo esc_html__('إذا كان لديك أي استفسار حول هذه السياسة، يرجى', 'astra-child'); ?> 
                   <a href="<?php echo esc_url(get_permalink(get_theme_mod('alrajhi_contact_page'))); ?>">
                       <?php echo esc_html__('التواصل معنا', 'astra-child'); ?>
                   </a>
                </p>
            </footer>
        <?php endwhile; ?>
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
