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
    ORDER BY p.tgl_jual DESC
");
$stmt->execute([$dariTanggal, $sampaiTanggal]);
$transaksi = $stmt->fetchAll();

$totalOmzet = array_sum(array_column($transaksi, 'total_bayar'));
$totalTransaksi = count($transaksi);

// Produk terlaris di rentang tanggal ini
$stmtTerlaris = $pdo->prepare("
    SELECT b.nama_barang, SUM(dp.qty) AS total_terjual
    FROM detail_penjualan dp
    JOIN penjualan p ON p.id_penjualan = dp.id_penjualan
    JOIN barang b ON b.id_barang = dp.id_barang
    WHERE DATE(p.tgl_jual) BETWEEN ? AND ?
    GROUP BY dp.id_barang
    ORDER BY total_terjual DESC
    LIMIT 5
");
$stmtTerlaris->execute([$dariTanggal, $sampaiTanggal]);
$produkTerlaris = $stmtTerlaris->fetchAll();

// Omzet per hari (untuk grafik)
$stmtHarian = $pdo->prepare("
    SELECT DATE(p.tgl_jual) AS tgl, SUM(dp.subtotal) AS omzet
    FROM penjualan p
    JOIN detail_penjualan dp ON dp.id_penjualan = p.id_penjualan
    WHERE DATE(p.tgl_jual) BETWEEN ? AND ?
    GROUP BY DATE(p.tgl_jual)
    ORDER BY tgl ASC
");
$stmtHarian->execute([$dariTanggal, $sampaiTanggal]);
$omzetHarian = $stmtHarian->fetchAll();
$chartLabels = array_map(fn($r) => date('d M', strtotime($r['tgl'])), $omzetHarian);
$chartValues = array_map(fn($r) => (float)$r['omzet'], $omzetHarian);

$pageTitle = 'Laporan Penjualan';
$activeMenu = 'laporan';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Laporan Penjualan</h1>
<p class="subtitle">Pantau performa penjualan berdasarkan rentang tanggal</p>

<div class="card">
  <form method="get" style="display:flex; gap:14px; align-items:end; flex-wrap:wrap;">
    <div class="field" style="margin:0;">
      <label for="dari">Dari Tanggal</label>
      <input type="date" id="dari" name="dari" value="<?= e($dariTanggal) ?>">
    </div>
    <div class="field" style="margin:0;">
      <label for="sampai">Sampai Tanggal</label>
      <input type="date" id="sampai" name="sampai" value="<?= e($sampaiTanggal) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a class="btn btn-outline" target="_blank"
       href="laporan_export_excel.php?dari=<?= e($dariTanggal) ?>&sampai=<?= e($sampaiTanggal) ?>">Export Excel</a>
    <a class="btn btn-outline" target="_blank"
       href="laporan_print.php?dari=<?= e($dariTanggal) ?>&sampai=<?= e($sampaiTanggal) ?>">Cetak / PDF</a>
  </form>
</div>

<div class="stat-grid">
  <div class="stat-card"><div class="num"><?= (int)$totalTransaksi ?></div><div class="label">Total Transaksi</div></div>
  <div class="stat-card"><div class="num"><?= rupiah($totalOmzet) ?></div><div class="label">Total Omzet</div></div>
  <div class="stat-card"><div class="num"><?= rupiah($totalTransaksi ? $totalOmzet / $totalTransaksi : 0) ?></div><div class="label">Rata-rata / Transaksi</div></div>
</div>

<div class="card">
  <h3 style="margin-top:0;">Grafik Omzet Harian</h3>
  <?php if (empty($omzetHarian)): ?>
    <p style="color:var(--muted);">Belum ada penjualan pada rentang ini.</p>
  <?php else: ?>
    <canvas id="chartOmzet" height="90"></canvas>
  <?php endif; ?>
</div>

<div class="card">
  <h3 style="margin-top:0;">Produk Terlaris</h3>
  <?php if (empty($produkTerlaris)): ?>
    <p style="color:var(--muted);">Belum ada penjualan pada rentang ini.</p>
  <?php else: ?>
    <table class="data">
      <tr><th>Nama Barang</th><th>Total Terjual</th></tr>
      <?php foreach ($produkTerlaris as $pt): ?>
        <tr><td><?= e($pt['nama_barang']) ?></td><td><span class="badge badge-ok"><?= (int)$pt['total_terjual'] ?> pcs</span></td></tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>

<div class="card">
  <h3 style="margin-top:0;">Detail Transaksi</h3>
  <table class="data">
    <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Kasir</th><th>Jenis Bayar</th><th>Item</th><th>Total</th></tr>
    <?php foreach ($transaksi as $t): ?>
    <tr>
      <td>#<?= (int)$t['id_penjualan'] ?></td>
      <td><?= tanggalIndo($t['tgl_jual']) ?></td>
      <td><?= e($t['nama_pelanggan'] ?? 'Umum') ?></td>
      <td><?= e($t['nama_karyawan'] ?? '-') ?></td>
      <td><span class="badge badge-info"><?= e($t['jenis_transaksi']) ?></span></td>
      <td><?= (int)$t['total_item'] ?> pcs</td>
      <td><strong><?= rupiah($t['total_bayar']) ?></strong></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($transaksi)): ?>
      <tr><td colspan="7" style="text-align:center; color:var(--muted);">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
    <?php endif; ?>
  </table>
</div>

<?php if (!empty($omzetHarian)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const ctx = document.getElementById('chartOmzet').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($chartLabels) ?>,
      datasets: [{
        label: 'Omzet (Rp)',
        data: <?= json_encode($chartValues) ?>,
        backgroundColor: '#1C7293',
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
          }
        }
      }
    }
  });
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
