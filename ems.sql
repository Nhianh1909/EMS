-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 06:34 PM
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
-- Database: `ems`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddGoal` (IN `p_user_id` INT, IN `p_category_id` INT, IN `p_name` VARCHAR(100), IN `p_target` DECIMAL(15,2), IN `p_deadline` DATE, IN `p_icon` VARCHAR(50), IN `p_color` VARCHAR(20))   BEGIN
    INSERT INTO goals (user_id, category_id, name, target, deadline, icon, color)
    VALUES (p_user_id, p_category_id, p_name, p_target, p_deadline, p_icon, p_color);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddTransaction` (IN `p_user_id` INT, IN `p_category_id` INT, IN `p_amount` DECIMAL(12,2), IN `p_description` TEXT, IN `p_transaction_date` DATETIME)   BEGIN
    DECLARE v_type ENUM('income', 'expense');
    DECLARE v_budget_id INT;
    DECLARE v_budget_limit DECIMAL(12,2);
    DECLARE v_total_spent DECIMAL(12,2);

    SELECT type INTO v_type
    FROM categories
    WHERE id = p_category_id;

    IF v_type = 'expense' THEN
        SELECT id, amount INTO v_budget_id, v_budget_limit
        FROM budgets
        WHERE category_id = p_category_id
          AND user_id = p_user_id
          AND p_transaction_date BETWEEN start_date AND end_date
        LIMIT 1;

        IF v_budget_id IS NULL THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Không có ngân sách cho danh mục này trong thời gian hiện tại.';
        END IF;

        SELECT COALESCE(SUM(amount), 0) INTO v_total_spent
        FROM transactions
        WHERE category_id = p_category_id
          AND user_id = p_user_id
          AND transaction_date BETWEEN (
              SELECT start_date FROM budgets WHERE id = v_budget_id
          ) AND (
              SELECT end_date FROM budgets WHERE id = v_budget_id
          );

        IF (v_total_spent + p_amount) > v_budget_limit THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Chi tiêu vượt quá ngân sách cho danh mục này!';
        END IF;
    END IF;

    INSERT INTO transactions (user_id, category_id, amount, description, transaction_date)
    VALUES (p_user_id, p_category_id, p_amount, p_description, p_transaction_date);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserGoals` (IN `p_user_id` INT)   BEGIN
    SELECT 
        g.*,
        ROUND((g.saved / g.target) * 100, 2) AS percentage
    FROM goals g
    WHERE g.user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateGoal` (IN `p_id` INT, IN `p_name` VARCHAR(100), IN `p_target` DECIMAL(15,2), IN `p_deadline` DATE, IN `p_icon` VARCHAR(50), IN `p_color` VARCHAR(20))   BEGIN
    UPDATE goals
    SET name = p_name,
        target = p_target,
        deadline = p_deadline,
        icon = p_icon,
        color = p_color
    WHERE id = p_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category_id`, `amount`, `start_date`, `end_date`, `created_at`) VALUES
(1, 2, 3, 1500000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(3, 2, 5, 2000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(4, 2, 1, 20000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(5, 2, 2, 5000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(6, 4, 8, 1000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(7, 4, 9, 500000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(8, 4, 10, 3000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(9, 4, 6, 7000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(10, 4, 7, 4000000.00, '2025-07-01', '2025-07-31', '2025-07-28 13:33:33'),
(11, 4, 8, 500000000.00, '2025-08-06', '2025-09-06', '2025-08-06 13:31:26'),
(12, 4, 9, 10000000.00, '2025-08-06', '2025-09-06', '2025-08-06 13:35:05'),
(13, 4, 10, 10000000.00, '2025-08-06', '2025-09-13', '2025-08-06 13:40:20'),
(14, 2, 4, 1000000.00, '2025-08-06', '2025-08-30', '2025-08-06 13:42:53');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `type`, `is_deleted`, `created_at`) VALUES
(1, 2, 'Lương chính', 'income', 0, '2025-07-28 13:33:33'),
(2, 2, 'Thưởng hiệu suất', 'income', 0, '2025-07-28 13:33:33'),
(3, 2, 'Ăn uống', 'expense', 0, '2025-07-28 13:33:33'),
(4, 2, 'Đi lại', 'expense', 0, '2025-07-28 13:33:33'),
(5, 2, 'Mua sắm', 'expense', 0, '2025-07-28 13:33:33'),
(6, 4, 'Thu nhập freelance', 'income', 0, '2025-07-28 13:33:33'),
(7, 4, 'Dạy học thêm', 'income', 0, '2025-07-28 13:33:33'),
(8, 4, 'Hóa đơn điện', 'expense', 0, '2025-07-28 13:33:33'),
(9, 4, 'Giải trí', 'expense', 0, '2025-07-28 13:33:33'),
(10, 4, 'Tiền nhà', 'expense', 0, '2025-07-28 13:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `target` decimal(15,2) NOT NULL,
  `saved` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deadline` date DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'bx-target-lock',
  `color` varchar(20) DEFAULT '#1a202c',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `category_id`, `name`, `target`, `saved`, `deadline`, `icon`, `color`, `created_at`) VALUES
(1, 2, 5, 'Du lịch Nhật Bản', 20000000.00, 0.00, '2025-12-31', 'bxs-plane-alt', '#ef4444', '2025-08-08 11:04:16'),
(2, 2, 7, 'Mua MacBook', 35000000.00, 0.00, '2025-10-01', 'bxl-apple', '#1a202c', '2025-08-08 11:04:16'),
(3, 2, 8, 'Quỹ khẩn cấp', 10000000.00, 0.00, NULL, 'bx-money', '#15803d', '2025-08-08 11:04:16'),
(5, 2, 1, 'Mua nhà ', 100000000.00, -15000000.00, '2025-08-29', 'bx-target-lock', '#3498db', '2025-08-08 16:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `period_type` enum('day','month','year') NOT NULL,
  `period_value` varchar(20) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `user_id`, `type`, `period_type`, `period_value`, `total_amount`, `created_at`) VALUES
(1, 2, 'income', 'month', '2025-07', 2000000.00, '2025-07-28 13:33:33'),
(2, 2, 'expense', 'month', '2025-07', 870000.00, '2025-07-28 13:33:33'),
(3, 4, 'income', 'month', '2025-07', 8000000.00, '2025-07-28 13:33:33'),
(4, 4, 'expense', 'month', '2025-07', 3400000.00, '2025-07-28 13:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `amount`, `description`, `transaction_date`, `created_at`) VALUES
(2, 2, 2, 2000000.00, 'Thưởng tháng', '2025-07-05 10:00:00', '2025-07-28 13:33:33'),
(3, 2, 3, 120000.00, 'Ăn trưa KFC', '2025-07-07 12:00:00', '2025-07-28 13:33:33'),
(4, 2, 4, 50000.00, 'Grab đi làm', '2025-07-08 08:00:00', '2025-07-28 13:33:33'),
(5, 2, 5, 700000.00, 'Mua đồ Shopee', '2025-07-10 20:00:00', '2025-07-28 13:33:33'),
(6, 4, 6, 5000000.00, 'Dự án content', '2025-07-03 09:00:00', '2025-07-28 13:33:33'),
(7, 4, 7, 3000000.00, 'Gia sư tiếng Anh', '2025-07-06 15:00:00', '2025-07-28 13:33:33'),
(8, 4, 8, 900000.00, 'Tiền điện', '2025-07-07 18:00:00', '2025-07-28 13:33:33'),
(9, 4, 9, 200000.00, 'Xem phim', '2025-07-09 19:00:00', '2025-07-28 13:33:33'),
(10, 4, 10, 2500000.00, 'Trả tiền thuê nhà', '2025-07-11 09:00:00', '2025-07-28 13:33:33'),
(11, 4, 8, -25000.00, 'Đóng tiền điện', '2025-07-28 00:00:00', '2025-07-28 22:06:44'),
(12, 4, 7, 30000000.00, 'Dậy bé lớp 10', '2025-07-28 00:00:00', '2025-07-28 22:08:03'),
(13, 4, 8, 400000.00, '', '2025-08-06 00:00:00', '2025-08-06 13:33:26'),
(14, 4, 9, 1000000.00, '', '2025-08-14 00:00:00', '2025-08-06 13:35:21'),
(15, 4, 10, 5000000.00, '', '2025-08-06 00:00:00', '2025-08-06 13:40:38'),
(16, 2, 4, 500000.00, '', '2025-08-06 00:00:00', '2025-08-06 13:43:04');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `after_delete_transaction` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
  DECLARE v_type VARCHAR(20);
  DECLARE v_period VARCHAR(20);
  DECLARE v_budget DECIMAL(12,2);
  DECLARE v_start DATE;
  DECLARE v_end DATE;
  DECLARE v_spent DECIMAL(12,2);

  SELECT type INTO v_type FROM categories WHERE id = OLD.category_id;
  SET v_period = DATE_FORMAT(OLD.transaction_date, '%Y-%m');

  UPDATE statistics
  SET total_amount = total_amount - OLD.amount
  WHERE user_id = OLD.user_id AND type = v_type
    AND period_type = 'month' AND period_value = v_period;

  DELETE FROM statistics
  WHERE user_id = OLD.user_id AND type = v_type
    AND period_type = 'month' AND period_value = v_period
    AND total_amount <= 0;

  IF v_type = 'expense' THEN
    SELECT amount, start_date, end_date INTO v_budget, v_start, v_end
    FROM budgets
    WHERE user_id = OLD.user_id AND category_id = OLD.category_id
      AND start_date <= OLD.transaction_date AND end_date >= OLD.transaction_date
    LIMIT 1;

    IF v_budget IS NOT NULL THEN
      SELECT COALESCE(SUM(amount), 0) INTO v_spent
      FROM transactions
      WHERE user_id = OLD.user_id AND category_id = OLD.category_id
        AND transaction_date BETWEEN v_start AND v_end;
    END IF;
  END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_transaction` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
  DECLARE v_type VARCHAR(20);
  DECLARE v_period VARCHAR(20);
  DECLARE v_budget DECIMAL(12,2);
  DECLARE v_start DATE;
  DECLARE v_end DATE;
  DECLARE v_spent DECIMAL(12,2);

  SELECT type INTO v_type FROM categories WHERE id = OLD.category_id;
  SET v_period = DATE_FORMAT(OLD.transaction_date, '%Y-%m');

  UPDATE statistics
  SET total_amount = total_amount - OLD.amount
  WHERE user_id = OLD.user_id AND type = v_type
    AND period_type = 'month' AND period_value = v_period;

  DELETE FROM statistics
  WHERE user_id = OLD.user_id AND type = v_type
    AND period_type = 'month' AND period_value = v_period
    AND total_amount <= 0;

  IF v_type = 'expense' THEN
    SELECT amount, start_date, end_date INTO v_budget, v_start, v_end
    FROM budgets
    WHERE user_id = OLD.user_id AND category_id = OLD.category_id
      AND start_date <= OLD.transaction_date AND end_date >= OLD.transaction_date
    LIMIT 1;

    IF v_budget IS NOT NULL THEN
      SELECT COALESCE(SUM(amount), 0) INTO v_spent
      FROM transactions
      WHERE user_id = OLD.user_id AND category_id = OLD.category_id
        AND transaction_date BETWEEN v_start AND v_end;
    END IF;
  END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_add_transaction` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    DECLARE v_goal_id INT;
    SELECT id INTO v_goal_id
    FROM goals
    WHERE category_id = NEW.category_id AND user_id = NEW.user_id
    LIMIT 1;

    IF v_goal_id IS NOT NULL AND NEW.amount > 0 THEN
        UPDATE goals
        SET saved = saved + NEW.amount
        WHERE id = v_goal_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_delete_transaction` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
    DECLARE v_goal_id INT;
    SELECT id INTO v_goal_id
    FROM goals
    WHERE category_id = OLD.category_id AND user_id = OLD.user_id
    LIMIT 1;

    IF v_goal_id IS NOT NULL AND OLD.amount > 0 THEN
        UPDATE goals
        SET saved = saved - OLD.amount
        WHERE id = v_goal_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_update_transaction` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
    DECLARE v_goal_id INT;
    SELECT id INTO v_goal_id
    FROM goals
    WHERE category_id = NEW.category_id AND user_id = NEW.user_id
    LIMIT 1;

    IF v_goal_id IS NOT NULL THEN
        IF OLD.amount > 0 THEN
            UPDATE goals
            SET saved = saved - OLD.amount
            WHERE id = v_goal_id;
        END IF;

        IF NEW.amount > 0 THEN
            UPDATE goals
            SET saved = saved + NEW.amount
            WHERE id = v_goal_id;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `avatar` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `avatar`) VALUES
(2, 'Vo Nhi Anh', 'vonhianh@gmail.com', '$2y$10$aeRssUjS9VQzbyI7MzmfrO6eGn1CA5liLDBPpZgtf5dw5LoCpShnq', '2025-07-28 16:13:42', 0x363838373366363133353935385f3131345f2e6a7067),
(4, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '$2y$10$GIQSnv7urQ1oHBAPml8xBO1KwBljHjqDADnFPtblh28GRkQ59W1V6', '2025-07-28 16:21:01', 0x363838373431303739353839665f5f5f5f6e685f5f5f5f5f5f695f64695f5f5f6e2e6a7067),
(5, 'user1', 'user1@mail.com', 'pass1', '2025-07-28 13:33:33', NULL),
(6, 'user2', 'user2@mail.com', 'pass2', '2025-07-28 13:33:33', NULL),
(7, 'user3', 'user3@mail.com', 'pass3', '2025-07-28 13:33:33', NULL),
(8, 'user4', 'user4@mail.com', 'pass4', '2025-07-28 13:33:33', NULL),
(9, 'user5', 'user5@mail.com', 'pass5', '2025-07-28 13:33:33', NULL),
(10, 'user6', 'user6@mail.com', 'pass6', '2025-07-28 13:33:33', NULL),
(11, 'user7', 'user7@mail.com', 'pass7', '2025-07-28 13:33:33', NULL),
(12, 'user8', 'user8@mail.com', 'pass8', '2025-07-28 13:33:33', NULL),
(13, 'user9', 'user9@mail.com', 'pass9', '2025-07-28 13:33:33', NULL),
(14, 'user10', 'user10@mail.com', 'pass10', '2025-07-28 13:33:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_budget` (`user_id`,`category_id`,`start_date`,`end_date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
