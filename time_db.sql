-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 26 Sep 2024 pada 08.10
-- Versi server: 8.0.39
-- Versi PHP: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prabowo_time`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `config`
--

CREATE TABLE `config` (
  `id` int NOT NULL,
  `adminWallet` text COLLATE utf8mb4_general_ci NOT NULL,
  `linkTMA` text COLLATE utf8mb4_general_ci NOT NULL,
  `linkHost` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `config`
--

INSERT INTO `config` (`id`, `adminWallet`, `linkTMA`, `linkHost`) VALUES
(1, 'UQDNzuIbCk8nAB6DZjDWH2oBjYrrV7DPECrLPWJZXvOAFu5J', 'https://t.me/timetelegram/', 'http://localhost/upwork_timer/');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history`
--

CREATE TABLE `history` (
  `id` int NOT NULL,
  `telegram_id` bigint NOT NULL,
  `type` enum('Task','Withdraw','Upgrade','Invite') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `hash` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Pending','Completed','Failed') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `history`
--

INSERT INTO `history` (`id`, `telegram_id`, `type`, `amount`, `hash`, `address`, `status`, `created_at`) VALUES
(1, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-19 16:41:58'),
(2, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-20 08:48:11'),
(3, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:11:35'),
(4, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:12:47'),
(5, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:13:51'),
(6, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:14:34'),
(7, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:15:42'),
(8, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:15:46'),
(9, 12345, 'Upgrade', 2.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:15:49'),
(10, 12345, 'Upgrade', 2.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:15:56'),
(11, 12345, 'Upgrade', 3.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:16:07'),
(12, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-23 18:28:54'),
(13, 12345, 'Upgrade', 1.00, 'tes02488ashf', NULL, 'Completed', '2024-09-24 13:48:39'),
(14, 12345, 'Upgrade', 1.00, '7f0b8ad3f8a53690772abc41e7c634f22c9daa6cc51c0dd81e231b6cd86daee9', NULL, 'Completed', '2024-09-25 18:11:33'),
(16, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-25 18:35:46'),
(17, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-25 18:50:56'),
(19, 12345, 'Invite', 1.00, NULL, NULL, 'Completed', '2024-09-26 05:51:09'),
(20, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-26 05:51:09'),
(21, 12345, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-26 05:51:36'),
(22, 6260583474, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-26 08:03:31'),
(23, 1972747570, 'Task', 1.00, NULL, NULL, 'Completed', '2024-09-26 08:05:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `referral`
--

CREATE TABLE `referral` (
  `id` int NOT NULL,
  `telegram_id` bigint NOT NULL,
  `referral_id` bigint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `referral`
--

INSERT INTO `referral` (`id`, `telegram_id`, `referral_id`, `created_at`) VALUES
(9, 6260583474, 12345, '2024-09-26 05:51:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link` text COLLATE utf8mb4_general_ci NOT NULL,
  `reward` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `link`, `reward`, `created_at`) VALUES
(1, 'Join our Telegram group', 'https://t.me/group', 1, '2024-09-19 16:10:49'),
(2, 'Join our Telegram channel', 'https://t.me/channel', 1, '2024-09-19 16:10:49'),
(3, 'Invite first friend', '', 1, '2024-09-19 16:11:58'),
(4, 'Invite 5 friends', '', 2, '2024-09-19 16:11:58'),
(5, 'Follow us on X', 'https://t.me/x', 1, '2024-09-19 16:12:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `telegram_id` bigint NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` text COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` text COLLATE utf8mb4_general_ci NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seconds` int DEFAULT '0',
  `minutes` int DEFAULT '0',
  `hours` int DEFAULT '0',
  `days` int DEFAULT '0',
  `speed` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `telegram_id`, `username`, `firstname`, `lastname`, `start_time`, `seconds`, `minutes`, `hours`, `days`, `speed`) VALUES
(1, 12345, 'admin', 'admin', 'time', '2024-09-22 12:14:18', 39, 4, 19, 9, 51),
(12, 6260583474, 'if_else_kun', 'If', 'Else', '2024-09-26 05:51:09', 1, 20, 1, 0, 1),
(13, 1972747570, 'shagitz_san', 'Super G', ' ', '2024-09-26 08:05:01', 51, 5, 1, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_task`
--

CREATE TABLE `user_task` (
  `id` int NOT NULL,
  `telegram_id` bigint NOT NULL,
  `task_id` int NOT NULL,
  `status` enum('false','complete','claim') COLLATE utf8mb4_general_ci DEFAULT 'false',
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `claimed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_task`
--

INSERT INTO `user_task` (`id`, `telegram_id`, `task_id`, `status`, `completed_at`, `claimed_at`) VALUES
(2, 12345, 1, 'claim', '2024-09-19 16:29:21', '2024-09-19 16:33:40'),
(4, 12345, 5, 'claim', '2024-09-20 08:48:01', '2024-09-20 08:48:11'),
(9, 12345, 3, 'claim', '2024-09-26 05:51:09', '2024-09-26 05:51:36'),
(10, 6260583474, 1, 'claim', '2024-09-26 07:53:06', '2024-09-26 08:03:31'),
(11, 1972747570, 1, 'claim', '2024-09-26 08:05:35', '2024-09-26 08:05:42'),
(12, 1972747570, 5, 'complete', '2024-09-26 08:05:49', '2024-09-26 08:05:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `referral`
--
ALTER TABLE `referral`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_task`
--
ALTER TABLE `user_task`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `config`
--
ALTER TABLE `config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `history`
--
ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `referral`
--
ALTER TABLE `referral`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `user_task`
--
ALTER TABLE `user_task`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
