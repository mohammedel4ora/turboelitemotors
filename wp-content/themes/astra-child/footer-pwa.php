<?php
/**
 * PWA Footer Template
 * Note: Cart Sidebar is injected via astra-hooks.php (alrajhi_astra_cart_sidebar function)
 */
?>

<!-- PWA Footer -->
<footer class="pwa-footer">
    <div class="container">
        <div class="footer-content">
            <!-- About Section -->
            <div class="footer-section about">
                <h3 class="footer-title"><?php echo esc_html(get_theme_mod('alrajhi_footer_about_title', 'TOYOTA PARTS')); ?></h3>
                <p class="footer-about-text"><?php echo esc_html(get_theme_mod('alrajhi_footer_about_text', 'وجهتك الأولى لقطع غيار تويوتا الأصلية في المملكة. نضمن لك الجودة والتوافق التام مع سيارتك.')); ?></p>
                <div class="social-links">
                    <a href="<?php echo esc_url(get_theme_mod('alrajhi_twitter_url', '#')); ?>" class="social-link" target="_blank" rel="noopener">𝕏</a>
                    <a href="<?php echo esc_url(get_theme_mod('alrajhi_instagram_url', '#')); ?>" class="social-link" target="_blank" rel="noopener">📸</a>
                    <a href="<?php echo esc_url(get_theme_mod('alrajhi_facebook_url', '#')); ?>" class="social-link" target="_blank" rel="noopener">📘</a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section links">
                <h4 class="footer-subtitle"><?php echo esc_html__('روابط سريعة', 'astra-child'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/'); ?>"><?php echo esc_html__('الرئيسية', 'astra-child'); ?></a></li>
                    <li><a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><?php echo esc_html__('المتجر', 'astra-child'); ?></a></li>
                    
                    <?php 
                    $about_id = get_theme_mod('alrajhi_about_page');
                    if ($about_id) : ?>
                        <li><a href="<?php echo get_permalink($about_id); ?>"><?php echo get_the_title($about_id); ?></a></li>
                    <?php endif; ?>

                    <?php 
                    $contact_id = get_theme_mod('alrajhi_contact_page');
                    if ($contact_id) : ?>
                        <li><a href="<?php echo get_permalink($contact_id); ?>"><?php echo get_the_title($contact_id); ?></a></li>
                    <?php endif; ?>

                    <?php 
                    $privacy_id = get_theme_mod('alrajhi_privacy_page');
                    if ($privacy_id) : ?>
                        <li><a href="<?php echo get_permalink($privacy_id); ?>"><?php echo get_the_title($privacy_id); ?></a></li>
                    <?php endif; ?>

                    <?php 
                    $terms_id = get_theme_mod('alrajhi_terms_page');
                    if ($terms_id) : ?>
                        <li><a href="<?php echo get_permalink($terms_id); ?>"><?php echo get_the_title($terms_id); ?></a></li>
                    <?php endif; ?>

                    <?php 
                    $refund_id = get_theme_mod('alrajhi_refund_page');
                    if ($refund_id) : ?>
                        <li><a href="<?php echo get_permalink($refund_id); ?>"><?php echo get_the_title($refund_id); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-section contact">
                <h4 class="footer-subtitle">تواصل معنا</h4>
                <ul class="contact-info">
                    <li>
                        <span class="icon">📞</span>
                        <span class="text footer-phone-text" dir="ltr"><?php echo esc_html(get_theme_mod('alrajhi_footer_phone', '+966 50 123 4567')); ?></span>
                    </li>
                    <li>
                        <span class="icon">📧</span>
                        <span class="text footer-email-text"><?php echo esc_html(get_theme_mod('alrajhi_footer_email', 'info@toyotaparts.sa')); ?></span>
                    </li>
                    <li>
                        <span class="icon">📍</span>
                        <span class="text footer-location-text"><?php echo esc_html(get_theme_mod('alrajhi_footer_location', 'الرياض، المملكة العربية السعودية')); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p><span class="footer-copyright-text"><?php echo esc_html(get_theme_mod('alrajhi_footer_copyright', 'Toyota Parts. جميع الحقوق محفوظة.')); ?></span> &copy; <?php echo date('Y'); ?></p>
            <div class="payment-icons">
                <span>💳</span>
                <span> Pay</span>
                <span>mada</span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
