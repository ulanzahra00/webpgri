<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
echo "<!-- Test: mulai -->";
try {
    require_once __DIR__ . '/../includes/functions.php';
    echo "<!-- functions OK -->";
    require_once __DIR__ . '/../includes/admin_header.php';
    echo "<!-- admin_header OK -->";
    
    $module = 'dashboard';
    $action = 'list';
    $id = 0;
    
    echo "<!-- module=$module, action=$action, id=$id -->";
    
    if ($module === 'dashboard') {
        ensure_member_registrations_table();
        echo "<!-- dashboard OK -->";
    }
    
    require_once __DIR__ . '/../includes/admin_footer.php';
    echo "<!-- admin_footer OK -->";
    echo "<br><strong>SUCCESS: admin page rendered without error!</strong>";
} catch (Throwable $e) {
    echo "<div style='color:red;padding:15px;border:2px solid red;margin:10px'>";
    echo "<h3>ERROR FOUND:</h3>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "Trace:<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}