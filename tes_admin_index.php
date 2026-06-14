<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Admin Index Page Simulation</h2>";

// Simulasi persis seperti admin/index.php
try {
    echo "<h3>1. Loading functions.php...</h3>";
    require_once __DIR__ . '/includes/functions.php';
    echo "✓ OK<br>";
    
    echo "<h3>2. Calling require_admin()...</h3>";
    require_admin();
    echo "✓ OK (sudah login sebagai " . (current_admin()['name'] ?? 'unknown') . ")<br>";
    
    echo "<h3>3. Loading admin_header.php...</h3>";
    ob_start();
    require_once __DIR__ . '/includes/admin_header.php';
    $header = ob_get_clean();
    echo "✓ OK (header rendered, length: " . strlen($header) . " chars)<br>";
    
    echo "<h3>4. Testing admin/index.php code...</h3>";
    
    $module = 'dashboard';
    $action = 'list';
    $id = 0;
    
    echo "Accessing dashboard module...<br>";
    ob_start();
    if ($module === 'dashboard') {
        ensure_member_registrations_table();
        ?>
        <div class="row g-4">
            <div class="col-12">
                <div class="card card-official">
                    <div class="card-body">
                        <h5>Selamat datang</h5>
                        <p class="mb-0">Gunakan menu admin untuk mengelola konten website resmi PGRI Kotamobagu.</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    $content = ob_get_clean();
    echo "✓ Dashboard rendered OK<br>";
    
    echo "<h3>5. Testing module: posts...</h3>";
    $module = 'posts';
    $action = 'list';
    ob_start();
    ensure_posts_video_column();
    $rows = db()->query('SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON categories.id = posts.category_id ORDER BY posts.id DESC')->fetchAll();
    echo "✓ posts list OK (" . count($rows) . " posts)<br>";
    ob_end_clean();
    
    echo "<h3>6. Testing module: finance...</h3>";
    $module = 'finance';
    $action = 'list';
    ob_start();
    ensure_financial_reports_deposit_date_column();
    $filterMonth = '';
    $where = '';
    $params = [];
    $stmt = db()->prepare("SELECT * FROM financial_reports $where ORDER BY period_year DESC, period_month DESC, id DESC");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    $summary = db()->prepare("SELECT COALESCE(SUM(income),0) AS income_total, COALESCE(SUM(expense),0) AS expense_total, COALESCE(SUM(balance),0) AS balance_total FROM financial_reports $where");
    $summary->execute($params);
    $totals = $summary->fetch();
    echo "✓ finance list OK (" . count($rows) . " reports)<br>";
    ob_end_clean();
    
    echo "<h3>7. Testing module: documents...</h3>";
    $module = 'documents';
    $action = 'list';
    ob_start();
    ensure_documents_table();
    $keyword = '';
    $catFilter = '';
    $where2 = '1=1';
    $params2 = [];
    $stmt = db()->prepare("SELECT * FROM documents WHERE $where2 ORDER BY created_at DESC");
    $stmt->execute($params2);
    $rows = $stmt->fetchAll();
    echo "✓ documents list OK (" . count($rows) . " documents)<br>";
    ob_end_clean();
    
    echo "<h3>8. Testing module: settings...</h3>";
    $module = 'settings';
    ob_start();
    $settingDefaults = [
        'hero_banner' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80',
        'site_logo' => '',
        'whatsapp_number' => '6281234567890',
    ];
    foreach ($settingDefaults as $key => $value) {
        $stmt = db()->prepare('INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)');
        $stmt->execute([$key, $value]);
    }
    $rows = db()->query('SELECT * FROM settings ORDER BY setting_key')->fetchAll();
    echo "✓ settings OK (" . count($rows) . " settings)<br>";
    ob_end_clean();
    
    echo "<h3>9. Testing module: schools...</h3>";
    $module = 'schools';
    $action = 'list';
    ob_start();
    $rows = db()->query('SELECT * FROM schools ORDER BY district, name')->fetchAll();
    echo "✓ schools list OK (" . count($rows) . " schools)<br>";
    ob_end_clean();
    
    echo "<h3>10. Testing admin footer...</h3>";
    ob_start();
    require_once __DIR__ . '/includes/admin_footer.php';
    $footer = ob_get_clean();
    echo "✓ Footer loaded OK<br>";
    
} catch (Throwable $e) {
    echo "<div style='color:red;font-weight:bold;padding:10px;border:2px solid red;margin:10px 0;'>";
    echo "<h4 style='color:red;margin-top:0'>ERROR TERDETEKSI!</h4>";
    echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<strong>Trace:</strong><br><pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<br><strong>=== ALL TESTS COMPLETE ===</strong>";