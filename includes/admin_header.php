<?php
/**
 * Dipanggil dari dalam folder admin/.
 * Wajib set $pageTitle dan $activeMenu sebelum include file ini.
 */
$menu = [
    'dashboard'  => ['label' => 'Dashboard',          'link' => 'dashboard.php'],
    'barang'     => ['label' => 'Barang & Stok',       'link' => 'barang.php'],
    'stok_minimum' => ['label' => 'Peringatan Stok',   'link' => 'stok_minimum.php'],
    'promo'      => ['label' => 'Promo & Diskon',      'link' => 'promo.php'],
    'pelanggan'  => ['label' => 'Pelanggan & Member',  'link' => 'pelanggan.php'],
    'supplier'   => ['label' => 'Supplier',            'link' => 'supplier.php'],
    'pembelian'  => ['label' => 'Pembelian',           'link' => 'pembelian.php'],
    'retur'      => ['label' => 'Retur Barang',        'link' => 'retur.php'],
    'laporan'    => ['label' => 'Laporan Penjualan',   'link' => 'laporan.php'],
    'profil'     => ['label' => 'Profil Saya',         'link' => 'profil.php'],
];

$jumlahStokMenipis = jumlahStokMenipis($pdo);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= e($pageTitle ?? 'Admin') ?> — Lia Belanja Bersama</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
  <div class="sidebar">
    <div class="brand">Lia Belanja Bersama<small>Panel Admin</small></div>
    <?php foreach ($menu as $key => $item): ?>
      <a href="<?= $item['link'] ?>" class="<?= ($activeMenu ?? '') === $key ? 'active' : '' ?>" style="display:flex; justify-content:space-between; align-items:center;">
        <span><?= e($item['label']) ?></span>
        <?php if ($key === 'stok_minimum' && $jumlahStokMenipis > 0): ?>
          <span class="nav-badge"><?= $jumlahStokMenipis ?></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
    <div class="logout">
      <a href="../index.php">Lihat Katalog Publik</a>
      <a href="../logout.php">Logout (<?= e($_SESSION['nama_karyawan']) ?>)</a>
    </div>
  </div>
  <div class="content">
    <?php if ($jumlahStokMenipis > 0 && ($activeMenu ?? '') !== 'stok_minimum'): ?>
      <div class="alert alert-warning">
        Ada <strong><?= $jumlahStokMenipis ?> barang</strong> yang stoknya sama dengan atau di bawah batas minimum.
        <a href="<?= (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? '' : 'admin/') ?>stok_minimum.php">Cek sekarang &rarr;</a>
      </div>
    <?php endif; ?>
