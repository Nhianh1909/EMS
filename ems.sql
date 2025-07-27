-- 1. Users table
CREATE TABLE Users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categories table
CREATE TABLE Categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  type ENUM('income', 'expense') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- 3. Transactions table
CREATE TABLE Transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  description TEXT,
  transaction_date DATETIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE CASCADE
);

-- 4. Budgets table
CREATE TABLE Budgets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE CASCADE
);

-- 5. Statistics table (optional)
CREATE TABLE Statistics (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  type ENUM('income', 'expense') NOT NULL,
  period_type ENUM('day', 'month', 'year') NOT NULL,
  period_value VARCHAR(20) NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
