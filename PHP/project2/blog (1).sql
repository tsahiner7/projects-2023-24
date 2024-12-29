-- Adminer 4.8.1 MySQL 8.0.39-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `blog` (`id`, `comment`, `date`, `title`) VALUES
(19,	'Please give me full point for this project! I did my best :) Thank you!',	'2024-11-07 15:53:49',	'Thanks Mr. Marks'),
(20,	'There have been some bugs I had been dealt with for more than 3 weeks. It is frustrating but I think I feel great after I solve those bugs :) ',	'2024-11-07 15:57:19',	'PHP is scary');

-- 2024-11-07 21:57:36
