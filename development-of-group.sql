-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 27, 2024 at 05:20 PM
-- Server version: 8.0.18
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `development-of-group`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_account`
--

DROP TABLE IF EXISTS `loan_account`;
CREATE TABLE IF NOT EXISTS `loan_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `account_number` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `loan_amount` varchar(10) NOT NULL,
  `approval_amount` varchar(10) NOT NULL,
  `plan` varchar(8) NOT NULL,
  `emi` varchar(10) NOT NULL,
  `last_payment` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '-',
  `last_paid_amount` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `total_paid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `next_payment` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '-',
  `remaining_payment` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'null',
  `status` varchar(5) NOT NULL,
  `account_opened_on` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Not Approved',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  UNIQUE KEY `account_number` (`account_number`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loan_account`
--

INSERT INTO `loan_account` (`id`, `member_id`, `account_number`, `loan_amount`, `approval_amount`, `plan`, `emi`, `last_payment`, `last_paid_amount`, `total_paid`, `next_payment`, `remaining_payment`, `status`, `account_opened_on`, `request_date`) VALUES
(28, 21, 'LN05119919', '4535', '324', '110', '38.282', '-', '0', '0', '2024-03-13', 'null', '1', '2024-03-12', '2024-03-02 09:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(100) NOT NULL,
  `device` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `email`, `datetime`, `ip_address`, `device`) VALUES
(134, 'admin@mail.com', '2024-03-14 13:41:12', '::1', 'Windows PC'),
(135, 'admin@mail.com', '2024-03-14 13:51:55', '::1', 'Windows PC'),
(136, 'admin@mail.com', '2024-04-27 17:18:51', '::1', 'Windows PC');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `photo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mobile` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dob` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `kyc_status` int(2) DEFAULT NULL,
  `address_perm` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address_temp` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `relative` varchar(1000) NOT NULL,
  `relation` varchar(1000) NOT NULL,
  `ref_name` varchar(1000) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`,`email`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `photo`, `mobile`, `email`, `dob`, `kyc_status`, `address_perm`, `address_temp`, `relative`, `relation`, `ref_name`, `date_created`) VALUES
('21', 'Madan Kumar Thakur', 'WhatsApp Image 2022-06-06 at 9.07.26 PM.jpeg', '455446554', 'madan@mail.com', '1999-07-15', 1, 'Samastipur', 'Samastipur', 'Raj Kumar Thakur', 'Father', 'Raj Kumar Thakur', '2023-12-31 11:28:35'),
('481132', 'Mohammad Inteshar Alam', 'Inteshar Logo Square Black.png', '456465', 'inteshar@mail.com', '2024-02-13', 1, 'fdghtrht', 'htrhteh', 'Mohammed Ansar', 'Father', 'Mohammed Ansar', '2024-02-15 07:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `members_kyc`
--

DROP TABLE IF EXISTS `members_kyc`;
CREATE TABLE IF NOT EXISTS `members_kyc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `aadhaar_card` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pan_card` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `signature` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ref_aadhaar` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `members_kyc`
--

INSERT INTO `members_kyc` (`id`, `member_id`, `aadhaar_card`, `pan_card`, `signature`, `ref_aadhaar`, `date_created`) VALUES
(15, 21, '../../Admin/MembersFiles/Madan Kumar Thakur/WhatsApp Image 2022-06-06 at 9.10.49 PM.jpeg', '../../Admin/MembersFiles/Madan Kumar Thakur/pancard.jpeg', '../../Admin/MembersFiles/Madan Kumar Thakur/WhatsApp Image 2023-09-24 at 12.30.30.jpeg', '../../Admin/MembersFiles/Madan Kumar Thakur/pancard.jpeg', '2023-12-31 11:31:47'),
(23, 481132, '../../Admin/MembersFiles/Mohammad Inteshar Alam/National Identity Card.pdf', '../../Admin/MembersFiles/Mohammad Inteshar Alam/Mohammad Inteshar Alam Passport.pdf', '../../Admin/MembersFiles/Mohammad Inteshar Alam/CitizenshipCard.pdf', '../../Admin/MembersFiles/Mohammad Inteshar Alam/Transcripts from 10th to UG.pdf', '2024-02-15 11:21:52');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings_account`
--

DROP TABLE IF EXISTS `savings_account`;
CREATE TABLE IF NOT EXISTS `savings_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `account_number` varchar(16) NOT NULL,
  `interest_rate` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `account_bal` varchar(16) NOT NULL,
  `last_transaction_amount` varchar(20) NOT NULL,
  `last_transaction_type` varchar(20) NOT NULL,
  `last_transaction_date` varchar(16) NOT NULL,
  `account_opened_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `savings_account`
--

INSERT INTO `savings_account` (`id`, `member_id`, `account_number`, `interest_rate`, `account_bal`, `last_transaction_amount`, `last_transaction_type`, `last_transaction_date`, `account_opened_on`) VALUES
(26, 21, 'SV08570180', '4.9', '0', '5454515', 'Withdrawal', '2024-02-17', '2024-02-17 11:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `savings_statement`
--

DROP TABLE IF EXISTS `savings_statement`;
CREATE TABLE IF NOT EXISTS `savings_statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(16) NOT NULL,
  `purpose` varchar(200) NOT NULL,
  `trans_id` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `withdrawal` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '--',
  `deposit` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '--',
  `transaction_date` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `savings_statement`
--

INSERT INTO `savings_statement` (`id`, `account_number`, `purpose`, `trans_id`, `withdrawal`, `deposit`, `transaction_date`) VALUES
(81, 'SV08570180', 'personal use', 'OG3RAY2DNH79', '5454515', '--', '2024-02-17'),
(79, 'SV08570180', 'personal use', 'GZHF601029ML', '--', '5454515', '2024-02-17'),
(80, 'SV08570180', 'personal use', '8RHNVVPENH7E', '5454515', '--', '2024-02-17'),
(78, 'SV08570180', 'family mentanance', '0R0VXB779IRX', '--', '5454515', '2024-02-17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(6) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `email`, `address`, `password`, `role`, `date_joined`) VALUES
(21, 'admin', '00000000', 'admin@mail.com', 'admin', 'admin', 'admin', '2023-12-20 12:08:17'),
(22, 'Manish Kumar Yadav', '9102531527', 'developementofgroup@gmail.com', 'Samastipur', 'Deve12345@', 'staff', '2023-12-23 04:45:42'),
(25, 'staff', '00000', 'staff@mail.com', 'staff', 'staff', 'staff', '2024-02-11 10:29:48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
