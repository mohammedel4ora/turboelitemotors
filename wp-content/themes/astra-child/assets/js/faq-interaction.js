jQuery(document).ready(function ($) {
    $('.faq-question').on('click', function () {
        const parent = $(this).closest('.faq-item');

        // Toggle active state
        parent.toggleClass('active');

        // Slide toggle answer
        parent.find('.faq-answer').slideToggle(300);

        // Close other items (Accordion effect) - Optional
        /*
        $('.faq-item').not(parent).removeClass('active');
        $('.faq-item').not(parent).find('.faq-answer').slideUp(300);
        */
    });
});
