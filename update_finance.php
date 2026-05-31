<?php
// Script update sekali jalan untuk menambahkan modul Laporan Keuangan
// pada database yang sudah pernah di-install.
require_once __DIR__ . '/includes/functions.php';

try {
    $pdo = db();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS financial_reports (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(180) NOT NULL,
            period_month TINYINT NOT NULL,
            period_year YEAR NOT NULL,
            category VARCHAR(120) NOT NULL,
            income DECIMAL(15,2) DEFAULT 0,
            expense DECIMAL(15,2) DEFAULT 0,
            balance DECIMAL(15,2) GENERATED ALWAYS AS (income - expense) STORED,
            description TEXT NULL,
            document VARCHAR(255) NULL,
            status ENUM('draft','published') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $count = (int)$pdo->query('SELECT COUNT(*) FROM financial_reports')->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO financial_reports (title, period_month, period_year, category, income, expense, description, status) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute(['Iuran Anggota Bulan Januari', 1, 2026, 'Iuran Anggota', 12500000, 0, 'Penerimaan iuran anggota PGRI Kotamobagu bulan Januari.', 'published']);
        $stmt->execute(['Kegiatan Pelatihan Guru Kreatif', 1, 2026, 'Program Kerja', 0, 7350000, 'Pengeluaran konsumsi, narasumber, dan perlengkapan pelatihan guru.', 'published']);
        $stmt->execute(['Dukungan Mitra Pendidikan', 2, 2026, 'Bantuan / Sponsor', 5000000, 0, 'Penerimaan dukungan kegiatan dari mitra pendidikan daerah.', 'published']);
    }

    echo '<h2>Update modul Laporan Keuangan berhasil.</h2>';
    echo '<p>Tabel <strong>financial_reports</strong> sudah tersedia.</p>';
    echo '<p><a href="' . e(url('?page=keuangan')) . '">Buka halaman Laporan Keuangan</a> | <a href="' . e(url('admin/?module=finance')) . '">Buka Admin Keuangan</a></p>';
    echo '<p><strong>Penting:</strong> hapus file <code>update_finance.php</code> setelah update selesai jika website sudah online.</p>';
} catch (Throwable $error) {
    http_response_code(500);
    echo '<h2>Update gagal</h2>';
    echo '<p>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
