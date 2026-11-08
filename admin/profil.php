<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = '';
$error = '';

$stmt = $pdo->prepare("SELECT * FROM karyawan WHERE id_karyawan = ?");
$stmt->execute([$_SESSION['id_karyawan']]);
$profil = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBaru = trim($_POST['nama_karyawan'] ?? '');
    $passwordLama = $_POST['password_lama'] ?? '';
    $passwordBaru = $_POST['password_baru'] ?? '';
    $passwordUlangi = $_POST['password_ulangi'] ?? '';

    if ($namaBaru === '') {
        $error = 'Nama tidak boleh kosong.';
    } else {
        $pdo->prepare("UPDATE karyawan SET nama_karyawan = ? WHERE id_karyawan = ?")
            ->execute([$namaBaru, $_SESSION['id_karyawan']]);
        $_SESSION['nama_karyawan'] = $namaBaru;
        $sukses = 'Nama berhasil diperbarui.';
        $profil['nama_karyawan'] = $namaBaru;

        if ($passwordBaru !== '' || $passwordLama !== '') {
            if (!password_verify($passwordLama, $profil['password'])) {
                $error = 'Password lama salah, password tidak diubah.';
            } elseif (strlen($passwordBaru) < 6) {
                $error = 'Password baru minimal 6 karakter.';
            } elseif ($passwordBaru !== $passwordUlangi) {
                $error = 'Konfirmasi password baru tidak cocok.';
            } else {
                $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE karyawan SET password = ? WHERE id_karyawan = ?")
                    ->execute([$hash, $_SESSION['id_karyawan']]);
                $sukses = 'Nama & password berhasil diperbarui.';
            }
        }
    }
}

$pageTitle = 'Profil Saya';
$activeMenu = 'profil';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Profil Saya</h1>
<p class="subtitle">Ganti nama tampilan dan password akun kamu</p>

<?php if ($sukses): ?><div class="alert alert-success"><?= e($sukses) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:440px;">
  <form method="post">
    <div class="field">
      <label>Username</label>
      <input type="text" value="<?= e($profil['username']) ?>" disabled style="background:#F1F5F9;">
    </div>
    <div class="field">
      <label for="nama_karyawan">Nama Tampilan</label>
      <input type="text" id="nama_karyawan" name="nama_karyawan" value="<?= e($profil['nama_karyawan']) ?>" required>
    </div>
    <hr style="border:none; border-top:1px solid #E2E8F0; margin:18px 0;">
    <p style="font-size:0.85rem; color:var(--muted); margin-top:0;">Kosongkan bagian ini kalau tidak mau ganti password.</p>
    <div class="field">
      <label for="password_lama">Password Lama</label>
      <input type="password" id="password_lama" name="password_lama">
    </div>
    <div class="field">
      <label for="password_baru">Password Baru</label>
      <input type="password" id="password_baru" name="password_baru">
    </div>
    <div class="field">
      <label for="password_ulangi">Ulangi Password Baru</label>
      <input type="password" id="password_ulangi" name="password_ulangi">
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
