/**
 * PWA Install Prompt Handler
 * Captures the beforeinstallprompt event and shows a custom install banner
 */

(function () {
    'use strict';

    let deferredPrompt = null;
    const INSTALL_DECLINED_KEY = 'pwa-install-declined';
    const INSTALL_DECLINED_DAYS = 7; // Don't show again for 7 days if declined

    // Check if user has declined recently
    function hasDeclinedRecently() {
        const declined = localStorage.getItem(INSTALL_DECLINED_KEY);
        if (!declined) return false;

        const declinedDate = new Date(parseInt(declined));
        const daysSinceDeclined = (Date.now() - declinedDate) / (1000 * 60 * 60 * 24);
        return daysSinceDeclined < INSTALL_DECLINED_DAYS;
    }

    // Mark as declined
    function markAsDeclined() {
        localStorage.setItem(INSTALL_DECLINED_KEY, Date.now().toString());
    }

    // Check if already installed
    function isInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true;
    }

    // Create install banner HTML
    function createInstallBanner() {
        const banner = document.createElement('div');
        banner.id = 'pwa-install-banner';
        banner.className = 'pwa-install-banner';
        banner.innerHTML = `
            <div class="install-banner-content">
                <div class="install-banner-icon">
                    <img src="/icons/icon-96.png" alt="App Icon" width="48" height="48">
                </div>
                <div class="install-banner-text">
                    <strong>ثبّت التطبيق</strong>
                    <span>احصل على وصول أسرع من الشاشة الرئيسية</span>
                </div>
                <div class="install-banner-actions">
                    <button id="pwa-install-btn" class="install-btn">تثبيت</button>
                    <button id="pwa-dismiss-btn" class="dismiss-btn">ليس الآن</button>
                </div>
            </div>
        `;
        document.body.appendChild(banner);

        // Attach event listeners
        document.getElementById('pwa-install-btn').addEventListener('click', handleInstallClick);
        document.getElementById('pwa-dismiss-btn').addEventListener('click', handleDismissClick);

        // Animate in
        setTimeout(() => banner.classList.add('visible'), 100);
    }

    // Handle install button click
    async function handleInstallClick() {
        if (!deferredPrompt) return;

        // Show the install prompt
        deferredPrompt.prompt();

        // Wait for user choice
        const { outcome } = await deferredPrompt.userChoice;
        console.log('[PWA] User choice:', outcome);

        // Clear the deferred prompt
        deferredPrompt = null;

        // Hide the banner
        hideBanner();

        if (outcome === 'dismissed') {
            markAsDeclined();
        }
    }

    // Handle dismiss button click
    function handleDismissClick() {
        markAsDeclined();
        hideBanner();
    }

    // Hide the banner
    function hideBanner() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.classList.remove('visible');
            setTimeout(() => banner.remove(), 300);
        }
    }

    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('[PWA] beforeinstallprompt fired');

        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();

        // Store the event for later use
        deferredPrompt = e;

        // Check conditions before showing banner
        if (isInstalled()) {
            console.log('[PWA] Already installed');
            return;
        }

        if (hasDeclinedRecently()) {
            console.log('[PWA] User declined recently');
            return;
        }

        // Show install banner after a short delay
        setTimeout(() => {
            createInstallBanner();
        }, 3000); // Show after 3 seconds
    });

    // Listen for successful installation
    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App installed successfully!');
        deferredPrompt = null;
        hideBanner();

        // Clear declined flag
        localStorage.removeItem(INSTALL_DECLINED_KEY);
    });

})();
