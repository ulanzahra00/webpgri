<?php
// Form login admin dengan validasi dan password_verify untuk hash bcrypt.
require_once __DIR__ . '/../includes/functions.php';

if (current_admin()) {
    redirect('admin/');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']];
        $_SESSION['admin_last_activity'] = time();
        unset($_SESSION['csrf_token']);
        redirect('admin/');
    }

    $error = 'Email atau password tidak sesuai.';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - PGRI Kotamobagu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<main class="login-screen px-3">
    <form class="card card-official p-4 p-md-5 bg-white" method="post" style="max-width: 440px; width: 100%;">
        <div class="text-center mb-4">
            <div class="logo-mark mx-auto mb-3"><i class="fa-solid fa-chalkboard-user"></i></div>
            <h3 class="fw-bold">Login Admin</h3>
            <p class="text-muted mb-0">Panel pengelolaan website PGRI Kotamobagu</p>
        </div>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required autofocus></div>
        <div class="mb-4"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
        <button class="btn btn-pgri w-100 py-2"><i class="fa-solid fa-right-to-bracket me-2"></i>Masuk</button>
        <a class="btn btn-outline-primary w-100 mt-3" href="<?= e(url('')) ?>"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Home</a>
    </form>
</main>
</body>
</html>
