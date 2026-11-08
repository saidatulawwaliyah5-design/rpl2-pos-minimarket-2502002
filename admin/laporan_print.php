<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$dariTanggal = $_GET['dari'] ?? date('Y-m-01');
$sampaiTanggal = $_GET['sampai'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT p.id_penjualan, p.tgl_jual, p.jenis_transaksi,
           pl.nama_pelanggan, k.nama_karyawan,
           SUM(dp.qty) AS total_item,
           SUM(dp.subtotal) AS total_bayar
    FROM penjualan p
    LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    LEFT JOIN karyawan k ON p.id_karyawan = k.id_karyawan
    LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id_penjualan
    WHERE DATE(p.tgl_jual) BETWEEN ? AND ?
    GROUP BY p.id_penjualan
    ORDER BY p.tgl_jual ASC
");
$stmt->execute([$dariTanggal, $sampaiTanggal]);
$transaksi = $stmt->fetchAll();
$totalOmzet = array_sum(array_column($transaksi, 'total_bayar'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penjualan <?= e($dariTanggal) ?> s/d <?= e($sampaiTanggal) ?></title>
<style>
  body { font-family: Arial, sans-serif; padding: 30px; color: #1E293B; }
  h1 { color: #21295C; margin-bottom: 2px; }
  .sub { color: #64748B; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; margin-top: 16px; }
  th, td { border: 1px solid #CBD5E1; padding: 7px 10px; font-size: 0.85rem; text-align: left; }
  th { background: #065A82; color: #fff; }
  tfoot td { font-weight: bold; background: #F1F5F9; }
  .no-print { margin-top: 20px; }
  @media print { .no-print { display: none; } }
</style>
</head>
<body>
  <h1>Laporan Penjualan</h1>
  <p class="sub">Lia Belanja Bersama &mdash; Periode <?= tanggalIndoTanggal($dariTanggal) ?> s/d <?= tanggalIndoTanggal($sampaiTanggal) ?></p>

  <table>
    <thead>
      <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Kasir</th><th>Metode</th><th>Item</th><th>Total</th></tr>
    </thead>
    <tbody>
      <?php foreach ($transaksi as $t): ?>
      <tr>
        <td>#<?= (int)$t['id_penjualan'] ?></td>
        <td><?= tanggalIndo($t['tgl_jual']) ?></td>
        <td><?= e($t['nama_pelanggan'] ?? 'Umum') ?></td>
        <td><?= e($t['nama_karyawan'] ?? '-') ?></td>
        <td><?= e($t['jenis_transaksi']) ?></td>
        <td><?= (int)$t['total_item'] ?></td>
        <td><?= rupiah($t['total_bayar']) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($transaksi)): ?>
        <tr><td colspan="7" style="text-align:center;">Tidak ada transaksi pada rentang ini.</td></tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr><td colspan="6">TOTAL OMZET</td><td><?= rupiah($totalOmzet) ?></td></tr>
    </tfoot>
  </table>

  <div class="no-print">
    <button onclick="window.print()">Cetak / Simpan sebagai PDF</button>
  </div>
</body>
</html>
