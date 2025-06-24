-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 24, 2025 at 12:52 PM
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
-- Database: `klinplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'scheduled',
  `id_order` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pelanggan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gmaps` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal_pengerjaan` date NOT NULL,
  `waktu_pengerjaan` time NOT NULL,
  `durasi` int NOT NULL,
  `waktu_selesai` time NOT NULL,
  `nama_petugas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_pembayaran` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `status`, `id_order`, `nama_pelanggan`, `alamat`, `gmaps`, `catatan`, `tanggal_pengerjaan`, `waktu_pengerjaan`, `durasi`, `waktu_selesai`, `nama_petugas`, `status_pembayaran`, `created_at`, `updated_at`) VALUES
(20, 'selesai', 'ORD-2506001', 'Javas', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', 'rumah hijau', '2025-06-23', '11:00:00', 60, '12:00:00', 'Bejo', 'DP', '2025-06-20 02:43:33', '2025-06-20 05:53:14'),
(21, 'selesai', 'ORD-2506002', 'Willy Wonka', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'samping bank bri', '2025-06-24', '09:00:00', 60, '10:00:00', 'Bejo', 'DP', '2025-06-20 03:50:44', '2025-06-20 06:28:45'),
(22, 'selesai', 'ORD-2506003', 'Edo', 'Jalan Raya Prapen', 'https://maps.app.goo.gl/KiPi75rPFotoCuNUA', 'pager hitam, blablabla', '2025-06-24', '09:00:00', 180, '12:00:00', 'Satria, Andi', 'Lunas', '2025-06-20 06:48:26', '2025-06-20 06:48:43'),
(23, 'selesai', 'ORD-2506004', 'Joko Siswanto', 'Jalan Pasar Tunjungan', 'https://maps.app.goo.gl/XbGd1g3KMc92qYGe7', 'samping warung madura', '2025-06-25', '11:00:00', 120, '13:00:00', 'Satria, Andi', 'Lunas', '2025-06-20 06:49:23', '2025-06-20 06:49:33'),
(24, 'selesai', 'ORD-2506005', 'Udin', 'Jalan Raya Menganti', 'https://maps.app.goo.gl/pM3NZVTVb91uxv3o8', 'samping sd negeri 5', '2025-06-27', '10:00:00', 60, '11:00:00', 'Bagus', 'DP', '2025-06-20 06:56:00', '2025-06-20 06:56:12'),
(25, 'selesai', 'ORD-2506006', 'Rosa', 'Jalan Kenjeran', 'https://maps.app.goo.gl/qsM97p513bHuCkDE7', 'samping warung soto cak har', '2025-06-30', '10:00:00', 0, '10:00:00', '-', '-', '2025-06-20 06:58:42', '2025-06-20 07:26:23'),
(26, 'Canceled', 'ORD-2506007', 'Javas', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', 'depan indomaret', '2025-07-01', '10:30:00', 0, '10:30:00', '-', '-', '2025-06-20 07:27:08', '2025-06-20 07:41:41'),
(27, 'Rescheduled', 'ORD-2506008', 'Willy Wonka', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'seberang kelurahan', '2025-07-03', '09:30:00', 180, '12:30:00', 'Bejo', 'DP', '2025-06-20 07:48:16', '2025-06-20 07:49:10'),
(28, 'selesai', 'ORD-2506009', 'Willy Wonka', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'seberang kelurahan', '2025-07-04', '10:00:00', 120, '12:00:00', 'Bejo', 'DP', '2025-06-20 07:49:58', '2025-06-20 07:50:10'),
(29, 'scheduled', 'ORD-2506010', 'Javas', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', NULL, '2025-06-25', '09:00:00', 60, '10:00:00', 'Satria', 'Lunas', '2025-06-20 07:51:56', '2025-06-20 07:51:56'),
(30, 'Rescheduled', 'ORD-2506011', 'Plensky', 'Jalan Rungkut Harapan', 'https://maps.app.goo.gl/tZ44zFqFM6FTaFYp8', '-', '2025-06-24', '10:00:00', 120, '12:00:00', 'Bagus', 'DP', '2025-06-21 02:28:31', '2025-06-21 03:01:03'),
(31, 'scheduled', 'ORD-2506014', 'Plensky', 'Jalan Rungkut Harapan', 'https://maps.app.goo.gl/tZ44zFqFM6FTaFYp8', '-', '2025-06-25', '10:00:00', 120, '12:00:00', 'Bagus', 'DP', '2025-06-21 03:01:54', '2025-06-21 03:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `id_kota` int NOT NULL,
  `nama_kota` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`id_kota`, `nama_kota`) VALUES
(1, 'Surabaya'),
(2, 'Sidoarjo'),
(3, 'Gresik');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_rootkategori`
--

CREATE TABLE `layanan_rootkategori` (
  `id` int NOT NULL,
  `nama_rootkategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_rootkategori`
--

INSERT INTO `layanan_rootkategori` (`id`, `nama_rootkategori`, `created_at`, `updated_at`) VALUES
(1, 'Auto Detailing', NULL, NULL),
(2, 'Cuci AC', NULL, NULL),
(3, 'Cuci Kasur', NULL, NULL),
(4, 'Cuci Sofa', NULL, NULL),
(5, 'Cuci Tandon', NULL, NULL),
(6, 'Filter Air', NULL, NULL),
(7, 'General Cleaning', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `layanan_subkategori`
--

CREATE TABLE `layanan_subkategori` (
  `id` int NOT NULL,
  `layanan_rootkategori_id` int NOT NULL,
  `nama_subkategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_subkategori`
--

INSERT INTO `layanan_subkategori` (`id`, `layanan_rootkategori_id`, `nama_subkategori`, `harga`) VALUES
(1, 1, 'Poles Body - S', 425000.00),
(2, 1, 'Poles Body - M', 550000.00),
(3, 1, 'Poles Body - L', 750000.00),
(4, 1, 'Poles Body - XL', 1250000.00),
(5, 1, 'Poles & Scratch Correction - S', 625000.00),
(6, 1, 'Poles & Scratch Correction - M', 750000.00),
(7, 1, 'Poles & Scratch Correction - L', 1000000.00),
(8, 1, 'Poles & Scratch Correction - XL', 1500000.00),
(9, 1, 'Interior Detailing - S', 200000.00),
(10, 1, 'Interior Detailing - M', 325000.00),
(11, 1, 'Interior Detailing - L', 450000.00),
(12, 1, 'Interior Detailing - XL', 750000.00),
(13, 1, 'Engine Detailing - S', 200000.00),
(14, 1, 'Engine Detailing - M', 325000.00),
(15, 1, 'Engine Detailing - L', 450000.00),
(16, 1, 'Engine Detailing - XL', 750000.00),
(17, 1, 'Poles Kaca - S', 200000.00),
(18, 1, 'Poles Kaca - M', 325000.00),
(19, 1, 'Poles Kaca - L', 450000.00),
(20, 1, 'Poles Kaca - XL', 750000.00),
(21, 2, '1/2 PK', 100000.00),
(22, 2, '3/4 PK', 100000.00),
(23, 2, '1 PK', 125000.00),
(24, 2, '1,5 PK', 150000.00),
(25, 2, '2 PK', 150000.00),
(26, 3, 'Basic (4 Sisi) - Bed 90x200', 200000.00),
(27, 3, 'Basic (4 Sisi) - Bed 100x200', 200000.00),
(28, 3, 'Basic (4 Sisi) - Bed 120x200', 200000.00),
(29, 3, 'Basic (4 Sisi) - Bed 140x200', 250000.00),
(30, 3, 'Basic (4 Sisi) - Bed 140x200', 250000.00),
(31, 3, 'Basic (4 Sisi) - Bed 160x200', 250000.00),
(32, 3, 'Basic (4 Sisi) - Bed 180x200', 250000.00),
(33, 3, 'Basic (4 Sisi) - Bed 200x200', 300000.00),
(34, 3, 'Premium (6 Sisi) - Bed 90x200', 250000.00),
(35, 3, 'Premium (6 Sisi) - Bed 100x200', 250000.00),
(36, 3, 'Premium (6 Sisi) - Bed 120x200', 250000.00),
(37, 3, 'Premium (6 Sisi) - Bed 140x200', 300000.00),
(38, 3, 'Premium (6 Sisi) - Bed 160x200', 300000.00),
(39, 3, 'Premium (6 Sisi) - Bed 180x200', 300000.00),
(40, 3, 'Premium (6 Sisi) - Bed 200x200', 350000.00),
(41, 3, 'UV Light - Bed 90x200', 50000.00),
(42, 3, 'UV Light - Bed 100x200', 50000.00),
(43, 3, 'UV Light - Bed 120x200', 50000.00),
(44, 3, 'UV Light - Bed 140x200', 75000.00),
(45, 3, 'UV Light - Bed 160x200', 75000.00),
(46, 3, 'UV Light - Bed 180x200', 75000.00),
(47, 3, 'UV Light - Bed 200x200', 100000.00),
(48, 3, 'Add On - Dipan/Ranjang - Dry Vacuum', 75000.00),
(49, 3, 'Add On - Dipan/Ranjang - Wet & Dry Vacuum', 100000.00),
(50, 3, 'Add On - Headboard - Dry Vacuum', 75000.00),
(51, 3, 'Add On - Headboard - Wet & Dry Vacuum', 100000.00),
(52, 4, 'Sofa 1 Seat - Dry Vacuum', 80000.00),
(53, 4, 'Sofa 1 Seat - Wet & Dry Vacuum', 100000.00),
(54, 4, 'Sofa 1 Seat - UV Light', 50000.00),
(55, 4, 'Sofa 2 Seat Standard - Dry Vacuum', 120000.00),
(56, 4, 'Sofa 2 Seat Standard - Wet & Dry Vacuum', 150000.00),
(57, 4, 'Sofa 2 Seat Standard - UV Light', 75000.00),
(58, 4, 'Sofa 2 Seat Jumbo - Dry Vacuum', 160000.00),
(59, 4, 'Sofa 2 Seat Jumbo - Wet & Dry Vacuum', 200000.00),
(60, 4, 'Sofa 2 Seat Jumbo - UV Light', 75000.00),
(61, 4, 'Sofa 3 Seat Standard - Dry Vacuum', 180000.00),
(62, 4, 'Sofa 3 Seat Standard - Wet & Dry Vacuum', 220000.00),
(63, 4, 'Sofa 3 Seat Standard - UV Light', 100000.00),
(64, 4, 'Sofa 3 Seat Jumbo - Dry Vacuum', 200000.00),
(65, 4, 'Sofa 3 Seat Jumbo - Wet & Dry Vacuum', 250000.00),
(66, 4, 'Sofa 3 Seat Jumbo - UV Light', 100000.00),
(67, 4, 'Sofa Letter L Standard - Dry Vacuum', 240000.00),
(68, 4, 'Sofa Letter L Standard - Wet & Dry Vacuum', 300000.00),
(69, 4, 'Sofa Letter L Standard - UV Light', 150000.00),
(70, 4, 'Sofa Letter L Jumbo - Dry Vacuum', 280000.00),
(71, 4, 'Sofa Letter L Jumbo - Wet & Dry Vacuum', 350000.00),
(72, 4, 'Sofa Letter L Jumbo - UV Light', 150000.00),
(73, 4, 'Sofa Bed Standard - Dry Vacuum', 200000.00),
(74, 4, 'Sofa Bed Standard - Wet & Dry Vacuum', 250000.00),
(75, 4, 'Sofa Bed Standard - UV Light', 100000.00),
(76, 4, 'Sofa Bed Jumbo - Dry Vacuum', 240000.00),
(77, 4, 'Sofa Bed Jumbo - Wet & Dry Vacuum', 300000.00),
(78, 4, 'Sofa Bed Jumbo - UV Light', 100000.00),
(79, 4, 'Sofa Recliner Perseat - Dry Vacuum', 120000.00),
(80, 4, 'Sofa Recliner Perseat - Wet & Dry Vacuum', 150000.00),
(81, 4, 'Sofa Recliner Perseat - UV Light', 50000.00),
(82, 5, 'Bawah - Small 0-3 m3', 150000.00),
(83, 5, 'Bawah - Medium >3-5 m3', 200000.00),
(84, 5, 'Bawah - Large >5-7 m3', 250000.00),
(85, 5, 'Atas - Small 0-800 l', 150000.00),
(86, 5, 'Atas - Medium >800-1200 l', 200000.00),
(87, 5, 'Atas - Large >1200-2000 l', 250000.00),
(88, 6, 'Paket Instalasi Baru - Basic Package (1 tabung)', 2500000.00),
(89, 6, 'Paket Instalasi Baru - Basic Package (2 tabung)', 4200000.00),
(90, 6, 'Paket Instalasi Baru - Premium Package', 4000000.00),
(91, 6, 'Paket Instalasi Baru - Premium Package (2 tabung)', 6000000.00),
(92, 6, 'Maintenance - Cuci Media (per tabung)', 500000.00),
(93, 6, 'Maintenance - Ganti Media (bahan lokal)', 1000000.00),
(94, 6, 'Maintenance - Ganti Media (bahan import)', 1600000.00),
(95, 7, 'Paket 3 jam - 1 cleaner', 175000.00),
(96, 7, 'Paket 3 jam - 2 cleaner', 300000.00),
(97, 7, 'Paket 5 jam - 1 cleaner', 275000.00),
(98, 7, 'Paket 5 jam - 2 cleaner', 500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_20_041054_add_catatan_to_pelanggan_table', 2),
(5, '2025_05_20_052557_add_alamat_order_to_orders_table', 3),
(6, '2025_05_06_114542_add_diskon_to_order_table', 4),
(7, '2025_05_26_070711_create_promo_table', 5),
(8, '2025_05_29_105742_add_updated_column_to_order_detail_table', 6),
(9, '2025_04_25_112632_create_petugas_table', 7),
(10, '2025_06_14_114828_add_timestamps_to_layanan_rootkategori', 8),
(11, '2025_06_15_073223_add_status_to_orders_table', 9),
(13, '2025_06_19_062915_add_metode_tipe_pembayaran_to_orders_table', 10),
(14, '2025_06_19_075509_create_jadwals_table', 11),
(15, '2025_06_19_102956_add_status_to_jadwals_table', 12),
(16, '2025_06_20_022430_remove_id_petugas_from_order_detail_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `reschedule_from` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alasan_reschedule` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Request',
  `id_pelanggan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_lokasi` text COLLATE utf8mb4_general_ci,
  `lokasi_gmaps` text COLLATE utf8mb4_general_ci,
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal_pengerjaan` date DEFAULT NULL,
  `jam_pengerjaan` time DEFAULT NULL,
  `total_harga` decimal(12,2) DEFAULT NULL,
  `diskon` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kode` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `metode_pembayaran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe_pembayaran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `reschedule_from`, `alasan_reschedule`, `status`, `id_pelanggan`, `alamat_lokasi`, `lokasi_gmaps`, `catatan`, `tanggal_pengerjaan`, `jam_pengerjaan`, `total_harga`, `diskon`, `kode`, `metode_pembayaran`, `tipe_pembayaran`, `created_at`, `updated_at`) VALUES
('ORD-2506001', NULL, NULL, 'Selesai', 'CS2506002', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', 'rumah hijau', '2025-06-23', '11:00:00', 420000.00, 5000.00, 'hemat5', 'Lunas', 'Transfer', '2025-06-20 02:43:12', '2025-06-23 07:01:07'),
('ORD-2506002', NULL, NULL, 'Selesai', 'CS2506003', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'samping bank bri', '2025-06-24', '09:00:00', 100000.00, 0.00, NULL, 'Lunas', 'Transfer', '2025-06-20 02:44:45', '2025-06-23 07:01:07'),
('ORD-2506003', NULL, NULL, 'Selesai', 'CS2506004', 'Jalan Raya Prapen', 'https://maps.app.goo.gl/KiPi75rPFotoCuNUA', 'pager hitam, blablabla', '2025-06-24', '09:00:00', 250000.00, 50000.00, 'potong50', 'Lunas', 'Transfer', '2025-06-20 03:14:03', '2025-06-23 07:01:07'),
('ORD-2506004', NULL, NULL, 'Selesai', 'CS2506005', 'Jalan Pasar Tunjungan', 'https://maps.app.goo.gl/XbGd1g3KMc92qYGe7', 'samping warung madura', '2025-06-25', '11:00:00', 140000.00, 10000.00, 'diskon10', 'Lunas', 'Transfer', '2025-06-20 03:18:48', '2025-06-23 07:01:07'),
('ORD-2506005', NULL, NULL, 'Selesai', 'CS2506006', 'Jalan Raya Menganti', 'https://maps.app.goo.gl/pM3NZVTVb91uxv3o8', 'samping sd negeri 5', '2025-06-27', '10:00:00', 200000.00, 0.00, NULL, 'Lunas', 'Transfer', '2025-06-20 06:53:38', '2025-06-23 07:01:07'),
('ORD-2506006', NULL, NULL, 'Selesai', 'CS2506007', 'Jalan Kenjeran', 'https://maps.app.goo.gl/qsM97p513bHuCkDE7', 'samping warung soto cak har', '2025-06-30', '10:00:00', 425000.00, 0.00, NULL, 'DP', 'Transfer', '2025-06-20 06:58:30', '2025-06-23 07:01:07'),
('ORD-2506007', NULL, NULL, 'Canceled', 'CS2506002', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', 'depan indomaret', '2025-07-01', '10:30:00', 200000.00, 0.00, NULL, 'DP', 'Transfer', '2025-06-20 07:27:04', '2025-06-20 07:41:41'),
('ORD-2506008', NULL, NULL, 'Rescheduled', 'CS2506003', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'seberang kelurahan', '2025-07-03', '09:30:00', 2500000.00, 0.00, NULL, 'DP', 'Transfer', '2025-06-20 07:31:21', '2025-06-20 07:49:10'),
('ORD-2506009', 'ORD-2506008', 'pelanggan ke kondangan', 'Selesai', 'CS2506003', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'seberang kelurahan', '2025-07-04', '10:00:00', 2000000.00, 500000.00, NULL, 'DP', 'Transfer', '2025-06-20 07:49:10', '2025-06-23 07:01:07'),
('ORD-2506010', NULL, NULL, 'Scheduled', 'CS2506002', 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', NULL, '2025-06-25', '09:00:00', 240000.00, 10000.00, 'diskon10', 'Lunas', 'Transfer', '2025-06-20 07:51:37', '2025-06-20 07:51:56'),
('ORD-2506011', NULL, NULL, 'Rescheduled', 'CS2506008', 'Jalan Rungkut Harapan', 'https://maps.app.goo.gl/tZ44zFqFM6FTaFYp8', '-', '2025-06-24', '10:00:00', 145000.00, 5000.00, 'hemat5', 'DP', 'Transfer', '2025-06-20 07:53:59', '2025-06-21 03:01:03'),
('ORD-2506012', NULL, NULL, 'Request', 'CS2506006', 'Jalan Raya Menganti', 'https://maps.app.goo.gl/pM3NZVTVb91uxv3o8', 'Masuk gang', '2025-06-23', '14:00:00', 190000.00, 10000.00, 'diskon10', 'DP', 'Transfer', '2025-06-20 22:40:47', '2025-06-21 14:46:58'),
('ORD-2506013', NULL, NULL, 'Request', 'CS2506004', 'Jalan Raya Prapen', 'https://maps.app.goo.gl/KiPi75rPFotoCuNUA', 'Samping alfamidi', '2025-06-24', '13:00:00', 145000.00, 5000.00, 'hemat5', 'Lunas', 'Transfer', '2025-06-21 01:35:58', '2025-06-21 14:46:58'),
('ORD-2506014', 'ORD-2506011', 'hujan', 'Scheduled', 'CS2506008', 'Jalan Rungkut Harapan', 'https://maps.app.goo.gl/tZ44zFqFM6FTaFYp8', '-', '2025-06-25', '10:00:00', 145000.00, 5000.00, 'hemat5', 'DP', 'Transfer', '2025-06-21 03:01:03', '2025-06-22 23:30:25'),
('ORD-2506015', NULL, NULL, 'Request', 'CS2506003', 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'sebelah toko listrik', '2025-06-25', '09:00:00', 425000.00, 0.00, NULL, NULL, NULL, '2025-06-21 07:34:41', '2025-06-21 14:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_order_detail` bigint UNSIGNED NOT NULL,
  `id_order` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `id_layanan_subkategori` int DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `durasi_layanan` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id_order_detail`, `id_order`, `id_layanan_subkategori`, `harga`, `durasi_layanan`, `created_at`, `updated_at`) VALUES
(265, 'ORD-2506001', 1, 425000.00, 60, '2025-06-20 02:43:12', '2025-06-20 06:28:15'),
(266, 'ORD-2506002', 21, 100000.00, 60, '2025-06-20 02:44:45', '2025-06-20 02:45:10'),
(267, 'ORD-2506003', 96, 300000.00, 180, '2025-06-20 03:14:03', '2025-06-20 06:48:13'),
(268, 'ORD-2506004', 85, 150000.00, 60, '2025-06-20 03:18:48', '2025-06-20 06:49:16'),
(269, 'ORD-2506004', 82, 150000.00, 60, '2025-06-20 03:22:23', '2025-06-20 06:49:16'),
(270, 'ORD-2506002', 25, 150000.00, NULL, '2025-06-20 03:25:31', '2025-06-20 03:25:31'),
(271, 'ORD-2506005', 26, 200000.00, 60, '2025-06-20 06:53:38', '2025-06-20 06:55:44'),
(272, 'ORD-2506006', 1, 425000.00, 60, '2025-06-20 06:58:30', '2025-06-20 06:59:39'),
(273, 'ORD-2506007', 9, 200000.00, 45, '2025-06-20 07:27:04', '2025-06-20 07:27:22'),
(274, 'ORD-2506008', 88, 2500000.00, 180, '2025-06-20 07:31:21', '2025-06-20 07:31:39'),
(275, 'ORD-2506009', 88, 2500000.00, 120, '2025-06-20 07:49:10', '2025-06-20 07:49:32'),
(276, 'ORD-2506010', 34, 250000.00, 60, '2025-06-20 07:51:37', '2025-06-20 07:51:48'),
(277, 'ORD-2506011', 53, 100000.00, 60, '2025-06-20 07:53:59', '2025-06-20 07:54:54'),
(278, 'ORD-2506011', 54, 50000.00, 60, '2025-06-20 07:53:59', '2025-06-20 07:54:54'),
(279, 'ORD-2506012', 26, 200000.00, 60, '2025-06-20 22:40:47', '2025-06-21 02:27:45'),
(280, 'ORD-2506013', 24, 150000.00, 60, '2025-06-21 01:35:58', '2025-06-21 02:28:13'),
(281, 'ORD-2506014', 53, 100000.00, 60, '2025-06-21 03:01:03', '2025-06-22 23:30:25'),
(282, 'ORD-2506014', 54, 50000.00, 60, '2025-06-21 03:01:03', '2025-06-22 23:30:25'),
(283, 'ORD-2506015', 1, 425000.00, NULL, '2025-06-21 07:34:41', '2025-06-21 07:34:41');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail_petugas`
--

CREATE TABLE `order_detail_petugas` (
  `id` int NOT NULL,
  `id_order_detail` bigint UNSIGNED NOT NULL,
  `id_petugas` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail_petugas`
--

INSERT INTO `order_detail_petugas` (`id`, `id_order_detail`, `id_petugas`) VALUES
(128, 266, 'PK2506004'),
(132, 270, 'PK2506004'),
(137, 265, 'PK2506004'),
(138, 267, 'PK2506001'),
(139, 267, 'PK2506002'),
(140, 268, 'PK2506001'),
(141, 269, 'PK2506002'),
(142, 271, 'PK2506003'),
(144, 272, 'PK2506001'),
(145, 273, 'PK2506002'),
(146, 274, 'PK2506004'),
(148, 275, 'PK2506004'),
(149, 276, 'PK2506001'),
(150, 277, 'PK2506003'),
(151, 278, 'PK2506003'),
(152, 279, 'PK2506004'),
(154, 280, 'PK2506001'),
(161, 281, 'PK2506003'),
(162, 282, 'PK2506003');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telp_pelanggan` varchar(13) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kota` int DEFAULT NULL,
  `alamat_lokasi` text COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi_gmaps` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `telp_pelanggan`, `email`, `id_kota`, `alamat_lokasi`, `lokasi_gmaps`, `catatan`, `created_at`, `updated_at`) VALUES
('CS2506002', 'Javas', '089612345678', 'javas@gmail.com', 2, 'Jalan Raya Buduran', 'https://maps.app.goo.gl/VKSj4BSU38zCoy7Y9', 'sebelah warung madura', '2025-06-14 22:37:27', '2025-06-14 22:37:27'),
('CS2506003', 'Willy Wonka', '081234566543', 'williwonka@gmail.com', 3, 'Jalan Gresik Kota Baru', 'https://maps.app.goo.gl/Hz9PM5fQZQ8iQDup8', 'rumah pagar merah', '2025-06-14 22:44:48', '2025-06-14 22:51:33'),
('CS2506004', 'Edo', '081234567898', 'edo@gmail.com', 1, 'Jalan Raya Prapen', 'https://maps.app.goo.gl/KiPi75rPFotoCuNUA', 'rumah pagar kayu', '2025-06-15 00:25:24', '2025-06-15 00:25:24'),
('CS2506005', 'Joko Siswanto', '0853123454321', 'joko@gmail.com', 1, 'Jalan Pasar Tunjungan', 'https://maps.app.goo.gl/XbGd1g3KMc92qYGe7', 'akbvaerv abvakuerka aeahynmeyu', '2025-06-17 00:14:54', '2025-06-17 00:14:54'),
('CS2506006', 'Udin', '089655447788', 'udin@gmail.com', 3, 'Jalan Raya Menganti', 'https://maps.app.goo.gl/pM3NZVTVb91uxv3o8', 'samping toko bangunan sbr, ada anjingnya tpi tidak galak', '2025-06-19 02:01:38', '2025-06-19 02:01:38'),
('CS2506007', 'Rosa', '081288557766', 'rosa@gmail.com', 1, 'Jalan Kenjeran', 'https://maps.app.goo.gl/qsM97p513bHuCkDE7', 'badkjbahoierv aeoiurvaoerva', '2025-06-19 02:53:31', '2025-06-19 02:53:31'),
('CS2506008', 'Plensky', '088812213443', 'plensky@gmail.com', 1, 'Jalan Rungkut Harapan', 'https://maps.app.goo.gl/tZ44zFqFM6FTaFYp8', '-', '2025-06-20 07:53:59', '2025-06-20 07:53:59');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_petugas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `no_telp`, `is_available`, `created_at`, `updated_at`) VALUES
('PK2506001', 'Satria', '081234567899', 1, '2025-06-14 04:43:24', '2025-06-14 04:43:24'),
('PK2506002', 'Andi', '089712345678', 1, '2025-06-14 22:00:20', '2025-06-14 22:00:20'),
('PK2506003', 'Bagus', '085345678765', 1, '2025-06-16 09:24:11', '2025-06-16 09:24:11'),
('PK2506004', 'Bejo', '087045678899', 1, '2025-06-19 02:02:48', '2025-06-19 02:02:48');

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `kode`, `diskon`, `created_at`, `updated_at`) VALUES
(3, 'DISKON10', 10000.00, '2025-05-27 03:42:37', '2025-05-27 03:42:37'),
(4, 'POTONG50', 50000.00, '2025-05-27 03:42:37', '2025-05-27 03:42:37'),
(5, 'HEMAT5', 5000.00, '2025-05-27 03:42:37', '2025-05-27 03:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mb7MwUV9nLWEL9PUU91LordWpVkm2AN2FsoejSxC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOFN1bHJHbTFGREZrY2oxSmN3UWZmWm9UVEZMbXVqR1M2bzN6ZUVYaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9tby9jaGVjaz9rb2RlPUFTVkFWIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750769387),
('vHbi0qtvh1aTq1RwWIIwTklcOMRjXqlUObpStHti', NULL, '192.168.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVVrWE5tNXpMcWhEMDFKTzM0dTZHVUtqb21OUmJhMVNvZGs0RWRjYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHA6Ly8xOTIuMTY4LjEuNDo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750768611);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_id_order_foreign` (`id_order`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kota`
--
ALTER TABLE `kota`
  ADD PRIMARY KEY (`id_kota`);

--
-- Indexes for table `layanan_rootkategori`
--
ALTER TABLE `layanan_rootkategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan_subkategori`
--
ALTER TABLE `layanan_subkategori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layanan_rootkategori_id` (`layanan_rootkategori_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_layanan_subkategori` (`id_layanan_subkategori`);

--
-- Indexes for table `order_detail_petugas`
--
ALTER TABLE `order_detail_petugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_detail_petugas_id_order_detail_foreign` (`id_order_detail`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD KEY `id_kota` (`id_kota`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_kode_unique` (`kode`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kota`
--
ALTER TABLE `kota`
  MODIFY `id_kota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layanan_rootkategori`
--
ALTER TABLE `layanan_rootkategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `layanan_subkategori`
--
ALTER TABLE `layanan_subkategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_order_detail` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;

--
-- AUTO_INCREMENT for table `order_detail_petugas`
--
ALTER TABLE `order_detail_petugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;

--
-- Constraints for table `layanan_subkategori`
--
ALTER TABLE `layanan_subkategori`
  ADD CONSTRAINT `layanan_subkategori_ibfk_1` FOREIGN KEY (`layanan_rootkategori_id`) REFERENCES `layanan_rootkategori` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_layanan_subkategori`) REFERENCES `layanan_subkategori` (`id`);

--
-- Constraints for table `order_detail_petugas`
--
ALTER TABLE `order_detail_petugas`
  ADD CONSTRAINT `order_detail_petugas_id_order_detail_foreign` FOREIGN KEY (`id_order_detail`) REFERENCES `order_detail` (`id_order_detail`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
