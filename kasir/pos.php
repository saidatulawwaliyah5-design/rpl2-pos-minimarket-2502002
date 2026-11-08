<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin', 'kasir']);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [id_barang => qty]
}

$pesan = '';
$pesanTipe = 'success';

// ---------- Handle actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_item') {
        $idBarang = (int)$_POST['id_barang'];
        $stmt = $pdo->prepare("SELECT stok FROM barang WHERE id_barang = ? AND status = 'aktif'");
        $stmt->execute([$idBarang]);
        $stok = (int)$stmt->fetchColumn();
        $qtyDiKeranjang = $_SESSION['cart'][$idBarang] ?? 0;

        if ($qtyDiKeranjang + 1 > $stok) {
            $pesan = 'Stok tidak mencukupi.';
            $pesanTipe = 'error';
        } else {
            $_SESSION['cart'][$idBarang] = $qtyDiKeranjang + 1;
        }
    }

    if ($action === 'remove_item') {
        $idBarang = (int)$_POST['id_barang'];
        unset($_SESSION['cart'][$idBarang]);
    }

    if ($action === 'update_qty') {
        $idBarang = (int)$_POST['id_barang'];
        $qty = max(0, (int)$_POST['qty']);
        if ($qty === 0) {
            unset($_SESSION['cart'][$idBarang]);
        } else {
            $_SESSION['cart'][$idBarang] = $qty;
        }
    }

    if ($action === 'checkout' && !empty($_SESSION['cart'])) {
        $idPelanggan = (int)($_POST['id_pelanggan'] ?: 1);
        $jenisTransaksi = in_array($_POST['jenis_transaksi'] ?? '', ['Kredit','Debet','Tunai'])
            ? $_POST['jenis_transaksi'] : 'Tunai';

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO penjualan (tgl_jual, id_pelanggan, id_karyawan, jenis_transaksi) VALUES (NOW(), ?, ?, ?)");
            $stmt->execute([$idPelanggan, $_SESSION['id_karyawan'], $jenisTransaksi]);
            $idPenjualan = $pdo->lastInsertId();

            foreach ($_SESSION['cart'] as $idBarang => $qty) {
                $stmt = $pdo->prepare("SELECT b.harga_jual, b.stok, b.status, p.diskon_persen
                                       FROM barang b LEFT JOIN promo p ON p.id_barang = b.id_barang
                                       WHERE b.id_barang = ? FOR UPDATE");
                $stmt->execute([$idBarang]);
                $row = $stmt->fetch();

                if (!$row || $row['status'] !== 'aktif' || $row['stok'] < $qty) {
                    throw new Exception('Stok barang ID ' . $idBarang . ' tidak mencukupi.');
                }

                $harga = (float)$row['harga_jual'];
                if (!empty($row['diskon_persen'])) {
                    $harga = $harga - ($harga * $row['diskon_persen'] / 100);
                }
                $subtotal = $harga * $qty;

                $stmt = $pdo->prepare("INSERT INTO detail_penjualan (id_penjualan, id_barang, qty, subtotal) VALUES (?,?,?,?)");
                $stmt->execute([$idPenjualan, $idBarang, $qty, $subtotal]);

                $stmt = $pdo->prepare("UPDATE barang SET stok = stok - ? WHERE id_barang = ?");
                $stmt->execute([$qty, $idBarang]);
            }

            $pdo->commit();

            // Setelah transaksi tersimpan: periksa stok terbaru terhadap batas minimum
            $ids = array_keys($_SESSION['cart']);
            $in  = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("SELECT nama_barang, stok, stok_minimum, status_peringatan
                                   FROM v_peringatan_stok WHERE id_barang IN ($in)");
            $stmt->execute($ids);
            $_SESSION['flash_stok'] = $stmt->fetchAll();

            $_SESSION['cart'] = [];
            header('Location: struk.php?id=' . $idPenjualan);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $pesan = 'Transaksi gagal: ' . $e->getMessage();
            $pesanTipe = 'error';
        }
    }
}

// ---------- Data untuk tampilan ----------
$keyword = trim($_GET['cari'] ?? '');
$sqlProduk = "SELECT b.*, p.diskon_persen FROM barang b LEFT JOIN promo p ON p.id_barang = b.id_barang WHERE b.stok > 0 AND b.status = 'aktif'";
$paramsProduk = [];
if ($keyword !== '') {
    $sqlProduk .= " AND b.nama_barang LIKE ?";
    $paramsProduk[] = "%$keyword%";
}
$sqlProduk .= " ORDER BY b.nama_barang ASC";
$stmt = $pdo->prepare($sqlProduk);
$stmt->execute($paramsProduk);
$produkList = $stmt->fetchAll();

$pelangganList = $pdo->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan")->fetchAll();

// Ambil detail barang di keranjang
$cartDetail = [];
$totalBelanja = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT b.id_barang, b.nama_barang, b.harga_jual, p.diskon_persen
                            FROM barang b LEFT JOIN promo p ON p.id_barang = b.id_barang
                            WHERE b.id_barang IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $row) {
        $qty = $_SESSION['cart'][$row['id_barang']];
        $harga = (float)$row['harga_jual'];
        if (!empty($row['diskon_persen'])) {
            $harga = $harga - ($harga * $row['diskon_persen'] / 100);
        }
        $subtotal = $harga * $qty;
        $totalBelanja += $subtotal;
        $cartDetail[] = ['id_barang' => $row['id_barang'], 'nama' => $row['nama_barang'], 'qty' => $qty, 'harga' => $harga, 'subtotal' => $subtotal];
    }
}

$pageTitle = 'Kasir - Transaksi Baru';
$activeMenuKasir = true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kasir — Lia Belanja Bersama</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="layout">
  <div class="sidebar">
    <div class="brand">Lia Belanja Bersama<small>Panel Kasir</small></div>
    <a href="pos.php" class="active">Transaksi Baru</a>
    <a href="riwayat.php">Riwayat Transaksi</a>
    <a href="profil.php">Profil Saya</a>
    <div class="logout">
      <a href="../index.php">Lihat Katalog Publik</a>
      <a href="../logout.php">Logout (<?= e($_SESSION['nama_karyawan']) ?>)</a>
    </div>
  </div>
  <div class="content">

<h1>Transaksi Baru</h1>
<p class="subtitle">Pilih produk untuk ditambahkan ke keranjang</p>

<?php if ($pesan): ?>
  <div class="alert alert-<?= $pesanTipe === 'error' ? 'error' : 'success' ?>"><?= e($pesan) ?></div>
<?php endif; ?>

<div class="pos-layout">
  <div>
    <div class="card" style="margin-bottom:14px; padding:14px 18px;">
      <label for="barcode_input" style="font-weight:600; font-size:0.85rem; display:block; margin-bottom:6px;">
        Scan Barcode (kode = ID Barang)
      </label>
      <form method="post" id="formBarcode">
        <input type="hidden" name="action" value="add_item">
        <input type="text" id="barcode_input" name="id_barang" placeholder="Scan atau ketik kode barang, lalu Enter"
               autocomplete="off" autofocus>
      </form>
      <p style="font-size:0.75rem; color:var(--muted); margin:6px 0 0;">
        Simulasi: ketik ID Barang (angka) di kolom ini lalu tekan Enter, sama seperti scanner sungguhan yang otomatis mengirim Enter setelah membaca kode.
      </p>
    </div>
    <form method="get" style="margin-bottom:14px;">
      <input type="text" name="cari" placeholder="Cari produk..." value="<?= e($keyword) ?>">
    </form>
    <div class="pos-products">
      <?php foreach ($produkList as $p):
          $harga = (float)$p['harga_jual'];
          if (!empty($p['diskon_persen'])) {
              $harga = $harga - ($harga * $p['diskon_persen'] / 100);
          }
      ?>
        <form method="post" class="pos-item">
          <input type="hidden" name="action" value="add_item">
          <input type="hidden" name="id_barang" value="<?= (int)$p['id_barang'] ?>">
          <div class="nama"><?= e($p['nama_barang']) ?></div>
          <div class="harga"><?= rupiah($harga) ?></div>
          <div class="stok">Stok: <?= (int)$p['stok'] ?></div>
          <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">+ Tambah</button>
        </form>
      <?php endforeach; ?>
      <?php if (empty($produkList)): ?>
        <p style="color:var(--muted);">Produk tidak ditemukan / stok habis.</p>
      <?php endif; ?>
    </div>
  </div>

  <div>
    <div class="card">
      <h3 style="margin-top:0;">🛒 Keranjang</h3>
      <?php if (empty($cartDetail)): ?>
        <p style="color:var(--muted); font-size:0.88rem;">Keranjang masih kosong.</p>
      <?php else: ?>
        <?php foreach ($cartDetail as $c): ?>
          <div class="cart-row">
            <div>
              <div><?= e($c['nama']) ?></div>
              <div style="color:var(--muted); font-size:0.8rem;"><?= rupiah($c['harga']) ?> x
                <form method="post" style="display:inline;">
                  <input type="hidden" name="action" value="update_qty">
                  <input type="hidden" name="id_barang" value="<?= (int)$c['id_barang'] ?>">
                  <input type="number" name="qty" value="<?= (int)$c['qty'] ?>" min="0"
                         style="width:52px; padding:2px 6px; display:inline;"
                         onchange="this.form.submit();">
                </form>
              </div>
            </div>
            <div style="text-align:right;">
              <div><?= rupiah($c['subtotal']) ?></div>
              <form method="post">
                <input type="hidden" name="action" value="remove_item">
                <input type="hidden" name="id_barang" value="<?= (int)$c['id_barang'] ?>">
                <button type="submit" class="btn btn-danger btn-sm" style="margin-top:3px;">Hapus</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="cart-total"><span>Total</span><span><?= rupiah($totalBelanja) ?></span></div>

        <form method="post" style="margin-top:16px;">
          <input type="hidden" name="action" value="checkout">
          <div class="field">
            <label for="id_pelanggan">Pelanggan</label>
            <select id="id_pelanggan" name="id_pelanggan">
              <?php foreach ($pelangganList as $pl): ?>
                <option value="<?= (int)$pl['id_pelanggan'] ?>"><?= e($pl['nama_pelanggan']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="jenis_transaksi">Metode Bayar</label>
            <select id="jenis_transaksi" name="jenis_transaksi">
              <option value="Tunai">Tunai</option>
              <option value="Debet">Debet</option>
              <option value="Kredit">Kredit</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;">Proses Transaksi</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

  </div>
</div>
</body>
</html>
