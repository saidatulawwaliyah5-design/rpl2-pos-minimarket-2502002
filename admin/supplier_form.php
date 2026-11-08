<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$error = '';
$data = ['nama_supplier' => '', 'alamat_supplier' => ''];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM supplier WHERE id_supplier = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { header('Location: supplier.php'); exit; }
    $data = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_supplier'] ?? '');
    $alamat = trim($_POST['alamat_supplier'] ?? '');

    if ($nama === '') {
        $error = 'Nama supplier wajib diisi.';
    } else {
        if ($isEdit) {
            $pdo->prepare("UPDATE supplier SET nama_supplier=?, alamat_supplier=? WHERE id_supplier=?")
                ->execute([$nama, $alamat, $id]);
            header('Location: supplier.php?sukses=ubah');
        } else {
            $pdo->prepare("INSERT INTO supplier (nama_supplier, alamat_supplier) VALUES (?,?)")
                ->execute([$nama, $alamat]);
            header('Location: supplier.php?sukses=tambah');
        }
        exit;
    }
    $data = ['nama_supplier' => $nama, 'alamat_supplier' => $alamat];
}

$pageTitle = $isEdit ? 'Edit Supplier' : 'Tambah Supplier';
$activeMenu = 'supplier';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1><?= $isEdit ? 'Edit Supplier' : 'Tambah Supplier Baru' ?></h1>
<p class="subtitle"><a href="supplier.php">&larr; Kembali ke daftar supplier</a></p>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:480px;">
  <form method="post">
    <div class="field">
      <label for="nama_supplier">Nama Supplier</label>
      <input type="text" id="nama_supplier" name="nama_supplier" value="<?= e($data['nama_supplier']) ?>" required>
    </div>
    <div class="field">
      <label for="alamat_supplier">Alamat</label>
      <textarea id="alamat_supplier" name="alamat_supplier" rows="3"><?= e($data['alamat_supplier']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Supplier' ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
