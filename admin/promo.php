<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';

$daftarPromo = $pdo->query("
    SELECT p.*, b.nama_barang, b.harga_jual
    FROM promo p
    LEFT JOIN barang b ON p.id_barang = b.id_barang
    ORDER BY p.id_promo DESC
")->fetchAll();

$pageTitle = 'Promo & Diskon';
$activeMenu = 'promo';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Promo & Diskon</h1>
<p class="subtitle">Atur diskon untuk produk tertentu — otomatis tampil di katalog & kasir</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Promo berhasil ditambahkan.</div><?php endif; ?>
<?php if ($sukses === 'ubah'): ?><div class="alert alert-success">Promo berhasil diperbarui.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Promo berhasil dihapus.</div><?php endif; ?>

<a href="promo_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Tambah Promo</a>

<table class="data">
  <tr><th>Nama Promo</th><th>Barang</th><th>Harga Normal</th><th>Diskon</th><th>Harga Setelah Diskon</th><th>Aksi</th></tr>
  <?php foreach ($daftarPromo as $p):
      $harga = (float)($p['harga_jual'] ?? 0);
      $final = $harga - ($harga * $p['diskon_persen'] / 100);
  ?>
  <tr>
    <td><?= e($p['nama_promo']) ?></td>
    <td><?= e($p['nama_barang'] ?? '(barang dihapus)') ?></td>
    <td><?= rupiah($harga) ?></td>
    <td><span class="badge badge-info"><?= (int)$p['diskon_persen'] ?>%</span></td>
    <td><strong><?= rupiah($final) ?></strong></td>
    <td>
      <a class="btn btn-outline btn-sm" href="promo_form.php?id=<?= (int)$p['id_promo'] ?>">Edit</a>
      <a class="btn btn-danger btn-sm" href="promo_delete.php?id=<?= (int)$p['id_promo'] ?>"
         onclick="return confirm('Yakin hapus promo ini?');">Hapus</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftarPromo)): ?>
    <tr><td colspan="6" style="text-align:center; color:var(--muted);">Belum ada promo.</td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
