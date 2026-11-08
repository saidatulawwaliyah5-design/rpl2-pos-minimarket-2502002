# Backlog dan Sprint Board — POS Minimarket dengan Peringatan Stok Minimum

Proyek individu RPL-2 — Saidatul Awwaliyah (NIM 2502002, TI 3A), Politeknik Purbaya.
Dosen pengampu: Fahrudin M.Kom.

Dokumen ini mencatat daftar pekerjaan (product backlog) dan statusnya per sprint.
Sprint 1 mencakup pertemuan 1–8 (sampai UTS), Sprint 2 mencakup pertemuan 9–16 (sampai UAS).

## Product backlog

| ID | User story | Prioritas | Sprint | Status |
|---|---|---|---|---|
| US-01 | Sebagai pengguna, saya ingin login agar hanya pengguna berwenang yang mengakses fitur sesuai peran | Tinggi | 1 | Selesai |
| US-02 | Sebagai administrator, saya ingin menambah, mengubah, mencari, dan menonaktifkan data barang agar informasi akurat dan riwayat transaksi utuh | Tinggi | 1 | Selesai |
| US-03 | Sebagai kasir, saya ingin mencatat transaksi penjualan agar penjualan tercatat dan total terhitung benar | Tinggi | 1 | Selesai |
| US-04 | Sebagai administrator/kasir, saya ingin stok berkurang otomatis setelah transaksi tersimpan agar stok selalu sesuai | Tinggi | 1 | Selesai |
| US-05 | Sebagai administrator/owner, saya ingin melihat peringatan stok minimum agar segera tahu barang yang perlu ditambah | Tinggi | 1 | Selesai |
| US-06 | Sebagai owner/pimpinan, saya ingin melihat riwayat transaksi dan laporan sederhana agar memantau penjualan per periode | Sedang | 1 | Selesai |
| BL-07 | Menjalankan 18 skenario Black Box Testing dan mencatat hasilnya | Tinggi | 2 | Belum |
| BL-08 | Menyusun unit test dan integration test untuk fungsi utama | Tinggi | 2 | Belum |
| BL-09 | UAT dengan pengguna uji dan pencatatan defect (severity, perbaikan, retest) | Sedang | 2 | Belum |
| BL-10 | Mengukur code coverage (indikator RPS minimal 70%) | Sedang | 2 | Belum |
| BL-11 | Melengkapi dokumentasi: README, panduan pengguna/admin, dokumentasi database | Sedang | 2 | Sebagian (README awal ada) |
| BL-12 | CI/CD dan deployment ke lingkungan hosting | Rendah | 2 | Belum |
| BL-13 | Menambah peran Owner/Pimpinan | Rendah | 2 | Belum |
| BL-14 | Menyediakan REST API dan JWT (dikonfirmasi dulu dengan dosen) | Rendah | 2 | Belum |

## Sprint board

### Sprint 1 — pertemuan 1–8 (sampai UTS)

| To Do | In Progress | Done |
|---|---|---|
| — | — | US-01 Login |
| | | US-02 Kelola data barang (tambah, edit, cari, nonaktifkan) |
| | | US-03 Transaksi penjualan |
| | | US-04 Stok berkurang otomatis |
| | | US-05 Peringatan stok minimum |
| | | US-06 Riwayat dan laporan |

### Sprint 2 — pertemuan 9–16 (sampai UAS)

| To Do | In Progress | Done |
|---|---|---|
| BL-07 Black Box Testing | BL-11 Dokumentasi | — |
| BL-08 Unit dan integration test | | |
| BL-09 UAT dan defect log | | |
| BL-10 Code coverage | | |
| BL-12 CI/CD dan deployment | | |
| BL-13 Peran Owner | | |
| BL-14 REST API dan JWT (konfirmasi dosen) | | |

## Definition of Done

Sebuah item dianggap selesai bila:
1. Fungsi berjalan sesuai kriteria penerimaan pada user story.
2. Kode sudah masuk ke repositori (branch `main`).
3. Fitur dapat didemonstrasikan dan memiliki bukti tampilan.
4. Untuk Sprint 2: sudah diuji dan hasilnya terdokumentasi.
