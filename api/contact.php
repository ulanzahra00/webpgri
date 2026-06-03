<?php
// Endpoint form keluhan public. Input divalidasi lalu disimpan ke database.
require_once __DIR__ . '/../includes/functions.php';

verify_csrf();

$name = trim($_POST['name'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || !$email || $subject === '' || $message === '') {
    flash('danger', 'Mohon lengkapi form keluhan dengan email yang valid.');
    redirect('?page=kontak');
}

$stmt = db()->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)');
$stmt->execute([$name, $email, $subject, $message]);

flash('success', 'Pesan berhasil dikirim. Terima kasih telah menghubungi PGRI Kotamobagu.');
redirect('?page=kontak');
