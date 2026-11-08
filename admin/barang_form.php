<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$error = '';

$data = ['nama_barang' => '', 'id_jenis' => '', 'stok' => '', 'stok_minimum' => 10, 'harga_jual' => '', 'status' => 'aktif'];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE id_barang = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { header('Location: barang.php'); exit; }
    $data = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_barang'] ?? '');
    $jenis  = (int)($_POST['id_jenis'] ?? 0);
    $stok   = (int)($_POST['stok'] ?? 0);
    $stokMin = (int)($_POST['stok_minimum'] ?? 0);
    $harga  = (float)($_POST['harga_jual'] ?? 0);
    $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';

    if ($nama === '' || $jenis <= 0 || $harga < 0 || $stok < 0 || $stokMin < 0) {
        $error = 'Semua field wajib diisi dengan benar.';
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE barang SET nama_barang=?, id_jenis=?, stok=?, stok_minimum=?, harga_jual=?, status=? WHERE id_barang=?");
            $stmt->execute([$nama, $jenis, $stok, $stokMin, $harga, $status, $id]);
            header('Location: barang.php?sukses=ubah');
        } else {
            $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, id_jenis, stok, stok_minimum, harga_jual) VALUES (?,?,?,?,?)");
            $stmt->execute([$nama, $jenis, $stok, $stokMin, $harga]);
            header('Location: barang.php?sukses=tambah');
        }
        exit;
    }
    $data = ['nama_barang' => $nama, 'id_jenis' => $jenis, 'stok' => $stok, 'stok_minimum' => $stokMin, 'harga_jual' => $harga, 'status' => $status];
}

$jenisList = $pdo->query("SELECT * FROM jenis ORDER BY nama_jenis")->fetchAll();

$pageTitle = $isEdit ? 'Edit Barang' : 'Tambah Barang';
$activeMenu = 'barang';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1><?= $isEdit ? 'Edit Barang' : 'Tambah Barang Baru' ?></h1>
<p class="subtitle"><a href="barang.php">&larr; Kembali ke daftar barang</a></p>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:480px;">
  <form method="post">
    <div class="field">
      <label for="nama_barang">Nama Barang</label>
      <input type="text" id="nama_barang" name="nama_barang" value="<?= e($data['nama_barang']) ?>" required>
    </div>
    <div class="field">
      <label for="id_jenis">Jenis Barang</label>
      <select id="id_jenis" name="id_jenis" required>
        <option value="">-- Pilih Jenis --</option>
        <?php foreach ($jenisList as $j): ?>
          <option value="<?= (int)$j['id_jenis'] ?>" <?= (int)$data['id_jenis'] === (int)$j['id_jenis'] ? 'selected' : '' ?>>
            <?= e($j['nama_jenis']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="stok">Stok</label>
      <input type="number" id="stok" name="stok" min="0" value="<?= e($data['stok']) ?>" required>
    </div>
    <div class="field">
      <label for="stok_minimum">Stok Minimum (batas peringatan)</label>
      <input type="number" id="stok_minimum" name="stok_minimum" min="0" value="<?= e($data['stok_minimum']) ?>" required>
      <small style="color:var(--muted);">Peringatan muncul jika stok sama dengan atau di bawah angka ini.</small>
    </div>
    <div class="field">
      <label for="harga_jual">Harga Jual (Rp)</label>
      <input type="number" id="harga_jual" name="harga_jual" min="0" step="0.01" value="<?= e($data['harga_jual']) ?>" required>
    </div>
    <?php if ($isEdit): ?>
    <div class="field">
      <label for="status">Status Barang</label>
      <select id="status" name="status">
        <option value="aktif"    <?= ($data['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif (dijual)</option>
        <option value="nonaktif" <?= ($data['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif (tidak dijual)</option>
      </select>
    </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Barang' ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
