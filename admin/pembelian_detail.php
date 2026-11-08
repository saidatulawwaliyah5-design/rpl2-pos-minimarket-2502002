<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT p.*, s.nama_supplier, s.alamat_supplier
    FROM pembelian p LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
    WHERE p.id_pembelian = ?
");
$stmt->execute([$id]);
$pembelian = $stmt->fetch();
if (!$pembelian) { header('Location: pembelian.php'); exit; }

$stmt = $pdo->prepare("
    SELECT dp.*, b.nama_barang
    FROM detail_pembelian dp JOIN barang b ON b.id_barang = dp.id_barang
    WHERE dp.id_pembelian = ?
");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

$pageTitle = 'Detail Pembelian';
$activeMenu = 'pembelian';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Detail Pembelian #<?= (int)$pembelian['id_pembelian'] ?></h1>
<p class="subtitle"><a href="pembelian.php">&larr; Kembali ke daftar pembelian</a></p>

<div class="card" style="max-width:500px;">
  <p><strong>Tanggal:</strong> <?= $pembelian['tgl_beli'] ? tanggalIndoTanggal($pembelian['tgl_beli']) : '-' ?></p>
  <p><strong>Supplier:</strong> <?= e($pembelian['nama_supplier'] ?? '-') ?></p>
  <p><strong>Alamat:</strong> <?= e($pembelian['alamat_supplier'] ?: '-') ?></p>
</div>

<table class="data">
  <tr><th>Barang</th><th>Qty Masuk</th></tr>
  <?php foreach ($items as $it): ?>
    <tr><td><?= e($it['nama_barang']) ?></td><td><?= (int)$it['qty'] ?> pcs</td></tr>
  <?php endforeach; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
