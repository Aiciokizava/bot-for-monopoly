-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 29 2022 г., 13:34
-- Версия сервера: 8.0.28-0ubuntu0.20.04.3
-- Версия PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `monopoly`
--

-- --------------------------------------------------------

--
-- Структура таблицы `information_about_fields`
--

CREATE TABLE `information_about_fields` (
  `ID` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `franchise` varchar(20) DEFAULT NULL,
  `rent` json DEFAULT NULL,
  `field_cost` int DEFAULT NULL,
  `field_deposit` int DEFAULT NULL,
  `field_redemption` int DEFAULT NULL,
  `purchase_of_a_branch` int DEFAULT NULL,
  `coordinates_for_chips` json DEFAULT NULL,
  `coordinates_for_field_colors` json DEFAULT NULL,
  `coordinates_for_stars` json DEFAULT NULL,
  `coordinates_for_text` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `information_about_fields`
--

INSERT INTO `information_about_fields` (`ID`, `name`, `franchise`, `rent`, `field_cost`, `field_deposit`, `field_redemption`, `purchase_of_a_branch`, `coordinates_for_chips`, `coordinates_for_field_colors`, `coordinates_for_stars`, `coordinates_for_text`) VALUES
(0, 'start', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 70, \"y\": 70}, {\"x\": 48, \"y\": 70}, {\"x\": 70, \"y\": 51}, {\"x\": 48, \"y\": 51}]}', NULL, NULL, NULL),
(1, 'Chanel', 'perfumery', '{\"rent\": [20, 100, 300, 900, 1600, 2500]}', 600, 300, 360, 500, '{\"coordinates\": [{\"x\": 169, \"y\": 70}, {\"x\": 169, \"y\": 50}, {\"x\": 173, \"y\": 47}, {\"x\": 173, \"y\": 32}]}', '{\"x\": 153, \"y\": 29, \"dst_width\": 67, \"dst_height\": 123}', '{\"x\": 178, \"y\": 142}', '{\"x\": 153, \"y\": 22}'),
(2, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 238, \"y\": 70}, {\"x\": 238, \"y\": 50}, {\"x\": 242, \"y\": 47}, {\"x\": 242, \"y\": 32}]}', NULL, NULL, NULL),
(3, 'Hugo Boss', 'perfumery', '{\"rent\": [40, 200, 600, 1800, 3200, 4500]}', 600, 300, 360, 500, '{\"coordinates\": [{\"x\": 307, \"y\": 70}, {\"x\": 307, \"y\": 50}, {\"x\": 311, \"y\": 47}, {\"x\": 311, \"y\": 32}]}', '{\"x\": 292, \"y\": 29, \"dst_width\": 66, \"dst_height\": 122}', '{\"x\": 317, \"y\": 142}', '{\"x\": 292, \"y\": 22}'),
(4, 'tax_income', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 377, \"y\": 70}, {\"x\": 377, \"y\": 50}, {\"x\": 381, \"y\": 47}, {\"x\": 381, \"y\": 32}]}', NULL, NULL, NULL),
(5, 'Mercedes-Benz', 'cars', NULL, 2000, 1000, 1200, NULL, '{\"coordinates\": [{\"x\": 445, \"y\": 70}, {\"x\": 445, \"y\": 50}, {\"x\": 449, \"y\": 47}, {\"x\": 449, \"y\": 32}]}', '{\"x\": 430, \"y\": 29, \"dst_width\": 66, \"dst_height\": 123}', NULL, '{\"x\": 430, \"y\": 22}'),
(6, 'Adidas', 'clothes', '{\"rent\": [60, 300, 900, 2700, 4000, 5500]}', 1000, 500, 600, 500, '{\"coordinates\": [{\"x\": 514, \"y\": 70}, {\"x\": 514, \"y\": 50}, {\"x\": 518, \"y\": 47}, {\"x\": 518, \"y\": 32}]}', '{\"x\": 499, \"y\": 29, \"dst_width\": 66, \"dst_height\": 123}', '{\"x\": 523, \"y\": 142}', '{\"x\": 499, \"y\": 22}'),
(7, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 584, \"y\": 70}, {\"x\": 584, \"y\": 50}, {\"x\": 588, \"y\": 47}, {\"x\": 588, \"y\": 32}]}', NULL, NULL, NULL),
(8, 'Puma', 'clothes', '{\"rent\": [60, 300, 900, 2700, 4000, 5500]}', 1000, 500, 600, 500, '{\"coordinates\": [{\"x\": 653, \"y\": 70}, {\"x\": 653, \"y\": 50}, {\"x\": 657, \"y\": 47}, {\"x\": 657, \"y\": 32}]}', '{\"x\": 638, \"y\": 29, \"dst_width\": 66, \"dst_height\": 123}', '{\"x\": 663, \"y\": 142}', '{\"x\": 638, \"y\": 22}'),
(9, 'Lacoste', 'clothes', '{\"rent\": [80, 400, 1000, 3000, 4500, 6000]}', 1200, 600, 720, 500, '{\"coordinates\": [{\"x\": 722, \"y\": 70}, {\"x\": 722, \"y\": 50}, {\"x\": 726, \"y\": 47}, {\"x\": 726, \"y\": 32}]}', '{\"x\": 706, \"y\": 29, \"dst_width\": 67, \"dst_height\": 123}', '{\"x\": 731, \"y\": 142}', '{\"x\": 706, \"y\": 22}'),
(10, 'police_station', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 845, \"y\": 45}, {\"x\": 841, \"y\": 52}, {\"x\": 852, \"y\": 40}, {\"x\": 841, \"y\": 40}]}', NULL, NULL, NULL),
(11, 'prison', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 790, \"y\": 100}, {\"x\": 786, \"y\": 107}, {\"x\": 797, \"y\": 95}, {\"x\": 786, \"y\": 95}]}', NULL, NULL, NULL),
(12, 'VK', 'social network', '{\"rent\": [100, 500, 1500, 4500, 6250, 7500]}', 1400, 700, 840, 750, '{\"coordinates\": [{\"x\": 819, \"y\": 170}, {\"x\": 796, \"y\": 170}, {\"x\": 794, \"y\": 174}, {\"x\": 779, \"y\": 174}]}', '{\"x\": 775, \"y\": 154, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 767, \"y\": 179}', '{\"x\": 906, \"y\": 154}'),
(13, 'Rockstar Games', 'game studios', NULL, 1500, 750, 900, NULL, '{\"coordinates\": [{\"x\": 819, \"y\": 239}, {\"x\": 796, \"y\": 239}, {\"x\": 794, \"y\": 243}, {\"x\": 779, \"y\": 243}]}', '{\"x\": 775, \"y\": 224, \"dst_width\": 124, \"dst_height\": 66}', NULL, '{\"x\": 906, \"y\": 223}'),
(14, 'Facebook', 'social network', '{\"rent\": [100, 500, 1500, 4500, 6250, 7500]}', 1400, 700, 840, 750, '{\"coordinates\": [{\"x\": 819, \"y\": 308}, {\"x\": 796, \"y\": 308}, {\"x\": 794, \"y\": 312}, {\"x\": 779, \"y\": 312}]}', '{\"x\": 775, \"y\": 292, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 767, \"y\": 318}', '{\"x\": 906, \"y\": 292}'),
(15, 'Twitter', 'social network', '{\"rent\": [120, 600, 1800, 5000, 7000, 9000]}', 1600, 800, 960, 750, '{\"coordinates\": [{\"x\": 819, \"y\": 377}, {\"x\": 796, \"y\": 377}, {\"x\": 794, \"y\": 381}, {\"x\": 779, \"y\": 381}]}', '{\"x\": 775, \"y\": 362, \"dst_width\": 124, \"dst_height\": 66}', '{\"x\": 767, \"y\": 387}', '{\"x\": 906, \"y\": 362}'),
(16, 'Audi', 'cars', NULL, 2000, 1000, 1200, NULL, '{\"coordinates\": [{\"x\": 819, \"y\": 446}, {\"x\": 796, \"y\": 446}, {\"x\": 794, \"y\": 450}, {\"x\": 779, \"y\": 450}]}', '{\"x\": 775, \"y\": 431, \"dst_width\": 124, \"dst_height\": 66}', NULL, '{\"x\": 906, \"y\": 431}'),
(17, 'Coca-Cola', 'drinks', '{\"rent\": [140, 700, 2000, 5500, 7500, 9500]}', 1800, 900, 1080, 1000, '{\"coordinates\": [{\"x\": 819, \"y\": 516}, {\"x\": 796, \"y\": 516}, {\"x\": 794, \"y\": 520}, {\"x\": 779, \"y\": 520}]}', '{\"x\": 775, \"y\": 500, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 767, \"y\": 526}', '{\"x\": 906, \"y\": 500}'),
(18, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 819, \"y\": 584}, {\"x\": 796, \"y\": 584}, {\"x\": 794, \"y\": 588}, {\"x\": 779, \"y\": 588}]}', NULL, NULL, NULL),
(19, 'Pepsi', 'drinks', '{\"rent\": [140, 700, 2000, 5500, 7500, 9500]}', 1800, 900, 1080, 1000, '{\"coordinates\": [{\"x\": 819, \"y\": 654}, {\"x\": 796, \"y\": 654}, {\"x\": 794, \"y\": 658}, {\"x\": 779, \"y\": 658}]}', '{\"x\": 775, \"y\": 638, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 767, \"y\": 663}', '{\"x\": 906, \"y\": 638}'),
(20, 'Fanta', 'drinks', '{\"rent\": [160, 800, 2200, 6000, 8000, 10000]}', 2000, 1000, 1200, 1000, '{\"coordinates\": [{\"x\": 819, \"y\": 723}, {\"x\": 796, \"y\": 723}, {\"x\": 794, \"y\": 727}, {\"x\": 779, \"y\": 727}]}', '{\"x\": 775, \"y\": 707, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 767, \"y\": 733}', '{\"x\": 906, \"y\": 707}'),
(21, 'jackpot', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 820, \"y\": 820}, {\"x\": 797, \"y\": 820}, {\"x\": 820, \"y\": 795}, {\"x\": 797, \"y\": 795}]}', NULL, NULL, NULL),
(22, 'American Airlines', 'airlines', '{\"rent\": [180, 900, 2500, 7000, 8750, 10500]}', 2200, 1100, 1320, 1250, '{\"coordinates\": [{\"x\": 722, \"y\": 820}, {\"x\": 722, \"y\": 797}, {\"x\": 726, \"y\": 794}, {\"x\": 726, \"y\": 779}]}', '{\"x\": 706, \"y\": 776, \"dst_width\": 67, \"dst_height\": 124}', '{\"x\": 731, \"y\": 767}', '{\"x\": 706, \"y\": 919}'),
(23, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 653, \"y\": 820}, {\"x\": 653, \"y\": 797}, {\"x\": 657, \"y\": 794}, {\"x\": 657, \"y\": 779}]}', NULL, NULL, NULL),
(24, 'Lufthansa', 'airlines', '{\"rent\": [180, 900, 2500, 7000, 8750, 10500]}', 2200, 1100, 1320, 1250, '{\"coordinates\": [{\"x\": 584, \"y\": 820}, {\"x\": 584, \"y\": 797}, {\"x\": 588, \"y\": 794}, {\"x\": 588, \"y\": 779}]}', '{\"x\": 569, \"y\": 776, \"dst_width\": 65, \"dst_height\": 124}', '{\"x\": 594, \"y\": 767}', '{\"x\": 568, \"y\": 919}'),
(25, 'British Airways', 'airlines', '{\"rent\": [200, 1000, 3000, 7500, 9250, 11000]}', 2400, 1200, 1440, 1250, '{\"coordinates\": [{\"x\": 514, \"y\": 820}, {\"x\": 514, \"y\": 797}, {\"x\": 518, \"y\": 794}, {\"x\": 518, \"y\": 779}]}', '{\"x\": 499, \"y\": 776, \"dst_width\": 66, \"dst_height\": 124}', '{\"x\": 523, \"y\": 767}', '{\"x\": 499, \"y\": 919}'),
(26, 'Ford', 'cars', NULL, 2000, 1000, 1200, NULL, '{\"coordinates\": [{\"x\": 445, \"y\": 820}, {\"x\": 445, \"y\": 797}, {\"x\": 449, \"y\": 794}, {\"x\": 449, \"y\": 779}]}', '{\"x\": 430, \"y\": 776, \"dst_width\": 66, \"dst_height\": 124}', NULL, '{\"x\": 430, \"y\": 919}'),
(27, 'McDonald’s', 'restaurants', '{\"rent\": [220, 1100, 3300, 8000, 9750, 11500]}', 2600, 1300, 1560, 1500, '{\"coordinates\": [{\"x\": 377, \"y\": 820}, {\"x\": 377, \"y\": 797}, {\"x\": 381, \"y\": 794}, {\"x\": 381, \"y\": 779}]}', '{\"x\": 361, \"y\": 776, \"dst_width\": 66, \"dst_height\": 124}', '{\"x\": 386, \"y\": 767}', '{\"x\": 361, \"y\": 919}'),
(28, 'Burger King', 'restaurants', '{\"rent\": [220, 1100, 3300, 8000, 9750, 11500]}', 2600, 1300, 1560, 1500, '{\"coordinates\": [{\"x\": 307, \"y\": 820}, {\"x\": 307, \"y\": 797}, {\"x\": 311, \"y\": 794}, {\"x\": 311, \"y\": 779}]}', '{\"x\": 292, \"y\": 776, \"dst_width\": 66, \"dst_height\": 124}', '{\"x\": 317, \"y\": 767}', '{\"x\": 292, \"y\": 919}'),
(29, 'Rovio', 'game studios', NULL, 1500, 750, 900, NULL, '{\"coordinates\": [{\"x\": 238, \"y\": 820}, {\"x\": 238, \"y\": 797}, {\"x\": 242, \"y\": 794}, {\"x\": 242, \"y\": 779}]}', '{\"x\": 222, \"y\": 776, \"dst_width\": 67, \"dst_height\": 124}', NULL, '{\"x\": 222, \"y\": 919}'),
(30, 'KFC', 'restaurants', '{\"rent\": [240, 1200, 3600, 8500, 10250, 12000]}', 2800, 1400, 1680, 1500, '{\"coordinates\": [{\"x\": 169, \"y\": 820}, {\"x\": 169, \"y\": 797}, {\"x\": 173, \"y\": 794}, {\"x\": 173, \"y\": 779}]}', '{\"x\": 153, \"y\": 776, \"dst_width\": 67, \"dst_height\": 124}', '{\"x\": 178, \"y\": 767}', '{\"x\": 153, \"y\": 919}'),
(31, 'police', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 71, \"y\": 820}, {\"x\": 49, \"y\": 820}, {\"x\": 71, \"y\": 793}, {\"x\": 49, \"y\": 797}]}', NULL, NULL, NULL),
(32, 'Holiday Inn', 'hotels', '{\"rent\": [260, 1300, 3900, 9000, 11000, 12750]}', 3000, 1500, 1800, 1750, '{\"coordinates\": [{\"x\": 71, \"y\": 723}, {\"x\": 49, \"y\": 723}, {\"x\": 46, \"y\": 727}, {\"x\": 31, \"y\": 727}]}', '{\"x\": 27, \"y\": 707, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 143, \"y\": 733}', '{\"x\": 21, \"y\": 775}'),
(33, 'Radisson Blu', 'hotels', '{\"rent\": [260, 1300, 3900, 9000, 11000, 12750]}', 3000, 1500, 1800, 1750, '{\"coordinates\": [{\"x\": 71, \"y\": 654}, {\"x\": 49, \"y\": 654}, {\"x\": 46, \"y\": 658}, {\"x\": 31, \"y\": 658}]}', '{\"x\": 27, \"y\": 638, \"dst_width\": 124, \"dst_height\": 66}', '{\"x\": 143, \"y\": 663}', '{\"x\": 21, \"y\": 706}'),
(34, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 71, \"y\": 584}, {\"x\": 49, \"y\": 584}, {\"x\": 46, \"y\": 588}, {\"x\": 31, \"y\": 588}]}', NULL, NULL, NULL),
(35, 'Novotel', 'hotels', '{\"rent\": [280, 1500, 4500, 10000, 12000, 14000]}', 3200, 1600, 1920, 1750, '{\"coordinates\": [{\"x\": 71, \"y\": 516}, {\"x\": 49, \"y\": 516}, {\"x\": 46, \"y\": 520}, {\"x\": 31, \"y\": 520}]}', '{\"x\": 27, \"y\": 500, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 143, \"y\": 526}', '{\"x\": 21, \"y\": 568}'),
(36, 'Land Rover', 'cars', NULL, 2000, 1000, 1200, NULL, '{\"coordinates\": [{\"x\": 71, \"y\": 446}, {\"x\": 49, \"y\": 446}, {\"x\": 46, \"y\": 450}, {\"x\": 31, \"y\": 450}]}', '{\"x\": 27, \"y\": 431, \"dst_width\": 124, \"dst_height\": 66}', NULL, '{\"x\": 21, \"y\": 498}'),
(37, 'tax_luxury', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 71, \"y\": 377}, {\"x\": 49, \"y\": 377}, {\"x\": 46, \"y\": 381}, {\"x\": 31, \"y\": 381}]}', NULL, NULL, NULL),
(38, 'Apple', 'electronics', '{\"rent\": [350, 1750, 5000, 11000, 13000, 15000]}', 3500, 1750, 2100, 2000, '{\"coordinates\": [{\"x\": 71, \"y\": 308}, {\"x\": 49, \"y\": 308}, {\"x\": 46, \"y\": 312}, {\"x\": 31, \"y\": 312}]}', '{\"x\": 27, \"y\": 292, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 143, \"y\": 318}', '{\"x\": 21, \"y\": 360}'),
(39, 'chance', NULL, NULL, NULL, NULL, NULL, NULL, '{\"coordinates\": [{\"x\": 71, \"y\": 239}, {\"x\": 49, \"y\": 239}, {\"x\": 46, \"y\": 243}, {\"x\": 31, \"y\": 243}]}', NULL, NULL, NULL),
(40, 'Nokia', 'electronics', '{\"rent\": [500, 2000, 6000, 14000, 17000, 20000]}', 4000, 2000, 2400, 2000, '{\"coordinates\": [{\"x\": 71, \"y\": 170}, {\"x\": 49, \"y\": 170}, {\"x\": 46, \"y\": 174}, {\"x\": 31, \"y\": 174}]}', '{\"x\": 27, \"y\": 155, \"dst_width\": 124, \"dst_height\": 67}', '{\"x\": 143, \"y\": 179}', '{\"x\": 21, \"y\": 222}');

-- --------------------------------------------------------

--
-- Структура таблицы `sample_game_parameters`
--

CREATE TABLE `sample_game_parameters` (
  `ID` int NOT NULL,
  `parameters` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `value` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sample_game_parameters`
--

INSERT INTO `sample_game_parameters` (`ID`, `parameters`, `value`) VALUES
(1, 'type_game', 2),
(2, 'current_move', 1),
(3, 'waiting_for_a_move', 0),
(4, 'building_a_branch', 1),
(5, 'field_participating_in_auction', 0),
(6, 'cost_of_the_field_at_auction', 0),
(7, 'current_move_auction', 0),
(8, 'fine', 0),
(9, 'duplicate', 0),
(10, 'load_game', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sample_map`
--

CREATE TABLE `sample_map` (
  `ID` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `recruiter` int NOT NULL,
  `level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sample_map`
--

INSERT INTO `sample_map` (`ID`, `name`, `recruiter`, `level`) VALUES
(1, 'start', 0, 0),
(2, 'Chanel', 0, 0),
(3, 'chance', 0, 0),
(4, 'Hugo Boss', 0, 0),
(5, 'tax_income', 0, 0),
(6, 'Mercedes-Benz', 0, 0),
(7, 'Adidas', 0, 0),
(8, 'chance', 0, 0),
(9, 'Puma', 0, 0),
(10, 'Lacoste', 0, 0),
(11, 'police_station', 0, 0),
(12, 'prison', 0, 0),
(13, 'VK', 0, 0),
(14, 'Rockstar Games', 0, 0),
(15, 'Facebook', 0, 0),
(16, 'Twitter', 0, 0),
(17, 'Audi', 0, 0),
(18, 'Coca-Cola', 0, 0),
(19, 'chance', 0, 0),
(20, 'Pepsi', 0, 0),
(21, 'Fanta', 0, 0),
(22, 'jackpot', 0, 0),
(23, 'American Airlines', 0, 0),
(24, 'chance', 0, 0),
(25, 'Lufthansa', 0, 0),
(26, 'British Airways', 0, 0),
(27, 'Ford', 0, 0),
(28, 'McDonald’s', 0, 0),
(29, 'Burger King', 0, 0),
(30, 'Rovio', 0, 0),
(31, 'KFC', 0, 0),
(32, 'police', 0, 0),
(33, 'Holiday Inn', 0, 0),
(34, 'Radisson Blu', 0, 0),
(35, 'chance', 0, 0),
(36, 'Novotel', 0, 0),
(37, 'Land Rover', 0, 0),
(38, 'tax_luxury', 0, 0),
(39, 'Apple', 0, 0),
(40, 'chance', 0, 0),
(41, 'Nokia', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `vk_donut`
--

CREATE TABLE `vk_donut` (
  `peer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `vk_donut`
--

INSERT INTO `vk_donut` (`peer_id`) VALUES
(347771519);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `information_about_fields`
--
ALTER TABLE `information_about_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `sample_game_parameters`
--
ALTER TABLE `sample_game_parameters`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `sample_map`
--
ALTER TABLE `sample_map`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `vk_donut`
--
ALTER TABLE `vk_donut`
  ADD PRIMARY KEY (`peer_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `information_about_fields`
--
ALTER TABLE `information_about_fields`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `sample_game_parameters`
--
ALTER TABLE `sample_game_parameters`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `sample_map`
--
ALTER TABLE `sample_map`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `vk_donut`
--
ALTER TABLE `vk_donut`
  MODIFY `peer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2000000049;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
