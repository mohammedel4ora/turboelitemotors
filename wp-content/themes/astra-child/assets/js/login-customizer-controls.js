/**
 * ARJ Login Customizer Controls
 * 
 * Handles conditional visibility of customizer controls.
 * 
 * @package Astra-Child
 * @since 1.0.0
 */

(function ($) {
    'use strict';

    wp.customize.bind('ready', function () {

        // Toggle background color/image controls based on background type
        function toggleBackgroundControls() {
            var bgType = wp.customize('arj_login_bg_type').get();

            if (bgType === 'color') {
                wp.customize.control('arj_login_bg_color').activate();
                wp.customize.control('arj_login_bg_image').deactivate();
            } else {
                wp.customize.control('arj_login_bg_color').deactivate();
                wp.customize.control('arj_login_bg_image').activate();
            }
        }

        // Initial toggle
        toggleBackgroundControls();

        // Listen for changes
        wp.customize('arj_login_bg_type', function (value) {
            value.bind(function () {
                toggleBackgroundControls();
            });
        });

    });

})(jQuery);
