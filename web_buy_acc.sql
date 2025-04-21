-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2025 at 04:43 AM
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
-- Database: `web_buy_acc`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_screenshots`
--

CREATE TABLE `account_screenshots` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_screenshots`
--

INSERT INTO `account_screenshots` (`id`, `account_id`, `filename`, `created_at`) VALUES
(1, 6, '68040e9beaf0a_game_6801a4f96e078.png', '2025-04-20 03:59:07'),
(2, 6, '68040e9beb260_game_6801a50d1b136.png', '2025-04-20 03:59:07'),
(3, 6, '68040e9beb4ad_game_6801a51996c0a.png', '2025-04-20 03:59:07'),
(4, 6, '68040e9beb7f5_lol.png', '2025-04-20 03:59:07'),
(5, 6, '68040e9bebbe1_pubg.png', '2025-04-20 03:59:07'),
(6, 7, '680411eca4ab2_game_6801a51996c0a.png', '2025-04-20 04:13:16'),
(7, 7, '680411eca5085_game_6801c4a017159.png', '2025-04-20 04:13:16'),
(8, 7, '680411eca53be_game_6801c4206e885.jpg', '2025-04-20 04:13:16'),
(9, 7, '680411eca566e_game_6801c37472239.jpg', '2025-04-20 04:13:16'),
(10, 7, '680411eca58fc_game_6801d04be8998.jpg', '2025-04-20 04:13:16'),
(11, 2, '6804178cc4fc9_6801bd38e6ebd.jpg', '2025-04-20 04:37:16'),
(12, 2, '6804178cc5409_6801c4312df66.jpg', '2025-04-20 04:37:16'),
(13, 2, '6804178cc56ae_game_6801a51996c0a.png', '2025-04-20 04:37:16'),
(14, 2, '6804178cc58f1_game_6801ae35552ac.png', '2025-04-20 04:37:16'),
(15, 4, '6804216995fcc_6801c4312df66.jpg', '2025-04-20 05:19:21'),
(16, 4, '6804216996403_freefire.png', '2025-04-20 05:19:21'),
(17, 4, '6804216996670_game_6801a4f96e078.png', '2025-04-20 05:19:21'),
(18, 4, '68042169968cd_game_6801a50d1b136.png', '2025-04-20 05:19:21'),
(19, 4, '6804216996b65_game_6801a51996c0a.png', '2025-04-20 05:19:21'),
(20, 8, '68042913922a0_6801bd38e6ebd.jpg', '2025-04-20 05:52:03'),
(21, 8, '680429139264d_6801c4312df66.jpg', '2025-04-20 05:52:03'),
(22, 8, '68042913928b2_freefire.png', '2025-04-20 05:52:03'),
(23, 8, '6804291392b37_game_6801a4f96e078.png', '2025-04-20 05:52:03'),
(24, 9, '68059d41a649f_103517-1.jpg', '2025-04-21 08:20:01'),
(25, 9, '68059d41a69af_103517-2.jpg', '2025-04-21 08:20:01'),
(26, 9, '68059d41a6cb3_154898a9a566531b6a902fcda8825c12.jpg', '2025-04-21 08:20:01'),
(27, 10, '68059d7b15ddb_491922005_122129026256627548_259223036078148190_n.jpg', '2025-04-21 08:20:59'),
(28, 10, '68059d7b162a8_491939700_122129026544627548_5800794671912646260_n.jpg', '2025-04-21 08:20:59'),
(29, 10, '68059d7b16563_492394574_122129026400627548_4257219414562275761_n.jpg', '2025-04-21 08:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MOBA', 'moba', NULL, 'Multiplayer Online Battle Arena Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45'),
(2, 'FPS', 'fps', NULL, 'First Person Shooter Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45'),
(3, 'RPG', 'rpg', NULL, 'Role Playing Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45'),
(4, 'Battle Royale', 'battle-royale', NULL, 'Battle Royale Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45'),
(5, 'Sports', 'sports', NULL, 'Sports Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45'),
(6, 'Action', 'action', NULL, 'Action Games', 1, '2025-04-19 11:41:45', '2025-04-19 11:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price_range` varchar(100) DEFAULT NULL,
  `total_accounts` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `slug`, `description`, `image`, `price_range`, `total_accounts`, `status`, `created_at`, `updated_at`) VALUES
(1, 'League of Legends', 'league-of-legends', 'Game MOBA phổ biến nhất thế giới', '6804285f2d40f_lol.png', NULL, 0, 1, '2025-04-19 11:33:44', '2025-04-19 22:49:03'),
(2, 'Valorant', 'valorant', 'Game FPS 5v5 táctica từ Riot Games', '6804287ed4e45_valorant.png', NULL, 0, 1, '2025-04-19 11:33:44', '2025-04-19 22:49:34'),
(3, 'Genshin Impact', 'genshin-impact', 'Game nhập vai thế giới mở', '6804289b9363b_game_6801a4f96e078.png', NULL, 0, 1, '2025-04-19 11:33:44', '2025-04-19 22:50:03'),
(4, 'PUBG', 'pubg', 'Game Battle Royale nổi tiếng', '680428a5f0a17_pubg.png', NULL, 0, 1, '2025-04-19 11:33:44', '2025-04-19 22:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `game_accounts`
--

CREATE TABLE `game_accounts` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `game_level` int(11) DEFAULT NULL,
  `game_rank` varchar(50) DEFAULT NULL,
  `game_server` varchar(100) DEFAULT NULL,
  `special_attributes` text DEFAULT NULL,
  `status` enum('available','sold','pending') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `game_accounts`
--

INSERT INTO `game_accounts` (`id`, `game_id`, `title`, `username`, `password`, `price`, `description`, `images`, `game_level`, `game_rank`, `game_server`, `special_attributes`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tài khoản League of Legends Rank Bạc', '111111', '11', 200000.00, 'Tài khoản đã có đủ 20 tướng, nhiều skin', NULL, NULL, 'Silver', NULL, NULL, 'sold', '2025-04-19 11:56:24', '2025-04-20 15:33:29'),
(2, 1, 'Tài khoản League of Legends Rank Vàng', '1111', '1234', 350000.00, 'Tài khoản có nhiều skin hiếm, 50 tướng', NULL, NULL, 'Gold', NULL, NULL, 'sold', '2025-04-19 11:56:24', '2025-04-20 08:37:35'),
(4, 3, 'Tài khoản PUBG Level 50', 'Admin', 'gsghdhd', 400000.00, 'Tài khoản có nhiều skin súng hiếm', NULL, NULL, 'Experienced', NULL, NULL, 'sold', '2025-04-19 11:56:24', '2025-04-20 08:36:33'),
(5, 4, '', 'dongstudy', '1234', 99999.99, 'ngon keng', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-19 20:58:40', '2025-04-20 15:40:49'),
(6, 4, '', 'dongstudy', '1234', 99999999.99, 'ngon keng', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-19 20:59:07', '2025-04-20 15:40:30'),
(7, 2, '', 'hihi', '111111111111111111', 1234.00, 'ngonnn', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-19 21:13:16', '2025-04-20 15:40:21'),
(8, 4, '', 'dongstudy', 'gsghdhd', 1234567.00, 'spacce', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-19 22:52:03', '2025-04-20 15:40:11'),
(9, 1, '', 'dongstudy', '1234', 250000.00, 'acc thông tin trắng ', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-21 01:20:01', '2025-04-21 01:52:26'),
(10, 4, '', 'pupy', 'gsghdhd', 1000000.00, 'full skin', NULL, NULL, NULL, NULL, NULL, 'available', '2025-04-21 01:20:59', '2025-04-21 01:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_account_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `game_account_id`, `amount`, `status`, `payment_method`, `payment_status`, `transaction_code`, `created_at`, `updated_at`) VALUES
(1, 3, 7, 1234.00, 'pending', 'momo', 'pending', NULL, '2025-04-20 04:48:31', '2025-04-20 04:48:31'),
(2, 3, 6, 99999999.99, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 04:55:23', '2025-04-20 04:55:23'),
(3, 3, 2, 350000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 05:02:45', '2025-04-20 05:02:45'),
(4, 3, 5, 99999999.99, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 05:16:47', '2025-04-20 05:16:47'),
(5, 3, 1, 200000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 05:18:09', '2025-04-20 05:18:09'),
(6, 3, 4, 400000.00, '', 'vnpay', 'paid', 'SANDBOX_1745126799', '2025-04-20 05:26:17', '2025-04-20 05:26:39'),
(7, 3, 8, 1234567.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 05:27:53', '2025-04-20 05:27:53'),
(8, 3, 7, 1234.00, '', 'vnpay', 'paid', 'SANDBOX_1745127268', '2025-04-20 05:34:02', '2025-04-20 05:34:28'),
(9, 3, 4, 400000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 08:36:33', '2025-04-20 08:36:33'),
(10, 3, 6, 99999999.99, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 08:37:08', '2025-04-20 08:37:08'),
(11, 3, 2, 350000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 08:37:35', '2025-04-20 08:37:35'),
(12, 3, 7, 1234.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 08:41:11', '2025-04-20 08:41:11'),
(13, 3, 7, 1234.00, '', 'vnpay', 'paid', 'SANDBOX_1745139435', '2025-04-20 08:42:33', '2025-04-20 08:57:15'),
(14, 3, 6, 99999999.99, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 08:57:25', '2025-04-20 08:57:25'),
(15, 4, 8, 1234567.00, '', 'vnpay', 'paid', 'SANDBOX_1745163113', '2025-04-20 15:31:06', '2025-04-20 15:31:53'),
(16, 4, 1, 200000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 15:33:29', '2025-04-20 15:33:29'),
(17, 1, 5, 99999.99, 'pending', 'vnpay', 'pending', NULL, '2025-04-20 15:35:57', '2025-04-20 15:35:57'),
(18, 1, 9, 250000.00, 'pending', 'vnpay', 'pending', NULL, '2025-04-21 01:51:50', '2025-04-21 01:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','withdraw','purchase','refund') NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `type`, `status`, `description`, `created_at`) VALUES
(1, 3, 1234.00, 'purchase', 'pending', 'Payment for order #13 via vnpay', '2025-04-20 08:57:07'),
(2, 3, 99999999.99, 'purchase', 'pending', 'Payment for order #14 via vnpay', '2025-04-20 08:57:28'),
(3, 3, 99999999.99, 'purchase', 'pending', 'Payment for order #14 via vnpay', '2025-04-20 08:58:05'),
(4, 3, 99999999.99, 'purchase', 'pending', 'Payment for order #14 via vnpay', '2025-04-20 08:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `phone`, `balance`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator', NULL, 0.00, NULL, 'admin', 1, '2025-04-19 11:33:44', '2025-04-19 11:33:44'),
(3, 'dong@gmail.com', '$2y$10$VDKwDU59ti.l9.6qo8peBebchHasPIAqfEy8XRpMnaApQcycu3rR2', 'dong@gmail.com', 'nnnn', '1234', 0.00, NULL, 'user', 1, '2025-04-19 22:13:33', '2025-04-19 22:13:33'),
(4, 'hihi', '$2y$10$a8X2lv.6rbeaNqg1dUKZCuleYR3pQwbmrZaFlWFteK4jxGHKANPAa', 'linhq0009@gmail.com', 'Nguyễn Thành Đồng', '0362656802', 0.00, NULL, 'user', 1, '2025-04-20 15:30:44', '2025-04-20 15:30:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_screenshots`
--
ALTER TABLE `account_screenshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `game_accounts`
--
ALTER TABLE `game_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_account_id` (`game_account_id`);

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
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_screenshots`
--
ALTER TABLE `account_screenshots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `game_accounts`
--
ALTER TABLE `game_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game_accounts`
--
ALTER TABLE `game_accounts`
  ADD CONSTRAINT `game_accounts_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`game_account_id`) REFERENCES `game_accounts` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
