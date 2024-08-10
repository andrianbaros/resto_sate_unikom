-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2024 at 02:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resto_sate`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `stock`) VALUES
(1, 'Bawang Merah', '55'),
(2, 'Bawang Putih', '54'),
(3, 'Cabe', '5'),
(4, 'Daun jeruk', '5'),
(5, 'Garam', '5'),
(6, 'Kecap Manis', '5'),
(7, 'Asam Jawa', '5'),
(8, 'Lada', '5'),
(9, 'Saos Kacang', '5'),
(10, 'Kemiri', '5'),
(11, 'Gula Merah', '5'),
(12, 'Air Jeruk Nipis', '5'),
(13, 'Merica', '5'),
(14, 'Ketumbar', '5'),
(15, 'Bubuk Teh', '5'),
(16, 'Gula', '5'),
(17, 'Es', '5'),
(18, 'Lemon', '5'),
(19, 'Bubuk Green Tea', '5'),
(20, 'Creamer', '5'),
(21, 'Susu', '5'),
(22, 'Air', '5'),
(23, 'Bubuk Coklat', '5');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `price`) VALUES
(1, 'ayam goreng', 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `payment` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `total_price`, `payment`, `created_at`, `status`) VALUES
(3, 5, 111000, 0, '2024-08-10 06:53:46', 'paid'),
(4, 6, 111000, 0, '2024-08-10 06:58:39', 'paid'),
(5, 6, 111000, 0, '2024-08-10 06:58:54', 'paid'),
(6, 6, 111000, 0, '2024-08-10 06:59:10', 'paid'),
(7, 4, 166500, 0, '2024-08-10 06:59:15', 'paid'),
(8, 5, 166500, 0, '2024-08-10 06:59:28', 'paid'),
(9, 3, 166500, 0, '2024-08-10 07:04:52', 'paid'),
(11, 5, 388500, 0, '2024-08-10 07:06:57', 'paid'),
(12, 5, 388500, 0, '2024-08-10 07:42:33', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `recipe_id`, `quantity`, `price`) VALUES
(7, 3, 1, 1, 50000),
(8, 3, 4, 1, 50000),
(9, 4, 1, 1, 50000),
(10, 4, 4, 1, 50000),
(11, 5, 1, 1, 50000),
(12, 5, 4, 1, 50000),
(13, 6, 1, 1, 50000),
(14, 6, 4, 1, 50000),
(15, 7, 1, 1, 50000),
(16, 7, 3, 1, 50000),
(17, 7, 4, 1, 50000),
(18, 8, 1, 1, 50000),
(19, 8, 2, 1, 50000),
(20, 8, 5, 1, 50000),
(21, 9, 1, 1, 50000),
(22, 9, 2, 1, 50000),
(23, 9, 4, 1, 50000),
(24, 10, 1, 1, 50000),
(25, 10, 2, 1, 50000),
(26, 10, 4, 1, 50000),
(27, 11, 1, 5, 250000),
(28, 11, 5, 2, 100000),
(29, 12, 1, 5, 250000),
(30, 12, 5, 2, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `image`, `price`, `category`) VALUES
(1, 'Sate Padang', 'image/makanan/sate padang.png', 35000, 'makanan'),
(2, 'Sate Maranggi', 'image/makanan/sate maranggi.jpg', 30000, 'makanan'),
(3, 'Sate Madura', 'image/makanan/sate madura.png', 25000, 'makanan'),
(4, 'Sate Jando', 'image/makanan/sate jando.jpeg', 25000, 'makanan'),
(5, 'Sate Taichan', 'image/makanan/sate taichan.jpg', 30000, 'makanan'),
(6, 'Es Teh', 'image/makanan/es teh.jpg', 10000, 'minuman'),
(7, 'Lemon Tea', 'image/makanan/Lemon Tea.jpg', 10000, 'minuman'),
(8, 'Green Tea', 'image/makanan/Green Tea.jpeg', 15000, 'minuman'),
(9, 'Air Mineral', 'image/makanan/air mineral.png', 5000, 'minuman'),
(10, 'Es Coklat', 'image/makanan/es coklat.png', 15000, 'minuman');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `quantity`) VALUES
(1, 1, 25),
(1, 2, 25),
(1, 3, 10),
(1, 4, 2),
(1, 5, 3),
(2, 5, 4),
(2, 6, 25),
(2, 7, 15),
(2, 8, 4),
(3, 3, 10),
(3, 5, 5),
(3, 6, 15),
(3, 9, 25),
(3, 10, 5),
(3, 11, 10),
(4, 1, 5),
(4, 2, 8),
(4, 5, 5),
(4, 11, 5),
(4, 14, 5),
(5, 2, 10),
(5, 3, 10),
(5, 5, 5),
(5, 12, 15),
(5, 13, 5),
(6, 15, 15),
(6, 16, 5),
(6, 17, 10),
(7, 15, 10),
(7, 16, 5),
(7, 18, 15),
(8, 19, 15),
(8, 20, 5),
(8, 21, 10),
(8, 22, 10),
(9, 22, 10),
(10, 20, 5),
(10, 21, 20),
(10, 23, 15);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `capacity`, `status`) VALUES
(1, 1, 4, 'occupied'),
(2, 2, 2, 'occupied'),
(3, 3, 7, 'ready'),
(4, 4, 3, 'ready'),
(5, 5, 8, 'ready'),
(6, 6, 2, 'ready');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('chef','waiter','cashier','admin','manager') NOT NULL,
  `picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `picture`) VALUES
('CA00001', 'kasir', '$2y$10$C6c1fvbfmBQox7PKgYaJXO29W4JTg7EY2eqYWxdPTjRRuOhvos1pG', 'cashier', 'image/Screenshot 2024-07-25 204512.png'),
('CA00002', 'cashier', '$2y$10$dSnmrYDqVHeGEuIAFDQDMObL5cYkWSGUvbpWgQ0vSuEpogydJEeN.', 'cashier', 'image/Gemini_Generated_Image_ui8bdsui8bdsui8b.jpeg'),
('CH00001', 'chef', '$2y$10$lwb8H8JzIU0mzvEp/iMBoeZhKJQfl5jVBGPYLvIvECNLFgGs/J4Cm', 'chef', 'image/icon-32.png'),
('CH00002', 'chefbaru', '$2y$10$91nyywnHQu1Uxci5uSbO5OfzAKgkQ3/vvnGSCt4HFRG4BCJHaWYEe', 'chef', 'image/Screenshot 2024-07-09 222951.png'),
('MA00001', 'baros', '$2y$10$tgHyDwonM1l.RipkovM4O.FdRfH3Pd56xndWRB.tMJlEDtBF.0L8a', 'manager', 'image/user-img.jpeg'),
('MA00002', 'bambang', '$2y$10$zzGUed1EfC/.8jVS3tPCHO3OxN9PfiEYLXO6WVR1zV3vUpZFhoFPW', 'manager', 'image/006cg0eegy1hqjvthp4u6j30u0140dos.jpg'),
('MA00003', 'daapinn', '$2y$10$Ds2Fzn1OMlkd8wRWCoCaiePoS6Ey5UmjQm6Ba5bfLMLVHjNwTVF7O', 'manager', 'image/776a12a0bcfb38d10894b4d8c79e777c.jpg'),
('WA00001', 'waiter', '$2y$10$CFw2nds/QInas2VtfCU3teONrt5DH5pa1HUHagehvEv2t6GPPHq/y', 'waiter', 'image/e4a609b5572c40400fc5784d18a7eb78.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`),
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
