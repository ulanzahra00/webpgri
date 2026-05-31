-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pgri_kotamobagu
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Organisasi','organisasi'),(2,'Pendidikan','pendidikan'),(3,'Kegiatan','kegiatan');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `subject` varchar(180) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (2,'satesting','tes@gmail.com','assalammualaikum','saya ingin melapor',0,'2026-05-28 04:39:05');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_reports`
--

DROP TABLE IF EXISTS `financial_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `financial_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(180) NOT NULL,
  `period_month` tinyint(4) NOT NULL,
  `period_year` year(4) NOT NULL,
  `category` varchar(120) NOT NULL,
  `income` decimal(15,2) DEFAULT 0.00,
  `expense` decimal(15,2) DEFAULT 0.00,
  `balance` decimal(15,2) GENERATED ALWAYS AS (`income` - `expense`) STORED,
  `description` text DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_reports`
--

LOCK TABLES `financial_reports` WRITE;
/*!40000 ALTER TABLE `financial_reports` DISABLE KEYS */;
INSERT INTO `financial_reports` VALUES (3,'Dukungan Mitra Pendidikan',2,2026,'Bantuan / Sponsor',5000000.00,0.00,5000000.00,'Penerimaan dukungan kegiatan dari mitra pendidikan daerah.',NULL,'published','2026-05-26 10:39:02',NULL),(5,'Sisa saldo PGRI dari bendahara lama',6,2026,'Iuran Anggota',500000.00,0.00,500000.00,'sisa saldo pengurus lama dan diserahkan ke pengurus baru',NULL,'published','2026-05-26 10:49:44',NULL);
/*!40000 ALTER TABLE `financial_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Rapat Koordinasi Pengurus','https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1000&q=80','Koordinasi program kerja organisasi.','2026-05-26','2026-05-26 10:25:02'),(2,'Pembelajaran Guru Kreatif','/uploads/galeri/galeri-6a15c07e2d3bf8.82435739.png','Pembelajaran Guru Kreatif SDN 2 Pobundayan','2026-05-26','2026-05-26 10:25:02'),(3,'Kegiatan Hari Pendidikan Nasional Tahun 2026','/uploads/galeri/galeri-6a15c049ef6415.65875788.jpg','Hari Pendidikan Nasional Tahun 2026','2026-05-26','2026-05-26 10:25:02');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_registrations`
--

DROP TABLE IF EXISTS `member_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(160) NOT NULL,
  `identity_number` varchar(80) DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_registrations`
--

LOCK TABLES `member_registrations` WRITE;
/*!40000 ALTER TABLE `member_registrations` DISABLE KEYS */;
INSERT INTO `member_registrations` VALUES (1,'siti wulandari osing','1234567','sahyudi.amparodo38@admin.sd.belajar.id','082195316493','SD Negeri 1 Mogolaing','Guru','Kotamobagu Barat','jalan veteran',NULL,'approved','','2026-05-28 11:56:43','2026-05-28 11:57:20'),(2,'zahra amparodo','1234456','zahra@gmail.com','0821334364344','SD negeri 2 Molinow','Kepala Sekolah','Kotamobagu Barat','molinow','terimakasih sudah membuat website pgri','approved','ok','2026-05-28 12:56:49','2026-05-28 12:57:08');
/*!40000 ALTER TABLE `member_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_members`
--

DROP TABLE IF EXISTS `organization_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `position` varchar(120) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_members`
--

LOCK TABLES `organization_members` WRITE;
/*!40000 ALTER TABLE `organization_members` DISABLE KEYS */;
INSERT INTO `organization_members` VALUES (1,'Erni Mokodompit S.Pd, M.Pd.','Ketua PGRI Kotamobagu','/uploads/pengurus/pengurus-6a15b184db3ea9.20231022.jpg','Memimpin penguatan organisasi dan advokasi profesi guru.',1),(2,'Amir Mahmud, S.Pd','Sekretaris','/uploads/pengurus/pengurus-6a15ca76b95e12.21010710.png','Mengelola administrasi dan koordinasi program kerja.',2),(3,'Sakina Mokodompit, S.Pd','Bendahara','/uploads/pengurus/pengurus-6a15cb08f16de3.86203211.png','Mengawal tata kelola keuangan organisasi.',3);
/*!40000 ALTER TABLE `organization_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'PGRI Kotamobagu Perkuat Kolaborasi Guru','pgri-kotamobagu-perkuat-kolaborasi-guru','PGRI Kotamobagu mendorong kolaborasi lintas sekolah untuk peningkatan mutu pendidikan.','PGRI Kotamobagu terus memperkuat sinergi antarpendidik melalui forum diskusi, pelatihan, dan pendampingan profesional. Program ini menjadi bagian dari komitmen organisasi untuk menghadirkan layanan pendidikan yang adaptif, inklusif, dan bermutu.','https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',NULL,'published','2026-05-26 18:25:02','2026-05-26 10:25:02',NULL),(2,2,'Pelatihan Literasi Digital untuk Guru','pelatihan-literasi-digital-untuk-guru','Guru didorong menguasai teknologi pembelajaran modern.','Kegiatan literasi digital diikuti oleh perwakilan guru dari berbagai kecamatan. Materi berfokus pada penggunaan media pembelajaran, keamanan data, dan penyusunan konten kelas yang menarik.','https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',NULL,'published','2026-05-26 18:25:02','2026-05-26 10:25:02',NULL),(3,3,'Bakti Sosial Pendidikan PGRI','bakti-sosial-pendidikan-pgri','Kegiatan sosial menjadi wujud kepedulian PGRI kepada masyarakat.','PGRI Kotamobagu menggelar bakti sosial pendidikan dengan melibatkan pengurus, guru, dan komunitas sekolah. Kegiatan ini diarahkan untuk memperkuat kepedulian dan solidaritas insan pendidikan.','https://images.unsplash.com/photo-1497486751825-1233686d5d80?auto=format&fit=crop&w=1200&q=80',NULL,'published','2026-05-26 18:25:02','2026-05-26 10:25:02',NULL),(4,2,'Upacara Hari Pendidikan Nasional (Hardiknas) Tahun 2026','upacara-hari-pendidikan-nasional-hardiknas-tahun-2026-4','PGRI Kota Kotamobagu melaksanakan Upacara Hari Pendidikan Nasional Tahun 2026 sebagai bentuk komitmen bersama dalam mewujudkan pendidikan bermutu untuk semua melalui kolaborasi seluruh elemen bangsa.','Dalam rangka memperingati Hari Pendidikan Nasional (Hardiknas) yang jatuh pada tanggal 2 Mei 2026, PGRI Kota Kotamobagu turut melaksanakan upacara bendera sebagai wujud penghormatan terhadap jasa para tokoh pendidikan serta komitmen bersama dalam memajukan dunia pendidikan.\r\n\r\nUpacara ini mengusung tema nasional “Menguatkan Partisipasi Semesta Mewujudkan Pendidikan Bermutu untuk Semua”, yang menegaskan pentingnya kolaborasi seluruh elemen masyarakat dalam meningkatkan kualitas pendidikan di Indonesia.\r\n\r\nKegiatan ini diikuti oleh pengurus PGRI, para guru, tenaga kependidikan, serta siswa dengan penuh khidmat. Momentum Hardiknas menjadi pengingat bahwa pendidikan merupakan fondasi utama dalam membangun generasi unggul, berkarakter, dan siap menghadapi tantangan masa depan.\r\n\r\nMelalui peringatan ini, PGRI Kota Kotamobagu mengajak seluruh insan pendidikan untuk terus berinovasi, meningkatkan profesionalisme, serta memperkuat sinergi demi terwujudnya pendidikan yang inklusif, merata, dan berkualitas bagi semua.','/uploads/berita/berita-6a15bbafb238b7.27953162.jpg','/uploads/berita/berita-6a15baaf3dc392.76679245.mp4','published','2026-05-26 23:22:23','2026-05-26 15:22:23','2026-05-26 15:28:53'),(5,2,'Guru Bahasa Inggris SD Terapkan Pendekatan Pembelajaran Mendalam','guru-bahasa-inggris-sd-terapkan-pendekatan-pembelajaran-mendalam-5','Seorang guru Bahasa Inggris di Sekolah Dasar Negeri 2 Pobundayan menerapkan pendekatan pembelajaran mendalam yang menekankan pemahaman konsep dan keterlibatan aktif siswa. Melalui metode interaktif, siswa tidak hanya menghafal, tetapi juga mampu menggunakan Bahasa Inggris dalam konteks nyata. Pendekatan ini membuat pembelajaran lebih bermakna, menyenangkan, serta meningkatkan kepercayaan diri siswa.','Seorang guru Bahasa Inggris di Sekolah Dasar Negeri 2 Pobundayanmenunjukkan inovasi dalam proses pembelajaran dengan menerapkan pendekatan pembelajaran mendalam (deep learning) yang berfokus pada pemahaman konsep, keterlibatan aktif siswa, serta penguatan karakter. Dalam kegiatan belajar mengajar, siswa tidak hanya diajak menghafal kosakata, tetapi juga memahami makna, penggunaan dalam konteks sehari-hari, serta mengembangkan kemampuan berpikir kritis dan komunikasi.\r\n\r\nMelalui metode interaktif seperti diskusi kelompok, permainan edukatif, dan praktik langsung, suasana kelas menjadi lebih hidup dan menyenangkan. Siswa terlihat lebih percaya diri dalam menggunakan Bahasa Inggris, baik secara lisan maupun tulisan. Pendekatan ini juga mendorong siswa untuk berani bertanya, mengemukakan pendapat, serta bekerja sama dengan teman.\r\n\r\nUpaya ini menjadi contoh nyata bahwa pembelajaran Bahasa Inggris di Sekolah Dasar dapat dilakukan secara bermakna dan relevan dengan kehidupan siswa. Diharapkan praktik baik ini dapat menginspirasi para guru lainnya untuk terus berinovasi dalam menciptakan pembelajaran yang berkualitas dan berpusat pada siswa.','/uploads/berita/berita-6a15bd7acd0542.63961944.png','/uploads/berita/berita-6a15bd7acdacf2.29822056.mp4','published','2026-05-26 23:34:18','2026-05-26 15:34:18','2026-05-26 15:35:50'),(6,2,'Link Pendaftaran Murid Baru','link-pendaftaran-murid-baru-6','Ayo daftarkan segera anak-anak kita !!\r\nhttps://spmb.kotamobagu.go.id\r\nAlur Pendaftaran:\r\n1. Masuk Portal\r\n2. Pilih Jenis Sekolah\r\n3.Pilih Sekolah\r\n4. Isi Formulir\r\n5. Unggah Dokumen\r\n6. Kirim Pendaftaran\r\n7. Verifikasi Sekolah\r\n8. Pengumuman\r\n9. Daftar Ulang','Alur Pendaftaran\r\nIkuti tahapan ini dari atas ke bawah.\r\n1. Masuk Portal\r\nBuka portal SPMB dan masuk menggunakan NISN serta tanggal lahir anak. Khusus TK dan SD, dapat memakai NIK jika belum memiliki NISN.\r\n2. Pilih Jenis Sekolah\r\nPilih mendaftar ke sekolah Negeri atau Swasta. Jika Negeri, pilih salah satu jalur: Domisili, Afirmasi, Prestasi, atau Mutasi.\r\n3.Pilih Sekolah\r\nPilih sekolah tujuan yang masih memiliki sisa kuota pada jalur yang Anda pilih.\r\n4. Isi Formulir\r\nLengkapi data diri dan keluarga. Sebagian data terisi otomatis dari data kependudukan dan sekolah asal.\r\n5. Unggah Dokumen\r\nUnggah dokumen pendukung sesuai jalur. Pastikan foto atau hasil scan terbaca dengan jelas.\r\n6. Kirim Pendaftaran\r\nKlik tombol Kirim. Sistem memberi nomor pendaftaran resmi, contoh: SPMB-SD-2026-001234.\r\n7. Verifikasi Sekolah\r\nOperator sekolah memeriksa dokumen selama kurang lebih 2–3 minggu. Status dapat dipantau melalui portal.\r\n8. Pengumuman\r\nHasil seleksi dapat dilihat di portal SPMB dengan cara login menggunakan NISN/NIK dan tanggal lahir anak. Pastikan untuk memeriksa secara berkala.\r\n9. Daftar Ulang\r\nJika diterima, datang ke sekolah pada jadwal daftar ulang dengan dokumen asli untuk konfirmasi penerimaan.\r\nadwal Penting\r\nTanggal pendaftaran, verifikasi, dan daftar ulang.\r\n#########################################################################################\r\n#########################################################################################\r\nPendaftaran SD\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 9 Juni 2026\r\nBatas Verifikasi SD\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang SD\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026\r\nPendaftaran SMP\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 9 Juni 2026\r\nBatas Verifikasi SMP\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang SMP\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026\r\nPendaftaran TK\r\nPeriode siswa mengisi dan mengirim pendaftaran\r\n4 Mei – 8 Juni 2026\r\nBatas Verifikasi TK\r\nTanggal terakhir operator memverifikasi dokumen\r\n19 Juni 2026\r\nDaftar Ulang TK\r\nCalon murid yang diterima konfirmasi ke sekolah\r\n30 Juni – 3 Juli 2026','/uploads/berita/berita-6a15c1a1102071.67023809.jpg',NULL,'published','2026-05-26 23:52:01','2026-05-26 15:52:01','2026-05-26 16:00:03');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `level` varchar(50) NOT NULL,
  `district` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `headmaster` varchar(120) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schools`
--

LOCK TABLES `schools` WRITE;
/*!40000 ALTER TABLE `schools` DISABLE KEYS */;
INSERT INTO `schools` VALUES (107,'SD Negeri Contoh 1','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(108,'SD Negeri Contoh 2','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(109,'SD Negeri Contoh 3','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(110,'SD Negeri Contoh 4','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(111,'SD Negeri Contoh 5','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(112,'SD Negeri Contoh 6','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(113,'SD Negeri Contoh 7','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(114,'SD Negeri Contoh 8','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(115,'SD Negeri Contoh 9','SD','Kotamobagu Barat','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(116,'SD Negeri Contoh 10','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(117,'SD Negeri Contoh 11','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(118,'SD Negeri Contoh 12','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(119,'SD Negeri Contoh 13','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(120,'SD Negeri Contoh 14','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(121,'SD Negeri Contoh 15','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(122,'SD Negeri Contoh 16','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(123,'SD Negeri Contoh 17','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(124,'SD Negeri Contoh 18','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(125,'SD Negeri Contoh 19','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(126,'SD Negeri Contoh 20','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(127,'SD Negeri Contoh 21','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(128,'SD Negeri Contoh 22','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(129,'SD Negeri Contoh 23','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(130,'SD Negeri Contoh 24','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(131,'SD Negeri Contoh 25','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(132,'SD Negeri Contoh 26','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(133,'SD Negeri Contoh 27','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(134,'SD Negeri Contoh 28','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(135,'SD Negeri Contoh 29','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(136,'SD Negeri Contoh 30','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(137,'SD Negeri Contoh 31','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(138,'SD Negeri Contoh 32','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(139,'SD Negeri Contoh 33','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(140,'SD Negeri Contoh 34','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(141,'SD Negeri Contoh 35','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(142,'SD Negeri Contoh 36','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(143,'SD Negeri Contoh 37','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(144,'SD Negeri Contoh 38','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(145,'SD Negeri Contoh 39','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(146,'SD Negeri Contoh 40','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(147,'SD Negeri Contoh 41','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(148,'SD Negeri Contoh 42','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(149,'SD Negeri Contoh 43','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(150,'SD Negeri Contoh 44','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(151,'SD Negeri Contoh 45','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(152,'SD Negeri Contoh 46','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(153,'SD Negeri Contoh 47','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(154,'SD Negeri Contoh 48','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(155,'SD Negeri Contoh 49','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(156,'SD Negeri Contoh 50','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37'),(157,'SD Negeri Contoh 51','SD','Kotamobagu Timur','Jl Zakaria Imban','','081234567890','2026-05-28 13:35:37');
/*!40000 ALTER TABLE `schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','PGRI Kotamobagu'),(2,'vision','Terwujudnya guru profesional, bermartabat, dan berdaya saing untuk pendidikan Kotamobagu yang unggul.'),(3,'history','PGRI Kotamobagu hadir sebagai wadah perjuangan, pengembangan profesi, dan pengabdian guru di wilayah Kota Kotamobagu. Organisasi ini memperkuat solidaritas pendidik sekaligus mendukung agenda peningkatan mutu pendidikan daerah.'),(4,'mission','Meningkatkan profesionalisme guru; memperjuangkan perlindungan dan kesejahteraan anggota; membangun kolaborasi pendidikan; memperkuat layanan organisasi berbasis data dan teknologi.'),(5,'address','Jl. Ahmad Yani, Kota Kotamobagu'),(6,'email','sahyudi.amparodo38@admin.sd.belajar.id'),(7,'phone','082195316493'),(8,'hero_banner','/uploads/banner/banner-6a15b23b664f78.72712533.png'),(19,'site_logo','/uploads/logo/logo-6a15b30c5f8d18.81200461.png'),(57,'whatsapp_number','6281234567890');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator PGRI','admin@pgrikotamobagu.or.id','$2y$10$ZZWGjYP6DKBILUOExiiYV.8qEE/9uaVMNdmZGYo4QTDwsSGqpuWne','superadmin','2026-05-26 10:25:02'),(2,'Sahyudi Amparodo','msdcokro@gmail.com','$2y$10$DyXii2oM9Zm0c4FluM5tGu96B1aekhXXU1YTuxhuGzkXRWZfynfvG','superadmin','2026-05-26 10:29:33'),(3,'Zahra Administrator','zahra@gmail.com','$2y$10$FRFlZDBWHYwrwgPGLEKVG.RYd8ZMQXHoI9nRWCzo8Tawx8tW6kTfa','superadmin','2026-05-30 17:27:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'pgri_kotamobagu'
--

--
-- Dumping routines for database 'pgri_kotamobagu'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-31  1:39:07
