<?php
// School modals untuk import dan delete
?>
<div class="modal fade" id="importSchoolsModal" tabindex="-1" aria-labelledby="importSchoolsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <form method="post" action="<?= e(url('admin/actions.php')) ?>" enctype="multipart/form-data">
            <div class="modal-header">
                <div><h5 class="modal-title">Import Data Sekolah</h5><small class="text-muted">Gunakan template agar kolom sesuai.</small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="module" value="schools">
                <input type="hidden" name="action" value="import">
                <div class="mb-3">
                    <label class="form-label">File Excel/CSV</label>
                    <input class="form-control" type="file" name="school_file" accept=".xlsx,.csv" required>
                    <small class="text-muted">Format: XLSX atau CSV. Kolom wajib: Nama Sekolah, Jenjang, Kecamatan, Alamat.</small>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/actions.php?module=schools&action=template')) ?>"><i class="fa-solid fa-file-arrow-down me-1"></i>Download Template CSV</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-pgri"><i class="fa-solid fa-file-import me-2"></i>Import Data</button>
            </div>
        </form>
    </div></div>
</div>
<div class="modal fade" id="deleteSchoolsModal" tabindex="-1" aria-labelledby="deleteSchoolsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable"><div class="modal-content">
        <form method="post" action="<?= e(url('admin/actions.php')) ?>">
            <div class="modal-header">
                <div><h5 class="modal-title">Pilih Data Sekolah yang Dihapus</h5><small class="text-muted">Centang hanya data yang ingin dihapus.</small></div>
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
                                <span><strong><?= e($row['name']) ?></strong><small class="d-block text-muted"><?= e($row['level']) ?> - <?= e($row['district']) ?></small><small class="d-block text-muted"><?= e($row['address']) ?></small></span>
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
    </div></div>
</div>