<?php
// Export data sekolah dalam format CSV yang dapat dibuka di Excel.
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data-sekolah-pgri-kotamobagu.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nama Sekolah', 'Jenjang', 'Kecamatan', 'Alamat', 'Kepala Sekolah', 'Telepon']);

$rows = db()->query('SELECT * FROM schools ORDER BY district, name')->fetchAll();
foreach ($rows as $row) {
    fputcsv($output, [$row['name'], $row['level'], $row['district'], $row['address'], $row['headmaster'], $row['phone']]);
}

fclose($output);
