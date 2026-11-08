<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    header('Location: ' . (currentRole() === 'admin' ? 'admin/dashboard.php' : 'kasir/pos.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM karyawan WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] && password_verify($password, $user['password'])) {
        $_SESSION['id_karyawan']   = $user['id_karyawan'];
        $_SESSION['nama_karyawan'] = $user['nama_karyawan'];
        $_SESSION['jabatan']       = $user['jabatan'];

        header('Location: ' . ($user['jabatan'] === 'admin' ? 'admin/dashboard.php' : 'kasir/pos.php'));
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login — Lia Belanja Bersama</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <h1>Lia Belanja Bersama</h1>
    <p class="subtitle">Masuk untuk mengakses panel admin/kasir</p>
    <?php if ($error): ?>
      <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
    </form>
    <p style="text-align:center; margin-top:18px; font-size:0.85rem;">
      <a href="index.php">&larr; Kembali ke katalog</a>
    </p>
  </div>
</div>
</body>
</html>
