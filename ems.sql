-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2025 at 12:54 PM
-- Server version: 9.3.0
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

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
    `amount` decimal(12, 2) NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO
    `budgets` (
        `id`,
        `user_id`,
        `category_id`,
        `amount`,
        `start_date`,
        `end_date`,
        `created_at`
    )
VALUES (
        1,
        2,
        3,
        '1500000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        2,
        2,
        4,
        '1000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        3,
        2,
        5,
        '2000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        4,
        2,
        1,
        '20000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        5,
        2,
        2,
        '5000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        6,
        4,
        8,
        '1000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        7,
        4,
        9,
        '500000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        8,
        4,
        10,
        '3000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        9,
        4,
        6,
        '7000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    ),
    (
        10,
        4,
        7,
        '4000000.00',
        '2025-07-01',
        '2025-07-31',
        '2025-07-28 13:33:33'
    );

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
    `id` int NOT NULL,
    `user_id` int NOT NULL,
    `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `type` enum('income', 'expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO
    `categories` (
        `id`,
        `user_id`,
        `name`,
        `type`,
        `created_at`
    )
VALUES (
        1,
        2,
        'Lương chính',
        'income',
        '2025-07-28 13:33:33'
    ),
    (
        2,
        2,
        'Thưởng hiệu suất',
        'income',
        '2025-07-28 13:33:33'
    ),
    (
        3,
        2,
        'Ăn uống',
        'expense',
        '2025-07-28 13:33:33'
    ),
    (
        4,
        2,
        'Đi lại',
        'expense',
        '2025-07-28 13:33:33'
    ),
    (
        5,
        2,
        'Mua sắm',
        'expense',
        '2025-07-28 13:33:33'
    ),
    (
        6,
        4,
        'Thu nhập freelance',
        'income',
        '2025-07-28 13:33:33'
    ),
    (
        7,
        4,
        'Dạy học thêm',
        'income',
        '2025-07-28 13:33:33'
    ),
    (
        8,
        4,
        'Hóa đơn điện',
        'expense',
        '2025-07-28 13:33:33'
    ),
    (
        9,
        4,
        'Giải trí',
        'expense',
        '2025-07-28 13:33:33'
    ),
    (
        10,
        4,
        'Tiền nhà',
        'expense',
        '2025-07-28 13:33:33'
    );

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
    `id` int NOT NULL,
    `user_id` int NOT NULL,
    `type` enum('income', 'expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `period_type` enum('day', 'month', 'year') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `period_value` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `total_amount` decimal(12, 2) NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO
    `statistics` (
        `id`,
        `user_id`,
        `type`,
        `period_type`,
        `period_value`,
        `total_amount`,
        `created_at`
    )
VALUES (
        1,
        2,
        'income',
        'month',
        '2025-07',
        '17000000.00',
        '2025-07-28 13:33:33'
    ),
    (
        2,
        2,
        'expense',
        'month',
        '2025-07',
        '870000.00',
        '2025-07-28 13:33:33'
    ),
    (
        3,
        4,
        'income',
        'month',
        '2025-07',
        '8000000.00',
        '2025-07-28 13:33:33'
    ),
    (
        4,
        4,
        'expense',
        'month',
        '2025-07',
        '3400000.00',
        '2025-07-28 13:33:33'
    );

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
    `id` int NOT NULL,
    `user_id` int NOT NULL,
    `category_id` int NOT NULL,
    `amount` decimal(12, 2) NOT NULL,
    `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    `transaction_date` datetime NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO
    `transactions` (
        `id`,
        `user_id`,
        `category_id`,
        `amount`,
        `description`,
        `transaction_date`,
        `created_at`
    )
VALUES (
        1,
        2,
        1,
        '15000000.00',
        'Lương tháng 7',
        '2025-07-01 08:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        2,
        2,
        2,
        '2000000.00',
        'Thưởng tháng',
        '2025-07-05 10:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        3,
        2,
        3,
        '120000.00',
        'Ăn trưa KFC',
        '2025-07-07 12:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        4,
        2,
        4,
        '50000.00',
        'Grab đi làm',
        '2025-07-08 08:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        5,
        2,
        5,
        '700000.00',
        'Mua đồ Shopee',
        '2025-07-10 20:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        6,
        4,
        6,
        '5000000.00',
        'Dự án content',
        '2025-07-03 09:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        7,
        4,
        7,
        '3000000.00',
        'Gia sư tiếng Anh',
        '2025-07-06 15:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        8,
        4,
        8,
        '900000.00',
        'Tiền điện',
        '2025-07-07 18:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        9,
        4,
        9,
        '200000.00',
        'Xem phim',
        '2025-07-09 19:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        10,
        4,
        10,
        '2500000.00',
        'Trả tiền thuê nhà',
        '2025-07-11 09:00:00',
        '2025-07-28 13:33:33'
    ),
    (
        11,
        4,
        8,
        '-25000.00',
        'Đóng tiền điện',
        '2025-07-28 00:00:00',
        '2025-07-28 22:06:44'
    ),
    (
        12,
        4,
        7,
        '30000000.00',
        'Dậy bé lớp 10',
        '2025-07-28 00:00:00',
        '2025-07-28 22:08:03'
    );

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
    `id` int NOT NULL,
    `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `avatar` longblob
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO
    `users` (
        `id`,
        `username`,
        `email`,
        `password`,
        `created_at`,
        `avatar`
    )
VALUES (
        2,
        'Vo Nhi Anh',
        'vonhianh@gmail.com',
        '$2y$10$aeRssUjS9VQzbyI7MzmfrO6eGn1CA5liLDBPpZgtf5dw5LoCpShnq',
        '2025-07-28 16:13:42',
        0x363838373366363133353935385f3131345f2e6a7067
    ),
    (
        4,
        'Nguyễn Văn A',
        'nguyenvana@gmail.com',
        '$2y$10$GIQSnv7urQ1oHBAPml8xBO1KwBljHjqDADnFPtblh28GRkQ59W1V6',
        '2025-07-28 16:21:01',
        0x363838373431303739353839665f5f5f5f6e685f5f5f5f5f5f695f64695f5f5f6e2e6a7067
    ),
    (
        5,
        'user1',
        'user1@mail.com',
        'pass1',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        6,
        'user2',
        'user2@mail.com',
        'pass2',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        7,
        'user3',
        'user3@mail.com',
        'pass3',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        8,
        'user4',
        'user4@mail.com',
        'pass4',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        9,
        'user5',
        'user5@mail.com',
        'pass5',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        10,
        'user6',
        'user6@mail.com',
        'pass6',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        11,
        'user7',
        'user7@mail.com',
        'pass7',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        12,
        'user8',
        'user8@mail.com',
        'pass8',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        13,
        'user9',
        'user9@mail.com',
        'pass9',
        '2025-07-28 13:33:33',
        NULL
    ),
    (
        14,
        'user10',
        'user10@mail.com',
        'pass10',
        '2025-07-28 13:33:33',
        NULL
    );

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
MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `budgets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

ALTER TABLE budgets
ADD CONSTRAINT unique_budget UNIQUE (
    user_id,
    category_id,
    start_date,
    end_date
);
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

-- Procedure

-- =====================================
-- PROCEDURE: AddTransaction
-- Thêm giao dịch mới và cập nhật thống kê
-- =====================================
DELIMITER $$

CREATE PROCEDURE AddTransaction(
    IN p_user_id INT,
    IN p_category_id INT,
    IN p_amount DECIMAL(12,2),
    IN p_description TEXT,
    IN p_transaction_date DATETIME
)
BEGIN
    -- Khởi tạo biến type (income / expense) và period (tháng)
    DECLARE v_type VARCHAR(20);
    DECLARE v_period VARCHAR(20);

    -- Lấy type từ categories
    SELECT type INTO v_type FROM categories WHERE id = p_category_id;

    SET v_period = DATE_FORMAT(p_transaction_date, '%Y-%m');

    -- Chèn giao dịch mới
    INSERT INTO transactions (user_id, category_id, amount, description, transaction_date)
    VALUES (p_user_id, p_category_id, p_amount, p_description, p_transaction_date);

    -- Cập nhật statistics
    INSERT INTO statistics (user_id, type, period_type, period_value, total_amount)
    VALUES (p_user_id, v_type, 'month', v_period, p_amount)
    ON DUPLICATE KEY UPDATE total_amount = total_amount + p_amount;

END$$

DELIMITER;

-- =====================================
-- TRIGGER: after_insert_transaction
-- Tự động cập nhật thống kê khi có giao dịch mới
-- =====================================
DELIMITER $$

CREATE TRIGGER after_insert_transaction
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
  DECLARE period VARCHAR(20);

  SET period = DATE_FORMAT(NEW.transaction_date, '%Y-%m');

  -- Nếu là chi tiêu
  IF (SELECT type FROM categories WHERE id = NEW.category_id) = 'expense' THEN
    INSERT INTO statistics (user_id, type, period_type, period_value, total_amount)
    VALUES (NEW.user_id, 'expense', 'month', period, NEW.amount)
    ON DUPLICATE KEY UPDATE total_amount = total_amount + NEW.amount;

  -- Nếu là thu nhập
  ELSEIF (SELECT type FROM categories WHERE id = NEW.category_id) = 'income' THEN
    INSERT INTO statistics (user_id, type, period_type, period_value, total_amount)
    VALUES (NEW.user_id, 'income', 'month', period, NEW.amount)
    ON DUPLICATE KEY UPDATE total_amount = total_amount + NEW.amount;
  END IF;
END$$

DELIMITER;

-- =====================================
-- TRIGGER: check_budget_before_insert
-- Kiểm tra ngân sách trước khi thêm giao dịch chi tiêu
-- =====================================
DELIMITER $$

CREATE TRIGGER check_budget_before_insert
BEFORE INSERT ON transactions
FOR EACH ROW
BEGIN
  DECLARE v_budget DECIMAL(12,2) DEFAULT 0;
  DECLARE v_spent DECIMAL(12,2) DEFAULT 0;
  DECLARE v_start DATE;
  DECLARE v_end DATE;

  -- Chỉ kiểm tra nếu là chi tiêu
  IF (SELECT type FROM categories WHERE id = NEW.category_id) = 'expense' THEN

    -- Lấy ngân sách hiện tại
    SELECT amount, start_date, end_date INTO v_budget, v_start, v_end
    FROM budgets
    WHERE user_id = NEW.user_id
      AND category_id = NEW.category_id
      AND start_date <= NEW.transaction_date
      AND end_date >= NEW.transaction_date
    LIMIT 1;

    -- Nếu có ngân sách thì kiểm tra tổng chi tiêu
    IF v_budget IS NOT NULL THEN
      SELECT COALESCE(SUM(amount), 0) INTO v_spent
      FROM transactions
      WHERE user_id = NEW.user_id
        AND category_id = NEW.category_id
        AND transaction_date BETWEEN v_start AND v_end;

      -- Nếu vượt thì báo lỗi
      IF (v_spent + NEW.amount) > v_budget THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Chi tiêu vượt quá ngân sách!';
      END IF;
    END IF;

  END IF;
END$$

DELIMITER;

-- =====================================
-- TRIGGER: after_delete_transaction
-- Hoàn ngân sách khi xóa giao dịch
-- =====================================
DELIMITER $$

CREATE TRIGGER after_delete_transaction
AFTER DELETE ON transactions
FOR EACH ROW
BEGIN
  DECLARE v_type VARCHAR(20);
  DECLARE v_period VARCHAR(20);

  -- Lấy loại giao dịch từ bảng categories
  SELECT type INTO v_type FROM categories WHERE id = OLD.category_id;
  SET v_period = DATE_FORMAT(OLD.transaction_date, '%Y-%m');

  -- Trừ số tiền khỏi statistics
  UPDATE statistics
  SET total_amount = total_amount - OLD.amount
  WHERE user_id = OLD.user_id
    AND type = v_type
    AND period_type = 'month'
    AND period_value = v_period;

  -- Nếu sau khi trừ còn <= 0 thì xóa luôn
  DELETE FROM statistics
  WHERE user_id = OLD.user_id
    AND type = v_type
    AND period_type = 'month'
    AND period_value = v_period
    AND total_amount <= 0;
END$$

DELIMITER;

-- =====================================
-- TRIGGER: after_update_transaction
-- Cập nhật lại thống kê khi sửa giao dịch
-- =====================================
DELIMITER $$

CREATE TRIGGER after_update_transaction
AFTER UPDATE ON transactions
FOR EACH ROW
BEGIN
  DECLARE v_old_type VARCHAR(20);
  DECLARE v_new_type VARCHAR(20);
  DECLARE v_old_period VARCHAR(20);
  DECLARE v_new_period VARCHAR(20);

  -- Lấy loại giao dịch cũ & mới
  SELECT type INTO v_old_type FROM categories WHERE id = OLD.category_id;
  SELECT type INTO v_new_type FROM categories WHERE id = NEW.category_id;

  SET v_old_period = DATE_FORMAT(OLD.transaction_date, '%Y-%m');
  SET v_new_period = DATE_FORMAT(NEW.transaction_date, '%Y-%m');

  -- Trừ số tiền cũ
  IF v_old_type IN ('income', 'expense') THEN
    UPDATE statistics
    SET total_amount = total_amount - OLD.amount
    WHERE user_id = OLD.user_id
      AND type = v_old_type
      AND period_type = 'month'
      AND period_value = v_old_period;

    -- Xóa nếu <= 0
    DELETE FROM statistics
    WHERE user_id = OLD.user_id
      AND type = v_old_type
      AND period_type = 'month'
      AND period_value = v_old_period
      AND total_amount <= 0;
  END IF;

  -- Cộng số tiền mới
  IF v_new_type IN ('income', 'expense') THEN
    INSERT INTO statistics (user_id, type, period_type, period_value, total_amount)
    VALUES (NEW.user_id, v_new_type, 'month', v_new_period, NEW.amount)
    ON DUPLICATE KEY UPDATE total_amount = total_amount + NEW.amount;
  END IF;

END$$

DELIMITER;

-- /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
-- ;
-- /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
-- ;
-- /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
-- ;