-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2022 at 05:32 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrobid`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_qty` varchar(25) NOT NULL,
  `buyer_id` int(10) NOT NULL,
  `buyer_image` varchar(255) NOT NULL,
  `buyer_name` varchar(50) NOT NULL,
  `buyer_mobileNo` bigint(11) NOT NULL,
  `buyer_email` varchar(25) NOT NULL,
  `buyer_address` varchar(50) NOT NULL,
  `buyer_city` varchar(20) NOT NULL,
  `buyer_state` varchar(20) NOT NULL,
  `base_amount` int(11) NOT NULL,
  `biding_amount` int(8) NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(5) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_qty` varchar(30) NOT NULL,
  `initial_price` int(7) NOT NULL,
  `product_description` text NOT NULL,
  `posted_on` date NOT NULL,
  `end_date` date NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `seller_id` int(5) NOT NULL,
  `seller_state` varchar(16) NOT NULL,
  `seller_city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `mobile_no` bigint(11) NOT NULL,
  `email_id` varchar(30) NOT NULL,
  `address_street` varchar(50) DEFAULT NULL,
  `address_city` varchar(50) DEFAULT NULL,
  `address_state` varchar(20) DEFAULT NULL,
  `address_zip` int(16) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_profileImg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_name` (`product_name`,`product_description`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_description` (`product_description`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_name_2` (`product_name`,`product_description`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mobileNo` (`mobile_no`),
  ADD UNIQUE KEY `emailId` (`email_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
