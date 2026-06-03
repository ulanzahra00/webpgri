<?php
// Layout admin: memastikan halaman terlindungi dan memiliki sidebar/topbar konsisten.
require_admin();
$admin = current_admin();
$active = $_GET['module'] ?? 'dashboard';
$flash = get_flash();
$siteLogo = setting('site_logo');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - PGRI Kotamobagu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(asset_url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body class="admin-shell">
<div class="loading-overlay"><div class="spinner-border text-danger" role="status"></div></div>
<aside class="admin-sidebar p-3">
    <div class="d-flex align-items-center gap-2 mb-4">
        <span class="logo-mark"><?php if ($siteLogo): ?><img src="<?= e(media_url($siteLogo)) ?>" alt="Logo PGRI"><?php else: ?><i class="fa-solid fa-chalkboard-user"></i><?php endif; ?></span>
        <div><strong>Admin PGRI</strong><small class="d-block">Kotamobagu</small></div>
    </div>
    <nav class="d-grid gap-1">
        <a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= e(url('admin/')) ?>"><i class="fa-solid fa-gauge"></i>Dashboard</a>
        <a class="<?= $active === 'posts' ? 'active' : '' ?>" href="<?= e(url('admin/?module=posts')) ?>"><i class="fa-solid fa-newspaper"></i>Berita</a>
        <a class="<?= $active === 'galleries' ? 'active' : '' ?>" href="<?= e(url('admin/?module=galleries')) ?>"><i class="fa-solid fa-images"></i>Galeri</a>
        <a class="<?= $active === 'schools' ? 'active' : '' ?>" href="<?= e(url('admin/?module=schools')) ?>"><i class="fa-solid fa-school"></i>Data Sekolah</a>
        <a class="<?= $active === 'finance' ? 'active' : '' ?>" href="<?= e(url('admin/?module=finance')) ?>"><i class="fa-solid fa-file-invoice-dollar"></i>Laporan Keuangan</a>
        <a class="<?= $active === 'members' ? 'active' : '' ?>" href="<?= e(url('admin/?module=members')) ?>"><i class="fa-solid fa-sitemap"></i>Pengurus</a>
        <a class="<?= $active === 'member_registrations' ? 'active' : '' ?>" href="<?= e(url('admin/?module=member_registrations')) ?>"><i class="fa-solid fa-user-plus"></i>Registrasi Anggota</a>
        <a class="<?= $active === 'users' ? 'active' : '' ?>" href="<?= e(url('admin/?module=users')) ?>"><i class="fa-solid fa-users-gear"></i>User</a>
        <a class="<?= $active === 'messages' ? 'active' : '' ?>" href="<?= e(url('admin/?module=messages')) ?>"><i class="fa-solid fa-envelope"></i>Keluhan</a>
        <a class="<?= $active === 'settings' ? 'active' : '' ?>" href="<?= e(url('admin/?module=settings')) ?>"><i class="fa-solid fa-gear"></i>Pengaturan</a>
    </nav>
</aside>
<main class="admin-main">
    <div class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center sticky-top">
        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu"><i class="fa-solid fa-bars"></i></button>
        <h5 class="mb-0">Dashboard Organisasi</h5>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-user-circle me-2"></i><?= e($admin['name']) ?></button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= e(url('')) ?>" target="_blank">Lihat Website</a></li>
                <li><a class="dropdown-item text-danger" href="<?= e(url('admin/logout.php')) ?>">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="p-4">
        <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
