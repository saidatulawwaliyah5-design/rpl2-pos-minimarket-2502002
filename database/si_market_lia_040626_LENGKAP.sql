-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2026 at 04:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_market_lia_040626`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(50) DEFAULT NULL,
  `id_jenis` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `harga_jual` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `id_jenis`, `stok`, `harga_jual`, `created_at`, `updated_at`) VALUES
(1, 'Beras 5kg', 10, 14, 71500.00, '2026-08-02 02:04:14', '2026-08-06 00:34:33'),
(2, 'Gula Pasir', 10, 50, 16500.00, '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(3, 'Minyak Goreng', 10, 20, 30800.00, '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(4, 'Sabun Cuci', 20, 10, 5000.00, '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(5, 'Shampoo', 20, 15, 12000.00, '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(20, 'Paket Sembako Hemat', 10, 50, 22000.00, '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(21, 'Mie Instan Goreng', 10, 100, 3500.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(22, 'Teh Celup Kotak', 10, 40, 8500.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(23, 'Kopi Sachet Renceng', 10, 60, 12000.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(24, 'Air Mineral 600ml', 40, 79, 4000.00, '2026-08-06 00:31:03', '2026-08-06 00:34:33'),
(25, 'Teh Botol Kemasan', 40, 50, 5500.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(26, 'Kerupuk Bawang', 50, 35, 9000.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(27, 'Biskuit Kaleng', 50, 24, 18500.00, '2026-08-06 00:31:03', '2026-08-06 00:34:33'),
(28, 'Pulpen Standar', 60, 120, 2500.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(29, 'Buku Tulis 38 Lembar', 60, 90, 4500.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03'),
(30, 'Pasta Gigi 190gr', 30, 30, 11000.00, '2026-08-06 00:31:03', '2026-08-06 00:31:03');

--
-- Triggers `barang`
--
DELIMITER $$
CREATE TRIGGER `setelah_update_barang` AFTER UPDATE ON `barang` FOR EACH ROW BEGIN
    INSERT INTO log_activity (nm_user, aksi, keterangan)
    VALUES ('SYSTEM', 'AUTO-UPDATE', CONCAT('Barang ', OLD.nama_barang, ' diubah stoknya'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_detail_beli` int(11) NOT NULL,
  `id_pembelian` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail_jual` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail_jual`, `id_penjualan`, `id_barang`, `qty`, `subtotal`) VALUES
(1, 3, 20, 1, 20000.00),
(2, 4, 20, 1, 20000.00),
(3, 5, 24, 1, 4000.00),
(4, 5, 1, 1, 71500.00),
(5, 5, 27, 1, 18500.00);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`) VALUES
(10, 'Sembako'),
(20, 'Kebutuhan Rumah Tangga'),
(30, 'Perawatan Diri'),
(40, 'Minuman'),
(50, 'Snack & Makanan Ringan'),
(60, 'Alat Tulis');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `jabatan` enum('admin','kasir') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `username`, `password`, `jabatan`, `created_at`, `updated_at`) VALUES
(25009, 'Saidatul Awwaliyah', 'admin', '$2y$10$rEEpn8mkDlYIw40i3dsXVucMkxvo3PFvBinqVL9Lsq4ec.WFvmWOi', 'admin', '2026-08-02 02:04:14', '2026-08-06 00:40:44'),
(25010, 'Kasir Toko', 'kasir', '$2y$10$pICkCADzS8muVU95IgX.aOWHnlX90Cw8Aoc21ABrvnpL6YkR0K6Hu', 'kasir', '2026-08-06 00:19:25', '2026-08-06 00:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `id_log` int(11) NOT NULL,
  `nm_user` varchar(50) DEFAULT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_activity`
--

INSERT INTO `log_activity` (`id_log`, `nm_user`, `aksi`, `keterangan`, `waktu`) VALUES
(5, 'SYSTEM', 'AUTO-UPDATE', 'Barang Beras 5kg diubah stoknya', '2026-05-01 12:10:15'),
(6, 'SYSTEM', 'AUTO-UPDATE', 'Barang Beras 5kg diubah stoknya', '2026-05-01 12:31:16'),
(7, 'SYSTEM', 'AUTO-UPDATE', 'Barang Gula Pasir diubah stoknya', '2026-05-01 12:31:16'),
(8, 'SYSTEM', 'AUTO-UPDATE', 'Barang Minyak Goreng diubah stoknya', '2026-05-01 12:31:16'),
(9, 'SYSTEM', 'AUTO-UPDATE', 'Barang Paket Sembako Hemat diubah stoknya', '2026-05-01 12:31:16'),
(10, 'SYSTEM', 'AUTO-UPDATE', 'Barang Sabun Cuci diubah stoknya', '2026-05-01 12:31:21'),
(11, 'SYSTEM', 'AUTO-UPDATE', 'Barang Shampoo diubah stoknya', '2026-05-01 12:31:21'),
(12, 'SYSTEM', 'AUTO-UPDATE', 'Barang Beras 5kg diubah stoknya', '2026-05-07 06:43:08'),
(13, 'SYSTEM', 'AUTO-UPDATE', 'Barang Beras 5kg diubah stoknya', '2026-08-02 02:08:05'),
(14, 'SYSTEM', 'AUTO-UPDATE', 'Barang Air Mineral 600ml diubah stoknya', '2026-08-06 00:34:33'),
(15, 'SYSTEM', 'AUTO-UPDATE', 'Barang Beras 5kg diubah stoknya', '2026-08-06 00:34:33'),
(16, 'SYSTEM', 'AUTO-UPDATE', 'Barang Biskuit Kaleng diubah stoknya', '2026-08-06 00:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `log_perubahan_struktur`
--

CREATE TABLE `log_perubahan_struktur` (
  `id_log_perubahan` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT curdate(),
  `bagian_diubah` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `diubah_oleh` varchar(50) DEFAULT 'Saidatul Awwaliyah'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_perubahan_struktur`
--

INSERT INTO `log_perubahan_struktur` (`id_log_perubahan`, `tanggal`, `bagian_diubah`, `keterangan`, `diubah_oleh`) VALUES
(1, '2026-06-04', 'Struktur Awal', 'Dump awal database dibuat dari phpMyAdmin (masih ada tabel jenis & jenis_barang terpisah).', 'Saidatul Awwaliyah'),
(2, '2026-07-08', 'Tabel jenis & jenis_barang', 'Tabel jenis_barang (duplikat) dihapus, seluruh FK yang tadinya menunjuk ke jenis_barang dialihkan ke tabel jenis.', 'Saidatul Awwaliyah'),
(3, '2026-07-08', 'Tabel promo', 'Ditambahkan Foreign Key promo.id_barang -> barang.id_barang yang sebelumnya belum ada.', 'Saidatul Awwaliyah'),
(4, '2026-07-08', 'Semua Foreign Key', 'Nama seluruh constraint FK dirapikan agar unik satu database (prefix fk_), menghindari bentrok nama.', 'Saidatul Awwaliyah'),
(5, '2026-08-02', 'Kolom Audit & Changelog', 'Ditambahkan kolom created_at & updated_at pada tabel master (barang, promo, karyawan, pelanggan, supplier, member) serta tabel log_perubahan_struktur, sesuai saran dosen pembimbing.', 'Saidatul Awwaliyah'),
(6, '2026-08-06', 'Tabel karyawan', 'Ditambahkan kolom username & password untuk login website (admin/kasir).', 'Saidatul Awwaliyah'),
(7, '2026-08-06', 'Tabel jenis', 'Nama kategori id_jenis=20 diperbaiki dari \"Alat Tulis\" menjadi \"Kebutuhan Rumah Tangga\" agar sesuai isinya (Sabun Cuci, Shampoo). Ditambahkan 3 kategori baru: Minuman, Snack & Makanan Ringan, Alat Tulis.', 'Saidatul Awwaliyah'),
(8, '2026-08-06', 'Tabel barang', 'Ditambahkan 10 produk baru tersebar di berbagai kategori untuk melengkapi katalog toko.', 'Saidatul Awwaliyah'),
(9, '2026-08-08', 'Tabel riwayat_poin', 'Tabel baru untuk mencatat histori penambahan/pengurangan poin member.', 'Saidatul Awwaliyah');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_member` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `poin` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(50) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'Pembeli Umum', '08123456789', '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(10, 'Siti Aminah', '0812345678', '2026-08-02 02:04:14', '2026-08-02 02:04:14'),
(11, 'Susi Susanti', '0811111111', '2026-08-02 02:04:14', '2026-08-02 02:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `tgl_beli` date DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tgl_jual` datetime DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_karyawan` int(11) DEFAULT NULL,
  `jenis_transaksi` enum('Kredit','Debet','Tunai') DEFAULT 'Tunai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `tgl_jual`, `id_pelanggan`, `id_karyawan`, `jenis_transaksi`) VALUES
(3, '2009-11-21 00:00:00', 10, 25009, 'Kredit'),
(4, '2009-11-21 00:00:00', 11, 25009, 'Debet'),
(5, '2026-08-06 07:34:33', 1, 25010, 'Tunai');

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id_promo` int(11) NOT NULL,
  `nama_promo` varchar(50) DEFAULT NULL,
  `diskon_persen` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id_promo`, `nama_promo`, `diskon_persen`, `id_barang`, `created_at`, `updated_at`) VALUES
(1, 'diskon akhir bulan', 5, 1, '2026-08-06 00:43:07', '2026-08-06 00:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `id_retur` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `tgl_retur` date DEFAULT NULL,
  `alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_poin`
--

CREATE TABLE `riwayat_poin` (
  `id_riwayat_poin` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `perubahan` int(11) NOT NULL COMMENT 'positif = tambah poin, negatif = pakai poin',
  `keterangan` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id_riwayat` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `tgl_perubahan` datetime DEFAULT current_timestamp(),
  `aksi` varchar(20) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_jenis` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_stok`
--

INSERT INTO `riwayat_stok` (`id_riwayat`, `id_barang`, `tgl_perubahan`, `aksi`, `jumlah`, `id_jenis`) VALUES
(1, 1, '2026-05-01 19:35:43', 'MASUK', 10, 10),
(2, 4, '2026-05-01 19:35:43', 'KELUAR', 2, 20);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(50) DEFAULT NULL,
  `alamat_supplier` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `idx_barang_jenis` (`id_jenis`);

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_detail_beli`),
  ADD KEY `idx_detbeli_pembelian` (`id_pembelian`),
  ADD KEY `idx_detbeli_barang` (`id_barang`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail_jual`),
  ADD KEY `idx_detjual_penjualan` (`id_penjualan`),
  ADD KEY `idx_detjual_barang` (`id_barang`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_dettrans_barang` (`id_barang`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `uk_karyawan_username` (`username`);

--
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `log_perubahan_struktur`
--
ALTER TABLE `log_perubahan_struktur`
  ADD PRIMARY KEY (`id_log_perubahan`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`),
  ADD KEY `idx_member_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `idx_pembelian_supplier` (`id_supplier`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `idx_penjualan_pelanggan` (`id_pelanggan`),
  ADD KEY `idx_penjualan_karyawan` (`id_karyawan`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id_promo`),
  ADD KEY `idx_promo_barang` (`id_barang`);

--
-- Indexes for table `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`id_retur`),
  ADD KEY `idx_retur_barang` (`id_barang`);

--
-- Indexes for table `riwayat_poin`
--
ALTER TABLE `riwayat_poin`
  ADD PRIMARY KEY (`id_riwayat_poin`),
  ADD KEY `idx_riwayatpoin_member` (`id_member`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `idx_riwayat_barang` (`id_barang`),
  ADD KEY `idx_riwayat_jenis` (`id_jenis`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_detail_beli` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail_jual` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25011;

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `log_perubahan_struktur`
--
ALTER TABLE `log_perubahan_struktur`
  MODIFY `id_log_perubahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `retur`
--
ALTER TABLE `retur`
  MODIFY `id_retur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_poin`
--
ALTER TABLE `riwayat_poin`
  MODIFY `id_riwayat_poin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_jenis` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`);

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `fk_detbeli_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_detbeli_pembelian` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`);

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `fk_detjual_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_detjual_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `fk_dettrans_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `fk_member_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `fk_pembelian_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `fk_penjualan_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`),
  ADD CONSTRAINT `fk_penjualan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `promo`
--
ALTER TABLE `promo`
  ADD CONSTRAINT `fk_promo_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `retur`
--
ALTER TABLE `retur`
  ADD CONSTRAINT `fk_retur_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `riwayat_poin`
--
ALTER TABLE `riwayat_poin`
  ADD CONSTRAINT `fk_riwayatpoin_member` FOREIGN KEY (`id_member`) REFERENCES `member` (`id_member`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `fk_riwayat_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_riwayat_jenis` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`);
COMMIT;

-- =====================================================================
-- PEMBARUAN: PERINGATAN STOK MINIMUM + SOFT DELETE BARANG
-- Database : si_market_lia_040626   (MariaDB 10.4.32 / XAMPP)
-- Disusun dari dump Anda (si_market_lia_040626 per 8 Agu 2026).
-- Aman dijalankan ulang (memakai IF NOT EXISTS / OR REPLACE).
-- Cara pakai: phpMyAdmin > pilih database > tab SQL > tempel > Go.
-- Backup dulu (Export) sebelum menjalankan.
-- =====================================================================

-- 1. Kolom baru pada tabel barang
--    stok_minimum : batas peringatan PER BARANG (default 10 agar setara
--                   dengan aturan lama "stok < 10"; CHECK >= 0)
--    status       : aktif / nonaktif (pengganti hapus permanen)
ALTER TABLE `barang`
  ADD COLUMN IF NOT EXISTS `stok_minimum` INT(11) NOT NULL DEFAULT 10
      CHECK (`stok_minimum` >= 0) AFTER `stok`,
  ADD COLUMN IF NOT EXISTS `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
      AFTER `harga_jual`;

-- 2. Indeks untuk daftar barang aktif dan pencarian nama
ALTER TABLE `barang`
  ADD INDEX IF NOT EXISTS `idx_barang_status` (`status`),
  ADD INDEX IF NOT EXISTS `idx_barang_nama` (`nama_barang`);

-- 3. VIEW peringatan stok minimum
--    Aturan inti proposal: tampil jika stok <= stok_minimum,
--    hanya barang berstatus aktif. Stok NULL dianggap 0.
CREATE OR REPLACE VIEW `v_peringatan_stok` AS
SELECT
  b.`id_barang`,
  b.`nama_barang`,
  j.`nama_jenis`,
  COALESCE(b.`stok`, 0) AS `stok`,
  b.`stok_minimum`,
  (COALESCE(b.`stok`, 0) - b.`stok_minimum`) AS `selisih`,
  CASE
    WHEN COALESCE(b.`stok`, 0) <= 0              THEN 'Stok habis'
    WHEN COALESCE(b.`stok`, 0) <  b.`stok_minimum` THEN 'Di bawah minimum'
    ELSE                                              'Sama dengan minimum'
  END AS `status_peringatan`
FROM `barang` b
LEFT JOIN `jenis` j ON j.`id_jenis` = b.`id_jenis`
WHERE b.`status` = 'aktif'
  AND COALESCE(b.`stok`, 0) <= b.`stok_minimum`;

-- 4. VIEW barang yang sudah terpakai di tabel lain.
--    Dipakai fitur Hapus: jika id_barang ada di sini -> NONAKTIFKAN,
--    jika tidak -> boleh dihapus permanen. Sesuai FK pada dump Anda
--    (detail_penjualan, detail_pembelian, detail_transaksi, retur,
--    promo, riwayat_stok).
CREATE OR REPLACE VIEW `v_barang_terpakai` AS
SELECT DISTINCT `id_barang` FROM (
  SELECT `id_barang` FROM `detail_penjualan`
  UNION ALL SELECT `id_barang` FROM `detail_pembelian`
  UNION ALL SELECT `id_barang` FROM `detail_transaksi`
  UNION ALL SELECT `id_barang` FROM `retur`
  UNION ALL SELECT `id_barang` FROM `promo`
  UNION ALL SELECT `id_barang` FROM `riwayat_stok`
) t WHERE `id_barang` IS NOT NULL;

-- 5. Perbaiki trigger lama. Trigger lama mencatat "diubah stoknya"
--    pada SETIAP update barang, termasuk saat hanya status atau
--    stok_minimum yang berubah. Versi baru mencatat sesuai perubahan.
DROP TRIGGER IF EXISTS `setelah_update_barang`;
DELIMITER $$
CREATE TRIGGER `setelah_update_barang` AFTER UPDATE ON `barang` FOR EACH ROW BEGIN
    IF NOT (OLD.stok <=> NEW.stok) THEN
        INSERT INTO log_activity (nm_user, aksi, keterangan)
        VALUES ('SYSTEM', 'AUTO-UPDATE',
                CONCAT('Barang ', OLD.nama_barang, ' diubah stoknya (',
                       IFNULL(OLD.stok, 0), ' -> ', IFNULL(NEW.stok, 0), ')'));
    END IF;
    IF NOT (OLD.status <=> NEW.status) THEN
        INSERT INTO log_activity (nm_user, aksi, keterangan)
        VALUES ('SYSTEM', 'STATUS-BARANG',
                CONCAT('Barang ', OLD.nama_barang, ' status: ', OLD.status, ' -> ', NEW.status));
    END IF;
    IF NOT (OLD.stok_minimum <=> NEW.stok_minimum) THEN
        INSERT INTO log_activity (nm_user, aksi, keterangan)
        VALUES ('SYSTEM', 'STOK-MINIMUM',
                CONCAT('Barang ', OLD.nama_barang, ' batas minimum: ',
                       OLD.stok_minimum, ' -> ', NEW.stok_minimum));
    END IF;
END
$$
DELIMITER ;

-- 6. Catat di changelog struktur
INSERT INTO `log_perubahan_struktur`
  (`tanggal`, `bagian_diubah`, `keterangan`, `diubah_oleh`) VALUES
(CURDATE(), 'Tabel barang',
 'Ditambahkan kolom stok_minimum (batas peringatan per barang, CHECK >= 0) dan status (aktif/nonaktif untuk soft delete); ditambahkan indeks status dan nama_barang.',
 'Saidatul Awwaliyah'),
(CURDATE(), 'View v_peringatan_stok & v_barang_terpakai',
 'View baru: daftar peringatan stok (stok <= stok_minimum, barang aktif) dan daftar barang yang sudah terpakai di tabel lain (dasar keputusan hapus/nonaktifkan).',
 'Saidatul Awwaliyah'),
(CURDATE(), 'Trigger setelah_update_barang',
 'Trigger diperbarui: mencatat perubahan stok, status, dan stok_minimum secara terpisah dengan nilai lama dan baru.',
 'Saidatul Awwaliyah');

-- =====================================================================
-- KUERI UNTUK DIPAKAI DI PHP
-- =====================================================================
-- a) Halaman Peringatan Stok Minimum (paling kritis dulu):
--    SELECT * FROM v_peringatan_stok ORDER BY selisih ASC, nama_barang ASC;
-- b) Kartu dashboard:  SELECT COUNT(*) FROM v_peringatan_stok;
--    Daftar 5 teratas: SELECT nama_barang, stok FROM v_peringatan_stok
--                      ORDER BY selisih ASC LIMIT 5;
-- c) Daftar barang admin + pencarian (ganti ? dengan "%kata kunci%"):
--    SELECT b.*, j.nama_jenis FROM barang b
--    LEFT JOIN jenis j ON j.id_jenis = b.id_jenis
--    WHERE b.status = 'aktif'
--      AND (b.nama_barang LIKE ? OR j.nama_jenis LIKE ? OR b.id_barang = ?)
--    ORDER BY b.nama_barang ASC;
-- d) Hapus cerdas:
--    SELECT COUNT(*) FROM v_barang_terpakai WHERE id_barang = ?;
--    -- > 0 : UPDATE barang SET status = 'nonaktif' WHERE id_barang = ?;
--    -- = 0 : DELETE FROM barang WHERE id_barang = ?;
-- e) Katalog publik & POS: tambahkan  AND b.status = 'aktif'
--    (POS juga tetap  AND b.stok > 0 ).
-- f) Badge stok (ganti angka 10 yang tetap di dashboard, barang.php,
--    dan includes/product_card.php):
--    stok <= 0             -> "Stok habis"
--    stok <= stok_minimum  -> "Sisa N"
--    selain itu            -> "Stok tersedia"

-- =====================================================================
-- OPSIONAL: batas minimum per jenis (hapus "-- " untuk memakai)
-- =====================================================================
-- UPDATE barang SET stok_minimum = 20 WHERE id_jenis = 10;  -- Sembako
-- UPDATE barang SET stok_minimum = 15 WHERE id_jenis = 40;  -- Minuman
-- UPDATE barang SET stok_minimum = 5  WHERE id_jenis = 60;  -- Alat Tulis

-- =====================================================================
-- OPSIONAL: peran Owner/Pimpinan
-- karyawan.jabatan di database Anda bertipe ENUM('admin','kasir'),
-- jadi harus diubah dulu. Hash password HARUS dibuat dengan
-- password_hash() PHP, tidak boleh diketik manual:
--   <?php echo password_hash('PasswordOwner', PASSWORD_DEFAULT);
-- =====================================================================
-- ALTER TABLE `karyawan` MODIFY `jabatan` ENUM('admin','kasir','owner') DEFAULT NULL;
-- INSERT INTO `karyawan` (`nama_karyawan`, `username`, `password`, `jabatan`)
-- VALUES ('Owner Toko', 'owner', '<hash dari password_hash()>', 'owner');

-- =====================================================================
-- UJI NILAI BATAS (skenario 14-16 Rencana Pengujian)
-- Jalankan setelah pembaruan, lalu hapus lagi barang ujinya.
-- =====================================================================
-- INSERT INTO barang (nama_barang, id_jenis, stok, stok_minimum, harga_jual) VALUES
--   ('UJI-SAMA',  10, 5, 5, 1000),   -- stok = minimum
--   ('UJI-BAWAH', 10, 4, 5, 1000),   -- stok < minimum
--   ('UJI-ATAS',  10, 8, 5, 1000);   -- stok > minimum
-- SELECT * FROM v_peringatan_stok WHERE nama_barang LIKE 'UJI-%';
--   -- Diharapkan: hanya UJI-SAMA dan UJI-BAWAH muncul.
-- DELETE FROM barang WHERE nama_barang LIKE 'UJI-%';

-- =====================================================================
-- ROLLBACK (mengembalikan struktur lama)
-- =====================================================================
-- DROP VIEW IF EXISTS v_peringatan_stok;
-- DROP VIEW IF EXISTS v_barang_terpakai;
-- ALTER TABLE barang DROP INDEX IF EXISTS idx_barang_status;
-- ALTER TABLE barang DROP INDEX IF EXISTS idx_barang_nama;
-- ALTER TABLE barang DROP COLUMN IF EXISTS stok_minimum;
-- ALTER TABLE barang DROP COLUMN IF EXISTS status;
-- DROP TRIGGER IF EXISTS setelah_update_barang;
-- (lalu buat ulang trigger lama dari dump Anda)



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
