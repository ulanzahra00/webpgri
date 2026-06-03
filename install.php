<?php
// Script instalasi opsional: membuat tabel, seed data, dan admin bcrypt yang valid.
// Jalankan sekali dari browser/CLI setelah database dibuat, lalu hapus file ini di hosting produksi.
require_once __DIR__ . '/config/database.php';

function read_install_sql(string $file): string
{
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException('File SQL tidak ditemukan: ' . basename($file));
    }

    // Shared hosting biasanya tidak mengizinkan CREATE DATABASE/USE dari script PHP.
    $sql = preg_replace('/^\s*CREATE\s+DATABASE\b.*?;\s*$/mi', '', $sql);
    $sql = preg_replace('/^\s*USE\s+`?[\w-]+`?\s*;\s*$/mi', '', $sql);

    return trim($sql);
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->exec(read_install_sql(__DIR__ . '/database/pgrikota_pgri.sql'));
    $pdo->exec(read_install_sql(__DIR__ . '/database/seed.sql'));

    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
    $stmt->execute([password_hash('admin12345', PASSWORD_BCRYPT), 'admin@pgrikotamobagu.my.id']);

    echo 'Instalasi selesai. Login admin: admin@pgrikotamobagu.my.id / admin12345. Hapus install.php setelah digunakan.';
} catch (Throwable $error) {
    http_response_code(500);
    echo 'Instalasi gagal: ' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
}
