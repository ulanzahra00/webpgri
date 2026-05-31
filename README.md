# Website Resmi PGRI Kotamobagu

Website organisasi resmi berbasis PHP native, MySQL, Bootstrap 5, dan JavaScript. Struktur dibuat clean, mudah dipahami, dan siap dipindahkan ke shared hosting.

## Instalasi

1. Buat database MySQL lalu jalankan salah satu cara berikut.

   Cara otomatis:
   ```bash
   php -S localhost:8000 -t .
   ```
   Buka `http://localhost:8000/install.php`, lalu hapus `install.php` setelah selesai.

   Cara manual SQL:
   ```sql
   SOURCE database/schema.sql;
   SOURCE database/seed.sql;
   ```
2. Sesuaikan koneksi di `config/database.php` atau gunakan environment variable `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
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

- Email: `admin@pgrikotamobagu.or.id`
- Password: `admin12345`
- Email: `zahra@gmail.com`
- Password: `tanyap4ZIL`

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

## Upload ke Hosting

Untuk hosting baru lewat phpMyAdmin, import file `database/hosting_import.sql` agar struktur tabel dan semua data lokal ikut masuk. File ini tidak memaksa nama database lokal, jadi aman dipakai pada database hosting yang namanya berbeda.

Setelah import, sesuaikan koneksi di `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nama_database_hosting');
define('DB_USER', 'username_database_hosting');
define('DB_PASS', 'password_database_hosting');
```

Upload semua file dan folder proyek ke `public_html` atau folder domain/subdomain tujuan. Pastikan folder `uploads/` bisa ditulis oleh aplikasi agar upload gambar, video, dan dokumen dari admin tetap berjalan.
