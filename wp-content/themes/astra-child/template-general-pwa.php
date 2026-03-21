<?php
/**
 * Template Name: General PWA Page
 * Description: A generic template for any page (Terms, Returns, Privacy) with PWA styling and Bottom Nav.
 */

get_header();
?>

<div class="pwa-page-wrapper general-pwa-page">
    <div class="container" style="padding-top: 40px; padding-bottom: 80px;">
        
        <?php while (have_posts()) : the_post(); ?>
            <header class="page-header" style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2rem; color: var(--color-primary); margin-bottom: 10px;"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="lead" style="color: var(--text-secondary);"><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>
            </header>

            <div class="page-content box-shadow-card" style="background: var(--bg-primary); padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <?php the_content(); ?>
            </div>
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

<?php get_footer(); ?>
