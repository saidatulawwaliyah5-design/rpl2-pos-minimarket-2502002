<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/icons.php';

$keyword = trim($_GET['cari'] ?? '');
$jenisFilter = (int)($_GET['jenis'] ?? 0);

$kategoriList = $pdo->query("
    SELECT j.id_jenis, j.nama_jenis, COUNT(b.id_barang) AS jumlah
    FROM jenis j LEFT JOIN barang b ON b.id_jenis = j.id_jenis AND b.status = 'aktif'
    GROUP BY j.id_jenis
    ORDER BY jumlah DESC
")->fetchAll();

$sql = "SELECT b.*, j.nama_jenis, p.diskon_persen, p.nama_promo
        FROM barang b
        LEFT JOIN jenis j ON b.id_jenis = j.id_jenis
        LEFT JOIN promo p ON p.id_barang = b.id_barang
        WHERE b.status = 'aktif'";
$params = [];
if ($keyword !== '') {
    $sql .= " AND b.nama_barang LIKE ?";
    $params[] = "%$keyword%";
}
if ($jenisFilter > 0) {
    $sql .= " AND b.id_jenis = ?";
    $params[] = $jenisFilter;
}
$sql .= " ORDER BY b.nama_barang ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produk = $stmt->fetchAll();

$terlaris = $pdo->query("
    SELECT b.id_barang
    FROM detail_penjualan dp
    JOIN barang b ON b.id_barang = dp.id_barang AND b.status = 'aktif'
    GROUP BY dp.id_barang
    ORDER BY SUM(dp.qty) DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_COLUMN);

if (count($terlaris) < 5) {
    $exclude = $terlaris ?: [0];
    $in = implode(',', array_fill(0, count($exclude), '?'));
    $stmt = $pdo->prepare("SELECT id_barang FROM barang WHERE status = 'aktif' AND id_barang NOT IN ($in) ORDER BY nama_barang ASC LIMIT " . (5 - count($exclude)));
    $stmt->execute($exclude);
    $terlaris = array_merge($terlaris, $stmt->fetchAll(PDO::FETCH_COLUMN));
}

$produkTerlaris = [];
if (!empty($terlaris)) {
    $in = implode(',', array_fill(0, count($terlaris), '?'));
    $stmt = $pdo->prepare("
        SELECT b.*, j.nama_jenis, p.diskon_persen, p.nama_promo
        FROM barang b
        LEFT JOIN jenis j ON b.id_jenis = j.id_jenis
        LEFT JOIN promo p ON p.id_barang = b.id_barang
        WHERE b.id_barang IN ($in)
    ");
    $stmt->execute($terlaris);
    $rows = $stmt->fetchAll();
    $byId = [];
    foreach ($rows as $r) { $byId[$r['id_barang']] = $r; }
    foreach ($terlaris as $tid) { if (isset($byId[$tid])) $produkTerlaris[] = $byId[$tid]; }
}

function hargaFinal(array $p): float {
    $h = (float)$p['harga_jual'];
    if (!empty($p['diskon_persen'])) {
        $h -= $h * $p['diskon_persen'] / 100;
    }
    return $h;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lia Belanja Bersama — Belanja Kebutuhan Sehari-hari</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/css/store.css">
</head>
<body class="store-body">

<nav class="store-nav">
  <a href="index.php" class="store-logo">
    <div class="icon-box"><?= ikonKecil('cart', 22, '#ffffff') ?></div>
    <div>Lia Belanja Bersama<small>Belanja kebutuhan sehari-hari</small></div>
  </a>
  <form method="get" class="store-search">
    <input type="text" name="cari" placeholder="Cari produk..." value="<?= e($keyword) ?>">
    <button type="submit"><?= ikonKecil('search', 18, '#ffffff') ?></button>
  </form>
  <div class="store-nav-right">
    <a class="plain" href="#promo"><?= ikonKecil('tag', 16, '#E5E7EB') ?> Promo</a>
    <?php if (isLoggedIn()): ?>
      <a class="store-login-btn" href="<?= currentRole() === 'admin' ? 'admin/dashboard.php' : 'kasir/pos.php' ?>">Dashboard &rarr;</a>
    <?php else: ?>
      <a class="store-login-btn" href="login.php">Login Admin/Kasir</a>
    <?php endif; ?>
  </div>
</nav>

<div class="store-layout">

  <aside class="store-sidebar">
    <div class="store-panel">
      <h4>Menu</h4>
      <a class="store-nav-link active" href="index.php"><span class="ic-wrap"><?= ikonKecil('home', 16) ?></span> Beranda</a>
      <a class="store-nav-link" href="#produk"><span class="ic-wrap"><?= ikonKecil('grid', 16) ?></span> Semua Produk</a>
      <a class="store-nav-link" href="#kategori"><span class="ic-wrap"><?= ikonKecil('store', 16) ?></span> Kategori</a>
      <a class="store-nav-link" href="#promo"><span class="ic-wrap"><?= ikonKecil('tag', 16) ?></span> Promo</a>
    </div>

    <div class="store-panel">
      <h4>Kategori Populer</h4>
      <?php foreach ($kategoriList as $k): ?>
        <a class="store-cat-link <?= $jenisFilter === (int)$k['id_jenis'] ? 'active' : '' ?>"
           href="?jenis=<?= (int)$k['id_jenis'] ?>">
          <?= illustrasiKategori($k['nama_jenis'], 24) ?> <?= e($k['nama_jenis']) ?>
          <span style="margin-left:auto; color:#9CA3AF; font-weight:600;">(<?= (int)$k['jumlah'] ?>)</span>
        </a>
      <?php endforeach; ?>
      <?php if ($jenisFilter > 0): ?>
        <a class="store-cat-link" href="index.php" style="color:#DC2626; margin-top:6px;">&times; Hapus filter</a>
      <?php endif; ?>
    </div>

    <div class="store-promo-box">
      <div class="big"><?= ikonKecil('gift', 20, '#ffffff') ?> Jadi Member, Dapat Poin!</div>
      <p style="font-size:0.8rem; color:#E0E7FF; margin:6px 0 14px;">Setiap belanja, poin kamu nambah terus — tukar jadi diskon berikutnya.</p>
      <a href="#promo">Lihat Promo</a>
    </div>
  </aside>

  <main>
    <section class="store-hero">
      <div>
        <h1>Belanja Mudah,<br><span class="accent">Harga</span> Bersahabat</h1>
        <p>Temukan kebutuhan sehari-hari dengan harga terbaik hanya di Lia Belanja Bersama!</p>
        <a class="cta" href="#produk">Belanja Sekarang &rarr;</a>
      </div>
      <div class="store-hero-emoji"><?= ilustrasiHeroKeranjang() ?></div>
      <div class="store-hero-badges">
        <div class="store-hero-badge">
          <span class="ic-wrap"><?= ikonKecil('tag', 18, '#FBBF24') ?></span>
          <div><strong>Harga Terbaik</strong><span class="desc">Dijamin bersahabat setiap hari</span></div>
        </div>
        <div class="store-hero-badge">
          <span class="ic-wrap"><?= ikonKecil('store', 18, '#5EEAD4') ?></span>
          <div><strong>Produk Lengkap</strong><span class="desc">Kebutuhan sehari-hari tersedia</span></div>
        </div>
        <div class="store-hero-badge">
          <span class="ic-wrap"><?= ikonKecil('lock', 18, '#C4B5FD') ?></span>
          <div><strong>Data Tercatat Rapi</strong><span class="desc">Setiap transaksi aman & akurat</span></div>
        </div>
      </div>
    </section>

    <div class="store-cat-row" id="kategori">
      <?php foreach ($kategoriList as $k): ?>
        <a class="store-cat-pill" href="?jenis=<?= (int)$k['id_jenis'] ?>">
          <div class="em-circle" style="background:<?= kategoriWarna($k['nama_jenis']) ?>;"><?= illustrasiKategori($k['nama_jenis'], 32) ?></div>
          <div>
            <strong><?= e($k['nama_jenis']) ?></strong>
            <span><?= (int)$k['jumlah'] ?> Produk</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($produkTerlaris) && $keyword === '' && $jenisFilter === 0): ?>
    <div class="store-section-head">
      <h2><?= ikonKecil('star', 20, '#F59E0B') ?> Produk Pilihan</h2>
      <a href="#produk">Lihat Semua <?= ikonKecil('arrow', 14, '#4F46E5') ?></a>
    </div>
    <div class="store-product-grid">
      <?php foreach ($produkTerlaris as $p): ?>
        <?php include __DIR__ . '/includes/product_card.php'; ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="store-section-head" id="produk">
      <h2><?= $jenisFilter > 0 ? e($kategoriList[array_search($jenisFilter, array_column($kategoriList, 'id_jenis'))]['nama_jenis'] ?? 'Produk') : 'Semua Produk' ?></h2>
    </div>
    <div class="store-product-grid">
      <?php if (empty($produk)): ?>
        <p style="grid-column:1/-1; text-align:center; color:var(--store-muted);">Produk tidak ditemukan.</p>
      <?php endif; ?>
      <?php foreach ($produk as $p): ?>
        <?php include __DIR__ . '/includes/product_card.php'; ?>
      <?php endforeach; ?>
    </div>

    <div class="store-banner" id="promo">
      <div style="display:flex; align-items:center; gap:16px;">
        <div class="ic-wrap"><?= ikonKecil('gift', 26, '#ffffff') ?></div>
        <div>
          <h3>Dapatkan Promo Spesial Setiap Hari!</h3>
          <p>Jangan lewatkan berbagai diskon menarik untuk produk pilihan.</p>
        </div>
      </div>
      <a class="store-banner-btn" href="?jenis=0#produk">Lihat Semua Produk</a>
    </div>

    <div class="store-features">
      <div class="store-feature"><div class="ic-wrap" style="background:#DBEAFE;"><?= ikonKecil('lock', 22, '#1D4ED8') ?></div><div><strong>Pembayaran Aman</strong><span>Transaksi tercatat rapi di sistem</span></div></div>
      <div class="store-feature"><div class="ic-wrap" style="background:#DCFCE7;"><?= ikonKecil('scale', 22, '#15803D') ?></div><div><strong>Harga Transparan</strong><span>Harga & promo selalu update</span></div></div>
      <div class="store-feature"><div class="ic-wrap" style="background:#FEF3C7;"><?= ikonKecil('store', 22, '#B45309') ?></div><div><strong>Stok Real-time</strong><span>Info stok langsung dari toko</span></div></div>
      <div class="store-feature"><div class="ic-wrap" style="background:#F3E8FF;"><?= ikonKecil('gift', 22, '#7C3AED') ?></div><div><strong>Program Member</strong><span>Kumpulkan poin tiap belanja</span></div></div>
    </div>

    <p class="store-footer">&copy; <?= date('Y') ?> Lia Belanja Bersama — Sistem Informasi Minimarket</p>
  </main>
</div>

</body>
</html>
