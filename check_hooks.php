<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');

$hooks = get_posts(array(
    'post_type' => 'astra-advanced-hook',
    'post_status' => 'publish',
    'posts_per_page' => -1,
));

if (empty($hooks)) {
    echo "No Astra Advanced Hooks found.\n";
} else {
    foreach ($hooks as $hook) {
        $meta = get_post_meta($hook->ID);
        echo "Hook ID: " . $hook->ID . " | Title: " . $hook->post_title . "\n";
        if (isset($meta['ast-advanced-hook-action'])) {
            echo "  Action: " . $meta['ast-advanced-hook-action'][0] . "\n";
        }
        if (isset($meta['ast-advanced-hook-layout'])) {
            echo "  Layout: " . $meta['ast-advanced-hook-layout'][0] . "\n";
        }
        if (isset($meta['ast-advanced-display-on'])) {
            $display_on = maybe_unserialize($meta['ast-advanced-display-on'][0]);
            echo "  Display On: " . print_r($display_on, true) . "\n";
        }
        if (isset($meta['ast-advanced-exclude-on'])) {
            $exclude_on = maybe_unserialize($meta['ast-advanced-exclude-on'][0]);
            echo "  Exclude On: " . print_r($exclude_on, true) . "\n";
        }
        echo "----------------------\n";
    }
}
?>
