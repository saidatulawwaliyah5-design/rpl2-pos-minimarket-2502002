<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';

$daftar = $pdo->query("
    SELECT p.*, m.id_member, m.poin
    FROM pelanggan p
    LEFT JOIN member m ON m.id_pelanggan = p.id_pelanggan
    ORDER BY p.nama_pelanggan ASC
")->fetchAll();

$pageTitle = 'Pelanggan & Member';
$activeMenu = 'pelanggan';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Pelanggan & Member</h1>
<p class="subtitle">Kelola data pelanggan dan status keanggotaan</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Pelanggan berhasil ditambahkan.</div><?php endif; ?>
<?php if ($sukses === 'ubah'): ?><div class="alert alert-success">Data pelanggan berhasil diperbarui.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Pelanggan berhasil dihapus.</div><?php endif; ?>

<a href="pelanggan_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Tambah Pelanggan</a>

<table class="data">
  <tr><th>Nama Pelanggan</th><th>No. HP</th><th>Status</th><th>Poin</th><th>Aksi</th></tr>
  <?php foreach ($daftar as $p): ?>
  <tr>
    <td><?= e($p['nama_pelanggan']) ?></td>
    <td><?= e($p['no_hp'] ?: '-') ?></td>
    <td>
      <?php if ($p['id_member']): ?>
        <span class="badge badge-ok">Member</span>
      <?php else: ?>
        <span class="badge badge-info">Reguler</span>
      <?php endif; ?>
    </td>
    <td>
      <?php if ($p['id_member']): ?>
        <a href="member_riwayat.php?id=<?= (int)$p['id_member'] ?>"><?= (int)$p['poin'] ?> poin &rarr;</a>
      <?php else: ?>
        -
      <?php endif; ?>
    </td>
    <td>
      <a class="btn btn-outline btn-sm" href="pelanggan_form.php?id=<?= (int)$p['id_pelanggan'] ?>">Edit</a>
      <a class="btn btn-danger btn-sm" href="pelanggan_delete.php?id=<?= (int)$p['id_pelanggan'] ?>"
         onclick="return confirm('Yakin hapus pelanggan \'<?= e($p['nama_pelanggan']) ?>\'?');">Hapus</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?>
    <tr><td colspan="5" style="text-align:center; color:var(--muted);">Belum ada data pelanggan.</td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
