-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2026 at 06:37 PM
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
-- Database: `greenloopdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `co2_per_kg` decimal(10,0) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `co2_per_kg`, `created_at`) VALUES
(1, 'phone', 5, '2026-11-12 04:42:11'),
(2, 'tablet', 7, '2026-11-12 04:42:13'),
(3, 'computer(PC)', 12, '2026-11-12 04:43:02'),
(4, 'NoteBook', 8, '2026-11-12 04:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `body` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `title`, `user_id`, `start_at`, `end_at`, `body`, `created_at`) VALUES
(3, 'wdw', 42, '2026-11-12 05:21:00', '2026-11-12 05:22:00', 'wdwd', '2026-11-12 05:22:45'),
(4, 'ssd', 42, '2026-11-12 05:29:00', '2026-11-12 05:30:00', '', '2026-11-12 05:28:17'),
(16, 'ssdww', 4, '2026-11-12 06:04:00', '2026-11-12 06:05:00', 'wdwdw', '2026-11-12 06:03:14'),
(17, 'ssdww', 16, '2026-11-12 06:07:00', '2026-11-12 06:09:00', 'wdwdwas', '2026-11-12 06:04:16'),
(18, 'ssdss', 4, '2026-11-12 06:06:00', '2026-11-12 06:06:00', 'sssss', '2026-11-12 06:04:32'),
(19, 'ssds', 4, '2026-11-12 06:09:00', '2026-11-12 06:07:00', 'ss', '2026-11-12 06:05:07'),
(20, 'ssdasdasdd', 4, '2026-11-06 08:36:00', '2026-11-19 22:35:00', 'asdasda', '2026-11-12 06:32:17'),
(21, 'ssdddddddd', 4, '2026-11-27 23:33:00', '2026-11-18 22:32:00', 'dddddddd', '2026-11-12 06:32:41'),
(22, 'ssddddddd', 4, '2026-11-17 22:35:00', '2026-11-14 21:35:00', 'asdasdasdasdasddasd', '2026-11-12 06:34:06'),
(24, 'ssdasdasdad', 4, '2026-11-12 06:50:00', '2026-11-26 18:54:00', 'asdasdasdasd', '2026-11-12 06:50:53'),
(25, 'ssd', 4, '2026-11-12 07:02:00', '2026-11-12 23:02:00', 'asdasdasdadad', '2026-11-12 07:02:55'),
(26, 'ssdasdasdasdasddasdasdadsdad', 25, '2026-11-12 19:07:00', '2026-11-26 09:03:00', 'asdasdasdadad', '2026-11-12 07:03:34'),
(27, 'ssdasdasdadddddddddddddddddddddddddddddd', 25, '2026-11-12 07:03:00', '2026-11-28 11:06:00', 'asdasdasdadad', '2026-11-12 07:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` varchar(200) NOT NULL,
  `is_read` int(11) NOT NULL,
  `link` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `link`, `created_at`) VALUES
(2, 1, 'สมาชิก', 'asa', 0, 'ads', '2026-11-12 02:16:48'),
(3, 1, 'sa', 'asa', 1, 'ads', '2026-11-12 02:16:51'),
(4, 1, 'สมาชิก', '$namsHashสมัครสมาชิกสำเร็จ', 0, 'admin/users.php', '2026-11-12 03:48:40'),
(12, 1, 'สมาชิก', '$namsHashสมัครสมาชิกสำเร็จ', 0, 'admin/users.php', '2026-11-12 03:52:19'),
(13, 1, 'สมาชิก', '$namsHashสมัครสมาชิกสำเร็จ', 0, 'admin/users.php', '2026-11-12 03:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `point_transactions`
--

CREATE TABLE `point_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `related_id` int(11) NOT NULL,
  `related_type` varchar(200) NOT NULL,
  `amount` int(11) NOT NULL,
  `link` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `point_transactions`
--

INSERT INTO `point_transactions` (`id`, `user_id`, `description`, `related_id`, `related_type`, `amount`, `link`) VALUES
(3, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(4, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(5, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(6, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(7, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(8, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(9, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(10, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(11, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(12, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(13, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(14, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(15, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(16, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(17, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(18, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(19, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(20, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(21, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(22, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(23, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(24, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(25, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(26, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(27, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(28, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(29, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(30, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(31, 41, '', 0, 'รีไซเคิล', 20, 'recycle.php'),
(32, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(33, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(34, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(35, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(36, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(37, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(38, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(39, 41, '', 42, 'รีไซเคิล', 20, 'recycle.php'),
(40, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(41, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(42, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(43, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(44, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(45, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(46, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(47, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(48, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(49, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(50, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(51, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(52, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(53, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(54, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(55, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(56, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(57, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(58, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(59, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(60, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(61, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(62, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(63, 45, '', 42, 'รีไซเคิล', 240, 'recycle.php'),
(64, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(65, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(66, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(67, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(68, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(69, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(70, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(71, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(72, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(73, 45, '', 44, 'รีไซเคิล', 240, 'recycle.php'),
(74, 45, '', 3, 'รีไซเคิล', 240, 'recycle.php'),
(75, 45, '', 2, 'รีไซเคิล', 240, 'recycle.php'),
(76, 45, '', 2, 'รีไซเคิล', 240, 'recycle.php'),
(77, 45, '', 2, 'รีไซเคิล', 240, 'recycle.php'),
(78, 45, '', 2, 'รีไซเคิล', 240, 'recycle.php'),
(79, 45, '', 3, 'รีไซเคิล', 240, 'recycle.php'),
(80, 45, '', 3, 'รีไซเคิล', 240, 'recycle.php'),
(81, 45, '', 2, 'รีไซเคิล', 240, 'recycle.php'),
(83, 2, '', 4, 'รีไซเคิล', 40200, 'recycle.php'),
(85, 2, '', 5, 'รีไซเคิล', 403100, 'recycle.php'),
(86, 2, '', 7, 'รีไซเคิล', 10830820, 'recycle.php'),
(87, 2, '', 8, 'รีไซเคิล', 10830820, 'recycle.php'),
(88, 2, '', 8, 'รีไซเคิล', 10830820, 'recycle.php'),
(89, 2, '', 9, 'รีไซเคิล', 10830820, 'recycle.php'),
(90, 2, '', 9, 'รีไซเคิล', 10830820, 'recycle.php');

-- --------------------------------------------------------

--
-- Table structure for table `recycles`
--

CREATE TABLE `recycles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_type` enum('pickup','self','','') NOT NULL,
  `address` varchar(200) NOT NULL,
  `status` enum('done','rejected','pending','') NOT NULL DEFAULT 'pending',
  `co2_kg` decimal(10,0) NOT NULL,
  `weight_kg` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recycles`
--

INSERT INTO `recycles` (`id`, `user_id`, `delivery_type`, `address`, `status`, `co2_kg`, `weight_kg`, `category_id`, `points`, `item_name`, `image`, `created_at`) VALUES
(1, 41, 'self', 'zax', 'done', 4, 1, 0, 1, 'zax', '72641507b300344abd593a6b4d0424f32e706e67', '2026-11-12 04:51:52'),
(2, 45, 'self', 'zax', 'done', 60, 12, 0, 240, 'zax', '77c89f8c301bfbae0e18d945f762ac5b2e706e67', '2026-11-12 06:08:20'),
(3, 43, 'self', 'asdasdasd', 'done', 0, 2030, 0, 0, 'sdasdasd', '7d5619ff72a116d1fb51d9fe2bde1f722e77656270', '2026-11-12 06:54:30'),
(4, 2, 'self', 'asdasdad', 'done', 0, 2010, 0, 0, 'asdasd', 'dfb1069da223b30a2d6af804a60105eb2e77656270', '2026-11-12 07:20:58'),
(5, 2, 'self', 'asdasda', 'done', 100775, 20155, 0, 403100, 'asdasdasd', '371b86410bd829461f6365c149f4ae442e6a7067', '2026-11-12 07:21:48'),
(6, 2, 'self', 'asdadsa', 'pending', 0, 541541, 0, 0, 'asdsada', '03a6f91911b3d05d918dd7f37d63ddf02e706e67', '2026-11-12 07:24:34'),
(7, 2, 'pickup', 'asdsda', 'done', 2707705, 202020, 0, 10830820, 'adsdasd', '115e1786d97f099d70458070c5b874052e706e67', '2026-11-12 07:25:33'),
(8, 48, 'pickup', 'tcc', 'done', 2707705, 2, 0, 10830820, 'iphone 18', '76b24314f1053468f6b1e967ccf97e0d2e706e67', '2026-11-13 03:05:01'),
(9, 49, 'self', 'tcc', 'rejected', 2707705, 13, 0, 10830820, 'iphone 18', '8f1e26356fe4adf8523652355cc4f8c92e706e67', '2026-11-13 03:18:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `points` int(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `role` enum('admin','user','','') NOT NULL DEFAULT 'user',
  `status` enum('active','suspended','','') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `points`, `password`, `question`, `answer`, `role`, `status`, `created_at`) VALUES
(1, '$name', '$phone', '$email', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', '$question', '$ah', 'user', 'suspended', '2026-11-12 00:41:03'),
(2, 'zaxsc', '0627984402', 'z@gmail.com', 54154104, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$eMXQHI5Krxr2wA/L9/673.tQ2uBI2p/9PPeL.XmIjCK9UwYPkz3di', 'user', 'active', '2026-11-12 03:45:57'),
(3, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$F1jJ/x7R7bTRDAA2CO5J0Og0V/g8bxBFrWv3SITEaflK0n5lnG1l2', 'user', 'active', '2026-11-12 03:46:15'),
(4, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$nK/AU1QiREC7qz9gm.dd0ekiY8jceZwJrQtRYbzyL1lTmPszyKM4W', 'user', 'active', '2026-11-12 03:46:15'),
(5, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$RKB8SeEx6abFZLBSm5OoveSwfUwYpamwJyCALejH6zFJdvNIvGXS.', 'user', 'active', '2026-11-12 03:46:20'),
(6, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$NxTUaVeCeF8V3NaqEk57ke9Wlz0WvouttTvxWiKkSgE1N5gIYdG3K', 'user', 'active', '2026-11-12 03:46:20'),
(7, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$/EyLsHExA4ZANzzuLqpu6.8HqG80jgUfcIWlox4c4C3XciVQkcczC', 'user', 'active', '2026-11-12 03:46:20'),
(8, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$.NIWermuDw6CyD5iMc3v.eti2FBsX/7x9vmVjRSXOzrcL1W.c2G5G', 'user', 'active', '2026-11-12 03:46:30'),
(9, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$SnfGs3c7TlApljxVQrLOC.V9Ce80H6FuaM7.lgPdoy81f.4oMJZ0e', 'user', 'active', '2026-11-12 03:46:31'),
(10, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$Ar/h9zl6NqbTa1tMy0abPu5q2NwP3tGlL8Wge18sTubmI3xRrZYIq', 'user', 'active', '2026-11-12 03:46:31'),
(11, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$v7KYCuvWToJi2KvnMbKC2.rtBSp/ngXKxQM85ZSCkR5xELTjJh80.', 'user', 'active', '2026-11-12 03:46:46'),
(12, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$7T7eKFFgdKK2UC4w0ZOj7eYBNXp2F3/KEJnWdraMG.u9AmYMdlCEW', 'user', 'active', '2026-11-12 03:46:56'),
(13, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$Or9v0lUkzntXXuE9KJQnduu0xaBn4v/KaTLDY0M9wKRY6Ig0H688K', 'user', 'active', '2026-11-12 03:46:56'),
(14, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$SYGVp9x6T/hmpbx6IDru2.ULrCwR3zu1jxOyqHIVzPrY8OtZj/znu', 'user', 'active', '2026-11-12 03:46:56'),
(15, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$t9p0cQvXRiv96ymJQBUIKeIq.hz7QkYxlHJqWeAmkpa2X7fn1/2eS', 'user', 'active', '2026-11-12 03:49:25'),
(16, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$O7vrZEhe4eToQUr9N25keeh27cTUl8Nr96iS2DGLgR4.GdnawCidG', 'user', 'active', '2026-11-12 03:49:25'),
(17, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$sai/g.AwOs.zCFr75gj.I.taKav7VFG14T99bR5TePwDbx.ZoU3ve', 'user', 'active', '2026-11-12 03:49:46'),
(18, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$dBOpsuicvIWpQidK7JWoDO524uoZ.QAPGzDFLKsEZcC.N4yXjLM9y', 'user', 'active', '2026-11-12 03:49:46'),
(19, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$iWBPzv.i3lOb6JnLIX2HHe6qHfXRJHSbE6/4oceeIGR/OBxEr0qia', 'user', 'active', '2026-11-12 03:49:46'),
(20, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$T6LelYQPfZ4eI/2TzXPwreMqwcyfwzFbhwK2xy1O/FNIj02t5MNsW', 'user', 'active', '2026-11-12 03:50:15'),
(21, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$KyBJ27rKtQBGSmODUCX7k.o/GO508LvQOLe6i7zXTfLaNYvAjix5q', 'user', 'active', '2026-11-12 03:50:15'),
(22, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$OtXNyx6/GDyro.Rv5Jo7/.7zrgY5uFwS6KZ2d2nfLAFeJwBkfmPkG', 'user', 'active', '2026-11-12 03:50:31'),
(23, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$ERBw.AuEfTrEP2TBZYqRB.e9tC.8aQC0vooiaO28KIWD4lKKbQan2', 'user', 'active', '2026-11-12 03:50:31'),
(24, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$4YCmrxbtK3mCPG20LH7OA.3QFEVppZIb6HZB5P.q9wAkzDrN9B4/.', 'user', 'active', '2026-11-12 03:50:31'),
(25, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$xhRGkH7x1fPagXcYpVrIk.G.u2fjpFP30EB2SonwRZnp6CQYzaqDy', 'user', 'active', '2026-11-12 03:50:31'),
(26, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$IOB4WHEneQX51xEBR5XJGOPJSqbMy2LEtywyfbKoSE4WpW1qqtN/y', 'user', 'active', '2026-11-12 03:50:36'),
(27, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$ZK5lcHfIZkEMjhs43uvG1.RFiUxM557qP5RHXrdydQoS19ebQrsZq', 'user', 'active', '2026-11-12 03:50:36'),
(28, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$Zpt3buTOvvmPKh0wBuwk4uNO6HO3kmfYFRDlkLLoyPle8Y7wRbcT.', 'user', 'active', '2026-11-12 03:50:36'),
(29, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$grun20lsY/E8YfcMMHVJRujsQssc4c1/DMvF1K/v1f/AiCNU/QNau', 'user', 'active', '2026-11-12 03:54:23'),
(30, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$TYP3tDpWedIydeuQOHzLWOzTSxNRPUy6rtHmo/gfjxpwpm87RQ9Fy', 'user', 'active', '2026-11-12 03:54:51'),
(31, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$VlEuK2Uk8QMIcLdG7cTeg.e1qG8CFKg.O55BnzE2bmnJf6u7VEX7.', 'user', 'active', '2026-11-12 03:55:03'),
(32, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$x/Cl3E0jAYgB2s/JAXbFiuXO.ch61v4Xeq7iwae46Ue8jWD2KQvFm', 'user', 'active', '2026-11-12 03:55:03'),
(33, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$YG8GJPKkw.oKM4GdSep.Heu3q5It2BuVbEDFUdkzFWFVPb.mP/TYy', 'user', 'active', '2026-11-12 03:55:03'),
(34, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$7Wp0hxwcvtC04FT/aVXT0.Sih5LfDV/CZjm.1jg7ZQGFhn3.eXYaK', 'user', 'active', '2026-11-12 03:55:03'),
(35, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$rGdJBeyqlp.Lv5XycsDcyOktc7QvuEqeTqXmFXjvLqqY585eu57TC', 'user', 'active', '2026-11-12 03:55:26'),
(36, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$xqbif8khC7M4B1GlcSHI6OJYhj17bsiGNN8hV0c0FB.wIbRqHgJkW', 'user', 'active', '2026-11-12 03:55:26'),
(37, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$IAUO2mqC/Gt.y.QFDl9YNuLE66P50tGpa89Z2maorfiC21TRwaqEG', 'user', 'active', '2026-11-12 03:55:32'),
(38, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$ITOlIfX.T.rFXReFD8xgpOL3aBsXbThFSatww1M2Ezeyjsqp48hGK', 'user', 'active', '2026-11-12 03:55:32'),
(39, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$lhh59B9JCstrbs6GFYnN9.QU7fs49PPCcCoPsAOX50P/79etNJ1jW', 'user', 'active', '2026-11-12 03:55:32'),
(40, 'zaxsc', '0627984402', 'z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$tXdys9OHyHPeeT.Can289uscNjxiR4DRX2uEtZwAcEfH8xRyFENLK', 'user', 'active', '2026-11-12 03:55:40'),
(41, 'qwe', '0627984407', 'zz1@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$hhKeZzv/rwH5DEjYXejJkOM6KTp831DQEGWs3x9MC9eEF8wGs393q', 'user', 'active', '2026-11-12 04:06:31'),
(42, 'admin', '0627984407', 'admin@a.co', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$NigJCk2SDKk5lsA77Xefpu2.HvRLrQ1wfudBKrC/DXGYRbbPrEnJa', 'admin', 'active', '2026-11-12 05:06:14'),
(43, 'aaaaaasdad', '0932461214', 'bodsdnthawat@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$YYpCl8N6WsjrTYGW3O//n.9C7walLEs.c9cz5fgiR8z.LP8BZXJza', 'user', 'active', '2026-11-12 05:44:19'),
(44, 'bsadasda', '0623502849', 'b@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$rwkqh7JVhDDhiuTrLU1LzOxZxNmvwZh5RPwuc0X0t4cY/oIgslRkq', 'admin', 'active', '2026-11-12 05:45:42'),
(45, 'zaxww', '0627984402', 'z@z.coms', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$GkHU2N66OhD91N1B2B4IPOpHvSELyZsWEmqjMpXfD.xTJFzHOnG32', 'user', 'active', '2026-11-12 06:07:34'),
(46, 'aas', '0627984402', 'zaa@z.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$6dSizZ0CTgwAqhtRHnm87eAEvDpbIzyx2YGEF5TEMjnNBMqqJ5ywS', 'user', 'active', '2026-11-12 06:10:53'),
(47, 'Z', '0849179928', 'Z@gmail.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$FQYUjC1OWEMyH2di5fZE1us9rzdww7cuclFMzjZCT6nDVb5pSXGle', 'user', 'active', '2026-11-12 06:41:13'),
(48, 'Silazaza', '0627984407', 'sila@za.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$OX9WDYRaRLGsk8wGwMX34O2E4RfHKa1gNmSmHOTjw1ibcvoVEBQdW', 'user', 'active', '2026-11-13 03:02:41'),
(49, 'Sila555s', '0627984402', 'sila@g.com', 0, '$2y$10$6IhRCt3U5NC9rPDEH0Pgb.btuoyj/wdxnyW4grXxJp8a4RYco75FO', 'สัตว์เลี้ยง', '$2y$10$5Msb8Xc/551qvCNblH7Q2ePGtKlZR2lo69wIggBMul6AcJCe2YsTK', 'user', 'active', '2026-11-13 03:16:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_ibfk_1` (`user_id`);

--
-- Indexes for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recycles`
--
ALTER TABLE `recycles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `point_transactions`
--
ALTER TABLE `point_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `recycles`
--
ALTER TABLE `recycles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD CONSTRAINT `point_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `recycles`
--
ALTER TABLE `recycles`
  ADD CONSTRAINT `recycles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Marketplace tables
--

CREATE TABLE `wallets` (
  `user_id` int(11) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `wallets_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wallets` (`user_id`, `balance`)
SELECT `id`, 0.00 FROM `users`;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `price_thb` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `products_active_stock_idx` (`is_active`, `stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_details` (
  `product_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `eco_impact` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`),
  CONSTRAINT `product_details_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `topups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `slip_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `topups_status_created_idx` (`status`, `created_at`),
  KEY `topups_user_created_idx` (`user_id`, `created_at`),
  CONSTRAINT `topups_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `topup_reviews` (
  `topup_id` int(11) NOT NULL,
  `reviewed_by` int(11) NOT NULL,
  `reviewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`topup_id`),
  CONSTRAINT `topup_reviews_topup_fk` FOREIGN KEY (`topup_id`) REFERENCES `topups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `topup_reviews_reviewer_fk` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `wallet_paid` decimal(10,2) NOT NULL,
  `status` enum('processing','shipped','delivered') NOT NULL DEFAULT 'processing',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_number_unique` (`order_number`),
  KEY `orders_user_created_idx` (`user_id`, `created_at`),
  CONSTRAINT `orders_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `points_used` int(11) NOT NULL DEFAULT 0,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` varchar(500) NOT NULL,
  PRIMARY KEY (`order_id`),
  CONSTRAINT `order_details_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `eco_impact` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_idx` (`order_id`),
  CONSTRAINT `order_items_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `transaction_type` enum('topup','purchase') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_user_created_idx` (`user_id`, `created_at`),
  CONSTRAINT `wallet_transactions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `price_thb`, `stock`) VALUES
(1, 'แก้วน้ำจากวัสดุรีไซเคิล', 250.00, 30),
(2, 'กระเป๋าผ้ารักษ์โลก', 180.00, 25),
(3, 'สมุดกระดาษรีไซเคิล', 95.00, 50);

INSERT INTO `product_details` (`product_id`, `description`, `eco_impact`) VALUES
(1, 'แก้วพกพาใช้ซ้ำ ลดการใช้แก้วแบบครั้งเดียว', 'ลดขยะพลาสติกจากแก้วใช้ครั้งเดียว'),
(2, 'กระเป๋าผ้าทนทาน ใช้แทนถุงพลาสติกในชีวิตประจำวัน', 'ลดการใช้ถุงพลาสติก'),
(3, 'สมุดจดจากกระดาษรีไซเคิล เหมาะกับการเรียนและทำงาน', 'ใช้กระดาษรีไซเคิลแทนกระดาษใหม่');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
