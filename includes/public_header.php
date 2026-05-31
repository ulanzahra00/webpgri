<?php
// Header public dipakai seluruh halaman agar konsisten dan mudah dipelihara.
$pageTitle = $pageTitle ?? setting('site_name', 'PGRI Kotamobagu');
$siteName = setting('site_name', 'PGRI Kotamobagu');
$siteLogo = setting('site_logo');
$heroBanner = setting('hero_banner', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80');
$metaTitle = $metaTitle ?? $pageTitle;
$metaDescription = meta_description($metaDescription ?? null);
$rawMetaImage = $metaImage ?? ($siteLogo ?: $heroBanner);
$metaImageDimensions = media_dimensions($rawMetaImage);
$metaImageType = media_mime_type($rawMetaImage);
$metaImage = media_absolute_url($rawMetaImage);
$canonicalUrl = $canonicalUrl ?? current_url();
$ogType = $ogType ?? 'website';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="PGRI Kotamobagu, guru, pendidikan, organisasi, sekolah">
    <title><?= e($pageTitle) ?> - <?= e($siteName) ?></title>
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($metaImage) ?>">
    <?php if (str_starts_with($metaImage, 'https://')): ?>
        <meta property="og:image:secure_url" content="<?= e($metaImage) ?>">
    <?php endif; ?>
    <?php if ($metaImageType): ?>
        <meta property="og:image:type" content="<?= e($metaImageType) ?>">
    <?php endif; ?>
    <meta property="og:image:alt" content="<?= e($metaTitle) ?>">
    <?php if ($metaImageDimensions): ?>
        <meta property="og:image:width" content="<?= e((string)$metaImageDimensions['width']) ?>">
        <meta property="og:image:height" content="<?= e((string)$metaImageDimensions['height']) ?>">
    <?php endif; ?>
    <?php if ($ogType === 'article' && !empty($articlePublishedTime)): ?>
        <meta property="article:published_time" content="<?= e($articlePublishedTime) ?>">
    <?php endif; ?>
    <?php if ($ogType === 'article' && !empty($articleModifiedTime)): ?>
        <meta property="article:modified_time" content="<?= e($articleModifiedTime) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($metaImage) ?>">
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
<nav class="navbar navbar-expand-lg bg-white sticky-top main-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= e(url('public/')) ?>">
            <span class="logo-mark"><?php if ($siteLogo): ?><img src="<?= e(media_url($siteLogo)) ?>" alt="<?= e($siteName) ?>"><?php else: ?><i class="fa-solid fa-chalkboard-user"></i><?php endif; ?></span>
            <span><strong><?= e($siteName) ?></strong><small class="d-block text-muted">Organisasi Profesi Guru</small></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/')) ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=profil')) ?>">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=berita')) ?>">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=sekolah')) ?>">Data Sekolah</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=anggota')) ?>">Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=keuangan')) ?>">Keuangan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=galeri')) ?>">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('public/?page=kontak')) ?>">Keluhan</a></li>
                <li class="nav-item"><a class="btn btn-sm btn-pgri ms-lg-2" href="<?= e(url('admin/login.php')) ?>"><i class="fa-solid fa-right-to-bracket me-1"></i>Login Admin</a></li>
            </ul>
        </div>
    </div>
</nav>
