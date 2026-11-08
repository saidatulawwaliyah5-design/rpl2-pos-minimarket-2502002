<?php
function rupiah($angka): string {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function e($str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function tanggalIndo($tanggal): string {
    if (!$tanggal) return '-';
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $t = strtotime($tanggal);
    return date('d', $t) . ' ' . $bulan[(int)date('n', $t)] . ' ' . date('Y', $t) . ' ' . date('H:i', $t);
}

function tanggalIndoTanggal($tanggal): string {
    if (!$tanggal) return '-';
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $t = strtotime($tanggal);
    return date('d', $t) . ' ' . $bulan[(int)date('n', $t)] . ' ' . date('Y', $t);
}

function kategoriIcon(?string $namaJenis): string {
    $n = strtolower((string)$namaJenis);
    if (str_contains($n, 'sembako')) return '🌾';
    if (str_contains($n, 'minuman')) return '🥤';
    if (str_contains($n, 'snack') || str_contains($n, 'makanan ringan')) return '🍪';
    if (str_contains($n, 'alat tulis')) return '✏️';
    if (str_contains($n, 'rumah tangga')) return '🧹';
    if (str_contains($n, 'perawatan') || str_contains($n, 'kosmetik')) return '🧴';
    return '📦';
}

function kategoriWarna(?string $namaJenis): string {
    $n = strtolower((string)$namaJenis);
    if (str_contains($n, 'sembako')) return '#FEF3C7';
    if (str_contains($n, 'minuman')) return '#DBEAFE';
    if (str_contains($n, 'snack') || str_contains($n, 'makanan ringan')) return '#FFE4E6';
    if (str_contains($n, 'alat tulis')) return '#DCFCE7';
    if (str_contains($n, 'rumah tangga')) return '#E0E7FF';
    if (str_contains($n, 'perawatan') || str_contains($n, 'kosmetik')) return '#F3E8FF';
    return '#F1F5F9';
}

/**
 * Status stok berdasarkan aturan inti: stok <= stok_minimum = peringatan.
 * Mengembalikan [label, kelas badge]. Status dibedakan dengan teks, bukan warna saja.
 */
function statusStok($stok, $min): array {
    $stok = (int)$stok; $min = (int)$min;
    if ($stok <= 0)     return ['Stok habis', 'badge-low'];
    if ($stok < $min)   return ['Di bawah minimum', 'badge-low'];
    if ($stok === $min) return ['Sama dengan minimum', 'badge-warn'];
    return ['Aman', 'badge-ok'];
}

function jumlahStokMenipis(PDO $pdo): int {
    return (int)$pdo->query("SELECT COUNT(*) FROM v_peringatan_stok")->fetchColumn();
}

/** Token CSRF sederhana untuk aksi yang mengubah data (hapus/aktifkan). */
function csrfToken(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrfCheck(): void {
    $ok = $_SERVER['REQUEST_METHOD'] === 'POST'
       && !empty($_POST['csrf'])
       && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
    if (!$ok) { http_response_code(400); die('Permintaan tidak valid.'); }
}

