-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2026 at 09:57 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_uks`
--

-- Re-import safe: existing rows and objects are retained; duplicate seed IDs are skipped.
USE `peminjaman_uks`;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE IF NOT EXISTS `fasilitas` (
  `id` int NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` int NOT NULL,
  `status` enum('tersedia','dipinjam') COLLATE utf8mb4_general_ci DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fasilitas`
--

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'fasilitas' AND index_name = 'PRIMARY') = 0,
  'ALTER TABLE `fasilitas` ADD PRIMARY KEY (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

INSERT IGNORE INTO `fasilitas` (`id`, `nama`, `kategori`, `kondisi`, `harga`, `status`, `created_at`, `jumlah`) VALUES
(1, 'Mic Wireless', 'Audio', 'Baik', 35000, 'tersedia', '2025-12-09 13:47:33', 1),
(2, 'Speaker ', 'Audio', 'Baik', 75000, 'dipinjam', '2025-12-09 13:47:33', 1),
(5, 'Mixer Audio 8-Channel', 'Audio', 'Baik', 30000, 'dipinjam', '2025-12-09 13:47:33', 1),
(8, 'Talempong 1 SET', 'Alat Musik', 'Baik', 150000, 'tersedia', '2025-12-18 11:48:23', 1),
(9, 'Tambua/Gendang', 'Alat Musik', 'Baik', 50000, 'tersedia', '2025-12-18 11:48:48', 4),
(10, 'Tansa', 'Alat Musik', 'Baik', 50000, 'tersedia', '2025-12-18 11:49:03', 1),
(11, 'Drum Akustik', 'Alat Musik', 'Baik', 400000, 'tersedia', '2025-12-18 11:49:26', 1),
(13, 'Ampli Bass', 'Alat Musik', 'Baik', 200000, 'tersedia', '2025-12-18 11:50:01', 1),
(14, 'Ampli Gitar', 'Alat Musik', 'Baik', 200000, 'tersedia', '2025-12-18 11:51:31', 1),
(15, 'MIC', 'Audio', 'Baik', 25000, 'tersedia', '2025-12-18 11:52:33', 1),
(16, 'BASS', 'Alat Musik', 'Baik', 150000, 'tersedia', '2025-12-18 11:52:58', 1),
(17, 'Gitar', 'Alat Musik', 'Baik', 150000, 'tersedia', '2025-12-18 11:53:11', 1),
(18, 'Stand Lukis', 'ATK', 'Baik', 50000, 'tersedia', '2025-12-18 11:54:23', 1),
(19, 'Baju Tari Satin (Orange)', 'Baju Tari (Baju Saja)', 'Baik', 50000, 'tersedia', '2025-12-18 11:55:27', 3),
(20, 'Baju Tari Satin (Merah)', 'Baju Tari (Baju Saja)', 'Baik', 50000, 'tersedia', '2025-12-18 11:55:59', 3),
(21, 'Baju Tari Satin (Ungu)', 'Baju Tari (Baju Saja)', 'Baik', 50000, 'tersedia', '2025-12-18 11:56:20', 3),
(22, 'Baju Tari Satin (Hijau)', 'Baju Tari (Baju Saja)', 'Baik', 50000, 'tersedia', '2025-12-18 11:56:40', 3),
(23, 'Baju Tari Bludru (Abu-Abu)', 'Baju Tari (Baju Saja)', 'Baik', 85000, 'tersedia', '2025-12-18 11:57:11', 5),
(24, 'Baju Anak Daro Bludru ', 'Baju Anak Daro (Baju Saja)', 'Baik', 100000, 'tersedia', '2025-12-18 11:58:08', 2),
(25, 'Baju Anak Daro Satin ', 'Baju Anak Daro (Baju Saja)', 'Baik', 75000, 'tersedia', '2025-12-18 11:58:42', 2),
(26, 'Baju Tari Satin (Orange)', 'Baju Tari (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:07:55', 3),
(27, 'Baju Tari Satin (Merah)', 'Baju Tari (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:08:10', 5),
(28, 'Baju Tari Satin (Ungu)', 'Baju Tari (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:08:19', 5),
(29, 'Baju Tari Satin (Hijau)', 'Baju Tari (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:08:30', 5),
(30, 'Baju Tari Bludru  (Abu-Abu)', 'Baju Tari (1 SET)', 'Baik', 125000, 'tersedia', '2025-12-18 12:09:02', 5),
(31, 'Baju Anak Daro Bludru ', 'Baju Anak Daro (1 SET)', 'Baik', 150000, 'tersedia', '2025-12-18 12:09:54', 2),
(32, 'Baju Anak Daro Satin', 'Baju Anak Daro (1 SET)', 'Baik', 85000, 'tersedia', '2025-12-18 12:10:17', 2),
(33, 'Baju Silat Bludru (Merah)', 'Baju Silat (Baju Saja)', 'Baik', 45000, 'tersedia', '2025-12-18 12:11:21', 5),
(34, 'Baju Silat Satin (Hitam)', 'Baju Silat (Baju Saja)', 'Baik', 35000, 'tersedia', '2025-12-18 12:12:08', 5),
(35, 'Baju Silat Bludru', 'Baju Silat (Baju Saja)', 'Baik', 45000, 'tersedia', '2025-12-18 12:12:40', 3),
(36, 'Baju Silat Bludru (Merah)', 'Baju Silat (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:15:13', 3),
(37, 'Baju Silat Satin (Hitam)', 'Baju Silat (1 SET)', 'Baik', 50000, 'tersedia', '2025-12-18 12:15:42', 7),
(38, 'Baju Silat Bludru (Hitam)', 'Baju Silat (1 SET)', 'Baik', 75000, 'tersedia', '2025-12-18 12:16:01', 7),
(39, 'Galembong Hitam', 'Celana Tari', 'Baik', 50000, 'tersedia', '2025-12-18 12:17:29', 1),
(40, 'Songket Merah', 'Aksesoris ', 'Baik', 75000, 'tersedia', '2025-12-18 12:17:51', 4),
(41, 'Deta Silat Merah', 'Aksesoris ', 'Baik', 15000, 'tersedia', '2025-12-18 12:18:16', 2),
(42, 'Suntiang Penari', 'Aksesoris ', 'Baik', 50000, 'tersedia', '2025-12-18 12:18:35', 7),
(43, 'Suntiang Anak Daro', 'Aksesoris ', 'Baik', 75000, 'tersedia', '2025-12-18 12:19:00', 3),
(44, 'Camera', 'Phocinemart', 'Rusak Ringan', 150000, 'tersedia', '2025-12-18 12:22:23', 1),
(45, 'Tripot', 'Phocinemart', 'Sangat Baik', 35000, 'tersedia', '2025-12-18 12:23:17', 1),
(46, 'Lensa FIX', 'Phocinemart', 'Baik', 50000, 'tersedia', '2025-12-18 12:24:00', 2),
(47, 'Horderphone', 'Phocinemart', 'Rusak Ringan', 25000, 'tersedia', '2025-12-18 12:24:50', 1),
(48, 'Drum Pad', 'Alat Musik', 'Baik', 125000, 'tersedia', '2025-12-18 12:26:23', 1),
(49, 'Gitar Kapok', 'Alat Musik', 'Rusak Ringan', 100000, 'tersedia', '2025-12-18 12:27:07', 1),
(50, 'Mic Behringer', 'Audio', 'Baik', 50000, 'dipinjam', '2025-12-18 12:28:14', 3),
(51, 'Stand Drum', 'Alat Musik', 'Baik', 80000, 'tersedia', '2025-12-18 12:29:02', 3),
(52, 'Strap Gitar', 'Alat Musik', 'Baik', 30000, 'tersedia', '2025-12-18 12:30:10', 3),
(53, 'Soundcard', 'Alat Musik', 'Baik', 75000, 'tersedia', '2025-12-18 12:30:40', 1),
(54, 'Stand Gitar', 'Alat Musik', 'Rusak Ringan', 35000, 'tersedia', '2025-12-18 12:31:13', 1),
(55, 'Multi Stand', 'Alat Musik', 'Rusak Ringan', 35000, 'tersedia', '2025-12-18 12:32:07', 1),
(56, 'Stand Power', 'Alat Musik', 'Baik', 50000, 'tersedia', '2025-12-18 12:32:34', 1),
(57, 'Jack Tunjuk', 'Alat Musik', 'Baik', 50000, 'tersedia', '2025-12-18 12:33:19', 2),
(58, 'Jack Canon', 'Alat Musik', 'Baik', 75000, 'tersedia', '2025-12-18 12:33:37', 4),
(59, 'Single Pedal', 'Alat Musik', 'Baik', 70000, 'tersedia', '2025-12-18 12:34:07', 1),
(60, 'Kick Drum', 'Alat Musik', 'Rusak Ringan', 50000, 'tersedia', '2025-12-18 12:35:01', 1),
(61, 'Snar Drum', 'Alat Musik', 'Baik', 55000, 'tersedia', '2025-12-18 12:35:32', 1),
(62, 'Tom (1,2,3)', 'Alat Musik', 'Baik', 45000, 'tersedia', '2025-12-18 12:36:30', 3),
(63, 'Floor Drum', 'Alat Musik', 'Baik', 45000, 'tersedia', '2025-12-18 12:36:49', 1),
(64, 'Stand Drum', 'Alat Musik', 'Baik', 65000, 'tersedia', '2025-12-18 12:37:11', 3),
(65, 'Cowbell', 'Alat Musik', 'Baik', 50000, 'tersedia', '2025-12-18 12:37:41', 1),
(66, 'Tamborin', 'Alat Musik', 'Baik', 95000, 'tersedia', '2025-12-18 12:38:03', 1),
(67, 'Hit Hat', 'Alat Musik', 'Rusak Ringan', 65000, 'tersedia', '2025-12-18 12:38:43', 1),
(68, 'Ride', 'Alat Musik', 'Baik', 40000, 'tersedia', '2025-12-18 12:39:14', 1),
(69, 'Cymbal Chinese', 'Alat Musik', 'Baik', 65000, 'tersedia', '2025-12-18 12:40:00', 1),
(70, 'Cymbal', 'Alat Musik', 'Baik', 60000, 'tersedia', '2025-12-18 12:40:16', 5),
(71, 'Kabinet Bass', 'Alat Musik', 'Baik', 65000, 'tersedia', '2025-12-18 12:40:58', 1),
(72, 'Head Kabinet Gitar', 'Alat Musik', 'Baik', 75000, 'tersedia', '2025-12-18 12:41:24', 1),
(73, 'Head Kabinet Bass', 'Alat Musik', 'Baik', 75000, 'tersedia', '2025-12-18 12:41:36', 1),
(74, 'Kabinet Marshall', 'Alat Musik', 'Baik', 70000, 'tersedia', '2025-12-18 12:42:24', 2),
(75, 'Gitar Rock Well', 'Musik', 'Baik', 130000, 'tersedia', '2025-12-18 12:43:01', 1),
(76, 'Wereless Ashley', 'Musik', 'Sangat Baik', 85000, 'tersedia', '2025-12-18 12:44:01', 1),
(77, 'Bansi', 'Musik', 'Baik', 85000, 'tersedia', '2025-12-18 12:44:55', 1),
(78, 'Djimbe', 'Musik', 'Baik', 120000, 'tersedia', '2025-12-18 12:45:55', 2),
(79, 'Toka Merah Penari', 'Aksesoris ', 'Baik', 50000, 'tersedia', '2025-12-18 12:46:42', 6),
(80, 'Baju Hitam Dance', 'Baju Tari (1 SET)', 'Baik', 65000, 'tersedia', '2025-12-18 12:47:39', 5),
(81, 'Ikat Pinggang dance', 'Aksesoris ', 'Baik', 25000, 'tersedia', '2025-12-18 12:48:07', 4),
(82, 'Toka Hijau Penari', 'Aksesoris ', 'Baik', 65000, 'tersedia', '2025-12-18 12:48:41', 7),
(83, 'Salempang Ungu Penari', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 12:49:12', 4),
(84, 'Ikat Pinggang Ungu', 'Tari', 'Baik', 25000, 'tersedia', '2025-12-18 12:49:43', 4),
(85, 'Ikat Pinggang Merah', 'Tari', 'Baik', 25000, 'tersedia', '2025-12-18 12:49:54', 4),
(86, 'Topi Kipas', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 12:50:17', 4),
(87, 'Penutup Sanggul ', 'Tari', 'Baik', 25000, 'tersedia', '2025-12-18 12:50:43', 5),
(88, 'Tutup Carano (Merah,Hitam)', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 12:51:29', 2),
(89, 'Tanduak Emas', 'Tari', 'Baik', 55000, 'tersedia', '2025-12-18 12:51:57', 4),
(90, 'Konde', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 12:52:20', 12),
(91, 'Piring Tari', 'Tari', 'Baik', 15000, 'tersedia', '2025-12-18 13:21:03', 6),
(92, 'Carano Kecil', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 13:23:34', 6),
(93, 'Carano Besar', 'Tari', 'Baik', 65000, 'tersedia', '2025-12-18 13:23:48', 1),
(94, 'Kaca', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 13:24:34', 1),
(95, 'Laca Anak Daro', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 13:25:02', 1),
(96, 'Laca Penari', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 13:25:14', 5),
(97, 'Laca Pendamping', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 13:25:29', 2),
(98, 'Kalung Emas', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 13:25:59', 7),
(99, 'Tanduak Merah', 'Tari', 'Baik', 75000, 'tersedia', '2025-12-18 13:26:40', 6),
(100, 'Andong Hitam Bludru', 'Tari', 'Baik', 55000, 'tersedia', '2025-12-18 13:27:11', 1),
(101, 'Galembong Hitam Merah', 'Tari', 'Baik', 85000, 'tersedia', '2025-12-18 13:28:00', 1),
(102, 'Songket Maroon', 'Tari', 'Baik', 65000, 'tersedia', '2025-12-18 13:28:33', 2),
(103, 'Kain Merah Rumbai', 'Tari', 'Baik', 55000, 'tersedia', '2025-12-18 13:29:31', 2),
(104, 'Ikat Pinggang Merah Rumbai', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 13:29:54', 2),
(105, 'Songket Hitam Silat', 'Tari', 'Baik', 75000, 'tersedia', '2025-12-18 13:30:20', 3),
(106, 'Kalung Merah', 'Tari', 'Baik', 35000, 'tersedia', '2025-12-18 13:30:43', 6),
(107, 'Sepatu Penari', 'Tari', 'Baik', 85000, 'tersedia', '2025-12-18 13:31:12', 6),
(108, 'Takuluak Sungayang', 'Tari', 'Baik', 85000, 'tersedia', '2025-12-18 13:31:38', 1),
(109, 'Bunga Merah', 'Tari', 'Baik', 25000, 'tersedia', '2025-12-18 13:32:02', 13),
(110, 'Topeng (Hitam)', 'Pertunjukan', 'Baik', 35000, 'tersedia', '2025-12-18 13:32:34', 7),
(111, 'Kostum Suku Dayak', 'Pertunjukan', 'Baik', 95000, 'tersedia', '2025-12-18 13:33:08', 1),
(112, 'Baju Merah', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 13:34:10', 1),
(113, 'Pistol', 'Pertunjukan', 'Baik', 55000, 'tersedia', '2025-12-18 13:34:45', 1),
(114, 'Ikat Kepala Suku Dayak', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 13:35:06', 1),
(115, 'Kain Abu-Abu Hitam', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 13:35:36', 1),
(116, 'Celana SMA', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 13:36:10', 1),
(117, 'Pedang Biasa', 'Pertunjukan', 'Baik', 65000, 'tersedia', '2025-12-18 13:36:48', 1),
(118, 'Mahkota Raja', 'Pertunjukan', 'Sangat Baik', 45000, 'tersedia', '2025-12-18 13:37:06', 1),
(119, 'Rantai', 'Pertunjukan', 'Baik', 35000, 'tersedia', '2025-12-18 13:37:53', 1),
(122, 'Kuas Flat 0,75', 'Seni rupa', 'Baik', 25000, 'tersedia', '2025-12-18 13:40:30', 1),
(123, 'Kuas Flat 0,5', 'Seni rupa', 'Baik', 27000, 'tersedia', '2025-12-18 13:41:04', 2),
(124, 'Kuas Round', 'Seni rupa', 'Baik', 28000, 'tersedia', '2025-12-18 13:41:26', 6),
(125, 'Kuas Bright', 'Seni rupa', 'Baik', 25000, 'tersedia', '2025-12-18 13:41:46', 1),
(126, 'Pisau Pahat Lurus', 'Seni rupa', 'Sangat Baik', 35000, 'tersedia', '2025-12-18 13:42:18', 1),
(127, 'Pisau Pahat Kuku', 'Seni rupa', 'Sangat Baik', 37000, 'tersedia', '2025-12-18 13:42:50', 1),
(128, 'Pisau Pahat Coret', 'Seni rupa', 'Sangat Baik', 37000, 'tersedia', '2025-12-18 13:43:13', 1),
(129, 'Pisau Pahat Cekung', 'Seni rupa', 'Sangat Baik', 38000, 'tersedia', '2025-12-18 13:43:31', 2),
(130, 'Skop Cat', 'Seni rupa', 'Sangat Baik', 25000, 'tersedia', '2025-12-18 13:44:12', 1),
(132, 'Papan Tulis', 'Seni rupa', 'Baik', 45000, 'tersedia', '2025-12-18 13:45:05', 1),
(133, 'Penggaris', 'Seni rupa', 'Baik', 15000, 'tersedia', '2025-12-18 13:45:30', 2),
(134, 'Canvas Kosong', 'Seni rupa', 'Baik', 35000, 'tersedia', '2025-12-18 13:45:56', 3),
(135, 'Kotak Bahan Ukir', 'Seni rupa', 'Baik', 25000, 'tersedia', '2025-12-18 13:46:15', 2),
(136, 'Kotak Pensil', 'Seni rupa', 'Baik', 10000, 'tersedia', '2025-12-18 13:46:49', 2),
(137, 'Tool Box', 'Umum', 'Baik', 30000, 'tersedia', '2025-12-18 13:47:27', 2),
(138, 'Gergaji', 'Umum', 'Baik', 10000, 'tersedia', '2025-12-18 13:47:52', 1),
(139, 'Kunci Gerinda', 'Umum', 'Baik', 15000, 'tersedia', '2025-12-18 13:48:11', 1),
(140, 'Palu', 'Umum', 'Baik', 10000, 'tersedia', '2025-12-18 13:48:25', 1),
(141, 'Gerinda', 'Umum', 'Baik', 20000, 'tersedia', '2025-12-18 13:48:36', 1),
(142, 'Obeng', 'Umum', 'Baik', 10000, 'tersedia', '2025-12-18 13:48:55', 1),
(143, 'Tang', 'Umum', 'Baik', 10000, 'tersedia', '2025-12-18 13:49:02', 1),
(144, 'Pahat', 'Umum', 'Baik', 20000, 'tersedia', '2025-12-18 13:49:12', 1),
(146, 'Solder', 'Umum', 'Baik', 40000, 'tersedia', '2025-12-18 13:49:47', 2),
(148, 'Multimeter', 'Umum', 'Baik', 20000, 'tersedia', '2025-12-18 13:50:29', 2),
(149, 'Roll Besi', 'Umum', 'Baik', 10000, 'tersedia', '2025-12-18 13:50:52', 1),
(150, 'Roll Siku-Siku', 'Pertunjukan', 'Baik', 12000, 'tersedia', '2025-12-18 13:51:05', 1),
(151, 'Pallet', 'Umum', 'Baik', 20000, 'tersedia', '2025-12-18 13:52:26', 3),
(152, 'HT', 'Umum', 'Baik', 65000, 'tersedia', '2025-12-18 13:53:01', 4),
(153, 'Karpet', 'Umum', 'Baik', 45000, 'tersedia', '2025-12-18 13:53:44', 8),
(154, 'Karopi', 'Umum', 'Baik', 35000, 'tersedia', '2025-12-18 13:54:00', 23),
(155, 'Celemek', 'Pertunjukan', 'Baik', 15000, 'tersedia', '2025-12-18 14:18:53', 1),
(156, 'Topeng Biru', 'Pertunjukan', 'Baik', 35000, 'tersedia', '2025-12-18 14:20:54', 24),
(157, 'Baju Hitam', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 14:22:16', 1),
(158, 'Kain Hitam', 'Pertunjukan', 'Baik', 25000, 'tersedia', '2025-12-18 14:25:26', 6),
(159, 'Kotak Cat Acrilic Warna', 'Seni rupa', 'Baik', 25000, 'tersedia', '2025-12-18 14:28:14', 1),
(160, 'Palet Plastik', 'Seni rupa', 'Baik', 20000, 'tersedia', '2025-12-18 14:28:39', 3),
(161, 'Lukisan Kanvas', 'Seni rupa', 'Baik', 15000, 'tersedia', '2025-12-18 14:29:24', 29),
(162, 'Lukisan Tanpa Bingkai', 'Seni rupa', 'Baik', 10000, 'tersedia', '2025-12-18 14:30:03', 3),
(163, 'Kipas', 'Tari', 'Baik', 15000, 'tersedia', '2025-12-18 14:35:22', 6),
(164, 'Ikat Pinggang ', 'Tari', 'Baik', 20000, 'tersedia', '2025-12-18 14:43:50', 2),
(165, 'Lame', 'Tari', 'Baik', 15000, 'tersedia', '2025-12-18 14:44:26', 17),
(166, 'Anak Jilbab Ninja Merah ', 'Tari', 'Baik', 10000, 'tersedia', '2025-12-18 14:44:57', 8),
(167, 'Melati Putih', 'Tari', 'Baik', 25000, 'tersedia', '2025-12-18 14:45:21', 1),
(168, 'Celana Hitam Penari', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 14:47:32', 3),
(169, 'Deta Silat Merah Polos', 'Tari', 'Baik', 10000, 'tersedia', '2025-12-18 15:04:03', 4),
(170, 'Deta Silat Hitam Polos', 'Tari', 'Baik', 10000, 'tersedia', '2025-12-18 15:04:36', 5),
(171, 'Baju Dongker Pendamping', 'Tari', 'Baik', 85000, 'tersedia', '2025-12-18 15:29:06', 2),
(172, 'Baju Dongker Anak Daro', 'Tari', 'Baik', 95000, 'tersedia', '2025-12-18 15:29:41', 1),
(173, 'Toka Anak Daro', 'Tari', 'Baik', 45000, 'tersedia', '2025-12-18 15:30:10', 1),
(174, 'Cajon', 'Musik', 'Baik', 75000, 'tersedia', '2025-12-18 15:32:14', 1),
(175, 'Snack Kabel', 'Musik', 'Baik', 35000, 'tersedia', '2025-12-18 15:33:04', 9),
(176, 'Double Pedal', 'Musik', 'Baik', 80000, 'tersedia', '2025-12-18 15:34:02', 1),
(177, 'Lampu Togok Besar', 'Pertunjukan', 'Baik', 55000, 'tersedia', '2025-12-18 15:34:56', 2),
(179, 'Dimmer Lampu', 'Pertunjukan', 'Baik', 40000, 'tersedia', '2025-12-18 15:36:07', 1),
(180, 'Mixer Lighting', 'Pertunjukan', 'Baik', 45000, 'tersedia', '2025-12-18 15:52:39', 1),
(181, 'Kostum Putih Polos', 'Pertunjukan', 'Baik', 50000, 'tersedia', '2025-12-18 15:53:38', 2),
(182, 'Clappert Board', 'Phocinemart', 'Baik', 60000, 'tersedia', '2025-12-18 15:54:21', 1),
(183, 'Pisau Kecil', 'Seni rupa', 'Baik', 5000, 'tersedia', '2025-12-18 15:54:53', 1),
(184, 'Clip Tembak', 'Seni rupa', 'Baik', 15000, 'tersedia', '2025-12-18 15:55:17', 2),
(185, 'Kuas Flat 0,625', 'Seni rupa', 'Baik', 30000, 'tersedia', '2025-12-18 15:56:32', 1),
(186, 'Papan Abo', 'Seni rupa', 'Sangat Baik', 5000, 'tersedia', '2025-12-18 15:57:11', 1),
(187, 'Kotak Pensil', 'Seni rupa', 'Baik', 15000, 'tersedia', '2025-12-18 15:57:41', 3),
(188, 'Klepon', 'Tari', 'Baik', 15000, 'tersedia', '2025-12-18 15:58:40', 29),
(189, 'Stand Partitur', 'Musik', 'Sangat Baik', 50000, 'tersedia', '2025-12-18 15:59:35', 1),
(190, 'Kursi drum', 'Musik', 'Sangat Baik', 30000, 'tersedia', '2025-12-18 16:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `fasilitas_id` int NOT NULL,
  `jumlah_pinjam` int NOT NULL DEFAULT '1',
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `keperluan` text COLLATE utf8mb4_general_ci,
  `kode_pembayaran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `identitas_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','dipinjam','dikembalikan','ditolak') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `denda` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `identitas_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Primary key for `fasilitas` is ensured before seed rows are inserted.
--

--
-- Indexes for table `notifikasi`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'notifikasi' AND index_name = 'PRIMARY') = 0,
  'ALTER TABLE `notifikasi` ADD PRIMARY KEY (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'notifikasi' AND index_name = 'user_id') = 0,
  'ALTER TABLE `notifikasi` ADD KEY `user_id` (`user_id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- Indexes for table `peminjaman`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'peminjaman' AND index_name = 'PRIMARY') = 0,
  'ALTER TABLE `peminjaman` ADD PRIMARY KEY (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'peminjaman' AND index_name = 'user_id') = 0,
  'ALTER TABLE `peminjaman` ADD KEY `user_id` (`user_id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'peminjaman' AND index_name = 'fasilitas_id') = 0,
  'ALTER TABLE `peminjaman` ADD KEY `fasilitas_id` (`fasilitas_id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- Indexes for table `users`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.statistics
   WHERE table_schema = DATABASE() AND table_name = 'users' AND index_name = 'PRIMARY') = 0,
  'ALTER TABLE `users` ADD PRIMARY KEY (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fasilitas`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.columns
   WHERE table_schema = DATABASE() AND table_name = 'fasilitas'
     AND column_name = 'id' AND LOCATE('auto_increment', LOWER(extra)) > 0) = 0,
  'ALTER TABLE `fasilitas` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- AUTO_INCREMENT for table `notifikasi`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.columns
   WHERE table_schema = DATABASE() AND table_name = 'notifikasi'
     AND column_name = 'id' AND LOCATE('auto_increment', LOWER(extra)) > 0) = 0,
  'ALTER TABLE `notifikasi` MODIFY `id` int NOT NULL AUTO_INCREMENT',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- AUTO_INCREMENT for table `peminjaman`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.columns
   WHERE table_schema = DATABASE() AND table_name = 'peminjaman'
     AND column_name = 'id' AND LOCATE('auto_increment', LOWER(extra)) > 0) = 0,
  'ALTER TABLE `peminjaman` MODIFY `id` int NOT NULL AUTO_INCREMENT',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- AUTO_INCREMENT for table `users`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.columns
   WHERE table_schema = DATABASE() AND table_name = 'users'
     AND column_name = 'id' AND LOCATE('auto_increment', LOWER(extra)) > 0) = 0,
  'ALTER TABLE `users` MODIFY `id` int NOT NULL AUTO_INCREMENT',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifikasi`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.table_constraints
   WHERE constraint_schema = DATABASE() AND table_name = 'notifikasi'
     AND constraint_name = 'notifikasi_ibfk_1') = 0,
  'ALTER TABLE `notifikasi` ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- Constraints for table `peminjaman`
--
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.table_constraints
   WHERE constraint_schema = DATABASE() AND table_name = 'peminjaman'
     AND constraint_name = 'peminjaman_ibfk_1') = 0,
  'ALTER TABLE `peminjaman` ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.table_constraints
   WHERE constraint_schema = DATABASE() AND table_name = 'peminjaman'
     AND constraint_name = 'peminjaman_ibfk_2') = 0,
  'ALTER TABLE `peminjaman` ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas` (`id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
