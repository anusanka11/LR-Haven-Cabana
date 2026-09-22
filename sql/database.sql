CREATE DATABASE IF NOT EXISTS lionrock_haven CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lionrock_haven;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bookings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  contact_number VARCHAR(30) NOT NULL,
  email VARCHAR(160) NOT NULL,
  id_number VARCHAR(60) NOT NULL,
  guest_type ENUM('Local','Foreign') NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  more_details TEXT NULL,
  status ENUM('New','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_dates (check_in, check_out),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admins (username, password_hash)
VALUES ('Admin', '$2y$12$zBDcWRuXRTSgGycmSz47Qeu0jbXFowzoLcL4USo5yFJeC3UMyoJ5G')
ON DUPLICATE KEY UPDATE username = username;
