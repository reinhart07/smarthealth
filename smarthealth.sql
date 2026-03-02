-- ============================================
-- SmartHealth Database Schema
-- INFEST Hackathon 2026
-- ============================================

CREATE DATABASE IF NOT EXISTS smarthealth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smarthealth;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Predictions
CREATE TABLE IF NOT EXISTS predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    patient_name VARCHAR(100) DEFAULT 'Anonim',
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    age FLOAT NOT NULL,
    hypertension TINYINT(1) NOT NULL,
    heart_disease TINYINT(1) NOT NULL,
    smoking_history VARCHAR(50) NOT NULL,
    bmi FLOAT NOT NULL,
    hba1c_level FLOAT NOT NULL,
    blood_glucose_level INT NOT NULL,
    result TINYINT(1) NOT NULL COMMENT '0=Non-Diabetes, 1=Diabetes',
    probability_diabetes FLOAT NOT NULL,
    risk_level ENUM('Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Dummy admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@smarthealth.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
