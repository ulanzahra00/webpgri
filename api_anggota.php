<?php
// Endpoint kompatibilitas data anggota. Semua akses database memakai prepared statement.
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    ensure_member_registrations_table();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(503);
        echo json_encode(['error' => 'Registrasi anggota online sedang dinonaktifkan sementara.']);
        exit;
    }

    $stmt = db()->prepare('
        SELECT id, full_name, identity_number, school_name, photo
        FROM member_registrations
        WHERE status = ?
        ORDER BY full_name ASC
    ');
    $stmt->execute(['approved']);

    $data = [];
    foreach ($stmt->fetchAll() as $row) {
        $data[] = [
            'id' => (string)$row['id'],
            'name' => $row['full_name'],
            'npa' => $row['identity_number'] ?: '-',
            'school' => $row['school_name'],
            'photoUrl' => $row['photo'] ? media_url($row['photo']) : null,
            'status' => 'Aktif',
        ];
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} catch (Throwable $error) {
    error_log('api_anggota error: ' . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server belum bisa memproses permintaan.']);
}
