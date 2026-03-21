// Dark Mode Toggle Script
(function () {
    'use strict';

    // Select body
    const body = document.body;

    // Check for saved dark mode preference or system preference
    const savedMode = localStorage.getItem('darkMode');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Apply saved mode or system preference on load
    if (savedMode === 'enabled' || (!savedMode && prefersDark)) {
        body.classList.add('dark-mode');
    }

    // Function to update icons based on mode
    function updateIcons(isDark) {
        // We might have multiple toggle buttons (e.g., one in normal header, one in PWA header, one in widget)
        const toggles = document.querySelectorAll('.dark-mode-toggle, .btn-icon[aria-label="Dark Mode"], .dark-mode-toggle-widget');

        toggles.forEach(btn => {
            const sunIcon = btn.querySelector('.sun-icon');
            const moonIcon = btn.querySelector('.moon-icon');

            if (sunIcon && moonIcon) {
                if (isDark) {
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'block';
                } else {
                    sunIcon.style.display = 'block';
                    moonIcon.style.display = 'none';
                }
            }
        });
    }

    // Update icons immediately on load
    updateIcons(body.classList.contains('dark-mode'));

    // Main Toggle Function
    function toggleDarkMode(e) {
        e.preventDefault();

        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');

        // Save preference
        if (isDark) {
            localStorage.setItem('darkMode', 'enabled');
            console.log('🌙 Dark mode enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
            console.log('☀️ Light mode enabled');
        }

        // Update all icons
        updateIcons(isDark);
    }

    // Setup Event Listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Listen to all possible toggle buttons
        const toggleBtns = document.querySelectorAll('#darkModeToggle, #pwaDarkModeToggle, .dark-mode-toggle-widget');

        toggleBtns.forEach(btn => {
            btn.addEventListener('click', toggleDarkMode);
        });

        // Ensure state is correct after DOM load
        updateIcons(body.classList.contains('dark-mode'));
    });

    console.log('🎨 Dark Mode Toggle initialized');

})();
