<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare("DELETE FROM retur WHERE id_retur = ?")->execute([$id]);
}
header('Location: retur.php?sukses=hapus');
exit;
