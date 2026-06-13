<?php
// Layout admin: memastikan halaman terlindungi dan memiliki sidebar/topbar konsisten.
require_admin();
$admin = current_admin();
$active = $_GET['module'] ?? 'dashboard';
$flash = get_flash();
$siteLogo = setting('site_logo');
$adminNavItems = [
    'dashboard' => ['label' => 'Dashboard', 'href' => url('admin/'), 'icon' => 'fa-gauge'],
    'posts' => ['label' => 'Berita', 'href' => url('admin/?module=posts'), 'icon' => 'fa-newspaper'],
    'galleries' => ['label' => 'Galeri', 'href' => url('admin/?module=galleries'), 'icon' => 'fa-images'],
    'schools' => ['label' => 'Data Sekolah', 'href' => url('admin/?module=schools'), 'icon' => 'fa-school'],
    'finance' => ['label' => 'Laporan Keuangan', 'href' => url('admin/?module=finance'), 'icon' => 'fa-file-invoice-dollar'],
    'members' => ['label' => 'Pengurus', 'href' => url('admin/?module=members'), 'icon' => 'fa-sitemap'],
    'member_registrations' => ['label' => 'Registrasi Anggota', 'href' => url('admin/?module=member_registrations'), 'icon' => 'fa-user-plus'],
    'users' => ['label' => 'User', 'href' => url('admin/?module=users'), 'icon' => 'fa-users-gear'],
    'messages' => ['label' => 'Keluhan', 'href' => url('admin/?module=messages'), 'icon' => 'fa-envelope'],
    'documents' => ['label' => 'Dokumen', 'href' => url('admin/?module=documents'), 'icon' => 'fa-folder-open'],
    'settings' => ['label' => 'Pengaturan', 'href' => url('admin/?module=settings'), 'icon' => 'fa-gear'],
];
$activeAdminLabel = $adminNavItems[$active]['label'] ?? 'Menu Admin';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
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
        <?php foreach ($adminNavItems as $key => $item): ?>
            <a class="<?= $active === $key ? 'active' : '' ?>" href="<?= e($item['href']) ?>"><i class="fa-solid <?= e($item['icon']) ?>"></i><?= e($item['label']) ?></a>
        <?php endforeach; ?>
    </nav>
</aside>
<main class="admin-main">
    <div class="admin-topbar bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center sticky-top">
        <div class="dropdown admin-mobile-menu d-lg-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-2"></i><?= e($activeAdminLabel) ?>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($adminNavItems as $key => $item): ?>
                    <li><a class="dropdown-item <?= $active === $key ? 'active' : '' ?>" href="<?= e($item['href']) ?>"><i class="fa-solid <?= e($item['icon']) ?> me-2"></i><?= e($item['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
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
