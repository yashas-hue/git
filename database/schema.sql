-- Core tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  phone VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(150) UNIQUE NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  referral_code VARCHAR(30) UNIQUE,
  referred_by INT NULL,
  is_admin TINYINT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  thumbnail_url VARCHAR(500),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS topics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  order_index INT DEFAULT 0,
  is_free TINYINT DEFAULT 0,
  description TEXT
);

CREATE TABLE IF NOT EXISTS subtopics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  topic_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  video_id INT NULL,
  materials JSON
);

CREATE TABLE IF NOT EXISTS media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('video','material','worksheet') NOT NULL,
  title VARCHAR(255) NOT NULL,
  file_path VARCHAR(500) NOT NULL,
  thumbnail_url VARCHAR(500),
  duration_seconds INT NULL,
  secure_token_salt VARCHAR(64) NULL
);

CREATE TABLE IF NOT EXISTS purchases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  payment_txn_id VARCHAR(100) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS referrals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  used_code VARCHAR(30) NOT NULL,
  used_from_user_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS consult_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  category VARCHAR(100) NOT NULL,
  details TEXT,
  preferred_mode ENUM('online','offline') NOT NULL DEFAULT 'online',
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NULL,
  title VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  thumbnail VARCHAR(500) NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  address JSON NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS forgot_password_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id_or_contact VARCHAR(255) NOT NULL,
  contact_type ENUM('phone','email') NOT NULL,
  message TEXT,
  status ENUM('open','contacted','closed') NOT NULL DEFAULT 'open',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS activity_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  action_type VARCHAR(100) NOT NULL,
  details JSON,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Minimal seed
INSERT INTO categories (name, slug) VALUES ('Successful Life Course', 'successful-life') ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO courses (category_id, title, slug, description, price, thumbnail_url)
VALUES (1, 'Successful Life 101', 'successful-life-101', 'Intro to success habits', 999.00, '/assets/images/course_placeholder.jpg')
ON DUPLICATE KEY UPDATE title=VALUES(title);
INSERT INTO topics (course_id, title, order_index, is_free, description) VALUES (1, 'Introduction', 1, 1, 'Free intro'), (1, 'Mindset', 2, 0, 'Premium');
INSERT INTO subtopics (topic_id, title, video_id, materials) VALUES (1, 'Welcome', NULL, NULL);
