-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 09:25 AM
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
-- Database: `account_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `basic_description` text DEFAULT NULL,
  `detailed_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `images` text NOT NULL DEFAULT '{}' COMMENT 'JSON structure for images',
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','sold','pending') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `game_id`, `seller_id`, `title`, `username`, `password`, `basic_description`, `detailed_description`, `description`, `images`, `price`, `status`, `created_at`) VALUES
(1, 1, 2, 'ads', '0356166587', '1234', 'ddddddd', '', '', '{\"main\":\"\\/public\\/img\\/accounts\\/main_6801347b2cf12.jpg\",\"sub\":[\"\\/public\\/img\\/accounts\\/sub_6801347b2d4b2.jpg\",\"\\/public\\/img\\/accounts\\/sub_6801347b2d738.jpg\",\"\\/public\\/img\\/accounts\\/sub_6801347b2da3e.jpg\"]}', 222222.00, 'available', '2025-04-17 17:03:55'),
(3, 3, 2, 'fad', 'v', 'sdddddddddddddd', 'aaaa', 'aaa', '', '{\"main\":\"\\/public\\/img\\/accounts\\/main_68019c89e4f23.jpg\",\"sub\":[\"\\/public\\/img\\/accounts\\/sub_68019c89e5d42.jpg\",\"\\/public\\/img\\/accounts\\/sub_68019c89e6491.jpg\",\"\\/public\\/img\\/accounts\\/sub_68019c89e683b.jpg\"]}', 99999999.99, 'available', '2025-04-18 00:27:53');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `slug`, `short_description`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'League of Legends', 'league-of-legends', 'Game MOBA ph? bi?n nh?t th? ', 'Game MOBA ph? bi?n nh?t th? gi?i', '/public/images/games/game_6801d04be8998.jpg', '2025-04-17 16:42:10', '2025-04-18 04:08:43'),
(2, 'Genshin Impact', 'genshin-impacto', 'Game nhập vs', 'ub', '/public/images/games/game_6801ae35552ac.png', '2025-04-17 16:42:10', '2025-04-18 01:43:17'),
(3, 'PUBG', 'pubg', 'Game battle royale n?i ti?ng', 'Game battle royale n?i ti?ng', '/public/images/games/game_6801a50d1b136.png', '2025-04-17 16:42:10', '2025-04-18 01:04:13'),
(4, 'Valorant', 'valorant', 'Game FPS chi?n thu?t t? Riot Games', 'Game FPS chi?n thu?t t? Riot Games', '/public/images/games/game_6801a51996c0a.png', '2025-04-17 16:42:10', '2025-04-18 01:04:25'),
(7, 'deptrai', 'deptrai', 'hehe', 'gugu', '/public/images/games/game_6801b71472e0f.jpg', '2025-04-18 01:52:16', '2025-04-18 02:21:08'),
(8, 'aa', 'aa', 'ad', 'â', '/uploads/games/6801b4e24fd3d.jpg', '2025-04-18 02:11:46', '2025-04-18 02:11:46'),
(12, 'deptraix2', 'deptraix2', 'â', 'ss', '/public/images/games/game_6801c4206e885.jpg', '2025-04-18 02:47:20', '2025-04-18 03:16:48'),
(13, 'sàg', 'sag', 'sg', 'gdfg', '/public/images/games/game_6801c4a017159.png', '2025-04-18 03:17:05', '2025-04-18 03:18:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','completed','cancelled','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `bonus` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) GENERATED ALWAYS AS (`amount` + `bonus`) STORED,
  `payment_method` enum('bank','momo','zalopay','card') NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `transaction_code` varchar(255) DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT '/images/avatars/default.png',
  `status` enum('active','banned') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_token_expiry`, `avatar`, `status`) VALUES
(1, '01 farm', 'accking000001@gmail.com', '$2y$10$8YRsqEKZT8MgmcM94Nn2tuRPlY6xNffl1MXHFtMiuXvCef.PZu5jy', 'user', '2025-04-17 04:58:45', NULL, NULL, '/images/avatars/default.png', 'active'),
(2, 'gigi', 'admin@gmail.com', '$2y$10$biXeCdGRQG0F.C.MmCrmtO/lU3SpYM768riK/doXnZ9DsWYc/4Y.C', 'admin', '2025-04-17 05:04:05', '0d6593b3123da454b3d3f0bb46ca550b1630169d6a0c73e68683fe394dac3a4b', '2025-04-17 11:22:43', '/images/avatars/default.png', 'active'),
(3, 'dong admin', 'dongad@gmail.com', '$2y$10$ARqarMyRbW20jFdvpZp9IuylOBP5srelfNgwt1/k25xjjuF1o2O6q', 'user', '2025-04-17 06:06:03', NULL, NULL, '/images/avatars/default.png', 'active'),
(4, 'Admi', 'a@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-04-17 06:33:09', NULL, NULL, '/images/avatars/default.png', 'active'),
(12, 'dongstu', 'dongstudy@gmail.com', '$2y$10$lVef5TUS.k.o5OBLpc9EPuFuCya2k1SlqlaPVvJ1lXOIELh3/9cPi', NULL, '2025-04-17 09:07:01', NULL, NULL, '/images/avatars/default.png', 'active'),
(15, 'hihi', 'linhq0009@gmail.com', '$2y$10$BE9tjdCvgAOU.5t.IP1Ebu2af/oiFmA6NvCSlrHpovAS3AfnX7R6u', 'user', '2025-04-18 00:11:27', NULL, NULL, '/images/avatars/default.png', 'active'),
(16, 'dong@gmail.com', 'dong@gmail.com', '$2y$10$uUE0xqXZ5A95sDa6dGBERu3b10ZigCBGP0qtHQjJWamIW3BMbJab2', 'user', '2025-04-18 03:22:02', NULL, NULL, '/images/avatars/default.png', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
