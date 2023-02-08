-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2023 at 01:25 PM
-- Server version: 10.6.11-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cek_reskir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `username`, `password`, `role`) VALUES
(3, 'admin', '25f9e794323b453885f5181f1b624d0b', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_produk`
--

CREATE TABLE `tbl_produk` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(256) NOT NULL,
  `kelompok_barang` varchar(256) DEFAULT NULL,
  `nama_barang` varchar(256) DEFAULT NULL,
  `berat` double NOT NULL DEFAULT 0,
  `harga` bigint(20) NOT NULL DEFAULT 0,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` (`id`, `kode_barang`, `kelompok_barang`, `nama_barang`, `berat`, `harga`, `admin_id`) VALUES
(2, 'BRG0001', 'sabun', 'Sampo', 12, 120000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resi`
--

CREATE TABLE `tbl_resi` (
  `resi_id` int(11) NOT NULL,
  `nama_customer` varchar(256) DEFAULT NULL,
  `no_telp` varchar(256) NOT NULL,
  `no_resi` varchar(256) NOT NULL,
  `kode_barang` varchar(256) NOT NULL,
  `ekspedisi` varchar(256) NOT NULL,
  `harga` bigint(20) NOT NULL DEFAULT 0,
  `tanggal_pencatatan` datetime NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resi`
--

INSERT INTO `tbl_resi` (`resi_id`, `nama_customer`, `no_telp`, `no_resi`, `kode_barang`, `ekspedisi`, `harga`, `tanggal_pencatatan`, `admin_id`) VALUES
(1, 'Nela', '085320148791', '004176544749', 'BRG0001', 'sicepat', 290, '2023-02-08 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resi_activity`
--

CREATE TABLE `tbl_resi_activity` (
  `resi_activity_id` int(11) NOT NULL,
  `resi_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(256) NOT NULL,
  `location` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resi_activity`
--

INSERT INTO `tbl_resi_activity` (`resi_activity_id`, `resi_id`, `date`, `description`, `location`) VALUES
(2, 1, '2023-01-31 09:12:00', 'PAKET DIBAWA [SIGESIT - MAMAN RUDIMAN]', ''),
(3, 1, '2023-01-31 08:52:00', 'PAKET TELAH DI TERIMA DI KUNINGAN [KUNINGAN JALAKSANA]', ''),
(4, 1, '2023-01-30 14:44:00', 'PAKET KELUAR DARI CIREBON [CIREBON SORTATION]', ''),
(5, 1, '2023-01-30 13:34:00', 'PAKET TELAH DI TERIMA DI CIREBON [CIREBON SORTATION]', ''),
(6, 1, '2023-01-30 06:42:00', 'PAKET KELUAR DARI JAKARTA UTARA [LINE HAUL DARAT JAKARTA 1]', ''),
(7, 1, '2023-01-30 04:50:00', 'PAKET TELAH DI TERIMA DI JAKARTA UTARA [LINE HAUL DARAT JAKARTA 1]', ''),
(8, 1, '2023-01-28 23:01:00', 'PAKET KELUAR DARI JAKARTA BARAT [JAKBAR MANGGA BESAR]', ''),
(9, 1, '2023-01-28 18:19:00', 'PAKET TELAH DI INPUT (MANIFESTED) DI JAKARTA BARAT [SICEPAT EKSPRES PINANGSIA]', ''),
(10, 1, '2023-01-28 10:45:00', 'TERIMA PERMINTAAN PICK UP DARI [SHOPEE]', ''),
(11, 1, '2023-01-31 10:43:00', 'PAKET DITERIMA OLEH [NELA - (KEL) KELUARGA SERUMAH]', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tbl_resi`
--
ALTER TABLE `tbl_resi`
  ADD PRIMARY KEY (`resi_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `tbl_resi_activity`
--
ALTER TABLE `tbl_resi_activity`
  ADD PRIMARY KEY (`resi_activity_id`),
  ADD KEY `resi_id` (`resi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_resi`
--
ALTER TABLE `tbl_resi`
  MODIFY `resi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_resi_activity`
--
ALTER TABLE `tbl_resi_activity`
  MODIFY `resi_activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
