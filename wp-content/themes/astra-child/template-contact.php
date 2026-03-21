<?php
/**
 * Template Name: Contact Page Custom
 */

get_header();

// Fetch dynamic data from Customizer
$phone = get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567');
$email = get_theme_mod('alrajhi_footer_email', 'info@toyotaparts.sa');
$location = get_theme_mod('alrajhi_footer_location', 'الرياض، المملكة العربية السعودية');
$map_code = get_theme_mod('alrajhi_contact_map');
?>

<div class="contact-page-wrapper">
    <div class="contact-container">
        <div class="contact-grid">
            <!-- Sidebar: Contact Info -->
            <div class="contact-info-sidebar">
                <h1><?php echo esc_html__('تواصل معنا', 'astra-child'); ?></h1>
                <p><?php echo esc_html__('نحن دائماً هنا لمساعدتك في العثور على قطع غيار تويوتا الأصلية.', 'astra-child'); ?></p>
                
                <div class="info-item">
                    <div class="icon">📍</div>
                    <div class="text">
                        <strong><?php echo esc_html__('الموقع', 'astra-child'); ?></strong><br>
                        <?php echo esc_html($location); ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="icon">📞</div>
                    <div class="text">
                        <strong><?php echo esc_html__('الهاتف', 'astra-child'); ?></strong><br>
                        <?php echo esc_html($phone); ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="icon">📧</div>
                    <div class="text">
                        <strong><?php echo esc_html__('البريد الإلكتروني', 'astra-child'); ?></strong><br>
                        <a href="mailto:<?php echo esc_attr($email); ?>" style="color: #fff;"><?php echo esc_html($email); ?></a>
                    </div>
                </div>
            </div>

            <!-- Form Area -->
            <div class="contact-form-wrapper">
                <h2><?php echo esc_html__('أرسل لنا رسالة', 'astra-child'); ?></h2>
                <form action="#" method="POST" class="custom-contact-form">
                    <div class="form-group">
                        <label><?php echo esc_html__('الاسم بالكامل', 'astra-child'); ?></label>
                        <input type="text" name="your_name" placeholder="<?php echo esc_attr__('أدخل اسمك هنا', 'astra-child'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo esc_html__('رقم الجوال', 'astra-child'); ?></label>
                        <input type="tel" name="your_phone" placeholder="<?php echo esc_attr__('05XXXXXXXX', 'astra-child'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo esc_html__('موضوع الرسالة', 'astra-child'); ?></label>
                        <input type="text" name="subject" placeholder="<?php echo esc_attr__('مثلاً: استفسار عن قطعة غيار', 'astra-child'); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo esc_html__('رسالتك', 'astra-child'); ?></label>
                        <textarea name="message" rows="5" placeholder="<?php echo esc_attr__('اكتب رسالتك هنا...', 'astra-child'); ?>" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn"><?php echo esc_html__('إرسال الطلب الآن', 'astra-child'); ?></button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <?php if ($map_code) : ?>
            <div class="map-section">
                <?php echo $map_code; // Sanitzed on save via Customizer ?>
            </div>
        <?php endif; ?>
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
