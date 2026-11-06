-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 04:14 AM
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
-- Database: `dinsos`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset_keluargas`
--

CREATE TABLE `aset_keluargas` (
  `id` smallint(6) NOT NULL,
  `aset_keluarga` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset_keluargas`
--

INSERT INTO `aset_keluargas` (`id`, `aset_keluarga`) VALUES
(1, 'Tabung Gas 3 KG'),
(2, 'Tabung Gas 5,5 KG atau Lebih'),
(3, 'Lemari Es/Kulkas'),
(4, 'AC (Air Conditioner)'),
(5, 'Emas/Perhiasan'),
(6, 'Komputer/Laptop/Tablet'),
(7, 'Sepeda Motor'),
(8, 'Mobil');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kecamatans`
--

CREATE TABLE `kecamatans` (
  `kecamatan_id` varchar(8) NOT NULL,
  `deskripsi` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kecamatans`
--

INSERT INTO `kecamatans` (`kecamatan_id`, `deskripsi`) VALUES
('357501', 'Gadingrejo'),
('357502', 'Purworejo'),
('357503', 'Bugul Kidul'),
('357504', 'Panggungrejo');

-- --------------------------------------------------------

--
-- Table structure for table `keluargas`
--

CREATE TABLE `keluargas` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `no_kk` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `status_keluarga` varchar(255) NOT NULL,
  `kecamatan_id` varchar(20) DEFAULT NULL,
  `kelurahan_id` varchar(20) DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `geotagging` varchar(255) DEFAULT NULL,
  `tanggal_entri` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluargas`
--

INSERT INTO `keluargas` (`id`, `kode`, `no_kk`, `nik`, `nama_lengkap`, `status_keluarga`, `kecamatan_id`, `kelurahan_id`, `rt`, `rw`, `kode_pos`, `alamat_lengkap`, `created_by`, `created_at`, `updated_by`, `updated_at`, `geotagging`, `tanggal_entri`) VALUES
(158, 'KLG-2A0PJJYEAA', 'enc:v1:eyJpdiI6InVza1kzMXlwMkkwSS9VVTV5OXVCT0E9PSIsInZhbHVlIjoiT1VJWFAvdUVVdENkTVRRZTJiTWlHV1J2a2hqdkZCOFc1cVVnM0tkVThwRT0iLCJtYWMiOiIxNmEwNWE3ZDZhMWNjNGVlMDIyY2Q4Njc1NWRhZTM1MzBlZmRhNmY0YTdkOWJhN2JjZmFkN2FjNjBlNWFmNGQ5IiwidGFnIjoiIn0=', 'enc:v1:eyJpdiI6Ilp1MCtZeE1rTlFnK2xNZ0xZWC94bHc9PSIsInZhbHVlIjoieEVFd1dzdXB3S0haekhPVDQxUGd4OTRqdEp5VGs5VGZ6dVRzcHk3bUowbz0iLCJtYWMiOiIxNDhmZTUwYzFhMDFlODhmZjVmZjFhYWFiOTA3YTU5ZjE3NWEwYmE4MTIxM2U1ZGVhNTVhNDlkN2VlOWUwODk1IiwidGFnIjoiIn0=', 'muhamad firmansyah', 'Kepala Keluarga', '357504', '3575041010', '009', '008', '675543', 'jl mancilan', 'Husnatul Adibah', '2026-11-03 18:31:03', NULL, '2026-11-03 18:31:03', '-7.6504054, 112.8930394', '2026-11-04'),
(159, 'KLG-UA07RUYTCO', 'enc:v1:eyJpdiI6IjVlNjE2NTN4bU54NysxZ1dDRlhXUkE9PSIsInZhbHVlIjoiZGlRUGU0Q0tFZjZUb2FSbVl0a1JrQk91MUJabTdudVlINFBEdTFvZ3Vjcz0iLCJtYWMiOiJkZDEyNmU5ODVlMmMxOWIwOTMzYjY5OTg0ZGZkODc5N2Q5OWEzNjE5NTVjODliMjVmMWUwNDhlOTE1MjFhMjBhIiwidGFnIjoiIn0=', 'enc:v1:eyJpdiI6IklZUzk5UWEyTysxSWhKUHJkc2NGREE9PSIsInZhbHVlIjoiVmQ2U0ZGeXArWjFLdERoRTJYaFFpOFh0U3B3T0tQVnNJSjRkSThScE5JWT0iLCJtYWMiOiI0NDcyMjQ2MTkyMzJiNjRkNTU3N2NiNmU5N2Y0MWNmYTliZTU0Nzc3MmVjY2I2NDYzYzIxMjVlNmMwNzFhODM5IiwidGFnIjoiIn0=', 'abdul aziz', 'Kepala Keluarga', '357503', '3575031009', '001', '004', '67112', 'jl mancilan raya', 'Husnatul Adibah', '2026-11-03 18:31:57', NULL, '2026-11-03 18:31:57', '-7.6491578, 112.8995310', '2026-11-04'),
(160, 'KLG-MN0J8STDSI', 'enc:v1:eyJpdiI6InJkU1FLY09yUSs3YjJEbnMvd0liYnc9PSIsInZhbHVlIjoiM28ySUJhNE8yUXJhd3BHWlZkck9MT0VBRmdSRkhhcUhKd21vYlJTaHF3Zz0iLCJtYWMiOiIwYmUzZDZhM2U2YmQxZjMyMmNmOGQzNmE2ZjRkODQ0YzE3NGY4ZTFjMTRlNDAzYjY2NzEyYjhhMjhlNjRmZDE0IiwidGFnIjoiIn0=', 'enc:v1:eyJpdiI6ImMzZUplZzZPNWtyblVFVFBZdHFIcEE9PSIsInZhbHVlIjoid0pudzZZNnUwT0xDWTBzdHdXeDl6bnVyTXdaczZyQUhEUjBTNWpGbFhRND0iLCJtYWMiOiI2ZTI3YWJmZTVjMTY3NTU2NmFmZDE5NmE2ZDYzN2VkNDgzZjJiZTQ0Mjk1OWRkZGI3OTdlNTY5NGIwYTdkMjRkIiwidGFnIjoiIn0=', 'fatkhul bari', 'Kepala Keluarga', '357502', '3575021004', '009', '004', '67115', 'jl temenggungan', 'Husnatul Adibah', '2026-11-03 18:32:48', NULL, '2026-11-03 18:32:48', '-7.6540066, 112.8983299', '2026-11-04'),
(161, 'KLG-HYYIGLXWVQ', 'enc:v1:eyJpdiI6InZocXprbThpWVZLK1E2dk0zN3JTUlE9PSIsInZhbHVlIjoiczkreXpZZmxZSS9HSjFPbm02S3RObktOQ2IxRkhFQytFb1pWalVqdVB3MD0iLCJtYWMiOiJiYjQ4NzkwY2RjNmRkOWM2MjhhNzgyOWYwZDY1MGFiYTQ1OWU4N2E4NmQxNjZmYWFlMTZkYTlmZDQ1MDBlMWMzIiwidGFnIjoiIn0=', 'enc:v1:eyJpdiI6IjNnVDF1ZXE1bDBmSHJITHlkSFIxU0E9PSIsInZhbHVlIjoiTlpCYXVTaU03dVpyaDltcTM3dkhWdXVJREFLTTVIWFFVMVdxT3JsSEVpTT0iLCJtYWMiOiI3NzZjOTI3ZjNlYmUxMjVhYjhmNTYzODc3NmU3OTdkYTU0MzQ1ZGY5NGY4ZTAzYmUwYWQ1OTk4NmJhZjNkNWU5IiwidGFnIjoiIn0=', 'M. Tawfikur Rohman', 'Kepala Keluarga', '357503', '3575031005', '001', '002', '87768', 'Jl. Mawar', 'Husnatul Adibah', '2026-11-03 18:43:32', NULL, '2026-11-03 18:43:32', '-7.6465791, 112.9079001', '2026-11-04'),
(162, 'KLG-AACCNZMJJA', 'enc:v1:eyJpdiI6IlBWc0p5cGJ5VnNISEdTcEl1b3Ira1E9PSIsInZhbHVlIjoiTDhySkIzOHRyQnowM3k3YW5CcDI1K3plWnhjcy9Rc2oxZ3VhRm1ISXdjYz0iLCJtYWMiOiI0NDczZWJlOWM3M2Q0NzNiZmFmYzQyMTAwYTc2YzZiNmIyOWY3ZTBlOTgwMDRiZWZmOWE5Y2Y5ZTA2ODRmYjRlIiwidGFnIjoiIn0=', 'enc:v1:eyJpdiI6IityYmFreEhIZjF0ZzlIbjFCci9HVlE9PSIsInZhbHVlIjoiK1VqMU5UZCtYRndHNHlsMmRZaWFTWldjaVJrZWNFSERCUEpNZmlubDlVcz0iLCJtYWMiOiJmZmQ5YmVhMDg0NTdmNWNlYzFkZjZmZWQ2OGJkZDMyZTIxZTc0MWY3ZjdlZmRmMGJhMjQ5YTNjODY0YzQ4ZGU5IiwidGFnIjoiIn0=', 'Santoso', 'Kepala Keluarga', '357503', '3575031009', '002', '009', '67171', 'Jalan Bugul Kidul', 'Husnatul Adibah', '2026-11-03 19:15:17', NULL, '2026-11-03 19:15:17', '-7.6435600, 112.9136735', '2026-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `keluarga_anggotas`
--

CREATE TABLE `keluarga_anggotas` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `keluarga_kode` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `status_keluarga` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluarga_anggotas`
--

INSERT INTO `keluarga_anggotas` (`id`, `kode`, `keluarga_kode`, `nik`, `nama_lengkap`, `status_keluarga`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(330, 'ANG-A6VYTHEJSJ', 'KLG-UA07RUYTCO', 'enc:v1:eyJpdiI6IjJzRytXTFN4Q1hhY2VqVHM0MFAraUE9PSIsInZhbHVlIjoiK2drRU1iNHNtcnJpT0txN2FRZjlwaUt6WlQ5aytKWmJCK3N1WWhLMTBiVT0iLCJtYWMiOiIyZWI4YmRhMzU1YzVkNmFkMTA5ZWVjMTdiZjdhNjczZDc4OWU3NWI2MzU2ZThlOTZmZTEyMmRmNDJlZDNhMjcyIiwidGFnIjoiIn0=', 'Faiz dwi Lestari', 'Istri', 'Husnatul Adibah', '2026-11-03 18:31:57', NULL, '2026-11-04 01:31:57'),
(331, 'ANG-ZKP30JMFXQ', 'KLG-MN0J8STDSI', 'enc:v1:eyJpdiI6IlhDSFZYeTZoU3pwYzdHWW93OVI3NGc9PSIsInZhbHVlIjoid3J6Y01ScnIyaU14WDdpYTdGSnRiMmNadGYyUm13RHdSclJaQnlDMFI1dz0iLCJtYWMiOiI3MTYxMmE1MzRkYzEwN2Q3NDVlZWRiMWQ0YzlkM2JkMmI2NWY4OGNkZGVmYTQxMmJhMTY0MjUzY2E0ZTJhMTVmIiwidGFnIjoiIn0=', 'muzainia', 'Istri', 'Husnatul Adibah', '2026-11-03 18:32:48', NULL, '2026-11-04 01:32:48'),
(332, 'ANG-2LGDFWBJXM', 'KLG-HYYIGLXWVQ', 'enc:v1:eyJpdiI6Ind1U3hCbmtDMUorVHkwVFFWQ0Nnenc9PSIsInZhbHVlIjoiZ0tzeXdlbG9CV3pXaU5CRFNYNGZhWEZFSnJkcU42NEt6aURiYjNtRWdtWT0iLCJtYWMiOiIxODg1MzFlNDlmMGM2NGMyMjk2NTUyMzIzODYxZDkyNTc1ZmEyMjAyODU3YjY1MTAyZThjOWY5OTA3MmU5YWI5IiwidGFnIjoiIn0=', 'Husnatul Adibah', 'Istri', 'Husnatul Adibah', '2026-11-03 18:43:32', NULL, '2026-11-04 01:43:32'),
(333, 'ANG-MJNYNQLZQW', 'KLG-AACCNZMJJA', 'enc:v1:eyJpdiI6IjB1aXpWcDlnbVlyMFJKMHlUeTVSVFE9PSIsInZhbHVlIjoiUmY5dHc1SkVua1pzcHBYcEQrWmRPa3FyY2JGaW95VWE1OTkzb1ltR2dqRT0iLCJtYWMiOiJkNGI4ODk0ZTQ0OTRkYjVlZDQ4YTJlYjlhMDM5YjA5MjZhMDJlZDhiMGQzMTEzMWZhNTBjNDZhMDIzYWMyM2I3IiwidGFnIjoiIn0=', 'siti', 'Istri', 'Husnatul Adibah', '2026-11-03 19:15:17', NULL, '2026-11-04 02:15:17'),
(334, 'ANG-ZPJ059TGZZ', 'KLG-AACCNZMJJA', 'enc:v1:eyJpdiI6IjFId0NwRGFBdWg0SG84RnRTTkYwc1E9PSIsInZhbHVlIjoiNWUrK0MrUms0OTlHcGNaMmNjWlJxeHpzb3l1a2pvdlJhdUNUeHA4MGRuMD0iLCJtYWMiOiJlNmRjMzIyODExOTFjYWQ1MTJkNDBkZmUwMzMzYzU2ZGMwOTUzOTI4Mjk0ZTg5YmRmZTRmMTM0M2VjNGZkYjZhIiwidGFnIjoiIn0=', 'Anto', 'Anak', 'Husnatul Adibah', '2026-11-03 19:15:17', NULL, '2026-11-04 02:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `keluarga_periodes`
--

CREATE TABLE `keluarga_periodes` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `keluarga_kode` varchar(255) NOT NULL,
  `periode_kode` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `status_kuisoner` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluarga_periodes`
--

INSERT INTO `keluarga_periodes` (`id`, `kode`, `keluarga_kode`, `periode_kode`, `is_active`, `status_kuisoner`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(65, 'KLP-HVUGVZB2QV', 'KLG-2A0PJJYEAA', 'PER-001', 1, 'belum', 'Husnatul Adibah', '2026-11-03 18:31:03', NULL, '2026-11-03 18:31:03'),
(66, 'KLP-MQFCRGNB01', 'KLG-UA07RUYTCO', 'PER-001', 1, 'belum', 'Husnatul Adibah', '2026-11-03 18:31:57', NULL, '2026-11-03 18:31:57'),
(67, 'KLP-VAGRA1YAKM', 'KLG-MN0J8STDSI', 'PER-001', 1, 'belum', 'Husnatul Adibah', '2026-11-03 18:32:48', NULL, '2026-11-03 18:32:48'),
(68, 'KLP-UJVZFD3JIP', 'KLG-2A0PJJYEAA', 'PER-012', 1, 'belum', 'Husnatul Adibah', '2026-11-04 03:29:05', 'admin', '2026-11-03 20:29:05'),
(69, 'KLP-Y3GSDAEZV0', 'KLG-UA07RUYTCO', 'PER-012', 1, 'belum', 'Husnatul Adibah', '2026-11-04 03:29:05', 'admin', '2026-11-03 20:29:05'),
(70, 'KLP-GQWQWKMOID', 'KLG-MN0J8STDSI', 'PER-012', 1, 'belum', 'Husnatul Adibah', '2026-11-04 03:29:05', 'admin', '2026-11-03 20:29:05'),
(71, 'KLP-IMIAH0KMIC', 'KLG-HYYIGLXWVQ', 'PER-012', 1, 'belum', 'Husnatul Adibah', '2026-11-04 03:29:05', 'admin', '2026-11-03 20:29:05'),
(72, 'KLP-ORZWX7SQPY', 'KLG-2A0PJJYEAA', 'PER-013', 1, 'belum', 'Husnatul Adibah', '2026-11-03 19:12:17', NULL, '2026-11-03 19:12:17'),
(73, 'KLP-RGQ79C6DXP', 'KLG-UA07RUYTCO', 'PER-013', 1, 'belum', 'Husnatul Adibah', '2026-11-03 19:12:17', NULL, '2026-11-03 19:12:17'),
(74, 'KLP-VJXEQ02JWX', 'KLG-MN0J8STDSI', 'PER-013', 1, 'belum', 'Husnatul Adibah', '2026-11-03 19:12:17', NULL, '2026-11-03 19:12:17'),
(75, 'KLP-BLHCNMRBQW', 'KLG-HYYIGLXWVQ', 'PER-013', 0, 'belum', 'Husnatul Adibah', '2026-11-04 02:15:56', 'Husnatul Adibah', '2026-11-03 19:15:56'),
(76, 'KLP-CUZSBX5EJ1', 'KLG-AACCNZMJJA', 'PER-013', 1, 'belum', 'Husnatul Adibah', '2026-11-04 02:15:47', 'Husnatul Adibah', '2026-11-03 19:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `kelurahans`
--

CREATE TABLE `kelurahans` (
  `kelurahan_id` varchar(13) NOT NULL,
  `kecamatan_id` varchar(8) NOT NULL,
  `deskripsi` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelurahans`
--

INSERT INTO `kelurahans` (`kelurahan_id`, `kecamatan_id`, `deskripsi`) VALUES
('3575011001', '357501', 'Karangketug'),
('3575011002', '357501', 'Gentong'),
('3575011003', '357501', 'Sebani'),
('3575011004', '357501', 'Petahunan'),
('3575011005', '357501', 'Bukir'),
('3575011006', '357501', 'Randusari'),
('3575011007', '357501', 'Krapyakrejo'),
('3575011009', '357501', 'Gadingrejo'),
('3575021001', '357502', 'Pohjentrek'),
('3575021002', '357502', 'Wirogunan'),
('3575021003', '357502', 'Tembokrejo'),
('3575021004', '357502', 'Purutrejo'),
('3575021005', '357502', 'Kebonagung'),
('3575021006', '357502', 'Purworejo'),
('3575021011', '357502', 'Sekargadung'),
('3575031001', '357503', 'Blandongan'),
('3575031002', '357503', 'Kepel'),
('3575031003', '357503', 'Tapa\'an'),
('3575031005', '357503', 'Bakalan'),
('3575031006', '357503', 'Krampyangan'),
('3575031009', '357503', 'Bugul Kidul'),
('3575041001', '357504', 'Karanganyar'),
('3575041002', '357504', 'Tamba\'an'),
('3575041003', '357504', 'Trajeng'),
('3575041004', '357504', 'Bangilan'),
('3575041005', '357504', 'Kebonsari'),
('3575041006', '357504', 'Mayangan'),
('3575041007', '357504', 'Ngemplakrejo'),
('3575041008', '357504', 'Petamanan'),
('3575041009', '357504', 'Pekuncen'),
('3575041010', '357504', 'Bugul Lor'),
('3575041011', '357504', 'Kandangsapi'),
('3575041012', '357504', 'Panggungrejo'),
('3575041013', '357504', 'Mandaranrejo');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_09_21_035314_add_terakhir_login_to_petugas_table', 1),
(2, '2026_09_23_022952_create_rt_rws_table', 1),
(3, '2026_09_23_023405_create_petugas_wilayah_table', 1),
(4, '2026_09_24_041859_add_responden_fields_to_keluargas_table', 2),
(5, '2026_09_24_134104_add_wilayah_to_keluargas_table', 3),
(6, '2026_09_25_000001_fix_responden_schema', 4),
(7, '2026_09_25_070833_add_draft_fields_to_part1_keluarga_table', 5),
(8, '2026_09_28_024248_add_data_tambahan_to_part1_keluarga_table', 6),
(9, '2026_09_23_041853_create_password_reset_tokens_table', 7),
(10, '2026_10_05_080931_add_rt_rw_to_keluargas_table', 8),
(11, '2026_10_14_034746_add_income_source_fields_to_part3_keuangan_keluargas', 9),
(12, '2026_10_14_035410_add_other_education_and_job_fields_to_part5_anggota_keluargas', 10),
(13, '2026_10_16_012613_add_status_kepemilikan_lainnya_to_part2_kondisi_rumahs_table', 11),
(14, '2026_09_20_000000_create_users_table', 12),
(15, '2026_10_09_021412_create_part5_foto_rumahs_table', 12),
(16, '2026_10_09_021830_align_part5_foto_rumahs_collation', 12),
(17, '2026_10_13_013035_add_geotagging_to_keluargas_table', 12),
(18, '2026_10_13_063051_add_alasan_tidak_sesuai_to_part1_keluarga_table', 12),
(19, '2026_10_14_023022_add_rt_rw_geotagging_to_keluargas_table', 12),
(20, '2026_10_14_034030_add_alasan_penolakan_to_part1_keluarga_table', 12),
(21, '2026_10_14_042622_add_tanggal_lahir_to_part5_anggota_keluargas_table', 12),
(22, '2026_10_16_012459_add_pekerjaan_lainnya_to_part5_anggota_keluargas_table', 12),
(23, '2026_10_19_013709_create_sessions_table', 12),
(24, '2026_10_22_065540_add_tanggal_entri_to_keluargas_table', 13),
(25, '2026_10_28_013358_widen_sensitive_identifier_columns', 14),
(26, '2026_10_28_041500_encrypt_part1_family_identifiers', 14),
(28, '2026_11_03_022149_add_uuid_to_part1_keluarga_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `part1_keluarga`
--

CREATE TABLE `part1_keluarga` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `nik` text DEFAULT NULL,
  `nama_kepala_keluarga` varchar(255) DEFAULT NULL,
  `no_kk` text DEFAULT NULL,
  `jml_keluarga` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `daerah` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(16) DEFAULT NULL,
  `alamat_lengkap` varchar(255) DEFAULT NULL,
  `jalan_rumah` varchar(255) DEFAULT NULL,
  `nomor_rumah` varchar(255) DEFAULT NULL,
  `is_alamat_sesuai` tinyint(4) DEFAULT NULL,
  `alasan_tidak_sesuai` text DEFAULT NULL,
  `geotangging` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `current_part` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `uuid` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part1_keluarga`
--

INSERT INTO `part1_keluarga` (`id`, `keluarga_periode_kode`, `nik`, `nama_kepala_keluarga`, `no_kk`, `jml_keluarga`, `provinsi`, `daerah`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat_lengkap`, `jalan_rumah`, `nomor_rumah`, `is_alamat_sesuai`, `alasan_tidak_sesuai`, `geotangging`, `status`, `current_part`, `created_at`, `created_by`, `updated_at`, `updated_by`, `alasan_penolakan`, `uuid`) VALUES
(86, 'KLP-HVUGVZB2QV', 'enc:v1:eyJpdiI6IlNMVmZDb3NnQUQ5REk3akxMSGJra0E9PSIsInZhbHVlIjoieThTWE02UWs2RjRCSkVaaXVHWGp1SVFwUGQvT0QwVC9reUI4Smx4Y1dwZz0iLCJtYWMiOiJiNjMwNWM1ZDg3MjNjZGYwMzFmYTM5YTNlNzFlNTc3ZTg3OTcxMmE4M2U3ODQyN2RiYWYyMjBkNTdjYjFlNDU5IiwidGFnIjoiIn0=', 'muhamad firmansyah', 'enc:v1:eyJpdiI6InFERDhWeVk0cXJ0TEVDTDlZOUlzVVE9PSIsInZhbHVlIjoiMm91UzIvZ2k3UWdWclVVZm9vS3phU3dNTVBwVjEyRHN6aHhOQ2dhenJnZz0iLCJtYWMiOiI2ZTgyNzY1N2MyYWM1YmQ0N2U2NDRjZDJlNjAwODg2YjRmM2U5NjliNDc4ZGExZTNkMDE2MzVhMGUyZDNmNGY2IiwidGFnIjoiIn0=', '0', 'Jawa Timur', NULL, 'Panggungrejo', 'Bugul Lor', '675543', 'jl mancilan', 'jl mancilan', '89', 1, NULL, NULL, 'approved', 5, '2026-11-04 01:45:28', 'Adinda Zulfa', '2026-11-03 18:45:28', 'admin', NULL, 'e6c97622-35f5-4b46-bf7e-f4a4b5a6542a'),
(87, 'KLP-MQFCRGNB01', 'enc:v1:eyJpdiI6IlNCQ0ZWNGg3ckdUblUwMUtSeEhOU1E9PSIsInZhbHVlIjoieGRMcHZxc1NjRWZNN3lMVjN4dFFmUitvTGNna2lpSENKdFlLczFUL2lMYz0iLCJtYWMiOiJmM2Y4ZTVkNDQ2MDRjOTllZjQzZDVhNDYzNDRmOGQxZGY5OTBlYzEwYjJhOTNkYTMzMWI3NGM1ZDkwZjI3YWVkIiwidGFnIjoiIn0=', 'abdul aziz', 'enc:v1:eyJpdiI6InVET000VVhZNkRSWUNiUzVtQy8rQ0E9PSIsInZhbHVlIjoiaHY4VXQvbUdjemNGQmd6MHdaM3BITExDNHoyRXNBbDNtalVVU2lsUzBoND0iLCJtYWMiOiI3MTg1ODEzYTQyMTVmYzA0MmFmMzgzNjhmNDhhMDgzYWNlNjJhNWUzOTBlYWUzNTg3NDU4Mzc2Yjk5ZGIwYTkyIiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Bugul Kidul', 'Bugul Kidul', '67112', 'jl mancilan raya', 'Jl. Mancilan raya', '67', 1, NULL, NULL, 'approved', 5, '2026-11-04 01:45:19', 'Adinda Zulfa', '2026-11-03 18:45:19', 'admin', NULL, '77b8b3a1-0c7e-4c39-beda-2d0a5372b755'),
(88, 'KLP-VAGRA1YAKM', 'enc:v1:eyJpdiI6ImsvNHhPQ096RjZMRFlDMnlNeksycFE9PSIsInZhbHVlIjoiYkpza1RmUUdmZnVUaEN1Qk4wSko5VTJ4VXBGUFVnbEIySi9zMHkwbHFhTT0iLCJtYWMiOiI4OGYwMTZlYzE1Y2I2ZjU3ZjlkNzE2ZTE3YmNhZmFhMDkzNzJiMjJmZmI2NWE3MDBjMDcwZGQ1ZTMyNDk3YzI0IiwidGFnIjoiIn0=', 'fatkhul bari', 'enc:v1:eyJpdiI6IlR4eml0L3JBZTJMSXpJZjdsR2IyVlE9PSIsInZhbHVlIjoiNmdmVzY5MnQ5c0lqTEhpYzM1dVVBcWlGR3M5eGx0bWpFMDZJMWpEQWV2dz0iLCJtYWMiOiI1NmJiMmVlYmU5YjlkZWUyNjM0MmJkN2Y2ZjMzM2JhOWI1MWRhNzIzN2I0ODYxYTVkN2NjMTVlYzVkMDBhOTcyIiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Purworejo', 'Purutrejo', '67115', 'jl temenggungan', 'Jl. Temenggungan', '34', 1, NULL, NULL, 'approved', 5, '2026-11-04 01:45:06', 'Adinda Zulfa', '2026-11-03 18:45:06', 'admin', NULL, '82c4a348-83cf-43f9-82f9-40540add9288'),
(89, 'KLP-Y3GSDAEZV0', 'enc:v1:eyJpdiI6IkxIZ2taRU5aZVd5TjE2QStsQ2tlVFE9PSIsInZhbHVlIjoiMVhNVnNKRVdYVTZGSDVkQkIxTlpxZngrTFlQc0ptNnZBaGcrV2xURE8wUT0iLCJtYWMiOiIxZTYzM2E3ZDYzYTM2YmNlNDUyMjkyOTI2ZTcxZmZlNTJlNTYzNmMzNzhlMzk0OWE1YmZkZTkyMTI5Zjg4YjZhIiwidGFnIjoiIn0=', 'abdul aziz', 'enc:v1:eyJpdiI6IkRFcTBMSkNNVHpFUUlsVmp4R3kvZVE9PSIsInZhbHVlIjoiRWFocUZpc1lNK3F2T0oxUVRCdW1RbTVNQzFYVld4S2tEZnQvcWRDSWdQcz0iLCJtYWMiOiJlN2MwMmJmZTM2MmJhN2Y4NTY0YTBkYWZlYzdjYTNlMjljMWI1MGJmZjk1YjY5NWM1NGRhMzA4ZjIzYmIxNTc0IiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Bugul Kidul', 'Bugul Kidul', '67112', 'jl mancilan raya', 'jl mancilan', '12', 1, NULL, NULL, 'selesai', 5, '2026-11-04 01:48:19', 'Faiz Dwi Lestari', '2026-11-03 18:48:19', 'Faiz Dwi Lestari', NULL, '410f3377-db23-4ad4-86f4-d520d2bcde54'),
(90, 'KLP-GQWQWKMOID', 'enc:v1:eyJpdiI6IkIrY0RiSWNkb1BJR1o1aWJpSUxlaGc9PSIsInZhbHVlIjoiNHg3WVg3UFhpbzQ3VjdiNGFCTzFUYzE3SCtOZ01xSFdmeFR5Mzl6dmFCST0iLCJtYWMiOiJmMzBjMmUyMjVhZWI1ZDA1YWIyN2RhYzMyMzE0YjFmYjdkMDFmMGJlNzUxZDI4MzM4OGI0YWVjYmJmNmUwMzBiIiwidGFnIjoiIn0=', 'fatkhul bari', 'enc:v1:eyJpdiI6InVOYXkyR0lwaVdlTWROK21jcEFmWXc9PSIsInZhbHVlIjoiZ0FXMlBhd2pGb2ljRG50UUF6L0JpbW05NGpLRjVaZ1lDZmZCZndKczNjND0iLCJtYWMiOiIyNjI5ZDBhNGEwNWY3YmI3MTcyMDgzMzRjZjg4YTA0MjJiNTNjYjIwMjRiYWE2Y2ZjNThkNzI4NTkzYjA1NTcyIiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Purworejo', 'Purutrejo', '67115', 'jl temenggungan', 'kebonagung emas', '1', 1, NULL, NULL, 'selesai', 5, '2026-11-04 01:52:50', 'Faiz Dwi Lestari', '2026-11-03 18:52:50', 'Faiz Dwi Lestari', NULL, '2ee73689-c3c5-4a9a-ac0a-3fe3c9f20d5a'),
(91, 'KLP-UJVZFD3JIP', 'enc:v1:eyJpdiI6IjAzRmhZSjNsZ2c4VDBqWFI3Y1dCQWc9PSIsInZhbHVlIjoiOFBWVDJHOFRZUlVYZnB1bGpIWC9qVkJMT0tscDdlWi9MdkpxYUlLQTNWdz0iLCJtYWMiOiIwNzdmZTVlODU2OTY3ZTNmNzg4ZmM1NDAyNThhYTA2Y2Q2OGY4MTJlMTNjZDI0ZTFhZWE3MjFjMzMwMjY3YzdlIiwidGFnIjoiIn0=', 'muhamad firmansyah', 'enc:v1:eyJpdiI6IkYwY3BMVU1mUDdGd0hLanJFbkVkUVE9PSIsInZhbHVlIjoidGc0d1FRWldET1ZKNm1ZWkRtWjZGWVdZcnphSlNHdFZkMnBIdnVlSElwcz0iLCJtYWMiOiI2ZjJhNzMzMTNiNjhmZTY3MjcyZWRiMGRlOGNkMTUzNWUxNGE5ZTZjODkxOGIzOTRmZTdiMmYwYTA0MWJmYzQ4IiwidGFnIjoiIn0=', '0', 'Jawa Timur', NULL, 'Panggungrejo', 'Bugul Lor', '675543', 'jl mancilan', 'jl mangga', '23', 1, NULL, NULL, 'selesai', 5, '2026-11-04 01:54:25', 'Adinda Zulfa', '2026-11-03 18:54:25', 'Faiz Dwi Lestari', NULL, '622554fa-9521-4b25-adf4-c5a5a094cadc'),
(92, 'KLP-IMIAH0KMIC', 'enc:v1:eyJpdiI6ImgvdnBzclptNUM1ME1QVUxwMjIrRHc9PSIsInZhbHVlIjoiOVh5YkhTUzZMR2VTMlFZZFgweHZyU0Mwa2ZGT0RHK3hFSmw2anNwZktSaz0iLCJtYWMiOiJhMTk4N2QyNDAzMmQ0NWFjNjIzOTFiZDBjZGNlYmY3ZmIzNDhhM2Y5ZWQyY2I5ZjJiYTYyMTg0NjQ5YmVjMGJkIiwidGFnIjoiIn0=', 'M. Tawfikur Rohman', 'enc:v1:eyJpdiI6IjU0Z2paT2czRFh2WkVkVVFUUkJkQXc9PSIsInZhbHVlIjoiNEhwUmRuTzdiVGV6enIybzNPM3VjTkpnTlZzd01oMnozeTZVcnVMb0xEST0iLCJtYWMiOiJiNmJmZjAyM2Y4ODJhNWNjY2RlM2E1MTUwNDEyYjU1MzNkMjIzNThiYTc2OTM0MmIxMTk3NjA4ODM1ZTAzYzkxIiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Bugul Kidul', 'Bakalan', '87768', 'Jl. Mawar', 'Jl.Mawar Manggis', '09', 0, 'Pindah domisili', NULL, 'rejected', 5, '2026-11-05 01:32:12', 'Adinda Zulfa', '2026-11-04 18:32:12', 'admin', 'kurang', '02e5e0f3-2f66-4a6e-b5bb-15a1208e7517'),
(93, 'KLP-RGQ79C6DXP', 'enc:v1:eyJpdiI6IkpGUHN6dnNUZFpZazV5M3lxeWdSeFE9PSIsInZhbHVlIjoiSUlqNERDcEVaaFNITmFZQUF3TDk3VTJLdkoxOXNFZHV4Smt5RzJPenZ2Yz0iLCJtYWMiOiIyOGZkNTg4NTdkMDRjNTY3YzgxZGQ0NWI4N2MxMDVmOGUyZGEwYWQzMGVkYjczOTZiNmJmZGRhMWM0NTc1ZDRhIiwidGFnIjoiIn0=', 'abdul aziz', 'enc:v1:eyJpdiI6ImNEeXRBZXhjTU5LNWp5ZmJ0NjluNWc9PSIsInZhbHVlIjoiVTFaNEk2cnJRSkVTM0hBTWJhSndpTTVtd3psWXNIcnU3eC9yZE5LcGVuMD0iLCJtYWMiOiI1NzU3ZGNhNjUzNWJkZjNiMTRlYzA5N2NkNmMxM2VlNzExNTEyMGZiZWE2ZTVhZTFjNzQyNWFkYzYwM2RkMjk1IiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Bugul Kidul', 'Bugul Kidul', '67112', 'jl mancilan raya', 'jl mancilan raya', '34', 1, NULL, NULL, 'selesai', 5, '2026-11-05 03:23:41', 'Adinda Zulfa', '2026-11-04 20:23:41', 'Adinda Zulfa', NULL, '95d3accd-2691-4e44-80aa-2a5f2cf2287b'),
(94, 'KLP-VJXEQ02JWX', 'enc:v1:eyJpdiI6IkJ0cW5IeDJyb2NmQlU4eGxSZ2lnV0E9PSIsInZhbHVlIjoidlUreHBUdFBhKyt4cHVpa3cvaWpXUjI5YjIrdTlwQlNMSHpIWnpoZEJtcz0iLCJtYWMiOiI1ZjNkZGFiYzFhMDJiODkxZmZjM2FkNDViM2NkNGE4M2IyNTdjZWFmNjg4ZWJkNTcwZDJkOGYzYTU3N2M4OGE1IiwidGFnIjoiIn0=', 'fatkhul bari', 'enc:v1:eyJpdiI6Im92bzZpVUM0ZnZoMEVXZkUrcC9PN3c9PSIsInZhbHVlIjoiNHdPMlZOdWk0TitQQTZCMlFCZEZ1VjJWaExQY1NWS3cvM0sxN3RUWkQ3RT0iLCJtYWMiOiIyYzYzZWUxMDE3MGJlNDFmMzc1ZTFiOTIyNDgwNTAzY2Q1MWIwZjdlMzE2MTNkZTNmYTFjN2Q3M2IxYTc3NmY3IiwidGFnIjoiIn0=', '1', 'Jawa Timur', NULL, 'Purworejo', 'Purutrejo', '67115', 'jl temenggungan', 'jl temenggungan', '23', 1, NULL, NULL, 'draft', 5, '2026-11-05 01:48:45', 'Adinda Zulfa', '2026-11-04 18:48:45', 'Adinda Zulfa', NULL, 'c1494919-a3e4-489e-998b-837ce9ecb43b');

-- --------------------------------------------------------

--
-- Table structure for table `part2_kondisi_rumahs`
--

CREATE TABLE `part2_kondisi_rumahs` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `jenis_bangungan` varchar(255) DEFAULT NULL,
  `is_keluarga_lain` tinyint(4) DEFAULT NULL,
  `jml_keluarga_lain` varchar(16) DEFAULT NULL,
  `total_penghuni` varchar(16) DEFAULT NULL,
  `kepemilikan_bangunan` varchar(255) DEFAULT NULL,
  `bukti_kepemilikan` varchar(255) DEFAULT NULL,
  `harga_sewa_kontrak` decimal(10,0) DEFAULT NULL,
  `luas_lantai` decimal(10,0) DEFAULT NULL,
  `jenis_lantai` varchar(255) DEFAULT NULL,
  `kondisi_lantai` varchar(255) DEFAULT NULL,
  `jenis_dinding` varchar(255) DEFAULT NULL,
  `kondisi_dinding` varchar(255) DEFAULT NULL,
  `jenis_atap` varchar(255) DEFAULT NULL,
  `kondisi_atap` tinyint(4) DEFAULT NULL,
  `fasilitas_bab` varchar(255) DEFAULT NULL,
  `jenis_kloset` varchar(255) DEFAULT NULL,
  `sumber_minum` varchar(255) DEFAULT NULL,
  `sumber_penerangan` varchar(255) DEFAULT NULL,
  `daya_listrik` varchar(255) DEFAULT NULL,
  `idpel_pln` varchar(255) DEFAULT NULL,
  `jml_meteran` decimal(10,0) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_kepemilikan_lainnya` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part2_kondisi_rumahs`
--

INSERT INTO `part2_kondisi_rumahs` (`id`, `keluarga_periode_kode`, `jenis_bangungan`, `is_keluarga_lain`, `jml_keluarga_lain`, `total_penghuni`, `kepemilikan_bangunan`, `bukti_kepemilikan`, `harga_sewa_kontrak`, `luas_lantai`, `jenis_lantai`, `kondisi_lantai`, `jenis_dinding`, `kondisi_dinding`, `jenis_atap`, `kondisi_atap`, `fasilitas_bab`, `jenis_kloset`, `sumber_minum`, `sumber_penerangan`, `daya_listrik`, `idpel_pln`, `jml_meteran`, `created_by`, `created_at`, `updated_by`, `updated_at`, `status_kepemilikan_lainnya`) VALUES
(73, 'KLP-HVUGVZB2QV', 'Rumah Tunggal', 0, NULL, '1', 'Milik Sendiri', 'SHM', NULL, 65, 'Keramik', 'Baik', 'Tembok', 'Baik', 'Genteng', 1, 'Sendiri', 'Plengsengan dengan tutup', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '450 watt', '8273635362827', 1, 'Adinda Zulfa', '2026-11-04 01:34:19', 'Adinda Zulfa', '2026-11-03 18:34:19', NULL),
(74, 'KLP-MQFCRGNB01', 'Rumah Susun', 0, NULL, '2', 'Kontrak/Sewa', NULL, 200000, 63, 'Keramik', 'Baik', 'Tembok', 'Rusak Ringan', 'Genteng', 1, 'Sendiri', 'Leher angsa', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '450 watt', '626353&', 1, 'Adinda Zulfa', '2026-11-03 18:36:36', 'Adinda Zulfa', '2026-11-03 18:36:36', NULL),
(75, 'KLP-VAGRA1YAKM', 'Rumah Tunggal', 0, NULL, '2', 'Milik Sendiri', 'SHM', NULL, 80, 'Keramik', 'Baik', 'Tembok', 'Baik', 'Genteng', 1, 'Sendiri', 'Plengsengan dengan tutup', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '450 watt', '8273635362827', 1, 'Adinda Zulfa', '2026-11-03 18:38:34', 'Adinda Zulfa', '2026-11-03 18:38:34', NULL),
(76, 'KLP-Y3GSDAEZV0', 'Apartemen', 0, NULL, '2', 'Bebas Sewa', NULL, 1000000, 78, 'Marmer/granit', 'Baik', 'Tembok', 'Baik', 'Beton', 1, 'Sendiri', 'Leher angsa', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '900 watt', '5135009879', 1, 'Faiz Dwi Lestari', '2026-11-03 18:45:58', 'Faiz Dwi Lestari', '2026-11-03 18:45:58', NULL),
(77, 'KLP-GQWQWKMOID', 'Apartemen', 0, NULL, '2', 'Milik Sendiri', 'SHM', NULL, 90, 'Marmer/granit', 'Baik', 'Tembok', 'Baik', 'Beton', 1, 'Sendiri', 'Leher angsa', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '900 watt', '5135009876', 1, 'Faiz Dwi Lestari', '2026-11-03 18:51:04', 'Faiz Dwi Lestari', '2026-11-03 18:51:04', NULL),
(78, 'KLP-UJVZFD3JIP', 'Rumah Tunggal', 0, NULL, '1', 'Milik Sendiri', 'SHM', NULL, 123, 'Keramik', 'Baik', 'Tembok', 'Baik', 'Genteng', 1, 'Sendiri', 'Cemplung/cebluk', 'Air isi ulang', 'Listrik PLN dengan meteran', '1300 watt', '1234567899', 1, 'Adinda Zulfa', '2026-11-04 01:53:19', 'Adinda Zulfa', '2026-11-03 18:53:19', NULL),
(79, 'KLP-IMIAH0KMIC', 'Rumah Tunggal', 1, '1', '4', 'Milik Sendiri', 'SHM', NULL, 10, 'Marmer/granit', 'Baik', 'Tembok', 'Baik', 'Beton', 1, 'Sendiri', 'Leher angsa', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '450 watt', '87198', 1, 'Adinda Zulfa', '2026-11-04 02:21:42', 'Adinda Zulfa', '2026-11-03 19:21:42', NULL),
(80, 'KLP-RGQ79C6DXP', 'Rumah Tunggal', 0, NULL, '2', 'Milik Sendiri', 'SHM', NULL, 67, 'Keramik', 'Baik', 'Tembok', 'Baik', 'Genteng', 1, 'Sendiri', 'Plengsengan dengan tutup', 'Air kemasan bermerk', 'Listrik PLN dengan meteran', '900 watt', '74353645', 1, 'Adinda Zulfa', '2026-11-04 18:42:56', 'Adinda Zulfa', '2026-11-04 18:42:56', NULL),
(81, 'KLP-VJXEQ02JWX', 'Rumah Tunggal', 0, NULL, '2', 'Milik Sendiri', 'SHM', NULL, 76, 'Keramik', 'Baik', 'Tembok', 'Baik', 'Genteng', 1, 'Sendiri', 'Leher angsa', 'Air isi ulang', 'Listrik PLN dengan meteran', '450 watt', '1872356', 1, 'Adinda Zulfa', '2026-11-04 18:47:07', 'Adinda Zulfa', '2026-11-04 18:47:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `part3_keuangan_keluargas`
--

CREATE TABLE `part3_keuangan_keluargas` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `pengeluaran_listrik_bulanan` decimal(10,0) DEFAULT NULL,
  `pengeluaran_pulsa_bulanan` decimal(10,0) DEFAULT NULL,
  `pengeluaran_internet_bulanan` decimal(10,0) DEFAULT NULL,
  `pengeluaran_makan_mingguan` decimal(10,0) DEFAULT NULL,
  `pengeluaran_nonmakan_bulanan` decimal(10,0) DEFAULT NULL,
  `pengeluaran_nonmakan_tahunan` decimal(10,0) DEFAULT NULL,
  `pendapatan_pekerjaan` varchar(10) DEFAULT NULL,
  `total_pendapatan_kerja` decimal(10,0) DEFAULT NULL,
  `total_pendapatan_usaha` decimal(10,0) DEFAULT NULL,
  `pendapatan_lainnya` varchar(10) DEFAULT NULL,
  `total_pendapatan_lainnya` decimal(10,0) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part3_keuangan_keluargas`
--

INSERT INTO `part3_keuangan_keluargas` (`id`, `keluarga_periode_kode`, `pengeluaran_listrik_bulanan`, `pengeluaran_pulsa_bulanan`, `pengeluaran_internet_bulanan`, `pengeluaran_makan_mingguan`, `pengeluaran_nonmakan_bulanan`, `pengeluaran_nonmakan_tahunan`, `pendapatan_pekerjaan`, `total_pendapatan_kerja`, `total_pendapatan_usaha`, `pendapatan_lainnya`, `total_pendapatan_lainnya`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(68, 'KLP-HVUGVZB2QV', 100000, 100000, 100000, 100000, 100000, 100000, 'Ada', 1000000, 200000, 'Ada', 300000, '2026-11-04 01:35:05', 'Adinda Zulfa', '2026-11-03 18:35:05', 'Adinda Zulfa'),
(69, 'KLP-MQFCRGNB01', 100000, 100000, 100000, 100000, 100000, 100000, 'Tidak', NULL, 100000, 'Tidak', NULL, '2026-11-03 18:36:56', 'Adinda Zulfa', '2026-11-03 18:36:56', 'Adinda Zulfa'),
(70, 'KLP-VAGRA1YAKM', 100000, 200000, 100000, 100000, 100000, 100000, 'Tidak', NULL, 2000000, 'Tidak', NULL, '2026-11-03 18:39:01', 'Adinda Zulfa', '2026-11-03 18:39:01', 'Adinda Zulfa'),
(71, 'KLP-Y3GSDAEZV0', 200000, 200000, 200000, 350000, 600000, 9000000, 'Ada', 12000000, 0, 'Tidak', NULL, '2026-11-03 18:46:38', 'Faiz Dwi Lestari', '2026-11-03 18:46:38', 'Faiz Dwi Lestari'),
(72, 'KLP-GQWQWKMOID', 200000, 200000, 150000, 350000, 600000, 9000000, 'Tidak', NULL, 0, 'Tidak', NULL, '2026-11-03 18:51:32', 'Faiz Dwi Lestari', '2026-11-03 18:51:32', 'Faiz Dwi Lestari'),
(73, 'KLP-UJVZFD3JIP', 200000, 300000, 400000, 500000, 500000, 4000000, 'Tidak', NULL, 0, 'Tidak', NULL, '2026-11-04 01:53:23', 'Adinda Zulfa', '2026-11-03 18:53:23', 'Adinda Zulfa'),
(74, 'KLP-IMIAH0KMIC', 150000, 175000, 200000, 500000, 100000, 10000000, 'Ada', 5000000, 5000000, 'Tidak', NULL, '2026-11-04 02:32:30', 'Adinda Zulfa', '2026-11-03 19:32:30', 'Adinda Zulfa'),
(75, 'KLP-RGQ79C6DXP', 100000, 100000, 100000, 100000, 100000, 100000, 'Tidak', NULL, 100000, 'Tidak', NULL, '2026-11-04 18:43:17', 'Adinda Zulfa', '2026-11-04 18:43:17', 'Adinda Zulfa'),
(76, 'KLP-VJXEQ02JWX', 100000, 100000, 100000, 100000, 100000, 100000, 'Tidak', NULL, 100000, 'Tidak', NULL, '2026-11-04 18:47:31', 'Adinda Zulfa', '2026-11-04 18:47:31', 'Adinda Zulfa');

-- --------------------------------------------------------

--
-- Table structure for table `part4_aset_keluargas`
--

CREATE TABLE `part4_aset_keluargas` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `aset_keluarga` varchar(255) DEFAULT NULL,
  `is_punya_aset` tinyint(4) DEFAULT NULL,
  `jml_aset` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part4_aset_keluargas`
--

INSERT INTO `part4_aset_keluargas` (`id`, `keluarga_periode_kode`, `aset_keluarga`, `is_punya_aset`, `jml_aset`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(651, 'KLP-HVUGVZB2QV', 'Tabung Gas 3 KG', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(652, 'KLP-HVUGVZB2QV', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(653, 'KLP-HVUGVZB2QV', 'Lemari Es/Kulkas', 1, 1, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(654, 'KLP-HVUGVZB2QV', 'AC (Air Conditioner)', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(655, 'KLP-HVUGVZB2QV', 'Emas/Perhiasan', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(656, 'KLP-HVUGVZB2QV', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(657, 'KLP-HVUGVZB2QV', 'Sepeda Motor', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(658, 'KLP-HVUGVZB2QV', 'Mobil', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(659, 'KLP-HVUGVZB2QV', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(660, 'KLP-HVUGVZB2QV', 'Lahan Lainnya', 0, 0, '2026-11-03 18:35:22', 'admin', '2026-11-03 18:35:22', 'admin'),
(661, 'KLP-MQFCRGNB01', 'Tabung Gas 3 KG', 1, 1, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(662, 'KLP-MQFCRGNB01', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(663, 'KLP-MQFCRGNB01', 'Lemari Es/Kulkas', 1, 1, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(664, 'KLP-MQFCRGNB01', 'AC (Air Conditioner)', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(665, 'KLP-MQFCRGNB01', 'Emas/Perhiasan', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(666, 'KLP-MQFCRGNB01', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(667, 'KLP-MQFCRGNB01', 'Sepeda Motor', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(668, 'KLP-MQFCRGNB01', 'Mobil', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(669, 'KLP-MQFCRGNB01', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(670, 'KLP-MQFCRGNB01', 'Lahan Lainnya', 0, 0, '2026-11-03 18:37:17', 'admin', '2026-11-03 18:37:17', 'admin'),
(671, 'KLP-VAGRA1YAKM', 'Tabung Gas 3 KG', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(672, 'KLP-VAGRA1YAKM', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(673, 'KLP-VAGRA1YAKM', 'Lemari Es/Kulkas', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(674, 'KLP-VAGRA1YAKM', 'AC (Air Conditioner)', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(675, 'KLP-VAGRA1YAKM', 'Emas/Perhiasan', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(676, 'KLP-VAGRA1YAKM', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(677, 'KLP-VAGRA1YAKM', 'Sepeda Motor', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(678, 'KLP-VAGRA1YAKM', 'Mobil', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(679, 'KLP-VAGRA1YAKM', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(680, 'KLP-VAGRA1YAKM', 'Lahan Lainnya', 0, 0, '2026-11-03 18:39:13', 'admin', '2026-11-03 18:39:13', 'admin'),
(681, 'KLP-Y3GSDAEZV0', 'Tabung Gas 3 KG', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(682, 'KLP-Y3GSDAEZV0', 'Tabung Gas 5,5 KG atau Lebih', 1, 1, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(683, 'KLP-Y3GSDAEZV0', 'Lemari Es/Kulkas', 1, 1, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(684, 'KLP-Y3GSDAEZV0', 'AC (Air Conditioner)', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(685, 'KLP-Y3GSDAEZV0', 'Emas/Perhiasan', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(686, 'KLP-Y3GSDAEZV0', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(687, 'KLP-Y3GSDAEZV0', 'Sepeda Motor', 1, 1, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(688, 'KLP-Y3GSDAEZV0', 'Mobil', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(689, 'KLP-Y3GSDAEZV0', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(690, 'KLP-Y3GSDAEZV0', 'Lahan Lainnya', 0, 0, '2026-11-03 18:47:06', 'admin', '2026-11-03 18:47:06', 'admin'),
(691, 'KLP-GQWQWKMOID', 'Tabung Gas 3 KG', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(692, 'KLP-GQWQWKMOID', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(693, 'KLP-GQWQWKMOID', 'Lemari Es/Kulkas', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(694, 'KLP-GQWQWKMOID', 'AC (Air Conditioner)', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(695, 'KLP-GQWQWKMOID', 'Emas/Perhiasan', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(696, 'KLP-GQWQWKMOID', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(697, 'KLP-GQWQWKMOID', 'Sepeda Motor', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(698, 'KLP-GQWQWKMOID', 'Mobil', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(699, 'KLP-GQWQWKMOID', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(700, 'KLP-GQWQWKMOID', 'Lahan Lainnya', 0, 0, '2026-11-03 18:52:08', 'admin', '2026-11-03 18:52:08', 'admin'),
(701, 'KLP-UJVZFD3JIP', 'Tabung Gas 3 KG', 1, 1, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(702, 'KLP-UJVZFD3JIP', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(703, 'KLP-UJVZFD3JIP', 'Lemari Es/Kulkas', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(704, 'KLP-UJVZFD3JIP', 'AC (Air Conditioner)', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(705, 'KLP-UJVZFD3JIP', 'Emas/Perhiasan', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(706, 'KLP-UJVZFD3JIP', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(707, 'KLP-UJVZFD3JIP', 'Sepeda Motor', 1, 1, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(708, 'KLP-UJVZFD3JIP', 'Mobil', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(709, 'KLP-UJVZFD3JIP', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(710, 'KLP-UJVZFD3JIP', 'Lahan Lainnya', 0, 0, '2026-11-04 01:53:27', 'admin', '2026-11-03 18:53:27', 'admin'),
(711, 'KLP-IMIAH0KMIC', 'Tabung Gas 3 KG', 1, 2, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(712, 'KLP-IMIAH0KMIC', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(713, 'KLP-IMIAH0KMIC', 'Lemari Es/Kulkas', 1, 1, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(714, 'KLP-IMIAH0KMIC', 'AC (Air Conditioner)', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(715, 'KLP-IMIAH0KMIC', 'Emas/Perhiasan', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(716, 'KLP-IMIAH0KMIC', 'Komputer/Laptop/Tablet', 1, 1, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(717, 'KLP-IMIAH0KMIC', 'Sepeda Motor', 1, 2, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(718, 'KLP-IMIAH0KMIC', 'Mobil', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(719, 'KLP-IMIAH0KMIC', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(720, 'KLP-IMIAH0KMIC', 'Lahan Lainnya', 0, 0, '2026-11-04 02:32:36', 'admin', '2026-11-03 19:32:36', 'admin'),
(721, 'KLP-RGQ79C6DXP', 'Tabung Gas 3 KG', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(722, 'KLP-RGQ79C6DXP', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(723, 'KLP-RGQ79C6DXP', 'Lemari Es/Kulkas', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(724, 'KLP-RGQ79C6DXP', 'AC (Air Conditioner)', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(725, 'KLP-RGQ79C6DXP', 'Emas/Perhiasan', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(726, 'KLP-RGQ79C6DXP', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(727, 'KLP-RGQ79C6DXP', 'Sepeda Motor', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(728, 'KLP-RGQ79C6DXP', 'Mobil', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(729, 'KLP-RGQ79C6DXP', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(730, 'KLP-RGQ79C6DXP', 'Lahan Lainnya', 0, 0, '2026-11-04 18:43:37', 'admin', '2026-11-04 18:43:37', 'admin'),
(731, 'KLP-VJXEQ02JWX', 'Tabung Gas 3 KG', 0, 0, '2026-11-04 18:47:49', 'admin', '2026-11-04 18:47:49', 'admin'),
(732, 'KLP-VJXEQ02JWX', 'Tabung Gas 5,5 KG atau Lebih', 0, 0, '2026-11-04 18:47:49', 'admin', '2026-11-04 18:47:49', 'admin'),
(733, 'KLP-VJXEQ02JWX', 'Lemari Es/Kulkas', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(734, 'KLP-VJXEQ02JWX', 'AC (Air Conditioner)', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(735, 'KLP-VJXEQ02JWX', 'Emas/Perhiasan', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(736, 'KLP-VJXEQ02JWX', 'Komputer/Laptop/Tablet', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(737, 'KLP-VJXEQ02JWX', 'Sepeda Motor', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(738, 'KLP-VJXEQ02JWX', 'Mobil', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(739, 'KLP-VJXEQ02JWX', 'Rumah/Bangunan (selain yang ditempati)', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin'),
(740, 'KLP-VJXEQ02JWX', 'Lahan Lainnya', 0, 0, '2026-11-04 18:47:50', 'admin', '2026-11-04 18:47:50', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `part5_anggota_keluargas`
--

CREATE TABLE `part5_anggota_keluargas` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `keluarga_anggota_kode` varchar(255) DEFAULT NULL,
  `keberadaan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(16) DEFAULT NULL,
  `jenis_kelamin` varchar(16) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `status_perkawinan` varchar(255) DEFAULT NULL,
  `status_sekolah` varchar(255) DEFAULT NULL,
  `ijazah_tertinggi` varchar(255) DEFAULT NULL,
  `ijazah_lainnya` varchar(255) DEFAULT NULL,
  `pekerjaan_utama` varchar(255) DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) DEFAULT NULL,
  `status_pekerjaan` varchar(255) DEFAULT NULL,
  `kepemilikan_rekening` varchar(255) DEFAULT NULL,
  `is_disabilitas_fisik` tinyint(4) DEFAULT NULL,
  `is_disabilitas_mental` tinyint(4) DEFAULT NULL,
  `is_disabilitas_intelektual` tinyint(4) DEFAULT NULL,
  `is_disabilitas_netra` tinyint(4) DEFAULT NULL,
  `is_disabilitas_rungu` tinyint(4) DEFAULT NULL,
  `is_disabilitas_wicara` tinyint(4) DEFAULT NULL,
  `keluhan_kesehatan` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part5_anggota_keluargas`
--

INSERT INTO `part5_anggota_keluargas` (`id`, `keluarga_periode_kode`, `keluarga_anggota_kode`, `keberadaan`, `no_hp`, `jenis_kelamin`, `tanggal_lahir`, `status_perkawinan`, `status_sekolah`, `ijazah_tertinggi`, `ijazah_lainnya`, `pekerjaan_utama`, `pekerjaan_lainnya`, `status_pekerjaan`, `kepemilikan_rekening`, `is_disabilitas_fisik`, `is_disabilitas_mental`, `is_disabilitas_intelektual`, `is_disabilitas_netra`, `is_disabilitas_rungu`, `is_disabilitas_wicara`, `keluhan_kesehatan`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(89, 'KLP-MQFCRGNB01', 'ANG-A6VYTHEJSJ', 'Sudah Pisah kartu keluarga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', '10', '2026-11-03 18:37:25', '10', '2026-11-03 18:37:25'),
(90, 'KLP-VAGRA1YAKM', 'ANG-ZKP30JMFXQ', 'Tinggal bersama keluarga', '9827263639373636', 'Perempuan', '2003-08-08', 'Kawin/Nikah', 'Tidak bersekolah lagi', 'SMP/Sederajat', NULL, 'Tidak Bekerja', NULL, NULL, 'Ya untuk pribadi', 0, 0, 0, 0, 0, 0, '[\"Rematik\",\"Asma\"]', '10', '2026-11-03 18:40:06', '10', '2026-11-03 18:40:06'),
(91, 'KLP-Y3GSDAEZV0', 'ANG-A6VYTHEJSJ', 'Tinggal bersama keluarga', NULL, 'Perempuan', '2003-05-26', 'Kawin/Nikah', 'Masih sekolah', 'SMA/Sederajat', NULL, 'Tenaga Pengajar', NULL, 'Buruh/karyawan/pegawai swasta', 'Ya untuk pribadi', 0, 0, 0, 0, 0, 0, '[\"Tidak ada\"]', '14', '2026-11-03 18:47:47', '14', '2026-11-03 18:47:47'),
(92, 'KLP-GQWQWKMOID', 'ANG-ZKP30JMFXQ', 'Sudah Pisah kartu keluarga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', '14', '2026-11-03 18:52:19', '14', '2026-11-03 18:52:19'),
(93, 'KLP-IMIAH0KMIC', 'ANG-2LGDFWBJXM', 'Tinggal bersama keluarga', '082229203462', 'Perempuan', '2002-09-10', 'Kawin/Nikah', 'Tidak bersekolah lagi', 'SMA/Sederajat', NULL, 'Akuntan', NULL, 'Buruh/karyawan/pegawai swasta', 'Ya untuk pribadi', 0, 0, 0, 0, 0, 0, '[\"Rematik\",\"Masalah jantung\",\"Stroke\"]', '10', '2026-11-04 02:26:36', '10', '2026-11-03 19:26:36'),
(94, 'KLP-RGQ79C6DXP', 'KLG-UA07RUYTCO', 'Tinggal bersama keluarga', '86274564', 'Laki-laki', '2001-01-01', 'Kawin/Nikah', 'Tidak bersekolah lagi', 'SMP/Sederajat', NULL, 'Lainnya', 'kuli bangunan', 'Berusaha dibantu buruh', 'Ya untuk pribadi', 0, 0, 0, 0, 0, 0, '[\"Tidak ada\"]', '10', '2026-11-04 18:52:47', '10', '2026-11-04 18:52:47'),
(95, 'KLP-RGQ79C6DXP', 'ANG-A6VYTHEJSJ', 'Sudah Pisah kartu keluarga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', '10', '2026-11-04 18:52:57', '10', '2026-11-04 18:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `part5_foto_rumahs`
--

CREATE TABLE `part5_foto_rumahs` (
  `id` int(11) NOT NULL,
  `keluarga_periode_kode` varchar(255) DEFAULT NULL,
  `jenis_foto` varchar(50) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `path_file` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part5_foto_rumahs`
--

INSERT INTO `part5_foto_rumahs` (`id`, `keluarga_periode_kode`, `jenis_foto`, `nama_file`, `path_file`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(196, 'KLP-VAGRA1YAKM', 'tampak_depan', '1793756532_tampak_depan_6aea8d743c7eb.jpg', 'kuisioner/rumah/1793756532_tampak_depan_6aea8d743c7eb.jpg', '14', '2026-11-03 18:42:12', '14', '2026-11-03 18:42:12'),
(197, 'KLP-VAGRA1YAKM', 'ruang_tamu', '1793756541_ruang_tamu_6aea8d7d2ec92.jpg', 'kuisioner/rumah/1793756541_ruang_tamu_6aea8d7d2ec92.jpg', '14', '2026-11-03 18:42:21', '14', '2026-11-03 18:42:21'),
(198, 'KLP-VAGRA1YAKM', 'kamar_mandi', '1793756547_kamar_mandi_6aea8d8327072.jpg', 'kuisioner/rumah/1793756547_kamar_mandi_6aea8d8327072.jpg', '14', '2026-11-03 18:42:27', '14', '2026-11-03 18:42:27'),
(199, 'KLP-MQFCRGNB01', 'tampak_depan', '1793756566_tampak_depan_6aea8d966c9e7.jpg', 'kuisioner/rumah/1793756566_tampak_depan_6aea8d966c9e7.jpg', '14', '2026-11-03 18:42:46', '14', '2026-11-03 18:42:46'),
(200, 'KLP-MQFCRGNB01', 'ruang_tamu', '1793756574_ruang_tamu_6aea8d9e9b2b8.jpg', 'kuisioner/rumah/1793756574_ruang_tamu_6aea8d9e9b2b8.jpg', '14', '2026-11-03 18:42:54', '14', '2026-11-03 18:42:54'),
(201, 'KLP-MQFCRGNB01', 'kamar_mandi', '1793756582_kamar_mandi_6aea8da6a73b7.jpg', 'kuisioner/rumah/1793756582_kamar_mandi_6aea8da6a73b7.jpg', '14', '2026-11-03 18:43:02', '14', '2026-11-03 18:43:02'),
(202, 'KLP-HVUGVZB2QV', 'tampak_depan', '1793756605_tampak_depan_6aea8dbd195c6.jpg', 'kuisioner/rumah/1793756605_tampak_depan_6aea8dbd195c6.jpg', '14', '2026-11-03 18:43:25', '14', '2026-11-03 18:43:25'),
(203, 'KLP-HVUGVZB2QV', 'ruang_tamu', '1793756616_ruang_tamu_6aea8dc8bfe8d.jpg', 'kuisioner/rumah/1793756616_ruang_tamu_6aea8dc8bfe8d.jpg', '14', '2026-11-03 18:43:36', '14', '2026-11-03 18:43:36'),
(204, 'KLP-HVUGVZB2QV', 'kamar_mandi', '1793756625_kamar_mandi_6aea8dd123726.jpg', 'kuisioner/rumah/1793756625_kamar_mandi_6aea8dd123726.jpg', '14', '2026-11-03 18:43:45', '14', '2026-11-03 18:43:45'),
(205, 'KLP-Y3GSDAEZV0', 'tampak_depan', '1793756878_tampak_depan_6aea8eced64c1.jpg', 'kuisioner/rumah/1793756878_tampak_depan_6aea8eced64c1.jpg', '14', '2026-11-03 18:47:58', '14', '2026-11-03 18:47:58'),
(206, 'KLP-Y3GSDAEZV0', 'ruang_tamu', '1793756886_ruang_tamu_6aea8ed658031.jpg', 'kuisioner/rumah/1793756886_ruang_tamu_6aea8ed658031.jpg', '14', '2026-11-03 18:48:06', '14', '2026-11-03 18:48:06'),
(207, 'KLP-Y3GSDAEZV0', 'kamar_mandi', '1793756896_kamar_mandi_6aea8ee07a0ae.jpg', 'kuisioner/rumah/1793756896_kamar_mandi_6aea8ee07a0ae.jpg', '14', '2026-11-03 18:48:16', '14', '2026-11-03 18:48:16'),
(208, 'KLP-GQWQWKMOID', 'tampak_depan', '1793757153_tampak_depan_6aea8fe12ae55.jpg', 'kuisioner/rumah/1793757153_tampak_depan_6aea8fe12ae55.jpg', '14', '2026-11-03 18:52:33', '14', '2026-11-03 18:52:33'),
(209, 'KLP-GQWQWKMOID', 'ruang_tamu', '1793757161_ruang_tamu_6aea8fe914ab5.jpg', 'kuisioner/rumah/1793757161_ruang_tamu_6aea8fe914ab5.jpg', '14', '2026-11-03 18:52:41', '14', '2026-11-03 18:52:41'),
(210, 'KLP-GQWQWKMOID', 'kamar_mandi', '1793757169_kamar_mandi_6aea8ff1293c3.jpg', 'kuisioner/rumah/1793757169_kamar_mandi_6aea8ff1293c3.jpg', '14', '2026-11-03 18:52:49', '14', '2026-11-03 18:52:49'),
(211, 'KLP-UJVZFD3JIP', 'tampak_depan', '1793757241_tampak_depan_6aea90390b609.jpg', 'kuisioner/rumah/1793757241_tampak_depan_6aea90390b609.jpg', '14', '2026-11-03 18:54:01', '14', '2026-11-03 18:54:01'),
(212, 'KLP-UJVZFD3JIP', 'ruang_tamu', '1793757250_ruang_tamu_6aea9042d0b39.jpg', 'kuisioner/rumah/1793757250_ruang_tamu_6aea9042d0b39.jpg', '14', '2026-11-03 18:54:10', '14', '2026-11-03 18:54:10'),
(213, 'KLP-UJVZFD3JIP', 'kamar_mandi', '1793757263_kamar_mandi_6aea904f248ab.jpg', 'kuisioner/rumah/1793757263_kamar_mandi_6aea904f248ab.jpg', '14', '2026-11-03 18:54:23', '14', '2026-11-03 18:54:23'),
(214, 'KLP-IMIAH0KMIC', 'tampak_depan', '1793759229_tampak_depan_6aea97fdbac75.jpeg', 'kuisioner/rumah/1793759229_tampak_depan_6aea97fdbac75.jpeg', '10', '2026-11-03 19:27:09', '10', '2026-11-03 19:27:09'),
(215, 'KLP-IMIAH0KMIC', 'ruang_tamu', '1793759236_ruang_tamu_6aea9804b0e9e.jpeg', 'kuisioner/rumah/1793759236_ruang_tamu_6aea9804b0e9e.jpeg', '10', '2026-11-03 19:27:16', '10', '2026-11-03 19:27:16'),
(216, 'KLP-IMIAH0KMIC', 'kamar_mandi', '1793759248_kamar_mandi_6aea98105b8c1.jpeg', 'kuisioner/rumah/1793759248_kamar_mandi_6aea98105b8c1.jpeg', '10', '2026-11-03 19:27:28', '10', '2026-11-03 19:27:28'),
(217, 'KLP-RGQ79C6DXP', 'tampak_depan', '1793848996_tampak_depan_6aebf6a4afb19.jpg', 'kuisioner/rumah/1793848996_tampak_depan_6aebf6a4afb19.jpg', '10', '2026-11-04 20:23:17', '10', '2026-11-04 20:23:17'),
(218, 'KLP-RGQ79C6DXP', 'ruang_tamu', '1793849009_ruang_tamu_6aebf6b17dfcd.jpg', 'kuisioner/rumah/1793849009_ruang_tamu_6aebf6b17dfcd.jpg', '10', '2026-11-04 20:23:22', '10', '2026-11-04 20:23:29'),
(219, 'KLP-RGQ79C6DXP', 'kamar_mandi', '1793849018_kamar_mandi_6aebf6ba35fa6.jpg', 'kuisioner/rumah/1793849018_kamar_mandi_6aebf6ba35fa6.jpg', '10', '2026-11-04 20:23:38', '10', '2026-11-04 20:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periodes`
--

CREATE TABLE `periodes` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(18) NOT NULL,
  `tgl_awal` datetime NOT NULL,
  `tgl_akhir` datetime NOT NULL,
  `status_periode` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `periodes`
--

INSERT INTO `periodes` (`id`, `kode`, `nama`, `tgl_awal`, `tgl_akhir`, `status_periode`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(11, 'PER-001', 'Periode Oktober', '2026-10-01 00:00:00', '2026-10-30 00:00:00', 'Selesai', 'Husnatul Adibah', '2026-11-04 01:45:59', 'Husnatul Adibah', '2026-11-04 01:45:59'),
(12, 'PER-012', 'Periode November', '2026-11-01 00:00:00', '2026-11-30 00:00:00', 'Aktif', 'Husnatul Adibah', '2026-11-03 18:41:48', NULL, '2026-11-04 01:41:48'),
(13, 'PER-013', 'Periode Desember', '2026-12-01 00:00:00', '2026-12-31 00:00:00', 'Aktif', 'Husnatul Adibah', '2026-11-05 01:39:07', 'admin', '2026-11-05 01:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint(6) NOT NULL,
  `role` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'Operator'),
(3, 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Ax3QiLnQLc6x7ZUji4ZqvD6jniRLOlWZNM6vGef1', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJiTXE1RnM0dFJKTTZrOEVCVVBRRXhKUmZRNWZKOHgzSHVPSkJXazlSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9rdWlzaW9uZXJcL3BhcnQ1XC9mb3RvIiwicm91dGUiOiJrdWlzaW9uZXIucGFydDUuZm90byJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxMCwicGVyaW9kZV9pZCI6MTMsInBlcmlvZGVfa29kZSI6IlBFUi0wMTMiLCJwZXJpb2RlX25hbWEiOiJQZXJpb2RlIERlc2VtYmVyIiwiZHJhZnRfa2VsdWFyZ2FfaWQiOjkzLCJzZWxlY3RlZF9rZWx1YXJnYV9rb2RlIjoiS0xQLVJHUTc5QzZEWFAiLCJwYXJ0MV9zZWxlc2FpIjp0cnVlLCJwYXJ0Ml9zZWxlc2FpIjp0cnVlLCJwYXJ0M19zZWxlc2FpIjp0cnVlLCJwYXJ0NF9zZWxlc2FpIjp0cnVlLCJwYXJ0NV9zZWxlc2FpIjpmYWxzZX0=', 1793845637),
('Ha193nb9gluhl3Ta6i8mo8oHXEQddaGL4tFw00Sj', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaYzVCNEpHVlFWTkFBYjdMUDFIaWp1OHp1SkNBNXFKcm10cTJnUnBXIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3ZlcmlmaWthc2kiLCJyb3V0ZSI6InZlcmlmaWthc2kuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjksInBlcmlvZGVfaWQiOjEzLCJwZXJpb2RlX2tvZGUiOiJQRVItMDEzIiwicGVyaW9kZV9uYW1hIjoiUGVyaW9kZSBEZXNlbWJlciJ9', 1793849656),
('KABWYLOQkE3twgc3FFYCQ2H78xZd6ghWkGoe6obm', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJyZlJRaWszamFWdld2VzRMYjA3RFFLSEJoRjdwa1dxSnRIVnlGb3ZxIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3ZlcmlmaWthc2kiLCJyb3V0ZSI6InZlcmlmaWthc2kuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjksInBlcmlvZGVfaWQiOjEzLCJwZXJpb2RlX2tvZGUiOiJQRVItMDEzIiwicGVyaW9kZV9uYW1hIjoiUGVyaW9kZSBEZXNlbWJlciJ9', 1793932578),
('LBoy0lRvkiOq69G4iTOTNWAoBA7w8RhpivjFIA9f', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIybWVxZ29pdmE1amlLbkNLRllpbFpzNGxNbWhrY2YxYzJudW1YNzRxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9yZWthcGl0dWxhc2kiLCJyb3V0ZSI6InJla2FwaXR1bGFzaS5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo4LCJwZXJpb2RlX2lkIjoxMywicGVyaW9kZV9rb2RlIjoiUEVSLTAxMyIsInBlcmlvZGVfbmFtYSI6IlBlcmlvZGUgRGVzZW1iZXIiLCJkcmFmdF9rZWx1YXJnYV9pZCI6OTMsInNlbGVjdGVkX2tlbHVhcmdhX2tvZGUiOiJLTFAtUkdRNzlDNkRYUCIsInBhcnQxX3NlbGVzYWkiOnRydWUsInBhcnQyX3NlbGVzYWkiOnRydWUsInBhcnQzX3NlbGVzYWkiOnRydWUsInBhcnQ0X3NlbGVzYWkiOnRydWUsInBhcnQ1X3NlbGVzYWkiOmZhbHNlfQ==', 1793850606),
('OwVOBBQYddFOUWVPIBgDTjX0Sg2G4E3HjNtPM2Bc', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', 'eyJfdG9rZW4iOiJpQ0c0cEI4SE9ZVjUyck4zZkcwc3FoN1F4bDhaUWNqclRkd1N5OFh3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9rdWlzaW9uZXJcL3BhcnQ1XC9mb3RvIiwicm91dGUiOiJrdWlzaW9uZXIucGFydDUuZm90byJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxMCwicGVyaW9kZV9pZCI6MTMsInBlcmlvZGVfa29kZSI6IlBFUi0wMTMiLCJwZXJpb2RlX25hbWEiOiJQZXJpb2RlIERlc2VtYmVyIiwiZHJhZnRfa2VsdWFyZ2FfaWQiOjkzLCJzZWxlY3RlZF9rZWx1YXJnYV9rb2RlIjoiS0xQLVJHUTc5QzZEWFAiLCJwYXJ0MV9zZWxlc2FpIjp0cnVlLCJwYXJ0Ml9zZWxlc2FpIjp0cnVlLCJwYXJ0M19zZWxlc2FpIjp0cnVlLCJwYXJ0NF9zZWxlc2FpIjp0cnVlLCJwYXJ0NV9zZWxlc2FpIjpmYWxzZX0=', 1793846421),
('R9Rj9CObxnLdNnpOysiTcNIGgkWI7dFdVVg4PBPA', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLdk9YaGhFWGVkQXVTWTRKZ3VYZzN5aDJSSGNFU3c0Y0xXc050ZFpoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9rdWlzaW9uZXJcL3BhcnQ1XC9mb3RvIiwicm91dGUiOiJrdWlzaW9uZXIucGFydDUuZm90byJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxMCwicGVyaW9kZV9pZCI6MTMsInBlcmlvZGVfa29kZSI6IlBFUi0wMTMiLCJwZXJpb2RlX25hbWEiOiJQZXJpb2RlIERlc2VtYmVyIiwiZHJhZnRfa2VsdWFyZ2FfaWQiOjkzLCJzZWxlY3RlZF9rZWx1YXJnYV9rb2RlIjoiS0xQLVJHUTc5QzZEWFAiLCJwYXJ0MV9zZWxlc2FpIjp0cnVlLCJwYXJ0Ml9zZWxlc2FpIjp0cnVlLCJwYXJ0M19zZWxlc2FpIjp0cnVlLCJwYXJ0NF9zZWxlc2FpIjp0cnVlLCJwYXJ0NV9zZWxlc2FpIjpmYWxzZX0=', 1793843853);

-- --------------------------------------------------------

--
-- Table structure for table `status_keluargas`
--

CREATE TABLE `status_keluargas` (
  `id` smallint(6) NOT NULL,
  `status_keluarga` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_keluargas`
--

INSERT INTO `status_keluargas` (`id`, `status_keluarga`) VALUES
(1, 'AYAH'),
(2, 'IBU'),
(3, 'ANAK'),
(4, 'SUAMI'),
(5, 'ISTRI'),
(6, 'KEPALA KELUARGA'),
(7, 'ORANG TUA'),
(8, 'CUCU'),
(9, 'MERTUA'),
(10, 'FAMILI LAIN'),
(11, 'LAINNYA'),
(12, 'BELUM DITENTUKAN'),
(13, 'MENANTU'),
(14, 'PEMBANTU');

-- --------------------------------------------------------

--
-- Table structure for table `status_kuisioner`
--

CREATE TABLE `status_kuisioner` (
  `id` smallint(6) NOT NULL,
  `status_kuisioner` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_kuisioner`
--

INSERT INTO `status_kuisioner` (`id`, `status_kuisioner`) VALUES
(1, 'Disetujui'),
(2, 'Draft'),
(3, 'Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `status_periodes`
--

CREATE TABLE `status_periodes` (
  `id` smallint(6) NOT NULL,
  `status_periode` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_periodes`
--

INSERT INTO `status_periodes` (`id`, `status_periode`) VALUES
(1, 'Berlangsung'),
(2, 'Akan Datang'),
(3, 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `terakhir_login` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(165) NOT NULL DEFAULT 'user',
  `kelurahan` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nomor_identitas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `terakhir_login`, `email_verified_at`, `password`, `role`, `kelurahan`, `remember_token`, `created_at`, `updated_at`, `nomor_identitas`) VALUES
(1, 'Admin E-Government', 'admin@kominfo.go.id', NULL, NULL, '$2y$12$mjUAx5GLGzvWaqA.ChYHd.K7npUrDnUDk8diTyAM2G0JWsMH2m.ZK', 'Admin', NULL, NULL, '2026-09-08 03:11:16', '2026-09-22 10:12:35', NULL),
(8, 'Husnatul Adibah', 'adibah@gmail.com', NULL, NULL, '$2y$12$wU0M.H75cBLCujRgOmN4KeEJ4W32Pbx.ygHlBV7XEF0U0znaCDT.m', 'operator', NULL, NULL, '2026-09-29 01:50:56', '2026-11-04 02:09:45', 'OP-001'),
(9, 'eka', 'eka@gmail.com', NULL, NULL, '$2y$12$VwVXYYUIWKdBfDcJ.Af98eNsyU1d0Mc8UIxXrbEtbswGminnV5xLO', 'verifikator', NULL, NULL, '2026-09-29 01:52:10', '2026-09-29 01:52:10', 'VR-001'),
(10, 'Adinda Zulfa', 'dinda@gmail.com', NULL, NULL, '$2y$12$qpS.DzcrPeCRfrqHMb5W6etjOSnyUg81YoRremIQhAbegLJ.pvIUi', 'petugas', 'Gentong', NULL, '2026-09-29 02:56:29', '2026-11-04 00:49:38', 'PT-001'),
(12, 'M. Salman', 'salman@gmail.com', NULL, NULL, '$2y$12$3T/he2UWFS58zTg7JZ64AuDe30IGlIal5nA92dX7eFjBA1dxet7C6', 'operator', NULL, NULL, '2026-11-03 07:37:37', '2026-11-03 07:37:37', 'OP-002'),
(13, 'Rusdiyanto', 'rusdi@gmail.com', NULL, NULL, '$2y$12$IyO8OYD2A.2ZP5UyDpkoLeh7dnRNNELuMxSGrO01VemcxmG5HtNLq', 'verifikator', NULL, NULL, '2026-11-03 07:38:07', '2026-11-03 07:38:07', 'VR-002'),
(14, 'Faiz Dwi Lestari', 'faiz@gmail.com', NULL, NULL, '$2y$12$.TZ/2jvJ5/RJmnnIrMYWfuFT/6HqFD.jkAzceS56d7SjsG1JekQmm', 'petugas', 'Bangilan', NULL, '2026-11-03 07:39:00', '2026-11-03 07:39:00', 'PT-002'),
(15, 'Farosania Amanda', 'faro@gmail.com', NULL, NULL, '$2y$12$D8RJ24lMxAxYt13lkuloUODllWXkOpwJ6Py41BNQs2/h2IU00AS1i', 'petugas', 'Gentong', NULL, '2026-11-03 07:40:10', '2026-11-03 07:40:10', 'PT-003'),
(16, 'Zulfan Ahmad', 'zul@gmail.com', NULL, NULL, '$2y$12$cHl5ALN1Tb9Z9SPlklck0uwTB2xYHRlyCL1JB/TzmwVSJabCcQ3Fu', 'petugas', 'Karanganyar', NULL, '2026-11-03 07:41:18', '2026-11-04 00:49:57', 'PT-004');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset_keluargas`
--
ALTER TABLE `aset_keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `kecamatans`
--
ALTER TABLE `kecamatans`
  ADD PRIMARY KEY (`kecamatan_id`) USING BTREE;

--
-- Indexes for table `keluargas`
--
ALTER TABLE `keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keluarga_anggotas`
--
ALTER TABLE `keluarga_anggotas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keluarga_periodes`
--
ALTER TABLE `keluarga_periodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelurahans`
--
ALTER TABLE `kelurahans`
  ADD PRIMARY KEY (`kelurahan_id`) USING BTREE,
  ADD KEY `m_kelurahan_m_kecamatan_idx` (`kecamatan_id`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part1_keluarga`
--
ALTER TABLE `part1_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `part1_keluarga_uuid_unique` (`uuid`);

--
-- Indexes for table `part2_kondisi_rumahs`
--
ALTER TABLE `part2_kondisi_rumahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part3_keuangan_keluargas`
--
ALTER TABLE `part3_keuangan_keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part4_aset_keluargas`
--
ALTER TABLE `part4_aset_keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part5_anggota_keluargas`
--
ALTER TABLE `part5_anggota_keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part5_foto_rumahs`
--
ALTER TABLE `part5_foto_rumahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `periodes`
--
ALTER TABLE `periodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `status_keluargas`
--
ALTER TABLE `status_keluargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_kuisioner`
--
ALTER TABLE `status_kuisioner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_periodes`
--
ALTER TABLE `status_periodes`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `aset_keluargas`
--
ALTER TABLE `aset_keluargas`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keluargas`
--
ALTER TABLE `keluargas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `keluarga_anggotas`
--
ALTER TABLE `keluarga_anggotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `keluarga_periodes`
--
ALTER TABLE `keluarga_periodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `part1_keluarga`
--
ALTER TABLE `part1_keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `part2_kondisi_rumahs`
--
ALTER TABLE `part2_kondisi_rumahs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `part3_keuangan_keluargas`
--
ALTER TABLE `part3_keuangan_keluargas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `part4_aset_keluargas`
--
ALTER TABLE `part4_aset_keluargas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=741;

--
-- AUTO_INCREMENT for table `part5_anggota_keluargas`
--
ALTER TABLE `part5_anggota_keluargas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `part5_foto_rumahs`
--
ALTER TABLE `part5_foto_rumahs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `periodes`
--
ALTER TABLE `periodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status_keluargas`
--
ALTER TABLE `status_keluargas`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `status_kuisioner`
--
ALTER TABLE `status_kuisioner`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status_periodes`
--
ALTER TABLE `status_periodes`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelurahans`
--
ALTER TABLE `kelurahans`
  ADD CONSTRAINT `m_kelurahan_m_kecamatan` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`kecamatan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
