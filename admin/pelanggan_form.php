<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$error = '';
$data = ['nama_pelanggan' => '', 'no_hp' => '', 'id_member' => null, 'poin' => 0];

if ($isEdit) {
    $stmt = $pdo->prepare("
        SELECT p.*, m.id_member, m.poin
        FROM pelanggan p LEFT JOIN member m ON m.id_pelanggan = p.id_pelanggan
        WHERE p.id_pelanggan = ?
    ");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { header('Location: pelanggan.php'); exit; }
    $data = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama_pelanggan'] ?? '');
    $hp      = trim($_POST['no_hp'] ?? '');
    $jadiMember = isset($_POST['jadi_member']);
    $poin    = (int)($_POST['poin'] ?? 0);

    if ($nama === '') {
        $error = 'Nama pelanggan wajib diisi.';
    } else {
        $pdo->beginTransaction();
        try {
            if ($isEdit) {
                $stmt = $pdo->prepare("UPDATE pelanggan SET nama_pelanggan=?, no_hp=? WHERE id_pelanggan=?");
                $stmt->execute([$nama, $hp, $id]);
                $pelangganId = $id;
            } else {
                $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_hp) VALUES (?,?)");
                $stmt->execute([$nama, $hp]);
                $pelangganId = $pdo->lastInsertId();
            }

            // Cek status member saat ini
            $stmt = $pdo->prepare("SELECT id_member FROM member WHERE id_pelanggan = ?");
            $stmt->execute([$pelangganId]);
            $memberRow = $stmt->fetch();

            if ($jadiMember && !$memberRow) {
                $stmt = $pdo->prepare("INSERT INTO member (id_pelanggan, poin) VALUES (?, ?)");
                $stmt->execute([$pelangganId, $poin]);
            } elseif ($jadiMember && $memberRow) {
                $stmt = $pdo->prepare("UPDATE member SET poin = ? WHERE id_pelanggan = ?");
                $stmt->execute([$poin, $pelangganId]);
            } elseif (!$jadiMember && $memberRow) {
                $stmt = $pdo->prepare("DELETE FROM member WHERE id_pelanggan = ?");
                $stmt->execute([$pelangganId]);
            }

            $pdo->commit();
            header('Location: pelanggan.php?sukses=' . ($isEdit ? 'ubah' : 'tambah'));
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
    $data = ['nama_pelanggan' => $nama, 'no_hp' => $hp, 'id_member' => $jadiMember ? 1 : null, 'poin' => $poin];
}

$pageTitle = $isEdit ? 'Edit Pelanggan' : 'Tambah Pelanggan';
$activeMenu = 'pelanggan';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1><?= $isEdit ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' ?></h1>
<p class="subtitle"><a href="pelanggan.php">&larr; Kembali ke daftar pelanggan</a></p>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card" style="max-width:480px;">
  <form method="post" id="formPelanggan">
    <div class="field">
      <label for="nama_pelanggan">Nama Pelanggan</label>
      <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?= e($data['nama_pelanggan']) ?>" required>
    </div>
    <div class="field">
      <label for="no_hp">No. HP</label>
      <input type="text" id="no_hp" name="no_hp" value="<?= e($data['no_hp']) ?>">
    </div>
    <div class="field" style="display:flex; align-items:center; gap:8px;">
      <input type="checkbox" id="jadi_member" name="jadi_member" style="width:auto;"
             onchange="document.getElementById('poinField').style.display = this.checked ? 'block' : 'none';"
             <?= $data['id_member'] ? 'checked' : '' ?>>
      <label for="jadi_member" style="margin:0;">Daftarkan sebagai Member</label>
    </div>
    <div class="field" id="poinField" style="display:<?= $data['id_member'] ? 'block' : 'none' ?>;">
      <label for="poin">Poin Member</label>
      <input type="number" id="poin" name="poin" min="0" value="<?= (int)($data['poin'] ?? 0) ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Pelanggan' ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
