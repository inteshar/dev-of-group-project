-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2024 at 07:55 AM
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
-- Database: `development-of-group`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_account`
--

CREATE TABLE `loan_account` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_number` varchar(16) NOT NULL,
  `loan_amount` varchar(10) NOT NULL,
  `approval_amount` varchar(10) NOT NULL,
  `plan` varchar(8) NOT NULL,
  `emi` varchar(10) NOT NULL,
  `last_payment` varchar(12) NOT NULL DEFAULT '-',
  `last_paid_amount` varchar(20) NOT NULL DEFAULT '0',
  `total_paid` varchar(20) NOT NULL DEFAULT '0',
  `next_payment` varchar(15) NOT NULL DEFAULT '-',
  `remaining_payment` varchar(20) NOT NULL DEFAULT 'null',
  `status` varchar(5) NOT NULL,
  `account_opened_on` varchar(12) NOT NULL DEFAULT 'Not Approved',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_account`
--

INSERT INTO `loan_account` (`id`, `member_id`, `account_number`, `loan_amount`, `approval_amount`, `plan`, `emi`, `last_payment`, `last_paid_amount`, `total_paid`, `next_payment`, `remaining_payment`, `status`, `account_opened_on`, `request_date`) VALUES
(39, 252660, 'LN05095781', '487546', '480000', '365', '1335.742', '2024-05-25', '1335.742', '1335.742', '2024-05-27', '486210.258', '1', '2024-05-26', '2024-05-25 19:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(100) NOT NULL,
  `device` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `email`, `datetime`, `ip_address`, `device`) VALUES
(182, 'admin@mail.com', '2024-05-25 19:21:34', '::1', 'Android Phone'),
(183, 'admin@mail.com', '2024-05-25 19:29:03', '::1', 'Android Phone'),
(184, 'admin@mail.com', '2024-05-25 19:39:18', '::1', 'Android Phone'),
(185, 'admin@mail.com', '2024-05-25 19:49:36', '::1', 'Android Phone');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `dob` varchar(10) DEFAULT NULL,
  `kyc_status` int(2) DEFAULT NULL,
  `address_perm` varchar(500) DEFAULT NULL,
  `address_temp` varchar(500) DEFAULT NULL,
  `relative` varchar(1000) NOT NULL,
  `relation` varchar(1000) NOT NULL,
  `ref_name` varchar(1000) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `photo`, `mobile`, `email`, `dob`, `kyc_status`, `address_perm`, `address_temp`, `relative`, `relation`, `ref_name`, `date_created`) VALUES
('252660', 'Test Gurung', '.trashed-1719183707-loan_details.png', '68568', 'test@gmail.com', '2024-05-24', 1, 'Jdkdf', 'Jzkff', 'jzkdf', 'Husband', 'heiff', '2024-05-25 19:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `members_kyc`
--

CREATE TABLE `members_kyc` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `aadhaar_card` varchar(1000) NOT NULL,
  `pan_card` varchar(1000) NOT NULL,
  `signature` varchar(1000) NOT NULL,
  `ref_aadhaar` varchar(1000) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members_kyc`
--

INSERT INTO `members_kyc` (`id`, `member_id`, `aadhaar_card`, `pan_card`, `signature`, `ref_aadhaar`, `date_created`) VALUES
(25, 252660, '../../Admin/MembersFiles/Test Gurung/report_1716491775420.pdf', '../../Admin/MembersFiles/Test Gurung/National Identity Card.pdf', '../../Admin/MembersFiles/Test Gurung/Sama Sheikh TC Evergreen Academy.pdf', '../../Admin/MembersFiles/Test Gurung/samajik-1.pdf', '2024-05-25 19:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payments_id` int(11) NOT NULL,
  `member_id` varchar(20) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `staff` varchar(50) NOT NULL,
  `date` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payments_id`, `member_id`, `amount`, `staff`, `date`) VALUES
(50, '252660', '16.378', '', '2024-05-25'),
(51, '252660', '1335.742', 'admin@mail.com', '2024-05-25');

-- --------------------------------------------------------

--
-- Table structure for table `savings_account`
--

CREATE TABLE `savings_account` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_number` varchar(16) NOT NULL,
  `interest_rate` varchar(4) NOT NULL,
  `account_bal` varchar(16) NOT NULL,
  `last_transaction_amount` varchar(20) NOT NULL,
  `last_transaction_type` varchar(20) NOT NULL,
  `last_transaction_date` varchar(16) NOT NULL,
  `account_opened_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings_statement`
--

CREATE TABLE `savings_statement` (
  `id` int(11) NOT NULL,
  `account_number` varchar(16) NOT NULL,
  `purpose` varchar(200) NOT NULL,
  `trans_id` varchar(13) NOT NULL,
  `withdrawal` varchar(16) NOT NULL DEFAULT '--',
  `deposit` varchar(16) NOT NULL DEFAULT '--',
  `transaction_date` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(6) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `email`, `address`, `password`, `role`, `date_joined`) VALUES
(21, 'admin', '00000000', 'admin@mail.com', 'admin', 'admin', 'admin', '2023-12-20 12:08:17'),
(27, 'staff', '687686', 'staff@mail.com', 'kdsjfkh', '123', 'staff', '2024-05-25 11:48:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loan_account`
--
ALTER TABLE `loan_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`,`email`);

--
-- Indexes for table `members_kyc`
--
ALTER TABLE `members_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payments_id`);

--
-- Indexes for table `savings_account`
--
ALTER TABLE `savings_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD UNIQUE KEY `member_id` (`member_id`);

--
-- Indexes for table `savings_statement`
--
ALTER TABLE `savings_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loan_account`
--
ALTER TABLE `loan_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `members_kyc`
--
ALTER TABLE `members_kyc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `savings_account`
--
ALTER TABLE `savings_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `savings_statement`
--
ALTER TABLE `savings_statement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
