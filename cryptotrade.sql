-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 12:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cryptotrade`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `target_price` decimal(15,2) NOT NULL,
  `action` enum('sell','buy') NOT NULL,
  `status` enum('active','triggered','cancelled') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `user_id`, `crypto_id`, `target_price`, `action`, `status`, `created_at`) VALUES
(16, 10, 1, 85000.00, 'buy', 'triggered', '2025-04-12 12:52:27'),
(17, 10, 1, 83000.00, 'buy', 'triggered', '2025-04-12 12:52:52'),
(18, 10, 1, 83000.00, 'buy', 'triggered', '2025-04-12 13:00:07'),
(19, 10, 1, 85000.00, 'sell', 'triggered', '2025-04-12 13:59:23'),
(20, 10, 1, 83000.00, 'buy', 'triggered', '2025-04-12 14:02:14'),
(21, 10, 1, 85000.00, 'buy', 'triggered', '2025-04-12 17:29:08'),
(22, 10, 1, 82500.00, 'buy', 'triggered', '2025-04-12 17:31:31'),
(23, 10, 4, 2.35, 'buy', 'triggered', '2025-04-12 17:32:15'),
(24, 10, 1, 83000.00, 'buy', 'triggered', '2025-04-12 17:37:52'),
(25, 10, 1, 83500.00, 'buy', 'triggered', '2025-04-12 17:40:24'),
(26, 10, 1, 84000.00, 'sell', 'triggered', '2025-04-12 17:40:43'),
(27, 11, 1, 83500.00, 'buy', 'triggered', '2025-04-12 17:47:04'),
(28, 11, 1, 83500.00, 'buy', 'triggered', '2025-04-12 17:48:10'),
(29, 11, 1, 83500.00, 'buy', 'triggered', '2025-04-12 17:49:01'),
(30, 11, 1, 83500.00, 'buy', 'triggered', '2025-04-12 17:49:19'),
(31, 10, 1, 83500.00, 'buy', 'triggered', '2025-04-13 17:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `cryptos`
--

CREATE TABLE `cryptos` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `initial_price` decimal(15,2) NOT NULL,
  `volatility` enum('low','medium','high') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cryptos`
--

INSERT INTO `cryptos` (`id`, `name`, `symbol`, `initial_price`, `volatility`, `created_at`, `slug`) VALUES
(1, 'Bitcoin', 'BTC', 84000.00, 'medium', '2025-03-16 14:20:38', 'bitcoin'),
(2, 'Ethereum', 'ETH', 1900.00, 'high', '2025-03-16 14:20:38', ''),
(3, 'Litecoin', 'LTC', 93.00, 'low', '2025-03-16 14:20:38', ''),
(4, 'Ripple', 'XRP', 2.40, 'medium', '2025-03-16 14:20:38', ''),
(5, 'Dogecoin', 'DOGE', 0.17, 'high', '2025-03-16 14:20:38', ''),
(6, 'Tether USD', 'USDT', 1.00, 'low', '2025-03-19 01:12:45', ''),
(7, 'PEPE', 'PEPE', 0.50, 'high', '2025-03-19 01:21:27', ''),
(8, 'Solana', 'SOL', 129.80, 'medium', '2025-04-12 13:38:49', 'solana'),
(10, 'Cardano', 'ADA', 0.65, 'medium', '2025-04-12 13:46:59', ''),
(12, 'Tron', 'TRX', 0.25, 'high', '2025-04-13 23:43:23', '');

-- --------------------------------------------------------

--
-- Table structure for table `crypto_prices`
--

CREATE TABLE `crypto_prices` (
  `id` int(11) NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `recorded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `amount`, `status`, `transaction_id`, `created_at`) VALUES
(11, 10, 1000.00, 'completed', NULL, '2025-04-03 23:37:09'),
(12, 10, 100.00, 'completed', NULL, '2025-04-07 00:32:14'),
(13, 10, 1000.00, 'completed', NULL, '2025-04-12 02:52:59'),
(14, 10, 799.00, 'pending', NULL, '2025-04-12 12:51:58'),
(15, 10, 799.00, 'pending', NULL, '2025-04-12 12:52:00'),
(16, 10, 10000.00, 'completed', NULL, '2025-04-12 17:43:00'),
(17, 10, 93934.00, 'pending', NULL, '2025-04-12 17:43:35'),
(18, 11, 1000.00, 'completed', NULL, '2025-04-12 17:45:50'),
(19, 10, 100000.00, 'completed', NULL, '2025-04-12 22:01:00'),
(20, 10, 10000000000.00, 'pending', NULL, '2025-04-13 21:34:10'),
(21, 10, 100000000.00, 'pending', NULL, '2025-04-13 21:34:20'),
(22, 10, 999999.00, 'completed', NULL, '2025-04-13 21:34:29'),
(23, 11, 1000.00, 'completed', NULL, '2025-04-13 21:43:44'),
(24, 10, 1000.00, 'completed', NULL, '2025-04-13 23:44:39'),
(25, 10, 1000.00, 'completed', NULL, '2025-04-16 18:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `stop_losses`
--

CREATE TABLE `stop_losses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `target_price` decimal(18,8) NOT NULL,
  `amount` decimal(16,8) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stop_losses`
--

INSERT INTO `stop_losses` (`id`, `user_id`, `crypto_id`, `target_price`, `amount`, `status`, `created_at`) VALUES
(16, 10, 8, 128.50000000, 1.50000000, 'triggered', '2025-04-13 19:12:53'),
(17, 10, 8, 128.50000000, 0.06500000, 'triggered', '2025-04-14 03:39:55'),
(18, 10, 10, 0.64000000, 1538.46150000, 'triggered', '2025-04-14 03:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `type` enum('buy','sell') NOT NULL,
  `amount` decimal(15,6) NOT NULL,
  `price_at_transaction` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `crypto_id`, `type`, `amount`, `price_at_transaction`, `created_at`) VALUES
(27, 10, 7, 'buy', 2083.333333, 0.48, '2025-04-03 23:43:41'),
(28, 10, 1, 'buy', 0.000121, 82740.00, '2025-04-04 00:36:44'),
(29, 10, 1, 'buy', 0.000118, 84949.20, '2025-04-04 00:36:46'),
(30, 10, 1, 'buy', 0.001209, 82731.60, '2025-04-07 00:32:52'),
(31, 10, 1, 'buy', 0.011723, 85302.00, '2025-04-12 17:39:21'),
(32, 10, 10, 'buy', 153.846154, 0.65, '2025-04-12 17:43:56'),
(33, 11, 1, 'buy', 0.012106, 82605.60, '2025-04-12 17:46:20'),
(34, 10, 1, 'buy', 0.006006, 83244.00, '2025-04-12 21:44:26'),
(35, 10, 1, 'buy', 0.059204, 84453.60, '2025-04-12 21:44:45'),
(36, 10, 1, 'buy', 0.001171, 85402.80, '2025-04-12 21:52:09'),
(37, 10, 1, 'buy', 0.011775, 84924.00, '2025-04-12 22:01:39'),
(38, 10, 8, 'buy', 1.565558, 127.75, '2025-04-12 22:02:32'),
(39, 10, 1, 'buy', 0.001169, 85520.40, '2025-04-12 22:02:54'),
(40, 10, 1, 'sell', 0.012944, 82345.20, '2025-04-13 02:06:53'),
(41, 10, 8, 'sell', 1.500000, 128.37, '2025-04-13 15:12:58'),
(42, 11, 1, 'buy', 0.001214, 82404.00, '2025-04-13 21:44:17'),
(43, 10, 8, 'sell', 0.065000, 127.89, '2025-04-13 23:39:57'),
(44, 10, 12, 'buy', 4166.666667, 0.24, '2025-04-13 23:43:44'),
(45, 10, 10, 'buy', 1538.461538, 0.65, '2025-04-13 23:45:33'),
(46, 10, 10, 'sell', 1538.461500, 0.64, '2025-04-13 23:46:24'),
(47, 10, 1, 'buy', 0.001179, 84814.80, '2025-04-16 17:52:40'),
(48, 10, 1, 'sell', 0.001000, 83193.60, '2025-04-16 17:58:55'),
(49, 10, 7, 'buy', 1666.666667, 0.48, '2025-04-16 17:59:40'),
(50, 10, 1, 'buy', 0.012084, 82756.80, '2025-04-16 18:09:23'),
(51, 10, 1, 'sell', 0.001200, 84705.60, '2025-04-16 18:12:34'),
(52, 10, 5, 'buy', 588.235294, 0.17, '2025-04-16 18:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_limits`
--

CREATE TABLE `transaction_limits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `max_transactions_per_day` int(11) NOT NULL DEFAULT 10,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_limits`
--

INSERT INTO `transaction_limits` (`id`, `user_id`, `max_transactions_per_day`, `created_at`) VALUES
(15, 10, 10000, '2025-04-04 00:36:37'),
(17, 11, 1000, '2025-04-12 02:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `invite_token` varchar(255) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `reset_2fa_request` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `invite_token`, `two_factor_enabled`, `created_at`, `two_factor_secret`, `reset_2fa_request`) VALUES
(10, 'saf', 'saf@email.com', '$2y$10$u1rX74b0018ja6HML.ROx.qXOhvbh3Xot2hPitKmXwP/hp7hhB5Eu', 'admin', NULL, 1, '2025-04-03 03:56:16', 'RDGWO7BOSNW7DL3C', 0),
(11, 'taf', 'taf@email.com', '$2y$10$9ZHtm3wFw4hjf1i/sG.BoOvVXh5QT1hfAc3OQFvWqtn4NizDMcQhm', 'user', NULL, 1, '2025-04-03 03:57:42', 'UM6T5HZT5KQP4W7S', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `balance` decimal(15,6) NOT NULL DEFAULT 0.000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `crypto_id`, `balance`) VALUES
(118, 10, 6, 1000.000000),
(119, 10, 6, -1000.000000),
(120, 10, 1, 0.012084),
(121, 10, 1, -0.001200),
(122, 10, 6, 101.646720),
(123, 10, 6, -100.000000),
(124, 10, 5, 588.235294);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `crypto_id` (`crypto_id`);

--
-- Indexes for table `cryptos`
--
ALTER TABLE `cryptos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `symbol` (`symbol`);

--
-- Indexes for table `crypto_prices`
--
ALTER TABLE `crypto_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crypto_id` (`crypto_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stop_losses`
--
ALTER TABLE `stop_losses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `crypto_id` (`crypto_id`);

--
-- Indexes for table `transaction_limits`
--
ALTER TABLE `transaction_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `invite_token` (`invite_token`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `crypto_id` (`crypto_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `cryptos`
--
ALTER TABLE `cryptos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `crypto_prices`
--
ALTER TABLE `crypto_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stop_losses`
--
ALTER TABLE `stop_losses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `transaction_limits`
--
ALTER TABLE `transaction_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`crypto_id`) REFERENCES `cryptos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crypto_prices`
--
ALTER TABLE `crypto_prices`
  ADD CONSTRAINT `crypto_prices_ibfk_1` FOREIGN KEY (`crypto_id`) REFERENCES `cryptos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`crypto_id`) REFERENCES `cryptos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_limits`
--
ALTER TABLE `transaction_limits`
  ADD CONSTRAINT `transaction_limits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wallets_ibfk_2` FOREIGN KEY (`crypto_id`) REFERENCES `cryptos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
