<footer class="footer pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <h5 class="text-white">PGRI Kotamobagu</h5>
                <p>Wadah perjuangan, pengembangan profesi, dan pengabdian guru untuk pendidikan Kotamobagu yang unggul.</p>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white">Navigasi</h6>
                <a class="d-block text-light-emphasis" href="<?= e(url('?page=profil')) ?>">Profil</a>
                <a class="d-block text-light-emphasis" href="<?= e(url('?page=berita')) ?>">Berita</a>
                <a class="d-block text-light-emphasis" href="<?= e(url('?page=sekolah')) ?>">Data Sekolah</a>
                <a class="d-block text-light-emphasis" href="<?= e(url('?page=anggota')) ?>">Registrasi Anggota</a>
                <a class="d-block text-light-emphasis" href="<?= e(url('?page=keuangan')) ?>">Laporan Keuangan</a>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white">Sekretariat</h6>
                <p class="mb-1"><?= e(setting('address')) ?></p>
                <p class="mb-0"><?= e(setting('email')) ?> | <?= e(setting('phone')) ?></p>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="mb-0 small">&copy; <?= date('Y') ?> PGRI Kotamobagu. Semua hak dilindungi.</p>
    </div>
</footer>

<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark">
            <button type="button" class="btn-close btn-close-white ms-auto m-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
            <img id="lightboxImage" class="img-fluid rounded-bottom" alt="Preview galeri">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('assets/js/app.js')) ?>"></script>
</body>
</html>
