<?php
// Script update sekali jalan untuk menambahkan upload video MP4 pada postingan berita.
// Jalankan sekali dari browser/CLI, lalu hapus file ini di hosting produksi.
require_once __DIR__ . '/includes/functions.php';

if (PHP_SAPI !== 'cli') {
    require_admin();
}

try {
    $pdo = db();
    $column = $pdo->query("SHOW COLUMNS FROM posts LIKE 'video'")->fetch();

    if (!$column) {
        $pdo->exec('ALTER TABLE posts ADD video VARCHAR(255) NULL AFTER image');
    }

    echo '<h2>Update video berita berhasil.</h2>';
    echo '<p>Kolom <strong>video</strong> sudah tersedia pada tabel <strong>posts</strong>.</p>';
    echo '<p><a href="' . e(url('admin/?module=posts')) . '">Buka Admin Berita</a></p>';
    echo '<p><strong>Penting:</strong> hapus file <code>update_posts_video.php</code> setelah update selesai jika website sudah online.</p>';
} catch (Throwable $error) {
    http_response_code(500);
    echo '<h2>Update gagal</h2>';
    echo '<p>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
