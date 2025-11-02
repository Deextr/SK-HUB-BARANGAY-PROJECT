-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 07:12 AM
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
(10, '2025-11-02', '2025-11-05', 'holiday', 1, NULL, NULL, 'active', '2025-11-02 04:37:26', '2025-11-02 04:38:49', NULL);

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
(11, '2025_10_05_000400_add_soft_deletes_to_closure_periods', 8),
(12, '2025_10_20_150124_make_time_fields_required_in_reservations', 9),
(13, '2025_01_20_000000_add_user_profiling_fields', 10),
(14, '2025_01_20_000001_remove_gmail_username_from_users', 11),
(15, '2025_01_20_000002_fix_existing_admin_accounts', 12);

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
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
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
(32, 9, 4, 'RSV-20251025-A69CE1', '2025-11-12', '14:00:00', '17:00:00', '20:29:00', '20:30:00', 1, 'completed', NULL, '2025-10-25 12:26:08', '2025-10-25 12:29:30'),
(33, 9, 2, 'RSV-20251101-CDDF0D', '2025-11-15', '08:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-01 12:13:07', '2025-11-01 12:14:42'),
(34, 9, 1, 'RSV-20251101-687E33', '2025-11-08', '08:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-01 12:15:05', '2025-11-01 12:22:14'),
(35, 12, 1, 'RSV-20251102-755285', '2025-11-09', '08:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-02 03:47:40', '2025-11-02 03:49:44'),
(36, 9, 1, 'RSV-20251102-852935', '2025-11-04', '08:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-02 04:37:56', '2025-11-02 04:38:49'),
(37, 12, 1, 'RSV-20251102-AC2939', '2025-11-03', '08:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-02 04:38:16', '2025-11-02 04:38:49'),
(38, 9, 1, 'RSV-20251102-B00147', '2025-11-06', '08:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-02 04:50:28', '2025-11-02 04:51:11');

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
(1, 'PC', 'High End PC\'s', 8, 1, '2025-09-30 04:36:58', '2025-10-05 14:49:40', NULL),
(2, 'Study Table', NULL, 8, 1, '2025-09-30 04:37:26', '2025-11-01 12:27:03', NULL),
(3, 'Cubicle', 'This is cubicle', 7, 0, '2025-09-30 07:08:12', '2025-11-02 05:25:55', NULL),
(4, 'TV', 'Sony', 2, 1, '2025-09-30 16:23:46', '2025-10-05 14:50:05', NULL),
(5, 'Gaming Chair', NULL, 3, 0, '2025-10-01 10:08:22', '2025-11-02 05:25:45', NULL),
(6, 'Printer', '10 pages per person', 10, 0, '2025-11-02 05:26:21', '2025-11-02 05:26:34', NULL),
(7, 'Charging Area', 'Charge cp', 2, 0, '2025-11-02 05:26:56', '2025-11-02 05:27:08', NULL);

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
('4b9iE0NElElyrPjmP6kONgzFZXsm8l202Wucov8y', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXRCbUFhMDg1T1lESXJZU3VuaHl5UGtqcDc4TXpES2J0a2p5RUlaeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZXNpZGVudC9yZXNlcnZhdGlvbi90aW1lLXNsb3RzP2RhdGU9MjAyNS0xMS0wNiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1762063845),
('9oX8rXVktHfxi1qIJAhyK16DCoWdSHXTGFiW0h7i', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTTJnOVpVajhLZGpuZ0txanR0T0dobGNERmlFRE4xemtUZXVCYWxscyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZXNpZGVudC9yZXNlcnZhdGlvbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO30=', 1762058360),
('tl8yBlcRyvdjMNFF4JqBxBpugzEb3wWx6Oo4ThXY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiR09yUldWZjlvZ2o5dXRKdlpVNXpWWVJPRVRUQmF4SER3Y0QzMzBXTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762054828);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `account_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `id_image_path` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `is_admin`, `account_status`, `id_image_path`, `rejection_reason`, `approved_at`, `rejected_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'emerson', 'emerson', 'User', 'emerson@gmail.com', NULL, '$2y$12$NFVL/GfqizBIFLJNPB7Ml.RfpTYKXsCdfkgkEo2nZqfGlXGaA796W', 1, 'approved', NULL, NULL, '2025-10-21 13:43:10', NULL, NULL, '2025-09-29 04:29:07', '2025-10-21 13:43:10'),
(8, 'Admin', 'Admin', 'User', 'admin@example.com', NULL, '$2y$12$pMmoSaQG60MKEEJ6eH0MA.7ChkAvme2.PFgwOAmZ2UdmKi.FYqOWO', 1, 'approved', NULL, NULL, NULL, NULL, NULL, '2025-10-21 13:41:14', '2025-10-21 13:41:14'),
(9, 'dexter ramos', 'dexter', 'ramos', 'dexter@gmail.com', NULL, '$2y$12$dOabwl4JLHK8JLiXOXEdau.5U8012Y4qXnswTJrGMDjhUD8UlC8ka', 0, 'approved', 'id_images/UYR36N8wvtJ9H2nzejmV3Vy2k3lu9T37IJ4o6hGa.jpg', NULL, '2025-10-21 14:47:30', NULL, NULL, '2025-10-21 13:46:28', '2025-10-21 14:47:30'),
(10, 'chan tornits', 'chan', 'tornits', 'tornits@gmail.com', NULL, '$2y$12$7xySB2pZQ6HokruajXfZUOXVOHifLr/jvFTuQTD5QNrNUo7XeyRw.', 0, 'rejected', 'id_images/mSXqMfFNn1HzbLBYKyf1sFPooHzRhFR7izqv35df.jpg', 'The ID is shows you\'re not belong in this barangay.', NULL, '2025-10-21 14:49:35', NULL, '2025-10-21 14:48:26', '2025-10-21 14:49:35'),
(11, 'chan tornits', 'chan', 'tornits', 'tornit@gmail.com', NULL, '$2y$12$L./9/H9la/5ZUxEN.FeQBez9DQfACF.QaXTdycpyRzUshAwESxlrK', 0, 'pending', 'id_images/07gVJ2zktvKusDBv4dIO1iRbuBBd418UEvGw3wwC.jpg', NULL, NULL, NULL, NULL, '2025-10-21 14:50:55', '2025-10-21 14:50:55'),
(12, 'nathaniel bayona', 'nathaniel', 'bayona', 'nathaniel@gmail.com', NULL, '$2y$12$uxsozfIndYJkuUGqS2Vyt.9aVCNhu0XGBrx6Cqo5J5sl8vbXrVlVe', 0, 'approved', 'id_images/URd1OQdhF3hf1KajE0l3i1zDqJcaklnA4tYyfEW2.jpg', NULL, '2025-11-02 03:44:24', NULL, NULL, '2025-10-22 00:11:04', '2025-11-02 03:44:24'),
(13, 'Nesa Limpuasan', 'Nesa', 'Limpuasan', 'nesa@gmail.com', NULL, '$2y$12$KU680KfKchzkRj4JmljlnO8D63SMqH7mYpcsTyzdLCIZ5LtbL.Bgu', 0, 'approved', 'id_images/JjedYr8uicRaAVOpHrI7HlRtg8mmZlDL10XxAhS6.png', NULL, '2025-11-02 03:44:31', NULL, NULL, '2025-10-25 12:15:28', '2025-11-02 03:44:31');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
