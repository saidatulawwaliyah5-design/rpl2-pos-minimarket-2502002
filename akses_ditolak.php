<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Akses Ditolak</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-box" style="text-align:center;">
    <h1>⛔ Akses Ditolak</h1>
    <p class="subtitle">Role akun kamu tidak punya izin untuk membuka halaman ini.</p>
    <?php if (isLoggedIn()): ?>
      <a class="btn btn-primary" href="<?= currentRole() === 'admin' ? 'admin/dashboard.php' : 'kasir/pos.php' ?>">Kembali ke Dashboard</a>
    <?php else: ?>
      <a class="btn btn-primary" href="login.php">Login</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
