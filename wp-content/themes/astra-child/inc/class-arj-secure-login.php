<?php
/**
 * ARJ Secure Login Handler
 * 
 * Handles custom login form processing with comprehensive security measures.
 * 
 * Security Features:
 * - CSRF Protection via WordPress Nonce
 * - XSS Protection via input sanitization
 * - Brute Force Protection Hooks
 * - Generic error messages (no information disclosure)
 * - Secure redirects
 * 
 * @package Astra-Child
 * @subpackage Security
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ARJ_Secure_Login
 * 
 * Main class for handling secure login functionality.
 */
class ARJ_Secure_Login {

    /**
     * Nonce action name for login form
     * 
     * @var string
     */
    const NONCE_ACTION = 'arj_secure_login_action';

    /**
     * Nonce field name
     * 
     * @var string
     */
    const NONCE_FIELD = 'arj_login_nonce';

    /**
     * Error message storage
     * 
     * @var string
     */
    private $error_message = '';

    /**
     * Success message storage
     * 
     * @var string
     */
    private $success_message = '';

    /**
     * Singleton instance
     * 
     * @var ARJ_Secure_Login
     */
    private static $instance = null;

    /**
     * Get singleton instance
     * 
     * @return ARJ_Secure_Login
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Private for singleton pattern
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Process login form submission
        add_action('init', array($this, 'process_login_form'));
        
        // Add custom hooks for brute force protection integration
        add_action('arj_login_failed', array($this, 'log_failed_attempt'), 10, 1);
        add_action('arj_login_success', array($this, 'log_successful_login'), 10, 1);
        
        // Filter for integrating with security plugins
        add_filter('arj_allow_login_attempt', array($this, 'check_login_allowed'), 10, 2);
    }

    /**
     * Process login form submission
     * 
     * This method handles the login form POST request with full security validation.
     * 
     * Security Checks:
     * 1. Verify request method is POST
     * 2. Verify nonce to prevent CSRF
     * 3. Sanitize all input data
     * 4. Check if login attempts are allowed (brute force hook)
     * 5. Authenticate user via WordPress
     * 6. Secure redirect after success
     */
    public function process_login_form() {
        // Only process POST requests with our nonce field
        if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($_POST[self::NONCE_FIELD])) {
            return;
        }

        // Verify nonce - CSRF Protection
        if (!wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST[self::NONCE_FIELD])),
            self::NONCE_ACTION
        )) {
            $this->error_message = __('انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.', 'astra-child');
            return;
        }

        // Sanitize inputs - XSS Protection
        $username = isset($_POST['arj_username']) 
            ? sanitize_text_field(wp_unslash($_POST['arj_username'])) 
            : '';
        
        $password = isset($_POST['arj_password']) 
            ? wp_unslash($_POST['arj_password']) // Don't sanitize password, just unslash
            : '';
        
        $remember = isset($_POST['arj_remember']) && $_POST['arj_remember'] === '1';
        
        $redirect_to = isset($_POST['arj_redirect_to']) 
            ? esc_url_raw(wp_unslash($_POST['arj_redirect_to'])) 
            : '';

        // Validate required fields
        if (empty($username) || empty($password)) {
            $this->error_message = __('يرجى إدخال اسم المستخدم وكلمة المرور.', 'astra-child');
            return;
        }

        // Check if login attempts are allowed (Brute Force Protection Hook)
        $client_ip = $this->get_client_ip();
        if (!apply_filters('arj_allow_login_attempt', true, $client_ip)) {
            $this->error_message = __('تم تجاوز الحد المسموح به من المحاولات. يرجى المحاولة لاحقاً.', 'astra-child');
            return;
        }

        // reCAPTCHA Verification (if enabled)
        if ($this->is_recaptcha_enabled()) {
            $recaptcha_response = isset($_POST['g-recaptcha-response']) 
                ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) 
                : '';
            
            if (!$this->verify_recaptcha($recaptcha_response)) {
                $this->error_message = __('فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.', 'astra-child');
                return;
            }
        }

        // Attempt authentication via WordPress
        $credentials = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        );

        /**
         * Filter credentials before authentication
         * 
         * Allows security plugins to modify or inspect credentials.
         * 
         * @param array  $credentials The login credentials
         * @param string $client_ip   The client IP address
         */
        $credentials = apply_filters('arj_login_credentials', $credentials, $client_ip);

        // Authenticate user
        $user = wp_signon($credentials, is_ssl());

        // Check for authentication errors
        if (is_wp_error($user)) {
            // Fire failed login action for logging/brute force tracking
            do_action('arj_login_failed', $username);
            
            // Generic error message - DON'T reveal if username or password was wrong
            $this->error_message = __('بيانات الدخول غير صحيحة. يرجى التحقق والمحاولة مرة أخرى.', 'astra-child');
            return;
        }

        // Success! Fire success action
        do_action('arj_login_success', $user);

        // Determine redirect URL
        $redirect_url = $this->get_redirect_url($redirect_to, $user);

        // Secure redirect
        wp_safe_redirect($redirect_url);
        exit;
    }

    /**
     * Get the appropriate redirect URL after login
     * 
     * @param string  $requested_redirect The requested redirect URL
     * @param WP_User $user               The logged-in user object
     * @return string The validated redirect URL
     */
    private function get_redirect_url($requested_redirect, $user) {
        // Priority 1: Customizer setting
        $default_redirect = get_theme_mod('arj_login_redirect_page', '');
        
        if (!empty($default_redirect) && is_numeric($default_redirect)) {
            $default_redirect = get_permalink((int) $default_redirect);
        }
        
        // Priority 2: Requested redirect (if valid)
        if (!empty($requested_redirect)) {
            $validated = wp_validate_redirect($requested_redirect, $default_redirect);
            if (!empty($validated)) {
                return $validated;
            }
        }
        
        // Priority 3: Default redirect from Customizer
        if (!empty($default_redirect)) {
            return $default_redirect;
        }
        
        // Priority 4: WooCommerce My Account page
        if (function_exists('wc_get_page_id')) {
            $myaccount_page = wc_get_page_id('myaccount');
            if ($myaccount_page > 0) {
                return get_permalink($myaccount_page);
            }
        }
        
        // Priority 5: Home URL
        return home_url('/');
    }

    /**
     * Get client IP address
     * 
     * Handles proxy servers and load balancers.
     * 
     * @return string The client IP address
     */
    private function get_client_ip() {
        $ip_keys = array(
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        );

        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = sanitize_text_field(wp_unslash($_SERVER[$key]));
                // Handle comma-separated IPs (X-Forwarded-For)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    /**
     * Check if reCAPTCHA is enabled
     * 
     * @return bool True if reCAPTCHA is enabled and configured
     */
    private function is_recaptcha_enabled() {
        $site_key = get_theme_mod('arj_recaptcha_site_key', '');
        $secret_key = get_theme_mod('arj_recaptcha_secret_key', '');
        return !empty($site_key) && !empty($secret_key);
    }

    /**
     * Verify reCAPTCHA response
     * 
     * @param string $response The reCAPTCHA response token
     * @return bool True if verification passed
     */
    private function verify_recaptcha($response) {
        if (empty($response)) {
            return false;
        }

        $secret_key = get_theme_mod('arj_recaptcha_secret_key', '');
        if (empty($secret_key)) {
            return true; // Skip if not configured
        }

        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $verify_response = wp_remote_post($verify_url, array(
            'body' => array(
                'secret'   => $secret_key,
                'response' => $response,
                'remoteip' => $this->get_client_ip(),
            ),
        ));

        if (is_wp_error($verify_response)) {
            // Log error but allow login (fail open)
            error_log('ARJ Login: reCAPTCHA verification failed - ' . $verify_response->get_error_message());
            return true;
        }

        $body = json_decode(wp_remote_retrieve_body($verify_response), true);
        
        return isset($body['success']) && $body['success'] === true;
    }

    /**
     * Log failed login attempt
     * 
     * Hook for security plugins to track failed attempts.
     * 
     * @param string $username The attempted username
     */
    public function log_failed_attempt($username) {
        /**
         * Action: Log failed login attempt
         * 
         * Security plugins can hook into this to implement rate limiting.
         * 
         * @param string $username  The attempted username
         * @param string $client_ip The client IP address
         */
        do_action('arj_login_attempt_logged', $username, $this->get_client_ip(), false);
    }

    /**
     * Log successful login
     * 
     * @param WP_User $user The logged-in user
     */
    public function log_successful_login($user) {
        /**
         * Action: Log successful login
         * 
         * Can be used for audit logging.
         * 
         * @param WP_User $user      The user who logged in
         * @param string  $client_ip The client IP address
         */
        do_action('arj_login_attempt_logged', $user->user_login, $this->get_client_ip(), true);
    }

    /**
     * Check if login attempt is allowed
     * 
     * Placeholder for brute force protection integration.
     * 
     * @param bool   $allowed   Whether login is currently allowed
     * @param string $client_ip The client IP address
     * @return bool Whether to allow the login attempt
     */
    public function check_login_allowed($allowed, $client_ip) {
        /**
         * Filter: Check if IP is blocked
         * 
         * Security plugins can filter this to block IPs with too many failed attempts.
         * 
         * @param bool   $allowed   Default allow status
         * @param string $client_ip The client IP
         */
        return apply_filters('arj_is_ip_blocked', $allowed, $client_ip);
    }

    /**
     * Get error message
     * 
     * @return string The current error message
     */
    public function get_error_message() {
        return $this->error_message;
    }

    /**
     * Get success message
     * 
     * @return string The current success message
     */
    public function get_success_message() {
        return $this->success_message;
    }

    /**
     * Render login form
     * 
     * Outputs the secure login form HTML.
     * 
     * @param array $args Form arguments
     */
    public function render_login_form($args = array()) {
        $defaults = array(
            'redirect_to'     => '',
            'show_remember'   => true,
            'show_lost_pass'  => true,
            'show_register'   => get_option('users_can_register'),
        );

        $args = wp_parse_args($args, $defaults);

        // Get customizer settings
        $form_title = get_theme_mod('arj_login_form_title', __('تسجيل الدخول', 'astra-child'));
        $button_text = get_theme_mod('arj_login_button_text', __('دخول', 'astra-child'));
        $lost_pass_text = get_theme_mod('arj_lost_password_text', __('نسيت كلمة المرور؟', 'astra-child'));
        
        // Get reCAPTCHA settings
        $recaptcha_enabled = $this->is_recaptcha_enabled();
        $recaptcha_site_key = get_theme_mod('arj_recaptcha_site_key', '');

        ?>
        <div class="arj-login-form-wrapper">
            <?php if (!empty($this->error_message)) : ?>
                <div class="arj-login-message arj-login-error" role="alert">
                    <span class="arj-message-icon">⚠️</span>
                    <?php echo esc_html($this->error_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->success_message)) : ?>
                <div class="arj-login-message arj-login-success" role="alert">
                    <span class="arj-message-icon">✓</span>
                    <?php echo esc_html($this->success_message); ?>
                </div>
            <?php endif; ?>

            <form id="arj-login-form" class="arj-login-form" method="post" action="">
                <?php if (!empty($form_title)) : ?>
                    <h2 class="arj-form-title"><?php echo esc_html($form_title); ?></h2>
                <?php endif; ?>

                <!-- CSRF Protection: Nonce Field -->
                <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_FIELD); ?>

                <!-- Username/Email Field -->
                <div class="arj-form-group">
                    <label for="arj_username" class="arj-form-label">
                        <?php esc_html_e('اسم المستخدم أو البريد الإلكتروني', 'astra-child'); ?>
                        <span class="required" aria-hidden="true">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="arj_username" 
                        name="arj_username" 
                        class="arj-form-input" 
                        required 
                        autocomplete="username"
                        placeholder="<?php esc_attr_e('أدخل اسم المستخدم أو البريد', 'astra-child'); ?>"
                    />
                </div>

                <!-- Password Field -->
                <div class="arj-form-group">
                    <label for="arj_password" class="arj-form-label">
                        <?php esc_html_e('كلمة المرور', 'astra-child'); ?>
                        <span class="required" aria-hidden="true">*</span>
                    </label>
                    <div class="arj-password-wrapper">
                        <input 
                            type="password" 
                            id="arj_password" 
                            name="arj_password" 
                            class="arj-form-input" 
                            required 
                            autocomplete="current-password"
                            placeholder="<?php esc_attr_e('أدخل كلمة المرور', 'astra-child'); ?>"
                        />
                        <button type="button" class="arj-toggle-password" aria-label="<?php esc_attr_e('إظهار/إخفاء كلمة المرور', 'astra-child'); ?>">
                            <span class="dashicons dashicons-visibility"></span>
                        </button>
                    </div>
                </div>

                <?php if ($args['show_remember']) : ?>
                    <!-- Remember Me -->
                    <div class="arj-form-group arj-remember-group">
                        <label class="arj-checkbox-label">
                            <input type="checkbox" name="arj_remember" value="1" />
                            <span class="arj-checkbox-text"><?php esc_html_e('تذكرني', 'astra-child'); ?></span>
                        </label>
                    </div>
                <?php endif; ?>

                <!-- Hidden Redirect Field -->
                <input type="hidden" name="arj_redirect_to" value="<?php echo esc_attr($args['redirect_to']); ?>" />

                <?php if ($recaptcha_enabled) : ?>
                    <!-- reCAPTCHA -->
                    <div class="arj-form-group arj-recaptcha-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
                    </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <div class="arj-form-group arj-submit-group">
                    <button type="submit" class="arj-login-button">
                        <span class="arj-button-text"><?php echo esc_html($button_text); ?></span>
                        <span class="arj-button-spinner" style="display: none;">
                            <span class="arj-spinner"></span>
                        </span>
                    </button>
                </div>

                <!-- Form Links -->
                <div class="arj-form-links">
                    <?php if ($args['show_lost_pass']) : ?>
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="arj-lost-password-link">
                            <?php echo esc_html($lost_pass_text); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($args['show_register']) : ?>
                        <span class="arj-link-separator">|</span>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="arj-register-link">
                            <?php esc_html_e('إنشاء حساب جديد', 'astra-child'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php
                /**
                 * Action: After login form
                 * 
                 * For adding custom fields or content after the form.
                 */
                do_action('arj_after_login_form');
                ?>
            </form>
        </div>
        <?php
    }
}

// Initialize singleton
ARJ_Secure_Login::get_instance();

/**
 * Shortcode: [arj_login_form]
 * 
 * يمكن استخدامه في أي صفحة عادية لعرض نموذج تسجيل الدخول
 * 
 * Usage: [arj_login_form redirect="/my-account/"]
 * 
 * @param array $atts Shortcode attributes
 * @return string Login form HTML
 */
function arj_login_form_shortcode($atts) {
    // إذا كان المستخدم مسجل دخوله، أظهر رسالة
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        return '<div class="arj-already-logged-in" style="text-align:center; padding:20px; background:#e8f5e9; border-radius:8px; margin:20px 0;">
            <p style="margin:0; color:#2e7d32;">👋 ' . sprintf(__('مرحباً %s، أنت مسجل الدخول بالفعل.', 'astra-child'), esc_html($current_user->display_name)) . '</p>
            <p style="margin:10px 0 0;"><a href="' . esc_url(wp_logout_url(home_url())) . '">' . __('تسجيل الخروج', 'astra-child') . '</a></p>
        </div>';
    }
    
    $atts = shortcode_atts(array(
        'redirect' => '',
    ), $atts, 'arj_login_form');
    
    // تحميل الـ CSS والـ JS
    wp_enqueue_style('arj-login', get_stylesheet_directory_uri() . '/assets/css/login.css', array(), '1.0.0');
    wp_enqueue_script('arj-login', get_stylesheet_directory_uri() . '/assets/js/login.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('dashicons');
    
    // reCAPTCHA if enabled
    $recaptcha_site_key = get_theme_mod('arj_recaptcha_site_key', '');
    if (!empty($recaptcha_site_key)) {
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
    }
    
    // عرض النموذج
    ob_start();
    
    $login_handler = ARJ_Secure_Login::get_instance();
    $login_handler->render_login_form(array(
        'redirect_to'    => $atts['redirect'],
        'show_remember'  => true,
        'show_lost_pass' => true,
        'show_register'  => get_option('users_can_register'),
    ));
    
    return ob_get_clean();
}
add_shortcode('arj_login_form', 'arj_login_form_shortcode');

