<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM member WHERE id_pelanggan = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?")->execute([$id]);
        $pdo->commit();
        header('Location: pelanggan.php?sukses=hapus');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: pelanggan.php?error=fk');
        exit;
    }
}
header('Location: pelanggan.php');
exit;
