-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 16, 2025 at 11:38 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pwd2025`
--
CREATE DATABASE IF NOT EXISTS `db_pwd2025` DEFAULT CHARACTER SET utf8 COLLATE utf8_sinhala_ci;
USE `db_pwd2025`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tamu`
--

CREATE TABLE `tbl_tamu` (
  `cid` int(11) NOT NULL,
  `cnama` varchar(100) COLLATE utf8_sinhala_ci DEFAULT NULL,
  `cemail` varchar(100) COLLATE utf8_sinhala_ci DEFAULT NULL,
  `cpesan` text COLLATE utf8_sinhala_ci,
  `dcreated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_sinhala_ci;

--
-- Dumping data for table `tbl_tamu`
--

INSERT INTO `tbl_tamu` (`cid`, `cnama`, `cemail`, `cpesan`, `dcreated_at`) VALUES
(1, 'savana destiani', 'savanadestiani0@gmail.com', 'hallo guys aku suka traveling', '2025-12-16 18:24:28'),
(2, 'khaila\r\n', 'khailaajah12@gmail.com', 'hallo aku cantik guys', '2025-12-16 18:24:28'),
(3, 'nuriza rahmatullah', 'nurizarahmatulah123@gmail.com', 'hallooo', '2025-12-16 18:24:28'),
(4, 'savana destiani', 'savanadestiani0@gmail.com', 'hallo guys hari ini kami belajar farmastika dasar', '2025-12-16 18:24:28'),
(5, 'khaila ', 'khailaajja@gmail.com', 'hari ini kami menggunakan aplikasi php.myadmin', '2025-12-16 18:24:28'),
(6, 'nuriza rahmatullah', 'nurizarahmatullah@gmail.com', 'hallo guys', '2025-12-16 18:24:28'),
(7, 'savana destiani', 'savanadestiani@gmail.com', 'haii kami belajar farmasetika', '2025-12-16 18:24:28'),
(8, 'khaila', 'khailaja@gmail.com', 'haii kami belajar php my admin', '2025-12-16 18:24:28'),
(9, 'dfg', 'wkmdksnjknsjlaw@gmail.com', 'qwertyuopdfghjkl', '2025-12-16 18:28:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_tamu`
--
ALTER TABLE `tbl_tamu`
  ADD PRIMARY KEY (`cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_tamu`
--
ALTER TABLE `tbl_tamu`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
