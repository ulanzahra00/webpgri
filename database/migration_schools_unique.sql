-- Migration: Menambahkan UNIQUE constraint (name + district) pada tabel schools
-- agar tidak ada data sekolah duplikat saat import atau input manual.
-- Jalankan via phpMyAdmin atau CLI MySQL setelah deploy.

-- Hapus duplikat terlebih dahulu (keep id terkecil)
DELETE s1 FROM schools s1
INNER JOIN schools s2
WHERE s1.id > s2.id
  AND s1.name = s2.name
  AND s1.district = s2.district;

-- Tambah UNIQUE constraint
ALTER TABLE schools ADD UNIQUE INDEX uq_school_name_district (name, district);