# دليل صفحة تسجيل الدخول الآمنة (ARJ Secure Login)

## نظرة عامة (Overview)

صفحة تسجيل دخول مخصصة وآمنة لووردبريس/ووكومرس مبنية بأعلى معايير الأمان. تستخدم دوال ووردبريس القياسية للمصادقة (`wp_signon()`) مع طبقات حماية متعددة ضد الهجمات الشائعة.

### الميزات الرئيسية

- ✅ حماية CSRF عبر WordPress Nonce
- ✅ حماية XSS عبر تعقيم المدخلات
- ✅ رسائل خطأ عامة (لا تكشف تفاصيل)
- ✅ Hooks للتكامل مع إضافات Brute Force
- ✅ دعم reCAPTCHA v2
- ✅ تكامل كامل مع WordPress Customizer
- ✅ دعم RTL والعربية
- ✅ تصميم متجاوب

---

## الملفات والأماكن

```
astra-child/
├── template-login.php              # قالب الصفحة الرئيسي
├── inc/
│   ├── class-arj-secure-login.php  # معالج الأمان (Class)
│   └── arj-login-customizer.php    # إعدادات Customizer
├── assets/
│   ├── css/
│   │   └── login.css               # تنسيقات الصفحة
│   └── js/
│       ├── login.js                # سكريبت التفاعل
│       ├── login-customizer-preview.js    # معاينة Customizer
│       └── login-customizer-controls.js   # تحكم Customizer
└── docs/
    └── LOGIN-DOCUMENTATION.md      # هذا الملف
```

---

## شرح الدوال الرئيسية

### Class: `ARJ_Secure_Login`

| الدالة | الوصف |
|:---|:---|
| `get_instance()` | Singleton pattern - إرجاع instance وحيد |
| `process_login_form()` | معالجة طلب POST مع كل التحققات الأمنية |
| `render_login_form()` | عرض نموذج HTML مع حقل Nonce |
| `verify_recaptcha()` | التحقق من رد reCAPTCHA مع Google |
| `get_client_ip()` | استخراج IP العميل (يدعم Cloudflare/Proxies) |
| `get_redirect_url()` | تحديد صفحة إعادة التوجيه بعد النجاح |

### Hooks المتاحة للتكامل

```php
// تُنفذ عند فشل تسجيل الدخول
do_action('arj_login_failed', $username);

// تُنفذ عند نجاح تسجيل الدخول
do_action('arj_login_success', $user);

// فلتر للتحقق من حظر IP
apply_filters('arj_allow_login_attempt', true, $client_ip);

// فلتر لتعديل بيانات الدخول قبل المصادقة
apply_filters('arj_login_credentials', $credentials, $client_ip);
```

---

## شرح جوانب الأمان

### 1. حماية CSRF (Cross-Site Request Forgery)

```php
// في النموذج - إنشاء Nonce
wp_nonce_field('arj_secure_login_action', 'arj_login_nonce');

// في المعالج - التحقق من Nonce
if (!wp_verify_nonce($_POST['arj_login_nonce'], 'arj_secure_login_action')) {
    // رفض الطلب
}
```

**كيف يحمي:** يضمن أن الطلب صادر من نموذج موقعك وليس من موقع خارجي.

### 2. حماية XSS (Cross-Site Scripting)

```php
// تعقيم اسم المستخدم
$username = sanitize_text_field(wp_unslash($_POST['arj_username']));

// عند الطباعة
echo esc_html($error_message);
echo esc_attr($input_value);
echo esc_url($redirect_url);
```

**كيف يحمي:** يمنع حقن أكواد JavaScript ضارة في الصفحة.

### 3. رسائل الخطأ العامة

```php
// ❌ خطأ - يكشف معلومات
"اسم المستخدم غير موجود"
"كلمة المرور خاطئة"

// ✅ صحيح - رسالة عامة
"بيانات الدخول غير صحيحة. يرجى التحقق والمحاولة مرة أخرى."
```

**كيف يحمي:** يمنع المهاجمين من معرفة أسماء المستخدمين الصحيحة.

### 4. التكامل مع إضافات Brute Force

```php
// في إضافة أمنية (مثل Limit Login Attempts)
add_action('arj_login_failed', function($username) {
    // تسجيل المحاولة الفاشلة
    $ip = $_SERVER['REMOTE_ADDR'];
    increment_failed_attempts($ip);
});

add_filter('arj_allow_login_attempt', function($allowed, $ip) {
    // التحقق من عدد المحاولات
    if (get_failed_attempts($ip) >= 5) {
        return false; // حظر
    }
    return $allowed;
}, 10, 2);
```

### 5. إعادة التوجيه الآمنة

```php
// استخدام wp_safe_redirect بدلاً من wp_redirect
wp_safe_redirect($redirect_url);

// التحقق من صلاحية الرابط
$validated = wp_validate_redirect($requested_url, $default_url);
```

---

## خطوات التثبيت والاستخدام

### الخطوة 1: نسخ الملفات

الملفات موجودة بالفعل في قالب `astra-child`. تأكد من تفعيل القالب الابن.

### الخطوة 2: إنشاء صفحة تسجيل الدخول

1. اذهب إلى **لوحة التحكم → صفحات → أضف جديد**
2. أدخل عنوان مثل "تسجيل الدخول"
3. في إعدادات الصفحة (يمين الشاشة)، اختر قالب: **"صفحة تسجيل الدخول"**
4. انشر الصفحة

### الخطوة 3: تخصيص المظهر (اختياري)

1. اذهب إلى **المظهر → تخصيص**
2. اختر **"إعدادات صفحة تسجيل الدخول"**
3. خصص:
   - الشعار
   - الألوان (الأساسي، الخلفية، النموذج)
   - النصوص (العنوان، زر الدخول)
   - صفحة إعادة التوجيه
   - مفاتيح reCAPTCHA

### الخطوة 4: إعداد reCAPTCHA (موصى به)

1. اذهب إلى [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
2. أنشئ موقعاً جديداً (reCAPTCHA v2 - Checkbox)
3. انسخ Site Key و Secret Key
4. الصقهما في إعدادات الأمان بالـ Customizer

### الخطوة 5: تغيير رابط تسجيل الدخول (اختياري)

لتوجيه `/wp-login.php` إلى صفحتك المخصصة:

```php
// أضف هذا في functions.php
add_action('init', function() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false 
        && !is_user_logged_in() 
        && !isset($_GET['action'])) {
        wp_redirect(home_url('/login/')); // غيّر المسار حسب صفحتك
        exit;
    }
});
```

> [!WARNING]
> تأكد من عدم حظر الوصول لـ `wp-login.php?action=logout` و `wp-login.php?action=lostpassword`

---

## التكامل مع إضافات الأمان

### Wordfence

يعمل تلقائياً - Wordfence يراقب `wp_signon()`.

### Limit Login Attempts Reloaded

```php
add_action('arj_login_failed', function($username) {
    // الإضافة تراقب wp_login_failed تلقائياً
}, 1);
```

### Two-Factor Authentication

```php
add_action('arj_after_login_form', function() {
    // أضف حقل 2FA هنا إذا لزم
    if (function_exists('render_2fa_field')) {
        render_2fa_field();
    }
});
```

---

## استكشاف الأخطاء

| المشكلة | الحل |
|:---|:---|
| الصفحة فارغة | تأكد من تفعيل قالب astra-child |
| خطأ انتهاء الجلسة | امسح الـ Cache وأعد تحميل الصفحة |
| reCAPTCHA لا يظهر | تأكد من إدخال Site Key صحيح |
| إعادة التوجيه لا تعمل | تأكد من اختيار صفحة في Customizer |

---

## معايير الكود المتبعة

- ✅ WordPress Coding Standards
- ✅ Prefix `arj_` لجميع الدوال والمتغيرات
- ✅ Namespacing عبر Class
- ✅ Singleton Pattern للـ Instance
- ✅ Sanitization لجميع المدخلات
- ✅ Escaping لجميع المخرجات
- ✅ تعليقات PHPDoc كاملة
- ✅ دعم الترجمة `__()` و `esc_html_e()`

---

**الإصدار:** 1.0.0  
**التوافق:** WordPress 5.0+ | WooCommerce 4.0+ | PHP 7.4+
