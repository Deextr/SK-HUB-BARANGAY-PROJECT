-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 07:57 AM
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
  `reason` varchar(200) DEFAULT NULL,
  `is_full_day` tinyint(1) NOT NULL DEFAULT 1,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('pending','active') NOT NULL DEFAULT 'pending',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `closure_periods`
--

INSERT INTO `closure_periods` (`id`, `start_date`, `end_date`, `reason`, `is_full_day`, `start_time`, `end_time`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2025-11-08', '2025-11-09', NULL, 1, NULL, NULL, 'active', NULL, '2025-11-07 04:32:31', '2025-11-07 04:32:31');

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
(4, '2025_09_26_000000_create_closure_periods_table', 1),
(5, '2025_09_27_000000_create_services_table', 1),
(6, '2025_09_28_151145_create_reservations_table', 1),
(7, '2025_11_07_121854_optimize_users_table_structure', 1),
(8, '2025_11_07_121859_optimize_services_table_structure', 1),
(9, '2025_11_07_121904_optimize_reservations_table_structure', 1);

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
  `closure_period_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(20) NOT NULL,
  `reservation_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `actual_time_in` time DEFAULT NULL,
  `actual_time_out` time DEFAULT NULL,
  `units_reserved` smallint(5) UNSIGNED DEFAULT 1,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `preferences` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `preferences`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, 'RSV-20251107-8E5A11', '2025-11-07', '08:00:00', '08:30:00', NULL, NULL, 1, 'completed', NULL, '2025-11-07 04:33:52', '2025-11-07 04:34:01'),
(2, 4, 1, NULL, 'RSV-20251107-B45628', '2025-11-07', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-07 04:50:56', '2025-11-07 04:58:53'),
(3, 4, 1, NULL, 'RSV-20251107-4B3E71', '2025-11-07', '13:30:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, '2025-11-07 05:02:51', '2025-11-07 05:02:51'),
(4, 3, 1, NULL, 'RSV-20251107-320349', '2025-11-10', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-07 05:19:48', '2025-11-07 05:20:30'),
(5, 2, 1, NULL, 'RSV-20251110-AD392B', '2025-11-11', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-10 06:39:22', '2025-11-10 06:39:32'),
(6, 2, 1, NULL, 'RSV-20251110-F427E0', '2025-11-13', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-10 06:45:40', '2025-11-10 06:46:30'),
(7, 2, 1, NULL, 'RSV-20251111-9C5029', '2025-11-12', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-11 06:30:46', '2025-11-11 06:31:06'),
(8, 2, 3, NULL, 'RSV-20251111-E658F3', '2025-11-13', '08:00:00', '09:30:00', NULL, NULL, 1, 'cancelled', NULL, '2025-11-11 06:52:03', '2025-11-11 06:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `capacity_units` smallint(5) UNSIGNED DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `capacity_units`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PC', 'Med-Specs PC', 8, 1, NULL, '2025-11-07 04:30:58', '2025-11-07 04:30:58'),
(2, 'Study Table', 'Study Area', 8, 1, NULL, '2025-11-07 04:31:14', '2025-11-07 04:31:14'),
(3, 'TV', 'Samsung TV', 1, 1, NULL, '2025-11-07 04:31:43', '2025-11-07 04:31:43');

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
('fmDxiXtWJMLbG1nQFdMwXuMrGaAL0anmSODFmkmZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVBJcllGYlNIR09mOHhCdW1jUHRRbkVha1RlRXJCd0pUa2htb3ZCdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1762844216),
('Q0x2FBRCxCxzgCz63ggjRB4x1KQZ1SdsnP3wfazn', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicDlaaWdGMzZwSjJnYkUzSmVycDdTRkxYQ2p1bkNERXRXeU1QeTNySyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1762843474);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `is_admin`, `account_status`, `id_image_path`, `rejection_reason`, `approved_at`, `rejected_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@example.com', NULL, '$2y$12$Otkxflz7bBftsowqBAWYK.9QDstc.NytLLD.tj0KXBIZBaIleiprC', 1, 'approved', NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:24:37', '2025-11-07 04:24:37'),
(2, 'dexter', 'ramos', 'dexter@gmail.com', NULL, '$2y$12$4ZtNaKOp44UeiBnNPgIvMO9q60M9dndKVIZCMXdY3KBQ0AJXRRMTW', 0, 'approved', 'id_images/Ns65vTVI0Ipdv8etILXZ5yplPQRB8a6yHKFPYWvn.jpg', NULL, '2025-11-07 04:30:15', NULL, NULL, '2025-11-07 04:27:24', '2025-11-07 04:30:15'),
(3, 'nathaniel', 'bayona', 'nath@gmail.com', NULL, '$2y$12$ZIElb4wZV4KYyTNGuEGSNuiZAfnucazJwBHVmUB2zwrI21yngIBqu', 0, 'approved', 'id_images/IiUlejX0nTHNBLUtQ9avk8X3o0kQQEMX5W7cCs5z.jpg', NULL, '2025-11-07 04:30:12', NULL, NULL, '2025-11-07 04:28:21', '2025-11-07 04:30:12'),
(4, 'nesa', 'Limpuasan', 'nesa@gmail.com', NULL, '$2y$12$t/uZaph8kEfEs9UTyvsucOzOd.Ii5GZEnqgH/xht6MtpYAZvnX5Sm', 0, 'approved', 'id_images/lh2btGOYvTDjSnsqHzlOdnaNjZKMiGU2WO1KhR3t.jpg', NULL, '2025-11-07 04:30:09', NULL, NULL, '2025-11-07 04:28:43', '2025-11-07 04:30:09'),
(5, 'christian', 'torion', 'chan@gmail.com', NULL, '$2y$12$roXAE.qFlXmADz6zT..dG.7ZfvTvoJXLn6LF.r9/Jkil07lRqgn7i', 0, 'rejected', 'id_images/qUA9Y81E4jqjRq5Cr6pPNEf5Lqdy4YHW9tGF7LcR.jpg', 'Please used your ID', NULL, '2025-11-07 04:30:02', NULL, '2025-11-07 04:29:13', '2025-11-07 04:30:02'),
(6, 'Admin', 'User', 'admin@gmail.com', NULL, '$2y$12$Y0.SqhfH7UkF0T9tJ1HWSOiwirsU4uLPOzNahJO/rK8n4of7gW466', 1, 'approved', NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:43:54', '2025-11-07 04:43:54');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `closure_periods_start_date_end_date_index` (`start_date`,`end_date`),
  ADD KEY `closure_periods_status_index` (`status`),
  ADD KEY `closure_periods_is_full_day_index` (`is_full_day`);

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
  ADD KEY `reservations_service_id_foreign` (`service_id`),
  ADD KEY `reservations_reservation_date_service_id_index` (`reservation_date`,`service_id`),
  ADD KEY `reservations_user_id_reservation_date_index` (`user_id`,`reservation_date`),
  ADD KEY `reservations_status_index` (`status`),
  ADD KEY `reservations_closure_period_id_index` (`closure_period_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_is_active_index` (`is_active`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_account_status_index` (`account_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `closure_periods`
--
ALTER TABLE `closure_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_closure_period_id_foreign` FOREIGN KEY (`closure_period_id`) REFERENCES `closure_periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
