<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';

$daftar = $pdo->query("
    SELECT p.id_pembelian, p.tgl_beli, s.nama_supplier,
           SUM(dp.qty) AS total_item
    FROM pembelian p
    LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
    LEFT JOIN detail_pembelian dp ON dp.id_pembelian = p.id_pembelian
    GROUP BY p.id_pembelian
    ORDER BY p.tgl_beli DESC, p.id_pembelian DESC
")->fetchAll();

$pageTitle = 'Pembelian dari Supplier';
$activeMenu = 'pembelian';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Pembelian dari Supplier</h1>
<p class="subtitle">Catat barang masuk dari supplier — stok otomatis bertambah</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Pembelian berhasil dicatat, stok barang sudah diperbarui.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Data pembelian berhasil dihapus.</div><?php endif; ?>

<a href="pembelian_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Catat Pembelian Baru</a>

<table class="data">
  <tr><th>No</th><th>Tanggal</th><th>Supplier</th><th>Total Item</th><th>Aksi</th></tr>
  <?php foreach ($daftar as $p): ?>
  <tr>
    <td>#<?= (int)$p['id_pembelian'] ?></td>
    <td><?= $p['tgl_beli'] ? tanggalIndoTanggal($p['tgl_beli']) : '-' ?></td>
    <td><?= e($p['nama_supplier'] ?? '(supplier dihapus)') ?></td>
    <td><?= (int)$p['total_item'] ?> pcs</td>
    <td>
      <a class="btn btn-outline btn-sm" href="pembelian_detail.php?id=<?= (int)$p['id_pembelian'] ?>">Lihat Detail</a>
      <a class="btn btn-danger btn-sm" href="pembelian_delete.php?id=<?= (int)$p['id_pembelian'] ?>"
         onclick="return confirm('Hapus data pembelian ini? Stok yang sudah masuk TIDAK otomatis dikurangi lagi.');">Hapus</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?>
    <tr><td colspan="5" style="text-align:center; color:var(--muted);">Belum ada data pembelian.</td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
