<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin', 'kasir']);

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT p.*, pl.nama_pelanggan, k.nama_karyawan
    FROM penjualan p
    LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    LEFT JOIN karyawan k ON p.id_karyawan = k.id_karyawan
    WHERE p.id_penjualan = ?
");
$stmt->execute([$id]);
$transaksi = $stmt->fetch();
if (!$transaksi) { header('Location: pos.php'); exit; }

$stmt = $pdo->prepare("
    SELECT dp.*, b.nama_barang
    FROM detail_penjualan dp
    JOIN barang b ON b.id_barang = dp.id_barang
    WHERE dp.id_penjualan = ?
");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
$total = array_sum(array_column($items, 'subtotal'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk #<?= (int)$id ?></title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
  .struk { max-width: 340px; margin: 30px auto; background:#fff; border:1px dashed #94A3B8; border-radius:10px; padding:20px; font-family: "Courier New", monospace; }
  .struk h2 { text-align:center; margin:0 0 2px; font-size:1.05rem; }
  .struk .center { text-align:center; font-size:0.78rem; color:#64748B; margin-bottom:12px; }
  .struk hr { border:none; border-top:1px dashed #CBD5E1; margin:10px 0; }
  .struk table { width:100%; font-size:0.85rem; border-collapse:collapse; }
  .struk td { padding:3px 0; }
  .struk .total-row { font-weight:700; font-size:1rem; }
  @media print { .no-print { display:none; } }
</style>
</head>
<body>
<div class="struk">
  <h2>Lia Belanja Bersama</h2>
  <div class="center">Struk Transaksi #<?= (int)$transaksi['id_penjualan'] ?></div>
  <div style="font-size:0.8rem;">
    Tanggal: <?= tanggalIndo($transaksi['tgl_jual']) ?><br>
    Kasir: <?= e($transaksi['nama_karyawan'] ?? '-') ?><br>
    Pelanggan: <?= e($transaksi['nama_pelanggan'] ?? 'Umum') ?><br>
    Bayar: <?= e($transaksi['jenis_transaksi']) ?>
  </div>
  <hr>
  <table>
    <?php foreach ($items as $it): ?>
      <tr>
        <td colspan="2"><?= e($it['nama_barang']) ?></td>
      </tr>
      <tr>
        <td><?= (int)$it['qty'] ?> x <?= rupiah($it['subtotal'] / max(1,$it['qty'])) ?></td>
        <td style="text-align:right;"><?= rupiah($it['subtotal']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <hr>
  <table>
    <tr class="total-row"><td>TOTAL</td><td style="text-align:right;"><?= rupiah($total) ?></td></tr>
  </table>
  <hr>
  <div class="center">Terima kasih telah berbelanja!</div>
</div>

<?php
$flashStok = $_SESSION['flash_stok'] ?? [];
unset($_SESSION['flash_stok']);
if (!empty($flashStok)): ?>
<div class="no-print alert alert-warning" style="max-width:340px; margin:14px auto 0;">
  <strong>Peringatan stok minimum:</strong>
  <?php foreach ($flashStok as $f): ?>
    <div><?= e($f['nama_barang']) ?> &mdash; sisa <?= (int)$f['stok'] ?> (minimum <?= (int)$f['stok_minimum'] ?>), <?= e($f['status_peringatan']) ?></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="no-print" style="text-align:center; margin-top:14px;">
  <button onclick="window.print()" class="btn btn-primary">Cetak Struk</button>
  <a href="pos.php" class="btn btn-outline">Transaksi Baru</a>
</div>
</body>
</html>
