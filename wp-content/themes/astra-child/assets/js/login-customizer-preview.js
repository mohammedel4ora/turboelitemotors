/**
 * ARJ Login Customizer Live Preview
 * 
 * Handles real-time preview updates in the WordPress Customizer.
 * 
 * @package Astra-Child
 * @since 1.0.0
 */

(function ($) {
    'use strict';

    // Form Title
    wp.customize('arj_login_form_title', function (value) {
        value.bind(function (newval) {
            $('.arj-form-title').text(newval);
        });
    });

    // Button Text
    wp.customize('arj_login_button_text', function (value) {
        value.bind(function (newval) {
            $('.arj-login-button .arj-button-text').text(newval);
        });
    });

    // Lost Password Text
    wp.customize('arj_lost_password_text', function (value) {
        value.bind(function (newval) {
            $('.arj-lost-password-link').text(newval);
        });
    });

    // Background Type
    wp.customize('arj_login_bg_type', function (value) {
        value.bind(function (newval) {
            var $page = $('.arj-login-page');
            if (newval === 'image') {
                var bgImage = wp.customize('arj_login_bg_image').get();
                if (bgImage) {
                    $page.css({
                        'background-image': 'url(' + bgImage + ')',
                        'background-size': 'cover',
                        'background-position': 'center',
                        'background-color': 'transparent'
                    });
                }
            } else {
                var bgColor = wp.customize('arj_login_bg_color').get();
                $page.css({
                    'background-image': 'none',
                    'background-color': bgColor
                });
            }
        });
    });

    // Background Color
    wp.customize('arj_login_bg_color', function (value) {
        value.bind(function (newval) {
            if (wp.customize('arj_login_bg_type').get() === 'color') {
                $('.arj-login-page').css('background-color', newval);
            }
        });
    });

    // Background Image
    wp.customize('arj_login_bg_image', function (value) {
        value.bind(function (newval) {
            if (wp.customize('arj_login_bg_type').get() === 'image' && newval) {
                $('.arj-login-page').css({
                    'background-image': 'url(' + newval + ')',
                    'background-size': 'cover',
                    'background-position': 'center'
                });
            }
        });
    });

    // Form Background Color
    wp.customize('arj_login_form_bg', function (value) {
        value.bind(function (newval) {
            $('.arj-login-card').css('background-color', newval);
            document.documentElement.style.setProperty('--arj-login-form-bg', newval);
        });
    });

    // Primary Color
    wp.customize('arj_login_primary_color', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--arj-login-primary', newval);
            document.documentElement.style.setProperty('--arj-login-primary-hover', newval + 'dd');
        });
    });

    // Logo
    wp.customize('arj_login_logo', function (value) {
        value.bind(function (newval) {
            if (newval) {
                wp.media.attachment(newval).fetch().then(function (data) {
                    var imgUrl = data.url;
                    if ($('.arj-login-logo img').length) {
                        $('.arj-login-logo img').attr('src', imgUrl);
                    } else {
                        var siteName = $('body').data('site-name') || '';
                        $('.arj-login-logo').html('<a href="/"><img src="' + imgUrl + '" alt="' + siteName + '" /></a>');
                    }
                    $('.arj-login-logo').removeClass('arj-login-logo-text');
                });
            } else {
                // Show text logo
                var $logo = $('.arj-login-logo');
                $logo.addClass('arj-login-logo-text');
                // Keep the text if already exists or show site name
            }
        });
    });

})(jQuery);
