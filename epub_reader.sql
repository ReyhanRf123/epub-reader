-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 03:28 PM
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
-- Database: `epub_reader`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `cfi` varchar(255) NOT NULL,
  `note` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `book_id`, `cfi`, `note`, `created_at`) VALUES
(1, 3, 1, 'epubcfi(/6/4!/4/4/1:0)', 'daftar isi', '2025-12-22 14:46:11'),
(3, 3, 1, 'epubcfi(/6/8!/4/4[id70363406407860]/2/2/2/2/1:0)', 'Penting', '2025-12-22 14:58:33'),
(4, 3, 1, 'epubcfi(/6/26!/10/4[id70363406291220]/2/2/2/2/1:0)', 'laura', '2025-12-22 15:06:46'),
(5, 7, 1, 'epubcfi(/6/16!/4/4[id70363406360000]/2/2/2/2/1:0)', 'halaman 5', '2025-12-22 15:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `user_id`, `title`, `category`, `file_path`, `uploaded_at`) VALUES
(1, 2, 'Efootball', 'Teknik Informatika', 'uploads/1766412647_contoh epub.epub', '2025-12-22 14:10:47'),
(2, 2, 'Clash Of Clans', 'Fisika', 'uploads/1766416536_pg77521-images-3.epub', '2025-12-22 15:15:36'),
(3, 7, 'Game of Thrones', 'Teknik Pertambangan', 'uploads/1766418859_pg77521-images-3.epub', '2025-12-22 15:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `cfi_range` varchar(255) NOT NULL,
  `color` varchar(20) DEFAULT 'yellow',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`id`, `user_id`, `book_id`, `cfi_range`, `color`, `created_at`) VALUES
(1, 3, 1, 'epubcfi(/6/12!/4/4[id70363406388640]/4,/1:0,/1:83)', 'yellow', '2025-12-22 14:49:39'),
(3, 3, 1, 'epubcfi(/6/26!/10/4[id70363406291220]/4,/1:0,/1:129)', 'yellow', '2025-12-22 15:06:24'),
(4, 7, 1, 'epubcfi(/6/16!/4/4[id70363406360000]/4,/1:0,/1:68)', 'yellow', '2025-12-22 15:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, '123', '$2y$10$4abChQzD.13I.ZMQ2HoWm.Mmc3XRPn8kDkkarymQw.6dm/vKnCyZO', 'user', '2025-12-22 14:10:35'),
(3, '333', '$2y$10$z2GC8UHwYV.jbUVcwEk95ek1C0O7XbfxEziyir2QNhniFKHjR2.yW', 'user', '2025-12-22 14:14:39'),
(5, 'adm', '$2y$10$V6OPKk5mfz9B4e5HoLsJn.RExhIrpPtPfrOXG.VlWdWYnyOOCQuvK', 'admin', '2025-12-22 15:33:36'),
(6, 'admin', '$2y$10$bfgOknjtsqjKHO10aujj8e5eaMM.V0irRssiXIfSDl.YLyafsX4sy', 'admin', '2025-12-22 15:35:04'),
(7, 'rubel', '$2y$10$ViPwnpmxO9qL3.jFQjr19upoqjlg6yPoRAHs8gOFEGnrl9TgNfVOG', 'user', '2025-12-22 15:53:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
