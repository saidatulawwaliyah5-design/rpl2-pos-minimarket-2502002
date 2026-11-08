<?php
/**
 * Komponen kartu produk untuk index.php.
 * Membutuhkan variabel $p (baris data produk) dari scope pemanggil.
 */
$hargaAsli = (float)$p['harga_jual'];
$hargaFinal = hargaFinal($p);
$adaDiskon = !empty($p['diskon_persen']);
?>
<div class="store-product-card">
  <div class="store-product-thumb" style="background:<?= kategoriWarna($p['nama_jenis']) ?>;">
    <?php if ($adaDiskon): ?>
      <span class="store-discount-badge"><?= (int)$p['diskon_persen'] ?>% OFF</span>
    <?php endif; ?>
    <span class="store-heart"><?= ikonKecil('heart', 18, '#6B7280') ?></span>
    <?= illustrasiKategori($p['nama_jenis'], 60) ?>
  </div>
  <div class="store-product-cat"><?= e($p['nama_jenis'] ?? 'Umum') ?></div>
  <h3><?= e($p['nama_barang']) ?></h3>
  <div>
    <?php if ($adaDiskon): ?>
      <span class="store-price-old"><?= rupiah($hargaAsli) ?></span>
    <?php endif; ?>
    <span class="store-price"><?= rupiah($hargaFinal) ?></span>
  </div>
  <div class="store-stock-row">
    <?php if ((int)$p['stok'] <= 0): ?>
      <span class="store-stock-badge store-stock-low">Stok Habis</span>
    <?php elseif ((int)$p['stok'] <= (int)$p['stok_minimum']): ?>
      <span class="store-stock-badge store-stock-low">Sisa <?= (int)$p['stok'] ?></span>
    <?php else: ?>
      <span class="store-stock-badge store-stock-ok">Stok Tersedia</span>
    <?php endif; ?>
    <a href="login.php" class="store-add-btn" title="Login sebagai kasir untuk transaksi"><?= ikonKecil('plus', 16, '#ffffff') ?></a>
  </div>
</div>
