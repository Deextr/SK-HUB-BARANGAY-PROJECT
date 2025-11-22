-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 05:03 PM
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
(1, '2025-11-08', '2025-11-09', NULL, 'active', NULL, '2025-11-07 04:32:31', '2025-11-07 04:32:31'),
(2, '2025-11-13', '2025-11-13', 'Outing', 'pending', '2025-11-12 10:51:58', '2025-11-12 10:51:55', '2025-11-12 10:51:58'),
(3, '2025-11-15', '2025-11-15', NULL, 'pending', '2025-11-15 13:45:00', '2025-11-12 13:39:21', '2025-11-15 13:45:00');

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
(10, '2025_11_12_000001_make_closure_periods_full_day', 2),
(11, '2025_11_15_201938_add_age_sex_is_pwd_to_users_table', 2),
(12, '2025_11_16_161336_replace_age_with_birth_date_in_users_table', 3),
(14, '2025_11_16_180000_add_reservation_reason_to_reservations_table', 4),
(15, '2025_11_16_190000_add_suspension_fields_to_users_table', 5),
(16, '2025_11_16_190100_add_cancellation_reason_to_reservations_table', 5),
(17, '2025_11_16_214357_add_archive_fields_to_users_table', 6),
(18, '2025_11_22_000000_create_service_archives_table', 7),
(19, '2025_11_22_000001_add_cancelled_by_to_reservations_table', 8);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, 'RSV-20251107-8E5A11', '2025-11-07', '08:00:00', '08:30:00', '19:23:00', '17:23:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:33:52', '2025-11-16 11:25:45'),
(2, 4, 1, NULL, 'RSV-20251107-B45628', '2025-11-07', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:50:56', '2025-11-07 04:58:53'),
(3, 4, 1, NULL, 'RSV-20251107-4B3E71', '2025-11-07', '13:30:00', '15:00:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-07 05:02:51', '2025-11-15 14:06:18'),
(4, 3, 1, NULL, 'RSV-20251107-320349', '2025-11-10', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-07 05:19:48', '2025-11-07 05:20:30'),
(5, 2, 1, NULL, 'RSV-20251110-AD392B', '2025-11-11', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-10 06:39:22', '2025-11-10 06:39:32'),
(6, 2, 1, NULL, 'RSV-20251110-F427E0', '2025-11-13', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-10 06:45:40', '2025-11-10 06:46:30'),
(7, 2, 1, NULL, 'RSV-20251111-9C5029', '2025-11-12', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-11 06:30:46', '2025-11-11 06:31:06'),
(8, 2, 3, NULL, 'RSV-20251111-E658F3', '2025-11-13', '08:00:00', '09:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-11 06:52:03', '2025-11-11 06:52:20'),
(9, 2, 1, NULL, 'RSV-20251113-990973', '2025-11-14', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-13 01:23:26', '2025-11-13 01:24:00'),
(10, 2, 1, NULL, 'RSV-20251114-317F9B', '2025-11-14', '09:30:00', '11:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-14 01:29:50', '2025-11-15 14:06:12'),
(11, 8, 1, NULL, 'RSV-20251114-25DAAF', '2025-11-14', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, 'wheelchair', NULL, NULL, '2025-11-14 01:37:58', '2025-11-14 01:40:14'),
(12, 2, 1, NULL, 'RSV-20251116-A8841A', '2025-11-18', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-16 10:08:47', '2025-11-16 10:10:01'),
(13, 2, 2, NULL, 'RSV-20251116-A433F0', '2025-11-17', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, 'Others', 'qweqweqw qweqw qweqw', '2025-11-16 10:13:16', '2025-11-16 10:13:30'),
(14, 2, 1, NULL, 'RSV-20251116-0CEE6E', '2025-11-17', '13:00:00', '15:00:00', NULL, '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-16 11:06:51', '2025-11-16 11:22:16'),
(15, 3, 1, NULL, 'RSV-20251116-6275E0', '2025-11-17', '08:30:00', '09:30:00', '22:45:00', '23:46:00', 1, 'cancelled', 'cancel lang', 0, '2025-11-18 03:02:05', NULL, NULL, 'Surfing', NULL, '2025-11-16 11:41:05', '2025-11-18 03:02:05'),
(16, 8, 1, NULL, 'RSV-20251116-5A2CD3', '2025-11-17', '10:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'cancel lang', 0, '2025-11-18 03:02:15', NULL, NULL, 'Surfing', NULL, '2025-11-16 12:12:36', '2025-11-18 03:02:15'),
(17, 2, 1, NULL, 'RSV-20251118-16E290', '2025-11-18', '08:30:00', '09:00:00', '09:47:00', '10:11:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-18 00:25:07', '2025-11-18 02:45:57'),
(18, 3, 1, NULL, 'RSV-20251118-3311B3', '2025-11-18', '11:00:00', '11:30:00', '11:16:00', '11:30:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-18 02:46:53', '2025-11-18 03:16:47'),
(19, 4, 1, NULL, 'RSV-20251118-EB23B8', '2025-11-18', '12:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', 'cancel for testing', 0, '2025-11-18 03:18:38', NULL, NULL, 'Surfing', NULL, '2025-11-18 03:18:04', '2025-11-18 03:18:38'),
(20, 4, 1, NULL, 'RSV-20251118-0B541C', '2025-11-18', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', 'wala nisipot', 0, '2025-11-18 05:35:42', NULL, NULL, 'Surfing', NULL, '2025-11-18 04:37:28', '2025-11-18 05:35:42'),
(21, 8, 1, NULL, 'RSV-20251118-F5198E', '2025-11-18', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-18 04:58:10', '2025-11-18 04:58:20'),
(22, 8, 1, NULL, 'RSV-20251118-825AFC', '2025-11-18', '13:00:00', '13:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-18 04:59:14', '2025-11-18 05:00:31'),
(23, 10, 3, NULL, 'RSV-20251118-A8EE39', '2025-11-18', '14:00:00', '14:30:00', '14:19:00', '14:29:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watch a presentation', '2025-11-18 05:43:01', '2025-11-18 06:29:49'),
(24, 8, 1, NULL, 'RSV-20251118-336148', '2025-11-18', '15:00:00', '16:00:00', '15:33:00', '15:34:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-18 06:56:29', '2025-11-18 07:33:33'),
(25, 2, 1, NULL, 'RSV-20251122-0F2ACB', '2025-11-23', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-22 15:17:54', '2025-11-22 15:18:24'),
(26, 2, 1, NULL, 'RSV-20251122-8227D1', '2025-11-24', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', 'For testing', 0, '2025-11-22 15:23:36', NULL, NULL, 'Surfing', NULL, '2025-11-22 15:23:23', '2025-11-22 15:23:36'),
(27, 2, 2, NULL, 'RSV-20251122-6D1EC0', '2025-11-23', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, '2025-11-22 15:33:29', 2, NULL, 'Reading', NULL, '2025-11-22 15:33:20', '2025-11-22 15:33:29'),
(28, 2, 1, NULL, 'RSV-20251122-86E6BA', '2025-11-28', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', NULL, 0, '2025-11-22 15:34:55', 2, NULL, 'Surfing', NULL, '2025-11-22 15:34:51', '2025-11-22 15:34:55'),
(29, 2, 1, NULL, 'RSV-20251122-30F98D', '2025-11-27', '08:00:00', '08:30:00', NULL, NULL, 1, 'cancelled', 'testing lang', 0, '2025-11-22 15:41:56', 6, NULL, 'Surfing', NULL, '2025-11-22 15:41:47', '2025-11-22 15:41:56');

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
(3, 'TV', 'Samsung TV', 1, 1, NULL, '2025-11-07 04:31:43', '2025-11-22 13:36:16'),
(4, 'Gaming Chair', 'Bangko', 5, 1, '2025-11-12 10:51:28', '2025-11-12 10:51:16', '2025-11-12 10:51:28'),
(5, 'test', 'test only', 5, 0, NULL, '2025-11-22 10:48:04', '2025-11-22 10:48:30'),
(6, 'test1', 'testonly', 12, 0, NULL, '2025-11-22 10:48:13', '2025-11-22 10:48:25'),
(7, 'test2', 'test only', 12, 0, NULL, '2025-11-22 10:48:41', '2025-11-22 10:48:57'),
(8, 'test3', 'testonly', 11, 0, NULL, '2025-11-22 10:48:50', '2025-11-22 10:49:10'),
(9, 'test4', 'testing', 6, 0, NULL, '2025-11-22 11:39:43', '2025-11-22 13:33:05'),
(10, 'test 5', 'testing purpose', 2, 0, NULL, '2025-11-22 11:40:00', '2025-11-22 11:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `service_archives`
--

CREATE TABLE `service_archives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `units_archived` smallint(5) UNSIGNED NOT NULL,
  `capacity_before` smallint(5) UNSIGNED NOT NULL,
  `capacity_after` smallint(5) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `reservations_cancelled` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cancelled_reservation_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cancelled_reservation_ids`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('6K5Owxx4mo6y19CvHGFL9E5XV3LNZwqb0wVpc4zn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidkhlOEdDREszM1dIU05VRnlaZDlKMDU2b1hVM1VDeGhmQ29hcmNMOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1763827371),
('tt80nAR9gDCfkqYoz2kcdwZbURKyhKI7wwJp0Qgk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibjhpN2JVZTQ5aVgwYTA4ckpYQmlzMUhyQU1SVEE4R0FENnJYMm9IUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1763827391);

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
  `account_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `birth_date`, `sex`, `is_pwd`, `email`, `email_verified_at`, `password`, `is_admin`, `account_status`, `suspension_count`, `is_suspended`, `is_archived`, `archive_reason`, `archived_at`, `suspension_end_date`, `id_image_path`, `rejection_reason`, `approved_at`, `rejected_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', NULL, NULL, 0, 'admin@example.com', NULL, '$2y$12$Otkxflz7bBftsowqBAWYK.9QDstc.NytLLD.tj0KXBIZBaIleiprC', 1, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:24:37', '2025-11-07 04:24:37'),
(2, 'dexter', 'ramos', NULL, 'Male', 0, 'dexter@gmail.com', NULL, '$2y$12$4ZtNaKOp44UeiBnNPgIvMO9q60M9dndKVIZCMXdY3KBQ0AJXRRMTW', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/Ns65vTVI0Ipdv8etILXZ5yplPQRB8a6yHKFPYWvn.jpg', NULL, '2025-11-07 04:30:15', NULL, NULL, '2025-11-07 04:27:24', '2025-11-07 04:30:15'),
(3, 'nathaniel', 'bayona', NULL, 'Male', 0, 'nath@gmail.com', NULL, '$2y$12$ZIElb4wZV4KYyTNGuEGSNuiZAfnucazJwBHVmUB2zwrI21yngIBqu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/IiUlejX0nTHNBLUtQ9avk8X3o0kQQEMX5W7cCs5z.jpg', NULL, '2025-11-07 04:30:12', NULL, NULL, '2025-11-07 04:28:21', '2025-11-07 04:30:12'),
(4, 'nesa', 'Limpuasan', NULL, 'Female', 0, 'nesa@gmail.com', NULL, '$2y$12$t/uZaph8kEfEs9UTyvsucOzOd.Ii5GZEnqgH/xht6MtpYAZvnX5Sm', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/lh2btGOYvTDjSnsqHzlOdnaNjZKMiGU2WO1KhR3t.jpg', NULL, '2025-11-07 04:30:09', NULL, NULL, '2025-11-07 04:28:43', '2025-11-07 04:30:09'),
(5, 'christian', 'torion', NULL, NULL, 0, 'chan@gmail.com', NULL, '$2y$12$roXAE.qFlXmADz6zT..dG.7ZfvTvoJXLn6LF.r9/Jkil07lRqgn7i', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, 'id_images/qUA9Y81E4jqjRq5Cr6pPNEf5Lqdy4YHW9tGF7LcR.jpg', 'Please used your ID', NULL, '2025-11-07 04:30:02', NULL, '2025-11-07 04:29:13', '2025-11-07 04:30:02'),
(6, 'Admin', 'User', NULL, NULL, 0, 'admin@gmail.com', NULL, '$2y$12$Y0.SqhfH7UkF0T9tJ1HWSOiwirsU4uLPOzNahJO/rK8n4of7gW466', 1, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-07 04:43:54', '2025-11-07 04:43:54'),
(7, 'Raven', 'Canedo', NULL, NULL, 0, 'raven@gmail.com', NULL, '$2y$12$CLf3dPp48e7xIQSP.HpUQOWRMMMNW4z/KMAJ/.rKnKvCRt1//zj8q', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, 'id_images/PqTPDShae8Cfi53KVCnvCsQj9ufKokzvSAaovCs8.jpg', 'change your id', NULL, '2025-11-14 01:34:22', NULL, '2025-11-13 00:27:53', '2025-11-14 01:34:22'),
(8, 'Razell', 'Ponce', NULL, NULL, 0, 'razel@gmail.com', NULL, '$2y$12$oYKrpEnjgCp0YtawaW5tRuOvOjNVVUHA26dCgu1tT8tp8yoi8wjOu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/VOZHkH5clWUPoqeQRdrphEj7cNd5gsx8lTGQly2N.jpg', NULL, '2025-11-14 01:33:58', NULL, NULL, '2025-11-14 01:32:55', '2025-11-14 01:33:58'),
(9, 'Loyd', 'Aure', '2004-11-10', 'Male', 0, 'loyd@gmail.com', NULL, '$2y$12$rSdseIRKLmD1SdpMr19zgOi9hESAokrO6yfM0.gEj/M73cuhW8a2q', 0, 'approved', 0, 0, 1, 'You didn\'t login for 3 months.', '2025-11-16 14:04:50', NULL, 'id_images/BvntE6fwHbyhOGpGegOrQc7DE9E7IqLj1DKlkwrW.jpg', NULL, '2025-11-16 14:04:21', NULL, NULL, '2025-11-16 08:16:33', '2025-11-16 14:04:50'),
(10, 'Brix', 'Aure', '2000-10-20', 'Male', 1, 'brix@gmail.com', NULL, '$2y$12$KobS8bnmKckVXPM/EiSuIu8DOAVEf4er8QEiZcmGMwKcRAUkmf7RC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/8PiBUyEdCZDXXKAhtcuA4FcgF8MVt6w9B4ZI5MTA.jpg', NULL, '2025-11-18 05:41:59', NULL, NULL, '2025-11-16 14:21:55', '2025-11-18 05:41:59'),
(11, 'Do Not', 'Login', '2003-01-01', 'Male', 0, 'donotlogin@gmail.com', NULL, '$2y$12$u3e84BJ7RF5akXNcNdG4O.Mh5N2zBUJwlI.xUvyVGjAEvepEj42Lu', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/mQpqer6jBQHBCXRsU5WICJPkgkQeY0Ff1gWNvOeF.jpg', NULL, '2025-11-18 05:42:11', NULL, NULL, '2025-11-18 05:41:30', '2025-11-18 05:42:11'),
(12, 'Uca', 'Ramos', '2000-10-20', 'Male', 0, 'uca@gmail.com', NULL, '$2y$12$kmUtFECco2QSVpZIdWdbouMJ17w0t2HQegMkYo6fshMcxDamFCJuK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, 'id_images/AG08btq8TYIohbeFwgYrIR4DmEl6ovCKfvTKAjhg.jpg', NULL, '2025-11-18 07:07:22', NULL, NULL, '2025-11-18 07:07:11', '2025-11-18 07:07:22'),
(13, 'Jr', 'Loayon', '2004-02-18', 'Male', 0, 'jr@gmail.com', NULL, '$2y$12$DPH5oBsE.Mzapb5AvvhvW.TMeipwsTN8RwHx6pxNLxU1tgyEN1bG2', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, 'id_images/JCdrJvxU5lyUJIBgL6UOUJgZyvs7f38WlGCAs6C2.jpg', 'Mali imong ID tol', NULL, '2025-11-19 02:33:10', NULL, '2025-11-19 02:32:16', '2025-11-19 02:33:10');

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
  ADD KEY `service_archives_service_id_created_at_index` (`service_id`,`created_at`),
  ADD KEY `service_archives_created_at_index` (`created_at`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `service_archives`
--
ALTER TABLE `service_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
