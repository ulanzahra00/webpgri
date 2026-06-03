# Website Resmi PGRI Kotamobagu

Website organisasi resmi berbasis PHP native, MySQL, Bootstrap 5, dan JavaScript. Struktur dibuat clean, mudah dipahami, dan siap dipindahkan ke shared hosting.

## Instalasi

1. Buat database MySQL di hosting, lalu sesuaikan koneksi di `config/database.php` atau gunakan environment variable `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

   Cara otomatis:
   ```bash
   php -S localhost:8000 -t .
   ```
   Buka `http://localhost:8000/install.php` atau `https://pgrikotamobagu.my.id/install.php`, lalu hapus `install.php` setelah selesai.

   Cara manual SQL:
   ```sql
   USE pgrikota_pgri;
   SOURCE database/pgrikota_pgri.sql;
   SOURCE database/seed.sql;
   ```
2. Upload semua isi folder proyek ke root hosting/public_html agar public website terbuka langsung di `https://pgrikotamobagu.my.id/`, bukan di subfolder `/public/`.
3. Jalankan server lokal:
   ```bash
   php -S localhost:8000 -t .
   ```
   Di Windows PowerShell bisa juga memakai:
   ```powershell
   .\run-local.ps1
   ```
4. Buka halaman:
   - Public: `http://localhost:8000/`
   - Admin: `http://localhost:8000/admin/login.php`

   Jika memakai XAMPP dan folder proyek berada di `htdocs/pgrikotamobagu`, buka:
   - Public: `http://localhost/pgrikotamobagu/`
   - Admin: `http://localhost/pgrikotamobagu/admin/login.php`

## Login Admin Dummy

- Email: `admin@pgrikotamobagu.my.id`
- Password: `admin12345`

## Struktur

- `index.php` halaman utama publik
- `admin/` dashboard dan CRUD
- `api/` endpoint form kontak, pencarian, dan export
- `api/member_registration.php` endpoint registrasi anggota baru
- `config/` konfigurasi database
- `includes/` helper dan layout
- `uploads/` penyimpanan gambar
- `database/` schema dan seed data

## Update Modul Laporan Keuangan

Jika database sudah pernah di-install sebelum modul keuangan ditambahkan, jalankan migrasi ini lewat MySQL CLI:

```sql
SOURCE database/migration_financial_reports.sql;
```

Untuk phpMyAdmin, buka file `database/migration_financial_reports.sql`, salin isinya, lalu tempel dan jalankan di tab SQL.
