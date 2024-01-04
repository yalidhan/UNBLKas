-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2024 at 01:40 PM
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
  `status` int(11) NOT NULL DEFAULT 1,
  `kelompok` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `no`, `tipe`, `nama`, `created_at`, `updated_at`, `status`, `kelompok`) VALUES
(1, '05.01', 'Beban', 'Gaji Pokok Pegawai Tetap', '2023-12-11 22:12:12', '2023-12-11 22:12:12', 1, 'Administrasi Keuangan'),
(2, '05.02', 'Beban', 'Tunjangan Jabatan', '2023-12-11 22:12:38', '2023-12-11 22:12:38', 1, 'Administrasi Keuangan'),
(3, '05.03', 'Beban', 'Tunjangan Fungsional', '2023-12-11 22:13:16', '2023-12-11 22:13:16', 1, 'Administrasi Keuangan'),
(4, '05.04', 'Beban', 'Tunjangan Lainnya', '2023-12-11 22:13:40', '2023-12-11 22:13:40', 1, 'Administrasi Keuangan'),
(5, '05.05', 'Beban', 'Tunjangan Hari Raya', '2023-12-11 22:13:57', '2023-12-11 22:13:57', 1, 'Administrasi Keuangan'),
(6, '05.06', 'Beban', 'Tunjangan Penyesuain', '2023-12-11 22:14:13', '2023-12-11 22:14:13', 1, 'Administrasi Keuangan'),
(7, '05.07', 'Beban', 'Tunjangan Transport', '2023-12-11 22:14:30', '2023-12-11 22:14:30', 1, 'Administrasi Keuangan'),
(8, '05.08', 'Beban', 'Konsumsi Pegawai', '2023-12-11 22:14:46', '2023-12-11 22:14:46', 1, 'Administrasi Keuangan'),
(9, '05.09', 'Beban', 'Asuransi DPLK', '2023-12-11 22:14:57', '2023-12-11 22:14:57', 1, 'Administrasi Keuangan'),
(10, '05.10', 'Beban', 'BPJS Kesehatan/Ketenagakerjaan', '2023-12-11 22:15:08', '2023-12-11 22:15:08', 1, 'Administrasi Keuangan'),
(11, '05.11', 'Beban', 'Gaji Honorer', '2023-12-11 22:15:17', '2023-12-11 22:15:17', 1, 'Administrasi Keuangan'),
(12, '05.12', 'Beban', 'Biaya Umroh Pegawai/Pengurus', '2023-12-11 22:15:26', '2023-12-11 22:15:26', 1, 'Administrasi Keuangan'),
(13, '05.13', 'Beban', 'Honor Pengurus Harian Yayasan', '2023-12-11 22:15:35', '2023-12-11 22:15:35', 1, 'Administrasi Keuangan'),
(14, '05.14', 'Beban', 'Bantuan Sosial Pendiri, Pembina, Pengawas, dan Rektor', '2023-12-11 22:15:45', '2023-12-11 22:15:45', 1, 'Administrasi Keuangan'),
(15, '05.15', 'Beban', 'Tunjangan Kehormatan', '2023-12-11 22:15:57', '2023-12-11 22:15:57', 1, 'Administrasi Keuangan'),
(16, '05.16', 'Beban', 'Pendidikan dan Pelatihan Pegawai(DIKLAT)', '2023-12-11 22:17:16', '2023-12-11 22:17:16', 1, 'Administrasi Kepegawaian'),
(17, '05.17', 'Beban', 'Pengadaan Pakaian Pegawai & Atribut Lainnya', '2023-12-11 22:17:29', '2023-12-11 22:17:29', 1, 'Administrasi Kepegawaian'),
(18, '05.18', 'Beban', 'Pengadaan Pakaian Mahasiswa & Atribut Lainnya', '2023-12-11 22:17:44', '2023-12-11 22:17:44', 1, 'Administrasi Kepegawaian'),
(19, '05.19', 'Beban', 'Penyediaan Peralatan dan Perlengkapan Kantor (ATK)', '2023-12-11 22:18:20', '2023-12-11 22:18:20', 1, 'Administrasi Umum'),
(20, '05.20', 'Beban', 'Biaya Perjalanan Dinas', '2023-12-11 22:18:33', '2023-12-11 22:18:33', 1, 'Administrasi Umum'),
(21, '05.21', 'Beban', 'Penyediaan Komponen Instalasi Listrik & Air (Alat/Bahan Kebersihan, dll)', '2023-12-11 22:18:46', '2023-12-11 22:18:46', 1, 'Administrasi Umum'),
(22, '05.22', 'Beban', 'Penyediaan Barang Cetakan dan Penggandaan', '2023-12-11 22:18:55', '2023-12-11 22:18:55', 1, 'Administrasi Umum'),
(23, '05.23', 'Beban', 'Penyediaan Bahan Bacaan, Majalah, Koran dan Jurnal', '2023-12-11 22:19:07', '2023-12-11 22:19:07', 1, 'Administrasi Umum'),
(24, '05.24', 'Beban', 'Penyelenggaraan Rapat Koordinasi dan Konsultasi', '2023-12-11 22:19:16', '2023-12-11 22:19:16', 1, 'Administrasi Umum'),
(25, '05.25', 'Beban', 'Biaya Sistem Informasi (sewa/pengembangan aplikasi)', '2023-12-11 22:19:33', '2023-12-11 22:19:33', 1, 'Administrasi Umum'),
(26, '05.26', 'Beban', 'Biaya Promosi & Kerjasama / Asosiasi', '2023-12-11 22:19:43', '2023-12-11 22:19:43', 1, 'Administrasi Umum'),
(27, '05.27', 'Beban', 'Bantuan Sosial a.n Universitas Borneo Lestari', '2023-12-11 22:19:53', '2023-12-11 22:19:53', 1, 'Administrasi Umum'),
(28, '05.28', 'Beban', 'Bantuan Sosial a.n Yayasan Borneo Lestari', '2023-12-11 22:20:10', '2023-12-11 22:20:10', 1, 'Administrasi Umum'),
(29, '05.29', 'Beban', 'Biaya Administrasi Bank', '2023-12-11 22:20:19', '2023-12-11 22:20:19', 1, 'Administrasi Umum'),
(30, '05.30', 'Beban', 'Biaya Jasa Notaris/Konsultan/Auditor', '2023-12-11 22:20:28', '2023-12-11 22:20:28', 1, 'Administrasi Umum'),
(31, '05.31', 'Beban', 'Biaya Perijinan', '2023-12-11 22:20:38', '2023-12-11 22:20:38', 1, 'Administrasi Umum'),
(32, '05.32', 'Beban', 'Bantuan Kesehatan Mahasiswa', '2023-12-11 22:20:49', '2023-12-11 22:20:49', 1, 'Administrasi Umum'),
(33, '05.33', 'Beban', 'Biaya Investasi', '2023-12-11 22:20:57', '2023-12-11 22:20:57', 1, 'Administrasi Umum'),
(34, '05.34', 'Beban', 'Biaya Lain-lain', '2023-12-11 22:21:06', '2023-12-11 22:21:06', 1, 'Administrasi Umum'),
(35, '05.35', 'Beban', 'Pajak Penghasilan (PPh 21)', '2023-12-11 22:22:27', '2023-12-11 22:22:27', 1, 'Beban Pajak'),
(36, '05.36', 'Beban', 'Pajak Badan (PPhB)', '2023-12-11 22:22:55', '2023-12-11 22:22:55', 1, 'Beban Pajak'),
(37, '05.37', 'Beban', 'Pajak Bumi dan Bangunan (PBB)', '2023-12-11 22:23:06', '2023-12-11 22:23:06', 1, 'Beban Pajak'),
(38, '05.38', 'Beban', 'Pajak Jasa (PPh 23)', '2023-12-11 22:23:15', '2023-12-11 22:23:15', 1, 'Beban Pajak'),
(39, '05.39', 'Beban', 'Pajak Reklame', '2023-12-11 22:23:26', '2023-12-11 22:23:26', 1, 'Beban Pajak'),
(40, '05.40', 'Beban', 'Pengadaan Mobil Dinas/Lapangan', '2023-12-11 22:24:01', '2023-12-11 22:24:01', 1, 'Pengadaan Barang/Alat/Bahan Habis Pakai/Reagens'),
(41, '05.41', 'Beban', 'Pengadaan Aset Lainnya', '2023-12-11 22:24:11', '2023-12-11 22:24:11', 1, 'Pengadaan Barang/Alat/Bahan Habis Pakai/Reagens'),
(42, '05.42', 'Beban', 'Peralatan Laboratorium/ Praktikum', '2023-12-11 22:24:27', '2023-12-11 22:24:27', 1, 'Pengadaan Barang/Alat/Bahan Habis Pakai/Reagens'),
(43, '05.43', 'Beban', 'Reagensia & Habis Pakai lainnya', '2023-12-11 22:24:45', '2023-12-11 22:24:45', 1, 'Pengadaan Barang/Alat/Bahan Habis Pakai/Reagens'),
(44, '05.44', 'Beban', 'Penyediaan Sumber Daya Air', '2023-12-11 22:25:17', '2023-12-11 22:25:17', 1, 'Penyediaan Jasa Penunjang'),
(45, '05.45', 'Beban', 'Penyediaan Sumber Listrik', '2023-12-11 22:25:45', '2023-12-11 22:25:45', 1, 'Penyediaan Jasa Penunjang'),
(46, '05.46', 'Beban', 'Penyediaan Jasa Komunikasi/Wifi/Internet', '2023-12-11 22:25:59', '2023-12-11 22:25:59', 1, 'Penyediaan Jasa Penunjang'),
(47, '05.47', 'Beban', 'Penyediaan Jasa Pelayanan Umum lainnya', '2023-12-11 22:26:09', '2023-12-11 22:26:09', 1, 'Penyediaan Jasa Penunjang'),
(48, '05.48', 'Beban', 'Biaya Operasional Harian Yayasan', '2023-12-11 22:26:22', '2023-12-11 22:26:22', 1, 'Penyediaan Jasa Penunjang'),
(49, '05.49', 'Beban', 'Jasa Pemeliharaan, Biaya Pemeliharaan, Pajak dan Perizinan Kendaraan Dinas Operasional atau Lapangan', '2023-12-11 22:28:28', '2023-12-11 22:28:28', 1, 'Biaya Pemeliharaan Barang/Peralatan Kantor/Laboratorium'),
(50, '05.50', 'Beban', 'Rehabilitasi Gedung Kantor dan Bangunan Lainnya', '2023-12-11 22:29:06', '2023-12-11 22:29:06', 1, 'Biaya Pemeliharaan Barang/Peralatan Kantor/Laboratorium'),
(51, '05.51', 'Beban', 'Pemeliharaan/Rehabilitasi Sarana dan Prasarana Pendukung Gedung Kantor atau Bangunan Lainnya', '2023-12-11 22:29:58', '2023-12-11 22:29:58', 1, 'Biaya Pemeliharaan Barang/Peralatan Kantor/Laboratorium'),
(52, '05.52', 'Beban', 'Penerimaan Mahasiswa Baru', '2023-12-11 22:30:48', '2023-12-11 22:30:48', 1, 'Pendidikan/Pengajaran'),
(53, '05.53', 'Beban', 'Pengenalan Kehidupan Kampus Mahasiswa Baru (PKKMB)', '2023-12-11 22:31:07', '2023-12-11 22:31:07', 1, 'Pendidikan/Pengajaran'),
(54, '05.54', 'Beban', 'Honor Pengajar Teori dan Praktikum', '2023-12-11 22:31:26', '2023-12-11 22:31:26', 1, 'Pendidikan/Pengajaran'),
(55, '05.55', 'Beban', 'Honor Pembimbing Akademik', '2023-12-11 22:31:38', '2023-12-11 22:31:38', 1, 'Pendidikan/Pengajaran'),
(56, '05.56', 'Beban', 'Honor Pembimbing Internal/Eksternal', '2023-12-11 22:31:54', '2023-12-11 22:31:54', 1, 'Pendidikan/Pengajaran'),
(57, '05.57', 'Beban', 'Honor Penyelenggaraan UTS / UAS', '2023-12-11 22:32:06', '2023-12-11 22:32:06', 1, 'Pendidikan/Pengajaran'),
(58, '05.58', 'Beban', 'Biaya Rapat, Kuliah Tamu / Perdana, Seminar, & Workshop', '2023-12-11 22:32:23', '2023-12-11 22:32:23', 1, 'Pendidikan/Pengajaran'),
(59, '05.59', 'Beban', 'Biaya Lahan Praktek', '2023-12-11 22:32:37', '2023-12-11 22:32:37', 1, 'Pendidikan/Pengajaran'),
(60, '05.60', 'Beban', 'Administrasi Tempat Praktek', '2023-12-11 22:32:51', '2023-12-11 22:32:51', 1, 'Pendidikan/Pengajaran'),
(61, '05.61', 'Beban', 'Pembekalan Tempat Praktek', '2023-12-11 22:33:18', '2023-12-11 22:33:18', 1, 'Pendidikan/Pengajaran'),
(62, '05.62', 'Beban', 'Kuliah Kerja Nyata (KKN)', '2023-12-11 22:33:49', '2023-12-11 22:33:49', 1, 'Pendidikan/Pengajaran'),
(63, '05.63', 'Beban', 'Praktik Kerja Lapangan (PKL)', '2023-12-11 22:34:01', '2023-12-11 22:34:01', 1, 'Pendidikan/Pengajaran'),
(64, '05.64', 'Beban', 'Problem Based Learning (PBL)', '2023-12-11 22:34:16', '2023-12-11 22:34:16', 1, 'Pendidikan/Pengajaran'),
(65, '05.65', 'Beban', 'Pendidikan dan Pelatatihan Kepemimpinan Mahasiswa (PPKM)', '2023-12-11 22:34:28', '2023-12-11 22:34:28', 1, 'Pendidikan/Pengajaran'),
(66, '05.66', 'Beban', 'Karya Tulis Ilmiah (KTI)', '2023-12-11 22:34:41', '2023-12-11 22:34:41', 1, 'Pendidikan/Pengajaran'),
(67, '05.67', 'Beban', 'Skripsi', '2023-12-11 22:34:55', '2023-12-11 22:34:55', 1, 'Pendidikan/Pengajaran'),
(68, '05.68', 'Beban', 'Laporan Tugas Akhir (LTA)', '2023-12-11 22:35:05', '2023-12-11 22:35:05', 1, 'Pendidikan/Pengajaran'),
(69, '05.69', 'Beban', 'Uji Kompetensi', '2023-12-11 22:35:16', '2023-12-11 22:35:16', 1, 'Pendidikan/Pengajaran'),
(70, '05.70', 'Beban', 'Bantuan Kegiatan Mahasiswa', '2023-12-11 22:35:33', '2023-12-11 22:35:33', 1, 'Pendidikan/Pengajaran'),
(71, '05.71', 'Beban', 'Biaya Seragam Siswa/Mahasiswa', '2023-12-11 23:02:30', '2023-12-11 23:02:30', 1, 'Pendidikan/Pengajaran'),
(72, '05.72', 'Beban', 'Wisuda Yudisium & Sumpah', '2023-12-11 23:02:57', '2023-12-11 23:02:57', 1, 'Pendidikan/Pengajaran'),
(73, '05.73', 'Beban', 'Kegiatan Penelitian Dana Internal (Total 77 Dosen / 9 Prodi)', '2023-12-11 23:04:05', '2023-12-11 23:04:05', 1, 'Bantuan Biaya Penelitian Dosen'),
(74, '05.74', 'Beban', 'Pengajuan Fasilitas Publikasi Ilmiah Dosen: Grammarly, Quillbot, Spinner', '2023-12-11 23:06:09', '2023-12-11 23:06:09', 1, 'Bantuan Biaya Penelitian Dosen'),
(75, '05.75', 'Beban', 'Layanan turnitin (plagiarisme check)', '2023-12-11 23:07:05', '2023-12-11 23:07:05', 1, 'Bantuan Biaya Penelitian Dosen'),
(76, '05.76', 'Beban', 'Kegiatan Pengabdian Masyarakat Hibah Internal (Total 77 Dosen / 9 Prodi)', '2023-12-12 04:36:27', '2023-12-12 04:36:27', 1, 'Bantuan Biaya Pengabdian Masyarakat'),
(77, '05.77', 'Beban', 'Pengembangan Desa Binaan', '2023-12-12 04:37:00', '2023-12-12 04:37:00', 1, 'Bantuan Biaya Pengabdian Masyarakat'),
(78, '05.78', 'Beban', 'Biaya Pendaftaran Akreditasi', '2023-12-12 04:37:36', '2023-12-12 04:37:36', 1, 'Akreditasi'),
(79, '05.79', 'Beban', 'Biaya Persiapan Akreditasi', '2023-12-12 04:38:04', '2023-12-12 04:38:04', 1, 'Akreditasi'),
(80, '05.80', 'Beban', 'Biaya Asesmen Lapangan Akreditasi', '2023-12-12 04:38:23', '2023-12-12 04:38:23', 1, 'Akreditasi'),
(81, '01.01', 'Harta', 'Kas Kecil Yayasan Borneo Lestari', '2023-12-12 04:42:27', '2023-12-12 04:42:27', 1, 'Kas Operasional'),
(82, '01.02', 'Harta', 'Kas Kecil Rektorat', '2023-12-12 04:43:01', '2023-12-12 04:43:01', 1, 'Kas Operasional'),
(83, '01.03', 'Harta', 'Kas Kecil Dekanat FK Farmasi', '2023-12-12 04:45:50', '2024-01-02 12:26:00', 1, 'Kas Operasional'),
(84, '04.01', 'Pendapatan', 'Penerimaan SPP', '2023-12-12 05:02:02', '2023-12-12 05:02:18', 1, 'Pendapatan'),
(87, '01.04', 'Harta', 'Kas Kecil Dekanat FIKST', '2024-01-02 12:30:59', '2024-01-02 12:30:59', 1, 'Kas Operasional'),
(88, '01.05', 'Harta', 'Kas Kecil Dekanat FISOSHUM', '2024-01-02 12:31:53', '2024-01-02 12:31:53', 1, 'Kas Operasional'),
(89, '01.06', 'Harta', 'Kas Kecil Prodi D3 TLM', '2024-01-02 12:32:55', '2024-01-02 12:32:55', 1, 'Kas Operasional'),
(90, '01.07', 'Harta', 'Kas Kecil PSPPA', '2024-01-02 12:33:45', '2024-01-02 12:33:45', 1, 'Kas Operasional'),
(91, '01.08', 'Harta', 'Kas Kecil Prodi S1 ARS', '2024-01-02 12:34:09', '2024-01-02 12:36:24', 1, 'Kas Operasional'),
(92, '01.09', 'Harta', 'Kas Kecil Prodi S1 Gizi', '2024-01-02 12:34:28', '2024-01-02 12:36:37', 1, 'Kas Operasional'),
(93, '01.10', 'Harta', 'Kas Kecil Prodi S1 Farmasi', '2024-01-02 12:34:58', '2024-01-02 12:36:50', 1, 'Kas Operasional'),
(94, '01.11', 'Harta', 'Kas Kecil Prodi S1 Farmasi AJ', '2024-01-02 12:35:17', '2024-01-02 12:37:18', 1, 'Kas Operasional'),
(95, '01.12', 'Harta', 'Kas Kecil Prodi S1 Hukum', '2024-01-02 12:35:45', '2024-01-02 12:37:34', 1, 'Kas Operasional'),
(96, '01.13', 'Harta', 'Kas Kecil Prodi S1 Manajemen', '2024-01-02 12:38:03', '2024-01-02 12:38:03', 1, 'Kas Operasional'),
(97, '01.14', 'Harta', 'Kas Kecil Prodi S1 PGSD', '2024-01-02 12:38:21', '2024-01-02 12:38:21', 1, 'Kas Operasional');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun` year(4) NOT NULL,
  `departement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `tahun`, `departement_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2023, 2, 7, '2023-12-15 01:56:05', '2023-12-15 01:56:05'),
(2, 2023, 12, 8, '2023-12-15 18:46:19', '2023-12-15 18:46:19'),
(3, 2023, 1, 1, '2023-12-17 17:27:24', '2023-12-17 17:27:24'),
(4, 2024, 12, 8, '2024-01-02 03:30:32', '2024-01-02 03:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `budget_details`
--

CREATE TABLE `budget_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_details`
--

INSERT INTO `budget_details` (`id`, `budget_id`, `account_id`, `nominal`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 118000000, NULL, '2023-12-15 01:58:50', '2023-12-15 01:58:50'),
(2, 1, 2, 21000000, NULL, '2023-12-15 01:59:12', '2023-12-15 01:59:12'),
(3, 1, 3, 179300000, NULL, '2023-12-15 01:59:36', '2023-12-15 01:59:36'),
(4, 1, 4, 16200000, NULL, '2023-12-15 02:00:11', '2023-12-15 02:00:11'),
(5, 1, 16, 99000000, NULL, '2023-12-15 02:00:43', '2023-12-15 02:00:43'),
(6, 1, 20, 157200000, NULL, '2023-12-15 02:01:20', '2023-12-15 02:01:20'),
(7, 1, 22, 36000000, NULL, '2023-12-15 02:01:42', '2023-12-15 02:01:42'),
(8, 1, 24, 1500000, NULL, '2023-12-15 02:02:02', '2023-12-15 02:02:02'),
(9, 2, 1, 111600000, '<p><br></p><table class=\"table table-bordered\"><tbody><tr><td>Gaji Pegawai Tetap S2</td><td>12</td><td>Pkt</td><td>1200000</td><td>14.400.000</td></tr><tr><td><br></td><td><br></td><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>', '2023-12-15 18:48:51', '2023-12-15 18:48:51'),
(10, 2, 2, 18000000, NULL, '2023-12-15 18:49:18', '2023-12-15 18:49:18'),
(11, 2, 3, 190600000, NULL, '2023-12-15 18:49:40', '2023-12-15 18:49:40'),
(12, 2, 20, 20400000, '<p>test</p>', '2023-12-15 18:50:52', '2023-12-28 12:24:54'),
(13, 2, 16, 9000000, NULL, '2023-12-15 18:51:30', '2023-12-15 18:51:30'),
(14, 3, 1, 200000000, NULL, '2023-12-17 17:27:43', '2023-12-17 17:27:43'),
(17, 4, 1, 200000000, '<p><br></p><table class=\"table table-bordered\"><tbody><tr><td>12</td><td>Rp 20.000.000</td><td>Rp 200.000.000</td></tr></tbody></table>', '2024-01-02 03:31:45', '2024-01-02 03:31:45'),
(18, 4, 20, 120000000, '<p><br></p><table class=\"table table-bordered\"><tbody><tr><td>12</td><td>Rp 10.000.000</td><td>Rp 120.000.000</td></tr></tbody></table>', '2024-01-02 03:32:31', '2024-01-02 03:32:31'),
(19, 4, 79, 5000000, '<p><br></p><table class=\"table table-bordered\"><tbody><tr><td>1</td><td>Rp 5.000.000</td><td>Rp 5.000.000</td></tr></tbody></table><p><br></p>', '2024-01-02 03:33:16', '2024-01-02 03:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

CREATE TABLE `departements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pusat` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departements`
--

INSERT INTO `departements` (`id`, `nama`, `status`, `created_at`, `updated_at`, `pusat`) VALUES
(1, 'Yayasan Borneo Lestari', 1, '2023-10-05 18:23:11', '2023-12-18 19:11:41', 'Yayasan Borneo Lestari'),
(2, 'Dekanat Fakultas Farmasi', 1, '2023-10-06 16:14:24', '2023-12-18 19:10:12', 'Fakultas Farmasi'),
(3, 'Dekanat Fakultas Sosial & Humaniora', 1, '2023-10-08 19:44:09', '2023-12-18 19:10:00', 'Fakultas Ilmu Sosial & Humaniora'),
(4, 'Dekanat Fakultas Ilmu Kesehatan & Sains Teknologi', 1, '2023-10-08 22:39:53', '2023-12-18 19:09:49', 'Fakultas Ilmu Kesehatan & Sains Teknologi'),
(6, 'Rektorat', 1, '2023-10-15 16:57:24', '2023-12-18 19:11:34', 'Rektorat'),
(8, 'Program Studi Pendidikan Profesi Apoteker', 1, '2023-12-11 19:50:25', '2023-12-18 19:10:28', 'Fakultas Farmasi'),
(9, 'Program Studi Sarjana Farmasi', 1, '2023-12-11 19:53:34', '2023-12-18 19:10:54', 'Fakultas Farmasi'),
(10, 'Program Studi Sarjana Farmasi AJ', 1, '2023-12-11 19:54:24', '2023-12-18 19:11:00', 'Fakultas Farmasi'),
(11, 'Program Studi D3 TLM', 1, '2023-12-11 19:56:15', '2023-12-18 19:10:22', 'Fakultas Farmasi'),
(12, 'Program Studi S1 Administrasi Rumah Sakit', 1, '2023-12-11 19:56:57', '2023-12-18 19:10:38', 'Fakultas Ilmu Kesehatan & Sains Teknologi'),
(13, 'Program Studi S1 Gizi', 1, '2023-12-11 19:57:43', '2023-12-18 19:10:48', 'Fakultas Ilmu Kesehatan & Sains Teknologi'),
(14, 'Program Studi Sarjana PGSD', 1, '2023-12-11 19:58:45', '2023-12-18 19:11:25', 'Fakultas Ilmu Sosial & Humaniora'),
(15, 'Program Studi Sarjana Hukum', 1, '2023-12-11 19:59:21', '2023-12-18 19:11:07', 'Fakultas Ilmu Sosial & Humaniora'),
(16, 'Program Studi Sarjana Manajemen', 1, '2023-12-11 20:00:29', '2023-12-18 19:11:16', 'Fakultas Ilmu Sosial & Humaniora');

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
(12, '2023_10_13_025115_add_status_to_accounts_table', 10),
(13, '2023_11_01_030501_create_transaction_table', 11),
(14, '2023_11_08_124907_drop_transaction_table', 12),
(15, '2023_11_08_125119_create_transactions_table', 13),
(16, '2023_11_08_132841_drop_transaction_details_table', 14),
(17, '2023_11_08_133630_drop_transaction_details_table', 15),
(18, '2023_11_08_133753_create_transcation_details_table', 16),
(19, '2023_12_01_134330_create_budgets_table', 17),
(20, '2023_12_01_134353_create_budget_details_table', 17),
(21, '2023_12_06_053500_add_kelompok_to_accounts_table', 18),
(22, '2023_12_19_021530_add_pusat_to_departements_table', 19),
(23, '2023_12_20_011658_create_plannings_table', 20),
(24, '2023_12_26_134801_create_planning_details_table', 21),
(25, '2023_12_31_215206_add_capaian_target_waktu_to_planning_details_table', 22);

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
-- Table structure for table `plannings`
--

CREATE TABLE `plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `for_bulan` date NOT NULL,
  `departement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED NOT NULL,
  `is_approved_wr2` int(11) NOT NULL DEFAULT 0,
  `is_approved_rektor` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plannings`
--

INSERT INTO `plannings` (`id`, `for_bulan`, `departement_id`, `user_id`, `budget_id`, `is_approved_wr2`, `is_approved_rektor`, `created_at`, `updated_at`) VALUES
(1, '2023-12-01', 12, 8, 2, 2, 2, '2023-12-19 22:00:30', '2023-12-19 22:00:30'),
(6, '2023-11-01', 12, 8, 2, 1, 2, '2023-12-25 10:13:44', '2023-12-25 10:13:44'),
(7, '2023-12-01', 12, 8, 2, 2, 1, '2023-12-25 12:59:18', '2023-12-25 12:59:18'),
(8, '2023-12-01', 12, 8, 2, 0, 0, '2023-12-25 14:27:25', '2023-12-27 02:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `planning_details`
--

CREATE TABLE `planning_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planning_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` int(11) NOT NULL DEFAULT 0,
  `nominal_disetujui` int(11) NOT NULL DEFAULT 0,
  `group_rektorat` varchar(25) NOT NULL,
  `approved_by_wr2` int(11) NOT NULL DEFAULT 0,
  `note_wr2` varchar(25) NOT NULL,
  `approved_by_rektor` int(11) NOT NULL DEFAULT 0,
  `note_rektor` varchar(25) NOT NULL,
  `pj` varchar(25) NOT NULL,
  `satuan_ukur_kinerja` varchar(250) DEFAULT NULL,
  `target_kinerja` varchar(100) NOT NULL,
  `capaian_kinerja` varchar(100) NOT NULL,
  `waktu_pelaksanaan` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `capaian_target_waktu` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `planning_details`
--

INSERT INTO `planning_details` (`id`, `planning_id`, `account_id`, `nominal`, `nominal_disetujui`, `group_rektorat`, `approved_by_wr2`, `note_wr2`, `approved_by_rektor`, `note_rektor`, `pj`, `satuan_ukur_kinerja`, `target_kinerja`, `capaian_kinerja`, `waktu_pelaksanaan`, `created_at`, `updated_at`, `capaian_target_waktu`) VALUES
(1, 8, 1, 125000000, 125000000, '', 1, '', 1, '', 'Sekretaris', 'https://drive.google.com/drive/folders/1E0Ub2Ar5F7R52eAjjgMN8ZeCy-ShnCn1?usp=drive_link', 'Bukti Pembayaran', 'Terwujudnya Peningkatan Mutu Pendidikan Yang Baik', 'Minggu ke 1', '2023-12-31 13:09:12', NULL, '1 Hari');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_trf` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `no_spb` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `departement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `no_trf`, `tanggal`, `no_spb`, `keterangan`, `departement_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '2023-12-01', 'REF/01/2023', 'Kolektif penerimaan SPP Tahun Semester Genap Tahun 2023', 1, 1, '2023-12-15 02:03:45', '2023-12-15 02:03:45'),
(2, 'TF.1', '2023-12-02', '01/YYS/BL/2023', 'Dropping Bulan Desember', 1, 1, '2023-12-15 02:04:44', '2023-12-15 02:04:44'),
(3, 'TF.1', '2023-12-02', '01/YYS/BL/2023', 'Dropping Bulan Desember', 6, 1, '2023-12-15 02:04:44', '2023-12-15 02:04:44'),
(4, 'TF.2', '2023-12-15', '01/REKTORAt/UNBL/2023', 'Dropping ke Fakultas Farmasi', 6, 6, '2023-12-15 02:07:27', '2023-12-15 02:07:27'),
(5, 'TF.2', '2023-12-15', '01/REKTORAt/UNBL/2023', 'Dropping ke Fakultas Farmasi', 2, 6, '2023-12-15 02:07:27', '2023-12-15 02:07:27'),
(6, NULL, '2023-12-15', '01/FKFARMASI/UNBL/2023', 'Dikeluarkan uang operasional untuk biaya perjalanan dinas', 2, 7, '2023-12-15 02:10:18', '2023-12-15 02:10:18'),
(7, NULL, '2023-12-16', '02/FKFARMASI/UNBL/2023', 'Dikeluarkan uang operasional untuk biaya cetak', 2, 7, '2023-12-15 02:12:06', '2023-12-15 02:12:06'),
(8, NULL, '2023-12-17', '03/FKFARMASI/UNBL/2023', 'Dikeluarkan uang operasional untuk pembayaran gaji pegawai bulan desember 2023', 2, 7, '2023-12-15 02:13:32', '2023-12-15 02:13:47'),
(9, NULL, '2023-12-18', '04/FKFARMASI/UNBL/2023', 'Dikeluarkan uang operasional untuk perjalanan dinas', 2, 7, '2023-12-15 04:59:20', '2023-12-15 04:59:20'),
(10, NULL, '2023-12-16', '05/FKFARMASI/2023', 'Dikeluarkan uang operasional u/ biaya cetak', 2, 7, '2023-12-15 05:02:24', '2023-12-15 05:02:24'),
(11, 'TF.3', '2023-12-16', '05/rktrt/2023', 'dropping dana ke ars', 6, 6, '2023-12-15 18:54:29', '2023-12-15 18:54:29'),
(12, 'TF.3', '2023-12-16', '05/rktrt/2023', 'dropping dana ke ars', 12, 6, '2023-12-15 18:54:29', '2023-12-15 18:54:29'),
(13, NULL, '2023-12-16', '01/S1 ARS/2023', 'Pembayaran gaji s1 ARS bulan Desember 2023', 12, 8, '2023-12-15 18:56:34', '2023-12-15 18:56:34'),
(14, NULL, '2023-12-16', '02/S1 ARS/2023', 'Dikeluarkan uang op u/ perjalanan dinas', 12, 8, '2023-12-15 18:58:32', '2023-12-15 18:58:32'),
(15, 'TF.4', '2023-12-16', 'TF/02/11/2023', 'Pengembalian sisa dana operasional', 12, 8, '2023-12-15 19:00:11', '2023-12-15 19:00:11'),
(16, 'TF.4', '2023-12-16', 'TF/02/11/2023', 'Pengembalian sisa dana operasional', 6, 8, '2023-12-15 19:00:11', '2023-12-15 19:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` int(11) NOT NULL,
  `dk` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `account_id`, `nominal`, `dk`, `created_at`, `updated_at`) VALUES
(1, 1, 84, 1000000000, 1, '2023-12-15 02:03:45', '2023-12-15 02:03:45'),
(2, 2, 81, 500000000, 2, '2023-12-15 02:04:44', '2023-12-15 02:04:44'),
(3, 3, 82, 500000000, 1, '2023-12-15 02:04:44', '2023-12-15 02:04:44'),
(4, 4, 82, 400000000, 2, '2023-12-15 02:07:27', '2023-12-15 02:07:27'),
(5, 5, 83, 400000000, 1, '2023-12-15 02:07:27', '2023-12-15 02:07:27'),
(6, 6, 20, 15000000, 2, '2023-12-15 02:11:18', '2023-12-15 02:11:18'),
(7, 7, 22, 2000000, 2, '2023-12-15 02:12:23', '2023-12-15 02:12:23'),
(8, 8, 1, 10000000, 2, '2023-12-15 02:14:07', '2023-12-15 02:14:07'),
(9, 8, 2, 5000000, 2, '2023-12-15 02:14:18', '2023-12-15 02:14:18'),
(10, 8, 3, 7000000, 2, '2023-12-15 02:14:31', '2023-12-15 02:14:31'),
(11, 9, 20, 5000000, 2, '2023-12-15 04:59:39', '2023-12-15 04:59:39'),
(12, 10, 22, 3000000, 2, '2023-12-15 05:03:09', '2023-12-15 05:03:09'),
(13, 8, 4, 5555555, 2, '2023-12-15 05:07:58', '2023-12-15 05:07:58'),
(15, 9, 16, 7000000, 2, '2023-12-15 05:09:55', '2023-12-15 05:09:55'),
(16, 11, 82, 50000000, 2, '2023-12-15 18:54:29', '2023-12-15 18:54:29'),
(17, 12, 82, 50000000, 1, '2023-12-15 18:54:29', '2023-12-15 18:54:29'),
(18, 13, 1, 7611229, 2, '2023-12-15 18:57:21', '2023-12-15 18:57:21'),
(19, 13, 2, 1500000, 2, '2023-12-15 18:57:44', '2023-12-15 18:57:44'),
(20, 13, 3, 250000, 2, '2023-12-15 18:58:03', '2023-12-15 18:58:03'),
(21, 14, 20, 5000000, 2, '2023-12-15 18:58:50', '2023-12-28 12:19:40'),
(22, 15, 83, 35638711, 2, '2023-12-15 19:00:11', '2023-12-15 19:00:11'),
(23, 16, 82, 35638711, 1, '2023-12-15 19:00:11', '2023-12-15 19:00:11');

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
(1, 'admin', 'dedek.southborneo@gmail.com', '$2y$10$imIIHwpD4aa9q9EEzHQD/eET7wb3hLfNsM.yAxYpUE4Amp08VWKXC', 1, 'Bendahara Yayasan', '2023-09-18 16:00:00', '2023-12-25 14:50:24', 'THfhaI4KMlz6MC3U2gJKNVibOioTcl0JzJcFFQnA5x2pJy2ISBpNmHoQC5sH', 1),
(6, 'Admin Rektorat', 'adminrektorat@gmail.com', '$2y$10$6qL9Vu365B0vbGKu9uEdw.UAJ46gUr.IBHSpsrIIe7U.XhcqCBAN.', 6, 'Bendahara Operasional', '2023-11-17 05:49:14', '2024-01-02 03:29:09', NULL, 1),
(7, 'Admin Farmasi', 'adminfarmasi@gmail.com', '$2y$10$Wo3NNXtHn.s5Ml9.skVtUejlX5456vlQw1oiFXXGkaUrmeYnp0OHu', 2, 'Bendahara Operasional', '2023-11-19 17:59:35', '2023-12-17 17:54:16', NULL, 1),
(8, 'Admin ARS', 'adminars@gmail.com', '$2y$10$FWehPTlJjjYqQm6hRBus4.RTqvW2Rr/EznyU.d2D.bY0LlBOnFR12', 12, 'Bendahara Operasional', '2023-12-15 02:06:14', '2023-12-15 02:06:14', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_departement_id_foreign` (`departement_id`),
  ADD KEY `budgets_user_id_foreign` (`user_id`);

--
-- Indexes for table `budget_details`
--
ALTER TABLE `budget_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_details_budget_id_foreign` (`budget_id`),
  ADD KEY `budget_details_account_id_foreign` (`account_id`);

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
-- Indexes for table `plannings`
--
ALTER TABLE `plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plannings_departement_id_foreign` (`departement_id`),
  ADD KEY `plannings_user_id_foreign` (`user_id`),
  ADD KEY `plannings_budget_id_foreign` (`budget_id`);

--
-- Indexes for table `planning_details`
--
ALTER TABLE `planning_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `planning_details_planning_id_foreign` (`planning_id`),
  ADD KEY `planning_details_account_id_foreign` (`account_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_departement_id_foreign` (`departement_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transcation_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transcation_details_account_id_foreign` (`account_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `budget_details`
--
ALTER TABLE `budget_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plannings`
--
ALTER TABLE `plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `planning_details`
--
ALTER TABLE `planning_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  ADD CONSTRAINT `budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `budget_details`
--
ALTER TABLE `budget_details`
  ADD CONSTRAINT `budget_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `budget_details_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plannings`
--
ALTER TABLE `plannings`
  ADD CONSTRAINT `plannings_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`),
  ADD CONSTRAINT `plannings_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  ADD CONSTRAINT `plannings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `planning_details`
--
ALTER TABLE `planning_details`
  ADD CONSTRAINT `planning_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `planning_details_planning_id_foreign` FOREIGN KEY (`planning_id`) REFERENCES `plannings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transcation_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `transcation_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
