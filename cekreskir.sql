-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2023 at 11:32 AM
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
-- Database: `cekreskir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(254) NOT NULL,
  `username` varchar(245) NOT NULL,
  `password` varchar(254) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `admin_id`, `username`, `password`, `role`) VALUES
(1, 'ADM0001', 'admin', '123456789', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_produk`
--

CREATE TABLE `tbl_produk` (
  `kode_barang` varchar(6) NOT NULL,
  `kelompok_barang` varchar(245) DEFAULT NULL,
  `nama_barang` varchar(254) DEFAULT NULL,
  `berat` double NOT NULL DEFAULT 0,
  `harga` bigint(20) NOT NULL DEFAULT 0,
  `admin_id` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` (`kode_barang`, `kelompok_barang`, `nama_barang`, `berat`, `harga`, `admin_id`) VALUES
('B00001', 'Shoes', 'Geoff Max', 1, 235000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resi`
--

CREATE TABLE `tbl_resi` (
  `resi_id` varchar(254) NOT NULL,
  `nama_customer` varchar(245) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `no_resi` varchar(35) NOT NULL,
  `kode_barang` varchar(6) NOT NULL,
  `ekspedisi` varchar(255) DEFAULT NULL,
  `harga` bigint(20) NOT NULL DEFAULT 0,
  `tanggal_pencatatan` datetime NOT NULL,
  `admin_id` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resi`
--

INSERT INTO `tbl_resi` (`resi_id`, `nama_customer`, `no_telp`, `no_resi`, `kode_barang`, `ekspedisi`, `harga`, `tanggal_pencatatan`, `admin_id`) VALUES
('ajqweqwe1231231', 'Irwansyah', NULL, '18812131231', 'B00001', NULL, 235000, '2023-01-25 16:51:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resi_activity`
--

CREATE TABLE `tbl_resi_activity` (
  `resi_activity_id` varchar(254) NOT NULL,
  `resi_id` varchar(254) NOT NULL,
  `date` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resi_activity`
--

INSERT INTO `tbl_resi_activity` (`resi_activity_id`, `resi_id`, `date`, `description`, `location`) VALUES
('jhgjhgjh4535hgf', 'ajqweqwe1231231', '2023-01-25 17:00:41', 'RECEIVED AT INBOUND STATION [JAKARTA, ANGKE(DESP)]', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD PRIMARY KEY (`kode_barang`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tbl_resi`
--
ALTER TABLE `tbl_resi`
  ADD PRIMARY KEY (`resi_id`),
  ADD UNIQUE KEY `no_resi` (`no_resi`),
  ADD KEY `kode_barang` (`kode_barang`),
  ADD KEY `admin_id` (`admin_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD CONSTRAINT `tbl_produk_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_resi`
--
ALTER TABLE `tbl_resi`
  ADD CONSTRAINT `tbl_resi_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `tbl_produk` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_resi_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_resi_activity`
--
ALTER TABLE `tbl_resi_activity`
  ADD CONSTRAINT `tbl_resi_activity_ibfk_1` FOREIGN KEY (`resi_id`) REFERENCES `tbl_resi` (`resi_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
