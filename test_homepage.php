<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');

$front_page_id = get_option('page_on_front');
$output = array(
    'front_page_id' => $front_page_id,
    'template' => get_page_template_slug($front_page_id),
    'elementor_status' => (bool) get_post_meta($front_page_id, '_elementor_data', true),
    'meta' => array()
);

if ($front_page_id) {
    $meta = get_post_meta($front_page_id);
    foreach ($meta as $key => $values) {
        if (strpos($key, 'ast') !== false || strpos($key, 'header') !== false || strpos($key, 'footer') !== false) {
            $output['meta'][$key] = $values[0];
        }
    }
}
// header('Content-Type: application/json');
?>
<!DOCTYPE html>
<html>
<head>
    <title>test_homepage.php</title>
</head>
<body>
    <div id="test-homepage-main-content">
        <h1>test</h1>
        <pre><?php echo json_encode($output, JSON_PRETTY_PRINT); ?></pre>
    </div>
    <div id="test-homepage-footer">
        footer
    </div>
</body>
</html>
