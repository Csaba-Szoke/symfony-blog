-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 17, 2020 at 08:59 AM
-- Server version: 10.4.12-MariaDB
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogs`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `slug`, `public`, `content`, `user_id`, `img`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'asd', 'asd', 0, NULL, 1, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Test', 'test-slug', 1, 'lotem ipsum', 1, '', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'need more', '123', 1, 'need more need more need more need more need more need more need more need more', 1, NULL, 3, '0000-00-00 00:00:00', '2020-12-11 08:30:05'),
(8, 'lorem', 'ipsum', 1, 'Content', 1, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'asd1', 'asd1', 1, 'asd1', 1, 'cropped-1920-1080-309983-5fce05978496e.jpeg', 10, '0000-00-00 00:00:00', '2020-12-16 13:02:23'),
(10, 'new11111111', 'new', 1, '1Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lectus felis, sagittis et nibh nec, faucibus faucibus eros. Morbi sed ante cursus, pulvinar dui sit amet, molestie metus. Nunc at sem non arcu faucibus sollicitudin in sed magna. Cras in lectus vitae ipsum ultrices congue. Nunc vestibulum odio vitae enim congue euismod nec ac metus. Donec volutpat, ex et feugiat maximus, mauris orci gravida lorem, vel luctus risus augue ac turpis. Maecenas hendrerit semper efficitur. In mollis mi at sapien placerat fringilla. Sed condimentum iaculis massa ac blandit.', 5, 'cropped-1920-1080-297923-5fcdfbcde4a5f.jpeg', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'New one!', 'new-one', 1, '<p>No comment!</p>', 1, '655996-5fcdfc7ceb236.jpeg', 1, '2020-12-08 14:21:21', '2020-12-16 09:30:08'),
(14, 'Exercitation alias d', 'Voluptas minus anim', 1, 'Reprehenderit est i', 1, 'cropped-1920-1080-234066-5fd2111ca2646.jpeg', 1, '2020-12-09 14:21:21', '2020-12-14 11:22:45'),
(18, 'Fugiat aspernatur fu', 'Iusto dolore id adip', 1, '<h2>Aliquam sunt dolor s</h2>\r\n\r\n<p><em>Lorem <s>ipsum</s></em></p>', 1, 'cropped-1920-1080-203761-5fd220d125338.jpeg', 4, '2020-12-10 14:21:21', '2020-12-14 11:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `blog_category`
--

CREATE TABLE `blog_category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_category`
--

INSERT INTO `blog_category` (`id`, `title`, `slug`) VALUES
(1, 'First Cat!', 'first-cat'),
(3, 'ipsum cat', 'ipsum-cat'),
(4, 'New one', 'new-one'),
(10, 'asd', 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `blog_tag`
--

CREATE TABLE `blog_tag` (
  `blog_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_tag`
--

INSERT INTO `blog_tag` (`blog_id`, `tag_id`) VALUES
(6, 1),
(6, 3),
(11, 2),
(11, 5),
(14, 1),
(18, 1),
(18, 2);

-- --------------------------------------------------------

--
-- Table structure for table `blog_user`
--

CREATE TABLE `blog_user` (
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_user`
--

INSERT INTO `blog_user` (`blog_id`, `user_id`) VALUES
(8, 6),
(10, 1),
(10, 5),
(11, 1),
(11, 4),
(11, 5),
(18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `blog_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 'first comment', '2020-12-04 13:40:58', '2020-12-04 13:40:58'),
(2, 1, 10, 'asd', '2020-12-04 13:42:10', '2020-12-04 13:42:10'),
(3, 1, 10, 's', '2020-12-04 13:42:31', '2020-12-04 13:42:31'),
(4, 1, 10, 'asd', '2020-12-04 14:46:51', '2020-12-04 14:46:51'),
(5, 1, 8, 'test', '2020-12-04 16:41:54', '2020-12-04 16:41:54'),
(6, 1, 1, 'hi', '2020-12-07 14:53:26', '2020-12-07 14:53:26'),
(7, 1, 11, 'best', '2020-12-10 09:50:29', '2020-12-10 09:50:29'),
(8, 1, 14, '...', '2020-12-10 13:41:43', '2020-12-10 13:41:43'),
(9, 1, 11, 'best2', '2020-12-10 09:50:29', '2020-12-10 09:50:29'),
(10, 1, 14, 'hi', '2020-12-15 13:45:53', '2020-12-15 15:44:11'),
(11, 5, 14, '<script>alert(\'Hi!\')</script>', '2020-12-15 13:49:45', '2020-12-15 13:49:45'),
(13, 1, 14, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fermentum vestibulum hendrerit. Vivamus sodales tellus vitae leo vehicula, quis semper enim ullamcorper. Morbi nec neque euismod, auctor lorem non, mollis ante. Duis accumsan ex justo, eleifend efficitur velit sodales vitae. Sed tempor pharetra tempus. Integer maximus, erat non commodo sodales, lacus sem mollis enim, et pharetra arcu mi sed nibh. Maecenas urna neque, auctor et imperdiet ac, aliquet at mi. Sed et ligula ac nibh luctus iaculis. Sed efficitur eros a sollicitudin accumsan. Nullam rhoncus, velit non commodo ornare, libero elit accumsan ligula, et dignissim magna nulla sed metus. Curabitur luctus, est at laoreet sollicitudin, sem arcu viverra lectus, ut interdum metus metus at augue. Vestibulum arcu diam, cursus in est at, tristique ultricies augue. Cras sit amet quam a sapien ornare sollicitudin ac quis dolor.', '2020-12-15 13:57:01', '2020-12-15 13:57:01');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201202142110', '2020-12-02 14:24:17', 64),
('DoctrineMigrations\\Version20201203073838', '2020-12-03 07:38:52', 691),
('DoctrineMigrations\\Version20201203125713', '2020-12-03 12:57:32', 62),
('DoctrineMigrations\\Version20201203131953', '2020-12-03 13:19:59', 49),
('DoctrineMigrations\\Version20201203141819', '2020-12-03 14:18:32', 51),
('DoctrineMigrations\\Version20201203144322', '2020-12-03 14:43:36', 58),
('DoctrineMigrations\\Version20201204074213', '2020-12-04 07:42:19', 174),
('DoctrineMigrations\\Version20201204100117', '2020-12-04 10:01:49', 352),
('DoctrineMigrations\\Version20201207074917', '2020-12-07 08:49:30', 666),
('DoctrineMigrations\\Version20201208141123', '2020-12-08 15:11:28', 192),
('DoctrineMigrations\\Version20201209095610', '2020-12-09 10:56:22', 74),
('DoctrineMigrations\\Version20201209095737', '2020-12-09 10:57:41', 160),
('DoctrineMigrations\\Version20201209133441', '2020-12-09 14:34:46', 71),
('DoctrineMigrations\\Version20201210130147', '2020-12-10 14:02:07', 526),
('DoctrineMigrations\\Version20201210135014', '2020-12-10 14:50:43', 55),
('DoctrineMigrations\\Version20201210135609', '2020-12-10 14:57:13', 162);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'first', 'first', '2020-12-10 09:00:05', '2020-12-10 15:00:05'),
(2, 'second', 'second', '2020-12-10 12:40:48', '2020-12-10 15:00:05'),
(3, 'third', 'third', '2020-12-10 15:00:05', '2020-12-11 08:13:35'),
(5, 'one more', 'one-more', '2020-12-15 15:00:05', '2020-12-15 08:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`) VALUES
(1, 'admin@email.com', '[\"ROLE_ADMIN\"]', '$argon2i$v=19$m=65536,t=4,p=1$SGJ3b0VQY0NiNUh2b1U4Vw$/quALXtChAbf7Ywvh5aLaFTIFkYMdt+v2gKSxuthg/s', 'admin'),
(4, 'test@email.com', '[]', '$argon2i$v=19$m=65536,t=4,p=1$Vlh3TE45UzgwQi5ZdEt4UA$uP/lTwNFpIlXN99prgJ+eqP1XNc94g9FDyUBs82KzBA', 'test'),
(5, 'asd@asd.asd', '[]', '$argon2i$v=19$m=65536,t=4,p=1$TFIvUXVYZEhWSFRrYmIvbA$+Laee+x4O3Vifh0MdSveg4CPfrr/fSvCDE4QgTzfuR8', 'asd'),
(6, 'asd@asd3.asd', '[]', '$argon2i$v=19$m=65536,t=4,p=1$RDVJSFRIYWJOeUhvYUVIWA$+nKDPjnSBhTj3ZeUrj7yIe1kjKddMs5+C+9VkY/Xtt8', 'asd3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C0155143A76ED395` (`user_id`),
  ADD KEY `IDX_C015514312469DE2` (`category_id`);

--
-- Indexes for table `blog_category`
--
ALTER TABLE `blog_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_72113DE6989D9B62` (`slug`);

--
-- Indexes for table `blog_tag`
--
ALTER TABLE `blog_tag`
  ADD PRIMARY KEY (`blog_id`,`tag_id`),
  ADD KEY `IDX_6EC3989DAE07E97` (`blog_id`),
  ADD KEY `IDX_6EC3989BAD26311` (`tag_id`);

--
-- Indexes for table `blog_user`
--
ALTER TABLE `blog_user`
  ADD PRIMARY KEY (`blog_id`,`user_id`),
  ADD KEY `IDX_6D435AD9DAE07E97` (`blog_id`),
  ADD KEY `IDX_6D435AD9A76ED395` (`user_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`),
  ADD KEY `IDX_9474526CDAE07E97` (`blog_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_389B783989D9B62` (`slug`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `blog_category`
--
ALTER TABLE `blog_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `FK_C015514312469DE2` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`id`),
  ADD CONSTRAINT `FK_C0155143A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `blog_tag`
--
ALTER TABLE `blog_tag`
  ADD CONSTRAINT `FK_6EC3989BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6EC3989DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_user`
--
ALTER TABLE `blog_user`
  ADD CONSTRAINT `FK_6D435AD9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6D435AD9DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
