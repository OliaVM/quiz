-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 10 2017 г., 17:46
-- Версия сервера: 5.5.55-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `quiz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `question_number_id` int(20) DEFAULT NULL,
  `theme` varchar(25) DEFAULT NULL,
  `question` varchar(150) DEFAULT NULL,
  `answer` varchar(20) DEFAULT NULL,
  `points` int(3) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `question_number_id` (`question_number_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`question_number_id`, `theme`, `question`, `answer`, `points`, `id`) VALUES
(1, 'литература', 'Кто написал комедию "Горе от ума"?', 'грибоедов', 10, 1),
(2, 'литература', 'Кто написал комедию "Преступление и наказание"?', 'достоевский', 10, 2),
(3, 'литература', 'В каком году родился Марк Твен', '1835', 10, 3),
(4, 'литература', 'В каком году родился Ф.М. Достоевский', '1821', 10, 4),
(5, 'литература', 'В каком году написан роман "Мастер и Маргарита"?', '1928', 10, 5),
(1, 'история', 'В каком году произошло крещение Руси?', '988', 10, 6),
(2, 'история', 'Как звали царя, который венчался на царство в 1547 г.?', 'иван', 10, 7),
(3, 'история', 'В каком году было положено начало династии Романовых?', '1613', 10, 8),
(4, 'история', 'Свод  законов России, принятый в 1550 г.', 'судебник', 10, 9),
(5, 'история', 'Как звали императора, при котором состоялось объединение Германской империи?', 'вильгельм', 10, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `user_id` int(11) DEFAULT NULL,
  `game_number` int(10) DEFAULT NULL,
  `theme` varchar(15) DEFAULT NULL,
  `points` int(3) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `results`
--

INSERT INTO `results` (`user_id`, `game_number`, `theme`, `points`, `id`) VALUES
(5, 1, '??????????', 30, 1),
(1, 6, '??????????', 20, 2),
(1, NULL, NULL, NULL, 3),
(1, 7, '??????????', 20, 4),
(1, 8, '??????????', 10, 5),
(1, 9, '??????????', 0, 6),
(1, 10, '??????????', 0, 7),
(1, 11, '??????????', 0, 8),
(1, 12, '??????????', 10, 9),
(1, 13, '??????????', 40, 10),
(1, 14, '???????', 0, 11),
(1, 15, '???????', 30, 12),
(1, 16, '???????', 20, 13),
(1, 17, '???????', 10, 14),
(1, 18, '???????', 10, 15);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(15) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `date_of_birth` varchar(15) DEFAULT NULL,
  `login` varchar(20) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `salt` varchar(30) DEFAULT NULL,
  `cookie` varchar(30) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `gender`, `date_of_birth`, `login`, `password`, `email`, `salt`, `cookie`, `id`) VALUES
(1, 'user1', 'surname1', 'woman', '2000-12-04', 'user1', 'd8009c825838f511eb025f27d14ff85a', 'ret@mail.ru', 'M', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `usersAnswers`
--

CREATE TABLE IF NOT EXISTS `usersAnswers` (
  `game_number` int(10) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `question_number_id` int(11) NOT NULL,
  `user_points` int(3) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `question_number_id` (`question_number_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=279 ;

--
-- Дамп данных таблицы `usersAnswers`
--

INSERT INTO `usersAnswers` (`game_number`, `user_id`, `question_number_id`, `user_points`, `id`) VALUES
(1, 1, 1, 10, 189),
(1, 1, 2, 0, 190),
(1, 1, 3, 0, 191),
(1, 1, 4, 0, 192),
(1, 1, 5, 0, 193),
(2, 1, 1, 10, 194),
(2, 1, 2, 10, 195),
(2, 1, 3, 0, 196),
(2, 1, 4, 0, 197),
(2, 1, 5, 0, 198),
(3, 1, 1, 10, 199),
(3, 1, 2, 10, 200),
(3, 1, 3, 10, 201),
(3, 1, 4, 0, 202),
(3, 1, 5, 0, 203),
(4, 1, 1, 10, 204),
(4, 1, 2, 10, 205),
(4, 1, 3, 10, 206),
(4, 1, 4, 0, 207),
(4, 1, 5, 0, 208),
(5, 1, 1, 10, 209),
(5, 1, 2, 10, 210),
(5, 1, 3, 0, 211),
(5, 1, 4, 10, 212),
(5, 1, 5, 0, 213),
(6, 1, 1, 10, 214),
(6, 1, 2, 10, 215),
(6, 1, 3, 0, 216),
(6, 1, 4, 0, 217),
(6, 1, 5, 0, 218),
(7, 1, 1, 10, 219),
(7, 1, 2, 0, 220),
(7, 1, 3, 0, 221),
(7, 1, 4, 10, 222),
(7, 1, 5, 0, 223),
(8, 1, 1, 0, 224),
(8, 1, 2, 0, 225),
(8, 1, 3, 10, 226),
(8, 1, 4, 0, 227),
(8, 1, 5, 0, 228),
(9, 1, 1, 0, 229),
(9, 1, 2, 0, 230),
(9, 1, 3, 0, 231),
(9, 1, 4, 0, 232),
(9, 1, 5, 0, 233),
(10, 1, 1, 0, 234),
(10, 1, 2, 0, 235),
(10, 1, 3, 0, 236),
(10, 1, 4, 0, 237),
(10, 1, 5, 0, 238),
(11, 1, 1, 0, 239),
(11, 1, 2, 0, 240),
(11, 1, 3, 0, 241),
(11, 1, 4, 0, 242),
(11, 1, 5, 0, 243),
(12, 1, 1, 10, 244),
(12, 1, 2, 0, 245),
(12, 1, 3, 0, 246),
(12, 1, 4, 0, 247),
(12, 1, 5, 0, 248),
(13, 1, 1, 10, 249),
(13, 1, 2, 10, 250),
(13, 1, 3, 10, 251),
(13, 1, 4, 10, 252),
(13, 1, 5, 0, 253),
(14, 1, 1, 0, 254),
(14, 1, 2, 0, 255),
(14, 1, 3, 0, 256),
(14, 1, 4, 0, 257),
(14, 1, 5, 0, 258),
(15, 1, 1, 10, 259),
(15, 1, 2, 10, 260),
(15, 1, 3, 10, 261),
(15, 1, 4, 0, 262),
(15, 1, 5, 0, 263),
(16, 1, 1, 10, 264),
(16, 1, 2, 0, 265),
(16, 1, 3, 0, 266),
(16, 1, 4, 10, 267),
(16, 1, 5, 0, 268),
(17, 1, 1, 0, 269),
(17, 1, 2, 10, 270),
(17, 1, 3, 0, 271),
(17, 1, 4, 0, 272),
(17, 1, 5, 0, 273),
(18, 1, 1, 0, 274),
(18, 1, 2, 0, 275),
(18, 1, 3, 10, 276),
(18, 1, 4, 0, 277),
(18, 1, 5, 0, 278);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
