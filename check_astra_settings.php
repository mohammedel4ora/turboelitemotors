<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');

// Check for Astra hooks that may be getting removed
$results = array();

// Check if astra_header action has hooks
global $wp_filter;

// Check for template_include filters that might redirect the template
if (isset($wp_filter['template_include'])) {
    $results['template_include_hooks'] = array();
    foreach ($wp_filter['template_include'] as $priority => $hooks) {
        foreach ($hooks as $tag => $hook) {
            $results['template_include_hooks'][] = array(
                'priority' => $priority,
                'function' => is_string($hook['function']) ? $hook['function'] : (is_array($hook['function']) ? (is_string($hook['function'][0]) ? $hook['function'][0] : get_class($hook['function'][0])) . '::' . $hook['function'][1] : 'closure'),
            );
        }
    }
}

// Check if Elementor is overriding the header/footer globally
$elementor_header = get_option('elementor_active_kit');
$results['elementor_kit_id'] = $elementor_header;

// Check Astra theme settings for header/footer
$astra_settings = get_option('astra-settings');
if ($astra_settings) {
    // Filter for header/footer related keys only
    $filtered = array();
    foreach ($astra_settings as $key => $val) {
        if (strpos($key, 'header') !== false || strpos($key, 'footer') !== false || strpos($key, 'transparent') !== false || strpos($key, 'above') !== false || strpos($key, 'below') !== false) {
            $filtered[$key] = $val;
        }
    }
    $results['astra_header_footer_settings'] = $filtered;
}

// Check insert-headers-and-footers plugin
$ihaf_header = get_option('ihaf_insert_header');
$ihaf_footer = get_option('ihaf_insert_footer');
if ($ihaf_header) $results['ihaf_header'] = substr($ihaf_header, 0, 200);
if ($ihaf_footer) $results['ihaf_footer'] = substr($ihaf_footer, 0, 200);

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
