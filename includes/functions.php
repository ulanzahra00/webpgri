<?php
// Helper umum untuk sanitasi, auth, upload, flash message, dan query kecil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function starts_with(string $value, string $prefix): bool
{
    return $prefix === '' || strncmp($value, $prefix, strlen($prefix)) === 0;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function base_path(): string
{
    static $basePath = null;

    if ($basePath !== null) {
        return $basePath;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    foreach (['/public/', '/admin/', '/api/'] as $segment) {
        $position = strpos($scriptName, $segment);
        if ($position !== false) {
            $basePath = rtrim(substr($scriptName, 0, $position), '/');
            return $basePath;
        }
    }

    $basePath = rtrim(dirname($scriptName), '/\\');
    return $basePath === '.' ? '' : $basePath;
}

function url(string $path = ''): string
{
    if ($path === 'public' || starts_with($path, 'public/')) {
        $path = substr($path, strlen('public'));
    }

    if ($path === '' || $path === '/') {
        return base_path() === '' ? '/' : base_path() . '/';
    }

    if (preg_match('#^(https?:)?//#', $path) || starts_with($path, '#')) {
        return $path;
    }

    return (base_path() === '' ? '' : base_path()) . '/' . ltrim($path, '/');
}

function media_url(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }

    return url($path);
}

function asset_url(string $path): string
{
    $assetPath = ltrim($path, '/');
    $filePath = __DIR__ . '/../' . $assetPath;
    $version = is_file($filePath) ? filemtime($filePath) : time();

    return url($assetPath) . '?v=' . $version;
}

function slugify(string $text): string
{
    $text = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    return $text ?: uniqid('post-');
}

function default_post_image_url(): string
{
    return 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80';
}

function is_demo_post_image(?string $image): bool
{
    return $image === default_post_image_url();
}

function has_custom_post_image(?string $image): bool
{
    return !empty($image) && !is_demo_post_image($image);
}

function normalize_whatsapp_number(?string $number): string
{
    $digits = preg_replace('/\D+/', '', $number ?? '');
    if ($digits === '') {
        return '';
    }

    if (starts_with($digits, '0')) {
        return '62' . substr($digits, 1);
    }

    if (starts_with($digits, '8')) {
        return '62' . $digits;
    }

    return $digits;
}

function whatsapp_link(string $message = ''): string
{
    $number = normalize_whatsapp_number(setting('whatsapp_number', setting('phone')));
    if ($number === '') {
        return '#';
    }

    $url = 'https://wa.me/' . $number;
    if ($message !== '') {
        $url .= '?text=' . rawurlencode($message);
    }

    return $url;
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function current_admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_admin(): void
{
    if (!current_admin()) {
        redirect('admin/login.php');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        flash('danger', 'Sesi tidak valid. Silakan ulangi aksi.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'admin/');
    }
}

function ensure_posts_video_column(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }

    $column = db()->query("SHOW COLUMNS FROM posts LIKE 'video'")->fetch();
    if (!$column) {
        db()->exec('ALTER TABLE posts ADD video VARCHAR(255) NULL AFTER image');
    }

    $checked = true;
}

function ensure_member_registrations_table(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }

    db()->exec("
        CREATE TABLE IF NOT EXISTS member_registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(160) NOT NULL,
            identity_number VARCHAR(80) NULL,
            photo VARCHAR(255) NULL,
            email VARCHAR(160) NOT NULL,
            phone VARCHAR(40) NOT NULL,
            school_name VARCHAR(180) NOT NULL,
            job_title VARCHAR(120) NULL,
            district VARCHAR(120) NULL,
            address TEXT NULL,
            reason TEXT NULL,
            status ENUM('pending','approved','rejected') DEFAULT 'pending',
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $photoColumn = db()->query("SHOW COLUMNS FROM member_registrations LIKE 'photo'")->fetch();
    if (!$photoColumn) {
        db()->exec('ALTER TABLE member_registrations ADD photo VARCHAR(255) NULL AFTER identity_number');
    }

    $checked = true;
}

function school_import_columns(): array
{
    return [
        'name' => 'Nama Sekolah',
        'level' => 'Jenjang',
        'district' => 'Kecamatan',
        'address' => 'Alamat',
        'headmaster' => 'Kepala Sekolah',
        'phone' => 'Telepon',
    ];
}

function normalize_import_header(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '_', $value);
    return trim($value ?? '', '_');
}

function school_import_header_map(): array
{
    return [
        'nama_sekolah' => 'name',
        'name' => 'name',
        'sekolah' => 'name',
        'jenjang' => 'level',
        'level' => 'level',
        'kecamatan' => 'district',
        'district' => 'district',
        'alamat' => 'address',
        'address' => 'address',
        'kepala_sekolah' => 'headmaster',
        'headmaster' => 'headmaster',
        'telepon' => 'phone',
        'phone' => 'phone',
        'no_hp' => 'phone',
        'kontak' => 'phone',
    ];
}

function map_school_import_rows(array $rows): array
{
    if (count($rows) < 2) {
        return [];
    }

    $headers = array_map(function ($header) {
        return normalize_import_header((string)$header);
    }, $rows[0]);
    $headerMap = school_import_header_map();
    $indexes = [];
    foreach ($headers as $index => $header) {
        if (isset($headerMap[$header])) {
            $indexes[$headerMap[$header]] = $index;
        }
    }

    foreach (['name', 'level', 'district', 'address'] as $required) {
        if (!array_key_exists($required, $indexes)) {
            throw new RuntimeException('Template tidak sesuai. Kolom wajib: Nama Sekolah, Jenjang, Kecamatan, Alamat.');
        }
    }

    $mappedRows = [];
    foreach (array_slice($rows, 1) as $row) {
        $data = [];
        foreach (school_import_columns() as $field => $label) {
            $data[$field] = trim((string)($row[$indexes[$field] ?? -1] ?? ''));
        }

        if (implode('', $data) === '') {
            continue;
        }

        if ($data['name'] === '' || $data['level'] === '' || $data['district'] === '' || $data['address'] === '') {
            continue;
        }

        $mappedRows[] = $data;
    }

    return $mappedRows;
}

function read_csv_rows(string $filePath): array
{
    $handle = fopen($filePath, 'r');
    if (!$handle) {
        throw new RuntimeException('Tidak dapat membaca file CSV.');
    }

    $rows = [];
    while (($row = fgetcsv($handle)) !== false) {
        $rows[] = $row;
    }
    fclose($handle);

    return $rows;
}

function read_xlsx_rows(string $filePath): array
{
    if (!class_exists('ZipArchive')) {
        throw new RuntimeException('Server belum mendukung ZipArchive. Simpan template sebagai CSV lalu import ulang.');
    }

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        throw new RuntimeException('File XLSX tidak dapat dibuka.');
    }

    $sharedStrings = [];
    $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($sharedXml !== false) {
        $xml = simplexml_load_string($sharedXml);
        foreach ($xml->si ?? [] as $stringItem) {
            if (isset($stringItem->t)) {
                $sharedStrings[] = (string)$stringItem->t;
                continue;
            }

            $parts = [];
            foreach ($stringItem->r ?? [] as $run) {
                $parts[] = (string)$run->t;
            }
            $sharedStrings[] = implode('', $parts);
        }
    }

    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    $zip->close();
    if ($sheetXml === false) {
        throw new RuntimeException('Sheet pertama tidak ditemukan pada file XLSX.');
    }

    $xml = simplexml_load_string($sheetXml);
    $rows = [];
    foreach ($xml->sheetData->row ?? [] as $rowXml) {
        $row = [];
        foreach ($rowXml->c ?? [] as $cell) {
            $reference = (string)$cell['r'];
            $column = preg_replace('/\d+/', '', $reference);
            $index = 0;
            foreach (str_split($column) as $char) {
                $index = ($index * 26) + (ord($char) - 64);
            }
            $index--;

            $type = (string)$cell['t'];
            $value = isset($cell->v) ? (string)$cell->v : '';
            if ($type === 's') {
                $value = $sharedStrings[(int)$value] ?? '';
            } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                $value = (string)$cell->is->t;
            }

            $row[$index] = $value;
        }

        if ($row) {
            ksort($row);
            $rows[] = array_values($row);
        }
    }

    return $rows;
}

function read_school_import_file(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File import belum dipilih atau gagal diunggah.');
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Ukuran file import maksimal 5MB.');
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if ($extension === 'csv') {
        return map_school_import_rows(read_csv_rows($file['tmp_name']));
    }

    if ($extension === 'xlsx') {
        return map_school_import_rows(read_xlsx_rows($file['tmp_name']));
    }

    throw new RuntimeException('Format file harus CSV atau XLSX.');
}

function detect_upload_mime(string $filePath): string
{
    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($filePath);
        if (is_string($mime) && $mime !== '') {
            return $mime;
        }
    }

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }
    }

    $imageInfo = @getimagesize($filePath);
    return is_array($imageInfo) ? (string)($imageInfo['mime'] ?? '') : '';
}

function create_image_resource(string $filePath, string $mime)
{
    if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
        return imagecreatefromjpeg($filePath);
    }

    if ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
        return imagecreatefrompng($filePath);
    }

    if ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
        return imagecreatefromwebp($filePath);
    }

    return false;
}

function save_compressed_jpeg(string $sourcePath, string $targetPath, string $mime, int $maxBytes): bool
{
    if (!function_exists('imagejpeg') || !function_exists('imagecreatetruecolor') || !function_exists('imagecopyresampled')) {
        throw new RuntimeException('Server belum mendukung kompresi gambar otomatis.');
    }

    $source = create_image_resource($sourcePath, $mime);
    if (!$source) {
        throw new RuntimeException('Gambar tidak dapat diproses untuk kompresi.');
    }

    $width = imagesx($source);
    $height = imagesy($source);
    if ($width < 1 || $height < 1) {
        imagedestroy($source);
        throw new RuntimeException('Ukuran gambar tidak valid.');
    }

    $maxDimension = 1800;
    $quality = 85;

    while ($maxDimension >= 640) {
        $scale = min(1, $maxDimension / max($width, $height));
        $newWidth = max(1, (int)round($width * $scale));
        $newHeight = max(1, (int)round($height * $scale));
        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        for ($currentQuality = $quality; $currentQuality >= 45; $currentQuality -= 10) {
            imagejpeg($canvas, $targetPath, $currentQuality);
            if (is_file($targetPath) && filesize($targetPath) <= $maxBytes) {
                imagedestroy($canvas);
                imagedestroy($source);
                return true;
            }
        }

        imagedestroy($canvas);
        $maxDimension = (int)floor($maxDimension * 0.75);
        $quality = 80;
    }

    imagedestroy($source);
    return false;
}

function upload_image(array $file, string $folder, int $maxBytes = 2097152, ?int $sourceMaxBytes = null): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $maxMegabytes = (int)ceil($maxBytes / 1024 / 1024);
    $sourceLimit = $sourceMaxBytes ?? $maxBytes;
    $sourceMaxMegabytes = (int)ceil($sourceLimit / 1024 / 1024);
    $error = (int)($file['error'] ?? UPLOAD_ERR_OK);
    if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
        throw new RuntimeException('Ukuran file melebihi batas server. Maksimal file awal ' . $sourceMaxMegabytes . 'MB.');
    }

    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal. Silakan pilih ulang file gambar.');
    }

    if (($file['size'] ?? 0) > $sourceLimit) {
        throw new RuntimeException('Ukuran file awal melebihi ' . $sourceMaxMegabytes . 'MB.');
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = detect_upload_mime($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, atau WEBP.');
    }

    $dir = __DIR__ . '/../uploads/' . $folder;
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Folder upload belum bisa dibuat. Periksa izin folder uploads.');
    }

    $mustCompress = ($file['size'] ?? 0) > $maxBytes;
    $extension = $mustCompress ? 'jpg' : $allowed[$mime];
    $name = uniqid($folder . '-', true) . '.' . $extension;
    $target = $dir . '/' . $name;

    if ($mustCompress) {
        if (!save_compressed_jpeg($file['tmp_name'], $target, $mime, $maxBytes)) {
            @unlink($target);
            throw new RuntimeException('Foto belum bisa dikompres sampai maksimal ' . $maxMegabytes . 'MB. Coba pilih foto yang lebih kecil.');
        }

        return '/uploads/' . $folder . '/' . $name;
    }

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Tidak dapat menyimpan file upload.');
    }

    return '/uploads/' . $folder . '/' . $name;
}

function upload_video(array $file, string $folder): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 50 * 1024 * 1024) {
        throw new RuntimeException('Upload gagal atau ukuran video melebihi 50MB.');
    }

    $allowed = ['video/mp4' => 'mp4', 'application/mp4' => 'mp4'];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format video harus MP4.');
    }

    $dir = __DIR__ . '/../uploads/' . $folder;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $name = uniqid($folder . '-', true) . '.' . $allowed[$mime];
    $target = $dir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Tidak dapat menyimpan video upload.');
    }

    return '/uploads/' . $folder . '/' . $name;
}

function upload_document(array $file, string $folder): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Upload gagal atau ukuran dokumen melebihi 5MB.');
    }

    $allowed = [
        'application/pdf' => 'pdf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
    ];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Dokumen laporan harus PDF, XLS, atau XLSX.');
    }

    $dir = __DIR__ . '/../uploads/' . $folder;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $name = uniqid($folder . '-', true) . '.' . $allowed[$mime];
    $target = $dir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Tidak dapat menyimpan dokumen upload.');
    }

    return '/uploads/' . $folder . '/' . $name;
}

function rupiah(float $value): string
{
    return 'Rp ' . number_format($value, 0, ',', '.');
}

function setting(string $key, string $fallback = ''): string
{
    $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
    $stmt->execute([$key]);
    return $stmt->fetchColumn() ?: $fallback;
}

function latest_posts(int $limit = 3): array
{
    $stmt = db()->prepare('SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON categories.id = posts.category_id WHERE posts.status = "published" ORDER BY published_at DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
