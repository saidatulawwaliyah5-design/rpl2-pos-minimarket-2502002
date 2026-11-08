<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);

// Hanya lewat POST + token CSRF (bukan link GET)
csrfCheck();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { header('Location: barang.php'); exit; }

try {
    // Barang yang sudah terpakai di transaksi/pembelian/retur/promo/riwayat
    // tidak dihapus permanen, melainkan dinonaktifkan agar riwayat tetap utuh.
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM v_barang_terpakai WHERE id_barang = ?");
    $stmt->execute([$id]);

    if ((int)$stmt->fetchColumn() > 0) {
        $pdo->prepare("UPDATE barang SET status = 'nonaktif' WHERE id_barang = ?")->execute([$id]);
        header('Location: barang.php?sukses=nonaktif');
    } else {
        $pdo->prepare("DELETE FROM barang WHERE id_barang = ?")->execute([$id]);
        header('Location: barang.php?sukses=hapus');
    }
} catch (PDOException $e) {
    header('Location: barang.php?error=gagal');
}
exit;
