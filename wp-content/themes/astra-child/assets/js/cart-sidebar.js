jQuery(document).ready(function ($) {

    // --- Cart Sidebar Features ---

    // Open Sidebar
    function openCartSidebar() {
        $('.cart-sidebar').addClass('active');
        $('.cart-sidebar-overlay').addClass('active');
        $('body').addClass('cart-open');
    }

    // Close Sidebar
    function closeCartSidebar() {
        $('.cart-sidebar').removeClass('active');
        $('.cart-sidebar-overlay').removeClass('active');
        $('body').removeClass('cart-open');
    }

    // Trigger open on 'Add to Cart' click (AJAX)
    $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
        openCartSidebar();
    });

    // Handle standard Add to Cart buttons to prevent reload if possible
    // (Requires check 'Enable AJAX add to cart buttons on archives' in WooCommerce Settings)

    // Explicitly open for any button with 'ajax_add_to_cart'
    $('.ajax_add_to_cart').on('click', function () {
        // Optional: Show loading state
    });

    // Close on click overlay or scroll button
    $('.cart-sidebar-overlay, .close-cart-sidebar').on('click', function () {
        closeCartSidebar();
    });

    // Manual Open Triggers (Header Cart Icons & Bottom Nav)
    // We will attach this to the existing theme cart icons and our new bottom nav
    $('.ast-site-header-cart, .cart-container, .header-cart-icon, .pwa-bottom-nav .cart-trigger').on('click', function (e) {
        e.preventDefault();
        openCartSidebar();
    });

    // Ensure "My Garage" bar is aware of cart
    // (Optional integration)

    // Handle AJAX updates inside the sidebar
    // Refresh fragments logic is handled by WooCommerce automatically
    // We just need to ensure sidebar stays open if needed

    // --- Product Grid Improvements ---

    // Add "View Details" click handler if needed
    // (Currently standard links)

});
