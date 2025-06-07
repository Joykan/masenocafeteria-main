-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 04:59 PM
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
-- Database: `cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `category`, `price`, `image`, `created_at`) VALUES
(2, 'apple juice', 'very sweet', 'Drinks', 100.00, 'Apple Juice.jpg', '2025-04-08 13:05:30'),
(3, 'papaya juice', 'very sweet, full vitamin full helps with constipation problems', 'Drinks', 130.00, 'papayajuice.jpg', '2025-04-08 13:57:44'),
(4, 'ugali mayai', 'home made ugali with chicken 🐔 eggs', 'Lunch', 200.00, 'ugali scramble.jpeg', '2025-04-08 14:06:46'),
(5, 'UGALI MBUZI', 'KUTSAMA KUHUHU', 'Lunch', 300.00, 'kuku.jpeg', '2025-04-08 16:14:56'),
(6, 'biscuits', 'nice one', 'Snack', 150.00, 'burger.jpeg', '2025-04-08 19:31:28'),
(7, 'pilau', 'very good', 'Lunch', 200.00, 'pilau nyama.jpeg', '2025-04-09 10:09:17'),
(8, 'sweets', 'very charming', 'snack', 50.00, 'burger.jpeg', '2025-04-09 10:43:50'),
(9, 'pilau', 'delicious', 'Lunch', 100.00, 'pilau nyama.jpeg', '2025-04-27 09:35:30'),
(10, 'papaya juice', 'very sweet', 'Drink', 120.00, 'papayajuice.jpg', '2025-04-29 09:01:29'),
(11, 'ugali', 'wariii', 'Lunch', 55.00, 'ugali_omena.jpeg', '2025-04-29 10:21:39'),
(12, 'pan cake', 'nice one', 'snack', 5.00, 'pancakes.jpg', '2025-04-29 10:35:30'),
(13, 'pan cake', 'nice one', 'snack', 5.00, 'pancakes.jpg', '2025-04-29 10:35:46'),
(14, 'pan cake', 'nice one', 'Snacks', 5.00, 'pancakes.jpg', '2025-04-29 10:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `pickup_method` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `delivery_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total`, `status`, `total_price`, `pickup_method`, `address`, `latitude`, `longitude`, `payment_method`, `delivery_address`) VALUES
(7, 16, '2025-04-10 06:47:19', 0.00, 'pending', 1050.00, 'Deliver to My Location', '', 0.000000, 0.000000, '', NULL),
(8, 16, '2025-04-10 06:47:51', 0.00, 'pending', 1050.00, 'Pick at Station', '', 0.000000, 0.000000, '', NULL),
(9, 19, '2025-04-10 06:50:16', 0.00, 'pending', 1050.00, 'Deliver to My Location', '', 0.000000, 0.000000, '', NULL),
(10, 19, '2025-04-10 06:50:27', 0.00, 'pending', 1050.00, 'Pick at Station', '', 0.000000, 0.000000, '', NULL),
(11, 19, '2025-04-10 06:56:31', 0.00, 'pending', 1050.00, 'Deliver to My Location', '', 0.000000, 0.000000, '', NULL),
(12, 19, '2025-04-10 06:56:50', 0.00, 'pending', 1050.00, 'Deliver to My Location', '', 0.000000, 0.000000, '', NULL),
(13, 19, '2025-04-10 07:01:52', 0.00, 'pending', 1050.00, 'Deliver to My Location', '', 0.000000, 0.000000, '', NULL),
(14, 16, '2025-04-25 12:34:11', 0.00, 'pending', 200.00, 'Pick at Station', '', 0.000000, 0.000000, '', NULL),
(15, 16, '2025-04-25 13:35:32', 0.00, 'pending', 200.00, 'Deliver to My Location', '', 0.001256, 34.615620, '', NULL),
(16, 16, '2025-04-25 13:40:57', 0.00, 'completed', 200.00, 'Pick at Station', '', 0.000000, 0.000000, '', NULL),
(17, 16, '2025-04-25 17:07:36', 200.00, 'cancelled', NULL, 'Deliver to My Location', '40100 kisumu', 0.001263, 34.615621, 'Mpesa', NULL),
(18, 16, '2025-04-25 17:11:10', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001263, 34.615621, 'Mpesa', NULL),
(19, 32, '2025-04-25 17:17:54', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', -0.000257, 34.611912, 'Mpesa', NULL),
(20, 32, '2025-04-25 18:10:33', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 14.131123, -89.209528, 'Mpesa', NULL),
(21, 32, '2025-04-25 18:27:46', 0.00, 'cancelled', NULL, 'Pick at Cafeteria', '', 0.000000, 0.000000, 'Mpesa', NULL),
(22, 32, '2025-04-25 19:14:14', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.000000, 0.000000, 'Mpesa', NULL),
(23, 16, '2025-04-25 19:32:10', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.000000, 0.000000, 'Debit Card', NULL),
(24, 32, '2025-04-26 17:20:53', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001269, 34.615642, 'Mpesa', NULL),
(25, 16, '2025-04-26 18:54:44', 0.00, 'cancelled', NULL, 'Delivery', '', -86.137629, 574.804688, 'Mpesa', NULL),
(26, 16, '2025-04-26 19:21:58', 0.00, 'cancelled', NULL, 'Delivery', '', 0.001273, 34.615635, 'Mpesa', NULL),
(27, 16, '2025-04-27 05:08:19', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001261, 34.615624, 'Credit Card', NULL),
(28, 16, '2025-04-27 05:09:13', 0.00, 'cancelled', NULL, 'Pick at Station', '', 0.000000, 0.000000, 'Credit Card', NULL),
(29, 16, '2025-04-27 05:24:31', 0.00, 'cancelled', NULL, 'Pick at Cafeteria', '', 0.000000, 0.000000, 'Credit Card', NULL),
(30, 16, '2025-04-27 05:37:05', 0.00, 'cancelled', NULL, 'Pick at Cafeteria', '', 0.000000, 0.000000, 'Credit Card', NULL),
(31, 16, '2025-04-27 06:03:07', 0.00, 'cancelled', NULL, 'Pick at Station', '', 0.000000, 0.000000, 'Credit Card', NULL),
(32, 16, '2025-04-27 06:19:07', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001271, 34.615633, 'Mpesa', NULL),
(33, 16, '2025-04-27 06:23:17', 150.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001271, 34.615633, 'Mpesa', NULL),
(34, 16, '2025-04-27 06:23:41', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001271, 34.615633, 'Mpesa', NULL),
(35, 16, '2025-04-27 06:28:13', 0.00, 'cancelled', NULL, 'Deliver to My Location', '', -1.292066, 36.821946, 'Credit Card', NULL),
(36, 16, '2025-04-27 06:58:57', 0.00, 'cancelled', NULL, 'pickup', '', 0.000000, 0.000000, 'cash', NULL),
(37, 16, '2025-04-27 07:08:31', 300.00, 'cancelled', NULL, 'Deliver to My Location', '', -1.292066, 36.821946, 'Credit Card', NULL),
(38, 16, '2025-04-27 07:17:51', 50.00, 'cancelled', NULL, 'Deliver to My Location', '', 0.001277, 34.615635, 'Credit Card', NULL),
(39, 16, '2025-04-27 07:22:17', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(40, 16, '2025-04-27 07:23:37', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(41, 16, '2025-04-27 07:25:55', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(42, 20, '2025-04-27 07:28:24', 200.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(43, 20, '2025-04-27 07:34:47', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(44, 20, '2025-04-27 07:47:35', 200.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(45, 20, '2025-04-27 07:56:27', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(47, 16, '2025-04-27 08:04:52', 50.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(48, 20, '2025-04-27 08:20:55', 200.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(49, 16, '2025-04-27 08:55:24', 150.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(50, 16, '2025-04-27 09:41:21', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(51, 16, '2025-04-27 10:09:08', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(52, 16, '2025-04-27 10:17:12', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(53, 16, '2025-04-27 10:25:02', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(54, 16, '2025-04-29 05:46:39', 100.00, 'completed', NULL, NULL, NULL, NULL, NULL, '', NULL),
(55, 16, '2025-04-29 07:18:19', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(56, 36, '2025-04-29 10:22:09', 55.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(57, 36, '2025-04-29 10:33:50', 100.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(58, 36, '2025-04-29 10:39:47', 5.00, 'cancelled', NULL, NULL, NULL, NULL, NULL, '', NULL),
(59, 36, '2025-04-29 11:42:05', 200.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(60, 36, '2025-04-29 11:43:07', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(61, 36, '2025-04-29 11:43:44', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(62, 20, '2025-04-29 12:58:39', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(63, 20, '2025-04-29 12:59:32', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(64, 20, '2025-04-29 13:01:10', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(65, 20, '2025-04-29 13:20:00', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(66, 16, '2025-04-29 14:20:49', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(67, 16, '2025-04-29 18:24:34', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(68, 16, '2025-04-30 10:50:29', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(69, 38, '2025-04-30 14:15:37', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(70, 16, '2025-05-01 08:01:56', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(71, 16, '2025-05-01 08:36:02', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(72, 16, '2025-05-01 08:51:05', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', ''),
(73, 16, '2025-05-01 09:09:07', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(74, 16, '2025-05-01 09:11:32', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(75, 16, '2025-05-01 09:17:30', 230.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(76, 16, '2025-05-01 13:45:53', 5.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL),
(77, 41, '2025-05-01 13:53:51', 5.00, 'completed', NULL, NULL, NULL, NULL, NULL, '', NULL),
(78, 41, '2025-05-01 14:58:08', 100.00, 'pending', NULL, NULL, NULL, NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `item_price`, `menu_item_id`, `quantity`, `subtotal`, `price`) VALUES
(3, 54, NULL, NULL, 2, 1, 100.00, NULL),
(4, 55, NULL, NULL, 2, 1, 100.00, NULL),
(5, 56, NULL, NULL, 11, 1, 55.00, NULL),
(6, 57, NULL, NULL, 9, 1, 100.00, NULL),
(7, 58, NULL, NULL, 14, 1, 5.00, NULL),
(8, 59, NULL, NULL, 4, 1, 200.00, NULL),
(9, 60, NULL, NULL, 14, 1, 5.00, NULL),
(10, 61, NULL, NULL, 14, 1, 5.00, NULL),
(11, 62, NULL, NULL, 2, 1, 100.00, NULL),
(12, 63, NULL, NULL, 13, 1, 5.00, NULL),
(13, 64, NULL, NULL, 14, 1, 5.00, NULL),
(14, 65, NULL, NULL, 2, 1, 100.00, NULL),
(15, 66, NULL, NULL, 2, 1, 100.00, NULL),
(16, 67, NULL, NULL, 2, 1, 100.00, NULL),
(17, 68, NULL, NULL, 2, 1, 100.00, NULL),
(18, 69, NULL, NULL, 14, 1, 5.00, NULL),
(19, 70, NULL, NULL, 2, 1, 100.00, NULL),
(20, 71, NULL, NULL, 2, 1, 100.00, NULL),
(21, 72, NULL, NULL, 2, 1, 100.00, NULL),
(22, 73, NULL, NULL, 2, 1, 100.00, NULL),
(23, 74, NULL, NULL, 2, 1, 100.00, NULL),
(24, 75, NULL, NULL, 2, 1, 100.00, NULL),
(25, 75, NULL, NULL, 3, 1, 130.00, NULL),
(26, 76, NULL, NULL, 14, 1, 5.00, NULL),
(27, 77, NULL, NULL, 14, 1, 5.00, NULL),
(28, 78, NULL, NULL, 2, 1, 100.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `phone`, `role`, `created_at`, `profile_image`) VALUES
(13, 'musa', 'Moses Andrew', '1@gmail.com', '$2y$10$BWL4uYzLKc/752o4b4CyDOtPRZaTMm0sTTBnvbvv8vuU8uROq5qai', '0741811118', 'customer', '2025-04-06 15:18:23', NULL),
(15, 'musa1', 'Moses Baraka', 'b@gmail.com', '$2y$10$B714/McNV5oM1.8Yl/WAeeOYAesiHHKB80jcgp0Iu3MTrJ1FX0Byi', '0741811118', 'admin', '2025-04-06 15:19:29', NULL),
(16, 'mozenga', 'Moses Randu', 'moses11@gmail.com', '$2y$10$hSMxkAkqrtj.LbtF0npRnenl3pGCE1SvHCXql9heiGCGbGR1X.UjO', '0741811118', 'customer', '2025-04-06 16:24:14', NULL),
(19, 'mthikisi', 'Muthikisi Muthawasitha', 'mm111@gmail.com', '$2y$10$m89yoZQKifbVsXSzCdaGsOgg1JsIkzkspa70V/5p6msH.qWZWDbVW', '0798362136', 'customer', '2025-04-06 17:04:35', NULL),
(20, 'candy', 'candy candy', 'c@gmai.com', '$2y$10$s4/55ZCLWC4BqV1beVWkpOaE9lwoYy7PbMPqfrcb6KTMsAA1eYxIO', '0798362136', 'customer', '2025-04-07 08:35:43', NULL),
(21, 'vickey', 'vickey viviane', 'vickeyvivian@gmail.com', '$2y$10$ij3nSKSQVxos2qgfZOfO2ujuTHzqh/co/3V1YU.kxeYQPVIvvYWXW', '0740846709', 'customer', '2025-04-07 10:28:25', NULL),
(24, 'vickey1', 'vickey viviane', '1vickeyvivian@gmail.com', '$2y$10$Cwp906eCJKJ4NB2.gQnrdOchWnV1sN8.hv3j3lGkbOR.hNMonPoXa', '0740846709', 'customer', '2025-04-07 10:29:03', NULL),
(25, 'juhudi', 'juhudi juhudi', 'juhudi@gmail.com', '$2y$10$YF9keu5EGTYwZGK5d9adjeFPDHa0dFBsR5PS5RBKluaYCrLYKxMwu', '0746135856', 'admin', '2025-04-08 08:14:32', NULL),
(26, '', '', '', '$2y$10$prfkeFNQCjylXy8yjbycnusqfslh9vuVZUyG18uCKP9imeHCHioda', '', 'customer', '2025-04-08 20:38:25', NULL),
(28, 'cit/00042/022', 'David Wangara', 'wangadavi65@gmail.com', '$2y$10$kpJUPALiu5HhudLlTait/.QnK5xovarIlGC0hd8Dpq671TpRAwMW6', '0115032061', 'admin', '2025-04-09 09:32:08', NULL),
(29, 'musaa', 'Andrew Nzaro', 'musa123@gmail.com', '$2y$10$m2etg5094C6MKr8zpYqRxOLXIP.P/oJgaUw7nU7sLXyGI9ihrhbh6', '0111111132', 'customer', '2025-04-25 17:16:08', NULL),
(32, 'nzaro', 'Andrew Nzaro', 'musa12345@gmail.com', '$2y$10$e5BXcJf6lzCbcLq/VfRjDOD.m3Vslc4JoywO6C6QK4xXuSgcRbd/C', '0111111132', 'customer', '2025-04-25 17:17:07', NULL),
(34, 'vickeyyy', 'Vivian Vickey', 'v@gmail.com', '$2y$10$7Sxt.9YB7.mM3/QXl/0MHeJTRmbzgCcS8R1.brVnvtUZMDXJXrn6u', '0110015306', 'customer', '2025-04-29 05:52:07', NULL),
(36, 'karisa', 'karisa wehu', 'karisa@gmail.com', '$2y$10$ASWoa1HuuGKKLHMVaX//0O7LTKH5M0/IJRUQrX5NiIGqzfgXCX.t2', '0789988778', 'customer', '2025-04-29 10:02:33', 'burger.jpeg'),
(37, 'moseso', 'moses andrew', 'soso@gmail.com', '$2y$10$KFexcXXKgLtuefHFo5jVGOGzLR7I7dRJaKOYF.ID7dfCgqAOGOLBO', '0110015306', 'admin', '2025-04-29 12:14:18', NULL),
(38, 'ruth', 'Ruth Ooko', 'ruth@gmail.com', '$2y$10$sEbGrVk87CTIwydEurTer.6U3/Mdh4AADBGpNbjtL/OprwW7FLNAS', '0112940412', 'customer', '2025-04-30 14:14:51', NULL),
(40, 'juhudiii', 'juhudi samuel', 'j@gmail.com', '$2y$10$xE9UdTNDSUbBno9fA3KjbO9E70dJzLbqcG0Zo.XY7GRL5wNmO.Xcu', '0746135856', 'customer', '2025-05-01 13:48:37', NULL),
(41, 'mimi', 'tsawe juhudi', 'jjjj@gmail.com', '$2y$10$5y0EPgsmUo2txVc8zuZpwuszlj3ggiXr1RyTUo9V6Bfd7.kj1LRvW', '0746135856', 'customer', '2025-05-01 13:53:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
