<?php
// Handler simpan dan hapus data admin. Semua input diproses lewat prepared statement.
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$module = $_POST['module'] ?? $_GET['module'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? 'save';

if ($module === 'member_registrations') {
    ensure_member_registrations_table();
}

if ($module === 'finance') {
    ensure_financial_reports_deposit_date_column();
}

if ($module === 'schools' && $action === 'template') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=template-data-sekolah.csv');

    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, array_values(school_import_columns()));
    fputcsv($output, ['SD Negeri Contoh', 'SD', 'Kotamobagu Barat', 'Jl. Pendidikan No. 1', 'Nama Kepala Sekolah, S.Pd', '081234567890']);
    fclose($output);
    exit;
}

if ($module === 'schools' && $action === 'delete_all') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf_token'] ?? '')) {
        flash('danger', 'Token keamanan tidak valid.');
        redirect('admin/?module=schools');
    }

    db()->exec('DELETE FROM schools');
    flash('success', 'Semua data sekolah berhasil dihapus.');
    redirect('admin/?module=schools');
}

if ($module === 'schools' && $action === 'delete_selected') {
    verify_csrf();

    $ids = array_values(array_filter(array_map('intval', $_POST['school_ids'] ?? [])));
    if (!$ids) {
        flash('danger', 'Pilih minimal satu data sekolah yang ingin dihapus.');
        redirect('admin/?module=schools');
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("DELETE FROM schools WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    flash('success', count($ids) . ' data sekolah berhasil dihapus.');
    redirect('admin/?module=schools');
}

if ($action === 'delete') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf_token'] ?? '')) {
        flash('danger', 'Token keamanan tidak valid.');
        redirect('admin/');
    }

    $id = (int)($_GET['id'] ?? 0);
    $tables = [
        'posts' => 'posts',
        'galleries' => 'galleries',
        'schools' => 'schools',
        'finance' => 'financial_reports',
        'members' => 'organization_members',
        'member_registrations' => 'member_registrations',
        'users' => 'users',
        'messages' => 'contact_messages',
    ];

    if (isset($tables[$module]) && $id > 0) {
        if ($module === 'users' && $id === (int)current_admin()['id']) {
            flash('danger', 'User yang sedang login tidak dapat dihapus.');
        } else {
            $stmt = db()->prepare('DELETE FROM ' . $tables[$module] . ' WHERE id = ?');
            $stmt->execute([$id]);
            flash('success', 'Data berhasil dihapus.');
        }
    }

    redirect('admin/?module=' . urlencode($module));
}

verify_csrf();

try {
    if ($module === 'posts') {
        ensure_posts_video_column();

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $image = upload_image($_FILES['image'] ?? [], 'berita');
        $video = upload_video($_FILES['video'] ?? [], 'berita');

        if ($id) {
            $old = db()->prepare('SELECT image, video FROM posts WHERE id = ?');
            $old->execute([$id]);
            $oldPost = $old->fetch() ?: [];
            $image = $image ?: ($oldPost['image'] ?? null);
            $image = is_demo_post_image($image) ? null : $image;
            $video = $video ?: ($oldPost['video'] ?? null);
            $stmt = db()->prepare('UPDATE posts SET category_id=?, title=?, slug=?, excerpt=?, content=?, image=?, video=?, status=? WHERE id=?');
            $stmt->execute([(int)$_POST['category_id'], $title, slugify($title) . '-' . $id, trim($_POST['excerpt']), trim($_POST['content']), $image, $video, $_POST['status'], $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO posts (category_id,title,slug,excerpt,content,image,video,status,published_at) VALUES (?,?,?,?,?,?,?,?,NOW())');
            $stmt->execute([(int)$_POST['category_id'], $title, slugify($title) . '-' . time(), trim($_POST['excerpt']), trim($_POST['content']), $image, $video, $_POST['status']]);
        }
        flash('success', 'Berita berhasil disimpan.');
    }

    if ($module === 'galleries') {
        $id = (int)($_POST['id'] ?? 0);
        $image = upload_image($_FILES['image'] ?? [], 'galeri');
        if ($id) {
            $old = db()->prepare('SELECT image FROM galleries WHERE id = ?');
            $old->execute([$id]);
            $image = $image ?: $old->fetchColumn();
            $stmt = db()->prepare('UPDATE galleries SET title=?, description=?, event_date=?, image=? WHERE id=?');
            $stmt->execute([trim($_POST['title']), trim($_POST['description']), $_POST['event_date'] ?: null, $image, $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO galleries (title,description,event_date,image) VALUES (?,?,?,?)');
            $stmt->execute([trim($_POST['title']), trim($_POST['description']), $_POST['event_date'] ?: null, $image]);
        }
        flash('success', 'Galeri berhasil disimpan.');
    }

    if ($module === 'schools') {
        if ($action === 'import') {
            $rows = read_school_import_file($_FILES['school_file'] ?? []);
            if (!$rows) {
                throw new RuntimeException('Tidak ada baris data valid untuk diimport.');
            }

            $stmt = db()->prepare('INSERT INTO schools (name, level, district, address, headmaster, phone) VALUES (?,?,?,?,?,?)');
            $imported = 0;
            foreach ($rows as $row) {
                $stmt->execute([
                    $row['name'],
                    $row['level'],
                    $row['district'],
                    $row['address'],
                    $row['headmaster'],
                    $row['phone'],
                ]);
                $imported++;
            }

            flash('success', $imported . ' data sekolah berhasil diimport.');
            redirect('admin/?module=schools');
        }

        $data = [trim($_POST['name']), trim($_POST['level']), trim($_POST['district']), trim($_POST['address']), trim($_POST['headmaster']), trim($_POST['phone'])];
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = db()->prepare('UPDATE schools SET name=?, level=?, district=?, address=?, headmaster=?, phone=? WHERE id=?');
            $stmt->execute(array_merge($data, [$id]));
        } else {
            $stmt = db()->prepare('INSERT INTO schools (name,level,district,address,headmaster,phone) VALUES (?,?,?,?,?,?)');
            $stmt->execute($data);
        }
        flash('success', 'Data sekolah berhasil disimpan.');
    }

    if ($module === 'finance') {
        $id = (int)($_POST['id'] ?? 0);
        $document = upload_document($_FILES['document'] ?? [], 'keuangan');
        $periodMonth = (int)($_POST['period_month'] ?? date('n'));
        if ($periodMonth < 0 || $periodMonth > 12) {
            $periodMonth = date('n');
        }
        $data = [
            trim($_POST['title']),
            $periodMonth,
            (int)$_POST['period_year'],
            $_POST['deposit_date'] ?: null,
            null,
            trim($_POST['category']),
            (float)($_POST['income'] ?? 0),
            (float)($_POST['expense'] ?? 0),
            trim($_POST['description']),
            $_POST['status'],
        ];

        if ($id) {
            $old = db()->prepare('SELECT document FROM financial_reports WHERE id = ?');
            $old->execute([$id]);
            $document = $document ?: $old->fetchColumn();
            $stmt = db()->prepare('UPDATE financial_reports SET title=?, period_month=?, period_year=?, deposit_date=?, report_date=?, category=?, income=?, expense=?, description=?, status=?, document=? WHERE id=?');
            $stmt->execute(array_merge($data, [$document, $id]));
        } else {
            $stmt = db()->prepare('INSERT INTO financial_reports (title,period_month,period_year,deposit_date,report_date,category,income,expense,description,status,document) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(array_merge($data, [$document]));
        }
        flash('success', 'Laporan keuangan berhasil disimpan.');
    }

    if ($module === 'members') {
        $id = (int)($_POST['id'] ?? 0);
        $photo = upload_image($_FILES['photo'] ?? [], 'pengurus');
        if ($id) {
            $old = db()->prepare('SELECT photo FROM organization_members WHERE id = ?');
            $old->execute([$id]);
            $photo = $photo ?: $old->fetchColumn();
            $stmt = db()->prepare('UPDATE organization_members SET name=?, position=?, bio=?, sort_order=?, photo=? WHERE id=?');
            $stmt->execute([trim($_POST['name']), trim($_POST['position']), trim($_POST['bio']), (int)$_POST['sort_order'], $photo, $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO organization_members (name,position,bio,sort_order,photo) VALUES (?,?,?,?,?)');
            $stmt->execute([trim($_POST['name']), trim($_POST['position']), trim($_POST['bio']), (int)$_POST['sort_order'], $photo ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80']);
        }
        flash('success', 'Data pengurus berhasil disimpan.');
    }

    if ($module === 'member_registrations') {
        ensure_member_registrations_table();
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }
        if ($id > 0) {
            $stmt = db()->prepare('UPDATE member_registrations SET status=?, notes=? WHERE id=?');
            $stmt->execute([$status, trim($_POST['notes'] ?? ''), $id]);
            flash('success', 'Status registrasi anggota berhasil diperbarui.');
        }
    }

    if ($module === 'users') {
        $id = (int)($_POST['id'] ?? 0);
        $password = $_POST['password'] ?? '';
        if ($id) {
            if ($password !== '') {
                $stmt = db()->prepare('UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?');
                $stmt->execute([trim($_POST['name']), filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), $_POST['role'], password_hash($password, PASSWORD_BCRYPT), $id]);
            } else {
                $stmt = db()->prepare('UPDATE users SET name=?, email=?, role=? WHERE id=?');
                $stmt->execute([trim($_POST['name']), filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), $_POST['role'], $id]);
            }
        } else {
            $stmt = db()->prepare('INSERT INTO users (name,email,role,password) VALUES (?,?,?,?)');
            $stmt->execute([trim($_POST['name']), filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), $_POST['role'], password_hash($password, PASSWORD_BCRYPT)]);
        }
        flash('success', 'User admin berhasil disimpan.');
    }

    if ($module === 'settings') {
        $settings = $_POST['settings'] ?? [];
        $heroBanner = upload_image($_FILES['hero_banner'] ?? [], 'banner');
        $siteLogo = upload_image($_FILES['site_logo'] ?? [], 'logo');
        if ($heroBanner) {
            $settings['hero_banner'] = $heroBanner;
        }
        if ($siteLogo) {
            $settings['site_logo'] = $siteLogo;
        }

        foreach ($settings as $key => $value) {
            $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
            $stmt->execute([$key, trim($value)]);
        }
        flash('success', 'Pengaturan berhasil disimpan.');
    }
} catch (Throwable $error) {
    flash('danger', 'Gagal memproses data: ' . $error->getMessage());
}

redirect('admin/?module=' . urlencode($module));
