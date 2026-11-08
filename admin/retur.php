<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';

$daftar = $pdo->query("
    SELECT r.*, b.nama_barang
    FROM retur r LEFT JOIN barang b ON r.id_barang = b.id_barang
    ORDER BY r.tgl_retur DESC, r.id_retur DESC
")->fetchAll();

$pageTitle = 'Retur Barang';
$activeMenu = 'retur';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Retur Barang</h1>
<p class="subtitle">Catat barang yang dikembalikan pelanggan — stok otomatis bertambah lagi</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Retur berhasil dicatat, stok barang sudah dikembalikan.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Data retur berhasil dihapus.</div><?php endif; ?>

<a href="retur_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Catat Retur Baru</a>

<table class="data">
  <tr><th>Tanggal</th><th>Barang</th><th>Alasan</th><th>Aksi</th></tr>
  <?php foreach ($daftar as $r): ?>
  <tr>
    <td><?= $r['tgl_retur'] ? tanggalIndoTanggal($r['tgl_retur']) : '-' ?></td>
    <td><?= e($r['nama_barang'] ?? '(barang dihapus)') ?></td>
    <td><?= e($r['alasan'] ?: '-') ?></td>
    <td>
      <a class="btn btn-danger btn-sm" href="retur_delete.php?id=<?= (int)$r['id_retur'] ?>"
         onclick="return confirm('Hapus catatan retur ini? Stok TIDAK otomatis dikurangi lagi.');">Hapus</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?>
    <tr><td colspan="4" style="text-align:center; color:var(--muted);">Belum ada data retur.</td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
