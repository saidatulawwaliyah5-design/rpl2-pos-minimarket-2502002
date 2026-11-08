<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$sukses = $_GET['sukses'] ?? '';
$error  = $_GET['error'] ?? '';

// Pencarian (ID, nama, atau jenis) dan filter status
$cari   = trim($_GET['cari'] ?? '');
$tampil = in_array($_GET['tampil'] ?? 'aktif', ['aktif', 'nonaktif', 'semua'], true) ? ($_GET['tampil'] ?? 'aktif') : 'aktif';

$sql = "SELECT b.*, j.nama_jenis
        FROM barang b
        LEFT JOIN jenis j ON b.id_jenis = j.id_jenis
        WHERE 1=1";
$params = [];
if ($tampil !== 'semua') {
    $sql .= " AND b.status = ?";
    $params[] = $tampil;
}
if ($cari !== '') {
    $sql .= " AND (b.nama_barang LIKE ? OR j.nama_jenis LIKE ? OR b.id_barang = ?)";
    $params[] = "%$cari%";
    $params[] = "%$cari%";
    $params[] = ctype_digit($cari) ? (int)$cari : 0;
}
$sql .= " ORDER BY b.nama_barang ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftarBarang = $stmt->fetchAll();

$pageTitle = 'Barang & Stok';
$activeMenu = 'barang';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Barang & Stok</h1>
<p class="subtitle">Kelola data produk minimarket</p>

<?php if ($sukses === 'tambah'): ?><div class="alert alert-success">Barang baru berhasil ditambahkan.</div><?php endif; ?>
<?php if ($sukses === 'ubah'): ?><div class="alert alert-success">Data barang berhasil diperbarui.</div><?php endif; ?>
<?php if ($sukses === 'hapus'): ?><div class="alert alert-success">Barang berhasil dihapus permanen (belum pernah dipakai di transaksi).</div><?php endif; ?>
<?php if ($sukses === 'nonaktif'): ?><div class="alert alert-success">Barang sudah pernah tercatat di transaksi/data lain, jadi <strong>dinonaktifkan</strong> (riwayat tetap utuh).</div><?php endif; ?>
<?php if ($sukses === 'aktif'): ?><div class="alert alert-success">Barang diaktifkan kembali.</div><?php endif; ?>
<?php if ($error !== ''): ?><div class="alert alert-error">Proses gagal. Silakan coba lagi.</div><?php endif; ?>

<a href="barang_form.php" class="btn btn-primary" style="margin-bottom:16px; display:inline-block;">+ Tambah Barang</a>
<a href="barang_export.php" class="btn btn-outline" style="margin-bottom:16px; display:inline-block;">Export Excel</a>

<form method="get" style="display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap;">
  <input type="text" name="cari" placeholder="Cari ID, nama, atau jenis barang..." value="<?= e($cari) ?>" style="max-width:320px;">
  <select name="tampil" style="max-width:180px;">
    <option value="aktif"    <?= $tampil === 'aktif' ? 'selected' : '' ?>>Barang aktif</option>
    <option value="nonaktif" <?= $tampil === 'nonaktif' ? 'selected' : '' ?>>Barang nonaktif</option>
    <option value="semua"    <?= $tampil === 'semua' ? 'selected' : '' ?>>Semua</option>
  </select>
  <button type="submit" class="btn btn-primary">Cari</button>
  <?php if ($cari !== '' || $tampil !== 'aktif'): ?><a href="barang.php" class="btn btn-outline">Reset</a><?php endif; ?>
</form>

<table class="data">
  <tr>
    <th>ID</th><th>Nama Barang</th><th>Jenis</th><th>Stok</th><th>Min.</th><th>Status Stok</th><th>Harga Jual</th><th>Terakhir Diubah</th><th>Aksi</th>
  </tr>
  <?php foreach ($daftarBarang as $b): ?>
  <?php [$lbl, $cls] = statusStok($b['stok'], $b['stok_minimum']); ?>
  <tr>
    <td><?= (int)$b['id_barang'] ?></td>
    <td><?= e($b['nama_barang']) ?>
      <?php if ($b['status'] === 'nonaktif'): ?><span class="badge badge-info">Nonaktif</span><?php endif; ?>
    </td>
    <td><span class="badge badge-info"><?= e($b['nama_jenis'] ?? '-') ?></span></td>
    <td><?= (int)$b['stok'] ?></td>
    <td><?= (int)$b['stok_minimum'] ?></td>
    <td><span class="badge <?= $cls ?>"><?= e($lbl) ?></span></td>
    <td><?= rupiah($b['harga_jual']) ?></td>
    <td><?= isset($b['updated_at']) ? tanggalIndo($b['updated_at']) : '-' ?></td>
    <td style="white-space:nowrap;">
      <a class="btn btn-outline btn-sm" href="barang_form.php?id=<?= (int)$b['id_barang'] ?>">Edit</a>
      <?php if ($b['status'] === 'aktif'): ?>
        <form method="post" action="barang_delete.php" style="display:inline;"
              onsubmit="return confirm('Yakin hapus barang \'<?= e(addslashes($b['nama_barang'])) ?>\'? Jika sudah pernah dipakai di transaksi, barang hanya dinonaktifkan.');">
          <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
          <input type="hidden" name="id" value="<?= (int)$b['id_barang'] ?>">
          <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
      <?php else: ?>
        <form method="post" action="barang_aktifkan.php" style="display:inline;">
          <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
          <input type="hidden" name="id" value="<?= (int)$b['id_barang'] ?>">
          <button type="submit" class="btn btn-primary btn-sm">Aktifkan</button>
        </form>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($daftarBarang)): ?>
    <tr><td colspan="9" style="text-align:center; color:var(--muted);">
      <?= $cari !== '' ? 'Data tidak ditemukan.' : 'Belum ada data barang.' ?>
    </td></tr>
  <?php endif; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
