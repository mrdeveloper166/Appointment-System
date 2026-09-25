-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 09:07 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appointment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'admin', '$2y$10$UIS4/3uP5CBU19F8Bu.E0ewRGAu8jRwZEDnyRZ8K2IO5f6PUuSlAm');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `select_service` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(10) NOT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Visited','Non-Visited') DEFAULT 'Non-Visited'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_name`, `age`, `gender`, `contact_no`, `email_id`, `select_service`, `description`, `appointment_date`, `appointment_time`, `remark`, `created_at`, `status`) VALUES
(1, 'Abhishek', 23, 'Male', '7536089052', 'vermaabhishek@gmail.com', 'COLOUR DOPPLER SONOGRAPHY', 'Demo', '2024-11-13', '03:00 PM', 'demomo', '2024-11-11 06:14:03', 'Visited'),
(2, 'Nancy', 24, 'Female', '7536089052', 'vermaabhishe@gmail.com', 'COLOUR DOPPLER SONOGRAPHY', 'demo', '2024-11-22', '04:00 PM', 'demoemo', '2024-11-11 06:16:32', 'Non-Visited'),
(4, 'Dev', 22, 'Male', '9866856565', 'verm@gmail.com', 'CAROTID & PERIPHERAL VESSELS', 'demo', '2024-11-13', '01:00 PM', 'rem', '2024-11-11 07:09:04', 'Non-Visited'),
(5, 'seema', 23, 'Female', '7985656565', 'seema@gmail.com', 'SMALL PART SONOGRAPHY', 'demeoe', '2024-11-12', '01:00 PM', 'demo', '2024-11-11 07:12:06', 'Non-Visited'),
(6, 'Pooja', 23, 'Female', '79652658', 'pooja@gmail.com', 'CAROTID & PERIPHERAL VESSELS', 'pooja beemar hai', '2024-11-22', '01:00 PM', 'demo', '2024-11-11 07:17:42', 'Non-Visited'),
(7, 'dev', 23, 'Male', '985685656', 'dev@gmail.com', 'CAROTID & PERIPHERAL VESSELS', 'demo', '2024-11-13', '04:00 PM', 'deb', '2024-11-11 07:22:47', 'Non-Visited'),
(8, 'dev', 56, 'Male', '989656598', 'dev@gmail.com', 'ABDOMINAL ULTRASONOGRAPHY', 'd', '2024-11-10', '03:00 PM', 'ssd', '2024-11-11 07:30:03', 'Non-Visited'),
(9, 'Radha ', 45, 'Female', '796856565', 'radha@gmail.com', 'CAROTID & PERIPHERAL VESSELS', 'demo', '2024-11-12', '11:00 AM', 'demo', '2024-11-11 07:35:42', 'Visited'),
(10, 'sweta', 23, 'Female', '795232656', 'sweta@gmail.com', 'COLOUR DOPPLER SONOGRAPHY', 'sweta', '2024-11-13', '12:00 PM', 'sdsfd', '2024-11-11 07:41:58', 'Non-Visited'),
(11, 'dev', 89, 'Male', '986556565', 'deb@gmail.com', 'SCROTAL', 'dem', '2024-11-19', '04:00 PM', 'dsfsf', '2024-11-11 07:47:52', 'Non-Visited'),
(12, 'Dev', 5, 'Male', '988956565', 'dev@gmail.com', 'ABDOMINAL ULTRASONOGRAPHY', 'debebeb', '2024-11-21', '03:00 PM', 'sdfsdf', '2024-11-11 07:55:38', 'Non-Visited'),
(13, 'Seema', 56, 'Female', '789368952', 'seema@gmail.com', 'SMALL PART SONOGRAPHY', 'ddee', '2024-11-29', '05:00 PM', 'sgdsgd', '2024-11-11 07:57:58', 'Non-Visited'),
(14, 'papa', 56, 'Male', '795985533', 'papa@gmail.com', 'CAROTID & PERIPHERAL VESSELS', 'gfhgfh', '2024-11-28', '01:00 PM', 'ghfhgfhgf', '2024-11-11 07:59:52', 'Non-Visited'),
(15, 'sdfsd', 5, 'Male', '598656532', 'dem@gmail.com', 'COLOUR DOPPLER SONOGRAPHY', 'gfghfhg', '2024-11-13', '05:00 PM', 'hgfghf', '2024-11-11 08:00:40', 'Non-Visited');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
