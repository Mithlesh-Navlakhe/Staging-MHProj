-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3307
-- Время создания: Дек 09 2016 г., 13:30
-- Версия сервера: 5.6.26
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `time_tracking`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Project`
--

CREATE TABLE IF NOT EXISTS `Project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `lead_id` int(10) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `hourly_rate` decimal(5,2) NOT NULL,
  `notes` text NOT NULL,
  `updated_at` date NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `Project`
--

INSERT INTO `Project` (`id`, `client_id`, `lead_id`, `project_name`, `hourly_rate`, `notes`, `updated_at`, `created_at`) VALUES
(3, 2, 0, 'qweqwe', '10.00', 'qwewqeqwe', '2016-12-08', '2016-12-08'),
(4, 2, 0, 'tester', '10.00', 'dasddasdasdds', '2016-12-08', '2016-12-08'),
(5, 2, 0, 'testing', '10.00', 'sdsdsdsd sdasdsa asdsdasd', '2016-12-08', '2016-12-08');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Project`
--
ALTER TABLE `Project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `Clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
