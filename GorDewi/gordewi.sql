-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Bulan Mei 2025 pada 11.47
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
  `status` enum('booked','selesai','cancelled','pending_cancel') NOT NULL DEFAULT 'booked',
  `status_pembayaran` enum('DP','LUNAS') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `id_lapangan`, `id_users`, `tanggal`, `jam_mulai`, `jam_selesai`, `status`, `status_pembayaran`) VALUES
(1, 1, 1, '2025-05-08', '18:45:00', '20:46:00', 'selesai', 'DP'),
(2, 1, 1, '2025-05-09', '07:00:00', '10:00:00', 'selesai', 'DP'),
(3, 1, 1, '2025-05-15', '09:00:00', '11:00:00', 'cancelled', 'DP'),
(4, 2, 1, '2025-05-15', '08:00:00', '10:00:00', 'cancelled', 'DP'),
(6, 1, 1, '2025-05-23', '08:00:00', '10:00:00', 'booked', 'DP'),
(7, 2, 1, '2025-05-23', '08:00:00', '10:00:00', 'cancelled', 'DP'),
(8, 2, 8, '2025-05-23', '22:00:00', '24:00:00', 'booked', 'LUNAS'),
(9, 1, 11, '2025-05-23', '22:00:00', '24:00:00', 'cancelled', 'LUNAS');

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
(3, 'Lapangan 3', 0);

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
(1, 'Kevin Victorian', 'kevinpohan11@gmail.com', '12345', '../assets/user-default.jpg', 'user'),
(2, 'Kai', 'kvienphn@gmail.com', '123', '../assets/user-default.jpg', 'admin'),
(3, 'Ryan', 'ryan@gmail.com', '123', '../assets/user-default.jpg', 'admin'),
(4, 'Adhwa', 'ad2@gmail.com', '123', '../assets/user-default.jpg', 'admin'),
(5, 'Reffa', 'reffa@gmail.com', '123', '../assets/user-default.jpg', 'admin'),
(8, 'Gideon', 'gidi@gmail.com', '111', '../assets/user-default.jpg', 'user'),
(9, 'Joko', 'joko@gmail.com', '111', '../assets/user-default.jpg', 'admin'),
(10, 'Leona', 'leo@gmail.com', '111', '../assets/user-default.jpg', 'user'),
(11, 'Burhan', 'duls@gmail.com', '123', '../assets/user-default.jpg', 'user'),
(12, 'Crys', 'crys@gmail.com', '12345', '../assets/user-default.jpg', 'admin');

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
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
