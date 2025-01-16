-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 09:57 PM
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
-- Database: `webshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `stock`, `category`, `description`) VALUES
(2, 'EcoGarden Pro Trowel', 69.69, 1336, 'Geen idee', 'Something'),
(3, 'SmartChef Digital Air Fryer', 49.99, 69, 'Kitchen Appliances', 'Make cooking easy and fun with this handy device.'),
(4, 'Mystery of the Moonlit Manor', 9.99, 200, 'Books', 'An engaging book you won\'t be able to put down.'),
(5, 'UltraFlex Pro Tennis Racket', 79.99, 30, 'Sports Equipment', 'Improve your sports performance with this advanced product.'),
(6, 'GreenThumb Retractable Pruner', 39.99, 80, 'Garden Supplies', 'Keep your garden neat and beautiful with this handy tool.'),
(7, 'Radiant Glow Facial Serum', 59.99, 58, 'Beauty and Care', 'Treat yourself with this luxury beauty product.'),
(8, 'BrainBuilder STEM Construction Set', 14.99, 150, 'Toys', 'Fun and educational toys for children.'),
(9, 'PowerClean Robot Vacuum 2000', 69.99, 40, 'Household Appliances', 'Make housework easier with this handy appliance.'),
(10, 'LightCraft Pro DSLR Camera', 24.99, 90, 'Photography', 'Capture unforgettable moments with this high-quality camera.');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `stars` int(11) NOT NULL,
  `comment` text NOT NULL,
  `username` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`stars`, `comment`, `username`) VALUES
(6, 'dfgdfg', '123'),
(6, 'dfgdfg', '123'),
(6, 'dfgdfg', '123'),
(4, 'joooooo', '123'),
(4, 'joooooo', '123'),
(4, 'fdgdfgdfgdfg', '123'),
(4, 'afsdfsdf', '321');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `firstname`, `lastname`, `email`, `password`, `is_admin`) VALUES
(27, 'testuser1', 'Test', 'User', '@', '$2y$10$HMVShggVIh7OvNXePFhFKu4zdNQFbO5Z4wc3VT4E.r4n1/jp7.X8G', 0),
(28, '123', '123', '123@123', '123', '$2y$10$Yom6ujWK7GNBRzcRvRKVo.jQNnQrdpdibZTmYiDyMAgDXm58NBEly', 0),
(29, '321', '321', '321@321', '321', '$2y$10$N0WXtCvX4hjexe91xPe6y.aMtbEtnkts6c02P/gY2QJGMGleXEpBq', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
