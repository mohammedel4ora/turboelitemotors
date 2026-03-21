<?php
/**
 * Template Name: Homepage PWA
 * Description: Custom homepage template with PWA design
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Force Full Width Layout (No Sidebar)
add_filter('astra_page_layout', function() { return 'no-sidebar'; });

// Remove default Astra content wrapper
remove_action('astra_content_before', 'astra_container_wrap_open');
remove_action('astra_content_after', 'astra_container_wrap_close');

// Custom PWA Header Helper
get_header();
?>

<!-- Full Width Container -->
<div class="pwa-page-wrapper">


<!-- Hero Section -->
<section class="pwa-hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h2 class="hero-title"><?php echo esc_html(get_theme_mod('alrajhi_hero_title', 'ابحث عن القطعة المثالية لسيارتك')); ?></h2>
            <p class="hero-subtitle"><?php echo esc_html(get_theme_mod('alrajhi_hero_subtitle', 'قطع غيار تويوتا الأصلية مع ضمان التوافقية الكاملة')); ?></p>
            
            <!-- Quick Part Number Search -->
            <div class="quick-search">
                <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" 
                           id="woocommerce-product-search-field" 
                           class="search-field search-input" 
                           placeholder="<?php echo esc_attr(get_theme_mod('alrajhi_search_placeholder', '🔍 ابحث برقم القطعة (Part Number)...')); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" />
                    <input type="hidden" name="post_type" value="product" />
                    <button type="submit" class="btn btn-primary"><?php echo esc_html(get_theme_mod('alrajhi_search_button_text', 'بحث سريع')); ?></button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- YMM Search Section -->
<section class="ymm-search-section">
    <div class="container">
        <h2 class="section-title"><?php echo esc_html(get_theme_mod('alrajhi_ymm_section_title', 'ابحث بحسب موديل السيارة')); ?></h2>
        
        <div class="ymm-widget-wrapper">
            <?php 
            // Display YMM Plugin Shortcode
            if (shortcode_exists('ymm_selector')) {
                echo do_shortcode('[ymm_selector]');
            } else {
                echo '<p class="ymm-notice">⚠️ يرجى تفعيل إضافة Year Make Model Search</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <div class="results-header">
            <h2 class="results-title"><?php echo esc_html(get_theme_mod('alrajhi_results_title', 'المنتجات المميزة')); ?></h2>
            <?php
            $product_count = wp_count_posts('product')->publish;
            ?>
            <div class="results-count"><?php echo $product_count; ?> قطعة</div>
        </div>

        <!-- WooCommerce Products Grid -->
        <div class="products-grid">
            <?php
            // Query WooCommerce Products
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 12,
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $loop = new WP_Query($args);

            if ($loop->have_posts()) :
                while ($loop->have_posts()) : $loop->the_post();
                    global $product;
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php 
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium');
                                } else {
                                    echo '<img src="' . wc_placeholder_img_src() . '" alt="' . get_the_title() . '" />';
                                }
                                ?>
                            </a>
                            <?php if (!$product->is_in_stock()) : ?>
                                <span class="out-of-stock-badge">نفذ من المخزون</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-details">
                            <h3 class="product-name">
                                <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php
                            $sku = $product->get_sku();
                            if ($sku) :
                            ?>
                                <p class="product-sku">OEM: <?php echo $sku; ?></p>
                            <?php endif; ?>
                            
                            <p class="product-price"><?php echo $product->get_price_html(); ?></p>
                            
                            <?php if ($product->is_in_stock()) : ?>
                                <p class="product-stock in-stock">
                                    متوفر: <?php echo $product->get_stock_quantity() ? $product->get_stock_quantity() : '✓'; ?>
                                </p>
                            <?php else : ?>
                                <p class="product-stock out-of-stock">نفذ من المخزون</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-actions">
                            <?php if ($product->is_in_stock()) : ?>
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" 
                                   class="btn btn-primary add_to_cart_button ajax_add_to_cart" 
                                   data-product_id="<?php echo $product->get_id(); ?>"
                                   data-product_sku="<?php echo $product->get_sku(); ?>"
                                   aria-label="Add to cart: <?php echo esc_attr($product->get_name()); ?>"
                                   rel="nofollow">
                                    <span>🛒</span> إضافة للسلة
                                </a>
                            <?php else : ?>
                                <button class="btn btn-primary" disabled>غير متوفر</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="empty-state">
                    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0h-2.586a1 1 0 0 0-.707.293l-2.414 2.414a1 1 0 0 1-.707.293h-3.172a1 1 0 0 1-.707-.293l-2.414-2.414A1 1 0 0 0 6.586 13H4"/>
                    </svg>
                    <h4>لا توجد منتجات</h4>
                    <p>لم يتم إضافة منتجات بعد</p>
                </div>
                <?php
            endif;
            ?>
        </div>
        
        <!-- View All Products Button -->
        <div class="products-footer">
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-secondary">
                عرض جميع المنتجات
            </a>
        </div>
    </div>
</section>


    <!-- Mobile Bottom Navigation -->
    <nav class="pwa-bottom-nav">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item active">
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

</div><!-- .pwa-page-wrapper -->

<?php get_footer(); ?>
