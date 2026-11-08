<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';
$daftar = $pdo->query("SELECT * FROM supplier ORDER BY nama_supplier ASC")->fetchAll();

$pageTitle = 'Supplier';
$activeMenu = 'supplier';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Supplier</h1>
<p class="subtitle">Kelola data pemasok barang</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Supplier berhasil ditambahkan.</div><?php endif; ?>
<?php if ($sukses === 'ubah'): ?><div class="alert alert-success">Data supplier berhasil diperbarui.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Supplier berhasil dihapus.</div><?php endif; ?>

<a href="supplier_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Tambah Supplier</a>

<table class="data">
  <tr><th>Nama Supplier</th><th>Alamat</th><th>Aksi</th></tr>
  <?php foreach ($daftar as $s): ?>
  <tr>
    <td><?= e($s['nama_supplier']) ?></td>
    <td><?= e($s['alamat_supplier'] ?: '-') ?></td>
    <td>
      <a class="btn btn-outline btn-sm" href="supplier_form.php?id=<?= (int)$s['id_supplier'] ?>">Edit</a>
      <a class="btn btn-danger btn-sm" href="supplier_delete.php?id=<?= (int)$s['id_supplier'] ?>"
         onclick="return confirm('Yakin hapus supplier \'<?= e($s['nama_supplier']) ?>\'?');">Hapus</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?>
    <tr><td colspan="3" style="text-align:center; color:var(--muted);">Belum ada data supplier.</td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
