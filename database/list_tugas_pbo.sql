-- Manajemen Tugas PBO — struktur database (MySQL / MariaDB)
-- Impor ke database KOSONG. Jika sebelumnya sudah ada database `list_tugas_pbo`
-- versi lama, hapus dulu di phpMyAdmin (Operasi > Hapus database) lalu impor file ini.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `list_tugas_pbo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `list_tugas_pbo`;

-- --------------------------------------------------------
-- Tabel `user` (induk): satu pengguna punya banyak tugas
-- `password` berisi hash dari password_hash() (bcrypt = 60 karakter), bukan password asli.
-- --------------------------------------------------------
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_username` (`username`),
  UNIQUE KEY `uq_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel `tugas` (anak): user_id -> user.id
-- user_id TIDAK unik, jadi satu pengguna boleh punya banyak tugas.
-- Hapus pengguna => tugas miliknya ikut terhapus (ON DELETE CASCADE).
-- --------------------------------------------------------
CREATE TABLE `tugas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jenis` enum('kuliah','pribadi') NOT NULL,
  `detail_tambahan` varchar(255) NOT NULL DEFAULT '',
  `prioritas` enum('penting','normal') NOT NULL DEFAULT 'normal',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tugas_user` (`user_id`),
  CONSTRAINT `fk_tugas_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
