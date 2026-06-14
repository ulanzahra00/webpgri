<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Login Page Simulation Test</h2>";

echo "<h3>1. Simulasi admin/login.php (tanpa redirect)</h3>";
try {
    require_once __DIR__ . '/includes/functions.php';
    echo "✓ functions.php loaded<br>";
    
    echo "Session status: " . session_status() . "<br>";
    
    // Cek current_admin
    $admin = current_admin();
    echo "current_admin: " . ($admin ? 'LOGGED IN as ' . ($admin['name'] ?? 'unknown') : 'not logged in') . "<br>";
    
    // Test database query seperti di login.php
    echo "<h3>2. Test query SELECT dari tabel users</h3>";
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute(['admin@pgri.com']);
    $user = $stmt->fetch();
    echo "Test query users: " . ($user ? "found user " . $user['name'] : "no user with admin@pgri.com") . "<br>";
    
    // Test semua tabel
    echo "<h3>3. Cek semua tabel penting</h3>";
    $tables = ['users', 'posts', 'categories', 'galleries', 'schools', 'financial_reports', 'organization_members', 'settings', 'contact_messages', 'documents', 'member_registrations'];
    foreach ($tables as $table) {
        try {
            $stmt = db()->query("SHOW TABLES LIKE '$table'");
            if ($stmt->fetch()) {
                $count = db()->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                echo "✓ $table ($count rows)<br>";
            } else {
                echo "✗ $table - TABLE NOT FOUND<br>";
            }
        } catch (Exception $e) {
            echo "✗ $table - ERROR: " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<h3>4. Cek admin/index.php modules</h3>";
    // Test modules yang dipanggil di admin/index.php
    echo "Test ensure_member_registrations_table(): ";
    try {
        ensure_member_registrations_table();
        echo "✓ OK<br>";
    } catch (Exception $e) {
        echo "✗ " . $e->getMessage() . "<br>";
    }
    
    echo "Test ensure_posts_video_column(): ";
    try {
        ensure_posts_video_column();
        echo "✓ OK<br>";
    } catch (Exception $e) {
        echo "✗ " . $e->getMessage() . "<br>";
    }
    
    echo "Test ensure_financial_reports_deposit_date_column(): ";
    try {
        ensure_financial_reports_deposit_date_column();
        echo "✓ OK<br>";
    } catch (Exception $e) {
        echo "✗ " . $e->getMessage() . "<br>";
    }
    
    echo "Test ensure_documents_table(): ";
    try {
        ensure_documents_table();
        echo "✓ OK<br>";
    } catch (Exception $e) {
        echo "✗ " . $e->getMessage() . "<br>";
    }
    
} catch (Throwable $e) {
    echo "<div style='color:red;font-weight:bold'>FATAL ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "</div>";
}

echo "<br><strong>Test selesai.</strong>";