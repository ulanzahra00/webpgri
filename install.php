<?php
// Script instalasi opsional: membuat database, schema, seed, dan admin bcrypt yang valid.
// Jalankan sekali dari browser/CLI, lalu hapus file ini di hosting produksi.
$host = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'pgri_kotamobagu';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    $seed = file_get_contents(__DIR__ . '/database/seed.sql');

    $pdo->exec($schema);
    $pdo->exec($seed);

    $app = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $app->prepare('UPDATE users SET password = ? WHERE email = ?');
    $stmt->execute([password_hash('admin12345', PASSWORD_BCRYPT), 'admin@pgrikotamobagu.or.id']);
    $stmt = $app->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), password = VALUES(password), role = VALUES(role)');
    $stmt->execute(['Zahra Administrator', 'zahra@gmail.com', password_hash('tanyap4ZIL', PASSWORD_BCRYPT), 'superadmin']);

    echo 'Instalasi selesai. Login admin: admin@pgrikotamobagu.or.id / admin12345 atau zahra@gmail.com / tanyap4ZIL. Hapus install.php setelah digunakan.';
} catch (Throwable $error) {
    http_response_code(500);
    echo 'Instalasi gagal: ' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
}
