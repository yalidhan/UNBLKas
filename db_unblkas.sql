-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2023 at 04:45 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_unblkas`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `no`, `tipe`, `nama`, `created_at`, `updated_at`, `status`) VALUES
(1, '05.1', 'Beban', 'Pembelian ATK', '2023-10-10 18:23:11', '2023-10-05 18:23:11', 1),
(2, '01.1', 'Harta', 'Bangunan', '2023-10-11 06:56:05', '2023-10-13 19:07:25', 1),
(3, '01.2', 'Harta', 'Kendaraan', '2023-10-12 01:33:02', NULL, 1),
(4, '01.3', 'Harta', 'Peralatan', '2023-10-12 01:37:46', NULL, 1),
(5, '01.4', 'Harta', 'Alat Laboratorium', '2023-10-13 17:15:04', '2023-10-13 19:08:52', 1),
(6, '02.1', 'Utang', 'Pinjaman BRI', '2023-10-13 17:16:05', '2023-10-13 17:16:05', 1),
(7, '02.2', 'Utang', 'Pinjaman BPD Syariah', '2023-10-13 17:16:18', '2023-10-13 17:16:18', 1),
(8, '03.1', 'Modal', 'Saldo Awal', '2023-10-13 17:16:29', '2023-10-13 17:16:29', 1),
(9, '03.2', 'Modal', 'Asset', '2023-10-13 17:16:38', '2023-10-13 17:16:38', 1),
(10, '04.1', 'Pendapatan', 'SPP Mahasiswa', '2023-10-13 17:16:51', '2023-10-13 17:16:51', 1),
(11, '04.2', 'Pendapatan', 'UKT Mahasiswa', '2023-10-13 17:17:01', '2023-10-13 17:17:01', 1),
(12, '05.2', 'Beban', 'Listrik', '2023-10-15 16:39:06', '2023-10-15 16:39:06', 1),
(13, '05.3', 'Beban', 'Indihome Telkom', '2023-10-15 16:52:37', '2023-10-15 16:52:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

CREATE TABLE `departements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departements`
--

INSERT INTO `departements` (`id`, `nama`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Yayasan Borneo Lestari', 1, '2023-10-05 18:23:11', '2023-10-22 22:23:49'),
(2, 'Fakultas Farmasi', 1, '2023-10-06 16:14:24', '2023-10-08 19:43:54'),
(3, 'Fakultas Sosial dan Humaniora', 1, '2023-10-08 19:44:09', '2023-10-08 19:44:09'),
(4, 'Fakultas Ilmu Kesehatan dan Sainteknologi', 1, '2023-10-08 22:39:53', '2023-10-08 22:39:53'),
(5, 'SMK Farmasi', 0, '2023-10-13 16:07:49', '2023-10-13 16:07:55'),
(6, 'Rektorat', 1, '2023-10-15 16:57:24', '2023-10-15 16:57:24');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2023_09_28_135634_rename_password_reset_table', 2),
(5, '2023_09_28_152006_add_remember_token_to_users_table', 3),
(6, '2023_10_04_061416_add_status_to_users', 4),
(7, '2023_10_05_010804_create_departements_table', 5),
(8, '2023_10_09_010211_add_foreign_key_to_users', 6),
(9, '2023_10_09_014511_add_foreign_key_to_users', 7),
(10, '2023_10_09_020005_add_foreign_key_to_users', 8),
(11, '2023_10_10_061112_create_accounts_table', 9),
(12, '2023_10_13_025115_add_status_to_accounts_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('dede99212@gmail.com', '$2y$10$n9zvJZEqcMJBgW5BPR3LmOkQCOplxgygSiW8yEUcFBpuEDQuKQwNi', '2023-09-28 06:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `departement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `departement_id`, `jabatan`, `created_at`, `updated_at`, `remember_token`, `status`) VALUES
(1, 'admin', 'dedek.southborneo@gmail.com', '$2y$10$imIIHwpD4aa9q9EEzHQD/eET7wb3hLfNsM.yAxYpUE4Amp08VWKXC', 1, 'Super Admin', '2023-09-18 16:00:00', '2023-09-28 07:22:19', 'umm2dwBFHO5IniKdxkSRo3mgLOSMODGlf274Tn1TCOpIGOFnAFkvMgght18s', 1),
(2, 'User1', 'dede99212@gmail.com', '$2y$10$JuoyAI30ZQaMRN8LW3ziYOzGBm7tYs7TS3c.d0Vf0N6xv.XGno7SW', 2, 'Admin Fakultas', '2023-09-19 06:30:19', '2023-10-04 16:46:25', NULL, 1),
(3, 'User23', 'user23@gmail.com', '$2y$10$BsbGxQexUPPHusU9oCBOb.ffmZ7AvHDJAzfp30bdpYUkjFdjhBb02', 2, 'User23', '2023-10-03 22:25:27', '2023-10-08 19:26:31', NULL, 0),
(4, 'Testing', 'Testing123@gmail.com', '$2y$10$dF.FG3ihpFHjzoBcPwBiwekWwWn/Zi8gQgLK1H8bipJ.0xDzT/YQe', 1, 'qwerty12', '2023-10-04 06:15:45', '2023-10-04 06:16:23', NULL, 0),
(5, 'Test Comcobox', 'testcombobox@gmail.com', '$2y$10$c1w2lMvLOCBPhIC9kSrCiuqAB8sz7UTHzWH4nMd8v76FE.QKXHmJW', 2, 'tsest', '2023-10-08 19:40:39', '2023-10-08 19:40:39', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_departement_id_foreign` (`departement_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
