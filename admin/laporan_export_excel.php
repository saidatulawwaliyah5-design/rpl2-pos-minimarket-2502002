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

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_penjualan_' . $dariTanggal . '_sd_' . $sampaiTanggal . '.xls');
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<!--[if gte mso 9]>
<xml>
<x:ExcelWorkbook>
  <x:ExcelWorksheets>
    <x:ExcelWorksheet>
      <x:Name>Laporan Penjualan</x:Name>
      <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
    </x:ExcelWorksheet>
  </x:ExcelWorksheets>
</x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 12px; }
  .judul { font-size: 16px; font-weight: bold; color: #21295C; }
  .sub { font-size: 11px; color: #64748B; }
  th { background: #065A82; color: #ffffff; font-weight: bold; padding: 6px 10px; border: 1px solid #0B4F6C; text-align: left; }
  td { padding: 5px 10px; border: 1px solid #CBD5E1; }
  .num { mso-number-format:"#,##0"; text-align: right; }
  .total-row td { font-weight: bold; background: #F1F5F9; }
</style>
</head>
<body>
<table>
  <tr><td class="judul" colspan="7">Laporan Penjualan &mdash; Lia Belanja Bersama</td></tr>
  <tr><td class="sub" colspan="7">Periode: <?= e($dariTanggal) ?> s/d <?= e($sampaiTanggal) ?></td></tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <th>No Transaksi</th>
    <th>Tanggal</th>
    <th>Pelanggan</th>
    <th>Kasir</th>
    <th>Metode Bayar</th>
    <th>Total Item</th>
    <th>Total Bayar (Rp)</th>
  </tr>
  <?php foreach ($transaksi as $t): ?>
  <tr>
    <td>#<?= (int)$t['id_penjualan'] ?></td>
    <td><?= e($t['tgl_jual']) ?></td>
    <td><?= e($t['nama_pelanggan'] ?? 'Umum') ?></td>
    <td><?= e($t['nama_karyawan'] ?? '-') ?></td>
    <td><?= e($t['jenis_transaksi']) ?></td>
    <td class="num"><?= (int)$t['total_item'] ?></td>
    <td class="num"><?= number_format((float)$t['total_bayar'], 0, ',', '.') ?></td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($transaksi)): ?>
    <tr><td colspan="7">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
  <?php endif; ?>
  <tr class="total-row">
    <td colspan="6" style="text-align:right;">TOTAL OMZET</td>
    <td class="num"><?= number_format($totalOmzet, 0, ',', '.') ?></td>
  </tr>
</table>
</body>
</html>
