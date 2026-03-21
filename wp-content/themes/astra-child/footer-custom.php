<?php
/**
 * Custom Footer Template
 * Based on PWA Design
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<footer class="custom-footer footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4><?php echo esc_html(get_theme_mod('alrajhi_footer_about_title', __('عن الموقع', 'astra-child'))); ?></h4>
                <p><?php echo esc_html(get_theme_mod('alrajhi_footer_about_text', __('نوفر قطع الغيار الأصلية لجميع موديلات تيوتا مع ضمان الجودة والتوافقية', 'astra-child'))); ?></p>
            </div>
            <div class="footer-section">
                <h4><?php echo esc_html__('روابط سريعة', 'astra-child'); ?></h4>
                <ul class="footer-links">
                    <?php
                    $about_page_id = get_theme_mod('alrajhi_about_page');
                    $contact_page_id = get_theme_mod('alrajhi_contact_page');
                    $privacy_page_id = get_theme_mod('alrajhi_privacy_page');
                    $terms_page_id = get_theme_mod('alrajhi_terms_page');
                    $refund_page_id = get_theme_mod('alrajhi_refund_page');
                    ?>
                    <?php if ($about_page_id) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($about_page_id)); ?>"><?php echo get_the_title($about_page_id); ?></a></li>
                    <?php endif; ?>
                    
                    <?php if ($contact_page_id) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($contact_page_id)); ?>"><?php echo get_the_title($contact_page_id); ?></a></li>
                    <?php endif; ?>

                    <?php if ($privacy_page_id) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($privacy_page_id)); ?>"><?php echo get_the_title($privacy_page_id); ?></a></li>
                    <?php endif; ?>

                    <?php if ($terms_page_id) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($terms_page_id)); ?>"><?php echo get_the_title($terms_page_id); ?></a></li>
                    <?php endif; ?>

                    <?php if ($refund_page_id) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($refund_page_id)); ?>"><?php echo get_the_title($refund_page_id); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-section">
                <h4><?php echo esc_html__('تواصل معنا', 'astra-child'); ?></h4>
                <?php
                $phone = get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567');
                $email = get_theme_mod('alrajhi_footer_email', 'info@toyotaparts.sa');
                $location = get_theme_mod('alrajhi_footer_location', 'الرياض، المملكة العربية السعودية');
                ?>
                <p>📞 <?php echo esc_html($phone); ?></p>
                <p>📧 <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                <p>📍 <?php echo esc_html($location); ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php echo esc_html__('جميع الحقوق محفوظة.', 'astra-child'); ?></p>
            <p class="pwa-status">
                <span class="status-dot" id="onlineStatus"></span>
                <span id="connectionStatus"><?php echo esc_html__('متصل', 'astra-child'); ?></span>
            </p>
        </div>
    </div>
</footer>

