<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBarang = (int)($_POST['id_barang'] ?? 0);
    $tglRetur = $_POST['tgl_retur'] ?? date('Y-m-d');
    $alasan = trim($_POST['alasan'] ?? '');

    if ($idBarang <= 0) {
        $error = 'Pilih barang yang diretur.';
    } else {
        try {
            $pdo->beginTransaction();
            $pdo->prepare("INSERT INTO retur (id_barang, tgl_retur, alasan) VALUES (?,?,?)")
                ->execute([$idBarang, $tglRetur, $alasan]);
            $pdo->prepare("UPDATE barang SET stok = stok + 1 WHERE id_barang = ?")
                ->execute([$idBarang]);
            $pdo->commit();
            header('Location: retur.php?sukses=tambah');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}

$barangList = $pdo->query("SELECT id_barang, nama_barang FROM barang WHERE status = 'aktif' ORDER BY nama_barang")->fetchAll();

$pageTitle = 'Catat Retur';
$activeMenu = 'retur';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Catat Retur Barang</h1>
<p class="subtitle"><a href="retur.php">&larr; Kembali ke daftar retur</a></p>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:480px;">
  <form method="post">
    <div class="field">
      <label for="id_barang">Barang</label>
      <select id="id_barang" name="id_barang" required>
        <option value="">-- Pilih Barang --</option>
        <?php foreach ($barangList as $b): ?>
          <option value="<?= (int)$b['id_barang'] ?>"><?= e($b['nama_barang']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="tgl_retur">Tanggal Retur</label>
      <input type="date" id="tgl_retur" name="tgl_retur" value="<?= date('Y-m-d') ?>">
    </div>
    <div class="field">
      <label for="alasan">Alasan Retur</label>
      <textarea id="alasan" name="alasan" rows="3" placeholder="Contoh: kemasan rusak, salah kirim, dll"></textarea>
    </div>
    <p style="font-size:0.82rem; color:var(--muted);">Catatan: menyimpan retur ini akan otomatis menambah stok barang +1 (dianggap barang kembali ke rak).</p>
    <button type="submit" class="btn btn-primary">Simpan Retur</button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
