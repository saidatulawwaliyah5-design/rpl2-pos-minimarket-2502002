<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin(['admin']);
csrfCheck();

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare("UPDATE barang SET status = 'aktif' WHERE id_barang = ?")->execute([$id]);
    header('Location: barang.php?sukses=aktif');
    exit;
}
header('Location: barang.php');
exit;
