-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 02:58 AM
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
-- Database: `db_uas_pbo_trpl1b_irma siti wahyuni`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `id_karyawan` varchar(20) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `hari_kerja_masuk` int NOT NULL,
  `gaji_dasar_per_hari` decimal(15,2) NOT NULL,
  `jenis_karyawan` enum('Kontrak','Tetap','Magang') NOT NULL,
  `durasi_kontrak_bulan` int DEFAULT NULL,
  `agensi_penyalur` varchar(100) DEFAULT NULL,
  `tunjangan_kesehatan` decimal(15,2) DEFAULT NULL,
  `opsi_saham_id` varchar(50) DEFAULT NULL,
  `uang_saku_bulanan` decimal(15,2) DEFAULT NULL,
  `sertifikat_kampus_merdeka` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`id_karyawan`, `nama_karyawan`, `departemen`, `hari_kerja_masuk`, `gaji_dasar_per_hari`, `jenis_karyawan`, `durasi_kontrak_bulan`, `agensi_penyalur`, `tunjangan_kesehatan`, `opsi_saham_id`, `uang_saku_bulanan`, `sertifikat_kampus_merdeka`) VALUES
('K001', 'Budi Santoso', 'Teknologi Informasi', 22, 150000.00, 'Kontrak', 12, 'PT Teknologi Maju Nusantara', NULL, NULL, NULL, NULL),
('K002', 'Siti Rahayu', 'Sumber Daya Manusia', 20, 140000.00, 'Kontrak', 6, 'PT Sumber Daya Manusia Andalan', NULL, NULL, NULL, NULL),
('K003', 'Andi Wijaya', 'Keuangan', 21, 160000.00, 'Kontrak', 18, 'PT Keuangan Sejahtera Abadi', NULL, NULL, NULL, NULL),
('K004', 'Dewi Lestari', 'Pemasaran', 19, 145000.00, 'Kontrak', 8, 'PT Kreatif Media Nusantara', NULL, NULL, NULL, NULL),
('K005', 'Rudi Hermawan', 'Teknologi Informasi', 23, 155000.00, 'Kontrak', 24, 'PT Solusi Digital Inovatif', NULL, NULL, NULL, NULL),
('K006', 'Nina Maharani', 'Sumber Daya Manusia', 20, 135000.00, 'Kontrak', 10, 'PT Konsultan SDM Profesional', NULL, NULL, NULL, NULL),
('K007', 'Fajar Pratama', 'Operasional', 21, 148000.00, 'Kontrak', 14, 'PT Logistik Cepat Sentosa', NULL, NULL, NULL, NULL),
('M001', 'Putri Aulia', 'Teknologi Informasi', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2500000.00, 'MSIB-KampusMerdeka-2024-001'),
('M002', 'Rizki Fadillah', 'Pemasaran', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2000000.00, 'MSIB-KampusMerdeka-2024-002'),
('M003', 'Lina Wati', 'Sumber Daya Manusia', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2200000.00, 'MSIB-KampusMerdeka-2024-003'),
('M004', 'Eko Prasetyo', 'Teknologi Informasi', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2600000.00, 'MSIB-KampusMerdeka-2024-004'),
('M005', 'Rini Susanti', 'Keuangan', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2300000.00, 'MSIB-KampusMerdeka-2024-005'),
('M006', 'Gilang Ramadhan', 'Operasional', 0, 0.00, 'Magang', NULL, NULL, NULL, NULL, 2100000.00, 'MSIB-KampusMerdeka-2024-006'),
('T001', 'Agus Salim', 'Teknologi Informasi', 25, 200000.00, 'Tetap', NULL, NULL, 1500000.00, 'SAHAM-IT-001', NULL, NULL),
('T002', 'Ratna Dewi', 'Keuangan', 24, 190000.00, 'Tetap', NULL, NULL, 1200000.00, 'SAHAM-KEU-002', NULL, NULL),
('T003', 'Bambang Sutrisno', 'Pemasaran', 26, 210000.00, 'Tetap', NULL, NULL, 1800000.00, 'SAHAM-MAR-003', NULL, NULL),
('T004', 'Sri Mulyani', 'Sumber Daya Manusia', 22, 185000.00, 'Tetap', NULL, NULL, 1100000.00, 'SAHAM-HRD-004', NULL, NULL),
('T005', 'Hendra Gunawan', 'Teknologi Informasi', 25, 205000.00, 'Tetap', NULL, NULL, 1600000.00, 'SAHAM-IT-005', NULL, NULL),
('T006', 'Rina Marlina', 'Operasional', 23, 195000.00, 'Tetap', NULL, NULL, 1300000.00, 'SAHAM-OPR-006', NULL, NULL),
('T007', 'Doni Permana', 'Keuangan', 24, 198000.00, 'Tetap', NULL, NULL, 1400000.00, 'SAHAM-KEU-007', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
