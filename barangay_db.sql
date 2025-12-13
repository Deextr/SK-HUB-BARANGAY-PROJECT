-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 05:02 PM
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

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(1, 76, 1, NULL, 'REF-2025-001000', '2025-07-02', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-18 16:00:00', '2025-07-01 17:00:00'),
(2, 13, 1, NULL, 'REF-2025-001001', '2025-02-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-31 16:00:00', '2025-02-04 17:00:00'),
(3, 18, 2, NULL, 'REF-2025-001002', '2025-04-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-04-08 16:00:00', '2025-04-11 17:00:00'),
(4, 4, 2, NULL, 'REF-2025-001003', '2025-09-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-07 16:00:00', '2025-09-08 17:00:00'),
(5, 76, 1, NULL, 'REF-2025-001004', '2025-06-13', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-06-09 16:00:00', '2025-06-12 17:00:00'),
(6, 13, 1, NULL, 'REF-2025-001005', '2025-09-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-27 16:00:00', '2025-09-10 17:00:00'),
(7, 63, 2, NULL, 'REF-2025-001006', '2025-07-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-06-23 16:00:00', '2025-07-01 17:00:00'),
(8, 62, 2, NULL, 'REF-2025-001007', '2025-04-04', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-21 16:00:00', '2025-04-03 17:00:00'),
(9, 58, 2, NULL, 'REF-2025-001008', '2025-07-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-06-23 16:00:00', '2025-06-30 17:00:00'),
(10, 66, 2, NULL, 'REF-2025-001009', '2025-08-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-07-26 16:00:00', '2025-07-31 17:00:00'),
(11, 86, 2, NULL, 'REF-2025-001010', '2025-12-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-04 16:00:00', '2025-12-13 17:00:00'),
(12, 15, 1, NULL, 'REF-2025-001011', '2025-05-11', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-04-26 16:00:00', '2025-05-10 17:00:00'),
(13, 21, 2, NULL, 'REF-2025-001012', '2025-09-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-30 16:00:00', '2025-09-06 17:00:00'),
(14, 11, 1, NULL, 'REF-2025-001013', '2025-07-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-19 16:00:00', '2025-07-03 17:00:00'),
(15, 4, 1, NULL, 'REF-2025-001014', '2025-03-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-02-26 16:00:00', '2025-03-09 17:00:00'),
(16, 88, 1, NULL, 'REF-2025-001015', '2025-11-07', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-11-04 16:00:00', '2025-11-06 17:00:00'),
(17, 16, 1, NULL, 'REF-2025-001016', '2025-12-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-12-01 16:00:00', '2025-12-07 17:00:00'),
(18, 15, 3, NULL, 'REF-2025-001017', '2025-08-11', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-08-07 16:00:00', '2025-08-10 17:00:00'),
(19, 59, 3, NULL, 'REF-2025-001018', '2025-11-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-10-29 16:00:00', '2025-10-31 17:00:00'),
(20, 91, 3, NULL, 'REF-2025-001019', '2025-12-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-11-30 16:00:00', '2025-12-02 17:00:00'),
(21, 18, 3, NULL, 'REF-2025-001020', '2025-03-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-27 16:00:00', '2025-02-28 17:00:00'),
(22, 19, 1, NULL, 'REF-2025-001021', '2025-08-05', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-22 16:00:00', '2025-08-04 17:00:00'),
(23, 56, 1, NULL, 'REF-2025-001022', '2025-06-11', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-06-07 16:00:00', '2025-06-10 17:00:00'),
(24, 3, 1, NULL, 'REF-2025-001023', '2025-10-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-10-03 16:00:00', '2025-10-09 17:00:00'),
(25, 7, 3, NULL, 'REF-2025-001024', '2025-03-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-20 16:00:00', '2025-02-28 17:00:00'),
(26, 62, 1, NULL, 'REF-2025-001025', '2025-12-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-28 16:00:00', '2025-12-06 17:00:00'),
(27, 5, 3, NULL, 'REF-2025-001026', '2025-09-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-31 16:00:00', '2025-09-08 17:00:00'),
(28, 1, 2, NULL, 'REF-2025-001027', '2025-08-12', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-30 16:00:00', '2025-08-11 17:00:00'),
(29, 10, 1, NULL, 'REF-2025-001028', '2025-02-06', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-03 16:00:00', '2025-02-05 17:00:00'),
(30, 3, 3, NULL, 'REF-2025-001029', '2025-12-05', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-20 16:00:00', '2025-12-04 17:00:00'),
(31, 17, 2, NULL, 'REF-2025-001030', '2025-01-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-31 16:00:00', '2025-01-12 17:00:00'),
(32, 13, 3, NULL, 'REF-2025-001031', '2025-12-14', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-12-03 16:00:00', '2025-12-13 17:00:00'),
(33, 8, 2, NULL, 'REF-2025-001032', '2025-11-08', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-30 16:00:00', '2025-11-07 17:00:00'),
(34, 76, 1, NULL, 'REF-2025-001033', '2025-01-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-29 16:00:00', '2024-12-31 17:00:00'),
(35, 61, 2, NULL, 'REF-2025-001034', '2025-01-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-31 16:00:00', '2025-01-02 17:00:00'),
(36, 90, 1, NULL, 'REF-2025-001035', '2025-03-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-04 16:00:00', '2025-03-13 17:00:00'),
(37, 19, 2, NULL, 'REF-2025-001036', '2025-05-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-01 16:00:00', '2025-05-10 17:00:00'),
(38, 4, 1, NULL, 'REF-2025-001037', '2025-01-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-27 16:00:00', '2025-01-02 17:00:00'),
(39, 68, 1, NULL, 'REF-2025-001038', '2025-06-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-04 16:00:00', '2025-06-08 17:00:00'),
(40, 12, 2, NULL, 'REF-2025-001039', '2025-04-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-31 16:00:00', '2025-04-11 17:00:00'),
(41, 83, 2, NULL, 'REF-2025-001040', '2025-12-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-30 16:00:00', '2025-12-04 17:00:00'),
(42, 62, 3, NULL, 'REF-2025-001041', '2025-03-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-21 16:00:00', '2025-03-02 17:00:00'),
(43, 20, 1, NULL, 'REF-2025-001042', '2025-08-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-04 16:00:00', '2025-08-12 17:00:00'),
(44, 73, 2, NULL, 'REF-2025-001043', '2025-08-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-30 16:00:00', '2025-08-05 17:00:00'),
(45, 84, 2, NULL, 'REF-2025-001044', '2025-12-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-03 16:00:00', '2025-12-08 17:00:00'),
(46, 63, 3, NULL, 'REF-2025-001045', '2025-11-05', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-21 16:00:00', '2025-11-04 17:00:00'),
(47, 20, 1, NULL, 'REF-2025-001046', '2025-08-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-05 16:00:00', '2025-08-12 17:00:00'),
(48, 68, 1, NULL, 'REF-2025-001047', '2025-09-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-02 16:00:00', '2025-09-03 17:00:00'),
(49, 21, 1, NULL, 'REF-2025-001048', '2025-07-10', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-07-04 16:00:00', '2025-07-09 17:00:00'),
(50, 10, 1, NULL, 'REF-2025-001049', '2025-04-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-22 16:00:00', '2025-04-01 17:00:00'),
(51, 75, 3, NULL, 'REF-2025-001050', '2025-01-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-01 16:00:00', '2025-01-10 17:00:00'),
(52, 79, 1, NULL, 'REF-2025-001051', '2025-03-10', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-25 16:00:00', '2025-03-09 17:00:00'),
(53, 81, 2, NULL, 'REF-2025-001052', '2025-01-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-01-04 16:00:00', '2025-01-11 17:00:00'),
(54, 52, 2, NULL, 'REF-2025-001053', '2025-08-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-05 16:00:00', '2025-08-11 17:00:00'),
(55, 84, 3, NULL, 'REF-2025-001054', '2025-11-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-10-20 16:00:00', '2025-10-31 17:00:00'),
(56, 16, 3, NULL, 'REF-2025-001055', '2025-01-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-08 16:00:00', '2025-01-12 17:00:00'),
(57, 61, 1, NULL, 'REF-2025-001056', '2025-12-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-08 16:00:00', '2025-12-12 17:00:00'),
(58, 10, 3, NULL, 'REF-2025-001057', '2025-09-06', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-09-03 16:00:00', '2025-09-05 17:00:00'),
(59, 90, 2, NULL, 'REF-2025-001058', '2025-02-07', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-31 16:00:00', '2025-02-06 17:00:00'),
(60, 73, 1, NULL, 'REF-2025-001059', '2025-03-07', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-05 16:00:00', '2025-03-06 17:00:00'),
(61, 91, 2, NULL, 'REF-2025-001060', '2025-11-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-10-30 16:00:00', '2025-11-04 17:00:00'),
(62, 14, 1, NULL, 'REF-2025-001061', '2025-07-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-05 16:00:00', '2025-07-12 17:00:00'),
(63, 6, 1, NULL, 'REF-2025-001062', '2025-09-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-06 16:00:00', '2025-09-13 17:00:00'),
(64, 69, 2, NULL, 'REF-2025-001063', '2025-05-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-05-02 16:00:00', '2025-05-03 17:00:00'),
(65, 6, 2, NULL, 'REF-2025-001064', '2025-01-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-01-03 16:00:00', '2025-01-07 17:00:00'),
(66, 76, 3, NULL, 'REF-2025-001065', '2025-04-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-25 16:00:00', '2025-04-02 17:00:00'),
(67, 66, 2, NULL, 'REF-2025-001066', '2025-08-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-08-02 16:00:00', '2025-08-08 17:00:00'),
(68, 70, 2, NULL, 'REF-2025-001067', '2025-03-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-22 16:00:00', '2025-03-06 17:00:00'),
(69, 72, 3, NULL, 'REF-2025-001068', '2025-07-06', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-06-25 16:00:00', '2025-07-05 17:00:00'),
(70, 69, 3, NULL, 'REF-2025-001069', '2025-07-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-03 16:00:00', '2025-07-07 17:00:00'),
(71, 17, 2, NULL, 'REF-2025-001070', '2025-06-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-03 16:00:00', '2025-06-12 17:00:00'),
(72, 74, 1, NULL, 'REF-2025-001071', '2025-01-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-04 16:00:00', '2025-01-08 17:00:00'),
(73, 54, 2, NULL, 'REF-2025-001072', '2025-05-11', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-09 16:00:00', '2025-05-10 17:00:00'),
(74, 74, 1, NULL, 'REF-2025-001073', '2025-08-04', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-25 16:00:00', '2025-08-03 17:00:00'),
(75, 65, 1, NULL, 'REF-2025-001074', '2025-07-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-08 16:00:00', '2025-07-10 17:00:00'),
(76, 13, 1, NULL, 'REF-2025-001075', '2025-03-10', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-03 16:00:00', '2025-03-09 17:00:00'),
(77, 58, 1, NULL, 'REF-2025-001076', '2025-10-02', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-28 16:00:00', '2025-10-01 17:00:00'),
(78, 7, 3, NULL, 'REF-2025-001077', '2025-07-11', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-27 16:00:00', '2025-07-10 17:00:00'),
(79, 12, 2, NULL, 'REF-2025-001078', '2025-07-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-05 16:00:00', '2025-07-08 17:00:00'),
(80, 54, 3, NULL, 'REF-2025-001079', '2025-10-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-27 16:00:00', '2025-10-10 17:00:00'),
(81, 74, 2, NULL, 'REF-2025-001080', '2025-02-04', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-02 16:00:00', '2025-02-03 17:00:00'),
(82, 3, 2, NULL, 'REF-2025-001081', '2025-03-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-02-26 16:00:00', '2025-03-04 17:00:00'),
(83, 53, 3, NULL, 'REF-2025-001082', '2025-09-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-01 16:00:00', '2025-09-13 17:00:00'),
(84, 7, 2, NULL, 'REF-2025-001083', '2025-01-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-29 16:00:00', '2025-01-03 17:00:00'),
(85, 86, 3, NULL, 'REF-2025-001084', '2025-10-11', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-04 16:00:00', '2025-10-10 17:00:00'),
(86, 53, 3, NULL, 'REF-2025-001085', '2025-06-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-31 16:00:00', '2025-06-08 17:00:00'),
(87, 81, 1, NULL, 'REF-2025-001086', '2025-05-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-05-05 16:00:00', '2025-05-06 17:00:00'),
(88, 14, 1, NULL, 'REF-2025-001087', '2025-06-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-07 16:00:00', '2025-06-09 17:00:00'),
(89, 12, 1, NULL, 'REF-2025-001088', '2025-04-08', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-01 16:00:00', '2025-04-07 17:00:00'),
(90, 21, 3, NULL, 'REF-2025-001089', '2025-06-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-26 16:00:00', '2025-06-05 17:00:00'),
(91, 78, 2, NULL, 'REF-2025-001090', '2025-01-04', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-26 16:00:00', '2025-01-03 17:00:00'),
(92, 71, 1, NULL, 'REF-2025-001091', '2025-06-03', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-23 16:00:00', '2025-06-02 17:00:00'),
(93, 13, 2, NULL, 'REF-2025-001092', '2025-11-04', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-10-26 16:00:00', '2025-11-03 17:00:00'),
(94, 15, 2, NULL, 'REF-2025-001093', '2025-11-14', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-30 16:00:00', '2025-11-13 17:00:00'),
(95, 60, 3, NULL, 'REF-2025-001094', '2025-06-13', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-09 16:00:00', '2025-06-12 17:00:00'),
(96, 78, 1, NULL, 'REF-2025-001095', '2025-06-05', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-05-26 16:00:00', '2025-06-04 17:00:00'),
(97, 19, 2, NULL, 'REF-2025-001096', '2025-10-10', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-03 16:00:00', '2025-10-09 17:00:00'),
(98, 5, 1, NULL, 'REF-2025-001097', '2025-07-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-03 16:00:00', '2025-07-08 17:00:00'),
(99, 91, 3, NULL, 'REF-2025-001098', '2025-07-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-07-04 16:00:00', '2025-07-13 17:00:00'),
(100, 17, 2, NULL, 'REF-2025-001099', '2025-09-10', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-03 16:00:00', '2025-09-09 17:00:00'),
(101, 82, 2, NULL, 'REF-2025-001100', '2025-01-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-05 16:00:00', '2025-01-11 17:00:00'),
(102, 76, 2, NULL, 'REF-2025-001101', '2025-01-13', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-07 16:00:00', '2025-01-12 17:00:00'),
(103, 52, 1, NULL, 'REF-2025-001102', '2025-02-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-31 16:00:00', '2025-02-03 17:00:00'),
(104, 71, 2, NULL, 'REF-2025-001103', '2025-04-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-22 16:00:00', '2025-04-04 17:00:00'),
(105, 67, 2, NULL, 'REF-2025-001104', '2025-07-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-04 16:00:00', '2025-07-11 17:00:00'),
(106, 14, 2, NULL, 'REF-2025-001105', '2025-04-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-03-30 16:00:00', '2025-04-07 17:00:00'),
(107, 83, 1, NULL, 'REF-2025-001106', '2025-12-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-01 16:00:00', '2025-12-07 17:00:00'),
(108, 57, 3, NULL, 'REF-2025-001107', '2025-04-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-07 16:00:00', '2025-04-08 17:00:00'),
(109, 63, 1, NULL, 'REF-2025-001108', '2025-12-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-29 16:00:00', '2025-12-11 17:00:00'),
(110, 82, 2, NULL, 'REF-2025-001109', '2025-04-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-03-27 16:00:00', '2025-04-01 17:00:00'),
(111, 21, 3, NULL, 'REF-2025-001110', '2025-07-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-07 16:00:00', '2025-07-11 17:00:00'),
(112, 85, 3, NULL, 'REF-2025-001111', '2025-01-04', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-22 16:00:00', '2025-01-03 17:00:00'),
(113, 65, 2, NULL, 'REF-2025-001112', '2025-10-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-29 16:00:00', '2025-10-05 17:00:00'),
(114, 14, 2, NULL, 'REF-2025-001113', '2025-08-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-07-25 16:00:00', '2025-08-08 17:00:00'),
(115, 3, 3, NULL, 'REF-2025-001114', '2025-05-01', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-28 16:00:00', '2025-04-30 17:00:00'),
(116, 78, 2, NULL, 'REF-2025-001115', '2025-02-02', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-31 16:00:00', '2025-02-01 17:00:00'),
(117, 57, 3, NULL, 'REF-2025-001116', '2025-10-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-30 16:00:00', '2025-10-06 17:00:00'),
(118, 81, 2, NULL, 'REF-2025-001117', '2025-01-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-23 16:00:00', '2025-01-03 17:00:00'),
(119, 74, 3, NULL, 'REF-2025-001118', '2025-11-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-10-26 16:00:00', '2025-11-07 17:00:00'),
(120, 57, 2, NULL, 'REF-2025-001119', '2025-03-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-03 16:00:00', '2025-03-13 17:00:00'),
(121, 20, 2, NULL, 'REF-2025-001120', '2025-12-13', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-10 16:00:00', '2025-12-12 17:00:00'),
(122, 70, 3, NULL, 'REF-2025-001121', '2025-10-06', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-26 16:00:00', '2025-10-05 17:00:00'),
(123, 1, 3, NULL, 'REF-2025-001122', '2025-05-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-30 16:00:00', '2025-05-06 17:00:00'),
(124, 68, 3, NULL, 'REF-2025-001123', '2025-12-10', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-12-03 16:00:00', '2025-12-09 17:00:00'),
(125, 88, 1, NULL, 'REF-2025-001124', '2025-07-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-07-03 16:00:00', '2025-07-12 17:00:00'),
(126, 62, 2, NULL, 'REF-2025-001125', '2025-08-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-07-25 16:00:00', '2025-08-08 17:00:00'),
(127, 21, 3, NULL, 'REF-2025-001126', '2025-02-03', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-01-26 16:00:00', '2025-02-02 17:00:00'),
(128, 70, 3, NULL, 'REF-2025-001127', '2025-04-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-03-30 16:00:00', '2025-04-12 17:00:00'),
(129, 58, 2, NULL, 'REF-2025-001128', '2025-09-11', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-08 16:00:00', '2025-09-10 17:00:00'),
(130, 15, 2, NULL, 'REF-2025-001129', '2025-12-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-09 16:00:00', '2025-12-13 17:00:00'),
(131, 19, 2, NULL, 'REF-2025-001130', '2025-09-07', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-24 16:00:00', '2025-09-06 17:00:00'),
(132, 14, 3, NULL, 'REF-2025-001131', '2025-02-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-02-01 16:00:00', '2025-02-08 17:00:00'),
(133, 54, 2, NULL, 'REF-2025-001132', '2025-01-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-31 16:00:00', '2025-01-13 17:00:00'),
(134, 55, 3, NULL, 'REF-2025-001133', '2025-09-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-31 16:00:00', '2025-09-06 17:00:00'),
(135, 11, 3, NULL, 'REF-2025-001134', '2025-07-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-06-30 16:00:00', '2025-07-08 17:00:00'),
(136, 71, 3, NULL, 'REF-2025-001135', '2025-07-10', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-04 16:00:00', '2025-07-09 17:00:00'),
(137, 54, 3, NULL, 'REF-2025-001136', '2025-09-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-30 16:00:00', '2025-09-03 17:00:00'),
(138, 85, 1, NULL, 'REF-2025-001137', '2025-01-04', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-26 16:00:00', '2025-01-03 17:00:00'),
(139, 20, 2, NULL, 'REF-2025-001138', '2025-11-10', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-11-05 16:00:00', '2025-11-09 17:00:00'),
(140, 78, 1, NULL, 'REF-2025-001139', '2025-10-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-10-06 16:00:00', '2025-10-11 17:00:00'),
(141, 70, 3, NULL, 'REF-2025-001140', '2025-05-02', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-29 16:00:00', '2025-05-01 17:00:00'),
(142, 55, 1, NULL, 'REF-2025-001141', '2025-02-10', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-02-04 16:00:00', '2025-02-09 17:00:00'),
(143, 15, 3, NULL, 'REF-2025-001142', '2025-01-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-19 16:00:00', '2024-12-31 17:00:00'),
(144, 65, 2, NULL, 'REF-2025-001143', '2025-05-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-16 16:00:00', '2025-04-30 17:00:00'),
(145, 60, 2, NULL, 'REF-2025-001144', '2025-11-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-25 16:00:00', '2025-11-08 17:00:00'),
(146, 2, 3, NULL, 'REF-2025-001145', '2025-06-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-26 16:00:00', '2025-06-05 17:00:00'),
(147, 78, 3, NULL, 'REF-2025-001146', '2025-10-07', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-10-04 16:00:00', '2025-10-06 17:00:00'),
(148, 12, 3, NULL, 'REF-2025-001147', '2025-04-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-03 16:00:00', '2025-04-04 17:00:00'),
(149, 53, 1, NULL, 'REF-2025-001148', '2025-09-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-31 16:00:00', '2025-09-04 17:00:00'),
(150, 63, 3, NULL, 'REF-2025-001149', '2025-02-08', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-30 16:00:00', '2025-02-07 17:00:00'),
(151, 80, 2, NULL, 'REF-2025-001150', '2025-10-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-04 16:00:00', '2025-10-09 17:00:00'),
(152, 60, 2, NULL, 'REF-2025-001151', '2025-09-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-30 16:00:00', '2025-09-11 17:00:00'),
(153, 21, 2, NULL, 'REF-2025-001152', '2025-05-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-05 16:00:00', '2025-05-09 17:00:00'),
(154, 65, 1, NULL, 'REF-2025-001153', '2025-06-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-01 16:00:00', '2025-06-06 17:00:00'),
(155, 13, 2, NULL, 'REF-2025-001154', '2025-12-01', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-22 16:00:00', '2025-11-30 17:00:00'),
(156, 87, 2, NULL, 'REF-2025-001155', '2025-11-02', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-25 16:00:00', '2025-11-01 17:00:00'),
(157, 76, 1, NULL, 'REF-2025-001156', '2025-01-02', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-26 16:00:00', '2025-01-01 17:00:00'),
(158, 75, 1, NULL, 'REF-2025-001157', '2025-04-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-09 16:00:00', '2025-04-13 17:00:00'),
(159, 82, 1, NULL, 'REF-2025-001158', '2025-10-14', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-01 16:00:00', '2025-10-13 17:00:00'),
(160, 10, 1, NULL, 'REF-2025-001159', '2025-01-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-25 16:00:00', '2025-01-05 17:00:00'),
(161, 54, 1, NULL, 'REF-2025-001160', '2025-11-11', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-29 16:00:00', '2025-11-10 17:00:00'),
(162, 76, 2, NULL, 'REF-2025-001161', '2025-08-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-29 16:00:00', '2025-08-05 17:00:00'),
(163, 4, 3, NULL, 'REF-2025-001162', '2025-04-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-04-07 16:00:00', '2025-04-08 17:00:00'),
(164, 79, 1, NULL, 'REF-2025-001163', '2025-12-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-27 16:00:00', '2025-12-03 17:00:00'),
(165, 72, 3, NULL, 'REF-2025-001164', '2025-06-14', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-06-09 16:00:00', '2025-06-13 17:00:00'),
(166, 56, 3, NULL, 'REF-2025-001165', '2025-06-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-26 16:00:00', '2025-06-02 17:00:00'),
(167, 88, 2, NULL, 'REF-2025-001166', '2025-04-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-28 16:00:00', '2025-04-02 17:00:00'),
(168, 13, 1, NULL, 'REF-2025-001167', '2025-01-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2024-12-22 16:00:00', '2025-01-01 17:00:00'),
(169, 12, 1, NULL, 'REF-2025-001168', '2025-03-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-08 16:00:00', '2025-03-13 17:00:00'),
(170, 76, 1, NULL, 'REF-2025-001169', '2025-08-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-20 16:00:00', '2025-08-01 17:00:00'),
(171, 11, 2, NULL, 'REF-2025-001170', '2025-12-07', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-26 16:00:00', '2025-12-06 17:00:00'),
(172, 13, 1, NULL, 'REF-2025-001171', '2025-03-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-06 16:00:00', '2025-03-08 17:00:00'),
(173, 68, 3, NULL, 'REF-2025-001172', '2025-07-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-28 16:00:00', '2025-07-10 17:00:00'),
(174, 14, 1, NULL, 'REF-2025-001173', '2025-12-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-27 16:00:00', '2025-12-04 17:00:00'),
(175, 9, 3, NULL, 'REF-2025-001174', '2025-04-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-03-26 16:00:00', '2025-04-09 17:00:00'),
(176, 53, 1, NULL, 'REF-2025-001175', '2025-01-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-25 16:00:00', '2025-01-08 17:00:00'),
(177, 15, 3, NULL, 'REF-2025-001176', '2025-08-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-31 16:00:00', '2025-08-05 17:00:00'),
(178, 54, 3, NULL, 'REF-2025-001177', '2025-11-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-25 16:00:00', '2025-11-07 17:00:00'),
(179, 53, 1, NULL, 'REF-2025-001178', '2025-04-07', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-23 16:00:00', '2025-04-06 17:00:00'),
(180, 5, 3, NULL, 'REF-2025-001179', '2025-12-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-03 16:00:00', '2025-12-12 17:00:00'),
(181, 3, 1, NULL, 'REF-2025-001180', '2025-09-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-08-25 16:00:00', '2025-09-03 17:00:00'),
(182, 90, 2, NULL, 'REF-2025-001181', '2025-03-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-01 16:00:00', '2025-03-03 17:00:00'),
(183, 60, 1, NULL, 'REF-2025-001182', '2025-08-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-07-22 16:00:00', '2025-07-31 17:00:00'),
(184, 18, 1, NULL, 'REF-2025-001183', '2025-07-01', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-16 16:00:00', '2025-06-30 17:00:00'),
(185, 3, 2, NULL, 'REF-2025-001184', '2025-03-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-14 16:00:00', '2025-02-28 17:00:00'),
(186, 6, 1, NULL, 'REF-2025-001185', '2025-03-13', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-28 16:00:00', '2025-03-12 17:00:00'),
(187, 4, 1, NULL, 'REF-2025-001186', '2025-10-11', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-30 16:00:00', '2025-10-10 17:00:00'),
(188, 61, 2, NULL, 'REF-2025-001187', '2025-06-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-03 16:00:00', '2025-06-05 17:00:00'),
(189, 53, 2, NULL, 'REF-2025-001188', '2025-11-14', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-02 16:00:00', '2025-11-13 17:00:00'),
(190, 71, 3, NULL, 'REF-2025-001189', '2025-06-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-30 16:00:00', '2025-06-09 17:00:00'),
(191, 12, 2, NULL, 'REF-2025-001190', '2025-09-12', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-05 16:00:00', '2025-09-11 17:00:00'),
(192, 80, 2, NULL, 'REF-2025-001191', '2025-02-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-22 16:00:00', '2025-02-03 17:00:00'),
(193, 16, 3, NULL, 'REF-2025-001192', '2025-12-04', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-24 16:00:00', '2025-12-03 17:00:00'),
(194, 83, 3, NULL, 'REF-2025-001193', '2025-05-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-27 16:00:00', '2025-05-11 17:00:00'),
(195, 56, 2, NULL, 'REF-2025-001194', '2025-08-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-03 16:00:00', '2025-08-11 17:00:00'),
(196, 57, 3, NULL, 'REF-2025-001195', '2025-12-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-12-02 16:00:00', '2025-12-10 17:00:00'),
(197, 87, 3, NULL, 'REF-2025-001196', '2025-03-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-02-27 16:00:00', '2025-03-09 17:00:00'),
(198, 54, 3, NULL, 'REF-2025-001197', '2025-11-04', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-10-28 16:00:00', '2025-11-03 17:00:00'),
(199, 60, 1, NULL, 'REF-2025-001198', '2025-12-10', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-28 16:00:00', '2025-12-09 17:00:00'),
(200, 52, 3, NULL, 'REF-2025-001199', '2025-04-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-04-02 16:00:00', '2025-04-04 17:00:00'),
(201, 56, 1, NULL, 'REF-2025-001200', '2025-11-08', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-31 16:00:00', '2025-11-07 17:00:00'),
(202, 57, 1, NULL, 'REF-2025-001201', '2025-11-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-10-25 16:00:00', '2025-11-07 17:00:00'),
(203, 54, 2, NULL, 'REF-2025-001202', '2025-03-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-02-27 16:00:00', '2025-03-01 17:00:00'),
(204, 2, 1, NULL, 'REF-2025-001203', '2025-11-03', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-10-20 16:00:00', '2025-11-02 17:00:00'),
(205, 7, 2, NULL, 'REF-2025-001204', '2025-11-13', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-08 16:00:00', '2025-11-12 17:00:00'),
(206, 9, 1, NULL, 'REF-2025-001205', '2025-09-04', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-08-31 16:00:00', '2025-09-03 17:00:00'),
(207, 14, 2, NULL, 'REF-2025-001206', '2025-10-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-30 16:00:00', '2025-10-12 17:00:00'),
(208, 12, 2, NULL, 'REF-2025-001207', '2025-04-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-28 16:00:00', '2025-04-11 17:00:00'),
(209, 20, 3, NULL, 'REF-2025-001208', '2025-09-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-31 16:00:00', '2025-09-02 17:00:00'),
(210, 19, 3, NULL, 'REF-2025-001209', '2025-02-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-29 16:00:00', '2025-02-12 17:00:00'),
(211, 6, 3, NULL, 'REF-2025-001210', '2025-05-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-29 16:00:00', '2025-05-04 17:00:00'),
(212, 74, 3, NULL, 'REF-2025-001211', '2025-10-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-02 16:00:00', '2025-10-10 17:00:00'),
(213, 3, 2, NULL, 'REF-2025-001212', '2025-03-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-02-19 16:00:00', '2025-03-04 17:00:00'),
(214, 20, 1, NULL, 'REF-2025-001213', '2025-03-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-02-25 16:00:00', '2025-03-11 17:00:00'),
(215, 74, 1, NULL, 'REF-2025-001214', '2025-02-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-30 16:00:00', '2025-01-31 17:00:00'),
(216, 54, 3, NULL, 'REF-2025-001215', '2025-06-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-29 16:00:00', '2025-06-06 17:00:00'),
(217, 86, 1, NULL, 'REF-2025-001216', '2025-03-10', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-02 16:00:00', '2025-03-09 17:00:00'),
(218, 13, 2, NULL, 'REF-2025-001217', '2025-09-07', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-27 16:00:00', '2025-09-06 17:00:00'),
(219, 58, 2, NULL, 'REF-2025-001218', '2025-02-07', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-04 16:00:00', '2025-02-06 17:00:00'),
(220, 18, 3, NULL, 'REF-2025-001219', '2025-10-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-09-23 16:00:00', '2025-10-03 17:00:00'),
(221, 9, 3, NULL, 'REF-2025-001220', '2025-10-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-26 16:00:00', '2025-10-10 17:00:00'),
(222, 15, 1, NULL, 'REF-2025-001221', '2025-12-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-27 16:00:00', '2025-12-08 17:00:00'),
(223, 87, 1, NULL, 'REF-2025-001222', '2025-07-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-21 16:00:00', '2025-07-02 17:00:00'),
(224, 66, 2, NULL, 'REF-2025-001223', '2025-06-07', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-06-03 16:00:00', '2025-06-06 17:00:00'),
(225, 68, 2, NULL, 'REF-2025-001224', '2025-01-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-25 16:00:00', '2025-01-07 17:00:00'),
(226, 3, 1, NULL, 'REF-2025-001225', '2025-10-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-23 16:00:00', '2025-10-01 17:00:00'),
(227, 65, 3, NULL, 'REF-2025-001226', '2025-11-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-11-01 16:00:00', '2025-11-11 17:00:00'),
(228, 12, 3, NULL, 'REF-2025-001227', '2025-03-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-02-20 16:00:00', '2025-03-05 17:00:00'),
(229, 2, 3, NULL, 'REF-2025-001228', '2025-08-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-23 16:00:00', '2025-08-03 17:00:00'),
(230, 2, 3, NULL, 'REF-2025-001229', '2025-08-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-21 16:00:00', '2025-07-31 17:00:00'),
(231, 80, 3, NULL, 'REF-2025-001230', '2025-02-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-02-05 16:00:00', '2025-02-08 17:00:00'),
(232, 60, 2, NULL, 'REF-2025-001231', '2025-07-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-25 16:00:00', '2025-07-04 17:00:00'),
(233, 87, 3, NULL, 'REF-2025-001232', '2025-12-11', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-06 16:00:00', '2025-12-10 17:00:00'),
(234, 83, 3, NULL, 'REF-2025-001233', '2025-08-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-08-07 16:00:00', '2025-08-13 17:00:00'),
(235, 73, 3, NULL, 'REF-2025-001234', '2025-08-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-26 16:00:00', '2025-08-01 17:00:00'),
(236, 20, 1, NULL, 'REF-2025-001235', '2025-11-14', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-11-06 16:00:00', '2025-11-13 17:00:00'),
(237, 69, 1, NULL, 'REF-2025-001236', '2025-12-03', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-28 16:00:00', '2025-12-02 17:00:00');
INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(238, 68, 2, NULL, 'REF-2025-001237', '2025-11-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-01 16:00:00', '2025-11-02 17:00:00'),
(239, 3, 1, NULL, 'REF-2025-001238', '2025-03-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-21 16:00:00', '2025-03-04 17:00:00'),
(240, 85, 2, NULL, 'REF-2025-001239', '2025-05-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-05-05 16:00:00', '2025-05-07 17:00:00'),
(241, 72, 3, NULL, 'REF-2025-001240', '2025-10-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-09-22 16:00:00', '2025-10-02 17:00:00'),
(242, 59, 1, NULL, 'REF-2025-001241', '2025-07-11', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-04 16:00:00', '2025-07-10 17:00:00'),
(243, 77, 2, NULL, 'REF-2025-001242', '2025-07-08', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-30 16:00:00', '2025-07-07 17:00:00'),
(244, 59, 2, NULL, 'REF-2025-001243', '2025-02-06', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-02-04 16:00:00', '2025-02-05 17:00:00'),
(245, 55, 1, NULL, 'REF-2025-001244', '2025-05-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-08 16:00:00', '2025-05-09 17:00:00'),
(246, 2, 2, NULL, 'REF-2025-001245', '2025-11-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-11-01 16:00:00', '2025-11-11 17:00:00'),
(247, 65, 3, NULL, 'REF-2025-001246', '2025-09-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-08 16:00:00', '2025-09-11 17:00:00'),
(248, 52, 1, NULL, 'REF-2025-001247', '2025-05-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-22 16:00:00', '2025-05-02 17:00:00'),
(249, 3, 2, NULL, 'REF-2025-001248', '2025-03-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-12 16:00:00', '2025-03-13 17:00:00'),
(250, 91, 2, NULL, 'REF-2025-001249', '2025-01-05', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-26 16:00:00', '2025-01-04 17:00:00'),
(251, 88, 1, NULL, 'REF-2025-001250', '2025-03-06', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-24 16:00:00', '2025-03-05 17:00:00'),
(252, 73, 3, NULL, 'REF-2025-001251', '2025-01-13', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-04 16:00:00', '2025-01-12 17:00:00'),
(253, 67, 2, NULL, 'REF-2025-001252', '2025-10-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-09-22 16:00:00', '2025-10-05 17:00:00'),
(254, 79, 1, NULL, 'REF-2025-001253', '2025-07-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-29 16:00:00', '2025-07-03 17:00:00'),
(255, 58, 1, NULL, 'REF-2025-001254', '2025-07-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-07-04 16:00:00', '2025-07-10 17:00:00'),
(256, 80, 1, NULL, 'REF-2025-001255', '2025-02-07', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-01-23 16:00:00', '2025-02-06 17:00:00'),
(257, 61, 1, NULL, 'REF-2025-001256', '2025-10-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-26 16:00:00', '2025-10-07 17:00:00'),
(258, 55, 2, NULL, 'REF-2025-001257', '2025-03-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-27 16:00:00', '2025-03-02 17:00:00'),
(259, 81, 2, NULL, 'REF-2025-001258', '2025-01-03', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-29 16:00:00', '2025-01-02 17:00:00'),
(260, 81, 1, NULL, 'REF-2025-001259', '2025-04-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-06 16:00:00', '2025-04-13 17:00:00'),
(261, 72, 1, NULL, 'REF-2025-001260', '2025-03-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-02-21 16:00:00', '2025-03-04 17:00:00'),
(262, 75, 3, NULL, 'REF-2025-001261', '2025-11-04', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-30 16:00:00', '2025-11-03 17:00:00'),
(263, 53, 3, NULL, 'REF-2025-001262', '2025-05-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-02 16:00:00', '2025-05-11 17:00:00'),
(264, 67, 3, NULL, 'REF-2025-001263', '2025-03-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-25 16:00:00', '2025-03-08 17:00:00'),
(265, 3, 2, NULL, 'REF-2025-001264', '2025-05-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-18 16:00:00', '2025-05-01 17:00:00'),
(266, 84, 3, NULL, 'REF-2025-001265', '2025-12-08', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-03 16:00:00', '2025-12-07 17:00:00'),
(267, 71, 1, NULL, 'REF-2025-001266', '2025-06-11', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-06-04 16:00:00', '2025-06-10 17:00:00'),
(268, 57, 1, NULL, 'REF-2025-001267', '2025-03-08', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-24 16:00:00', '2025-03-07 17:00:00'),
(269, 68, 1, NULL, 'REF-2025-001268', '2025-05-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-04-18 16:00:00', '2025-05-02 17:00:00'),
(270, 69, 1, NULL, 'REF-2025-001269', '2025-02-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-28 16:00:00', '2025-02-11 17:00:00'),
(271, 16, 3, NULL, 'REF-2025-001270', '2025-01-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-19 16:00:00', '2024-12-31 17:00:00'),
(272, 71, 2, NULL, 'REF-2025-001271', '2025-06-10', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-06-05 16:00:00', '2025-06-09 17:00:00'),
(273, 59, 2, NULL, 'REF-2025-001272', '2025-09-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-23 16:00:00', '2025-08-31 17:00:00'),
(274, 73, 1, NULL, 'REF-2025-001273', '2025-09-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-31 16:00:00', '2025-09-09 17:00:00'),
(275, 91, 2, NULL, 'REF-2025-001274', '2025-05-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-30 16:00:00', '2025-05-06 17:00:00'),
(276, 89, 2, NULL, 'REF-2025-001275', '2025-05-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-05-05 16:00:00', '2025-05-06 17:00:00'),
(277, 68, 1, NULL, 'REF-2025-001276', '2025-09-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-09-02 16:00:00', '2025-09-05 17:00:00'),
(278, 2, 1, NULL, 'REF-2025-001277', '2025-03-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-05 16:00:00', '2025-03-10 17:00:00'),
(279, 57, 1, NULL, 'REF-2025-001278', '2025-12-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-26 16:00:00', '2025-12-05 17:00:00'),
(280, 53, 1, NULL, 'REF-2025-001279', '2025-04-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-03-22 16:00:00', '2025-04-01 17:00:00'),
(281, 52, 1, NULL, 'REF-2025-001280', '2025-01-07', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-29 16:00:00', '2025-01-06 17:00:00'),
(282, 5, 2, NULL, 'REF-2025-001281', '2025-11-04', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-27 16:00:00', '2025-11-03 17:00:00'),
(283, 85, 3, NULL, 'REF-2025-001282', '2025-06-10', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-28 16:00:00', '2025-06-09 17:00:00'),
(284, 20, 1, NULL, 'REF-2025-001283', '2025-08-04', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-07-20 16:00:00', '2025-08-03 17:00:00'),
(285, 74, 1, NULL, 'REF-2025-001284', '2025-04-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-05 16:00:00', '2025-04-08 17:00:00'),
(286, 75, 3, NULL, 'REF-2025-001285', '2025-11-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-09 16:00:00', '2025-11-12 17:00:00'),
(287, 3, 3, NULL, 'REF-2025-001286', '2025-09-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-26 16:00:00', '2025-09-01 17:00:00'),
(288, 6, 3, NULL, 'REF-2025-001287', '2025-03-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-07 16:00:00', '2025-03-08 17:00:00'),
(289, 2, 1, NULL, 'REF-2025-001288', '2025-06-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-01 16:00:00', '2025-06-05 17:00:00'),
(290, 84, 1, NULL, 'REF-2025-001289', '2025-05-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-05 16:00:00', '2025-05-12 17:00:00'),
(291, 78, 3, NULL, 'REF-2025-001290', '2025-01-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2024-12-30 16:00:00', '2025-01-10 17:00:00'),
(292, 59, 2, NULL, 'REF-2025-001291', '2025-06-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-29 16:00:00', '2025-06-11 17:00:00'),
(293, 83, 2, NULL, 'REF-2025-001292', '2025-04-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-27 16:00:00', '2025-04-01 17:00:00'),
(294, 6, 3, NULL, 'REF-2025-001293', '2025-12-13', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-05 16:00:00', '2025-12-12 17:00:00'),
(295, 13, 2, NULL, 'REF-2025-001294', '2025-06-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-28 16:00:00', '2025-06-08 17:00:00'),
(296, 70, 2, NULL, 'REF-2025-001295', '2025-10-11', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-05 16:00:00', '2025-10-10 17:00:00'),
(297, 21, 1, NULL, 'REF-2025-001296', '2025-07-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-22 16:00:00', '2025-06-30 17:00:00'),
(298, 10, 3, NULL, 'REF-2025-001297', '2025-08-07', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-25 16:00:00', '2025-08-06 17:00:00'),
(299, 69, 3, NULL, 'REF-2025-001298', '2025-08-07', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-01 16:00:00', '2025-08-06 17:00:00'),
(300, 20, 2, NULL, 'REF-2025-001299', '2025-08-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-08-04 16:00:00', '2025-08-07 17:00:00'),
(301, 18, 3, NULL, 'REF-2025-001300', '2025-01-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2024-12-28 16:00:00', '2025-01-11 17:00:00'),
(302, 72, 1, NULL, 'REF-2025-001301', '2025-11-09', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-05 16:00:00', '2025-11-08 17:00:00'),
(303, 72, 3, NULL, 'REF-2025-001302', '2025-01-11', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2024-12-31 16:00:00', '2025-01-10 17:00:00'),
(304, 4, 1, NULL, 'REF-2025-001303', '2025-10-02', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-23 16:00:00', '2025-10-01 17:00:00'),
(305, 54, 1, NULL, 'REF-2025-001304', '2025-08-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-10 16:00:00', '2025-08-12 17:00:00'),
(306, 18, 3, NULL, 'REF-2025-001305', '2025-11-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-31 16:00:00', '2025-11-12 17:00:00'),
(307, 62, 1, NULL, 'REF-2025-001306', '2025-08-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-07-30 16:00:00', '2025-08-04 17:00:00'),
(308, 55, 1, NULL, 'REF-2025-001307', '2025-10-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-10-03 16:00:00', '2025-10-09 17:00:00'),
(309, 82, 1, NULL, 'REF-2025-001308', '2025-04-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-29 16:00:00', '2025-04-01 17:00:00'),
(310, 62, 3, NULL, 'REF-2025-001309', '2025-12-02', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-17 16:00:00', '2025-12-01 17:00:00'),
(311, 80, 2, NULL, 'REF-2025-001310', '2025-06-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-05-28 16:00:00', '2025-06-06 17:00:00'),
(312, 65, 3, NULL, 'REF-2025-001311', '2025-08-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-31 16:00:00', '2025-08-01 17:00:00'),
(313, 64, 3, NULL, 'REF-2025-001312', '2025-01-12', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-10 16:00:00', '2025-01-11 17:00:00'),
(314, 5, 1, NULL, 'REF-2025-001313', '2025-11-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-10-31 16:00:00', '2025-11-05 17:00:00'),
(315, 80, 3, NULL, 'REF-2025-001314', '2025-06-03', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-21 16:00:00', '2025-06-02 17:00:00'),
(316, 73, 1, NULL, 'REF-2025-001315', '2025-12-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-01 16:00:00', '2025-12-11 17:00:00'),
(317, 59, 1, NULL, 'REF-2025-001316', '2025-07-11', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-30 16:00:00', '2025-07-10 17:00:00'),
(318, 15, 2, NULL, 'REF-2025-001317', '2025-05-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-04-17 16:00:00', '2025-04-30 17:00:00'),
(319, 77, 3, NULL, 'REF-2025-001318', '2025-06-13', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-05 16:00:00', '2025-06-12 17:00:00'),
(320, 72, 1, NULL, 'REF-2025-001319', '2025-05-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-04-22 16:00:00', '2025-05-06 17:00:00'),
(321, 56, 3, NULL, 'REF-2025-001320', '2025-01-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-27 16:00:00', '2025-01-09 17:00:00'),
(322, 15, 2, NULL, 'REF-2025-001321', '2025-01-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-30 16:00:00', '2025-01-08 17:00:00'),
(323, 63, 1, NULL, 'REF-2025-001322', '2025-09-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-27 16:00:00', '2025-09-02 17:00:00'),
(324, 15, 3, NULL, 'REF-2025-001323', '2025-08-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-03 16:00:00', '2025-08-08 17:00:00'),
(325, 83, 1, NULL, 'REF-2025-001324', '2025-06-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-24 16:00:00', '2025-06-07 17:00:00'),
(326, 63, 2, NULL, 'REF-2025-001325', '2025-06-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-05-29 16:00:00', '2025-06-01 17:00:00'),
(327, 20, 1, NULL, 'REF-2025-001326', '2025-02-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-21 16:00:00', '2025-01-31 17:00:00'),
(328, 60, 3, NULL, 'REF-2025-001327', '2025-08-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-07-27 16:00:00', '2025-08-02 17:00:00'),
(329, 63, 1, NULL, 'REF-2025-001328', '2025-05-14', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-12 16:00:00', '2025-05-13 17:00:00'),
(330, 69, 1, NULL, 'REF-2025-001329', '2025-06-03', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-31 16:00:00', '2025-06-02 17:00:00'),
(331, 65, 1, NULL, 'REF-2025-001330', '2025-05-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-03 16:00:00', '2025-05-08 17:00:00'),
(332, 87, 1, NULL, 'REF-2025-001331', '2025-02-05', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-26 16:00:00', '2025-02-04 17:00:00'),
(333, 2, 1, NULL, 'REF-2025-001332', '2025-05-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-30 16:00:00', '2025-05-13 17:00:00'),
(334, 17, 2, NULL, 'REF-2025-001333', '2025-02-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-06 16:00:00', '2025-02-07 17:00:00'),
(335, 77, 2, NULL, 'REF-2025-001334', '2025-01-10', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-04 16:00:00', '2025-01-09 17:00:00'),
(336, 63, 1, NULL, 'REF-2025-001335', '2025-06-13', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-05-30 16:00:00', '2025-06-12 17:00:00'),
(337, 62, 3, NULL, 'REF-2025-001336', '2025-05-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-05-08 16:00:00', '2025-05-13 17:00:00'),
(338, 84, 3, NULL, 'REF-2025-001337', '2025-12-14', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-11-30 16:00:00', '2025-12-13 17:00:00'),
(339, 82, 3, NULL, 'REF-2025-001338', '2025-11-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-06 16:00:00', '2025-11-10 17:00:00'),
(340, 85, 1, NULL, 'REF-2025-001339', '2025-05-03', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-23 16:00:00', '2025-05-02 17:00:00'),
(341, 82, 3, NULL, 'REF-2025-001340', '2025-10-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-25 16:00:00', '2025-10-06 17:00:00'),
(342, 61, 3, NULL, 'REF-2025-001341', '2025-10-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-25 16:00:00', '2025-10-07 17:00:00'),
(343, 73, 1, NULL, 'REF-2025-001342', '2025-05-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-01 16:00:00', '2025-05-07 17:00:00'),
(344, 6, 3, NULL, 'REF-2025-001343', '2025-11-09', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-05 16:00:00', '2025-11-08 17:00:00'),
(345, 56, 1, NULL, 'REF-2025-001344', '2025-02-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-31 16:00:00', '2025-02-03 17:00:00'),
(346, 89, 1, NULL, 'REF-2025-001345', '2025-06-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-04 16:00:00', '2025-06-06 17:00:00'),
(347, 14, 1, NULL, 'REF-2025-001346', '2025-01-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2024-12-27 16:00:00', '2025-01-06 17:00:00'),
(348, 72, 2, NULL, 'REF-2025-001347', '2025-11-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-10-20 16:00:00', '2025-10-31 17:00:00'),
(349, 9, 1, NULL, 'REF-2025-001348', '2025-08-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-05 16:00:00', '2025-08-08 17:00:00'),
(350, 85, 1, NULL, 'REF-2025-001349', '2025-02-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-05 16:00:00', '2025-02-13 17:00:00'),
(351, 72, 3, NULL, 'REF-2025-001350', '2025-04-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-25 16:00:00', '2025-04-04 17:00:00'),
(352, 74, 2, NULL, 'REF-2025-001351', '2025-01-01', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2024-12-21 16:00:00', '2024-12-31 17:00:00'),
(353, 8, 3, NULL, 'REF-2025-001352', '2025-09-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-02 16:00:00', '2025-09-09 17:00:00'),
(354, 74, 1, NULL, 'REF-2025-001353', '2025-02-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-02 16:00:00', '2025-02-09 17:00:00'),
(355, 55, 2, NULL, 'REF-2025-001354', '2025-05-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-09 16:00:00', '2025-05-11 17:00:00'),
(356, 6, 2, NULL, 'REF-2025-001355', '2025-06-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-24 16:00:00', '2025-06-02 17:00:00'),
(357, 68, 2, NULL, 'REF-2025-001356', '2025-04-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-31 16:00:00', '2025-04-08 17:00:00'),
(358, 14, 2, NULL, 'REF-2025-001357', '2025-09-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-09-01 16:00:00', '2025-09-13 17:00:00'),
(359, 17, 1, NULL, 'REF-2025-001358', '2025-10-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-10-10 16:00:00', '2025-10-11 17:00:00'),
(360, 55, 3, NULL, 'REF-2025-001359', '2025-05-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-07 16:00:00', '2025-05-08 17:00:00'),
(361, 88, 1, NULL, 'REF-2025-001360', '2025-12-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-27 16:00:00', '2025-12-04 17:00:00'),
(362, 68, 2, NULL, 'REF-2025-001361', '2025-09-13', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-29 16:00:00', '2025-09-12 17:00:00'),
(363, 13, 2, NULL, 'REF-2025-001362', '2025-11-10', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-02 16:00:00', '2025-11-09 17:00:00'),
(364, 86, 3, NULL, 'REF-2025-001363', '2025-05-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-04-29 16:00:00', '2025-05-11 17:00:00'),
(365, 8, 3, NULL, 'REF-2025-001364', '2025-10-06', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-30 16:00:00', '2025-10-05 17:00:00'),
(366, 59, 2, NULL, 'REF-2025-001365', '2025-02-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-18 16:00:00', '2025-02-01 17:00:00'),
(367, 84, 2, NULL, 'REF-2025-001366', '2025-11-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-27 16:00:00', '2025-11-04 17:00:00'),
(368, 82, 2, NULL, 'REF-2025-001367', '2025-09-03', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-27 16:00:00', '2025-09-02 17:00:00'),
(369, 16, 1, NULL, 'REF-2025-001368', '2025-08-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-27 16:00:00', '2025-08-05 17:00:00'),
(370, 53, 1, NULL, 'REF-2025-001369', '2025-06-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-06-05 16:00:00', '2025-06-10 17:00:00'),
(371, 52, 1, NULL, 'REF-2025-001370', '2025-09-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-05 16:00:00', '2025-09-11 17:00:00'),
(372, 86, 2, NULL, 'REF-2025-001371', '2025-08-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-06 16:00:00', '2025-08-13 17:00:00'),
(373, 9, 2, NULL, 'REF-2025-001372', '2025-07-10', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-07-04 16:00:00', '2025-07-09 17:00:00'),
(374, 76, 1, NULL, 'REF-2025-001373', '2025-10-14', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-07 16:00:00', '2025-10-13 17:00:00'),
(375, 70, 2, NULL, 'REF-2025-001374', '2025-12-03', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-30 16:00:00', '2025-12-02 17:00:00'),
(376, 84, 1, NULL, 'REF-2025-001375', '2025-08-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-07-30 16:00:00', '2025-08-04 17:00:00'),
(377, 4, 1, NULL, 'REF-2025-001376', '2025-05-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-23 16:00:00', '2025-05-07 17:00:00'),
(378, 53, 3, NULL, 'REF-2025-001377', '2025-05-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-05-04 16:00:00', '2025-05-06 17:00:00'),
(379, 89, 1, NULL, 'REF-2025-001378', '2025-12-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-07 16:00:00', '2025-12-08 17:00:00'),
(380, 66, 1, NULL, 'REF-2025-001379', '2025-05-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-20 16:00:00', '2025-05-04 17:00:00'),
(381, 69, 1, NULL, 'REF-2025-001380', '2025-06-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-06-03 16:00:00', '2025-06-06 17:00:00'),
(382, 6, 2, NULL, 'REF-2025-001381', '2025-10-10', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-02 16:00:00', '2025-10-09 17:00:00'),
(383, 66, 2, NULL, 'REF-2025-001382', '2025-12-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-11-25 16:00:00', '2025-12-03 17:00:00'),
(384, 78, 2, NULL, 'REF-2025-001383', '2025-07-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-01 16:00:00', '2025-07-02 17:00:00'),
(385, 7, 2, NULL, 'REF-2025-001384', '2025-05-04', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-02 16:00:00', '2025-05-03 17:00:00'),
(386, 82, 1, NULL, 'REF-2025-001385', '2025-10-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-24 16:00:00', '2025-10-06 17:00:00'),
(387, 19, 1, NULL, 'REF-2025-001386', '2025-03-10', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-25 16:00:00', '2025-03-09 17:00:00'),
(388, 60, 1, NULL, 'REF-2025-001387', '2025-06-04', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-06-02 16:00:00', '2025-06-03 17:00:00'),
(389, 90, 2, NULL, 'REF-2025-001388', '2025-12-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-11-28 16:00:00', '2025-12-10 17:00:00'),
(390, 59, 3, NULL, 'REF-2025-001389', '2025-05-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-24 16:00:00', '2025-05-05 17:00:00'),
(391, 66, 3, NULL, 'REF-2025-001390', '2025-07-06', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-24 16:00:00', '2025-07-05 17:00:00'),
(392, 57, 1, NULL, 'REF-2025-001391', '2025-12-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-11-28 16:00:00', '2025-12-05 17:00:00'),
(393, 76, 3, NULL, 'REF-2025-001392', '2025-02-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-29 16:00:00', '2025-02-09 17:00:00'),
(394, 13, 2, NULL, 'REF-2025-001393', '2025-06-03', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-21 16:00:00', '2025-06-02 17:00:00'),
(395, 63, 2, NULL, 'REF-2025-001394', '2025-12-04', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-25 16:00:00', '2025-12-03 17:00:00'),
(396, 54, 2, NULL, 'REF-2025-001395', '2025-08-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-07-31 16:00:00', '2025-08-05 17:00:00'),
(397, 84, 3, NULL, 'REF-2025-001396', '2025-03-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-05 16:00:00', '2025-03-07 17:00:00'),
(398, 83, 1, NULL, 'REF-2025-001397', '2025-11-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-05 16:00:00', '2025-11-08 17:00:00'),
(399, 8, 3, NULL, 'REF-2025-001398', '2025-06-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-28 16:00:00', '2025-06-08 17:00:00'),
(400, 80, 1, NULL, 'REF-2025-001399', '2025-03-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-28 16:00:00', '2025-03-05 17:00:00'),
(401, 20, 2, NULL, 'REF-2025-001400', '2025-02-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-30 16:00:00', '2025-01-31 17:00:00'),
(402, 53, 2, NULL, 'REF-2025-001401', '2025-03-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-05 16:00:00', '2025-03-11 17:00:00'),
(403, 68, 1, NULL, 'REF-2025-001402', '2025-03-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-28 16:00:00', '2025-03-13 17:00:00'),
(404, 74, 3, NULL, 'REF-2025-001403', '2025-04-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-03-19 16:00:00', '2025-03-31 17:00:00'),
(405, 89, 2, NULL, 'REF-2025-001404', '2025-08-11', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-31 16:00:00', '2025-08-10 17:00:00'),
(406, 56, 3, NULL, 'REF-2025-001405', '2025-05-04', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-04-27 16:00:00', '2025-05-03 17:00:00'),
(407, 80, 3, NULL, 'REF-2025-001406', '2025-12-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-06 16:00:00', '2025-12-08 17:00:00'),
(408, 54, 2, NULL, 'REF-2025-001407', '2025-01-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-27 16:00:00', '2025-01-03 17:00:00'),
(409, 68, 2, NULL, 'REF-2025-001408', '2025-01-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-26 16:00:00', '2025-01-07 17:00:00'),
(410, 12, 1, NULL, 'REF-2025-001409', '2025-06-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-28 16:00:00', '2025-06-11 17:00:00'),
(411, 69, 1, NULL, 'REF-2025-001410', '2025-08-01', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-19 16:00:00', '2025-07-31 17:00:00'),
(412, 58, 1, NULL, 'REF-2025-001411', '2025-02-04', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-24 16:00:00', '2025-02-03 17:00:00'),
(413, 14, 3, NULL, 'REF-2025-001412', '2025-11-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-11-04 16:00:00', '2025-11-08 17:00:00'),
(414, 90, 1, NULL, 'REF-2025-001413', '2025-07-07', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-23 16:00:00', '2025-07-06 17:00:00'),
(415, 68, 1, NULL, 'REF-2025-001414', '2025-11-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-10-19 16:00:00', '2025-11-01 17:00:00'),
(416, 80, 2, NULL, 'REF-2025-001415', '2025-10-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-24 16:00:00', '2025-10-08 17:00:00'),
(417, 89, 2, NULL, 'REF-2025-001416', '2025-03-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-21 16:00:00', '2025-03-05 17:00:00'),
(418, 80, 1, NULL, 'REF-2025-001417', '2025-05-04', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-04-24 16:00:00', '2025-05-03 17:00:00'),
(419, 88, 3, NULL, 'REF-2025-001418', '2025-02-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-29 16:00:00', '2025-01-31 17:00:00'),
(420, 52, 1, NULL, 'REF-2025-001419', '2025-07-10', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-08 16:00:00', '2025-07-09 17:00:00'),
(421, 52, 2, NULL, 'REF-2025-001420', '2025-07-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-07 16:00:00', '2025-07-11 17:00:00'),
(422, 20, 3, NULL, 'REF-2025-001421', '2025-03-01', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-21 16:00:00', '2025-02-28 17:00:00'),
(423, 20, 1, NULL, 'REF-2025-001422', '2025-05-13', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-08 16:00:00', '2025-05-12 17:00:00'),
(424, 64, 2, NULL, 'REF-2025-001423', '2025-11-04', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-20 16:00:00', '2025-11-03 17:00:00'),
(425, 5, 2, NULL, 'REF-2025-001424', '2025-05-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-30 16:00:00', '2025-05-11 17:00:00'),
(426, 56, 2, NULL, 'REF-2025-001425', '2025-07-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-30 16:00:00', '2025-07-07 17:00:00'),
(427, 76, 3, NULL, 'REF-2025-001426', '2025-12-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-03 16:00:00', '2025-12-07 17:00:00'),
(428, 8, 1, NULL, 'REF-2025-001427', '2025-09-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-08 16:00:00', '2025-09-11 17:00:00'),
(429, 17, 1, NULL, 'REF-2025-001428', '2025-07-01', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-24 16:00:00', '2025-06-30 17:00:00'),
(430, 79, 3, NULL, 'REF-2025-001429', '2025-01-06', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2024-12-28 16:00:00', '2025-01-05 17:00:00'),
(431, 20, 2, NULL, 'REF-2025-001430', '2025-11-11', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-10-28 16:00:00', '2025-11-10 17:00:00'),
(432, 3, 2, NULL, 'REF-2025-001431', '2025-02-05', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-29 16:00:00', '2025-02-04 17:00:00'),
(433, 8, 3, NULL, 'REF-2025-001432', '2025-10-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-28 16:00:00', '2025-09-30 17:00:00'),
(434, 84, 1, NULL, 'REF-2025-001433', '2025-03-11', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-02-25 16:00:00', '2025-03-10 17:00:00'),
(435, 59, 1, NULL, 'REF-2025-001434', '2025-01-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2024-12-26 16:00:00', '2025-01-09 17:00:00'),
(436, 1, 2, NULL, 'REF-2025-001435', '2025-07-13', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-04 16:00:00', '2025-07-12 17:00:00'),
(437, 75, 1, NULL, 'REF-2025-001436', '2025-08-08', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-08-01 16:00:00', '2025-08-07 17:00:00'),
(438, 14, 3, NULL, 'REF-2025-001437', '2025-11-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-11-06 16:00:00', '2025-11-13 17:00:00'),
(439, 57, 1, NULL, 'REF-2025-001438', '2025-07-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-07-06 16:00:00', '2025-07-11 17:00:00'),
(440, 55, 3, NULL, 'REF-2025-001439', '2025-12-08', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-29 16:00:00', '2025-12-07 17:00:00'),
(441, 14, 2, NULL, 'REF-2025-001440', '2025-09-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-28 16:00:00', '2025-09-10 17:00:00'),
(442, 85, 2, NULL, 'REF-2025-001441', '2025-02-07', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-01-29 16:00:00', '2025-02-06 17:00:00'),
(443, 57, 1, NULL, 'REF-2025-001442', '2025-07-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-06-21 16:00:00', '2025-07-05 17:00:00'),
(444, 82, 2, NULL, 'REF-2025-001443', '2025-05-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-29 16:00:00', '2025-05-10 17:00:00'),
(445, 83, 3, NULL, 'REF-2025-001444', '2025-01-12', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-07 16:00:00', '2025-01-11 17:00:00'),
(446, 2, 1, NULL, 'REF-2025-001445', '2025-11-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-30 16:00:00', '2025-11-11 17:00:00'),
(447, 19, 1, NULL, 'REF-2025-001446', '2025-11-02', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-10-24 16:00:00', '2025-11-01 17:00:00'),
(448, 74, 1, NULL, 'REF-2025-001447', '2025-08-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-08-02 16:00:00', '2025-08-06 17:00:00'),
(449, 83, 1, NULL, 'REF-2025-001448', '2025-03-04', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-02-28 16:00:00', '2025-03-03 17:00:00'),
(450, 68, 3, NULL, 'REF-2025-001449', '2025-04-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-25 16:00:00', '2025-04-01 17:00:00'),
(451, 88, 2, NULL, 'REF-2025-001450', '2025-11-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-10-31 16:00:00', '2025-11-08 17:00:00'),
(452, 16, 3, NULL, 'REF-2025-001451', '2025-12-01', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-18 16:00:00', '2025-11-30 17:00:00'),
(453, 84, 3, NULL, 'REF-2025-001452', '2025-05-06', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-03 16:00:00', '2025-05-05 17:00:00'),
(454, 70, 1, NULL, 'REF-2025-001453', '2025-01-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-01 16:00:00', '2025-01-13 17:00:00'),
(455, 67, 3, NULL, 'REF-2025-001454', '2025-06-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-03 16:00:00', '2025-06-05 17:00:00'),
(456, 89, 3, NULL, 'REF-2025-001455', '2025-03-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-03 16:00:00', '2025-03-13 17:00:00'),
(457, 21, 1, NULL, 'REF-2025-001456', '2025-02-04', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-27 16:00:00', '2025-02-03 17:00:00'),
(458, 5, 1, NULL, 'REF-2025-001457', '2025-09-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-29 16:00:00', '2025-09-10 17:00:00'),
(459, 90, 1, NULL, 'REF-2025-001458', '2025-09-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-31 16:00:00', '2025-09-04 17:00:00'),
(460, 86, 2, NULL, 'REF-2025-001459', '2025-02-12', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-09 16:00:00', '2025-02-11 17:00:00'),
(461, 66, 3, NULL, 'REF-2025-001460', '2025-05-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-05-01 16:00:00', '2025-05-05 17:00:00'),
(462, 66, 2, NULL, 'REF-2025-001461', '2025-03-01', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-18 16:00:00', '2025-02-28 17:00:00'),
(463, 61, 2, NULL, 'REF-2025-001462', '2025-05-14', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-09 16:00:00', '2025-05-13 17:00:00'),
(464, 74, 1, NULL, 'REF-2025-001463', '2025-07-08', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-03 16:00:00', '2025-07-07 17:00:00'),
(465, 58, 2, NULL, 'REF-2025-001464', '2025-07-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-30 16:00:00', '2025-07-05 17:00:00'),
(466, 12, 1, NULL, 'REF-2025-001465', '2025-08-08', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-25 16:00:00', '2025-08-07 17:00:00'),
(467, 14, 3, NULL, 'REF-2025-001466', '2025-04-13', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-03-31 16:00:00', '2025-04-12 17:00:00'),
(468, 72, 1, NULL, 'REF-2025-001467', '2025-03-01', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-02-20 16:00:00', '2025-02-28 17:00:00'),
(469, 2, 1, NULL, 'REF-2025-001468', '2025-04-05', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-22 16:00:00', '2025-04-04 17:00:00'),
(470, 9, 1, NULL, 'REF-2025-001469', '2025-11-02', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-10-22 16:00:00', '2025-11-01 17:00:00'),
(471, 69, 2, NULL, 'REF-2025-001470', '2025-06-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-02 16:00:00', '2025-06-10 17:00:00'),
(472, 64, 1, NULL, 'REF-2025-001471', '2025-12-13', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-07 16:00:00', '2025-12-12 17:00:00'),
(473, 7, 2, NULL, 'REF-2025-001472', '2025-03-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-02-20 16:00:00', '2025-03-06 17:00:00'),
(474, 14, 1, NULL, 'REF-2025-001473', '2025-12-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-11-27 16:00:00', '2025-12-06 17:00:00');
INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(475, 19, 1, NULL, 'REF-2025-001474', '2025-09-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-27 16:00:00', '2025-09-01 17:00:00'),
(476, 89, 2, NULL, 'REF-2025-001475', '2025-03-03', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-02-24 16:00:00', '2025-03-02 17:00:00'),
(477, 16, 3, NULL, 'REF-2025-001476', '2025-07-08', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-29 16:00:00', '2025-07-07 17:00:00'),
(478, 71, 2, NULL, 'REF-2025-001477', '2025-08-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-07-31 16:00:00', '2025-08-08 17:00:00'),
(479, 84, 1, NULL, 'REF-2025-001478', '2025-01-01', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-29 16:00:00', '2024-12-31 17:00:00'),
(480, 55, 1, NULL, 'REF-2025-001479', '2025-10-08', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-25 16:00:00', '2025-10-07 17:00:00'),
(481, 59, 3, NULL, 'REF-2025-001480', '2025-04-12', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-04-01 16:00:00', '2025-04-11 17:00:00'),
(482, 65, 3, NULL, 'REF-2025-001481', '2025-09-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-09-02 16:00:00', '2025-09-11 17:00:00'),
(483, 3, 1, NULL, 'REF-2025-001482', '2025-07-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-19 16:00:00', '2025-07-02 17:00:00'),
(484, 65, 2, NULL, 'REF-2025-001483', '2025-01-13', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-01-07 16:00:00', '2025-01-12 17:00:00'),
(485, 11, 2, NULL, 'REF-2025-001484', '2025-09-06', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-08-22 16:00:00', '2025-09-05 17:00:00'),
(486, 82, 1, NULL, 'REF-2025-001485', '2025-08-05', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-30 16:00:00', '2025-08-04 17:00:00'),
(487, 85, 3, NULL, 'REF-2025-001486', '2025-08-09', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-28 16:00:00', '2025-08-08 17:00:00'),
(488, 86, 1, NULL, 'REF-2025-001487', '2025-08-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-17 16:00:00', '2025-07-31 17:00:00'),
(489, 73, 1, NULL, 'REF-2025-001488', '2025-01-08', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-28 16:00:00', '2025-01-07 17:00:00'),
(490, 65, 2, NULL, 'REF-2025-001489', '2025-09-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-25 16:00:00', '2025-09-07 17:00:00'),
(491, 91, 1, NULL, 'REF-2025-001490', '2025-05-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-04 16:00:00', '2025-05-05 17:00:00'),
(492, 81, 2, NULL, 'REF-2025-001491', '2025-12-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-25 16:00:00', '2025-12-02 17:00:00'),
(493, 62, 3, NULL, 'REF-2025-001492', '2025-05-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-04-30 16:00:00', '2025-05-10 17:00:00'),
(494, 2, 2, NULL, 'REF-2025-001493', '2025-09-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-08-20 16:00:00', '2025-09-02 17:00:00'),
(495, 84, 2, NULL, 'REF-2025-001494', '2025-07-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-05 16:00:00', '2025-07-13 17:00:00'),
(496, 84, 2, NULL, 'REF-2025-001495', '2025-04-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-03-27 16:00:00', '2025-04-07 17:00:00'),
(497, 3, 3, NULL, 'REF-2025-001496', '2025-06-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-23 16:00:00', '2025-06-04 17:00:00'),
(498, 84, 3, NULL, 'REF-2025-001497', '2025-03-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-02-22 16:00:00', '2025-02-28 17:00:00'),
(499, 84, 3, NULL, 'REF-2025-001498', '2025-12-06', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-25 16:00:00', '2025-12-05 17:00:00'),
(500, 58, 2, NULL, 'REF-2025-001499', '2025-02-12', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-09 16:00:00', '2025-02-11 17:00:00'),
(501, 1, 1, NULL, 'REF-2025-001500', '2025-08-07', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-31 16:00:00', '2025-08-06 17:00:00'),
(502, 77, 2, NULL, 'REF-2025-001501', '2025-08-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-07-24 16:00:00', '2025-08-01 17:00:00'),
(503, 90, 1, NULL, 'REF-2025-001502', '2025-06-11', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-06-06 16:00:00', '2025-06-10 17:00:00'),
(504, 72, 1, NULL, 'REF-2025-001503', '2025-12-13', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-12-04 16:00:00', '2025-12-12 17:00:00'),
(505, 73, 1, NULL, 'REF-2025-001504', '2025-11-09', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-11-01 16:00:00', '2025-11-08 17:00:00'),
(506, 67, 1, NULL, 'REF-2025-001505', '2025-04-06', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-03-28 16:00:00', '2025-04-05 17:00:00'),
(507, 79, 1, NULL, 'REF-2025-001506', '2025-10-05', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-25 16:00:00', '2025-10-04 17:00:00'),
(508, 57, 1, NULL, 'REF-2025-001507', '2025-08-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-08-05 16:00:00', '2025-08-07 17:00:00'),
(509, 80, 3, NULL, 'REF-2025-001508', '2025-08-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-08-04 16:00:00', '2025-08-08 17:00:00'),
(510, 64, 1, NULL, 'REF-2025-001509', '2025-02-05', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-02 16:00:00', '2025-02-04 17:00:00'),
(511, 87, 2, NULL, 'REF-2025-001510', '2025-04-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-03 16:00:00', '2025-04-07 17:00:00'),
(512, 52, 3, NULL, 'REF-2025-001511', '2025-08-10', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-01 16:00:00', '2025-08-09 17:00:00'),
(513, 8, 1, NULL, 'REF-2025-001512', '2025-04-06', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-03-28 16:00:00', '2025-04-05 17:00:00'),
(514, 14, 2, NULL, 'REF-2025-001513', '2025-02-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-02-07 16:00:00', '2025-02-09 17:00:00'),
(515, 52, 2, NULL, 'REF-2025-001514', '2025-02-08', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-02-02 16:00:00', '2025-02-07 17:00:00'),
(516, 84, 2, NULL, 'REF-2025-001515', '2025-05-01', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-29 16:00:00', '2025-04-30 17:00:00'),
(517, 86, 3, NULL, 'REF-2025-001516', '2025-06-07', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-29 16:00:00', '2025-06-06 17:00:00'),
(518, 17, 2, NULL, 'REF-2025-001517', '2025-01-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-27 16:00:00', '2025-01-10 17:00:00'),
(519, 13, 3, NULL, 'REF-2025-001518', '2025-02-05', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-26 16:00:00', '2025-02-04 17:00:00'),
(520, 72, 2, NULL, 'REF-2025-001519', '2025-10-11', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-10-06 16:00:00', '2025-10-10 17:00:00'),
(521, 91, 1, NULL, 'REF-2025-001520', '2025-07-13', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-07-02 16:00:00', '2025-07-12 17:00:00'),
(522, 85, 2, NULL, 'REF-2025-001521', '2025-09-05', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-09-02 16:00:00', '2025-09-04 17:00:00'),
(523, 68, 3, NULL, 'REF-2025-001522', '2025-04-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-26 16:00:00', '2025-04-08 17:00:00'),
(524, 3, 3, NULL, 'REF-2025-001523', '2025-08-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-08-04 16:00:00', '2025-08-10 17:00:00'),
(525, 83, 1, NULL, 'REF-2025-001524', '2025-04-06', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-25 16:00:00', '2025-04-05 17:00:00'),
(526, 66, 3, NULL, 'REF-2025-001525', '2025-01-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2024-12-22 16:00:00', '2025-01-01 17:00:00'),
(527, 1, 2, NULL, 'REF-2025-001526', '2025-10-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-01 16:00:00', '2025-10-02 17:00:00'),
(528, 88, 2, NULL, 'REF-2025-001527', '2025-07-10', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-03 16:00:00', '2025-07-09 17:00:00'),
(529, 89, 2, NULL, 'REF-2025-001528', '2025-04-07', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-03 16:00:00', '2025-04-06 17:00:00'),
(530, 21, 3, NULL, 'REF-2025-001529', '2025-04-03', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-03-31 16:00:00', '2025-04-02 17:00:00'),
(531, 71, 3, NULL, 'REF-2025-001530', '2025-09-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-08-21 16:00:00', '2025-09-01 17:00:00'),
(532, 81, 2, NULL, 'REF-2025-001531', '2025-12-08', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-28 16:00:00', '2025-12-07 17:00:00'),
(533, 69, 3, NULL, 'REF-2025-001532', '2025-10-06', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-10-01 16:00:00', '2025-10-05 17:00:00'),
(534, 5, 1, NULL, 'REF-2025-001533', '2025-07-07', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-06-29 16:00:00', '2025-07-06 17:00:00'),
(535, 54, 2, NULL, 'REF-2025-001534', '2025-12-08', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-30 16:00:00', '2025-12-07 17:00:00'),
(536, 19, 2, NULL, 'REF-2025-001535', '2025-04-10', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-01 16:00:00', '2025-04-09 17:00:00'),
(537, 21, 3, NULL, 'REF-2025-001536', '2025-09-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-21 16:00:00', '2025-09-04 17:00:00'),
(538, 71, 3, NULL, 'REF-2025-001537', '2025-03-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-22 16:00:00', '2025-03-01 17:00:00'),
(539, 70, 2, NULL, 'REF-2025-001538', '2025-06-02', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-05-25 16:00:00', '2025-06-01 17:00:00'),
(540, 77, 2, NULL, 'REF-2025-001539', '2025-05-08', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-05 16:00:00', '2025-05-07 17:00:00'),
(541, 62, 1, NULL, 'REF-2025-001540', '2025-12-01', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-11-28 16:00:00', '2025-11-30 17:00:00'),
(542, 58, 3, NULL, 'REF-2025-001541', '2025-03-11', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-07 16:00:00', '2025-03-10 17:00:00'),
(543, 21, 1, NULL, 'REF-2025-001542', '2025-08-09', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-27 16:00:00', '2025-08-08 17:00:00'),
(544, 76, 1, NULL, 'REF-2025-001543', '2025-09-02', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-24 16:00:00', '2025-09-01 17:00:00'),
(545, 2, 1, NULL, 'REF-2025-001544', '2025-07-14', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-07-01 16:00:00', '2025-07-13 17:00:00'),
(546, 15, 1, NULL, 'REF-2025-001545', '2025-06-07', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-25 16:00:00', '2025-06-06 17:00:00'),
(547, 62, 1, NULL, 'REF-2025-001546', '2025-09-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-30 16:00:00', '2025-09-04 17:00:00'),
(548, 61, 1, NULL, 'REF-2025-001547', '2025-02-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-25 16:00:00', '2025-02-05 17:00:00'),
(549, 58, 2, NULL, 'REF-2025-001548', '2025-01-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-01-04 16:00:00', '2025-01-08 17:00:00'),
(550, 79, 3, NULL, 'REF-2025-001549', '2025-11-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-10-30 16:00:00', '2025-11-01 17:00:00'),
(551, 21, 2, NULL, 'REF-2025-001550', '2025-12-02', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-22 16:00:00', '2025-12-01 17:00:00'),
(552, 8, 3, NULL, 'REF-2025-001551', '2025-03-06', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-02-25 16:00:00', '2025-03-05 17:00:00'),
(553, 2, 2, NULL, 'REF-2025-001552', '2025-05-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-27 16:00:00', '2025-04-30 17:00:00'),
(554, 18, 1, NULL, 'REF-2025-001553', '2025-04-02', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-03-25 16:00:00', '2025-04-01 17:00:00'),
(555, 74, 1, NULL, 'REF-2025-001554', '2025-10-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-09-21 16:00:00', '2025-09-30 17:00:00'),
(556, 7, 2, NULL, 'REF-2025-001555', '2025-08-03', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-27 16:00:00', '2025-08-02 17:00:00'),
(557, 81, 1, NULL, 'REF-2025-001556', '2025-06-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-05-30 16:00:00', '2025-05-31 17:00:00'),
(558, 52, 1, NULL, 'REF-2025-001557', '2025-10-01', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-09-23 16:00:00', '2025-09-30 17:00:00'),
(559, 89, 3, NULL, 'REF-2025-001558', '2025-10-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-09-24 16:00:00', '2025-09-30 17:00:00'),
(560, 65, 3, NULL, 'REF-2025-001559', '2025-01-13', '16:00:00', '17:00:00', '16:00:00', '17:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-01 16:00:00', '2025-01-12 17:00:00'),
(561, 84, 1, NULL, 'REF-2025-001560', '2025-05-02', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-19 16:00:00', '2025-05-01 17:00:00'),
(562, 12, 3, NULL, 'REF-2025-001561', '2025-02-10', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-02-08 16:00:00', '2025-02-09 17:00:00'),
(563, 73, 2, NULL, 'REF-2025-001562', '2025-05-13', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-04 16:00:00', '2025-05-12 17:00:00'),
(564, 17, 2, NULL, 'REF-2025-001563', '2025-07-05', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-06-29 16:00:00', '2025-07-04 17:00:00'),
(565, 59, 3, NULL, 'REF-2025-001564', '2025-07-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-06-22 16:00:00', '2025-07-02 17:00:00'),
(566, 61, 1, NULL, 'REF-2025-001565', '2025-04-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-03 16:00:00', '2025-04-07 17:00:00'),
(567, 76, 2, NULL, 'REF-2025-001566', '2025-01-14', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-01-04 16:00:00', '2025-01-13 17:00:00'),
(568, 74, 1, NULL, 'REF-2025-001567', '2025-11-03', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-11-01 16:00:00', '2025-11-02 17:00:00'),
(569, 75, 3, NULL, 'REF-2025-001568', '2025-01-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2024-12-30 16:00:00', '2025-01-04 17:00:00'),
(570, 11, 3, NULL, 'REF-2025-001569', '2025-12-07', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-25 16:00:00', '2025-12-06 17:00:00'),
(571, 77, 3, NULL, 'REF-2025-001570', '2025-06-01', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-05-23 16:00:00', '2025-05-31 17:00:00'),
(572, 53, 1, NULL, 'REF-2025-001571', '2025-01-02', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2024-12-24 16:00:00', '2025-01-01 17:00:00'),
(573, 86, 3, NULL, 'REF-2025-001572', '2025-12-09', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-11-26 16:00:00', '2025-12-08 17:00:00'),
(574, 83, 1, NULL, 'REF-2025-001573', '2025-02-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-30 16:00:00', '2025-02-10 17:00:00'),
(575, 62, 2, NULL, 'REF-2025-001574', '2025-11-05', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-10-23 16:00:00', '2025-11-04 17:00:00'),
(576, 14, 3, NULL, 'REF-2025-001575', '2025-09-09', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-09-03 16:00:00', '2025-09-08 17:00:00'),
(577, 1, 3, NULL, 'REF-2025-001576', '2025-10-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-09-26 16:00:00', '2025-10-04 17:00:00'),
(578, 61, 3, NULL, 'REF-2025-001577', '2025-05-11', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-26 16:00:00', '2025-05-10 17:00:00'),
(579, 79, 2, NULL, 'REF-2025-001578', '2025-12-08', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-26 16:00:00', '2025-12-07 17:00:00'),
(580, 21, 1, NULL, 'REF-2025-001579', '2025-09-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-08-29 16:00:00', '2025-09-05 17:00:00'),
(581, 66, 1, NULL, 'REF-2025-001580', '2025-03-01', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-02-26 16:00:00', '2025-02-28 17:00:00'),
(582, 19, 2, NULL, 'REF-2025-001581', '2025-07-14', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-07-08 16:00:00', '2025-07-13 17:00:00'),
(583, 21, 3, NULL, 'REF-2025-001582', '2025-06-04', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-05-25 16:00:00', '2025-06-03 17:00:00'),
(584, 91, 3, NULL, 'REF-2025-001583', '2025-04-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-03-29 16:00:00', '2025-04-07 17:00:00'),
(585, 79, 3, NULL, 'REF-2025-001584', '2025-03-06', '08:00:00', '09:00:00', '08:00:00', '09:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-02-27 16:00:00', '2025-03-05 17:00:00'),
(586, 83, 3, NULL, 'REF-2025-001585', '2025-04-10', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-04-07 16:00:00', '2025-04-09 17:00:00'),
(587, 67, 3, NULL, 'REF-2025-001586', '2025-05-02', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-04-27 16:00:00', '2025-05-01 17:00:00'),
(588, 78, 3, NULL, 'REF-2025-001587', '2025-04-12', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-04-09 16:00:00', '2025-04-11 17:00:00'),
(589, 82, 1, NULL, 'REF-2025-001588', '2025-02-05', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-01-31 16:00:00', '2025-02-04 17:00:00'),
(590, 62, 1, NULL, 'REF-2025-001589', '2025-09-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-08-22 16:00:00', '2025-09-05 17:00:00'),
(591, 60, 2, NULL, 'REF-2025-001590', '2025-05-03', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-04-20 16:00:00', '2025-05-02 17:00:00'),
(592, 3, 1, NULL, 'REF-2025-001591', '2025-04-08', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-03-31 16:00:00', '2025-04-07 17:00:00'),
(593, 77, 1, NULL, 'REF-2025-001592', '2025-12-05', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-11-23 16:00:00', '2025-12-04 17:00:00'),
(594, 53, 3, NULL, 'REF-2025-001593', '2025-10-06', '15:00:00', '16:00:00', '15:00:00', '16:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-09-21 16:00:00', '2025-10-05 17:00:00'),
(595, 13, 1, NULL, 'REF-2025-001594', '2025-05-14', '14:00:00', '15:00:00', '14:00:00', '15:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-05-09 16:00:00', '2025-05-13 17:00:00'),
(596, 15, 3, NULL, 'REF-2025-001595', '2025-03-13', '11:00:00', '12:00:00', '11:00:00', '12:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-03-01 16:00:00', '2025-03-12 17:00:00'),
(597, 66, 3, NULL, 'REF-2025-001596', '2025-08-10', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-07-30 16:00:00', '2025-08-09 17:00:00'),
(598, 79, 1, NULL, 'REF-2025-001597', '2025-12-12', '13:00:00', '14:00:00', '13:00:00', '14:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-29 16:00:00', '2025-12-11 17:00:00'),
(599, 4, 1, NULL, 'REF-2025-001598', '2025-12-14', '10:00:00', '11:00:00', '10:00:00', '11:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-03 16:00:00', '2025-12-13 17:00:00'),
(600, 11, 2, NULL, 'REF-2025-001599', '2025-06-08', '09:00:00', '10:00:00', '09:00:00', '10:00:00', 1, 'completed', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-05-30 16:00:00', '2025-06-07 17:00:00'),
(601, 73, 3, NULL, 'REF-2025-001600', '2025-11-09', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-11-06 16:00:00', 73, NULL, 'Reading', NULL, '2025-10-25 16:00:00', '2025-11-06 16:00:00'),
(602, 55, 3, NULL, 'REF-2025-001601', '2025-12-01', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-11-27 16:00:00', 55, NULL, 'Making Activity', NULL, '2025-11-27 16:00:00', '2025-11-27 16:00:00'),
(603, 59, 3, NULL, 'REF-2025-001602', '2025-07-04', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-07-01 16:00:00', 59, NULL, 'Surfing', NULL, '2025-06-19 16:00:00', '2025-07-01 16:00:00'),
(604, 88, 3, NULL, 'REF-2025-001603', '2025-06-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-05-30 16:00:00', 88, NULL, 'Surfing', NULL, '2025-05-31 16:00:00', '2025-05-30 16:00:00'),
(605, 56, 2, NULL, 'REF-2025-001604', '2025-01-11', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-01-06 16:00:00', 56, NULL, 'Surfing', NULL, '2025-01-02 16:00:00', '2025-01-06 16:00:00'),
(606, 75, 3, NULL, 'REF-2025-001605', '2025-11-09', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-11-04 16:00:00', 75, NULL, 'Others', 'Online meeting', '2025-10-28 16:00:00', '2025-11-04 16:00:00'),
(607, 8, 3, NULL, 'REF-2025-001606', '2025-08-07', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-08-05 16:00:00', 8, NULL, 'Reading', NULL, '2025-07-30 16:00:00', '2025-08-05 16:00:00'),
(608, 87, 1, NULL, 'REF-2025-001607', '2025-03-03', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-02-26 16:00:00', 87, NULL, 'Surfing', NULL, '2025-02-23 16:00:00', '2025-02-26 16:00:00'),
(609, 54, 3, NULL, 'REF-2025-001608', '2025-01-09', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-01-04 16:00:00', 54, NULL, 'Surfing', NULL, '2025-01-04 16:00:00', '2025-01-04 16:00:00'),
(610, 58, 3, NULL, 'REF-2025-001609', '2025-01-06', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2024-12-31 16:00:00', 58, NULL, 'Surfing', NULL, '2024-12-26 16:00:00', '2024-12-31 16:00:00'),
(611, 64, 2, NULL, 'REF-2025-001610', '2025-11-02', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-10-29 16:00:00', 64, NULL, 'Making Activity', NULL, '2025-10-31 16:00:00', '2025-10-29 16:00:00'),
(612, 9, 3, NULL, 'REF-2025-001611', '2025-07-13', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-07-08 16:00:00', 9, NULL, 'Others', 'Video editing', '2025-07-10 16:00:00', '2025-07-08 16:00:00'),
(613, 57, 1, NULL, 'REF-2025-001612', '2025-08-13', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-08-07 16:00:00', 57, NULL, 'Reading', NULL, '2025-07-31 16:00:00', '2025-08-07 16:00:00'),
(614, 89, 3, NULL, 'REF-2025-001613', '2025-10-03', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-09-30 16:00:00', 89, NULL, 'Reading', NULL, '2025-09-22 16:00:00', '2025-09-30 16:00:00'),
(615, 60, 1, NULL, 'REF-2025-001614', '2025-01-06', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-01-03 16:00:00', 60, NULL, 'Surfing', NULL, '2025-01-01 16:00:00', '2025-01-03 16:00:00'),
(616, 61, 2, NULL, 'REF-2025-001615', '2025-05-04', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-30 16:00:00', 61, NULL, 'Others', 'Online meeting', '2025-04-25 16:00:00', '2025-04-30 16:00:00'),
(617, 54, 3, NULL, 'REF-2025-001616', '2025-03-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-02-27 16:00:00', 54, NULL, 'Reading', NULL, '2025-02-17 16:00:00', '2025-02-27 16:00:00'),
(618, 72, 2, NULL, 'REF-2025-001617', '2025-03-14', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-03-12 16:00:00', 72, NULL, 'Others', 'Email checking', '2025-03-07 16:00:00', '2025-03-12 16:00:00'),
(619, 12, 3, NULL, 'REF-2025-001618', '2025-09-05', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-09-02 16:00:00', 12, NULL, 'Reading', NULL, '2025-08-26 16:00:00', '2025-09-02 16:00:00'),
(620, 66, 2, NULL, 'REF-2025-001619', '2025-04-01', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-03-29 16:00:00', 66, NULL, 'Making Activity', NULL, '2025-03-24 16:00:00', '2025-03-29 16:00:00'),
(621, 90, 3, NULL, 'REF-2025-001620', '2025-09-01', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-08-28 16:00:00', 90, NULL, 'Making Activity', NULL, '2025-08-27 16:00:00', '2025-08-28 16:00:00'),
(622, 63, 2, NULL, 'REF-2025-001621', '2025-11-04', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-11-02 16:00:00', 63, NULL, 'Reading', NULL, '2025-10-23 16:00:00', '2025-11-02 16:00:00'),
(623, 58, 2, NULL, 'REF-2025-001622', '2025-05-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-05-01 16:00:00', 58, NULL, 'Others', 'Project work', '2025-05-02 16:00:00', '2025-05-01 16:00:00'),
(624, 70, 1, NULL, 'REF-2025-001623', '2025-08-03', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-07-30 16:00:00', 70, NULL, 'Making Activity', NULL, '2025-07-26 16:00:00', '2025-07-30 16:00:00'),
(625, 54, 2, NULL, 'REF-2025-001624', '2025-04-04', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-03-30 16:00:00', 54, NULL, 'Reading', NULL, '2025-03-27 16:00:00', '2025-03-30 16:00:00'),
(626, 3, 2, NULL, 'REF-2025-001625', '2025-07-10', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 1, '2025-07-05 16:00:00', 3, NULL, 'Making Activity', NULL, '2025-07-01 16:00:00', '2025-07-05 16:00:00'),
(627, 58, 1, NULL, 'REF-2025-001626', '2025-02-11', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-02-05 16:00:00', 58, NULL, 'Surfing', NULL, '2025-01-28 16:00:00', '2025-02-05 16:00:00'),
(628, 87, 1, NULL, 'REF-2025-001627', '2025-05-14', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-05-10 16:00:00', 87, NULL, 'Others', 'Project work', '2025-05-05 16:00:00', '2025-05-10 16:00:00'),
(629, 4, 2, NULL, 'REF-2025-001628', '2025-05-13', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-05-10 16:00:00', 4, NULL, 'Surfing', NULL, '2025-05-04 16:00:00', '2025-05-10 16:00:00'),
(630, 54, 1, NULL, 'REF-2025-001629', '2025-05-02', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-04-29 16:00:00', 54, NULL, 'Making Activity', NULL, '2025-04-24 16:00:00', '2025-04-29 16:00:00'),
(631, 67, 2, NULL, 'REF-2025-001630', '2025-03-04', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-02-27 16:00:00', 67, NULL, 'Others', 'Thesis writing', '2025-02-24 16:00:00', '2025-02-27 16:00:00'),
(632, 19, 3, NULL, 'REF-2025-001631', '2025-03-14', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-10 16:00:00', 19, NULL, 'Others', 'Social media', '2025-03-10 16:00:00', '2025-03-10 16:00:00'),
(633, 58, 3, NULL, 'REF-2025-001632', '2025-07-14', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-07-11 16:00:00', 58, NULL, 'Others', 'Social media', '2025-07-02 16:00:00', '2025-07-11 16:00:00'),
(634, 10, 3, NULL, 'REF-2025-001633', '2025-02-13', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-02-09 16:00:00', 10, NULL, 'Surfing', NULL, '2025-02-08 16:00:00', '2025-02-09 16:00:00'),
(635, 5, 2, NULL, 'REF-2025-001634', '2025-04-13', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-04-10 16:00:00', 5, NULL, 'Reading', NULL, '2025-04-04 16:00:00', '2025-04-10 16:00:00'),
(636, 86, 3, NULL, 'REF-2025-001635', '2025-02-07', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-02-04 16:00:00', 86, NULL, 'Making Activity', NULL, '2025-01-27 16:00:00', '2025-02-04 16:00:00'),
(637, 61, 3, NULL, 'REF-2025-001636', '2025-02-02', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-01-27 16:00:00', 61, NULL, 'Others', 'Watching videos', '2025-01-24 16:00:00', '2025-01-27 16:00:00'),
(638, 72, 2, NULL, 'REF-2025-001637', '2025-05-14', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-08 16:00:00', 72, NULL, 'Making Activity', NULL, '2025-05-06 16:00:00', '2025-05-08 16:00:00'),
(639, 81, 2, NULL, 'REF-2025-001638', '2025-03-07', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 1, '2025-03-01 16:00:00', 81, NULL, 'Others', 'Video editing', '2025-02-26 16:00:00', '2025-03-01 16:00:00'),
(640, 73, 2, NULL, 'REF-2025-001639', '2025-02-04', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-01-31 16:00:00', 73, NULL, 'Reading', NULL, '2025-01-25 16:00:00', '2025-01-31 16:00:00'),
(641, 79, 1, NULL, 'REF-2025-001640', '2025-01-12', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 1, '2025-01-08 16:00:00', 79, NULL, 'Surfing', NULL, '2025-01-09 16:00:00', '2025-01-08 16:00:00'),
(642, 74, 1, NULL, 'REF-2025-001641', '2025-01-01', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2024-12-27 16:00:00', 74, NULL, 'Surfing', NULL, '2024-12-18 16:00:00', '2024-12-27 16:00:00'),
(643, 63, 2, NULL, 'REF-2025-001642', '2025-07-06', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-07-01 16:00:00', 63, NULL, 'Surfing', NULL, '2025-07-04 16:00:00', '2025-07-01 16:00:00'),
(644, 83, 1, NULL, 'REF-2025-001643', '2025-05-12', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-05-08 16:00:00', 83, NULL, 'Reading', NULL, '2025-05-09 16:00:00', '2025-05-08 16:00:00'),
(645, 91, 1, NULL, 'REF-2025-001644', '2025-05-12', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-05-09 16:00:00', 91, NULL, 'Making Activity', NULL, '2025-04-28 16:00:00', '2025-05-09 16:00:00'),
(646, 15, 1, NULL, 'REF-2025-001645', '2025-06-13', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-06-08 16:00:00', 15, NULL, 'Making Activity', NULL, '2025-05-29 16:00:00', '2025-06-08 16:00:00'),
(647, 54, 1, NULL, 'REF-2025-001646', '2025-06-08', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-06-02 16:00:00', 54, NULL, 'Others', 'Group study', '2025-06-03 16:00:00', '2025-06-02 16:00:00'),
(648, 85, 3, NULL, 'REF-2025-001647', '2025-03-12', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-08 16:00:00', 85, NULL, 'Others', 'Homework', '2025-03-03 16:00:00', '2025-03-08 16:00:00'),
(649, 84, 2, NULL, 'REF-2025-001648', '2025-03-08', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-03-03 16:00:00', 84, NULL, 'Reading', NULL, '2025-02-28 16:00:00', '2025-03-03 16:00:00'),
(650, 8, 3, NULL, 'REF-2025-001649', '2025-02-05', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-02-01 16:00:00', 8, NULL, 'Reading', NULL, '2025-01-23 16:00:00', '2025-02-01 16:00:00'),
(651, 20, 3, NULL, 'REF-2025-001650', '2025-10-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-10-08 16:00:00', 20, NULL, 'Making Activity', NULL, '2025-09-27 16:00:00', '2025-10-08 16:00:00'),
(652, 65, 2, NULL, 'REF-2025-001651', '2025-05-12', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-07 16:00:00', 65, NULL, 'Making Activity', NULL, '2025-05-01 16:00:00', '2025-05-07 16:00:00'),
(653, 73, 2, NULL, 'REF-2025-001652', '2025-12-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-11-29 16:00:00', 73, NULL, 'Making Activity', NULL, '2025-11-29 16:00:00', '2025-11-29 16:00:00'),
(654, 10, 2, NULL, 'REF-2025-001653', '2025-09-09', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-09-07 16:00:00', 10, NULL, 'Making Activity', NULL, '2025-08-31 16:00:00', '2025-09-07 16:00:00'),
(655, 7, 2, NULL, 'REF-2025-001654', '2025-10-10', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-10-06 16:00:00', 7, NULL, 'Others', 'Gaming', '2025-10-05 16:00:00', '2025-10-06 16:00:00'),
(656, 6, 3, NULL, 'REF-2025-001655', '2025-06-10', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-06-05 16:00:00', 6, NULL, 'Others', 'Email checking', '2025-06-06 16:00:00', '2025-06-05 16:00:00'),
(657, 53, 1, NULL, 'REF-2025-001656', '2025-08-01', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-07-27 16:00:00', 53, NULL, 'Others', 'Watching videos', '2025-07-18 16:00:00', '2025-07-27 16:00:00'),
(658, 69, 3, NULL, 'REF-2025-001657', '2025-03-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-02-27 16:00:00', 69, NULL, 'Others', 'Watching videos', '2025-02-17 16:00:00', '2025-02-27 16:00:00'),
(659, 90, 1, NULL, 'REF-2025-001658', '2025-03-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 1, '2025-02-25 16:00:00', 90, NULL, 'Others', 'Video editing', '2025-02-25 16:00:00', '2025-02-25 16:00:00'),
(660, 79, 3, NULL, 'REF-2025-001659', '2025-03-07', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-03-01 16:00:00', 79, NULL, 'Surfing', NULL, '2025-02-25 16:00:00', '2025-03-01 16:00:00'),
(661, 63, 2, NULL, 'REF-2025-001660', '2025-01-09', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-01-04 16:00:00', 63, NULL, 'Reading', NULL, '2025-01-01 16:00:00', '2025-01-04 16:00:00'),
(662, 82, 2, NULL, 'REF-2025-001661', '2025-04-12', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-10 16:00:00', 82, NULL, 'Surfing', NULL, '2025-04-09 16:00:00', '2025-04-10 16:00:00'),
(663, 84, 1, NULL, 'REF-2025-001662', '2025-07-10', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-07-04 16:00:00', 84, NULL, 'Reading', NULL, '2025-07-01 16:00:00', '2025-07-04 16:00:00'),
(664, 54, 1, NULL, 'REF-2025-001663', '2025-01-13', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-01-09 16:00:00', 54, NULL, 'Others', 'Homework', '2025-01-04 16:00:00', '2025-01-09 16:00:00'),
(665, 4, 3, NULL, 'REF-2025-001664', '2025-04-01', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-26 16:00:00', 4, NULL, 'Reading', NULL, '2025-03-23 16:00:00', '2025-03-26 16:00:00'),
(666, 91, 3, NULL, 'REF-2025-001665', '2025-09-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-08-29 16:00:00', 91, NULL, 'Making Activity', NULL, '2025-08-28 16:00:00', '2025-08-29 16:00:00'),
(667, 68, 3, NULL, 'REF-2025-001666', '2025-02-13', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-02-10 16:00:00', 68, NULL, 'Surfing', NULL, '2025-01-29 16:00:00', '2025-02-10 16:00:00'),
(668, 79, 3, NULL, 'REF-2025-001667', '2025-09-09', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-09-04 16:00:00', 79, NULL, 'Others', 'Project work', '2025-08-28 16:00:00', '2025-09-04 16:00:00'),
(669, 17, 2, NULL, 'REF-2025-001668', '2025-07-03', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-06-27 16:00:00', 17, NULL, 'Others', 'Thesis writing', '2025-06-24 16:00:00', '2025-06-27 16:00:00'),
(670, 19, 3, NULL, 'REF-2025-001669', '2025-08-14', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-08-08 16:00:00', 19, NULL, 'Others', 'Email checking', '2025-08-07 16:00:00', '2025-08-08 16:00:00'),
(671, 73, 3, NULL, 'REF-2025-001670', '2025-08-14', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-08-10 16:00:00', 73, NULL, 'Making Activity', NULL, '2025-07-31 16:00:00', '2025-08-10 16:00:00'),
(672, 79, 2, NULL, 'REF-2025-001671', '2025-02-06', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-02-03 16:00:00', 79, NULL, 'Reading', NULL, '2025-02-04 16:00:00', '2025-02-03 16:00:00'),
(673, 76, 1, NULL, 'REF-2025-001672', '2025-02-10', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-02-07 16:00:00', 76, NULL, 'Reading', NULL, '2025-02-04 16:00:00', '2025-02-07 16:00:00'),
(674, 64, 1, NULL, 'REF-2025-001673', '2025-03-04', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-02-27 16:00:00', 64, NULL, 'Others', 'Project work', '2025-02-21 16:00:00', '2025-02-27 16:00:00'),
(675, 89, 1, NULL, 'REF-2025-001674', '2025-07-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-06-27 16:00:00', 89, NULL, 'Others', 'Video editing', '2025-06-30 16:00:00', '2025-06-27 16:00:00'),
(676, 70, 1, NULL, 'REF-2025-001675', '2025-01-01', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2024-12-29 16:00:00', 70, NULL, 'Surfing', NULL, '2024-12-23 16:00:00', '2024-12-29 16:00:00'),
(677, 54, 3, NULL, 'REF-2025-001676', '2025-11-05', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-11-01 16:00:00', 54, NULL, 'Surfing', NULL, '2025-10-22 16:00:00', '2025-11-01 16:00:00'),
(678, 60, 3, NULL, 'REF-2025-001677', '2025-09-13', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 1, '2025-09-08 16:00:00', 60, NULL, 'Others', 'Homework', '2025-08-29 16:00:00', '2025-09-08 16:00:00'),
(679, 56, 3, NULL, 'REF-2025-001678', '2025-10-12', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-10-09 16:00:00', 56, NULL, 'Making Activity', NULL, '2025-09-28 16:00:00', '2025-10-09 16:00:00'),
(680, 66, 2, NULL, 'REF-2025-001679', '2025-01-07', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-01-02 16:00:00', 66, NULL, 'Reading', NULL, '2025-01-01 16:00:00', '2025-01-02 16:00:00'),
(681, 3, 1, NULL, 'REF-2025-001680', '2025-09-14', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-09-09 16:00:00', 3, NULL, 'Making Activity', NULL, '2025-09-04 16:00:00', '2025-09-09 16:00:00'),
(682, 8, 3, NULL, 'REF-2025-001681', '2025-03-02', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-02-26 16:00:00', 8, NULL, 'Surfing', NULL, '2025-02-24 16:00:00', '2025-02-26 16:00:00'),
(683, 67, 2, NULL, 'REF-2025-001682', '2025-03-03', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-01 16:00:00', 67, NULL, 'Reading', NULL, '2025-02-25 16:00:00', '2025-03-01 16:00:00'),
(684, 54, 3, NULL, 'REF-2025-001683', '2025-10-07', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-10-01 16:00:00', 54, NULL, 'Making Activity', NULL, '2025-09-28 16:00:00', '2025-10-01 16:00:00'),
(685, 74, 2, NULL, 'REF-2025-001684', '2025-11-09', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-11-06 16:00:00', 74, NULL, 'Making Activity', NULL, '2025-11-06 16:00:00', '2025-11-06 16:00:00'),
(686, 19, 2, NULL, 'REF-2025-001685', '2025-08-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-08-09 16:00:00', 19, NULL, 'Making Activity', NULL, '2025-07-30 16:00:00', '2025-08-09 16:00:00'),
(687, 4, 1, NULL, 'REF-2025-001686', '2025-06-06', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-31 16:00:00', 4, NULL, 'Others', 'Social media', '2025-05-27 16:00:00', '2025-05-31 16:00:00'),
(688, 57, 2, NULL, 'REF-2025-001687', '2025-12-11', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-12-07 16:00:00', 57, NULL, 'Reading', NULL, '2025-11-28 16:00:00', '2025-12-07 16:00:00'),
(689, 87, 1, NULL, 'REF-2025-001688', '2025-08-08', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-08-06 16:00:00', 87, NULL, 'Making Activity', NULL, '2025-07-24 16:00:00', '2025-08-06 16:00:00'),
(690, 87, 2, NULL, 'REF-2025-001689', '2025-08-03', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 1, '2025-07-28 16:00:00', 87, NULL, 'Reading', NULL, '2025-07-31 16:00:00', '2025-07-28 16:00:00'),
(691, 17, 1, NULL, 'REF-2025-001690', '2025-11-12', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-11-09 16:00:00', 17, NULL, 'Others', 'Group study', '2025-11-02 16:00:00', '2025-11-09 16:00:00'),
(692, 72, 2, NULL, 'REF-2025-001691', '2025-07-12', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-07-08 16:00:00', 72, NULL, 'Surfing', NULL, '2025-07-02 16:00:00', '2025-07-08 16:00:00'),
(693, 58, 2, NULL, 'REF-2025-001692', '2025-02-09', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-02-05 16:00:00', 58, NULL, 'Making Activity', NULL, '2025-02-03 16:00:00', '2025-02-05 16:00:00'),
(694, 65, 3, NULL, 'REF-2025-001693', '2025-05-02', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-04-28 16:00:00', 65, NULL, 'Making Activity', NULL, '2025-04-25 16:00:00', '2025-04-28 16:00:00'),
(695, 11, 3, NULL, 'REF-2025-001694', '2025-03-07', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-03-01 16:00:00', 11, NULL, 'Reading', NULL, '2025-03-01 16:00:00', '2025-03-01 16:00:00'),
(696, 7, 3, NULL, 'REF-2025-001695', '2025-02-07', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-02-03 16:00:00', 7, NULL, 'Making Activity', NULL, '2025-01-27 16:00:00', '2025-02-03 16:00:00'),
(697, 58, 1, NULL, 'REF-2025-001696', '2025-04-08', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-03 16:00:00', 58, NULL, 'Making Activity', NULL, '2025-03-28 16:00:00', '2025-04-03 16:00:00'),
(698, 52, 3, NULL, 'REF-2025-001697', '2025-04-07', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-04-03 16:00:00', 52, NULL, 'Reading', NULL, '2025-03-31 16:00:00', '2025-04-03 16:00:00'),
(699, 89, 3, NULL, 'REF-2025-001698', '2025-06-09', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-06-04 16:00:00', 89, NULL, 'Making Activity', NULL, '2025-05-25 16:00:00', '2025-06-04 16:00:00'),
(700, 62, 1, NULL, 'REF-2025-001699', '2025-07-13', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-07-11 16:00:00', 62, NULL, 'Reading', NULL, '2025-07-05 16:00:00', '2025-07-11 16:00:00'),
(701, 2, 2, NULL, 'REF-2025-001700', '2025-08-11', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-08-06 16:00:00', 2, NULL, 'Reading', NULL, '2025-08-07 16:00:00', '2025-08-06 16:00:00'),
(702, 85, 1, NULL, 'REF-2025-001701', '2025-05-13', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-05-11 16:00:00', 85, NULL, 'Others', 'Video editing', '2025-05-09 16:00:00', '2025-05-11 16:00:00');
INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(703, 3, 3, NULL, 'REF-2025-001702', '2025-11-06', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 1, '2025-11-03 16:00:00', 3, NULL, 'Surfing', NULL, '2025-11-01 16:00:00', '2025-11-03 16:00:00'),
(704, 15, 2, NULL, 'REF-2025-001703', '2025-12-06', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-12-01 16:00:00', 15, NULL, 'Making Activity', NULL, '2025-11-26 16:00:00', '2025-12-01 16:00:00'),
(705, 84, 3, NULL, 'REF-2025-001704', '2025-09-14', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-09-09 16:00:00', 84, NULL, 'Reading', NULL, '2025-09-03 16:00:00', '2025-09-09 16:00:00'),
(706, 56, 2, NULL, 'REF-2025-001705', '2025-01-03', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-01-01 16:00:00', 56, NULL, 'Reading', NULL, '2024-12-23 16:00:00', '2025-01-01 16:00:00'),
(707, 66, 3, NULL, 'REF-2025-001706', '2025-09-13', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-09-08 16:00:00', 66, NULL, 'Reading', NULL, '2025-09-07 16:00:00', '2025-09-08 16:00:00'),
(708, 89, 1, NULL, 'REF-2025-001707', '2025-09-02', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-08-28 16:00:00', 89, NULL, 'Surfing', NULL, '2025-08-24 16:00:00', '2025-08-28 16:00:00'),
(709, 11, 2, NULL, 'REF-2025-001708', '2025-05-14', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-05-10 16:00:00', 11, NULL, 'Reading', NULL, '2025-04-30 16:00:00', '2025-05-10 16:00:00'),
(710, 87, 3, NULL, 'REF-2025-001709', '2025-07-08', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-07-04 16:00:00', 87, NULL, 'Others', 'Online meeting', '2025-07-06 16:00:00', '2025-07-04 16:00:00'),
(711, 52, 3, NULL, 'REF-2025-001710', '2025-03-13', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-03-09 16:00:00', 52, NULL, 'Surfing', NULL, '2025-03-02 16:00:00', '2025-03-09 16:00:00'),
(712, 13, 2, NULL, 'REF-2025-001711', '2025-10-04', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-09-29 16:00:00', 13, NULL, 'Reading', NULL, '2025-10-01 16:00:00', '2025-09-29 16:00:00'),
(713, 58, 2, NULL, 'REF-2025-001712', '2025-03-09', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-03-04 16:00:00', 58, NULL, 'Surfing', NULL, '2025-02-26 16:00:00', '2025-03-04 16:00:00'),
(714, 5, 3, NULL, 'REF-2025-001713', '2025-04-02', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 1, '2025-03-29 16:00:00', 5, NULL, 'Others', 'Group study', '2025-03-18 16:00:00', '2025-03-29 16:00:00'),
(715, 16, 1, NULL, 'REF-2025-001714', '2025-06-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-27 16:00:00', 16, NULL, 'Making Activity', NULL, '2025-05-24 16:00:00', '2025-05-27 16:00:00'),
(716, 65, 3, NULL, 'REF-2025-001715', '2025-03-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-03-08 16:00:00', 65, NULL, 'Others', 'Video editing', '2025-03-01 16:00:00', '2025-03-08 16:00:00'),
(717, 71, 2, NULL, 'REF-2025-001716', '2025-07-01', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 1, '2025-06-26 16:00:00', 71, NULL, 'Making Activity', NULL, '2025-06-20 16:00:00', '2025-06-26 16:00:00'),
(718, 71, 1, NULL, 'REF-2025-001717', '2025-11-07', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-11-03 16:00:00', 71, NULL, 'Reading', NULL, '2025-10-30 16:00:00', '2025-11-03 16:00:00'),
(719, 10, 3, NULL, 'REF-2025-001718', '2025-04-12', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-04-06 16:00:00', 10, NULL, 'Reading', NULL, '2025-04-01 16:00:00', '2025-04-06 16:00:00'),
(720, 64, 3, NULL, 'REF-2025-001719', '2025-03-07', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-03-03 16:00:00', 64, NULL, 'Making Activity', NULL, '2025-03-02 16:00:00', '2025-03-03 16:00:00'),
(721, 73, 3, NULL, 'REF-2025-001720', '2025-02-11', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-02-05 16:00:00', 73, NULL, 'Others', 'Social media', '2025-02-04 16:00:00', '2025-02-05 16:00:00'),
(722, 3, 2, NULL, 'REF-2025-001721', '2025-04-10', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-04-07 16:00:00', 3, NULL, 'Surfing', NULL, '2025-03-30 16:00:00', '2025-04-07 16:00:00'),
(723, 17, 3, NULL, 'REF-2025-001722', '2025-01-14', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-01-11 16:00:00', 17, NULL, 'Others', 'Group study', '2025-01-06 16:00:00', '2025-01-11 16:00:00'),
(724, 3, 2, NULL, 'REF-2025-001723', '2025-05-01', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-04-28 16:00:00', 3, NULL, 'Making Activity', NULL, '2025-04-21 16:00:00', '2025-04-28 16:00:00'),
(725, 19, 3, NULL, 'REF-2025-001724', '2025-07-12', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-07-09 16:00:00', 19, NULL, 'Making Activity', NULL, '2025-06-29 16:00:00', '2025-07-09 16:00:00'),
(726, 63, 1, NULL, 'REF-2025-001725', '2025-06-07', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-06-03 16:00:00', 63, NULL, 'Surfing', NULL, '2025-06-01 16:00:00', '2025-06-03 16:00:00'),
(727, 90, 2, NULL, 'REF-2025-001726', '2025-07-10', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-07-08 16:00:00', 90, NULL, 'Others', 'Homework', '2025-06-30 16:00:00', '2025-07-08 16:00:00'),
(728, 6, 2, NULL, 'REF-2025-001727', '2025-05-12', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-05-06 16:00:00', 6, NULL, 'Making Activity', NULL, '2025-05-03 16:00:00', '2025-05-06 16:00:00'),
(729, 60, 2, NULL, 'REF-2025-001728', '2025-09-06', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-08-31 16:00:00', 60, NULL, 'Surfing', NULL, '2025-09-04 16:00:00', '2025-08-31 16:00:00'),
(730, 58, 1, NULL, 'REF-2025-001729', '2025-03-03', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-02-27 16:00:00', 58, NULL, 'Others', 'Watching videos', '2025-03-01 16:00:00', '2025-02-27 16:00:00'),
(731, 79, 1, NULL, 'REF-2025-001730', '2025-11-09', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-11-06 16:00:00', 79, NULL, 'Others', 'Project work', '2025-10-25 16:00:00', '2025-11-06 16:00:00'),
(732, 8, 2, NULL, 'REF-2025-001731', '2025-01-06', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-01-01 16:00:00', 8, NULL, 'Making Activity', NULL, '2024-12-26 16:00:00', '2025-01-01 16:00:00'),
(733, 21, 1, NULL, 'REF-2025-001732', '2025-08-13', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-08-10 16:00:00', 21, NULL, 'Surfing', NULL, '2025-08-02 16:00:00', '2025-08-10 16:00:00'),
(734, 53, 3, NULL, 'REF-2025-001733', '2025-07-03', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-07-01 16:00:00', 53, NULL, 'Surfing', NULL, '2025-06-28 16:00:00', '2025-07-01 16:00:00'),
(735, 84, 2, NULL, 'REF-2025-001734', '2025-08-06', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-08-04 16:00:00', 84, NULL, 'Others', 'Watching videos', '2025-08-03 16:00:00', '2025-08-04 16:00:00'),
(736, 70, 2, NULL, 'REF-2025-001735', '2025-02-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-02-09 16:00:00', 70, NULL, 'Surfing', NULL, '2025-02-09 16:00:00', '2025-02-09 16:00:00'),
(737, 54, 3, NULL, 'REF-2025-001736', '2025-05-07', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-05-05 16:00:00', 54, NULL, 'Others', 'Thesis writing', '2025-05-05 16:00:00', '2025-05-05 16:00:00'),
(738, 54, 2, NULL, 'REF-2025-001737', '2025-04-07', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-04-01 16:00:00', 54, NULL, 'Reading', NULL, '2025-03-26 16:00:00', '2025-04-01 16:00:00'),
(739, 80, 2, NULL, 'REF-2025-001738', '2025-01-02', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2024-12-30 16:00:00', 80, NULL, 'Reading', NULL, '2024-12-27 16:00:00', '2024-12-30 16:00:00'),
(740, 74, 3, NULL, 'REF-2025-001739', '2025-08-01', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-07-27 16:00:00', 74, NULL, 'Others', 'Watching videos', '2025-07-22 16:00:00', '2025-07-27 16:00:00'),
(741, 82, 2, NULL, 'REF-2025-001740', '2025-12-01', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-11-25 16:00:00', 82, NULL, 'Surfing', NULL, '2025-11-26 16:00:00', '2025-11-25 16:00:00'),
(742, 67, 3, NULL, 'REF-2025-001741', '2025-01-03', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2024-12-31 16:00:00', 67, NULL, 'Reading', NULL, '2024-12-28 16:00:00', '2024-12-31 16:00:00'),
(743, 14, 1, NULL, 'REF-2025-001742', '2025-02-13', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 1, '2025-02-10 16:00:00', 14, NULL, 'Surfing', NULL, '2025-02-04 16:00:00', '2025-02-10 16:00:00'),
(744, 60, 3, NULL, 'REF-2025-001743', '2025-07-05', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-07-03 16:00:00', 60, NULL, 'Others', 'Social media', '2025-07-01 16:00:00', '2025-07-03 16:00:00'),
(745, 12, 3, NULL, 'REF-2025-001744', '2025-08-08', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-08-03 16:00:00', 12, NULL, 'Making Activity', NULL, '2025-07-28 16:00:00', '2025-08-03 16:00:00'),
(746, 57, 2, NULL, 'REF-2025-001745', '2025-06-07', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-06-01 16:00:00', 57, NULL, 'Making Activity', NULL, '2025-06-02 16:00:00', '2025-06-01 16:00:00'),
(747, 5, 1, NULL, 'REF-2025-001746', '2025-07-01', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-06-28 16:00:00', 5, NULL, 'Surfing', NULL, '2025-06-21 16:00:00', '2025-06-28 16:00:00'),
(748, 17, 1, NULL, 'REF-2025-001747', '2025-12-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-12-07 16:00:00', 17, NULL, 'Making Activity', NULL, '2025-12-07 16:00:00', '2025-12-07 16:00:00'),
(749, 57, 1, NULL, 'REF-2025-001748', '2025-09-08', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 1, '2025-09-06 16:00:00', 57, NULL, 'Others', 'Group study', '2025-08-29 16:00:00', '2025-09-06 16:00:00'),
(750, 69, 3, NULL, 'REF-2025-001749', '2025-05-09', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-05-06 16:00:00', 69, NULL, 'Others', 'Homework', '2025-05-03 16:00:00', '2025-05-06 16:00:00'),
(751, 54, 3, NULL, 'REF-2025-001750', '2025-01-07', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-01-01 16:00:00', 54, NULL, 'Reading', NULL, '2024-12-30 16:00:00', '2025-01-01 16:00:00'),
(752, 81, 1, NULL, 'REF-2025-001751', '2025-04-12', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-10 16:00:00', 81, NULL, 'Surfing', NULL, '2025-04-07 16:00:00', '2025-04-10 16:00:00'),
(753, 74, 3, NULL, 'REF-2025-001752', '2025-12-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-11-29 16:00:00', 74, NULL, 'Reading', NULL, '2025-11-28 16:00:00', '2025-11-29 16:00:00'),
(754, 88, 2, NULL, 'REF-2025-001753', '2025-01-08', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-01-05 16:00:00', 88, NULL, 'Reading', NULL, '2024-12-26 16:00:00', '2025-01-05 16:00:00'),
(755, 86, 3, NULL, 'REF-2025-001754', '2025-08-06', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-08-02 16:00:00', 86, NULL, 'Making Activity', NULL, '2025-07-27 16:00:00', '2025-08-02 16:00:00'),
(756, 61, 2, NULL, 'REF-2025-001755', '2025-12-05', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-12-01 16:00:00', 61, NULL, 'Others', 'Email checking', '2025-11-21 16:00:00', '2025-12-01 16:00:00'),
(757, 52, 2, NULL, 'REF-2025-001756', '2025-03-10', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-07 16:00:00', 52, NULL, 'Making Activity', NULL, '2025-03-03 16:00:00', '2025-03-07 16:00:00'),
(758, 80, 2, NULL, 'REF-2025-001757', '2025-11-07', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-11-03 16:00:00', 80, NULL, 'Others', 'Email checking', '2025-10-23 16:00:00', '2025-11-03 16:00:00'),
(759, 16, 3, NULL, 'REF-2025-001758', '2025-11-04', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-10-29 16:00:00', 16, NULL, 'Making Activity', NULL, '2025-10-30 16:00:00', '2025-10-29 16:00:00'),
(760, 17, 3, NULL, 'REF-2025-001759', '2025-07-14', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-07-09 16:00:00', 17, NULL, 'Reading', NULL, '2025-07-03 16:00:00', '2025-07-09 16:00:00'),
(761, 4, 2, NULL, 'REF-2025-001760', '2025-02-03', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-01-29 16:00:00', 4, NULL, 'Reading', NULL, '2025-01-24 16:00:00', '2025-01-29 16:00:00'),
(762, 17, 3, NULL, 'REF-2025-001761', '2025-11-14', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-11-10 16:00:00', 17, NULL, 'Making Activity', NULL, '2025-11-06 16:00:00', '2025-11-10 16:00:00'),
(763, 83, 2, NULL, 'REF-2025-001762', '2025-06-05', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-30 16:00:00', 83, NULL, 'Others', 'Social media', '2025-05-24 16:00:00', '2025-05-30 16:00:00'),
(764, 17, 1, NULL, 'REF-2025-001763', '2025-08-09', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-08-03 16:00:00', 17, NULL, 'Making Activity', NULL, '2025-08-02 16:00:00', '2025-08-03 16:00:00'),
(765, 78, 3, NULL, 'REF-2025-001764', '2025-09-04', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-08-29 16:00:00', 78, NULL, 'Reading', NULL, '2025-08-24 16:00:00', '2025-08-29 16:00:00'),
(766, 8, 1, NULL, 'REF-2025-001765', '2025-12-01', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-11-29 16:00:00', 8, NULL, 'Making Activity', NULL, '2025-11-25 16:00:00', '2025-11-29 16:00:00'),
(767, 20, 1, NULL, 'REF-2025-001766', '2025-04-14', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-04-10 16:00:00', 20, NULL, 'Making Activity', NULL, '2025-04-01 16:00:00', '2025-04-10 16:00:00'),
(768, 89, 1, NULL, 'REF-2025-001767', '2025-03-14', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-03-12 16:00:00', 89, NULL, 'Reading', NULL, '2025-03-07 16:00:00', '2025-03-12 16:00:00'),
(769, 87, 2, NULL, 'REF-2025-001768', '2025-06-01', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-05-27 16:00:00', 87, NULL, 'Surfing', NULL, '2025-05-26 16:00:00', '2025-05-27 16:00:00'),
(770, 82, 2, NULL, 'REF-2025-001769', '2025-09-10', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-09-07 16:00:00', 82, NULL, 'Others', 'Video editing', '2025-08-30 16:00:00', '2025-09-07 16:00:00'),
(771, 72, 3, NULL, 'REF-2025-001770', '2025-01-03', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2024-12-28 16:00:00', 72, NULL, 'Making Activity', NULL, '2024-12-21 16:00:00', '2024-12-28 16:00:00'),
(772, 90, 1, NULL, 'REF-2025-001771', '2025-01-11', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-01-08 16:00:00', 90, NULL, 'Surfing', NULL, '2024-12-31 16:00:00', '2025-01-08 16:00:00'),
(773, 72, 2, NULL, 'REF-2025-001772', '2025-11-08', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-11-05 16:00:00', 72, NULL, 'Making Activity', NULL, '2025-10-28 16:00:00', '2025-11-05 16:00:00'),
(774, 12, 1, NULL, 'REF-2025-001773', '2025-09-08', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-09-02 16:00:00', 12, NULL, 'Surfing', NULL, '2025-08-24 16:00:00', '2025-09-02 16:00:00'),
(775, 66, 2, NULL, 'REF-2025-001774', '2025-04-04', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-29 16:00:00', 66, NULL, 'Making Activity', NULL, '2025-03-25 16:00:00', '2025-03-29 16:00:00'),
(776, 18, 1, NULL, 'REF-2025-001775', '2025-03-05', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-03-02 16:00:00', 18, NULL, 'Making Activity', NULL, '2025-02-26 16:00:00', '2025-03-02 16:00:00'),
(777, 59, 3, NULL, 'REF-2025-001776', '2025-05-05', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-04-30 16:00:00', 59, NULL, 'Others', 'Video editing', '2025-05-03 16:00:00', '2025-04-30 16:00:00'),
(778, 20, 2, NULL, 'REF-2025-001777', '2025-07-11', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-07-06 16:00:00', 20, NULL, 'Making Activity', NULL, '2025-07-04 16:00:00', '2025-07-06 16:00:00'),
(779, 70, 1, NULL, 'REF-2025-001778', '2025-04-13', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-08 16:00:00', 70, NULL, 'Others', 'Gaming', '2025-04-01 16:00:00', '2025-04-08 16:00:00'),
(780, 73, 1, NULL, 'REF-2025-001779', '2025-09-01', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-08-28 16:00:00', 73, NULL, 'Reading', NULL, '2025-08-26 16:00:00', '2025-08-28 16:00:00'),
(781, 64, 2, NULL, 'REF-2025-001780', '2025-12-09', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-12-06 16:00:00', 64, NULL, 'Others', 'Gaming', '2025-12-07 16:00:00', '2025-12-06 16:00:00'),
(782, 81, 2, NULL, 'REF-2025-001781', '2025-12-08', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-12-06 16:00:00', 81, NULL, 'Surfing', NULL, '2025-12-05 16:00:00', '2025-12-06 16:00:00'),
(783, 6, 2, NULL, 'REF-2025-001782', '2025-04-02', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-03-30 16:00:00', 6, NULL, 'Others', 'Email checking', '2025-03-29 16:00:00', '2025-03-30 16:00:00'),
(784, 69, 1, NULL, 'REF-2025-001783', '2025-09-12', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-09-10 16:00:00', 69, NULL, 'Making Activity', NULL, '2025-09-09 16:00:00', '2025-09-10 16:00:00'),
(785, 78, 3, NULL, 'REF-2025-001784', '2025-11-13', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-11-08 16:00:00', 78, NULL, 'Surfing', NULL, '2025-11-11 16:00:00', '2025-11-08 16:00:00'),
(786, 69, 2, NULL, 'REF-2025-001785', '2025-01-11', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-01-05 16:00:00', 69, NULL, 'Surfing', NULL, '2024-12-27 16:00:00', '2025-01-05 16:00:00'),
(787, 79, 2, NULL, 'REF-2025-001786', '2025-01-06', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2024-12-31 16:00:00', 79, NULL, 'Reading', NULL, '2025-01-01 16:00:00', '2024-12-31 16:00:00'),
(788, 88, 3, NULL, 'REF-2025-001787', '2025-12-13', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-12-07 16:00:00', 88, NULL, 'Others', 'Online meeting', '2025-12-10 16:00:00', '2025-12-07 16:00:00'),
(789, 58, 2, NULL, 'REF-2025-001788', '2025-05-03', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-05-01 16:00:00', 58, NULL, 'Surfing', NULL, '2025-04-19 16:00:00', '2025-05-01 16:00:00'),
(790, 61, 1, NULL, 'REF-2025-001789', '2025-05-04', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-04-28 16:00:00', 61, NULL, 'Others', 'Project work', '2025-04-23 16:00:00', '2025-04-28 16:00:00'),
(791, 83, 3, NULL, 'REF-2025-001790', '2025-01-06', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-01-04 16:00:00', 83, NULL, 'Others', 'Group study', '2025-01-03 16:00:00', '2025-01-04 16:00:00'),
(792, 19, 2, NULL, 'REF-2025-001791', '2025-06-14', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-06-12 16:00:00', 19, NULL, 'Others', 'Project work', '2025-06-11 16:00:00', '2025-06-12 16:00:00'),
(793, 74, 2, NULL, 'REF-2025-001792', '2025-01-02', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2024-12-28 16:00:00', 74, NULL, 'Reading', NULL, '2024-12-20 16:00:00', '2024-12-28 16:00:00'),
(794, 13, 2, NULL, 'REF-2025-001793', '2025-09-10', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-09-04 16:00:00', 13, NULL, 'Others', 'Thesis writing', '2025-09-02 16:00:00', '2025-09-04 16:00:00'),
(795, 73, 1, NULL, 'REF-2025-001794', '2025-09-11', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-09-08 16:00:00', 73, NULL, 'Making Activity', NULL, '2025-09-09 16:00:00', '2025-09-08 16:00:00'),
(796, 56, 3, NULL, 'REF-2025-001795', '2025-01-02', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2024-12-30 16:00:00', 56, NULL, 'Making Activity', NULL, '2024-12-25 16:00:00', '2024-12-30 16:00:00'),
(797, 56, 1, NULL, 'REF-2025-001796', '2025-10-11', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-10-05 16:00:00', 56, NULL, 'Reading', NULL, '2025-10-02 16:00:00', '2025-10-05 16:00:00'),
(798, 81, 2, NULL, 'REF-2025-001797', '2025-10-01', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-09-26 16:00:00', 81, NULL, 'Reading', NULL, '2025-09-16 16:00:00', '2025-09-26 16:00:00'),
(799, 19, 3, NULL, 'REF-2025-001798', '2025-12-14', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-12-12 16:00:00', 19, NULL, 'Others', 'Project work', '2025-12-08 16:00:00', '2025-12-12 16:00:00'),
(800, 20, 3, NULL, 'REF-2025-001799', '2025-09-10', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-09-06 16:00:00', 20, NULL, 'Making Activity', NULL, '2025-08-31 16:00:00', '2025-09-06 16:00:00'),
(801, 82, 2, NULL, 'REF-2025-001800', '2025-09-11', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-09-07 16:00:00', 82, NULL, 'Reading', NULL, '2025-09-08 16:00:00', '2025-09-07 16:00:00'),
(802, 90, 2, NULL, 'REF-2025-001801', '2025-10-08', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-10-06 16:00:00', 90, NULL, 'Surfing', NULL, '2025-09-29 16:00:00', '2025-10-06 16:00:00'),
(803, 66, 2, NULL, 'REF-2025-001802', '2025-06-05', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-06-01 16:00:00', 66, NULL, 'Reading', NULL, '2025-05-21 16:00:00', '2025-06-01 16:00:00'),
(804, 8, 1, NULL, 'REF-2025-001803', '2025-04-01', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-03-26 16:00:00', 8, NULL, 'Surfing', NULL, '2025-03-20 16:00:00', '2025-03-26 16:00:00'),
(805, 73, 2, NULL, 'REF-2025-001804', '2025-09-02', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-08-31 16:00:00', 73, NULL, 'Surfing', NULL, '2025-08-28 16:00:00', '2025-08-31 16:00:00'),
(806, 1, 1, NULL, 'REF-2025-001805', '2025-09-10', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-09-08 16:00:00', 1, NULL, 'Surfing', NULL, '2025-08-26 16:00:00', '2025-09-08 16:00:00'),
(807, 71, 2, NULL, 'REF-2025-001806', '2025-11-08', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-11-02 16:00:00', 71, NULL, 'Others', 'Gaming', '2025-11-03 16:00:00', '2025-11-02 16:00:00'),
(808, 52, 3, NULL, 'REF-2025-001807', '2025-09-14', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-09-12 16:00:00', 52, NULL, 'Reading', NULL, '2025-08-31 16:00:00', '2025-09-12 16:00:00'),
(809, 58, 3, NULL, 'REF-2025-001808', '2025-02-06', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-02-03 16:00:00', 58, NULL, 'Others', 'Group study', '2025-02-01 16:00:00', '2025-02-03 16:00:00'),
(810, 70, 1, NULL, 'REF-2025-001809', '2025-04-06', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 1, '2025-03-31 16:00:00', 70, NULL, 'Others', 'Group study', '2025-03-23 16:00:00', '2025-03-31 16:00:00'),
(811, 72, 3, NULL, 'REF-2025-001810', '2025-07-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 1, '2025-07-08 16:00:00', 72, NULL, 'Reading', NULL, '2025-07-07 16:00:00', '2025-07-08 16:00:00'),
(812, 16, 3, NULL, 'REF-2025-001811', '2025-08-10', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-08-07 16:00:00', 16, NULL, 'Surfing', NULL, '2025-08-07 16:00:00', '2025-08-07 16:00:00'),
(813, 14, 1, NULL, 'REF-2025-001812', '2025-04-03', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 1, '2025-04-01 16:00:00', 14, NULL, 'Making Activity', NULL, '2025-03-26 16:00:00', '2025-04-01 16:00:00'),
(814, 63, 2, NULL, 'REF-2025-001813', '2025-08-07', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-08-02 16:00:00', 63, NULL, 'Making Activity', NULL, '2025-07-28 16:00:00', '2025-08-02 16:00:00'),
(815, 88, 1, NULL, 'REF-2025-001814', '2025-06-02', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-05-28 16:00:00', 88, NULL, 'Reading', NULL, '2025-05-19 16:00:00', '2025-05-28 16:00:00'),
(816, 61, 3, NULL, 'REF-2025-001815', '2025-11-02', '10:00:00', '11:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-10-31 16:00:00', 61, NULL, 'Making Activity', NULL, '2025-10-18 16:00:00', '2025-10-31 16:00:00'),
(817, 91, 1, NULL, 'REF-2025-001816', '2025-02-13', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-02-11 16:00:00', 91, NULL, 'Surfing', NULL, '2025-02-05 16:00:00', '2025-02-11 16:00:00'),
(818, 9, 3, NULL, 'REF-2025-001817', '2025-12-09', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-12-04 16:00:00', 9, NULL, 'Surfing', NULL, '2025-12-07 16:00:00', '2025-12-04 16:00:00'),
(819, 73, 3, NULL, 'REF-2025-001818', '2025-04-10', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 1, '2025-04-08 16:00:00', 73, NULL, 'Reading', NULL, '2025-04-04 16:00:00', '2025-04-08 16:00:00'),
(820, 61, 3, NULL, 'REF-2025-001819', '2025-03-13', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 1, '2025-03-11 16:00:00', 61, NULL, 'Reading', NULL, '2025-03-09 16:00:00', '2025-03-11 16:00:00'),
(821, 79, 3, NULL, 'REF-2025-001820', '2025-11-06', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-10-31 16:00:00', 79, NULL, 'Reading', NULL, '2025-10-31 16:00:00', '2025-10-31 16:00:00'),
(822, 56, 2, NULL, 'REF-2025-001821', '2025-07-05', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 1, '2025-07-02 16:00:00', 56, NULL, 'Reading', NULL, '2025-06-26 16:00:00', '2025-07-02 16:00:00'),
(823, 71, 2, NULL, 'REF-2025-001822', '2025-02-08', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-02-02 16:00:00', 71, NULL, 'Reading', NULL, '2025-01-30 16:00:00', '2025-02-02 16:00:00'),
(824, 21, 3, NULL, 'REF-2025-001823', '2025-07-01', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-06-29 16:00:00', 21, NULL, 'Reading', NULL, '2025-06-28 16:00:00', '2025-06-29 16:00:00'),
(825, 11, 2, NULL, 'REF-2025-001824', '2025-04-01', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-27 16:00:00', 11, NULL, 'Others', 'Group study', '2025-03-20 16:00:00', '2025-03-27 16:00:00'),
(826, 69, 2, NULL, 'REF-2025-001825', '2025-03-02', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Transportation issues', 0, '2025-02-25 16:00:00', 69, NULL, 'Reading', NULL, '2025-02-24 16:00:00', '2025-02-25 16:00:00'),
(827, 86, 3, NULL, 'REF-2025-001826', '2025-06-04', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-06-01 16:00:00', 86, NULL, 'Making Activity', NULL, '2025-06-02 16:00:00', '2025-06-01 16:00:00'),
(828, 78, 3, NULL, 'REF-2025-001827', '2025-04-04', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-03-29 16:00:00', 78, NULL, 'Making Activity', NULL, '2025-03-23 16:00:00', '2025-03-29 16:00:00'),
(829, 63, 2, NULL, 'REF-2025-001828', '2025-02-13', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-02-07 16:00:00', 63, NULL, 'Making Activity', NULL, '2025-02-04 16:00:00', '2025-02-07 16:00:00'),
(830, 90, 3, NULL, 'REF-2025-001829', '2025-11-07', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-11-02 16:00:00', 90, NULL, 'Making Activity', NULL, '2025-11-02 16:00:00', '2025-11-02 16:00:00'),
(831, 8, 2, NULL, 'REF-2025-001830', '2025-01-09', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-01-05 16:00:00', 8, NULL, 'Surfing', NULL, '2024-12-29 16:00:00', '2025-01-05 16:00:00'),
(832, 89, 3, NULL, 'REF-2025-001831', '2025-02-09', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-02-03 16:00:00', 89, NULL, 'Surfing', NULL, '2025-01-29 16:00:00', '2025-02-03 16:00:00'),
(833, 76, 1, NULL, 'REF-2025-001832', '2025-12-04', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-12-01 16:00:00', 76, NULL, 'Others', 'Social media', '2025-11-25 16:00:00', '2025-12-01 16:00:00'),
(834, 8, 1, NULL, 'REF-2025-001833', '2025-05-06', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-05-03 16:00:00', 8, NULL, 'Making Activity', NULL, '2025-04-24 16:00:00', '2025-05-03 16:00:00'),
(835, 9, 3, NULL, 'REF-2025-001834', '2025-12-11', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Work commitment', 0, '2025-12-05 16:00:00', 9, NULL, 'Making Activity', NULL, '2025-11-29 16:00:00', '2025-12-05 16:00:00'),
(836, 76, 3, NULL, 'REF-2025-001835', '2025-11-08', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Health reasons', 0, '2025-11-02 16:00:00', 76, NULL, 'Reading', NULL, '2025-11-05 16:00:00', '2025-11-02 16:00:00'),
(837, 77, 1, NULL, 'REF-2025-001836', '2025-04-06', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-04-01 16:00:00', 77, NULL, 'Surfing', NULL, '2025-03-29 16:00:00', '2025-04-01 16:00:00'),
(838, 12, 2, NULL, 'REF-2025-001837', '2025-01-07', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-01-04 16:00:00', 12, NULL, 'Others', 'Social media', '2024-12-24 16:00:00', '2025-01-04 16:00:00'),
(839, 10, 2, NULL, 'REF-2025-001838', '2025-03-10', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-03-06 16:00:00', 10, NULL, 'Others', 'Video editing', '2025-02-26 16:00:00', '2025-03-06 16:00:00'),
(840, 63, 1, NULL, 'REF-2025-001839', '2025-09-06', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-09-04 16:00:00', 63, NULL, 'Making Activity', NULL, '2025-08-28 16:00:00', '2025-09-04 16:00:00'),
(841, 89, 3, NULL, 'REF-2025-001840', '2025-08-05', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-08-01 16:00:00', 89, NULL, 'Surfing', NULL, '2025-07-31 16:00:00', '2025-08-01 16:00:00'),
(842, 4, 1, NULL, 'REF-2025-001841', '2025-11-10', '15:00:00', '16:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-11-07 16:00:00', 4, NULL, 'Reading', NULL, '2025-11-05 16:00:00', '2025-11-07 16:00:00'),
(843, 16, 1, NULL, 'REF-2025-001842', '2025-07-10', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-07-04 16:00:00', 16, NULL, 'Others', 'Social media', '2025-07-08 16:00:00', '2025-07-04 16:00:00'),
(844, 62, 2, NULL, 'REF-2025-001843', '2025-07-02', '13:00:00', '14:00:00', NULL, NULL, 1, 'cancelled', 'Personal emergency', 0, '2025-06-29 16:00:00', 62, NULL, 'Surfing', NULL, '2025-06-22 16:00:00', '2025-06-29 16:00:00'),
(845, 59, 3, NULL, 'REF-2025-001844', '2025-04-03', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Weather conditions', 0, '2025-03-28 16:00:00', 59, NULL, 'Others', 'Email checking', '2025-03-19 16:00:00', '2025-03-28 16:00:00'),
(846, 82, 3, NULL, 'REF-2025-001845', '2025-01-02', '16:00:00', '17:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2024-12-29 16:00:00', 82, NULL, 'Surfing', NULL, '2024-12-25 16:00:00', '2024-12-29 16:00:00'),
(847, 79, 1, NULL, 'REF-2025-001846', '2025-06-12', '11:00:00', '12:00:00', NULL, NULL, 1, 'cancelled', 'Family matters', 0, '2025-06-09 16:00:00', 79, NULL, 'Surfing', NULL, '2025-06-05 16:00:00', '2025-06-09 16:00:00'),
(848, 21, 1, NULL, 'REF-2025-001847', '2025-03-11', '09:00:00', '10:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-03-05 16:00:00', 21, NULL, 'Reading', NULL, '2025-02-25 16:00:00', '2025-03-05 16:00:00'),
(849, 68, 2, NULL, 'REF-2025-001848', '2025-07-14', '08:00:00', '09:00:00', NULL, NULL, 1, 'cancelled', 'No longer needed', 0, '2025-07-12 16:00:00', 68, NULL, 'Surfing', NULL, '2025-07-06 16:00:00', '2025-07-12 16:00:00'),
(850, 83, 1, NULL, 'REF-2025-001849', '2025-04-09', '14:00:00', '15:00:00', NULL, NULL, 1, 'cancelled', 'Schedule conflict', 0, '2025-04-07 16:00:00', 83, NULL, 'Reading', NULL, '2025-03-31 16:00:00', '2025-04-07 16:00:00'),
(851, 63, 1, NULL, 'REF-2025-001850', '2025-12-20', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-17 16:00:00', NULL),
(852, 88, 3, NULL, 'REF-2025-001851', '2025-12-30', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-18 16:00:00', NULL),
(853, 68, 3, NULL, 'REF-2025-001852', '2025-12-23', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-12-12 16:00:00', NULL),
(854, 76, 2, NULL, 'REF-2025-001853', '2025-12-27', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-19 16:00:00', NULL),
(855, 89, 3, NULL, 'REF-2025-001854', '2025-12-23', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-10 16:00:00', NULL),
(856, 85, 1, NULL, 'REF-2025-001855', '2025-12-15', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-05 16:00:00', NULL),
(857, 90, 3, NULL, 'REF-2025-001856', '2025-12-30', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-16 16:00:00', NULL),
(858, 86, 3, NULL, 'REF-2025-001857', '2025-12-23', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-18 16:00:00', NULL),
(859, 18, 3, NULL, 'REF-2025-001858', '2025-12-28', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-21 16:00:00', NULL),
(860, 75, 2, NULL, 'REF-2025-001859', '2025-12-31', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-27 16:00:00', NULL),
(861, 59, 3, NULL, 'REF-2025-001860', '2025-12-17', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-04 16:00:00', NULL),
(862, 85, 3, NULL, 'REF-2025-001861', '2025-12-31', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-22 16:00:00', NULL),
(863, 59, 3, NULL, 'REF-2025-001862', '2025-12-15', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-12-07 16:00:00', NULL),
(864, 87, 2, NULL, 'REF-2025-001863', '2025-12-27', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-19 16:00:00', NULL),
(865, 83, 1, NULL, 'REF-2025-001864', '2025-12-30', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-15 16:00:00', NULL),
(866, 80, 1, NULL, 'REF-2025-001865', '2025-12-19', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-11 16:00:00', NULL),
(867, 3, 1, NULL, 'REF-2025-001866', '2025-12-19', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-09 16:00:00', NULL),
(868, 81, 3, NULL, 'REF-2025-001867', '2025-12-17', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-09 16:00:00', NULL),
(869, 4, 2, NULL, 'REF-2025-001868', '2025-12-31', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-27 16:00:00', NULL),
(870, 17, 3, NULL, 'REF-2025-001869', '2025-12-30', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-17 16:00:00', NULL),
(871, 82, 3, NULL, 'REF-2025-001870', '2025-12-18', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-12-12 16:00:00', NULL),
(872, 81, 3, NULL, 'REF-2025-001871', '2025-12-25', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-19 16:00:00', NULL),
(873, 53, 1, NULL, 'REF-2025-001872', '2025-12-17', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-14 16:00:00', NULL),
(874, 12, 3, NULL, 'REF-2025-001873', '2025-12-19', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-16 16:00:00', NULL),
(875, 75, 1, NULL, 'REF-2025-001874', '2025-12-31', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-27 16:00:00', NULL),
(876, 5, 1, NULL, 'REF-2025-001875', '2025-12-25', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-15 16:00:00', NULL),
(877, 3, 1, NULL, 'REF-2025-001876', '2025-12-26', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-22 16:00:00', NULL),
(878, 56, 3, NULL, 'REF-2025-001877', '2025-12-20', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-05 16:00:00', NULL),
(879, 84, 3, NULL, 'REF-2025-001878', '2025-12-18', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-15 16:00:00', NULL),
(880, 87, 2, NULL, 'REF-2025-001879', '2025-12-28', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-26 16:00:00', NULL),
(881, 74, 1, NULL, 'REF-2025-001880', '2025-12-21', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-08 16:00:00', NULL),
(882, 56, 3, NULL, 'REF-2025-001881', '2025-12-22', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-10 16:00:00', NULL),
(883, 71, 3, NULL, 'REF-2025-001882', '2025-12-20', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-12-05 16:00:00', NULL),
(884, 82, 3, NULL, 'REF-2025-001883', '2025-12-17', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-12-14 16:00:00', NULL),
(885, 2, 1, NULL, 'REF-2025-001884', '2025-12-18', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-03 16:00:00', NULL),
(886, 20, 2, NULL, 'REF-2025-001885', '2025-12-18', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-03 16:00:00', NULL),
(887, 2, 1, NULL, 'REF-2025-001886', '2025-12-29', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-26 16:00:00', NULL),
(888, 80, 2, NULL, 'REF-2025-001887', '2025-12-15', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-01 16:00:00', NULL),
(889, 16, 2, NULL, 'REF-2025-001888', '2025-12-29', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-16 16:00:00', NULL),
(890, 5, 3, NULL, 'REF-2025-001889', '2025-12-19', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-12-14 16:00:00', NULL),
(891, 72, 1, NULL, 'REF-2025-001890', '2025-12-30', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-12-24 16:00:00', NULL),
(892, 67, 1, NULL, 'REF-2025-001891', '2025-12-15', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-11-30 16:00:00', NULL),
(893, 12, 1, NULL, 'REF-2025-001892', '2025-12-15', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-12-11 16:00:00', NULL),
(894, 59, 1, NULL, 'REF-2025-001893', '2025-12-28', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-15 16:00:00', NULL),
(895, 87, 1, NULL, 'REF-2025-001894', '2025-12-26', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-12-13 16:00:00', NULL),
(896, 75, 3, NULL, 'REF-2025-001895', '2025-12-21', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-15 16:00:00', NULL),
(897, 87, 2, NULL, 'REF-2025-001896', '2025-12-28', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-23 16:00:00', NULL),
(898, 64, 2, NULL, 'REF-2025-001897', '2025-12-28', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-13 16:00:00', NULL),
(899, 60, 1, NULL, 'REF-2025-001898', '2025-12-29', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-20 16:00:00', NULL),
(900, 84, 1, NULL, 'REF-2025-001899', '2025-12-18', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-05 16:00:00', NULL),
(901, 79, 2, NULL, 'REF-2025-001900', '2025-12-15', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-02 16:00:00', NULL),
(902, 11, 2, NULL, 'REF-2025-001901', '2025-12-19', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-08 16:00:00', NULL),
(903, 81, 1, NULL, 'REF-2025-001902', '2025-12-21', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-06 16:00:00', NULL),
(904, 84, 3, NULL, 'REF-2025-001903', '2025-12-19', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-05 16:00:00', NULL),
(905, 13, 3, NULL, 'REF-2025-001904', '2025-12-19', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-12-12 16:00:00', NULL),
(906, 12, 1, NULL, 'REF-2025-001905', '2025-12-27', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-23 16:00:00', NULL),
(907, 16, 3, NULL, 'REF-2025-001906', '2025-12-31', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-23 16:00:00', NULL),
(908, 83, 3, NULL, 'REF-2025-001907', '2025-12-25', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-19 16:00:00', NULL),
(909, 6, 2, NULL, 'REF-2025-001908', '2025-12-25', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-23 16:00:00', NULL),
(910, 78, 2, NULL, 'REF-2025-001909', '2025-12-25', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-13 16:00:00', NULL),
(911, 13, 1, NULL, 'REF-2025-001910', '2025-12-15', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-12-03 16:00:00', NULL),
(912, 81, 2, NULL, 'REF-2025-001911', '2025-12-19', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-08 16:00:00', NULL),
(913, 91, 3, NULL, 'REF-2025-001912', '2025-12-27', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-23 16:00:00', NULL),
(914, 16, 3, NULL, 'REF-2025-001913', '2025-12-17', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-06 16:00:00', NULL),
(915, 6, 3, NULL, 'REF-2025-001914', '2025-12-19', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-14 16:00:00', NULL),
(916, 7, 3, NULL, 'REF-2025-001915', '2025-12-21', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-09 16:00:00', NULL),
(917, 64, 1, NULL, 'REF-2025-001916', '2025-12-21', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-09 16:00:00', NULL),
(918, 15, 2, NULL, 'REF-2025-001917', '2025-12-23', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-21 16:00:00', NULL),
(919, 10, 2, NULL, 'REF-2025-001918', '2025-12-30', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-21 16:00:00', NULL),
(920, 2, 3, NULL, 'REF-2025-001919', '2025-12-18', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-15 16:00:00', NULL),
(921, 14, 2, NULL, 'REF-2025-001920', '2025-12-25', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-12-13 16:00:00', NULL),
(922, 74, 2, NULL, 'REF-2025-001921', '2025-12-15', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-12-04 16:00:00', NULL),
(923, 81, 1, NULL, 'REF-2025-001922', '2025-12-17', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-04 16:00:00', NULL),
(924, 78, 2, NULL, 'REF-2025-001923', '2025-12-24', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-13 16:00:00', NULL),
(925, 81, 1, NULL, 'REF-2025-001924', '2025-12-31', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-29 16:00:00', NULL),
(926, 86, 2, NULL, 'REF-2025-001925', '2025-12-26', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-18 16:00:00', NULL),
(927, 1, 1, NULL, 'REF-2025-001926', '2025-12-23', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-13 16:00:00', NULL),
(928, 58, 3, NULL, 'REF-2025-001927', '2025-12-22', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-18 16:00:00', NULL),
(929, 85, 1, NULL, 'REF-2025-001928', '2025-12-31', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-20 16:00:00', NULL),
(930, 20, 2, NULL, 'REF-2025-001929', '2025-12-22', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-18 16:00:00', NULL),
(931, 74, 1, NULL, 'REF-2025-001930', '2025-12-19', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-09 16:00:00', NULL),
(932, 4, 3, NULL, 'REF-2025-001931', '2025-12-22', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-20 16:00:00', NULL),
(933, 79, 1, NULL, 'REF-2025-001932', '2025-12-30', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-19 16:00:00', NULL),
(934, 80, 2, NULL, 'REF-2025-001933', '2025-12-23', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-21 16:00:00', NULL),
(935, 15, 2, NULL, 'REF-2025-001934', '2025-12-15', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-03 16:00:00', NULL),
(936, 80, 2, NULL, 'REF-2025-001935', '2025-12-22', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-07 16:00:00', NULL),
(937, 68, 1, NULL, 'REF-2025-001936', '2025-12-22', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Project work', '2025-12-18 16:00:00', NULL),
(938, 72, 1, NULL, 'REF-2025-001937', '2025-12-15', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Group study', '2025-12-05 16:00:00', NULL),
(939, 78, 1, NULL, 'REF-2025-001938', '2025-12-28', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-13 16:00:00', NULL),
(940, 15, 2, NULL, 'REF-2025-001939', '2025-12-17', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-04 16:00:00', NULL);
INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `closure_period_id`, `reference_no`, `reservation_date`, `start_time`, `end_time`, `actual_time_in`, `actual_time_out`, `units_reserved`, `status`, `cancellation_reason`, `suspension_applied`, `cancelled_at`, `cancelled_by`, `preferences`, `reservation_reason`, `other_reason`, `created_at`, `updated_at`) VALUES
(941, 65, 1, NULL, 'REF-2025-001940', '2025-12-24', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-10 16:00:00', NULL),
(942, 9, 1, NULL, 'REF-2025-001941', '2025-12-15', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-11 16:00:00', NULL),
(943, 13, 1, NULL, 'REF-2025-001942', '2025-12-22', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-18 16:00:00', NULL),
(944, 82, 1, NULL, 'REF-2025-001943', '2025-12-22', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-07 16:00:00', NULL),
(945, 17, 1, NULL, 'REF-2025-001944', '2025-12-19', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-12-14 16:00:00', NULL),
(946, 80, 3, NULL, 'REF-2025-001945', '2025-12-31', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-26 16:00:00', NULL),
(947, 88, 2, NULL, 'REF-2025-001946', '2025-12-23', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-21 16:00:00', NULL),
(948, 11, 1, NULL, 'REF-2025-001947', '2025-12-27', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-13 16:00:00', NULL),
(949, 75, 3, NULL, 'REF-2025-001948', '2025-12-29', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-15 16:00:00', NULL),
(950, 54, 1, NULL, 'REF-2025-001949', '2025-12-29', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-19 16:00:00', NULL),
(951, 67, 3, NULL, 'REF-2025-001950', '2025-12-22', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-09 16:00:00', NULL),
(952, 4, 1, NULL, 'REF-2025-001951', '2025-12-31', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-29 16:00:00', NULL),
(953, 3, 3, NULL, 'REF-2025-001952', '2025-12-20', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-17 16:00:00', NULL),
(954, 81, 1, NULL, 'REF-2025-001953', '2025-12-28', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-18 16:00:00', NULL),
(955, 71, 1, NULL, 'REF-2025-001954', '2025-12-16', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-10 16:00:00', NULL),
(956, 79, 1, NULL, 'REF-2025-001955', '2025-12-28', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-15 16:00:00', NULL),
(957, 4, 2, NULL, 'REF-2025-001956', '2025-12-16', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-12 16:00:00', NULL),
(958, 6, 3, NULL, 'REF-2025-001957', '2025-12-19', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-09 16:00:00', NULL),
(959, 12, 2, NULL, 'REF-2025-001958', '2025-12-28', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-23 16:00:00', NULL),
(960, 86, 1, NULL, 'REF-2025-001959', '2025-12-25', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-19 16:00:00', NULL),
(961, 14, 2, NULL, 'REF-2025-001960', '2025-12-24', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-21 16:00:00', NULL),
(962, 63, 3, NULL, 'REF-2025-001961', '2025-12-21', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Email checking', '2025-12-12 16:00:00', NULL),
(963, 21, 1, NULL, 'REF-2025-001962', '2025-12-25', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-11 16:00:00', NULL),
(964, 75, 2, NULL, 'REF-2025-001963', '2025-12-25', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-17 16:00:00', NULL),
(965, 8, 1, NULL, 'REF-2025-001964', '2025-12-20', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-12 16:00:00', NULL),
(966, 67, 3, NULL, 'REF-2025-001965', '2025-12-31', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-29 16:00:00', NULL),
(967, 1, 2, NULL, 'REF-2025-001966', '2025-12-23', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-08 16:00:00', NULL),
(968, 62, 2, NULL, 'REF-2025-001967', '2025-12-21', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-12 16:00:00', NULL),
(969, 55, 3, NULL, 'REF-2025-001968', '2025-12-19', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-12-15 16:00:00', NULL),
(970, 7, 1, NULL, 'REF-2025-001969', '2025-12-15', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-12 16:00:00', NULL),
(971, 57, 2, NULL, 'REF-2025-001970', '2025-12-24', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Thesis writing', '2025-12-15 16:00:00', NULL),
(972, 64, 3, NULL, 'REF-2025-001971', '2025-12-23', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-11 16:00:00', NULL),
(973, 78, 3, NULL, 'REF-2025-001972', '2025-12-25', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-12-12 16:00:00', NULL),
(974, 8, 3, NULL, 'REF-2025-001973', '2025-12-17', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-11 16:00:00', NULL),
(975, 91, 2, NULL, 'REF-2025-001974', '2025-12-22', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-11 16:00:00', NULL),
(976, 54, 2, NULL, 'REF-2025-001975', '2025-12-24', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-13 16:00:00', NULL),
(977, 11, 1, NULL, 'REF-2025-001976', '2025-12-29', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-24 16:00:00', NULL),
(978, 21, 1, NULL, 'REF-2025-001977', '2025-12-27', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-12 16:00:00', NULL),
(979, 74, 3, NULL, 'REF-2025-001978', '2025-12-27', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-17 16:00:00', NULL),
(980, 13, 1, NULL, 'REF-2025-001979', '2025-12-25', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Watching videos', '2025-12-18 16:00:00', NULL),
(981, 5, 2, NULL, 'REF-2025-001980', '2025-12-16', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-14 16:00:00', NULL),
(982, 4, 3, NULL, 'REF-2025-001981', '2025-12-15', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-02 16:00:00', NULL),
(983, 75, 1, NULL, 'REF-2025-001982', '2025-12-20', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-11 16:00:00', NULL),
(984, 10, 1, NULL, 'REF-2025-001983', '2025-12-29', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-23 16:00:00', NULL),
(985, 78, 2, NULL, 'REF-2025-001984', '2025-12-24', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Homework', '2025-12-11 16:00:00', NULL),
(986, 17, 2, NULL, 'REF-2025-001985', '2025-12-16', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-05 16:00:00', NULL),
(987, 9, 1, NULL, 'REF-2025-001986', '2025-12-21', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-08 16:00:00', NULL),
(988, 8, 2, NULL, 'REF-2025-001987', '2025-12-26', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Video editing', '2025-12-22 16:00:00', NULL),
(989, 56, 2, NULL, 'REF-2025-001988', '2025-12-30', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Gaming', '2025-12-25 16:00:00', NULL),
(990, 68, 3, NULL, 'REF-2025-001989', '2025-12-31', '16:00:00', '17:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-27 16:00:00', NULL),
(991, 16, 1, NULL, 'REF-2025-001990', '2025-12-20', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-07 16:00:00', NULL),
(992, 87, 1, NULL, 'REF-2025-001991', '2025-12-26', '13:00:00', '14:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-21 16:00:00', NULL),
(993, 90, 1, NULL, 'REF-2025-001992', '2025-12-24', '14:00:00', '15:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Online meeting', '2025-12-11 16:00:00', NULL),
(994, 87, 1, NULL, 'REF-2025-001993', '2025-12-24', '08:00:00', '09:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-14 16:00:00', NULL),
(995, 53, 2, NULL, 'REF-2025-001994', '2025-12-17', '11:00:00', '12:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-02 16:00:00', NULL),
(996, 75, 2, NULL, 'REF-2025-001995', '2025-12-21', '09:00:00', '10:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-09 16:00:00', NULL),
(997, 87, 1, NULL, 'REF-2025-001996', '2025-12-26', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Reading', NULL, '2025-12-24 16:00:00', NULL),
(998, 66, 2, NULL, 'REF-2025-001997', '2025-12-28', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Others', 'Social media', '2025-12-19 16:00:00', NULL),
(999, 16, 1, NULL, 'REF-2025-001998', '2025-12-23', '10:00:00', '11:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Making Activity', NULL, '2025-12-21 16:00:00', NULL),
(1000, 78, 2, NULL, 'REF-2025-001999', '2025-12-17', '15:00:00', '16:00:00', NULL, NULL, 1, 'pending', NULL, 0, NULL, NULL, NULL, 'Surfing', NULL, '2025-12-02 16:00:00', NULL);

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

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `capacity_units`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PC', 'Personal Computer workstation with internet access for research, document processing, and online activities.', 8, 1, NULL, '2025-12-13 15:59:42', '2025-12-13 15:59:42'),
(2, 'Study Area', 'Quiet study space with desk and chair for reading, studying, and focused work.', 8, 1, NULL, '2025-12-13 15:59:42', '2025-12-13 15:59:42'),
(3, 'TV', 'Television viewing area for entertainment, news, and educational programs.', 1, 1, NULL, '2025-12-13 15:59:42', '2025-12-13 15:59:42');

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
('vapJsj7uyYZljY6a0WLEqthn5UDJAx2eA5oiS4EO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZlMycjZraXZZOEd2dFpETElrRExJSUhZcWJTdzROZVdvSXpRMHpOZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1765641725);

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
(1, 'Admin', 'User', NULL, NULL, 0, 'admin@gmail.com', NULL, '$2y$12$PreMaiuVgdGQIV0TPZK44unRDWFpWJX7fdjKG2MQoUv91vi3CDlVK', 1, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:42', '2025-12-13 15:59:42'),
(2, 'Dexter', 'Ramos', '1990-05-15', 'Male', 0, 'dexter@gmail.com', NULL, '$2y$12$.SrMdRzLk8GWSyLJpzGHeeFguI6XGE/5N0IRT7U28.JgUkDInGRNm', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:43', '2025-12-13 15:59:43'),
(3, 'Nathaniel', 'Bayona', '1992-08-22', 'Male', 0, 'nathaniel@gmail.com', NULL, '$2y$12$SyQ1XhfyxUEyTSBbEG5W2OhX6qT0sH7r.CYtyakZmt0zbeDApn6sm', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:43', '2025-12-13 15:59:43'),
(4, 'Park', 'Gica', '1988-03-10', 'Male', 0, 'park@gmail.com', NULL, '$2y$12$vXpg7vPiYlSyZ7ktAwbs7OLPmvwxmmEwq.7wONZKYWuP/IB8ycAhW', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:43', '2025-12-13 15:59:43'),
(5, 'Christian', 'Torion', '1995-11-05', 'Male', 0, 'chan@gmail.com', NULL, '$2y$12$q9MSIuWTrwp73rfvS/1mI.5iwfCaN3H3ynEEviCJ107R.z/0UPgWa', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:43', '2025-12-13 15:59:43'),
(6, 'Rocky', 'Adaya', '1991-07-18', 'Male', 0, 'rocky@gmail.com', NULL, '$2y$12$J7FOnVzNi1WFHkLuJ4op5u8MY5rKsdpAkg4rxCgoyYAJ5qamyE2f2', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:44', '2025-12-13 15:59:44'),
(7, 'Juan', 'Santos', '1993-02-14', 'Male', 0, 'juan@gmail.com', NULL, '$2y$12$/oLDNImFQrI5aoGe9tbrVeDlg.k3FtPnpV1TGB3t5p7sTlCJMBVJq', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:44', '2025-12-13 15:59:44'),
(8, 'Miguel', 'Cruz', '1989-09-28', 'Male', 0, 'miguel@gmail.com', NULL, '$2y$12$mGVxNKmAZD2KrFY1r4iCTuATIMmgTjYJNWfgO.Y7g9xT4/wCvGFBy', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:44', '2025-12-13 15:59:44'),
(9, 'Carlos', 'Reyes', '1994-04-03', 'Male', 0, 'carlos@gmail.com', NULL, '$2y$12$RwWMHFfLbw3Lk8/pv0AU1OZ8/wmsDrZXkKqPhtxtZtqPKnd1qPBhO', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:44', '2025-12-13 15:59:44'),
(10, 'Antonio', 'Flores', '1987-12-20', 'Male', 0, 'antonio@gmail.com', NULL, '$2y$12$RI9g5hcwExTJclMgkx07/ugcjflJLwmxoh1UG8bU2./EPMLkQZhv6', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:45', '2025-12-13 15:59:45'),
(11, 'Roberto', 'Morales', '1996-06-11', 'Male', 0, 'roberto@gmail.com', NULL, '$2y$12$8oB6l0tRKTVZ47bbKgBIYurAj2JICAHRDcN4XUwwB/JLJr8lPsT8K', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:45', '2025-12-13 15:59:45'),
(12, 'Maria', 'Garcia', '1991-01-08', 'Female', 0, 'maria@gmail.com', NULL, '$2y$12$j1qX9bxHfEbOq0rEo./gG.H8GsTNJtlXN7vPC/xaIuHydBlfrwnTS', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:45', '2025-12-13 15:59:45'),
(13, 'Rosa', 'Lopez', '1993-05-19', 'Female', 0, 'rosa@gmail.com', NULL, '$2y$12$Vt3IzuBy5AwLrvmn43vQpuryhsJoEbX1P/3n3fA30UpOnO7YFrTj.', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:45', '2025-12-13 15:59:45'),
(14, 'Ana', 'Martinez', '1990-10-25', 'Female', 0, 'ana@gmail.com', NULL, '$2y$12$rslWJjN1gQVV7inzR0/5deZtJnHkdymmTA7ZdFeR1pO6.Hek9cvee', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:46', '2025-12-13 15:59:46'),
(15, 'Sofia', 'Hernandez', '1994-03-12', 'Female', 0, 'sofia@gmail.com', NULL, '$2y$12$NCSAzIrgcYSqaVEQLy.2V./j.KYahdCXkp7TppbJ2g5tKDWxh2nzK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:46', '2025-12-13 15:59:46'),
(16, 'Isabella', 'Perez', '1992-07-30', 'Female', 0, 'isabella@gmail.com', NULL, '$2y$12$QqrFK/wpNshGMQVK.Sp2ge1BAeUMKPUngmurn4gdW3mhaVEZ6F4YC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:46', '2025-12-13 15:59:46'),
(17, 'Carmen', 'Sanchez', '1988-11-06', 'Female', 0, 'carmen@gmail.com', NULL, '$2y$12$AO6sChIE3btALf6FRNoO5uhp5qkYdsMTcYgAUkihhgyn9VCAuuIuK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:46', '2025-12-13 15:59:46'),
(18, 'Elena', 'Ramirez', '1995-02-17', 'Female', 0, 'elena@gmail.com', NULL, '$2y$12$Jv380sAHDweIln8mvW14Iuorxw97LBPrrXazJXs266LHwF9u6q30S', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:47', '2025-12-13 15:59:47'),
(19, 'Lucia', 'Torres', '1989-08-09', 'Female', 0, 'lucia@gmail.com', NULL, '$2y$12$4QPDMWwlRO2CJR7LuZ/u0u9CpNbI99WuwXKg6oFu34KQSqm28CbAC', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:47', '2025-12-13 15:59:47'),
(20, 'Daniela', 'Rivera', '1996-04-21', 'Female', 0, 'daniela@gmail.com', NULL, '$2y$12$Rz3Y1V88vu9B7XQ3rphBM.0QatfTAIQ7pml2OYJd/WHBBNeXPtGLK', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:47', '2025-12-13 15:59:47'),
(21, 'Valentina', 'Castillo', '1991-09-14', 'Female', 0, 'valentina@gmail.com', NULL, '$2y$12$l887LbuluksFcPQZ6BFnZuERPHO1YQ6m5rvWH9QQbfUK4AhvuoGz.', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-12-13 15:59:47', '2025-12-13 15:59:47'),
(22, 'Concepcion', 'Velasquez', '2003-05-08', 'Female', 0, 'concepcion.velasquez578@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-21 15:59:47', '2025-11-22 15:59:47'),
(23, 'Roberto', 'Montes', '2005-01-14', 'Male', 0, 'roberto.montes347@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-05-31 15:59:47', '2025-11-26 15:59:47'),
(24, 'Cristina', 'Torres', '2005-11-07', 'Male', 1, 'cristina.torres467@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-04-15 15:59:47', '2025-11-21 15:59:47'),
(25, 'Guillermo', 'Ortega', '2004-08-14', 'Male', 0, 'guillermo.ortega679@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-29 15:59:47', '2025-11-13 15:59:47'),
(26, 'Carlos', 'Perez', '2007-11-05', 'Female', 0, 'carlos.perez728@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-01-23 15:59:47', '2025-12-10 15:59:47'),
(27, 'Presentacion', 'Jimenez', '2012-02-22', 'Female', 0, 'presentacion.jimenez491@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-30 15:59:47', '2025-11-30 15:59:47'),
(28, 'Angelita', 'Rios', '2012-05-10', 'Male', 0, 'angelita.rios152@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-02-20 15:59:47', '2025-11-15 15:59:47'),
(29, 'Alfredo', 'Cortez', '2010-06-05', 'Female', 0, 'alfredo.cortez587@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-18 15:59:47', '2025-11-28 15:59:47'),
(30, 'Enrique', 'Fernandez', '2013-09-14', 'Female', 0, 'enrique.fernandez727@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-24 15:59:47', '2025-11-20 15:59:47'),
(31, 'Pablo', 'Tan', '2008-04-21', 'Male', 0, 'pablo.tan560@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2024-12-24 15:59:47', '2025-12-03 15:59:47'),
(32, 'Carmen', 'Sy', '2012-02-01', 'Female', 0, 'carmen.sy987@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-08-22 15:59:47', '2025-11-21 15:59:47'),
(33, 'Ricardo', 'Soriano', '2009-02-10', 'Male', 0, 'ricardo.soriano32@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-07-20 15:59:47', '2025-11-13 15:59:47'),
(34, 'Salvador', 'Dela Cruz', '2004-01-17', 'Female', 0, 'salvador.dela cruz876@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-02-08 15:59:47', '2025-11-30 15:59:47'),
(35, 'Patricia', 'Perez', '2010-11-07', 'Female', 0, 'patricia.perez412@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-03-16 15:59:47', '2025-12-13 15:59:47'),
(36, 'Angela', 'Espiritu', '2004-09-15', 'Male', 0, 'angela.espiritu900@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-19 15:59:47', '2025-12-03 15:59:47'),
(37, 'Cesar', 'Vega', '2013-11-28', 'Male', 0, 'cesar.vega838@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-07 15:59:47', '2025-11-25 15:59:47'),
(38, 'Armando', 'Lao', '2008-10-01', 'Female', 0, 'armando.lao286@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-03-09 15:59:47', '2025-12-11 15:59:47'),
(39, 'Caridad', 'Tan', '2007-12-24', 'Male', 0, 'caridad.tan790@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-03-30 15:59:47', '2025-12-01 15:59:47'),
(40, 'Victoria', 'Cortez', '2009-11-13', 'Male', 0, 'victoria.cortez545@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-12 15:59:47', '2025-11-25 15:59:47'),
(41, 'Luz', 'Marquez', '2004-05-27', 'Female', 0, 'luz.marquez442@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-05-04 15:59:47', '2025-12-04 15:59:47'),
(42, 'Juan', 'Garcia', '2011-07-30', 'Female', 0, 'juan.garcia298@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-01-16 15:59:47', '2025-11-30 15:59:47'),
(43, 'Eduardo', 'Ocampo', '2003-09-20', 'Female', 0, 'eduardo.ocampo199@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-02-13 15:59:47', '2025-12-09 15:59:47'),
(44, 'Armando', 'Solis', '2007-02-07', 'Female', 0, 'armando.solis577@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-08-25 15:59:47', '2025-11-15 15:59:47'),
(45, 'Cesar', 'Vargas', '2009-08-05', 'Female', 0, 'cesar.vargas121@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-14 15:59:47', '2025-12-04 15:59:47'),
(46, 'Pilar', 'Perez', '2009-11-16', 'Male', 0, 'pilar.perez525@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-02-04 15:59:47', '2025-11-25 15:59:47'),
(47, 'Remedios', 'Pena', '2005-12-24', 'Male', 0, 'remedios.pena500@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-19 15:59:47', '2025-12-13 15:59:47'),
(48, 'Encarnita', 'Ocampo', '2009-10-11', 'Female', 0, 'encarnita.ocampo291@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-11-02 15:59:47', '2025-12-01 15:59:47'),
(49, 'Ernesto', 'Dela Cruz', '2011-07-19', 'Female', 0, 'ernesto.dela cruz68@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-01-26 15:59:47', '2025-12-10 15:59:47'),
(50, 'Natividad', 'Bautista', '2007-12-06', 'Female', 0, 'natividad.bautista883@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-01-19 15:59:47', '2025-12-07 15:59:47'),
(51, 'Antonio', 'Miranda', '2002-12-14', 'Male', 0, 'antonio.miranda239@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'pending', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-01-13 15:59:47', '2025-12-10 15:59:47'),
(52, 'Resurreccion', 'Alonzo', '2005-04-10', 'Female', 0, 'resurreccion.alonzo64@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-01-29 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-01-29 15:59:47', '2025-11-20 15:59:47'),
(53, 'Felicidad', 'Miranda', '2002-07-11', 'Female', 0, 'felicidad.miranda148@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-05-19 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-07-04 15:59:47', '2025-11-29 15:59:47'),
(54, 'Victoria', 'Carrillo', '2001-08-21', 'Male', 0, 'victoria.carrillo189@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-01-23 15:59:47', '2025-11-18 15:59:47'),
(55, 'Asuncion', 'Castro', '2008-03-26', 'Female', 0, 'asuncion.castro648@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-03-30 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-01-01 15:59:47', '2025-12-08 15:59:47'),
(56, 'Armando', 'Sy', '2006-09-03', 'Female', 0, 'armando.sy52@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-28 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-04-11 15:59:47', '2025-11-24 15:59:47'),
(57, 'Diego', 'Valdez', '2010-06-09', 'Female', 0, 'diego.valdez901@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:59:47', NULL, NULL, NULL, 0, NULL, '2024-12-27 15:59:47', '2025-11-28 15:59:47'),
(58, 'Visitacion', 'Chavez', '2011-02-20', 'Male', 0, 'visitacion.chavez773@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-06-06 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-06-09 15:59:47', '2025-11-30 15:59:47'),
(59, 'Salvacion', 'Rosales', '2011-12-20', 'Male', 0, 'salvacion.rosales897@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-17 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-07-28 15:59:47', '2025-11-26 15:59:47'),
(60, 'Fe', 'Villanueva', '2010-10-02', 'Female', 0, 'fe.villanueva680@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-03-26 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-04-08 15:59:47', '2025-11-15 15:59:47'),
(61, 'Maria', 'Santos', '2001-06-10', 'Male', 0, 'maria.santos969@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-10-03 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-07-26 15:59:47', '2025-11-14 15:59:47'),
(62, 'Rosalinda', 'Morales', '2011-10-20', 'Male', 0, 'rosalinda.morales576@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-11-04 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-07 15:59:47', '2025-12-08 15:59:47'),
(63, 'Rosalinda', 'Sandoval', '2005-05-02', 'Male', 0, 'rosalinda.sandoval661@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-01-23 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-01-15 15:59:47', '2025-12-13 15:59:47'),
(64, 'Angela', 'Aguilar', '2007-06-25', 'Female', 0, 'angela.aguilar951@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2024-12-31 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-12 15:59:47', '2025-11-28 15:59:47'),
(65, 'Domingo', 'Ang', '2006-03-25', 'Male', 0, 'domingo.ang495@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-03-23 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-12-02 15:59:47', '2025-11-13 15:59:47'),
(66, 'Francisco', 'Suarez', '2007-08-06', 'Male', 0, 'francisco.suarez556@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-08-23 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-07-13 15:59:47', '2025-11-13 15:59:47'),
(67, 'Victoria', 'Velasquez', '2001-01-25', 'Male', 0, 'victoria.velasquez76@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-08-26 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-06-17 15:59:47', '2025-12-03 15:59:47'),
(68, 'Adoracion', 'Ortega', '2008-02-01', 'Female', 0, 'adoracion.ortega103@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-10-23 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-03 15:59:47', '2025-12-07 15:59:47'),
(69, 'Carmencita', 'Diaz', '2007-02-26', 'Male', 0, 'carmencita.diaz891@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-06-15 15:59:47', '2025-12-08 15:59:47'),
(70, 'Resurreccion', 'Herrera', '2001-03-01', 'Female', 0, 'resurreccion.herrera68@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-02-21 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-05-29 15:59:47', '2025-11-27 15:59:47'),
(71, 'Angelita', 'Rios', '2008-12-07', 'Male', 0, 'angelita.rios307@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-12-06 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-08-01 15:59:47', '2025-11-28 15:59:47'),
(72, 'Mercedes', 'Cruz', '2012-11-25', 'Male', 0, 'mercedes.cruz393@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-13 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-08-09 15:59:47', '2025-11-19 15:59:47'),
(73, 'Leonardo', 'Chavez', '2001-08-06', 'Male', 0, 'leonardo.chavez728@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2024-12-21 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-23 15:59:47', '2025-11-29 15:59:47'),
(74, 'Beatriz', 'Molina', '2006-08-15', 'Female', 0, 'beatriz.molina522@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-06-28 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-28 15:59:47', '2025-12-13 15:59:47'),
(75, 'Ana', 'Go', '2013-03-06', 'Female', 1, 'ana.go63@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-04-17 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-19 15:59:47', '2025-11-13 15:59:47'),
(76, 'Salvacion', 'Fernandez', '2013-04-21', 'Male', 0, 'salvacion.fernandez34@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-27 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-05-07 15:59:47', '2025-11-28 15:59:47'),
(77, 'Diego', 'Lim', '2007-08-20', 'Male', 0, 'diego.lim872@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-10-16 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-04 15:59:47', '2025-12-13 15:59:47'),
(78, 'Gabriel', 'Leon', '2004-11-17', 'Male', 0, 'gabriel.leon599@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-05-17 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-10-27 15:59:47', '2025-11-22 15:59:47'),
(79, 'Encarnita', 'Reyes', '2010-04-09', 'Male', 0, 'encarnita.reyes57@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-02-19 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-03 15:59:47', '2025-11-20 15:59:47'),
(80, 'Domingo', 'Contreras', '2010-07-10', 'Female', 0, 'domingo.contreras883@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-02-04 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-24 15:59:47', '2025-12-05 15:59:47'),
(81, 'Ramon', 'Lim', '2007-04-09', 'Male', 0, 'ramon.lim671@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-01-24 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-04-07 15:59:47', '2025-11-16 15:59:47'),
(82, 'Cesar', 'Delgado', '2005-10-04', 'Female', 0, 'cesar.delgado747@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-05-19 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-12-11 15:59:47', '2025-12-05 15:59:47'),
(83, 'Mario', 'Yu', '2007-10-07', 'Female', 0, 'mario.yu635@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-13 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-04-28 15:59:47', '2025-12-10 15:59:47'),
(84, 'Oscar', 'Cordero', '2007-09-13', 'Female', 1, 'oscar.cordero599@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-12-06 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-30 15:59:47', '2025-11-20 15:59:47'),
(85, 'Luz', 'Fernandez', '2007-09-19', 'Female', 0, 'luz.fernandez30@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-01-09 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-03-03 15:59:47', '2025-11-20 15:59:47'),
(86, 'Ruben', 'Morales', '2003-09-19', 'Female', 0, 'ruben.morales604@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2024-12-22 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-07-02 15:59:47', '2025-12-13 15:59:47'),
(87, 'Salvacion', 'Velasco', '2006-05-24', 'Female', 1, 'salvacion.velasco233@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-26 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-10-13 15:59:47', '2025-11-22 15:59:47'),
(88, 'Diego', 'Bautista', '2001-05-31', 'Female', 0, 'diego.bautista67@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-02-16 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-01-06 15:59:47', '2025-12-08 15:59:47'),
(89, 'Leonardo', 'Estrada', '2004-03-24', 'Male', 0, 'leonardo.estrada509@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-09-10 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-09-09 15:59:47', '2025-11-26 15:59:47'),
(90, 'Alejandro', 'Galvez', '2007-11-07', 'Male', 0, 'alejandro.galvez954@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-04-08 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-02-13 15:59:47', '2025-11-25 15:59:47'),
(91, 'Victoria', 'Rosales', '2001-12-23', 'Male', 0, 'victoria.rosales474@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'approved', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-05-27 15:59:47', NULL, NULL, NULL, 0, NULL, '2025-11-19 15:59:47', '2025-11-13 15:59:47'),
(92, 'Isabel', 'Padilla', '2007-12-10', 'Male', 0, 'isabel.padilla549@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-26 15:59:47', NULL, NULL, 0, NULL, '2025-10-21 15:59:47', '2025-11-13 15:59:47'),
(93, 'Alberto', 'Yap', '2006-06-27', 'Female', 0, 'alberto.yap989@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-12-04 15:59:47', NULL, NULL, 0, NULL, '2025-07-16 15:59:47', '2025-11-24 15:59:47'),
(94, 'Rosa', 'Vargas', '2013-02-18', 'Male', 0, 'rosa.vargas261@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-16 15:59:47', NULL, NULL, 0, NULL, '2025-03-31 15:59:47', '2025-12-06 15:59:47'),
(95, 'Felicidad', 'Maldonado', '2003-07-31', 'Female', 0, 'felicidad.maldonado795@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-23 15:59:47', NULL, NULL, 0, NULL, '2025-08-31 15:59:47', '2025-11-22 15:59:47'),
(96, 'Rosalinda', 'Mejia', '2012-09-23', 'Male', 0, 'rosalinda.mejia398@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-26 15:59:47', NULL, NULL, 0, NULL, '2025-02-10 15:59:47', '2025-11-13 15:59:47'),
(97, 'Dolores', 'Morales', '2005-11-21', 'Male', 0, 'dolores.morales451@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-12-08 15:59:47', NULL, NULL, 0, NULL, '2025-01-15 15:59:47', '2025-11-17 15:59:47'),
(98, 'Sofia', 'Contreras', '2012-04-08', 'Female', 1, 'sofia.contreras321@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-11-20 15:59:47', NULL, NULL, 0, NULL, '2025-01-10 15:59:47', '2025-12-05 15:59:47'),
(99, 'Consuelo', 'Ong', '2007-11-03', 'Female', 0, 'consuelo.ong816@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-17 15:59:47', NULL, NULL, 0, NULL, '2025-12-02 15:59:47', '2025-12-10 15:59:47'),
(100, 'Encarnacion', 'Garcia', '2005-09-18', 'Female', 0, 'encarnacion.garcia234@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-27 15:59:47', NULL, NULL, 0, NULL, '2024-12-25 15:59:47', '2025-12-01 15:59:47'),
(101, 'Enrique', 'Castro', '2012-11-06', 'Male', 0, 'enrique.castro879@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-21 15:59:47', NULL, NULL, 0, NULL, '2025-05-01 15:59:47', '2025-11-22 15:59:47'),
(102, 'Carmencita', 'Fuentes', '2003-11-05', 'Male', 0, 'carmencita.fuentes646@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-20 15:59:47', NULL, NULL, 0, NULL, '2025-05-17 15:59:47', '2025-11-22 15:59:47'),
(103, 'Cesar', 'Valdez', '2010-07-31', 'Female', 0, 'cesar.valdez890@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-30 15:59:47', NULL, NULL, 0, NULL, '2025-06-05 15:59:47', '2025-12-04 15:59:47'),
(104, 'Trinidad', 'Aguilar', '2007-06-18', 'Male', 0, 'trinidad.aguilar887@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-11-15 15:59:47', NULL, NULL, 0, NULL, '2025-10-18 15:59:47', '2025-11-18 15:59:47'),
(105, 'Leonardo', 'Villanueva', '2006-03-09', 'Female', 0, 'leonardo.villanueva427@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-24 15:59:47', NULL, NULL, 0, NULL, '2025-07-02 15:59:47', '2025-11-21 15:59:47'),
(106, 'Pablo', 'Reyes', '2009-08-05', 'Male', 0, 'pablo.reyes228@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Incomplete registration information.', NULL, '2025-11-24 15:59:47', NULL, NULL, 0, NULL, '2025-09-01 15:59:47', '2025-11-30 15:59:47'),
(107, 'Maria', 'Ocampo', '2001-08-22', 'Female', 0, 'maria.ocampo897@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Information does not match ID.', NULL, '2025-11-19 15:59:47', NULL, NULL, 0, NULL, '2025-08-18 15:59:47', '2025-11-25 15:59:47'),
(108, 'Marcos', 'Galvez', '2004-03-19', 'Female', 0, 'marcos.galvez612@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-12-08 15:59:47', NULL, NULL, 0, NULL, '2024-12-23 15:59:47', '2025-12-11 15:59:47'),
(109, 'Purificacion', 'Guzman', '2004-05-08', 'Male', 1, 'purificacion.guzman382@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Incomplete registration information.', NULL, '2025-11-24 15:59:47', NULL, NULL, 0, NULL, '2025-02-10 15:59:47', '2025-12-11 15:59:47'),
(110, 'Eduardo', 'Chua', '2007-08-21', 'Male', 0, 'eduardo.chua851@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-20 15:59:47', NULL, NULL, 0, NULL, '2025-01-10 15:59:47', '2025-11-27 15:59:47'),
(111, 'Adoracion', 'Yap', '2004-04-30', 'Female', 0, 'adoracion.yap999@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-17 15:59:47', NULL, NULL, 0, NULL, '2025-02-13 15:59:47', '2025-12-09 15:59:47'),
(112, 'Mercedes', 'Lim', '2008-09-04', 'Male', 0, 'mercedes.lim495@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-12-05 15:59:47', NULL, NULL, 0, NULL, '2025-05-08 15:59:47', '2025-12-09 15:59:47'),
(113, 'Felipe', 'Morales', '2009-07-12', 'Male', 0, 'felipe.morales45@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Information does not match ID.', NULL, '2025-11-13 15:59:47', NULL, NULL, 0, NULL, '2025-06-19 15:59:47', '2025-12-03 15:59:47'),
(114, 'Fe', 'Padilla', '2007-11-16', 'Male', 1, 'fe.padilla943@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-26 15:59:47', NULL, NULL, 0, NULL, '2025-11-26 15:59:47', '2025-12-09 15:59:47'),
(115, 'Rodrigo', 'Ang', '2004-01-23', 'Female', 0, 'rodrigo.ang204@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-12-09 15:59:47', NULL, NULL, 0, NULL, '2025-09-21 15:59:47', '2025-12-08 15:59:47'),
(116, 'Cristina', 'Molina', '2012-04-22', 'Female', 0, 'cristina.molina892@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'ID photo is unclear or blurry.', NULL, '2025-11-29 15:59:47', NULL, NULL, 0, NULL, '2025-01-12 15:59:47', '2025-11-16 15:59:47'),
(117, 'Pilar', 'Ocampo', '2012-04-04', 'Female', 0, 'pilar.ocampo90@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Information does not match ID.', NULL, '2025-11-19 15:59:47', NULL, NULL, 0, NULL, '2025-01-07 15:59:47', '2025-11-16 15:59:47'),
(118, 'Angela', 'Gonzales', '2006-01-28', 'Male', 0, 'angela.gonzales352@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Information does not match ID.', NULL, '2025-12-06 15:59:47', NULL, NULL, 0, NULL, '2025-06-10 15:59:47', '2025-12-10 15:59:47'),
(119, 'Rafael', 'Herrera', '2004-04-14', 'Female', 0, 'rafael.herrera116@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Invalid ID document provided.', NULL, '2025-11-16 15:59:47', NULL, NULL, 0, NULL, '2025-01-08 15:59:47', '2025-11-26 15:59:48'),
(120, 'Jorge', 'Lee', '2007-12-11', 'Female', 0, 'jorge.lee479@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-12-12 15:59:48', NULL, NULL, 0, NULL, '2025-09-12 15:59:48', '2025-11-13 15:59:48'),
(121, 'Alberto', 'Soriano', '2009-07-15', 'Male', 0, 'alberto.soriano104@example.com', NULL, '$2y$12$h3OxuzeB46uy2glasZPeWe2LY./BIde.hX/01bKHFdD.YwZwhXR6W', 0, 'rejected', 0, 0, 0, NULL, NULL, NULL, NULL, 'Duplicate account detected.', NULL, '2025-11-27 15:59:48', NULL, NULL, 0, NULL, '2025-04-18 15:59:48', '2025-12-13 15:59:48');

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
