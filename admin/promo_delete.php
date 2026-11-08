<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM promo WHERE id_promo = ?");
    $stmt->execute([$id]);
}
header('Location: promo.php?sukses=hapus');
exit;
