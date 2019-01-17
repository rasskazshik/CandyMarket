-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 17 2019 г., 12:56
-- Версия сервера: 5.6.37
-- Версия PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `alenka`
--

-- --------------------------------------------------------

--
-- Структура таблицы `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL,
  `text` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `action_item`
--

CREATE TABLE IF NOT EXISTS `action_item` (
  `id` int(11) NOT NULL,
  `id_action` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `discount_precent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `merchandise`
--

CREATE TABLE IF NOT EXISTS `merchandise` (
  `id` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_measure` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `merchandise_measure`
--

CREATE TABLE IF NOT EXISTS `merchandise_measure` (
  `id` int(11) NOT NULL,
  `text` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `offer`
--

CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(11) NOT NULL,
  `id_state` int(11) NOT NULL,
  `date` date NOT NULL,
  `client_name` varchar(500) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `offer_item`
--

CREATE TABLE IF NOT EXISTS `offer_item` (
  `id` int(11) NOT NULL,
  `id_offer` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `count_in_offer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `offer_state`
--

CREATE TABLE IF NOT EXISTS `offer_state` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `action_item`
--
ALTER TABLE `action_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_action` (`id_action`),
  ADD KEY `id_merchandise` (`id_merchandise`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_measure` (`id_measure`);

--
-- Индексы таблицы `merchandise_measure`
--
ALTER TABLE `merchandise_measure`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_state` (`id_state`);

--
-- Индексы таблицы `offer_item`
--
ALTER TABLE `offer_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_merchandise` (`id_merchandise`),
  ADD KEY `id_offer` (`id_offer`);

--
-- Индексы таблицы `offer_state`
--
ALTER TABLE `offer_state`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `action_item`
--
ALTER TABLE `action_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `merchandise_measure`
--
ALTER TABLE `merchandise_measure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `offer_item`
--
ALTER TABLE `offer_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `offer_state`
--
ALTER TABLE `offer_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `action_item`
--
ALTER TABLE `action_item`
  ADD CONSTRAINT `action_item_ibfk_1` FOREIGN KEY (`id_action`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `action_item_ibfk_2` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`);

--
-- Ограничения внешнего ключа таблицы `merchandise`
--
ALTER TABLE `merchandise`
  ADD CONSTRAINT `merchandise_ibfk_2` FOREIGN KEY (`id_measure`) REFERENCES `merchandise_measure` (`id`),
  ADD CONSTRAINT `merchandise_ibfk_3` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`);

--
-- Ограничения внешнего ключа таблицы `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`id_state`) REFERENCES `offer_state` (`id`);

--
-- Ограничения внешнего ключа таблицы `offer_item`
--
ALTER TABLE `offer_item`
  ADD CONSTRAINT `offer_item_ibfk_1` FOREIGN KEY (`id_offer`) REFERENCES `offer` (`id`),
  ADD CONSTRAINT `offer_item_ibfk_2` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
