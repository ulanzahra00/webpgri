<?php
// Export laporan keuangan dalam format CSV.
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan-keuangan-pgri-kotamobagu.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Judul', 'Bulan', 'Tahun', 'Kategori', 'Pemasukan', 'Pengeluaran', 'Saldo', 'Status', 'Keterangan']);

$where = current_admin() ? '' : 'WHERE status = "published"';
$rows = db()->query("SELECT * FROM financial_reports $where ORDER BY period_year DESC, period_month DESC, id DESC")->fetchAll();
foreach ($rows as $row) {
    fputcsv($output, [
        $row['title'],
        $row['period_month'],
        $row['period_year'],
        $row['category'],
        $row['income'],
        $row['expense'],
        $row['balance'],
        $row['status'],
        $row['description'],
    ]);
}

fclose($output);
