<?php
// Header public dipakai seluruh halaman agar konsisten dan mudah dipelihara.
$pageTitle = $pageTitle ?? setting('site_name', 'PGRI Kotamobagu');
$siteName = setting('site_name', 'PGRI Kotamobagu');
$siteLogo = setting('site_logo');
$heroBanner = setting('hero_banner', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80');
$metaDescription = $metaDescription ?? 'Website resmi PGRI Kotamobagu, pusat informasi organisasi guru, berita pendidikan, data sekolah, galeri, dan layanan publik.';
$metaImage = $metaImage ?? absolute_url($siteLogo ?: $heroBanner ?: default_post_image_url());
$metaImageWidth = $metaImageWidth ?? 1200;
$metaImageHeight = $metaImageHeight ?? 630;
$metaImageType = $metaImageType ?? 'image/jpeg';
$metaUrl = $metaUrl ?? absolute_url(url(''));
$metaType = $metaType ?? 'website';
$metaTitle = $pageTitle . ' - ' . $siteName;
$activePage = $page ?? 'home';
$navItems = [
    'home' => ['label' => 'Home', 'href' => url(''), 'icon' => 'fa-house'],
    'profil' => ['label' => 'Profil', 'href' => url('?page=profil'), 'icon' => 'fa-id-card'],
    'berita' => ['label' => 'Berita', 'href' => url('?page=berita'), 'icon' => 'fa-newspaper'],
    'sekolah' => ['label' => 'Data Sekolah', 'href' => url('?page=sekolah'), 'icon' => 'fa-school'],
    'anggota' => ['label' => 'Anggota', 'href' => url('?page=anggota'), 'icon' => 'fa-users'],
    'keuangan' => ['label' => 'Keuangan', 'href' => url('?page=keuangan'), 'icon' => 'fa-wallet'],
    'galeri' => ['label' => 'Galeri', 'href' => url('?page=galeri'), 'icon' => 'fa-images'],
    'kontak' => ['label' => 'Keluhan', 'href' => url('?page=kontak'), 'icon' => 'fa-comment-dots'],
];
$activeNavKey = $activePage === 'detail' ? 'berita' : $activePage;
$activeNavLabel = $navItems[$activeNavKey]['label'] ?? 'Menu';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="keywords" content="PGRI Kotamobagu, guru, pendidikan, organisasi, sekolah">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:type" content="<?= e($metaType) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($metaUrl) ?>">
    <meta property="og:image" content="<?= e($metaImage) ?>">
    <meta property="og:image:url" content="<?= e($metaImage) ?>">
    <meta property="og:image:secure_url" content="<?= e($metaImage) ?>">
    <meta property="og:image:type" content="<?= e($metaImageType) ?>">
    <meta property="og:image:width" content="<?= e((string)$metaImageWidth) ?>">
    <meta property="og:image:height" content="<?= e((string)$metaImageHeight) ?>">
    <meta property="og:image:alt" content="<?= e($pageTitle) ?>">
    <?php if (!empty($metaPublishedAt)): ?>
    <meta property="article:published_time" content="<?= e($metaPublishedAt) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($metaImage) ?>">
    <meta name="thumbnail" content="<?= e($metaImage) ?>">
    <title><?= e($metaTitle) ?></title>
    <link rel="canonical" href="<?= e($metaUrl) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(asset_url('assets/css/style.css')) ?>" rel="stylesheet">
    <style>:root{--page-header-image:url("<?= e(media_url($heroBanner)) ?>");}</style>
</head>
<body>
<div class="loading-overlay"><div class="spinner-border text-danger" role="status"></div></div>
<div class="top-strip py-2">
    <div class="container d-flex flex-wrap gap-3 justify-content-between">
        <span><i class="fa-solid fa-location-dot me-2"></i><?= e(setting('address')) ?></span>
        <span><i class="fa-solid fa-envelope me-2"></i><?= e(setting('email')) ?> <span class="mx-2">|</span> <i class="fa-solid fa-phone me-2"></i><?= e(setting('phone')) ?></span>
    </div>
</div>
<nav class="navbar navbar-expand-xl bg-white sticky-top main-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= e(url('')) ?>">
            <span class="logo-mark"><?php if ($siteLogo): ?><img src="<?= e(media_url($siteLogo)) ?>" alt="<?= e($siteName) ?>"><?php else: ?><i class="fa-solid fa-chalkboard-user"></i><?php endif; ?></span>
            <span><strong><?= e($siteName) ?></strong><small class="d-block text-muted">Organisasi Profesi Guru</small></span>
        </a>
        <div class="dropdown mobile-nav-menu d-xl-none">
            <button class="btn btn-pgri dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-2"></i><?= e($activeNavLabel) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php foreach ($navItems as $key => $item): ?>
                    <li><a class="dropdown-item <?= $activeNavKey === $key ? 'active' : '' ?>" href="<?= e($item['href']) ?>"><i class="fa-solid <?= e($item['icon']) ?> me-2"></i><?= e($item['label']) ?></a></li>
                <?php endforeach; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= e(url('admin/login.php')) ?>"><i class="fa-solid fa-right-to-bracket me-2"></i>Login Admin</a></li>
            </ul>
        </div>
        <ul class="navbar-nav ms-auto gap-xl-2 d-none d-xl-flex">
            <?php foreach ($navItems as $key => $item): ?>
                <li class="nav-item"><a class="nav-link <?= $activeNavKey === $key ? 'active' : '' ?>" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
            <?php endforeach; ?>
            <li class="nav-item"><a class="btn btn-sm btn-pgri ms-xl-2" href="<?= e(url('admin/login.php')) ?>"><i class="fa-solid fa-right-to-bracket me-1"></i>Login Admin</a></li>
        </ul>
    </div>
</nav>
