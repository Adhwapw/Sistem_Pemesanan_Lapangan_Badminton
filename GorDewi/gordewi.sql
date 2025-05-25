-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Bulan Mei 2025 pada 11.15
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
-- Database: `gordewi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `id_lapangan` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `status` enum('booked','selesai','menunggu_pembatalan','belum_dibayar','cancelled') NOT NULL DEFAULT 'booked',
  `status_pembayaran` enum('DP','LUNAS') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `id_lapangan`, `id_users`, `tanggal`, `jam_mulai`, `jam_selesai`, `status`, `status_pembayaran`) VALUES
(10, 2, 2, '2025-05-25', '22:00:00', '24:00:00', 'cancelled', 'DP'),
(11, 2, 2, '2025-05-28', '19:00:00', '21:00:00', 'selesai', 'LUNAS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan`
--

CREATE TABLE `lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lapangan`
--

INSERT INTO `lapangan` (`id_lapangan`, `nama_lapangan`, `status_aktif`) VALUES
(1, 'Lapangan 1', 1),
(2, 'Lapangan 2', 1),
(3, 'Lapangan 3', 1),
(4, 'Lapangan 4', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT '../assets/user-default.jpg',
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_users`, `nama_lengkap`, `email`, `password`, `foto_profil`, `role`) VALUES
(1, 'Kevin', 'kvienphn@gmail.com', '$2y$10$lBZOJ80KVzLrmvVlnPweROufACTAlx4RNWZIHmUTZ1Wa2Sfv0wLOi', '../assets/user-default.jpg', 'admin'),
(2, 'Kevin', 'viensil@gmail.com', '$2y$10$buYctH3vbmev/r1umMK1vuacIXGCpLOXiR6.lD1aCbiJe04WnQB3y', '../assets/user-default.jpg', 'user'),
(3, 'adhwa', 'ad2@gmail.com', '$2y$10$uvyMK7Wqd.Drc2GLmNZYK.cORI/wOxijJSC.MJqAuOIwVDr2V/CwC', '../assets/user-default.jpg', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_lapangan` (`id_lapangan`),
  ADD KEY `fk_users_booking` (`id_users`);

--
-- Indeks untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id_lapangan`),
  ADD UNIQUE KEY `nama_lapangan` (`nama_lapangan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_lapangan`) REFERENCES `lapangan` (`id_lapangan`),
  ADD CONSTRAINT `fk_users_booking` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
