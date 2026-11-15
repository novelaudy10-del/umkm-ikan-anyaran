-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2026 at 01:23 PM
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
-- Database: `db_ikan_anyaran`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama_lengkap`, `username`, `password`, `last_login`) VALUES
(1, 'Pengelola Ikan Anyaran', 'admin', '$2y$10$uPuftIQbpxHF67FV80LVZOD//Tdw1DfiZr8scY7d1Rh7GQaGsdkFO', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_pelanggan`, `no_whatsapp`, `alamat_lengkap`, `email`, `password`) VALUES
(2, 'Novelia Audy Wijaya', '081222630615', 'Jl. Ciawilor, no.12, rt/rw 06/03, Ciawilor, Ciawigebang, Kuningan', 'novelaudy10@gmail.com', '335113e2306c836bb525c0c646776be5'),
(3, 'Kiki ', '087899008911', 'Desa Karangtaruna', 'kazumihana42@gmail.com', '26c87671e984c9882c36b7788c5c26c5'),
(4, 'Anette', '081133445566', 'Desa Babakan Reuma', 'adrishta@gmain.com', 'a87f93ca6e09beeedc040657f5322a86'),
(5, 'Dewi ', '089023456789', 'Desa Bangkit asih', 'noveliawijaya867@gmail.com', 'd271396307ef779493ebf5cfc31e758e'),
(6, 'Marsha Alika', '081234567891', 'jalan bahari, no 13, rt/rw 09/10, Kuningan, jawa barat', 'alikamarsh@gmail.com', '46b7ac0294b55508916274940faf2980'),
(7, 'Nana', '087788993322', 'Kuningan', 'Nana@gmail.com', '0808f74a0157925fee93996f1e21559f'),
(9, 'Andi Wijaya', '081234567890', 'Jl. Siliwangi No. 10', 'andi@mail.com', '482c811da5d5b4bc6d497ffa98491e38'),
(10, 'Ahmad ', '081987876565', 'desa cinghirup', 'ahmad@gmail.com', '82069e3f1fe1f29255c5a8ff790a6ada'),
(11, 'Yeni', '082345678910', 'Kuningan', 'yeni@gmail.com', '$2y$10$UMnCYl3bh1im8nCYVJUIyOR42Rem4eaHTA3J8jXR66mNPh0BYoeIW');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah_beli` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `status_pesanan` enum('Baru','Proses','Selesai','Dibatalkan') DEFAULT 'Baru',
  `alasan_batal` text DEFAULT NULL,
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `alasan_batal_user` text DEFAULT NULL,
  `persetujuan_batal` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_pelanggan`, `id_produk`, `jumlah_beli`, `total_bayar`, `status_pesanan`, `alasan_batal`, `tanggal_pesan`, `alasan_batal_user`, `persetujuan_batal`) VALUES
(1, 2, 5, 10, 90000, 'Dibatalkan', NULL, '2025-12-28 12:46:30', NULL, 'Disetujui'),
(7, 2, NULL, 0, 63000, 'Selesai', NULL, '2025-12-27 13:46:34', 'Berubah pikiran/Tidak jadi beli', 'Ditolak'),
(8, 2, NULL, 0, 54000, 'Selesai', NULL, '2025-12-27 14:01:22', NULL, 'Ditolak'),
(9, 2, NULL, 0, 22000, 'Dibatalkan', NULL, '2025-12-27 14:02:27', NULL, 'Disetujui'),
(10, 2, NULL, 0, 12000, 'Selesai', NULL, '2025-12-28 04:50:11', NULL, 'Ditolak'),
(11, 2, NULL, 0, 12000, 'Selesai', NULL, '2025-12-28 05:01:50', NULL, 'Ditolak'),
(12, 2, NULL, 0, 72000, 'Selesai', NULL, '2025-12-28 05:11:41', NULL, NULL),
(13, 2, NULL, 0, 90000, 'Dibatalkan', NULL, '2025-12-28 05:20:41', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(14, 2, NULL, 0, 16000, 'Selesai', NULL, '2025-12-28 05:21:18', 'Menemukan harga lebih murah', 'Ditolak'),
(15, 2, NULL, 0, 90000, 'Dibatalkan', NULL, '2025-12-28 05:56:14', NULL, 'Disetujui'),
(16, 2, NULL, 0, 90000, 'Dibatalkan', NULL, '2025-12-28 05:59:16', NULL, 'Disetujui'),
(17, 2, NULL, 0, 14000, 'Selesai', NULL, '2025-12-28 05:59:34', NULL, 'Ditolak'),
(18, 2, NULL, 0, 24000, 'Selesai', NULL, '2025-12-28 06:01:07', NULL, 'Ditolak'),
(19, 2, NULL, 0, 2000, 'Selesai', NULL, '2025-12-28 06:07:16', NULL, 'Ditolak'),
(20, 2, NULL, 0, 477000, 'Dibatalkan', NULL, '2025-12-28 06:13:15', NULL, 'Disetujui'),
(21, 2, NULL, 0, 1442000, 'Selesai', NULL, '2025-12-28 06:34:45', NULL, 'Ditolak'),
(22, 2, NULL, 0, 20000, 'Dibatalkan', NULL, '2025-12-28 06:42:37', NULL, 'Disetujui'),
(23, 2, NULL, 0, 468000, 'Selesai', NULL, '2025-12-28 06:55:25', NULL, 'Ditolak'),
(24, 2, NULL, 0, 2000, 'Dibatalkan', NULL, '2025-12-28 07:17:34', NULL, 'Disetujui'),
(25, 2, NULL, 0, 63000, 'Selesai', NULL, '2025-12-28 07:52:20', NULL, 'Ditolak'),
(26, 2, NULL, 0, 189000, 'Dibatalkan', NULL, '2025-12-28 13:57:34', NULL, 'Disetujui'),
(27, 2, NULL, 0, 2000, 'Dibatalkan', NULL, '2025-12-28 14:24:57', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(28, 3, NULL, 0, 24000, 'Dibatalkan', NULL, '2025-12-28 16:36:52', 'Berubah pikiran / Tidak jadi beli', 'Disetujui'),
(29, 3, NULL, 0, 9000, 'Selesai', NULL, '2025-12-28 16:41:38', NULL, 'Ditolak'),
(30, 4, NULL, 0, 138000, 'Dibatalkan', NULL, '2025-12-29 14:05:47', NULL, 'Disetujui'),
(31, 4, NULL, 0, 128000, 'Dibatalkan', 'Toko sedang libur', '2025-12-29 14:28:29', NULL, 'Disetujui'),
(32, 4, NULL, 0, 50000, 'Dibatalkan', NULL, '2025-12-29 14:35:44', 'Berubah pikiran / Tidak jadi beli', 'Disetujui'),
(33, 4, NULL, 0, 50000, 'Selesai', NULL, '2025-12-29 14:41:58', 'Berubah pikiran / Tidak jadi beli', 'Ditolak'),
(34, 4, NULL, 0, 288000, 'Dibatalkan', NULL, '2025-12-29 14:55:03', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(35, 2, NULL, 0, 153000, 'Dibatalkan', NULL, '2025-12-29 14:56:48', 'Salah memasukkan alamat', 'Disetujui'),
(36, 5, NULL, 0, 131000, 'Dibatalkan', NULL, '2025-12-29 14:59:47', 'Berubah pikiran / Tidak jadi beli', 'Disetujui'),
(37, 2, NULL, 0, 2000, 'Dibatalkan', NULL, '2025-12-30 03:29:01', NULL, 'Disetujui'),
(38, 2, NULL, 0, 6000, 'Dibatalkan', 'Toko tutup', '2026-01-11 12:36:14', NULL, 'Menunggu'),
(39, 2, NULL, 0, 291000, 'Dibatalkan', NULL, '2026-01-11 12:42:03', 'Berubah pikiran / Tidak jadi beli', 'Disetujui'),
(40, 2, NULL, 0, 45000, 'Selesai', NULL, '2026-01-11 13:27:52', NULL, 'Menunggu'),
(41, 2, NULL, 0, 20000, 'Dibatalkan', NULL, '2026-01-11 13:48:48', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(42, 2, NULL, 0, 2000, 'Selesai', NULL, '2026-01-11 14:11:01', NULL, 'Menunggu'),
(43, 2, NULL, 0, 90000, 'Selesai', NULL, '2026-01-12 03:52:38', NULL, 'Menunggu'),
(44, 2, NULL, 0, 108000, 'Selesai', NULL, '2026-01-13 05:27:41', NULL, 'Menunggu'),
(45, 2, NULL, 0, 342000, 'Dibatalkan', NULL, '2026-01-13 05:30:28', 'Menemukan harga lebih murah', 'Disetujui'),
(46, 2, NULL, 0, 486000, 'Dibatalkan', NULL, '2026-01-13 05:45:22', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(47, 2, NULL, 0, 72000, 'Dibatalkan', NULL, '2026-01-13 05:51:50', 'Ingin mengubah rincian pesanan', 'Disetujui'),
(48, 2, NULL, 0, 108000, 'Dibatalkan', 'Toko tutup', '2026-01-14 08:28:46', NULL, 'Menunggu'),
(49, 2, NULL, 0, 90000, 'Dibatalkan', NULL, '2026-01-14 08:29:30', 'Salah memasukkan alamat', 'Disetujui'),
(50, 2, NULL, 0, 297000, 'Selesai', NULL, '2026-01-25 13:29:57', NULL, 'Menunggu'),
(51, 2, NULL, 0, 24000, 'Selesai', NULL, '2026-01-25 13:41:24', NULL, 'Menunggu'),
(53, 7, NULL, 0, 255000, 'Dibatalkan', NULL, '2026-07-09 12:07:24', 'Berubah pikiran / Tidak jadi beli', 'Disetujui'),
(54, 7, NULL, 0, 22000, 'Selesai', NULL, '2026-07-09 12:48:25', NULL, 'Menunggu'),
(59, 9, NULL, 0, 9000, 'Dibatalkan', 'Stok ikan habis', '2026-07-09 14:55:54', NULL, 'Menunggu'),
(60, 9, NULL, 0, 2000, 'Selesai', NULL, '2026-07-09 14:55:57', NULL, 'Menunggu'),
(61, 9, NULL, 0, 2000, 'Selesai', NULL, '2026-07-09 14:56:00', 'Berubah pikiran / Tidak jadi beli', 'Ditolak'),
(62, 9, NULL, 0, 2000, 'Dibatalkan', NULL, '2026-07-09 14:56:02', 'Salah memasukkan alamat', 'Disetujui');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_produk`
--

CREATE TABLE `pesanan_produk` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `satuan` enum('ikat','eceran') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan_produk`
--

INSERT INTO `pesanan_produk` (`id`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`, `harga`, `satuan`) VALUES
(5, 7, 7, 7, 9000, 0, 'ikat'),
(6, 8, 2, 6, 9000, 0, 'ikat'),
(7, 9, 5, 11, 2000, 0, 'eceran'),
(8, 10, 3, 6, 2000, 0, 'eceran'),
(9, 11, 2, 6, 2000, 0, 'eceran'),
(10, 12, 7, 8, 9000, 0, 'ikat'),
(11, 13, 7, 10, 9000, 0, 'ikat'),
(12, 14, 7, 8, 2000, 0, 'eceran'),
(13, 16, 7, 10, 9000, 0, 'ikat'),
(14, 17, 7, 7, 2000, 0, 'eceran'),
(15, 18, 5, 12, 2000, 0, 'eceran'),
(16, 19, 2, 1, 2000, 0, 'eceran'),
(17, 20, 3, 53, 9000, 0, 'ikat'),
(18, 21, 7, 78, 9000, 0, 'ikat'),
(19, 21, 5, 100, 2000, 0, 'eceran'),
(20, 21, 2, 60, 9000, 0, 'ikat'),
(21, 22, 7, 10, 2000, 0, 'eceran'),
(22, 23, 2, 36, 9000, 0, 'ikat'),
(23, 23, 5, 16, 9000, 0, 'ikat'),
(24, 24, 5, 1, 2000, 0, 'eceran'),
(25, 25, 3, 7, 9000, 0, 'ikat'),
(26, 26, 7, 21, 9000, 0, 'ikat'),
(27, 27, 3, 1, 2000, 0, 'eceran'),
(28, 28, 7, 3, 2000, 0, 'eceran'),
(29, 28, 5, 4, 2000, 0, 'eceran'),
(30, 28, 3, 5, 2000, 0, 'eceran'),
(31, 29, 3, 1, 9000, 0, 'ikat'),
(32, 30, 3, 8, 2000, 0, 'eceran'),
(33, 30, 3, 9, 9000, 0, 'ikat'),
(34, 30, 7, 3, 9000, 0, 'ikat'),
(35, 30, 7, 7, 2000, 0, 'eceran'),
(36, 31, 7, 10, 9000, 0, 'ikat'),
(37, 31, 5, 7, 2000, 0, 'eceran'),
(38, 31, 3, 4, 2000, 0, 'eceran'),
(39, 31, 2, 8, 2000, 0, 'eceran'),
(40, 32, 5, 25, 2000, 0, 'eceran'),
(41, 33, 5, 25, 2000, 0, 'eceran'),
(42, 34, 2, 32, 9000, 0, 'ikat'),
(43, 35, 5, 17, 9000, 0, 'ikat'),
(44, 36, 2, 16, 2000, 0, 'eceran'),
(45, 36, 3, 11, 9000, 0, 'ikat'),
(46, 37, 5, 1, 2000, 0, 'eceran'),
(47, 38, 7, 3, 2000, 0, 'eceran'),
(48, 39, 2, 23, 9000, 0, 'ikat'),
(49, 39, 5, 8, 9000, 0, 'ikat'),
(50, 39, 7, 6, 2000, 0, 'eceran'),
(51, 40, 5, 5, 9000, 0, 'ikat'),
(52, 41, 2, 10, 2000, 0, 'eceran'),
(53, 42, 7, 1, 2000, 0, 'eceran'),
(54, 43, 2, 10, 9000, 0, 'ikat'),
(55, 44, 3, 12, 9000, 0, 'ikat'),
(56, 45, 3, 38, 9000, 0, 'ikat'),
(57, 46, 7, 54, 9000, 0, 'ikat'),
(58, 47, 3, 36, 2000, 0, 'eceran'),
(59, 48, 2, 12, 9000, 0, 'ikat'),
(60, 49, 5, 10, 9000, 0, 'ikat'),
(61, 50, 2, 33, 9000, 0, 'ikat'),
(62, 51, 5, 12, 2000, 0, 'eceran'),
(63, 53, 7, 10, 9000, 0, 'ikat'),
(64, 53, 5, 5, 9000, 0, 'ikat'),
(65, 53, 3, 10, 9000, 0, 'ikat'),
(66, 53, 2, 15, 2000, 0, 'eceran'),
(67, 54, 7, 1, 2000, 0, 'eceran'),
(68, 54, 5, 10, 2000, 0, 'eceran'),
(73, 59, 3, 1, 9000, 0, 'ikat'),
(74, 60, 3, 1, 2000, 0, 'eceran'),
(75, 61, 3, 1, 2000, 0, 'eceran'),
(76, 62, 3, 1, 2000, 0, 'eceran');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `jenis_ikan` enum('Ikan Laut','Ikan Tawar','Ikan Olahan') NOT NULL,
  `harga_eceran` int(11) DEFAULT NULL,
  `harga_ikat` int(11) DEFAULT NULL,
  `stok_ikat` int(11) NOT NULL DEFAULT 0,
  `stok_eceran` int(11) NOT NULL DEFAULT 0,
  `foto_produk` varchar(255) DEFAULT 'default_ikan.jpg',
  `foto_produk2` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `jenis_ikan`, `harga_eceran`, `harga_ikat`, `stok_ikat`, `stok_eceran`, `foto_produk`, `foto_produk2`, `deskripsi`, `created_at`) VALUES
(2, 'Udang', 'Ikan Laut', 2000, 9000, 190, 40, 'Udang.jpeg', 'Udang2.jpeg', 'Udang kecil (rebon/peci) yang digoreng krispi, sangat cocok untuk lauk pauk atau cemilan.', '2025-12-21 15:17:51'),
(3, 'Ikan Tunjang', 'Ikan Laut', 2000, 9000, 88, 50, 'Ikan Tunjang.jpeg', 'Ikan Tunjang2.jpeg', NULL, '2025-12-21 16:26:46'),
(5, 'Ikan Pirik', 'Ikan Laut', 2000, 9000, 60, 34, 'Ikan Pirik.jpeg', 'Ikan Pirik2.jpeg', NULL, '2025-12-27 14:54:18'),
(7, 'Ikan Teri', 'Ikan Laut', 2000, 9000, 80, 59, 'Ikan Teri.jpeg', 'Ikan Teri2.jpeg', NULL, '2025-12-27 16:32:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pesanan_produk`
--
ALTER TABLE `pesanan_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `pesanan_produk`
--
ALTER TABLE `pesanan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
