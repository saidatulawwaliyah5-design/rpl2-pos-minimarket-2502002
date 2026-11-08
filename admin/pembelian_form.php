<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

if (!isset($_SESSION['cart_beli'])) {
    $_SESSION['cart_beli'] = []; // [id_barang => qty]
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_item') {
        $idBarang = (int)$_POST['id_barang'];
        $qty = max(1, (int)$_POST['qty']);
        $_SESSION['cart_beli'][$idBarang] = ($_SESSION['cart_beli'][$idBarang] ?? 0) + $qty;
    }

    if ($action === 'remove_item') {
        unset($_SESSION['cart_beli'][(int)$_POST['id_barang']]);
    }

    if ($action === 'simpan') {
        $idSupplier = (int)($_POST['id_supplier'] ?? 0);
        $tglBeli = $_POST['tgl_beli'] ?? date('Y-m-d');

        if ($idSupplier <= 0) {
            $error = 'Pilih supplier terlebih dahulu.';
        } elseif (empty($_SESSION['cart_beli'])) {
            $error = 'Tambahkan minimal 1 barang.';
        } else {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO pembelian (tgl_beli, id_supplier) VALUES (?, ?)");
                $stmt->execute([$tglBeli, $idSupplier]);
                $idPembelian = $pdo->lastInsertId();

                foreach ($_SESSION['cart_beli'] as $idBarang => $qty) {
                    $pdo->prepare("INSERT INTO detail_pembelian (id_pembelian, id_barang, qty) VALUES (?,?,?)")
                        ->execute([$idPembelian, $idBarang, $qty]);
                    $pdo->prepare("UPDATE barang SET stok = stok + ? WHERE id_barang = ?")
                        ->execute([$qty, $idBarang]);
                }

                $pdo->commit();
                $_SESSION['cart_beli'] = [];
                header('Location: pembelian.php?sukses=tambah');
                exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Gagal menyimpan: ' . $e->getMessage();
            }
        }
    }
}

$supplierList = $pdo->query("SELECT * FROM supplier ORDER BY nama_supplier")->fetchAll();
$barangList = $pdo->query("SELECT id_barang, nama_barang, stok FROM barang WHERE status = 'aktif' ORDER BY nama_barang")->fetchAll();

$cartDetail = [];
if (!empty($_SESSION['cart_beli'])) {
    $ids = array_keys($_SESSION['cart_beli']);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id_barang, nama_barang FROM barang WHERE id_barang IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $row) {
        $cartDetail[] = ['id_barang' => $row['id_barang'], 'nama' => $row['nama_barang'], 'qty' => $_SESSION['cart_beli'][$row['id_barang']]];
    }
}

$pageTitle = 'Catat Pembelian';
$activeMenu = 'pembelian';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h1>Catat Pembelian dari Supplier</h1>
<p class="subtitle"><a href="pembelian.php">&larr; Kembali ke daftar pembelian</a></p>

<?php if (empty($supplierList)): ?>
  <div class="alert alert-error">Belum ada data supplier. <a href="supplier_form.php">Tambah supplier dulu</a> sebelum mencatat pembelian.</div>
<?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="pos-layout">
  <div>
    <div class="card">
      <h3 style="margin-top:0;">1. Pilih Barang</h3>
      <?php foreach ($barangList as $b): ?>
        <form method="post" style="display:flex; gap:8px; align-items:center; margin-bottom:8px;">
          <input type="hidden" name="action" value="add_item">
          <input type="hidden" name="id_barang" value="<?= (int)$b['id_barang'] ?>">
          <div style="flex:1;"><?= e($b['nama_barang']) ?> <span style="color:var(--muted); font-size:0.8rem;">(stok: <?= (int)$b['stok'] ?>)</span></div>
          <input type="number" name="qty" value="10" min="1" style="width:70px;">
          <button type="submit" class="btn btn-primary btn-sm">+ Tambah</button>
        </form>
      <?php endforeach; ?>
    </div>
  </div>

  <div>
    <div class="card">
      <h3 style="margin-top:0;">2. Barang Masuk</h3>
      <?php if (empty($cartDetail)): ?>
        <p style="color:var(--muted); font-size:0.88rem;">Belum ada barang dipilih.</p>
      <?php else: ?>
        <?php foreach ($cartDetail as $c): ?>
          <div class="cart-row">
            <div><?= e($c['nama']) ?> <span style="color:var(--muted);">x <?= (int)$c['qty'] ?></span></div>
            <form method="post">
              <input type="hidden" name="action" value="remove_item">
              <input type="hidden" name="id_barang" value="<?= (int)$c['id_barang'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </div>
        <?php endforeach; ?>

        <form method="post" style="margin-top:16px;">
          <input type="hidden" name="action" value="simpan">
          <div class="field">
            <label for="id_supplier">Supplier</label>
            <select id="id_supplier" name="id_supplier" required>
              <option value="">-- Pilih Supplier --</option>
              <?php foreach ($supplierList as $s): ?>
                <option value="<?= (int)$s['id_supplier'] ?>"><?= e($s['nama_supplier']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="tgl_beli">Tanggal Beli</label>
            <input type="date" id="tgl_beli" name="tgl_beli" value="<?= date('Y-m-d') ?>">
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;">Simpan & Tambah Stok</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
