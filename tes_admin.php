<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Admin Diagnostic Test</h2>";

// Test step by step seperti admin flow
echo "<h3>Step 1: Load functions.php</h3>";
try {
    require_once __DIR__ . '/includes/functions.php';
    echo "✓ functions.php loaded<br>";
} catch (Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "<br>";
}

echo "<h3>Step 2: Session check</h3>";
echo "Session status: " . session_status() . " (0=none, 1=active, 2=disabled)<br>";

echo "<h3>Step 3: Test current_admin()</h3>";
try {
    $admin = current_admin();
    echo "✓ current_admin() executed, result: " . ($admin ? 'logged in' : 'not logged in') . "<br>";
} catch (Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "<br>";
}

echo "<h3>Step 4: Test db() function</h3>";
try {
    $pdo = db();
    echo "✓ db() success<br>";
    $stmt = $pdo->query("SELECT 1");
    echo "✓ Query success<br>";
} catch (Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 5: Test setting() function</h3>";
try {
    $val = setting('site_logo');
    echo "✓ setting('site_logo') = '" . $val . "'<br>";
} catch (Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 6: Test csrf_token() function</h3>";
try {
    $token = csrf_token();
    echo "✓ csrf_token() = " . substr($token, 0, 10) . "...<br>";
} catch (Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 7: Check memory & error limits</h3>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "error_reporting: " . error_reporting() . "<br>";
echo "display_errors: " . ini_get('display_errors') . "<br>";

echo "<h3>Step 8: Check required PHP extensions</h3>";
$extensions = ['pdo', 'pdo_mysql', 'session', 'mbstring', 'gd', 'zip', 'json'];
foreach ($extensions as $ext) {
    echo ($ext) . ": " . (extension_loaded($ext) ? '✓' : '✗ MISSING!') . "<br>";
}

echo "<br><strong>Done.</strong>";