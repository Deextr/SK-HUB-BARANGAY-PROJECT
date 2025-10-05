-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2025 at 01:51 PM
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
-- Database: `barangay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `closure_periods`
--

CREATE TABLE `closure_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_full_day` tinyint(1) NOT NULL DEFAULT 1,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `closure_periods`
--

INSERT INTO `closure_periods` (`id`, `start_date`, `end_date`, `reason`, `is_full_day`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, '2025-10-26', '2025-10-27', 'Seminar', 1, NULL, NULL, 'active', '2025-10-05 08:12:52', '2025-10-05 08:12:57', NULL),
(5, '2025-10-17', '2025-10-17', 'Outing', 1, NULL, NULL, 'active', '2025-10-05 09:50:57', '2025-10-05 09:50:57', NULL),
(6, '2025-10-30', '2025-10-30', NULL, 1, NULL, NULL, 'pending', '2025-10-05 11:30:24', '2025-10-05 11:49:13', '2025-10-05 11:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_28_151145_create_reservations_table', 1),
(5, '2025_09_27_000000_create_services_table', 2),
(6, '2025_09_30_000000_alter_reservations_add_fields', 3),
(7, '2025_10_01_000001_add_soft_deletes_to_services_table', 4),
(8, '2025_10_01_000100_create_closure_periods_table', 5),
(9, '2025_10_01_000101_add_status_to_closure_periods', 6),
(10, '2025_10_05_000300_add_actual_times_to_reservations', 7),
(11, '2025_10_05_000400_add_soft_deletes_to_closure_periods', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `reservation_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `actual_time_in` time DEFAULT NULL,
  `actual_time_out` time DEFAULT NULL,
  `units_reserved` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `preferences` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `preferences`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'RSV-20250930-551AF2', '2025-10-01', '08:00:00', '17:00:00', NULL, NULL, 1, 'completed', NULL, '2025-09-30 07:16:52', '2025-10-01 09:48:36'),
(2, 4, 1, 'RSV-20251001-E0CD6E', '2025-10-08', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-09-30 16:20:11', '2025-09-30 16:21:40'),
(3, 4, 4, 'RSV-20251001-A4717A', '2025-10-03', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-09-30 16:25:08', '2025-09-30 16:25:57'),
(4, 4, 1, 'RSV-20251001-213882', '2025-10-03', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-01 09:21:29', '2025-10-01 09:22:59'),
(5, 4, 1, 'RSV-20251001-64DA42', '2025-10-15', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-01 09:26:30', '2025-10-01 09:26:42'),
(11, 4, 2, 'RSV-20251001-4EA046', '2025-10-25', '14:50:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-01 13:29:53', '2025-10-01 13:30:14'),
(12, 4, 4, 'RSV-20251001-A856D0', '2025-10-20', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Special Assistant', '2025-10-01 14:09:39', '2025-10-01 14:17:42'),
(13, 4, 1, 'RSV-20251005-8642D7', '2025-10-28', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 07:35:26', '2025-10-05 07:43:58'),
(14, 4, 1, 'RSV-20251005-2CBA4A', '2025-10-24', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 07:44:56', '2025-10-05 07:57:22'),
(15, 4, 1, 'RSV-20251005-0AFCE9', '2025-10-25', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 07:57:39', '2025-10-05 07:58:30'),
(16, 4, 1, 'RSV-20251005-EEBD6F', '2025-10-26', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 07:58:17', '2025-10-05 08:03:11'),
(17, 4, 1, 'RSV-20251005-92CA40', '2025-10-29', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 08:40:25', '2025-10-05 08:42:52'),
(18, 4, 1, 'RSV-20251005-37735D', '2025-10-22', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 08:49:50', '2025-10-05 08:53:58'),
(19, 4, 1, 'RSV-20251005-5B74E1', '2025-10-06', '08:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-10-05 09:12:33', '2025-10-05 09:13:02'),
(20, 4, 3, 'RSV-20251005-5AF675', '2025-10-18', '08:00:00', '17:00:00', '09:06:00', '14:07:00', 1, 'completed', NULL, '2025-10-05 10:59:10', '2025-10-05 11:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `capacity_units` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `capacity_units`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PC', NULL, 8, 1, '2025-09-30 04:36:58', '2025-09-30 04:43:21', NULL),
(2, 'Study Table', NULL, 6, 1, '2025-09-30 04:37:26', '2025-09-30 04:37:26', NULL),
(3, 'Cubicle', NULL, 7, 1, '2025-09-30 07:08:12', '2025-09-30 07:08:12', NULL),
(4, 'TV', NULL, 2, 1, '2025-09-30 16:23:46', '2025-09-30 16:23:46', NULL),
(5, 'Gaming Chair', NULL, 3, 0, '2025-10-01 10:08:22', '2025-10-01 10:30:29', '2025-10-01 10:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ViVjmO5WWRNy4rhXuj4DeJiWiijIUE50gIn7oZS4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDFPOGVXNXRYdDRHcTBXWGEzZ3pFaWNMenNZUWNDZEp6VlN6WmdXcSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1759665001);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'emerson', 'emerson@gmail.com', NULL, '$2y$12$NFVL/GfqizBIFLJNPB7Ml.RfpTYKXsCdfkgkEo2nZqfGlXGaA796W', 1, NULL, '2025-09-29 04:29:07', '2025-09-29 04:29:07'),
(2, 'resident', 'resident@gmail.com', NULL, '$2y$12$sS/sek5Ly0cLTMTJHQQ5XOXknNyaWuLGVDjFlhK97gW/IJ2rebuxe', 0, NULL, '2025-09-29 05:00:17', '2025-09-29 05:00:17'),
(3, 'bayona', 'bayona@gmail.com', NULL, '$2y$12$gBd52oIr88UCMpc4WPv9R.qZ6OTxLkcLpZimR0S8JluQuWmXEGXfW', 0, NULL, '2025-09-29 07:17:54', '2025-09-29 07:17:54'),
(4, 'dexter', 'dexter@gmail.com', NULL, '$2y$12$uiEzF0OlD16wXt9wvw85C.AP.hwBGJSXGjPDxiSvINMmg6j5ROX8.', 0, NULL, '2025-09-30 04:18:42', '2025-09-30 04:18:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `closure_periods`
--
ALTER TABLE `closure_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservations_reference_no_unique` (`reference_no`),
  ADD KEY `reservations_user_id_foreign` (`user_id`),
  ADD KEY `reservations_service_id_foreign` (`service_id`),
  ADD KEY `reservations_reservation_date_service_id_index` (`reservation_date`,`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `closure_periods`
--
ALTER TABLE `closure_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
