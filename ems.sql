-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 28, 2025 at 09:35 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ems`
--

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_general_ci NOT NULL,
  `period_type` enum('day','month','year') COLLATE utf8mb4_general_ci NOT NULL,
  `period_value` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `transaction_date` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `avatar` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `avatar`) VALUES
(2, 'Vo Nhi Anh', 'vonhianh@gmail.com', '$2y$10$aeRssUjS9VQzbyI7MzmfrO6eGn1CA5liLDBPpZgtf5dw5LoCpShnq', '2025-07-28 16:13:42', 0x363838373366363133353935385f3131345f2e6a7067),
(4, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '$2y$10$GIQSnv7urQ1oHBAPml8xBO1KwBljHjqDADnFPtblh28GRkQ59W1V6', '2025-07-28 16:21:01', 0x363838373431303739353839665f5f5f5f6e685f5f5f5f5f5f695f64695f5f5f6e2e6a7067);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;


-- insert user
INSERT INTO Users (username, email, password)
VALUES 
  ('user1', 'user1@mail.com', 'pass1'),
  ('user2', 'user2@mail.com', 'pass2'),
  ('user3', 'user3@mail.com', 'pass3'),
  ('user4', 'user4@mail.com', 'pass4'),
  ('user5', 'user5@mail.com', 'pass5'),
  ('user6', 'user6@mail.com', 'pass6'),
  ('user7', 'user7@mail.com', 'pass7'),
  ('user8', 'user8@mail.com', 'pass8'),
  ('user9', 'user9@mail.com', 'pass9'),
  ('user10', 'user10@mail.com', 'pass10');
  -- insert category
  INSERT INTO Categories (user_id, name, type)
VALUES
  -- Dành cho user_id = 2 (Vo Nhi Anh)
  (2, 'Lương chính', 'income'),
  (2, 'Thưởng hiệu suất', 'income'),
  (2, 'Ăn uống', 'expense'),
  (2, 'Đi lại', 'expense'),
  (2, 'Mua sắm', 'expense'),

  -- Dành cho user_id = 4 (Nguyễn Văn A)
  (4, 'Thu nhập freelance', 'income'),
  (4, 'Dạy học thêm', 'income'),
  (4, 'Hóa đơn điện', 'expense'),
  (4, 'Giải trí', 'expense'),
  (4, 'Tiền nhà', 'expense');

-- insert transaction
INSERT INTO Transactions (user_id, category_id, amount, description, transaction_date)
VALUES
  -- User 2 (id = 2)
  (2, 1, 15000000, 'Lương tháng 7', '2025-07-01 08:00:00'),
  (2, 2, 2000000, 'Thưởng tháng', '2025-07-05 10:00:00'),
  (2, 3, 120000, 'Ăn trưa KFC', '2025-07-07 12:00:00'),
  (2, 4, 50000, 'Grab đi làm', '2025-07-08 08:00:00'),
  (2, 5, 700000, 'Mua đồ Shopee', '2025-07-10 20:00:00'),

  -- User 4 (id = 4)
  (4, 6, 5000000, 'Dự án content', '2025-07-03 09:00:00'),
  (4, 7, 3000000, 'Gia sư tiếng Anh', '2025-07-06 15:00:00'),
  (4, 8, 900000, 'Tiền điện', '2025-07-07 18:00:00'),
  (4, 9, 200000, 'Xem phim', '2025-07-09 19:00:00'),
  (4,10, 2500000, 'Trả tiền thuê nhà', '2025-07-11 09:00:00');

-- Budget
INSERT INTO Budgets (user_id, category_id, amount, start_date, end_date)
VALUES
  -- User 2
  (2, 3, 1500000, '2025-07-01', '2025-07-31'), -- Ăn uống
  (2, 4, 1000000, '2025-07-01', '2025-07-31'), -- Đi lại
  (2, 5, 2000000, '2025-07-01', '2025-07-31'), -- Mua sắm
  (2, 1, 20000000, '2025-07-01', '2025-07-31'), -- Lương
  (2, 2, 5000000, '2025-07-01', '2025-07-31'), -- Thưởng

  -- User 4
  (4, 8, 1000000, '2025-07-01', '2025-07-31'), -- Hóa đơn điện
  (4, 9, 500000,  '2025-07-01', '2025-07-31'), -- Giải trí
  (4,10, 3000000, '2025-07-01', '2025-07-31'), -- Tiền nhà
  (4, 6, 7000000, '2025-07-01', '2025-07-31'), -- Freelance
  (4, 7, 4000000, '2025-07-01', '2025-07-31'); -- Dạy học

-- statics
INSERT INTO Statistics (user_id, type, period_type, period_value, total_amount)
VALUES
  (2, 'income', 'month', '2025-07', 17000000),   -- Lương + Thưởng
  (2, 'expense', 'month', '2025-07', 870000),    -- Ăn + Đi lại + Mua sắm

  (4, 'income', 'month', '2025-07', 8000000),    -- Freelance + Dạy học
  (4, 'expense', 'month', '2025-07', 3400000);   -- Hóa đơn + Giải trí + Nhà

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
