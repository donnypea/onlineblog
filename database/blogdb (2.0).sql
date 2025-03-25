-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 04:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_delete` tinyint(1) DEFAULT 0,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`, `is_delete`, `is_read`) VALUES
(1, 1, 1, 'Levi is so fucking cool man!', '2025-03-04 01:38:02', 0, 1),
(2, 14, 2, 'HAHAHAHAHAHAHA', '2025-03-11 01:50:14', 0, 1),
(3, 11, 1, 'so pogi', '2025-03-14 03:38:23', 0, 0),
(6, 8, 7, 'atay hahahhaa', '2025-03-25 03:11:42', 0, 1),
(7, 8, 7, 'hahahha', '2025-03-25 03:15:10', 0, 1),
(8, 8, 7, 'awdawd', '2025-03-25 03:15:31', 0, 1),
(9, 8, 7, 'ddddd', '2025-03-25 03:15:36', 0, 1),
(10, 8, 7, 'wadawd', '2025-03-25 03:15:40', 0, 1),
(11, 8, 7, 'd', '2025-03-25 03:15:44', 0, 1),
(12, 8, 7, 'dawdawd', '2025-03-25 03:15:54', 0, 1),
(13, 8, 7, 'awdwad', '2025-03-25 03:15:59', 0, 1),
(14, 8, 7, 'awdaw', '2025-03-25 03:16:02', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_delete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `image`, `created_at`, `is_delete`) VALUES
(1, 2, 'Attack on Titan', 'Best character of AOT!', 'levi.jpg', '2025-03-04 01:31:07', 0),
(2, 1, 'Quintessential quintuplets', 'Miku best girl!', 'miku.jpg', '2025-03-04 01:39:24', 0),
(3, 9, 'Background', 'Chill background right?', 'bgvery.jfif', '2025-03-11 01:05:18', 0),
(5, 9, 'Sky', 'so pretty!', 'chillbg.webp', '2025-03-11 01:09:25', 0),
(6, 11, 'Attack on Titan', 'my top best anime of all!', 'aot.webp', '2025-03-11 01:20:50', 0),
(7, 2, 'Broooss', 'with broooss', 'with bro.jfif', '2025-03-11 01:22:13', 0),
(8, 7, 'LOL', 'HAHAHAHA', 'jana.jpg', '2025-03-11 01:27:38', 0),
(9, 13, 'Brawlhalla', 'Come on and let\'s play!', 'brawlhalla.jpg', '2025-03-11 01:35:52', 0),
(10, 14, 'HEHE', 'sshhhhh', 'jun.jpg', '2025-03-11 01:37:18', 0),
(11, 15, 'Sunkissss', 'HAHAHAHAHA', 'gayle.jfif', '2025-03-11 01:41:49', 0),
(12, 2, 'Nino', 'With her!', 'ninocos.jfif', '2025-03-11 01:42:31', 0),
(13, 5, 'GGWP', 'nc g', 'neil.jfif', '2025-03-11 01:44:03', 0),
(14, 2, 'Yor', 'with yor buff!', 'withyorbuff.jfif', '2025-03-11 01:44:52', 0),
(15, 1, 'Hiking', 'Let\'s go hiking!', 'mount.webp', '2025-03-14 03:42:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','blogger') NOT NULL DEFAULT 'blogger',
  `is_delete` tinyint(1) DEFAULT 0,
  `p_image` varchar(255) NOT NULL DEFAULT 'uploads/default_profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `role`, `is_delete`, `p_image`) VALUES
(1, 'dondave1', 'dondave@gmail.com', '$2y$10$KLpZpFYm3xkhk44rEXK7MOzXHBg4GQLHkpRHPw4viRTLPNwWVRj3u', '2025-03-04 00:59:36', 'admin', 0, 'kayden.webp'),
(2, 'vincent', 'vincentgodfrey@gmail.com', '$2y$10$TluNDEVzD/1mM5Eth7RfWeDmUrGrb3.IE/FydeXsgov2alq2bhg/K', '2025-03-04 01:04:16', 'blogger', 0, 'ninonakano.jpeg'),
(4, 'Roel', 'roel@gmail.com', '112233', '2025-03-07 01:41:26', 'admin', 1, ''),
(5, 'Neil11', 'neil@gmail.com', '$2y$10$AHYNll2cl3tNi/tOUZgApOrPF4M4Y3/FrD.LHyXjIaIqx3rKPQTzG', '2025-03-07 01:53:16', 'blogger', 0, ''),
(6, 'marc', 'marc@gmail.com', '$2y$10$ETFisOAMn3poy86WsfcmaOEdZyvFk6IJUM5wYFsIcBMhA5eY.PL9i', '2025-03-07 01:55:28', 'blogger', 0, ''),
(7, 'jana11', 'jana@gmail.com', '$2y$10$inAjryAsRyDMJhffQ.x8JunWTcTGQzBweU1q6LPwNMKFvUuNBwdxO', '2025-03-07 02:08:48', 'blogger', 0, ''),
(8, 'Sherlyn', 'sherlyn@gmail.com', '$2y$10$.wUL/NFyP.h09EVTGEKEhu8sDRkf6r7ssdyR26Rlv.AHnX0FU9wf.', '2025-03-11 00:50:03', 'blogger', 0, ''),
(9, 'yeahmap', 'yeahmap@gmail.com', '$2y$10$P7VvmMUg7iSZjaGi6MZbTunfbHNdkWmldAR8eygAw9ucYIel1lR6a', '2025-03-11 00:59:29', 'blogger', 0, ''),
(10, 'Juliet11', 'juliet@gmail.co', '$2y$10$.3QSxmVoNFzIkMs6RpoL9ubrlX/YLVoFklDaSlFfKTG4WdF689g1G', '2025-03-11 01:17:14', 'blogger', 0, ''),
(11, 'romeo22', 'romeo@gmail.com', '$2y$10$MN0EnFb8zuR4FYBKSRm6tuPtPsmX6ggm95kARWdup3tqektrToWu6', '2025-03-11 01:18:05', 'blogger', 0, ''),
(13, 'Brawlhalla', 'brawl@gmail.com', '$2y$10$i4FwLtAnBvu1oHCCy7pj8eNoL3DqzMR5NTP237oBFVyZHYuMJzUVW', '2025-03-11 01:35:04', 'blogger', 0, ''),
(14, 'juniee', 'jun@gmail.com', '$2y$10$hB3FSFW6MHL/jBrewOKsT.xuoEFxqRgRkO8VdMgGRZteuhENVIxh6', '2025-03-11 01:36:56', 'blogger', 0, ''),
(15, 'oglere', 'gayle@gmail.com', '$2y$10$ZS2aeE85Y.6PH7a1DXjRpuJSEzsgbi6NwbaMACkCA9d3T1OfgGhTG', '2025-03-11 01:41:16', 'blogger', 0, 'bg.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
