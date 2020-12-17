-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2020 at 04:51 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `nic` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone_1` varchar(20) DEFAULT NULL,
  `telephone_2` varchar(20) DEFAULT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
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

INSERT INTO `employees` (`id`, `employee_code`, `first_name`, `last_name`, `nic`, `email`, `telephone_1`, `telephone_2`, `address_line_1`, `address_line_2`, `city`, `zip_code`, `epf_number`, `etf_number`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'EMP-0000000001', 'dsf', 'wrwer', 'wqeqe', 'tharindu@wysheit.com', '412', '12313', 'qweq', 'qeqe', 'qeqe', 'qeqe', 'qeqe', 'qwe', NULL, '2020-12-15 03:51:51', '2020-12-15 03:51:51', NULL);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `item_name`, `unit_price`, `item_description`, `category_code`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'ITM-0000000001', 'iPhone 11', 170000, 'Apple iPhone 11', 'CAT-0000000002', NULL, '2020-12-15 13:17:44', '2020-12-15 13:17:44', NULL),
(3, 'ITM-0000000002', 'iPhone 12', 210000, '', 'CAT-0000000002', NULL, '2020-12-16 03:43:11', '2020-12-16 03:43:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL,
  `category_code` varchar(50) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `is_active` varchar(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `category_code`, `category_name`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CAT-0000000001', 'CAT 1', 'on', '2020-12-15 02:24:08', '2020-12-15 11:49:25', NULL),
(2, 'CAT-0000000002', 'CAT 2', 'on', '2020-12-15 11:32:14', '2020-12-15 11:49:32', NULL),
(3, 'CAT-0000000003', 'CAT 3', 'on', '2020-12-15 12:58:17', '2020-12-15 12:58:17', NULL);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
