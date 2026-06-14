<?php
// Script untuk memastikan kolom report_date ada di database
require 'config/database.php';

echo "<h2>Database Migration - Add report_date Column</h2>";

try {
    // Check jika kolom sudah ada
    $column = db()->query("SHOW COLUMNS FROM financial_reports LIKE 'report_date'")->fetch();
    
    if (!$column) {
        echo "<p>⏳ Creating report_date column...</p>";
        db()->exec('ALTER TABLE financial_reports ADD COLUMN report_date DATE NULL AFTER deposit_date');
        echo "<p style='color: green;'>✓ SUCCESS: Kolom report_date berhasil ditambahkan!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ INFO: Kolom report_date sudah ada.</p>";
    }
    
    echo "<hr>";
    echo "<p><a href='/admin/?module=finance&action=form'>Buka Form Tambah Laporan Keuangan →</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}

