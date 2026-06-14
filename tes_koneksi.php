<?php
// File diagnostic untuk cek koneksi database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnostic - PGRI Kotamobagu</h2>";

// Cek versi PHP
echo "<h3>1. PHP Version</h3>";
echo phpversion() . "<br>";

// Cek file config database
echo "<h3>2. File config/database.php</h3>";
if (file_exists(__DIR__ . '/config/database.php')) {
    echo "File exists ✓<br>";
    require_once __DIR__ . '/config/database.php';
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
    echo "DB_USER: " . DB_USER . "<br>";
    echo "DB_PASS: " . str_repeat('*', strlen(DB_PASS)) . "<br>";
} else {
    echo "File NOT FOUND ✗<br>";
}

// Test koneksi database
echo "<h3>3. Database Connection Test</h3>";
try {
    if (function_exists('db')) {
        $pdo = db();
        echo "PDO connection SUCCESS ✓<br>";
        
        // Cek tabel users
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->fetch()) {
            echo "Table 'users' exists ✓<br>";
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            echo "Users count: " . $stmt->fetchColumn() . "<br>";
        } else {
            echo "Table 'users' NOT FOUND ✗<br>";
        }
    } else {
        echo "Function db() not found ✗<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " line " . $e->getLine() . "<br>";
}

// Cek file functions.php
echo "<h3>4. File includes/functions.php</h3>";
if (file_exists(__DIR__ . '/includes/functions.php')) {
    echo "File exists ✓<br>";
} else {
    echo "File NOT FOUND ✗<br>";
}

echo "<h3>5. Server Info</h3>";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "<br>";
echo "<br><em>--- End of diagnostic ---</em>";