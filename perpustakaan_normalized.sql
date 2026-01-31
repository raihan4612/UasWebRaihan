-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Jan 2026 pada 01.16
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_normalized`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama`, `alamat`, `no_hp`, `status`) VALUES
(202501, 'Raihan', 'Handilbakti', '082250644437', 'Aktif'),
(202502, 'Rahma', 'Kayutangi', '085277214356', 'Aktif'),
(202503, 'Ahmad', 'Banjarmasin', '083254984380', 'Aktif'),
(202504, 'Nabila', 'Semangatdalam', '082152519911', 'Aktif'),
(202505, 'Dewi', 'Banjarmasin', '083133713371', 'Aktif'),
(202506, 'Rifki', 'HKSN', '081820040218', 'Aktif'),
(202507, 'Riyadi', 'Banjarbaru', '082711200323', 'Aktif'),
(202508, 'Ayu', 'S_Parman', '083003197050', 'Aktif'),
(202509, 'Farhan', 'Alalak', '081720197040', 'Aktif'),
(202510, 'Hasan', 'Handilbakti', '082199718800', 'Aktif'),
(202511, 'Siti', 'Banjarmasin', '085145876540', 'Aktif'),
(202512, 'Dani', 'Banjarbaru', '085612387261', 'Aktif'),
(202513, 'Sufi', 'Martapura', '081122334455', 'Aktif'),
(202514, 'Putri', 'Martapura', '086677889900', 'Aktif'),
(202515, 'Putra', 'Alalak', '081234567890', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `id_pengarang` int(11) NOT NULL,
  `id_penerbit` int(11) NOT NULL,
  `tahun` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `id_pengarang`, `id_penerbit`, `tahun`, `jumlah`) VALUES
(1, 'Belajar MySQL', 1, 7, 2021, 5),
(2, 'Pemrograman Web', 3, 7, 2020, 4),
(3, 'Laskar Pelangi', 2, 1, 2005, 6),
(4, 'Harry Potter 1', 3, 2, 1997, 8),
(5, 'Seni Berbicara', 6, 3, 2018, 5),
(6, 'Sejarah Dunia', 10, 14, 2015, 3),
(7, 'Basis Data Modern', 5, 7, 2022, 7),
(8, 'Psikologi Dasar', 11, 9, 2019, 5),
(9, 'Pemrograman Java', 4, 7, 2019, 8),
(10, 'Novel Bumi', 1, 3, 2018, 10),
(11, 'Ensiklopedia Dunia', 5, 15, 2017, 2),
(12, 'Anak Pintar', 7, 1, 2019, 9),
(13, 'Bisnis Online', 9, 12, 2021, 4),
(14, 'Aljabar Linear', 5, 7, 2020, 3),
(15, 'Kesehatan Mental', 6, 10, 2022, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_kategori`
--

CREATE TABLE `buku_kategori` (
  `id_buku_kategori` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku_kategori`
--

INSERT INTO `buku_kategori` (`id_buku_kategori`, `id_buku`, `id_kategori`) VALUES
(1, 1, 1),
(2, 1, 11),
(3, 2, 11),
(4, 3, 2),
(5, 4, 2),
(6, 5, 3),
(7, 6, 4),
(8, 7, 1),
(9, 7, 11),
(10, 8, 12),
(11, 9, 11),
(12, 10, 7),
(13, 11, 8),
(14, 12, 14),
(15, 13, 13),
(16, 14, 10),
(17, 15, 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_rak`
--

CREATE TABLE `buku_rak` (
  `id_buku_rak` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `id_rak` int(11) NOT NULL,
  `jumlah_di_rak` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku_rak`
--

INSERT INTO `buku_rak` (`id_buku_rak`, `id_buku`, `id_rak`, `jumlah_di_rak`) VALUES
(1, 1, 1, 5),
(2, 2, 2, 4),
(3, 3, 3, 6),
(4, 4, 4, 8),
(5, 5, 5, 5),
(6, 6, 6, 3),
(7, 7, 7, 7),
(8, 8, 8, 5),
(9, 9, 9, 8),
(10, 10, 10, 10),
(11, 11, 11, 2),
(12, 12, 12, 9),
(13, 13, 13, 4),
(14, 14, 14, 3),
(15, 15, 15, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `denda`
--

CREATE TABLE `denda` (
  `id_denda` int(11) NOT NULL,
  `id_detail_kembali` int(11) NOT NULL,
  `id_tarif` int(11) NOT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `denda`
--

INSERT INTO `denda` (`id_denda`, `id_detail_kembali`, `id_tarif`, `catatan`) VALUES
(1, 4, 1, 'Harry Potter 1 mengalami kerusakan ringan pada cover'),
(2, 6, 1, 'Pemrograman Web halaman sobek'),
(3, 7, 1, 'Sejarah Dunia cover terkelupas'),
(4, 8, 1, 'Ensiklopedia Dunia halaman berlipat'),
(5, 10, 1, 'Seni Berbicara tulisan coretan'),
(6, 11, 2, 'Anak Pintar halaman hilang beberapa lembar'),
(7, 12, 2, 'Kesehatan Mental cover rusak parah'),
(8, 13, 2, 'Bisnis Online basah dan berjamur'),
(9, 15, 1, 'Psikologi Dasar cover bengkok'),
(10, 16, 1, 'Harry Potter 1 halaman terlipat'),
(11, 18, 2, 'Kesehatan Mental robek halaman depan'),
(12, 19, 1, 'Laskar Pelangi punggung buku terkelupas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_pinjam` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_pinjam`, `id_buku`) VALUES
(1, 1, 1),
(2, 1, 7),
(3, 2, 3),
(4, 3, 4),
(5, 3, 10),
(6, 4, 2),
(7, 5, 6),
(8, 5, 11),
(9, 6, 9),
(10, 7, 5),
(11, 8, 12),
(12, 8, 15),
(13, 9, 13),
(14, 10, 14),
(15, 10, 8),
(16, 11, 4),
(17, 11, 1),
(18, 12, 15),
(19, 13, 3),
(20, 13, 12),
(21, 14, 14),
(22, 15, 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pengembalian`
--

CREATE TABLE `detail_pengembalian` (
  `id_detail_kembali` int(11) NOT NULL,
  `id_kembali` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `kondisi` varchar(20) DEFAULT 'Baik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pengembalian`
--

INSERT INTO `detail_pengembalian` (`id_detail_kembali`, `id_kembali`, `id_buku`, `kondisi`) VALUES
(1, 1, 1, 'Baik'),
(2, 1, 7, 'Baik'),
(3, 2, 3, 'Baik'),
(4, 3, 4, 'Rusak Ringan'),
(5, 3, 10, 'Baik'),
(6, 4, 2, 'Rusak Ringan'),
(7, 5, 6, 'Rusak Ringan'),
(8, 5, 11, 'Rusak Ringan'),
(9, 6, 9, 'Baik'),
(10, 7, 5, 'Rusak Ringan'),
(11, 8, 12, 'Rusak Berat'),
(12, 8, 15, 'Rusak Berat'),
(13, 9, 13, 'Rusak Berat'),
(14, 10, 14, 'Baik'),
(15, 10, 8, 'Rusak Ringan'),
(16, 11, 4, 'Rusak Ringan'),
(17, 11, 1, 'Baik'),
(18, 12, 15, 'Rusak Berat'),
(19, 13, 3, 'Rusak Ringan'),
(20, 13, 12, 'Baik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_buku`
--

INSERT INTO `kategori_buku` (`id_kategori`, `nama_kategori`) VALUES
(15, 'Agama'),
(14, 'Anak-anak'),
(13, 'Bisnis'),
(2, 'Fiksi'),
(6, 'Komik'),
(11, 'Komputer'),
(10, 'Matematika'),
(3, 'Non-Fiksi'),
(7, 'Novel'),
(5, 'Pendidikan'),
(12, 'Psikologi'),
(8, 'Referensi'),
(9, 'Sains'),
(4, 'Sejarah'),
(1, 'Teknologi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_harus_kembali` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_anggota`, `id_petugas`, `tanggal_pinjam`, `tanggal_harus_kembali`) VALUES
(1, 202501, 2, '2025-01-05', '2025-01-12'),
(2, 202503, 3, '2025-01-06', '2025-01-13'),
(3, 202504, 1, '2025-01-07', '2025-01-14'),
(4, 202506, 5, '2025-01-08', '2025-01-15'),
(5, 202507, 6, '2025-01-10', '2025-01-17'),
(6, 202509, 8, '2025-01-12', '2025-01-19'),
(7, 202510, 4, '2025-01-13', '2025-01-20'),
(8, 202512, 7, '2025-01-14', '2025-01-21'),
(9, 202514, 9, '2025-01-15', '2025-01-22'),
(10, 202515, 10, '2025-01-16', '2025-01-23'),
(11, 202502, 3, '2025-01-17', '2025-01-24'),
(12, 202508, 5, '2025-01-18', '2025-01-25'),
(13, 202511, 7, '2025-01-19', '2025-01-26'),
(14, 202510, 4, '2026-01-31', '2026-02-07'),
(15, 202510, 4, '2026-01-31', '2026-02-07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `nama`, `kota`) VALUES
(1, 'Erlangga', 'Jakarta'),
(2, 'Gramedia', 'Jakarta'),
(3, 'Mizan', 'Bandung'),
(4, 'Andi Offset', 'Yogyakarta'),
(5, 'Deepublish', 'Yogyakarta'),
(6, 'Media Kita', 'Jakarta'),
(7, 'Informatika', 'Bandung'),
(8, 'Republika', 'Jakarta'),
(9, 'PT Remaja Rosdakarya', 'Bandung'),
(10, 'Pustaka Setia', 'Bandung'),
(11, 'Pustaka Pelajar', 'Jogja'),
(12, 'Springer', 'Berlin'),
(13, 'Oxford Press', 'London'),
(14, 'McGraw Hill', 'New York'),
(15, 'Pearson', 'London');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengarang`
--

CREATE TABLE `pengarang` (
  `id_pengarang` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengarang`
--

INSERT INTO `pengarang` (`id_pengarang`, `nama`) VALUES
(1, 'Tere Liye'),
(2, 'Andrea Hirata'),
(3, 'JK Rowling'),
(4, 'Habiburrahman El Shirazy'),
(5, 'Dewi Lestari'),
(6, 'Asma Nadia'),
(7, 'Raditya Dika'),
(8, 'Pramoedya Ananta Toer'),
(9, 'Mario Puzo'),
(10, 'Yuval Noah Harari'),
(11, 'George Orwell'),
(12, 'Dan Brown'),
(13, 'Rick Riordan'),
(14, 'Neil Gaiman'),
(15, 'Agatha Christie');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_kembali` int(11) NOT NULL,
  `id_pinjam` int(11) NOT NULL,
  `tanggal_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`id_kembali`, `id_pinjam`, `tanggal_kembali`) VALUES
(1, 1, '2025-01-12'),
(2, 2, '2025-01-11'),
(3, 3, '2025-01-14'),
(4, 4, '2025-01-12'),
(5, 5, '2025-01-18'),
(6, 6, '2025-01-20'),
(7, 7, '2025-01-18'),
(8, 8, '2025-01-20'),
(9, 9, '2025-01-22'),
(10, 10, '2025-01-23'),
(11, 11, '2025-01-25'),
(12, 12, '2025-01-26'),
(13, 13, '2025-01-27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama`, `jabatan`, `no_hp`) VALUES
(1, 'Budi', 'Admin', '081264826572'),
(2, 'Santi', 'Pustakawan', '0865724133254'),
(3, 'Rudi', 'Pustakawan', '086476870386'),
(4, 'Lilis', 'Admin', '082126647382'),
(5, 'Fajar', 'Staff', '087584726375'),
(6, 'Dina', 'Staff', '084767634834'),
(7, 'Bagas', 'Staff', '089686858396'),
(8, 'Maya', 'Pustakawan', '0867552436519'),
(9, 'Seno', 'Staff', '085451637462'),
(10, 'Wati', 'Admin', '089475763516'),
(11, 'Fani', 'Staff', '089783647582'),
(12, 'Andi', 'Pustakawan', '086496763847'),
(13, 'Ayu', 'Staff', '086465732817'),
(14, 'Ilham', 'Admin', '086586295736'),
(15, 'Rina', 'Pustakawan', '085673928375');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak`
--

CREATE TABLE `rak` (
  `id_rak` int(11) NOT NULL,
  `kode_rak` varchar(20) NOT NULL,
  `lokasi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rak`
--

INSERT INTO `rak` (`id_rak`, `kode_rak`, `lokasi`) VALUES
(1, 'R01', 'Lantai 1'),
(2, 'R02', 'Lantai 1'),
(3, 'R03', 'Lantai 1'),
(4, 'R04', 'Lantai 2'),
(5, 'R05', 'Lantai 2'),
(6, 'R06', 'Lantai 2'),
(7, 'R07', 'Lantai 3'),
(8, 'R08', 'Lantai 3'),
(9, 'R09', 'Lantai 3'),
(10, 'R10', 'Lantai 1'),
(11, 'R11', 'Lantai 2'),
(12, 'R12', 'Lantai 3'),
(13, 'R13', 'Lantai 1'),
(14, 'R14', 'Lantai 2'),
(15, 'R15', 'Lantai 3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif_denda`
--

CREATE TABLE `tarif_denda` (
  `id_tarif` int(11) NOT NULL,
  `jenis_kerusakan` varchar(50) NOT NULL,
  `jumlah_denda` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tarif_denda`
--

INSERT INTO `tarif_denda` (`id_tarif`, `jenis_kerusakan`, `jumlah_denda`) VALUES
(1, 'Rusak Ringan', 10000.00),
(2, 'Rusak Berat', 25000.00),
(3, 'Hilang', 50000.00);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `idx_anggota_nama` (`nama`),
  ADD KEY `idx_anggota_status` (`status`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `fk_buku_pengarang` (`id_pengarang`),
  ADD KEY `fk_buku_penerbit` (`id_penerbit`),
  ADD KEY `idx_buku_judul` (`judul`),
  ADD KEY `idx_buku_tahun` (`tahun`);

--
-- Indeks untuk tabel `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD PRIMARY KEY (`id_buku_kategori`),
  ADD UNIQUE KEY `uq_buku_kategori` (`id_buku`,`id_kategori`),
  ADD KEY `fk_buku_kategori_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `buku_rak`
--
ALTER TABLE `buku_rak`
  ADD PRIMARY KEY (`id_buku_rak`),
  ADD UNIQUE KEY `uq_buku_rak` (`id_buku`,`id_rak`),
  ADD KEY `fk_buku_rak_rak` (`id_rak`);

--
-- Indeks untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD UNIQUE KEY `uq_denda_detail_kembali` (`id_detail_kembali`),
  ADD KEY `fk_denda_tarif` (`id_tarif`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_peminjaman_pinjam` (`id_pinjam`),
  ADD KEY `fk_detail_peminjaman_buku` (`id_buku`);

--
-- Indeks untuk tabel `detail_pengembalian`
--
ALTER TABLE `detail_pengembalian`
  ADD PRIMARY KEY (`id_detail_kembali`),
  ADD KEY `fk_detail_kembali_pengembalian` (`id_kembali`),
  ADD KEY `fk_detail_kembali_buku` (`id_buku`);

--
-- Indeks untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`),
  ADD KEY `idx_kategori_nama` (`nama_kategori`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `fk_peminjaman_anggota` (`id_anggota`),
  ADD KEY `fk_peminjaman_petugas` (`id_petugas`),
  ADD KEY `idx_peminjaman_tanggal` (`tanggal_pinjam`);

--
-- Indeks untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indeks untuk tabel `pengarang`
--
ALTER TABLE `pengarang`
  ADD PRIMARY KEY (`id_pengarang`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_kembali`),
  ADD UNIQUE KEY `id_pinjam` (`id_pinjam`),
  ADD KEY `idx_pengembalian_tanggal` (`tanggal_kembali`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indeks untuk tabel `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`id_rak`),
  ADD UNIQUE KEY `kode_rak` (`kode_rak`);

--
-- Indeks untuk tabel `tarif_denda`
--
ALTER TABLE `tarif_denda`
  ADD PRIMARY KEY (`id_tarif`),
  ADD UNIQUE KEY `jenis_kerusakan` (`jenis_kerusakan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202516;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `buku_kategori`
--
ALTER TABLE `buku_kategori`
  MODIFY `id_buku_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `buku_rak`
--
ALTER TABLE `buku_rak`
  MODIFY `id_buku_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `detail_pengembalian`
--
ALTER TABLE `detail_pengembalian`
  MODIFY `id_detail_kembali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `pengarang`
--
ALTER TABLE `pengarang`
  MODIFY `id_pengarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_kembali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `rak`
--
ALTER TABLE `rak`
  MODIFY `id_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tarif_denda`
--
ALTER TABLE `tarif_denda`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_buku_penerbit` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buku_pengarang` FOREIGN KEY (`id_pengarang`) REFERENCES `pengarang` (`id_pengarang`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD CONSTRAINT `fk_buku_kategori_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buku_kategori_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_buku` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `buku_rak`
--
ALTER TABLE `buku_rak`
  ADD CONSTRAINT `fk_buku_rak_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buku_rak_rak` FOREIGN KEY (`id_rak`) REFERENCES `rak` (`id_rak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `fk_denda_detail_kembali` FOREIGN KEY (`id_detail_kembali`) REFERENCES `detail_pengembalian` (`id_detail_kembali`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_denda_tarif` FOREIGN KEY (`id_tarif`) REFERENCES `tarif_denda` (`id_tarif`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_peminjaman_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_peminjaman_pinjam` FOREIGN KEY (`id_pinjam`) REFERENCES `peminjaman` (`id_pinjam`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pengembalian`
--
ALTER TABLE `detail_pengembalian`
  ADD CONSTRAINT `fk_detail_kembali_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_kembali_pengembalian` FOREIGN KEY (`id_kembali`) REFERENCES `pengembalian` (`id_kembali`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `fk_pengembalian_pinjam` FOREIGN KEY (`id_pinjam`) REFERENCES `peminjaman` (`id_pinjam`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
