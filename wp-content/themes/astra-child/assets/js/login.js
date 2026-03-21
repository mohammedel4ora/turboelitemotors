/**
 * ARJ Secure Login JavaScript
 * 
 * Handles client-side interactions for the login form.
 * 
 * Features:
 * - Password visibility toggle
 * - Form submission loading state
 * - Client-side validation feedback
 * 
 * @package Astra-Child
 * @since 1.0.0
 */

(function ($) {
    'use strict';

    /**
     * ARJ Login Module
     */
    const ARJLogin = {
        /**
         * Initialize the module
         */
        init: function () {
            this.cacheElements();
            this.bindEvents();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function () {
            this.$form = $('#arj-login-form');
            this.$passwordInput = $('#arj_password');
            this.$togglePassword = $('.arj-toggle-password');
            this.$submitButton = $('.arj-login-button');
            this.$buttonText = this.$submitButton.find('.arj-button-text');
            this.$buttonSpinner = this.$submitButton.find('.arj-button-spinner');
        },

        /**
         * Bind event handlers
         */
        bindEvents: function () {
            // Password visibility toggle
            this.$togglePassword.on('click', this.togglePasswordVisibility.bind(this));

            // Form submission
            this.$form.on('submit', this.handleFormSubmit.bind(this));

            // Input focus effects
            this.$form.find('.arj-form-input').on('focus blur', this.handleInputFocus.bind(this));

            // Clear errors on input
            this.$form.find('.arj-form-input').on('input', this.clearErrors.bind(this));
        },

        /**
         * Toggle password visibility
         * 
         * @param {Event} e Click event
         */
        togglePasswordVisibility: function (e) {
            e.preventDefault();

            const $icon = this.$togglePassword.find('.dashicons');
            const isPassword = this.$passwordInput.attr('type') === 'password';

            if (isPassword) {
                this.$passwordInput.attr('type', 'text');
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                this.$passwordInput.attr('type', 'password');
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        },

        /**
         * Handle form submission
         * 
         * @param {Event} e Submit event
         */
        handleFormSubmit: function (e) {
            // Validate required fields
            const username = $('#arj_username').val().trim();
            const password = this.$passwordInput.val();

            if (!username || !password) {
                e.preventDefault();
                this.showError('يرجى ملء جميع الحقول المطلوبة.');
                return false;
            }

            // Show loading state
            this.showLoadingState();

            // Form will submit normally
            return true;
        },

        /**
         * Show loading state on submit button
         */
        showLoadingState: function () {
            this.$submitButton.prop('disabled', true);
            this.$buttonText.hide();
            this.$buttonSpinner.show();
        },

        /**
         * Reset loading state
         */
        resetLoadingState: function () {
            this.$submitButton.prop('disabled', false);
            this.$buttonText.show();
            this.$buttonSpinner.hide();
        },

        /**
         * Handle input focus/blur
         * 
         * @param {Event} e Focus/blur event
         */
        handleInputFocus: function (e) {
            const $input = $(e.target);
            const $group = $input.closest('.arj-form-group');

            if (e.type === 'focus') {
                $group.addClass('is-focused');
            } else {
                $group.removeClass('is-focused');
            }
        },

        /**
         * Clear error messages on input
         */
        clearErrors: function () {
            $('.arj-login-error').slideUp(200, function () {
                $(this).remove();
            });
        },

        /**
         * Show error message
         * 
         * @param {string} message Error message to display
         */
        showError: function (message) {
            // Remove existing errors
            $('.arj-login-error').remove();

            // Create error element
            const $error = $('<div class="arj-login-message arj-login-error" role="alert">' +
                '<span class="arj-message-icon">⚠️</span>' +
                '<span>' + this.escapeHtml(message) + '</span>' +
                '</div>');

            // Insert before form
            this.$form.find('.arj-form-title').after($error);

            // Animate in
            $error.hide().slideDown(200);

            // Reset loading state
            this.resetLoadingState();
        },

        /**
         * Escape HTML entities for XSS prevention
         * 
         * @param {string} text Text to escape
         * @return {string} Escaped text
         */
        escapeHtml: function (text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Initialize on DOM ready
    $(document).ready(function () {
        ARJLogin.init();
    });

    // Handle page show (back/forward cache)
    $(window).on('pageshow', function (e) {
        if (e.originalEvent.persisted) {
            ARJLogin.resetLoadingState();
        }
    });

})(jQuery);
