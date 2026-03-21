<?php
/**
 * ARJ Login Customizer Settings
 * 
 * Adds WordPress Customizer settings for the secure login page.
 * Allows dynamic customization of login page appearance and behavior.
 * 
 * @package Astra-Child
 * @subpackage Customizer
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register customizer settings for the login page
 * 
 * @param WP_Customize_Manager $wp_customize The customizer manager object
 */
function arj_login_customizer_settings($wp_customize) {

    /**
     * Panel: Login Page Settings
     */
    $wp_customize->add_panel('arj_login_panel', array(
        'title'       => __('إعدادات صفحة تسجيل الدخول', 'astra-child'),
        'description' => __('تخصيص مظهر وسلوك صفحة تسجيل الدخول المخصصة.', 'astra-child'),
        'priority'    => 150,
    ));

    // =========================================================================
    // Section: Appearance Settings
    // =========================================================================
    $wp_customize->add_section('arj_login_appearance', array(
        'title'       => __('المظهر والتصميم', 'astra-child'),
        'description' => __('تخصيص الشعار والألوان والخلفية.', 'astra-child'),
        'panel'       => 'arj_login_panel',
        'priority'    => 10,
    ));

    // Logo Setting
    $wp_customize->add_setting('arj_login_logo', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'arj_login_logo', array(
        'label'       => __('شعار صفحة الدخول', 'astra-child'),
        'description' => __('اختر شعاراً مخصصاً لصفحة تسجيل الدخول.', 'astra-child'),
        'section'     => 'arj_login_appearance',
        'mime_type'   => 'image',
    )));

    // Background Type
    $wp_customize->add_setting('arj_login_bg_type', array(
        'default'           => 'color',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_login_bg_type', array(
        'label'   => __('نوع الخلفية', 'astra-child'),
        'section' => 'arj_login_appearance',
        'type'    => 'radio',
        'choices' => array(
            'color' => __('لون ثابت', 'astra-child'),
            'image' => __('صورة', 'astra-child'),
        ),
    ));

    // Background Color
    $wp_customize->add_setting('arj_login_bg_color', array(
        'default'           => '#f5f5f5',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'arj_login_bg_color', array(
        'label'       => __('لون الخلفية', 'astra-child'),
        'section'     => 'arj_login_appearance',
        'description' => __('يظهر عند اختيار "لون ثابت" كنوع الخلفية.', 'astra-child'),
    )));

    // Background Image
    $wp_customize->add_setting('arj_login_bg_image', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'arj_login_bg_image', array(
        'label'       => __('صورة الخلفية', 'astra-child'),
        'section'     => 'arj_login_appearance',
        'description' => __('تظهر عند اختيار "صورة" كنوع الخلفية.', 'astra-child'),
    )));

    // Form Background Color
    $wp_customize->add_setting('arj_login_form_bg', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'arj_login_form_bg', array(
        'label'   => __('لون خلفية النموذج', 'astra-child'),
        'section' => 'arj_login_appearance',
    )));

    // Primary Color
    $wp_customize->add_setting('arj_login_primary_color', array(
        'default'           => '#eb0a1e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'arj_login_primary_color', array(
        'label'       => __('اللون الأساسي', 'astra-child'),
        'description' => __('يُستخدم للزر والروابط.', 'astra-child'),
        'section'     => 'arj_login_appearance',
    )));

    // =========================================================================
    // Section: Text Settings
    // =========================================================================
    $wp_customize->add_section('arj_login_text', array(
        'title'    => __('النصوص', 'astra-child'),
        'panel'    => 'arj_login_panel',
        'priority' => 20,
    ));

    // Form Title
    $wp_customize->add_setting('arj_login_form_title', array(
        'default'           => __('تسجيل الدخول', 'astra-child'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_login_form_title', array(
        'label'   => __('عنوان النموذج', 'astra-child'),
        'section' => 'arj_login_text',
        'type'    => 'text',
    ));

    // Button Text
    $wp_customize->add_setting('arj_login_button_text', array(
        'default'           => __('دخول', 'astra-child'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_login_button_text', array(
        'label'   => __('نص زر الدخول', 'astra-child'),
        'section' => 'arj_login_text',
        'type'    => 'text',
    ));

    // Lost Password Text
    $wp_customize->add_setting('arj_lost_password_text', array(
        'default'           => __('نسيت كلمة المرور؟', 'astra-child'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_lost_password_text', array(
        'label'   => __('نص رابط استعادة كلمة المرور', 'astra-child'),
        'section' => 'arj_login_text',
        'type'    => 'text',
    ));

    // =========================================================================
    // Section: Behavior Settings
    // =========================================================================
    $wp_customize->add_section('arj_login_behavior', array(
        'title'    => __('السلوك والتوجيه', 'astra-child'),
        'panel'    => 'arj_login_panel',
        'priority' => 30,
    ));

    // Redirect Page After Login
    $wp_customize->add_setting('arj_login_redirect_page', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('arj_login_redirect_page', array(
        'label'       => __('صفحة إعادة التوجيه بعد الدخول', 'astra-child'),
        'description' => __('اختر الصفحة التي سيتم توجيه المستخدم إليها بعد تسجيل الدخول. اتركها فارغة للتوجيه إلى صفحة "حسابي".', 'astra-child'),
        'section'     => 'arj_login_behavior',
        'type'        => 'dropdown-pages',
    ));

    // =========================================================================
    // Section: Security Settings (reCAPTCHA)
    // =========================================================================
    $wp_customize->add_section('arj_login_security', array(
        'title'       => __('الأمان (reCAPTCHA)', 'astra-child'),
        'description' => __('إعدادات Google reCAPTCHA لحماية النموذج من الروبوتات.', 'astra-child'),
        'panel'       => 'arj_login_panel',
        'priority'    => 40,
    ));

    // reCAPTCHA Site Key
    $wp_customize->add_setting('arj_recaptcha_site_key', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_recaptcha_site_key', array(
        'label'       => __('مفتاح الموقع (Site Key)', 'astra-child'),
        'description' => __('مفتاح الموقع من Google reCAPTCHA v2.', 'astra-child'),
        'section'     => 'arj_login_security',
        'type'        => 'text',
    ));

    // reCAPTCHA Secret Key
    $wp_customize->add_setting('arj_recaptcha_secret_key', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('arj_recaptcha_secret_key', array(
        'label'       => __('المفتاح السري (Secret Key)', 'astra-child'),
        'description' => __('المفتاح السري من Google reCAPTCHA v2. يُحفظ بشكل آمن ولا يظهر للمستخدمين.', 'astra-child'),
        'section'     => 'arj_login_security',
        'type'        => 'password',
    ));

    // reCAPTCHA Info Link
    $wp_customize->add_setting('arj_recaptcha_info', array(
        'default'           => '',
        'sanitize_callback' => '__return_empty_string',
    ));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'arj_recaptcha_info', array(
        'label'       => __('كيفية الحصول على مفاتيح reCAPTCHA', 'astra-child'),
        'description' => sprintf(
            /* translators: %s: Google reCAPTCHA URL */
            __('يمكنك الحصول على مفاتيح reCAPTCHA مجاناً من <a href="%s" target="_blank">Google reCAPTCHA</a>.', 'astra-child'),
            'https://www.google.com/recaptcha/admin'
        ),
        'section'     => 'arj_login_security',
        'type'        => 'hidden',
    )));
}
add_action('customize_register', 'arj_login_customizer_settings');

/**
 * Enqueue customizer live preview scripts
 */
function arj_login_customizer_preview_js() {
    wp_enqueue_script(
        'arj-login-customizer-preview',
        get_stylesheet_directory_uri() . '/assets/js/login-customizer-preview.js',
        array('customize-preview', 'jquery'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'arj_login_customizer_preview_js');

/**
 * Enqueue customizer control scripts
 */
function arj_login_customizer_controls_js() {
    wp_enqueue_script(
        'arj-login-customizer-controls',
        get_stylesheet_directory_uri() . '/assets/js/login-customizer-controls.js',
        array('customize-controls', 'jquery'),
        '1.0.0',
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'arj_login_customizer_controls_js');
