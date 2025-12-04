-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 08:06 AM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `closure_periods`
--

INSERT INTO `closure_periods` (`id`, `start_date`, `end_date`, `reason`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2025-11-30', '2025-11-30', 'meeting', 'active', NULL, '2025-11-30 08:39:05', '2025-11-30 08:39:05'),
(2, '2025-12-01', '2025-12-01', 'meeting', 'pending', '2025-12-03 07:05:11', '2025-11-30 09:42:13', '2025-12-03 07:05:11');

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
(20, '2025_11_30_000000_create_password_histories_table', 2);

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

--
-- Dumping data for table `password_histories`
--

INSERT INTO `password_histories` (`id`, `user_id`, `password_hash`, `changed_at`) VALUES
(1, 18, '$2y$12$XNSrHczeG8HnnBsAPSj5o.yyTrwHoaQdSKLBwqwGB5FTp/SdIchyO', '2025-11-30 11:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('apolonioramos2442@gmail.com', '$2y$12$ivSotlGlalNmNr4P9O03vOIrmqkjDzfUJcYTaeKJrlsm/7lU2c2AC', '2025-12-01 03:11:09'),
('buaknasaging111@gmail.com', '$2y$12$VulBiuFsF2.ui/R8DXSCK.pvlrQ.62Yb.tPWZpbeiAch11cTdjvPS', '2025-11-30 12:45:30');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, 'RSV-20251130-9106E0', '2025-12-01', '08:00:00', '09:00:00', NULL, NULL, 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-30 08:32:59', '2025-12-01 03:12:10'),
(2, 3, 1, NULL, 'RSV-20251130-CC01AE', '2025-12-01', '08:00:00', '08:30:00', NULL, NULL, 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-30 08:33:33', '2025-12-01 03:12:56'),
(3, 4, 1, NULL, 'RSV-20251130-21B3CE', '2025-12-01', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', 'Service capacity reduced due to unit archival. Cancelled to maintain fair booking.', 0, '2025-11-30 08:35:01', NULL, NULL, 'Surfing', NULL, '2025-11-30 08:34:02', '2025-11-30 08:35:01'),
(4, 5, 1, NULL, 'RSV-20251201-F749B3', '2025-12-01', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', 'testing', 0, '2025-12-01 05:37:08', 1, NULL, 'Surfing', NULL, '2025-12-01 04:58:50', '2025-12-01 05:37:08'),
(5, 5, 2, NULL, 'RSV-20251201-97E440', '2025-12-01', '14:00:00', '14:30:00', '14:17:00', '14:32:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-01 05:37:42', '2025-12-01 06:32:51'),
(6, 2, 2, NULL, 'RSV-20251202-C438B7', '2025-12-02', '11:00:00', '11:30:00', NULL, NULL, 1, 'cancelled', 'testing lang ni', 0, '2025-12-02 03:29:19', 1, NULL, 'Surfing', NULL, '2025-12-02 02:33:48', '2025-12-02 03:29:19'),
(7, 2, 2, NULL, 'RSV-20251202-6982B5', '2025-12-02', '14:00:00', '14:30:00', '14:28:00', '14:29:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-02 05:40:14', '2025-12-02 06:29:31');

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
(1, 'PC TEST', 'testing', 2, 1, NULL, '2025-11-30 08:31:39', '2025-11-30 08:35:01'),
(2, 'PC', 'high-end PC', 8, 1, NULL, '2025-11-30 08:31:48', '2025-11-30 08:31:48'),
(3, 'TV', 'For presentation', 1, 1, NULL, '2025-11-30 08:32:01', '2025-11-30 08:32:01'),
(4, 'Study Table', 'it depends on chairs available', 8, 1, NULL, '2025-11-30 08:32:28', '2025-11-30 08:32:28');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_archives`
--

INSERT INTO `service_archives` (`id`, `service_id`, `units_archived`, `capacity_before`, `capacity_after`, `reason`, `reservations_cancelled`, `cancelled_reservation_ids`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 2, 'testing', 1, '[3]', '2025-11-30 08:35:01', '2025-11-30 08:35:01');

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
('klvgmPpCqh9IyyTdaqEVxRtNtxccbSjI803brpVW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnN6SVVnWFEzaUxiekV1NmxXb0Y3M2d6bHBqN0s5aEZqb0JobEZrWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1764745555);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `birth_date`, `sex`, `is_pwd`, `email`, `email_verified_at`, `password`, `is_admin`, `account_status`, `suspension_count`, `is_suspended`, `is_archived`, `archive_reason`, `archived_at`, `suspension_end_date`, `id_image_path`, `rejection_reason`, `approved_at`, `rejected_at`, `partially_rejected_at`, `partially_rejected_reason`, `resubmission_count`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', NULL, NULL, 0, 'admin@gmail.com', NULL, '$2y$12$ZFH6LR1DSWgKu9vbssibk.t60xagQdfFB3mgybIUj7kMPN4SwPKra', 1, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:18:06', '2025-11-30 08:18:06'),
(2, 'Dexter', 'Ramos', '1990-05-15', 'Male', 0, 'dexter@gmail.com', NULL, '$2y$12$owu95HTN.qU1.9vYcyv9eu9onQDg7T1MoYp0vYTewpBIbmqPAaUbi', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:57', '2025-11-30 08:28:57'),
(3, 'Nathaniel', 'Bayona', '1992-08-22', 'Male', 0, 'nathaniel@gmail.com', NULL, '$2y$12$rHI8rDw0bjAGOouF.wM8ougyKV1lETtycwMYJ31609UBd01tra8jG', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:58', '2025-11-30 08:28:58'),
(4, 'Park', 'Gica', '1988-03-10', 'Male', 0, 'park@gmail.com', NULL, '$2y$12$grv/0tGeaqsm1zDWyQBwUurjWW39enU1s/KAY4vIVYUAbwMF4k0lC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:58', '2025-11-30 08:28:58'),
(5, 'Christian', 'Torion', '1995-11-05', 'Male', 0, 'chan@gmail.com', NULL, '$2y$12$WrFqBq8NpRlRLuuqfVV1z.1Sb568xkKjdkAuOxl7aXHOpmBUO/W8m', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:58', '2025-11-30 08:28:58'),
(6, 'Rocky', 'Adaya', '1991-07-18', 'Male', 0, 'rocky@gmail.com', NULL, '$2y$12$o.7aXbvD5BdF.rlDbi1/nezcIJ7E5QkL7kVrMLd2XTBxtkyyM4aeu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:58', '2025-11-30 08:28:58'),
(7, 'Juan', 'Santos', '1993-02-14', 'Male', 0, 'juan@gmail.com', NULL, '$2y$12$RXNG6rgAqEzhz.avVqlMuuN467IfN65AxonhudD2I/m9FkR2iwuOq', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:59', '2025-11-30 08:28:59'),
(8, 'Miguel', 'Cruz', '1989-09-28', 'Male', 0, 'miguel@gmail.com', NULL, '$2y$12$5EIV2fUxpBuB1wyHVCqdjOpGI.xn6F.U7WAUur8WPxPANZBRbJqhu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:59', '2025-11-30 08:28:59'),
(9, 'Carlos', 'Reyes', '1994-04-03', 'Male', 0, 'carlos@gmail.com', NULL, '$2y$12$pBKC7OsHpWt7evU2ZwApmOaZrEDYpM9aDprG3eH2phYkCNO7ZL6hC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:59', '2025-11-30 08:28:59'),
(10, 'Antonio', 'Flores', '1987-12-20', 'Male', 0, 'antonio@gmail.com', NULL, '$2y$12$NxpMKbz6UUKbDWThs74xS.2z2H.B5gdkFFxOzSV54CnGNG0MjO0LS', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:28:59', '2025-11-30 08:28:59'),
(11, 'Roberto', 'Morales', '1996-06-11', 'Male', 0, 'roberto@gmail.com', NULL, '$2y$12$KisWw8iEXnyKmXxOfB/BGeg7GAl2U96StPLe4FH1c.WcLKdOmAkwy', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:00', '2025-11-30 08:29:00'),
(12, 'Maria', 'Garcia', '1991-01-08', 'Female', 0, 'maria@gmail.com', NULL, '$2y$12$dzxz4xis/B1wJttX4Eyxae.3xdMbiBKuH90OrzqWlAKs0sR4yQin6', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:00', '2025-11-30 08:29:00'),
(13, 'Rosa', 'Lopez', '1993-05-19', 'Female', 0, 'rosa@gmail.com', NULL, '$2y$12$n0St4ZDhctWAjXZYoMZC5OU0GEjbxxuHzLWtgEV2EIOY30VD5Wtc2', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:00', '2025-11-30 08:29:00'),
(14, 'Ana', 'Martinez', '1990-10-25', 'Female', 0, 'ana@gmail.com', NULL, '$2y$12$q/EQT6969T17IhE8VFXN0uvsq/aDWxvUmmEN5HNOrGk1DoN/RWHxG', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:01', '2025-11-30 08:29:01'),
(15, 'Sofia', 'Hernandez', '1994-03-12', 'Female', 0, 'sofia@gmail.com', NULL, '$2y$12$TqPvTTjcr0e2EITJR1bjxOJodsj2oLfiScwzMEEnxmYvJ0nS/tqO6', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:01', '2025-11-30 08:29:01'),
(16, 'Isabella', 'Perez', '1992-07-30', 'Female', 0, 'isabella@gmail.com', NULL, '$2y$12$baL8dbrgAg.17R/8145qjuTmT0LsRI5B8ZAXXkmqbpkGBkf03y4ZC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:01', '2025-11-30 08:29:01'),
(17, 'Carmen', 'Sanchez', '1988-11-06', 'Female', 0, 'carmen@gmail.com', NULL, '$2y$12$YSI2o0TBc6XLLKA6rDC2Pu6HYO9ZLTuOOjpSl8zbaPH.dJ36Rebz2', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:01', '2025-11-30 08:29:01'),
(18, 'Elena', 'Ramirez', '1995-02-17', 'Female', 0, 'elena@gmail.com', NULL, '$2y$12$7755gYtqhRExD58FUax4zu.DtL0PmrjGvvUHzfwQhMj37Vls3OwIm', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:02', '2025-11-30 11:37:22'),
(19, 'Lucia', 'Torres', '1989-08-09', 'Female', 0, 'lucia@gmail.com', NULL, '$2y$12$1cRSVdrQ5hfwv.HDKk4WYu6szj0eS/AqSJnKEXxlgD0ZwB2qBlDke', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:02', '2025-11-30 08:29:02'),
(20, 'Daniela', 'Rivera', '1996-04-21', 'Female', 0, 'daniela@gmail.com', NULL, '$2y$12$U9AWY3qvUUXXFaWU3LeQte.c.gZB4meOzdV1hrkunh8dmljknbMKS', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:02', '2025-11-30 08:29:02'),
(21, 'Valentina', 'Castillo', '1991-09-14', 'Female', 0, 'valentina@gmail.com', NULL, '$2y$12$i8MVOVcfH98rWBUR9AjTEedrDCY7Mlv7K9bEwSObioiyw6lAX0zGW', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:29:02', '2025-11-30 08:29:02'),
(22, 'Nesa', 'Limpuasan', '2005-02-16', 'Female', 0, 'nesa@gmail.com', NULL, '$2y$12$QTydHhpRMA.0fDWgUVBwLeHqO.RBKWKfDwrrM/w4Cp1BCL9sA00Au', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, 'id_images/dx9hjvl2Ma4N1BbvUOikGDQT77xgv4axWnh9Nw77.jpg', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-30 08:44:40', '2025-11-30 08:44:40'),
(24, 'Apol', 'Ramos', '2005-05-04', 'Male', 0, 'buaknasaging111@gmail.com', NULL, '$2y$12$Zpg/6YbCKmq6nNdFQy58jeFtOjiZ3pcZePuwUW7kZCgv08mpqTOJy', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/sHQElvzzK7wBTzfzBXYGqgkj1OvPWBxzQoUWU0cY.jpg', NULL, '2025-11-30 12:15:47', NULL, NULL, NULL, 0, NULL, '2025-11-30 12:15:29', '2025-11-30 12:15:47'),
(25, 'Loyd', 'Aure', '2000-03-06', 'Male', 0, 'apolonioramos2442@gmail.com', NULL, '$2y$12$yy4ZJX.8En3mqooycpV8heza3dT32JJHoo.hyiq1n8275D44pLPAi', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/taatK0zVgdaEH8jmkDDxvo6SdNa1yR07v79BZcYc.jpg', NULL, '2025-11-30 12:48:32', NULL, NULL, NULL, 0, '3oQ8Evp1SOraLVdNxVQTiqFaGaOIs7iPMHE95cK5nQbu60rq0WC09VfEDOoN', '2025-11-30 12:48:20', '2025-11-30 12:53:35');

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
  ADD KEY `closure_periods_status_index` (`status`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `password_histories`
--
ALTER TABLE `password_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_archives`
--
ALTER TABLE `service_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
