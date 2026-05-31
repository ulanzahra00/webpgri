<?php
// Endpoint JSON untuk pencarian sekolah jika dibutuhkan oleh frontend lain.
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$keyword = '%' . trim($_GET['q'] ?? '') . '%';
$district = trim($_GET['district'] ?? '');

if ($district !== '') {
    $stmt = db()->prepare('SELECT * FROM schools WHERE district = ? AND (name LIKE ? OR level LIKE ? OR address LIKE ?) ORDER BY name');
    $stmt->execute([$district, $keyword, $keyword, $keyword]);
} else {
    $stmt = db()->prepare('SELECT * FROM schools WHERE name LIKE ? OR level LIKE ? OR address LIKE ? ORDER BY name');
    $stmt->execute([$keyword, $keyword, $keyword]);
}

echo json_encode($stmt->fetchAll(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
