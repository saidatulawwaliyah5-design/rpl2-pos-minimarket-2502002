# Lia Belanja Bersama — POS Minimarket dengan Peringatan Stok Minimum

Sistem Point of Sale (POS) berbasis web untuk toko retail, dilengkapi fitur **peringatan stok minimum**.
Dibuat sebagai tugas individu mata kuliah Rekayasa Perangkat Lunak 2 (RPL-2), Program Studi D3 Teknik Informatika, Politeknik Purbaya.

- Nama: Saidatul Awwaliyah
- NIM: 2502002 — Kelas TI 3A
- Dosen pengampu: Fahrudin M.Kom

> Aplikasi ini adalah prototipe akademik. Seluruh data di dalamnya adalah data dummy.

## Aturan inti

Jika **stok tersedia ≤ stok minimum**, sistem menampilkan peringatan. Batas stok minimum ditentukan per barang.

## Fitur

- Login dengan peran **admin** dan **kasir** (password disimpan dalam bentuk hash)
- Katalog publik: pencarian, filter kategori, harga promo
- Kelola barang: tambah, edit, cari, nonaktifkan/aktifkan
- Hapus cerdas: barang yang sudah pernah dipakai di transaksi dinonaktifkan (tidak dihapus permanen) agar riwayat tetap utuh
- Transaksi kasir: keranjang, validasi stok, total otomatis, struk cetak
- Stok berkurang otomatis setelah transaksi tersimpan (database transaction: commit/rollback)
- Halaman **Peringatan Stok Minimum**, badge di menu, kartu di dashboard, dan peringatan di struk setelah transaksi
- Promo, pelanggan/member, supplier, pembelian, retur, riwayat dan laporan penjualan (cetak dan Excel)
- Token CSRF pada aksi hapus dan aktifkan barang

## Teknologi

| Bagian | Teknologi |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP 8.2 (PDO, prepared statement) |
| Database | MariaDB 10.4 (view, trigger, foreign key) |
| Lingkungan | XAMPP, phpMyAdmin |

## Cara menjalankan

1. Pasang [XAMPP](https://www.apachefriends.org/) lalu nyalakan **Apache** dan **MySQL**.
2. Salin folder proyek ini ke `C:\xampp\htdocs\minimarket_lia`.
3. Buka `http://localhost/phpmyadmin`, buat database bernama `si_market_lia_040626` (collation `utf8mb4_general_ci`).
4. Pada database tersebut, buka tab **Import** lalu pilih `database/si_market_lia_040626_LENGKAP.sql`.
5. Periksa `includes/config.php` (bawaan XAMPP: user `root`, tanpa password).
6. Buka `http://localhost/minimarket_lia/`.

Akun demo: username `admin` (administrator) dan `kasir` (kasir). Password akun demo diberikan saat demonstrasi.

## Struktur folder

```
minimarket_lia/
├── index.php          Katalog publik
├── login.php          Halaman login
├── admin/             Panel admin: dashboard, barang, peringatan stok, promo, laporan, dll.
├── kasir/             Panel kasir: transaksi (POS), struk, riwayat
├── includes/          Konfigurasi, autentikasi, fungsi bantu, header/footer
├── assets/            CSS dan gambar
└── database/          Dump SQL lengkap (struktur, data dummy, view, trigger)
```

## Database

- 17 tabel dengan foreign key, 2 view, dan 1 trigger.
- Kolom penting pada tabel `barang`: `stok`, `stok_minimum`, `status` (aktif/nonaktif).
- View `v_peringatan_stok`: barang aktif dengan stok ≤ stok minimum.
- View `v_barang_terpakai`: barang yang sudah dipakai di transaksi, pembelian, retur, promo, atau riwayat stok.

## Status dan keterbatasan

- Pengujian fungsional (Black Box Testing) dikerjakan setelah UTS.
- Aplikasi berupa PHP server-rendered, belum menyediakan REST API maupun JWT.
- Peran Owner/Pimpinan dan aplikasi mobile masih berupa rancangan.
- Belum terhubung dengan pembayaran online maupun sistem eksternal.
