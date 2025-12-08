-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 05:44 AM
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
  `status` enum('pending','active') NOT NULL DEFAULT 'pending',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

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
(9, '2025_11_07_121904_optimize_reservations_table_structure', 1),
(10, '2025_11_12_000001_make_closure_periods_full_day', 1),
(11, '2025_11_15_201938_add_age_sex_is_pwd_to_users_table', 1),
(12, '2025_11_16_161336_replace_age_with_birth_date_in_users_table', 1),
(13, '2025_11_16_180000_add_reservation_reason_to_reservations_table', 1),
(14, '2025_11_16_190000_add_suspension_fields_to_users_table', 1),
(15, '2025_11_16_190100_add_cancellation_reason_to_reservations_table', 1),
(16, '2025_11_16_214357_add_archive_fields_to_users_table', 1),
(17, '2025_11_22_000000_create_service_archives_table', 1),
(18, '2025_11_22_000001_add_cancelled_by_to_reservations_table', 1),
(19, '2025_11_23_000000_add_partial_rejection_to_users_table', 1),
(20, '2025_11_30_000000_create_password_histories_table', 1),
(21, '2025_12_08_000000_add_database_constraints_for_integrity', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_histories`
--

CREATE TABLE `password_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `suspension_applied` tinyint(1) NOT NULL DEFAULT 0,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `reservation_reason` varchar(255) DEFAULT NULL,
  `other_reason` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

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
) ;

-- --------------------------------------------------------

--
-- Table structure for table `service_archives`
--

CREATE TABLE `service_archives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `units_archived` int(11) NOT NULL,
  `capacity_before` int(11) NOT NULL,
  `capacity_after` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reservations_cancelled` int(11) NOT NULL DEFAULT 0,
  `cancelled_reservation_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cancelled_reservation_ids`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

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
('9dVHT7Pd90VOl07zzThG8dNvrIRMBsQjlWADzEyI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnNwTWtsSExranJQd0lCQjlyRUlNaGhnTGVteUFhSVV0NkU4dXhTciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1765169005);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `is_pwd` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `account_status` enum('pending','approved','partially_rejected','rejected') NOT NULL,
  `suspension_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `archive_reason` text DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `suspension_end_date` timestamp NULL DEFAULT NULL,
  `id_image_path` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `partially_rejected_at` timestamp NULL DEFAULT NULL,
  `partially_rejected_reason` text DEFAULT NULL,
  `resubmission_count` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `birth_date`, `sex`, `is_pwd`, `email`, `email_verified_at`, `password`, `is_admin`, `account_status`, `suspension_count`, `is_suspended`, `is_archived`, `archive_reason`, `archived_at`, `suspension_end_date`, `id_image_path`, `rejection_reason`, `approved_at`, `rejected_at`, `partially_rejected_at`, `partially_rejected_reason`, `resubmission_count`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', NULL, NULL, 0, 'admin@gmail.com', NULL, '$2y$12$dvgj7Uc.IvEeieqLsDaolOhue8oRk4hfimKF3afhuv2F2TX393RBS', 1, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:41', '2025-12-08 04:42:41'),
(2, 'Dexter', 'Ramos', '1990-05-15', 'Male', 0, 'dexter@gmail.com', NULL, '$2y$12$NdSXBeqOi.In.tJYN2sCXepmoLf7MCSnhUrl/sL1KAxUWCYd0lCzq', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(3, 'Nathaniel', 'Bayona', '1992-08-22', 'Male', 0, 'nathaniel@gmail.com', NULL, '$2y$12$rkRjYtb1jN.ppjnfvGSS0Op5tu5gAR3rlZpRCDpzVT3/U.reFe6JK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(4, 'Park', 'Gica', '1988-03-10', 'Male', 0, 'park@gmail.com', NULL, '$2y$12$j2GyDoFpD0BTiTJPwyp3kOlcbqB1laTxu0LqGggh7ykMRaL0i.3ci', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(5, 'Christian', 'Torion', '1995-11-05', 'Male', 0, 'chan@gmail.com', NULL, '$2y$12$Leqev.wyg8lJpUP8MbwdYOCm3KlnNYT.bpvR9uUYRE3fJUGNQ3Sai', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(6, 'Rocky', 'Adaya', '1991-07-18', 'Male', 0, 'rocky@gmail.com', NULL, '$2y$12$3SmvjC912Us5gWM/3pyZIevoY8lMysYLAZQijQI4SuLb9BRwTZHna', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(7, 'Juan', 'Santos', '1993-02-14', 'Male', 0, 'juan@gmail.com', NULL, '$2y$12$dtALVncRVTpEmB4i/PsEX.ATCSvZOLC2vCMnI8O8YkhF02pbZopNK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:42', '2025-12-08 04:42:42'),
(8, 'Miguel', 'Cruz', '1989-09-28', 'Male', 0, 'miguel@gmail.com', NULL, '$2y$12$s9w4WedSG0zS0CXtCpWCCO2l7nVAYuR7dNgyb8l3Ye0Df/eJhvSnu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:43', '2025-12-08 04:42:43'),
(9, 'Carlos', 'Reyes', '1994-04-03', 'Male', 0, 'carlos@gmail.com', NULL, '$2y$12$LhT004ZACC/wsUbhKSUWJ.nYMsHQf6bQYAaqNCVcfNEFTu50gGfiC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:43', '2025-12-08 04:42:43'),
(10, 'Antonio', 'Flores', '1987-12-20', 'Male', 0, 'antonio@gmail.com', NULL, '$2y$12$C96giZ5KrfrYgQUoYKA4V.WKVG8zUErF.PlKwT1sTtKLokiG1VxB6', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:43', '2025-12-08 04:42:43'),
(11, 'Roberto', 'Morales', '1996-06-11', 'Male', 0, 'roberto@gmail.com', NULL, '$2y$12$CohVtfczvHAzU9HYywbbcewJzxnQqAma4a1S5979TCYMxabTget0G', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:43', '2025-12-08 04:42:43'),
(12, 'Maria', 'Garcia', '1991-01-08', 'Female', 0, 'maria@gmail.com', NULL, '$2y$12$EM0f4X7zHEbhp6PdQAoHHOFoDIdlrL.1udwgZ3vBGPdvNKxTJFpeO', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:43', '2025-12-08 04:42:43'),
(13, 'Rosa', 'Lopez', '1993-05-19', 'Female', 0, 'rosa@gmail.com', NULL, '$2y$12$w21k2Lsmw1lVnL3FzH.jDee8UQCtY5LlpVQw6gGAuyyYP.yX09VTW', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:44', '2025-12-08 04:42:44'),
(14, 'Ana', 'Martinez', '1990-10-25', 'Female', 0, 'ana@gmail.com', NULL, '$2y$12$8qlVpVxuMK1XsE7bwi.zpO0ya1qyofnbnINnXuufWSZVhczvaNLeS', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:44', '2025-12-08 04:42:44'),
(15, 'Sofia', 'Hernandez', '1994-03-12', 'Female', 0, 'sofia@gmail.com', NULL, '$2y$12$pknwPIXifPVbVBLwk0TEkeIerxkrNlDszJgarXZnrTbtaqTLnKA4G', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:44', '2025-12-08 04:42:44'),
(16, 'Isabella', 'Perez', '1992-07-30', 'Female', 0, 'isabella@gmail.com', NULL, '$2y$12$SrS81c6yX5NkkQ1udmUBHeVshen3VwHfiMwengNiiJ2NjFvliSOUe', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:44', '2025-12-08 04:42:44'),
(17, 'Carmen', 'Sanchez', '1988-11-06', 'Female', 0, 'carmen@gmail.com', NULL, '$2y$12$2FZrQE7LhiZiDZ9SC530qeHEYIum9qbzqyI0aa68KqWFSbGCeSfJa', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:44', '2025-12-08 04:42:44'),
(18, 'Elena', 'Ramirez', '1995-02-17', 'Female', 0, 'elena@gmail.com', NULL, '$2y$12$AfJKfStgMY8pBJDnzwJAjuLlE85HuuW8A9y/Jor5DPL86MAViBlC6', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:45', '2025-12-08 04:42:45'),
(19, 'Lucia', 'Torres', '1989-08-09', 'Female', 0, 'lucia@gmail.com', NULL, '$2y$12$xjQKn.hP4gTzobxza5cAJ.bGGAKg.QEJJu2vTEIURG5tiGZ139ahy', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:45', '2025-12-08 04:42:45'),
(20, 'Daniela', 'Rivera', '1996-04-21', 'Female', 0, 'daniela@gmail.com', NULL, '$2y$12$EjriKqPHggGuU5Ii4Ccfpe0vWUWJbqHKqMwldH81IW193HsDZTbrO', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:45', '2025-12-08 04:42:45'),
(21, 'Valentina', 'Castillo', '1991-09-14', 'Female', 0, 'valentina@gmail.com', NULL, '$2y$12$VpCBodaBPSwg1RY5eRg5ze08cLGlBkelotlNMhr3mKgY1smgGVzwO', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:42:45', '2025-12-08 04:42:45');

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
  ADD KEY `idx_closure_periods_dates` (`start_date`,`end_date`);

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
-- Indexes for table `password_histories`
--
ALTER TABLE `password_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_histories_user_id_changed_at_index` (`user_id`,`changed_at`);

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
  ADD KEY `reservations_closure_period_id_index` (`closure_period_id`),
  ADD KEY `reservations_cancelled_by_foreign` (`cancelled_by`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_name_unique` (`name`),
  ADD KEY `services_is_active_index` (`is_active`);

--
-- Indexes for table `service_archives`
--
ALTER TABLE `service_archives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_archives_service_id_foreign` (`service_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `password_histories`
--
ALTER TABLE `password_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_archives`
--
ALTER TABLE `service_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_histories`
--
ALTER TABLE `password_histories`
  ADD CONSTRAINT `password_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_closure_period_id_foreign` FOREIGN KEY (`closure_period_id`) REFERENCES `closure_periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_archives`
--
ALTER TABLE `service_archives`
  ADD CONSTRAINT `service_archives_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
