-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2020 at 08:19 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wyse_erp_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `nic` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone_1` varchar(20) DEFAULT NULL,
  `telephone_2` varchar(20) DEFAULT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `epf_number` varchar(150) DEFAULT NULL,
  `etf_number` varchar(150) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `designation`, `first_name`, `last_name`, `nic`, `email`, `telephone_1`, `telephone_2`, `address_line_1`, `address_line_2`, `fax`, `city`, `zip_code`, `epf_number`, `etf_number`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'EMP-0000000001', NULL, 'dsf', 'wrwer', 'wqeqe', 'tharindu@wysheit.com', '412', '12313', 'qweq', 'qeqe', NULL, 'qeqe', 'qeqe', 'qeqe', 'qwe', NULL, '2020-12-15 03:51:51', '2020-12-15 03:51:51', NULL),
(3, 'EMP-0000000002', NULL, 'Kasun', 'jayasekara', '911161761v', 'dilipkasuntha123@gmail.com', '0774155244', '0774155244', '824, Unagalawehara', '', NULL, 'polonnaruwa', '51000', '534345', '54654', NULL, '2020-12-25 12:57:42', '2020-12-25 12:57:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `unit_price` float DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `category_code` varchar(50) NOT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `parent_item` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `item_name`, `unit_price`, `item_description`, `category_code`, `is_active`, `division`, `parent_item`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'ITM-0000000001', 'iPhone 11', 170000, 'Apple iPhone 11', 'CAT-0000000002', 1, NULL, 0, '2020-12-15 13:17:44', '2020-12-15 13:17:44', NULL),
(3, 'ITM-0000000002', 'iPhone 12', 210000, '', 'CAT-0000000002', 1, NULL, 0, '2020-12-16 03:43:11', '2020-12-16 03:43:11', NULL),
(6, 'ITM-0000000003', 'Coca cola 350ml', 70, 'dczvdf', 'CAT-0000000001', 1, 'food', 0, '2020-12-28 12:16:20', '2020-12-28 12:28:36', NULL),
(7, 'ITM-0000000004', 'Coca cola 500ml', 300, 'sfdfgdg', 'CAT-0000000003', 1, 'food', 6, '2020-12-28 12:30:36', '2020-12-28 12:30:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL,
  `category_code` varchar(50) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `is_active` varchar(4) NOT NULL,
  `parent_categories` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `category_code`, `category_name`, `is_active`, `parent_categories`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CAT-0000000001', 'CAT 1', 'on', 0, '2020-12-15 02:24:08', '2020-12-15 11:49:25', NULL),
(2, 'CAT-0000000002', 'CAT 2', 'on', 0, '2020-12-15 11:32:14', '2020-12-15 11:49:32', NULL),
(3, 'CAT-0000000003', 'CAT 3', 'on', 0, '2020-12-15 12:58:17', '2020-12-15 12:58:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_serial_numbers`
--

CREATE TABLE `item_serial_numbers` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `serial_no` varchar(50) NOT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_serial_numbers`
--

INSERT INTO `item_serial_numbers` (`id`, `item_id`, `serial_no`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, '1234', 1, '2020-12-28 12:16:20', '2020-12-28 12:26:01', '2020-12-28 12:26:01'),
(2, 7, '12345', 1, '2020-12-28 12:30:36', '2020-12-28 12:30:36', NULL),
(3, 7, '54321', 1, '2020-12-28 12:30:36', '2020-12-28 12:30:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_code` (`item_code`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_serial_numbers`
--
ALTER TABLE `item_serial_numbers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `item_serial_numbers`
--
ALTER TABLE `item_serial_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
