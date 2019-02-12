-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 12, 2019 at 10:20 AM
-- Server version: 5.6.37
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alenka`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL,
  `text` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `action_item`
--

CREATE TABLE IF NOT EXISTS `action_item` (
  `id` int(11) NOT NULL,
  `id_action` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `discount_precent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`) VALUES
(4, 'Конфеты'),
(5, 'Пряники');

-- --------------------------------------------------------

--
-- Table structure for table `merchandise`
--

CREATE TABLE IF NOT EXISTS `merchandise` (
  `id` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_measure` int(11) NOT NULL,
  `title` varchar(1500) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `merchandise`
--

INSERT INTO `merchandise` (`id`, `id_category`, `id_measure`, `title`, `description`, `price`, `image`) VALUES
(2, 4, 1, 'Соль с перцем', 'Смесь соли с перцем отсыревшая на складе', 5000, 'врппв ваппвапп авпва');

-- --------------------------------------------------------

--
-- Table structure for table `merchandise_measure`
--

CREATE TABLE IF NOT EXISTS `merchandise_measure` (
  `id` int(11) NOT NULL,
  `text` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `merchandise_measure`
--

INSERT INTO `merchandise_measure` (`id`, `text`) VALUES
(1, '100 г.');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(11) NOT NULL,
  `id_state` int(11) NOT NULL,
  `date` date NOT NULL,
  `client_name` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `id_state`, `date`, `client_name`, `address`, `phone`) VALUES
(4, 1, '2019-02-12', 'sfesfdsdf', 'sdfsdf', 'sdfsdfsdf'),
(5, 1, '2019-02-12', '111111111', '111111111111111111', '11111111111111111');

-- --------------------------------------------------------

--
-- Table structure for table `offer_item`
--

CREATE TABLE IF NOT EXISTS `offer_item` (
  `id` int(11) NOT NULL,
  `id_offer` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `count_in_offer` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer_item`
--

INSERT INTO `offer_item` (`id`, `id_offer`, `id_merchandise`, `count_in_offer`) VALUES
(5, 4, 2, 123),
(6, 5, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `offer_state`
--

CREATE TABLE IF NOT EXISTS `offer_state` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer_state`
--

INSERT INTO `offer_state` (`id`, `title`) VALUES
(1, 'Не обработано'),
(2, 'Выполняется'),
(3, 'Завершено');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `action_item`
--
ALTER TABLE `action_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_action` (`id_action`),
  ADD KEY `id_merchandise` (`id_merchandise`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_measure` (`id_measure`);

--
-- Indexes for table `merchandise_measure`
--
ALTER TABLE `merchandise_measure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_state` (`id_state`);

--
-- Indexes for table `offer_item`
--
ALTER TABLE `offer_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_merchandise` (`id_merchandise`),
  ADD KEY `id_offer` (`id_offer`);

--
-- Indexes for table `offer_state`
--
ALTER TABLE `offer_state`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `action_item`
--
ALTER TABLE `action_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `merchandise_measure`
--
ALTER TABLE `merchandise_measure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `offer_item`
--
ALTER TABLE `offer_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `offer_state`
--
ALTER TABLE `offer_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_item`
--
ALTER TABLE `action_item`
  ADD CONSTRAINT `action_item_ibfk_1` FOREIGN KEY (`id_action`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `action_item_ibfk_2` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`);

--
-- Constraints for table `merchandise`
--
ALTER TABLE `merchandise`
  ADD CONSTRAINT `merchandise_ibfk_2` FOREIGN KEY (`id_measure`) REFERENCES `merchandise_measure` (`id`),
  ADD CONSTRAINT `merchandise_ibfk_3` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`);

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`id_state`) REFERENCES `offer_state` (`id`);

--
-- Constraints for table `offer_item`
--
ALTER TABLE `offer_item`
  ADD CONSTRAINT `offer_item_ibfk_1` FOREIGN KEY (`id_offer`) REFERENCES `offer` (`id`),
  ADD CONSTRAINT `offer_item_ibfk_2` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
