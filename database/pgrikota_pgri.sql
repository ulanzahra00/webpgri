-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 14 Jun 2026 pada 12.56
-- Versi server: 11.4.10-MariaDB
-- Versi PHP: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pgrikota_pgri`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Organisasi', 'organisasi'),
(2, 'Pendidikan', 'pendidikan'),
(3, 'Kegiatan', 'kegiatan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `subject` varchar(180) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(3, 'Katiyar', 'aman@rocketdigitaltech.com', 'Let’s Boost Your Website Traffic', 'Hello http://pgrikotamobagu.my.id,\r\n \r\nI hope you’re doing well. I came across your business online and thought you might be interested in improving your visibility and traffic on search engines.\r\n \r\nWe specialize in helping businesses strengthen their online presence through effective SEO strategies.\r\n \r\nOnce you share your target keywords and target market, I’ll send a full proposal.\r\n \r\nWarm regards,\r\nAman', 0, '2026-06-02 19:21:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) NOT NULL DEFAULT 0,
  `file_type` varchar(100) NOT NULL DEFAULT '',
  `file_extension` varchar(20) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Umum',
  `downloads` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `documents`
--

INSERT INTO `documents` (`id`, `title`, `filename`, `original_name`, `file_path`, `file_size`, `file_type`, `file_extension`, `description`, `category`, `downloads`, `created_at`, `updated_at`) VALUES
(2, 'PERATURAN MENTERI PENDIDIKAN DASAR DAN MENENGAH REPUBLIK INDONESIA NOMOR 4 TAHUN 2026 TENTANG PERLINDUNGAN BAGI PENDIDIK DAN TENAGA KEPENDIDIKAN', 'dokumen-6a2e3de7be5e03.79116646.pdf', 'Permendikdasmen 4 Tahun 2026 Perlindungan Bagi Pendidik dan Tenaga Kependidikan.pdf', '/uploads/dokumen/dokumen-6a2e3de7be5e03.79116646.pdf', 191086, 'application/pdf', 'pdf', 'PERATURAN MENTERI PENDIDIKAN DASAR DAN\r\nMENENGAH TENTANG PERLINDUNGAN BAGI PENDIDIK\r\nDAN TENAGA KEPENDIDIKAN.', 'Surat Edaran', 0, '2026-06-14 05:36:39', NULL),
(3, 'Undangan Konkernas II PGRI Masa Bakti XXIII Tahun 2026', 'dokumen-6a2e3e1dcd8e55.91096600.pdf', 'Revisi No 55 Undangan Konkernas II PGRI Masa Bakti XXIII Tahun 2026.pdf', '/uploads/dokumen/dokumen-6a2e3e1dcd8e55.91096600.pdf', 1319640, 'application/pdf', 'pdf', 'Undangan Konkernas II PGRI Masa Bakti XXIII Tahun 2026', 'Surat Edaran', 0, '2026-06-14 05:37:33', NULL),
(4, 'KEPUTUSAN PENGURUS PROVINSI SULAWESI UTARA PERSATUAN GURU REPUBLIKINDONESIANOMOR: 15/Kep/SLU/XXIII/2026', 'dokumen-6a2e3e424323d2.27112101.pdf', '15 FIX SK 2025 PERSATUAN GURU REPUBLIK INDONESIA Kotamobagu.pdf', '/uploads/dokumen/dokumen-6a2e3e424323d2.27112101.pdf', 161483, 'application/pdf', 'pdf', 'TENTANG SUSUNAN DAN PERSONALIA DEWAN PEMBINA DAN PENGURUS PERSATUAN GURU REPUBLIK INDONESIAKOTA KOTAMOBAGU MASA BAKTI XXIII TAHUN 2025-2030', 'Umum', 0, '2026-06-14 05:38:10', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `period_month` tinyint(4) NOT NULL,
  `period_year` year(4) NOT NULL,
  `deposit_date` date DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `category` varchar(120) NOT NULL,
  `income` decimal(15,2) DEFAULT 0.00,
  `expense` decimal(15,2) DEFAULT 0.00,
  `balance` decimal(15,2) GENERATED ALWAYS AS (`income` - `expense`) STORED,
  `description` text DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `financial_reports`
--

INSERT INTO `financial_reports` (`id`, `title`, `period_month`, `period_year`, `deposit_date`, `report_date`, `category`, `income`, `expense`, `description`, `document`, `status`, `created_at`, `updated_at`) VALUES
(7, 'iuran PGRI sdn 1 molinow bulan januari-desember 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 1044000.00, 0.00, 'lunas', NULL, 'published', '2026-06-12 01:29:58', '2026-06-12 03:58:25'),
(8, 'iuran PGRI SMP N 4 Kotamobagu tahun 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 2956000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 01:31:08', '2026-06-12 03:57:32'),
(9, 'Iuran PGRI SDN 3 Kotobangon tahun 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 648000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 01:31:38', '2026-06-12 03:57:19'),
(10, 'Iuran PGRI SDN 1 Pontodon tahun 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 432000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 01:32:04', '2026-06-12 03:57:06'),
(11, 'Iuran PGRI SDN 2 Kotamobagu tahun 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 1280000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 01:32:29', '2026-06-12 03:56:42'),
(12, 'Iuran PGRI SDN 2 Pobundayan 2026', 0, '2026', '2026-06-12', NULL, 'iuran', 576000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 01:32:51', '2026-06-12 03:56:20'),
(13, 'iuran PGRI SDN 2 SININDIAN', 0, '2026', '2026-06-12', NULL, 'IURAN', 648000.00, 0.00, 'Lunas', NULL, 'published', '2026-06-12 04:11:28', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `galleries`
--

CREATE TABLE `galleries` (
  `id` int(11) NOT NULL,
  `title` varchar(160) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `image`, `description`, `event_date`, `created_at`) VALUES
(1, 'Rapat Koordinasi Pengurus', 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1000&q=80', 'Koordinasi program kerja organisasi.', '2026-05-26', '2026-05-26 10:25:02'),
(2, 'Pembelajaran Guru Kreatif', '/uploads/galeri/galeri-6a15c07e2d3bf8.82435739.png', 'Pembelajaran Guru Kreatif SDN 2 Pobundayan', '2026-05-26', '2026-05-26 10:25:02'),
(3, 'Kegiatan Hari Pendidikan Nasional Tahun 2026', '/uploads/galeri/galeri-6a15c049ef6415.65875788.jpg', 'Hari Pendidikan Nasional Tahun 2026', '2026-05-26', '2026-05-26 10:25:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `member_registrations`
--

CREATE TABLE `member_registrations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(160) NOT NULL,
  `identity_number` varchar(80) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(160) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `school_name` varchar(180) NOT NULL,
  `job_title` varchar(120) DEFAULT NULL,
  `district` varchar(120) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `organization_members`
--

CREATE TABLE `organization_members` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `position` varchar(120) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `organization_members`
--

INSERT INTO `organization_members` (`id`, `name`, `position`, `photo`, `bio`, `sort_order`) VALUES
(1, 'Erni Mokodompit S.Pd, M.Pd.', 'Ketua PGRI Kotamobagu', '/uploads/pengurus/pengurus-6a15b184db3ea9.20231022.jpg', 'Memimpin penguatan organisasi dan advokasi profesi guru.', 1),
(2, 'Amir Mahmud, S.Pd', 'Sekretaris', '/uploads/pengurus/pengurus-6a15ca76b95e12.21010710.png', 'Mengelola administrasi dan koordinasi program kerja.', 2),
(3, 'Sakina Mokodompit, S.Pd', 'Bendahara', '/uploads/pengurus/pengurus-6a15cb08f16de3.86203211.png', 'Mengawal tata kelola keuangan organisasi.', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `published_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `image`, `video`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'PGRI Kotamobagu Perkuat Kolaborasi Guru', 'pgri-kotamobagu-perkuat-kolaborasi-guru', 'PGRI Kotamobagu mendorong kolaborasi lintas sekolah untuk peningkatan mutu pendidikan.', 'PGRI Kotamobagu terus memperkuat sinergi antarpendidik melalui forum diskusi, pelatihan, dan pendampingan profesional. Program ini menjadi bagian dari komitmen organisasi untuk menghadirkan layanan pendidikan yang adaptif, inklusif, dan bermutu.', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80', NULL, 'published', '2026-05-26 18:25:02', '2026-05-26 10:25:02', NULL),
(2, 2, 'Pelatihan Literasi Digital untuk Guru', 'pelatihan-literasi-digital-untuk-guru', 'Guru didorong menguasai teknologi pembelajaran modern.', 'Kegiatan literasi digital diikuti oleh perwakilan guru dari berbagai kecamatan. Materi berfokus pada penggunaan media pembelajaran, keamanan data, dan penyusunan konten kelas yang menarik.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80', NULL, 'published', '2026-05-26 18:25:02', '2026-05-26 10:25:02', NULL),
(3, 3, 'Bakti Sosial Pendidikan PGRI', 'bakti-sosial-pendidikan-pgri', 'Kegiatan sosial menjadi wujud kepedulian PGRI kepada masyarakat.', 'PGRI Kotamobagu menggelar bakti sosial pendidikan dengan melibatkan pengurus, guru, dan komunitas sekolah. Kegiatan ini diarahkan untuk memperkuat kepedulian dan solidaritas insan pendidikan.', 'https://images.unsplash.com/photo-1497486751825-1233686d5d80?auto=format&fit=crop&w=1200&q=80', NULL, 'published', '2026-05-26 18:25:02', '2026-05-26 10:25:02', NULL),
(4, 2, 'Upacara Hari Pendidikan Nasional (Hardiknas) Tahun 2026', 'upacara-hari-pendidikan-nasional-hardiknas-tahun-2026-4', 'PGRI Kota Kotamobagu melaksanakan Upacara Hari Pendidikan Nasional Tahun 2026 sebagai bentuk komitmen bersama dalam mewujudkan pendidikan bermutu untuk semua melalui kolaborasi seluruh elemen bangsa.', 'Dalam rangka memperingati Hari Pendidikan Nasional (Hardiknas) yang jatuh pada tanggal 2 Mei 2026, PGRI Kota Kotamobagu turut melaksanakan upacara bendera sebagai wujud penghormatan terhadap jasa para tokoh pendidikan serta komitmen bersama dalam memajukan dunia pendidikan.\r\n\r\nUpacara ini mengusung tema nasional “Menguatkan Partisipasi Semesta Mewujudkan Pendidikan Bermutu untuk Semua”, yang menegaskan pentingnya kolaborasi seluruh elemen masyarakat dalam meningkatkan kualitas pendidikan di Indonesia.\r\n\r\nKegiatan ini diikuti oleh pengurus PGRI, para guru, tenaga kependidikan, serta siswa dengan penuh khidmat. Momentum Hardiknas menjadi pengingat bahwa pendidikan merupakan fondasi utama dalam membangun generasi unggul, berkarakter, dan siap menghadapi tantangan masa depan.\r\n\r\nMelalui peringatan ini, PGRI Kota Kotamobagu mengajak seluruh insan pendidikan untuk terus berinovasi, meningkatkan profesionalisme, serta memperkuat sinergi demi terwujudnya pendidikan yang inklusif, merata, dan berkualitas bagi semua.', '/uploads/berita/berita-6a15bbafb238b7.27953162.jpg', '/uploads/berita/berita-6a15baaf3dc392.76679245.mp4', 'published', '2026-05-26 23:22:23', '2026-05-26 15:22:23', '2026-05-26 15:28:53'),
(5, 2, 'Guru Bahasa Inggris SD Terapkan Pendekatan Pembelajaran Mendalam', 'guru-bahasa-inggris-sd-terapkan-pendekatan-pembelajaran-mendalam-5', 'Guru Bahasa Inggris Refina Dilasani, M.Pd., Gr. adalah guru Sekolah Dasar Negeri 2 Pobundayan yang menerapkan pendekatan pembelajaran mendalam yang menekankan pemahaman konsep dan keterlibatan aktif murid. Melalui metode interaktif, murid tidak hanya menghafal, tetapi juga mampu menggunakan Bahasa Inggris dalam konteks nyata. Pendekatan ini membuat pembelajaran lebih bermakna, menyenangkan, serta meningkatkan kepercayaan diri murid.', 'Guru Bahasa Inggris di Sekolah Dasar Negeri 2 Pobundayan menunjukkan inovasi dalam proses pembelajaran dengan menerapkan pendekatan pembelajaran mendalam (deep learning) yang berfokus pada pemahaman konsep, keterlibatan aktif murid, serta penguatan karakter. Dalam kegiatan belajar mengajar, murid tidak hanya diajak menghafal kosakata, tetapi juga memahami makna, penggunaan dalam konteks sehari-hari, serta mengembangkan kemampuan berpikir kritis dan komunikasi.\r\n\r\nMelalui metode interaktif seperti diskusi kelompok, permainan edukatif, dan praktik langsung, suasana kelas menjadi lebih hidup dan menyenangkan. murid terlihat lebih percaya diri dalam menggunakan Bahasa Inggris, baik secara lisan maupun tulisan. Pendekatan ini juga mendorong murid untuk berani bertanya, mengemukakan pendapat, serta bekerja sama dengan teman.\r\n\r\nUpaya ini menjadi contoh nyata bahwa pembelajaran Bahasa Inggris di Sekolah Dasar dapat dilakukan secara bermakna dan relevan dengan kehidupan murid. Diharapkan praktik baik ini dapat menginspirasi para guru lainnya untuk terus berinovasi dalam menciptakan pembelajaran yang berkualitas dan berpusat pada murid.', '/uploads/berita/berita-6a15bd7acd0542.63961944.png', '/uploads/berita/berita-6a1c1f84a7e935.16854234.mp4', 'published', '2026-05-26 23:34:18', '2026-05-26 15:34:18', '2026-06-01 01:49:25'),
(6, 2, 'Link Pendaftaran Murid Baru', 'link-pendaftaran-murid-baru-6', 'Ayo daftarkan segera anak-anak kita !!\r\nhttps://spmb.kotamobagu.go.id\r\nAlur Pendaftaran:\r\n1. Masuk Portal\r\n2. Pilih Jenis Sekolah\r\n3.Pilih Sekolah\r\n4. Isi Formulir\r\n5. Unggah Dokumen\r\n6. Kirim Pendaftaran\r\n7. Verifikasi Sekolah\r\n8. Pengumuman\r\n9. Daftar Ulang', 'Alur Pendaftaran\r\nIkuti tahapan ini dari atas ke bawah.\r\n1. Masuk Portal\r\nBuka portal SPMB dan masuk menggunakan NISN serta tanggal lahir anak. Khusus TK dan SD, dapat memakai NIK jika belum memiliki NISN.\r\n2. Pilih Jenis Sekolah\r\nPilih mendaftar ke sekolah Negeri atau Swasta. Jika Negeri, pilih salah satu jalur: Domisili, Afirmasi, Prestasi, atau Mutasi.\r\n3.Pilih Sekolah\r\nPilih sekolah tujuan yang masih memiliki sisa kuota pada jalur yang Anda pilih.\r\n4. Isi Formulir\r\nLengkapi data diri dan keluarga. Sebagian data terisi otomatis dari data kependudukan dan sekolah asal.\r\n5. Unggah Dokumen\r\nUnggah dokumen pendukung sesuai jalur. Pastikan foto atau hasil scan terbaca dengan jelas.\r\n6. Kirim Pendaftaran\r\nKlik tombol Kirim. Sistem memberi nomor pendaftaran resmi, contoh: SPMB-SD-2026-001234.\r\n7. Verifikasi Sekolah\r\nOperator sekolah memeriksa dokumen selama kurang lebih 2–3 minggu. Status dapat dipantau melalui portal.\r\n8. Pengumuman\r\nHasil seleksi dapat dilihat di portal SPMB dengan cara login menggunakan NISN/NIK dan tanggal lahir anak. Pastikan untuk memeriksa secara berkala.\r\n9. Daftar Ulang\r\nJika diterima, datang ke sekolah pada jadwal daftar ulang dengan dokumen asli untuk konfirmasi penerimaan.\r\nadwal Penting\r\nTanggal pendaftaran, verifikasi, dan daftar ulang.\r\n#########################################################################################\r\n#########################################################################################\r\nPendaftaran SD\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 9 Juni 2026\r\nBatas Verifikasi SD\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang SD\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026\r\nPendaftaran SMP\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 9 Juni 2026\r\nBatas Verifikasi SMP\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang SMP\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026\r\nPendaftaran TK\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 8 Juni 2026\r\nBatas Verifikasi TK\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang TK\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026', '/uploads/berita/berita-6a15c1a1102071.67023809.jpg', NULL, 'published', '2026-05-26 23:52:01', '2026-05-26 15:52:01', '2026-05-26 16:00:03'),
(7, 3, 'Peringatan Hari Lahir Pancasila dan Hari Kebangkitan Nasional ke-118 Tahun 2026 di Lingkungan Pemerintah Kota Kotamobagu', 'peringatan-hari-lahir-pancasila-dan-hari-kebangkitan-nasional-ke-118-tahun-2026-di-lingkungan-pemerintah-kota-kotamobagu-7', 'KOTAMOBAGU – Pemerintah Kota Kotamobagu melaksanakan rangkaian kegiatan Peringatan Hari Lahir Pancasila Tahun 2026 dan Hari Kebangkitan Nasional (Harkitnas) ke-118 Tahun 2026 dengan penuh khidmat dan semangat kebangsaan.', 'KOTAMOBAGU – Pemerintah Kota Kotamobagu melaksanakan rangkaian kegiatan Peringatan Hari Lahir Pancasila Tahun 2026 dan Hari Kebangkitan Nasional (Harkitnas) ke-118 Tahun 2026 dengan penuh khidmat dan semangat kebangsaan. Kegiatan yang diikuti oleh jajaran Aparatur Sipil Negara (ASN),serta berbagai unsur masyarakat tersebut berlangsung dengan lancar, aman, dan tertib.\r\n\r\nPeringatan Hari Kebangkitan Nasional ke-118 yang jatuh pada 20 Mei 2026 menjadi momentum untuk mengingat kembali semangat persatuan dan perjuangan bangsa dalam membangun Indonesia yang maju. Tahun ini, peringatan Harkitnas mengusung tema “Jaga Tunas Bangsa Demi Kedaulatan Negara”.\r\n\r\nSementara itu, Peringatan Hari Lahir Pancasila yang diperingati setiap tanggal 1 Juni menjadi pengingat bagi seluruh masyarakat untuk terus mengamalkan nilai-nilai Pancasila sebagai dasar negara dan pedoman dalam kehidupan berbangsa dan bernegara.\r\n\r\nDalam pelaksanaan upacara dan rangkaian kegiatan peringatan tersebut, seluruh peserta mengikuti kegiatan dengan penuh disiplin dan rasa tanggung jawab. Suasana khidmat dan semangat nasionalisme terlihat selama kegiatan berlangsung, mencerminkan komitmen bersama dalam menjaga persatuan dan kesatuan bangsa.\r\n\r\nPemerintah Kota Kotamobagu berharap melalui peringatan Hari Lahir Pancasila dan Hari Kebangkitan Nasional tahun 2026 ini, semangat gotong royong, persatuan, serta kecintaan terhadap tanah air dapat terus ditingkatkan dalam mendukung pembangunan daerah maupun nasional.\r\n\r\nKegiatan yang berlangsung di lingkungan Pemerintah Kota Kotamobagu tersebut berakhir dengan aman, tertib, dan sukses, serta menjadi momentum untuk memperkuat komitmen seluruh elemen masyarakat dalam menjaga nilai-nilai kebangsaan demi kemajuan Indonesia.', '/uploads/berita/berita-6a1d2a3fb604c3.70537925.jpg', NULL, 'published', '2026-06-01 06:43:50', '2026-05-31 23:43:50', '2026-06-01 06:44:16'),
(8, 1, 'PGRI Kota Kotamobagu Tingkatkan Transparansi Pengelolaan Keuangan', 'pgri-kota-kotamobagu-tingkatkan-transparansi-pengelolaan-keuangan-8', 'PGRI Kota Kotamobagu terus berupaya meningkatkan transparansi pengelolaan keuangan melalui pencatatan yang tertib, pelaporan yang terbuka, serta pertanggungjawaban yang jelas kepada seluruh anggota organisasi.\r\nhttps://pgrikotamobagu.my.id/?page=keuangan', 'Dalam upaya mewujudkan tata kelola organisasi yang baik, PGRI Kota Kotamobagu terus meningkatkan transparansi dan akuntabilitas dalam pengelolaan keuangan organisasi. Setiap pemasukan dan pengeluaran dana dicatat, dikelola, serta dilaporkan secara terbuka sesuai dengan ketentuan yang berlaku.\r\nKetua PGRI Kota Kotamobagu menyampaikan bahwa pengelolaan keuangan yang transparan merupakan bentuk tanggung jawab organisasi kepada seluruh anggota. Oleh karena itu, laporan keuangan secara berkala disampaikan kepada pengurus dan anggota sebagai bentuk keterbukaan informasi.\r\nMelalui sistem pengelolaan yang lebih tertib dan terukur, PGRI Kota Kotamagu berkomitmen menjaga kepercayaan anggota serta memastikan setiap program dan kegiatan organisasi dapat berjalan secara efektif, efisien, dan dapat dipertanggungjawabkan.\r\nLangkah ini diharapkan semakin memperkuat kepercayaan anggota terhadap organisasi serta mendukung terwujudnya PGRI yang profesional, modern, dan berintegritas.\r\nlink laporan keuangan PGRI Kotamobagu\r\nhttps://pgrikotamobagu.my.id/keuangan', '/uploads/berita/berita-6a2bf2da57d715.75050267.png', NULL, 'published', '2026-06-12 11:28:14', '2026-06-12 04:28:14', '2026-06-14 05:51:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(180) NOT NULL,
  `level` varchar(50) NOT NULL,
  `district` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `headmaster` varchar(120) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `schools`
--

INSERT INTO `schools` (`id`, `name`, `level`, `district`, `address`, `headmaster`, `phone`, `created_at`) VALUES
(325, 'MIS ALKHAIRAAT MOGOLAING', 'SD/MI', 'Kotamobagu Barat', 'Jln. Fajar Bulawan No. 01', '', '', '2026-06-13 09:22:38'),
(326, 'MIS BAITUL MAKMUR KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'JL. JEND. AHMAD YANI NO. 10 KELURAHAN KOTAMOBAGU KECAMATAN KOTAMOBAGU BARAT KOT', '', '', '2026-06-13 09:22:38'),
(327, 'MIS BUDI MULIA GOGAGOMAN', 'SD/MI', 'Kotamobagu Barat', 'JL. AL-HUDA', '', '', '2026-06-13 09:22:38'),
(328, 'MIS HIDAYATULLAH MONGKONAI', 'SD/MI', 'Kotamobagu Barat', 'JLN. AKD DEPAN TERMINAL BONAWANG MONGKONAI', '', '', '2026-06-13 09:22:38'),
(329, 'MTSN 1 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jalan Kapten Piere Tendean Nomor 60. Kecamatan Kotamobagu Barat Kota Kotamobagu', '', '', '2026-06-13 09:22:38'),
(330, 'SD ALAM INSAN MULIA KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jln. Husin Raupu Kelurahan Molinow', '', '', '2026-06-13 09:22:38'),
(331, 'SD COKROAMINOTO MOLINOW', 'SD/MI', 'Kotamobagu Barat', 'Jln Adampe Dolot No 226', '', '', '2026-06-13 09:22:38'),
(332, 'SD ISLAM GREEN MONTESSORI', 'SD/MI', 'Kotamobagu Barat', 'Jl. Zakaria Imban No. 26', '', '', '2026-06-13 09:22:38'),
(333, 'SD KATOLIK CHRISTI REGIS', 'SD/MI', 'Kotamobagu Barat', 'Jl. Jenderal Ahmad Yani No. 800', '', '', '2026-06-13 09:22:38'),
(334, 'SD KRISTEN II KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl. Walanda Maramis', '', '', '2026-06-13 09:22:38'),
(335, 'SD KRISTEN X KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'JL. WALANDA MARAMIS', '', '', '2026-06-13 09:22:38'),
(336, 'SD NEGERI 1 GOGAGOMAN', 'SD/MI', 'Kotamobagu Barat', 'Jl Suprato', '', '', '2026-06-13 09:22:38'),
(337, 'SD NEGERI 1 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl. Kesatria No.1', '', '', '2026-06-13 09:22:38'),
(338, 'SD NEGERI 1 MOGOLAING', 'SD/MI', 'Kotamobagu Barat', 'Jl. CEMPAKA', '', '', '2026-06-13 09:22:38'),
(339, 'SD NEGERI 1 MOLINOW', 'SD/MI', 'Kotamobagu Barat', 'Jl. Veteran', '', '', '2026-06-13 09:22:38'),
(340, 'SD NEGERI 1 MONGKONAI', 'SD/MI', 'Kotamobagu Barat', 'Jl. Brawijaya', '', '', '2026-06-13 09:22:38'),
(341, 'SD NEGERI 2 GOGAGOMAN', 'SD/MI', 'Kotamobagu Barat', 'Jl. Piere Tendean', '', '', '2026-06-13 09:22:38'),
(342, 'SD NEGERI 2 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jalan Kasatria No 11', '', '', '2026-06-13 09:22:38'),
(343, 'SD NEGERI 2 MOGOLAING', 'SD/MI', 'Kotamobagu Barat', 'Jl. Pribumi', '', '', '2026-06-13 09:22:38'),
(344, 'SD NEGERI 2 MOLINOW', 'SD/MI', 'Kotamobagu Barat', 'Jl. Gatot Subroto', '', '', '2026-06-13 09:22:38'),
(345, 'SD NEGERI 2 MONGKONAI', 'SD/MI', 'Kotamobagu Barat', 'Jl. AKD Linggkungan II', '', '', '2026-06-13 09:22:38'),
(346, 'SD NEGERI 3 GOGAGOMAN', 'SD/MI', 'Kotamobagu Barat', 'Jl Inpres Nomor 3', '', '', '2026-06-13 09:22:38'),
(347, 'SD NEGERI 3 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl. Mawar', '', '', '2026-06-13 09:22:38'),
(348, 'SD NEGERI 3 MOGOLAING', 'SD/MI', 'Kotamobagu Barat', 'Jl. Siswa', '', '', '2026-06-13 09:22:38'),
(349, 'SD NEGERI 3 MONGKONAI', 'SD/MI', 'Kotamobagu Barat', 'Jl. Gatot Subroto', '', '', '2026-06-13 09:22:38'),
(350, 'SD NEGERI 4 MOGOLAING', 'SD/MI', 'Kotamobagu Barat', 'Jl. Adampe Dolot', '', '', '2026-06-13 09:22:38'),
(351, 'SMP Cokroaminoto Kotamobagu', 'SD/MI', 'Kotamobagu Barat', 'Jl Adampe Dolot No 69', '', '', '2026-06-13 09:22:38'),
(352, 'SMP KRISTEN KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl S Parman Kotamobagu', '', '', '2026-06-13 09:22:38'),
(353, 'SMP Maarif Sainstren Kotamobagu', 'SD/MI', 'Kotamobagu Barat', 'Jl. Kampus No.9', '', '', '2026-06-13 09:22:38'),
(354, 'SMP Muhammadiyah Kotamobagu', 'SD/MI', 'Kotamobagu Barat', 'Jalan Soeprapto No. 700', '', '', '2026-06-13 09:22:38'),
(355, 'SMP NEGERI 1 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl Arief Rahman Hakim', '', '', '2026-06-13 09:22:38'),
(356, 'SMP NEGERI 3 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jalan Arief Rahman Hakim Nomor 18', '', '', '2026-06-13 09:22:38'),
(357, 'SMP NEGERI 4 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Barat', 'Jl. Ahmad Yani', '', '', '2026-06-13 09:22:38'),
(358, 'MI Tahfidzul Qur\'an Motoboi Kecil', 'SD/MI', 'Kotamobagu Selatan', 'Motoboi Kecil', '', '', '2026-06-13 09:22:54'),
(359, 'MTSN 2 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Selatan', 'JLN. HI. ZAKARIA IMBAN NO. 97', '', '', '2026-06-13 09:22:54'),
(360, 'MTsS Al-Hikmah Mogutat', 'SD/MI', 'Kotamobagu Selatan', 'Jl. Raya Poyowa Besar I Kotamobagu Selatan', '', '', '2026-06-13 09:22:54'),
(361, 'SD COKROAMINOTO POYOWA BESAR', 'SD/MI', 'Kotamobagu Selatan', 'Desa Poyowa Besar 1 Kecamatan Kotamobagu Selatan', '', '', '2026-06-13 09:22:54'),
(362, 'SD ISLAM TERPADU AN-NAHL KOTAMOBAGU', 'SD/MI', 'Kotamobagu Selatan', 'Jln. Hi. Zakaria Imban', '', '', '2026-06-13 09:22:54'),
(363, 'SD NEGERI 1 KOPANDAKAN', 'SD/MI', 'Kotamobagu Selatan', 'Jl Labot Dugian', '', '', '2026-06-13 09:22:54'),
(364, 'SD NEGERI 1 MOTOBOI KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Jln 19 Desember 45', '', '', '2026-06-13 09:22:54'),
(365, 'SD NEGERI 1 POBUNDAYAN', 'SD/MI', 'Kotamobagu Selatan', 'Pobundayan', '', '', '2026-06-13 09:22:54'),
(366, 'SD NEGERI 1 POYOWA BESAR', 'SD/MI', 'Kotamobagu Selatan', 'Jln Raya Poyowa Besar II', '', '', '2026-06-13 09:22:54'),
(367, 'SD NEGERI 1 POYOWA KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Poyowa Kecil', '', '', '2026-06-13 09:22:54'),
(368, 'SD NEGERI 1 TABANG', 'SD/MI', 'Kotamobagu Selatan', 'Tabang', '', '', '2026-06-13 09:22:54'),
(369, 'SD NEGERI 2 MOTOBOI KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Jl. Melati No. 5a', '', '', '2026-06-13 09:22:54'),
(370, 'SD NEGERI 2 POBUNDAYAN', 'SD/MI', 'Kotamobagu Selatan', 'Pobundayan', '', '', '2026-06-13 09:22:54'),
(371, 'SD NEGERI 2 POYOWA BESAR', 'SD/MI', 'Kotamobagu Selatan', 'Poyowa Besar Satu, Jl Lapangan Bogani', '', '', '2026-06-13 09:22:54'),
(372, 'SD NEGERI 2 POYOWA KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Jln.H.Zakaria Imban, Desa Poyowa Kecil, Kecamatan Kotamobagu Selatan', '', '', '2026-06-13 09:22:54'),
(373, 'SD NEGERI 2 TABANG', 'SD/MI', 'Kotamobagu Selatan', 'TABANG', '', '', '2026-06-13 09:22:54'),
(374, 'SD NEGERI 3 KOPANDAKAN', 'SD/MI', 'Kotamobagu Selatan', 'Jl. Labot Dugian', '', '', '2026-06-13 09:22:54'),
(375, 'SD NEGERI 3 MOTOBOI KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Motoboi Kecil', '', '', '2026-06-13 09:22:54'),
(376, 'SD NEGERI 3 POBUNDAYAN', 'SD/MI', 'Kotamobagu Selatan', 'Pobundayan', '', '', '2026-06-13 09:22:54'),
(377, 'SD NEGERI 4 KOPANDAKAN', 'SD/MI', 'Kotamobagu Selatan', 'Jl Losik Lobud', '', '', '2026-06-13 09:22:54'),
(378, 'SD NEGERI 4 MOTOBOI KECIL', 'SD/MI', 'Kotamobagu Selatan', 'Motoboi Kecil', '', '', '2026-06-13 09:22:54'),
(379, 'SD NEGERI BUNGKO', 'SD/MI', 'Kotamobagu Selatan', 'Jalan J.A.Damopolii', '', '', '2026-06-13 09:22:54'),
(380, 'SD NEGERI MONGONDOW', 'SD/MI', 'Kotamobagu Selatan', 'Jl. Brawijaya No.52', '', '', '2026-06-13 09:22:54'),
(381, 'SMP IT AN-NAHL KOTAMOBAGU', 'SD/MI', 'Kotamobagu Selatan', 'Jl Hi Zakaria Imban', '', '', '2026-06-13 09:22:54'),
(382, 'SMP NEGERI 8 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Selatan', 'Jl Labot Dugian', '', '', '2026-06-13 09:22:54'),
(383, 'SMP NEGERI 9 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Selatan', 'Jl Pangan', '', '', '2026-06-13 09:22:54'),
(384, 'MI MI Assa\'adah', 'SD/MI', 'Kotamobagu Timur', 'Jln. Fajar Bulawan No. 01', '', '', '2026-06-13 09:23:01'),
(385, 'MIS CENDEKIA MUHAMMADIYAH KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'JL. JEND. AHMAD YANI NO. 10 KELURAHAN KOTAMOBAGU KECAMATAN KOTAMOBAGU BARAT KOT', '', '', '2026-06-13 09:23:01'),
(386, 'MTsS Muhammadiyah', 'SD/MI', 'Kotamobagu Timur', 'JL. AL-HUDA', '', '', '2026-06-13 09:23:01'),
(387, 'SD ADVENT KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'JLN. AKD DEPAN TERMINAL BONAWANG MONGKONAI', '', '', '2026-06-13 09:23:01'),
(388, 'SD KRISTEN I TUMOBUI', 'SD/MI', 'Kotamobagu Timur', 'Jalan Kapten Piere Tendean Nomor 60. Kecamatan Kotamobagu Barat Kota Kotamobagu', '', '', '2026-06-13 09:23:01'),
(389, 'SD NEGERI 1 KOBO BESAR', 'SD/MI', 'Kotamobagu Timur', 'Jln. Husin Raupu Kelurahan Molinow', '', '', '2026-06-13 09:23:01'),
(390, 'SD NEGERI 1 KOBO KECIL', 'SD/MI', 'Kotamobagu Timur', 'Jln Adampe Dolot No 226', '', '', '2026-06-13 09:23:01'),
(391, 'SD NEGERI 1 KOTOBANGON', 'SD/MI', 'Kotamobagu Timur', 'Jl. Zakaria Imban No. 26', '', '', '2026-06-13 09:23:01'),
(392, 'SD NEGERI 1 MATALI', 'SD/MI', 'Kotamobagu Timur', 'Jl. Jenderal Ahmad Yani No. 800', '', '', '2026-06-13 09:23:01'),
(393, 'SD NEGERI 1 MOTOBOI BESAR', 'SD/MI', 'Kotamobagu Timur', 'Jl. Walanda Maramis', '', '', '2026-06-13 09:23:02'),
(394, 'SD NEGERI 1 MOYAG', 'SD/MI', 'Kotamobagu Timur', 'JL. WALANDA MARAMIS', '', '', '2026-06-13 09:23:02'),
(395, 'SD NEGERI 1 SININDIAN', 'SD/MI', 'Kotamobagu Timur', 'Jl Suprato', '', '', '2026-06-13 09:23:02'),
(396, 'SD NEGERI 2 KOBO BESAR', 'SD/MI', 'Kotamobagu Timur', 'Jl. Kesatria No.1', '', '', '2026-06-13 09:23:02'),
(397, 'SD NEGERI 2 KOBO KECIL', 'SD/MI', 'Kotamobagu Timur', 'Jl. CEMPAKA', '', '', '2026-06-13 09:23:02'),
(398, 'SD NEGERI 2 KOTOBANGON', 'SD/MI', 'Kotamobagu Timur', 'Jl. Veteran', '', '', '2026-06-13 09:23:02'),
(399, 'SD NEGERI 2 MATALI', 'SD/MI', 'Kotamobagu Timur', 'Jl. Brawijaya', '', '', '2026-06-13 09:23:02'),
(400, 'SD NEGERI 2 MOTOBOI BESAR', 'SD/MI', 'Kotamobagu Timur', 'Jl. Piere Tendean', '', '', '2026-06-13 09:23:02'),
(401, 'SD NEGERI 2 MOYAG', 'SD/MI', 'Kotamobagu Timur', 'Jalan Kasatria No 11', '', '', '2026-06-13 09:23:02'),
(402, 'SD NEGERI 2 SININDIAN', 'SD/MI', 'Kotamobagu Timur', 'Jl. Pribumi', '', '', '2026-06-13 09:23:02'),
(403, 'SD NEGERI 3 KOTOBANGON', 'SD/MI', 'Kotamobagu Timur', 'Jl. Gatot Subroto', '', '', '2026-06-13 09:23:02'),
(404, 'SD NEGERI 3 MATALI', 'SD/MI', 'Kotamobagu Timur', 'Jl. AKD Linggkungan II', '', '', '2026-06-13 09:23:02'),
(405, 'SD NEGERI 3 MOYAG', 'SD/MI', 'Kotamobagu Timur', 'Jl Inpres Nomor 3', '', '', '2026-06-13 09:23:02'),
(406, 'SD NEGERI 4 MOYAG', 'SD/MI', 'Kotamobagu Timur', 'Jl. Mawar', '', '', '2026-06-13 09:23:02'),
(407, 'SDIT AL-HASANAIN KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl. Siswa', '', '', '2026-06-13 09:23:02'),
(408, 'SMP ADVENT KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl. Gatot Subroto', '', '', '2026-06-13 09:23:02'),
(409, 'SMP NEGERI 2 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl. Adampe Dolot', '', '', '2026-06-13 09:23:02'),
(410, 'SMP NEGERI 5 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl Adampe Dolot No 69', '', '', '2026-06-13 09:23:02'),
(411, 'SMP NEGERI 6 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl S Parman Kotamobagu', '', '', '2026-06-13 09:23:02'),
(412, 'SMPIT AL-HASANAIN KOTAMOBAGU', 'SD/MI', 'Kotamobagu Timur', 'Jl. Kampus No.9', '', '', '2026-06-13 09:23:02'),
(413, 'SD NEGERI 1 BIGA', 'SD/MI', 'Kotamobagu Utara', 'Biga', '', '', '2026-06-13 09:23:08'),
(414, 'SD NEGERI 1 PONTODON', 'SD/MI', 'Kotamobagu Utara', 'Desa Pontodon', '', '', '2026-06-13 09:23:08'),
(415, 'SD NEGERI 1 UPAI', 'SD/MI', 'Kotamobagu Utara', 'Upai', '', '', '2026-06-13 09:23:08'),
(416, 'SD NEGERI 2 BIGA', 'SD/MI', 'Kotamobagu Utara', 'Jl. Golkar', '', '', '2026-06-13 09:23:08'),
(417, 'SD NEGERI 2 PONTODON', 'SD/MI', 'Kotamobagu Utara', 'Jln A.P Mokoginta', '', '', '2026-06-13 09:23:08'),
(418, 'SD NEGERI 2 UPAI', 'SD/MI', 'Kotamobagu Utara', 'Jl. A.p. Mokoginta', '', '', '2026-06-13 09:23:08'),
(419, 'SD NEGERI 3 BILALANG', 'SD/MI', 'Kotamobagu Utara', 'Desa Bilalang 2', '', '', '2026-06-13 09:23:08'),
(420, 'SD NEGERI 4 BILALANG', 'SD/MI', 'Kotamobagu Utara', 'Desa Bilalang 1', '', '', '2026-06-13 09:23:08'),
(421, 'SD NEGERI GENGGULANG', 'SD/MI', 'Kotamobagu Utara', 'Jl. Kapten Tendean', '', '', '2026-06-13 09:23:08'),
(422, 'SD NEGERI SIA', 'SD/MI', 'Kotamobagu Utara', 'Desa Sia', '', '', '2026-06-13 09:23:08'),
(423, 'SEKOLAH MENENGAH PERTAMA ISLAM DARURRAHMAH', 'SD/MI', 'Kotamobagu Utara', 'Jln. AP Mokoginta, Desa Pontodon, Kec. Kotamobagu Utara', '', '', '2026-06-13 09:23:08'),
(424, 'SMP KATOLIK THEODORUS KOTAMOBAGU', 'SD/MI', 'Kotamobagu Utara', 'Jalan Diponegoro', '', '', '2026-06-13 09:23:08'),
(425, 'SMP NEGERI 7 KOTAMOBAGU', 'SD/MI', 'Kotamobagu Utara', 'Jalan Siswa Bilalang II', '', '', '2026-06-13 09:23:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_name', 'PGRI Kotamobagu'),
(2, 'vision', 'Terwujudnya guru profesional, bermartabat, dan berdaya saing untuk pendidikan Kotamobagu yang unggul.'),
(3, 'history', 'PGRI Kotamobagu hadir sebagai wadah perjuangan, pengembangan profesi, dan pengabdian guru di wilayah Kota Kotamobagu. Organisasi ini memperkuat solidaritas pendidik sekaligus mendukung agenda peningkatan mutu pendidikan daerah.'),
(4, 'mission', 'Meningkatkan profesionalisme guru; memperjuangkan perlindungan dan kesejahteraan anggota; membangun kolaborasi pendidikan; memperkuat layanan organisasi berbasis data dan teknologi.'),
(5, 'address', 'Jl. Ahmad Yani, Kota Kotamobagu'),
(6, 'email', 'pgrikotamobagu@gmail.com'),
(7, 'phone', '081242052380'),
(8, 'hero_banner', '/uploads/banner/banner-6a15b23b664f78.72712533.png'),
(19, 'site_logo', '/uploads/logo/logo-6a15b30c5f8d18.81200461.png'),
(57, 'whatsapp_number', '081242052380');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Sahyudi Amparodo', 'msdcokro@gmail.com', '$2y$10$DyXii2oM9Zm0c4FluM5tGu96B1aekhXXU1YTuxhuGzkXRWZfynfvG', 'superadmin', '2026-05-26 10:29:33'),
(3, 'Zahra Administrator', 'zahra@gmail.com', '$2y$10$FRFlZDBWHYwrwgPGLEKVG.RYd8ZMQXHoI9nRWCzo8Tawx8tW6kTfa', 'superadmin', '2026-05-30 17:27:10'),
(5, 'Reza Pahlawan Kobandaha', 'icankobandaha@gmail.com', '$2y$10$AQAkzsaUMeSl2Gx0VS6EYOVArMDiF5EKygei.wnVeATH4T9saIDby', 'superadmin', '2026-06-11 02:53:36');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `member_registrations`
--
ALTER TABLE `member_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `organization_members`
--
ALTER TABLE `organization_members`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_school_name_district` (`name`,`district`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `member_registrations`
--
ALTER TABLE `member_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `organization_members`
--
ALTER TABLE `organization_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
