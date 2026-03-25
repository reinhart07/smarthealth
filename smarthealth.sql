-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 25, 2026 at 04:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smarthealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 2, 'Hai', '2026-02-28 17:26:19'),
(2, 2, 'Apa kabar? semoga kalian dalam keadaan baik', '2026-02-28 18:01:40'),
(3, 3, 'Baik', '2026-02-28 18:02:06'),
(4, 2, 'siap', '2026-02-28 23:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `patient_name` varchar(100) DEFAULT 'Anonim',
  `gender` enum('Male','Female','Other') NOT NULL,
  `age` float NOT NULL,
  `hypertension` tinyint(1) NOT NULL,
  `heart_disease` tinyint(1) NOT NULL,
  `smoking_history` varchar(50) NOT NULL,
  `bmi` float NOT NULL,
  `hba1c_level` float NOT NULL,
  `blood_glucose_level` int NOT NULL,
  `result` tinyint(1) NOT NULL COMMENT '0=Non-Diabetes, 1=Diabetes',
  `probability_diabetes` float NOT NULL,
  `risk_level` enum('Rendah','Sedang','Tinggi','Sangat Tinggi') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `predictions`
--

INSERT INTO `predictions` (`id`, `user_id`, `patient_name`, `gender`, `age`, `hypertension`, `heart_disease`, `smoking_history`, `bmi`, `hba1c_level`, `blood_glucose_level`, `result`, `probability_diabetes`, `risk_level`, `created_at`) VALUES
(1, 2, 'Abdul', 'Male', 48, 1, 0, 'current', 28, 7.8, 130, 1, 0.99, 'Sangat Tinggi', '2026-02-28 03:03:31'),
(2, 4, 'Djefry', 'Male', 56, 0, 0, 'not current', 28, 6.6, 119, 0, 0.1525, 'Rendah', '2026-02-28 09:37:58'),
(3, 2, 'Hamka', 'Male', 78, 0, 0, 'former', 29, 6.1, 124, 0, 0.03, 'Rendah', '2026-02-28 15:14:35'),
(4, 4, 'Mail', 'Male', 76, 0, 0, 'former', 29.7, 7.8, 130, 1, 0.99, 'Sangat Tinggi', '2026-03-15 11:11:17'),
(5, 2, 'Ahmad', 'Male', 76, 1, 1, 'former', 30, 8, 130, 1, 0.99, 'Sangat Tinggi', '2026-03-25 03:29:24'),
(6, 3, 'Kashim', 'Male', 56, 1, 1, 'former', 30, 8, 130, 1, 0.95, 'Sangat Tinggi', '2026-03-25 03:43:36'),
(7, 3, 'Zira', 'Male', 30, 0, 0, 'never', 28, 6, 124, 0, 0, 'Rendah', '2026-03-25 03:44:15'),
(8, 3, 'Afdhal', 'Female', 34, 1, 0, 'current', 30, 9, 129, 1, 0.99, 'Sangat Tinggi', '2026-03-25 03:50:06'),
(9, 3, 'Atila', 'Male', 50, 1, 0, 'current', 32, 9, 139, 1, 0.99, 'Sangat Tinggi', '2026-03-25 03:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text,
  `status` enum('active','banned') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `avatar`, `bio`, `status`, `last_login`) VALUES
(2, 'Reinhart Jens Robert', 'reinhartrobert23@gmail.com', '$2y$10$MzQp8/Qrf/Xw/3OVaekNYeDdAd4Cuoa0iFCqfZk9Vcs2IAShx35dq', 'admin', '2026-02-28 02:22:01', 'avatar_2_1772286243.jpeg', '', 'active', '2026-03-25 11:33:54'),
(3, 'Valendino', 'valendino23@gmail.com', '$2y$10$kMPgXEYcFpi7fDzfBuUyNuvh5rX3u6FLhzwuSPkxFmgYQA3/AHYmq', 'user', '2026-02-28 09:19:40', NULL, 'Pasien', 'active', '2026-03-25 11:42:59'),
(4, 'Djefri Wotyla', 'djefry23@gmail.com', '$2y$10$8FnbWMSJt8ErWgIlftbf.OIa8oLa3SqtcW0Gc1pC.WcYqL8NqzQ/u', 'user', '2026-02-28 09:37:07', NULL, NULL, 'active', '2026-03-15 19:38:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
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
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `predictions`
--
ALTER TABLE `predictions`
  ADD CONSTRAINT `predictions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
