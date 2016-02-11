-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 11 2016 г., 11:30
-- Версия сервера: 5.5.38-log
-- Версия PHP: 5.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `online`
--

-- --------------------------------------------------------

--
-- Структура таблицы `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
  `id_s` int(11) NOT NULL AUTO_INCREMENT,
  `id_r` int(11) NOT NULL,
  `date_s` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text_s` varchar(255) NOT NULL,
  `author_s` int(11) NOT NULL,
  `status_s` int(11) NOT NULL,
  PRIMARY KEY (`id_r`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
