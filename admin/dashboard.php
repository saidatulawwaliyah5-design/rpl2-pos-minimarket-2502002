<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$totalBarang     = $pdo->query("SELECT COUNT(*) FROM barang")->fetchColumn();
$stokMenipis     = jumlahStokMenipis($pdo);
$totalPelanggan  = $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$totalPromo      = $pdo->query("SELECT COUNT(*) FROM promo")->fetchColumn();
$omzetHariIni    = $pdo->query("
    SELECT COALESCE(SUM(dp.subtotal),0)
    FROM penjualan p
    JOIN detail_penjualan dp ON dp.id_penjualan = p.id_penjualan
    WHERE DATE(p.tgl_jual) = CURDATE()
")->fetchColumn();
$transaksiHariIni = $pdo->query("SELECT COUNT(*) FROM penjualan WHERE DATE(tgl_jual) = CURDATE()")->fetchColumn();

$barangMenipis = $pdo->query("SELECT nama_barang, stok, stok_minimum, status_peringatan FROM v_peringatan_stok ORDER BY selisih ASC, nama_barang ASC LIMIT 5")->fetchAll();

$kasirAktif = $pdo->query("
    SELECT k.nama_karyawan, COUNT(p.id_penjualan) AS jumlah_transaksi,
           SUM(dp.subtotal) AS total_omzet, MAX(p.tgl_jual) AS terakhir
    FROM penjualan p
    JOIN karyawan k ON k.id_karyawan = p.id_karyawan
    LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id_penjualan
    WHERE DATE(p.tgl_jual) = CURDATE()
    GROUP BY p.id_karyawan
    ORDER BY jumlah_transaksi DESC
")->fetchAll();

$pageTitle = 'Dashboard';
$activeMenu = 'dashboard';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Dashboard</h1>
<p class="subtitle">Ringkasan kondisi toko hari ini, <?= date('d M Y') ?></p>

<div class="stat-grid">
  <div class="stat-card"><div class="num"><?= (int)$totalBarang ?></div><div class="label">Total Barang</div></div>
  <div class="stat-card" style="border-color:#DC2626;"><div class="num"><?= (int)$stokMenipis ?></div><div class="label">Perlu Restok (&le; minimum)</div></div>
  <div class="stat-card"><div class="num"><?= (int)$totalPelanggan ?></div><div class="label">Total Pelanggan</div></div>
  <div class="stat-card"><div class="num"><?= (int)$totalPromo ?></div><div class="label">Promo Aktif</div></div>
  <div class="stat-card"><div class="num"><?= (int)$transaksiHariIni ?></div><div class="label">Transaksi Hari Ini</div></div>
  <div class="stat-card"><div class="num"><?= rupiah($omzetHariIni) ?></div><div class="label">Omzet Hari Ini</div></div>
</div>

<div class="card">
  <h3 style="margin-top:0;">Kasir Aktif Hari Ini</h3>
  <?php if (empty($kasirAktif)): ?>
    <p style="color:var(--muted);">Belum ada transaksi hari ini.</p>
  <?php else: ?>
    <table class="data">
      <tr><th>Nama Kasir</th><th>Jumlah Transaksi</th><th>Total Omzet</th><th>Transaksi Terakhir</th></tr>
      <?php foreach ($kasirAktif as $k): ?>
        <tr>
          <td><?= e($k['nama_karyawan']) ?> <span class="badge badge-ok" style="margin-left:6px;">Aktif</span></td>
          <td><?= (int)$k['jumlah_transaksi'] ?></td>
          <td><?= rupiah($k['total_omzet']) ?></td>
          <td><?= tanggalIndo($k['terakhir']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>

<div class="card">
  <h3 style="margin-top:0;">⚠️ Barang Perlu Restock</h3>
  <?php if (empty($barangMenipis)): ?>
    <p style="color:var(--muted);">Semua stok aman.</p>
  <?php else: ?>
    <table class="data">
      <tr><th>Nama Barang</th><th>Sisa Stok</th><th>Minimum</th><th>Status</th></tr>
      <?php foreach ($barangMenipis as $b): ?>
        <tr><td><?= e($b['nama_barang']) ?></td><td><?= (int)$b['stok'] ?></td><td><?= (int)$b['stok_minimum'] ?></td>
          <?php [$lbl,$cls] = statusStok($b['stok'], $b['stok_minimum']); ?>
          <td><span class="badge <?= $cls ?>"><?= e($lbl) ?></span></td></tr>
      <?php endforeach; ?>
    </table>
    <p style="margin:10px 0 0;"><a href="stok_minimum.php">Lihat semua peringatan &rarr;</a></p>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
