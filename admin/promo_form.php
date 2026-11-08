<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$error = '';
$data = ['nama_promo' => '', 'diskon_persen' => '', 'id_barang' => ''];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM promo WHERE id_promo = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { header('Location: promo.php'); exit; }
    $data = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_promo'] ?? '');
    $diskon = (int)($_POST['diskon_persen'] ?? 0);
    $barang = (int)($_POST['id_barang'] ?? 0);

    if ($nama === '' || $barang <= 0 || $diskon < 1 || $diskon > 90) {
        $error = 'Pastikan nama promo diisi, barang dipilih, dan diskon antara 1-90%.';
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE promo SET nama_promo=?, diskon_persen=?, id_barang=? WHERE id_promo=?");
            $stmt->execute([$nama, $diskon, $barang, $id]);
            header('Location: promo.php?sukses=ubah');
        } else {
            $stmt = $pdo->prepare("INSERT INTO promo (nama_promo, diskon_persen, id_barang) VALUES (?,?,?)");
            $stmt->execute([$nama, $diskon, $barang]);
            header('Location: promo.php?sukses=tambah');
        }
        exit;
    }
    $data = ['nama_promo' => $nama, 'diskon_persen' => $diskon, 'id_barang' => $barang];
}

$barangList = $pdo->query("SELECT id_barang, nama_barang FROM barang WHERE status = 'aktif' ORDER BY nama_barang")->fetchAll();

$pageTitle = $isEdit ? 'Edit Promo' : 'Tambah Promo';
$activeMenu = 'promo';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1><?= $isEdit ? 'Edit Promo' : 'Tambah Promo Baru' ?></h1>
<p class="subtitle"><a href="promo.php">&larr; Kembali ke daftar promo</a></p>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:480px;">
  <form method="post">
    <div class="field">
      <label for="nama_promo">Nama Promo</label>
      <input type="text" id="nama_promo" name="nama_promo" value="<?= e($data['nama_promo']) ?>" placeholder="Contoh: Diskon Akhir Bulan" required>
    </div>
    <div class="field">
      <label for="id_barang">Barang</label>
      <select id="id_barang" name="id_barang" required>
        <option value="">-- Pilih Barang --</option>
        <?php foreach ($barangList as $b): ?>
          <option value="<?= (int)$b['id_barang'] ?>" <?= (int)$data['id_barang'] === (int)$b['id_barang'] ? 'selected' : '' ?>>
            <?= e($b['nama_barang']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="diskon_persen">Diskon (%)</label>
      <input type="number" id="diskon_persen" name="diskon_persen" min="1" max="90" value="<?= e($data['diskon_persen']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Promo' ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
