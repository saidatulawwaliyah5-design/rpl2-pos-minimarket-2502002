<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$idMember = (int)($_GET['id'] ?? 0);
$error = '';

$stmt = $pdo->prepare("
    SELECT m.*, p.nama_pelanggan, p.no_hp
    FROM member m JOIN pelanggan p ON p.id_pelanggan = m.id_pelanggan
    WHERE m.id_member = ?
");
$stmt->execute([$idMember]);
$member = $stmt->fetch();
if (!$member) { header('Location: pelanggan.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perubahan = (int)($_POST['perubahan'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($perubahan === 0) {
        $error = 'Jumlah perubahan poin tidak boleh 0.';
    } elseif ($perubahan < 0 && abs($perubahan) > (int)$member['poin']) {
        $error = 'Poin tidak mencukupi untuk dikurangi sebanyak itu.';
    } else {
        try {
            $pdo->beginTransaction();
            $pdo->prepare("INSERT INTO riwayat_poin (id_member, perubahan, keterangan) VALUES (?,?,?)")
                ->execute([$idMember, $perubahan, $keterangan ?: ($perubahan > 0 ? 'Penambahan poin' : 'Penukaran poin')]);
            $pdo->prepare("UPDATE member SET poin = poin + ? WHERE id_member = ?")
                ->execute([$perubahan, $idMember]);
            $pdo->commit();
            header('Location: member_riwayat.php?id=' . $idMember . '&sukses=1');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM riwayat_poin WHERE id_member = ? ORDER BY tanggal DESC");
$stmt->execute([$idMember]);
$riwayat = $stmt->fetchAll();

$sukses = isset($_GET['sukses']);

$pageTitle = 'Riwayat Poin Member';
$activeMenu = 'pelanggan';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Kartu Member: <?= e($member['nama_pelanggan']) ?></h1>
<p class="subtitle"><a href="pelanggan.php">&larr; Kembali ke daftar pelanggan</a></p>

<?php if ($sukses): ?><div class="alert alert-success">Poin berhasil diperbarui.</div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); max-width:600px;">
  <div class="stat-card"><div class="num"><?= e($member['nama_pelanggan']) ?></div><div class="label">Nama Member</div></div>
  <div class="stat-card"><div class="num"><?= e($member['no_hp'] ?: '-') ?></div><div class="label">No. HP</div></div>
  <div class="stat-card"><div class="num"><?= (int)$member['poin'] ?></div><div class="label">Poin Saat Ini</div></div>
</div>

<div class="card" style="max-width:420px;">
  <h3 style="margin-top:0;">Tambah / Kurangi Poin</h3>
  <form method="post">
    <div class="field">
      <label for="perubahan">Jumlah Poin (isi minus buat mengurangi, contoh: -20)</label>
      <input type="number" id="perubahan" name="perubahan" required>
    </div>
    <div class="field">
      <label for="keterangan">Keterangan</label>
      <input type="text" id="keterangan" name="keterangan" placeholder="Contoh: Bonus belanja 100rb / Tukar voucher">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>

<div class="card">
  <h3 style="margin-top:0;">Riwayat Poin</h3>
  <table class="data">
    <tr><th>Tanggal</th><th>Perubahan</th><th>Keterangan</th></tr>
    <?php foreach ($riwayat as $r): ?>
      <tr>
        <td><?= tanggalIndo($r['tanggal']) ?></td>
        <td>
          <?php if ($r['perubahan'] > 0): ?>
            <span class="badge badge-ok">+<?= (int)$r['perubahan'] ?></span>
          <?php else: ?>
            <span class="badge badge-low"><?= (int)$r['perubahan'] ?></span>
          <?php endif; ?>
        </td>
        <td><?= e($r['keterangan']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($riwayat)): ?>
      <tr><td colspan="3" style="text-align:center; color:var(--muted);">Belum ada riwayat poin.</td></tr>
    <?php endif; ?>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
