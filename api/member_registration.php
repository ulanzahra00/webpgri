<?php
// Endpoint registrasi anggota baru dari halaman publik.
require_once __DIR__ . '/../includes/functions.php';

try {
    flash('warning', 'Registrasi anggota online sedang dinonaktifkan sementara. Silakan hubungi sekretariat PGRI Kotamobagu untuk informasi pendaftaran.');
    redirect('?page=anggota');
} catch (Throwable $error) {
    error_log('Member registration error: ' . $error->getMessage());
    flash('danger', 'Registrasi belum berhasil dikirim. ' . $error->getMessage());
    redirect('?page=anggota');
}
