<?php
/**
 * AlRajhi Customizer Setup
 * 
 * This file handles all the customizer registrations for the theme.
 * It separates the logic from functions.php for better maintainability.
 * 
 * @package Astra Child - Toyota Parts
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer Class
 */
class AlRajhi_Customizer {

    /**
     * Setup class
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_settings'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_google_fonts'));
        add_action('wp_head', array($this, 'output_customizer_css'), 100);
    }

    /**
     * Enqueue Google Fonts based on selection
     */
    public function enqueue_google_fonts() {
        $font = get_theme_mod('alrajhi_font_family', 'Cairo');
        $font_url = '';

        switch ($font) {
            case 'Tajawal':
                $font_url = 'https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap';
                break;
            case 'IBM Plex Sans Arabic':
                $font_url = 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;700&display=swap';
                break;
            case 'Almarai':
                $font_url = 'https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap';
                break;
            case 'Amiri':
                $font_url = 'https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&display=swap';
                break;
            default: // Cairo
                $font_url = 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap';
                break;
        }

        wp_enqueue_style('alrajhi-google-fonts', $font_url, array(), null);
    }

    /**
     * Register Customizer controls
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_settings($wp_customize) {
        
        // ---------------------------------------------
        // Panel: Homepage Settings
        // ---------------------------------------------
        $wp_customize->add_panel('alrajhi_homepage_panel', array(
            'title'       => __('إعدادات الصفحة الرئيسية (PWA)', 'astra-child'),
            'priority'    => 10,
            'description' => __('تخصيص جميع أقسام الصفحة الرئيسية الجديدة.', 'astra-child'),
        ));

        // ---------------------------------------------
        // Section: Hero Section
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_hero_section', array(
            'title'    => __('قسم الهيرو (Hero)', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 10,
        ));

        // Hero Title
        $wp_customize->add_setting('alrajhi_hero_title', array(
            'default'           => 'ابحث عن القطعة المثالية لسيارتك',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_hero_title', array(
            'label'    => __('العنوان الرئيسي', 'astra-child'),
            'section'  => 'alrajhi_hero_section',
            'type'     => 'text',
        ));

        // Hero Subtitle
        $wp_customize->add_setting('alrajhi_hero_subtitle', array(
            'default'           => 'قطع غيار تويوتا الأصلية مع ضمان التوافقية الكاملة',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control('alrajhi_hero_subtitle', array(
            'label'    => __('الوصف الفرعي', 'astra-child'),
            'section'  => 'alrajhi_hero_section',
            'type'     => 'textarea',
        ));

        // Hero Background Image
        $wp_customize->add_setting('alrajhi_hero_bg_image', array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'alrajhi_hero_bg_image', array(
            'label'    => __('صورة الخلفية', 'astra-child'),
            'section'  => 'alrajhi_hero_section',
        )));

        // ---------------------------------------------
        // Section: Top Bar Settings
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_topbar_section', array(
            'title'    => __('الشريط العلوي (Top Bar)', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 12,
        ));

        // Show/Hide Top Bar
        $wp_customize->add_setting('alrajhi_show_topbar', array(
            'default'   => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        $wp_customize->add_control('alrajhi_show_topbar', array(
            'label'    => __('عرض الشريط العلوي', 'astra-child'),
            'section'  => 'alrajhi_topbar_section',
            'type'     => 'checkbox',
        ));

        // Top Bar Mobile Visibility
        $wp_customize->add_setting('alrajhi_show_topbar_mobile', array(
            'default'   => false,
            'transport' => 'refresh',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        $wp_customize->add_control('alrajhi_show_topbar_mobile', array(
            'label'    => __('عرض الشريط على الموبايل', 'astra-child'),
            'section'  => 'alrajhi_topbar_section',
            'type'     => 'checkbox',
        ));

        // Top Bar Message
        $wp_customize->add_setting('alrajhi_topbar_text', array(
            'default'           => '🚀 شحن سريع لجميع أنحاء المملكة | قطع غيار تويوتا الأصلية 100%',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_topbar_text', array(
            'label'    => __('رسالة الشريط العلوي', 'astra-child'),
            'section'  => 'alrajhi_topbar_section',
            'type'     => 'text',
        ));

        // Top Bar Background Color
        $wp_customize->add_setting('alrajhi_topbar_bg', array(
            'default'   => '#1a1a1a',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alrajhi_topbar_bg', array(
            'label'    => __('لون خلفية الشريط', 'astra-child'),
            'section'  => 'alrajhi_topbar_section',
        )));

        // Top Bar Text Color
        $wp_customize->add_setting('alrajhi_topbar_color', array(
            'default'   => '#ffffff',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alrajhi_topbar_color', array(
            'label'    => __('لون نص الشريط', 'astra-child'),
            'section'  => 'alrajhi_topbar_section',
        )));

        // ---------------------------------------------
        // Section: Header & Search
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_header_section', array(
            'title'    => __('الهيدر والبحث (Header & Search)', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 15,
        ));

        // Site Name
        $wp_customize->add_setting('alrajhi_header_title', array(
            'default'           => 'TOYOTA PARTS',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_header_title', array(
            'label'    => __('اسم الموقع بالهيدر', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // Site Tagline
        $wp_customize->add_setting('alrajhi_header_tagline', array(
            'default'           => 'قطع غيار تويوتا الأصلية',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_header_tagline', array(
            'label'    => __('الوصف الفرعي بالهيدر', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // Search Placeholder
        $wp_customize->add_setting('alrajhi_search_placeholder', array(
            'default'           => '🔍 ابحث برقم القطعة (Part Number)...',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_search_placeholder', array(
            'label'    => __('نص البحث (Placeholder)', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // Search Button Text
        $wp_customize->add_setting('alrajhi_search_button_text', array(
            'default'           => 'بحث سريع',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_search_button_text', array(
            'label'    => __('نص زر البحث', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // YMM Section Title
        $wp_customize->add_setting('alrajhi_ymm_section_title', array(
            'default'           => 'ابحث عن القطع المناسبة لسيارتك',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_ymm_section_title', array(
            'label'    => __('عنوان قسم اختيار السيارة', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // Results Section Title
        $wp_customize->add_setting('alrajhi_results_title', array(
            'default'           => 'قطع الغيار المتوفرة',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_results_title', array(
            'label'    => __('عنوان نتائج البحث', 'astra-child'),
            'section'  => 'alrajhi_header_section',
            'type'     => 'text',
        ));

        // ---------------------------------------------
        // Section: Appearance & Colors (Global)
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_appearance_section', array(
            'title'    => __('الألوان والمظهر العام', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 5,
        ));

        // Primary Color
        $wp_customize->add_setting('alrajhi_primary_color', array(
            'default'   => '#EB0A1E',
            'transport' => 'postMessage', // Changed to postMessage
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alrajhi_primary_color', array(
            'label'    => __('اللون الأساسي', 'astra-child'),
            'section'  => 'alrajhi_appearance_section',
        )));

        // Secondary Color (Restored)
        $wp_customize->add_setting('alrajhi_secondary_color', array(
            'default'   => '#C00818',
            'transport' => 'postMessage', // Changed to postMessage
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'alrajhi_secondary_color', array(
            'label'    => __('اللون الثانوي', 'astra-child'),
            'section'  => 'alrajhi_appearance_section',
        )));

        // Font Family
        $wp_customize->add_setting('alrajhi_font_family', array(
            'default'           => 'Cairo',
            'transport'         => 'refresh', // Specific refresh needed for fonts unless we use JS to swap link href (advanced)
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_font_family', array(
            'label'    => __('نوع الخط (Font Family)', 'astra-child'),
            'section'  => 'alrajhi_appearance_section',
            'type'     => 'select',
            'choices'  => array(
                'Cairo' => 'Cairo (Arabic Standard)',
                'Tajawal' => 'Tajawal (Modern)',
                'IBM Plex Sans Arabic' => 'IBM Plex Sans Arabic',
                'Almarai' => 'Almarai',
                'Amiri' => 'Amiri (Classic)',
            ),
        ));

        // Logo Icon Size
        $wp_customize->add_setting('alrajhi_logo_size', array(
            'default'           => '35',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_logo_size', array(
            'label'    => __('حجم أيقونة اللوجو (px)', 'astra-child'),
            'section'  => 'alrajhi_appearance_section',
            'type'     => 'number',
            'input_attrs' => array('min' => 20, 'max' => 100, 'step' => 1),
        ));

        // Header Icons Size
        $wp_customize->add_setting('alrajhi_header_icon_size', array(
            'default'           => '24',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_header_icon_size', array(
            'label'    => __('حجم أيقونات الهيدر (px)', 'astra-child'),
            'section'  => 'alrajhi_appearance_section',
            'type'     => 'number',
            'input_attrs' => array('min' => 15, 'max' => 50, 'step' => 1),
        ));

        // ---------------------------------------------
        // Section: Mobile Settings (Restored)
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_mobile_settings', array(
            'title'    => __('إعدادات واجهة الموبايل', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel', // Moved inside panel
            'priority' => 20,
        ));

        // Show Category Slider
        $wp_customize->add_setting('alrajhi_show_category_slider', array(
            'default'   => true,
            'transport' => 'refresh', // Keep refresh for logic changes
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        $wp_customize->add_control('alrajhi_show_category_slider', array(
            'label'    => __('عرض شريط الفئات المتحرك', 'astra-child'),
            'section'  => 'alrajhi_mobile_settings',
            'type'     => 'checkbox',
        ));

        // Products per Row
        $wp_customize->add_setting('alrajhi_mobile_product_columns', array(
            'default'   => '2',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_mobile_product_columns', array(
            'label'    => __('عدد أعمدة المنتجات (موبايل)', 'astra-child'),
            'section'  => 'alrajhi_mobile_settings',
            'type'     => 'select',
            'choices'  => array(
                '1' => '1 عمود',
                '2' => '2 أعمدة',
            ),
        ));

        // ---------------------------------------------
        // Section: Footer Settings
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_footer_section', array(
            'title'    => __('إعدادات الفوتر (Footer)', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 30,
        ));

        // Footer About Title
        $wp_customize->add_setting('alrajhi_footer_about_title', array(
            'default'           => 'TOYOTA PARTS',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_footer_about_title', array(
            'label'    => __('عنوان قسم "عن الموقع"', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'text',
        ));

        // Footer About Text
        $wp_customize->add_setting('alrajhi_footer_about_text', array(
            'default'           => 'وجهتك الأولى لقطع غيار تويوتا الأصلية في المملكة. نضمن لك الجودة والتوافق التام مع سيارتك.',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control('alrajhi_footer_about_text', array(
            'label'    => __('وصف قسم "عن الموقع"', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'textarea',
        ));

        // Contact Phone
        $wp_customize->add_setting('alrajhi_footer_phone', array(
            'default'           => '+966 50 123 4567',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_footer_phone', array(
            'label'    => __('رقم الهاتف', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'text',
        ));

        // Contact Email
        $wp_customize->add_setting('alrajhi_footer_email', array(
            'default'           => 'info@toyotaparts.sa',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_email',
        ));
        $wp_customize->add_control('alrajhi_footer_email', array(
            'label'    => __('البريد الإلكتروني', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'text',
        ));
        
        // Contact Location
        $wp_customize->add_setting('alrajhi_footer_location', array(
            'default'           => 'الرياض، المملكة العربية السعودية',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_footer_location', array(
            'label'    => __('العنوان / الموقع', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'text',
        ));

        // Copyright Text
        $wp_customize->add_setting('alrajhi_footer_copyright', array(
            'default'           => 'Toyota Parts. جميع الحقوق محفوظة.',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_footer_copyright', array(
            'label'    => __('نص حقوق النشر', 'astra-child'),
            'section'  => 'alrajhi_footer_section',
            'type'     => 'text',
        ));

        // ---------------------------------------------
        // Section: Social Links
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_social_section', array(
            'title'    => __('روابط التواصل الاجتماعي', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 35,
        ));

        // Twitter URL
        $wp_customize->add_setting('alrajhi_twitter_url', array(
            'default'           => '#',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('alrajhi_twitter_url', array(
            'label'    => __('رابط X (تويتر سابقاً)', 'astra-child'),
            'section'  => 'alrajhi_social_section',
            'type'     => 'url',
        ));

        // Instagram URL
        $wp_customize->add_setting('alrajhi_instagram_url', array(
            'default'           => '#',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('alrajhi_instagram_url', array(
            'label'    => __('رابط انستقرام', 'astra-child'),
            'section'  => 'alrajhi_social_section',
            'type'     => 'url',
        ));

        // Facebook URL
        $wp_customize->add_setting('alrajhi_facebook_url', array(
            'default'           => '#',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('alrajhi_facebook_url', array(
            'label'    => __('رابط فيسبوك', 'astra-child'),
            'section'  => 'alrajhi_social_section',
            'type'     => 'url',
        ));

        // ---------------------------------------------
        // Section: Basic Pages Settings
        // ---------------------------------------------
        $wp_customize->add_section('alrajhi_pages_section', array(
            'title'    => __('إعدادات الصفحات والسياسات', 'astra-child'),
            'panel'    => 'alrajhi_homepage_panel',
            'priority' => 40,
        ));

        // About Us Page
        $wp_customize->add_setting('alrajhi_about_page', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_about_page', array(
            'label'    => __('صفحة "من نحن"', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'dropdown-pages',
        ));

        // Contact Us Page
        $wp_customize->add_setting('alrajhi_contact_page', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_contact_page', array(
            'label'    => __('صفحة "اتصل بنا"', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'dropdown-pages',
        ));

        // Privacy Policy Page
        $wp_customize->add_setting('alrajhi_privacy_page', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_privacy_page', array(
            'label'    => __('صفحة سياسة الخصوصية', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'dropdown-pages',
        ));

        // Terms of Use Page
        $wp_customize->add_setting('alrajhi_terms_page', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_terms_page', array(
            'label'    => __('صفحة شروط الاستخدام', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'dropdown-pages',
        ));

        // Refund Policy Page
        $wp_customize->add_setting('alrajhi_refund_page', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control('alrajhi_refund_page', array(
            'label'    => __('صفحة سياسة الاسترجاع', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'dropdown-pages',
        ));

        // Google Map Embed Code
        $wp_customize->add_setting('alrajhi_contact_map', array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'alrajhi_sanitize_html', // Custom sanitizer for iframe
        ));
        $wp_customize->add_control('alrajhi_contact_map', array(
            'label'    => __('كود خريطة جوجل (Iframe)', 'astra-child'),
            'description' => __('قم بلصق كود Embed الخاص بالخريطة هنا.', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'textarea',
        ));

        // About Us Content: Title
        $wp_customize->add_setting('alrajhi_about_title', array(
            'default'           => 'الراجحي لقطع الغيار: ريادة وتميز في عالم تويوتا',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('alrajhi_about_title', array(
            'label'    => __('عنوان صفحة "من نحن"', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'text',
        ));

        // About Us Content: Description
        $wp_customize->add_setting('alrajhi_about_description', array(
            'default'           => 'نحن مؤسسة رائدة متخصصة في توفير كافة قطع غيار تويوتا الأصلية بالمملكة العربية السعودية، نهدف إلى تقديم أفضل جودة بأسعار تنافسية.',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control('alrajhi_about_description', array(
            'label'    => __('وصف صفحة "من نحن"', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
            'type'     => 'textarea',
        ));

        // About Us Content: Image
        $wp_customize->add_setting('alrajhi_about_image', array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'alrajhi_about_image', array(
            'label'    => __('صورة صفحة "من نحن"', 'astra-child'),
            'section'  => 'alrajhi_pages_section',
        )));
    }

    /**
     * Enqueue Customizer Preview Script
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'alrajhi-customizer-preview',
            get_stylesheet_directory_uri() . '/assets/js/customizer-preview.js',
            array('jquery', 'customize-preview'),
            '1.1.0', // Version bump
            true
        );
    }

    /**
     * Output Dynamic CSS to wp_head
     */
    public function output_customizer_css() {
        // Colors
        $primary = get_theme_mod('alrajhi_primary_color', '#EB0A1E');
        $secondary = get_theme_mod('alrajhi_secondary_color', '#C00818');
        $mobile_cols = get_theme_mod('alrajhi_mobile_product_columns', '2');
        
        // Typography
        $font_family = get_theme_mod('alrajhi_font_family', 'Cairo'); // Simplified fallback
        
        // Hero Background
        $hero_bg = get_theme_mod('alrajhi_hero_bg_image');
        ?>
        <style id="alrajhi-dynamic-css">
            :root {
                --color-primary: <?php echo esc_attr($primary); ?>;
                --alrajhi-primary: <?php echo esc_attr($primary); ?>; /* Legacy support */
                --color-primary-dark: <?php echo esc_attr($secondary); ?>;
                --alrajhi-secondary: <?php echo esc_attr($secondary); ?>; /* Legacy support */
                --alrajhi-mobile-columns: <?php echo esc_attr($mobile_cols); ?>;
                --font-family: '<?php echo esc_attr($font_family); ?>', sans-serif;
                --header-icon-size: <?php echo esc_attr(get_theme_mod('alrajhi_header_icon_size', '24')); ?>px;
                --logo-size: <?php echo esc_attr(get_theme_mod('alrajhi_logo_size', '35')); ?>px;
                --topbar-bg: <?php echo esc_attr(get_theme_mod('alrajhi_topbar_bg', '#1a1a1a')); ?>;
                --topbar-color: <?php echo esc_attr(get_theme_mod('alrajhi_topbar_color', '#ffffff')); ?>;
            }
            
            /* Top Bar Visibility */
            <?php if (!get_theme_mod('alrajhi_show_topbar', true)): ?>
            .alrajhi-top-bar { display: none !important; }
            <?php endif; ?>

            <?php if (!get_theme_mod('alrajhi_show_topbar_mobile', false)): ?>
            @media (max-width: 768px) {
                .alrajhi-top-bar { display: none !important; }
            }
            <?php endif; ?>
            
            /* Apply Font Globally */
            body, button, input, select, textarea {
                font-family: var(--font-family) !important;
            }
            
            <?php if ($hero_bg): ?>
            .pwa-hero .hero-bg {
                background-image: url('<?php echo esc_url($hero_bg); ?>') !important;
                background-size: cover !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
            }
            <?php endif; ?>
        </style>
        <?php
    }
}

/**
 * Sanitize HTML Content (specifically for iframes/maps)
 */
function alrajhi_sanitize_html($input) {
    return wp_kses($input, array(
        'iframe' => array(
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'style'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'referrerpolicy'  => true,
            'frameborder'     => true,
        ),
    ));
}

// Initialize Customizer
new AlRajhi_Customizer();
