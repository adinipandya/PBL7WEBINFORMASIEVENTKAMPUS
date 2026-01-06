-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2025 at 11:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_event_kampus`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(200) NOT NULL,
  `contact` varchar(120) NOT NULL,
  `attachment` text,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `category_id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`, `contact`, `attachment`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Seminar Polibatam', 'Seminar umum Polibatam.', '2025-10-23', NULL, NULL, 'Auditorium Politeknik Negeri Batam', '0812xxxxxxx', NULL, 'published', 1, '2025-12-19 07:38:27', NULL),
(2, 2, 'Bazar Inovasi Mahasiswa Polibatam 2025', 'Bazar inovasi dan pameran karya mahasiswa.', '2025-10-25', NULL, NULL, 'Politeknik Negeri Batam', '0812xxxxxxx', NULL, 'published', 1, '2025-12-19 07:38:27', NULL),
(3, 3, 'Kompetisi Wawasan Bisnis', 'Kompetisi wawasan bisnis tingkat kampus.', '2025-10-26', NULL, NULL, 'Auditorium Politeknik Negeri Batam', '0812xxxxxxx', NULL, 'published', 1, '2025-12-19 07:38:27', NULL),
(4, 4, 'Green Campus Festival 2025', 'Festival kampus hijau: pameran, aksi lingkungan, edukasi, dan hiburan ramah lingkungan.', '2025-12-20', NULL, NULL, 'Area Kampus & Taman Ormawa Politeknik Negeri Batam', '0812xxxxxxx', NULL, 'published', 1, '2025-12-22 04:47:27', '2025-12-22 04:47:27');

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE `event_categories` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`id`, `slug`, `name`, `is_active`, `created_at`) VALUES
(1, 'seminar', 'Seminar', 1, '2025-12-19 07:30:43'),
(2, 'lokakarya', 'Lokakarya', 1, '2025-12-19 07:30:43'),
(3, 'kompetisi', 'Kompetisi', 1, '2025-12-19 07:30:43'),
(4, 'festival', 'Festival', 1, '2025-12-19 07:30:43');

-- --------------------------------------------------------

--
-- Table structure for table `event_competition_details`
--

CREATE TABLE `event_competition_details` (
  `event_id` bigint UNSIGNED NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `registration_deadline` date DEFAULT NULL,
  `rules` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_competition_details`
--

INSERT INTO `event_competition_details` (`event_id`, `theme`, `prize`, `registration_deadline`, `rules`) VALUES
(3, 'Membangun Jiwa Wirausaha Kreatif dan Kompetitif di Era Digital', 'Total hadiah Rp 10.000.000 + sertifikat + mentoring', '2025-10-20', 'Tim 3-5 orang. Presentasi 10 menit + QnA 5 menit. Penilaian: inovasi, kelayakan, dampak.');

-- --------------------------------------------------------

--
-- Table structure for table `event_competition_requirements`
--

CREATE TABLE `event_competition_requirements` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `requirement_text` varchar(255) NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_competition_requirements`
--

INSERT INTO `event_competition_requirements` (`id`, `event_id`, `requirement_text`, `sort_order`) VALUES
(1, 3, 'Mahasiswa aktif (dibuktikan dengan KTM).', 1),
(2, 3, 'Tim terdiri dari 3-5 orang.', 2),
(3, 3, 'Wajib membawa pitch deck (PDF).', 3),
(4, 3, 'Ide bisnis orisinal dan belum pernah menang lomba serupa.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `event_festival_details`
--

CREATE TABLE `event_festival_details` (
  `event_id` bigint UNSIGNED NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `is_ticketed` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_price` decimal(12,2) DEFAULT NULL,
  `area_note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_festival_details`
--

INSERT INTO `event_festival_details` (`event_id`, `theme`, `is_ticketed`, `ticket_price`, `area_note`) VALUES
(4, 'Go Green, Go Future: Aksi Nyata Mahasiswa untuk Bumi', 0, NULL, 'Bawa tumbler sendiri. Disarankan pakai pakaian nyaman.');

-- --------------------------------------------------------

--
-- Table structure for table `event_festival_lineups`
--

CREATE TABLE `event_festival_lineups` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `item_note` varchar(255) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_festival_lineups`
--

INSERT INTO `event_festival_lineups` (`id`, `event_id`, `item_name`, `item_note`, `sort_order`) VALUES
(1, 4, 'Pameran Daur Ulang', 'Booth karya mahasiswa berbahan daur ulang', 1),
(2, 4, 'Workshop Eco Craft', 'Membuat kerajinan dari barang bekas', 2),
(3, 4, 'Music Corner', 'Penampilan band kampus konsep ramah lingkungan', 3);

-- --------------------------------------------------------

--
-- Table structure for table `event_festival_rundowns`
--

CREATE TABLE `event_festival_rundowns` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `activity` varchar(200) NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_festival_rundowns`
--

INSERT INTO `event_festival_rundowns` (`id`, `event_id`, `start_time`, `end_time`, `activity`, `sort_order`) VALUES
(1, 4, '08:00:00', '09:00:00', 'Registrasi & pembukaan', 1),
(2, 4, '09:00:00', '12:00:00', 'Pameran + Booth edukasi lingkungan', 2),
(3, 4, '13:00:00', '15:00:00', 'Workshop Eco Craft', 3),
(4, 4, '15:00:00', '16:00:00', 'Music Corner + penutupan', 4);

-- --------------------------------------------------------

--
-- Table structure for table `event_instructors`
--

CREATE TABLE `event_instructors` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `title_role` varchar(200) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_instructors`
--

INSERT INTO `event_instructors` (`id`, `event_id`, `name`, `title_role`, `sort_order`) VALUES
(1, 2, 'Rizky Ananda, S.Ds.', 'Videografer & Editor Profesional', 1),
(2, 2, 'Tania Lestari, S.I.Kom.', 'Digital Marketing Specialist', 2);

-- --------------------------------------------------------

--
-- Table structure for table `event_judges`
--

CREATE TABLE `event_judges` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `title_role` varchar(200) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_judges`
--

INSERT INTO `event_judges` (`id`, `event_id`, `name`, `title_role`, `sort_order`) VALUES
(1, 3, 'Prof. Dr. Ir. Siti Aisyah, M.Sc.', 'Pakar Inovasi Teknologi Nasional', 1),
(2, 3, 'Ir. Heru Susanto, M.Kom.', 'Ketua Jurusan Teknik Informatika Polibatam', 2);

-- --------------------------------------------------------

--
-- Table structure for table `event_seminar_details`
--

CREATE TABLE `event_seminar_details` (
  `event_id` bigint UNSIGNED NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `quota` int DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_seminar_details`
--

INSERT INTO `event_seminar_details` (`event_id`, `theme`, `quota`, `registration_link`) VALUES
(1, 'Membangun Generasi Inovatif di Era Transformasi Digital', 300, 'https://example.com/daftar-seminar');

-- --------------------------------------------------------

--
-- Table structure for table `event_speakers`
--

CREATE TABLE `event_speakers` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `title_role` varchar(200) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_speakers`
--

INSERT INTO `event_speakers` (`id`, `event_id`, `name`, `title_role`, `sort_order`) VALUES
(1, 1, 'Ir. Budi Santoso, M.Eng.', 'Kepala Divisi Inovasi Digital Kemenkominfo', 1),
(2, 1, 'Dr. Yusriadi, M.T.', 'Dosen Teknik Informatika Polibatam', 2);

-- --------------------------------------------------------

--
-- Table structure for table `event_workshop_details`
--

CREATE TABLE `event_workshop_details` (
  `event_id` bigint UNSIGNED NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `tools_required` text,
  `quota` int DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_workshop_details`
--

INSERT INTO `event_workshop_details` (`event_id`, `theme`, `tools_required`, `quota`, `registration_link`) VALUES
(2, 'Menciptakan Visual yang Berbicara', 'Laptop, aplikasi editing (CapCut/Premiere), headphone', 60, 'https://example.com/daftar-lokakarya');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(120) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `cover_image` text,
  `published_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `slug`, `title`, `content`, `cover_image`, `published_at`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Berita kE 2', 'bERITA KE 2', 'YAYAA', 'assets/cover-Berita-kE-2-2.jpg', '2025-12-22 12:00:00', 1, 1, '2025-12-22 07:03:45', '2025-12-22 08:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `image_path` text NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `news_images`
--

INSERT INTO `news_images` (`id`, `news_id`, `image_path`, `sort_order`, `created_at`) VALUES
(4, 2, 'assets/news-2-2.jpg', 2, '2025-12-22 08:06:27'),
(5, 2, 'assets/news-2-2-1.jpg', 2, '2025-12-22 08:15:49');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(80) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `content`, `is_active`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'tentang', 'Tentang', 'Isi halaman tentang (silakan ganti).', 1, NULL, '2025-12-19 07:30:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$P4B54abStm1XTAYKZ8SFH.9O7crAO.pTHlp7f6xEv/HFqyWqAQ.MK', 'Administrator', 'admin', 1, '2025-12-19 07:30:43', '2025-12-19 08:05:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_event_list`
-- (See below for the actual view)
--
CREATE TABLE `v_event_list` (
`id` bigint unsigned
,`category_slug` varchar(50)
,`category_name` varchar(80)
,`title` varchar(200)
,`event_date` date
,`location` varchar(200)
,`status` enum('draft','published','archived')
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `v_event_list`
--
DROP TABLE IF EXISTS `v_event_list`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_event_list`  AS SELECT `e`.`id` AS `id`, `c`.`slug` AS `category_slug`, `c`.`name` AS `category_name`, `e`.`title` AS `title`, `e`.`event_date` AS `event_date`, `e`.`location` AS `location`, `e`.`status` AS `status`, `e`.`created_at` AS `created_at` FROM (`events` `e` join `event_categories` `c` on((`c`.`id` = `e`.`category_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_events_date` (`event_date`),
  ADD KEY `idx_events_category` (`category_id`),
  ADD KEY `fk_events_created_by` (`created_by`);

--
-- Indexes for table `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_event_categories_slug` (`slug`);

--
-- Indexes for table `event_competition_details`
--
ALTER TABLE `event_competition_details`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_competition_requirements`
--
ALTER TABLE `event_competition_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comp_req_event` (`event_id`);

--
-- Indexes for table `event_festival_details`
--
ALTER TABLE `event_festival_details`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_festival_lineups`
--
ALTER TABLE `event_festival_lineups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fest_lineup_event` (`event_id`);

--
-- Indexes for table `event_festival_rundowns`
--
ALTER TABLE `event_festival_rundowns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fest_rundown_event` (`event_id`);

--
-- Indexes for table `event_instructors`
--
ALTER TABLE `event_instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_instructors_event` (`event_id`);

--
-- Indexes for table `event_judges`
--
ALTER TABLE `event_judges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_judges_event` (`event_id`);

--
-- Indexes for table `event_seminar_details`
--
ALTER TABLE `event_seminar_details`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_speakers`
--
ALTER TABLE `event_speakers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_speakers_event` (`event_id`);

--
-- Indexes for table `event_workshop_details`
--
ALTER TABLE `event_workshop_details`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_news_slug` (`slug`),
  ADD KEY `idx_news_published` (`published_at`),
  ADD KEY `fk_news_created_by` (`created_by`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_news_images_news` (`news_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pages_slug` (`slug`),
  ADD KEY `fk_pages_updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_categories`
--
ALTER TABLE `event_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_competition_requirements`
--
ALTER TABLE `event_competition_requirements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_festival_lineups`
--
ALTER TABLE `event_festival_lineups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_festival_rundowns`
--
ALTER TABLE `event_festival_rundowns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_instructors`
--
ALTER TABLE `event_instructors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_judges`
--
ALTER TABLE `event_judges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_speakers`
--
ALTER TABLE `event_speakers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `event_competition_details`
--
ALTER TABLE `event_competition_details`
  ADD CONSTRAINT `fk_competition_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_competition_requirements`
--
ALTER TABLE `event_competition_requirements`
  ADD CONSTRAINT `fk_comp_req_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_festival_details`
--
ALTER TABLE `event_festival_details`
  ADD CONSTRAINT `fk_festival_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_festival_lineups`
--
ALTER TABLE `event_festival_lineups`
  ADD CONSTRAINT `fk_fest_lineup_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_festival_rundowns`
--
ALTER TABLE `event_festival_rundowns`
  ADD CONSTRAINT `fk_fest_rundown_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_instructors`
--
ALTER TABLE `event_instructors`
  ADD CONSTRAINT `fk_instructors_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_judges`
--
ALTER TABLE `event_judges`
  ADD CONSTRAINT `fk_judges_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_seminar_details`
--
ALTER TABLE `event_seminar_details`
  ADD CONSTRAINT `fk_seminar_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_speakers`
--
ALTER TABLE `event_speakers`
  ADD CONSTRAINT `fk_speakers_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_workshop_details`
--
ALTER TABLE `event_workshop_details`
  ADD CONSTRAINT `fk_workshop_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `fk_news_images_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `fk_pages_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
