-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 23 2017 г., 12:16
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`question_number_id`, `theme`, `question`, `answer`, `points`, `id`) VALUES
(1, 'литература', 'Кто написал комедию "Горе от ума"?', 'грибоедов', 10, 7),
(2, 'литература', 'Кто написал комедию "Преступление и наказание"?', 'достоевский', 10, 8),
(3, 'литература', 'В каком году родился Марк Твен', '1835', 10, 9),
(4, 'литература', 'В каком году родился Ф.М. Достоевский', '1821', 10, 10),
(5, 'литература', 'В каком году написан роман "Мастер и Маргарита"?', '1928', 10, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `user_id` int(11) DEFAULT NULL,
  `theme` varchar(15) DEFAULT NULL,
  `best_points` int(3) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
  `user_id` int(11) DEFAULT NULL,
  `question_number_id` int(20) DEFAULT NULL,
  `user_points` int(3) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Дамп данных таблицы `usersAnswers`
--

INSERT INTO `usersAnswers` (`game_number`, `user_id`, `question_number_id`, `user_points`, `id`) VALUES
(2, 1, 1, 10, 36),
(2, 1, 2, 0, 37),
(2, 1, 4, 0, 38);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
