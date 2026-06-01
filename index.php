<?php
// Router public sederhana berbasis query string agar cocok untuk shared hosting.
require_once __DIR__ . '/includes/functions.php';

$page = $_GET['page'] ?? 'home';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$basePath = base_path();
if ($basePath !== '' && str_starts_with($requestPath, $basePath . '/')) {
    $requestPath = substr($requestPath, strlen($basePath));
}

if (preg_match('#^/berita/([^/]+)/?$#', $requestPath, $matches)) {
    $page = 'detail';
    $_GET['slug'] = rawurldecode($matches[1]);
}

$pageTitles = [
    'home' => 'Home',
    'profil' => 'Profil',
    'berita' => 'Berita & Artikel',
    'detail' => 'Berita',
    'sekolah' => 'Data Sekolah',
    'anggota' => 'Anggota',
    'keuangan' => 'Keuangan',
    'galeri' => 'Galeri Kegiatan',
    'kontak' => 'Keluhan',
];
$pageTitle = $pageTitles[$page] ?? ucfirst($page);
$metaTitle = $pageTitle . ' - ' . setting('site_name', 'PGRI Kotamobagu');
$metaDescription = 'Website resmi PGRI Kotamobagu, pusat informasi organisasi guru, berita pendidikan, data sekolah, galeri, dan layanan publik.';
$metaImage = setting('hero_banner', default_post_image_url());
$metaVideo = '';
$canonicalUrl = current_url();
$ogType = 'website';
$articlePublishedTime = null;
$articleModifiedTime = null;
$detailPost = null;

if ($page === 'detail') {
    $stmt = db()->prepare('SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON categories.id = posts.category_id WHERE posts.slug = ? AND status = "published" LIMIT 1');
    $stmt->execute([$_GET['slug'] ?? '']);
    $detailPost = $stmt->fetch() ?: null;

    if ($detailPost) {
        $pageTitle = $detailPost['title'];
        $metaTitle = $detailPost['title'];
        $metaDescription = $detailPost['excerpt'] ?: $detailPost['content'];
        $metaImage = has_custom_post_image($detailPost['image']) ? $detailPost['image'] : $metaImage;
        $metaVideo = !empty($detailPost['video']) ? $detailPost['video'] : '';
        $canonicalUrl = absolute_url(post_url($detailPost['slug']));
        $ogType = 'article';
        $articlePublishedTime = !empty($detailPost['published_at']) ? date(DATE_ATOM, strtotime($detailPost['published_at'])) : null;
        $articleModifiedTime = !empty($detailPost['updated_at']) ? date(DATE_ATOM, strtotime($detailPost['updated_at'])) : $articlePublishedTime;
    }
}

require_once __DIR__ . '/includes/public_header.php';

function render_latest_posts_sidebar(?string $currentSlug = null): void
{
    $latestPosts = latest_posts(6);
    $shown = 0;
    ?>
    <aside class="latest-posts-sidebar">
        <h2>Berita Terbaru</h2>
        <?php foreach ($latestPosts as $latestPost): ?>
            <?php
            if ($currentSlug && $latestPost['slug'] === $currentSlug) {
                continue;
            }
            if ($shown >= 5) {
                break;
            }
            $shown++;
            ?>
            <a class="latest-post-item" href="<?= e(post_url($latestPost['slug'])) ?>">
                <?php if (has_custom_post_image($latestPost['image'])): ?>
                    <img loading="lazy" src="<?= e(media_url($latestPost['image'])) ?>" alt="<?= e($latestPost['title']) ?>">
                <?php else: ?>
                    <span class="latest-post-thumb"><i class="fa-solid fa-newspaper"></i></span>
                <?php endif; ?>
                <span>
                    <strong><?= e($latestPost['title']) ?></strong>
                    <small><?= date('d M Y', strtotime($latestPost['published_at'])) ?><?= !empty($latestPost['video']) ? ' - Video' : '' ?></small>
                </span>
            </a>
        <?php endforeach; ?>
    </aside>
    <?php
}

if ($page === 'profil') {
    $members = db()->query('SELECT * FROM organization_members ORDER BY sort_order ASC')->fetchAll();
    $historyText = setting('history') . "\n\n" . 'Sebagai bagian dari keluarga besar Persatuan Guru Republik Indonesia, PGRI Kotamobagu berperan aktif menjadi ruang pemersatu bagi guru, tenaga kependidikan, dan pemerhati pendidikan di daerah. Organisasi ini tidak hanya hadir sebagai wadah administratif, tetapi juga sebagai rumah perjuangan profesi yang mendorong peningkatan kapasitas, etika, solidaritas, dan martabat guru.' . "\n\n" . 'Dalam perkembangannya, PGRI Kotamobagu terus menyesuaikan diri dengan kebutuhan zaman. Berbagai program kerja diarahkan untuk mendukung peningkatan mutu pembelajaran, penguatan literasi digital, pendampingan anggota, serta kerja sama dengan pemerintah daerah dan satuan pendidikan. Melalui semangat kebersamaan, PGRI Kotamobagu berkomitmen menjaga peran strategis guru sebagai penggerak utama kemajuan pendidikan dan pembentukan karakter generasi muda.';
    ?>
    <header class="page-header page-header-profile"><div class="container"><h1>Profil Organisasi</h1><p class="lead mb-0">Sejarah, visi misi, dan struktur PGRI Kotamobagu.</p></div></header>
    <section class="section-band">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7"><h2 class="section-title">Sejarah PGRI Kotamobagu</h2><p><?= nl2br(e($historyText)) ?></p></div>
                <div class="col-lg-5"><h2 class="section-title">Visi & Misi</h2><h5>Visi</h5><p><?= e(setting('vision')) ?></p><h5>Misi</h5><p><?= e(setting('mission')) ?></p></div>
            </div>
            <h2 class="section-title mt-5">Struktur Organisasi</h2>
            <div class="row g-4 mt-2">
                <?php foreach ($members as $member): ?>
                    <div class="col-md-4">
                        <div class="card card-official h-100">
                            <img loading="lazy" src="<?= e(media_url($member['photo'])) ?>" alt="<?= e($member['name']) ?>">
                            <div class="card-body"><h5><?= e($member['name']) ?></h5><p class="text-danger fw-semibold"><?= e($member['position']) ?></p><p><?= e($member['bio']) ?></p></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'berita') {
    $currentPage = max(1, (int)($_GET['p'] ?? 1));
    $limit = 6;
    $offset = ($currentPage - 1) * $limit;
    $category = $_GET['kategori'] ?? '';
    $params = [];
    $where = 'WHERE posts.status = "published"';
    if ($category !== '') {
        $where .= ' AND categories.slug = ?';
        $params[] = $category;
    }
    $count = db()->prepare("SELECT COUNT(*) FROM posts LEFT JOIN categories ON categories.id = posts.category_id $where");
    $count->execute($params);
    $total = (int)$count->fetchColumn();
    $stmt = db()->prepare("SELECT posts.*, categories.name AS category_name, categories.slug AS category_slug FROM posts LEFT JOIN categories ON categories.id = posts.category_id $where ORDER BY published_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $posts = $stmt->fetchAll();
    $categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    ?>
    <header class="page-header page-header-news"><div class="container"><h1>Berita & Artikel</h1><p class="lead mb-0">Informasi resmi kegiatan dan pendidikan.</p></div></header>
    <section class="section-band section-soft">
        <div class="container">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a class="btn btn-sm btn-outline-primary" href="<?= e(url('public/?page=berita')) ?>">Semua</a>
                <?php foreach ($categories as $cat): ?><a class="btn btn-sm btn-outline-primary" href="<?= e(url('public/?page=berita&kategori=' . $cat['slug'])) ?>"><?= e($cat['name']) ?></a><?php endforeach; ?>
            </div>
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="row g-4">
                        <?php foreach ($posts as $post): ?>
                            <div class="col-md-6">
                                <article class="card card-official h-100">
                                    <?php if (has_custom_post_image($post['image'])): ?><a class="post-image-link" href="<?= e(post_url($post['slug'])) ?>"><img loading="lazy" src="<?= e(media_url($post['image'])) ?>" alt="<?= e($post['title']) ?>"></a><?php endif; ?>
                                    <div class="card-body">
                                        <span class="badge text-bg-danger mb-2"><?= e($post['category_name']) ?></span>
                                        <?php if (!empty($post['video'])): ?><span class="badge text-bg-primary mb-2">Video</span><?php endif; ?>
                                        <h5><?= e($post['title']) ?></h5>
                                        <p><?= e($post['excerpt']) ?></p>
                                        <a class="fw-semibold" href="<?= e(post_url($post['slug'])) ?>">Baca selengkapnya</a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <nav class="mt-4"><ul class="pagination">
                        <?php for ($i = 1; $i <= max(1, ceil($total / $limit)); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>"><a class="page-link" href="<?= e(url('public/?page=berita' . ($category !== '' ? '&kategori=' . urlencode($category) : '') . '&p=' . $i)) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                    </ul></nav>
                </div>
                <div class="col-lg-4">
                    <?php render_latest_posts_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php
} elseif ($page === 'detail') {
    $post = $detailPost;
    if (!$post) { echo '<main class="section-band"><div class="container"><h1>Berita tidak ditemukan</h1></div></main>'; }
    else { ?>
        <header class="page-header page-header-news"><div class="container"><span class="badge text-bg-light mb-3"><?= e($post['category_name']) ?></span><h1><?= e($post['title']) ?></h1></div></header>
        <article class="section-band"><div class="container"><div class="row g-5 align-items-start"><div class="col-lg-8"><?php if (!empty($post['video'])): ?><video class="post-video mb-4" controls preload="metadata" <?= has_custom_post_image($post['image']) ? 'poster="' . e(media_url($post['image'])) . '"' : '' ?>><source src="<?= e(media_url($post['video'])) ?>" type="video/mp4">Browser Anda tidak mendukung pemutar video.</video><?php elseif (has_custom_post_image($post['image'])): ?><a class="post-image-link" href="<?= e(media_url($post['image'])) ?>" target="_blank" rel="noopener"><img loading="lazy" class="img-fluid rounded mb-4" src="<?= e(media_url($post['image'])) ?>" alt="<?= e($post['title']) ?>"></a><?php endif; ?><p class="text-muted"><?= date('d M Y', strtotime($post['published_at'])) ?></p><div class="post-content fs-5"><?= nl2br(e($post['content'])) ?></div></div><div class="col-lg-4"><?php render_latest_posts_sidebar($post['slug']); ?></div></div></div></article>
    <?php }
} elseif ($page === 'sekolah') {
    $district = $_GET['kecamatan'] ?? '';
    $perPageOptions = [5, 10, 25, 50];
    $perPage = (int)($_GET['per_page'] ?? 5);
    if (!in_array($perPage, $perPageOptions, true)) {
        $perPage = 5;
    }
    $currentPage = max(1, (int)($_GET['p'] ?? 1));
    $where = $district ? 'WHERE district = ?' : '';
    $countStmt = db()->prepare("SELECT COUNT(*) FROM schools $where");
    $countStmt->execute($district ? [$district] : []);
    $totalSchools = (int)$countStmt->fetchColumn();
    $totalPages = max(1, (int)ceil($totalSchools / $perPage));
    $currentPage = min($currentPage, $totalPages);
    $offset = ($currentPage - 1) * $perPage;
    $stmt = db()->prepare("SELECT * FROM schools $where ORDER BY district, name LIMIT ? OFFSET ?");
    $paramIndex = 1;
    if ($district) {
        $stmt->bindValue($paramIndex++, $district);
    }
    $stmt->bindValue($paramIndex++, $perPage, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $schools = $stmt->fetchAll();
    $districts = db()->query('SELECT DISTINCT district FROM schools ORDER BY district')->fetchAll();
    $schoolPageUrl = function (int $targetPage) use ($district, $perPage): string {
        $query = ['page' => 'sekolah', 'per_page' => $perPage, 'p' => $targetPage];
        if ($district !== '') {
            $query['kecamatan'] = $district;
        }
        return url('public/?' . http_build_query($query)) . '#schoolDataSection';
    };
    ?>
    <header class="page-header page-header-schools"><div class="container"><h1>Data Sekolah</h1><p class="lead mb-0">Pencarian dan filter sekolah berdasarkan kecamatan.</p></div></header>
    <section class="section-band" id="schoolDataSection">
        <div class="container">
            <form class="row g-3 mb-4" method="get" action="<?= e(url('public/')) ?>#schoolDataSection" id="schoolFilterForm">
                <input type="hidden" name="page" value="sekolah">
                <div class="col-md-4"><input class="form-control" data-table-search="#schoolTable" placeholder="Search realtime halaman ini..."></div>
                <div class="col-md-3"><select class="form-select" name="kecamatan" onchange="document.getElementById('schoolFilterForm').submit()"><option value="">Semua Kecamatan</option><?php foreach ($districts as $d): ?><option value="<?= e($d['district']) ?>" <?= $district === $d['district'] ? 'selected' : '' ?>><?= e($d['district']) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-2"><select class="form-select" name="per_page" onchange="document.getElementById('schoolFilterForm').submit()"><?php foreach ($perPageOptions as $option): ?><option value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?> data</option><?php endforeach; ?></select></div>
                <div class="col-md-1"><button class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter"></i></button></div>
                <div class="col-md-3"><a class="btn btn-pgri w-100" href="<?= e(url('api/export_schools.php')) ?>"><i class="fa-solid fa-file-csv me-2"></i>Export CSV</a></div>
            </form>
            <p class="text-muted small mb-3">Menampilkan <?= e((string)count($schools)) ?> dari <?= e((string)$totalSchools) ?> data sekolah. Halaman <?= e((string)$currentPage) ?> dari <?= e((string)$totalPages) ?>.</p>
            <div class="table-responsive"><table class="table table-bordered align-middle" id="schoolTable"><thead><tr><th>Nama Sekolah</th><th>Jenjang</th><th>Kecamatan</th><th>Kepala Sekolah</th><th>Kontak</th></tr></thead><tbody>
                <?php foreach ($schools as $school): ?><tr><td><strong><?= e($school['name']) ?></strong><br><small><?= e($school['address']) ?></small></td><td><?= e($school['level']) ?></td><td><?= e($school['district']) ?></td><td><?= e($school['headmaster']) ?></td><td><?= e($school['phone']) ?></td></tr><?php endforeach; ?>
                <?php if (!$schools): ?><tr><td colspan="5" class="text-center text-muted py-4">Data sekolah belum tersedia.</td></tr><?php endif; ?>
            </tbody></table></div>
            <?php if ($totalPages > 1): ?>
                <nav class="mt-4" aria-label="Pagination data sekolah">
                    <ul class="pagination flex-wrap">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= e($schoolPageUrl(max(1, $currentPage - 1))) ?>">Sebelumnya</a></li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>"><a class="page-link" href="<?= e($schoolPageUrl($i)) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="<?= e($schoolPageUrl(min($totalPages, $currentPage + 1))) ?>">Berikutnya</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </section>
    <?php
} elseif ($page === 'anggota') {
    ensure_member_registrations_table();
    $flash = get_flash();
    $memberKeyword = trim($_GET['q'] ?? '');
    $memberDistrict = trim($_GET['kecamatan'] ?? '');
    $memberParams = [];
    $memberWhere = 'WHERE status = "approved"';
    if ($memberKeyword !== '') {
        $memberWhere .= ' AND (full_name LIKE ? OR school_name LIKE ? OR job_title LIKE ? OR district LIKE ?)';
        $like = '%' . $memberKeyword . '%';
        array_push($memberParams, $like, $like, $like, $like);
    }
    if ($memberDistrict !== '') {
        $memberWhere .= ' AND district = ?';
        $memberParams[] = $memberDistrict;
    }
    $memberStmt = db()->prepare("SELECT * FROM member_registrations $memberWhere ORDER BY full_name ASC, school_name ASC");
    $memberStmt->execute($memberParams);
    $registeredMembers = $memberStmt->fetchAll();
    $approvedMemberCount = (int)db()->query('SELECT COUNT(*) FROM member_registrations WHERE status = "approved"')->fetchColumn();
    $approvedSchoolCount = (int)db()->query('SELECT COUNT(DISTINCT school_name) FROM member_registrations WHERE status = "approved"')->fetchColumn();
    $approvedDistrictCount = (int)db()->query('SELECT COUNT(DISTINCT district) FROM member_registrations WHERE status = "approved" AND district IS NOT NULL AND district != ""')->fetchColumn();
    $memberDistricts = db()->query('SELECT DISTINCT district FROM member_registrations WHERE status = "approved" AND district IS NOT NULL AND district != "" ORDER BY district')->fetchAll();
    ?>
    <header class="page-header page-header-members"><div class="container"><h1>Registrasi Anggota</h1><p class="lead mb-0">Daftar sebagai anggota PGRI Kotamobagu untuk mendapatkan layanan dan informasi organisasi.</p></div></header>
    <section class="section-band section-soft">
        <div class="container">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="section-title mb-0">Direktori Anggota</h2>
                    <p class="text-muted mb-0 mt-3">Daftar anggota yang sudah diverifikasi oleh sekretariat.</p>
                </div>
                <button class="btn btn-pgri" type="button" data-bs-toggle="modal" data-bs-target="#memberRegistrationModal"><i class="fa-solid fa-user-plus me-2"></i>Registrasi Baru</button>
            </div>
            <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><div class="stat-tile p-3"><small>Anggota Terverifikasi</small><h4 class="mb-0"><?= e((string)$approvedMemberCount) ?></h4></div></div>
                <div class="col-md-4"><div class="stat-tile p-3"><small>Tempat Tugas</small><h4 class="mb-0"><?= e((string)$approvedSchoolCount) ?></h4></div></div>
                <div class="col-md-4"><div class="stat-tile p-3"><small>Kecamatan</small><h4 class="mb-0"><?= e((string)$approvedDistrictCount) ?></h4></div></div>
            </div>
            <form class="row g-3 mb-4" method="get">
                <input type="hidden" name="page" value="anggota">
                <div class="col-md-5"><input class="form-control" name="q" value="<?= e($memberKeyword) ?>" placeholder="Cari nama, sekolah, jabatan..."></div>
                <div class="col-md-4">
                    <select class="form-select" name="kecamatan">
                        <option value="">Semua Kecamatan</option>
                        <?php foreach ($memberDistricts as $districtItem): ?>
                            <option value="<?= e($districtItem['district']) ?>" <?= $memberDistrict === $districtItem['district'] ? 'selected' : '' ?>><?= e($districtItem['district']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-grid"><button class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass me-2"></i>Filter Anggota</button></div>
            </form>
            <?php if ($registeredMembers): ?>
                <div class="card card-official"><div class="table-responsive"><table class="table table-bordered align-middle mb-0 member-directory-table"><thead><tr><th>Anggota</th><th>Tempat Tugas</th><th>Wilayah</th><th>Status</th><th>Terdaftar</th></tr></thead><tbody>
                    <?php foreach ($registeredMembers as $member): ?>
                        <tr>
                            <td><strong><?= e($member['full_name']) ?></strong><br><small><?= e($member['job_title'] ?: 'Anggota PGRI') ?></small></td>
                            <td><?= e($member['school_name']) ?></td>
                            <td><?= e($member['district'] ?: '-') ?></td>
                            <td><span class="badge text-bg-success"><i class="fa-solid fa-circle-check me-1"></i>Terverifikasi</span></td>
                            <td><?= date('d M Y', strtotime($member['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody></table></div></div>
            <?php else: ?>
                <div class="card card-official p-4 text-center">
                    <i class="fa-solid fa-users fa-3x text-muted mb-3"></i>
                    <h5>Belum ada anggota terverifikasi</h5>
                    <p class="text-muted mb-0">Data anggota akan tampil di sini setelah registrasi disetujui oleh admin.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <div class="modal fade" id="memberRegistrationModal" tabindex="-1" aria-labelledby="memberRegistrationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content member-registration-modal">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="memberRegistrationModalLabel">Registrasi Anggota Baru</h5>
                        <small class="text-muted">Lengkapi data untuk diverifikasi sekretariat PGRI Kotamobagu.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="<?= e(url('api/member_registration.php')) ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <div class="member-steps mb-4">
                            <div><span>1</span><strong>Isi formulir</strong><p>Lengkapi data diri, kontak, dan tempat tugas dengan benar.</p></div>
                            <div><span>2</span><strong>Verifikasi sekretariat</strong><p>Admin akan mengecek data pendaftaran yang masuk.</p></div>
                            <div><span>3</span><strong>Konfirmasi anggota</strong><p>Hasil verifikasi akan disampaikan melalui kontak yang didaftarkan.</p></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8"><label class="form-label">Nama Lengkap</label><input class="form-control" name="full_name" required></div>
                            <div class="col-md-4"><label class="form-label">NIP/NIK/NUPTK</label><input class="form-control" name="identity_number"></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
                            <div class="col-md-6"><label class="form-label">No. HP/WhatsApp</label><input class="form-control" name="phone" required></div>
                            <div class="col-md-7"><label class="form-label">Tempat Tugas/Sekolah</label><input class="form-control" name="school_name" required></div>
                            <div class="col-md-5"><label class="form-label">Jabatan/Profesi</label><input class="form-control" name="job_title" placeholder="Guru, Kepala Sekolah, dll."></div>
                            <div class="col-md-5"><label class="form-label">Kecamatan</label><input class="form-control" name="district"></div>
                            <div class="col-md-7"><label class="form-label">Alamat</label><input class="form-control" name="address"></div>
                            <div class="col-12"><label class="form-label">Catatan/Keterangan</label><textarea class="form-control" name="reason" rows="4" placeholder="Tuliskan informasi tambahan jika diperlukan."></textarea></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-pgri"><i class="fa-solid fa-user-plus me-2"></i>Kirim Registrasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
} elseif ($page === 'keuangan') {
    $year = (int)($_GET['tahun'] ?? date('Y'));
    $month = (int)($_GET['bulan'] ?? 0);
    $params = [$year];
    $where = 'WHERE status = "published" AND period_year = ?';
    if ($month > 0) {
        $where .= ' AND period_month = ?';
        $params[] = $month;
    }
    $stmt = db()->prepare("SELECT * FROM financial_reports $where ORDER BY period_year DESC, period_month DESC, id DESC");
    $stmt->execute($params);
    $reports = $stmt->fetchAll();
    $years = db()->query('SELECT DISTINCT period_year FROM financial_reports WHERE status = "published" ORDER BY period_year DESC')->fetchAll();
    $summary = db()->prepare("SELECT COALESCE(SUM(income),0) AS income_total, COALESCE(SUM(expense),0) AS expense_total, COALESCE(SUM(balance),0) AS balance_total FROM financial_reports $where");
    $summary->execute($params);
    $totals = $summary->fetch();
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    ?>
    <header class="page-header page-header-finance"><div class="container"><h1>Laporan Keuangan</h1><p class="lead mb-0">Transparansi pemasukan dan pengeluaran organisasi PGRI Kotamobagu.</p></div></header>
    <section class="section-band section-soft">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-arrow-trend-up fa-2x text-success mb-3"></i><h4><?= rupiah((float)$totals['income_total']) ?></h4><p class="mb-0">Total Pemasukan</p></div></div>
                <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-arrow-trend-down fa-2x text-danger mb-3"></i><h4><?= rupiah((float)$totals['expense_total']) ?></h4><p class="mb-0">Total Pengeluaran</p></div></div>
                <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-wallet fa-2x text-primary mb-3"></i><h4><?= rupiah((float)$totals['balance_total']) ?></h4><p class="mb-0">Saldo Periode</p></div></div>
            </div>
            <form class="row g-3 mb-4" method="get">
                <input type="hidden" name="page" value="keuangan">
                <div class="col-md-4"><select class="form-select" name="tahun"><?php foreach ($years as $item): ?><option value="<?= e($item['period_year']) ?>" <?= $year === (int)$item['period_year'] ? 'selected' : '' ?>><?= e($item['period_year']) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-4"><select class="form-select" name="bulan"><option value="0">Semua Bulan</option><?php foreach ($months as $number => $name): ?><option value="<?= $number ?>" <?= $month === $number ? 'selected' : '' ?>><?= e($name) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-2"><button class="btn btn-pgri w-100"><i class="fa-solid fa-filter me-2"></i>Filter</button></div>
                <div class="col-md-2"><a class="btn btn-outline-primary w-100" href="<?= e(url('api/export_finance.php')) ?>"><i class="fa-solid fa-file-csv me-2"></i>CSV</a></div>
            </form>
            <input class="form-control mb-3" data-table-search="#financeTable" placeholder="Search realtime judul, kategori, keterangan...">
            <div class="card card-official"><div class="table-responsive"><table class="table table-bordered align-middle mb-0" id="financeTable"><thead><tr><th>Periode</th><th>Uraian</th><th>Kategori</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th><th>Dokumen</th></tr></thead><tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= e($months[(int)$report['period_month']] ?? '-') ?> <?= e($report['period_year']) ?></td>
                        <td><strong><?= e($report['title']) ?></strong><br><small><?= e($report['description']) ?></small></td>
                        <td><?= e($report['category']) ?></td>
                        <td class="text-success fw-semibold"><?= rupiah((float)$report['income']) ?></td>
                        <td class="text-danger fw-semibold"><?= rupiah((float)$report['expense']) ?></td>
                        <td class="fw-bold"><?= rupiah((float)$report['balance']) ?></td>
                        <td><?php if ($report['document']): ?><a class="btn btn-sm btn-outline-primary" href="<?= e(media_url($report['document'])) ?>" target="_blank"><i class="fa-solid fa-download me-1"></i>Unduh</a><?php else: ?><span class="text-muted">-</span><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody></table></div></div>
        </div>
    </section>
    <?php
} elseif ($page === 'galeri') {
    $galleries = db()->query('SELECT * FROM galleries ORDER BY event_date DESC, id DESC')->fetchAll();
    ?>
    <header class="page-header page-header-gallery"><div class="container"><h1>Galeri Kegiatan</h1><p class="lead mb-0">Dokumentasi kegiatan PGRI Kotamobagu.</p></div></header>
    <section class="section-band section-soft"><div class="container"><div class="row g-4">
        <?php foreach ($galleries as $item): ?><div class="col-md-6 col-lg-4"><div class="gallery-item" data-lightbox="<?= e(media_url($item['image'])) ?>"><img loading="lazy" src="<?= e(media_url($item['image'])) ?>" alt="<?= e($item['title']) ?>"><h5 class="mt-3"><?= e($item['title']) ?></h5><p class="text-muted"><?= e($item['description']) ?></p></div></div><?php endforeach; ?>
    </div></div></section>
    <?php
} elseif ($page === 'kontak') {
    $flash = get_flash();
    ?>
    <header class="page-header page-header-contact"><div class="container"><h1>Keluhan</h1><p class="lead mb-0">Sampaikan keluhan, masukan, atau pertanyaan kepada sekretariat PGRI Kotamobagu.</p></div></header>
    <section class="section-band"><div class="container" style="max-width: 760px;">
            <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
            <form class="card card-official p-4" action="<?= e(url('api/contact.php')) ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
                <div class="mb-3"><label class="form-label">Subjek</label><input class="form-control" name="subject" required></div>
                <div class="mb-3"><label class="form-label">Pesan</label><textarea class="form-control" name="message" rows="5" required></textarea></div>
                <button class="btn btn-pgri"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Keluhan</button>
            </form>
    </div></section>
    <?php
} else {
    $posts = latest_posts(3);
    $schoolCount = db()->query('SELECT COUNT(*) FROM schools')->fetchColumn();
    $galleryCount = db()->query('SELECT COUNT(*) FROM galleries')->fetchColumn();
    $heroBanner = setting('hero_banner', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80');
    $chairpersonStmt = db()->prepare('SELECT name, position, photo FROM organization_members WHERE position LIKE ? ORDER BY sort_order ASC, id ASC LIMIT 1');
    $chairpersonStmt->execute(['%Ketua%']);
    $chairperson = $chairpersonStmt->fetch() ?: [];
    $chairpersonPhoto = $chairperson['photo'] ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=900&q=80';
    $chairpersonName = $chairperson['name'] ?? '';
    $chairpersonPosition = $chairperson['position'] ?? 'Ketua PGRI Kotamobagu';
    ?>
    <header class="hero" style="background-image: linear-gradient(90deg, rgba(15, 61, 46, .92), rgba(19, 138, 61, .74)), url(&quot;<?= e(media_url($heroBanner)) ?>&quot;);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <span class="badge text-bg-light mb-3">Website Resmi Organisasi Profesi Guru</span>
                    <h1 class="display-4">PGRI Kotamobagu</h1>
                    <p class="lead"><?= e(setting('vision')) ?></p>
                    <a href="<?= e(url('public/?page=profil')) ?>" class="btn btn-light btn-lg">Lihat Profil</a>
                </div>
            </div>
        </div>
    </header>
    <section class="section-band">
        <div class="container">
            <h2 class="section-title">Berita Terbaru</h2>
            <div class="row g-4 align-items-start mt-2">
                <div class="col-lg-8">
                    <div class="row g-4">
                        <?php foreach ($posts as $post): ?><div class="col-md-6 col-xl-4"><article class="card card-official h-100"><?php if (has_custom_post_image($post['image'])): ?><a class="post-image-link" href="<?= e(post_url($post['slug'])) ?>"><img loading="lazy" src="<?= e(media_url($post['image'])) ?>" alt="<?= e($post['title']) ?>"></a><?php endif; ?><div class="card-body"><span class="badge text-bg-danger mb-2"><?= e($post['category_name']) ?></span><?php if (!empty($post['video'])): ?> <span class="badge text-bg-primary mb-2">Video</span><?php endif; ?><h5><?= e($post['title']) ?></h5><p class="home-post-excerpt"><?= e($post['excerpt']) ?></p><a class="fw-semibold" href="<?= e(post_url($post['slug'])) ?>">Baca selengkapnya</a></div></article></div><?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php render_latest_posts_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="section-band section-soft">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5"><img loading="lazy" class="img-fluid rounded shadow" src="<?= e(media_url($chairpersonPhoto)) ?>" alt="<?= e($chairpersonPosition) ?>"></div>
                <div class="col-lg-7"><h2 class="section-title">Sambutan Ketua PGRI</h2><p class="fs-5 chairperson-message">Selamat datang di website resmi PGRI Kotamobagu. Media ini menjadi ruang informasi, layanan, dan kolaborasi bagi seluruh insan pendidikan. Kehadiran website ini kami harapkan mampu mempercepat penyampaian informasi organisasi, memperkuat komunikasi antaranggota, serta menjadi sarana publikasi kegiatan PGRI yang transparan dan mudah diakses masyarakat.</p><p class="fs-5 chairperson-message">PGRI Kotamobagu terus berkomitmen mendukung peningkatan kompetensi, perlindungan profesi, dan kesejahteraan guru. Melalui kerja sama yang solid antara pengurus, anggota, sekolah, pemerintah daerah, dan seluruh pemangku kepentingan pendidikan, kami percaya kualitas pendidikan di Kota Kotamobagu dapat terus tumbuh secara berkelanjutan.</p><p class="fs-5 chairperson-message">Mari bergerak bersama memperkuat profesionalisme guru, membangun budaya belajar yang adaptif, dan menghadirkan layanan pendidikan yang bermutu bagi generasi masa depan.</p><p class="fw-semibold mb-0"><?= e($chairpersonName ?: $chairpersonPosition) ?></p><p class="text-muted mb-0"><?= e($chairpersonPosition) ?></p></div>
            </div>
        </div>
    </section>
    <section class="section-band">
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-users fa-2x text-danger mb-3"></i><h3>1.250+</h3><p class="mb-0">Anggota Guru</p></div></div>
            <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-school fa-2x text-primary mb-3"></i><h3><?= e($schoolCount) ?></h3><p class="mb-0">Data Sekolah</p></div></div>
            <div class="col-md-4"><div class="stat-tile p-4"><i class="fa-solid fa-calendar-check fa-2x text-success mb-3"></i><h3><?= e($galleryCount) ?>+</h3><p class="mb-0">Kegiatan Terdokumentasi</p></div></div>
        </div></div>
    </section>
    <?php
}

require_once __DIR__ . '/includes/public_footer.php';
