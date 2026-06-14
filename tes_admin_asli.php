<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h2>Exact Admin Index Replica Test</h2>";

try {
    // Persis seperti admin/index.php baris 1-4
    require_once __DIR__ . '/includes/functions.php';
    require_once __DIR__ . '/includes/admin_header.php';
    
    $module = 'dashboard';
    $action = 'list';
    $id = 0;
    
    if ($module === 'dashboard') {
        ensure_member_registrations_table();
    }
    echo "✓ admin/index.php berhasil di-render<br>";
    echo "Session: " . session_status() . "<br>";
    echo "Admin: " . (current_admin()['name'] ?? '-') . "<br>";

} catch (Throwable $e) {
    echo "<div style='color:red;font-weight:bold;padding:10px;border:2px solid red;margin:10px 0;'>";
    echo "<h4 style='color:red;margin-top:0'>ERROR!</h4>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "Trace:<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<br><strong>Selesai</strong>";