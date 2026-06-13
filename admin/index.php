<?php
// Router dashboard admin dan CRUD sederhana untuk semua modul utama.
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_header.php';

$module = $_GET['module'] ?? 'dashboard';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

function admin_table_actions(string $module, int $id): string
{
    return '<a class="btn btn-sm btn-outline-primary" href="' . e(url('admin/?module=' . $module . '&action=form&id=' . $id)) . '"><i class="fa-solid fa-pen"></i></a>
    <a data-confirm="Hapus data ini?" class="btn btn-sm btn-outline-danger" href="' . e(url('admin/actions.php?module=' . $module . '&action=delete&id=' . $id . '&csrf_token=' . csrf_token())) . '"><i class="fa-solid fa-trash"></i></a>';
}

if ($module === 'dashboard') {
    ensure_member_registrations_table();
    ?>
    <div class="row g-4">
        <div class="col-12">
            <div class="card card-official">
                <div class="card-body">
                    <h5>Selamat datang</h5>
                    <p class="mb-0">Gunakan menu admin untuk mengelola konten website resmi PGRI Kotamobagu.</p>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($module === 'posts') {
    ensure_posts_video_column();

    if ($action === 'form') {
        $post = ['id' => 0, 'category_id' => '', 'title' => '', 'excerpt' => '', 'content' => '', 'status' => 'published', 'image' => '', 'video' => ''];
        if ($id) {
            $stmt = db()->prepare('SELECT * FROM posts WHERE id = ?');
            $stmt->execute([$id]);
            $post = $stmt->fetch() ?: $post;
        }
        $categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> Berita</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="posts"><input type="hidden" name="id" value="<?= e($post['id']) ?>">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Judul</label><input class="form-control" name="title" value="<?= e($post['title']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Kategori</label><select class="form-select" name="category_id"><?php foreach ($categories as $cat): ?><option value="<?= e($cat['id']) ?>" <?= (int)$post['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option><?php endforeach; ?></select></div>
                <div class="col-12"><label class="form-label">Ringkasan</label><textarea class="form-control" name="excerpt" rows="2"><?= e($post['excerpt']) ?></textarea></div>
                <div class="col-12"><label class="form-label">Konten</label><textarea class="form-control" name="content" rows="8" required><?= e($post['content']) ?></textarea></div>
                <div class="col-md-6"><label class="form-label">Gambar</label><input class="form-control" type="file" name="image" accept="image/*"><small class="text-muted">Kosongkan jika tidak mengganti.</small></div>
                <div class="col-md-6"><label class="form-label">Video MP4</label><input class="form-control" type="file" name="video" accept="video/mp4,.mp4"><small class="text-muted">Opsional, maksimal 50MB. Kosongkan jika tidak mengganti.</small><?php if (!empty($post['video'])): ?><div class="mt-2"><a href="<?= e(media_url($post['video'])) ?>" target="_blank">Lihat video saat ini</a></div><?php endif; ?></div>
                <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="published">Published</option><option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
            </div>
            <button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $rows = db()->query('SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON categories.id = posts.category_id ORDER BY posts.id DESC')->fetchAll();
        ?>
        <div class="d-flex justify-content-between mb-3"><h4>Kelola Berita</h4><a class="btn btn-pgri" href="<?= e(url('admin/?module=posts&action=form')) ?>">Tambah Berita</a></div>
        <div class="card card-official"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Judul</th><th>Kategori</th><th>Media</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach ($rows as $row): ?><tr><td><?= e($row['title']) ?></td><td><?= e($row['category_name']) ?></td><td><?= !empty($row['video']) ? '<span class="badge text-bg-primary">Video MP4</span>' : '<span class="badge text-bg-secondary">Gambar</span>' ?></td><td><?= e($row['status']) ?></td><td><?= admin_table_actions('posts', (int)$row['id']) ?></td></tr><?php endforeach; ?>
        </tbody></table></div></div>
        <?php
    }
} elseif ($module === 'galleries') {
    if ($action === 'form') {
        $row = ['id' => 0, 'title' => '', 'description' => '', 'event_date' => date('Y-m-d'), 'image' => ''];
        if ($id) { $stmt = db()->prepare('SELECT * FROM galleries WHERE id = ?'); $stmt->execute([$id]); $row = $stmt->fetch() ?: $row; }
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> Galeri</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="galleries"><input type="hidden" name="id" value="<?= e($row['id']) ?>">
            <div class="mb-3"><label class="form-label">Judul</label><input class="form-control" name="title" value="<?= e($row['title']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description"><?= e($row['description']) ?></textarea></div>
            <div class="row g-3"><div class="col-md-6"><label class="form-label">Tanggal</label><input class="form-control" type="date" name="event_date" value="<?= e($row['event_date']) ?>"></div><div class="col-md-6"><label class="form-label">Gambar</label><input class="form-control" type="file" name="image" accept="image/*" <?= $id ? '' : 'required' ?>></div></div>
            <button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $rows = db()->query('SELECT * FROM galleries ORDER BY id DESC')->fetchAll();
        echo '<div class="d-flex justify-content-between mb-3"><h4>Kelola Galeri</h4><a class="btn btn-pgri" href="' . e(url('admin/?module=galleries&action=form')) . '">Tambah Foto</a></div><div class="row g-4">';
        foreach ($rows as $row) {
            echo '<div class="col-md-4"><div class="card card-official"><img src="' . e(media_url($row['image'])) . '" alt=""><div class="card-body"><h5>' . e($row['title']) . '</h5>' . admin_table_actions('galleries', (int)$row['id']) . '</div></div></div>';
        }
        echo '</div>';
    }
} elseif ($module === 'schools') {
    if ($action === 'form') {
        $row = ['id' => 0, 'name' => '', 'level' => '', 'district' => '', 'address' => '', 'headmaster' => '', 'phone' => ''];
        if ($id) { $stmt = db()->prepare('SELECT * FROM schools WHERE id = ?'); $stmt->execute([$id]); $row = $stmt->fetch() ?: $row; }
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> Data Sekolah</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="schools"><input type="hidden" name="id" value="<?= e($row['id']) ?>">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Nama Sekolah</label><input class="form-control" name="name" value="<?= e($row['name']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Jenjang</label><input class="form-control" name="level" value="<?= e($row['level']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Kecamatan</label><input class="form-control" name="district" value="<?= e($row['district']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Kepala Sekolah</label><input class="form-control" name="headmaster" value="<?= e($row['headmaster']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Telepon</label><input class="form-control" name="phone" value="<?= e($row['phone']) ?>"></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="address" required><?= e($row['address']) ?></textarea></div>
            </div><button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $rows = db()->query('SELECT * FROM schools ORDER BY district, name')->fetchAll();
        ?>
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Kelola Data Sekolah</h4>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" href="<?= e(url('admin/actions.php?module=schools&action=template')) ?>"><i class="fa-solid fa-file-arrow-down me-2"></i>Download Template</a>
                <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#importSchoolsModal"><i class="fa-solid fa-file-import me-2"></i>Import Excel</button>
                <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteSchoolsModal"><i class="fa-solid fa-trash-can me-2"></i>Hapus Semua</button>
                <a class="btn btn-pgri" href="<?= e(url('admin/?module=schools&action=form')) ?>">Tambah Sekolah</a>
            </div>
        </div>
        <div class="modal fade" id="importSchoolsModal" tabindex="-1" aria-labelledby="importSchoolsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title" id="importSchoolsModalLabel">Import Data Sekolah</h5>
                                <small class="text-muted">Gunakan template agar kolom sesuai.</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="module" value="schools">
                            <input type="hidden" name="action" value="import">
                            <div class="mb-3">
                                <label class="form-label">File Excel/CSV</label>
                                <input class="form-control" type="file" name="school_file" accept=".xlsx,.csv" required>
                                <small class="text-muted">Format yang didukung: XLSX atau CSV. Kolom wajib: Nama Sekolah, Jenjang, Kecamatan, Alamat.</small>
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/actions.php?module=schools&action=template')) ?>"><i class="fa-solid fa-file-arrow-down me-1"></i>Download Template CSV</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-pgri"><i class="fa-solid fa-file-import me-2"></i>Import Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteSchoolsModal" tabindex="-1" aria-labelledby="deleteSchoolsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <form method="post" action="<?= e(url('admin/actions.php')) ?>">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title" id="deleteSchoolsModalLabel">Pilih Data Sekolah yang Dihapus</h5>
                                <small class="text-muted">Centang hanya data yang ingin dihapus. Data yang tidak dicentang akan tetap tersimpan.</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="module" value="schools">
                            <input type="hidden" name="action" value="delete_selected">
                            <?php if ($rows): ?>
                                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                                    <strong><?= count($rows) ?> data sekolah</strong>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="document.querySelectorAll('.school-delete-checkbox').forEach((checkbox) => checkbox.checked = true)">Centang Semua</button>
                                        <button class="btn btn-sm btn-outline-danger" type="button" onclick="const checked = document.querySelectorAll('.school-delete-checkbox:checked').length; if (!checked) { alert('Pilih minimal satu data sekolah yang ingin dihapus.'); return; } if (confirm('Hapus data sekolah yang dicentang?')) { this.closest('form').submit(); }">Hapus Terpilih</button>
                                    </div>
                                </div>
                                <div class="list-group school-delete-list">
                                    <?php foreach ($rows as $row): ?>
                                        <label class="list-group-item d-flex gap-3 align-items-start">
                                            <input class="form-check-input mt-1 school-delete-checkbox" type="checkbox" name="school_ids[]" value="<?= e($row['id']) ?>">
                                            <span>
                                                <strong><?= e($row['name']) ?></strong>
                                                <small class="d-block text-muted"><?= e($row['level']) ?> - <?= e($row['district']) ?></small>
                                                <small class="d-block text-muted"><?= e($row['address']) ?></small>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">Belum ada data sekolah untuk dihapus.</div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button data-confirm="Hapus data sekolah yang dicentang?" class="btn btn-danger" <?= $rows ? '' : 'disabled' ?>><i class="fa-solid fa-trash-can me-2"></i>Hapus yang Dicentang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <input class="form-control mb-3" data-table-search="#adminSchoolTable" placeholder="Cari sekolah...">
        <div class="card card-official"><div class="table-responsive"><table class="table mb-0" id="adminSchoolTable"><thead><tr><th>Nama</th><th>Jenjang</th><th>Kecamatan</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach ($rows as $row): ?><tr><td><?= e($row['name']) ?></td><td><?= e($row['level']) ?></td><td><?= e($row['district']) ?></td><td><?= admin_table_actions('schools', (int)$row['id']) ?></td></tr><?php endforeach; ?>
        </tbody></table></div></div>
        <?php
    }
} elseif ($module === 'finance') {
    ensure_financial_reports_deposit_date_column();
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $periodOptions = [0 => 'Tahunan (Januari-Desember)'] + $months;
    if ($action === 'form') {
        $row = ['id' => 0, 'title' => '', 'period_month' => date('n'), 'period_year' => date('Y'), 'deposit_date' => date('Y-m-d'), 'category' => '', 'income' => 0, 'expense' => 0, 'description' => '', 'document' => '', 'status' => 'published'];
        if ($id) { $stmt = db()->prepare('SELECT * FROM financial_reports WHERE id = ?'); $stmt->execute([$id]); $row = $stmt->fetch() ?: $row; }
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> Laporan Keuangan</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="finance"><input type="hidden" name="id" value="<?= e($row['id']) ?>">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Judul Laporan</label><input class="form-control" name="title" value="<?= e($row['title']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Kategori</label><input class="form-control" name="category" value="<?= e($row['category']) ?>" placeholder="Iuran, Program Kerja, Bantuan" required></div>
                <div class="col-md-3"><label class="form-label">Periode</label><select class="form-select" name="period_month"><?php foreach ($periodOptions as $number => $name): ?><option value="<?= $number ?>" <?= (int)$row['period_month'] === $number ? 'selected' : '' ?>><?= e($name) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Tahun</label><input class="form-control" type="number" min="2000" max="2100" name="period_year" value="<?= e($row['period_year']) ?>" required></div>
                <div class="col-md-3"><label class="form-label">Tanggal Setor</label><input class="form-control" type="date" name="deposit_date" value="<?= e($row['deposit_date'] ?? '') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Pemasukan</label><input class="form-control" type="number" min="0" step="100" name="income" value="<?= e($row['income']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Pengeluaran</label><input class="form-control" type="number" min="0" step="100" name="expense" value="<?= e($row['expense']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Dokumen PDF/Excel</label><input class="form-control" type="file" name="document" accept=".pdf,.xls,.xlsx"><small class="text-muted">Maksimal 5MB. Kosongkan jika tidak mengganti.</small></div>
                <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="published">Published</option><option value="draft" <?= $row['status'] === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea class="form-control" name="description" rows="4"><?= e($row['description']) ?></textarea></div>
            </div>
            <button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $filterMonth = $_GET['bulan'] ?? '';
        if ($filterMonth === 'annual') {
            $filterMonth = 0;
        } elseif ($filterMonth !== '') {
            $filterMonth = (int)$filterMonth;
            if ($filterMonth < 1 || $filterMonth > 12) {
                $filterMonth = '';
            }
        }

        $where = '';
        $params = [];
        if ($filterMonth !== '') {
            $where = 'WHERE period_month = ?';
            $params[] = $filterMonth;
        }

        $stmt = db()->prepare("SELECT * FROM financial_reports $where ORDER BY period_year DESC, period_month DESC, id DESC");
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $summary = db()->prepare("SELECT COALESCE(SUM(income),0) AS income_total, COALESCE(SUM(expense),0) AS expense_total, COALESCE(SUM(balance),0) AS balance_total FROM financial_reports $where");
        $summary->execute($params);
        $totals = $summary->fetch();
        ?>
        <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
            <h4>Kelola Laporan Keuangan</h4>
            <div><a class="btn btn-outline-primary" href="<?= e(url('api/export_finance.php')) ?>"><i class="fa-solid fa-file-csv me-2"></i>Export CSV</a> <a class="btn btn-pgri" href="<?= e(url('admin/?module=finance&action=form')) ?>">Tambah Laporan</a></div>
        </div>
        <form class="card card-official p-3 mb-3" method="get">
            <input type="hidden" name="module" value="finance">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Pilih Periode</label>
                    <select class="form-select" name="bulan">
                        <option value="" <?= $filterMonth === '' ? 'selected' : '' ?>>Semua Periode</option>
                        <option value="annual" <?= $filterMonth === 0 ? 'selected' : '' ?>>Tahunan (Januari-Desember)</option>
                        <?php foreach ($months as $number => $name): ?>
                            <option value="<?= $number ?>" <?= $filterMonth === $number ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-pgri"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                </div>
                <?php if ($filterMonth !== ''): ?>
                    <div class="col-md-auto">
                        <a class="btn btn-outline-secondary" href="<?= e(url('admin/?module=finance')) ?>">Reset</a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><div class="stat-tile p-3"><small>Total Pemasukan</small><h5 class="text-success mb-0"><?= rupiah((float)$totals['income_total']) ?></h5></div></div>
            <div class="col-md-4"><div class="stat-tile p-3"><small>Total Pengeluaran</small><h5 class="text-danger mb-0"><?= rupiah((float)$totals['expense_total']) ?></h5></div></div>
            <div class="col-md-4"><div class="stat-tile p-3"><small>Saldo</small><h5 class="text-primary mb-0"><?= rupiah((float)$totals['balance_total']) ?></h5></div></div>
        </div>
        <input class="form-control mb-3" data-table-search="#adminFinanceTable" placeholder="Cari laporan keuangan...">
        <div class="card card-official"><div class="table-responsive"><table class="table mb-0 align-middle" id="adminFinanceTable"><thead><tr><th>Periode</th><th>Tanggal Setor</th><th>Judul</th><th>Kategori</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach ($rows as $row): ?><tr><td><?= e($periodOptions[(int)$row['period_month']] ?? '-') ?> <?= e($row['period_year']) ?></td><td><?= !empty($row['deposit_date']) ? e(date('d M Y', strtotime($row['deposit_date']))) : '-' ?></td><td><?= e($row['title']) ?></td><td><?= e($row['category']) ?></td><td class="text-success"><?= rupiah((float)$row['income']) ?></td><td class="text-danger"><?= rupiah((float)$row['expense']) ?></td><td class="fw-semibold"><?= rupiah((float)$row['balance']) ?></td><td><?= e($row['status']) ?></td><td><?= admin_table_actions('finance', (int)$row['id']) ?></td></tr><?php endforeach; ?>
        </tbody></table></div></div>
        <?php
    }
} elseif ($module === 'members') {
    if ($action === 'form') {
        $row = ['id' => 0, 'name' => '', 'position' => '', 'bio' => '', 'photo' => '', 'sort_order' => 0];
        if ($id) { $stmt = db()->prepare('SELECT * FROM organization_members WHERE id = ?'); $stmt->execute([$id]); $row = $stmt->fetch() ?: $row; }
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> Pengurus</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="members"><input type="hidden" name="id" value="<?= e($row['id']) ?>">
            <div class="row g-3"><div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" name="name" value="<?= e($row['name']) ?>" required></div><div class="col-md-6"><label class="form-label">Jabatan</label><input class="form-control" name="position" value="<?= e($row['position']) ?>" required></div><div class="col-md-4"><label class="form-label">Urutan</label><input class="form-control" type="number" name="sort_order" value="<?= e($row['sort_order']) ?>"></div><div class="col-md-8"><label class="form-label">Foto</label><input class="form-control" type="file" name="photo" accept="image/*"></div><div class="col-12"><label class="form-label">Bio</label><textarea class="form-control" name="bio"><?= e($row['bio']) ?></textarea></div></div>
            <button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $rows = db()->query('SELECT * FROM organization_members ORDER BY sort_order')->fetchAll();
        echo '<div class="d-flex justify-content-between mb-3"><h4>Kelola Pengurus</h4><a class="btn btn-pgri" href="' . e(url('admin/?module=members&action=form')) . '">Tambah Pengurus</a></div><div class="card card-official"><table class="table mb-0"><thead><tr><th>Nama</th><th>Jabatan</th><th>Aksi</th></tr></thead><tbody>';
        foreach ($rows as $row) { echo '<tr><td>' . e($row['name']) . '</td><td>' . e($row['position']) . '</td><td>' . admin_table_actions('members', (int)$row['id']) . '</td></tr>'; }
        echo '</tbody></table></div>';
    }
} elseif ($module === 'member_registrations') {
    ensure_member_registrations_table();
    $statusLabels = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
    $statusBadges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
    $rows = db()->query('SELECT * FROM member_registrations ORDER BY created_at DESC, id DESC')->fetchAll();
    ?>
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <h4>Registrasi Anggota</h4>
        <input class="form-control" style="max-width: 320px;" data-table-search="#memberRegistrationTable" placeholder="Cari pendaftar...">
    </div>
    <div class="card card-official"><div class="table-responsive"><table class="table mb-0 align-middle" id="memberRegistrationTable"><thead><tr><th>Pendaftar</th><th>Tempat Tugas</th><th>Kontak</th><th>Status</th><th>Catatan Admin</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <div class="member-profile-cell">
                        <?php if (!empty($row['photo'])): ?>
                            <img class="member-avatar" src="<?= e(media_url($row['photo'])) ?>" alt="Foto <?= e($row['full_name']) ?>">
                        <?php else: ?>
                            <span class="member-avatar member-avatar-placeholder"><i class="fa-solid fa-user"></i></span>
                        <?php endif; ?>
                        <div><strong><?= e($row['full_name']) ?></strong><br><small><?= e($row['identity_number'] ?: '-') ?></small><br><small><?= date('d M Y H:i', strtotime($row['created_at'])) ?></small></div>
                    </div>
                </td>
                <td><?= e($row['school_name']) ?><br><small><?= e($row['job_title'] ?: '-') ?><?= $row['district'] ? ' - ' . e($row['district']) : '' ?></small></td>
                <td><?= e($row['email']) ?><br><small><?= e($row['phone']) ?></small></td>
                <td><span class="badge text-bg-<?= e($statusBadges[$row['status']] ?? 'secondary') ?>"><?= e($statusLabels[$row['status']] ?? $row['status']) ?></span></td>
                <td>
                    <form method="post" action="<?= e(url('admin/actions.php')) ?>" class="registration-status-form">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="module" value="member_registrations">
                        <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                        <select class="form-select form-select-sm mb-2" name="status">
                            <?php foreach ($statusLabels as $value => $label): ?><option value="<?= e($value) ?>" <?= $row['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
                        </select>
                        <textarea class="form-control form-control-sm mb-2" name="notes" rows="2" placeholder="Catatan verifikasi"><?= e($row['notes']) ?></textarea>
                        <button class="btn btn-sm btn-pgri">Simpan</button>
                    </form>
                </td>
                <td><a data-confirm="Hapus registrasi ini?" class="btn btn-sm btn-outline-danger" href="<?= e(url('admin/actions.php?module=member_registrations&action=delete&id=' . (int)$row['id'] . '&csrf_token=' . csrf_token())) ?>"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody></table></div></div>
    <?php
} elseif ($module === 'users') {
    if ($action === 'form') {
        $row = ['id' => 0, 'name' => '', 'email' => '', 'role' => 'admin'];
        if ($id) { $stmt = db()->prepare('SELECT id,name,email,role FROM users WHERE id = ?'); $stmt->execute([$id]); $row = $stmt->fetch() ?: $row; }
        ?>
        <h4><?= $id ? 'Edit' : 'Tambah' ?> User Admin</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="users"><input type="hidden" name="id" value="<?= e($row['id']) ?>">
            <div class="row g-3"><div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" name="name" value="<?= e($row['name']) ?>" required></div><div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($row['email']) ?>" required></div><div class="col-md-6"><label class="form-label">Password <?= $id ? 'Baru' : '' ?></label><input class="form-control" type="password" name="password" <?= $id ? '' : 'required' ?>></div><div class="col-md-6"><label class="form-label">Role</label><select class="form-select" name="role"><option value="admin">Admin</option><option value="superadmin" <?= $row['role'] === 'superadmin' ? 'selected' : '' ?>>Superadmin</option></select></div></div>
            <button class="btn btn-pgri mt-4">Simpan</button>
        </form>
        <?php
    } else {
        $rows = db()->query('SELECT id,name,email,role FROM users ORDER BY id DESC')->fetchAll();
        echo '<div class="d-flex justify-content-between mb-3"><h4>Kelola User Admin</h4><a class="btn btn-pgri" href="' . e(url('admin/?module=users&action=form')) . '">Tambah User</a></div><div class="card card-official"><table class="table mb-0"><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead><tbody>';
        foreach ($rows as $row) { echo '<tr><td>' . e($row['name']) . '</td><td>' . e($row['email']) . '</td><td>' . e($row['role']) . '</td><td>' . admin_table_actions('users', (int)$row['id']) . '</td></tr>'; }
        echo '</tbody></table></div>';
    }
} elseif ($module === 'messages') {
    $rows = db()->query('SELECT * FROM contact_messages ORDER BY id DESC')->fetchAll();
    echo '<h4 class="mb-3">Keluhan</h4><div class="card card-official"><table class="table mb-0"><thead><tr><th>Pengirim</th><th>Subjek</th><th>Keluhan</th><th>Aksi</th></tr></thead><tbody>';
    foreach ($rows as $row) { echo '<tr><td><strong>' . e($row['name']) . '</strong><br><small>' . e($row['email']) . '</small></td><td>' . e($row['subject']) . '</td><td>' . e($row['message']) . '</td><td><a data-confirm="Hapus pesan ini?" class="btn btn-sm btn-outline-danger" href="' . e(url('admin/actions.php?module=messages&action=delete&id=' . (int)$row['id'] . '&csrf_token=' . csrf_token())) . '"><i class="fa-solid fa-trash"></i></a></td></tr>'; }
    echo '</tbody></table></div>';
} elseif ($module === 'documents') {
    ensure_documents_table();

    if ($action === 'form') {
        $row = ['id' => 0, 'title' => '', 'description' => '', 'category' => 'Umum', 'filename' => '', 'original_name' => '', 'file_path' => '', 'file_size' => 0, 'file_extension' => ''];
        if ($id) {
            $stmt = db()->prepare('SELECT * FROM documents WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch() ?: $row;
        }
        $categories = db()->query('SELECT DISTINCT category FROM documents ORDER BY category')->fetchAll();
        $defaultCategories = ['Umum', 'Surat Edaran', 'SK Pengurus', 'Laporan', 'Pedoman', 'Notulen'];
        ?>
        <h4><?= $id ? 'Edit' : 'Upload' ?> Dokumen</h4>
        <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="module" value="documents">
            <input type="hidden" name="id" value="<?= e((string)$row['id']) ?>">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input class="form-control" name="title" value="<?= e($row['title']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category">
                        <?php $existingCats = array_unique(array_merge($defaultCategories, array_map(function($c) { return $c['category']; }, $categories))); ?>
                        <?php foreach ($existingCats as $cat): ?>
                            <option value="<?= e($cat) ?>" <?= $row['category'] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                        <?php endforeach; ?>
                        <option value="" disabled>── Tulis manual ──</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3"><?= e($row['description']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">File <?= !$id ? '<span class="text-danger">*</span>' : '' ?></label>
                    <?php if ($id && $row['file_path']): ?>
                        <div class="d-flex align-items-center gap-3 mb-2 p-3 bg-light rounded">
                            <i class="fa-solid <?= get_file_icon($row['file_extension']) ?> fa-2x"></i>
                            <div>
                                <strong><?= e($row['original_name']) ?></strong><br>
                                <small class="text-muted"><?= format_file_size((int)$row['file_size']) ?></small>
                            </div>
                            <a class="btn btn-sm btn-outline-primary ms-auto" href="<?= e(media_url($row['file_path'])) ?>" target="_blank"><i class="fa-solid fa-eye"></i> Lihat</a>
                        </div>
                        <input class="form-control" type="file" name="file_document">
                        <small class="text-muted">Kosongkan jika tidak mengganti file. Maksimal 100MB. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP, RAR, dan lainnya.</small>
                    <?php else: ?>
                        <input class="form-control" type="file" name="file_document" required>
                        <small class="text-muted">Maksimal 100MB. Semua format file didukung: PDF, Word, Excel, PowerPoint, gambar, arsip, dan lainnya.</small>
                    <?php endif; ?>
                </div>
            </div>
            <button class="btn btn-pgri mt-4"><i class="fa-solid fa-upload me-2"></i><?= $id ? 'Simpan Perubahan' : 'Upload Dokumen' ?></button>
        </form>
        <?php
    } else {
        $keyword = trim($_GET['q'] ?? '');
        $catFilter = trim($_GET['category'] ?? '');
        $where = '1=1';
        $params = [];
        if ($keyword !== '') {
            $where .= ' AND (title LIKE ? OR original_name LIKE ? OR description LIKE ?)';
            $like = '%' . $keyword . '%';
            array_push($params, $like, $like, $like);
        }
        if ($catFilter !== '') {
            $where .= ' AND category = ?';
            $params[] = $catFilter;
        }
        $stmt = db()->prepare("SELECT * FROM documents WHERE $where ORDER BY created_at DESC");
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        $filterCategories = db()->query('SELECT DISTINCT category FROM documents ORDER BY category')->fetchAll();
        ?>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h4 class="mb-0">Dokumen</h4>
            <a class="btn btn-pgri" href="<?= e(url('admin/?module=documents&action=form')) ?>"><i class="fa-solid fa-upload me-1"></i>Upload Dokumen</a>
        </div>
        <form class="row g-2 mb-3" method="get">
            <input type="hidden" name="module" value="documents">
            <div class="col-md-5">
                <input class="form-control" name="q" value="<?= e($keyword) ?>" placeholder="Cari judul, nama file...">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($filterCategories as $fc): ?>
                        <option value="<?= e($fc['category']) ?>" <?= $catFilter === $fc['category'] ? 'selected' : '' ?>><?= e($fc['category']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-outline-secondary w-100" href="<?= e(url('admin/?module=documents')) ?>"><i class="fa-solid fa-rotate me-1"></i>Reset</a>
            </div>
        </form>
        <?php if ($rows): ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Dokumen</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Diunduh</th>
                            <th>Tanggal</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fa-solid <?= get_file_icon($row['file_extension']) ?> fa-xl"></i>
                                        <div>
                                            <strong><?= e($row['title'] ?: $row['original_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= e($row['original_name']) ?></small>
                                            <?php if ($row['description']): ?>
                                                <br><small class="text-muted"><?= e(mb_substr($row['description'], 0, 80)) ?><?= strlen($row['description']) > 80 ? '...' : '' ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= e($row['category']) ?></span></td>
                                <td><span class="text-nowrap"><?= format_file_size((int)$row['file_size']) ?></span></td>
                                <td><?= e((string)$row['downloads']) ?>x</td>
                                <td><small class="text-nowrap"><?= date('d M Y', strtotime($row['created_at'])) ?></small></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= e(media_url($row['file_path'])) ?>" target="_blank" title="Lihat/Download"><i class="fa-solid fa-download"></i></a>
                                        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/?module=documents&action=form&id=' . $row['id'])) ?>" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <a class="btn btn-sm btn-outline-danger" data-confirm="Hapus dokumen <?= e($row['original_name']) ?>?" href="<?= e(url('admin/actions.php?module=documents&action=delete&id=' . $row['id'] . '&csrf_token=' . csrf_token())) ?>" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted small">Total: <?= count($rows) ?> dokumen</p>
        <?php else: ?>
            <div class="card card-official p-5 text-center">
                <i class="fa-solid fa-folder-open fa-4x text-muted mb-3"></i>
                <h5>Belum ada dokumen</h5>
                <p class="text-muted mb-0">Upload dokumen seperti PDF, Word, Excel, atau file lainnya.</p>
                <a class="btn btn-pgri mt-3" href="<?= e(url('admin/?module=documents&action=form')) ?>"><i class="fa-solid fa-upload me-1"></i>Upload Dokumen Pertama</a>
            </div>
        <?php endif; ?>
    <?php
} elseif ($module === 'settings') {
    $settingDefaults = [
        'hero_banner' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80',
        'site_logo' => '',
        'whatsapp_number' => '6281234567890',
    ];
    foreach ($settingDefaults as $key => $value) {
        $stmt = db()->prepare('INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)');
        $stmt->execute([$key, $value]);
    }
    $rows = db()->query('SELECT * FROM settings ORDER BY setting_key')->fetchAll();
    ?>
    <h4>Pengaturan Website</h4>
    <form class="card card-official p-4" method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="module" value="settings">
        <?php foreach ($rows as $row): ?>
            <?php if ($row['setting_key'] === 'hero_banner'): ?>
                <div class="mb-3">
                    <label class="form-label">Banner Header</label>
                    <?php if ($row['setting_value']): ?><img class="img-fluid rounded mb-2" src="<?= e(media_url($row['setting_value'])) ?>" alt="Banner Header"><?php endif; ?>
                    <input type="hidden" name="settings[hero_banner]" value="<?= e($row['setting_value']) ?>">
                    <input class="form-control" type="file" name="hero_banner" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak mengganti banner.</small>
                </div>
            <?php elseif ($row['setting_key'] === 'site_logo'): ?>
                <div class="mb-3">
                    <label class="form-label">Logo Website</label>
                    <?php if ($row['setting_value']): ?><div class="mb-2"><span class="logo-mark"><img src="<?= e(media_url($row['setting_value'])) ?>" alt="Logo Website"></span></div><?php endif; ?>
                    <input type="hidden" name="settings[site_logo]" value="<?= e($row['setting_value']) ?>">
                    <input class="form-control" type="file" name="site_logo" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak mengganti logo.</small>
                </div>
            <?php elseif ($row['setting_key'] === 'whatsapp_number'): ?>
                <div class="mb-3">
                    <label class="form-label">Nomor WhatsApp Admin</label>
                    <input class="form-control" name="settings[whatsapp_number]" value="<?= e($row['setting_value']) ?>" placeholder="Contoh: 6281234567890">
                    <small class="text-muted">Gunakan format internasional tanpa tanda plus, misalnya 6281234567890.</small>
                </div>
            <?php else: ?>
                <div class="mb-3"><label class="form-label"><?= e($row['setting_key']) ?></label><textarea class="form-control" name="settings[<?= e($row['setting_key']) ?>]" rows="2"><?= e($row['setting_value']) ?></textarea></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="btn btn-pgri">Simpan Pengaturan</button>
    </form>
    <?php
}

require_once __DIR__ . '/../includes/admin_footer.php';
