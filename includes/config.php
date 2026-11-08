<?php
/**
 * KONFIGURASI DATABASE
 * Sesuaikan kalau username/password MySQL kamu berbeda dari default XAMPP.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'si_market_lia_040626');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()) .
        '<br>Pastikan XAMPP (Apache & MySQL) sudah menyala dan database si_market_lia_040626 sudah diimport.');
}
