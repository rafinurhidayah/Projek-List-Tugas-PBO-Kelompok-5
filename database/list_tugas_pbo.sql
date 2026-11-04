-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Nov 2026 pada 12.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `list_tugas_pbo`
--

CREATE DATABASE IF NOT EXISTS `list_tugas_pbo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `list_tugas_pbo`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jenis` enum('kuliah','pribadi') NOT NULL,
  `detail_tambahan` varchar(255) NOT NULL,
  `prioritas` enum('penting','normal') DEFAULT 'normal',
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id`, `judul`, `jenis`, `detail_tambahan`, `prioritas`, `status`, `created_at`) VALUES
(2, 'TEst', 'kuliah', 'test input db', 'normal', 0, '2026-10-07 11:57:26'),
(3, 'Pbo', 'kuliah', 'Test', 'normal', 0, '2026-10-07 11:57:48'),
(4, 'Test', 'kuliah', 'oaoa', 'normal', 0, '2026-10-07 12:24:51'),
(5, 'Yusril', 'pribadi', 'dasdasd', 'penting', 0, '2026-10-07 13:37:45'),
(6, 'PBO', 'pribadi', 'Web', 'penting', 0, '2026-10-21 13:13:09'),
(7, 'sad', 'kuliah', 'dads', 'normal', 0, '2026-11-04 11:45:53');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
