-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 12, 2011 at 04:33 PM
-- Server version: 5.1.40
-- PHP Version: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `osp`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Table structure for table `meta`
--

CREATE TABLE IF NOT EXISTS `meta` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `meta`
--

INSERT INTO `meta` (`id`, `user_id`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, 1, 'Admin', 'istrator', 'ADMIN', '0');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '0 -> static; 1 -> dynamic',
  `uri` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lang` enum('ru','en') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ru',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  `disabled` datetime NOT NULL,
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `removed` (`removed`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `type`, `uri`, `lang`, `title`, `body`, `keywords`, `description`, `status`, `created`, `last_edited`, `disabled`, `removed`) VALUES
(1, 0, 'title', 'ru', 'title', '<h1>Ta Da!!!</h1>\r\n<p>Sample page</p>', 'sample page', 'sample page', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2011-08-06 19:08:20', 1),
(2, 1, 'page-1', 'ru', 'Страница 1', '<h1>Text of page 1</h1>\n<p>lsdasldfl</p>', '', '', 0, '2011-07-23 00:26:03', '2011-08-06 18:06:01', '2011-08-07 14:27:16', 1),
(3, 1, 'atelier', 'ru', 'Ателье', 'Ателье', '', '', 1, '2011-07-23 12:43:00', '2011-10-01 11:52:30', '0000-00-00 00:00:00', 0),
(4, 1, 'shop-online', 'ru', 'Онлайн магазин', 'Онлайн магазин', '', '', 1, '2011-08-06 18:43:07', '2011-10-01 11:52:27', '0000-00-00 00:00:00', 0),
(5, 1, 'collection', 'ru', 'Коллекция', 'this is very valuable page\n', '', '', 1, '2011-08-06 19:56:56', '2011-10-01 11:52:22', '0000-00-00 00:00:00', 0),
(6, 1, 'cool-urii', 'ru', 'Some Page', 'this is very valuable page', '', '', 0, '2011-08-07 00:18:55', '2011-08-07 00:18:55', '2011-08-07 14:27:11', 1),
(7, 1, 'our-philosophy', 'ru', 'Наша философия', 'Наша философия', '', '', 1, '2011-08-07 12:45:19', '2011-09-23 00:57:24', '0000-00-00 00:00:00', 0),
(8, 1, 'contacts', 'ru', 'Связаться с нами', 'Текст страницы контакты', '', '', 1, '2011-08-07 12:58:16', '2011-09-23 01:04:49', '0000-00-00 00:00:00', 0),
(9, 1, 'hjkl;', 'ru', 'bnm,.', 'hjkl;', 'hjkl;', '', 0, '2011-08-07 14:19:00', '2011-08-07 14:19:00', '2011-08-07 14:21:36', 1),
(10, 1, 'hjkl', 'ru', 'bnm,', '', 'hjkl', 'hjkl', 0, '2011-08-07 14:21:49', '2011-08-07 14:21:49', '2011-08-07 14:21:59', 1),
(11, 1, 'jkiuhjk', 'ru', 'nm,.', 'hjkmnbhjukm', 'huikmjuik', '', 0, '2011-08-07 14:22:18', '2011-08-07 14:22:18', '2011-08-07 14:22:47', 1),
(12, 1, 'hjnbhjm', 'ru', 'vbhjhj', 'hjnbhjm', '', '', 0, '2011-08-07 14:23:01', '2011-08-07 14:23:01', '2011-08-07 14:23:53', 1),
(13, 1, 'fffff', 'ru', 'Yet Another Backbone.js Tutorial – Part 1 – Backbone.js Philosophy | Kickass Labs', 'fksadhads;fkasfdkl', '', '', 0, '2011-08-07 14:24:10', '2011-08-07 14:24:10', '2011-08-07 14:27:05', 1),
(14, 1, 'fadsfsdafsdf', 'ru', 'fasdf', 'hjkl;hjkl;iujukik', '', '', 0, '2011-08-07 14:27:41', '2011-08-07 14:27:41', '2011-08-07 14:45:37', 1),
(15, 1, 'alsdfnlksancsa', 'ru', 'lckcllnlnsadlfn', 'nxddjdjdd', '', '', 0, '2011-08-07 14:42:48', '2011-08-07 14:42:48', '2011-08-07 14:44:15', 1),
(16, 1, 'alsdfksancsa', 'ru', 'lckclnlnsadlfn', 'nxddjdjdd', '', '', 0, '2011-08-07 14:44:25', '2011-08-07 14:44:25', '2011-08-07 14:45:09', 1),
(17, 1, 'alsdfksancsa', 'ru', 'lckclnlnsadlfn', 'nxddjdjdd', '', '', 0, '2011-08-07 14:46:39', '2011-08-07 14:46:39', '2011-08-07 14:47:49', 1),
(18, 1, 'alsdfksancsa', 'ru', 'lckclnlnsadlfn', 'nxddjdjdd', '', '', 0, '2011-08-07 14:47:55', '2011-08-07 14:47:55', '2011-08-07 14:49:41', 1),
(19, 1, 'alsdfksancsa', 'ru', 'lckclnlnsadlfn', 'nxddjdjdd', '', '', 0, '2011-08-07 14:49:47', '2011-08-07 14:49:47', '2011-08-07 17:10:53', 1),
(20, 1, 'press', 'ru', 'Пресса', 'Пресса', '', '', 1, '2011-09-23 01:07:44', '2011-09-23 01:08:07', '0000-00-00 00:00:00', 0),
(21, 1, 'press', 'ru', 'Пресса', 'Пресса', '', '', 0, '2011-09-23 01:07:48', '2011-09-23 01:07:48', '2011-09-23 01:07:57', 1),
(22, 1, 'legal-information', 'ru', 'Правовая информация', 'Правовая информация', '', '', 1, '2011-09-23 02:03:40', '2011-09-23 02:04:01', '0000-00-00 00:00:00', 0),
(23, 1, 'terms-and-conditions', 'ru', 'Пользовательское соглашение', 'Пользовательское соглашение', '', '', 1, '2011-09-23 02:04:48', '2011-09-23 02:05:05', '0000-00-00 00:00:00', 0),
(24, 1, 'help', 'ru', 'Помощь', 'Помощь', '', '', 1, '2011-09-23 02:05:55', '2011-09-24 02:06:14', '0000-00-00 00:00:00', 0),
(25, 1, 'index', 'ru', 'Главная', '<img src="/assets/images/content/image.jpg" title="" />\nГлавная', '', '', 1, '2011-09-30 02:13:43', '2011-10-01 16:31:11', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions_data`
--

CREATE TABLE IF NOT EXISTS `sessions_data` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sessions_data`
--

INSERT INTO `sessions_data` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('2e259dc7a5a82028f3d3a33f4a4fbf72', '127.0.0.1', 'Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100', 1318377215, 'a:5:{s:5:"email";s:15:"admin@admin.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL,
  `ip_address` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `remember_code`, `created_on`, `last_login`, `active`) VALUES
(1, 1, '127.0.0.1', 'administrator', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'admin@admin.com', '', NULL, '9d029802e28cd9c768e8e62277c0df49ec65c48c', 1268889823, 1318377430, 1);
