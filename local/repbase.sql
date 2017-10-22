-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 22 2017 г., 18:42
-- Версия сервера: 5.7.19-0ubuntu0.16.04.1
-- Версия PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `repbase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `disc`
--

CREATE TABLE `disc` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Дисциплины';

--
-- Дамп данных таблицы `disc`
--

INSERT INTO `disc` (`id`, `name`) VALUES
(1, 'Структуры данных и алгоритмы'),
(2, 'Программирование на JAVA');

-- --------------------------------------------------------

--
-- Структура таблицы `rep`
--

CREATE TABLE `rep` (
  `rep_id` int(11) NOT NULL,
  `rep_url` varchar(255) NOT NULL,
  `rep_description` varchar(255) DEFAULT NULL,
  `is_ind` tinyint(1) NOT NULL,
  `pater_rep` int(11) DEFAULT NULL,
  `rep_owner` int(11) DEFAULT NULL,
  `rep_disc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rep`
--

INSERT INTO `rep` (`rep_id`, `rep_url`, `rep_description`, `is_ind`, `pater_rep`, `rep_owner`, `rep_disc`) VALUES
(1, 'https://github.com/TRPO-7group/markup.git', 'Версточка', 0, NULL, NULL, 1),
(2, 'https://github.com/TRPO-7group/backend.git', 'Бэкенд', 1, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `reptegs`
--

CREATE TABLE `reptegs` (
  `id` int(11) NOT NULL,
  `repid` int(11) DEFAULT NULL,
  `tegid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `reptegs`
--

INSERT INTO `reptegs` (`id`, `repid`, `tegid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `teg`
--

CREATE TABLE `teg` (
  `teg_id` int(11) NOT NULL,
  `teg_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `teg`
--

INSERT INTO `teg` (`teg_id`, `teg_name`) VALUES
(1, 'quartus'),
(2, 'big data');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_mail` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `user_type` varchar(20) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `disc`
--
ALTER TABLE `disc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `rep`
--
ALTER TABLE `rep`
  ADD PRIMARY KEY (`rep_id`),
  ADD KEY `rep_owner` (`rep_owner`),
  ADD KEY `pater_rep` (`pater_rep`),
  ADD KEY `rep_desc` (`rep_disc`);

--
-- Индексы таблицы `reptegs`
--
ALTER TABLE `reptegs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repid` (`repid`),
  ADD KEY `tegid` (`tegid`);

--
-- Индексы таблицы `teg`
--
ALTER TABLE `teg`
  ADD PRIMARY KEY (`teg_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `disc`
--
ALTER TABLE `disc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `rep`
--
ALTER TABLE `rep`
  MODIFY `rep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `reptegs`
--
ALTER TABLE `reptegs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `teg`
--
ALTER TABLE `teg`
  MODIFY `teg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rep`
--
ALTER TABLE `rep`
  ADD CONSTRAINT `rep_ibfk_1` FOREIGN KEY (`rep_owner`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `rep_ibfk_3` FOREIGN KEY (`pater_rep`) REFERENCES `rep` (`rep_id`);

--
-- Ограничения внешнего ключа таблицы `reptegs`
--
ALTER TABLE `reptegs`
  ADD CONSTRAINT `reptegs_fkg_key1` FOREIGN KEY (`tegid`) REFERENCES `teg` (`teg_id`),
  ADD CONSTRAINT `reptegs_fkg_key2` FOREIGN KEY (`repid`) REFERENCES `rep` (`rep_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
