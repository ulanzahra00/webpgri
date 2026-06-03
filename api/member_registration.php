<?php
// Endpoint registrasi anggota baru dari halaman publik.
require_once __DIR__ . '/../includes/functions.php';

ensure_member_registrations_table();
verify_csrf();

$fullName = trim($_POST['full_name'] ?? '');
$identityNumber = trim($_POST['identity_number'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$schoolName = trim($_POST['school_name'] ?? '');
$jobTitle = trim($_POST['job_title'] ?? '');
$district = trim($_POST['district'] ?? '');
$address = trim($_POST['address'] ?? '');
$reason = trim($_POST['reason'] ?? '');

if ($fullName === '' || !$email || $phone === '' || $schoolName === '') {
    flash('danger', 'Mohon lengkapi nama, email valid, nomor HP/WhatsApp, dan tempat tugas.');
    redirect('?page=anggota');
}

$photo = null;
try {
    $photo = upload_image($_FILES['photo'] ?? [], 'anggota');
} catch (RuntimeException $error) {
    flash('danger', $error->getMessage());
    redirect('?page=anggota');
}

$stmt = db()->prepare('INSERT INTO member_registrations (full_name, identity_number, photo, email, phone, school_name, job_title, district, address, reason) VALUES (?,?,?,?,?,?,?,?,?,?)');
$stmt->execute([
    $fullName,
    $identityNumber ?: null,
    $photo,
    $email,
    $phone,
    $schoolName,
    $jobTitle ?: null,
    $district ?: null,
    $address ?: null,
    $reason ?: null,
]);

flash('success', 'Registrasi anggota berhasil dikirim. Sekretariat PGRI Kotamobagu akan memverifikasi data Anda.');
redirect('?page=anggota');
