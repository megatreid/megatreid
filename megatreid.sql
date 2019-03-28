-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 28 2019 г., 21:33
-- Версия сервера: 5.6.31
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `megatreid`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contractor`
--

CREATE TABLE IF NOT EXISTS `contractor` (
  `id_contractor` int(11) NOT NULL,
  `country_id` int(6) NOT NULL,
  `region_id` int(6) NOT NULL,
  `city_id` int(6) NOT NULL,
  `org_name` varchar(80) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dogovor` varchar(60) NOT NULL,
  `method_payment` int(1) NOT NULL,
  `card_number` varchar(18) NOT NULL,
  `anketa` varchar(5) NOT NULL,
  `ownership` varchar(6) NOT NULL,
  `system_no` varchar(7) NOT NULL,
  `contact_name` varchar(250) NOT NULL,
  `passport` varchar(400) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(100) NOT NULL,
  `comment` varchar(400) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `contractor`
--

INSERT INTO `contractor` (`id_contractor`, `country_id`, `region_id`, `city_id`, `org_name`, `status`, `dogovor`, `method_payment`, `card_number`, `anketa`, `ownership`, `system_no`, `contact_name`, `passport`, `mobile`, `phone`, `email`, `web`, `comment`) VALUES
(1, 3159, 3563, 3612, 'Компас', 1, '', 2, '', 'Есть', 'ИП', 'Без НДС', 'Иванов Алексей Иванович', '', '+7920-789-48-57', '', '', '', ''),
(2, 3159, 3563, 3612, 'ZTE', 1, '', 2, '', 'Есть', 'ООО', 'Без НДС', 'Михайлов Сергей Николаевич', '', '+7960-484-45-44', '', '', '', ''),
(3, 3159, 3563, 3572, 'Рога и копыта', 1, '', 2, '', 'Есть', 'ООО', 'Без НДС', 'Иванов А.А.', '', '+7952-588-78-98', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id_customer` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `jur_address` varchar(400) NOT NULL,
  `post_address` varchar(400) NOT NULL,
  `ogrn` varchar(15) NOT NULL,
  `inn` varchar(12) NOT NULL,
  `kpp` varchar(9) NOT NULL,
  `dogovor_number` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `bank_bik` varchar(9) NOT NULL,
  `korr_schet` varchar(20) NOT NULL,
  `rasch_schet` varchar(20) NOT NULL,
  `recipient` varchar(150) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_name` varchar(200) NOT NULL,
  `comment` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`id_contractor`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contractor`
--
ALTER TABLE `contractor`
  MODIFY `id_contractor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
