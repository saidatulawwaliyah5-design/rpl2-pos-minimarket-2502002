<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $pdo->prepare("DELETE FROM supplier WHERE id_supplier = ?")->execute([$id]);
        header('Location: supplier.php?sukses=hapus');
        exit;
    } catch (PDOException $e) {
        header('Location: supplier.php?error=fk');
        exit;
    }
}
header('Location: supplier.php');
exit;
