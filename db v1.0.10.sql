-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2024 at 06:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `euro_float`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_role` int DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `OTP` varchar(255) DEFAULT NULL,
  `OTP_Timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `username`, `password`, `user_role`, `fullname`, `email_id`, `phone_no`, `OTP`, `OTP_Timestamp`) VALUES
(1, 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 'admin', 'revanthshiva3@gmail.com', '+917010538733', '145404', '2024-06-07 19:00:43'),
(5, 'production@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 'Production  Account Demo', 'production@gmail.com', '+917010538733', NULL, NULL),
(6, 'sales@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 3, 'Sales Account Demo', 'sales@gmail.com', '+917010538733', NULL, NULL),
(7, 'purchase@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 4, 'Purchase Account Demo', 'purchase@gmail.com', '+917010538733', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_ph` text,
  `customer_address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_complaint`
--

CREATE TABLE `customer_complaint` (
  `sales_id` int NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_contact` varchar(255) DEFAULT NULL,
  `vin_number` text,
  `model_name` varchar(255) DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `customer_complaint` varchar(255) DEFAULT NULL,
  `complaint_severity` varchar(255) DEFAULT NULL,
  `complaint_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_complaint`
--

INSERT INTO `customer_complaint` (`sales_id`, `customer_name`, `customer_contact`, `vin_number`, `model_name`, `unit`, `date`, `time`, `customer_complaint`, `complaint_severity`, `complaint_status`) VALUES
(1, 'Revanth Shiva PS', '987654321', '111111', NULL, NULL, '2024-06-07', '07:35:10', 'breaking failure ', 'High Severity', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `final_qc`
--

CREATE TABLE `final_qc` (
  `productionId` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `checkpoint` varchar(255) DEFAULT NULL,
  `instruction` varchar(255) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finished_costing`
--

CREATE TABLE `finished_costing` (
  `vin_number` int NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `cost` int NOT NULL,
  `costing_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_purchase`
--

CREATE TABLE `live_purchase` (
  `purchase_id` int NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `warehouse_suppliers` text,
  `vin_number` text,
  `supplier_status` varchar(255) DEFAULT NULL,
  `purchase_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `components` text,
  `purchase_timestamp` timestamp NULL DEFAULT NULL,
  `folder_id` varchar(255) DEFAULT NULL,
  `tracking_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Not  Available',
  `tracking_service` text,
  `adr_qms` varchar(300) DEFAULT NULL,
  `final_qms` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `live_purchase`
--

INSERT INTO `live_purchase` (`purchase_id`, `unit`, `subject`, `content`, `warehouse_suppliers`, `vin_number`, `supplier_status`, `purchase_status`, `customer`, `components`, `purchase_timestamp`, `folder_id`, `tracking_id`, `tracking_service`, `adr_qms`, `final_qms`) VALUES
(1, '555.A', 'Test', 'content ', '1', NULL, 'Complete', 'Completed', NULL, NULL, '2024-06-04 06:14:59', '1p7EhCDGtdETjyxo9sw5xs68FvHsoQ4ZO', NULL, NULL, NULL, NULL),
(2, '555.B', 'Test', 'content ', '1', NULL, 'Complete', 'Received From Supplier', NULL, NULL, '2024-06-04 06:14:59', '1Mbk0CYFSThyrjQdwCyI7E0N0roAR5qVD', '11111', 'ddddd', 'Complete', 'Complete'),
(3, '555.C', 'Test', 'content ', '1', NULL, 'Complete', 'Completed', NULL, NULL, '2024-06-04 06:14:59', '1hL6B-5g9w0yotYw052LvwevwgPn7ebi1', NULL, NULL, NULL, NULL),
(4, '888.A', 'Demo 1', 'Please login E-car Software for order details', '1', NULL, NULL, 'Sent To Supplier', NULL, NULL, '2024-06-07 13:34:57', '1hPpf_B_hpO0rLBEY67rYvEkGshitTDvd', 'Not  Available', NULL, NULL, NULL),
(5, '888.B', 'Demo 1', 'Please login E-car Software for order details', '1', NULL, NULL, 'Sent To Supplier', NULL, NULL, '2024-06-07 13:34:57', '1TXsPnZb-Y0sBeyRomzTwGsEiFWTFczrp', 'Not  Available', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

CREATE TABLE `production` (
  `production_id` int NOT NULL,
  `vin_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `unit` varchar(255) NOT NULL,
  `process1` varchar(255) DEFAULT NULL,
  `process2` varchar(255) DEFAULT NULL,
  `process3` varchar(255) DEFAULT NULL,
  `process4` varchar(255) DEFAULT NULL,
  `process1_status` varchar(255) DEFAULT NULL,
  `process2_status` varchar(255) DEFAULT NULL,
  `process3_status` varchar(255) DEFAULT NULL,
  `process4_status` varchar(255) DEFAULT NULL,
  `QC_Status` varchar(255) DEFAULT NULL,
  `sold` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `note1` text,
  `note2` text,
  `note3` text,
  `note4` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `production`
--

INSERT INTO `production` (`production_id`, `vin_number`, `unit`, `process1`, `process2`, `process3`, `process4`, `process1_status`, `process2_status`, `process3_status`, `process4_status`, `QC_Status`, `sold`, `note1`, `note2`, `note3`, `note4`) VALUES
(1, '111111', '555.A', '5', '5', '5', NULL, 'Active', 'Active', 'Active', NULL, NULL, NULL, 'bbbbsssss', 'qqqq', 'wwwww', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` int NOT NULL,
  `production_id` varchar(255) NOT NULL,
  `vin_number` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `job` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `vendor_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sales_id` int NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `production_id` int DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `vin_number` text,
  `customer_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `customer_complaint` varchar(255) DEFAULT NULL,
  `complaint_severity` varchar(255) DEFAULT NULL,
  `purchase_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sales_id`, `customer_name`, `production_id`, `unit`, `vin_number`, `customer_contact`, `date`, `time`, `customer_complaint`, `complaint_severity`, `purchase_timestamp`) VALUES
(1, 'Revanth Shiva PS', 1, '555.A', '111111', '987654321', '2024-06-07', NULL, 'breaking failure ', 'High Severity', '2024-06-04 06:14:59'),
(2, NULL, 2, '555.B', NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-04 06:14:59'),
(3, NULL, 3, '555.C', NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-04 06:14:59'),
(4, NULL, 4, '888.A', NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 13:34:57'),
(5, NULL, 5, '888.B', NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 13:34:57');

-- --------------------------------------------------------

--
-- Table structure for table `sold`
--

CREATE TABLE `sold` (
  `purchase_id` int DEFAULT NULL,
  `vin_number` int DEFAULT NULL,
  `model_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sold` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplierrecords`
--

CREATE TABLE `supplierrecords` (
  `unit` varchar(255) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `production_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplierrecords`
--

INSERT INTO `supplierrecords` (`unit`, `timestamp`, `supplier_id`, `production_id`) VALUES
('555.B', '2024-06-07 12:19:21', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type_role` varchar(255) NOT NULL,
  `OTP` varchar(255) DEFAULT NULL,
  `OTP_Timestamp` timestamp NULL DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `fullname`, `contact_number`, `email_id`, `username`, `password`, `type_role`, `OTP`, `OTP_Timestamp`, `address`) VALUES
(1, 'Shandong', '07010538733', 'revanthshiva3@gmail.com', 'test@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 'suppliers', '384840', '2024-06-07 15:16:09', '5/403 Mannar Thirumalai Street,'),
(2, 'Supplier B', '1234567890', 'revanthshiva3@gmail.com', 'supplierB', '40be4e59b9a2a2b5dffb918c0e86b3d7', 'suppliers', '878899', '2024-05-13 06:36:00', 'aaaaaaaaaaaaaaaaa'),
(4, 'Supplier 3', '07010538733', 'revanthshiva3@gmail.com', 'revanthshiva3@gmail.com', '40be4e59b9a2a2b5dffb918c0e86b3d7', 'suppliers', NULL, NULL, '5/403 Mannar Thirumalai Street,\r\nAnananagar'),
(5, '5/403 Mannar Thirumalai Street,', '07010538733', 'revanthshiva3@gmail.com', 'revanthshiva3@gmail.com', '40be4e59b9a2a2b5dffb918c0e86b3d7', 'suppliers', NULL, NULL, '5/403 Mannar Thirumalai Street,\r\nAnananagar');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int NOT NULL,
  `fullname` text,
  `contact_number` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `account_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `OTP` varchar(255) DEFAULT NULL,
  `OTP_Timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `fullname`, `contact_number`, `email_id`, `username`, `password`, `account_type`, `OTP`, `OTP_Timestamp`) VALUES
(5, 'arun', '+917010538733', 'revanthshiva3@gmail.com', 'arun@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 'vendor', '550701', '2024-06-08 07:11:28'),
(7, 'Hari', '+917010538733', 'revanthshiva3@gmail.com', 'hari@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 'vendor', '191599', '2024-06-08 07:10:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_complaint`
--
ALTER TABLE `customer_complaint`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `live_purchase`
--
ALTER TABLE `live_purchase`
  ADD PRIMARY KEY (`purchase_id`) USING BTREE;

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`production_id`,`unit`) USING BTREE;

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `supplierrecords`
--
ALTER TABLE `supplierrecords`
  ADD PRIMARY KEY (`production_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_complaint`
--
ALTER TABLE `customer_complaint`
  MODIFY `sales_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_purchase`
--
ALTER TABLE `live_purchase`
  MODIFY `purchase_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplierrecords`
--
ALTER TABLE `supplierrecords`
  MODIFY `production_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
