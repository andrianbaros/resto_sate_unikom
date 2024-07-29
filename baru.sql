-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
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
	(1, 'kol', '10'),
	(2, 'cabe', '30'),
	(3, 'tahu', '5'),
	(23, 'garlic', '5'),
	(24, 'Nasi', '10'),
	(25, 'Ayam', '2'),
	(26, 'Cabe', '5');

-- Dumping structure for table resto_sate.recipes
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table resto_sate.recipes: ~2 rows (approximately)
INSERT INTO `recipes` (`id`, `name`, `image`) VALUES
	(1, 'Nasi Padang', 'images/nasipadang.png'),
	(2, 'Ayam Goreng', 'images/ayamgoreng.png');

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
	(1, 2, 2),
	(1, 3, 200),
	(2, 26, 1);

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
	('MA00002', 'bambang', '$2y$10$zzGUed1EfC/.8jVS3tPCHO3OxN9PfiEYLXO6WVR1zV3vUpZFhoFPW', 'manager', 'image/006cg0eegy1hqjvthp4u6j30u0140dos.jpg'),
	('MA00003', 'daapinn', '$2y$10$M2E/lmL5FVNaTpvByQunqOjCAo3O.KeMmMRdh2P.HHPw838d1Mtai', 'manager', 'image/9eefd3badd2079f0b2ede810f8d5d677.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
