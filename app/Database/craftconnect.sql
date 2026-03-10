-- phpMyAdmin SQL Dump
-- Database: `craftconnect`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) UNIQUE DEFAULT NULL,
  `username_updated_at` TIMESTAMP NULL DEFAULT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('homeowner', 'craftsman', 'admin') DEFAULT 'homeowner',
  `phone_number` VARCHAR(20) NULL,
  `wilaya` VARCHAR(100) NULL,
  `profile_picture` VARCHAR(255) DEFAULT 'default.png',
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craftsmen_profiles`
--

CREATE TABLE IF NOT EXISTS `craftsmen_profiles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `service_category` VARCHAR(100) NOT NULL,
  `hourly_rate` DECIMAL(10, 2) NOT NULL,
  `is_verified` BOOLEAN DEFAULT FALSE,
  `is_published` BOOLEAN DEFAULT FALSE,
  `portfolio_images` JSON NULL,
  `latitude` DECIMAL(10, 8) NULL,
  `longitude` DECIMAL(11, 8) NULL,
  `bio` TEXT NULL,
  `json_metadata` JSON NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE IF NOT EXISTS `job_postings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `posted_by_user_id` INT NOT NULL,
  `service_category` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `address` TEXT NOT NULL,
  `budget_range` VARCHAR(100) NULL,
  `status` ENUM('open', 'assigned', 'completed', 'cancelled') DEFAULT 'open',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`posted_by_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_quotes`
--

CREATE TABLE IF NOT EXISTS `job_quotes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_posting_id` INT NOT NULL,
  `craftsman_id` INT NOT NULL,
  `quoted_price` DECIMAL(10, 2) NOT NULL,
  `cover_message` TEXT NULL,
  `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`craftsman_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests_bookings`
--

CREATE TABLE IF NOT EXISTS `requests_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `homeowner_id` INT NOT NULL,
  `craftsman_id` INT NOT NULL,
  `job_posting_id` INT NULL,
  `description` TEXT NOT NULL,
  `counter_description` TEXT NULL,
  `address` TEXT NOT NULL,
  `scheduled_date` DATETIME NOT NULL,
  `quoted_price` DECIMAL(10, 2) NULL,
  `counter_price` DECIMAL(10, 2) NULL,
  `counter_date` DATETIME NULL,
  `counter_note` TEXT NULL,
  `status` ENUM('requested', 'quoted', 'counter_offered', 'hired', 'in_progress', 'pending_completion', 'completed', 'cancelled') DEFAULT 'requested',
  `completion_date` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`homeowner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`craftsman_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `initiator_id` INT NOT NULL,
  `participant_id` INT NOT NULL,
  `status` ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE(`initiator_id`, `participant_id`),
  FOREIGN KEY (`initiator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`participant_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `conversation_id` INT NOT NULL,
  `sender_id` INT NOT NULL,
  `receiver_id` INT NOT NULL,
  `message_body` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL UNIQUE,
  `amount_due` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('draft', 'sent', 'paid', 'overdue') DEFAULT 'draft',
  `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `requests_bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `stripe_charge_id` VARCHAR(255) NULL,
  `amount_paid` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'succeeded', 'failed') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE IF NOT EXISTS `disputes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL UNIQUE,
  `raised_by_user_id` INT NOT NULL,
  `reason` TEXT NOT NULL,
  `status` ENUM('open', 'investigating', 'resolved_refund', 'resolved_payout') DEFAULT 'open',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `requests_bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`raised_by_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL UNIQUE,
  `homeowner_id` INT NOT NULL,
  `craftsman_id` INT NOT NULL,
  `star_rating` INT NOT NULL CHECK (`star_rating` >= 1 AND `star_rating` <= 5),
  `comment` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `requests_bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`homeowner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`craftsman_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `homeowner_id` INT NOT NULL,
  `craftsman_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(`homeowner_id`, `craftsman_id`),
  FOREIGN KEY (`homeowner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`craftsman_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(500) NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
