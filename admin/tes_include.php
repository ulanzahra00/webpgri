<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
try {
    // Include admin/index.php dengan error catching
    require_once __DIR__ . '/../includes/functions.php';
    require_once __DIR__ . '/../includes/admin_header.php';
    echo "=== Dashboard module ===";
    ensure_member_registrations_table();
    echo "OK<br>";
    require_once __DIR__ . '/../includes/admin_footer.php';
} catch (Throwable $e) {
    echo "<div style='color:red;font-weight:bold'>ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "</div>";
}