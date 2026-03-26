<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');

$purged = false;
// WordPress object cache flush
wp_cache_flush();

// LiteSpeed Cache purge all
if (defined('LSCWP_V') || class_exists('LiteSpeed\\Purge')) {
    do_action('litespeed_purge_all');
    $purged = true;
    echo "LiteSpeed Cache purged.\n";
} else {
    echo "LiteSpeed Cache not detected or active.\n";
}

echo "Cache purge script executed.\n";
?>
