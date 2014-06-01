--
-- Table structure for table '_files_data'
--

DROP TABLE IF EXISTS _files_data;
CREATE TABLE `_files_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text,
  `size` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `md5` varchar(32) DEFAULT NULL,
  `content` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table '_temp_files'
--

DROP TABLE IF EXISTS _temp_files;
CREATE TABLE `_temp_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(254) DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table '_vfs'
--

DROP TABLE IF EXISTS _vfs;
CREATE TABLE `_vfs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `real_name` varchar(64) DEFAULT NULL,
  `real_path` text,
  `virtual_name` varchar(64) DEFAULT NULL,
  `virtual_path` text,
  PRIMARY KEY (`id`),
  KEY `virtual_name` (`virtual_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'banners'
--

DROP TABLE IF EXISTS banners;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(5) DEFAULT NULL,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `file` varchar(21) DEFAULT NULL,
  `text` text,
  `url` text,
  `link` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'constants'
--

DROP TABLE IF EXISTS constants;
CREATE TABLE `constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'directories'
--

DROP TABLE IF EXISTS directories;
CREATE TABLE `directories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'directories_data'
--

DROP TABLE IF EXISTS directories_data;
CREATE TABLE `directories_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir` int(11) NOT NULL DEFAULT '0',
  `content` varchar(254) DEFAULT NULL,
  `linked` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dc` (`dir`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'menus'
--

DROP TABLE IF EXISTS menus;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

insert into menus (`id`, `name`) values ('1', 'Юридические услуги'),('2', 'Бухгалтерские услуги');

--
-- Table structure for table 'menus_items'
--

DROP TABLE IF EXISTS menus_items;
CREATE TABLE `menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `url` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  `tag` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=cp1251;

insert into menus_items (`id`, `name`, `url`, `parent`, `sort`, `menu`, `visible`, `tag`) values ('1', 'Судебные дела', '', '0', '0', '1', '1', ''),('2', 'Автоюрист', '', '1', '1', '1', '1', ''),('3', 'Защита прав потребителей', '', '1', '2', '1', '1', ''),('4', 'Споры с пенсионным фондом', '', '1', '3', '1', '1', ''),('5', 'Трудовые споры', '', '1', '4', '1', '1', ''),('6', 'Семейные дела', '', '1', '5', '1', '1', ''),('7', 'Арбитраж', '', '1', '6', '1', '1', ''),('8', 'Регистрация фирм', '', '0', '1', '1', '1', ''),('10', 'Создание Юр.лиц и ИП', '', '8', '1', '1', '1', ''),('11', 'Учредительные документы и ЕГРЮЛ', '', '8', '2', '1', '1', ''),('12', 'Реорганизация', '', '8', '3', '1', '1', ''),('13', 'Ликвидация', '', '8', '4', '1', '1', ''),('14', 'Недвижимость', '', '0', '2', '1', '1', ''),('15', 'Разработка договоров', '', '14', '1', '1', '1', ''),('16', 'Полное сопровождение сделок', '', '14', '2', '1', '1', ''),('17', 'Главная', '', '0', '3', '1', '0', ''),('18', 'Юридические лица', '', '0', '1', '2', '1', ''),('19', 'ИП', '', '18', '1', '2', '1', ''),('20', 'ООО', '', '18', '2', '2', '1', ''),('21', 'ЗАО', '', '18', '3', '2', '1', ''),('22', 'ОАО', '', '18', '4', '2', '1', ''),('23', 'Физические лица', '', '0', '2', '2', '1', ''),('24', 'Для тех кто хочет начать свое дело', '', '23', '1', '2', '1', ''),('25', 'Прочие услуги', '', '0', '3', '2', '1', ''),('26', 'Кадровое дело', '', '25', '1', '2', '1', ''),('27', 'Услуги курьера', '', '25', '2', '2', '1', ''),('28', 'Обучение', '', '25', '3', '2', '1', ''),('29', 'Главная', '', '0', '4', '2', '0', ''),('30', 'О нас', 'about', '0', '5', '2', '0', ''),('31', 'О нас', 'about.html', '0', '4', '1', '0', ''),('32', 'Контакты', 'contacts.html', '0', '5', '1', '0', ''),('33', 'Контакты', 'contacts', '0', '6', '2', '0', '');

--
-- Table structure for table 'objects'
--

DROP TABLE IF EXISTS objects;
CREATE TABLE `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(254) DEFAULT NULL,
  `note` text,
  `image` varchar(254) DEFAULT NULL,
  `gallery` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vti` (`visible`,`type`,`id`),
  FULLTEXT KEY `ft_name` (`name`,`note`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'objects_details'
--

DROP TABLE IF EXISTS objects_details;
CREATE TABLE `objects_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NOT NULL DEFAULT '0',
  `typeId` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `nt` (`node`,`typeId`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'text_parts'
--

DROP TABLE IF EXISTS text_parts;
CREATE TABLE `text_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `node` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `content` longtext,
  `sort` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tnvs` (`type`,`node`,`visible`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'texts'
--

DROP TABLE IF EXISTS texts;
CREATE TABLE `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signature` varchar(10) DEFAULT NULL,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `keywords` text,
  `descr` text,
  `content` longtext,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

insert into texts (`id`, `signature`, `menu_item`, `date`, `title`, `keywords`, `descr`, `content`, `visible`) values ('1', 'ONS0', '30', '2014-06-01', 'О нас', '', '', '<h1>\n	Услуги бухгалтера</h1>\n<p>\n	Компания &laquo;Автоюрист&raquo; приветствует Вас на нашем сайте. Вот уже почти 9 лет наша компания осуществляет защиту прав автовладельцев на всей территории России и возвращает водительские удостоверения. Наши специалисты всегда готовы прийти на помощь в случае лишения прав. Мы бесплатно проведем анализ материалов дела, предо ставим профессиональную бесплатную консультацию в полном объеме, а также осуществим защиту Ваших прав в суде. Я лично, а также все сотрудники Компании &laquo;Ав тоюрист&raquo; желают Вам</p>\n', '1'),('2', 'KNTKT0', '33', '2014-06-01', 'Контакты', '', '', '<h1>\n	Контакты бухгалтера</h1>\n<p>\n	Тут идет текст контактов</p>\n', '1');

