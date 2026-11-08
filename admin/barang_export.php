<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$stmt = $pdo->query("
    SELECT b.id_barang, b.nama_barang, j.nama_jenis, b.stok, b.stok_minimum, b.status, b.harga_jual
    FROM barang b LEFT JOIN jenis j ON b.id_jenis = j.id_jenis
    ORDER BY b.nama_barang ASC
");
$daftarBarang = $stmt->fetchAll();

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename=data_barang_' . date('Y-m-d') . '.xls');
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<!--[if gte mso 9]>
<xml>
<x:ExcelWorkbook>
  <x:ExcelWorksheets>
    <x:ExcelWorksheet>
      <x:Name>Data Barang</x:Name>
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
  .low { background: #FEE2E2; color: #991B1B; font-weight: bold; }
</style>
</head>
<body>
<table>
  <tr><td class="judul" colspan="7">Data Barang &mdash; Lia Belanja Bersama</td></tr>
  <tr><td class="sub" colspan="7">Diekspor: <?= date('d M Y H:i') ?></td></tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <th>ID Barang</th>
    <th>Nama Barang</th>
    <th>Jenis</th>
    <th>Stok</th>
    <th>Stok Minimum</th>
    <th>Status Stok</th>
    <th>Harga Jual (Rp)</th>
  </tr>
  <?php foreach ($daftarBarang as $b): ?>
  <tr>
    <td><?= (int)$b['id_barang'] ?></td>
    <td><?= e($b['nama_barang']) ?></td>
    <td><?= e($b['nama_jenis'] ?? '-') ?></td>
    <?php [$lbl,$cls] = statusStok($b['stok'], $b['stok_minimum']); ?>
    <td class="num<?= (int)$b['stok'] <= (int)$b['stok_minimum'] ? ' low' : '' ?>"><?= (int)$b['stok'] ?></td>
    <td class="num"><?= (int)$b['stok_minimum'] ?></td>
    <td><?= e($lbl) ?><?= $b['status'] === 'nonaktif' ? ' (barang nonaktif)' : '' ?></td>
    <td class="num"><?= number_format((float)$b['harga_jual'], 0, ',', '.') ?></td>
  </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
