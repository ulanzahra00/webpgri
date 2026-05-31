USE pgri_kotamobagu;

INSERT INTO users (name, email, password, role) VALUES
('Administrator PGRI', 'admin@pgrikotamobagu.or.id', '$2y$12$tgXwVrFfGOwvzvq4uMBgS.pP8VTqdrFbGCsMbDKwMfC83qMYEnqsC', 'superadmin'),
('Zahra Administrator', 'zahra@gmail.com', '$2y$10$ooPmK0b1ZCUnipm7kuMqleATLTyF5sCIwAML70scp2LfBm0r15Fl6', 'superadmin');
-- Password dummy: admin12345
-- Password Zahra Administrator: tanyap4ZIL

INSERT INTO categories (name, slug) VALUES
('Organisasi', 'organisasi'), ('Pendidikan', 'pendidikan'), ('Kegiatan', 'kegiatan');

INSERT INTO posts (category_id, title, slug, excerpt, content, image, status, published_at) VALUES
(1, 'PGRI Kotamobagu Perkuat Kolaborasi Guru', 'pgri-kotamobagu-perkuat-kolaborasi-guru', 'PGRI Kotamobagu mendorong kolaborasi lintas sekolah untuk peningkatan mutu pendidikan.', 'PGRI Kotamobagu terus memperkuat sinergi antarpendidik melalui forum diskusi, pelatihan, dan pendampingan profesional. Program ini menjadi bagian dari komitmen organisasi untuk menghadirkan layanan pendidikan yang adaptif, inklusif, dan bermutu.', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80', 'published', NOW()),
(2, 'Pelatihan Literasi Digital untuk Guru', 'pelatihan-literasi-digital-untuk-guru', 'Guru didorong menguasai teknologi pembelajaran modern.', 'Kegiatan literasi digital diikuti oleh perwakilan guru dari berbagai kecamatan. Materi berfokus pada penggunaan media pembelajaran, keamanan data, dan penyusunan konten kelas yang menarik.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80', 'published', NOW()),
(3, 'Bakti Sosial Pendidikan PGRI', 'bakti-sosial-pendidikan-pgri', 'Kegiatan sosial menjadi wujud kepedulian PGRI kepada masyarakat.', 'PGRI Kotamobagu menggelar bakti sosial pendidikan dengan melibatkan pengurus, guru, dan komunitas sekolah. Kegiatan ini diarahkan untuk memperkuat kepedulian dan solidaritas insan pendidikan.', 'https://images.unsplash.com/photo-1497486751825-1233686d5d80?auto=format&fit=crop&w=1200&q=80', 'published', NOW());

INSERT INTO schools (name, level, district, address, headmaster, phone) VALUES
('SD Negeri 1 Kotamobagu', 'SD', 'Kotamobagu Barat', 'Jl. Ahmad Yani, Kotamobagu', 'Hj. Nurhayati, S.Pd', '0434-000001'),
('SMP Negeri 2 Kotamobagu', 'SMP', 'Kotamobagu Timur', 'Jl. Pendidikan, Kotamobagu', 'Drs. Amirudin', '0434-000002'),
('SMA Negeri 1 Kotamobagu', 'SMA', 'Kotamobagu Selatan', 'Jl. Kampus, Kotamobagu', 'Dra. Melati, M.Pd', '0434-000003'),
('SMK Negeri 1 Kotamobagu', 'SMK', 'Kotamobagu Utara', 'Jl. Teknologi, Kotamobagu', 'Ir. Rahmat, M.Pd', '0434-000004');

INSERT INTO galleries (title, image, description, event_date) VALUES
('Rapat Koordinasi Pengurus', 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1000&q=80', 'Koordinasi program kerja organisasi.', CURDATE()),
('Pelatihan Guru Kreatif', 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', 'Pelatihan peningkatan kompetensi guru.', CURDATE()),
('Kegiatan Hari Guru', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1000&q=80', 'Peringatan Hari Guru Nasional.', CURDATE());

INSERT INTO organization_members (name, position, photo, bio, sort_order) VALUES
('Drs. Abdul Karim, M.Pd', 'Ketua PGRI Kotamobagu', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80', 'Memimpin penguatan organisasi dan advokasi profesi guru.', 1),
('Hj. Siti Aisyah, S.Pd', 'Sekretaris', 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=600&q=80', 'Mengelola administrasi dan koordinasi program kerja.', 2),
('Rahman Mokoginta, S.Pd', 'Bendahara', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=600&q=80', 'Mengawal tata kelola keuangan organisasi.', 3);

INSERT INTO financial_reports (title, period_month, period_year, category, income, expense, description, status) VALUES
('Iuran Anggota Bulan Januari', 1, 2026, 'Iuran Anggota', 12500000, 0, 'Penerimaan iuran anggota PGRI Kotamobagu bulan Januari.', 'published'),
('Kegiatan Pelatihan Guru Kreatif', 1, 2026, 'Program Kerja', 0, 7350000, 'Pengeluaran konsumsi, narasumber, dan perlengkapan pelatihan guru.', 'published'),
('Dukungan Mitra Pendidikan', 2, 2026, 'Bantuan / Sponsor', 5000000, 0, 'Penerimaan dukungan kegiatan dari mitra pendidikan daerah.', 'published');

INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'PGRI Kotamobagu'),
('vision', 'Terwujudnya guru profesional, bermartabat, dan berdaya saing untuk pendidikan Kotamobagu yang unggul.'),
('history', 'PGRI Kotamobagu hadir sebagai wadah perjuangan, pengembangan profesi, dan pengabdian guru di wilayah Kota Kotamobagu. Organisasi ini memperkuat solidaritas pendidik sekaligus mendukung agenda peningkatan mutu pendidikan daerah. Sebagai bagian dari keluarga besar Persatuan Guru Republik Indonesia, PGRI Kotamobagu berperan aktif menjadi ruang pemersatu bagi guru, tenaga kependidikan, dan pemerhati pendidikan di daerah. Organisasi ini tidak hanya hadir sebagai wadah administratif, tetapi juga sebagai rumah perjuangan profesi yang mendorong peningkatan kapasitas, etika, solidaritas, dan martabat guru. Dalam perkembangannya, PGRI Kotamobagu terus menyesuaikan diri dengan kebutuhan zaman. Berbagai program kerja diarahkan untuk mendukung peningkatan mutu pembelajaran, penguatan literasi digital, pendampingan anggota, serta kerja sama dengan pemerintah daerah dan satuan pendidikan. Melalui semangat kebersamaan, PGRI Kotamobagu berkomitmen menjaga peran strategis guru sebagai penggerak utama kemajuan pendidikan dan pembentukan karakter generasi muda.'),
('mission', 'Meningkatkan profesionalisme guru; memperjuangkan perlindungan dan kesejahteraan anggota; membangun kolaborasi pendidikan; memperkuat layanan organisasi berbasis data dan teknologi.'),
('address', 'Jl. Pendidikan, Kota Kotamobagu, Sulawesi Utara'),
('email', 'sekretariat@pgrikotamobagu.or.id'),
('phone', '0434-000000'),
('whatsapp_number', '6281234567890'),
('hero_banner', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=80'),
('site_logo', '');
