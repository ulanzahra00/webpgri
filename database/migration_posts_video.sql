-- Tambahkan dukungan video MP4 untuk postingan berita.
ALTER TABLE posts
ADD COLUMN video VARCHAR(255) NULL AFTER image;
