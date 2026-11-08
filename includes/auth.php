<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['id_karyawan']);
}

function currentRole(): ?string {
    return $_SESSION['jabatan'] ?? null;
}

/**
 * Panggil di paling atas halaman yang butuh proteksi.
 * $allowedRoles kosong = semua role yang login boleh akses.
 */
function requireLogin(array $allowedRoles = []): void {
    if (!isLoggedIn()) {
        header('Location: ' . basePath() . 'login.php');
        exit;
    }
    if (!empty($allowedRoles) && !in_array(currentRole(), $allowedRoles, true)) {
        header('Location: ' . basePath() . 'akses_ditolak.php');
        exit;
    }
}

/**
 * Menentukan prefix path relatif ke root project,
 * supaya link tetap benar baik diakses dari /admin/, /kasir/, maupun root.
 */
function basePath(): string {
    return (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false
         || strpos($_SERVER['SCRIPT_NAME'], '/kasir/') !== false) ? '../' : '';
}
