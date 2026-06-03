USE `pgrikota_pgri`;

CREATE TABLE IF NOT EXISTS financial_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    period_month TINYINT NOT NULL,
    period_year YEAR NOT NULL,
    category VARCHAR(120) NOT NULL,
    income DECIMAL(15,2) DEFAULT 0,
    expense DECIMAL(15,2) DEFAULT 0,
    balance DECIMAL(15,2) GENERATED ALWAYS AS (income - expense) STORED,
    description TEXT NULL,
    document VARCHAR(255) NULL,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO financial_reports (title, period_month, period_year, category, income, expense, description, status)
SELECT 'Iuran Anggota Bulan Januari', 1, 2026, 'Iuran Anggota', 12500000, 0, 'Penerimaan iuran anggota PGRI Kotamobagu bulan Januari.', 'published'
WHERE NOT EXISTS (SELECT 1 FROM financial_reports LIMIT 1);

INSERT INTO financial_reports (title, period_month, period_year, category, income, expense, description, status)
SELECT 'Kegiatan Pelatihan Guru Kreatif', 1, 2026, 'Program Kerja', 0, 7350000, 'Pengeluaran konsumsi, narasumber, dan perlengkapan pelatihan guru.', 'published'
WHERE (SELECT COUNT(*) FROM financial_reports) = 1;

INSERT INTO financial_reports (title, period_month, period_year, category, income, expense, description, status)
SELECT 'Dukungan Mitra Pendidikan', 2, 2026, 'Bantuan / Sponsor', 5000000, 0, 'Penerimaan dukungan kegiatan dari mitra pendidikan daerah.', 'published'
WHERE (SELECT COUNT(*) FROM financial_reports) = 2;
