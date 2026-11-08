<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin', 'kasir']);

$stmt = $pdo->prepare("
    SELECT p.id_penjualan, p.tgl_jual, p.jenis_transaksi, pl.nama_pelanggan,
           SUM(dp.subtotal) AS total
    FROM penjualan p
    LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id_penjualan
    WHERE p.id_karyawan = ?
    GROUP BY p.id_penjualan
    ORDER BY p.tgl_jual DESC
    LIMIT 50
");
$stmt->execute([$_SESSION['id_karyawan']]);
$riwayat = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Transaksi — Lia Belanja Bersama</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
  <div class="sidebar">
    <div class="brand">Lia Belanja Bersama<small>Panel Kasir</small></div>
    <a href="pos.php">Transaksi Baru</a>
    <a href="riwayat.php" class="active">Riwayat Transaksi</a>
    <a href="profil.php">Profil Saya</a>
    <div class="logout">
      <a href="../index.php">Lihat Katalog Publik</a>
      <a href="../logout.php">Logout (<?= e($_SESSION['nama_karyawan']) ?>)</a>
    </div>
  </div>
  <div class="content">

<h1>Riwayat Transaksi Saya</h1>
<p class="subtitle">50 transaksi terakhir yang kamu proses</p>

<table class="data">
  <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Metode</th><th>Total</th><th>Aksi</th></tr>
  <?php foreach ($riwayat as $r): ?>
    <tr>
      <td>#<?= (int)$r['id_penjualan'] ?></td>
      <td><?= tanggalIndo($r['tgl_jual']) ?></td>
      <td><?= e($r['nama_pelanggan'] ?? 'Umum') ?></td>
      <td><span class="badge badge-info"><?= e($r['jenis_transaksi']) ?></span></td>
      <td><?= rupiah($r['total']) ?></td>
      <td><a class="btn btn-outline btn-sm" href="struk.php?id=<?= (int)$r['id_penjualan'] ?>">Lihat Struk</a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (empty($riwayat)): ?>
    <tr><td colspan="6" style="text-align:center; color:var(--muted);">Belum ada transaksi.</td></tr>
  <?php endif; ?>
</table>

  </div>
</div>
</body>
</html>
