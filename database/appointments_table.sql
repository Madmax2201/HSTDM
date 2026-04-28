-- ============================================================
-- APPOINTMENT BOOKING TABLE
-- Run this in phpMyAdmin or via MySQL CLI to add appointment support
-- Database: _sms
-- ============================================================

USE `_sms`;

-- --------------------------------------------------------
-- Table structure for `appointments`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `appointments` (
  `id`             INT(11) NOT NULL AUTO_INCREMENT,
  `full_name`      VARCHAR(200) NOT NULL,
  `email`          VARCHAR(256) NOT NULL,
  `phone`          VARCHAR(30) NOT NULL,
  `reason`         VARCHAR(200) NOT NULL,
  `preferred_date` DATE NOT NULL,
  `message`        TEXT DEFAULT NULL,
  `status`         ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `submitted_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Optional: seed a sample appointment for testing
-- ============================================================
-- INSERT INTO `appointments` (`full_name`, `email`, `phone`, `reason`, `preferred_date`, `message`, `status`)
-- VALUES ('Ahmed Benali', 'ahmed@example.com', '+213 555 123 456', 'Inscription', '2026-03-20', 'Je souhaite en savoir plus sur les conditions d\'admission.', 'pending');
