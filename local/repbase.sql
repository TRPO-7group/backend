-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 02 2017 г., 23:46
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
(2, 'Программирование на JAVA'),
(3, 'Вычислительные машины системы и сети'),
(4, 'Разработка мобильных приложений'),
(5, 'Веб-разработка');

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
(2, 'https://github.com/TRPO-7group/backend.git', 'Бэкенд', 0, NULL, NULL, 2),
(3, 'https://github.com/seclab-ucr/INTANG.git', NULL, 0, 2, 1, 3),
(4, 'https://github.com/SparkPost/heml.git', NULL, 1, 2, 2, 1),
(5, 'https://github.com/yishn/tikzcd-editor.git', NULL, 1, NULL, NULL, 3),
(6, 'https://github.com/thedaviddias/Front-End-Checklist.git', NULL, 1, NULL, NULL, 4),
(7, 'https://github.com/BalestraPatrick/WhatsNew.git', NULL, 1, NULL, NULL, 4),
(8, 'https://github.com/matterport/Mask_RCNN.git', NULL, 0, NULL, NULL, 3),
(9, 'https://github.com/ausi/slug-generator.git', NULL, 1, NULL, NULL, 1),
(10, 'https://github.com/d-plaindoux/masala-parser.git', NULL, 0, NULL, NULL, 5),
(11, 'https://github.com/neugram/ng.git', NULL, 0, NULL, NULL, 1),
(12, 'https://github.com/tensorflow/tensorflow.git', NULL, 1, NULL, NULL, 1),
(13, 'https://github.com/android/kotlin-guides.git', NULL, 1, NULL, NULL, 5),
(14, 'https://github.com/ignoreintuition/jSchema.git', NULL, 0, NULL, NULL, 2),
(15, 'https://github.com/JetBrains/kotlinconf-app.git', NULL, 1, NULL, NULL, 2);

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
(3, 2, 2),
(4, 3, 3),
(7, 4, 5),
(8, 5, 4),
(9, 6, 6),
(10, 7, 7),
(11, 8, 8),
(12, 9, 9),
(13, 10, 7),
(14, 11, 5),
(15, 12, 9),
(16, 13, 10),
(17, 14, 11),
(18, 15, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `rep_user_status`
--

CREATE TABLE `rep_user_status` (
  `id` int(11) NOT NULL,
  `rep_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `rep_user_status`
--

INSERT INTO `rep_user_status` (`id`, `rep_id`, `user_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2);

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
(2, 'big data'),
(3, 'TCP'),
(4, 'Создание диаграмм'),
(5, 'Новый язык'),
(6, 'Front-End Checklist'),
(7, 'Mobile app'),
(8, 'Обнаружение объектов'),
(9, 'Библиотека'),
(10, 'Сайт'),
(11, 'Моделирование данных');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_mail` varchar(100) DEFAULT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `name` varchar(300) NOT NULL,
  `group_num` varchar(100) DEFAULT NULL,
  `preview_img` varchar(300) DEFAULT NULL,
  `google_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`user_id`, `user_mail`, `user_type`, `name`, `group_num`, `preview_img`, `google_id`) VALUES
(1, 'aa@mail.ru', '0', 'Vasya', '13541/2', '', ''),
(2, 'bb@mail.ru', '0', 'Петя', '13541/1', '', '');

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
-- Индексы таблицы `rep_user_status`
--
ALTER TABLE `rep_user_status`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `rep`
--
ALTER TABLE `rep`
  MODIFY `rep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `reptegs`
--
ALTER TABLE `reptegs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `rep_user_status`
--
ALTER TABLE `rep_user_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `teg`
--
ALTER TABLE `teg`
  MODIFY `teg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
