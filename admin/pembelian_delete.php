<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM detail_pembelian WHERE id_pembelian = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM pembelian WHERE id_pembelian = ?")->execute([$id]);
        $pdo->commit();
        header('Location: pembelian.php?sukses=hapus');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: pembelian.php?error=fk');
        exit;
    }
}
header('Location: pembelian.php');
exit;
