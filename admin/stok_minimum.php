<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

// Aturan inti: stok <= stok_minimum (hanya barang aktif), lihat view v_peringatan_stok
$daftar = $pdo->query("SELECT * FROM v_peringatan_stok ORDER BY selisih ASC, nama_barang ASC")->fetchAll();

$pageTitle = 'Peringatan Stok Minimum';
$activeMenu = 'stok_minimum';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Peringatan Stok Minimum</h1>
<p class="subtitle">Barang aktif yang stoknya sama dengan atau di bawah batas minimum. Keputusan penambahan stok tetap pada pemilik/petugas.</p>

<?php if (empty($daftar)): ?>
  <div class="alert alert-success">Semua stok aman. Tidak ada barang yang mencapai batas minimum.</div>
<?php else: ?>
  <table class="data">
    <tr><th>ID</th><th>Nama Barang</th><th>Jenis</th><th>Stok</th><th>Minimum</th><th>Selisih</th><th>Status</th><th>Aksi</th></tr>
    <?php foreach ($daftar as $d): ?>
      <?php [$lbl, $cls] = statusStok($d['stok'], $d['stok_minimum']); ?>
      <tr>
        <td><?= (int)$d['id_barang'] ?></td>
        <td><?= e($d['nama_barang']) ?></td>
        <td><span class="badge badge-info"><?= e($d['nama_jenis'] ?? '-') ?></span></td>
        <td><?= (int)$d['stok'] ?></td>
        <td><?= (int)$d['stok_minimum'] ?></td>
        <td><?= (int)$d['selisih'] ?></td>
        <td><span class="badge <?= $cls ?>"><?= e($lbl) ?></span></td>
        <td><a class="btn btn-outline btn-sm" href="barang_form.php?id=<?= (int)$d['id_barang'] ?>">Atur</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p style="color:var(--muted); font-size:0.85rem;">Total: <?= count($daftar) ?> barang perlu diperiksa. Tambah stok lewat menu Pembelian atau ubah stok di form barang.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
