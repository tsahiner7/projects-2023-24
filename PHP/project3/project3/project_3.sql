-- Adminer 4.8.1 MySQL 8.0.39-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `exercise_log`;
CREATE TABLE `exercise_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` datetime NOT NULL,
  `type` varchar(100) NOT NULL,
  `time_in_minutes` int NOT NULL,
  `heart_rate` int NOT NULL,
  `calories_burned` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `exercise_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_exercise` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `exercise_log` (`id`, `user_id`, `date`, `type`, `time_in_minutes`, `heart_rate`, `calories_burned`) VALUES
(2,	6,	'2024-11-20 00:00:00',	'Swimming',	40,	120,	395),
(4,	7,	'2024-11-21 00:00:00',	'Running',	45,	135,	370);

DROP TABLE IF EXISTS `user_exercise`;
CREATE TABLE `user_exercise` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(55) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `gender` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birthdate` datetime NOT NULL,
  `weight` int NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `user_exercise` (`user_id`, `first_name`, `last_name`, `username`, `gender`, `birthdate`, `weight`, `password_hash`) VALUES
(3,	'Homer',	'Simpson',	'Homer',	'Male',	'2002-11-26 00:00:00',	183,	'$2y$10$nK/Myyhz2DY138WLzqFKXOh3EAvB7.fnKA7VmxWazz17fnN/sOqZ6'),
(5,	'Natalie',	'student',	'nportman',	'Female',	'1981-07-09 00:00:00',	120,	'$2y$10$H.2LsBFN1ks5T0KMEMPFtu0s1T3bm/49XsnpvRI8m5B0uZaPXBqVi'),
(6,	'Tolga',	'Sahiner',	'student',	'Male',	'2002-11-26 00:00:00',	183,	'$2y$10$uvJYOi6145NO9s2HBXWvhOLd6z.dml/0RQ5dvT9zQKCx/LYwY0OtG'),
(7,	'Fred',	'Flintstone',	'fred',	'Female',	'1963-01-01 00:00:00',	175,	'$2y$10$oIwZ8ndTOpo8GAw6ruIOFulN9bkLUxKz.k8Rq4/jBtb5YWYky15CK');

-- 2024-11-21 21:34:09
