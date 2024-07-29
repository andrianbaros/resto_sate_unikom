-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for resto_sate
CREATE DATABASE IF NOT EXISTS `resto_sate` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `resto_sate`;

-- Dumping structure for table resto_sate.ingredients
CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `stock` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.ingredients: ~7 rows (approximately)
INSERT INTO `ingredients` (`id`, `name`, `stock`) VALUES
	(1, 'kol', '5'),
	(2, 'cabe', '20'),
	(3, 'tahu', ''),
	(23, 'garlic', ''),
	(24, 'Nasi', '10'),
	(25, 'Ayam', '2'),
	(26, 'Cabe', '5');

-- Dumping structure for table resto_sate.menu_items
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.menu_items: ~1 rows (approximately)
INSERT INTO `menu_items` (`id`, `name`, `price`) VALUES
	(1, 'ayam goreng', 20000.00);

-- Dumping structure for table resto_sate.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','in-progress','completed') DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.orders: ~2 rows (approximately)
INSERT INTO `orders` (`id`, `table_id`, `created_at`, `status`) VALUES
	(1, 1, '2024-07-29 04:28:02', 'pending'),
	(2, 0, '2024-07-29 04:30:25', 'pending');

-- Dumping structure for table resto_sate.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `menu_item_id` (`menu_item_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.order_items: ~0 rows (approximately)

-- Dumping structure for table resto_sate.recipes
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.recipes: ~6 rows (approximately)
INSERT INTO `recipes` (`id`, `name`, `image`, `price`) VALUES
	(1, 'Nasi Padang', 'images/nasipadang.png', 6000),
	(2, 'Ayam Goreng', 'images/ayamgoreng.png', 6000),
	(3, 'Ayam Goreng', 'images/ayamgoreng.png', 5600),
	(4, 'Ayam Goreng', 'images/ayamgoreng.png', 60),
	(5, 'Ayam Goreng', 'images/ayamgoreng.png', 60),
	(6, 'Ayam Goreng', 'images/ayamgoreng.png', 60);

-- Dumping structure for table resto_sate.recipe_ingredients
CREATE TABLE IF NOT EXISTS `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`recipe_id`,`ingredient_id`),
  KEY `ingredient_id` (`ingredient_id`),
  CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`),
  CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.recipe_ingredients: ~4 rows (approximately)
INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `quantity`) VALUES
	(1, 1, 3),
	(1, 2, 58),
	(1, 3, 1),
	(2, 26, 1);

-- Dumping structure for table resto_sate.tables
CREATE TABLE IF NOT EXISTS `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_number` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.tables: ~2 rows (approximately)
INSERT INTO `tables` (`id`, `table_number`, `capacity`, `status`) VALUES
	(1, 1, 4, 'not-ready'),
	(2, 2, 2, 'ready');

-- Dumping structure for table resto_sate.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('chef','waiter','cashier','admin','manager') NOT NULL,
  `picture` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `role`, `picture`) VALUES
	('CH00001', 'chef', '$2y$10$lwb8H8JzIU0mzvEp/iMBoeZhKJQfl5jVBGPYLvIvECNLFgGs/J4Cm', 'chef', ''),
	('MA00001', 'baros', '$2y$10$tgHyDwonM1l.RipkovM4O.FdRfH3Pd56xndWRB.tMJlEDtBF.0L8a', 'manager', 'image/user-img.jpeg'),
	('MA00002', 'bambang', '$2y$10$zzGUed1EfC/.8jVS3tPCHO3OxN9PfiEYLXO6WVR1zV3vUpZFhoFPW', 'manager', 'image/006cg0eegy1hqjvthp4u6j30u0140dos.jpg');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
